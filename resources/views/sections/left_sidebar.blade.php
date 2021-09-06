<style>
    .slimScrollDiv{
        overflow: initial !important;
    }
</style>
<div class="navbar-default sidebar" role="navigation">
    <div class="navbar-header">
        <!-- Toggle icon for mobile view -->
        <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse"
            data-target=".navbar-collapse"><i class="ti-menu"></i></a>

        <div class="top-left-part">
            <!-- Logo -->
            <a class="logo hidden-xs text-center" href="{{ route('admin.dashboard') }}">
                <span class="visible-md"><img src="{{ $global->logo_url }}" alt="home" class=" admin-logo"/></span>
                <span class="visible-sm"><img src="{{ $global->logo_url }}" alt="home" class=" admin-logo"/></span>
            </a>

        </div>
        <!-- /Logo -->

        <!-- This is the message dropdown -->
        <ul class="nav navbar-top-links navbar-right pull-right visible-xs">
            @if(isset($activeTimerCount))
            <li class="dropdown hidden-xs">
            <span id="timer-section">
                <div class="nav navbar-top-links navbar-right pull-right m-t-10">
                    <a class="btn btn-rounded btn-default timer-modal" href="javascript:;">@lang("modules.projects.activeTimers")
                        <span class="label label-danger" id="activeCurrentTimerCount">@if($activeTimerCount > 0) {{ $activeTimerCount }} @else 0 @endif</span>
                    </a>
                </div>
            </span>
            </li>
            @endif



            <!-- .Task dropdown -->
            <li class="dropdown" id="top-notification-dropdown">
                <a class="dropdown-toggle waves-effect waves-light show-user-notifications" data-toggle="dropdown" href="#">
                    <i class="icon-bell"></i>
                    @if($unreadNotificationCount > 0)
                        <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                    @endif
                </a>
                <ul class="dropdown-menu  dropdown-menu-right mailbox animated slideInDown">
                    <li>
                        <a href="javascript:;">...</a>
                    </li>

                </ul>
            </li>
            <!-- /.Task dropdown -->


            <li class="dropdown">
                <a href="{{ route('logout') }}" title="Logout" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();"
                ><i class="fa fa-power-off"></i>
                </a>
            </li>



        </ul>

    </div>
    <!-- /.navbar-header -->

    <div class="top-left-part">
        <a class="logo hidden-xs hidden-sm text-center" href="{{ route('admin.dashboard') }}">
            <img src="{{ $global->logo_url }}" alt="home" class=" admin-logo"/>
        </a>


    </div>
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">

        <!-- .User Profile -->
        <ul class="nav" id="side-menu">
            <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                <!-- input-group -->
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search..."> <span class="input-group-btn">
                            <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
                            </span> </div>
                <!-- /input-group -->
            </li>

            <li class="user-pro hidden-sm hidden-md hidden-lg">
                @if(is_null($user->image))
                    <a href="#" class="waves-effect"><img src="{{ asset('img/default-profile-3.png') }}" alt="user-img" class="img-circle"> <span class="hide-menu">{{ (strlen($user->name) > 24) ? substr(ucwords($user->name), 0, 20).'..' : ucwords($user->name) }}
                    <span class="fa arrow"></span></span>
                    </a>
                @else
                    <a href="#" class="waves-effect"><img src="{{ asset_url('avatar/'.$user->image) }}" alt="user-img" class="img-circle"> <span class="hide-menu">{{ ucwords($user->name) }}
                            <span class="fa arrow"></span></span>
                    </a>
                @endif
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('member.dashboard') }}">
                            <i class="fa fa-sign-in fa-fw"></i> @lang('app.loginAsEmployee')
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();"
                        ><i class="fa fa-power-off fa-fw"></i> @lang('app.logout')</a>

                    </li>
                </ul>
            </li>

                   
                <li><a href="{{ route('admin.dashboard') }}" class="waves-effect"><i class="icon-speedometer fa-fw"></i> <span class="hide-menu">@lang('app.menu.dashboard') </span></a> </li>
   
                @if(in_array('tasks',$modules))
                <li><a href="{{ route('admin.task.index') }}" class="waves-effect"><i class="fa fa-tasks fa-fw"></i> <span class="hide-menu"> @lang('app.menu.tasks') <span class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ route('admin.all-tasks.index') }}">@lang('app.menu.work')</a></li>
                        <li><a href="{{ route('admin.all-tasks.create') }}">@lang('app.menu.newwork')</a></li>
                        <li><a href="{{ route('admin.task-calendar.index') }}">@lang('app.menu.taskCalendar')</a></li>
                    </ul>
                </li>
                @endif
                
                @if(in_array('tasks',$modules))
                <li><a href="#" class="waves-effect"><i class="icon-people fa-fw"></i> <span class="hide-menu"> @lang('app.menu.customers') <span class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ route('admin.clients.index') }}">@lang('app.menu.clients')</a></li>
                        <li><a href="{{ route('admin.employees.index') }}">@lang('app.menu.employees')</a></li>
                    </ul>
                </li>
                @endif


            @if(in_array('tasks',$modules))
            <li><a href="{{ route('admin.site.index') }}" class="waves-effect"><i class="icon-doc fa-fw"></i> 
                <span class="hide-menu">@lang('app.menu.taskLabel') <span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('admin.site.index') }}">@lang('app.menu.browsetaskLabel')</a></li>
                    <li><a href="{{ route('admin.site.create') }}">@lang('app.menu.newtaskLabel')</a></li>
                </ul>    
            </li>
            <li><a href="{{ route('admin.company.index') }}" class="waves-effect"><i class="ti-receipt"></i> 
                <span class="hide-menu">@lang('app.menu.company') <span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('admin.company.index') }}">@lang('app.menu.browsecompany')</a></li>
                    <li><a href="{{ route('admin.company.creates') }}">@lang('app.menu.newcompany')</a></li>
                </ul>    
            </li>
            <li><a href="{{ route('admin.country.index') }}" class="waves-effect"><i class="ti-check-box"></i> 
                <span class="hide-menu">@lang('app.menu.country') <span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('admin.country.index') }}">@lang('app.menu.browsecountry')</a></li>
                    <li><a href="{{ route('admin.country.create') }}">@lang('app.menu.newcountry')</a></li>
                    <li><a href="{{ route('admin.state.index') }}">@lang('app.menu.browsestate')</a></li>
                    <li><a href="{{ route('admin.state.create') }}">@lang('app.menu.newstate')</a></li>
                </ul>    
            </li>
            @endif
            <li><a href="{{ route('admin.settings.index') }}" class="waves-effect"><i class="fa fa-cog"></i> 
                <span class="hide-menu">@lang('app.menu.settings') </span> </a>
            </li>
            @foreach ($worksuitePlugins as $item)
                @if(View::exists(strtolower($item).'::sections.left_sidebar'))
                    @include(strtolower($item).'::sections.left_sidebar')
                @endif
            @endforeach

        </ul>

        <div class="clearfix"></div>

    </div>

    <div class="menu-footer">
        <div class="menu-user row">
            <div class="col-lg-4 m-b-5">
                <div class="btn-group dropup user-dropdown">

                    <img aria-expanded="false" data-toggle="dropdown" src="{{ $user->image_url }}" alt="user-img" class="img-circle dropdown-toggle h-30 w-30">

                    <ul role="menu" class="dropdown-menu">
                        <li><a class="bg-inverse"><strong class="text-white font-semi-bold">{{ ucwords($user->name) }}</strong></a></li>
                        <!-- <li>
                            <a href="{{ route('member.dashboard') }}">
                                <i class="fa fa-sign-in fa-fw"></i> @lang('app.loginAsEmployee')
                            </a>
                        </li> -->
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();"
                            ><i class="fa fa-power-off fa-fw"></i> @lang('app.logout')</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="col-lg-4 text-center  m-b-5">
                <div class="btn-group dropup shortcut-dropdown">
                    <a class="dropdown-toggle waves-effect waves-light text-uppercase" data-toggle="dropdown" href="#">
                        <i class="fa fa-plus"></i>
                    </a>
                    <ul class="dropdown-menu">

                        @if(in_array('projects',$modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.projects.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('app.add') @lang('app.project')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif

                        @if(in_array('tasks',$modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.all-tasks.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('app.add') @lang('app.task')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif

                        @if(in_array('clients',$modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.clients.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('app.add') @lang('app.client')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif

                        @if(in_array('employees',$modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.employees.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('app.add') @lang('app.employee')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif

                        @if(in_array('payments',$modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.payments.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('modules.payments.addPayment')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif

                        @if(in_array('tickets',$modules))
                            <li>
                                <div class="message-center">
                                    <a href="{{ route('admin.tickets.create') }}">
                                        <div class="mail-contnet">
                                            <span class="mail-desc m-0">@lang('app.add') @lang('modules.tickets.ticket')</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>

            <div class="col-lg-4 text-right m-b-5">
                <div class="btn-group dropup notification-dropdown">
                    <a class="dropdown-toggle show-user-notifications" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>
                        @if($unreadNotificationCount > 0)

                            <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                        @endif
                    </a>
                    <ul class="dropdown-menu mailbox ">
                        <li>
                            <a href="javascript:;">...</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="menu-copy-right">
            <a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="ti-angle-double-right ti-angle-double-left"></i> <span class="collapse-sidebar-text">@lang('app.collapseSidebar')</span></a>
        </div>

    </div>


</div>
