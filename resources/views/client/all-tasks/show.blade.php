<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">
<style>
    .dropzone .dz-preview .dz-remove {
        display: none;
    }
</style>

<div class="rpanel-title"> @lang('app.task') #{{ $task->id }} <span><i class="ti-close right-side-toggle"></i></span> </div>
<div class="r-panel-body p-t-0">

    <div class="row">
        <div class="col-xs-12 col-md-9 p-t-20 b-r h-scroll">

            <div class="col-xs-12">
                <a href="javascript:;" id="reminderButton" class="btn btn-default btn-sm m-b-10 btn-rounded btn-outline pull-right  m-l-5 @if($task->board_column->slug == 'completed') hidden @endif" title="@lang('messages.remindToAssignedEmployee')"><i class="fa fa-bell"></i> @lang('modules.tasks.reminder')</a>

            </div>
            <div class="col-xs-12">
                <h2>
                     @lang('modules.tasks.wodetails')
                </h2>
                <div class="row">
                    <div class="col-md-6">
                        <P>
                            <strong>Work Order:  </strong>{{ ucwords($task->heading) }}
                        </P>
                        
                        @if(!is_null($task->project_id))
                            <p><strong>Work Order:  </strong> {{ ucfirst($task->project->project_name) }}</p>
                        @endif
                        <p>
                            @if($task->task_category_id)
                            <strong>Project:  </strong>{{ ucwords($task->category->category_name) }}
                            @endif
                       </p>
                       <p>
                            <strong>PO Number:  </strong>
                       </p>
                       <p>
                            <strong>Client Ref No.:  </strong>
                       </p>
                       <p>
                            <strong>Order Date:  </strong> {{ $task->start_date->format($global->date_format) }}
                       </p>
                       <p>
    
                            <strong>Summery:  </strong> {!! $task->exception ?? __('messages.noDescriptionAdded') !!}
                       </p>
                       <p>
                            <strong>Work Order Type:  </strong>
                       </p>
                       <p>
                            <strong>Client:  </strong> {{ ucwords($task->users[0]->name) }}
                       </p>
                       <p>
                            <strong>Submitted By:  </strong> {{ ucwords($task->create_by->name) }}
                       </p>
                    </div>
                    <div class="col-md-6">
                    <h3>
                         @lang('modules.tasks.siteinfo')
                    </h3>
                    @php 
                    $contacts = json_decode($task->label[0]->label->contacts, true);
                    @endphp
                    <P>
                            <strong>Site ID: </strong> {{$task->label[0]->label->id}}
                        </P>
                       <p>
                            <strong>Site Name:  </strong> {{$task->label[0]->label->label_name}}
                       </p>
                       <p>
                            <strong>Time Zone:  </strong>
                       </p>
                       <p>
                            <strong>Address:  </strong>{{$contacts['site_address']}}
                       </p>
                       <h3>
                           @lang('modules.tasks.sitecontacts')
                       </h3>
                       <p>
                            <strong>Primary:  </strong> {{ ucwords($task->create_by->name) }}
                       </p>
                       <p>
                            <strong>Phone:  </strong> {{$contacts['site_phone']}}
                       </p>
                       <p>
                            <strong>Email:  </strong> {{$contacts['site_pemail']}}
                       </p>
                    </div>
                </div>
    
            </div>
    
            <ul class="nav customtab nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#home1" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">@lang('app.task')</a></li>
                <li role="presentation" class=""><a href="#messages1" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">@lang('app.file') (<span id="totalUploadedFiles">{{ sizeof($task->files) }}</span>) </a></li>
                <li role="presentation" class=""><a href="#settings1" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false">@lang('modules.tasks.comment') ({{ count($task->comments) }})</a></li>
                <li role="presentation" class=""><a href="#notes1" aria-controls="note" role="tab" data-toggle="tab" aria-expanded="false">@lang('app.notes') ({{ count($task->notes) }})</a></li>

                <li role="presentation" >  <a href="#history1" id="view-task-history" role="tab" data-toggle="tab" aria-expanded="false" data-task-id="{{ $task->id }}" > <span class="hidden-xs">@lang('modules.tasks.history')</span></a></li>

                {{-- <li role="presentation"  >  <a href="javascript:;" class="close-task-history" style="display:none"><span class="hidden-xs">@lang('app.close') @lang('modules.tasks.history')</span> <i class="fa fa-times"></i></a></li> --}}
            </ul>
    
            <div class="tab-content" id="task-detail-section">
                <div role="tabpanel" class="tab-pane fade active in" id="home1">
    
                    <div class="col-xs-12" >

                        <div class="row visible-xs visible-sm">
                            <div class="col-xs-6 col-md-3 font-12">
                                <label class="font-12" for="">@lang('modules.tasks.assignTo')</label><br>
                                @foreach ($task->users as $item)
                                    <img src="{{ $item->image_url }}" data-toggle="tooltip"
                                         data-original-title="{{ ucwords($item->name) }}" data-placement="right"
                                         class="img-circle" width="25" height="25" alt="">
                                @endforeach
                            </div>
                            @if($task->create_by)
                                <div class="col-xs-6 col-md-3 font-12">
                                    <label class="font-12" for="">@lang('modules.tasks.assignBy')</label><br>
                                    <img src="{{ $task->create_by->image_url }}" class="img-circle" width="25" height="25" alt="">
    
                                    {{ ucwords($task->create_by->name) }}
                                </div>
                            @endif
    
                            @if($task->start_date)
                                <div class="col-xs-6 col-md-3 font-12">
                                    <label class="font-12" for="">@lang('app.startDate')</label><br>
                                    <span class="text-success" >{{ $task->start_date->format($global->date_format) }}</span><br>
                                </div>
                            @endif
                            <div class="col-xs-6 col-md-3 font-12">
                                <label class="font-12" for="">@lang('app.dueDate')</label><br>
                                <span @if($task->due_date->isPast()) class="text-danger" @endif>
                                    {{ $task->due_date->format($global->date_format) }}
                                </span>

                            </div>
                        </div>
                     
                        {{--Custom fields data--}}
                        @if(isset($fields) && count($fields) > 0)
                        <div class="row m-t-10">
                            @foreach($fields as $field)
                                <div class="col-md-3">
                                    <label class="font-12" for="">{{ ucfirst($field->label) }}</label><br>
                                    <p class="text-muted">
                                        @if( $field->type == 'text')
                                            {{$task->custom_fields_data['field_'.$field->id] ?? '-'}}
                                        @elseif($field->type == 'password')
                                            {{$task->custom_fields_data['field_'.$field->id] ?? '-'}}
                                        @elseif($field->type == 'number')
                                            {{$task->custom_fields_data['field_'.$field->id] ?? '-'}}
        
                                        @elseif($field->type == 'textarea')
                                            {{$task->custom_fields_data['field_'.$field->id] ?? '-'}}
        
                                        @elseif($field->type == 'radio')
                                            {{ !is_null($task->custom_fields_data['field_'.$field->id]) ? $task->custom_fields_data['field_'.$field->id] : '-' }}
                                        @elseif($field->type == 'select')
                                            {{ (!is_null($task->custom_fields_data['field_'.$field->id]) && $task->custom_fields_data['field_'.$field->id] != '') ? $field->values[$task->custom_fields_data['field_'.$field->id]] : '-' }}
                                        @elseif($field->type == 'checkbox')
                                            {{ !is_null($task->custom_fields_data['field_'.$field->id]) ? $field->values[$task->custom_fields_data['field_'.$field->id]] : '-' }}
                                        @elseif($field->type == 'date')
                                            {{ !is_null($task->custom_fields_data['field_'.$field->id]) ? \Carbon\Carbon::parse($task->custom_fields_data['field_'.$field->id])->format($global->date_format) : '--'}}
                                        @endif
                                    </p>
        
                                </div>
                            @endforeach
                        </div>
                        @endif
                        {{--custom fields data end--}}
                        
                        <div class="row">
                            <div class="col-xs-12 col-md-12 m-t-10">
                                <label class="font-bold">@lang('app.description')</label><br>
                                <div class="task-description m-t-10">
                                    {!! $task->description ?? __('messages.noDescriptionAdded') !!}
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="messages1">
                    <div class="col-xs-12">
                        <button href="javascript:;" id="show-dropzone"
                                class="btn btn-success btn-sm btn-outline  m-b-20"><i class="ti-upload"></i> @lang('modules.projects.uploadFile')</button>

                        <div class="row m-b-20 hide" id="file-dropzone">
                            <div class="col-xs-12">
                                <form action="{{ route('admin.task-files.store') }}" class="dropzone"
                                      id="file-upload-dropzone">
                                    {{ csrf_field() }}

                                    {!! Form::hidden('task_id', $task->id) !!}

                                    <input name="view" type="hidden" id="view" value="list">

                                    <div class="fallback">
                                        <input name="file" type="file" multiple/>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <ul class="list-group" id="files-list">
                            @forelse($task->files as $file)
                            <li class="list-group-item" id="task-file-{{ $file->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        {{ $file->filename }}
                                    </div>
                                    <div class="col-md-3">
                                        <span class="">{{ $file->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="col-md-3">
                                            <a target="_blank" href="{{ $file->file_url }}"
                                               data-toggle="tooltip" data-original-title="View"
                                               class="btn btn-info btn-circle"><i
                                                        class="fa fa-search"></i></a>
                                        @if(is_null($file->external_link))
                                        <a href="{{ route('admin.task-files.download', $file->id) }}"
                                           data-toggle="tooltip" data-original-title="Download"
                                           class="btn btn-inverse btn-circle"><i
                                                    class="fa fa-download"></i></a>
                                        @endif
            
                                        <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $file->id }}"
                                           data-pk="list" class="btn btn-danger btn-circle file-delete"><i class="fa fa-times"></i></a>
                                        
                                    </div>
                                </div>
                            </li>
                            @empty
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-10">
                                            @lang('messages.noFileUploaded')
                                        </div>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                        
                    </div>
                </div>
                
                <div role="tabpanel" class="tab-pane" id="settings1">
                    <div class="col-xs-12">
                        <h4>@lang('modules.tasks.comment')</h4>
                    </div>
    
                    <div class="col-xs-12" id="comment-container">
                        <div id="comment-list">
                            @forelse($task->comments as $comment)
                                <div class="row b-b m-b-5 font-12">
                                    <div class="col-xs-12 m-b-5">
                                        <span class="font-semi-bold">{{ ucwords($comment->user->name) }}</span> <span class="text-muted font-12">{{ ucfirst($comment->created_at->diffForHumans()) }}</span>
                                    </div>
                                    <div class="col-xs-10">
                                        {!! ucfirst($comment->comment)  !!}
                                    </div>
                                    <div class="col-xs-2 text-right">
                                        <a href="javascript:;" data-comment-id="{{ $comment->id }}" class="btn btn-xs  btn-outline btn-default" onclick="deleteComment('{{ $comment->id }}');return false;"><i class="fa fa-trash"></i> @lang('app.delete')</a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-xs-12">
                                    @lang('modules.tasks.noCommentFound')
                                </div>
                            @endforelse
                        </div>
                    </div>
    
                    <div class="form-group" id="comment-box">
                        <div class="col-xs-12 m-t-10">
                            <textarea name="comment" id="task-comment" class="summernote" placeholder="@lang('modules.tasks.comment')"></textarea>
                        </div>
                        <div class="col-xs-12">
                            <a href="javascript:;" id="submit-comment" class="btn btn-info btn-sm"><i class="fa fa-send"></i> @lang('app.submit')</a>
                        </div>
                    </div>
    
                </div>
  
                <div role="tabpanel" class="tab-pane" id="notes1">
                    <div class="col-xs-12">
                        <h4>@lang('app.notes')</h4>
                    </div>
    
                    <div class="col-xs-12" id="note-container">
                        <div id="note-list">
                            @forelse($task->notes as $note)
                                <div class="row b-b m-b-5 font-12">
                                    <div class="col-xs-12 m-b-5">
                                        <span class="font-semi-bold">{{ ucwords($note->user->name) }}</span> <span class="text-muted font-12">{{ ucfirst($note->created_at->diffForHumans()) }}</span>
                                    </div>
                                    <div class="col-xs-10">
                                        {!! ucfirst($note->note)  !!}
                                    </div>
                                    <div class="col-xs-2 text-right">
                                        <a href="javascript:;" data-comment-id="{{ $note->id }}" class="btn btn-xs  btn-outline btn-default" onclick="deleteNote('{{ $note->id }}');return false;"><i class="fa fa-trash"></i> @lang('app.delete')</a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-xs-12">
                                    @lang('messages.noNoteFound')
                                </div>
                            @endforelse
                        </div>
                    </div>
    
                    <div class="form-group" id="note-box">
                        <div class="col-xs-12 m-t-10">
                            <textarea name="note" id="task-note" class="summernote" placeholder="@lang('app.notes')"></textarea>
                        </div>
                        <div class="col-xs-12">
                            <a href="javascript:;" id="submit-note" class="btn btn-info btn-sm"><i class="fa fa-send"></i> @lang('app.submit')</a>
                        </div>
                    </div>
    
                </div>

                <div role="tabpanel" class="tab-pane" id="history1">
                    <div class="col-xs-12">
                        <label class="font-bold">@lang('modules.tasks.history')</label>
                    </div>
                    <div class="col-xs-12" id="task-history-section">
                    </div>
                </div>

            </div>
    
    
            
        </div>

        <div class="col-xs-6 col-md-3 hidden-xs hidden-sm">

            <div class="row">
                <div class="col-xs-12 p-10 p-t-20 ">
                    <label class="font-12" for="">@lang('app.status')</label><br>
                    <span id="columnStatusColor" style="width: 15px; height: 15px; background-color: {{ $task->board_column->label_color }}" class="btn btn-small btn-circle">&nbsp;</span> <span id="columnStatus">{{ $task->board_column->column_name }}</span>
                </div>

                <div class="col-xs-12">
                    <hr>

                    <label class="font-12" for="">@lang('modules.tasks.assignTo')</label><br>
                    @foreach ($task->users as $item)
                        <img src="{{ $item->image_url }}" data-toggle="tooltip"
                             data-original-title="{{ ucwords($item->name) }}" data-placement="right"
                             class="img-circle" width="35" height="35" alt="">
                             {{ ucwords($item->name) }}
                    @endforeach
                    <hr>
                </div>
                @if($task->create_by)
                    <div class="col-xs-12">
                        <label class="font-12" for="">@lang('modules.tasks.assignBy')</label><br>
                        <img src="{{ $task->create_by->image_url }}" class="img-circle" width="35" height="35" alt="">

                        {{ ucwords($task->create_by->name) }}
                        <hr>
                    </div>
                @endif

                @if($task->start_date)
                    <div class="col-xs-12  ">
                        <label class="font-12" for="">@lang('app.startDate')</label><br>
                        <span class="text-success" >{{ $task->start_date->format($global->date_format) }}</span><br>
                        <hr>
                    </div>
                @endif

                <div class="col-xs-12 ">
                    <label class="font-12" for="">@lang('app.dueDate')</label><br>
                    <span @if($task->due_date->isPast()) class="text-danger" @endif>
                        {{ $task->due_date->format($global->date_format) }}
                    </span>
                    <hr>
                </div>

            </div>


        </div>



    </div>

</div>

<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('js/sweetalert.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/peity/jquery.peity.init.js') }}"></script>
<script src="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.js') }}"></script>

<script>

var myDropzone;
    $('body').on('click', '.edit-sub-task', function () {
        var id = $(this).data('sub-task-id');
        var url = '{{ route('admin.sub-task.edit', ':id')}}';
        url = url.replace(':id', id);

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    })

    Dropzone.autoDiscover = false;
    //Dropzone class
    myDropzone = new Dropzone("#file-upload-dropzone", {
        url: "{{ route('client.task-files.store') }}",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        paramName: "file",
        maxFilesize: 10,
        maxFiles: 10,
        acceptedFiles: "image/*,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/docx,application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        uploadMultiple: true,
        addRemoveLinks:true,
        parallelUploads:10,
        init: function () {
            this.on("success", function (file, response) {

                if(response.status == 'fail') {
                    $.showToastr(response.message, 'error');
                    return;
                }

                $('#totalUploadedFiles').html(response.totalFiles);
                $('#files-list').html(response.html);

            })
        }
    });
    $('#show-dropzone').click(function () {
        $('#file-dropzone').toggleClass('hide show');
        myDropzone.removeAllFiles();
    });

    $('#submit-comment').click(function () {
        var comment = $('#task-comment').val();
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: '{{ route("client.task-comment.store") }}',
            type: "POST",
            data: {'_token': token, comment: comment, taskId: '{{ $task->id }}'},
            success: function (response) {
                if (response.status == "success") {
                    $('#comment-list').html(response.view);
                    $('.summernote').summernote("reset");
                    $('.note-editable').html('');
                    $('#task-comment').val('');
                }
            }
        })
    })
    $('#reminderButton').click(function () {
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.sendReminder')",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "@lang('messages.confirmNoArchive')",
                confirm: {
                    text: "@lang('messages.confirmSend')",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {

                var url = '{{ route('client.all-tasks.reminder', $task->id)}}';

                $.easyAjax({
                    type: 'GET',
                    url: url,
                    success: function (response) {
                        //
                    }
                });
            }
        });
    })

    $('#view-task-history').click(function () {
        var id = $(this).data('task-id');

        var url = '{{ route('client.all-tasks.history', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            type: "GET",
            success: function (response) {
                $('#task-history-section').html(response.view)
            }
        })

    })

    $('.close-task-history').click(function () {
        $(this).hide();
        $('#task-detail-section').show();
        $('#view-task-history').show();
        $('#task-history-section').html('');
    })

    $('.summernote').summernote({
        height: 100,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });


    $('#submit-note').click(function () {
        var note = $('#task-note').val();
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: '{{ route("client.task-note.store") }}',
            type: "POST",
            data: {'_token': token, note: note, taskId: '{{ $task->id }}'},
            success: function (response) {
                if (response.status == "success") {
                    $('#note-list').html(response.view);
                    $('.summernote').summernote("reset");
                    $('.note-editable').html('');
                    $('#task-note').val('');
                }
            }
        })
    })

    $('#uploadedFiles').click(function () {

        var url = '{{ route("client.all-tasks.show-files", ':id') }}';

        var id = {{ $task->id }};
        url = url.replace(':id', id);

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    });

    function deleteNote (id) {

        var url = '{{ route("client.task-note.destroy", ':id') }}';
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': '{{ csrf_token() }}', '_method': 'DELETE'},
            success: function (response) {
                if (response.status == "success") {
                    $('#note-list').html(response.view);
                }
            }
        })
    }

    //    change task status
    function markComplete(status) {

        var id = '{{ $task->id }}';

        if(status == 'completed'){
            var checkUrl = '{{route('client.tasks.checkTask', ':id')}}';
            checkUrl = checkUrl.replace(':id', id);
            $.easyAjax({
                url: checkUrl,
                type: "GET",
                container: '#task-list-panel',
                data: {},
                success: function (data) {
                    if(data.taskCount > 0){
                        swal({
                            title: "Are you sure?",
                            text: "There is a incomplete sub-task in this task do you want to mark complete!",
                            dangerMode: true,
                            icon: 'warning',
                            buttons: {
                                cancel: "No, cancel please!",
                                confirm: {
                                    text: "Yes, complete it!",
                                    value: true,
                                    visible: true,
                                    className: "danger",
                                }
                            }
                        }).then(function (isConfirm) {
                            if (isConfirm) {
                                updateTask(id,status)
                            }
                        });
                    }
                    else{
                        updateTask(id,status)
                    }

                }
            });
        }
        else{
            updateTask(id,status)
        }


    }

    // Update Task
    function updateTask(id,status){
        var url = "{{route('client.tasks.changeStatus')}}";
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: url,
            type: "POST",
            async: false,
            data: {'_token': token, taskId: id, status: status, sortBy: 'id'},
            success: function (data) {
                $('#columnStatusColor').css('background-color', data.textColor);
                $('#columnStatus').html(data.column);
                if(status == 'completed'){

                    $('#inCompletedButton').removeClass('hidden');
                    $('#completedButton').addClass('hidden');
                    $('#reminderButton').addClass('hidden');
                }
                else{
                    $('#completedButton').removeClass('hidden');
                    $('#inCompletedButton').addClass('hidden');
                    $('#reminderButton').removeClass('hidden');
                }

                if( typeof table !== 'undefined'){
                    window.LaravelDataTables["allTasks-table"].draw();
                }
                else if (typeof showTable === "function") {
                    showTable();
                }
            }
        })
    }

    function refreshTask(taskId) {
        var id = taskId;
        var url = "{{ route('client.all-tasks.show',':id') }}";
        url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response.status == "success") {
                    $('#right-sidebar-content').html(response.view);
                }
            }
        });
    }

</script>
<script>
    $('body').on('click', '.file-delete', function () {
        var id = $(this).data('file-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted file!",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "No, cancel please!",
                confirm: {
                    text: "Yes, delete it!",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('client.task-files.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $('#task-file-'+id).remove();
                            $('#totalUploadedFiles').html(response.totalFiles);
                            $('#list ul.list-group').html(response.html);
                        }
                    }
                });
            }
        });
    });
</script>
