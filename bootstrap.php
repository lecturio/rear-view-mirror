<?php
require_once __DIR__ . '/vendor/autoload.php';
use Symfony\Component\ClassLoader\UniversalClassLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaceFallback(__DIR__ . '/src/main');
$loader->register();

$container = new ContainerBuilder();
$loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/src/resources'));
$loader->load('wiring.php');