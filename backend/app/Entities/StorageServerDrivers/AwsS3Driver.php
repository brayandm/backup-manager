<?php

namespace App\Entities\StorageServerDrivers;

use App\Interfaces\StorageServerDriverInterface;

class AwsS3Driver implements StorageServerDriverInterface
{
    public $bucket;

    public $region;

    public $key;

    public $secret;

    public $endpoint;

    public $path;

    public function __construct(string $bucket, string $region, string $key, string $secret, string $endpoint, string $path)
    {
        $this->bucket = $bucket;
        $this->region = $region;
        $this->key = $key;
        $this->secret = $secret;
        $this->endpoint = $endpoint;
        $this->path = $path;
    }

    public function push(string $localWorkDir, string $backupName)
    {
    }

    public function pull(string $localWorkDir, string $backupName)
    {
    }

    public function delete(string $backupName)
    {
    }

    public function setup()
    {
    }

    public function clean()
    {
    }

    public function getFreeSpace()
    {
    }

    public function dockerContext(bool $dockerContext)
    {
    }
}
