<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.tasks.site') {{ $taskLabel->label_name }}</h4>
</div>
@php
$contacts = json_decode($taskLabel->contacts, true);
@endphp
<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-6">
                <h3>
                    @lang('modules.tasks.sitecontacts')
                </h3>
                @if($contacts['site_phone'] != null)<p>Phone: {{$contacts['site_phone']}} </p>@endif
                @if($contacts['site_fax'] != null)<p>Fax: {{$contacts['site_fax']}} </p>@endif

                <h3>
                    @lang('modules.tasks.siteprimarycontacts')
                </h3>
                @if($contacts['site_pname'] != null)<p>Primary Name: {{$contacts['site_pname']}} </p>@endif
                @if($contacts['site_pphone'] != null)<p>Primary Phone: {{$contacts['site_pphone']}} </p>@endif
                @if($contacts['site_pemail'] != null)<p>Primary Email: {{$contacts['site_pemail']}} </p>@endif
                @if($contacts['site_sname'] != null)<p>Secondary Name: {{$contacts['site_sname']}} </p>@endif
                @if($contacts['site_sphone'] != null)<p>Secondary Phone: {{$contacts['site_sphone']}} </p>@endif
                @if($contacts['site_semail'] != null)<p>Secondary Email: {{$contacts['site_semail']}} </p>@endif
            </div>
            <div class="col-md-6">
                <h3>
                    @lang('modules.tasks.siteaddress')
                </h3>
                @if($countries->name != null)<p>Country: {{$countries->name}} </p>@endif
                @if($state->names != null)<p>State: {{$state->names}} </p>@endif
                @if($contacts['site_city'] != null)<p>City: {{$contacts['site_city']}} </p>@endif
                @if($contacts['site_zip'] != null)<p>Zip / Postal Code: {{$contacts['site_zip']}} </p>@endif
                @if($contacts['site_timezone'] != null)<p>Time Zone: {{$contacts['site_timezone']}} </p>@endif
                @if($contacts['site_latitude'] != null)<p>Latitude: {{$contacts['site_latitude']}} </p>@endif
                @if($contacts['site_longitude'] != null)<p>Longitude: {{$contacts['site_longitude']}} </p>@endif
            </div>
            <div class="col-md-12">
            @if($taskLabel->description != null)
            <h3>
                    @lang('modules.tasks.sitedescription')
                </h3>
             {{ $taskLabel->description }} @endif
            </div>
        </div>

        <hr>
    </div>
</div>

