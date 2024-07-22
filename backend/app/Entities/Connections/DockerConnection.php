<?php

namespace App\Entities\Connections;

use App\Interfaces\ConnectionInterface;

class DockerConnection implements ConnectionInterface
{
    public $containerName;

    public function __construct(string $containerName)
    {
        $this->containerName = $containerName;
    }

    private function exec(string $command)
    {
        $command = escapeshellarg($command);

        $command = "docker exec {$this->containerName} {$command}";

        return $command;
    }

    public function run(string $command)
    {
        $command = $this->exec($command);

        return $command;
    }

    public function push(string $localWorkDir, string $externalWorkDir)
    {
        $command = "docker cp {$localWorkDir} {$this->containerName}:{$externalWorkDir}";

        return $command;
    }

    public function pull(string $localWorkDir, string $externalWorkDir)
    {
        $command = "docker cp {$this->containerName}:{$externalWorkDir} {$localWorkDir}";

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

    public function cleanAfterPush(string $localWorkDir, string $externalWorkDir)
    {
        $command = "rm -rf {$localWorkDir}";

        return $command;
    }

    public function cleanAfterPull(string $localWorkDir, string $externalWorkDir)
    {
        $command = $this->exec("rm -rf {$externalWorkDir}");

        return $command;
    }

    public function dockerContext(bool $dockerContext)
    {
    }
}
