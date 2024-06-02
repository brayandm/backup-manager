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

    public function Run()
    {
    }

    public function Push(string $filepath)
    {
    }

    public function Pull(string $filepath)
    {
    }

    public function Setup()
    {
    }

    public function Clean()
    {
    }
}
