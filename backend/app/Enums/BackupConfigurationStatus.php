<?php

namespace App\Enums;

enum BackupConfigurationStatus: int
{
    case ACTIVE = 0;
    case INACTIVE = 1;
}
