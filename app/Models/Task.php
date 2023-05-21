<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    public function select_with_tag_content(){
        return static::select('tasks.id', 'tasks.content', 'tasks.deadline_at', 'tasks.created_at')
                     ->addSelect('task_tag.name')
                     ->addSelect('task_tag_content.content as tag_content')
                     ->leftJoin('tag_contents_tasks_has', 'tag_contents_tasks_has.task_id', '=', 'tasks.id')
                     ->leftJoin('task_tag_content',       'task_tag_content.id',            '=', 'tag_contents_tasks_has.tag_content_id')
                     ->leftJoin('task_tag',               'task_tag.id',                    '=', 'task_tag_content.tag_id')
                     ->GroupBy('tasks.id');
    }
}
