<?php

/**
 * An interface representing an enum value returned from a call to \Setty\Enum::CONST_NAME()
 *
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace Setty\Builder;

interface EnumValueBuilder {

    /**
     * Should generate a \Setty\Enum\UserEnum\$enumType object that has available
     * as class constants the key/values set in $constants; when the returned type
     * is evaluated as a string it will be seen as $setConstant.
     *
     * If the $setConstant does not exist as a *key* in $constants then an exception
     * should be thrown.
     *
     * @param  $enumType
     * @param $constName
     * @param $constValue
     * @return mixed
     *
     * @throws \Setty\Exception\EnumValueNotFoundException
     */
    public function buildEnumValue($enumType, array $constants, $setConstant);

}
