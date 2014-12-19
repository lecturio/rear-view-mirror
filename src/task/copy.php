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
    const SSH = 'ssh';
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
            ->addOption(
                self::SSH,
                null,
                InputOption::VALUE_NONE,
                'Copy files to different ssh location'
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
            $config = $this->container->get('configuration');
            $config = $config->get();
            $s3client = $this->container->get('s3client');
            $s3client = $s3client->factory();
            $reader = $this->container->get('recursiveReader');

            foreach ($reader->read() as $node) {
                //TODO copy to amazon
            }

        }

        if ($input->getOption(self::SSH)) {
            /**
             * @var $reader \Lecturio\CloudCopy\Filesystem\RecursiveReader
             * @var $s3client \Aws\S3\S3Client
             * @var $configuration \Lecturio\CloudCopy\Configuration
             */
            $config = $this->container->get('configuration');
            $config = $config->get();
            $reader = $this->container->get('recursiveReader');

            foreach ($reader->read() as $node) {
                $destinationPath = preg_replace('/' . str_replace('/', '\/',
                        $config['filesystem']['general.node']) . '/', '', $node);
                $destinationPath = sprintf('%s%s', $config['ssh']['backup.location'], dirname($destinationPath));

                $createPath = sprintf('ssh -p %s -i %s -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no \
                    -o LogLevel=quiet %s "mkdir -p %s"',
                    $config['ssh']['port'], $config['ssh']['key'],
                    $config['ssh']['server'], $destinationPath);

                system($createPath, $statusCode);

                if ($statusCode !== 0) {
                    $output->writeln('can\'t create remote destination' . $statusCode);
                }

                $cmd = sprintf('rsync -aqzr --chmod=u=rw,g=r,o=r %s -e "ssh -p %s \
                    -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -o LogLevel=quiet -i %s" %s:%s',
                    $node,
                    $config['ssh']['port'],
                    $config['ssh']['key'],
                    $config['ssh']['server'],
                    $destinationPath);
                system($cmd, $statusCode);

                if ($statusCode !== 0) {
                    $output->writeln('can\'t copy ' . $node);
                    continue;
                }

            }
        }

    }
}

$application = new Application();
$application->add(new \CopyFile);
$application->run();