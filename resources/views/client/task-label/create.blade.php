@extends('layouts.client-app')
@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/jquery-asColorPicker-master/css/asColorPicker.css') }}">
<style>
    .suggest-colors a {
        border-radius: 4px;
        width: 30px;
        height: 30px;
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 10px;
        text-decoration: none;
    }
</style>
@endpush
@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                
                <li><a href="{{ route('client.task-label.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="panel panel-inverse">
            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('app.add') @lang('app.menu.taskLabel')</div>

            <p class="text-muted m-b-30 font-13"></p>

            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
            {!! Form::open(['id'=>'createContract','class'=>'ajax-form','method'=>'POST']) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company_name" class="required">@lang('app.label') @lang('app.name')</label>
                            <input type="text" class="form-control" name="label_name" value="" />
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">@lang('app.description') </label>
                            <textarea class="form-control"></textarea>
                        </div>

                    </div>
                </div>

                    <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                        @lang('app.save')
                    </button>
                    <button type="reset" class="btn btn-inverse waves-effect waves-light">@lang('app.reset')</button>
                </div>
            {!! Form::close() !!}
            </div>
        </div>
        </div>
    </div>
    <!-- .row -->
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>

    <script>

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('client.task-label.store')}}',
                container: '#createContract',
                type: "POST",
                redirect: true,
                data: $('#createContract').serialize()
            })
        });

    </script>
@endpush

