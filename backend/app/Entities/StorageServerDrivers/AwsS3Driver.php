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

    private $dir;

    public function __construct(string $bucket, string $region, string $key, string $secret, ?string $endpoint, ?string $path)
    {
        $this->bucket = $bucket;
        $this->region = $region;
        $this->key = $key;
        $this->secret = $secret;
        $this->endpoint = $endpoint ? $endpoint : "https://s3.$region.amazonaws.com";
        $this->path = $this->removeSlashes($path);
        $this->dir = $this->path ? $this->bucket . '/' . $this->path : $this->bucket;
    }

    private function removeSlashes(?string $path)
    {
        if ($path !== null && $path !== '') {
            $path = trim($path, '/');
        }
        return $path;
    }

    public function push(string $localWorkDir, string $backupName)
    {
        $command = "AWS_ACCESS_KEY_ID={$this->key}";

        $command .= " && AWS_SECRET_ACCESS_KEY={$this->secret}";

        $command .= " && aws s3 cp $localWorkDir s3://$this->dir/$backupName --endpoint-url {$this->endpoint} --recursive";

        $command .= ' && rm -r -f ' . $localWorkDir;

        return $command;
    }

    public function pull(string $localWorkDir, string $backupName)
    {
        $command = "mkdir $localWorkDir -p";

        $command .= " && AWS_ACCESS_KEY_ID={$this->key}";

        $command .= " && AWS_SECRET_ACCESS_KEY={$this->secret}";

        $command .= " && aws s3 cp s3://$this->dir/$backupName $localWorkDir --endpoint-url {$this->endpoint} --recursive";

        return $command;
    }

    public function delete(string $backupName)
    {
        $command = "AWS_ACCESS_KEY_ID={$this->key}";

        $command .= " && AWS_SECRET_ACCESS_KEY={$this->secret}";

        $command .= " && aws s3 rm s3://$this->dir/$backupName --endpoint-url {$this->endpoint} --recursive";

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
