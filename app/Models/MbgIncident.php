<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MbgIncident extends Model
{
    protected $fillable = [
        'mbg_order_id',
        'supplier_confirmation_id',
        'type',
        'description',
        'status',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(MbgOrder::class, 'mbg_order_id');
    }
}
