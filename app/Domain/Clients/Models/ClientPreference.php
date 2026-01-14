<?php

namespace App\Domain\Clients\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientPreference extends Model
{
    protected $fillable = [
        'user_id',
        'preferred_contact_method',
        'marketing_opt_in',
    ];

    protected $casts = [
        'marketing_opt_in' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
