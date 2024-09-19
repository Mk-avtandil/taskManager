<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Resources\TaskCollection;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    public function index(Request $request): JsonResponse|TaskCollection
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $query = Task::where('user_id', $user->id)->with('user');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority !== null) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->get();

        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'User has no tasks.'], 404);
        }

        return new TaskCollection($tasks);
    }


    public function save(TaskCreateRequest $request): JsonResponse
    {
        try {
            Task::create([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'priority' => $request->get('priority'),
                'status' => $request->get('status'),
                'user_id' => $request->get('user_id'),
            ]);
            return response()->json([
                'message' => 'Task created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function detail(Task $task): JsonResponse
    {
        try {
            $task = $task->load("user");

            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve task details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Task $task): JsonResponse
    {
        try {
            return response()->json($task, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to edit task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Task $task, TaskCreateRequest $request): JsonResponse
    {
        try {
            $task->update([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'priority' => $request->get('priority'),
                'status' => $request->get('status'),
                'user_id' => $request->get('user_id'),
            ]);

            return response()->json([
                "message" => "Task updated successfully",
                "task" => $task
            ]);
            } catch (\Exception $e) {
                return response()->json([
                    "message" => "Failed to update task",
                    "error" => $e->getMessage()
                ], 500);
            }
    }

    public function delete(Task $task): JsonResponse
    {
        try {
            $task->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Failed to delete task",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
