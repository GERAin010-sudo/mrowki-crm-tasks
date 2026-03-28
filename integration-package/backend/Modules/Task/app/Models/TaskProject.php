<?php

namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Contragent\Models\Contragent;
use Modules\User\Models\User;

class TaskProject extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'type',
        'color',
        'creator_id',
        'coordinator_id',
        'contractor_name',
        'contragent_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function contragent(): BelongsTo
    {
        return $this->belongsTo(Contragent::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id');
    }
}
