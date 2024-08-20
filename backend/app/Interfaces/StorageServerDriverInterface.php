<?php

namespace App\Interfaces;

interface StorageServerDriverInterface
{
    public function push(string $localWorkDir, string $backupConfigurationName, string $dataSourceName, string $backupName);

    public function pull(string $localWorkDir, string $backupConfigurationName, string $dataSourceName, string $backupName);

    public function delete(string $backupName);

    public function setup();

    public function clean();

    public function getFreeSpace();

    public function isAvailable();

    public function dockerContext(bool $dockerContext);
}
