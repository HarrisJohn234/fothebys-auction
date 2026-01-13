<?php

namespace App\Domain\Lots\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lot extends Model
{
    protected $fillable = [
        'lot_number',
        'artist_name',
        'year_produced',
        'subject_classification',
        'description',
        'estimate_low',
        'estimate_high',
        'auction_date',
        'category_id',
        'category_metadata',
        'auction_id',
        'status',
    ];

    protected $casts = [
        'category_metadata' => 'array',
        'auction_date' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Categories\Models\Category::class);
    }

    public function auction(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Auctions\Models\Auction::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(\App\Domain\Bidding\Models\CommissionBid::class, 'lot_id');
    }
}
