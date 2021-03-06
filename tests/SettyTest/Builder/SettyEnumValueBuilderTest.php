<?php
/**
 * A TestCase to ensure that the \Setty\Builder\SettyEnumValueBuilder is implemented
 * with all required functionality and that functionality behaves as expected.
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace SettyTest\Builder;

use \Setty\Builder;
use \PHPUnit_Framework_TestCase;

class SettyEnumValueBuilderTest extends PHPUnit_Framework_TestCase {

    /**
     * Ensures that the appropriate type is created when using the Builder\SettyEnumValueBuilder
     * implementation.
     *
     * @covers \Setty\Builder\SettyEnumValueBuilder::buildEnumValue
     */
    public function testCreatingEnumReturnsAppropriateType() {
        $Builder = new Builder\SettyEnumValueBuilder();
        $Compass = $Builder->buildEnumValue('Compass', 'north');

        $this->assertInstanceOf('\\Setty\\EnumValue', $Compass);
        $this->assertInstanceOf('\\Setty\Enum\\Compass', $Compass);
    }

    /**
     * Ensures that can make two different enum types without naming collisions.
     *
     * @covers \Setty\Builder\SettyEnumValueBuilder::buildEnumValue
     */
    public function testCreateMultipleEnumsDoesNotResultInNameCollision() {
        $Builder = new Builder\SettyEnumValueBuilder();

        $Compass = $Builder->buildEnumValue('Compass', 'WEST');
        $this->assertInstanceOf('\\Setty\\EnumValue', $Compass);
        $this->assertInstanceOf('\\Setty\\Enum\\Compass', $Compass);

        $YesNoMaybe = $Builder->buildEnumValue('YesNoMaybe', 'YES');
        $this->assertInstanceOf('\\Setty\\EnumValue', $YesNoMaybe);
        $this->assertInstanceOf('\\Setty\\Enum\\YesNoMaybe', $YesNoMaybe);
    }

    /**
     * Ensures that if we create an enum with the same type and set value that the
     * same object is returned.
     *
     * @covers \Setty\Builder\SettyEnumValueBuilder::buildEnumValue
     */
    public function testCreatingSameEnumWithSameValueTwiceResultsInSameObject() {
        $Builder = new Builder\SettyEnumValueBuilder();

        $Compass = $Builder->buildEnumValue('Compass', 'EAST');
        $this->assertInstanceOf('\\Setty\\EnumValue', $Compass);
        $this->assertInstanceOf('\\Setty\\Enum\\Compass', $Compass);

        $SecondCompass = $Builder->buildEnumValue('Compass', 'EAST');
        $this->assertInstanceOf('\\Setty\\EnumValue', $SecondCompass);
        $this->assertInstanceOf('\\Setty\\Enum\\Compass', $SecondCompass);

        $this->assertSame($Compass, $SecondCompass);
    }

    /**
     * Ensures that if we create the same enum type with different set constants
     * that the objects returned are not identical.
     *
     * @covers \Setty\Builder\SettyEnumValueBuilder::buildEnumValue
     */
    public function testCreatingSameEnumWithDifferentValueResultsInDifferentObjectInstance() {
        $Builder = new Builder\SettyEnumValueBuilder();

        $East = $Builder->buildEnumValue('Compass', 'EAST');
        $this->assertInstanceOf('\\Setty\\EnumValue', $East);
        $this->assertInstanceOf('\\Setty\\Enum\\Compass', $East);

        $West = $Builder->buildEnumValue('Compass', 'WEST' );
        $this->assertInstanceOf('\\Setty\\EnumValue', $West);
        $this->assertInstanceOf('\\Setty\\Enum\\Compass', $West);

        $this->assertNotSame($East, $West, 'East and West are the same objects but should not be');
    }

    /**
     * Ensures that the defined constants and values are set and available when
     * creating an Enum.
     *
     * @covers \Setty\Builder\SettyEnumValueBuilder::buildEnumValue
     */
    public function testCreatingEnumValuesDoesNotCreateConstants() {
        $Builder = new \Setty\Builder\SettyEnumValueBuilder();


        $Compass = $Builder->buildEnumValue('Compass', 'WEST');

        $actualConstants = (new \ReflectionObject($Compass))->getConstants();
        $this->assertSame([], $actualConstants, 'Created EnumValue has constants but should not');
    }

}
