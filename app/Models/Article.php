<?php

namespace App\Models;

use App\Enums\ArticleApiSource;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'api_source',
        'news_source',
        'title',
        'description',
        'content',
        'url',
        'image_url',
        'author',
        'category',
        'published_at',
    ];

    protected $casts = [
        'api_source' => ArticleApiSource::class,
        'published_at' => 'datetime',
    ];
}
