<?php

namespace App\Interfaces;

interface DriverInterface
{
    public function Push(string $filepath);

    public function Pull(string $filepath);

    public function Setup();

    public function Clean();

    public function DockerContext();
}
