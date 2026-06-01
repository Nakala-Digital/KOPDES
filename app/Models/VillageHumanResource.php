<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillageHumanResource extends Model
{
    protected $fillable = [
        'created_by',
        'village_name',
        'name',
        'category',
        'phone',
        'address',
    ];
}
