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

    public function __construct(string $bucket, string $region, string $key, string $secret, ?string $endpoint, ?string $path)
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
        $command = "aws s3 cp $localWorkDir s3://$this->bucket/$this->path/$backupName --recursive";

        $command .= ' && rm -r -f '.$localWorkDir;

        return $command;
    }

    public function pull(string $localWorkDir, string $backupName)
    {
        $command = "mkdir $localWorkDir -p";

        $command .= " && aws s3 cp s3://$this->bucket/$this->path/$backupName $localWorkDir --recursive";

        return $command;
    }

    public function delete(string $backupName)
    {
        $command = "aws s3 rm s3://$this->bucket/$this->path/$backupName --recursive";

        return $command;
    }

    public function setup()
    {
        $command = "true";

        return $command;
    }

    public function clean()
    {
        $command = "true";

        return $command;
    }

    public function getFreeSpace()
    {
        $command = "true";

        return $command;
    }

    public function dockerContext(bool $dockerContext)
    {
    }
}
