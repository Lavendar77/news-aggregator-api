<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\User $user */
        $user = $this->resource;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            $this->mergeWhen($user->relationLoaded('preference'), [
                'preference' => new UserPreferenceResource($user->preference),
            ]),
            'created_at' => $user->created_at?->toIso8601ZuluString(),
            'updated_at' => $user->updated_at?->toIso8601ZuluString(),
        ];
    }
}
