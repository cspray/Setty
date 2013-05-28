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
    protected $createdEnumTypes = [];

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

        if (\array_key_exists($name, $this->createdEnumTypes)) {
            $message = 'The enum type passed, %s, has already been created';
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

            $validConstants[$constName] = $constValue;
        }

        $this->createdEnumTypes[$name] = $validConstants;
    }

    /**
     * Return a \Setty\Enum suitable for the given $enumName if it
     * has been stored.
     *
     * If the $enumName has not been stored in the builder throw an exception.
     *
     * @param string $enumName
     * @return \Setty\Enum
     *
     * @throw \Setty\Exception\EnumNotFoundException
     */
    public function buildStored($enumName) {
        // TODO: Implement buildStored() method.
    }

    /**
     * Set the $enumName to be used in successive calls to constant(), store()
     * and build().
     *
     * @param string $enumName
     * @return \Setty\Builder\Builder
     */
    public function enum($enumName) {
        // TODO: Implement enum() method.
    }

    /**
     * Set a $constName and $constValue that should be applied to the $enumName
     * set with a call to enum().
     *
     * If enum() has not been called or there is a null value for the name of
     * the enum to set the constant to an exception should be thrown.
     *
     * @param string $constName
     * @param string $constValue
     * @return \Setty\Builder\Builder
     *
     * @throws \Setty\Exception\EnumBlueprintInvalidException
     */
    public function constant($constName, $constValue) {
        // TODO: Implement constant() method.
    }

    /**
     * Should store the configured enum blueprint after calls to enum() and constant().
     *
     * See the interface docs for more information on the appropriate format expected
     * to be created and stored by this method.
     *
     * @return boolean
     *
     * @throw \Setty\Exception\EnumBlueprintInvalidException
     */
    public function store() {
        // TODO: Implement store() method.
    }

    /**
     * Should create a \Setty\Enum object and return it based on the
     * blueprint configuration set in calls to enum() and constant().
     *
     * If an appropriate blueprint has not been created for the enum based on these
     * calls then an exception should be thrown.
     *
     * @return \Setty\Enum
     *
     * @throw \Setty\Exception\EnumBlueprintInvalidException
     */
    public function build() {
        // TODO: Implement build() method.
    }

}
