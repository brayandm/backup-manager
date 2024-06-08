<?php

namespace App\Entities;

use App\Interfaces\EncryptionMethodInterface;

class EncryptionMethodConfig
{
    public EncryptionMethodInterface $encryptionMethod;

    public function __construct(EncryptionMethodInterface $encryptionMethod)
    {
        $this->encryptionMethod = $encryptionMethod;
    }
}
