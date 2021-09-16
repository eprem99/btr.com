@extends('layouts.app')

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
            <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
            <li><a href="{{ route('admin.wotype.index') }}">@lang($pageTitle)</a></li>
            <li class="active">@lang('app.addNew')</li>
        </ol>
    </div>
    <!-- /.breadcrumb -->
</div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/icheck/skins/all.css') }}">
@endpush

@section('content')
<div class="vtabs customvtab m-t-10">

@include('sections.admin_setting_menu')

<div class="tab-content">
    <div id="vhome3" class="tab-pane active">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">@lang('app.update') @lang('app.menu.wotype')</div>

            <p class="text-muted font-13"></p>

            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                        {!! Form::open(['id'=>'createwotype','class'=>'ajax-form','method'=>'PUT']) !!}
                            <div class="form-group">
                                <label for="wotype_name" class="required">@lang('app.menu.wotype')</label>
                                <input type="text" class="form-control" id="wotype_name" name="name"
                                    value="{{ $group->name }}">
                            </div>
                            <div class="form-group">
                                <label for="wotype_price" class="required">@lang('app.prcie')</label>
                                <input type="text" class="form-control" id="wotype_price" name="price"
                                    value="{{ $group->price }}">
                            </div>

                            <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                @lang('app.save')
                            </button>
                            <a href="{{route('admin.wotype.index')}}" class="btn btn-default waves-effect waves-light">@lang('app.back')</a>
                            {!! Form::close() !!}
                            <hr>

                        </div>
                    </div>
                </div>
            </div>
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

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.wotype.update', [$group->id])}}',
                container: '#createwotype',
                type: "POST",
                redirect: true,
                data: $('#createwotype').serialize()
            })
        });

</script>
@endpush