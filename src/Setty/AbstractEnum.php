<?php

/**
 * An implementation of \Setty\Enum that dynamically created Enum classes will
 * inherit.
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace Setty;

abstract class AbstractEnum implements Enum {

    /**
     * Holds the \Setty\EnumValue objects associated to this enum.
     *
     * @property \Setty\EnumValue[]
     */
    protected static $values;

    /**
     * Each element in $values should be a \Setty\EnumValue implementation.
     *
     * @param array $values
     */
    public function __construct(array $values) {
        self::$values = $values;
    }

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
     * We are throwing a \Setty\Exception\EnumValueNotFoundException for obvious
     * reasons; if you call a $name that isn't valid you get an exception in return.
     * However, we also at times call a \BadMethodCallException. If you attempt to
     * call a static method before the AbstractEnum __construct is called then
     * it's a no-go.
     *
     * This behavior was implemented because we don't want to needlessly create
     * \Setty\EnumValue objects. In our current implementation of \Setty\Builder\SettyEnumValueBuilder
     * we are actually returning the objects created. Unfortunately to embed the
     * creation of these objects into the code template means we're creating an
     * extra set of objects that could be n in length. This is not acceptable.
     *
     * In later versions of this library we are going to take a look at adding a
     * command chain to the building of \Setty\Enum objects that will prevent
     * the use of eval, creating objects and a slew of other functionality will
     * open up. We may revisit this issue at that time.
     *
     * @param string $name
     * @param array $arguments
     * @return \Setty\EnumValue
     *
     * @throws \Setty\Exception\EnumValueNotFoundException
     * @throws \BadMethodCallException
     *
     * @todo
     * Consider how we need to necessitate the __construct() being called with
     * appropriate values for this to work. Being static it should really be able
     * to work without class instantiation. After we've refactored the Builder
     * module to be more flexible we should really reexamine this.
     */
    public static function __callStatic($name, $arguments) {
        if (empty(self::$values)) {
            $message = 'The appropriate values have not been set for this enum';
            throw new \BadMethodCallException($message);
        }

        return self::$values[$name];
    }

    /**
     * Should return the NON-NAMESPACED enum name that this object represents.
     *
     * @return string
     */
    public static function NAME() {
        // TODO: Implement NAME() method.
    }

    /**
     * Should return an associative array of [constName => constValue] that are
     * valid for this enum.
     *
     * @return array
     */
    public static function CONSTANTS() {
        // TODO: Implement CONSTANTS() method.
    }

}
