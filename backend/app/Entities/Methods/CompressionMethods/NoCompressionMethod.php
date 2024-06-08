<?php

namespace App\Entities\Methods\CompressionMethods;

use App\Interfaces\CompressionMethodInterface;

class NoCompressionMethod implements CompressionMethodInterface
{
    public function __construct()
    {
    }

    public function compress(string $source, string $destination)
    {
    }

    public function decompress(string $source, string $destination)
    {
    }

    public function setup()
    {
    }

    public function clean()
    {
    }
}
