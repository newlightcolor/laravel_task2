<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskTag;
use DateTime;

class TaskTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $tags = [
            [
                'name' => 'priority',
                'max_select_content' => 1,
                'use_order_by_column' => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        ];

        TaskTag::insert($tags);
    }
}