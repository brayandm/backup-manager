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

    private $filepath;

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
        $command = "ssh -p {$this->port} -i {$this->privateKeyPath} -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -o LogLevel=ERROR {$this->user}@{$this->host} '{$command}'";

        return $command;
    }

    public function Push(string $filepath)
    {
        $this->filepath = $filepath;

        $command = "scp -P {$this->port} -i {$this->privateKeyPath} -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -o LogLevel=ERROR {$filepath} {$this->user}@{$this->host}:{$filepath}";

        return $command;
    }

    public function Pull(string $filepath)
    {
        $this->filepath = $filepath;

        $command = "scp -P {$this->port} -i {$this->privateKeyPath} -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -o LogLevel=ERROR {$this->user}@{$this->host}:{$filepath} {$filepath}";

        return $command;
    }

    public function Setup()
    {
        $this->privateKeyPath = '/tmp/backup-manager/.ssh/'.Str::uuid();

        $command = "mkdir -p /tmp/backup-manager/backups && mkdir -p /tmp/backup-manager/.ssh && echo \"{$this->privateKey}\" > {$this->privateKeyPath} && chmod 600 {$this->privateKeyPath}";

        return $command;
    }

    public function Clean()
    {
        $command = "ssh -p {$this->port} -i {$this->privateKeyPath} -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -o LogLevel=ERROR {$this->user}@{$this->host} 'rm -f {$this->filepath}' && rm -f {$this->privateKeyPath}";

        return $command;
    }

    public function DockerContext()
    {
        if ($this->host === 'localhost' || $this->host === '127.0.0.1') {
            $this->host = 'host.docker.internal';
        }
    }
}
