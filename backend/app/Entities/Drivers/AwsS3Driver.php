<?php

namespace App\Entities\Drivers;

use App\Interfaces\DriverInterface;

class AwsS3Driver implements DriverInterface
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

    public function Setup(string $localWorkDir)
    {
    }

    public function Clean(string $localWorkDir)
    {
    }

    public function DockerContext()
    {
    }
}
