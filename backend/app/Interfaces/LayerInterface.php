<?php

namespace App\Interfaces;

interface LayerInterface
{
    public function Apply(string $localWorkDir);

    public function Unapply(string $localWorkDir);

    public function setup();

    public function clean();
}
