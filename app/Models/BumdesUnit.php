<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BumdesUnit extends Model
{
    protected $fillable = [
        'created_by',
        'village_name',
        'name',
        'category',
        'pades_percentage',
        'status',
        'notes',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(BumdesUnitTransaction::class, 'unit_id');
    }
}
