<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Category;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_budget_analysis()
    {
        $user = User::factory()->create(['budget_method' => '50-30-20']);
        $category = Category::factory()->create([
            'type' => 'income',
            'user_id' => $user->id
        ]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => 10000
        ]);

        $this->actingAs($user);
        $response = $this->get(route('budget.analysis'));

        $response->assertStatus(200);
        $response->assertViewHas('budgetData');
    }
}
