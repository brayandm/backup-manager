<?php

namespace App\Entities\Methods\IntegrityCheckMethods;

use App\Interfaces\IntegrityCheckMethodInterface;

class Sha256SumIntegrityCheckMethod implements IntegrityCheckMethodInterface
{
    public string $hash;

    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    public function verify(string $localWorkDir)
    {
        $command = 'true';

        return $command;
    }

    public function generateHash(string $localWorkDir)
    {
        $command = "cd $localWorkDir && find . -type f -exec sha256sum {} + | sha256sum | awk '{print $1}'";

        return $command;
    }

    public function setHash(string $hash)
    {
        $this->hash = $hash;
    }

    public function getHash()
    {
        return $this->hash;
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
