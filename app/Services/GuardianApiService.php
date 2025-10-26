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

class GuardianApiService implements ArticleSourceContract
{
    /**
     * Get the qualifier for the article source.
     */
    public function getQualifier(): ArticleApiSource
    {
        return ArticleApiSource::GUARDIAN;
    }

    /**
     * @inheritDoc
     */
    public function getArticles(?int $currentPage = 1, ?int $perPage = 100): ArticleSourcePaginatedDataDto
    {
        /** @var string $baseUrl */
        $baseUrl = config('services.guardian.base_url');

        $response = Http::acceptJson()
            ->get($baseUrl . '/search', [
                'api-key' => config('services.guardian.api_key'),
                'format' => 'json',
                'show-fields' => 'trailText,thumbnail,bodyText,byline',
                'page-size' => $perPage,
                'page' => $currentPage,
            ]);

        if ($response->failed()) {
            context()->add("{$this->getQualifier()->value}_api_response", $response->body());
            throw new Exception("Failed to fetch articles from {$this->getQualifier()->value}");
        }

        $data = (array) $response->json();

        return new ArticleSourcePaginatedDataDto(
            articles: $this->formatArticles($data['response']['results']),
            total: $data['response']['total'],
            perPage: $data['response']['pageSize'],
            currentPage: $data['response']['currentPage'],
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
                ->setApiSource($this->getQualifier())
                ->setNewsSource($this->getQualifier()->defaultNewsSource())
                ->setTitle($article['webTitle'])
                ->setDescription($article['fields']['trailText'])
                ->setContent($article['fields']['bodyText'] ?? '')
                ->setUrl($article['webUrl'])
                ->setImageUrl($article['fields']['thumbnail'] ?? null)
                ->setAuthor(preg_replace('/^by\s+/i', '', $article['fields']['byline'] ?? ''))
                ->setPublishedAt(now()->parse($article['webPublicationDate']))
                ->setCategory($article['sectionName']);
        });
    }
}
