<?php

namespace App\Http\Controllers\Member;

use App\Attendance;
use App\AttendanceSetting;
use App\Holiday;
use App\Notice;
use App\Project;
use App\ProjectActivity;
use App\ProjectTimeLog;
use App\Task;
use App\TaskboardColumn;
use App\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MemberDashboardController extends MemberBaseController
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

        $completedTaskColumn = TaskboardColumn::completeColumn();


        $this->counts = DB::table('users')
            ->select(
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id=' . $completedTaskColumn->id . ' and task_users.user_id = ' . $this->user->id . ') as totalCompletedTasks'),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where task_users.user_id = ' . $this->user->id . ') as totalAllTasks'),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id!=' . $completedTaskColumn->id . ' and task_users.user_id = ' . $this->user->id . ') as totalPendingTasks')
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

        $this->pendingTasks = Task::with('project')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('tasks.board_column_id', '<>', $completedTaskColumn->id)
            ->where(DB::raw('DATE(due_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->where('task_users.user_id', $this->user->id)
            ->select('tasks.*')
            ->groupBy('tasks.id')
            ->limit(15)
            ->get();



        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();
        $this->tasks = Task::select('tasks.*')
            ->with('board_column')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('board_column_id', '<>', $completedTaskColumn->id)
            ->where('tasks.start_date', '!=', null)
            ->where('task_users.user_id', $this->user->id);

            // $this->tasks = $this->tasks->where('task_users.user_id', $this->user->id);

        $this->tasks =  $this->tasks->groupBy('tasks.id');
        $this->tasks =  $this->tasks->get();

        return view('member.dashboard.index', $this->data);
    }
}
