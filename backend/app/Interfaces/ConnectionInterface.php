<?php

namespace App\Interfaces;

interface ConnectionInterface
{
    public function Run(string $command);

    public function push(string $localWorkDir, string $externalWorkDir);

    public function pull(string $localWorkDir, string $externalWorkDir);

    public function setup();

    public function clean();

    public function dockerContext();
}
