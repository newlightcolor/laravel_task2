<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Models\TaskTag;
use App\Models\TaskTagContent;
use App\Models\TagContentsTasksHas;
use App\Rules\TagContentsTaskHasLessThanLimit;
use DateTime;

class TaskController extends Controller
{

    /**
     * 一覧
     */
    public function index(Request $request)
    {

        //既存の検索値
        $where_clauses = [];
        $order_by_clauses = [];
        if($request->input('where_clauses')){
            foreach($request->input('where_clauses') as $column=>$value){
                $where_clauses[$column] = $value;
            }
        }
        if($request->input('order_by_clauses')){
            foreach($request->input('order_by_clauses') as $column=>$sort){
                $order_by_clauses[$column] = $sort;
            }
        }

        //新規の検索値
        $where_columns = array_keys($where_clauses);
        $order_by_columns = array_keys($order_by_clauses);
        if($request->input('where') && $request->input('where_value')){
            $where_clauses[$request->input('where')] = $request->input('where_value');
        }
        if($request->input('sort_table') && $request->input('sort_column')){
            $order = $request->input('sort_table').'.'.$request->input('sort_column');
            if(in_array($order, $order_by_columns)){
                $order_by_clauses[$order] = 'DESC';
            }else{
                $order_by_clauses[$order] = 'ASC';
            }
        }

        //検索値の除外
        if($request->input('remove_where')){
            unset($where_clauses[$request->input('remove_where')]);
        }
        if($request->input('remove_order_by')){
            if(!is_array($request->input('remove_order_by'))){
                unset($order_by_clauses[$request->input('remove_order_by')]);
            }else{
                $remove_order_by = $request->input('remove_order_by');
                foreach($order_by_clauses as $column=>$sort){
                    list($table, $column) = explode('.', $column);
                    if($table === $remove_order_by['table'] && $column !== $remove_order_by['column_not']){
                        unset($order_by_clauses[$table.'.'.$column]);
                    }
                }
            }
        }


        $taskModel = new Task();
        $taskModel = $taskModel->select_with_tag_content();
        foreach($where_clauses as $column=>$value){
            $taskModel = $taskModel->where($column, $value);
        }
        foreach($order_by_clauses as $column=>$sort){
            $taskModel = $taskModel->orderBy($column, $sort);
        }
        $tasks = $taskModel->get();

        //タスクが持つタグ取得（N+1解消用処理）
        $tagContentsTasksHas = new TagContentsTasksHas();
        $tags_task_has_temp = $tagContentsTasksHas->all_contents();
        $tags_task_has = [];
        foreach($tags_task_has_temp as $tag_task_has){
            $task_id = $tag_task_has->task_id;
            $tag_content_id = $tag_task_has->tag_content_id;
            $tags_task_has[$task_id][] = $tag_task_has;
        }

        //タグ一覧取得
        $tags = TaskTag::all();
        $tag_contents_temp = TaskTagContent::all();
        $tag_contents = [];
        foreach($tag_contents_temp as $tag_content){
            $tag_contents[$tag_content->tag_id][] = $tag_content;
        }

        $view_args = [];
        $view_args['where_clauses'] = $where_clauses;
        $view_args['order_by_clauses'] = $order_by_clauses;
        $view_args['tasks'] = $tasks;
        $view_args['tags_task_has'] = $tags_task_has;
        $view_args['tags'] = $tags;
        $view_args['tag_contents'] = $tag_contents;
        return view('task.index', $view_args);
    }

    /**
     * 登録
     */
    public function post(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'deadline_at' => 'date_format:Y-m-d H:i:s',
            'tag_contents_task_has' => [new TagContentsTaskHasLessThanLimit]
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), Response::HTTP_I_AM_A_TEAPOT);
        }

        //タスク登録
        $task = [];
        $task['content'] = $request->input('content');
        $task['deadline_at'] = $request->input('deadline_at');
        $task['created_at'] = new DateTime();
        $task['updated_at'] = new DateTime();
        $task_id = Task::insertGetId($task);

        //タグ紐づけ登録
        if($request->input('tag_contents_task_has')){
            $tag_contents_task_has = [];
            foreach($request->input('tag_contents_task_has') as $tag_content_id){
                $tag_content_task_has = [];
                $tag_content_task_has['task_id'] = $task_id;
                $tag_content_task_has['tag_content_id'] = $tag_content_id;
                $tag_content_task_has['created_at'] = new DateTime();
                $tag_content_task_has['updated_at'] = new DateTime();
                $tag_contents_task_has[] = $tag_content_task_has;
            }
            TagContentsTasksHas::insert($tag_contents_task_has);
        }

        return response()->json('', 200);
    }

    /**
     * 編集
     */
    public function edit(Request $request)
    {
        $task = Task::where('id', $request->input('task_id'))->first();

        //タスクが持つタグ取得
        $where = [];
        $where['task_id'] = $request->input('task_id');
        $tagContentsTasksHas = new TagContentsTasksHas();
        $tag_contents_task_has_temp = $tagContentsTasksHas->select_contents($where);
        $tag_content_ids_task_has = [];
        foreach($tag_contents_task_has_temp as $tag_task_has){
            $tag_content_ids_task_has[] = $tag_task_has->tag_content_id;
        }

        //タグ一覧取得
        $tags = TaskTag::all();
        $tag_contents_temp = TaskTagContent::all();
        $tag_contents = [];
        foreach($tag_contents_temp as $tag_content){
            $tag_contents[$tag_content->tag_id][] = $tag_content;
        }

        $view_args = [];
        $view_args['task'] = $task;
        $view_args['tag_content_ids_task_has'] = $tag_content_ids_task_has;
        $view_args['tags'] = $tags;
        $view_args['tag_contents'] = $tag_contents;
        return response()->json([
            'modal' => view('task.modal.edit', $view_args)->render()
        ]);
    }

    /**
     * 更新
     */
    public function put(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'task_id' => 'required|numeric',
            'content' => 'required',
            'deadline_at' => 'date_format:Y-m-d H:i:s',
            'tag_contents_task_has' => [new TagContentsTaskHasLessThanLimit]
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), Response::HTTP_I_AM_A_TEAPOT);
        }

        //タスク更新
        $task = [];
        $task['content'] = $request->input('content');
        $task['deadline_at'] = $request->input('deadline_at');
        $task['updated_at'] = new DateTime();
        Task::where('id', $request->input('task_id'))->update($task);

        //紐づけタグ更新
        TagContentsTasksHas::where('task_id', $request->input('task_id'))->delete();
        if($request->input('tag_contents_task_has')){
            $tag_contents_task_has = [];
            foreach($request->input('tag_contents_task_has') as $tag_content_id){
                $tag_content_task_has = [];
                $tag_content_task_has['task_id'] = $request->input('task_id');
                $tag_content_task_has['tag_content_id'] = $tag_content_id;
                $tag_content_task_has['created_at'] = new DateTime();
                $tag_content_task_has['updated_at'] = new DateTime();
                $tag_contents_task_has[] = $tag_content_task_has;
            }
            TagContentsTasksHas::insert($tag_contents_task_has);
        }

        return response()->json('', 200);
    }

    /**
     * 削除
     */
    public function delete(Request $request)
    {
        Task::where('id', $request->input('task_id'))->delete();
        TagContentsTasksHas::where('task_id', $request->input('task_id'))->delete();
    }

}
