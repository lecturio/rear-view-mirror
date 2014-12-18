<?php

/**
 * @var $container \Symfony\Component\DependencyInjection\ContainerBuilder
 */
$container->register('configResource', 'Symfony\Component\Config\FileLocator')
    ->addArgument(__DIR__);
$container->register('ymlParser', 'Symfony\Component\Yaml\Parser');

$resource = $container->get('configResource')->locate('config.yml', null, false);
$config = $container->get('ymlParser')->parse(file_get_contents($resource[0]));

$container->register('s3client', 'Lecturio\CloudCopy\AWS\S3Client')
    ->addArgument($config);
$container->register('recursiveReader', 'Lecturio\CloudCopy\Filesystem\RecursiveReader')
    ->addArgument($config);
$container->register('configuration', 'Lecturio\CloudCopy\Configuration')
    ->addArgument($config);
