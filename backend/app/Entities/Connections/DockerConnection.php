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

        $command = "docker exec \"{$this->containerName}\" sh -c {$command}";

        return $command;
    }

    private function cp(string $from, string $to)
    {
        $command = "docker cp \"{$from}\" \"{$to}\"";

        return $command;
    }

    public function run(string $command)
    {
        $command = $this->exec($command);

        return $command;
    }

    public function push(string $localWorkDir, string $externalWorkDir)
    {
        $command = $this->exec("mkdir -p \"{$externalWorkDir}\"");

        $command .= ' && '.$this->cp("{$localWorkDir}/.", "{$this->containerName}:{$externalWorkDir}");

        return $command;
    }

    public function pull(string $localWorkDir, string $externalWorkDir)
    {
        $command = "mkdir -p \"{$localWorkDir}\"";

        $command .= ' && '.$this->cp("{$this->containerName}:{$externalWorkDir}/.", "{$localWorkDir}");

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
        $command = "rm -rf \"{$localWorkDir}\"";

        return $command;
    }

    public function cleanAfterPull(string $localWorkDir, string $externalWorkDir)
    {
        $command = $this->exec("rm -rf \"{$externalWorkDir}\"");

        return $command;
    }

    public function dockerContext(bool $dockerContext)
    {
    }
}
