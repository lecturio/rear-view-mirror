<?php
namespace Lecturio\CloudCopy\Filesystem;


class RecursiveReaderTest extends \PHPUnit_Framework_TestCase
{

    function testWiring()
    {
        $container = $GLOBALS['container'];

        /**
         * @var $recursiveReader RecursiveReader
         */
        $recursiveReader = $container->get('recursiveReader');
        $this->assertTrue($recursiveReader instanceof RecursiveReader);
    }

    function testDirectoryRead()
    {
        $recursiveReader = new RecursiveReader(array(
            'filesystem' => array('general.node' => __DIR__)
        ));
        $this->assertEquals(1, count($recursiveReader->read()));
    }

    function testDirectoryReadWithMaskWithOneMatch()
    {
        $recursiveReader = new RecursiveReader(array(
            'filesystem' => array('general.node' => __DIR__, 'copy.paths' => array('Filesystem'))
        ));
        $this->assertEquals(1, count($recursiveReader->read()));
    }

    function testDirectoryReadWithMaskWithMultipleMatches()
    {
        $recursiveReader = new RecursiveReader(array(
            'filesystem' => array('general.node' => __DIR__, 'copy.paths' => array('system', 'Recursive'))
        ));

        var_dump($recursiveReader->read());
        $this->assertEquals(1, count($recursiveReader->read()));
    }

}
