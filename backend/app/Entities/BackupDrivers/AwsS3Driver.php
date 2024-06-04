<?php

namespace App\Entities\BackupDrivers;

use App\Interfaces\BackupDriverInterface;

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

    public function Push(string $localWorkDir)
    {
    }

    public function Pull(string $localWorkDir)
    {
    }

    public function Setup()
    {
    }

    public function Clean()
    {
    }

    public function dockerContext()
    {
    }
}
