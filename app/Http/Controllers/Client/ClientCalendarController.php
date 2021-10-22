<?php

namespace App\Http\Controllers\Client;

use App\Task;
use App\TaskboardColumn;

class ClientCalendarController extends ClientBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.taskCalendar';
        $this->pageIcon = 'icon-calender';
        $this->middleware(function ($request, $next) {
            if (!in_array('tasks', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();
        $this->tasks = Task::select('tasks.*')
            ->with('board_column')
            ->join('client_details', 'client_details.user_id', '=', 'tasks.client_id')
            ->where('board_column_id', '<>', $completedTaskColumn->id)
            ->where('tasks.start_date', '!=', null)
            ->where('client_details.category_id', '=', $this->user->client_details->category_id);
            //->where('tasks.client_id', '=', user()->id);

        $this->tasks =  $this->tasks->groupBy('tasks.id');
        $this->tasks =  $this->tasks->get();
        return view('client.task-calendar.index', $this->data);
    }

    public function show($id)
    {
        $this->task = Task::findOrFail($id);
        return view('client.task-calendar.show', $this->data);
    }
}
