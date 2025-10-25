<?php

declare(strict_types=1);

namespace App\Dtos;

use Illuminate\Support\Collection;

class ArticleSourcePaginatedDataDto
{
    /** @var \Illuminate\Support\Collection<int, \App\Dtos\ArticleSourceDto> */
    public Collection $articles;

    public bool $hasMorePages;

    public function __construct(
        Collection $articles,
        public ?int $total = null,
        public ?int $perPage = null,
        public ?int $currentPage = null,
    ) {
        $this->articles = $articles;

        $this->hasMorePages = $this->currentPage < $this->totalPages();
    }

    public function totalPages(): int
    {
        return (int) ceil($this->total / ($this->perPage ?? 100));
    }

    public function nextPage(): int
    {
        return $this->currentPage + 1;
    }
}
