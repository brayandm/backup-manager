<?php

namespace App\Entities\BackupDrivers;

use App\Interfaces\BackupDriverInterface;

class FileSystemDriver implements BackupDriverInterface
{
    public $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function Push(string $localWorkDir)
    {
        $command = "cp -r $localWorkDir/* $this->path";

        $command .= ' && rm -r -f '.$localWorkDir;

        return $command;
    }

    public function pull(string $localWorkDir)
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

    public function dockerContext()
    {
        $this->path = '/host'.$this->path;
    }
}
