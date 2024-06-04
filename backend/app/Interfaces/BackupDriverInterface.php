<?php

namespace App\Interfaces;

interface BackupDriverInterface
{
    public function Push(string $localWorkDir);

    public function pull(string $localWorkDir);

    public function setup();

    public function clean();

    public function dockerContext();
}
