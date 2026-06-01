<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KoperasiLoan extends Model
{
    protected $fillable = [
        'member_id',
        'created_by',
        'principal_amount',
        'interest_rate',
        'tenor_months',
        'monthly_installment',
        'total_payable',
        'status',
        'approved_at',
        'due_start_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'date',
            'due_start_date' => 'date',
        ];
    }

    public function installments(): HasMany
    {
        return $this->hasMany(KoperasiInstallment::class, 'loan_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(KoperasiMember::class, 'member_id');
    }
}
