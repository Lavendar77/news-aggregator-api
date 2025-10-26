<?php

use App\Console\Commands\FetchArticlesFromSourceCommand;
use App\Enums\ArticleApiSource;
use App\Jobs\FetchArticlesFromSourceJob;
use Illuminate\Support\Facades\Queue;

use function Pest\Laravel\artisan;

it('can dispatch the job for the article source', function () {
    Queue::fake();

    // @phpstan-ignore-next-line
    artisan(FetchArticlesFromSourceCommand::class)->assertSuccessful();

    Queue::assertPushed(FetchArticlesFromSourceJob::class, count(ArticleApiSource::cases()));
});




it('can dispatch the job for the article source with a specific source', function () {
    Queue::fake();

    // @phpstan-ignore-next-line
    artisan(FetchArticlesFromSourceCommand::class, ['source' => [ArticleApiSource::cases()[0]->value]])
        ->assertSuccessful();

    Queue::assertPushed(FetchArticlesFromSourceJob::class, 1);
});
