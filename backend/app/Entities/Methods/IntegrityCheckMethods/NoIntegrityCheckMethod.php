<?php

namespace App\Entities\Methods\IntegrityCheckMethods;

use App\Interfaces\IntegrityCheckMethodInterface;

class NoIntegrityCheckMethod implements IntegrityCheckMethodInterface
{
    public function __construct()
    {
    }

    public function verify(string $localWorkDir)
    {
        $command = 'true';

        return $command;
    }

    public function generateHash(string $localWorkDir)
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
