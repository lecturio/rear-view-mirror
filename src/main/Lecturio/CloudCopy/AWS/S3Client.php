<?php
namespace Lecturio\CloudCopy\AWS;

use Aws\S3\S3Client as client;

/**
 * Adapter for S3 client.
 * @package CloudCopy\AWS
 */
class S3Client
{
    /**
     * @var client
     */
    private $s3Client;

    function __construct($config)
    {
        $this->s3Client = client::factory(array(
            'key' => $config['aws']['access.key'],
            'secret' => $config['aws']['secret.key']
        ));
    }

    /**
     * @return client
     */
    function factory()
    {
        return $this->s3Client;
    }

}
