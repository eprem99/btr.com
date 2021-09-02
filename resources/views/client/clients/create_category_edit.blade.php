
@extends('layouts.client-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
    <style>
        .salutation .form-control {
            padding: 2px 2px;
          }
          .bg-title .breadcrumb {
                display: block !important;
            }
       </style>
@endpush

@section('content')

<div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.client.editCompany')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
       {!! Form::open(['id'=>'createClientCategory','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="required">@lang('modules.client.categoryName')</label>
                        <input type="text" name="category_name" id="category_name" class="form-control" value="{{$category->category_name}}">
                    </div>
                </div>
            </div>
            <h3 class="box-title">@lang('modules.client.editCompanyAddress')</h3>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_address" class="required">@lang('modules.client.categoryAddress')</label>
                        <input type="text" name="category_address" id="category_address" class="form-control" value="{{$category->category_address}}">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_suite">@lang('modules.client.categorySuite')</label>
                        <input type="text" name="category_suite" id="category_suite" class="form-control" value="{{$category->category_suite}}">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="country" class="required">@lang('modules.stripeCustomerAddress.country')</label>

                        <select name="category_country" class="form-control" id="country">
                            <option value>@lang('app.site.country')</option>
                            @foreach ($countries as $item)
                                <option
                                @if ($item->id == $category->category_country)
                                    selected
                                @endif
                                value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label>@lang('modules.stripeCustomerAddress.state')</label>
                        <select name="category_state" class="form-control" id="state">
                            <option value="0"> -- Select -- </option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_city">@lang('modules.client.categoryCity')</label>
                        <input type="text" name="category_city" id="category_city" class="form-control" value="{{$category->category_city}}">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_zip">@lang('modules.client.categoryZip')</label>
                        <input type="text" name="category_zip" id="category_zip" class="form-control" value="{{$category->category_zip}}">
                    </div>
                </div>
            </div>

            <h3 class="box-title">@lang('modules.client.editCompanyContacts')</h3>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_email" class="required">@lang('modules.client.categoryemail')</label>
                        <input type="text" name="category_email" id="category_email" class="form-control" value="{{$category->category_email}}">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_phone" class="required">@lang('modules.client.categoryphone')</label>
                        <input type="text" name="category_phone" id="category_phone" class="form-control" value="{{$category->category_phone}}">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_altphone">@lang('modules.client.categoryaltphone')</label>
                        <input type="text" name="category_altphone" id="category_altphone" class="form-control" value="{{$category->category_altphone}}">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_fax">@lang('modules.client.categoryfax')</label>
                        <input type="text" name="category_fax" id="category_fax" class="form-control" value="{{$category->category_fax}}">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-category" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
        </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script>

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
$('#save-category').click(function () {
    $.easyAjax({
        url: '{{route('client.company.update')}}',
        container: '#createClientCategory',
        type: "POST",
        data: $('#createClientCategory').serialize(),
        success: function (response) {
            if(response.status == 'success'){
                if(response.status == 'success'){
                    console.log(response.data);
                    var options = [];
                    var rData = [];
                    rData = response.data;
                    $.each(rData, function( index, value ) {
                        var selectData = '';
                        selectData = '<option value="'+value.id+'">'+value.category_name+'</option>';
                        options.push(selectData);
                    });
                }
            }
        }
    })
});
$('#country').select2({
        }).on("change", function (e) {
        var url = "{{ route('client.company.state', [$category->id]) }}";
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            data: $('#createClientCategory').serialize(),
            success: function (data) {
                $('#state').html(data.data);
            }
        })
    });
    jQuery(document).ready(function($) {
        $.each($('#country option:selected'), function(){            

        var url = "{{ route('client.company.state', [$category->id]) }}";
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            data: $('#createClientCategory').serialize(),
            success: function (data) {
            //  alert(data.data)
                $('#state').html(data.data);
                
            }
        })
        });
	});
</script>
@endpush
