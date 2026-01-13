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
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }
}
