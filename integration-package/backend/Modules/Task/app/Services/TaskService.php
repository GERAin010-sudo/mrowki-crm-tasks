<?php

namespace Modules\Task\Services;

use App\Services\Common\RankService;
use App\Services\CRUD\AbstractCRUDService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Modules\Task\Models\Task;

class TaskService extends AbstractCRUDService
{
    public string $model = Task::class;

    public function __construct(protected RankService $rankService)
    {
    }

    protected function with(Builder $query): Builder
    {
        return $query->with([
            'status',
            'priority',
            'category',
            'project',
            'assignee.avatar',
            'creator.avatar',
            'contragent.avatar',
        ]);
    }

    /**
     * Custom filters for task listing.
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if ($value = Arr::get($filters, 'status_id')) {
            $query->where('status_id', $value);
        }
        if ($value = Arr::get($filters, 'priority_id')) {
            $query->where('priority_id', $value);
        }
        if ($value = Arr::get($filters, 'category_id')) {
            $query->where('category_id', $value);
        }
        if ($value = Arr::get($filters, 'project_id')) {
            $query->where('project_id', $value);
        }
        if ($value = Arr::get($filters, 'assignee_id')) {
            $query->where('assignee_id', $value);
        }
        if ($value = Arr::get($filters, 'creator_id')) {
            $query->where('creator_id', $value);
        }
        if ($value = Arr::get($filters, 'contragent_id')) {
            $query->where('contragent_id', $value);
        }
        if ($value = Arr::get($filters, 'search')) {
            $query->where(function ($q) use ($value) {
                $q->where('title', 'like', "%{$value}%")
                  ->orWhere('description', 'like', "%{$value}%");
            });
        }
        if ($value = Arr::get($filters, 'deadline_from')) {
            $query->where('deadline', '>=', $value);
        }
        if ($value = Arr::get($filters, 'deadline_to')) {
            $query->where('deadline', '<=', $value);
        }

        return $query;
    }

    /**
     * Dashboard statistics.
     */
    public function dashboard(): array
    {
        $tasks = Task::query();

        return [
            'total'       => (clone $tasks)->count(),
            'in_progress' => (clone $tasks)->whereHas('status', fn($q) => $q->where('name', 'in_progress'))->count(),
            'overdue'     => (clone $tasks)->where('deadline', '<', now())->whereHas('status', fn($q) => $q->whereNotIn('name', ['done']))->count(),
            'done'        => (clone $tasks)->whereHas('status', fn($q) => $q->where('name', 'done'))->count(),
        ];
    }

    /**
     * Move task position (kanban drag-n-drop).
     *
     * @throws \Throwable
     */
    public function move(array $data): void
    {
        $task = Task::query()->findOrFail(Arr::get($data, 'task_id'));
        $prev = Task::query()->find(Arr::get($data, 'prev_id'));
        $next = Task::query()->find(Arr::get($data, 'next_id'));
        $statusId = (int) Arr::get($data, 'status_id');

        $this->rankService->moveBetween($task, $prev, $next, $statusId, 'status_id');
    }
}
