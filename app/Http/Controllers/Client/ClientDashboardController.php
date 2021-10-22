<?php

namespace App\Http\Controllers\Client;

use App\Attendance;
use App\AttendanceSetting;
use App\Holiday;
use App\Notice;
use App\EmployeeDetails;
use App\ClientDetails;
use App\Task;
use App\TaskboardColumn;
use App\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helper\Reply;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends ClientBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'app.menu.dashboard';
        $this->pageIcon = 'icon-speedometer';

        // Getting Attendance setting data
        $this->attendanceSettings = AttendanceSetting::first();

        //Getting Maximum Check-ins in a day
        $this->maxAttendanceInDay = $this->attendanceSettings->clockin_in_day;
    }

    public function index()
    {
        $this->taskBoardColumn = TaskboardColumn::all();

        $completedTaskColumn = TaskboardColumn::completeColumn();

        $this->counts = DB::table('users')
            ->select(
                DB::raw('(select count(tasks.id) from `tasks` inner join client_details on client_details.user_id=tasks.client_id where tasks.board_column_id=' . $completedTaskColumn->id . ' and client_details.category_id = ' . $this->user->client_details->category_id . ') as totalCompletedTasks'),
                DB::raw('(select count(tasks.id) from `tasks` inner join client_details on client_details.user_id=tasks.client_id where client_details.category_id = ' . $this->user->client_details->category_id . ') as totalAllTasks'),
                DB::raw('(select count(tasks.id) from `tasks` inner join client_details on client_details.user_id=tasks.client_id where tasks.board_column_id!=' . $completedTaskColumn->id . ' and client_details.category_id = ' . $this->user->client_details->category_id . ') as totalPendingTasks')
            )
            ->first();

            if ($this->user->can('view_notice')) {
                $this->notices = Notice::latest()->get();
            }
    
            $this->userActivities = UserActivity::with('user')->limit(15)->orderBy('id', 'desc');
    
            if (!$this->user->can('view_employees')) {
                $this->userActivities = $this->userActivities->where('user_id', $this->user->id);
            }
    
            $this->userActivities = $this->userActivities->get();

        $this->tasks = Task::select('tasks.*')
            ->with('board_column')
            ->join('client_details', 'client_details.user_id', '=', 'tasks.client_id')
            ->where('tasks.board_column_id', '<>', $completedTaskColumn->id)
            ->where('tasks.start_date', '!=', null)
            ->where('client_details.category_id', '=', $this->user->client_details->category_id);
            //->where('tasks.client_id', '=', user()->id);

        $this->tasks =  $this->tasks->groupBy('tasks.id');
        $this->tasks =  $this->tasks->get();

        $this->employee = EmployeeDetails::with('user')->get();
        $this->clients = ClientDetails::with('user')->get();

        $this->pendingTasks = Task::where('tasks.board_column_id', '<>', $completedTaskColumn->id)
            ->where(DB::raw('DATE(due_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->where('tasks.client_id', user()->id)
            ->select('tasks.*')
            ->groupBy('tasks.id')
            ->limit(15)
            ->get();


      
        return view('client.dashboard.index', $this->data);
    }
    public function show(Request $request) 
    {
    
        $tasks = Task::with('board_column')->select('tasks.*')
        ->join('client_details', 'client_details.user_id', '=', 'tasks.client_id')
        ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
        ->where('tasks.start_date', '!=', null)
        ->where('client_details.category_id', '=', $this->user->client_details->category_id);
       // ->where('tasks.client_id', '=', user()->id);
        if($request->tech != 0){
            $tasks->where('task_users.user_id', '=', $request->tech);
        }
        if($request->client != 0){
            $tasks->where('tasks.client_id', '=', $request->client);
        }
        if($request->status != 0){
            $tasks->where('board_column_id', '=', $request->status);
        }
        $task = $tasks->groupBy('tasks.id')->get();
 
        return Reply::dataOnly($task);
    }

    public function filter() {
    
  }
}