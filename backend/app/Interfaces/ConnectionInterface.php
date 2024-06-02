<?php

namespace App\Interfaces;

interface ConnectionInterface
{
    public function Run();

    public function Push(string $filepath);

    public function Pull(string $filepath);

    public function Setup();

    public function Clean();
}
