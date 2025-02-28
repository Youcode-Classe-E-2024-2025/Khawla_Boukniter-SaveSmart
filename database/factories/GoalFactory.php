<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goal>
 */
class GoalFactory extends Factory
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
            'name' => fake()->randomElement(['goal1', 'goal2', 'goal3']),
            'current_amount' => fake()->numberBetween(0, 10000),
            'target_amount' => fake()->numberBetween(10000, 20000),
            'category' => fake()->randomElement(['category1', 'category2', 'category3']),
            'target_date' => fake()->dateTimeBetween('+1 month', '+1 year'),
            'description' => fake()->sentence(),
        ];
    }
}
