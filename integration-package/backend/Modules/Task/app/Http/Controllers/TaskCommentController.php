<?php

namespace Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Task\Models\Task;
use Modules\Task\Models\TaskComment;

class TaskCommentController extends Controller
{
    public function index(Task $task): JsonResponse
    {
        $comments = $task->comments()->with('user.avatar')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $comments]);
    }

    public function store(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate(['text' => 'required|string|max:5000']);

        $comment = $task->comments()->create([
            'user_id' => auth()->id(),
            'text'    => $validated['text'],
        ]);

        $comment->load('user.avatar');

        return response()->json(['data' => $comment], 201);
    }
}
