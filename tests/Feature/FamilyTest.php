<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Family;
use App\Models\User;



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
        $this->actingAs($user);

        $response = $this->get(route('family.index'));
        $response->assertStatus(200);
        $response->assertViewIs('family.index');
    }
}
