<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Enums\ArticleApiSource;
use Carbon\Carbon;

class ArticleSourceDto
{
    public ArticleApiSource $apiSource;

    public string $newsSource;

    public string $title;

    public string $description;

    public string $content;

    public string $url;

    public ?string $imageUrl = null;

    public ?string $author = null;

    public ?string $category = null;

    public Carbon $publishedAt;

    public function setApiSource(ArticleApiSource $apiSource): self
    {
        $this->apiSource = $apiSource;
        return $this;
    }

    public function setNewsSource(string $newsSource): self
    {
        $this->newsSource = $newsSource;
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = trim(strip_tags($description));
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = trim(strip_tags($content));
        return $this;
    }

    public function setUrl(string $url): self
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException("Invalid URL provided: {$url}");
        }

        $this->url = $url;
        return $this;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl ?: null;
        return $this;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author ?: null;
        return $this;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category ?: null;
        return $this;
    }

    public function setPublishedAt(Carbon $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    /**
     * Get the article as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'api_source' => $this->apiSource->value,
            'news_source' => $this->newsSource,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'url' => $this->url,
            'image_url' => $this->imageUrl,
            'author' => $this->author,
            'category' => $this->category,
            'published_at' => $this->publishedAt,
        ];
    }
}
