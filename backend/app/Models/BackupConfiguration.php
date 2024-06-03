<?php

namespace App\Models;

use App\Casts\ConnectionCast;
use App\Casts\DriverCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupConfiguration extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'connection_config' => ConnectionCast::class,
        'driver_config' => DriverCast::class,
    ];

    public function storageServer()
    {
        return $this->belongsTo(StorageServer::class);
    }
}
