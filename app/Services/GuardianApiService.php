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
    protected $qualifier = ArticleApiSource::GUARDIAN;

    /**
     * @inheritDoc
     */
    public function getArticles(?int $currentPage = 1, ?int $perPage = 100): ArticleSourcePaginatedDataDto
    {
        $response = Http::acceptJson()
            ->get(config('services.guardian.base_url') . '/search', [
                'api-key' => config('services.guardian.api_key'),
                'format' => 'json',
                'show-fields' => 'trailText,thumbnail,bodyText,byline',
                'page-size' => $perPage,
                'page' => $currentPage,
            ]);

        if ($response->failed()) {
            context()->add("{$this->qualifier->value}_api_response", $response->body());
            throw new Exception("Failed to fetch articles from {$this->qualifier->value}");
        }

        $data = $response->json();

        return new ArticleSourcePaginatedDataDto(
            articles: $this->formatArticles($data['response']['results']),
            total: $data['response']['total'],
            perPage: $data['response']['pageSize'],
            currentPage: $data['response']['currentPage'],
        );
    }

    /**
     * Format the articles from the API response.
     */
    private function formatArticles(array $data): Collection
    {
        return collect($data)->map(function ($article) {
            return (new ArticleSourceDto())
                ->setApiSource($this->qualifier)
                ->setNewsSource($this->qualifier->defaultNewsSource())
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
