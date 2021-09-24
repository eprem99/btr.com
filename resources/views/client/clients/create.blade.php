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
                <li><a href="{{ route('client.clients.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <div> </div>
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
       </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.client.createTitle')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createClient','class'=>'ajax-form','method'=>'POST','autocomplete'=>'off']) !!}
                            <div class="form-body">
                            <h3 class="box-title">@lang('modules.client.clientBasicDetails')</h3>
                                <hr>
                                <div class="row">
                                <div class="col-md-1 ">
                                <label class="required">@lang('app.gender')</label>
                                        <select name="salutation" id="salutation" class="form-control">
                                            <option value="">--</option>
                                             <option value="mr">@lang('app.mr')</option>
                                            <option value="mrs">@lang('app.mrs')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.client.clientName')</label>
                                            <input type="text" name="name" id="name"  value=""   class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.client.clientEmail')</label>
                                            <input style="opacity: 0;position: absolute;">
                                            <input type="email" name="email" readonly="readonly" onfocus="this.removeAttribute('readonly');" id="email" value=""  class="form-control auto-complete-off">
                                            <span class="help-block">@lang('modules.client.emailNote')</span>
                                        </div>
                                    </div>
                                    <!--/span-->


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.client.password')</label>
                                            <input type="password" style="opacity: 0;position: absolute;">
                                            <input type="password" name="password" id="password" autocomplete="off" class="form-control">
                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            <span class="help-block"> @lang('modules.client.passwordNote')</span>
                                        </div>
                                        <div class="form-group">
                                            <div class="checkbox checkbox-info">
                                                <input id="random_password" name="random_password" value="true"
                                                        type="checkbox">
                                                <label for="random_password" class="text-info">@lang('modules.client.generateRandomPassword')</label>
                                            </div>
                                        </div>
                                    </div>


                                <div class="row">
                                <div class="col-md-4">
                                        <label>@lang('app.mobile')</label>
                                        <div class="form-group">
                                            <input type="tel" name="mobile" id="mobile"  value="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('modules.client.officePhoneNumber')</label>
                                            <input type="text" name="office" id="office"  value="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.postalCode')</label>
                                            <input type="text" name="postal_code" id="postalCode"  value=""   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.country')</label>
                                        <select name="country" class="form-control" id="country">
                                            <option value>@lang('app.site.country')</option>
                                            @foreach ($countries as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>   
                                <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.state')</label>
                                            <select name="state" class="select2 form-control" id="state">
                                                <option value="0"> -- Select -- </option>
                                            </select>
                                        </div>
                                    </div>
                                <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.city')</label>
                                            <input type="text" name="city" id="city"  value=""   class="form-control">
                                        </div>
                                    </div>
                               </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">@lang('app.address')</label>
                                            <textarea name="address"  id="address"  rows="3" value="" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="box-title">@lang('modules.client.clientOtherDetails')</h3>
                                <hr>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('app.login')</label>
                                            <select name="login" id="login" class="form-control">
                                                <option value="enable">@lang('app.enable')</option>
                                                <option value="disable">@lang('app.disable')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="m-b-10">
                                                <label class="control-label">@lang('modules.client.sendCredentials')</label>
                                                <a class="mytooltip" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">@lang('modules.client.sendCredentialsMessage')</span></span></span></a>
                                            </div>
                                            <div class="radio radio-inline">
                                                <input type="radio" checked name="sendMail" id="sendMail1"
                                                       value="yes">
                                                <label for="sendMail1" class="">
                                                    @lang('app.yes') </label>

                                            </div>
                                            <div class="radio radio-inline ">
                                                <input type="radio" name="sendMail"
                                                       id="sendMail2" value="no">
                                                <label for="sendMail2" class="">
                                                    @lang('app.no') </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="m-b-10">
                                                <label class="control-label">@lang('modules.emailSettings.emailNotifications')</label>
                                            </div>
                                            <div class="radio radio-inline">
                                                <input type="radio" checked name="email_notifications" id="email_notifications1" value="1">
                                                <label for="email_notifications1" class="">
                                                    @lang('app.enable') </label>

                                            </div>
                                            <div class="radio radio-inline ">
                                                <input type="radio" name="email_notifications"
                                                       id="email_notifications2" value="0">
                                                <label for="email_notifications2" class="">
                                                    @lang('app.disable') </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/row-->

                                 <div class="row">
                                    <div class="col-md-12">
                                        <label>@lang('app.note')</label>
                                        <div class="form-group">
                                            <textarea name="note" id="note" class="form-control summernote" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="category_id" value="{{$clientDetail->category_id}}">
                            </div>
                            <div class="form-actions">
                                <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
                                <button type="reset" class="btn btn-default">@lang('app.reset')</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="clientCategoryModal" role="dialog" aria-labelledby="myModalLabel"
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
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>


<script>

    $(".date-picker").datepicker({
        todayHighlight: true,
        autoclose: true
    });
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('client.clients.store')}}',
            container: '#createClient',
            type: "POST",
            redirect: true,
            data: $('#createClient').serialize()
        })
    });

    $('#random_password').change(function () {
        var randPassword = $(this).is(":checked");

        if(randPassword){
            $('#password').val('{{ str_random(8) }}');
            $('#password').attr('readonly', 'readonly');
        }
        else{
            $('#password').val('');
            $('#password').removeAttr('readonly');
        }
    });
    
    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });
    $('#addClientCategory').click(function () {
        var url = '{{ route('client.clientCategory.create')}}';
        $('#modelHeading').html('...');
        $.ajaxModal('#clientCategoryModal', url);
    })


    $('#country').select2({
        }).on("change", function (e) {
        var id = $(this).val();
        var url = "{{ route('client.clients.state',':id') }}";
        url = url.replace(':id', id);
       // console.log(url);
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            data: $('#updateProfile').serialize(),
            success: function (data) {
            //  alert(data.data)
                $('#state').html(data.data);
            }
        })
    });

</script>
@endpush

