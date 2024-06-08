<?php

namespace App\Interfaces;

interface BackupDriverInterface
{
    public function push(string $localWorkDir, CompressionMethodInterface $compressionMethod);

    public function pull(string $localWorkDir, CompressionMethodInterface $compressionMethod);

    public function setup();

    public function clean();

    public function dockerContext(bool $dockerContext);
}
