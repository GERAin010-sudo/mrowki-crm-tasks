<?php

use Illuminate\Support\Facades\Route;
use Modules\Task\Http\Controllers\TaskController;
use Modules\Task\Http\Controllers\TaskProjectController;
use Modules\Task\Http\Controllers\TaskCommentController;
use Modules\Task\Http\Controllers\TaskSubtaskController;
use Modules\Task\Http\Controllers\TaskTimeEntryController;
use Modules\Task\Models\TaskStatus;
use Modules\Task\Models\TaskPriority;
use Modules\Task\Models\TaskCategory;
use Modules\Task\Models\TaskTag;
use Modules\Task\Models\TaskTemplate;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    // ── Tasks ──
    Route::get('tasks/kanban', [TaskController::class, 'kanban'])->name('tasks.kanban');
    Route::get('tasks/dashboard', [TaskController::class, 'dashboard'])->name('tasks.dashboard');
    Route::post('tasks/move', [TaskController::class, 'move'])->name('tasks.move');
    Route::apiResource('tasks', TaskController::class);

    // ── Subtasks (nested) ──
    Route::post('tasks/{task}/subtasks', [TaskSubtaskController::class, 'store']);
    Route::put('tasks/{task}/subtasks/{subtask}', [TaskSubtaskController::class, 'update']);
    Route::delete('tasks/{task}/subtasks/{subtask}', [TaskSubtaskController::class, 'destroy']);

    // ── Comments (nested) ──
    Route::get('tasks/{task}/comments', [TaskCommentController::class, 'index']);
    Route::post('tasks/{task}/comments', [TaskCommentController::class, 'store']);

    // ── Time entries (nested) ──
    Route::get('tasks/{task}/time-entries', [TaskTimeEntryController::class, 'index']);
    Route::post('tasks/{task}/time-entries', [TaskTimeEntryController::class, 'store']);

    // ── Projects ──
    Route::apiResource('task-projects', TaskProjectController::class);

    // ── Lookups (read-only) ──
    Route::get('task-statuses', fn() => response()->json(['data' => TaskStatus::orderBy('position')->get()]));
    Route::get('task-priorities', fn() => response()->json(['data' => TaskPriority::orderBy('position')->get()]));
    Route::get('task-categories', fn() => response()->json(['data' => TaskCategory::all()]));
    Route::get('task-tags', fn() => response()->json(['data' => TaskTag::all()]));
    Route::get('task-templates', fn() => response()->json(['data' => TaskTemplate::all()]));

    // ── Apply template ──
    Route::post('task-templates/{template}/apply', function (TaskTemplate $template, \Illuminate\Http\Request $request) {
        $projectId = $request->input('project_id');
        $creatorId = auth()->id();
        $defaultStatusId = TaskStatus::where('name', 'new')->first()?->id;

        $created = [];
        foreach ($template->tasks_json as $taskDef) {
            $priorityId = TaskPriority::where('name', $taskDef['priority'] ?? 'medium')->first()?->id;
            $categoryId = TaskCategory::where('name', $taskDef['category'] ?? null)->first()?->id;

            $created[] = \Modules\Task\Models\Task::create([
                'title'       => $taskDef['title'],
                'status_id'   => $defaultStatusId,
                'priority_id' => $priorityId,
                'category_id' => $categoryId,
                'project_id'  => $projectId,
                'creator_id'  => $creatorId,
            ]);
        }

        return response()->json(['data' => $created, 'count' => count($created)], 201);
    })->name('task-templates.apply');
});
