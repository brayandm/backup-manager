<?php

namespace App\Models;

use App\Casts\BackupDriverCast;
use App\Casts\ConnectionCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupConfiguration extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'connection_config' => ConnectionCast::class,
        'driver_config' => BackupDriverCast::class,
    ];

    public function storageServer()
    {
        return $this->belongsTo(StorageServer::class);
    }
}
