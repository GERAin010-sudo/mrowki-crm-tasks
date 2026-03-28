<?php
namespace Modules\Task\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskSubtask extends Model
{
    protected $fillable = ['task_id', 'text', 'is_done', 'position'];
    protected $casts = ['is_done' => 'boolean'];

    public function task(): BelongsTo { return $this->belongsTo(Task::class); }
}
