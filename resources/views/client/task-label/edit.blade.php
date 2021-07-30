
@extends('layouts.client-app')
@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
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
@php
$contacts = json_decode($taskLabel->contacts, true);

@endphp

@section('content')

    <div class="row">
        <div class="panel panel-inverse">
            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('app.edit') @lang('app.menu.taskLabel')</div>

                <p class="text-muted m-b-30 font-13"></p>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createContract','class'=>'ajax-form','method'=>'PUT']) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company_name" class="required">@lang('app.name')</label>
    
                            <input type="text" class="form-control" name="label_name" value="{{ $taskLabel->label_name }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_id"> @lang('app.site.id')</label>
                            <input type="text" class="form-control" name="site_id" value="{{ $contacts['site_id'] }}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_phone"> @lang('app.site.phone')</label>
                            <input type="text" class="form-control" name="site_phone" value="{{ $contacts['site_phone'] }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_fax"> @lang('app.site.fax')</label>
                            <input type="text" class="form-control" name="site_fax" value="{{ $contacts['site_fax'] }}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_address" class="required"> @lang('app.site.address')</label>
                            <input type="text" class="form-control" name="site_address" value="{{ $contacts['site_address'] }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_suiteunit"> @lang('app.site.suiteunit')</label>
                            <input type="text" class="form-control" name="site_suiteunit" value="{{ $contacts['site_suiteunit'] }}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="site_country" class="required"> @lang('app.site.country')</label>
                            <select name="site_country" class="form-control" id="country">
                                <option value>@lang('app.site.country')</option>
                                <option @if($contacts['site_country'] == 1) selected @endif value="1">UNITED STATES</option>
                                <option @if($contacts['site_country'] == 2) selected @endif value="2">CANADA</option>
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
                            <input type="text" class="form-control" name="site_city" value="{{ $contacts['site_city'] }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_zip" class="required"> @lang('app.site.zip')</label>
                            <input type="text" class="form-control" name="site_zip" value="{{ $contacts['site_zip'] }}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_timezone"> @lang('app.site.timezone')</label>
                            <input type="text" class="form-control" name="site_timezone" value="{{ $contacts['site_timezone'] }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_notification"> @lang('app.site.notification') 
                                <input type="checkbox" class="form-control" name="site_notification" value="true"  @if($contacts['site_notification']
                                        == "true") checked @endif/></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_latitude"> @lang('app.site.latitude')</label>
                            <input type="text" class="form-control" name="site_latitude" value="{{ $contacts['site_latitude'] }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="site_longitude"> @lang('app.site.longitude')</label>
                            <input type="text" class="form-control" name="site_longitude" value="{{ $contacts['site_longitude'] }}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_pname" class="required"> @lang('app.site.pname')</label>
                            <input type="text" class="form-control" name="site_pname" value="{{ $contacts['site_pname'] }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_pphone" class="required"> @lang('app.site.pphone')</label>
                            <input type="text" class="form-control" name="site_pphone" value="{{ $contacts['site_pphone'] }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_pemail" class="required"> @lang('app.site.pemail')</label>
                            <input type="email" class="form-control" name="site_pemail" value="{{ $contacts['site_pemail'] }}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_sname"> @lang('app.site.sname')</label>
                            <input type="text" class="form-control" name="site_sname" value="{{ $contacts['site_sname'] }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_sphone"> @lang('app.site.sphone')</label>
                            <input type="text" class="form-control" name="site_sphone" value="{{ $contacts['site_sphone'] }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="site_semail"> @lang('app.site.semail')</label>
                            <input type="email" class="form-control" name="site_semail" value="{{ $contacts['site_semail'] }}" />
                        </div>
                    </div>
                </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">@lang('app.description') </label>

                                    <textarea name="description" class="form-control">{{ $taskLabel->description }} </textarea>
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
            url: '{{route('client.task-label.update', $taskLabel->id)}}',
            container: '#createContract',
            type: "POST",
            redirect: true,
            data: $('#createContract').serialize()
        })
    });
    $('#country').select2({
        }).on("change", function (e) {
        console.log(e.val);
        if(e.val == 1){
            $('#state').html(
                '<option value="1">Alabama</option><option value="2">Alaska</option><option value="60">American Samoa</option><option value="4">Arizona</option><option value="5">Arkansas</option><option value="6">California</option><option value="8">Colorado</option><option value="9">Connecticut</option><option value="10">Delaware</option><option value="11">District of Columbia</option><option value="12">Florida</option><option value="13">Georgia</option><option value="66">Guam</option><option value="15">Hawaii</option><option value="16">Idaho</option><option value="17">Illinois</option><option value="18">Indiana</option><option value="19">Iowa</option><option value="20">Kansas</option><option value="21">Kentucky</option><option value="22">Louisiana</option><option value="23">Maine</option><option value="24">Maryland</option><option value="25">Massachusetts</option><option value="26">Michigan</option><option value="27">Minnesota</option><option value="28">Mississippi</option><option value="29">Missouri</option><option value="30">Montana</option><option value="31">Nebraska</option><option value="32">Nevada</option><option value="33">New Hampshire</option><option value="34">New Jersey</option><option value="35">New Mexico</option><option value="36">New York</option><option value="37">North Carolina</option><option value="38">North Dakota</option><option value="69">Northern Mariana Islands</option><option value="39">Ohio</option><option value="40">Oklahoma</option><option value="41">Oregon</option><option value="42">Pennsylvania</option><option value="72">Puerto Rico</option><option value="44">Rhode Island</option><option value="45">South Carolina</option><option value="46">South Dakota</option><option value="47">Tennessee</option><option value="48">Texas</option><option value="78">U.S. Virgin Islands</option><option value="49">Utah</option><option value="50">Vermont</option><option value="51">Virginia</option><option value="53">Washington</option><option value="54">West Virginia</option><option value="55">Wisconsin</option><option value="56">Wyoming</option>'
            )
        }else if(e.val == 2){
            $('#state').html(
                '<option value="87">Alberta</option><option value="84">British Columbia</option><option value="83">Manitoba</option><option value="82">New Brunswick</option><option value="88">Newfoundland and Labrado</option><option value="89">Northwest Territories</option><option value="81">Nova Scotia</option><option value="91">Nunavut</option><option value="79">Ontario</option><option value="85">Prince Edward Island</option><option value="80">Quebec</option><option value="86">Saskatchewan</option><option value="90">Yukon</option>'
            ) 
        }else if(e.val == null || e.val == '') {
            $('#state').html(
                '<option value="0"> -- Select -- </option>'
            )    
        }
    });
    jQuery(document).ready(function($) {
        $.each($('#country option:selected'), function(){            
            console.log($(this).val())
        });
	});

</script>
@endpush

