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
                <li><a href="{{ route('admin.state.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.update')</li>
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
            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.state.updateTitle')</div>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id'=>'updateState','class'=>'ajax-form','method'=>'POST']) !!}
                               <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="names" class="required">@lang("modules.state.stateName")</label>
                                            <input type="text" class="form-control" id="names" name="names" value="">
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug" class="required">@lang("modules.state.stateSlug")</label>
                                            <input type="text" class="form-control" id="slug" name="slug" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country_id" class="required">@lang("modules.state.country")</label>
                                            <select class="select2 form-control" data-placeholder="@lang('modules.state.pleaseSelectCountries')"  id="country_id" name="country_id">
                                                <option value="">@lang('modules.state.pleaseSelectCountries')</option>
                                                @forelse($countries as $country)
                                                     <option value="{{ $country->id }}">{{ ucwords($country->name) }}</option>
                                                  @empty
                                                      <option value="">@lang('modules.state.pleaseSelectCountries')</option>
                                                 @endforelse
                                                    
                                                </select>
                                        </div>
                                   </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="timezone" class="required">@lang("modules.state.timezone")</label>
                                            <select class="select2 form-control" data-placeholder="@lang('modules.state.selectTimezone')"  id="timezone" name="timezone">
                                                <option value="">@lang('modules.state.selectTimezone')</option>
                                                <option value="GMT-12:00">GMT-12:00</option>
                                                <option value="GMT-11:00">GMT-11:00</option>
                                                <option value="GMT-10:00">GMT-10:00</option>
                                                <option value="GMT-09:00">GMT-09:00</option>
                                                <option value="GMT-08:00">GMT-08:00</option>
                                                <option value="GMT-07:00">GMT-07:00</option>
                                                <option value="GMT-06:00">GMT-06:00</option>
                                                <option value="GMT-05:00">GMT-05:00</option>
                                                <option value="GMT-04:00">GMT-04:00</option>
                                                <option value="GMT-03:30">GMT-03:30</option>
                                                <option value="GMT-02:00">GMT-02:00</option>
                                                <option value="GMT-01:00">GMT-01:00</option>
                                                <option value="GMT+00:00">GMT+00:00</option>
                                                <option value="GMT+01:00">GMT+01:00</option>
                                                <option value="GMT+02:00">GMT+02:00</option>
                                                <option value="GMT+03:00">GMT+03:00</option>
                                                <option value="GMT+04:00">GMT+04:00</option>
                                                <option value="GMT+04:30">GMT+04:30</option>
                                                <option value="GMT+05:00">GMT+05:00</option>
                                                <option value="GMT+05:30">GMT+05:30</option>
                                                <option value="GMT+05:45">GMT+05:45</option>
                                                <option value="GMT+06:00">GMT+06:00</option>
                                                <option value="GMT+07:00">GMT+07:00</option>
                                                <option value="GMT+08:00">GMT+08:00</option>
                                                <option value="GMT+09:00">GMT+09:00</option>
                                                <option value="GMT+10:00">GMT+10:00</option>
                                                <option value="GMT+11:00">GMT+11:00</option>
                                                <option value="GMT+12:00">GMT+12:00</option>
                                                <option value="GMT+13:00">GMT+13:00</option>                                                 
                                            </select>
                                        </div>
                                   </div>
                               </div>
                                <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                    @lang('app.save')
                                </button>
                                <a href="{{route('admin.state.index')}}" class="btn btn-default waves-effect waves-light">@lang('app.back')</a>
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
            url: '{{route('admin.state.store')}}',
            container: '#updateState',
            type: "POST",
            data: $('#updateState').serialize()
        })
    });

</script>
@endpush

