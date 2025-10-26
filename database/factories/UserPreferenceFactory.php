<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPreference>
 */
class UserPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'sources' => fake()->randomElements([fake()->word(), fake()->word(), fake()->word()], 1),
            'categories' => fake()->randomElements([fake()->word(), fake()->word(), fake()->word()], 1),
            'authors' => fake()->randomElements([fake()->name(), fake()->name(), fake()->name()], 1),
        ];
    }
}
