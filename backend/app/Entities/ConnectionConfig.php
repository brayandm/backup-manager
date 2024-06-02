<?php

namespace App\Entities;

class ConnectionConfig
{
    public array $connections;

    public function __construct(array $connections)
    {
        $this->connections = $connections;
    }
}
