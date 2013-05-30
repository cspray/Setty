<?php
/**
 * Interface that represents the primary public facing API for storing \Setty\Enum
 * blueprints and creating \Setty\Enum objects.
 *
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since 0.1.0
 */

namespace Setty\Builder;


/**
 * An interface that stores blueprints for \Setty\Enum types that are dynamically
 * created and used to generate the appropriate typehintable objects.
 *
 * This interface is intended to work with a structured PHP array that details the
 * blueprint or configuration details for how a \Setty\Enum should be created. This
 * format should appear similar to the following:
 *
 * <code>
 * [
 *  'name' => 'EnumName',
 *  'constant' => [
 *      'CONST_NAME' => 'constValue',
 *      'OTHER_CONST' => 'otherValue'
 *  ]
 * ]
 * </code>
 *
 * Ultimately this blueprint is how all \Setty\Enum objects are expected to be created
 * whether the blueprint is stored from an array or generated through the fluent
 * API available to implementations of this interface.
 */
interface EnumBuilder {

    // Methods below are those used to store Enum blueprint information to create
    // \Setty\Enum objects in other parts of the system. This API is for configuring enums
    // in one part of the code and using them in a different part

    /**
     * Should store information appropriate for dynamically creating a \Setty\Enum
     * object for use when retrieving the Enum object in later calls.
     *
     * For simplicity reasons this method should only support setting one
     * enum at a time. If the blueprint is invalid an exception should be thrown.
     *
     * Please see interface docs for more information on the appropriate format
     * expected in $settyEnumBlueprint.
     *
     * @param array $settyEnumBlueprint
     * @return void
     *
     * @throw \Setty\Exception\EnumBlueprintInvalidException
     */
    public function storeFromArray(array $settyEnumBlueprint);

    /**
     * Return a \Setty\Enum suitable for the given $enumName if it
     * has been stored.
     *
     * If the $enumName has not been stored in the builder throw an exception.
     *
     * @param string $enumName
     * @return \Setty\Enum
     *
     * @throw \Setty\Exception\EnumNotFoundException
     */
    public function buildStored($enumName);

}
