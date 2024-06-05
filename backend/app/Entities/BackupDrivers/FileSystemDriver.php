<?php

namespace App\Entities\BackupDrivers;

use App\Interfaces\BackupDriverInterface;

class FileSystemDriver implements BackupDriverInterface
{
    public $path;
    private $dockerContext;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function push(string $localWorkDir)
    {
        if($this->dockerContext)
        {
            $command = "cp -r $localWorkDir/* /host$this->path";
        }
        else
        {
            $command = "cp -r $localWorkDir/* $this->path";
        }

        $command .= ' && rm -r -f '.$localWorkDir;

        return $command;
    }

    public function pull(string $localWorkDir)
    {
        if($this->dockerContext)
        {
            $command = "mkdir $localWorkDir -p && cp -r /host$this->path $localWorkDir";
        }
        else
        {
            $command = "mkdir $localWorkDir -p && cp -r $this->path $localWorkDir";
        }

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
        $this->dockerContext = $dockerContext;
    }
}
