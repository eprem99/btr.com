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
            if (User::isAdmin(user()->id) && request('user_id')) {
                $notifyuser = User::whereIn('id', request('user_id'))->where('email_notifications', '=', '1')->get();
                event(new TaskEvent($task, $notifyuser, 'NewTask'));
            }elseif(User::isClient(user()->id)){
                $admins = User::allAdmins();
                event(new TaskEvent($task, $admins, 'NewTask'));
            }else{
                $admins = User::allAdmins();
                event(new TaskEvent($task, $admins, 'TaskCompleted'));
            }
        
        }
    }

    public function updated(Task $task)
    {
        if (!isRunningInConsoleOrSeeding()) {

            if ($task->isDirty('board_column_id')) {

                if (request()->status == 'completed') {

                    $admins = User::allAdmins();
                    event(new TaskEvent($task, $admins, 'TaskCompleted'));

                    $taskUser = $task->notifyusers->whereNotIn('id', $admins->pluck('id'));
                   
                    event(new TaskEvent($task, $taskUser, 'TaskUpdated'));

                }elseif(request()->status == 'assigned') {

                    event(new TaskEvent($task, $task->notifyusers, 'TaskUpdated'));
    
                }elseif(request()->status == 'scheduled') {
                  
                    event(new TaskEvent($task, $task->create_by, 'TaskCompletedClient'));
                    
                    $admins = User::allAdmins();
                    event(new TaskEvent($task, $admins, 'TaskUpdated'));
                    
                    event(new TaskEvent($task, $task->notifyusers, 'TaskUpdated'));
     
                 }elseif(request()->status == 'tech-Off-Site') {
                  

                    event(new TaskEvent($task, $task->notifyusers, 'TaskUpdated'));
     
                 }elseif(request()->status == 'incomplete') {

                    event(new TaskEvent($task, $task->notifyusers, 'TaskUpdated'));
     
                 }elseif(request()->status == 'off-site-complete') {

                    event(new TaskEvent($task, $task->notifyusers, 'TaskUpdated'));
     
                 }elseif(request()->status == 'off-site-return-trip-required') {
                                    
                    event(new TaskEvent($task, $task->create_by, 'TaskUpdated'));
                     
                }elseif(request()->status == 'cancelled') {

                    event(new TaskEvent($task, $task->create_by, 'TaskUpdated'));
                    
                    $admins = User::allAdmins();
                    event(new TaskEvent($task, $admins, 'TaskUpdated'));
    
                }
                      
            }
            
            if (request('user_id')) {
                   //Send notification to user
                   if(User::isAdmin(user()->id) && $task->board_column_id = 1){
                      $notifyuser = User::whereIn('id', request('user_id'))->where('email_notifications', '=', '1')->get();
                      event(new TaskEvent($task, $notifyuser, 'NewTask'));
                      event(new TaskEvent($task, $task->create_by, 'TaskUpdatedClient'));
                   }elseif(User::isAdmin(user()->id) && $task->board_column_id != 1){
                      event(new TaskEvent($task, $task->users, 'TaskUpdatedClient'));
                      event(new TaskEvent($task, $task->create_by, 'TaskUpdatedClient'));
                   }elseif(User::isClient(user()->id)){
                       event(new TaskEvent($task, $task->users, 'TaskUpdated'));
                       $admins = User::allAdmins();
                       event(new TaskEvent($task, $admins, 'TaskUpdated'));
                   }
            }
        }


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
