<?php

namespace Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Task\Models\Task;

class TaskTimeEntryController extends Controller
{
    public function index(Task $task): JsonResponse
    {
        $entries = $task->timeEntries()->with('user.avatar')->orderBy('date', 'desc')->get();
        return response()->json(['data' => $entries]);
    }

    public function store(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'minutes'     => 'required|integer|min:1',
            'description' => 'nullable|string|max:500',
            'date'        => 'required|date',
        ]);

        $entry = $task->timeEntries()->create([
            'user_id' => auth()->id(),
            ...$validated,
        ]);

        $entry->load('user.avatar');

        return response()->json(['data' => $entry], 201);
    }
}
