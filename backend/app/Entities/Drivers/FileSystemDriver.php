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
    }

    public function Pull(string $filepath)
    {
    }

    public function Setup()
    {
    }

    public function Clean()
    {
    }
}
