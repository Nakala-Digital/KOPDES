<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsetDesa extends Model
{
    protected $table = 'aset_desa';

    protected $fillable = [
        'created_by',
        'village_name',
        'name',
        'category',
        'location_description',
        'estimated_value',
        'condition',
        'latitude',
        'longitude',
        'photo_url',
        'notes',
    ];
}
