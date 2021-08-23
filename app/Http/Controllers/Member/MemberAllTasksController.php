<?php

namespace App\Http\Controllers\Member;

use App\Events\TaskReminderEvent;
use App\Helper\Reply;
use App\Http\Requests\Tasks\StoreTask;
use App\Task;
use App\TaskboardColumn;
use App\TaskFile;
use App\User;
use App\TaskLabelList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;


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

    public function index()
    {

        $this->employees = ($this->user->can('view_employees')) ? User::allEmployees() : User::where('id', $this->user->id)->get();

        $this->clients = User::allClients();
        $this->taskBoardStatus = TaskboardColumn::all();
        $this->taskLabels = TaskLabelList::all();
        $this->startDate = Carbon::today()->subDays(15)->format($this->global->date_format);
        $this->endDate = Carbon::today()->addDays(15)->format($this->global->date_format);

        return view('member.all-tasks.index', $this->data);
    }

    public function data(Request $request, $startDate = null, $endDate = null, $hideCompleted = null)
    {
        $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
        $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
        $hideCompleted = $request->hideCompleted;

        $taskBoardColumn = TaskboardColumn::completeColumn();
        $taskBoardColumns = TaskboardColumn::orderBy('id', 'asc')->get();

        $tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->join('users as member', 'task_users.user_id', '=', 'member.id')
            ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->join('task_label_list', 'tasks.site_id', '=', 'task_label_list.id')
            ->selectRaw('tasks.id, tasks.heading, creator_user.name as created_by, creator_user.id as created_by_id, creator_user.image as created_image,
             tasks.due_date, taskboard_columns.column_name as board_column, taskboard_columns.label_color, task_label_list.label_name, task_label_list.id as ids')
            ->with('users')
            ->groupBy('tasks.id');


        $tasks->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween(DB::raw('DATE(tasks.`due_date`)'), [$startDate, $endDate]);

            $q->orWhereBetween(DB::raw('DATE(tasks.`start_date`)'), [$startDate, $endDate]);
        });

        if ($request->assignedBY != '' && $request->assignedBY !=  null && $request->assignedBY !=  'all') {
            $tasks->where('creator_user.id', '=', $request->assignedBY);
        }
        if ($request->label != '' && $request->label !=  null && $request->label !=  'all') {
            $tasks->where('tasks.site_id', '=', $request->label);
        }

        if ($request->category_id != '' && $request->category_id !=  null && $request->category_id !=  'all') {
            $tasks->where('tasks.task_category_id', '=', $request->category_id);
        }
        if ($request->status != '' && $request->status !=  null && $request->status !=  'all') {
            $tasks->where('tasks.board_column_id', '=', $request->status);
        }
        if ($hideCompleted == '1') {
            $tasks->where('tasks.board_column_id', '<>', $taskBoardColumn->id);
        }

        if (!$this->user->can('view_tasks')) {
            $tasks->where(
                function ($q) {
                    $q->where(
                        function ($q1) {
                            $q1->where(
                                function ($q3) {
                                    $q3->where('task_users.user_id', $this->user->id);
                                }
                            );
                            $q1->orWhere('tasks.created_by', $this->user->id);
                        }
                    );
                    $q->orWhere(
                        function ($q2) {
                            $q2->where('task_users.user_id', $this->user->id);
                        }
                    );
                }
            );
        }

        $tasks->get();

        return DataTables::of($tasks)
            ->addColumn('action', function ($row) {
                $action = '';

                if ($this->user->can('edit_tasks') || ($this->global->task_self == 'yes' && $this->user->id == $row->created_by_id)) {
                    $action .= '<a href="' . route('member.all-tasks.edit', $row->id) . '" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if ($this->user->can('delete_tasks') || ($this->global->task_self == 'yes' && $this->user->id == $row->created_by_id)) {
                    $recurringTaskCount = Task::where('recurring_task_id', $row->id)->count();
                    $recurringTask = $recurringTaskCount > 0 ? 'yes' : 'no';

                    $action .= '&nbsp;&nbsp;<a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-task-id="' . $row->id . '" data-recurring="' . $recurringTask . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('due_date', function ($row) {

                if ($row->due_date->endOfDay()->isPast()) {
                    return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                } elseif ($row->due_date->setTimezone($this->global->timezone)->isToday()) {
                    return '<span class="text-success">' . __('app.today') . '</span>';
                }
                return '<span >' . $row->due_date->format($this->global->date_format) . '</span>';
            })
            ->editColumn('created_by', function ($row) {
                if (!is_null($row->created_by)) {
                    return ($row->created_image) ? '<img src="' . asset_url('avatar/' . $row->created_image) . '"
                                                            alt="user" class="img-circle" width="25" height="25"> ' . ucwords($row->created_by) : '<img src="' . asset('img/default-profile-3.png') . '"
                                                            alt="user" class="img-circle" width="25" height="25"> ' . ucwords($row->created_by);
                }
                return '-';
            })

            ->editColumn('users', function ($row) {
                $members = '';
                foreach ($row->users as $member) {
                    $members .= '<a href="' . route('admin.employees.show', [$member->id]) . '">';
                    $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->name) . '" src="' . $member->image_url . '"
                    alt="user" class="img-circle" width="25" height="25"> ';
                    $members .= '</a>';
                }
                return $members;
            })
            ->editColumn('heading', function ($row) {
                $pin = '';

                $name = '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a> '.$pin;

                return $name;
            })
            ->editColumn('site', function ($row) {
                $site = '';            
                if ($row->label_name) {
                    $site = $row->label_name;
                } 
               return $site;
            })

            ->editColumn('siteid', function ($row) {
                $site = '';          
                if ($row->ids) {
                    $site = $row->ids;
                } 
                
               return $site;
            })
            ->editColumn('board_column', function ($row) use ($taskBoardColumns) {
                $status = '<div class="btn-group dropdown">';
                $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs"  style="border-color: ' . $row->label_color . '; color: ' . $row->label_color . '" type="button">' . $row->board_column . '</button>';
                $status .= '</div>';
                return $status;
            })
            ->rawColumns(['board_column', 'action', 'created_by', 'due_date', 'users', 'heading', 'site', 'siteid'])
            ->removeColumn('image')
            ->removeColumn('label_color')
            ->addIndexColumn()
            ->make(true);
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
            __('modules.tasks.site')  => ['data' => 'site', 'name' => 'site', 'visible' => false],
            __('modules.tasks.siteid')  => ['data' => 'siteid', 'name' => 'siteid', 'visible' => false],
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
                ->width(50)
                ->addClass('text-center')
        ];
    }
    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('allTasks-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->buttons(
                Button::make(['extend' => 'export', 'buttons' => ['excel', 'pdf'], 'text' => '<i class="fa fa-download"></i> ' . trans('app.exportExcel') . '&nbsp;<span class="caret"></span>'])
            )
            ->parameters([
                'initComplete' => 'function () {
                    showTable().buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
            ]);
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
            ->leftJoin('project_time_logs', 'project_time_logs.user_id', '=', 'users.id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id');

        
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
