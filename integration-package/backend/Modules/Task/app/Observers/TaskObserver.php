<?php

namespace Modules\Task\Observers;

use Illuminate\Support\Facades\Auth;
use Modules\Task\Models\Task;
use Modules\Task\Models\TaskHistory;

class TaskObserver
{
    /**
     * Track field changes automatically.
     */
    public function updating(Task $task): void
    {
        $tracked = ['title', 'status_id', 'priority_id', 'category_id', 'project_id', 'assignee_id', 'deadline'];

        foreach ($tracked as $field) {
            if ($task->isDirty($field)) {
                TaskHistory::create([
                    'task_id'   => $task->id,
                    'user_id'   => Auth::id(),
                    'field'     => $field,
                    'old_value' => (string) $task->getOriginal($field),
                    'new_value' => (string) $task->getAttribute($field),
                ]);
            }
        }
    }
}
