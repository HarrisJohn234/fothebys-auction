<?php

namespace App\Domain\Bids\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    protected $fillable = [
        'lot_id',
        'client_id',
        'max_bid_amount',
        'status',
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
