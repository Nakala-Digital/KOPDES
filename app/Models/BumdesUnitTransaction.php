<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BumdesUnitTransaction extends Model
{
    protected $fillable = [
        'unit_id',
        'created_by',
        'reversal_of_id',
        'transaction_number',
        'type',
        'category',
        'amount',
        'transaction_date',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(BumdesUnit::class, 'unit_id');
    }
}
