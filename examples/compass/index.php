<?php

$rootDir = \dirname(\dirname(__DIR__));

require_once $rootDir . '/src/ClassLoader/Loader.php';

$Loader = new \ClassLoader\Loader();

$Loader->registerNamespaceDirectory('Setty', $rootDir . '/src');
$Loader->setAutoloader();

$ValueBuilder = new \Setty\Builder\SettyEnumValueBuilder();
$EnumBuilder = new \Setty\Builder\SettyEnumBuilder($ValueBuilder);

$EnumBuilder->storeFromArray([
    'name' => 'Compass',
    'constant' => ['NORTH' => 'n', 'SOUTH' => 's', 'EAST' => 'e', 'WEST' => 'w']
]);
$Enum = $EnumBuilder->buildStored('Compass');

var_dump($Enum::NORTH());
var_dump((string) \Setty\Enum\CompassEnum::NORTH());
var_dump($Enum::SOUTH());
var_dump($Enum::EAST());
var_dump($Enum::WEST());
