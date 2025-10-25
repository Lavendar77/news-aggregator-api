<?php

declare(strict_types=1);

namespace App\Dtos;

use Illuminate\Support\Collection;

class ArticleSourcePaginatedDataDto
{
    public bool $hasMorePages;

    /**
     * Create a new paginated data DTO.
     *
     * @param \Illuminate\Support\Collection<int, \App\Dtos\ArticleSourceDto> $articles
     * @param int|null $total
     * @param int|null $perPage
     * @param int|null $currentPage
     */
    public function __construct(
        public Collection $articles,
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
