<?php

namespace App\Entities\Connections;

use App\Interfaces\ConnectionInterface;
use Illuminate\Support\Str;

class SshConnection implements ConnectionInterface
{
    public $user;

    public $host;

    public $port;

    public $privateKeyType;

    public $privateKey;

    public $passphrase;

    private $contextHost;

    private $contextPath;

    private $privateKeyPath;

    private $maxAttempts = 10;

    public function __construct(string $user, string $host, string $port, string $privateKeyType, string $privateKey, ?string $passphrase)
    {
        $this->user = $user;
        $this->host = $host;
        $this->contextHost = $host;
        $this->port = $port;

        if ($privateKeyType !== 'file' && $privateKeyType !== 'text') {
            throw new \Exception('Invalid private key type');
        }

        $this->privateKeyType = $privateKeyType;
        $this->privateKey = $privateKey;
        $this->passphrase = $passphrase;
        $this->contextPath = '';
    }

    private function ssh($command)
    {
        $command = escapeshellarg($command);

        $bashScript = <<<EOT
    SERVER="{$this->user}@{$this->contextHost}"
    COMMAND={$command}
    TIMEOUT=3
    RETRY_INTERVAL=1
    LOG_FILE=\$(mktemp)
    STATUS_FILE=\$(mktemp)
    PID_FILE=\$(mktemp)
    MAX_ATTEMPTS={$this->maxAttempts}
    attempt=0

    while [ \$attempt -lt \$MAX_ATTEMPTS ]; do
        echo "false" > \$STATUS_FILE
        attempt=\$((attempt+1))

        ssh -v -p {$this->port} -i {$this->privateKeyPath} -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -o LogLevel=ERROR \$SERVER "\$COMMAND" 2> \$LOG_FILE &
        SSH_PID=\$!

        (timeout \$TIMEOUT tail -f \$LOG_FILE 2>/dev/null & echo \$! > \$PID_FILE) | while IFS= read -r line; do
            if echo "\$line" | grep -q "Authenticating to {$this->contextHost}:{$this->port} as '{$this->user}'"; then
                echo "true" > \$STATUS_FILE
                break
            fi
        done 2> /dev/null

        FOUND_AUTHENTICATING=\$(cat \$STATUS_FILE)

        if [ "\$FOUND_AUTHENTICATING" = "true" ]; then
            wait \$SSH_PID 2>/dev/null
            break
        else
            kill \$SSH_PID 2>/dev/null
            wait \$SSH_PID 2>/dev/null
            sleep \$RETRY_INTERVAL
        fi
    done

    rm -f \$LOG_FILE
    rm -f \$STATUS_FILE
    rm -f \$PID_FILE
    EOT;

        return $bashScript;
    }

    private function scp($from, $to)
    {
        $bashScript = <<<EOT
    TIMEOUT=3
    RETRY_INTERVAL=1
    LOG_FILE=\$(mktemp)
    STATUS_FILE=\$(mktemp)
    PID_FILE=\$(mktemp)
    MAX_ATTEMPTS={$this->maxAttempts}
    attempt=0

    while [ \$attempt -lt \$MAX_ATTEMPTS ]; do
        echo "false" > \$STATUS_FILE
        attempt=\$((attempt+1))

        scp -v -r -P {$this->port} -i {$this->privateKeyPath} -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -o LogLevel=ERROR {$from} {$to} 2> \$LOG_FILE &
        SSH_PID=\$!

        (timeout \$TIMEOUT tail -f \$LOG_FILE 2>/dev/null & echo \$! > \$PID_FILE) | while IFS= read -r line; do
            if echo "\$line" | grep -q "Authenticating to {$this->contextHost}:{$this->port} as '{$this->user}'"; then
                echo "true" > \$STATUS_FILE
                break
            fi
        done 2> /dev/null

        FOUND_AUTHENTICATING=\$(cat \$STATUS_FILE)

        if [ "\$FOUND_AUTHENTICATING" = "true" ]; then
            wait \$SSH_PID 2>/dev/null
            break
        else
            kill \$SSH_PID 2>/dev/null
            wait \$SSH_PID 2>/dev/null
            sleep \$RETRY_INTERVAL
        fi
    done

    rm -f \$LOG_FILE
    rm -f \$STATUS_FILE
    rm -f \$PID_FILE
    EOT;

        return $bashScript;
    }

    public function run(string $command)
    {
        $command = $this->ssh($command);

        return $command;
    }

    public function push(string $localWorkDir, string $externalWorkDir)
    {
        $command = $this->ssh("mkdir -p \"{$externalWorkDir}\"");

        $command .= ' && '.$this->scp("\"{$localWorkDir}\"/*", "{$this->user}@{$this->contextHost}:\"{$externalWorkDir}\"");

        return $command;
    }

    public function pull(string $localWorkDir, string $externalWorkDir)
    {
        $command = "mkdir -p \"{$localWorkDir}\"";

        $command .= ' && '.$this->scp("{$this->user}@{$this->contextHost}:\"{$externalWorkDir}\"/*", "\"{$localWorkDir}\"");

        return $command;
    }

    public function setup()
    {
        $this->privateKeyPath = '/tmp/backup-manager/.ssh/'.Str::uuid();

        $command = 'mkdir -p /tmp/backup-manager/.ssh';
        $command .= ' && unset HISTFILE';

        if ($this->privateKeyType === 'file') {
            $command .= " && cat \"{$this->contextPath}{$this->privateKey}\" > \"{$this->privateKeyPath}\"";
        } else {
            $command .= " && echo \"{$this->privateKey}\" > \"{$this->privateKeyPath}\"";
        }

        $command .= " && chmod 600 \"{$this->privateKeyPath}\"";

        if ($this->passphrase) {
            $command .= " && ssh-keygen -p -f \"{$this->privateKeyPath}\" -P \"{$this->passphrase}\" -N \"\" > /dev/null 2>&1";
        }

        return $command;
    }

    public function clean()
    {
        $command = "rm -f \"{$this->privateKeyPath}\"";

        return $command;
    }

    public function cleanAfterPush(string $localWorkDir, string $externalWorkDir)
    {
        $command = 'rm -r -f \"'.$localWorkDir.'\"';

        return $command;
    }

    public function cleanAfterPull(string $localWorkDir, string $externalWorkDir)
    {
        $command = $this->ssh("rm -r -f \"{$externalWorkDir}\"");

        return $command;
    }

    public function dockerContext(bool $dockerContext)
    {
        if ($dockerContext) {
            if ($this->host === 'localhost' || $this->host === '127.0.0.1') {
                $this->contextHost = 'host.docker.internal';
            }
            $this->contextPath = '/host';
        } else {
            $this->contextHost = $this->host;
            $this->contextPath = '';
        }
    }
}
