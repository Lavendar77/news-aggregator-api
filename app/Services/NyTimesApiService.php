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

class NyTimesApiService implements ArticleSourceContract
{
    protected ArticleApiSource $qualifier = ArticleApiSource::NYTIMES;

    /**
     * @inheritDoc
     */
    public function getArticles(?int $currentPage = 1, ?int $perPage = 100): ArticleSourcePaginatedDataDto
    {
        /** @var string $baseUrl */
        $baseUrl = config('services.nytimes.base_url');

        $response = Http::acceptJson()
            ->get($baseUrl . '/svc/news/v3/content/all/all.json', [
                'api-key' => config('services.nytimes.api_key'),
            ]);

        if ($response->failed()) {
            context()->add("{$this->qualifier->value}_api_response", $response->body());
            throw new Exception("Failed to fetch articles from {$this->qualifier->value}");
        }

        $data = (array) $response->json();

        return new ArticleSourcePaginatedDataDto(
            articles: $this->formatArticles($data['results']),
            total: $data['num_results'],
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
                ->setNewsSource($article['source'] ?? $this->qualifier->defaultNewsSource())
                ->setTitle($article['title'])
                ->setDescription($article['abstract'])
                ->setContent($article['content'] ?? $article['abstract'])
                ->setUrl($article['url'])
                ->setImageUrl($article['multimedia'][0]['url'] ?? null)
                ->setAuthor(preg_replace('/^by\s+/i', '', $article['author'] ?? ''))
                ->setPublishedAt(now()->parse($article['published_date']))
                ->setCategory($article['subsection']);
        });
    }
}
