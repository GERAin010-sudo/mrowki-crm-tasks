<?php
namespace Modules\Task\Models;
use Illuminate\Database\Eloquent\Model;

class TaskTag extends Model
{
    protected $fillable = ['label', 'color'];
}
