<?php

namespace Lecturio\CloudCopy\Filesystem;

class RecursiveReader
{
    private $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Reads directory by given mask
     *
     * @return array
     */
    public function read()
    {
        $directory = new \RecursiveDirectoryIterator($this->config['filesystem']['general.node']);
        $iterator = new \RecursiveIteratorIterator($directory);

        $nodes = array();
        foreach ($iterator as $name => $node) {
            if (is_dir($name)) {
                continue;
            }

            if (isset($this->config['filesystem']['copy.paths'])) {
                foreach ($this->config['filesystem']['copy.paths'] as $path) {
                    if (preg_match("/$path/", $name)) {
                        $nodes[$name] = $name;
                    }
                }
            } else {
                $nodes[$name] = $name;
            }
        }

        return $nodes;
    }
}