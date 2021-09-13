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
            <a href="{{ route('admin.country.create') }}" id="addcountry" class="btn btn-outline btn-success btn-sm">@lang('modules.country.newcountry') <i class="fa fa-plus" aria-hidden="true"></i></a>
            
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')
<div class="vtabs customvtab m-t-10">
@include('sections.country_setting_menu')
<div class="tab-content">
    <div id="vhome3" class="tab-pane active">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('modules.country.countryName')</th>
                                    <th>@lang('modules.country.iso')</th>
                                    <th>@lang('modules.country.iso3')</th>
                                    <th>@lang('modules.country.pcode')</th>
                                    <th>@lang('modules.country.ncode')</th>
                                    <th class="text-nowrap">@lang('app.action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($countries as $key => $country)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ ucwords($country->name) }} </td>
                                        <td>{{ $country->iso }}</td>
                                        <td>{{ $country->iso3 }}</td>
                                        <td>{{ $country->phonecode }}</td>
                                        <td>{{ $country->numcode }}</td>
                                        <td class="text-nowrap">

                                            <div class="btn-group dropdown m-r-10">
                                                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                                                <ul role="menu" class="dropdown-menu pull-right">
                                                    <li><a href="{{ route('admin.country.edit', [$country->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> @lang('app.edit')</a></li>
                                                    <li><a href="javascript:;"  data-currency-id="{{ $country->id }}"  class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> @lang('app.delete') </a></li>
                                    
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>    <!-- .row -->

            <div class="clearfix"></div>
            </div>
        </div>


    </div>
    <!-- .row -->

    </div>
  </div>
</div>


@endsection

@push('footer-script')
<script>

    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('currency-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.deleteCurrency')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.country.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });

</script>
@endpush