<?php

namespace App\Entities\Connections;

use App\Interfaces\ConnectionInterface;

class SshConnection implements ConnectionInterface
{
    public $user;

    public $host;

    public $port;

    public $privateKey;

    public $passphrase;

    public function __construct(string $user, string $host, string $port, string $privateKey, ?string $passphrase)
    {
        $this->user = $user;
        $this->host = $host;
        $this->port = $port;
        $this->privateKey = $privateKey;
        $this->passphrase = $passphrase;
    }

    public function Run(string $command)
    {
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
