<?php

namespace App\Domain\Categories\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug'];

    public function lots(): HasMany
    {
        return $this->hasMany(\App\Domain\Lots\Models\Lot::class);
    }
}
