<?php

namespace App\Http\Controllers\Member;

use App\DataTables\Admin\AllTasksDataTable;
use App\Events\TaskReminderEvent;
use App\Helper\Reply;
use App\Http\Requests\Tasks\StoreTask;
use App\Task;
use App\TaskboardColumn;
use App\TaskFile;
use App\User;
use App\WoType;
use App\TaskLabelList;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MemberAllTasksController extends MemberBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.tasks';
        $this->pageIcon = 'fa fa-tasks';
        $this->middleware(function ($request, $next) {
            if (!in_array('tasks', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(AllTasksDataTable $dataTable)
    {
        if (!request()->ajax()) {
          //  $this->projects = Project::allProjects();
            $this->clients = User::allClients();
            $this->employees = User::allEmployees();
            $this->taskBoardStatus = TaskboardColumn::all();
            $this->taskLabels = TaskLabelList::all();
            $this->wotype = WoType::all();
            $this->startDate = Carbon::today()->subDays(30)->format($this->global->date_format);
            $this->endDate = Carbon::today()->addDays(15)->format($this->global->date_format);
        }

        return $dataTable->render('member.all-tasks.index', $this->data);
    }

        /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false, 'exportable' => false],
            '#' => ['data' => 'id', 'name' => 'id', 'visible' => true],
            __('app.task') => ['data' => 'heading', 'name' => 'heading'],
           // __('app.project')  => ['data' => 'project_name', 'name' => 'projects.project_name'],
            __('modules.tasks.assigned') => ['data' => 'name', 'name' => 'name', 'visible' => false],
            __('modules.tasks.assignTo') => ['data' => 'users', 'name' => 'member.name', 'exportable' => false],
            __('app.dueDate') => ['data' => 'due_date', 'name' => 'due_date'],
            __('app.status') => ['data' => 'status', 'name' => 'status', 'visible' => false],
            __('app.columnStatus') => ['data' => 'board_column', 'name' => 'board_column', 'exportable' => false, 'searchable' => false],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-center')
        ];
    }

    public function edit($id)
    {
      //
    }

    public function update()
    {

        //
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // If it is recurring and allowed by user to delete all its recurring tasks
        if ($request->has('recurring') && $request->recurring == 'yes') {
            Task::where('recurring_task_id', $id)->delete();
        }

        Task::destroy($id);

        //calculate project progress if enabled

        return Reply::success(__('messages.taskDeletedSuccessfully'));
    }


    public function create()
    {
      //
    }


    public function store(StoreTask $request)
    {

      //
    }

    public function showFiles($id)
    {
        $this->taskFiles = TaskFile::where('task_id', $id)->get();
        return view('member.all-tasks.ajax-file-list', $this->data);
    }

    public function remindForTask($taskID)
    {
        
        $task = Task::with('users')->findOrFail($taskID);

        // Send  reminder notification to user

        event(new TaskReminderEvent($task));
        return Reply::success('messages.reminderMailSuccess');
    }

    public function show($id)
    {
        $this->task = Task::with('board_column', 'users', 'files', 'comments', 'notes', 'labels', 'wotype', 'sporttype')->findOrFail($id);
        
        $this->clientDetail = User::where('id', '=', $this->task->client_id)->first();
       
      //  $this->sport = SportType::all();
        $this->employees = User::join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->leftJoin('project_time_logs', 'project_time_logs.user_id', '=', 'users.id');

        
        $this->employees = $this->employees->select(
            'users.name',
            'users.image',
            'users.id'
        );

        $this->employees = $this->employees->where('project_time_logs.task_id', '=', $id);

        $this->employees = $this->employees->groupBy('project_time_logs.user_id')
            ->orderBy('users.name')
            ->get();
            
        

        $view = view('member.all-tasks.show', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function updateTaskDuration(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
        $task->save();

        return Reply::success('messages.taskUpdatedSuccessfully');
    }
    public function history($id)
    {
        $this->task = Task::with('board_column', 'history', 'history.board_column')->findOrFail($id);
        $view = view('admin.tasks.history', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }
        /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'All_Task_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);
        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }
}
