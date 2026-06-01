<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmkmFinancialTransaction extends Model
{
    protected $fillable = [
        'umkm_profile_id',
        'order_id',
        'type',
        'amount',
        'description',
        'transaction_date',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
        ];
    }
}
