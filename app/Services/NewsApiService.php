<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ArticleSourceContract;
use App\Dtos\ArticleSourceDto;
use App\Dtos\ArticleSourcePaginatedDataDto;
use App\Enums\ArticleApiSource;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class NewsApiService implements ArticleSourceContract
{
    protected ArticleApiSource $qualifier = ArticleApiSource::NEWSAPI;

    /**
     * @inheritDoc
     */
    public function getArticles(?int $currentPage = 1, ?int $perPage = 100): ArticleSourcePaginatedDataDto
    {
        /** @var string $baseUrl */
        $baseUrl = config('services.newsapi.base_url');

        $response = Http::acceptJson()
            ->withHeaders([
                'X-Api-Key' => config('services.newsapi.api_key'),
            ])
            ->get($baseUrl . '/v2/everything', [
                'q' => 'top-headlines',
                'pageSize' => $perPage,
                'page' => $currentPage,
            ]);

        if ($response->failed()) {
            context()->add("{$this->qualifier->value}_api_response", $response->body());
            throw new Exception("Failed to fetch articles from {$this->qualifier->value}");
        }

        $data = (array) $response->json();

        return new ArticleSourcePaginatedDataDto(
            articles: $this->formatArticles($data['articles']),
            total: $data['totalResults'],
            perPage: $perPage,
            currentPage: $currentPage,
        );
    }

    /**
     * Format the articles from the API response.
     *
     * @param array<int, array<string, mixed>> $data
     * @return \Illuminate\Support\Collection<int, \App\Dtos\ArticleSourceDto>
     */
    private function formatArticles(array $data): Collection
    {
        return collect($data)->map(function ($article) {
            return (new ArticleSourceDto())
                ->setApiSource($this->qualifier)
                ->setNewsSource($article['source']['name'] ?? $this->qualifier->defaultNewsSource())
                ->setTitle($article['title'])
                ->setDescription($article['description'])
                ->setContent($article['content'])
                ->setUrl($article['url'])
                ->setImageUrl($article['urlToImage'])
                ->setAuthor($article['author'])
                ->setPublishedAt(now()->parse($article['publishedAt']));
        });
    }
}
