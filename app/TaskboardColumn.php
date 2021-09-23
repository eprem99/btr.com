<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskboardColumn extends BaseModel
{
    protected $fillable = ['column_name', 'slug', 'label_color', 'priority', 'role_id'];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'board_column_id')->orderBy('column_priority');
    }

    public function membertasks()
    {
        return $this->hasMany(Task::class, 'board_column_id')->where('user_id', auth()->user()->id)->orderBy('column_priority');
    }

    public static function completeColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'completed')->first();
            }
        );
    }
    public static function closedColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'closed')->first();
            }
        );
    }
    public static function canceledColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'cancelled')->first();
            }
        );
    }
}
