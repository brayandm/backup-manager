<?php

namespace App\Entities\Connections;

use App\Interfaces\ConnectionInterface;

class SshConnection implements ConnectionInterface
{
    private $user;

    private $host;

    private $port;

    private $private_key;

    private $passphrase;

    public function __construct(string $user, string $host, string $port, string $private_key, ?string $passphrase)
    {
        $this->user = $user;
        $this->host = $host;
        $this->port = $port;
        $this->private_key = $private_key;
        $this->passphrase = $passphrase;
    }

    public function Run()
    {

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
