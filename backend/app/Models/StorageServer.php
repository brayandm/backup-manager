<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageServer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function backups()
    {
        return $this->hasMany(Backup::class);
    }
}
