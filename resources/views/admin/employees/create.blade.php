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
                <li><a href="{{ route('admin.employees.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/tagify-master/dist/tagify.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            {!!   $smtpSetting->set_smtp_message !!}

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.employees.createTitle')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createEmployee','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.employees.employeeId')</label>
                                        <a class="mytooltip" href="javascript:void(0)">
                                            <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span
                                                            class="tooltip-inner2">@lang('modules.employees.employeeIdInfo')</span></span></span></a>
                                        <input type="text" name="employee_id" id="employee_id" class="form-control" value="{{ $lastEmployeeID+1 }}"
                                               autocomplete="nope">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>@lang('modules.employees.gender')</label>
                                        <select name="gender" id="gender" class="form-control">
                                            <option value="male">@lang('app.male')</option>
                                            <option value="female">@lang('app.female')</option>
                                            <option value="others">@lang('app.others')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.employees.employeeName')</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                               autocomplete="nope">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.employees.employeePassword')</label>
                                        <input type="password" style="display: none">
                                        <input type="password" name="password" id="password" readonly="readonly" onfocus="this.removeAttribute('readonly');" class="form-control auto-complete-off"
                                               autocomplete="nope">
                                        <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span class="help-block"> @lang('modules.employees.passwordNote') </span>
                                        <div class="checkbox checkbox-info">
                                            <input id="random_password" name="random_password" value="true" type="checkbox">
                                            <label for="random_password">@lang('modules.client.generateRandomPassword')</label>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>

                            <!--/row-->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.employees.employeeEmail')</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                               autocomplete="nope">
                                        <span class="help-block">@lang('modules.employees.emailNote')</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>@lang('app.mobile')</label>
                                    <div class="form-group">
                                        <input type="tel" name="mobile" id="mobile" class="form-control" autocomplete="nope">
                                    </div>
                                   
                                </div>

                                
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('app.department') <button  id="department-setting" type="button" class="btn btn-xs btn-outline btn-info"><i class="ti-settings"></i> @lang('messages.manageDepartment')</button></label>
                                        <select name="department" id="department" class="form-control">
                                            <option value="">--</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.country')</label>
                                        <select name="country" class="form-control" id="country">
                                            <option value>@lang('app.site.country')</option>
                                            <option value="1">UNITED STATES</option>
                                            <option value="2">CANADA</option>
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
                                            <input type="text" name="city" id="city"  value="{{ $leadDetail->city ?? '' }}" class="form-control">
                                        </div>
                                    </div>
                                </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.address')</label>
                                        <textarea name="address" id="address" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>

                            </div>
                            <!--/span-->
                            <!--/row-->
                            <div class="row">

                                <!--/span-->

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('app.login')</label>
                                        <select name="login" id="login" class="form-control">
                                            <option value="enable">@lang('app.enable')</option>
                                            <option value="disable">@lang('app.disable')</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
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
                                <div class="col-md-6">
                                    <label>@lang('modules.profile.profilePicture')</label>
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="http://via.placeholder.com/200x150.png?text=@lang('modules.profile.uploadPicture')"
                                                     alt=""/>
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="max-width: 200px; max-height: 150px;"></div>
                                            <div>
                                <span class="btn btn-info btn-file">
                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                    <input type="file" id="image" name="image"> </span>
                                                <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                   data-dismiss="fileinput"> @lang('app.remove') </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!--/span-->
                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"><i
                                        class="fa fa-check"></i> @lang('app.save')</button>
                            <button type="reset" class="btn btn-default">@lang('app.reset')</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="departmentModel" role="dialog" aria-labelledby="myModalLabel"
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
    <script src="{{ asset('plugins/tagify-master/dist/tagify.js') }}"></script>
    <script data-name="basic">
        (function () {
            $("#department").select2({
                formatNoMatches: function () {
                    return "{{ __('messages.noRecordFound') }}";
                }
            });
            $("#designation").select2({
                formatNoMatches: function () {
                    return "{{ __('messages.noRecordFound') }}";
                }
            });
            $(".select2").select2({
                formatNoMatches: function () {
                    return "{{ __('messages.noRecordFound') }}";
                }
            });
        })()
    </script>

    <script>

        $("#joining_date, #end_date").datepicker({
            format: '{{ $global->date_picker_format }}',
            todayHighlight: true,
            autoclose: true
        });

        $("#joining_date").datepicker("setDate", new Date());
        $(".date-picker").datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight: true,
            autoclose: true
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.employees.store')}}',
                container: '#createEmployee',
                type: "POST",
                redirect: true,
                file: (document.getElementById("image").files.length == 0) ? false : true,
                data: $('#createEmployee').serialize()
            })
        });

        $('#random_password').change(function () {
            var randPassword = $(this).is(":checked");

            if (randPassword) {
                $('#password').val('{{ str_random(8) }}');
                $('#password').attr('readonly', 'readonly');
            } else {
                $('#password').val('');
                $('#password').removeAttr('readonly');
            }
        });

        $('#department-setting').on('click', function (event) {
            var url = '{{ route('admin.department.quick-create')}}';
            $('#modelHeading').html("@lang('messages.manageDepartment')");
            $.ajaxModal('#departmentModel', url);
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
    </script>
@endpush

