<?php
/**
 * An exception thrown if a Setty\Enum is attempted to be built that has not had
 * an appropriate blueprint stored for it.
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace Setty\Exception;

use \InvalidArgumentException;

class EnumNotFoundException extends InvalidArgumentException  {}
