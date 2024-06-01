<?php

namespace App\Interfaces;

interface ConnectionInterface
{
    public function Run();
    public function Push();
    public function Pull();
    public function Setup();
    public function Clean();
}
