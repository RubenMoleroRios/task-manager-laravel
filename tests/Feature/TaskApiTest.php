<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_tasks(): void
    {
        $user = User::factory()->create();

        Task::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);
        Task::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/tasks');

        $response
            ->assertOk()
            ->assertJsonPath('message', 'Tasks retrieved successfully')
            ->assertJsonCount(2, 'data');
    }

    public function test_it_creates_a_task(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Prepare Laravel interview demo',
            'description' => 'Build a simple CRUD with service layer.',
            'completed' => false,
        ];

        $response = $this->actingAs($user)->postJson('/api/tasks', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('message', 'Task created successfully')
            ->assertJsonPath('data.title', $payload['title'])
            ->assertJsonPath('data.completed', false)
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('tasks', [
            'title' => $payload['title'],
            'completed' => false,
            'user_id' => $user->id,
        ]);
    }

    public function test_it_updates_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'completed' => false,
        ]);

        $response = $this->actingAs($user)->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated title',
            'description' => 'Updated description',
            'completed' => true,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('message', 'Task updated successfully')
            ->assertJsonPath('data.completed', true);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated title',
            'completed' => true,
            'user_id' => $user->id,
        ]);
    }

    public function test_it_deletes_a_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/tasks/{$task->id}");

        $response
            ->assertOk()
            ->assertJsonPath('message', 'Task deleted successfully')
            ->assertJsonPath('data', null);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_it_returns_validation_errors_when_creating_a_task(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/tasks', [
            'title' => '',
            'completed' => 'invalid',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'completed']);
    }

    public function test_it_cannot_access_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($intruder)->getJson("/api/tasks/{$task->id}");

        $response
            ->assertNotFound()
            ->assertJsonPath('message', 'Task not found');
    }
}
