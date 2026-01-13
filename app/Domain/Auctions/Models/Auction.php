<?php

namespace App\Domain\Auctions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model
{
    protected $fillable = [
        'title', 'theme', 'auction_type', 'starts_at', 'duration_minutes', 'status', 'created_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
    ];

    public function lots(): HasMany
    {
        return $this->hasMany(\App\Domain\Lots\Models\Lot::class);
    }
}
