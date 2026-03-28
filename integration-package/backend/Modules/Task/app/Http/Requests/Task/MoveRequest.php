<?php
namespace Modules\Task\Http\Requests\Task;
use Illuminate\Foundation\Http\FormRequest;

class MoveRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id'   => 'required|integer|exists:tasks,id',
            'prev_id'   => 'nullable|integer|exists:tasks,id',
            'next_id'   => 'nullable|integer|exists:tasks,id',
            'status_id' => 'required|integer|exists:task_statuses,id',
        ];
    }
}
