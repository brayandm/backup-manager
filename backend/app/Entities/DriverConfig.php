<?php

namespace App\Entities;

use App\Interfaces\DriverInterface;

class DriverConfig
{
    public DriverInterface $driver;

    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }
}
