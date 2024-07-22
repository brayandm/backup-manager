<?php

namespace App\Entities\DataSourceDrivers;

use App\Interfaces\DataSourceDriverInterface;
use App\Interfaces\CompressionMethodInterface;

class MysqlDriver implements DataSourceDriverInterface
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
        $command = $compressionMethod->decompress($localWorkDir, $localWorkDir.'/dump.sql');

        $command = 'mysql -h '.$this->host.' -P '.$this->port.' -u '.$this->user.' -p'.$this->password.' '.$this->database.' < '.$localWorkDir.'/dump.sql';

        $command .= ' && rm -f '.$localWorkDir.'/dump.sql';

        return $command;
    }

    public function pull(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
        $command = "mkdir $localWorkDir -p";

        $command .= ' && mysqldump -h '.$this->host.' -P '.$this->port.' -u '.$this->user.' -p'.$this->password.' '.$this->database.' > '.$localWorkDir.'/dump.sql';

        $command .= ' && '.$compressionMethod->compress($localWorkDir.'/dump.sql', $localWorkDir);

        $command .= ' && rm -f '.$localWorkDir.'/dump.sql';

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
        if ($this->host === 'localhost' || $this->host === '127.0.0.1') {
            $this->host = 'host.docker.internal';
        }
    }
}
