<?php

namespace App\Interfaces;

interface DriverInterface
{
    public function Push(string $localWorkDir);

    public function Pull(string $localWorkDir);

    public function Setup(string $localWorkDir);

    public function Clean(string $localWorkDir);

    public function DockerContext();
}
