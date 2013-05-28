<?php
/**
 * An interface that represents a value from a call to \Setty\Enum::CONSTANT_NAME().
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace Setty;

interface EnumValue {

    /**
     * Should return an enum-unique value that indicates which of the \Setty\Enum
     * values that this object instance represents.
     *
     * @return string
     */
    public function __toString();

}
