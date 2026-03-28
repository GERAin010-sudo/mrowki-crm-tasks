<?php

namespace Modules\Task\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskShortResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'status'        => $this->whenLoaded('status'),
            'priority'      => $this->whenLoaded('priority'),
            'category'      => $this->whenLoaded('category'),
            'project'       => $this->whenLoaded('project', fn() => [
                'id'    => $this->project->id,
                'name'  => $this->project->name,
                'color' => $this->project->color,
            ]),
            'assignee'      => $this->whenLoaded('assignee'),
            'contragent'    => $this->whenLoaded('contragent', fn() => [
                'id'   => $this->contragent->id,
                'name' => $this->contragent->name ?? $this->contragent->company_name,
            ]),
            'assignee_type' => $this->assignee_type,
            'deadline'      => $this->deadline?->toISOString(),
            'created_at'    => $this->created_at?->toISOString(),
        ];
    }
}
