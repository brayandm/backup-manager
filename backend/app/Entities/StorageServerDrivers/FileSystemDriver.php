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

    public function Push(string $localWorkDir)
    {
        $command = "cp -r $localWorkDir/* $this->path";

        $command .= ' && rm -r -f '.$localWorkDir;

        return $command;
    }

    public function Pull(string $localWorkDir)
    {
        $command = "mkdir $localWorkDir -p && cp -r $this->path $localWorkDir";

        return $command;
    }

    public function Setup()
    {
        $command = 'true';

        return $command;
    }

    public function Clean()
    {
        $command = 'true';

        return $command;
    }

    public function dockerContext()
    {
        $this->path = '/host'.$this->path;
    }
}
