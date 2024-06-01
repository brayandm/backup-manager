<?php

namespace App\Entities\Connections;

use App\Interfaces\ConnectionInterface;

class DockerConnection implements ConnectionInterface
{
    private $container_name;

    public function __construct(string $container_name)
    {
        $this->container_name = $container_name;

    }

    public function Run()
    {
    }

    public function Push()
    {
    }

    public function Pull()
    {
    }

    public function Setup()
    {
    }

    public function Clean()
    {
    }
}
