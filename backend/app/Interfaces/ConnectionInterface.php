<?php

namespace App\Interfaces;

interface ConnectionInterface
{
    public function run(string $command);

    public function push(string $localWorkDir, string $externalWorkDir);

    public function pull(string $localWorkDir, string $externalWorkDir);

    public function setup();

    public function clean();

    public function cleanAfterPush(string $localWorkDir, string $externalWorkDir);

    public function cleanAfterPull(string $localWorkDir, string $externalWorkDir);

    public function dockerContext(bool $dockerContext);
}
