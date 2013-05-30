# Setty

Setty is an educational proof of concept library to allow typesafe enums in PHP. This library is purely a proof of concept and is not intended to be used in a production environment. **This codebase makes use of eval() statement and could be susceptible to code injection if used improperly!** You have been warned! Use at your own risk.

## Release Info

- Current Stable Version: *In Development*
- Master Branch (Stable Development): [![Build Status](https://travis-ci.org/cspray/Setty.png?branch=master)](https://travis-ci.org/cspray/Setty)

## Requirements

- PHP 5.4+
- eval() support
- The [`ClassLoader`](http://github.com/cspray/ClassLoader) library

## Goals

- Provide a means for methods to typehint an enum and be confident that values passed into that method are of the type expected.
- Provide a means to create objects that will dynamically generate the appropriate enum type.
- Provide a means to hook into the processing of the system to allow other libraries finely grained access to Setty generated code.

## Resources

- Home page: http://cspray.github.io/Setty
- Source code: http://github.com/cspray/Setty
- Issues: http://github.com/cspray/Setty/issues

## Why typesafe enums?

Enums are a very useful structure in programming languages. They offer a more expressive way of handling "magic values" and can be an extremely useful tool when dealing with a set of constant data tightly associated to each other. In languages that offer more OOP support there is the concept of typesafe enums. What this means is that you can typehint the enum in your methods and only values from that enum may be passed in. Unfortunately this type of behavior is unsupported natively in PHP. This lack of support is due to the pattern in which "PHP enums" are created and the limits on class constants being scalar values.

This is the most common pattern for creating enums in PHP. In all of our examples we're gonna take a look at the Compass enum.

 ```php
<?php

class Compass {

    const NORTH = 'north';
    const SOUTH = 'south';
    const EAST = 'east';
    const WEST = 'west';

}
 ```

Using this "PHP enum" is quite simple and also very expressive: `Compass::NORTH`. Ultimately there is nothing inherently wrong with this. However when using this value as a parameter to a method it can sometimes become very troublesome. Since primitive types that can be stored in a constant can't be typehinted you can ultimately pass whatever scalar value you want into these methods. Setty aims to provide a mechanism for dynamically creating PHP classes that can be used as a typehint and act as more robust enums while still supporting the above use case.

## Expected Usage

> Setty is still in the design and development phase and the API demonstrated below is simply a fleshing out process and may be susceptible to change.

Let's take a look at an example of creating the `Compass` enum using the Setty library. This is just a potential API example and will be updated when the Setty API is stable.

```php
<?php

use \Setty\Builder;
use \Setty\Enum;

function enumMethodObjectCompare(Enum\Compass $compassDirection) {
    if (Enum\CompassEnum::NORTH() === $compassDirection) {
        echo 'North Pole here we come!';
    } else if (Enum\CompassEnum::SOUTH() === $compassDirection) {
        echo 'To the sun down South';
    } else if (Enum\CompassEnum::EAST() === $compassDirection) {
        echo 'Going to the big cities in the East';
    } else if (Enum\CompassEnum::WEST() === $compassDirection) {
        echo 'Sun and fun in the West';
    } else {
        echo 'We should never get here as $compassDirection will always match above checks';
    }
}

function enumMethodStringCompare(Enum\Compass $compassDirection) {
    switch ((string) $compassDirection) {
        case Enum\Compass::NORTH:
            echo 'going north';
            break;
        case Enum\Compass::SOUTH:
            echo 'going south';
            break;
        case Enum\Compass::WEST:
            echo 'going west';
            break;
        case Enum\Compass::EAST
            echo 'going east';
            break;
        case default:
            // we should never get here as Compass::__toString will always return
            // one of the available values.
            break;
    }

}

$Builder = new Builder\SettyEnumBuilder();
$Builder->storeFromArray([
    'name' => 'Compass',
    'constants' => [
        'NORTH' => 'n',
        'SOUTH' => 's',
        'EAST', 'e',
        'WEST', 'w'
    ]
]);
$CompassEnum = $Builder->buildStored('Compass');

enumMethodStringCompare($CompassEnum::NORTH()); // echos 'going north'
enumMethodObjectCompare(Enum\CompassEnum::SOUTH()); // echos 'To the sun down South', note static call to Enum\CompassEnum (dynamically created)
enumMethodStringCompare(Enum\CompassEnum::NO_DIRECTION()); // throws exception, enumMethodStringCompare is never invoked
```

The `\Setty\Builder\EnumBuilder::build()` will return a class of type `\Setty\Enum\<EnumName>Enum` which implements the `\Setty\Enum` interface. This is the class that calling code will utilize to work with the values set in the enum. So from our example we would be working with an object of type `\Setty\Enum\CompassEnum`. This object implements the `__callStatic()` PHP magic method. When this method is invoked with a name of one of the constants an object of type `\Setty\Enum\<EnumValue>` will be returned; in our example this would be `\Setty\Enum\Compass`. This object implements PHP's `__toString()` magic method that will return the constant value associated to the object. Because each constant get's its very own object to represent it we can effectively compare `\Setty\EnumValue` implementations as either objects or strings. If you attempt to call for a constant that does not exist an exception will be thrown because, well, come one why'd you try to do that?

## Technical Details

As you can tell from the expected usage it can be a tad bit hacky when comparing as a string. When we typehint our methods we are using an object type to ensure type safety. However when we compare the passed enum object to our expected value we are actually comparing the string representation of the object. Because of this depending on how you are using the `\Setty\EnumValue` objects you should explicitly cast the object to a string. The reason for having this behavior is that we are striving for as "PHP enum-like" API syntax as possible. That means to compare the passed enum object we really want to use the constant syntax `Compass::NORTH`. However to support this behavior we must compare scalar values as, again, constants don't allow anything but primitive scalar values.

However, when we compare using objects we have far more integrity that we are truly dealing with the appropriate type. Each `\Setty\EnumValue` returned from `\Setty\Enum::CONSTANT()` call is a `final` class that has been dynamically created. A class is created for each individual constant associated to that enum. A private variable within that dynamically created class stores the value from the list of constants that the instance represents. As such each `\Setty\EnumValue` can be compared as both a string and an object. Comparing as an object can be useful when 'duck type' hinting; you pass along values dynamically and compare as an object to see if it is truly a `\Setty\Enum\<EnumName>` object.

Because of some of the technical limitation in the language and our intended goals there are also necessary limitations on the library. The following expectations **MUST** be met when using the `\Setty\Builder\EnumBuilder`. If the expectations are not met an exception will be thrown and your enum will not be created.

- Manually building enums is **NOT** supported. The `\Setty\Enum\<EnumName>Enum` and `\Setty\Enum\<EnumName>` objects are *dynamically created using `eval()`*. Thus for them to be properly created you **MUST** create them using the provided Setty API.
- Enums **MUST** be given a valid unique string name with the appropriate types to be available for creation by the library. In our example this would mean the types `\Setty\Enum\CompassEnum` and `\Setty\Enum\Compass` **MUST** not be types already included into script processing.
- If the expected class names exist they **MUST** implement the `\Setty\Enum` and `\Setty\EnumValue` interfaces, respectively.
- All enum constant names and values **MUST** be unique to the enum, non-empty string values. If either the name or value are not unique to the enum or the name or value are empty then that enum is invalid.
- All enum constant values **MUST** be a string. You **MUST** not pass non-stringy values. The decision to enforce stringiness on constant values was to ease development for the initial prototype. This issue may be reevaluated in future releases.
- Enum names **MUST** only contain letters and underscores. No other characters, including spaces, numbers and hyphens are permitted to be in the Enum name.
- Enum constant names **MUST** only contain letters, numbers and underscores. No other characters, including spaces and hyphens are permitted to be in the Enum constant name.

## Who the hell would do this?!

I would that's who! My name is Charles Sprayberry and I occasionally write PHP code. I created [`ClassLoader`](http://github.com/cspray/ClassLoader), [`SprayFire`](http://github.com/cspray/SprayFire) and now Setty. You can read more about my thoughts on software development and PHP at my [blog where I ramble about things](http://cspray.github.io).
