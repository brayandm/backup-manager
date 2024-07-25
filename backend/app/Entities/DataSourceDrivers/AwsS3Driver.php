<?php

namespace App\Entities\DataSourceDrivers;

use App\Interfaces\DataSourceDriverInterface;
use App\Interfaces\CompressionMethodInterface;

class AwsS3Driver implements DataSourceDriverInterface
{
    public $bucket;

    public $region;

    public $key;

    public $secret;

    public $endpoint;

    public $path;

    public function __construct(string $bucket, string $region, string $key, string $secret, ?string $endpoint, ?string $path)
    {
        $this->bucket = $bucket;
        $this->region = $region;
        $this->key = $key;
        $this->secret = $secret;
        $this->endpoint = $endpoint;
        $this->path = $path;
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
