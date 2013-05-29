<?php
/**
 * An abstract class extended by dynamically created \Setty\EnumValue objects that
 * provides basic functionality for string representation of the enum value.
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace Setty;

use \Setty;

abstract class AbstractEnumValue implements Setty\EnumValue {

    /**
     * The string representation of the enum
     *
     * @property string
     */
    private $value;

    /**
     * This function is final as we do not want extending classes modifying the
     * way this object performs Setty provided operations.
     *
     * @param string $value
     */
    final public function __construct($value) {
        $this->value = $value;
    }

    /**
     * We do not want any other value being able to be returned from this implementation,
     * it should *ALWAYS* return the value set in the constructor.
     *
     * @return string
     */
    final public function __toString() {
        return (string) $this->value;
    }


}
