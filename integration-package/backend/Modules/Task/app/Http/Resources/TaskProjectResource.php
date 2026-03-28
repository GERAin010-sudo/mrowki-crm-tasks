<?php

namespace Modules\Task\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskProjectResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'description'     => $this->description,
            'status'          => $this->status,
            'type'            => $this->type,
            'color'           => $this->color,
            'creator'         => $this->whenLoaded('creator'),
            'coordinator'     => $this->whenLoaded('coordinator'),
            'contragent'      => $this->whenLoaded('contragent'),
            'contractor_name' => $this->contractor_name,
            'tasks_count'     => $this->when($this->tasks_count !== null, $this->tasks_count),
            'created_at'      => $this->created_at?->toISOString(),
            'updated_at'      => $this->updated_at?->toISOString(),
        ];
    }
}
