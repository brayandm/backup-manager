<?php

namespace App\Entities\Drivers;

use App\Interfaces\DriverInterface;

class MysqlDriver implements DriverInterface
{
    public $host;

    public $port;

    public $user;

    public $password;

    public $database;

    public function __construct(string $host, string $port, string $user, string $password, string $database)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }

    public function Push()
    {
    }

    public function Pull()
    {
    }

    public function Setup()
    {
    }

    public function Clean()
    {
    }
}
