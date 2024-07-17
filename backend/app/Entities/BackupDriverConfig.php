<?php

namespace App\Entities;

use App\Interfaces\DataSourceDriverInterface;

class DataSourceDriverConfig
{
    public DataSourceDriverInterface $driver;

    public function __construct(DataSourceDriverInterface $driver)
    {
        $this->driver = $driver;
    }
}
