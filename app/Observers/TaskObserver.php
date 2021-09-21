<?php

namespace App\Observers;

use App\Events\TaskEvent;
use App\Events\TaskUpdated as EventsTaskUpdated;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Task;
use App\TaskboardColumn;
use App\Traits\ProjectProgress;
use App\UniversalSearch;
use App\User;
use Carbon\Carbon;

class TaskObserver
{

  //  use ProjectProgress;
    public function saving(Task $task)
    {

    }

    public function creating(Task $task)
    {
        $task->hash = \Illuminate\Support\Str::random(32);
        if (!isRunningInConsoleOrSeeding()) {
            $user = auth()->user();
            //         Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
            if ($user) {
                $task->created_by = $user->id;
            }
        }
    }

    public function created(Task $task)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (request()->has('project_id') && request()->project_id != "all" && request()->project_id != '') {
                if ($task->project->client_id != null && $task->project->allow_client_notification == 'enable' && $task->project->client->status != 'deactive') {
                    event(new TaskEvent($task, $task->project->client, 'NewClientTask'));
                }
            }
            $log = new AdminBaseController();
            if (\user()) {
                $log->logTaskActivity($task->id, user()->id, "createActivity", $task->board_column_id);
            }

            // if ($task->project_id) {
            //     //calculate project progress if enabled
            //     $log->logProjectActivity($task->project_id, __('messages.newTaskAddedToTheProject'));
            //     $this->calculateProjectProgress($task->project_id);
            // }

            //log search
            $log->logSearchEntry($task->id, 'Task: ' . $task->heading, 'admin.all-tasks.edit', 'task');

            // Sync task users
            if (!empty(request()->user_id)) {
                $task->users()->sync(request()->user_id);
            }


            //Send notification to user
            event(new TaskEvent($task, $task->users, 'NewTask'));
        }
    }

    public function updated(Task $task)
    {
        if (!isRunningInConsoleOrSeeding()) {

            if ($task->isDirty('board_column_id')) {

                if (request()->status == 'completed') {

                    $admins = User::allAdmins();
                    event(new TaskEvent($task, $admins, 'TaskCompleted'));

                    $taskUser = $task->users->whereNotIn('id', $admins->pluck('id'));
                    event(new TaskEvent($task, $taskUser, 'TaskUpdated'));

                }elseif(request()->status == 'assigned') {

                    $clients = User::allClients();
                    $taskUser = $task->users->whereNotIn('id', $clients->pluck('id'));
                    event(new TaskEvent($task, $taskUser, 'TaskUpdated'));
    
                }elseif(request()->status == 'scheduled') {

                    event(new TaskEvent($task, $task->users, 'TaskUpdated'));
     
                 }elseif(request()->status == 'tech-Off-Site') {
                  
                    $clients = User::allClients();
                    $taskUser = $task->users->whereNotIn('id', $clients->pluck('id'));
                    event(new TaskEvent($task, $taskUser, 'TaskUpdated'));
     
                 }elseif(request()->status == 'incomplete') {

                    $clients = User::allClients();
                    $taskUser = $task->users->whereNotIn('id', $clients->pluck('id'));
                    event(new TaskEvent($task, $taskUser, 'TaskUpdated'));
     
                 }elseif(request()->status == 'off-site-complete') {

                    $clients = User::allClients();
                    $taskUser = $task->users->whereNotIn('id', $clients->pluck('id'));
                    event(new TaskEvent($task, $taskUser, 'TaskUpdated'));
     
                 }elseif(request()->status == 'off-site-return-trip-required') {
                  
                    $admins = User::allAdmins();
                    $taskUser = $task->users->whereNotIn('id', $admins->pluck('id'));
                    event(new TaskEvent($task, $taskUser, 'TaskUpdated'));
                     
                }elseif(request()->status == 'cancelled') {

                    $admins = User::allEmployees();
                    $taskUser = $task->users->whereNotIn('id', $admins->pluck('id'));
                    event(new TaskEvent($task, $taskUser, 'TaskUpdated'));
    
                }

            }

            // if (request('user_id')) {
            //     //Send notification to user
            //     event(new TaskEvent($task, $task->users, 'TaskUpdated'));

            //     if ((request()->project_id != "all") && !is_null($task->project)) {
            //         if ($task->project->client_id != null && $task->project->allow_client_notification == 'enable' && $task->project->client->status != 'deactive') {
            //             event(new TaskEvent($task, $task->project->client, 'TaskUpdatedClient'));
            //         }
            //     }
            // }
        }

        if (!request()->has('draggingTaskId') && !request()->has('draggedTaskId')) {
            event(new EventsTaskUpdated($task));
        }

        if (\user()) {
            $log = new AdminBaseController();
            $log->logTaskActivity($task->id, user()->id, "updateActivity", $task->board_column_id);
        }

        // if ($task->project_id) {
        //     //calculate project progress if enabled
        //     $this->calculateProjectProgress($task->project_id);
        // }
    }

    public function deleting(Task $task)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $task->id)->where('module_type', 'task')->get();
        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }
    }
}
