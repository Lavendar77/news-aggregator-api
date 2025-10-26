<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\putJson;

uses(DatabaseMigrations::class);

it('can update user preferences: sources', function () {
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson('/api/v1/me/preferences', [
        'sources' => [$source1 = fake()->word(), $source2 = fake()->word(), $source3 = fake()->word()],
    ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'preference' => [
                    'sources',
                    'categories',
                    'authors',
                    'created_at',
                    'updated_at',
                ],
            ],
        ])
        ->assertJsonFragment([
            'sources' => [$source1, $source2, $source3],
            'categories' => [],
            'authors' => [],
        ]);
});




it('can update user preferences: categories', function () {
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson('/api/v1/me/preferences', [
        'categories' => [$category1 = fake()->word(), $category2 = fake()->word(), $category3 = fake()->word()],
    ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'preference' => [
                    'sources',
                    'categories',
                    'authors',
                    'created_at',
                    'updated_at',
                ],
            ],
        ])
        ->assertJsonFragment([
            'sources' => [],
            'categories' => [$category1, $category2, $category3],
            'authors' => [],
        ]);
});




it('can update user preferences: authors', function () {
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson('/api/v1/me/preferences', [
        'authors' => [$author1 = fake()->name(), $author2 = fake()->name(), $author3 = fake()->name()],
    ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'preference' => [
                    'sources',
                    'categories',
                    'authors',
                    'created_at',
                    'updated_at',
                ],
            ],
        ])
        ->assertJsonFragment([
            'sources' => [],
            'categories' => [],
            'authors' => [$author1, $author2, $author3],
        ]);
});




it('can update user preferences: sources, categories, and authors', function () {
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson('/api/v1/me/preferences', [
        'sources' => [$source1 = fake()->word(), $source2 = fake()->word(), $source3 = fake()->word()],
        'categories' => [$category1 = fake()->word(), $category2 = fake()->word(), $category3 = fake()->word()],
        'authors' => [$author1 = fake()->name(), $author2 = fake()->name(), $author3 = fake()->name()],
    ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'preference' => [
                    'sources',
                    'categories',
                    'authors',
                    'created_at',
                    'updated_at',
                ],
            ],
        ])
        ->assertJsonFragment([
            'sources' => [$source1, $source2, $source3],
            'categories' => [$category1, $category2, $category3],
            'authors' => [$author1, $author2, $author3],
        ]);
});
