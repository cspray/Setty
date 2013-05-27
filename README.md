# Setty

Setty is an educational proof of concept library to allow typesafe enums in PHP. This library is purely a proof of concept and is not intended to be used in a production environment. **This codebase makes use of eval() statement and could be susceptible to code injection if used improperly!**. You have been warned! Use at your own risk.

## Requirements

- PHP 5.4+
- eval() support
- The [`ClassLoader`](http://github.com/cspray/ClassLoader) library

## Why typesafe enums?

Enums are a very useful structure in OOP languages. They offer a more expressive way of handling "magic values" and can be an extremely useful tool. In languages that offer more OOP support there is the concept of typesafe enums. What this means is that you can typehint the enum as a type and only values from that enum may be passed in. Unfortunately this type of behavior is unsupported in PHP.

This is the most common pattern for creating very loose enums in PHP. In all of our examples we're gonna take a look at the Compass enum.

 ```php
<?php

abstract class Compass {

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

function enumMethod(Enum\Compass $compassDirection) {
    switch ((string) $compassDirection) {
        case Compass::NORTH:
            // do stuff for north
            break;
        case Compass::SOUTH:
            // do stuff for south
            break;
        case Compass::WEST:
            // do stuff for west
            break;
        case Compass::EAST
            // do stuff for east
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

enumMethod($CompassEnum::NORTH()); // Returns a \Setty\Enum\Compass with __toString set to 'north'
enumMethod($CompassEnum::NO_DIRECTION()); // would throw an exception
```

The `\Setty\Builder\EnumBuilder` class will create Setty enums and return a class of type `\Setty\Enum\<EnumName>Enum` where `<EnumName>` is the name of the enum. In the example above the `$CompassEnum` variable will be of type `\Setty\Enum\CompassEnum`. This is the class that calling code will utilize to work with the values set in the enum. All of the enum values are accessible by calling the constant name as if it were a static method. Each time you call this method an object of type `\Setting\Enum\<EnumName>` is returned. This type is also what should be used in your method typehints. This method will have a `__toString()` implementation that will return one of the values provided in the enum. The example above would create a `\Setty\Enum\Compass` from the call to `$CompassEnum::NORTH()`. When you cast this object to a string you would retrieve the value 'north'.

## Technical Details

As you can tell from the expected usage it is a tad bit hacky. When we typehint our methods we are using an object type to ensure type safety. However when we compare the passed enum object to our expected value we are actually comparing the string representation of the object. Because of this depending on how you are using the `\Setty\Enum` object you should explicitly cast the object to a string. The reason for having this behavior is that we are striving for as "PHP enum-like" API syntax as possible. That means to compare the passed enum object we really want to use the constant syntax `Compass::NORTH`. However to support this behavior we must compare scalar values as constants don't allow anything but primitive scalar values.

Because of this technical limitation in the language, that is not being able to provide constants of complex object types, there are also necessary limitations on the library. The following expectations **MUST** be met when using the `\Setty\Builder\EnumBuilder`. If the expectations are not met an exception will be thrown and your enum will not be created.

- Enums must be given a valid unique string name that has not been utilized as an enum name already. This means both the `Setty\Enum\CompassEnum` and `Setty\Enum\Compass` names **MUST** be available.
- All enum constant names and values **MUST** be unique string values. If either the name or value are not unique to the enum then that enum is invalid.
- Manually building enums is *not* supported. Both the `\Setty\Enum\<EnumName>Enum` and `\Setty\Enum\<EnumName>` objects are *dynamically created using `eval()`*. Thus for them to be properly created you **MUST** create them through the `\Setty\Builder\EnumBuilder` interface.
- All enum constant values will be cast to a string. You may pass integers and floats as constant values but they will be cast as a string. Because of our use of the `__toString` method for comparison we are making a decision to enforce stringiness on all constant values.

## Who the hell would do this?!

I would that's who! My name is Charles Sprayberry and I occasionally write PHP code. I created [`ClassLoader`](http://github.com/cspray/ClassLoader), [`SprayFire`](http://github.com/cspray/SprayFire) and now Setty. You can read more about my thoughts on software development and PHP at my [blog where I ramble about things](http://cspray.github.io).
