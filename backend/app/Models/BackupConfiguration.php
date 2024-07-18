<?php

namespace App\Models;

use App\Casts\CompressionMethodCast;
use App\Casts\EncryptionMethodCast;
use App\Casts\IntegrityCheckMethodCast;
use App\Casts\RetentionPolicyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupConfiguration extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'retention_policy_config' => RetentionPolicyCast::class,
        'compression_config' => CompressionMethodCast::class,
        'encryption_config' => EncryptionMethodCast::class,
        'integrity_check_config' => IntegrityCheckMethodCast::class,
    ];

    public function storageServers()
    {
        return $this->belongsToMany(StorageServer::class)->withTimestamps();
    }

    public function dataSources()
    {
        return $this->belongsToMany(DataSource::class)->withTimestamps();
    }

    public function backups()
    {
        return $this->hasMany(Backup::class);
    }
}
