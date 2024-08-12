<?php

namespace App\Entities\DataSourceDrivers;

use App\Helpers\CommandBuilder;
use App\Interfaces\CompressionMethodInterface;
use App\Interfaces\DataSourceDriverInterface;

class FileSystemDriver implements DataSourceDriverInterface
{
    public $type = 'files_system';

    public $path;

    private $contextPath;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->contextPath = $path;
    }

    public function push(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
        $tempDir = CommandBuilder::tmpPathGenerator();

        $command = "mkdir $tempDir -p";

        $command .= ' && '.$compressionMethod->decompress("$localWorkDir", $tempDir.'/data');

        $command .= " && rm -r -f $this->contextPath";

        $command .= " && mkdir \$(dirname \"$this->contextPath\") -p";

        $command .= ' && cp -r '.$tempDir.'/data '.$this->contextPath;

        $command .= " && rm -r -f $localWorkDir";

        $command .= ' && rm -r -f '.$tempDir;

        return $command;
    }

    public function pull(string $localWorkDir, CompressionMethodInterface $compressionMethod)
    {
        $tempDir = CommandBuilder::tmpPathGenerator();

        $command = "mkdir $localWorkDir -p";

        $command .= ' && mkdir '.$tempDir.' -p';

        $command .= ' && cp -r '.$this->contextPath.' '.$tempDir.'/data';

        $command .= ' && '.$compressionMethod->compress($tempDir.'/data', $localWorkDir);

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
        $command = "test -e $this->contextPath > /dev/null 2>&1 && echo 'true'";

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
