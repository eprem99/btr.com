<?php

namespace App\DataTables\Admin;


use App\DataTables\BaseDataTable;
use App\TaskLabelList;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use App\State;


class LabelDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                <ul role="menu" class="dropdown-menu pull-right">
                  <li><a href="' . route('admin.site.edit', [$row->id]) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>
                  <li><a href="javascript:;"   data-contract-id="' . $row->id . '"  class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';

                $action .= '</ul> </div>';

                return $action;
            })
            ->editColumn('id', function ($row) {
                return ucwords($row->id);
            })
            
            ->editColumn('client', function ($row) {
                if($row->user_id){
                  //  $siteid = json_decode($row->contacts, true);
                    $client = User::allClients()->where('id', '=', $row->user_id)->first();
                    if($client){
                        return $client->name;
                    }else{
                         return '--';
                    }
                        
                }
                return '--';

            })
            
            ->editColumn('label_name', function ($row) {
                return '<a onclick="siteshow('.$row->id.')" data-id="' .$row->id. '" href="#">'.ucwords($row->label_name).'</a>';
            })

            ->editColumn('site_city', function ($row) {
                if($row->contacts){
                    $siteid = json_decode($row->contacts, true);
                    if($siteid['site_city'] != NULL){
                        return ucwords($siteid['site_city']);
                    }else{
                        return '--';

                    }
                        return ucwords($siteid['site_city']);
                }
                return '--';

            })
            ->editColumn('site_state', function ($row) {
                if($row->contacts){
                    $siteid = json_decode($row->contacts, true);
                    if($siteid['site_state'] != '' || $siteid['site_state'] != 0 || $siteid['site_state'] != NULL){
                        $states = State::where('id', '=', $siteid['site_state'])->first();
                        if($states){
                            return $states->names;
                        }
                        
                    }else{
                        return '--';
                    }
                }
                return '--';

            })
            ->editColumn('site_phone', function ($row) {
                if($row->contacts){
                    $siteid = json_decode($row->contacts, true);
                        return ucwords($siteid['site_phone']);
                }
                return '--';

            })

            ->editColumn('created_at', function ($row) {
                if($row->created_at){
                        return ucwords($row->created_at);
                }
                return '--';

            })
            ->addIndexColumn()
            ->rawColumns(['action', 'label_name']);
    }

    /**
     * @param TaskLabelList $model
     * @return TaskLabelList
     */
    public function query(TaskLabelList $model)
    {
        $request = $this->request();


        return $model->select('id', 'label_name', 'contacts', 'created_at');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
        ->setTableId('taskLabelList-table')
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
               window.LaravelDataTables["taskLabelList-table"].buttons().container()
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
            __('app.site.id')  => ['data' => 'id', 'name' => 'id'],
            __('app.site.name') => ['data' => 'label_name', 'name' => 'label_name'],
            __('app.site.client') => ['data' => 'client', 'name' => 'client', 'searchable' => false, 'orderable' => false],
            __('app.site.city')  => ['data' => 'site_city', 'name' => 'contacts', 'searchable' => false, 'orderable' => false],
            __('app.site.state')  => ['data' => 'site_state', 'name' => 'contacts', 'searchable' => false, 'orderable' => false],
            __('app.site.phone')  => ['data' => 'site_phone', 'name' => 'contacts', 'searchable' => false, 'orderable' => false],
            __('app.site.scheduled')  => ['data' => 'created_at', 'name' => 'created_at', 'searchable' => true, 'orderable' => true],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(true)
                ->width(50)
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
        return 'taskLabelList_' . date('YmdHis');
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
