<div class="modal" id="modal-task-edit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-closer" style="padding: 10px; text-align: right;">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="alert alert-danger alert-put-task" style="display: none;"></div>

                <form action="{{url('/')}}/task" method="put" id="put_task">
                    @csrf
                    <input type="hidden" name="task_id" value="{{$task->id}}">

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <input name="content" id="content" value="{{$task->content}}" class="form-control init-input">
                    </div>
                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input name="deadline_at" id="deadline" value="{{$task->deadline_at}}" class="form-control init-input task-datetimepicker">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        @foreach($tags as $tag)
                            <div class="tag-wrapper" data-tag-id="{{$tag->id}}">
                                <div>{{$tag->name}}</div>
                                @if(array_key_exists($tag->id, $tag_contents))
                                @foreach($tag_contents[$tag->id] as $tag_content)
                                    @if(in_array($tag_content->id, $tag_content_ids_task_has))
                                        @if($tag_content->content_color)
                                        <span class="badge tag-content tag-content-active"
                                              data-tag-content-id="{{$tag_content->id}}"
                                              data-max-select="{{$tag->max_select_content}}"
                                              data-back-color="{{$tag_content->content_color}}"
                                              style="background-color: {{$tag_content->content_color}};">
                                              {{$tag_content->content}}
                                              <input type="hidden" 
                                                     name="tag_contents_task_has[]"
                                                     value="{{$tag_content->id}}"
                                                     class="input-active-tag">
                                        </span>
                                        @else
                                        <span class="badge tag-content bg-secondary tag-content-active"
                                              data-tag-content-id="{{$tag_content->id}}"
                                              data-max-select="{{$tag->max_select_content}}"
                                              data-back-color="{{$tag_content->content_color}}">
                                              {{$tag_content->content}}
                                              <input type="hidden" 
                                                     name="tag_contents_task_has[]"
                                                     value="{{$tag_content->id}}"
                                                     class="input-active-tag">
                                        </span>
                                        @endif
                                    @else
                                    <span class="badge tag-content"
                                          data-tag-content-id="{{$tag_content->id}}"
                                          data-max-select="{{$tag->max_select_content}}"
                                          data-back-color="{{$tag_content->content_color}}">
                                          {{$tag_content->content}}
                                          <input type="hidden" 
                                                 name="tag_contents_task_has[]"
                                                 value="{{$tag_content->id}}"
                                                 class="input-active-tag"
                                                 disabled>
                                    </span>
                                    @endif
                                @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-primary put-task">Submit</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        //モーダル表示
        $("#modal-task-edit").modal('show');

        //更新
        $("#modal-task-edit .put-task").off("click");
        $("#modal-task-edit .put-task").on("click", function(){

            $('#modal-task-edit .alert').hide();
            let formData = new FormData($("#put_task").get(0));

            $.ajax({
                url: "{{url('/')}}/task/put",
                type: 'POST',
                data: formData ,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            }).done(function(data) {
                location.href = "{{url('/')}}";
            }).fail(function( jqXHR )
            {
                show_error_alert(jqXHR, '#modal-task-edit .alert-put-task')
            });
        });

        // タグ選択
        $("#modal-task-edit .tag-content").off("click");
        $("#modal-task-edit .tag-content").on("click", function()
        {
            //最大選択数が1なら他の選択タグを除外
            if($(this).data('max-select') == 1 && !$(this).hasClass('tag-content-active')){
                init_tag_select($(this));
            }

            toggle_tag_select($(this));
            set_tag_bg_color($(this));
        });

        function init_tag_select(that){
            that.closest(".tag-wrapper").find(".tag-content").removeClass("tag-content-active");
            that.closest(".tag-wrapper").find(".tag-content").css("background-color", "#fff");
            that.closest(".tag-wrapper").find('.input-active-tag').prop('disabled', true);
        }

        function toggle_tag_select(that){
            if(that.hasClass('tag-content-active')){
                that.removeClass('tag-content-active');
                that.find('.input-active-tag').prop('disabled', true);
            }else{
                that.addClass('tag-content-active');
                that.find('.input-active-tag').prop('disabled', false);
            }
        }

        function set_tag_bg_color(that){
            if(that.hasClass('tag-content-active')){
                if(that.data('back-color')){
                    that.css('background-color', that.data('back-color'));
                }else{
                    that.addClass('bg-secondary');
                }
            }else{
                that.css('background-color', '#fff');
                that.removeClass('bg-secondary');
            }
        }

        // 日時選択
        $('#modal-task-edit .task-datetimepicker').datetimepicker({
            format: 'Y-m-d H:i:s'
        });
    })
</script>