<?php

namespace App\Models;

use App\Casts\CompressionMethodCast;
use App\Casts\ConnectionCast;
use App\Casts\EncryptionMethodCast;
use App\Casts\IntegrityCheckMethodCast;
use App\Casts\StorageServerDriverCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Backup extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'connection_config' => ConnectionCast::class,
        'driver_config' => StorageServerDriverCast::class,
        'compression_config' => CompressionMethodCast::class,
        'encryption_config' => EncryptionMethodCast::class,
        'integrity_check_config' => IntegrityCheckMethodCast::class,
    ];

    public function dataSource()
    {
        return $this->belongsTo(DataSource::class);
    }

    public function storageServer()
    {
        return $this->belongsTo(StorageServer::class);
    }

    public function backupConfiguration()
    {
        return $this->belongsTo(BackupConfiguration::class);
    }
}
