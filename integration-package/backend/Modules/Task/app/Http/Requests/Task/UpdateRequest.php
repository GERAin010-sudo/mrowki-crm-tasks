<?php
namespace Modules\Task\Http\Requests\Task;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'              => 'sometimes|string|max:500',
            'description'        => 'nullable|string',
            'status_id'          => 'nullable|exists:task_statuses,id',
            'priority_id'        => 'nullable|exists:task_priorities,id',
            'category_id'        => 'nullable|exists:task_categories,id',
            'project_id'         => 'nullable|exists:task_projects,id',
            'assignee_id'        => 'nullable|exists:users,id',
            'assignee_type'      => 'nullable|in:user,team,department',
            'contragent_id'      => 'nullable|exists:contragents,id',
            'deadline'           => 'nullable|date',
            'linked_entity_type' => 'nullable|string|max:50',
            'linked_entity_id'   => 'nullable|integer',
            'linked_entity_name' => 'nullable|string|max:255',
            'assignee_ids'       => 'nullable|array',
            'assignee_ids.*'     => 'integer|exists:users,id',
            'tag_ids'            => 'nullable|array',
            'tag_ids.*'          => 'integer|exists:task_tags,id',
        ];
    }
}
