<?php

namespace App\Entities\StorageServerDrivers;

use App\Interfaces\StorageServerDriverInterface;

class FileSystemDriver implements StorageServerDriverInterface
{
    public $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function push(string $localWorkDir, string $backupName)
    {
        $command = "mkdir $this->path$backupName";

        $command .= " && cp -r $localWorkDir/* $this->path$backupName/";

        $command .= ' && rm -r -f '.$localWorkDir;

        return $command;
    }

    public function pull(string $localWorkDir, string $backupName)
    {
        $command = "mkdir $localWorkDir -p && cp -r $this->path $localWorkDir";

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
        $this->path = '/host'.$this->path;
    }
}
