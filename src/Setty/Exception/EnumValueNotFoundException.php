<?php
/**
 * An exception thrown if the Setty\EnumValue implementation being created could
 * not be found.
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace Setty\Exception;

use \LogicException;

class EnumValueNotFoundException extends LogicException {}
