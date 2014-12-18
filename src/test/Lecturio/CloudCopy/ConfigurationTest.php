<?php
namespace Lecturio\CloudCopy;

class ConfigurationTest extends \PHPUnit_Framework_TestCase {

    function testWiring()
    {
        /**
         * @var $container \Symfony\Component\DependencyInjection\Container
         * @var $config Configuration
         */
        $container = $GLOBALS['container'];

        $config = $container->get('configuration');
        $this->assertTrue($config instanceof Configuration);
        $this->assertTrue(is_array($config->get()));
    }
}
