<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use Datetime;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'content' => '買い物に行く',
                'deadline_at' => date('Y-m-d').' 19:00:00',
                'parent_task_id' => NULL,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'content' => '勉強',
                'deadline_at' => date('Y-m-d').' 15:00:00',
                'parent_task_id' => NULL,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'content' => '掃除',
                'deadline_at' => date('Y-m-d').' 19:00:00',
                'parent_task_id' => NULL,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
            ];

            Task::insert($tasks);
    }
}
