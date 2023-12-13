<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskLog extends Model
{
    use HasFactory;

    protected $table = "task_logs";

    protected $guarded = ['id'];

}
