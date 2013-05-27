<?php
/**
 * Interface that represents an object that is used by calling code to create the
 * appropriate typehintable objects storing the enum's values.
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace Setty;

/**
 * The primary purpose of this interface is to require the appropriate magic method
 * functionality for \Setty\Enum implementations.
 */
interface Enum {

    /**
     * Should return an instance of \Setty\EnumValue that corresponds to the
     * blueprint for the \Setty\Enum.
     *
     * The $name of the static method called should correspond to a constant name
     * set in the blueprint of the Enum. No $arguments should be supported; this
     * parameter is present as the __callStatic() magic method is expecting its
     * presence. Implementations should not support using this parameter and passing
     * arguments to the magic static method is undefined behavior.
     *
     * @param string $name
     * @param array $arguments
     * @return \Setty\EnumValue
     *
     * @throw \Setty\Exception\EnumValueNotFoundException
     */
    public static function __callStatic($name, $arguments);

}
