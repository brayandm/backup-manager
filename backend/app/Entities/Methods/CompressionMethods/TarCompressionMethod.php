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
        $command = "tar -czf $destination/data.tar.gz -C \$(dirname \"$source\") \$(basename \"$source\") > /dev/null 2>&1";

        return $command;
    }

    public function decompress(string $source, string $destination)
    {
        $command = 'tar -xzf '.$source."data.tar.gz -C $destination > /dev/null 2>&1";

        return $command;
    }

    public function setup()
    {
    }

    public function clean()
    {
    }
}
