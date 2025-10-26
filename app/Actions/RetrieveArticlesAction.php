<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Laravel\Scout\Builder;

class RetrieveArticlesAction
{
    /**
     * Execute the action.
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<string, mixed>
     */
    public function execute(Request $request): LengthAwarePaginator
    {
        /** @var \App\Models\User|null $user */
        $user = $request->user();
        if (!is_null($user)) {
            $user->load('preference');
        }

        $query = Article::search($request->string('search', '')->toString())
            ->orderBy('published_at', 'desc');

        $this->filterBySource($query, $request, $user);
        $this->filterByCategory($query, $request, $user);
        $this->filterByAuthor($query, $request, $user);
        $this->filterByApiSource($query, $request);
        $this->filterByFromDate($query, $request);
        $this->filterByToDate($query, $request);

        $perPage = min($request->integer('per_page', 10), 100);
        return $query->paginate($perPage);
    }

    /**
     * Filter articles by source.
     *
     * @param \Laravel\Scout\Builder<\App\Models\Article> $query
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User|null $user
     */
    private function filterBySource(Builder $query, Request $request, ?User $user = null): void
    {
        if ($request->has('source')) {
            $query->where('news_source', $request->string('source'));
        } elseif (!is_null($user?->preference?->sources) && count($user->preference->sources) > 0) {
            $query->whereIn('news_source', $user->preference->sources);
        }
    }

    /**
     * Filter articles by category.
     *
     * @param \Laravel\Scout\Builder<\App\Models\Article> $query
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User|null $user
     */
    private function filterByCategory(Builder $query, Request $request, ?User $user = null): void
    {
        if ($request->has('category')) {
            $query->where('category', $request->string('category'));
        } elseif (!is_null($user?->preference?->categories) && count($user->preference->categories) > 0) {
            $query->whereIn('category', $user->preference->categories);
        }
    }

    /**
     * Filter articles by author.
     *
     * @param \Laravel\Scout\Builder<\App\Models\Article> $query
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User|null $user
     */
    private function filterByAuthor(Builder $query, Request $request, ?User $user = null): void
    {
        if ($request->has('author')) {
            $query->where('author', $request->string('author'));
        } elseif (!is_null($user?->preference?->authors) && count($user->preference->authors) > 0) {
            $query->whereIn('author', $user->preference->authors);
        }
    }

    /**
     * Filter articles by API source.
     *
     * @param \Laravel\Scout\Builder<\App\Models\Article> $query
     * @param \Illuminate\Http\Request $request
     */
    private function filterByApiSource(Builder $query, Request $request): void
    {
        if ($request->has('api_source')) {
            $query->where('api_source', $request->string('api_source'));
        }
    }

    /**
     * Filter articles by from date.
     *
     * @param \Laravel\Scout\Builder<\App\Models\Article> $query
     * @param \Illuminate\Http\Request $request
     */
    private function filterByFromDate(Builder $query, Request $request): void
    {
        if ($request->has('from_date')) {
            $query->where('published_at', '>=', $request->date('from_date'));
        }
    }

    /**
     * Filter articles by to date.
     *
     * @param \Laravel\Scout\Builder<\App\Models\Article> $query
     * @param \Illuminate\Http\Request $request
     */
    private function filterByToDate(Builder $query, Request $request): void
    {
        if ($request->has('to_date')) {
            $query->where('published_at', '<=', $request->date('to_date'));
        }
    }
}
