<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskWebController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    public function index(): View
    {
        return view('tasks.index', [
            'tasks' => $this->taskService->getAllTasks(),
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->taskService->createTask($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('status', 'Tarea creada correctamente');
    }

    public function toggle(int $id): RedirectResponse
    {
        try {
            $task = $this->taskService->toggleTaskCompletion($id);

            return redirect()
                ->route('tasks.index')
                ->with('status', $task->completed ? 'Tarea marcada como completada' : 'Tarea marcada como pendiente');
        } catch (ModelNotFoundException) {
            return redirect()
                ->route('tasks.index')
                ->with('status', 'Tarea no encontrada');
        }
    }

    public function update(UpdateTaskRequest $request, int $id): RedirectResponse
    {
        try {
            $this->taskService->updateTask($id, $request->validated());

            return redirect()
                ->route('tasks.index')
                ->with('status', 'Tarea actualizada correctamente');
        } catch (ModelNotFoundException) {
            return redirect()
                ->route('tasks.index')
                ->with('status', 'Tarea no encontrada');
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->taskService->deleteTask($id);

            return redirect()
                ->route('tasks.index')
                ->with('status', 'Tarea eliminada correctamente');
        } catch (ModelNotFoundException) {
            return redirect()
                ->route('tasks.index')
                ->with('status', 'Tarea no encontrada');
        }
    }
}
