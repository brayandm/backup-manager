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

    private function Ssh($command)
    {
        $command = escapeshellarg($command);

        return "ssh -p {$this->port} -i {$this->privateKeyPath} -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -o LogLevel=ERROR {$this->user}@{$this->host} {$command}";
    }

    private function Scp($from, $to)
    {
        return "scp -r -P {$this->port} -i {$this->privateKeyPath} -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -o LogLevel=ERROR {$from} {$to}";
    }

    public function Run(string $command)
    {
        $command = $this->Ssh($command);

        return $command;
    }

    public function Push(string $localWorkDir, string $externalWorkDir)
    {
        $command = $this->Ssh("mkdir -p {$externalWorkDir}");

        $command .= ' && '.$this->Scp("{$localWorkDir}/*", "{$this->user}@{$this->host}:{$externalWorkDir}");

        $command .= ' && rm -r -f '.$localWorkDir;

        return $command;
    }

    public function Pull(string $localWorkDir, string $externalWorkDir)
    {
        $command = "mkdir -p {$localWorkDir}";

        $command .= ' && '.$this->Scp("{$this->user}@{$this->host}:{$externalWorkDir}/*", "{$localWorkDir}");

        $command .= ' && '.$this->Ssh("rm -r -f {$externalWorkDir}");

        return $command;
    }

    public function Setup()
    {
        $this->privateKeyPath = '/tmp/backup-manager/.ssh/'.Str::uuid();

        $command = 'mkdir -p /tmp/backup-manager/.ssh';
        $command .= " && echo \"{$this->privateKey}\" > {$this->privateKeyPath}";
        $command .= " && chmod 600 {$this->privateKeyPath}";

        return $command;
    }

    public function Clean()
    {
        $command = "rm -f {$this->privateKeyPath}";

        return $command;
    }

    public function DockerContext()
    {
        if ($this->host === 'localhost' || $this->host === '127.0.0.1') {
            $this->host = 'host.docker.internal';
        }
    }
}
