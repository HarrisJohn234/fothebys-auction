<?php

namespace App\Domain\Categories\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    public function lots(): HasMany
    {
        return $this->hasMany(\App\Domain\Lots\Models\Lot::class);
    }
}
