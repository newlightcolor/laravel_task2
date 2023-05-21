<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\TaskTag;
use App\Models\TaskTagContent;
use App\Models\TagContentsTasksHas;
use DateTime;

class TagContentsTasksHasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = Task::all();
        $tag_contents = TaskTagContent::all();

        $task_has_tags = [];
        foreach($tasks as $task){
            $task_has_tag = [];
            $task_has_tag['task_id'] = $task->id;
            $task_has_tag['tag_content_id'] = $tag_contents[rand(0, count($tag_contents)-1)]->id;
            $task_has_tag['created_at'] = new DateTime();
            $task_has_tag['updated_at'] = new DateTime();
            $task_has_tags[] = $task_has_tag;
        }

        TagContentsTasksHas::insert($task_has_tags);
    }
}
