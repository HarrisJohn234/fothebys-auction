<?php

namespace App\Domain\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'lot_id',
        'buyer_id',
        'hammer_price',
        'buyer_premium_rate',
        'buyer_premium_amount',
        'total_due',
        'sold_at',
    ];

    protected $casts = [
        'sold_at' => 'datetime',
        'buyer_premium_rate' => 'decimal:4',
    ];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Lots\Models\Lot::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'buyer_id');
    }
}
