<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Dtos\ArticleSourcePaginatedDataDto;
use App\Enums\ArticleApiSource;

interface ArticleSourceContract
{
    /**
     * Get the qualifier for the article source.
     */
    public function getQualifier(): ArticleApiSource;

    /**
     * Get the articles from the source.
     */
    public function getArticles(?int $currentPage = 1, ?int $perPage = 100): ArticleSourcePaginatedDataDto;
}
