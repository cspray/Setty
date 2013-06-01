<?php
/**
 * A PHPUnit TestCase that will ensure the \Setty\Enum\SettyEnumBuilder implementation will
 * construct \Setty\Enum blueprints appropriately in both the fluent and non-fluent
 * API methods.
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace SettyTest\Builder;

use \Setty\Builder;
use \PHPUnit_Framework_TestCase;

class SettyEnumBuilderTest extends PHPUnit_Framework_TestCase {

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
     * Ensures that if a 'name' and 'constant' key are not set in the blue print
     * array passed to EnumBuilder::storeFromArray that the appropriate exception
     * and message is thrown.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringInvalidBlueprintKeysThrowsException() {
        $invalidBlueprint = [
            'noNameKey' => 'does not matter not right key',
            'noConstant' => []
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The array passed to Setty\\Builder\\SettyEnumBuilder::storeFromArray must have \'name\' and \'constant\' keys set';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($invalidBlueprint);
    }

    /**
     * Ensures that if a 'name' and 'constant' key are set but with a non-string
     * 'name' value that the appropriate exception and message is thrown.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringInvalidBlueprintNameWithCorrectKeys() {
        $emptyBlueprint = [
            'name' => null,
            'constant' => [
                'VALID' => 'constants'
            ]
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The value stored in the \'name\' key in the array passed to Setty\\Builder\\SettyEnumBuilder::storeFromArray must be a non-empty string type';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($emptyBlueprint);
    }

    /**
     * Ensures that if an empty string is passed as the 'name' that the appropriate
     * exception and message is thrown.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringEmptyBlueprintNameWithCorrectKeys() {
        $emptyBlueprint = [
            'name' => '',
            'constant' => [
                'VALID' => 'constants'
            ]
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The value stored in the \'name\' key in the array passed to Setty\\Builder\\SettyEnumBuilder::storeFromArray must be a non-empty string type';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($emptyBlueprint);
    }

    /**
     * Ensures that if a non-empty string with invalid characters is passed as the
     * enum name the appropriate exception and message is thrown.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringBlueprintNameWithInvalidCharacters() {
        $invalidBlueprint = [
            'name' => 'I have spaces and other {} invalid ch4rs!',
            'constant' => [
                'VALID' => 'constants'
            ]
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The value stored in the \'name\' key in the array passed to Setty\\Builder\\SettyEnumBuilder::storeFromArray may only have letter and underscore characters';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($invalidBlueprint);
    }

    /**
     * Ensures that if a non-array is passed as the enum constants the appropriate
     * exception and message is thrown.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringBlueprintWithInvalidConstantType() {
        $invalidBlueprint = [
            'name' => 'Valid',
            'constant' => 'I am not an array'
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The value stored in the \'constant\' key in the array passed to Setty\\Builder\\SettyEnumBuilder::storeFromArray must be a non-empty array type';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($invalidBlueprint);
    }

    /**
     * Ensures that if an empty array is passed as the enum constants the appropriate
     * exception and message is thrown.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringBlueprintWithEmptyConstant() {
        $invalidBlueprint = [
            'name' => 'Valid',
            'constant' => []
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The value stored in the \'constant\' key in the array passed to Setty\\Builder\\SettyEnumBuilder::storeFromArray must be a non-empty array type';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($invalidBlueprint);
    }

    /**
     * Ensures that if a 'constant' key is passed with a name that has invalid
     * characters the appropriate exception and message is thrown.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringBlueprintWithInvalidConstantName() {
        $invalidBlueprint = [
            'name' => 'Valid',
            'constant' => [
                'I have invalid space characters and other !!! things $& that make this a bad key...besides the length' => 'I am valid though!'
            ]
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The keys stored in the \'constant\' array in the array passed to Setty\\Builder\\SettyEnumBuilder::storeFromArray may only have letters, numbers and underscore characters';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($invalidBlueprint);
    }

    /**
     * Ensures that if a 'constant' key is passed with a name that is numerically
     * indexed the appropriate exception and message is thrown.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringBlueprintWithConstantArrayNumericallyIndexed() {
        $invalidBlueprint = [
            'name' => 'Valid',
            'constant' => [
                'I am the value not the key, I am valid!'
            ]
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();
        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The keys stored in the \'constant\' array in the array passed to Setty\\Builder\\SettyEnumBuilder::storeFromArray must be a non-empty string type';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($invalidBlueprint);
    }

    /**
     * Ensures that if a blank string is passed as a key the appropriate exception
     * and message is thrown.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringBlueprintConstantWithEmptyStringKeyName() {
        $invalidBlueprint = [
            'name' => 'Valid',
            'constant' => [
                '' => 'still valid value'
            ]
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();
        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The keys stored in the \'constant\' array in the array passed to Setty\\Builder\\SettyEnumBuilder::storeFromArray must be a non-empty string type';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($invalidBlueprint);
    }

    /**
     * Ensures that if a non-string is passed as a constant value the appropriate
     * exception and message is thrown.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringBlueprintWithConstantValueThatIsNotString() {
        $invalidBlueprint = [
            'name' => 'Valid',
            'constant' => [
                'VALID' => []
            ]
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();
        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The values stored in the \'constant\' array passed to Setty\\Builder\\SettyEnumBuilder::storeFromArray must be non-empty string values';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($invalidBlueprint);
    }

    /**
     * Ensures that blank string enum constant values cannot be set in the blueprint
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringBlueprintWithConstantValueEmptyString() {
        $invalidBlueprint = [
            'name' => 'Valid',
            'constant' => [
                'VALID' => ''
            ]
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();
        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The values stored in the \'constant\' array passed to Setty\\Builder\\SettyEnumBuilder::storeFromArray must be non-empty string values';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($invalidBlueprint);
    }

    /**
     * Ensures that if we store an Enum blueprint with the same name twice that
     * an exception is thrown the second time.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::storeFromArray
     */
    public function testStoringBlueprintWithDuplicateEnumNames() {
        $blueprint = [
            'name' => 'Valid',
            'constant' => [
                'VALID' => 'foo'
            ]
        ];

        $EnumBuilder = $this->getSettyEnumBuilder();
        $EnumBuilder->storeFromArray($blueprint);

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The enum type passed, Valid, has already been stored';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($blueprint);
    }

    /**
     * Ensures that we get an exception if we attempt to create an enum that
     * has not been stored.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::buildStored
     */
    public function testBuildingEnumThatHasNotBeenStored() {
        $Builder = $this->getSettyEnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumNotFoundException';
        $expectedMessage = 'The enum type passed to Setty\\Builder\\SettyEnumBuilder::buildStored, NotStored, could not be found';
        $this->setExpectedException($expectedException, $expectedMessage);
        $Builder->buildStored('NotStored');
    }

    /**
     * Adding test that we create the appropriate enum type.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::buildStored
     */
    public function testBuildingCompassExampleIsRightType() {
        $Builder = $this->getSettyEnumBuilder();
        $Builder->storeFromArray(['name' => 'Compass', 'constant' => $this->compassConst]);

        $CompassEnum = $Builder->buildStored('Compass');
        $this->assertInstanceOf('\\Setty\\Enum', $CompassEnum);
        $this->assertInstanceOf('\\Setty\\Enum\\CompassEnum', $CompassEnum);
    }

    // -------------------------------------------------------------------------
    // The tests below represent those tests on the dynamically created \Setty\Enum.
    // These tests are here by a design "decision" that was forced upon us by the
    // nature of static methods and properties. By having to rely on static we
    // must closely couple everything about the enum's creation and management to
    // each individual object created. We could create some static inheritance
    // code but honestly it's a pain in the ass.

    // Ultimately we only have to "write" the autogenerated code once. Our generated
    // code will be a little bloated but that's the nature of compromise.
    // -------------------------------------------------------------------------

    /**
     * Ensures that if we call methods with appropriate names we get the appropriate
     * value back.
     */
    public function testEnsureThatCallingStaticMethodReturnsRightValues() {
        $Builder = $this->getSettyEnumBuilder();

        $Builder->storeFromArray(['name' => 'Compass' ,'constant' => $this->compassConst]);
        $Enum = $Builder->buildStored('Compass');

        $this->assertInstanceOf('\\Setty\\Enum', $Enum);
        $this->assertInstanceOf('\\Setty\\Enum\\CompassEnum', $Enum);

        $North = $Enum::NORTH();
        $this->assertInstanceOf('\\Setty\\Enum\\Compass', $North);
        $this->assertEquals('north', (string) $North);
    }

    /**
     * Ensures that the defined constants and values are set and available when
     * creating an Enum.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::buildStored
     */
    public function testCreatingEnumSetsAppropriateConstantsAndValues() {
        $Builder = $this->getSettyEnumBuilder();

        $Builder->storeFromArray(['name' => 'Compass', 'constant' => $this->compassConst]);
        $Compass = $Builder->buildStored('Compass');

        $actualConstants = (new \ReflectionObject($Compass))->getConstants();
        $this->assertSame($this->compassConst, $actualConstants, 'Created EnumValue does not have the appropriate constants');
    }

    /**
     * Ensures that if a constant name is defined twice then an exception will
     * be thrown.
     *
     * @covers \Setty\Builder\SettyEnumBuilder::buildStored
     */
    public function testCreatingEnumWithDuplicateConstantValuesThrowsException() {
        $Builder = $this->getSettyEnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The enum, YesNo, has a duplicate constant value: dupe';
        $this->setExpectedException($expectedException, $expectedMessage);
        $Builder->storeFromArray(['name' => 'YesNo', 'constant' => ['YES' => 'dupe', 'NO' => 'dupe']]);
    }

    /**
     * Creates an instance of the object under test.
     *
     * @return \Setty\Builder\SettyEnumBuilder
     */
    public function getSettyEnumBuilder() {
        $EnumValueBuilder = new Builder\SettyEnumValueBuilder();
        return new Builder\SettyEnumBuilder($EnumValueBuilder);
    }

}
