<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::factory()->create();

        return [
            'name' => $this->faker->word,
            'type' => $this->faker->randomElement(['needs', 'wants', 'savings', 'income']),
            'scope' => 'personal',
            'user_id' => $user->id,
            'family_id' => $user->family_id
        ];
    }
}
