<?php
/**
 * 
 * @author Charles Sprayberry
 */

$settySrc = \dirname(\dirname(__DIR__)) . '/src';

require_once $settySrc . '/ClassLoader/Loader.php';

$Loader = new \ClassLoader\Loader();
$Loader->registerNamespaceDirectory('Setty', $settySrc);
$Loader->setAutoloader();
