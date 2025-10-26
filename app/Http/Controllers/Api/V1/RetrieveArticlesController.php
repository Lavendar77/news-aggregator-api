<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\RetrieveArticlesAction;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RetrieveArticlesController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function __invoke(Request $request, RetrieveArticlesAction $retrieveArticlesAction): JsonResponse
    {
        $articles = $retrieveArticlesAction->execute($request);

        return ApiResponse::success(200, 'Articles retrieved successfully', $articles);
    }
}
