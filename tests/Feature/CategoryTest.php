<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('categories.store'), [
            'name' => 'category test',
            'user_id' => $user->id,
            'family_id' => $user->family_id
        ]);

        $response->assertStatus(201);
        $response->assertRedirect(route('transactions'));
        $response->assertDatabaseHas('categories', [
            'name' => 'category test',
            'user_id' => $user->id,
            'family_id' => $user->family_id,
        ]);
    }
}
