<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KoperasiInstallment extends Model
{
    protected $fillable = [
        'loan_id',
        'installment_number',
        'due_date',
        'amount_due',
        'amount_paid',
        'paid_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'paid_at' => 'date',
        ];
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(KoperasiLoan::class, 'loan_id');
    }
}
