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
        $command = "for FILE in $localWorkDir/*; do [ -f \"\$FILE\" ]";
        $command .= " && openssl enc -aes-256-cbc -salt -pbkdf2 -in \"\$FILE\" -out \"\${FILE}.enc\" -pass pass:\"$this->key\"";
        $command .= ' && rm "$FILE"; done';

        return $command;
    }

    public function decrypt(string $localWorkDir)
    {
        $command = "for FILE in $localWorkDir/*.enc; do [ -f \"\$FILE\" ]";
        $command .= " && openssl enc -d -aes-256-cbc -pbkdf2 -in \"\$FILE\" -out \"\${FILE%.enc}\" -pass pass:\"$this->key\"";
        $command .= ' && rm "$FILE"; done';

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
