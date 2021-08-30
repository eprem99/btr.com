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

<div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.state.updateTitle')</div>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id'=>'updateState','class'=>'ajax-form']) !!}
                               <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="names" class="required">@lang("modules.state.stateName")</label>
                                            <input type="text" class="form-control" id="names" name="names" value="{{ $state->names }}">
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug" class="required">@lang("modules.state.stateSlug")</label>
                                            <input type="text" class="form-control" id="slug" name="slug" value="{{ $state->slug }}">
                                        </div>
                                   </div>
                               <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country_id" class="required">@lang("modules.state.country")</label>
                                            <select class="select2 form-control" data-placeholder="@lang('modules.state.pleaseSelectCountries')"  id="country_id" name="country_id">
                                                <option value="">@lang('modules.state.pleaseSelectCountries')</option>
                                                @forelse($countries as $country)
                                                     <option @if($country->id == $state->country_id) selected
                                                    @endif value="{{ $country->id }}">{{ ucwords($country->name) }}</option>
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
                                                <option @if($state->timezone == 'GMT-12:00') selected @endif value="GMT-12:00">GMT-12:00</option>
                                                <option @if($state->timezone == 'GMT-11:00') selected @endif value="GMT-11:00">GMT-11:00</option>
                                                <option @if($state->timezone == 'GMT-10:00') selected @endif value="GMT-10:00">GMT-10:00</option>
                                                <option @if($state->timezone == 'GMT-09:00') selected @endif value="GMT-09:00">GMT-09:00</option>
                                                <option @if($state->timezone == 'GMT-08:00') selected @endif value="GMT-08:00">GMT-08:00</option>
                                                <option @if($state->timezone == 'GMT-07:00') selected @endif value="GMT-07:00">GMT-07:00</option>
                                                <option @if($state->timezone == 'GMT-06:00') selected @endif value="GMT-06:00">GMT-06:00</option>
                                                <option @if($state->timezone == 'GMT-05:00') selected @endif value="GMT-05:00">GMT-05:00</option>
                                                <option @if($state->timezone == 'GMT-04:00') selected @endif value="GMT-04:00">GMT-04:00</option>
                                                <option @if($state->timezone == 'GMT-03:00') selected @endif value="GMT-03:30">GMT-03:30</option>
                                                <option @if($state->timezone == 'GMT-02:00') selected @endif value="GMT-02:00">GMT-02:00</option>
                                                <option @if($state->timezone == 'GMT-01:00') selected @endif value="GMT-01:00">GMT-01:00</option>
                                                <option @if($state->timezone == 'GMT+00:00') selected @endif value="GMT+00:00">GMT+00:00</option>
                                                <option @if($state->timezone == 'GMT+01:00') selected @endif value="GMT+01:00">GMT+01:00</option>
                                                <option @if($state->timezone == 'GMT+02:00') selected @endif value="GMT+02:00">GMT+02:00</option>
                                                <option @if($state->timezone == 'GMT+03:00') selected @endif value="GMT+03:00">GMT+03:00</option>
                                                <option @if($state->timezone == 'GMT+04:00') selected @endif value="GMT+04:00">GMT+04:00</option>
                                                <option @if($state->timezone == 'GMT+04:30') selected @endif value="GMT+04:30">GMT+04:30</option>
                                                <option @if($state->timezone == 'GMT+05:00') selected @endif value="GMT+05:00">GMT+05:00</option>
                                                <option @if($state->timezone == 'GMT+05:30') selected @endif value="GMT+05:30">GMT+05:30</option>
                                                <option @if($state->timezone == 'GMT+05:45') selected @endif value="GMT+05:45">GMT+05:45</option>
                                                <option @if($state->timezone == 'GMT+06:00') selected @endif value="GMT+06:00">GMT+06:00</option>
                                                <option @if($state->timezone == 'GMT+07:00') selected @endif value="GMT+07:00">GMT+07:00</option>
                                                <option @if($state->timezone == 'GMT+08:00') selected @endif value="GMT+08:00">GMT+08:00</option>
                                                <option @if($state->timezone == 'GMT+09:00') selected @endif value="GMT+09:00">GMT+09:00</option>
                                                <option @if($state->timezone == 'GMT+10:00') selected @endif value="GMT+10:00">GMT+10:00</option>
                                                <option @if($state->timezone == 'GMT+11:00') selected @endif value="GMT+11:00">GMT+11:00</option>
                                                <option @if($state->timezone == 'GMT+12:00') selected @endif value="GMT+12:00">GMT+12:00</option>
                                                <option @if($state->timezone == 'GMT+13:00') selected @endif value="GMT+13:00">GMT+13:00</option>                                                 
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

@endsection

@push('footer-script')
<script>
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.state.update', $state->id )}}',
            container: '#updateState',
            type: "POST",
            data: $('#updateState').serialize()
        })
    });

</script>
@endpush

