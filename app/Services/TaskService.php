<?php

namespace App\Services;

use Illuminate\Auth\AuthenticationException;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    /**
     * @return Collection<int, Task>
     */
    public function getAllTasks(): Collection
    {
        return Task::query()
            ->where('user_id', $this->userId())
            ->latest()
            ->get();
    }

    public function getTaskById(int $id): Task
    {
        return Task::query()
            ->where('user_id', $this->userId())
            ->findOrFail($id);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createTask(array $data): Task
    {
        return Task::query()->create([
            ...$data,
            'user_id' => $this->userId(),
        ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateTask(int $id, array $data): Task
    {
        $task = $this->getTaskById($id);
        $task->update($data);

        return $task->refresh();
    }

    public function deleteTask(int $id): void
    {
        $task = $this->getTaskById($id);
        $task->delete();
    }

    public function toggleTaskCompletion(int $id): Task
    {
        $task = $this->getTaskById($id);
        $task->update([
            'completed' => ! $task->completed,
        ]);

        return $task->refresh();
    }

    /**
     * @throws AuthenticationException
     */
    private function userId(): int
    {
        $userId = Auth::id();

        if ($userId === null) {
            throw new AuthenticationException();
        }

        return $userId;
    }
}
