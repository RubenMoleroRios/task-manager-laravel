<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_tasks(): void
    {
        Task::factory()->count(2)->create();

        $response = $this->getJson('/api/tasks');

        $response
            ->assertOk()
            ->assertJsonPath('message', 'Tasks retrieved successfully')
            ->assertJsonCount(2, 'data');
    }

    public function test_it_creates_a_task(): void
    {
        $payload = [
            'title' => 'Prepare Laravel interview demo',
            'description' => 'Build a simple CRUD with service layer.',
            'completed' => false,
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('message', 'Task created successfully')
            ->assertJsonPath('data.title', $payload['title'])
            ->assertJsonPath('data.completed', false);

        $this->assertDatabaseHas('tasks', [
            'title' => $payload['title'],
            'completed' => false,
        ]);
    }

    public function test_it_updates_a_task(): void
    {
        $task = Task::factory()->create([
            'completed' => false,
        ]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
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
        ]);
    }

    public function test_it_deletes_a_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

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
        $response = $this->postJson('/api/tasks', [
            'title' => '',
            'completed' => 'invalid',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'completed']);
    }
}
