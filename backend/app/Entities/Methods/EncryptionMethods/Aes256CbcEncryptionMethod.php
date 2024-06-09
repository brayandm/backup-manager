<?php

namespace App\Entities\Methods\EncryptionMethods;

use App\Interfaces\EncryptionMethodInterface;

class Aes256CbcEncryptionMethod implements EncryptionMethodInterface
{
    public string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function encrypt(string $localWorkDir)
    {
        $command = "openssl enc -aes-256-cbc -salt -pbkdf2 -in \"\$1\" -out \"\$1.enc\" -pass pass:\"$this->key\" && rm \"\$1\"";
        $command = "find \"$localWorkDir\" -type f -name '*' -exec sh -c '$command' _ {} \\;";

        return $command;
    }

    public function decrypt(string $localWorkDir)
    {
        $command = "openssl enc -d -aes-256-cbc -pbkdf2 -in \"\$1\" -out \"\${1%.enc}\" -pass pass:\"$this->key\" && rm \"\$1\"";
        $command = "find \"$localWorkDir\" -type f -name '*.enc' -exec sh -c '' _ {} \\;";

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
