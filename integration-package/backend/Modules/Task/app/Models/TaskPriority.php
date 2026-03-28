<?php

namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Model;

class TaskPriority extends Model
{
    protected $table = 'task_priorities';
    protected $fillable = ['name', 'label', 'color', 'icon', 'position'];
}
