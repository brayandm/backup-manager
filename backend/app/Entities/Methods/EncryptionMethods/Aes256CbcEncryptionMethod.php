<?php

namespace App\Entities\Methods\EncryptionMethods;

use App\Interfaces\EncryptionMethodInterface;

class Aes256CbcEncryptionMethod implements EncryptionMethodInterface
{
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function encrypt(string $localWorkDir)
    {
        $command = 'true';

        return $command;
    }

    public function decrypt(string $localWorkDir)
    {
        $command = 'true';

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
}
