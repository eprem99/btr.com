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
                <div class="col-md-4">
                        <div class="form-group">
                            <label for="label_name" class="required"> @lang('app.site.name')</label>
                            <input type="text" class="form-control" name="label_name" value="" />
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_phone" class="required"> @lang('app.site.phone')</label>
                            <input type="text" class="form-control" name="site_phone" value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_fax"> @lang('app.site.fax')</label>
                            <input type="text" class="form-control" name="site_fax" value="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_address" class="required"> @lang('app.site.address')</label>
                            <input type="text" class="form-control" name="site_address" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_suiteunit"> @lang('app.site.suiteunit')</label>
                            <input type="text" class="form-control" name="site_suiteunit" value="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="site_country" class="required"> @lang('app.site.country')</label>
                        <select name="site_country" class="form-control" id="country">
                                <option value>@lang('app.site.country')</option>
                                @foreach($countries as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_state" class="required"> @lang('app.site.state')</label>
                            <select name="site_state" class="select2 form-control" id="state">
                                <option value="0"> -- Select -- </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_city" class="required"> @lang('app.site.city')</label>
                            <input type="text" class="form-control" name="site_city" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_zip" class="required"> @lang('app.site.zip')</label>
                            <input type="text" class="form-control" name="site_zip" value="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_timezone"> @lang('app.site.timezone')</label>
                            <input type="text" class="form-control" name="site_timezone" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_notification"> @lang('app.site.notification') 
                                <input type="checkbox" class="form-control" name="site_notification" value="1" checked/></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_latitude"> @lang('app.site.latitude')</label>
                            <input type="text" class="form-control" name="site_latitude" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_longitude"> @lang('app.site.longitude')</label>
                            <input type="text" class="form-control" name="site_longitude" value="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_pname" class="required"> @lang('app.site.pname')</label>
                            <input type="text" class="form-control" name="site_pname" value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_pphone" class="required"> @lang('app.site.pphone')</label>
                            <input type="text" class="form-control" name="site_pphone" value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_pemail" class="required"> @lang('app.site.pemail')</label>
                            <input type="email" class="form-control" name="site_pemail" value="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_sname"> @lang('app.site.sname')</label>
                            <input type="text" class="form-control" name="site_sname" value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_sphone"> @lang('app.site.sphone')</label>
                            <input type="text" class="form-control" name="site_sphone" value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_semail"> @lang('app.site.semail')</label>
                            <input type="email" class="form-control" name="site_semail" value="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">@lang('app.site.description') </label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                    </div>
                </div>

                    <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                        @lang('app.save')
                    </button>
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
        $('#country').select2({
        }).on("change", function (e) {
        var id = $(this).val();
        var url = "{{ route('client.state.country',':id') }}";
        url = url.replace(':id', id);
       // console.log(url);
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            data: $('#createContract').serialize(),
            success: function (data) {
            //  alert(data.data)
                $('#state').html(data.data);
            }
        })
    });
    </script>
@endpush

