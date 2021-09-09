@extends('layouts.app')


@push('head-script')
    <style>
        .list-group{
            margin-bottom:0px !important;
        }
    </style>
@endpush
@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->

    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('css/full-calendar/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}"><!--Owl carousel CSS -->
    <link rel="stylesheet"
          href="{{ asset('plugins/bower_components/owl.carousel/owl.carousel.min.css') }}"><!--Owl carousel CSS -->
    <link rel="stylesheet"
          href="{{ asset('plugins/bower_components/owl.carousel/owl.theme.default.css') }}"><!--Owl carousel CSS -->

    <style>
        .col-in {
            padding: 0 20px !important;

        }

        .fc-event {
            font-size: 10px !important;
        }

        .dashboard-settings {
            padding-bottom: 8px !important;
        }
        .panel-heading span {
            padding: 10px 13px;
            margin-top: -10px;
            color: #fff;
            border-radius: 50%;
        }
        @media (min-width: 769px) {
            #wrapper .panel-wrapper {
                max-height: 265px;
                overflow-y: auto;
            }
        }

    </style>
@endpush

@section('content')

    <div class="col-md-12">
        @if(!$progress['progress_completed'] && App::environment('codecanyon'))
            @include('admin.dashboard.get_started')
        @endif
    </div>

    <div class="white-box">
    <div class="row">

@if(in_array('tasks',$modules) && in_array('overdue_tasks',$activeWidgets))
    <div class="col-md-6">
        <div class="panel panel-inverse">
            @php
                $totalnew = count($newTasks);
            @endphp
            <div class="panel-heading">@lang('modules.dashboard.newTasks') <span class="bg-info pull-right">{{$totalnew}}</span></div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                <ul class="list-task list-group" data-role="tasklist">
                        <li class="list-group-item" data-role="task">
                            <strong>@lang('app.title')</strong> <span
                                    class="pull-right"><strong>@lang('modules.dashboard.newDate')</strong></span>
                        </li>
                        @forelse($newTasks as $key=>$task)
                            <li class="list-group-item row" data-role="task">
                                <div class="col-xs-9">
                                    {!! ($key+1).'. <a href="javascript:;" data-task-id="'.$task->id.'" class="show-task-detail">'.ucfirst($task->heading).'</a>' !!}
                                </div>
                                <label class="label label-success pull-right col-xs-3">{{ $task->created_at->format($global->date_format) }}</label>
                            </li>
                        @empty
                            <li class="list-group-item" data-role="task">
                                <div  class="text-center">
                                    <div class="empty-space" style="height: 200px;">
                                        <div class="empty-space-inner">
                                            <div class="icon" style="font-size:20px"><i
                                                        class="fa fa-tasks"></i>
                                            </div>
                                            <div class="title m-b-15">@lang("messages.noOpenTasks")
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
@if(in_array('tasks',$modules) && in_array('overdue_tasks',$activeWidgets))
    <div class="col-md-6">
        <div class="panel panel-inverse">
            @php
                $totalpanding = count($pendingTasks);
            @endphp
            <div class="panel-heading">@lang('modules.dashboard.overdueTasks') <span class="bg-info pull-right">{{ $totalpanding }}</span></div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <ul class="list-task list-group" data-role="tasklist">
                        <li class="list-group-item" data-role="task">
                            <strong>@lang('app.title')</strong> <span
                                    class="pull-right"><strong>@lang('modules.dashboard.dueDate')</strong></span>
                                   
                        </li>
                        @forelse($pendingTasks as $key=>$task)
                            <li class="list-group-item row" data-role="task">
                                <div class="col-xs-9">
                                    {!! ($key+1).'. <a href="javascript:;" data-task-id="'.$task->id.'" class="show-task-detail">'.ucfirst($task->heading).'</a>' !!}
                                </div>
                                <label class="label label-danger pull-right col-xs-3">{{ $task->due_date->format($global->date_format) }}</label>
                            </li>
                        @empty
                            <li class="list-group-item" data-role="task">
                                <div  class="text-center">
                                    <div class="empty-space" style="height: 200px;">
                                        <div class="empty-space-inner">
                                            <div class="icon" style="font-size:20px"><i
                                                        class="fa fa-tasks"></i>
                                            </div>
                                            <div class="title m-b-15">@lang("messages.noOpenTasks")
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

</div>

        <div class="row dashboard-stats front-dashboard">
            <div class="col-md-4">
            @if(in_array('clients',$modules) && in_array('total_clients',$activeWidgets))
                    <a href="{{ route('admin.clients.index') }}">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-xs-3">
                                    <div>
                                        <span class="bg-warning-gradient"><i class="icon-user"></i></span>
                                    </div>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <span class="widget-title"> @lang('modules.dashboard.totalClients')</span><br>
                                    <span class="counter">{{ $counts->totalClients }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
            @endif

            @if(in_array('employees',$modules) && in_array('total_employees',$activeWidgets))
                    <a href="{{ route('admin.employees.index') }}">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-xs-3">
                                    <div>
                                        <span class="bg-info-gradient"><i class="icon-people"></i></span>
                                    </div>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <span class="widget-title"> @lang('modules.dashboard.totalEmployees')</span><br>
                                    <span class="counter">{{ $counts->totalEmployees }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
            @endif

            @if(in_array('invoices',$modules) && in_array('total_unpaid_invoices',$activeWidgets))
                    <a href="{{ route('admin.all-invoices.index') }}">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-xs-3">
                                    <div>
                                        <span class="bg-inverse-gradient"><i class="ti-receipt"></i></span>
                                    </div>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <span class="widget-title"> @lang('modules.dashboard.totalUnpaidInvoices')</span><br>
                                    <span class="counter">{{ $counts->totalUnpaidInvoices }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
            @endif


            @if(in_array('tasks',$modules) && in_array('total_pending_tasks',$activeWidgets))
                    <a href="{{ route('admin.all-tasks.index','stat=0&hideComplet=0') }}">
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
            @endif

            @if(in_array('tasks',$modules) && in_array('total_pending_tasks',$activeWidgets))
                    <a href="{{ route('admin.all-tasks.index','stat=11&hideComplet=0') }}">
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
            @endif
           </div>

           @if(in_array('employees',$modules) && in_array('user_activity_timeline',$activeWidgets))
                <div class="col-md-8">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">@lang('modules.dashboard.userActivityTimeline')</div>
                        <div class="panel-wrapper collapse in">
                            <div class="panel-body">
                                <div class="steamline">
                                    @forelse($userActivities as $key=>$activity)
                                        <div class="sl-item">
                                            <div class="sl-left">
                                                <img src="{{ $activity->user->image_url }}" width="40" height="40" alt="user" class="img-circle">
                                            </div>
                                            <div class="sl-right">
                                                <div class="m-l-40"><a
                                                            href="{{ route('admin.employees.show', $activity->user_id) }}"
                                                            class="">{{ ucwords($activity->user->name) }}</a>
                                                    <span class="sl-date">{{ $activity->created_at->diffForHumans() }}</span>
                                                    <p>{!! ucfirst($activity->activity) !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @if(count($userActivities) > ($key+1))
                                            <hr>
                                        @endif
                                    @empty
                                        <div class="text-center">
                                            <div class="empty-space" style="height: 200px;">
                                                <div class="empty-space-inner">
                                                    <div class="icon" style="font-size:20px"><i
                                                                class="fa fa-history"></i>
                                                    </div>
                                                    <div class="title m-b-15">@lang("messages.noActivityByThisUser")
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    <div class="row">
            <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">@lang('modules.taskCalendar.note')</div>
                        <div class="panel-wrapper collapse in" style="overflow: auto">
                            <div class="panel-body">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
        <!-- .row -->
    </div>


@endsection


@push('footer-script')

    <script>
    var taskEvents = [
        @foreach($tasks as $task)
        {
            id: '{{ $task->id }}',
            title: "{!! ucfirst($task->heading) !!}",
            start: '{{ $task->start_date->format("Y-m-d") }}',
            end:  '{{ $task->due_date->addDay()->format("Y-m-d") }}',
            color  : '{{ $task->board_column->label_color }}'
        },
        @endforeach
    ];

    // only use for sidebar call method
    function showTable(){}
    </script>


    <script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>

    <script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>

    <!-- jQuery for carousel -->
    <script src="{{ asset('plugins/bower_components/owl.carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/owl.carousel/owl.custom.js') }}"></script>

    <!--weather icon -->

    <script src="{{ asset('plugins/bower_components/calendar/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
    <script src="{{ asset('js/full-calendar/main.min.js') }}"></script>
    <script src="{{ asset('js/full-calendar/locales-all.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/moment-timezone.js') }}"></script>
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
    var getEventDetail = function (id) {
        $(".right-sidebar").slideDown(50).addClass("shw-rside");
        var url = "{{ route('admin.all-tasks.show',':id') }}";
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
        function showTable (){
            location.reload();
        }
        $(document).ready(function () {


            $('.vcarousel').carousel({
                interval: 3000
            })

        })

        $('.show-task-detail').click(function () {
            $(".right-sidebar").slideDown(50).addClass("shw-rside");

            var id = $(this).data('task-id');
            var url = "{{ route('admin.all-tasks.show',':id') }}";
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


        $('.keep-open .dropdown-menu').on({
            "click":function(e){
            e.stopPropagation();
            }
        });



    </script>

    

@endpush
