<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Category;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_transaction()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('transactions.store'), [
            'type' => 'income',
            'newCategory' => 'categorytest',
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

    public function test_user_can_delete_transaction()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'family_id' => $user->family_id,
            'category_id' => $category->id
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('transactions.destroy', $transaction));

        $response->assertRedirect(route('transactions.index'));
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    public function test_user_can_edit_transaction()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['type' => 'income']);
        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'family_id' => $user->family_id,
            'type' => 'income',
            'category_id' => $category->id,
            'amount' => 1000
        ]);

        $this->actingAs($user);

        $response = $this->put(route('transactions.update', $transaction), [
            'type' => 'expense',
            'category_id' => (string)$category->id,
            'amount' => 2000,
            'description' => 'Updated transaction',
            'goal_id' => null,
            'goal_contribution' => false
        ]);

        $response->assertRedirect(route('transactions.index'));
        $this->assertEquals(2000, $transaction->fresh()->amount);
    }

    public function test_transaction_budget_limits()
    {
        $user = User::factory()->create(['budget_method' => '50-30-20']);
        $this->actingAs($user);

        $this->post(route('transactions.store'), [
            'type' => 'income',
            'category_id' => Category::factory()->create(['type' => 'income'])->id,
            'amount' => 10000
        ]);

        $response = $this->post(route('transactions.store'), [
            'type' => 'expense',
            'category_id' => Category::factory()->create(['type' => 'needs'])->id,
            'amount' => 6000
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_user_can_export_pdf()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Transaction::factory()->count(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'family_id' => $user->family_id
        ]);

        $this->actingAs($user);
        $response = $this->get(route('transactions.export.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_user_can_export_csv()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Transaction::factory()->count(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'family_id' => $user->family_id
        ]);

        $this->actingAs($user);
        $response = $this->get(route('transactions.export.csv'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
