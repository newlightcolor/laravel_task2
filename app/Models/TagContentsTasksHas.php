<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagContentsTasksHas extends Model
{
    use HasFactory;

    public function all_contents(){
        $tagContentsTasksHas = new self();
        $tagContentsTasksHas = $tagContentsTasksHas->select('tag_contents_tasks_has.task_id');
        $tagContentsTasksHas = $tagContentsTasksHas->addSelect('tag_contents_tasks_has.tag_content_id');
        $tagContentsTasksHas = $tagContentsTasksHas->addSelect('task_tag.id as tag_id');
        $tagContentsTasksHas = $tagContentsTasksHas->addSelect('task_tag.name as tag_name');
        $tagContentsTasksHas = $tagContentsTasksHas->addSelect('task_tag.use_order_by_column');
        $tagContentsTasksHas = $tagContentsTasksHas->addSelect('task_tag_content.content');
        $tagContentsTasksHas = $tagContentsTasksHas->addSelect('task_tag_content.content_color');
        $tagContentsTasksHas = $tagContentsTasksHas->leftJoin('task_tag_content', 'task_tag_content.id', '=', 'tag_contents_tasks_has.tag_content_id');
        $tagContentsTasksHas = $tagContentsTasksHas->leftJoin('task_tag', 'task_tag.id', '=', 'task_tag_content.tag_id');
        return $tagContentsTasksHas->get();
    }

    public function select_contents($where = []){
        $tagContentsTasksHas = new self();
        $tagContentsTasksHas = $tagContentsTasksHas->select('tag_contents_tasks_has.task_id');
        $tagContentsTasksHas = $tagContentsTasksHas->addSelect('tag_contents_tasks_has.tag_content_id');
        $tagContentsTasksHas = $tagContentsTasksHas->addSelect('task_tag.name as tag_name');
        $tagContentsTasksHas = $tagContentsTasksHas->addSelect('task_tag_content.content');
        $tagContentsTasksHas = $tagContentsTasksHas->addSelect('task_tag_content.content_color');
        $tagContentsTasksHas = $tagContentsTasksHas->leftJoin('task_tag_content', 'task_tag_content.id', '=', 'tag_contents_tasks_has.tag_content_id');
        $tagContentsTasksHas = $tagContentsTasksHas->leftJoin('task_tag', 'task_tag.id', '=', 'task_tag_content.tag_id');
        foreach($where as $column=>$value){
            if(is_array($value)){
                $tagContentsTasksHas = $tagContentsTasksHas->whereIn($column, $value);
            }else{
                $tagContentsTasksHas = $tagContentsTasksHas->where($column, $value);
            }
        }
        return $tagContentsTasksHas->get();
    }
}
