<?php

namespace App\Entities\BackupDrivers;

use App\Interfaces\BackupDriverInterface;
use App\Interfaces\CompressionMethodInterface;

class MysqlDriver implements BackupDriverInterface
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

    public function push(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
    }

    public function pull(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
    }

    public function setup()
    {
    }

    public function clean()
    {
    }

    public function dockerContext(bool $dockerContext)
    {
        if ($this->host === 'localhost' || $this->host === '127.0.0.1') {
            $this->host = 'host.docker.internal';
        }
    }
}
