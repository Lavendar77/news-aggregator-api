<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Dtos\ArticleSourcePaginatedDataDto;

interface ArticleSourceContract
{
    /**
     * Get the articles from the source.
     *
     * @return \App\Dtos\ArticleSourcePaginatedDataDto
     */
    public function getArticles(?int $currentPage = 1, ?int $perPage = 100): ArticleSourcePaginatedDataDto;
}
