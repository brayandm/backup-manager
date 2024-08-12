<?php

namespace App\Interfaces;

interface DataSourceDriverInterface
{
    public function push(string $localWorkDir, CompressionMethodInterface $compressionMethod);

    public function pull(string $localWorkDir, CompressionMethodInterface $compressionMethod);

    public function setup();

    public function clean();

    public function isAvailable();

    public function dockerContext(bool $dockerContext);
}
