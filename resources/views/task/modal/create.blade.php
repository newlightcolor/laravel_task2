<div class="modal" id="modal-task-create" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-closer" style="padding: 10px; text-align: right;">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="alert alert-danger alert-post-task" style="display: none;"></div>

                <form action="{{url('/')}}/task/post" method="post" id="post_task">
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <input name="content" id="content" class="form-control init-input">
                    </div>
                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input name="deadline_at" id="deadline" class="form-control init-input task-datetimepicker">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        @foreach($tags as $tag)
                            <div class="tag-wrapper" data-tag-id="{{$tag->id}}">
                                <div>{{$tag->name}}</div>
                                @if(array_key_exists($tag->id, $tag_contents))
                                @foreach($tag_contents[$tag->id] as $tag_content)
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
                                @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-primary post-task">Submit</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        //登録
        $("#modal-task-create .post-task").off("click");
        $("#modal-task-create .post-task").on("click", function(){

            $('#modal-task-create .alert').hide();
            let formData = new FormData($("#post_task").get(0));

            $.ajax({
                url: "{{url('/')}}/task",
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
                show_error_alert(jqXHR, '#modal-task-create .alert-post-task')
            });
        });

        // タグ選択
        $("#modal-task-create .tag-content").off("click");
        $("#modal-task-create .tag-content").on("click", function()
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
            }
        }

        // 日時選択
        $('#modal-task-create .task-datetimepicker').datetimepicker({
            format: 'Y-m-d H:i:s'
        });
    })
</script>