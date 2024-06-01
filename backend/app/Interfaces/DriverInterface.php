<?php

namespace App\Interfaces;

interface DriverInterface
{
    public function Push();
    public function Pull();
    public function Setup();
    public function Clean();
}
