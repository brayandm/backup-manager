<?php

namespace App\Entities;

use App\Interfaces\StorageServerDriverInterface;

class StorageServerDriverConfig
{
    public StorageServerDriverInterface $driver;

    public function __construct(StorageServerDriverInterface $driver)
    {
        $this->driver = $driver;
    }
}
