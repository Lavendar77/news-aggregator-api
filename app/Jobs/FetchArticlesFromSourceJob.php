<?php

namespace App\Jobs;

use App\Enums\ArticleApiSource;
use App\Models\Article;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchArticlesFromSourceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected ArticleApiSource $apiSource, protected int $page = 1)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $result = $this->apiSource->processor()->getArticles($this->page, 100);

            info('Fetched ' . $result->articles->count() . ' articles from ' . $this->apiSource->value);

            Article::upsert(
                $result->articles->map(function ($article) {
                    return $article->toArray();
                })->toArray(),
                ['title', 'api_source'],
                ['description', 'content', 'url', 'image_url', 'author', 'category', 'published_at']
            );

            // Handle pagination - spin up the same job for the next page
            if ($result->hasMorePages) {
                info(
                    'Fetching next page: ' . $result->nextPage() . ' from ' . $this->apiSource->value
                    . ' with total pages: ' . $result->totalPages()
                );
                dispatch(new self($this->apiSource, $result->nextPage()))->delay(now()->addSeconds(10));
            }
        } catch (Exception $e) {
            report($e);
            return;
        }
    }
}
