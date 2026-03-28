<?php
namespace Modules\Task\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

class TaskHistory extends Model
{
    protected $table = 'task_history';
    protected $fillable = ['task_id', 'user_id', 'field', 'old_value', 'new_value'];

    public function task(): BelongsTo { return $this->belongsTo(Task::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
