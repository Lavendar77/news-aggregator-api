<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\UserPreference $preference */
        $preference = $this->resource;

        return [
            'sources' => $preference->sources,
            'categories' => $preference->categories,
            'authors' => $preference->authors,
            'created_at' => $preference->created_at?->toIso8601ZuluString(),
            'updated_at' => $preference->updated_at?->toIso8601ZuluString(),
        ];
    }
}
