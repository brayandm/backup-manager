<?php

namespace App\Entities\DataSourceDrivers;

use App\Helpers\CommandBuilder;
use App\Interfaces\DataSourceDriverInterface;
use App\Interfaces\CompressionMethodInterface;

class AwsS3Driver implements DataSourceDriverInterface
{
    public $type = 'aws_s3';

    public $bucket;

    public $region;

    public $key;

    public $secret;

    public $endpoint;

    public $path;

    private $dir;

    public function __construct(string $bucket, string $region, string $key, string $secret, ?string $endpoint, ?string $path)
    {
        $this->bucket = $bucket;
        $this->region = $region;
        $this->key = $key;
        $this->secret = $secret;
        $this->endpoint = $endpoint ? $endpoint : "https://s3.$region.amazonaws.com";
        $this->path = $path;
        $this->dir = $this->path ? $this->bucket . '/' . $this->removeSlashes($this->path) : $this->bucket;
    }

    private function removeSlashes(?string $path)
    {
        if ($path !== null && $path !== '') {
            $path = trim($path, '/');
        }
        return $path;
    }

    private function awsCp(string $from, string $to)
    {
        $command = "AWS_ACCESS_KEY_ID={$this->key}";

        $command .= " && AWS_SECRET_ACCESS_KEY={$this->secret}";

        $command .= " && aws s3 cp $from $to --endpoint-url {$this->endpoint} --recursive";

        return $command;
    }

    private function awsRm(string $path)
    {
        $command = "AWS_ACCESS_KEY_ID={$this->key}";

        $command .= " && AWS_SECRET_ACCESS_KEY={$this->secret}";

        $command .= " && aws s3 rm $path --endpoint-url {$this->endpoint} --recursive";

        return $command;
    }

    // FilesystemDriver
    // public function push(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    // {
    //     $command = "rm -r -f $this->contextPath";

    //     $command .= " && mkdir \$(dirname \"$this->contextPath\") -p";

    //     $command .= ' && '.$compressionMethod->decompress("$localWorkDir", $this->contextPath);

    //     $command .= " && rm -r -f $localWorkDir";

    //     return $command;
    // }

    // public function pull(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    // {
    //     $command = "mkdir $localWorkDir -p";

    //     $command .= ' && '.$compressionMethod->compress($this->contextPath, $localWorkDir);

    //     return $command;
    // }

    public function push(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
        $tempDir = CommandBuilder::tmpPathGenerator();

        $command = "mkdir $tempDir -p";

        $command .= ' && '.$compressionMethod->decompress("$localWorkDir", $tempDir);

        $command .= " && ".$this->awsCp($tempDir, "s3://$this->dir");

        $command .= ' && rm -rf '.$localWorkDir;

        $command .= ' && rm -rf '.$tempDir;

        return $command;
    }

    public function pull(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
        $tempDir = CommandBuilder::tmpPathGenerator();

        $command = "mkdir $localWorkDir -p";

        $command .= ' && mkdir '.$tempDir.' -p';

        $command .= " && " . $this->awsCp("s3://$this->dir", $tempDir);

        $command .= ' && '.$compressionMethod->compress($tempDir, $localWorkDir);

        $command .= ' && rm -rf '.$tempDir;

        return $command;
    }

    public function setup()
    {
        $command = 'true';

        return $command;
    }

    public function clean()
    {
        $command = 'true';

        return $command;
    }

    public function dockerContext(bool $dockerContext)
    {
    }
}
