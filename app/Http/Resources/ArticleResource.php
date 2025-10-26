<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Article $article */
        $article = $this->resource;

        return [
            'id' => $article->id,
            'api_source' => $article->api_source->value,
            'news_source' => $article->news_source,
            'title' => $article->title,
            'description' => $article->description,
            'content' => $article->content,
            'url' => $article->url,
            'image_url' => $article->image_url,
            'author' => $article->author,
            'category' => $article->category,
            'published_at' => $article->published_at->toISOString(),
            'created_at' => $article->created_at?->toISOString(),
            'updated_at' => $article->updated_at?->toISOString(),
        ];
    }
}
