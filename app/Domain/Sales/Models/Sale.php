<?php

namespace App\Domain\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    /**
     * Commission-bid demo scope.
     *
     * The sales table is a simple, sprint-friendly ledger created when an auction closes:
     * - One sale row per lot (unique lot_id)
     * - client_id can be NULL when a lot is UNSOLD
     * - hammer_price is the winning commission bid amount (nullable when UNSOLD)
     * - commission_amount is the auction house commission taken from the hammer price
     */
    protected $fillable = [
        'lot_id',
        'client_id',
        'hammer_price',
        'commission_amount',
        'status', // COMPLETED | UNSOLD
    ];

    protected $casts = [
        'hammer_price' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Lots\Models\Lot::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'client_id');
    }
}
