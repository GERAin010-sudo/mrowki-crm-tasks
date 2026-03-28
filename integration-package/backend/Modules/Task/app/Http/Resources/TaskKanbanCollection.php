<?php

namespace Modules\Task\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskKanbanCollection extends ResourceCollection
{
    protected $statuses;

    public function __construct($resource, $statuses)
    {
        parent::__construct($resource);
        $this->statuses = $statuses;
    }

    public function toArray($request): array
    {
        $grouped = $this->collection->groupBy('status_id');

        return $this->statuses->map(function ($status) use ($grouped) {
            return [
                'status' => $status,
                'tasks'  => TaskShortResource::collection($grouped->get($status->id, collect())),
            ];
        })->toArray();
    }
}
