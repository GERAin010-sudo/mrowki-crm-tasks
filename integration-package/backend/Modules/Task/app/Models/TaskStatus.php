<?php

namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    protected $fillable = ['name', 'label', 'color', 'bg', 'position'];
}
