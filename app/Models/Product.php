<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'umkm_profile_id',
        'name',
        'description',
        'price',
        'unit',
        'stock',
        'minimum_stock',
        'category',
        'photo_url',
        'status',
        'is_marketplace_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_marketplace_visible' => 'boolean',
        ];
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(UmkmProfile::class, 'umkm_profile_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(MarketplaceOrderItem::class);
    }
}
