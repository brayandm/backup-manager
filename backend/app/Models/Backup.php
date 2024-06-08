<?php

namespace App\Models;

use App\Casts\BackupDriverCast;
use App\Casts\ConnectionCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'connection_config' => ConnectionCast::class,
        'driver_config' => BackupDriverCast::class,
    ];

    public function backupConfiguration()
    {
        return $this->belongsTo(BackupConfiguration::class);
    }
}
