<?php
namespace Modules\Task\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

class TaskTimeEntry extends Model
{
    protected $fillable = ['task_id', 'user_id', 'minutes', 'description', 'date'];
    protected $casts = ['date' => 'date'];

    public function task(): BelongsTo { return $this->belongsTo(Task::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
