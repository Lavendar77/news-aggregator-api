<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    /** @use HasFactory<\Database\Factories\UserPreferenceFactory> */
    use HasFactory;

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
