<?php

namespace App\Entities;

use App\Interfaces\CompressionMethodInterface;

class CompressionMethodConfig
{
    public CompressionMethodInterface $compressionMethod;

    public function __construct(CompressionMethodInterface $compressionMethod)
    {
        $this->compressionMethod = $compressionMethod;
    }
}
