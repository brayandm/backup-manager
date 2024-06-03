<?php

namespace App\Entities;

use App\Interfaces\BackupDriverInterface;

class BackupDriverConfig
{
    public BackupDriverInterface $driver;

    public function __construct(BackupDriverInterface $driver)
    {
        $this->driver = $driver;
    }
}
