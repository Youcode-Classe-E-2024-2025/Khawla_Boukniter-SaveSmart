<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Transaction;
use App\Models\User;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_transaction()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('transactions.store'), [
            'type' => 'income',
            'category' => 'categorytest',
            'amount' => 2444,
            'description' => 'test',
        ]);

        $response->assertRedirect(route('transactions.index'));
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 2444,
        ]);
    }

    public function test_user_can_view_transaction()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Transaction::factory()->count(3)->create(['user_id' => $user->id, 'family_id' => $user->family_id]);

        $response = $this->get(route('transactions.index'));

        $response->assertStatus(200);
        $response->assertViewHas('transactions');
    }
}
