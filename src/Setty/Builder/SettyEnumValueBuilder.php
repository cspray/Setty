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
     */
    public function buildEnumValue($enumType, array $constants, $setConstant) {
        $enumClass = "\\Setty\\Enum\\UserEnum\\$enumType";
        if (!\class_exists($enumClass)) {
            eval($this->getEnumValueCode($enumType));
        }

        return new $enumClass($setConstant);
    }

    /**
     * Will return PHP code that is suitable for evaluating into a \Setty\Enum\UserEnum\$enumType
     * object.
     *
     * @param string $enumType
     * @return string
     */
    protected function getEnumValueCode($enumType) {
        return <<<PHP_CODE
namespace Setty\\Enum\\UserEnum;

use \\Setty;
use \\Setty\\Enum;

class {$enumType} extends Enum\\AbstractEnumValue {}
PHP_CODE;
    }
}
