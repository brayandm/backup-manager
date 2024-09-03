<?php

namespace App\Entities\StorageServerDrivers;

use App\Interfaces\StorageServerDriverInterface;

class AwsS3Driver implements StorageServerDriverInterface
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
        $this->dir = $this->path ? $this->bucket.'/'.$this->removeSlashes($this->path) : $this->bucket;
    }

    public function update($storageServerDriver)
    {
        $this->bucket = $storageServerDriver->bucket;
        $this->region = $storageServerDriver->region;
        $this->key = $storageServerDriver->key;
        $this->secret = $storageServerDriver->secret;
        $this->endpoint = $storageServerDriver->endpoint;
        $this->dir = $this->path ? $this->bucket.'/'.$this->removeSlashes($this->path) : $this->bucket;
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

        $command .= " && aws s3 cp \"$from\" \"$to\" --endpoint-url {$this->endpoint} --recursive";

        return $command;
    }

    private function awsRm(string $path)
    {
        $command = "AWS_ACCESS_KEY_ID={$this->key}";

        $command .= " && AWS_SECRET_ACCESS_KEY={$this->secret}";

        $command .= " && aws s3 rm \"$path\" --endpoint-url {$this->endpoint} --recursive";

        return $command;
    }

    public function push(string $localWorkDir, string $backupPath, string $backupName)
    {
        $command = $this->awsCp($localWorkDir, "s3://$this->dir/$backupPath/$backupName");

        $command .= ' && rm -r -f "'.$localWorkDir.'"';

        return $command;
    }

    public function pull(string $localWorkDir, string $backupPath, string $backupName)
    {
        $command = "mkdir \"$localWorkDir\" -p";

        $command .= ' && '.$this->awsCp("s3://$this->dir/$backupPath/$backupName", $localWorkDir);

        return $command;
    }

    public function delete(string $backupPath, string $backupName)
    {
        $command = $this->awsRm("s3://$this->dir/$backupPath/$backupName");

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

    public function getFreeSpace()
    {
        $command = 'echo 9223372036854775807';

        return $command;
    }

    public function isAvailable()
    {
        $command = "AWS_ACCESS_KEY_ID={$this->key}";

        $command .= " && AWS_SECRET_ACCESS_KEY={$this->secret}";

        $command .= " && aws s3 ls --endpoint-url {$this->endpoint} > /dev/null 2>&1 && echo 'true'";

        return $command;
    }

    public function hasInfiniteSpace()
    {
        return true;
    }

    public function dockerContext(bool $dockerContext)
    {
    }
}
