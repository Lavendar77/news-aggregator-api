<?php

namespace App\Models;

use App\Enums\ArticleApiSource;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use Searchable;

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

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    #[SearchUsingFullText(['title', 'description', 'content'])]
    #[SearchUsingPrefix(['category', 'news_source', 'api_source'])]
    public function toSearchableArray(): array
    {
        return $this->except(['url', 'image_url']);
    }
}
