<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Goal;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_goal(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('goals.store'), [
            'name' => 'goal test',
            'target_amount' => 1000,
            'current_amount' => 0,
            'category' => 'category',
            'target_date' => '2025-5-30',
            'user_id' => $user->id,
            'family_id' => $user->family_id,
        ]);

        $response->assertRedirect(route('goals.index'));
        $this->assertDatabaseHas('goals', [
            'name' => 'goal test',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_update_goal()
    {
        $user = User::factory()->create();
        $goal = Goal::factory()->create([
            'user_id' => $user->id,
            'family_id' => $user->family_id,
        ]);

        $this->actingAs($user);

        $response = $this->put(route('goals.update', $goal), [
            'name' => $goal->name,
            'target_amount' => $goal->target_amount,
            'current_amount' => 5060,
            'category' => $goal->category,
            'target_date' => '2025-06-30'
        ]);

        $response->assertRedirect(route('goals.index'));
        $this->assertEquals(5060, $goal->fresh()->current_amount);
        $this->assertEquals('2025-06-30', $goal->fresh()->target_date->format('Y-m-d'));
    }

    public function test_user_can_delete_goal()
    {
        $user = User::factory()->create();
        $goal = Goal::factory()->create([
            'user_id' => $user->id,
            'family_id' => $user->family_id,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('goals.destroy', $goal));

        $response->assertRedirect(route('goals.index'));
        $this->assertDatabaseMissing('goals', ['id' => $goal->id]);
    }

    public function test_goal_progress_tracking()
    {
        $user = User::factory()->create();
        $goal = Goal::factory()->create([
            'user_id' => $user->id,
            'family_id' => $user->family_id,
            'target_amount' => 10000,
            'current_amount' => 5000
        ]);

        $this->actingAs($user);

        $response = $this->get(route('goals.index'));

        $response->assertStatus(200);
        $response->assertViewHas('goals');
        $this->assertEquals(50, ($goal->current_amount / $goal->target_amount) * 100);
    }
}
