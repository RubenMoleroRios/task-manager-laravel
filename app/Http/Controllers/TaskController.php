<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->taskService->getAllTasks(),
            'message' => 'Tasks retrieved successfully',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        try {
            return response()->json([
                'data' => $this->taskService->getTaskById($id),
                'message' => 'Task retrieved successfully',
            ]);
        } catch (ModelNotFoundException) {
            return $this->taskNotFoundResponse();
        }
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());

        return response()->json([
            'data' => $task,
            'message' => 'Task created successfully',
        ], 201);
    }

    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        try {
            return response()->json([
                'data' => $this->taskService->updateTask($id, $request->validated()),
                'message' => 'Task updated successfully',
            ]);
        } catch (ModelNotFoundException) {
            return $this->taskNotFoundResponse();
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->taskService->deleteTask($id);

            return response()->json([
                'data' => null,
                'message' => 'Task deleted successfully',
            ]);
        } catch (ModelNotFoundException) {
            return $this->taskNotFoundResponse();
        }
    }

    private function taskNotFoundResponse(): JsonResponse
    {
        return response()->json([
            'data' => null,
            'message' => 'Task not found',
        ], 404);
    }
}
