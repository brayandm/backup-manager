<?php

namespace App\Entities\StorageServerDrivers;

use App\Interfaces\StorageServerDriverInterface;

class FileSystemDriver implements StorageServerDriverInterface
{
    public $path;

    private $contextPath;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->contextPath = $path;
    }

    public function push(string $localWorkDir, string $backupName)
    {
        $command = "mkdir $this->contextPath$backupName";

        $command .= " && cp -r $localWorkDir/* $this->contextPath$backupName/";

        $command .= ' && rm -r -f '.$localWorkDir;

        return $command;
    }

    public function pull(string $localWorkDir, string $backupName)
    {
        $command = "mkdir $localWorkDir -p && cp -r $this->contextPath$backupName/* $localWorkDir";

        return $command;
    }

    public function delete(string $backupName)
    {
        $command = "rm -r -f $this->contextPath$backupName";

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
        if ($dockerContext) {
            $this->contextPath = '/host'.$this->path;
        } else {
            $this->contextPath = $this->path;
        }
    }
}
