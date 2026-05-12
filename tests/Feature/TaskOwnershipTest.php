<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_only_sees_their_own_tasks_on_the_tasks_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Usuario Uno',
        ]);
        $otherUser = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Tarea visible',
        ]);

        Task::factory()->create([
            'user_id' => $otherUser->id,
            'title' => 'Tarea privada ajena',
        ]);

        $response = $this->actingAs($user)->get('/tasks');

        $response
            ->assertOk()
            ->assertSee('Tarea visible')
            ->assertDontSee('Tarea privada ajena');
    }

    public function test_new_tasks_are_attached_to_the_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'Tarea propia',
            'description' => 'Solo para este usuario',
            'completed' => false,
        ]);

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'title' => 'Tarea propia',
            'user_id' => $user->id,
        ]);
    }
}
