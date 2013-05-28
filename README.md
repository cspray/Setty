# Setty

Setty is an educational proof of concept library to allow typesafe enums in PHP. This library is purely a proof of concept and is not intended to be used in a production environment. **This codebase makes use of eval() statement and could be susceptible to code injection if used improperly!**. You have been warned! Use at your own risk.

## Requirements

- PHP 5.4+
- eval() support
- The [`ClassLoader`](http://github.com/cspray/ClassLoader) library

## Goals

- Provide a means for methods to typehint an enum and be confident that values passed into that method are of the type expected.
- Provide a means to create objects that will dynamically generate the appropriate enum type.
- Provide a means to hook into the processing of the system to allow other libraries finely grained access to Setty generated code.

## Why typesafe enums?

Enums are a very useful structure in OOP languages. They offer a more expressive way of handling "magic values" and can be an extremely useful tool whwen dealing with a set of constant data associated to each other. In languages that offer more OOP support there is the concept of typesafe enums. What this means is that you can typehint the enum in your methods and only values from that enum may be passed in. Unfortunately this type of behavior is unsupported natively in PHP. This lack of support is due to the pattern in which "PHP enums" are created and the limits on class constants being scalar values.

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
use \Setty\Enum\UserEnum;

function enumMethod(UserEnum\Compass $compassDirection) {
    switch ((string) $compassDirection) {
        case UserEnum\Compass::NORTH:
            echo 'going north';
            break;
        case UserEnum\Compass::SOUTH:
            echo 'going south';
            break;
        case UserEnum\Compass::WEST:
            echo 'going west';
            break;
        case UserEnum\Compass::EAST
            echo 'going east';
            break;
        case default:
            // we should never get here as Compass::__toString will always return
            // one of the available values.
            break;
    }

}

$CompassEnum = (new Builder\EnumBuilder())->enum('Compass')
                                          ->constant('NORTH', 'north')
                                          ->constant('SOUTH', 'south')
                                          ->constant('WEST', 'west')
                                          ->constant('EAST', 'east')
                                          ->build();

enumMethod($CompassEnum::NORTH());
// echos 'going north'

enumMethod($CompassEnum::NO_DIRECTION());
// throws exception, enumMethod is never invoked
```

The `\Setty\Builder\EnumBuilder::build()` will return a class of type `\Setty\Enum\BaseEnum` which implements the `\Setty\Enum` interface. This is the class that calling code will utilize to work with the values set in the enum. All of the enum values are accessible by calling the constant name as if it were a static method. Each time you call this method an object of type `\Setting\Enum\UserEnum\<EnumName>` is returned; this dynamically created object will implement the `\Setty\EnumValue` interface. This type is also what should be used in your method typehints. This object will have a `__toString()` implementation that will return one of the values provided in the enum. The example above would create a `\Setty\Enum\UserEnum\Compass` from the call to `$CompassEnum::NORTH()`. When you cast this object to a string you would retrieve the value 'north' which would match `\Setty\Enum\UserEnum\Compass::NORTH`.

It is important to keep in mind though that the ``$compassDirection`` argument passed is a true object and other functionality can be composed or extended with this object.

## Technical Details

As you can tell from the expected usage it is a tad bit "hacky". When we typehint our methods we are using an object type to ensure type safety. However when we compare the passed enum object to our expected value we are actually comparing the string representation of the object. Because of this depending on how you are using the `\Setty\EnumValue` objects you should explicitly cast the object to a string. The reason for having this behavior is that we are striving for as "PHP enum-like" API syntax as possible. That means to compare the passed enum object we really want to use the constant syntax `Compass::NORTH`. However to support this behavior we must compare scalar values as, again, constants don't allow anything but primitive scalar values.

Because of this technical limitation in the language there are also necessary limitations on the library. The following expectations **MUST** be met when using the `\Setty\Builder\EnumBuilder`. If the expectations are not met an exception will be thrown and your enum will not be created.

- Enums must be given a valid unique string name that has not been utilized as an enum or class name already. This means in our example the `Setty\Enum\UserEnum\Compass` name **MUST** be available for use by the library.
- All enum constant names and values **MUST** be unique, non-empty string values. If either the name or value are not unique to the enum or the name or value are empty then that enum is invalid.
- Manually building enums is **NOT** supported. The `\Setty\Enum\UserEnum\<EnumName>` objects are *dynamically created using `eval()`*. Thus for them to be properly created you **MUST** create them using the provided Setty API.
- All enum constant values will be cast to a string. You may pass integers and floats as constant values but they will be cast as a string. Because of our use of the `__toString` method for comparison we are making a decision to enforce stringiness on all constant values.
- Enum names must only contain letters and underscores. No other characters, including spaces, numbers and hyphens are permitted to be in the Enum name.
- Enum constant names may contain letters, numbers and underscores. No other characters, including spaces and hyphens are permitted to be in the Enum constant name.
- Enum constant values may contain any unique to the enum, non-empty string value.

## Who the hell would do this?!

I would that's who! My name is Charles Sprayberry and I occasionally write PHP code. I created [`ClassLoader`](http://github.com/cspray/ClassLoader), [`SprayFire`](http://github.com/cspray/SprayFire) and now Setty. You can read more about my thoughts on software development and PHP at my [blog where I ramble about things](http://cspray.github.io).
