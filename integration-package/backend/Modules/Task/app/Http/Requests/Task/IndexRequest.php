<?php
namespace Modules\Task\Http\Requests\Task;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'status_id'     => 'nullable|integer',
            'priority_id'   => 'nullable|integer',
            'category_id'   => 'nullable|integer',
            'project_id'    => 'nullable|integer',
            'assignee_id'   => 'nullable|integer',
            'creator_id'    => 'nullable|integer',
            'contragent_id' => 'nullable|integer',
            'search'        => 'nullable|string|max:255',
            'deadline_from' => 'nullable|date',
            'deadline_to'   => 'nullable|date',
            'sort'          => 'nullable|string|in:deadline,created_at,title,priority_id',
            'dir'           => 'nullable|string|in:asc,desc',
            'page'          => 'nullable|integer|min:1',
            'per_page'      => 'nullable|integer|min:1|max:100',
            'limit'         => 'nullable|integer|min:1',
        ];
    }
}
