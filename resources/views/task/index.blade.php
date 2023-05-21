@extends('layout.common')
 
@section('header')
    @include('layout.header')
@endsection

@section('content')

	<form id="task-search" action="{{url('/')}}" method="GET">
		@foreach($where_clauses as $column=>$value)
		<input type="hidden" name="where_clauses[{{$column}}]" value="{{$value}}">
		@endforeach
		@foreach($order_by_clauses as $column=>$sort)
		<input type="hidden" name="order_by_clauses[{{$column}}]" value="{{$sort}}">
		@endforeach
	</form>
    

    <div class="task-container">

		<table class="table table-hover">
			<thead>
				<tr>
					<th class="th-task-content"></th>
					<th class="th-timestamp search-task" data-sort-table="tasks" data-sort-column="deadline_at">
						Deadline 
						@if(array_key_exists('tasks.deadline_at', $order_by_clauses))
							{{$order_by_clauses['tasks.deadline_at'] === 'ASC'? '▲': '▼'}}
							@if(count($order_by_clauses) >= 2)
								{{array_search('tasks.deadline_at', array_keys($order_by_clauses)) + 1}}
							@endif
						@endif
					</th>
					<th class="th-timestamp search-task" data-sort-table="tasks" data-sort-column="created_at">
						Created_at 
						@if(array_key_exists('tasks.created_at', $order_by_clauses))
							{{$order_by_clauses['tasks.created_at'] === 'ASC'? '▲': '▼'}}
							@if(count($order_by_clauses) >= 2)
								{{array_search('tasks.created_at', array_keys($order_by_clauses)) + 1}}
							@endif
						@endif
					</th>
					<th class="th-tags">
						@if(!array_key_exists('task_tag_content.tag_id', $where_clauses))
							Tags
						@else
							@foreach($tags as $tag)
								{{$tag->id == $where_clauses['task_tag_content.tag_id']? $tag->name: ''}}
							@endforeach
							@if(array_key_exists('task_tag_content.content', $order_by_clauses))
								{{$order_by_clauses['task_tag_content.content'] === 'ASC'? '▲': '▼'}}
								@if(count($order_by_clauses) >= 2)
									{{array_search('task_tag_content.content', array_keys($order_by_clauses)) + 1}}
								@endif
							@else
								{{$order_by_clauses['task_tag_content.for_order_by'] === 'ASC'? '▲': '▼'}}
								@if(count($order_by_clauses) >= 2)
									{{array_search('task_tag_content.for_order_by', array_keys($order_by_clauses)) + 1}}
								@endif
							@endif
						@endif
					</th>
					<th class="th-actions"></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($tasks as $task)
				<tr>
					<td>{{$task->content}}</td>
					<td>{{$task->deadline_at}}</td>
					<td>{{$task->created_at}}</td>
					<td style="padding: 3px;">
						<div class="tags-wrapper">
							@if(array_key_exists($task->id, $tags_task_has))
							@foreach($tags_task_has[$task->id] as $tag)
								@if($tag->content_color)
									<span class="badge index-tags" style="background: {{$tag->content_color}}; color: #fff;">{{$tag->tag_name}}: {{$tag->content}}</span>
								@else
									<span class="badge index-tags bg-secondary">{{$tag->tag_name}}: {{$tag->content}}</span>
								@endif
							@endforeach
							@endif
							<div class="card tag-tooltip">
								@if(array_key_exists($task->id, $tags_task_has))
								@foreach($tags_task_has[$task->id] as $tag)
									@if($tag->content_color)
										<span class="badge tooltip-tags search-task"
											  data-sort-table="task_tag_content"
											  data-sort-column="{{$tag->use_order_by_column? 'for_order_by': 'content'}}"
											  data-where="task_tag_content.tag_id"
											  data-where-value="{{$tag->tag_id}}"
											  style="background: {{$tag->content_color}}; color: #fff;">
											{{$tag->tag_name}}: {{$tag->content}}
										</span>
									@else
										<span class="badge tooltip-tags bg-secondary search-task"
											  data-sort-table="task_tag_content"
											  data-sort-column="{{$tag->use_order_by_column? 'for_order_by': 'content'}}"
											  data-where="task_tag_content.tag_id"
											  data-where-value="{{$tag->tag_id}}">
											{{$tag->tag_name}}: {{$tag->content}}
										</span>
									@endif
								@endforeach
								@endif
							</div>
						</div>
					</td>
					<td style="padding: 0;">
						<a class="btn delete-task" data-task-id="{{$task->id}}"><span class="material-symbols-outlined">delete</span></a>
						<a class="btn edit-task" data-task-id="{{$task->id}}"><span class="material-symbols-outlined">edit</span></a>
					</td>
				</tr>
				@endforeach
			</tbody>
      	</table>
    
    </div>

  @include('common.add_task_button')

@endsection

@section('page_modals')
	@include('task.modal.create')
@endsection


@section('page_script')
    <script>
        $(document).ready(function(){

			//検索
			$(".search-task").off("click");
			$(".search-task").on("click", function() {
				let query_params = {};
				let where = $(this).data('where');
				let order_by = $(this).data('sort-table') + '.' + $(this).data('sort-column');
				let current_sort_is_desc = $("#task-search input[name='order_by_clauses["+order_by+"]']").val() == 'DESC';
				let selected_where_value_is_same = true;
				if($(this).data('where-value')){
					selected_where_value_is_same = $("#task-search input[name='where_clauses["+where+"]']").val() == $(this).data('where-value');
				}
				
				if(current_sort_is_desc && selected_where_value_is_same){
					query_params['remove_order_by'] = order_by;
					if($(this).data('where')){
						query_params['remove_where'] = $(this).data('where');
					}
				}else{
					query_params['sort_table'] = $(this).data('sort-table');
					query_params['sort_column'] = $(this).data('sort-column');
					query_params['where'] = $(this).data('where');
					query_params['where_value'] = $(this).data('where-value');
				}

				if(!selected_where_value_is_same){
					query_params['remove_order_by[table]'] = $(this).data('sort-table');
					query_params['remove_order_by[column_not]'] = $(this).data('sort-column');
				}

				let searchForm = new FormData($("#task-search").get(0));
				for(data of searchForm){
					query_params[data[0]] = data[1];
				}

				let url_query = create_query(query_params);
				location.href = "{{url('/')}}/"+url_query;
			});
			
			//削除
			$(".delete-task").off("click");
			$(".delete-task").on("click", function() {
				$(this).closest("tr").remove();
				$.ajax({
					url: "{{url('/')}}/task?task_id="+$(this).data('task-id'),
					type: 'DELETE',
					contentType: false,
					processData: false,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
					}
				}).fail(function( jqXHR )
				{
					location.href = "{{url('/')}}"
				});
			});
			
			//編集
			$(".edit-task").off("click");
			$(".edit-task").on("click", function() {
				$.ajax({
					url: "{{url('/')}}/task/edit?task_id="+$(this).data('task-id'),
					type: 'GET',
					contentType: false,
					processData: false,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
					}
				}).then(function(data){
                    $('#modal-task-edit').remove();
                    $('body').append(data.modal);
				});
			});

			//作成
			$(".create-task").off("click");
			$(".create-task").on("click", function() {
				$("#modal-task-create .init-input").val('');
				$("#modal-task-create .alert").hide();
                $("#modal-task-create").modal('show');
			});

			//タグツールチップ表示/非表示
			$(".tags-wrapper").off("mouseenter");
			$(".tags-wrapper").on("mouseenter", function() {
				$(".tags-wrapper").css('position', 'inherit');
				$(".tags-wrapper .index-tags").css('position', 'inherit');
				$(this).css("position", 'relative');
				$(this).find('.tooltip-tags').css("position", 'static');
				$(this).find('.index-tags').css("display", 'none');
			});
			$(".tags-wrapper").off("mouseleave");
			$(".tags-wrapper").on("mouseleave", function() {
				$(".tags-wrapper").css('position', 'relative');
				$(".tags-wrapper .index-tags").css('position', 'static');
				$(this).find('.index-tags').css("display", 'inline-block');
			});
        })
    </script>
@endsection