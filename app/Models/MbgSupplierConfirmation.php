<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MbgSupplierConfirmation extends Model
{
    protected $fillable = [
        'mbg_order_id',
        'supplier_name',
        'supplier_type',
        'product_name',
        'quantity',
        'unit',
        'delivery_date',
        'estimated_unit_price',
        'estimated_total_cost',
        'status',
        'notified_at',
        'reminder_due_at',
        'confirmation_due_at',
        'confirmed_at',
        'failed_at',
        'admin_approval_required',
        'risk_note',
    ];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'notified_at' => 'datetime',
            'reminder_due_at' => 'datetime',
            'confirmation_due_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'failed_at' => 'datetime',
            'admin_approval_required' => 'boolean',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(MbgOrder::class, 'mbg_order_id');
    }
}
