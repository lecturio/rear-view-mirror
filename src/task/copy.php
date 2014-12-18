<?php
#!/usr/bin/env php

require_once __DIR__ . '../../../bootstrap.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;


class CopyFile extends Command
{

    const AMAZON_S3 = 's3';
    /**
     * @var Container
     */
    private $container;

    protected function configure()
    {
        $this
            ->setName('copy:files')
            ->addOption(
                self::AMAZON_S3,
                null,
                InputOption::VALUE_NONE,
                'Copy files to Amazon S3 storage'
            )
            ->setDescription('copy files across cloud services');

        $this->container = $GLOBALS['container'];
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption(self::AMAZON_S3)) {


            /**
             * @var $reader \Lecturio\CloudCopy\Filesystem\RecursiveReader
             * @var $s3client \Aws\S3\S3Client
             * @var $configuration \Lecturio\CloudCopy\Configuration
             */
            $config = $this->get('configuration');
            $config = $config->get();
            $s3client = $this->container->get('s3client');
            $s3client = $s3client->factory();
            $reader = $this->container->get('recursiveReader');

            foreach ($reader->read() as $node) {

                //TODO upload file to amazon
            }

        }

    }
}

$application = new Application();
$application->add(new \CopyFile);
$application->run();