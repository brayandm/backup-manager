<?php

namespace App\Interfaces;

interface EncryptionMethodInterface
{
    public function encrypt(string $localWorkDir);

    public function decrypt(string $localWorkDir);

    public function generateKey();

    public function setup();

    public function clean();
}
