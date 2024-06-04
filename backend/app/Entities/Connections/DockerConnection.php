<?php

namespace App\Entities\Connections;

use App\Interfaces\ConnectionInterface;

class DockerConnection implements ConnectionInterface
{
    public $containerName;

    public function __construct(string $containerName)
    {
        $this->containerName = $containerName;
    }

    public function Run(string $command)
    {
    }

    public function push(string $localWorkDir, string $externalWorkDir)
    {
    }

    public function pull(string $localWorkDir, string $externalWorkDir)
    {
    }

    public function setup()
    {
    }

    public function clean()
    {
    }

    public function dockerContext()
    {
    }
}
