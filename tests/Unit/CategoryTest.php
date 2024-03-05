<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/categories', [
            'name' => 'category test',
            'user_id' => $user->id,
            'family_id' => $user->family_id,
            'type' => 'needs',
            'scope' => 'personal'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', [
            'name' => 'category test',
            'user_id' => $user->id,
            'family_id' => $user->family_id,
        ]);
    }

    public function test_user_can_view_categories()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/categories');

        $response->assertStatus(200);
    }

    public function test_category_scope_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/categories', [
            'name' => 'Test Category',
            'type' => 'needs',
            'scope' => 'personal'
        ]);

        $response->assertStatus(201);
    }
}
