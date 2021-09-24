@extends('layouts.member-app')
@push('head-script')
<link rel="stylesheet" href="{{ asset('css/full-calendar/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

@endpush
@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <style>
        .col-in {
            padding: 0 20px !important;

        }

        .fc-event{
            font-size: 10px !important;
        }

        @media (min-width: 769px) {
            #wrapper .panel-wrapper{
                max-height: 350px;
                overflow-y: auto;
            }
        }

    </style>
@endpush

@section('content')

<div class="white-box">
    <div class="row dashboard-stats front-dashboard">
        <div class="col-md-12">
            <div class="row">
        @if(in_array('tasks',$modules))
        <div class="col-md-4">
            <a href="{{ route('member.all-tasks.index','stat=0&hideComplet=1') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div>
                                <span class="bg-danger-gradient"><i class="ti-alert"></i></span>
                            </div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalPendingTasks')</span><br>
                            <span class="counter">{{ $counts->totalPendingTasks }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('member.all-tasks.index','stat=11&hideComplet=0') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div>
                                <span class="bg-success-gradient"><i class="ti-check-box"></i></span>
                            </div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalCompletedTasks')</span><br>
                            <span class="counter">{{ $counts->totalCompletedTasks }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('member.all-tasks.index','stat=1&hideComplet=1') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div>
                                <span class="bg-info-gradient"><i class="icon-layers"></i></span>
                            </div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalAllTasks')</span><br>
                            <span class="counter">{{ $counts->totalAllTasks }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif
                
        </div>
        </div>
  
       </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">@lang('app.menu.taskCalendar')</h3>

                <p>
                    @lang('modules.taskCalendar.note')
                </p>

                <div id="calendar"></div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')

<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('js/moment-timezone.js') }}"></script>
<script>

    $(function () {
        $('.selectpicker').selectpicker();
    });


    function showNoticeModal(id) {
        var url = '{{ route('client.notices.show', ':id') }}';
        url = url.replace(':id', id);
        $.ajaxModal('#projectTimerModal', url);
    }

    $('.show-task-detail').click(function () {
            $(".right-sidebar").slideDown(50).addClass("shw-rside");

            var id = $(this).data('task-id');
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
        })

</script>


<script>
    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: '{{ $global->date_picker_format }}',
        language: '{{ $global->locale }}',
        autoclose: true
    });

    var taskEvents = [
        @foreach($tasks as $task)
        {
            id: '{{ ucfirst($task->id) }}',
            title: '{{ ucfirst($task->heading) }}',
            start: '{{ $task->start_date->format("Y-m-d") }}',
            end:  '{{ $task->start_date->format("Y-m-d") }}',
            color: '{{ $task->board_column->label_color }}'
        },
        @endforeach
    ];

    // only use for sidebar call method
    function loadData(){}

    // Task Detail show in sidebar
    var getEventDetail = function (id) {
        $(".right-sidebar").slideDown(50).addClass("shw-rside");
        var url = "{{ route('member.all-tasks.show',':id') }}";
        url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response.status == "success") {
                    $('#right-sidebar-content').html(response.view);
                }

                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }
        });
    }

    var calendarLocale = '{{ $global->locale }}';
</script>

<script src="{{ asset('plugins/bower_components/calendar/jquery-ui.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('js/full-calendar/main.min.js') }}"></script>
<script src="{{ asset('js/full-calendar/locales-all.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>
    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: '{{ $global->date_picker_format }}',
        language: '{{ $global->locale }}',
        autoclose: true
    });
</script>
<script>
    var initialLocaleCode = '{{ $global->locale }}';
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
  
      var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: initialLocaleCode,
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        // initialDate: '2020-09-12',
        navLinks: true, // can click day/week names to navigate views
        selectable: false,
        selectMirror: true,
        select: function(arg) {
          var title = prompt('Event Title:');
          if (title) {
            calendar.addEvent({
              title: title,
              start: arg.start,
              end: arg.end,
              allDay: arg.allDay
            })
          }
          calendar.unselect()
        },
        eventClick: function(arg) {
            getEventDetail(arg.event.id);
        },
        editable: false,
        dayMaxEvents: true, // allow "more" link when too many events
        events: taskEvents
      });
  
      calendar.render();
    });
    </script>   

@endpush
