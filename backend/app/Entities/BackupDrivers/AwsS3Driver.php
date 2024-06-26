<?php

namespace App\Entities\BackupDrivers;

use App\Interfaces\BackupDriverInterface;
use App\Interfaces\CompressionMethodInterface;

class AwsS3Driver implements BackupDriverInterface
{
    public $bucket;

    public $region;

    public $key;

    public $secret;

    public function __construct(string $bucket, string $region, string $key, string $secret)
    {
        $this->bucket = $bucket;
        $this->region = $region;
        $this->key = $key;
        $this->secret = $secret;
    }

    public function push(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
    }

    public function pull(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
    }

    public function setup()
    {
    }

    public function clean()
    {
    }

    public function dockerContext(bool $dockerContext)
    {
    }
}
