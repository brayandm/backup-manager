<?php

namespace App\Interfaces;

interface DriverInterface
{
    public function Push(string $localWorkDir);

    public function Pull(string $localWorkDir);

    public function Setup();

    public function Clean();

    public function DockerContext();
}
