<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Category;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_monthly_stats()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create([
            'type' => 'income',
            'name' => 'Salary',
            'scope' => 'personal',
            'user_id' => $user->id
        ]);

        Transaction::factory()->count(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => 1000
        ]);

        $this->actingAs($user);
        $response = $this->get(route('statistics.index'));

        $response->assertStatus(200);
        $response->assertViewHas('monthlyStats');
    }

    public function test_user_can_view_category_stats()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create([
            'type' => 'needs',
            'user_id' => $user->id
        ]);

        Transaction::factory()->count(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 500
        ]);

        $this->actingAs($user);
        $response = $this->get(route('statistics.index'));

        $response->assertStatus(200);
        $response->assertViewHas('categoryStats');
        $this->assertEquals(1500, $response['categoryStats']->first()->total);
    }
}
