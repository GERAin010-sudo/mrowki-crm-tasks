<?php

namespace Modules\Task\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->whenLoaded('status'),
            'priority'    => $this->whenLoaded('priority'),
            'category'    => $this->whenLoaded('category'),
            'project'     => new TaskProjectResource($this->whenLoaded('project')),
            'creator'     => $this->whenLoaded('creator'),
            'assignee'    => $this->whenLoaded('assignee'),
            'contragent'  => $this->whenLoaded('contragent'),
            'assignee_type'      => $this->assignee_type,
            'assignees'          => $this->whenLoaded('assignees'),
            'watchers'           => $this->whenLoaded('watchers'),
            'subtasks'           => $this->whenLoaded('subtasks'),
            'comments'           => $this->whenLoaded('comments'),
            'tags'               => $this->whenLoaded('tags'),
            'relations'          => $this->whenLoaded('relations'),
            'time_entries'       => $this->whenLoaded('timeEntries'),
            'history'            => $this->whenLoaded('history'),
            'deadline'           => $this->deadline?->toISOString(),
            'linked_entity_type' => $this->linked_entity_type,
            'linked_entity_id'   => $this->linked_entity_id,
            'linked_entity_name' => $this->linked_entity_name,
            'created_at'         => $this->created_at?->toISOString(),
            'updated_at'         => $this->updated_at?->toISOString(),
        ];
    }
}
