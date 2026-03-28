<?php

namespace Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Task\Http\Resources\TaskProjectResource;
use Modules\Task\Models\TaskProject;
use Modules\Task\Services\TaskProjectService;
use Illuminate\Http\Request;

class TaskProjectController extends Controller
{
    public function __construct(protected TaskProjectService $service) {}

    public function index(Request $request): JsonResponse
    {
        $projects = $this->service->index($request->all());
        return TaskProjectResource::collection($projects)->response();
    }

    public function store(Request $request): JsonResponse
    {
        $project = $this->service->store($request->validated());
        return (new TaskProjectResource($project))->response()->setStatusCode(201);
    }

    public function show(TaskProject $taskProject): JsonResponse
    {
        $taskProject->load(['creator.avatar', 'coordinator.avatar', 'contragent.avatar']);
        $taskProject->loadCount('tasks');
        return (new TaskProjectResource($taskProject))->response();
    }

    public function update(Request $request, TaskProject $taskProject): JsonResponse
    {
        $project = $this->service->update($taskProject, $request->all());
        return (new TaskProjectResource($project))->response();
    }

    public function destroy(TaskProject $taskProject): JsonResponse
    {
        $this->service->destroy($taskProject);
        return (new TaskProjectResource($taskProject))->response();
    }
}
