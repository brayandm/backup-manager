<?php

namespace App\Models;

use App\Casts\CompressionMethodCast;
use App\Casts\ConnectionCast;
use App\Casts\StorageServerDriverCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'connection_config' => ConnectionCast::class,
        'driver_config' => StorageServerDriverCast::class,
        'compression_config' => CompressionMethodCast::class,
    ];

    public function backupConfiguration()
    {
        return $this->belongsTo(BackupConfiguration::class);
    }
}
