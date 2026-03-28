<?php

namespace Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Task\Http\Requests\Task\IndexRequest;
use Modules\Task\Http\Requests\Task\StoreRequest;
use Modules\Task\Http\Requests\Task\UpdateRequest;
use Modules\Task\Http\Requests\Task\MoveRequest;
use Modules\Task\Http\Resources\TaskResource;
use Modules\Task\Http\Resources\TaskShortResource;
use Modules\Task\Http\Resources\TaskKanbanCollection;
use Modules\Task\Models\Task;
use Modules\Task\Models\TaskStatus;
use Modules\Task\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $service,
    ) {
    }

    /**
     * List tasks with filters and pagination.
     */
    public function index(IndexRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $tasks = $this->service->index($validated);

        return TaskShortResource::collection($tasks)->response();
    }

    /**
     * Kanban view — grouped by statuses.
     */
    public function kanban(IndexRequest $request): JsonResponse
    {
        $attribute = $request->validated();
        $tasks = $this->service->all($attribute);
        $statuses = TaskStatus::orderBy('position')->get();

        return (new TaskKanbanCollection($tasks, $statuses))->response();
    }

    /**
     * Dashboard statistics.
     */
    public function dashboard(): JsonResponse
    {
        return response()->json(['data' => $this->service->dashboard()]);
    }

    /**
     * Store a new task.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $task = $this->service->store($validated);

        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    /**
     * Show task details with all relations.
     */
    public function show(Task $task): JsonResponse
    {
        $task->load([
            'status', 'priority', 'category', 'project',
            'creator.avatar', 'assignee.avatar', 'contragent.avatar',
            'assignees.avatar', 'watchers.avatar',
            'subtasks', 'comments.user.avatar', 'tags',
            'relations.relatedTask.status', 'timeEntries.user',
            'history.user',
        ]);

        return (new TaskResource($task))->response();
    }

    /**
     * Update a task.
     */
    public function update(UpdateRequest $request, Task $task): JsonResponse
    {
        $validated = $request->validated();
        $task = $this->service->update($task, $validated);

        return (new TaskResource($task))->response();
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->service->destroy($task);

        return (new TaskResource($task))->response();
    }

    /**
     * Move task position (kanban drag-n-drop).
     */
    public function move(MoveRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->service->move($validated);

        return response()->json();
    }
}
