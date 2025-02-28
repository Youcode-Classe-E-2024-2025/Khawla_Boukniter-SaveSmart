<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'family_id' => $user->family_id,
            'category' => Category::factory(),
            'type' => fake()->randomElement(['income', 'expense']),
            'amount' => fake()->randomFloat(2, 10, 999999),
            'description' => fake()->sentence(),
        ];
    }
}
