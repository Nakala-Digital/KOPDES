<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketplaceOrder extends Model
{
    protected $fillable = [
        'umkm_profile_id',
        'order_number',
        'buyer_name',
        'buyer_phone',
        'shipping_address',
        'notes',
        'total_amount',
        'status',
        'confirmed_at',
        'shipped_at',
        'completed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'confirmed_at' => 'datetime',
            'shipped_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(UmkmProfile::class, 'umkm_profile_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MarketplaceOrderItem::class, 'order_id');
    }
}
