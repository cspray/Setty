<?php
/**
 * An implementation of \Setty\Builder\EnumValueBuilder that will dynamically
 * generate new \Setty\EnumValue types under the \Setty\Enum\UserEnum namespace.
 *
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace Setty\Builder;

use \Setty;
use \Setty\Exception;

/**
 * Generates dynamically created classes, that is classes created at runtime, that
 * implement \Setty\EnumValue.
 *
 * Types returned from this implementation are the types that should be hinted in
 * your method signatures.
 *
 */
class SettyEnumValueBuilder implements EnumValueBuilder {

    /**
     * Stores a multidimensional array holding implemented Enum\AbstractEnumValue
     * implementations.
     *
     * The following format should be followed for this property:
     *
     * [
     *      'EnumType' => [
     *          'CONST_NAME' => <Setty\EnumValue>,
     *          'OTHER_CONST' => <Setty\EnumValue>
     *      ]
     *
     * ]
     *
     * This property is here to ensure that if there are multiple calls to create
     * EnumType::CONST_NAME that we return the same object without having to create
     * it over again. We also do this to ensure that if calling code chooses to
     * compare objects we can do so correctly.
     *
     * @property array
     */
    protected $createdEnumValues = [];

    /**
     * Should generate a \Setty\Enum\UserEnum\$enumType object that has available
     * as class constants the key/values set in $constants; when the returned type
     * is evaluated as a string it will be seen as $setConstant.
     *
     * If the $setConstant does not exist as a *key* in $constants then an exception
     * should be thrown.
     *
     * @param string $enumType
     * @param array $constants
     * @param string $setConstant
     * @return mixed
     *
     * @throws \Setty\Exception\EnumValueNotFoundException
     * @throws \Setty\Exception\EnumBlueprintInvalidException
     */
    public function buildEnumValue($enumType, $setConstant) {
        $enumClass = "\\Setty\\Enum\\$enumType";
        if (!$this->isEnumStored($enumType, $setConstant)) {
            if (!\class_exists($enumClass)) {
                eval($this->getEnumValueCode($enumType));
            }
            $this->storeEnumValue(new $enumClass($setConstant), $enumType, $setConstant);
        }

        return $this->fetchStoredEnum($enumType, $setConstant);
    }

    /**
     * Determines if a Setty\EnumValue has already been stored for the passed
     * $enumType and $constName.
     *
     * @param string $enumType
     * @param string $constName
     * @return bool
     */
    protected function isEnumStored($enumType, $constName) {
        if (isset($this->createdEnumValues[$enumType]) && \is_array($this->createdEnumValues[$enumType])) {
            if (isset($this->createdEnumValues[$enumType][$constName])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Will store a created Setty\EnumValue in the appropriate format to work
     * with the other enum storing and retrieval internal methods.
     *
     * @param \Setty\EnumValue $EnumValue
     * @param string $enumType
     * @param string $constName
     */
    protected function storeEnumValue(Setty\EnumValue $EnumValue, $enumType, $constName) {
        if (!isset($this->createdEnumValues[$enumType])) {
            $this->createdEnumValues[$enumType] = [];
        }

        $this->createdEnumValues[$enumType][$constName] = $EnumValue;
    }

    /**
     * Returns the \Setty\EnumValue associated to the $enumType and $constName or
     * false if no object exists.
     *
     * @param string $enumType
     * @param string $constName
     * @return mixed
     */
    protected function fetchStoredEnum($enumType, $constName) {
        if (!$this->isEnumStored($enumType, $constName)) {
            return false;
        }

        return $this->createdEnumValues[$enumType][$constName];
    }

    /**
     * Will return PHP code that is suitable for evaluating into a \Setty\Enum\UserEnum\$enumType
     * object.
     *
     * @param string $enumType
     * @return string
     *
     * @throws \Setty\Exception\EnumBlueprintInvalidException
     */
    protected function getEnumValueCode($enumType) {


        return <<<PHP_CODE
namespace Setty\\Enum;

use \\Setty;

final class {$enumType} extends Setty\AbstractEnumValue {}
PHP_CODE;
    }
}
