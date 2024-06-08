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
        $command = "tar -xzf $source/data.tar.gz -C \$(dirname \"$destination\") ";

        return $command;
    }

    public function setup()
    {
        $command = 'true';

        return $command;
    }

    public function clean()
    {
        $command = 'true';

        return $command;
    }
}
