<?php

namespace App\Interfaces;

interface LayerInterface
{
    public function apply(string $localWorkDir);

    public function unapply(string $localWorkDir);

    public function setup();

    public function clean();
}
