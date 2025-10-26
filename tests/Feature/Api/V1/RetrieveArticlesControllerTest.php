<?php

use App\Enums\ArticleApiSource;
use App\Models\Article;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

uses(DatabaseMigrations::class);

it('can retrieve articles as a guest', function () {
    Article::factory()->count(10)->create();

    getJson('/api/v1/articles')
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'api_source',
                        'news_source',
                        'title',
                        'description',
                        'content',
                        'url',
                        'image_url',
                        'author',
                        'category',
                        'published_at',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);
});




it('can retrieve articles as an authenticated user', function () {
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();
    UserPreference::factory()->create([
        'user_id' => $user->id,
        'sources' => [$source1 = fake()->word()],
        'categories' => null,
        'authors' => null,
    ]);

    Sanctum::actingAs($user);

    Article::factory()->count(10)->create();
    Article::factory()->count(3)->create([
        'news_source' => $source1,
    ]);

    getJson('/api/v1/articles')
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'api_source',
                        'news_source',
                        'title',
                        'description',
                        'content',
                        'url',
                        'image_url',
                        'author',
                        'category',
                        'published_at',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ])
        ->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has('data.data', 3, fn (AssertableJson $json) => $json->where('news_source', $source1)->etc())
                ->etc()
        );
});




it('can filter articles by api source', function () {
    $apiSources = ArticleApiSource::cases();

    Article::factory()->count(2)->create([
        'api_source' => $apiSource = $apiSources[array_rand($apiSources)],
    ]);
    Article::factory()->count(10)->create([
        'api_source' => $apiSources[array_rand(
            array_filter($apiSources, fn ($as) => $as->value !== $apiSource->value)
        )],
    ]);

    $query = http_build_query([
        'api_source' => $apiSource->value,
    ]);

    getJson("/api/v1/articles?{$query}")
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has('data.data', 2, fn (AssertableJson $json) => $json->where('api_source', $apiSource->value)->etc())
                ->etc()
        );
});




it('can filter articles by news source', function () {
    Article::factory()->count(2)->create([
        'news_source' => $newsSource = fake()->company(),
    ]);
    Article::factory()->count(10)->create([
        'news_source' => fake()->company() . ' XXX',
    ]);

    $query = http_build_query([
        'source' => $newsSource,
    ]);

    getJson("/api/v1/articles?{$query}")
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has('data.data', 2, fn (AssertableJson $json) => $json->where('news_source', $newsSource)->etc())
                ->etc()
        );
});




it('can filter articles by category', function () {
    Article::factory()->count(2)->create([
        'category' => $category = fake()->word(),
    ]);
    Article::factory()->count(10)->create([
        'category' => fake()->word() . ' XXX',
    ]);

    $query = http_build_query([
        'category' => $category,
    ]);

    getJson("/api/v1/articles?{$query}")
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has('data.data', 2, fn (AssertableJson $json) => $json->where('category', $category)->etc())
                ->etc()
        );
});




it('can filter articles by from date', function () {
    Article::factory()->count(2)->create([
        'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
    ]);
    Article::factory()->count(5)->create([
        'published_at' => fake()->dateTimeBetween('-1 year', '-2 months'),
    ]);

    $query = http_build_query([
        'from_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'),
    ]);

    getJson("/api/v1/articles?{$query}")
        ->assertStatus(200)
        ->assertJsonCount(2, 'data.data');
});




it('can filter articles by to date', function () {
    Article::factory()->count(2)->create([
        'published_at' => fake()->dateTimeThisMonth(),
    ]);
    Article::factory()->count(5)->create([
        'published_at' => fake()->dateTimeBetween('1 month', '2 months'),
    ]);

    $query = http_build_query([
        'to_date' => now()->endOfMonth()->format('Y-m-d'),
    ]);

    getJson("/api/v1/articles?{$query}")
        ->assertStatus(200)
        ->assertJsonCount(2, 'data.data');
});




it('can retrieve articles based on user preference: sources', function () {
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();
    UserPreference::factory()->create([
        'user_id' => $user->id,
        'sources' => [$source1 = fake()->word()],
        'categories' => null,
        'authors' => null,
    ]);

    Sanctum::actingAs($user);

    Article::factory()->count(10)->create();
    Article::factory()->count(5)->create([
        'news_source' => $source1,
    ]);

    getJson('/api/v1/articles')
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has('data.data', 5, fn (AssertableJson $json) => $json->where('news_source', $source1)->etc())
                ->etc()
        );
});




it('can retrieve articles based on user preference: categories', function () {
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();
    UserPreference::factory()->create([
        'user_id' => $user->id,
        'sources' => null,
        'categories' => [$category1 = fake()->word()],
        'authors' => null,
    ]);

    Sanctum::actingAs($user);

    Article::factory()->count(10)->create();
    Article::factory()->count(5)->create([
        'category' => $category1,
    ]);

    getJson('/api/v1/articles')
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has('data.data', 5, fn (AssertableJson $json) => $json->where('category', $category1)->etc())
                ->etc()
        );
});




it('can retrieve articles based on user preference: authors', function () {
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();
    UserPreference::factory()->create([
        'user_id' => $user->id,
        'sources' => null,
        'categories' => null,
        'authors' => [$author1 = fake()->name()],
    ]);

    Sanctum::actingAs($user);

    Article::factory()->count(10)->create();
    Article::factory()->count(5)->create([
        'author' => $author1,
    ]);

    getJson('/api/v1/articles')
        ->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has('data.data', 5, fn (AssertableJson $json) => $json->where('author', $author1)->etc())
                ->etc()
        );
});
