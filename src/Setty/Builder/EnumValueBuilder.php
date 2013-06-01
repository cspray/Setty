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
     * Should generate a \Setty\Enum\UserEnum\$enumType object that when cast to string
     * will be seen as $setConstant.
     *
     * @param string $enumType
     * @param string $setConstant
     * @return mixed
     *
     * @throws \Setty\Exception\EnumValueNotFoundException
     */
    public function buildEnumValue($enumType, $setConstant);

}
