<?php

namespace App\Models;

use App\Casts\ConnectionCast;
use App\Casts\StorageServerDriverCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorageServer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'connection_config' => ConnectionCast::class,
        'driver_config' => StorageServerDriverCast::class,
    ];

    public function backupConfigurations()
    {
        return $this->belongsToMany(BackupConfiguration::class)->withTimestamps();
    }

    public function backups()
    {
        return $this->hasMany(Backup::class);
    }
}
