<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Migration extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = 'migrations_table';

    public function originDataSource()
    {
        return $this->belongsTo(DataSource::class);
    }

    public function endDataSource()
    {
        return $this->belongsTo(DataSource::class);
    }

    public function migrationConfiguration()
    {
        return $this->belongsTo(MigrationConfiguration::class);
    }
}
