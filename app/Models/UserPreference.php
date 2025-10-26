<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'sources',
        'categories',
        'authors',
    ];

    protected $casts = [
        'sources' => 'array',
        'categories' => 'array',
        'authors' => 'array',
    ];

    /**
     * Get the user.
     *
     * @return BelongsTo<covariant User, covariant UserPreference>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
