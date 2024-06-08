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
        $command = "cp -r $source $destination";

        return $command;
    }

    public function decompress(string $source, string $destination)
    {
        $command = "cp -r $source/* $destination";

        return $command;
    }

    public function setup()
    {
    }

    public function clean()
    {
    }
}
