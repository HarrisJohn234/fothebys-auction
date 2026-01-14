<?php

namespace App\Domain\Bidding\Models;

use App\Domain\Lots\Models\Lot;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionBid extends Model
{
    protected $table = 'bids';

    protected $fillable = [
        'client_id',
        'lot_id',
        'max_bid_amount',
        'status',
    ];

    protected $casts = [
        'max_bid_amount' => 'integer',
    ];

    public function user(): BelongsTo
    {
        // Keep the relation name `user()` for convenience in views/controllers
        return $this->belongsTo(User::class, 'client_id');
    }

    public function client(): BelongsTo
    {
        // Alias for admin UI which expects $bid->client
        return $this->belongsTo(User::class, 'client_id');
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }
}
