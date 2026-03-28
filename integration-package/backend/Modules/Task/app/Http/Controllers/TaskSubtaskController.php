<?php

namespace Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Task\Models\Task;
use Modules\Task\Models\TaskSubtask;

class TaskSubtaskController extends Controller
{
    public function store(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'text'     => 'required|string|max:500',
            'position' => 'nullable|integer',
        ]);

        $subtask = $task->subtasks()->create($validated);
        return response()->json(['data' => $subtask], 201);
    }

    public function update(Request $request, Task $task, TaskSubtask $subtask): JsonResponse
    {
        $validated = $request->validate([
            'text'    => 'sometimes|string|max:500',
            'is_done' => 'sometimes|boolean',
        ]);

        $subtask->update($validated);
        return response()->json(['data' => $subtask]);
    }

    public function destroy(Task $task, TaskSubtask $subtask): JsonResponse
    {
        $subtask->delete();
        return response()->json(null, 204);
    }
}
