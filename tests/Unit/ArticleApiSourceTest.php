<?php

use App\Contracts\ArticleSourceContract;
use App\Enums\ArticleApiSource;

it('can get the processor for the article API source', function (ArticleApiSource $apiSource) {
    $processor = $apiSource->processor();

    expect($processor)->toBeInstanceOf(ArticleSourceContract::class);
})->with(ArticleApiSource::cases());




it('uses the correct qualifier for the article API source', function (ArticleApiSource $apiSource) {
    $processor = $apiSource->processor();

    expect($processor->getQualifier())->toBe($apiSource);
})->with(ArticleApiSource::cases());
