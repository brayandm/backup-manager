<?php

namespace App\Interfaces;

interface StorageServerDriverInterface
{
    public function push(string $localWorkDir, string $backupName);

    public function pull(string $localWorkDir, string $backupName);

    public function setup();

    public function clean();

    public function dockerContext(bool $dockerContext);
}
