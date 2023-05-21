<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskTag;
use App\Models\TaskTagContent;
use DateTime;

class TaskTagContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $tag_contents = [
            [
                'tag_id' => TaskTag::all()->random()->id,
                'content' => 'High',
                'content_color' => '#dc3545',
                'for_order_by' => '300',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'tag_id' => TaskTag::all()->random()->id,
                'content' => 'Medium',
                'content_color' => '#ffc107',
                'for_order_by' => '200',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'tag_id' => TaskTag::all()->random()->id,
                'content' => 'Low',
                'content_color' => '#0d6efd',
                'for_order_by' => '100',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        ];

        TaskTagContent::insert($tag_contents);
    }
}
