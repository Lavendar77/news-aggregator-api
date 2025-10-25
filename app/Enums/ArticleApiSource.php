<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\ArticleSourceContract;
use App\Services\GuardianApiService;
use App\Services\NewsApiService;
use App\Services\NyTimesApiService;

enum ArticleApiSource: string
{
    case NEWSAPI = 'newsapi';
    case GUARDIAN = 'guardian';
    case NYTIMES = 'nytimes';

    /**
     * Get the processor class for the article API source.
     */
    public function processor(): ArticleSourceContract
    {
        return match ($this) {
            self::NEWSAPI => new NewsApiService(),
            self::GUARDIAN => new GuardianApiService(),
            self::NYTIMES => new NyTimesApiService(),
        };
    }

    /**
     * Get the default news source for the article API source.
     */
    public function defaultNewsSource(): string
    {
        return match ($this) {
            self::NEWSAPI => 'NewsAPI.org',
            self::GUARDIAN => 'The Guardian',
            self::NYTIMES => 'The New York Times',
        };
    }
}
