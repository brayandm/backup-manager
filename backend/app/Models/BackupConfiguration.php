<?php

namespace App\Models;

use App\Casts\BackupDriverCast;
use App\Casts\CompressionMethodCast;
use App\Casts\ConnectionCast;
use App\Casts\EncryptionMethodCast;
use App\Casts\IntegrityCheckMethodCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupConfiguration extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'compression_config' => CompressionMethodCast::class,
        'encryption_config' => EncryptionMethodCast::class,
        'integrity_check_config' => IntegrityCheckMethodCast::class,
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
