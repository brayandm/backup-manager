<?php

namespace App\Entities\DataSourceDrivers;

use App\Helpers\CommandBuilder;
use App\Interfaces\DataSourceDriverInterface;
use App\Interfaces\CompressionMethodInterface;

class PostgreSqlDriver implements DataSourceDriverInterface
{
    public $type = 'pgsql';

    public $host;

    public $port;

    public $user;

    public $password;

    public $database;

    private $contextHost;

    public function __construct(string $host, string $port, string $user, string $password, string $database)
    {
        $this->host = $host;
        $this->contextHost = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }

    public function push(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
        $tempDir = CommandBuilder::tmpPathGenerator();

        $command = "mkdir $tempDir -p";

        $command .= ' && '.$compressionMethod->decompress("$localWorkDir", $tempDir."/dump.sql");

        $command .= ' && PGPASSWORD='.$this->password.' psql -h '.$this->contextHost.' -p '.$this->port.' -U '.$this->user.' -d '.$this->database.' -f '.$tempDir.'/dump.sql';

        $command .= ' && rm -rf '.$localWorkDir;

        $command .= ' && rm -rf '.$tempDir;

        return $command;
    }

    public function pull(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
        $tempDir = CommandBuilder::tmpPathGenerator();

        $command = "mkdir $localWorkDir -p";

        $command .= ' && mkdir '.$tempDir.' -p';

        $command .= ' && PGPASSWORD='.$this->password.' pg_dump -h '.$this->contextHost.' -p '.$this->port.' -U '.$this->user.' -d '.$this->database.' > '.$tempDir.'/dump.sql';

        $command .= ' && '.$compressionMethod->compress($tempDir.'/dump.sql', $localWorkDir);

        $command .= ' && rm -rf '.$tempDir;

        return $command;
    }

    public function setup()
    {
        $command = 'true';

        return $command;
    }

    public function clean()
    {
        $command = 'true';

        return $command;
    }

    public function dockerContext(bool $dockerContext)
    {
        if ($dockerContext) {
            if ($this->host === 'localhost' || $this->host === '127.0.0.1') {
                $this->contextHost = 'host.docker.internal';
            }
        } else {
            $this->contextHost = $this->host;
        }
    }
}
