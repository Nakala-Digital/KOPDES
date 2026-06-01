<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MbgOrder extends Model
{
    protected $fillable = [
        'order_number',
        'village_name',
        'beneficiary_name',
        'target_portions',
        'realized_portions',
        'distribution_date',
        'status',
    ];

    protected function casts(): array
    {
        return ['distribution_date' => 'date'];
    }

    public function supplierConfirmations(): HasMany
    {
        return $this->hasMany(MbgSupplierConfirmation::class);
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(MbgDistribution::class);
    }
}
