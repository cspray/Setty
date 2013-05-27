<?php
/**
 * A PHPUnit TestCase that will ensure the \Setty\Enum\Builder implementation will
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

class EnumBuilderTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensures that if a 'name' and 'constant' key are not set in the blue print
     * array passed to EnumBuilder::storeFromArray that the appropriate exception
     * and message is thrown.
     *
     * @covers \Setty\Builder\EnumBuilder::storeFromArray
     */
    public function testStoringInvalidBluePrintKeysThrowsException() {
        $invalidBlueprint = [
            'noNameKey' => 'does not matter not right key',
            'noConstant' => []
        ];

        $EnumBuilder = new Builder\EnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The array passed to Setty\\Builder\\EnumBuilder::storeFromArray must have \'name\' and \'constant\' keys set';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($invalidBlueprint);
    }

    /**
     * Ensures that if a 'name' and 'constant' key are set but with a non-string
     * 'name' value that the appropriate exception and message is thrown.
     *
     * @covers \Setty\Builder\EnumBuilder::storeFromArray
     */
    public function testStoringInvalidBluePrintNameWithCorrectKeys() {
        $emptyBlueprint = [
            'name' => null,
            'constant' => [
                'VALID' => 'constants'
            ]
        ];

        $EnumBuilder = new Builder\EnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The value stored in the \'name\' key in the array passed to Setty\\Builder\\EnumBuilder::storeFromArray must be a non-empty string type';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($emptyBlueprint);
    }

    /**
     * Ensures that if an empty string is passed as the 'name' that the appropriate
     * exception and message is thrown.
     *
     * @covers \Setty\Builder\EnumBuilder::storeFromArray
     */
    public function testStoringEmptyBluePrintNameWithCorrectKeys() {
        $emptyBlueprint = [
            'name' => '',
            'constant' => [
                'VALID' => 'constants'
            ]
        ];

        $EnumBuilder = new Builder\EnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The value stored in the \'name\' key in the array passed to Setty\\Builder\\EnumBuilder::storeFromArray must be a non-empty string type';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($emptyBlueprint);
    }

    /**
     * Ensures that if a non-empty string with invalid characters is passed as the
     * enum name the appropriate exception and message is thrown.
     *
     * @covers \Setty\Builder\EnumBuilder::storeFromArray
     */
    public function testStoringBluePrintNameWithInvalidCharacters() {
        $invalidBlueprint = [
            'name' => 'I have spaces and other {} invalid ch4rs!',
            'constant' => [
                'VALID' => 'constants'
            ]
        ];

        $EnumBuilder = new Builder\EnumBuilder();

        $expectedException = '\\Setty\\Exception\\EnumBlueprintInvalidException';
        $expectedMessage = 'The value stored in the \'name\' key in the array passed to Setty\\Builder\\EnumBuilder::storeFromArray may only have letter and underscore characters';
        $this->setExpectedException($expectedException, $expectedMessage);
        $EnumBuilder->storeFromArray($invalidBlueprint);
    }



}
