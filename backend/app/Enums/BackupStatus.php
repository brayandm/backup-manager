<?php

namespace App\Enums;

enum BackupStatus: int
{
    case CREATED = 0;
    case RUNNING = 1;
    case COMPLETED = 2;
    case FAILED = 3;
}
