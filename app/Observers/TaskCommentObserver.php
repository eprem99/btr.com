<?php

namespace App\Observers;

use App\Events\TaskCommentEvent;
use App\Task;
use App\TaskComment;

class TaskCommentObserver
{
    public function created(TaskComment $comment)
    {
        if (!isRunningInConsoleOrSeeding() ) {
            $task = Task::findOrFail($comment->task_id);

            if ($task->task_id != null) {
                if ($task->notifyusers != null) {
                    event(new TaskCommentEvent($task, $comment->created_at, $task->notifyusers, 'client'));
                }
                event(new TaskCommentEvent($task, $comment->created_at, $task->notifyusers));
            }
        }
    }
}
