<?php

namespace Modules\Task\Models;

use App\Concerns\Models\CreatorTrait;
use App\Concerns\Models\HasPosition;
use App\Contracts\Models\PositionableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Contragent\Models\Contragent;
use Modules\User\Models\User;

class Task extends Model implements PositionableInterface
{
    use HasFactory;
    use CreatorTrait;
    use HasPosition;

    protected $fillable = [
        'title',
        'description',
        'status_id',
        'priority_id',
        'category_id',
        'project_id',
        'creator_id',
        'assignee_id',
        'assignee_type',
        'contragent_id',
        'deadline',
        'position',
        'linked_entity_type',
        'linked_entity_id',
        'linked_entity_name',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    // ── Relations ──

    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(TaskPriority::class, 'priority_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class, 'category_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(TaskProject::class, 'project_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function contragent(): BelongsTo
    {
        return $this->belongsTo(Contragent::class);
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_assignees');
    }

    public function watchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_watchers');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(TaskSubtask::class)->orderBy('position');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->orderBy('created_at', 'desc');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(TaskTag::class, 'task_tag_task');
    }

    public function relations(): HasMany
    {
        return $this->hasMany(TaskRelation::class, 'task_id');
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TaskTimeEntry::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(TaskHistory::class)->orderBy('created_at', 'desc');
    }
}
