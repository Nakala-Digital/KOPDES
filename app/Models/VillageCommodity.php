<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillageCommodity extends Model
{
    protected $fillable = [
        'created_by',
        'village_name',
        'name',
        'monthly_production_volume',
        'unit',
        'price',
        'location_description',
        'latitude',
        'longitude',
    ];
}
