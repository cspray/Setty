<?php
/**
 * An implementation of \Setty\Builder\Builder that will dynamically create appropriate
 * \Setty\Enum types.
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace Setty\Builder;

use \Setty\Exception;

class SettyEnumBuilder implements EnumBuilder {

    /**
     * An array of strings that represent the enum types this builder has created.
     *
     * We keep track of this to ensure we do not dynamically create a class that
     * might already exist.
     *
     * @property array
     */
    protected $storedEnumTypes = [];

    /**
     * Used to create the appropriate \Setty\EnumValue objects representing the
     * constants for the created \Setty\Enum
     *
     * @property EnumValueBuilder
     */
    protected $EnumValueBuilder;

    /**
     * @param \Setty\Builder\EnumValueBuilder $EnumValueBuilder
     */
    public function __construct(EnumValueBuilder $EnumValueBuilder) {
        $this->EnumValueBuilder = $EnumValueBuilder;
    }

    /**
     * Should store information appropriate for dynamically creating a \Setty\Enum
     * object for use when retrieving the Enum object in later calls.
     *
     * For simplicity reasons this method should only support setting one
     * enum at a time. If the blueprint is invalid an exception should be thrown.
     *
     * Please see interface docs for more information on the appropriate format
     * expected in $settyEnumBlueprint.
     *
     * @param array $settyEnumBlueprint
     * @return void
     *
     * @throws \Setty\Exception\EnumBlueprintInvalidException
     */
    public function storeFromArray(array $settyEnumBlueprint) {
        if (!\array_key_exists('name', $settyEnumBlueprint) || !\array_key_exists('constant', $settyEnumBlueprint)) {
            $message = 'The array passed to %s must have \'name\' and \'constant\' keys set';
            throw new Exception\EnumBlueprintInvalidException(\sprintf($message, __METHOD__));
        }

        $name = $settyEnumBlueprint['name'];
        if (!\is_string($name) || empty($name)) {
            $message = 'The value stored in the \'name\' key in the array passed to %s must be a non-empty string type';
            throw new Exception\EnumBlueprintInvalidException(\sprintf($message, __METHOD__));
        }

        $invalidNamePattern = '#[^A-Za-z_]#';
        if (\preg_match($invalidNamePattern, $name)) {
            $message = 'The value stored in the \'name\' key in the array passed to %s may only have letter and underscore characters';
            throw new Exception\EnumBlueprintInvalidException(\sprintf($message, __METHOD__));
        }

        if (\array_key_exists($name, $this->storedEnumTypes)) {
            $message = 'The enum type passed, %s, has already been stored';
            throw new Exception\EnumBlueprintInvalidException(\sprintf($message, $name));
        }

        $constant = $settyEnumBlueprint['constant'];
        if (!\is_array($constant) || empty($constant)) {
            $message = 'The value stored in the \'constant\' key in the array passed to %s must be a non-empty array type';
            throw new Exception\EnumBlueprintInvalidException(\sprintf($message, __METHOD__));
        }

        $validConstants = [];
        $invalidConstantNamePattern = '#[^A-Za-z0-9_]#';
        foreach ($constant as $constName => $constValue) {
            if (!\is_string($constName) || empty($constName)) {
                $message = 'The keys stored in the \'constant\' array in the array passed to %s must be a non-empty string type';
                throw new Exception\EnumBlueprintInvalidException(\sprintf($message, __METHOD__));
            }

            if (\preg_match($invalidConstantNamePattern, $constName)) {
                $message = 'The keys stored in the \'constant\' array in the array passed to %s may only have letters, numbers and underscore characters';
                throw new Exception\EnumBlueprintInvalidException(\sprintf($message, __METHOD__));
            }

            if (!\is_string($constValue) || empty($constValue)) {
                $message = 'The values stored in the \'constant\' array passed to %s must be non-empty string values';
                throw new Exception\EnumBlueprintInvalidException(\sprintf($message, __METHOD__));
            }

            if (\in_array($constValue, $validConstants)) {
                $message = 'The enum, %s, has a duplicate constant value: %s';
                throw new Exception\EnumBlueprintInvalidException(\sprintf($message, $name, $constValue));
            }

            $validConstants[$constName] = $constValue;
        }

        $this->storedEnumTypes[$name] = $validConstants;
    }

    /**
     * Return a \Setty\Enum suitable for the given $enumName if it
     * has been stored.
     *
     * If the $enumName has not been stored in the builder throw an exception.
     *
     * @param string $enumType
     * @return \Setty\Enum
     *
     * @throws \Setty\Exception\EnumNotFoundException
     */
    public function buildStored($enumType) {
        if (!\array_key_exists($enumType, $this->storedEnumTypes)) {
            $message = 'The enum type passed to %s, %s, could not be found';
            throw new Exception\EnumNotFoundException(\sprintf($message, __METHOD__, $enumType));
        }

        $enumClass = "\\Setty\\Enum\\{$enumType}Enum";
        if (!\class_exists($enumClass)) {
            eval($this->getEnumCode($enumType, $this->storedEnumTypes[$enumType]));
        }

        $constants = $this->storedEnumTypes[$enumType];
        $EnumValues = [];
        foreach($constants as $constant => $value) {
            $EnumValues[$constant] = $this->EnumValueBuilder->buildEnumValue($enumType, $value);
        }

        return new $enumClass($EnumValues);
    }

    /**
     * @param string $enumType
     * @param array $constants
     * @return string
     */
    protected function getEnumCode($enumType, array $constants) {
        $constCode = '';
        $constForm = "const %s = '%s';\n";
        $storedValues = [];
        foreach ($constants as $constName => $constValue) {
            if (\in_array($constValue, $storedValues)) {
                $message = 'The enum, %s, has a duplicate constant value: %s';
                throw new Exception\EnumBlueprintInvalidException(\sprintf($message, $enumType, $constValue));
            }
            $storedValues[] = $constValue;
            $constCode .= \sprintf($constForm, $constName, $constValue);
        }

        return <<<PHP_CODE
namespace Setty\\Enum;

use \\Setty;

final class {$enumType}Enum implements Setty\\Enum {

    {$constCode}

    protected static \$values = [];

    final public function __construct(array \$values) {
        static::\$values = \$values;
    }

    public static function __callStatic(\$name, \$arguments) {
        if (empty(static::\$values)) {
            \$message = 'The appropriate values have not been set for this enum';
            throw new \BadMethodCallException(\$message);
        }

        return static::\$values[\$name];
    }

    public static function NAME() {
        // TODO: Implement NAME() method.
    }

    public static function CONSTANTS() {
        return static::\$values;
    }

}
PHP_CODE;
    }

}
