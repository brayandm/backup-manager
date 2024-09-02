<?php

namespace App\Interfaces;

interface StorageServerDriverInterface
{
    public function update($storageServerDriver);

    public function push(string $localWorkDir, string $backupPath, string $backupName);

    public function pull(string $localWorkDir, string $backupPath, string $backupName);

    public function delete(string $backupPath, string $backupName);

    public function setup();

    public function clean();

    public function getFreeSpace();

    public function isAvailable();

    public function dockerContext(bool $dockerContext);
}
