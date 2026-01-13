<?php

namespace App\Domain\Bidding\Models;

use App\Domain\Lots\Models\Lot;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionBid extends Model
{
    protected $fillable = [
        'user_id',
        'lot_id',
        'max_bid_amount',
        'status',
        'placed_at',
    ];

    protected $casts = [
        'max_bid_amount' => 'decimal:2',
        'placed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }
}
