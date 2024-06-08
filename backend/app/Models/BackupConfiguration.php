<?php

namespace App\Models;

use App\Casts\BackupDriverCast;
use App\Casts\CompressionMethodCast;
use App\Casts\ConnectionCast;
use App\Casts\EncryptionMethodCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupConfiguration extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'connection_config' => ConnectionCast::class,
        'driver_config' => BackupDriverCast::class,
        'compression_config' => CompressionMethodCast::class,
        'encryption_config' => EncryptionMethodCast::class,
    ];

    public function storageServers()
    {
        return $this->belongsToMany(StorageServer::class)->withTimestamps();
    }

    public function backups()
    {
        return $this->hasMany(Backup::class);
    }
}
