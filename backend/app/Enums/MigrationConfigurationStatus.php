<?php

namespace App\Enums;

enum MigrationConfigurationStatus: int
{
    case ACTIVE = 0;
    case INACTIVE = 1;
}
