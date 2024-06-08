<?php

namespace App\Entities\Methods\CompressionMethods;

use App\Interfaces\CompressionMethodInterface;

class TarCompressionMethod implements CompressionMethodInterface
{

    public function __construct()
    {
    }

    public function compress(string $source, string $destination)
    {
    }

    public function setup()
    {
    }

    public function clean()
    {
    }
}
