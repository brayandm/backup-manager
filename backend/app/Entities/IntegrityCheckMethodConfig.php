<?php

namespace App\Entities;

use App\Interfaces\IntegrityCheckMethodInterface;

class IntegrityCheckMethodConfig
{
    public IntegrityCheckMethodInterface $integrityCheckMethod;

    public function __construct(IntegrityCheckMethodInterface $integrityCheckMethod)
    {
        $this->integrityCheckMethod = $integrityCheckMethod;
    }
}
