<?php

namespace App\Interfaces;

interface StorageServerDriverInterface
{
    public function Push(string $localWorkDir);

    public function Pull(string $localWorkDir);

    public function setup();

    public function clean();

    public function dockerContext();
}
