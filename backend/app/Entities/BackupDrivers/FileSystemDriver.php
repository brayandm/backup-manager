<?php

namespace App\Entities\BackupDrivers;

use App\Interfaces\BackupDriverInterface;
use App\Interfaces\CompressionMethodInterface;

class FileSystemDriver implements BackupDriverInterface
{
    public $path;

    private $contextPath;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->contextPath = $path;
    }

    public function push(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
        $command = "rm -r -f $this->contextPath";

        $command .= " && mkdir \$(dirname \"$this->contextPath\") -p";

        $command .= ' && '.$compressionMethod->decompress("$localWorkDir", $this->contextPath);

        $command .= " && rm -r -f $localWorkDir";

        return $command;
    }

    public function pull(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
        $command = "mkdir $localWorkDir -p";

        $command .= ' && '.$compressionMethod->compress($this->contextPath, $localWorkDir);

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
            $this->contextPath = '/host'.$this->path;
        } else {
            $this->contextPath = $this->path;
        }
    }
}
