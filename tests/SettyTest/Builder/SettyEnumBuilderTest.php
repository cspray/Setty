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
        $expectedMessage = 'The enum type passed, Valid, has already been created';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($blueprint);
    }

    /**
     * Creates an instance of the object under test.
     *
     * @return \Setty\Builder\SettyEnumBuilder
     */
    public function getSettyEnumBuilder() {
        return new Builder\SettyEnumBuilder();
    }


}