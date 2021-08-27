
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
                <li><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
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
          .bg-title .breadcrumb {
                display: block !important;
            }
       </style>
@endpush

@section('content')

<div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.client.editCompany')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
{!! Form::open(['id'=>'createClientCategory','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="required">@lang('modules.client.categoryName')</label>
                        <input type="text" name="category_name" id="category_name" class="form-control" value="">
                    </div>
                </div>
            </div>
            <h3 class="box-title">@lang('modules.client.editCompanyAddress')</h3>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_address" class="required">@lang('modules.client.categoryAddress')</label>
                        <input type="text" name="category_address" id="category_address" class="form-control" value="">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_suite">@lang('modules.client.categorySuite')</label>
                        <input type="text" name="category_suite" id="category_suite" class="form-control" value="">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="country" class="required">@lang('modules.stripeCustomerAddress.country')</label>
                        <select name="category_country" class="form-control" id="country">
                            <option value>@lang('app.site.country')</option>
                            @foreach ($countries as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label>@lang('modules.stripeCustomerAddress.state')</label>
                        <select name="category_state" class="select2 form-control" id="state">
                            <option value="0"> -- Select -- </option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_city">@lang('modules.client.categoryCity')</label>
                        <input type="text" name="category_city" id="category_city" class="form-control" value="">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_zip">@lang('modules.client.categoryZip')</label>
                        <input type="text" name="category_zip" id="category_zip" class="form-control" value="">
                    </div>
                </div>
            </div>

            <h3 class="box-title">@lang('modules.client.editCompanyContacts')</h3>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_email" class="required">@lang('modules.client.categoryemail')</label>
                        <input type="text" name="category_email" id="category_email" class="form-control" value="">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_phone" class="required">@lang('modules.client.categoryphone')</label>
                        <input type="text" name="category_phone" id="category_phone" class="form-control" value="">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_altphone">@lang('modules.client.categoryaltphone')</label>
                        <input type="text" name="category_altphone" id="category_altphone" class="form-control" value="">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_fax">@lang('modules.client.categoryfax')</label>
                        <input type="text" name="category_fax" id="category_fax" class="form-control" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-category" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
        </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script>

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
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

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.company.stores')}}',
            type: "POST",
            data: $('#createClientCategory').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    if(response.status == 'success'){
                        console.log(response.data);
                    }
                }
            }
        })
    });
</script>
@endpush
