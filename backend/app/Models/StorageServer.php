<?php

namespace App\Models;

use App\Casts\ConnectionCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageServer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'connection_config' => ConnectionCast::class,
        'driver_config' => ConnectionCast::class,
    ];

    public function backups()
    {
        return $this->hasMany(Backup::class);
    }
}
