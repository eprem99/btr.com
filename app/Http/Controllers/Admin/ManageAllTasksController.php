<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\AllTasksDataTable;
use App\Events\TaskReminderEvent;
use App\Helper\Reply;
use App\Http\Requests\Tasks\StoreTask;
use App\Pinned;
use App\Project;
use App\ProjectMember;
use App\Task;
use App\TaskboardColumn;
use App\TaskCategory;
use App\TaskFile;
use App\TaskLabel;
use App\TaskLabelList;
use App\TaskUser;
use App\WoType;
use App\SportType;
use App\Traits\ProjectProgress;
use App\User;
use App\ClientDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageAllTasksController extends AdminBaseController
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
            $this->wotype = WoType::all();
            $this->startDate = Carbon::today()->subDays(15)->format($this->global->date_format);
            $this->endDate = Carbon::today()->addDays(15)->format($this->global->date_format);
        }

        return $dataTable->render('admin.tasks.index', $this->data);
    }

    public function edit($id)
    {
        $this->task = Task::with('users', 'label')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->task->client_id)->first();
        $this->clients = User::allClients();
        $this->labelIds = $this->task->label->pluck('label_id')->toArray();

        $this->employees  = User::allEmployees();
        $this->wotype = WoType::all();
        $this->sport = SportType::all();
        $this->categories = TaskCategory::all();
        $this->taskLabels = TaskLabelList::all();
        $this->taskBoardColumns = TaskboardColumn::orderBy('priority', 'asc')->get();
        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::where('board_column_id', '<>', $completedTaskColumn->id)
                ->where('id', '!=', $id);

            if ($this->task->project_id != '') {
                $this->allTasks = $this->allTasks->where('project_id', $this->task->project_id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }
        return view('admin.tasks.edit', $this->data);
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
        $task->task_category_id = $request->category_id;
        $task->wo_id = $request->task_type;
        $task->sport_id = $request->sport_type;
        $task->client_id = $request->client_id;
        $task->qty = $request->task_qty;
            
        if($request->user_id && $request->status == "1"){
            $task->board_column_id = 2;
        }else{
            $task->board_column_id = $request->status;
        }

        $taskBoardColumn = TaskboardColumn::findOrFail($request->status);

        if ($taskBoardColumn->slug == 'completed') {
            $task->completed_on = Carbon::now()->format('Y-m-d');
        } else {
            $task->completed_on = null;
        }

        if ($request->project_id != "all") {
            $task->project_id = $request->project_id;
        } else {
            $task->project_id = null;
        }
        $task->save();

        // save labels
        // $task->labels()->sync($request->task_labels);


        // Sync task users
        $task->users()->sync($request->user_id);

 
        return Reply::dataOnly(['taskID' => $task->id]);

        //        return Reply::redirect(route('admin.all-tasks.index'), __('messages.taskUpdatedSuccessfully'));
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // If it is recurring and allowed by user to delete all its recurring tasks
        if ($request->has('recurring') && $request->recurring == 'yes') {
            Task::where('recurring_task_id', $id)->delete();
        }

        // Delete current task
        Task::destroy($id);

        if (!is_null($task->project_id)) {
            //calculate project progress if enabled
            $this->calculateProjectProgress($task->project_id);
        }

        return Reply::success(__('messages.taskDeletedSuccessfully'));
    }

    public function create()
    {
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->user->id)->first();
        $this->clients = User::allClients();
        $this->employees = User::allEmployees();
        $this->categories = TaskCategory::all();
        $this->taskLabels = TaskLabelList::all();
        $this->wotype = WoType::all();
        $this->sport = SportType::all();
        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::where('board_column_id', '<>', $completedTaskColumn->id)->get();
        } else {
            $this->allTasks = [];
        }
        $this->taskboardColumns = TaskboardColumn::orderBy('priority', 'asc')->get();

        $task = new Task();
        $this->fields = $task->getCustomFieldGroupsWithFields()->fields;
        return view('admin.tasks.create', $this->data);
    }

    public function membersList($projectId)
    {

        $this->employees = User::allEmployees();
        $list = view('admin.tasks.members-list', $this->data)->render();
        return Reply::dataOnly(['html' => $list]);
    }

    
    public function dependentTaskLists($projectId, $taskId = null)
    {
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::where('board_column_id', $completedTaskColumn->id)
                ->where('project_id', $projectId);

            if ($taskId != null) {
                $this->allTasks = $this->allTasks->where('id', '!=', $taskId);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }

        $list = view('admin.tasks.dependent-task-list', $this->data)->render();
        return Reply::dataOnly(['html' => $list]);
    }

    public function store(StoreTask $request)
    {
        DB::beginTransaction();
        $ganttTaskArray = [];
        $gantTaskLinkArray = [];
        $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();
        $task = new Task();
        $task->heading = $request->heading;
        if ($request->description != '') {
            $task->description = $request->description;
        }
        $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
            
        $task->task_category_id = $request->category_id;
        $task->site_id = $request->task_labels;
        $task->wo_id = $request->task_type;
        $task->sport_id = $request->sport_type;
        $task->client_id = $request->client_id;
        $task->qty = $request->task_qty;

        // if ($request->board_column_id) {
        //     $task->board_column_id = $request->board_column_id;
        // }
        if($request->user_id && $request->board_column_id == "1"){
            $task->board_column_id = 2;
        }else{
            $task->board_column_id = $request->board_column_id;
        }
        $task->save();

        DB::commit();
        //log search
        $this->logSearchEntry($task->id, 'Task ' . $task->heading, 'admin.all-tasks.edit', 'task');



//         if ($request->board_column_id) {
//             return Reply::redirect(route('admin.taskboard.index'), __('messages.taskCreatedSuccessfully'));
//         }
        return Reply::dataOnly(['taskID' => $task->id]);
        //        return Reply::redirect(route('admin.all-tasks.index'), __('messages.taskCreatedSuccessfully'));
    }

    public function ajaxCreate($columnId)
    {
        $this->projects = Project::allProjects();
        $this->columnId = $columnId;
        $this->categories = TaskCategory::all();
        $this->employees = User::allEmployees();
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::where('board_column_id', $completedTaskColumn->id)->get();
        } else {
            $this->allTasks = [];
        }

        return view('admin.tasks.ajax_create', $this->data);
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
             
        $this->user = User::where('id', '=', $this->task->client_id)->first();
      
        $view = view('admin.tasks.show', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function showFiles($id)
    {
        $this->taskFiles = TaskFile::where('task_id', $id)->get();
        return view('admin.tasks.ajax-file-list', $this->data);
    }

    public function history($id)
    {
        $this->task = Task::with('board_column', 'history', 'history.board_column')->findOrFail($id);
        $view = view('admin.tasks.history', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

}
