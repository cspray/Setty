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
     * An array of constants used to generate the Compass enum example.
     *
     * @property array
     */
    protected $compassConst = [
        'NORTH' => 'north',
        'SOUTH' => 'south',
        'EAST' => 'east',
        'WEST' => 'west'
    ];

    /**
     * Ensures that the appropriate type is created when using the Builder\SettyEnumValueBuilder
     * implementation.
     *
     * @covers \Setty\Builder\SettyEnumValueBuilder::buildEnumValue
     */
    public function testCreatingEnumReturnsAppropriateType() {
        $Builder = new Builder\SettyEnumValueBuilder();
        $Compass = $Builder->buildEnumValue('Compass', $this->compassConst, 'NORTH');

        $this->assertInstanceOf('\\Setty\\EnumValue', $Compass);
        $this->assertInstanceOf('\\Setty\Enum\\UserEnum\\Compass', $Compass);
    }

    /**
     * Ensures that can make two different enum types without naming collisions.
     *
     * @covers \Setty\Builder\SettyEnumValueBuilder::buildEnumValue
     */
    public function testCreateMultipleEnumsDoesNotResultInNameCollision() {
        $Builder = new Builder\SettyEnumValueBuilder();

        $Compass = $Builder->buildEnumValue('Compass', $this->compassConst, 'WEST');
        $this->assertInstanceOf('\\Setty\\EnumValue', $Compass);
        $this->assertInstanceOf('\\Setty\\Enum\\UserEnum\\Compass', $Compass);

        $YesNoMaybe = $Builder->buildEnumValue('YesNoMaybe', ['YES' => 'y', 'NO' => 'n', 'MAYBE' => 'm'], 'YES');
        $this->assertInstanceOf('\\Setty\\EnumValue', $YesNoMaybe);
        $this->assertInstanceOf('\\Setty\\Enum\\UserEnum\\YesNoMaybe', $YesNoMaybe);
    }

}
