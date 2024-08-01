<?php

namespace App\Helpers;

use App\Entities\CompressionMethodConfig;
use App\Entities\ConnectionConfig;
use App\Entities\DataSourceDriverConfig;
use App\Entities\EncryptionMethodConfig;
use App\Entities\IntegrityCheckMethodConfig;
use App\Entities\StorageServerDriverConfig;
use App\Interfaces\DataSourceDriverInterface;
use App\Interfaces\StorageServerDriverInterface;
use Illuminate\Support\Str;

class CommandBuilder
{
    public static function push(
        bool $preserveBackupManagerWorkDir,
        ?string $backupName,
        string $backupManagerWorkDir,
        ConnectionConfig $connectionConfig,
        DataSourceDriverConfig|StorageServerDriverConfig $driverConfig,
        ?CompressionMethodConfig $compressionMethodConfig,
    ) {
        $connections = $connectionConfig->connections;
        $driver = $driverConfig->driver;

        if (count($connections)) {
            $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            $connections[0]->dockerContext(true);
        } else {
            $localWorkDir = $backupManagerWorkDir;
            $driver->dockerContext(true);
        }

        $command = $driver->setup();

        if ($driver instanceof StorageServerDriverInterface) {
            $command .= ' && '.$driver->push($localWorkDir, $backupName);
        } else {
            $command .= ' && '.$compressionMethodConfig->compressionMethod->setup();
            $command .= ' && '.$driver->push($localWorkDir, $compressionMethodConfig->compressionMethod);
            $command .= ' && '.$compressionMethodConfig->compressionMethod->clean();
        }
        $command .= ' && '.$driver->clean();

        $connections = array_reverse($connections);

        for ($i = 0; $i < count($connections); $i++) {

            $connection = $connections[$i];

            $externalWorkDir = $localWorkDir;

            if ($i == count($connections) - 1) {
                $localWorkDir = $backupManagerWorkDir;
            } else {
                $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            }

            $command = $connection->setup().
            ' && '.$connection->push($localWorkDir, $externalWorkDir).
            ' && '.$connection->run($command);

            if ($preserveBackupManagerWorkDir == false || $i != count($connections) - 1) {
                $command .= ' && '.$connection->cleanAfterPush($localWorkDir, $externalWorkDir);
            }

            $command .= ' && '.$connection->clean();
        }

        return $command;
    }

    public static function pull(
        ?string $backupName,
        string $backupManagerWorkDir,
        ConnectionConfig $connectionConfig,
        DataSourceDriverConfig|StorageServerDriverConfig $driverConfig,
        ?CompressionMethodConfig $compressionMethodConfig,
    ) {
        $connections = $connectionConfig->connections;
        $driver = $driverConfig->driver;

        if (count($connections)) {
            $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            $connections[0]->dockerContext(true);
        } else {
            $localWorkDir = $backupManagerWorkDir;
            $driver->dockerContext(true);
        }

        $command = $driver->setup();
        if ($driver instanceof DataSourceDriverInterface) {
            $command .= ' && '.$compressionMethodConfig->compressionMethod->setup();
            $command .= ' && '.$driver->pull($localWorkDir, $compressionMethodConfig->compressionMethod);
            $command .= ' && '.$compressionMethodConfig->compressionMethod->clean();
        } else {
            $command .= ' && '.$driver->pull($localWorkDir, $backupName);
        }
        $command .= ' && '.$driver->clean();

        $connections = array_reverse($connections);

        for ($i = 0; $i < count($connections); $i++) {

            $connection = $connections[$i];

            $externalWorkDir = $localWorkDir;

            if ($i == count($connections) - 1) {
                $localWorkDir = $backupManagerWorkDir;
            } else {
                $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            }

            $command = $connection->setup().
            ' && '.$connection->run($command).
            ' && '.$connection->pull($localWorkDir, $externalWorkDir).
            ' && '.$connection->cleanAfterPull($localWorkDir, $externalWorkDir).
            ' && '.$connection->clean();
        }

        return $command;
    }

    public static function execute(string $command, ConnectionConfig $connectionConfig)
    {
        $connections = $connectionConfig->connections;

        if (count($connections)) {
            $connections[0]->dockerContext(true);
        }

        foreach (array_reverse($connections) as $connection) {
            $command = $connection->setup().' && '.$connection->run($command).' && '.$connection->clean();
        }

        return $command;
    }

    public static function encrypt(
        string $backupManagerWorkDir,
        EncryptionMethodConfig $encryptionMethodConfig,
    ) {
        $command = $encryptionMethodConfig->encryptionMethod->setup();
        $command .= ' && '.$encryptionMethodConfig->encryptionMethod->encrypt($backupManagerWorkDir);
        $command .= ' && '.$encryptionMethodConfig->encryptionMethod->clean();

        return $command;
    }

    public static function decrypt(
        string $backupManagerWorkDir,
        EncryptionMethodConfig $encryptionMethodConfig,
    ) {
        $command = $encryptionMethodConfig->encryptionMethod->setup();
        $command .= ' && '.$encryptionMethodConfig->encryptionMethod->decrypt($backupManagerWorkDir);
        $command .= ' && '.$encryptionMethodConfig->encryptionMethod->clean();

        return $command;
    }

    public static function dataPull(
        ConnectionConfig $backupConnectionConfig,
        DataSourceDriverConfig $dataSourceDriverConfig,
        CompressionMethodConfig $compressionMethodConfig,
    ) {
        $backupManagerWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();

        $command = CommandBuilder::pull(null, $backupManagerWorkDir, $backupConnectionConfig, $dataSourceDriverConfig, $compressionMethodConfig);

        return [
            'command' => $command,
            'backupManagerWorkDir' => $backupManagerWorkDir,
        ];
    }

    public static function backupPull(
        ConnectionConfig $backupConnectionConfig,
        DataSourceDriverConfig $dataSourceDriverConfig,
        CompressionMethodConfig $compressionMethodConfig,
        EncryptionMethodConfig $encryptionMethodConfig,
    ) {
        $result = CommandBuilder::dataPull($backupConnectionConfig, $dataSourceDriverConfig, $compressionMethodConfig);

        $command = $result['command'];

        $command .= ' && '.CommandBuilder::encrypt($result['backupManagerWorkDir'], $encryptionMethodConfig);

        return [
            'command' => $command,
            'backupManagerWorkDir' => $result['backupManagerWorkDir'],
        ];
    }

    public static function backupPush(
        bool $preserveBackupManagerWorkDir,
        string $backupName,
        string $backupManagerWorkDir,
        ConnectionConfig $storageServerConnectionConfig,
        StorageServerDriverConfig $storageServerDriverConfig,
    ) {
        $command = CommandBuilder::push($preserveBackupManagerWorkDir, $backupName, $backupManagerWorkDir, $storageServerConnectionConfig, $storageServerDriverConfig, null);

        return $command;
    }

    public static function integrityCheckGenerate(
        string $backupManagerWorkDir,
        IntegrityCheckMethodConfig $integrityCheckMethodConfig,
    ) {
        $command = $integrityCheckMethodConfig->integrityCheckMethod->setup();
        $command .= ' && '.$integrityCheckMethodConfig->integrityCheckMethod->generateHash($backupManagerWorkDir);
        $command .= ' && '.$integrityCheckMethodConfig->integrityCheckMethod->clean();

        return $command;
    }

    public static function integrityCheckVerify(
        string $backupManagerWorkDir,
        IntegrityCheckMethodConfig $integrityCheckMethodConfig,
    ) {
        $command = $integrityCheckMethodConfig->integrityCheckMethod->setup();
        $command .= ' && '.$integrityCheckMethodConfig->integrityCheckMethod->verify($backupManagerWorkDir);
        $command .= ' && '.$integrityCheckMethodConfig->integrityCheckMethod->clean();

        return $command;
    }

    public static function calculateSize(string $backupManagerWorkDir)
    {
        $command = 'du -sb '.$backupManagerWorkDir;

        return $command;
    }

    public static function getStorageServerFreeSpace(
        ConnectionConfig $storageServerConnectionConfig,
        StorageServerDriverConfig $storageServerDriverConfig,
    ) {
        $connections = $storageServerConnectionConfig->connections;
        $driver = $storageServerDriverConfig->driver;

        if (count($connections)) {
            $connections[0]->dockerContext(true);
        } else {
            $driver->dockerContext(true);
        }

        $command = $storageServerDriverConfig->driver->getFreeSpace();

        foreach (array_reverse($connections) as $connection) {
            $command = $connection->setup().' && '.$connection->run($command).' && '.$connection->clean();
        }

        return $command;

        return $command;
    }

    public static function restore(
        string $backupName,
        ConnectionConfig $backupConnectionConfig,
        DataSourceDriverConfig $dataSourceDriverConfig,
        ConnectionConfig $storageServerConnectionConfig,
        StorageServerDriverConfig $storageServerDriverConfig,
        CompressionMethodConfig $compressionMethodConfig,
        EncryptionMethodConfig $encryptionMethodConfig,
        IntegrityCheckMethodConfig $integrityCheckMethodConfig,
    ) {
        $backupManagerWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();

        $command = CommandBuilder::pull($backupName, $backupManagerWorkDir, $storageServerConnectionConfig, $storageServerDriverConfig, $compressionMethodConfig);
        $command .= ' && '.CommandBuilder::integrityCheckVerify($backupManagerWorkDir, $integrityCheckMethodConfig);
        $command .= ' && '.CommandBuilder::decrypt($backupManagerWorkDir, $encryptionMethodConfig);
        $command .= ' && '.CommandBuilder::push(false, null, $backupManagerWorkDir, $backupConnectionConfig, $dataSourceDriverConfig, $compressionMethodConfig);

        return $command;
    }

    public static function delete(
        string $backupName,
        ConnectionConfig $storageServerConnectionConfig,
        StorageServerDriverConfig $storageServerDriverConfig
    ) {
        $command = CommandBuilder::execute(
            $storageServerDriverConfig->driver->delete($backupName),
            $storageServerConnectionConfig
        );

        return $command;
    }

    public static function tmpPathGenerator()
    {
        return '/tmp/backup-manager/temp/'.Str::uuid();
    }
}
