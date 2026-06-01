<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UmkmProfile extends Model
{
    protected $fillable = [
        'created_by',
        'owner_user_id',
        'village_name',
        'business_name',
        'owner_name',
        'phone',
        'business_type',
        'main_products',
        'production_capacity',
        'production_unit',
        'production_period',
        'needs_capital',
        'address',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'main_products' => 'array',
            'needs_capital' => 'boolean',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
