<?php

namespace App\Entities\Drivers;

use App\Interfaces\DriverInterface;

class FileSystemDriver implements DriverInterface
{
    public $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function Push(string $filepath)
    {
        $command = "tar -xzf $filepath $this->path";

        return $command;
    }

    public function Pull(string $filepath)
    {
        $command = "tar -czf $filepath $this->path";

        return $command;

    }

    public function Setup()
    {
        $command = 'command -v tar >/dev/null 2>&1 || { sudo apt-get update -qq && sudo apt-get install -y -qq tar; }';

        return $command;
    }

    public function Clean()
    {
    }
}
