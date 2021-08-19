<?php

namespace App\Http\Controllers\Client;

use App\DataTables\Admin\AllTasksDataTable;
use App\Events\TaskReminderEvent;
use App\Helper\Reply;
use App\Http\Requests\Tasks\StoreTask;
use App\Pinned;
use App\Project;
use App\ProjectClient;
use App\Task;
use App\TaskboardColumn;
use App\TaskCategory;
use App\WoType;
use App\SportType;
use App\TaskFile;
use App\TaskLabelList;
use App\Traits\ProjectProgress;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TaskUser;


class ClientAllTasksController extends ClientBaseController
{
    use ProjectProgress;

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
            $this->taskCategories = TaskCategory::all();
            $this->taskLabels = TaskLabelList::all();
            $this->startDate = Carbon::today()->subDays(15)->format($this->global->date_format);
            $this->endDate = Carbon::today()->addDays(15)->format($this->global->date_format);
        }

        return $dataTable->render('client.all-tasks.index', $this->data);
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
            __('app.project')  => ['data' => 'project_name', 'name' => 'projects.project_name'],
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
        if (!$this->user->can('edit_tasks') && $this->global->task_self == 'no') {
            abort(403);
        }

        $this->taskBoardColumns = TaskboardColumn::where('role_id', '=', '3')->get();
        $this->wotype = WoType::all();
        $this->sport = SportType::all();
        $this->task = Task::with('label')->findOrFail($id);
        $this->labelIds = $this->task->label->pluck('label_id')->toArray();
        $this->taskLabels = TaskLabelList::all();
        if (!$this->user->can('add_tasks') && $this->global->task_self == 'yes') {
            $this->projects = Project::join('project_members', 'project_members.project_id', '=', 'projects.id')
                ->join('users', 'users.id', '=', 'project_members.user_id')
                ->where('project_members.user_id', $this->user->id)
                ->select('projects.id', 'projects.project_name')
                ->get();
        } else {
            $this->projects = Project::allProjects();
        }

        $this->employees = User::allClients();
        $this->categories = TaskCategory::all();
        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('board_column_id', '<>', $completedTaskColumn->id)
                ->where('tasks.id', '!=', $id)->select('tasks.*');

            if ($this->task->project_id != '') {
                $this->allTasks = $this->allTasks->where('project_id', $this->task->project_id);
            }

            if (!$this->user->can('view_tasks')) {
                $this->allTasks = $this->allTasks->where('task_users.user_id', '=', $this->user->id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }

        return view('client.all-tasks.edit', $this->data);
    }

    public function update(StoreTask $request, $id)
    {

        $task = Task::findOrFail($id);
        $oldStatus = TaskboardColumn::findOrFail($task->board_column_id);

        $task->heading = $request->heading;
        if ($request->description != '') {
            $task->description = $request->description;
        }
        $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $task->priority = $request->priority;
        $task->board_column_id = $request->status;
        $task->task_category_id = $request->category_id;
        $task->wo_id = $request->task_type;
        $task->sport_id = $request->sport_type;
        $task->qty = $request->task_qty;
        $task->p_order = $request->task_purchase;

        $taskBoardColumn = TaskboardColumn::findOrFail($request->status);
        if ($taskBoardColumn->slug == 'completed') {
            $task->completed_on = Carbon::now($this->global->timezone)->format('Y-m-d');
        } else {
            $task->completed_on = null;
        }

        $task->project_id = 1;
        $task->site_id = $request->task_labels;
        $task->save();

        // save labels
      //  $task->labels()->sync($request->task_labels);

        if ($this->user->can('add_tasks') && $this->global->task_self == 'yes') {
            $request->user_id = [$this->user->id];
        }

        TaskUser::where('task_id', $task->id)->delete();
        foreach ($request->user_id as $key => $value) {
            TaskUser::create(
                [
                    'user_id' => $value,
                    'task_id' => $task->id
                ]
            );
        }

        // if ($request->project_id) {
        //     //calculate project progress if enabled
        //     $this->calculateProjectProgress($request->project_id);
        // }
        return Reply::dataOnly(['taskID' => $task->id]);
        //        return Reply::redirect(route('client.all-tasks.index'), __('messages.taskUpdatedSuccessfully'));
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
        $this->calculateProjectProgress($task->project_id);

        return Reply::success(__('messages.taskDeletedSuccessfully'));
    }


    public function create()
    {
        if (!$this->user->can('add_tasks') && $this->global->task_self == 'no') {
            abort(403);
        }

        if (!$this->user->can('view_projects') && $this->global->task_self == 'yes') {
            $this->projects = Project::join('project_members', 'project_members.project_id', '=', 'projects.id')
                ->join('users', 'users.id', '=', 'project_members.user_id')
                ->where('project_members.user_id', $this->user->id)
                ->select('projects.id', 'projects.project_name')
                ->get();
        } else {
            $this->projects = Project::allProjects();
        }

        $this->employees = User::allEmployees();
      // $this->employees = User::allClienets();
    //    dd($this->clients);
        $this->categories = TaskCategory::all();
        $this->taskLabels = TaskLabelList::all();
        $this->wotype = WoType::all();
        $this->sport = SportType::all();
        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('board_column_id', '<>', $completedTaskColumn->id)->select('tasks.*');

            if (!$this->user->can('view_tasks')) {
                $this->allTasks = $this->allTasks->where('task_users.user_id', '=', $this->user->id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }

        return view('client.all-tasks.create', $this->data);
    }




    public function membersList($projectId)
    {
        if ($projectId != "all") {
            $this->members = ProjectClient::byProject($projectId);
        } else {
            $this->members = ProjectClient::all();
        }
        $list = view('client.all-tasks.members-list', $this->data)->render();
        return Reply::dataOnly(['html' => $list]);
    }

    public function store(StoreTask $request)
    {

 
        $task = new Task();

        $task->heading = $request->heading;
        if ($request->description != '') {
            $task->description = $request->description;
        }
        $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $task->board_column_id = $this->global->default_task_status;
        $task->task_category_id = $request->category_id;
        $task->site_id = $request->task_labels;
        $task->wo_id = $request->task_type;
        $task->sport_id = $request->sport_type;
        $task->qty = $request->task_qty;
        $task->p_order = $request->task_purchase;

        if ($request->board_column_id) {
            $task->board_column_id = $request->board_column_id;
        }
        $task->project_id = 1;
        $task->save();
      //  echo 'easdasd';
        // save labels
       //  $task->labels()->sync($request->task_labels);

        if (!$this->user->can('add_tasks') && $this->global->task_self == 'yes') {
            $request->user_id = [$this->user->id];
        }

        // For gantt chart
        if ($request->page_name && $request->page_name == 'ganttChart') {
            $parentGanttId = $request->parent_gantt_id;

            $taskDuration = $task->due_date->diffInDays($task->start_date);
            $taskDuration = $taskDuration + 1;

            $ganttTaskArray[] = [
                'id' => 10,
                'text' => $task->heading,
                'start_date' => $task->start_date->format('Y-m-d'),
                'duration' => $taskDuration,
                'parent' => $parentGanttId,
                'taskid' => 10
            ];

            $gantTaskLinkArray[] = [
                'id' => 'link_' . 10,
                'source' => $task->dependent_task_id != '' ? $task->dependent_task_id : $parentGanttId,
                'target' => 10,
                'type' => $task->dependent_task_id != '' ? 0 : 1
            ];
        }

        if ($request->project_id) {
            $this->calculateProjectProgress($request->project_id);
        }

        //log search
      //  $this->logSearchEntry($task->id, 'Task ' . $task->heading, 'admin.all-tasks.edit', 'task');

        if ($request->page_name && $request->page_name == 'ganttChart') {

            return Reply::successWithData(
                'messages.taskCreatedSuccessfully',
                [
                    'tasks' => $ganttTaskArray,
                    'links' => $gantTaskLinkArray
                ]
            );
        }

        if ($request->board_column_id) {
            return Reply::redirect(route('client.taskboard.index'), __('messages.taskCreatedSuccessfully'));
        }

        return Reply::dataOnly(['taskID' => $task->id]);

        //        return Reply::redirect(route('client.all-tasks.index'), __('messages.taskCreatedSuccessfully'));
    }

    public function showFiles($id)
    {
        $this->taskFiles = TaskFile::where('task_id', $id)->get();
        return view('client.all-tasks.ajax-file-list', $this->data);
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
        $this->task = Task::with('board_column', 'users', 'files', 'comments', 'activeTimer', 'notes', 'labels')
       // ->join('task_label_list', 'tasks.site_id', 'task_label_list.id')
        ->findOrFail($id);

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
            
        

        $view = view('client.all-tasks.show', $this->data)->render();
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

    public function dependentTaskLists($projectId, $taskId = null)
    {
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('board_column_id', $completedTaskColumn->id)
                ->where('project_id', $projectId);

            if ($taskId != null) {
                $this->allTasks = $this->allTasks->where('tasks.id', '!=', $taskId);
            }

            if (!$this->user->can('view_tasks')) {
                $this->allTasks = $this->allTasks->where('task_users.user_id', '=', $this->user->id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }

        $list = view('client.tasks.dependent-task-list', $this->data)->render();
        return Reply::dataOnly(['html' => $list]);
    }

    public function history($id)
    {
        $this->task = Task::with('board_column', 'history', 'history.board_column')->findOrFail($id);
        $view = view('admin.tasks.history', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * @return mixed
     */
    public function pinnedItem()
    {
        $this->pinnedItems = Pinned::join('tasks', 'tasks.id', '=', 'pinned.task_id')
            ->where('pinned.user_id','=',user()->id)
            ->select('tasks.id', 'heading')
            ->get();

        return view('client.tasks.pinned-task', $this->data);
    }

    public function ajaxCreate($columnId)
    {

        if (!$this->user->can('view_projects') && $this->global->task_self == 'yes') {
            $this->projects = Project::with('members', 'members.user')
                ->join('project_members', 'project_members.project_id', '=', 'projects.id')
                ->where('project_members.user_id', '=', $this->user->id)
                ->select('projects.*')
                ->get();
        } else {
            $this->projects = Project::allProjects();
        }
        $this->columnId = $columnId;
        $this->categories = TaskCategory::all();
        $this->employees = User::allEmployees();
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('board_column_id', $completedTaskColumn->id);

            if (!$this->user->can('view_tasks')) {
                $this->allTasks = $this->allTasks->where('task_users.user_id', '=', $this->user->id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }

        return view('client.all-tasks.ajax_create', $this->data);
    }
}
