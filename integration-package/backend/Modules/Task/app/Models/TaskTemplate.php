<?php
namespace Modules\Task\Models;
use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    protected $fillable = ['name', 'description', 'icon', 'color', 'tasks_json'];
    protected $casts = ['tasks_json' => 'array'];
}
