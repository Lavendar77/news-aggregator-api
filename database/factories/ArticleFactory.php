<?php

namespace Database\Factories;

use App\Enums\ArticleApiSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'api_source' => fake()->randomElement(ArticleApiSource::cases()),
            'news_source' => fake()->company(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'url' => fake()->url(),
            'image_url' => fake()->imageUrl(640, 480, 'articles', true),
            'author' => fake()->name(),
            'category' => fake()->word(),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
