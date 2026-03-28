<?php

namespace Modules\Task\Services;

use App\Services\CRUD\AbstractCRUDService;
use Illuminate\Database\Eloquent\Builder;
use Modules\Task\Models\TaskProject;

class TaskProjectService extends AbstractCRUDService
{
    public string $model = TaskProject::class;

    protected function with(Builder $query): Builder
    {
        return $query->with(['creator.avatar', 'coordinator.avatar', 'contragent.avatar'])
                     ->withCount('tasks');
    }
}
