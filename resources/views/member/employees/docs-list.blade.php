@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
    <style>
        .table .btn-inverse {
            color: #000;
        }
    </style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="white-box">
       <button class="btn btn-sm btn-info addDocs" onclick="showAdd()"><i class="fa fa-plus"></i> @lang('app.add')</button>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th width="70%">@lang('app.name')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                    </thead>
                    <tbody id="employeeDocsList">
                    @forelse($employeeDocs as $key=>$employeeDoc)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ ucwords($employeeDoc->name) }}</td>
                            <td>
                                <a href="{{ route('member.employee-docs.download', $employeeDoc->id) }}"
                                data-toggle="tooltip" data-original-title="Download"
                                class="btn btn-inverse btn-circle"><i
                                            class="fa fa-download"></i></a>
                                <a target="_blank" href="{{ asset_url('employee-docs/'.$employeeDoc->user_id.'/'.$employeeDoc->hashname) }}"
                                data-toggle="tooltip" data-original-title="View"
                                class="btn btn-info btn-circle"><i
                                            class="fa fa-search"></i></a>
                             </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">@lang('messages.noDocsFound')</td>
                        </tr>
                    @endforelse
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<!-- /.row -->
{{--Ajax Modal--}}
        <div class="modal fade bs-modal-md in" id="edit-column-form" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-md" id="modal-data-application">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                    </div>
                    <div class="modal-body">
                        Loading...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn blue">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        {{--Ajax Modal Ends--}}    
@endsection

@push('footer-script')
<script>
    var table;
    // Show Create employeeDocs Modal
    function showAdd() {
        var url = "{{ route('member.employees.docs-create', [$employee->id]) }}";
        $.ajaxModal('#edit-column-form', url);
    }

    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('file-id');
        var deleteView = $(this).data('pk');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.removeFileText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('member.employee-docs.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE', 'view': deleteView},
                    success: function (response) {
                        console.log(response);
                        if (response.status == "success") {
                            $.unblockUI();
                            $('#employeeDocsList').html(response.html);
                        }
                    }
                });
            }
        });
    });
</script>

@endpush