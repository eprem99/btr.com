@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.company.creates') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.company.newcompany') <i class="fa fa-plus" aria-hidden="true"></i></a>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.menu.company')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->

    </div>
@endsection
@push('head-script')
<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
<style>
    .swal-footer {
        text-align: center !important;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <hr>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                            id="tasks-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('app.menu.company')</th>
                                <th>@lang('app.site.country')</th>
                                <th>@lang('app.site.state')</th>
                                <th>@lang('app.phone')</th>
                                <th>@lang('app.action')</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script>
    showTable();

    $('#leave-table').dataTable({
        responsive: true,
        "columnDefs": [
            { responsivePriority: 1, targets: 0, 'width': '20%' },
            { responsivePriority: 2, targets: 1, 'width': '20%' }
        ],
        "autoWidth" : false,
        searching: false,
        paging: false,
        info: false
    });

    var table;

    function showTable() {

        var url = '{{ route('admin.company.data') }}';

        table = $('#tasks-table').dataTable({
            destroy: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: url,
            deferRender: true,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function (oSettings) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            "order": [[0, "desc"]],
            columns: [
                {data: 'id', name: 'id' },
                {data: 'category_name', name: 'category_name', width: '20%'},
                {data: 'category_country', name: 'category_country'},
                {data: 'category_state', name: 'category_state'},
                {data: 'category_phone', name: 'category_phone'},
                {data: 'action', name: 'action', "searchable": false}
            ]
        });
    }
    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('task-id');
        var recurring = $(this).data('recurring');

        var buttons = {
            cancel: "No, cancel please!",
            confirm: {
                text: "Yes, delete it!",
                value: 'confirm',
                visible: true,
                className: "danger",
            }
        };

        if(recurring == 'yes')
        {
            buttons.recurring = {
                text: "{{ trans('modules.tasks.deleteRecurringCompany') }}",
                value: 'recurring'
            }
        }

        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.deleteCompany')",
            dangerMode: true,
            icon: 'warning',
            buttons: buttons,
        }).then(function (isConfirm) {
            if (isConfirm == 'confirm' || isConfirm == 'recurring') {

                var url = "{{ route('admin.company.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";
                var dataObject = {'_token': token, '_method': 'DELETE'};

                if(isConfirm == 'recurring')
                {
                    dataObject.recurring = 'yes';
                }

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: dataObject,
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            table._fnDraw();
                        }
                    }
                });
            }
        });
    });
    $('ul.showClientTabs .clientProjects').addClass('tab-current');
   
   </script>
@endpush