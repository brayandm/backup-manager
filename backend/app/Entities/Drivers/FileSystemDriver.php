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

    function getLast(string $path)
    {
        $trimmedPath = rtrim($path, '/');

        $last = basename($trimmedPath);

        return $last;
    }

    function getBase(string $path)
    {
        $trimmedPath = rtrim($path, '/');

        $basePath = dirname($trimmedPath);

        return $basePath;
    }

    public function Push(string $filepath)
    {
        $command = "tar -xzf $filepath -C $this->path";

        return $command;
    }

    public function Pull(string $filepath)
    {
        $basePath = $this->getBase($this->path);

        $last = $this->getLast($this->path);

        $command = "tar -czf $filepath -C $basePath $last";

        return $command;

    }

    public function Setup()
    {
        $command = 'command -v tar >/dev/null 2>&1 || { sudo apt-get update -qq && sudo apt-get install -y -qq tar; }';

        return $command;
    }

    public function Clean()
    {
        $command = 'true';

        return $command;
    }

    public function DockerContext()
    {
        $this->path = '/host'.$this->path;
    }
}
