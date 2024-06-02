<?php

namespace App\Entities\Connections;

use App\Interfaces\ConnectionInterface;
use Illuminate\Support\Str;

class SshConnection implements ConnectionInterface
{
    public $user;

    public $host;

    public $port;

    public $privateKey;

    public $passphrase;

    private $privateKeyPath;

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
        $command = "ssh -p {$this->port} -i {$this->privateKeyPath} {$this->user}@{$this->host} '{$command}'";

        return $command;
    }

    public function Push(string $filepath)
    {
        $command = "scp -P {$this->port} -i {$this->privateKeyPath} {$filepath} {$this->user}@{$this->host}:{$filepath}";

        return $command;
    }

    public function Pull(string $filepath)
    {
        $command = "scp -P {$this->port} -i {$this->privateKeyPath} {$this->user}@{$this->host}:{$filepath} {$filepath}";

        return $command;
    }

    public function Setup()
    {
        $this->privateKeyPath = '/tmp/backup-manager/' . Str::uuid();

        $command = "echo '{$this->privateKey}' > {$this->privateKeyPath}";

        return $command;
    }

    public function Clean()
    {
        $command = "rm -f {$this->privateKeyPath}";

        return $command;
    }
}
