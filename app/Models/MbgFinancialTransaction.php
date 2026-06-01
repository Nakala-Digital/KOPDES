<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MbgFinancialTransaction extends Model
{
    protected $fillable = [
        'mbg_order_id',
        'supplier_confirmation_id',
        'supplier_name',
        'supplier_type',
        'amount',
        'description',
        'transaction_date',
    ];

    protected function casts(): array
    {
        return ['transaction_date' => 'date'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(MbgOrder::class, 'mbg_order_id');
    }
}
