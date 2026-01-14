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
        'image_path',
        'status',
    ];

    protected $casts = [
        'category_metadata' => 'array',
        'auction_date' => 'date',
    ];

    protected $appends = [
        'image_url',
    ];

    /**
     * IMPORTANT: return a relative URL to avoid mixed-content issues
     * when APP_URL is http but the site is served over https (common with Herd).
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        return '/storage/' . ltrim($this->image_path, '/');
    }

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
