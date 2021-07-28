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
                <a href="{{route('front.task-share',[$task->hash])}}" target="_blank" data-toggle="tooltip" data-placement="bottom"
                    data-original-title="@lang('app.share')" class="btn btn-default btn-sm m-b-10 btn-rounded btn-outline pull-right m-l-5"> <i class="fa fa-share-alt"></i></a>

                @if ($user->can('edit_tasks') || $user->id == $task->created_by)
                    <a href="{{route('client.all-tasks.edit',$task->id)}}" class="btn btn-default btn-sm m-b-10 btn-rounded btn-outline pull-right m-l-5"> <i class="fa fa-edit"></i> @lang('app.edit')</a>                   
                @endif

                @if($task->board_column->slug != 'completed' && ($user->can('edit_tasks') || $task->created_by == $user->id))
                    <a href="javascript:;" id="reminderButton" class="btn btn-default btn-sm m-b-10 m-l-5 btn-rounded btn-outline pull-right" title="@lang('messages.remindToAssignedEmployee')"><i class="fa fa-bell"></i> @lang('modules.tasks.reminder')</a>
                @endif

            </div>
            <div class="col-xs-12">
                <h4>
                    {{ ucwords($task->heading) }}
                </h4>
                @if(!is_null($task->project_id))
                    <p><i class="icon-layers"></i> {{ ucfirst($task->project->project_name) }}</p>
                @endif

                <h5>
                    @if($task->task_category_id)
                        <label class="label label-default text-dark font-light">{{ ucwords($task->category->category_name) }}</label>
                    @endif

                </h5>
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

                @if ($task->estimate_hours > 0 || $task->estimate_minutes > 0)
                    <div class="col-xs-12 ">
                        <label class="font-12" for="">@lang('app.estimate')</label><br>
                       
                        <span>
                            {{ $task->estimate_hours }} @lang('app.hrs') 
                            {{ $task->estimate_minutes }} @lang('app.mins')
                        </span>
                        <hr>
                    </div>

                    <div class="col-xs-12 ">
                        <label class="font-12" for="">@lang('modules.employees.hoursLogged')</label><br>
                        <span>
                            @php
                                $timeLog = intdiv($task->timeLogged->sum('total_minutes'), 60) . ' ' . __('app.hrs') . ' ';

                                if (($task->timeLogged->sum('total_minutes') % 60) > 0) {
                                    $timeLog .= ($task->timeLogged->sum('total_minutes') % 60) . ' ' . __('app.mins');
                                }
                            @endphp 
                            <span @if ($task->total_estimated_minutes < $task->timeLogged->sum('total_minutes')) class="text-danger font-semi-bold" @endif>
                                {{ $timeLog }}
                            </span>
                        </span>
                        <hr>
                    </div>
                @else
                    <div class="col-xs-12 ">
                        <label class="font-12" for="">@lang('modules.employees.hoursLogged')</label><br>
                        <span>
                            @php
                                $timeLog = intdiv($task->timeLogged->sum('total_minutes'), 60) . ' ' . __('app.hrs') . ' ';

                                if (($task->timeLogged->sum('total_minutes') % 60) > 0) {
                                    $timeLog .= ($task->timeLogged->sum('total_minutes') % 60) . ' ' . __('app.mins');
                                }
                            @endphp 
                            <span>
                                {{ $timeLog }}
                            </span>
                        </span>
                        <hr>
                    </div>
                @endif

                @if(sizeof($task->label))
                    <div class="col-xs-12">
                        <label class="font-12" for="">@lang('app.label')</label><br>
                        <span>
                            @foreach($task->label as $key => $label)
                                <label class="badge text-capitalize font-semi-bold" style="background:{{ $label->label->label_color }}">{{ ucwords($label->label->label_name) }} </label>
                            @endforeach
                        </span>
                        <hr>
                    </div>
                @endif

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
        var url = '{{ route('member.sub-task.edit', ':id')}}';
        url = url.replace(':id', id);

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    });

    $('body').on('click', '.add-sub-task', function () {
        console.log('add-sub-task');
        var id = $(this).data('task-id');
        var url = '{{ route('member.sub-task.create')}}?task_id='+id;

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    });

    $(function() {
        Dropzone.autoDiscover = false;
        //Dropzone class
        myDropzone = new Dropzone("#file-upload-dropzone", {
            url: "{{ route('member.task-files.store') }}",
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
    });
    $('#show-dropzone').click(function () {
        $('#file-dropzone').toggleClass('hide show');
        myDropzone.removeAllFiles();

    });
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

                var url = '{{ route('member.all-tasks.reminder', $task->id)}}';

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

    function saveSubTask() {
        $.easyAjax({
            url: '{{route('member.sub-task.store')}}',
            container: '#createSubTask',
            type: "POST",
            data: $('#createSubTask').serialize(),
            success: function (response) {
                $('#subTaskModal').modal('hide');
                $('#sub-task-list').html(response.view)
            }
        })
    }

    function updateSubTask(id) {
        var url = '{{ route('member.sub-task.update', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            container: '#createSubTask',
            type: "POST",
            data: $('#createSubTask').serialize(),
            success: function (response) {
                $('#subTaskModal').modal('hide');
                $('#sub-task-list').html(response.view)
            }
        })
    }

    $('#view-task-history').click(function () {
        var id = $(this).data('task-id');

        var url = '{{ route('member.all-tasks.history', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            type: "GET",
            success: function (response) {
                // $('#task-detail-section').hide();
                $('#task-history-section').html(response.view);
                // $('#view-task-history').hide();
                // $('.close-task-history').show();
            }
        })

    })

    $('.close-task-history').click(function () {
        $('#task-detail-section').show();
        $('#task-history-section').html('');
        $(this).hide();
        $('#view-task-history').show();

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


    $('body').on('click', '.delete-sub-task', function () {
        var id = $(this).data('sub-task-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.deleteSubtaskText')",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "@lang('messages.confirmNoArchive')",
                confirm: {
                    text: "@lang('messages.completeIt')",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('member.sub-task.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $('#sub-task-list').html(response.view);
                        }
                    }
                });
            }
        });
    });

    //    change sub task status
    $('#sub-task-list').on('click', '.task-check', function () {
        if ($(this).is(':checked')) {
            var status = 'complete';
        }else{
            var status = 'incomplete';
        }

        var id = $(this).data('sub-task-id');
        var url = "{{route('member.sub-task.changeStatus')}}";
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': token, subTaskId: id, status: status},
            success: function (response) {
                if (response.status == "success") {
                    $('#sub-task-list').html(response.view);
                }
            }
        })
    });

    $('#uploadedFiles').click(function () {

        var url = '{{ route("member.all-tasks.show-files", ':id') }}';

        var id = {{ $task->id }};
        url = url.replace(':id', id);

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    });

    $('#submit-comment').click(function () {
        var comment = $('#task-comment').val();
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: '{{ route("member.task-comment.store") }}',
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
    });


    function deleteComment(id) {

        var commentId = id;
        var token = '{{ csrf_token() }}';

        var url = '{{ route("member.task-comment.destroy", ':id') }}';
        url = url.replace(':id', commentId);

        $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': token, '_method': 'DELETE', commentId: commentId},
            success: function (response) {
                if (response.status == "success") {
                    $('#comment-list').html(response.view);
                }
            }
        })
    }

    $('#submit-note').click(function () {
        var note = $('#task-note').val();
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: '{{ route("member.task-note.store") }}',
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

    function deleteNote (id) {

        var url = '{{ route("member.task-note.destroy", ':id') }}';
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

        var id = {{ $task->id }};

        if(status == 'completed'){
            var checkUrl = '{{route('member.tasks.checkTask', ':id')}}';
            checkUrl = checkUrl.replace(':id', id);
            $.easyAjax({
                url: checkUrl,
                type: "GET",
                container: '#task-list-panel',
                data: {},
                success: function (data) {
                    if(data.taskCount > 0){
                        swal({
                            title: "@lang('messages.sweetAlertTitle')",
                            text: "@lang('messages.markCompleteTask')",
                            dangerMode: true,
                            icon: 'warning',
                            buttons: {
                                cancel: "@lang('messages.confirmNoArchive')",
                                confirm: {
                                    text: "@lang('messages.completeIt')",
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
        var url = "{{route('member.tasks.changeStatus')}}";
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: url,
            type: "POST",
            container: '.r-panel-body',
            data: {'_token': token, taskId: id, status: status, sortBy: 'id'},
            async: false,
            success: function (data) {
                $('#columnStatus').css('color', data.textColor);
                $('#columnStatus').html(data.column);
                if(status == 'completed'){

                    $('#inCompletedButton').removeClass('hidden');
                    $('#completedButton').addClass('hidden');
                    if($('#reminderButton').length){
                        $('#reminderButton').addClass('hidden');
                    }
                }
                else{
                    $('#completedButton').removeClass('hidden');
                    $('#inCompletedButton').addClass('hidden');
                    if($('#reminderButton').length){
                        $('#reminderButton').removeClass('hidden');
                    }
                }

                if( typeof table !== 'undefined'){
                    table._fnDraw();
                }
                else{
                    if ($.fn.showTable) {
                        showTable();
                    }
                }
                refreshTask(id);
            }
        })
    }

    $('#start-task-timer').click(function () {
        var task_id = "{{ $task->id }}";
        var project_id = "{{ $task->project_id }}";
        var user_id = "{{ user()->id }}";
        var memo = "{{ $task->heading }}";
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: '{{route('member.time-log.store')}}',
            container: '#startTimer',
            type: "POST",
            data: {task_id: task_id, project_id: project_id, memo: memo, '_token': token, user_id: user_id},
            success: function (data) {
                refreshTask(task_id);
            }
        })
    });

    $('#stop-task-timer').click(function () {
        var id = $(this).data('time-id');
        var url = '{{route('member.all-time-logs.stopTimer', ':id')}}';
        url = url.replace(':id', id);
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: url,
            type: "POST",
            data: {timeId: id, _token: token},
            success: function (data) {
                refreshTask("{{ $task->id }}");
            }
        })
    });

    function refreshTask(taskId) {
        var id = taskId;
        var url = "{{ route('member.all-tasks.show',':id') }}";
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

    $('body').on('click', '#pinnedItem', function(){
        var type = $('#pinnedItem').attr('data-pinned');
        var id = {{ $task->id }};
        var pinType = 'task';

        var dataPin = type.trim(type);
        if(dataPin == 'pinned'){
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.unpinTask')",
                dangerMode: true,
                icon: 'warning',
                buttons: {
                    cancel: "@lang('messages.confirmNoArchive')",
                    confirm: {
                        text: "@lang('messages.confirmUnpin')",
                        value: true,
                        visible: true,
                        className: "danger",
                    }
                }
            }).then(function (isConfirm) {
                if (isConfirm) {
                    var url = "{{ route('member.pinned.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE','type':pinType},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('.pin-icon').removeClass('pinned');
                                $('.pin-icon').addClass('unpinned');
                                $('#pinnedItem').attr('data-pinned','unpinned');
                                $('#pinnedItem').attr('data-original-title','Pin');
                                $("#pinnedItem").tooltip("hide");
                                table._fnDraw();
                            }
                        }
                    })
                }
            });
        }
        else {
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.pinTask')",
                dangerMode: true,
                icon: 'warning',
                buttons: {
                    cancel: "@lang('messages.confirmNoArchive')",
                    confirm: {
                        text: "@lang('app.yes')",
                        value: true,
                        visible: true,
                        className: "danger",
                    }
                }
            }).then(function (isConfirm) {
                if (isConfirm) {
                    var url = "{{ route('member.pinned.store') }}?type="+pinType;

                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token,'task_id':id},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('.pin-icon').removeClass('unpinned');
                                $('.pin-icon').addClass('pinned');
                                $('#pinnedItem').attr('data-pinned','pinned');
                                $('#pinnedItem').attr('data-original-title','Unpin');
                                $("#pinnedItem").tooltip("hide");
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        }
    });

</script>


<script>
    $('body').on('click', '.file-delete', function () {
        var id = $(this).data('file-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.removeFileText')",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "@lang('messages.confirmNoArchive')",
                confirm: {
                    text: "@lang('messages.confirmDelete')",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('member.task-files.destroy',':id') }}";
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

@if ($task->board_column->slug != 'completed' && !is_null($task->activeTimer))
    <script>

        $(document).ready(function(e) {
            var $worked = $("#active-task-timer");
            function updateTimer() {
                var myTime = $worked.html();
                var ss = myTime.split(":");

                var hours = ss[0];
                var mins = ss[1];
                var secs = ss[2];
                secs = parseInt(secs)+1;

                if(secs > 59){
                    secs = '00';
                    mins = parseInt(mins)+1;
                }

                if(mins > 59){
                    secs = '00';
                    mins = '00';
                    hours = parseInt(hours)+1;
                }

                if(hours.toString().length < 2) {
                    hours = '0'+hours;
                }
                if(mins.toString().length < 2) {
                    mins = '0'+mins;
                }
                if(secs.toString().length < 2) {
                    secs = '0'+secs;
                }
                var ts = hours+':'+mins+':'+secs;

                $worked.html(ts);
                setTimeout(updateTimer, 1000);
            }
            setTimeout(updateTimer, 1000);
        });

    </script>
@endif
