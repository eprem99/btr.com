<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Task;
use App\TaskboardColumn;
use Carbon\Carbon;
use App\State;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class AllMemberTasksDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $taskBoardColumns = TaskboardColumn::orderBy('priority', 'asc')->get();

        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) use ($taskBoardColumns) {

                if ($this->user->can('delete_tasks')) {
                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                <ul role="menu" class="dropdown-menu pull-right">
                  <li><a href="' . route('admin.all-tasks.edit', $row->id) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>
                  <li><a href="javascript:;"  data-task-id="' . $row->id . '" data-recurring="no" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';

                $action .= '</ul> </div>';
                }elseif($this->user->can('edit_tasks')){
                    $action = '<a href="' . route('client.all-tasks.edit', $row->id) . '" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }else{
                    $action = '<a target="_blank" href="' . route('front.task-share', [$row->hash]) . '" class="btn btn-info btn-circle"
                    data-toggle="tooltip" data-original-title="View"><i class="fa fa-share-alt" aria-hidden="true"></i></a>';
                }
              //  return $this->user->can('delete_projects');
               return $action;
            })
            ->addColumn('site', function ($row) {
                $site = '';            
                if ($row->label_name) {
                    $site = $row->label_name;
                } 
               return $site;
            })

            ->addColumn('siteid', function ($row) {
                $site = '';            
                if ($row->ids) {
                    $site = $row->ids;
                } 
                
               return $site;
            })
            ->addColumn('state', function ($row) {
                $site = '';            
                if ($row->contacts) {
                   $state = json_decode($row->contacts, true);
                    $site = $state['site_state'];
                    if($site != NULL){
                        $site = State::where('id', '=', $site)->first();
                        if($site != '' || !empty($site)){
                            $state = $site->names;
                        }else{
                            $state = '';
                        } 
                    }

                } 
                
               return $state;
            })
            ->addColumn('city', function ($row) {
                $site = '';            
                if ($row->contacts) {
                   $state = json_decode($row->contacts, true);
                    $site = $state['site_city'];
                } 
                
               return $site;
            })
            ->editColumn('due_date', function ($row) {

                if ($row->due_date->endOfDay()->isPast()) {
                    return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                } elseif ($row->due_date->setTimezone($this->global->timezone)->isToday()) {
                    return '<span class="text-success">' . __('app.today') . '</span>';
                }
                return '<span >' . $row->due_date->format($this->global->date_format) . '</span>';
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
            ->addColumn('name', function ($row) {
                $members = [];
                foreach ($row->users as $member) {
                    $members[] = $member->name;
                }
                return implode(',', $members);
            })
            ->editColumn('clientName', function ($row) {
                return ($row->clientName) ? ucwords($row->clientName) : '-';
            })
            ->editColumn('created_by', function ($row) {
                if (!is_null($row->created_by)) {
                    return ($row->created_image) ? '<img src="' . asset_url('avatar/' . $row->created_image) . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ' . ucwords($row->created_by) : '<img src="' . asset('img/default-profile-3.png') . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ' . ucwords($row->created_by);
                }
                return '--';
            })
            ->editColumn('heading', function ($row) {

                $name = '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a> ';


               return $name;
            })
            ->editColumn('board_column', function ($row) use ($taskBoardColumns) {
                $status = '<div class="btn-group dropdown">';
                $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs"  style="border-color: ' . $row->label_color . '; color: ' . $row->label_color . '" type="button">' . $row->board_column . ' <span class="caret"></span></button>';
                $status .= '<ul role="menu" class="dropdown-menu pull-right">';
                if ($this->user->can('delete_tasks')) {
                    foreach ($taskBoardColumns as $key => $value) {
                        $status .= '<li><a href="javascript:;" data-task-id="' . $row->id . '" class="change-status" data-status="' . $value->slug . '">' . $value->column_name . '  <span style="width: 15px; height: 15px; border-color: ' . $value->label_color . '; background: ' . $value->label_color . '"
                                class="btn btn-warning btn-small btn-circle">&nbsp;</span></a></li>';
                    }
                }elseif($this->user->can('edit_tasks')){
                    foreach ($taskBoardColumns as $key => $value) {
                        if($value->role_id == $row->role_id){
                            $status .= '<li><a href="javascript:;" data-task-id="' . $row->id . '" class="change-status" data-status="' . $value->slug . '">' . $value->column_name . '  <span style="width: 15px; height: 15px; border-color: ' . $value->label_color . '; background: ' . $value->label_color . '"
                                class="btn btn-warning btn-small btn-circle">&nbsp;</span></a></li>';
                        }
                    }

                }else{
                    $status = '<div class="btn-group dropdown">';
                    $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs"  style="border-color: ' . $row->label_color . '; color: ' . $row->label_color . '" type="button">' . $row->board_column . '</button>';
                
                }
                $status .= '</ul>';
                $status .= '</div>';
                return $status;
            })
            ->addColumn('status', function ($row) {
                return ucfirst($row->column_name);
            })
            ->rawColumns(['board_column', 'action',  'clientName', 'due_date', 'users', 'created_by', 'heading']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Task $model)
    {
        $request = $this->request();
        $startDate = null;
        $endDate = null;
        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
        }
        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
        }

        $hideCompleted = $request->hideCompleted;
        $taskBoardColumn = TaskboardColumn::completeColumn();

        $model = $model->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->join('users as client', 'task_users.user_id', '=', 'client.id')
            ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
            ->leftJoin('role_user as role', 'tasks.created_by', '=', 'role.user_id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->join('task_label_list', 'tasks.site_id', '=', 'task_label_list.id')
            ->selectRaw('tasks.id, tasks.heading, tasks.hash, task_label_list.contacts, task_label_list.label_name, task_label_list.id as ids, creator_user.name as created_by, 
            creator_user.id as created_by_id, creator_user.image as created_image,
             tasks.due_date, taskboard_columns.column_name as board_column, taskboard_columns.label_color, role.role_id')
            ->with('users')
            ->where('task_users.user_id', '=', user()->id)
            ->groupBy('tasks.id');
 
          //  dd(user()->id);

        if ($startDate !== null && $endDate !== null) {
            $model->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw('DATE(tasks.`due_date`)'), [$startDate, $endDate]);

                $q->orWhereBetween(DB::raw('DATE(tasks.`start_date`)'), [$startDate, $endDate]);
            });
        }

        if ($request->assignedTo != '' && $request->assignedTo !=  null && $request->assignedTo !=  'all') {
            $model->where('task_users.user_id', '=', $request->assignedTo);
        }

        if ($request->assignedBY != '' && $request->assignedBY !=  null && $request->assignedBY !=  'all') {
            $model->where('creator_user.id', '=', $request->assignedBY);
        }

        if (isset($_GET['stat']) && $_GET['stat'] == '0') {
            $model->where('tasks.board_column_id', '!=', '11');
        }elseif(isset($_GET['stat']) && $_GET['stat'] != ''){
            $model->where('tasks.board_column_id', '=', $_GET['stat']);
        }else{
            $model->where('tasks.board_column_id', '=', $request->status);
        }
        if ($request->label != '' && $request->label !=  null && $request->label !=  'all') {
            $model->where('tasks.site_id', '=', $request->label);
        }

        if ($request->category_id != '' && $request->category_id !=  null && $request->category_id !=  'all') {
            $model->where('tasks.task_category_id', '=', $request->category_id);
        }
        if(isset($_GET['hideComplet'])){
            if (isset($_GET['hideComplet']) &&  $_GET['hideComplet'] == '1') {
                $model->where('tasks.board_column_id', '<>', $taskBoardColumn->id);
            }
        }else{
            if ($hideCompleted == '1') {
                $model->where('tasks.board_column_id', '<>', $taskBoardColumn->id);
            }
        }


        return $model;
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
                   window.LaravelDataTables["allTasks-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ]);
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
            __('modules.tasks.site')  => ['data' => 'site', 'name' => 'site'],
          //  __('modules.tasks.siteid')  => ['data' => 'siteid', 'name' => 'siteid'],
            __('modules.tasks.state')  => ['data' => 'state', 'name' => 'state'],
            __('modules.tasks.city')  => ['data' => 'city', 'name' => 'city'],
            __('modules.tasks.assigned') => ['data' => 'name', 'name' => 'name', 'visible' => false],
            __('modules.tasks.assignTo') => ['data' => 'users', 'name' => 'name', 'exportable' => false],
            __('app.dueDate') => ['data' => 'due_date', 'name' => 'due_date'],
            __('app.status') => ['data' => 'status', 'name' => 'status', 'visible' => false],
            __('app.columnStatus') => ['data' => 'board_column', 'name' => 'board_column', 'exportable' => false, 'searchable' => false],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(40)
                ->addClass('text-center')
        ];
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
