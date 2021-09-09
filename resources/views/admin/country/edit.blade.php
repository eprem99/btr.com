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
                <li><a href="{{ route('admin.country.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.update')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')
<div class="vtabs customvtab m-t-10">

@include('sections.admin_setting_menu')

<div class="tab-content">
    <div id="vhome3" class="tab-pane active">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.country.updateTitle')</div>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id'=>'updateCountry','class'=>'ajax-form']) !!}
                               <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="required">@lang("modules.country.countryName")</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $country->name }}">
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nicename" class="required">@lang("modules.country.nicename")</label>
                                            <input type="text" class="form-control" id="nicename" name="nicename" value="{{ $country->nicename }}">
                                        </div>
                                   </div>
                                   <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="iso" class="required">@lang("modules.country.iso")</label>
                                            <input type="text" class="form-control" id="iso" name="iso" value="{{ $country->iso }}">
                                        </div>
                                   </div>
                                   <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="iso3" class="required">@lang("modules.country.iso3")</label>
                                            <input type="text" class="form-control" id="iso3" name="iso3" value="{{ $country->iso3 }}">
                                        </div>
                                   </div>
                                   <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="phonecode" class="required">@lang("modules.country.pcode")</label>
                                            <input type="text" class="form-control" id="phonecode" name="phonecode" value="{{ $country->phonecode }}">
                                        </div>
                                   </div>
                                   <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="numcode" class="required">@lang("modules.country.ncode")</label>
                                            <input type="text" class="form-control" id="numcode" name="numcode" value="{{ $country->numcode }}">
                                        </div>
                                   </div>
                               </div>

                                <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                    @lang('app.save')
                                </button>
                                <a href="{{route('admin.country.index')}}" class="btn btn-default waves-effect waves-light">@lang('app.back')</a>
                                {!! Form::close() !!}
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
            url: '{{route('admin.country.update', $country->id )}}',
            container: '#updateCountry',
            type: "POST",
            data: $('#updateCountry').serialize()
        })
    });

</script>
@endpush

