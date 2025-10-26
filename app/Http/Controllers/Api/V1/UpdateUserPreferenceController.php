<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpdateUserPreferenceRequest;
use App\Http\Resources\UserPreferenceResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class UpdateUserPreferenceController extends Controller
{
    /**
     * Update user preferences.
     */
    public function __invoke(UpdateUserPreferenceRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $preferenceData = [
            'sources' => $request->array('sources', []),
            'categories' => $request->array('categories', []),
            'authors' => $request->array('authors', []),
        ];

        $preference = $user->preference()->updateOrCreate(
            ['user_id' => $user->id],
            $preferenceData
        );

        return ApiResponse::success(200, 'User preferences updated successfully', [
            'preference' => new UserPreferenceResource($preference),
        ]);
    }
}
