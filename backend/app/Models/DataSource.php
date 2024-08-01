<?php

namespace App\Models;

use App\Casts\ConnectionCast;
use App\Casts\DataSourceDriverCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataSource extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'connection_config' => ConnectionCast::class,
        'driver_config' => DataSourceDriverCast::class,
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
