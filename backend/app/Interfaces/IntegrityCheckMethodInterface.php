<?php

namespace App\Interfaces;

interface IntegrityCheckMethodInterface
{
    public function verify(string $localWorkDir);

    public function generateHash(string $localWorkDir);

    public function setHash(?string $hash);

    public function getHash();

    public function setup();

    public function clean();
}
