<?php

namespace App\Entities\Methods\EncryptionMethods;

use App\Interfaces\EncryptionMethodInterface;
use Illuminate\Support\Str;

class Aes256CbcEncryptionMethod implements EncryptionMethodInterface
{
    public string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function encrypt(string $localWorkDir)
    {
        $tempDir = $localWorkDir."/".Str::uuid();
        $encrypt = "openssl enc -aes-256-cbc -salt -pbkdf2 -in \"\$1\" -out \"$tempDir/tmp.enc\" -pass pass:\"$this->key\" && mv \"$tempDir/tmp.enc\" \"\$1\"";

        $command = "find \"$localWorkDir\" -type d | sed 's|^$localWorkDir||' | awk -v tempDir=\"$tempDir\" '{print \"mkdir -p \\\"\" tempDir \$0 \"\\\"\"}' | sh";
        $command .= " && mkdir -p \"$tempDir\"";
        $command .= " && find \"$localWorkDir\" -type f -name '*' -exec sh -c '$encrypt' _ {} \\;";
        $command .= " && rm -rf \"$tempDir\"";

        return $command;
    }

    public function decrypt(string $localWorkDir)
    {
        $tempDir = $localWorkDir."/".Str::uuid();
        $decrypt = "openssl enc -d -aes-256-cbc -pbkdf2 -in \"\$1\" -out \"$tempDir/tmp.dec\" -pass pass:\"$this->key\"&& mv \"$tempDir/tmp.dec\" \"\$1\"";

        $command = "find \"$localWorkDir\" -type d | sed 's|^$localWorkDir||' | awk -v tempDir=\"$tempDir\" '{print \"mkdir -p \\\"\" tempDir \$0 \"\\\"\"}' | sh";
        $command .= " && mkdir -p \"$tempDir\"";
        $command .= " && find \"$localWorkDir\" -type f -name '*' -exec sh -c '$decrypt' _ {} \\;";
        $command .= " && rm -rf \"$tempDir\"";

        return $command;
    }

    public function generateKey()
    {
        $this->key = base64_encode(random_bytes(32));
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
}
