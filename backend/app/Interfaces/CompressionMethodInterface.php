<?php

namespace App\Interfaces;

interface CompressionMethodInterface
{
    public function compress(string $source, string $destination);

    public function decompress(string $source, string $destination);

    public function setup();

    public function clean();
}
