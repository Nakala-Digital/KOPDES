<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MbgDistribution extends Model
{
    protected $fillable = [
        'mbg_order_id',
        'school_name',
        'target_portions',
        'realized_portions',
        'distributed_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return ['distributed_at' => 'date'];
    }
}
