@extends('layouts.admin')

@section('content')

@include('notification')

<div class="row">

    <div class="col-md-6">

        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption uppercase">
                    My Profile
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <input type="hidden" name="op" value="1">   

                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Profile Picture</label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="min-width: 150px; height: 150px;"> 
                                        <img src="{{ has_photo(@$info->profile_picture) }}">
                                    </div>
                                    <div>
                                        <span class="btn blue btn-outline btn-file btn-xs">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change image </span>
                                        <input type="file" name="file" accept="image/*"> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">First Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rtip" name="firstname" placeholder="First Name" value="{{ @$info->firstname }}">
                                <!-- START error message -->
                                {!! $errors->first('firstname','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Last Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rtip" name="lastname" placeholder="Last Name" value="{{ @$info->lastname }}">
                                <!-- START error message -->
                                {!! $errors->first('lastname','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Username <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rtip" name="username" placeholder="Last Name" value="{{ @$info->username }}">
                                <!-- START error message -->
                                {!! $errors->first('username','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-envelope"></i>
                                    <input type="email" class="form-control rtip" name="email" placeholder="Email"  value="{{ @$info->email }}"> 
                                </div>
                                <!-- START error message -->
                                {!! $errors->first('email','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>

                     
                    </div>

                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update Profile</button>
                            </div>
                        </div>

                </form>
                <!-- END FORM-->
            </div>
        </div>

        
    </div>

    <div class="col-md-6 col-sm-4">
            <div class="portlet light bordered">
                <div class="row">
                    <div class="col-md-12">
                        <i class="fa fa-calendar"></i> Created on <span class="sbold h5"> <span class="sbold h5">{{ date_formatted($info->created_at) }}</span> @ {{ time_formatted($info->created_at) }}          
                    </div>
                    <div class="col-md-12 margin-top-10">
                        <i class="fa fa-calendar"></i> Updated on <span class="sbold h5"> <span class="sbold h5">{{ date_formatted($info->updated_at) }}</span> @ {{ time_formatted($info->updated_at) }}       
                    </div>
                </div>
            </div>
            
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption uppercase">
                    <span class="caption-subject">Change Password</span>
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-horizontal" role="form" method="post">
                    <div class="form-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="op" value="2">   
        
                        <div class="form-group">
                            <label class="col-md-4 control-label">New Password <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="password" class="form-control rtip" name="new_password" placeholder="New Password" value="{{ Input::old('new_password') }}">
                                <!-- START error message -->
                                {!! $errors->first('new_password','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="password" class="form-control rtip" name="new_password_confirmation" placeholder="Confirm New Password" value="{{ Input::old('new_password_confirmation') }}">
                                <!-- START error message -->
                                {!! $errors->first('new_password_confirmation','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>


                    </div>

                        <div class="row">
                            <div class="col-md-offset-4 col-md-8">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update Password</button>
                            </div>
                        </div>

                </form>
            </div>
        </div>
    </div>

</div>


@endsection


@section('top_style')
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop

@section('bottom_script')

@stop
