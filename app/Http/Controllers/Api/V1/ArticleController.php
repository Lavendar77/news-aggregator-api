<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $query = Article::search($request->string('search', '')->toString())
            ->orderBy('published_at', 'desc');

        if ($request->has('source')) {
            $query->where('news_source', $request->string('source'));
        }

        if ($request->has('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->has('api_source')) {
            $query->where('api_source', $request->string('api_source'));
        }

        if ($request->has('from_date')) {
            $query->where('published_at', '>=', $request->date('from_date'));
        }

        if ($request->has('to_date')) {
            $query->where('published_at', '<=', $request->date('to_date'));
        }

        $perPage = min($request->integer('per_page', 10), 100);
        $articles = $query->paginate($perPage);

        return ApiResponse::success(200, 'Articles retrieved successfully', $articles);
    }
}
