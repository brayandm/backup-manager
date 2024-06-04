<?php

namespace App\Interfaces;

interface ConnectionInterface
{
    public function Run(string $command);

    public function Push(string $localWorkDir, string $externalWorkDir);

    public function Pull(string $localWorkDir, string $externalWorkDir);

    public function Setup();

    public function clean();

    public function dockerContext();
}
