<?php

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(DatabaseMigrations::class);

it('can register a new user', function () {
    postJson('/api/v1/auth/register', [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'password' => ($password = fake()->password()),
        'password_confirmation' => $password,
    ])
        ->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
                'token',
            ],
        ])
        ->assertJsonMissing([
            'data.user.password',
        ]);
});




it('can login a user', function () {
    User::factory()->create([
        'email' => ($email = fake()->email()),
        'password' => ($password = fake()->password()),
    ]);

    postJson('/api/v1/auth/login', [
        'email' => $email,
        'password' => $password,
    ])
    ->assertStatus(200)
    ->assertJsonStructure([
        'success',
        'message',
        'data' => [
            'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
            'token',
        ],
    ])
    ->assertJsonMissing([
        'data.user.password',
    ]);
});




it('can logout a user', function () {
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    postJson('/api/v1/me/logout')
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
        ]);
});




it('can get authenticated user', function () {
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    getJson('/api/v1/me')
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'preference',
                    'created_at',
                    'updated_at',
                ],
            ],
        ])
        ->assertJsonMissing([
            'data.user.password',
        ]);
});




it('can get authenticated user with their preferences', function () {
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();
    UserPreference::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    getJson('/api/v1/me')
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'preference' => [
                        'sources',
                        'categories',
                        'authors',
                        'created_at',
                        'updated_at',
                    ],
                    'created_at',
                    'updated_at',
                ],
            ],
        ])
        ->assertJsonMissing([
            'data.user.password',
        ]);
});
