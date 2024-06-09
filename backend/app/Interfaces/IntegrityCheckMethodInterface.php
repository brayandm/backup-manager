<?php

namespace App\Interfaces;

interface IntegrityCheckMethodInterface
{
    public function check(string $localWorkDir);

    public function generateHash(string $localWorkDir);

    public function setup();

    public function clean();
}
