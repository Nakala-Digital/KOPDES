<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KopdesSetting extends Model
{
    protected $fillable = [
        'village_name',
        'monthly_interest_rate',
        'principal_saving_amount',
        'mandatory_saving_amount',
    ];
}
