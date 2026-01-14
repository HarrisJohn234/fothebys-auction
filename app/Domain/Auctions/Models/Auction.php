<?php

namespace App\Domain\Auctions\Models;

use App\Domain\Lots\Models\Lot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model
{
    protected $fillable = [
        'title',
        'starts_at',
        'ends_at',
        'status',
        'image_path',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    protected $appends = [
        'image_url',
    ];

    /**
     * IMPORTANT: return a relative URL to avoid mixed-content issues.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        return '/storage/' . ltrim($this->image_path, '/');
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }
}
