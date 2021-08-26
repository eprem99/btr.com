@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.menu.projects')</li>
            </ol>
        </div>
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">

            <a href="{{ route('admin.clients.edit',$client->id) }}"
               class="btn btn-outline btn-success btn-sm">@lang('modules.lead.edit')
                <i class="fa fa-edit" aria-hidden="true"></i></a>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection


@section('content')

    <div class="row">


        @include('admin.clients.client_header')

        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">
                    
                    @include('admin.clients.tabs')

                    
                    <div class="content-wrap">
                        <section id="section-line-1" class="show">
                            <div class="row">


                                <div class="col-md-12">
                                    <div class="white-box">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('modules.employees.fullName')</strong> <br>
                                                <p class="text-muted">{{ ucwords($client->name) }}</p>
                        
                                            </div>
                                            <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('app.email')</strong> <br>
                                                <p class="text-muted">{{ $client->email }}</p>
                                            </div>
                                            <div class="col-md-4 col-xs-6"> <strong>@lang('app.mobile')</strong> <br>
                                                <p class="text-muted">{{ ucwords($client->mobile) }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6 col-xs-6 b-r"> <strong>@lang('modules.client.officePhoneNumber')</strong> <br>
                                                <p class="text-muted">{{ ucwords($clientDetail->office) }}</p>
                        
                                            </div>
                                            <div class="col-md-6 col-xs-6 b-r"> <strong>@lang('modules.client.companyName')</strong> <br>
                                                <p class="text-muted">{{ (!empty($clientDetail) && !empty($clientDetail->clientCategory))  ? $clientDetail->clientCategory->category_name : '--' }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"> <strong>@lang('modules.stripeCustomerAddress.country')</strong> <br>
                                                <p class="text-muted">{{ ucwords($clientDetail->country) }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"> <strong>@lang('modules.stripeCustomerAddress.state')</strong> <br>
                                                <p class="text-muted">{{ $clientDetail->state }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong>@lang('modules.stripeCustomerAddress.city')</strong> <br>
                                                <p class="text-muted">{{ ucwords($clientDetail->city) }}</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong>@lang('modules.stripeCustomerAddress.postalCode')</strong> <br>
                                                <p class="text-muted">{{ ucwords($clientDetail->postal_code)  ?? ''}}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                          
                                            <div class="col-xs-6 b-r"> <strong>@lang('app.address')</strong> <br>
                                                <p class="text-muted">{!!  (!empty($clientDetail)) ? ucwords($clientDetail->address) : 'NA' !!}</p>
                                            </div>
                                            
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-xs-12"> <strong>@lang('app.note')</strong> <br>
                                                <p class="text-muted">{!!  $clientDetail->note ?? 'NA' !!}</p>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </section>
                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script>
        $('ul.showClientTabs .clientProfile').addClass('tab-current');
    </script>
@endpush