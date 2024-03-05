<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Family;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;



class FamilyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_family()
    {
        $user = User::factory()->create(['account_type' => 'family']);
        $this->actingAs($user);

        $response = $this->post(route('family.store'), ['family_name' => 'test family']);

        $response->assertRedirect(route('family.index'));

        $this->assertDatabaseHas('families', [
            'name' => 'test family',
            'owner_id' => $user->id
        ]);
    }

    public function test_user_can_view_family_dash()
    {
        $user = User::factory()->create(['account_type' => 'family']);
        $family = Family::factory()->create(['owner_id' => $user->id]);
        $user->update(['family_id' => $family->id]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'family_id' => $family->id,
            'type' => 'income',
            'amount' => 1000,
            'category_id' => Category::factory()->create(['type' => 'income'])->id
        ]);

        $this->actingAs($user);

        $response = $this->get(route('family.index'));
        $response->assertStatus(200);
        $response->assertViewIs('family.index');
    }

    public function test_user_can_join_family()
    {
        $owner = User::factory()->create(['account_type' => 'family']);
        $family = Family::factory()->create([
            'owner_id' => $owner->id,
            'invitation_code' => Family::generateCode()
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('family.join'), [
            'invitation_code' => $family->invitation_code
        ]);

        $response->assertRedirect(route('family.index'));
        $this->assertEquals($family->id, $user->fresh()->family_id);
    }

    public function test_owner_can_update_budget_method()
    {
        $user = User::factory()->create(['account_type' => 'family']);
        $family = Family::factory()->create(['owner_id' => $user->id]);
        $user->update(['family_id' => $family->id]);

        $this->actingAs($user);

        $response = $this->post(route('family.updateBudgetMethod'), [
            'budget_method' => 'intelligent-allocation'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('intelligent-allocation', $user->fresh()->budget_method);
    }
}
