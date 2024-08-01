<?php

namespace App\Models;

use App\Casts\CompressionMethodCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MigrationConfiguration extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'compression_config' => CompressionMethodCast::class,
    ];

    public function dataSource()
    {
        return $this->hasOne(DataSource::class);
    }

    public function dataSources()
    {
        return $this->belongsToMany(DataSource::class)->withTimestamps();
    }
}
