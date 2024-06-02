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
        $command = "cp $filepath $this->path";

        return $command;
    }

    public function Pull(string $filepath)
    {
        $command = "cp $this->path $filepath";

        return $command;
    }

    public function Setup()
    {
    }

    public function Clean()
    {
    }
}
