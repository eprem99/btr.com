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
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.profile.updateTitle')</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    {!! Form::open(['id'=>'updateProfile','class'=>'ajax-form', 'method' => 'PUT' ]) !!}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <div class="form-group">
                                                    <label class="required">@lang('modules.profile.yourName')</label>
                                                    <input type="text" name="name" id="name"
                                                           class="form-control" value="{{ $userDetail->name }}">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="required">@lang('modules.profile.yourEmail')</label>
                                                    <input type="email" name="email" id="email"
                                                           class="form-control" value="{{ $userDetail->email }}">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('modules.profile.yourPassword')</label>
                                                    <input type="password" name="password" id="password"
                                                           readonly="readonly" onfocus="this.removeAttribute('readonly');" class="form-control auto-complete-off">
                                                    <span class="help-block"> @lang('modules.profile.passwordNote')</span>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            
                                            <div class="col-md-4">
                                                <label>@lang('app.mobile')</label>
                                                <div class="form-group">
                                                    <input type="tel" name="mobile" id="mobile" class="form-control" value="{{ $userDetail->mobile }}"> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('modules.employees.gender')</label>
                                                    <select name="gender" id="gender" class="form-control">
                                                        <option @if($userDetail->gender == 'male') selected @endif value="male">@lang('app.male')</option>
                                                        <option @if($userDetail->gender == 'female') selected @endif value="female">@lang('app.female')</option>
                                                        <option @if($userDetail->gender == 'others') selected @endif value="others">@lang('app.others')</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="m-b-10">
                                                        <label class="control-label">@lang('modules.emailSettings.emailNotifications')</label>
                                                    </div>
                                                    <div class="radio radio-inline">
                                                        <input type="radio" 
                                                        @if ($userDetail->email_notifications)
                                                            checked
                                                        @endif
                                                        name="email_notifications" id="email_notifications1" value="1">
                                                        <label for="email_notifications1" class="">
                                                            @lang('app.enable') </label>
            
                                                    </div>
                                                    <div class="radio radio-inline ">
                                                        <input type="radio" name="email_notifications"
                                                        @if (!$userDetail->email_notifications)
                                                            checked
                                                        @endif
            
                                                               id="email_notifications2" value="0">
                                                        <label for="email_notifications2" class="">
                                                            @lang('app.disable') </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>@lang('modules.stripeCustomerAddress.country')</label>
                                                <select name="country" class="form-control" id="country">
                                                    <option value>@lang('app.site.country')</option>
                                                    @foreach($countries as $country)
                                                    <option 
                                                @if ($country->id == $userDetail->employee_details->country)
                                                    selected
                                                @endif  value="{{$country->id}}">{{$country->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>   
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>@lang('modules.stripeCustomerAddress.state')</label>
                                                <select name="state" class="form-control" id="state">
                                                    <option value="0"> -- Select -- </option>
                                                    @foreach($states as $state)
                                                    <option 
                                                @if ($state->id == $userDetail->employee_details->state)
                                                    selected
                                                @endif  value="{{$state->id}}">{{$state->names}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>@lang('modules.stripeCustomerAddress.city')</label>
                                                <input type="text" name="city" id="city"  value="{{ $leadDetail->city ?? '' }}"   class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>@lang('modules.stripeCustomerAddress.postalCode')</label>
                                                <input type="text" name="postal_code" id="postalCode"  value="{{ $leadDetail->postal_code ?? '' }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">@lang('modules.profile.yourAddress')</label>
                                        <textarea name="address" id="address" rows="5"
                                                  class="form-control">@if(!empty($userDetail->employee_details)){{ $userDetail->employee_details->address }}@endif</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label>@lang('modules.profile.profilePicture')</label>

                                                <div class="form-group">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail"
                                                             style="width: 200px; height: 150px;">
                                                            @if(is_null($userDetail->image))
                                                                <img src="http://via.placeholder.com/200x150.png?text=@lang('modules.profile.uploadPicture')"
                                                                     alt=""/>
                                                            @else
                                                                <img src="{{ asset_url('avatar/'.$userDetail->image) }}"
                                                                     alt=""/>
                                                            @endif
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                                              style="max-width: 200px; max-height: 150px;"></div>
                                                        <div>
                                <span class="btn btn-info btn-file">
                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                    <input type="file" name="image" id="image"> </span>
                                                            <a href="javascript:;"
                                                               class="btn btn-danger fileinput-exists"
                                                               data-dismiss="fileinput"> @lang('app.remove') </a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!--/span-->


                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" id="save-form-2" class="btn btn-success"><i
                                                    class="fa fa-check"></i>
                                            @lang('app.update')
                                        </button>
                                        <button type="reset" class="btn btn-default">@lang('app.reset')</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>

                </div>
            </div>    <!-- .row -->
        </div>
    </div>

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script>
    var ids = "";
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
 //    $( "#country" ).change(function() {
 //        var id = $(this).val();
 //        var url = "{{ route('admin.company.country',':id') }}";
 //        url = url.replace(':id', id);
 //        $.easyAjax({
 //            url: url,
 //            type: "GET",
 //            data: $('#updateProfile').serialize(),
 //            success: function (data) {
 //            //   alert(data.data)
 //                $('#state').html(data.data);
 //            }
 //        })
 //    });
 //        jQuery(document).ready(function($) {
 //        $.each($('#country option:selected'), function(){            
 //            var id = $(this).val();
 //        var url = "{{ route('admin.company.country',':id') }}";
 //        url = url.replace(':id', id);
 //        $.easyAjax({
 //            url: url,
 //            type: "GET",
 //            redirect: true,
 //            data: $('').serialize(),
 //            success: function (data) {
 //            //  alert(data.data)
 //                $('#state').html(data.data);
                
 //            }
 //        })
 //        });
	// }); 
    $('#save-form-2').click(function () {
        $.easyAjax({
            url: '{{route('member.profile.update', [$userDetail->id])}}',
            container: '#updateProfile',
            type: "POST",
            redirect: true,
            file: (document.getElementById("image").files.length == 0) ? false : true,
            data: $('#updateProfile').serialize(),
            success: function (data) {
                if (data.status == 'success') {
                    window.location.reload();
                }
            }
        })
    });
</script>
@endpush
