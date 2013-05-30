<?php
/**
 * Ensures that the \Setty\AbstractEnum implementation handles the static method
 * calls the way it is supposed to.
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace SettyTest;

use Setty\AbstractEnum;

class AbstractEnumTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensures that if we attempt to call this method as a "true" static without
     * class instantiation that an exception is thrown.
     *
     * See method level docs for more information on why we're doing this.
     *
     * @covers \Setty\AbstractEnum::__callStatic
     */
    public function testEnsureThatBeforeConstructedCallsThrowExceptions() {
        $expectedException = '\\BadMethodCallException';
        $expectedMessage = 'The appropriate values have not been set for this enum';
        $this->setExpectedException($expectedException, $expectedMessage);

        AbstractEnumTestHelper::DOES_NOT_MATTER();
    }

    /**
     * Ensures that if we call methods with appropriate names we get the appropriate
     * value back.
     *
     * @covers \Setty\AbstractEnum::__callStatic
     */
    public function testEnsureThatCallingStaticMethodReturnsRightValues() {
        $Helper = new AbstractEnumTestHelper(['ONE' => '1', 'TWO' => '2']);
        $this->assertEquals('1', $Helper::ONE(), "Expected to get appropriate value when called Helper::ONE()");
    }

}

class AbstractEnumTestHelper extends AbstractEnum {}
