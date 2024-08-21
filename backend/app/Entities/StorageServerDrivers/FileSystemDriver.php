<?php

namespace App\Entities\StorageServerDrivers;

use App\Helpers\Formatting;
use App\Interfaces\StorageServerDriverInterface;

class FileSystemDriver implements StorageServerDriverInterface
{
    public $type = 'files_system';

    public $path;

    private $contextPath;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->contextPath = '/'.$this->removeSlashes($this->path);
    }

    private function removeSlashes(?string $path)
    {
        if ($path !== null && $path !== '') {
            $path = trim($path, '/');
        }

        return $path;
    }

    public function push(string $localWorkDir, string $backupConfigurationName, string $dataSourceName, string $backupName)
    {
        $dir = $this->contextPath.'/'.
            Formatting::formatText($backupConfigurationName).'/'.
            Formatting::formatText($dataSourceName).'/'.$backupName;

        $command = "mkdir $dir";

        $command .= " && cp -r $localWorkDir/* $dir/";

        $command .= ' && rm -r -f '.$localWorkDir;

        return $command;
    }

    public function pull(string $localWorkDir, string $backupConfigurationName, string $dataSourceName, string $backupName)
    {
        $dir = $this->contextPath.'/'.
            Formatting::formatText($backupConfigurationName).'/'.
            Formatting::formatText($dataSourceName).'/'.$backupName;

        $command = "mkdir $localWorkDir -p && cp -r $dir/* $localWorkDir";

        return $command;
    }

    public function delete(string $backupConfigurationName, string $dataSourceName, string $backupName)
    {
        $dir = $this->contextPath.'/'.
            Formatting::formatText($backupConfigurationName).'/'.
            Formatting::formatText($dataSourceName).'/'.$backupName;

        $command = "rm -r -f $dir";

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

    public function getFreeSpace()
    {
        $command = "mkdir -p $this->contextPath";

        $command .= "&& df --output=avail --block-size=1 $this->contextPath | tail -n 1";

        return $command;
    }

    public function isAvailable()
    {
        $command = "echo true > /dev/null 2>&1 && echo 'true'";

        return $command;
    }

    public function dockerContext(bool $dockerContext)
    {
        if ($dockerContext) {
            $this->contextPath = '/host'.'/'.$this->removeSlashes($this->path);
        } else {
            $this->contextPath = '/'.$this->removeSlashes($this->path);
        }
    }
}
