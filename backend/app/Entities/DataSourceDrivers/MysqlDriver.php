<?php

namespace App\Entities\DataSourceDrivers;

use App\Helpers\CommandBuilder;
use App\Interfaces\CompressionMethodInterface;
use App\Interfaces\DataSourceDriverInterface;

class MysqlDriver implements DataSourceDriverInterface
{
    public $type = 'mysql';

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

        $command .= ' && '.$compressionMethod->decompress("$localWorkDir", $tempDir.'/dump.sql');

        $command .= ' && mysql -h '.$this->contextHost.' -P '.$this->port.' -u '.$this->user.' -p\''.$this->password.'\' -e "DROP DATABASE '.$this->database.'; CREATE DATABASE '.$this->database.';" > /dev/null 2>&1';

        $command .= ' && mysql -h '.$this->contextHost.' -P '.$this->port.' -u '.$this->user.' -p\''.$this->password.'\' '.$this->database.' < '.$tempDir.'/dump.sql';

        $command .= ' && rm -rf '.$localWorkDir;

        $command .= ' && rm -rf '.$tempDir;

        return $command;
    }

    public function pull(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
        $tempDir = CommandBuilder::tmpPathGenerator();

        $command = "mkdir $localWorkDir -p";

        $command .= ' && mkdir '.$tempDir.' -p';

        $command .= ' && mysqldump -h '.$this->contextHost.' -P '.$this->port.' -u '.$this->user.' -p\''.$this->password.'\' '.$this->database.' > '.$tempDir.'/dump.sql';

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

    public function isAvailable()
    {
        $command = "mysql -h $this->contextHost -P $this->port -u $this->user -p'$this->password' -e 'SHOW DATABASES;' > /dev/null 2>&1 && echo 'true'";

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
