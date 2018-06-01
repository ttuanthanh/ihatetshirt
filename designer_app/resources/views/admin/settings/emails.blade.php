@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $label }}</h1>
<!-- END PAGE TITLE-->

@include('admin.settings.tab')

<form class="form-horizontal" method="post">
{{ csrf_field() }}
<div class="portlet light bordered">
    <div class="portlet-body">

        @include('notification')

        <div class="tab-content">

            <!-- BEGIN TAB CONTENT -->
            <div class="tabbable-custom nav-justified margin-top-20">
                <ul class="nav nav-tabs uppercase">
                    <li class="{{ actived(Input::get('tab', 'register'), 'register') }}">
                        <a href="?tab=register">Register </a>
                    </li>
                    <li class="{{ actived(Input::get('tab'), 'change-password') }}">
                        <a href="?tab=change-password">Change Password </a>
                    </li>

                    <li class="{{ actived(Input::get('tab'), 'forgot-password') }}">
                        <a href="?tab=forgot-password">Forgot Password</a>
                    </li>
                    <li class="{{ actived(Input::get('tab'), 'saved-design') }}">
                        <a href="?tab=saved-design">Saved Design </a>
                    </li>  
                    <li class="{{ actived(Input::get('tab'), 'order-details') }}">
                        <a href="?tab=order-details">Order Details </a>
                    </li>       
                    <li class="{{ actived(Input::get('tab'), 'order-status') }}">
                        <a href="?tab=order-status">Order Status </a>
                    </li>  
                </ul>
            </div>

            <div class="portlet light bordered">

                <div class="row">
                    <div class="col-md-7">

                        <input type="text" class="form-control input-lg" name="subject" placeholder="Email Subject" value="{{ Input::old('subject', @$info->post_title) }}">
                        {!! $errors->first('subject','<span class="help-block text-danger">:message</span>') !!}

                        <div class="margin-top-10">
                            <textarea class="tinymce form-control" name="content" rows="5">{{ Input::old('content', @$info->post_content) }}</textarea>               
                        </div>


                    </div>
                    <div class="col-md-5">

                        <h5 class="sbold">Enable / Disable</h5>
                        <div class="mt-checkbox-inline">
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="hidden" name="status" value="inactived">
                                <input type="checkbox" name="status" value="actived" {{ checked(Input::old('status', @$info->post_status), 'actived') }}> Enable this email notification
                                <span></span>
                            </label>
                        </div>


                        @include('admin.settings.emails.'.Input::get('tab', 'register'))

                                            
                    </div>                
                </div>

            </div>          
            <!-- END TAB CONTENT -->

        </div>

    </div>
</div>

<div class="form-actions">
    <button class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button> 
</div>

</form>
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
