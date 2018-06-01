@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }} <small>Edit</small> 
    <div class="pull-right">        
        <a href="{{ URL::route($view.'.index') }}" class="btn"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>
        <a href="{{ URL::route($view.'.add') }}" class="btn"><i class="fa fa-plus"></i> Add New</a>
    </div>
</h1>
<!-- END PAGE TITLE-->

@include('notification')

<form method="post" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-8">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-4 control-label">First Name</label>
                            <div class="col-md-8">
                                <input type="text" name="firstname" class="form-control" value="{{ Input::old('firstname', $info->firstname) }}">
                                {!! $errors->first('firstname','<span class="help-block text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Last Name</label>
                            <div class="col-md-8">
                                <input type="text" name="lastname" class="form-control" value="{{ Input::old('lastname', $info->lastname) }}">
                                {!! $errors->first('lastname','<span class="help-block text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Email Address</label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-envelope"></i>
                                    <input type="text" name="email" class="form-control" value="{{ Input::old('email', $info->email) }}"> 
                                    {!! $errors->first('email','<span class="help-block text-danger">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Username</label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-user"></i>
                                    <input type="text" name="username" class="form-control" value="{{ Input::old('username', $info->username) }}"> 
                                    {!! $errors->first('username','<span class="help-block text-danger">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Group</label>
                            <div class="col-md-8">
                                {{ Form::select('group', $groups, Input::old('group', $info->group), ['class' => 'form-control select2'] )  }}
                                {!! $errors->first('group','<span class="help-block text-danger">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-lock"></i>
                                    <input type="password" class="form-control" name="password" placeholder="Password" value="{{ Input::old('password') }}"> 
                                </div>
                                <span class="help-block small">Leave blank if you don't want to change it</span>
                                
                                <!-- START error message -->
                                {!! $errors->first('password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="portlet light bordered">
                <div class="row">
                    <div class="col-md-12">
                        <i class="fa fa-calendar"></i> Created on <span class="sbold h5">{{ date_formatted($info->created_at) }}</span> @ <span class="sbold h5">{{ time_formatted($info->created_at) }}</span>          
                    </div>
                    <div class="col-md-12 margin-top-10">
                        <i class="fa fa-calendar"></i> Updated on <span class="sbold h5">{{ date_formatted($info->updated_at) }}</span> @ <span class="sbold h5">{{ time_formatted($info->updated_at) }}</span>       
                    </div>
                </div>
            </div>
            <div class="portlet light bordered">
                <h5 class="sbold">Status</h5>
                <hr>
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        <input name="status" type="radio" value="actived" {{ checked(Input::old('status', $info->status), 'actived') }}> Actived
                        <span></span>
                    </label> 
                    <label class="mt-radio mt-radio-outline">
                        <input name="status" type="radio" value="inactived" {{ checked(Input::old('status', $info->status), 'inactived') }}> Inactived
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="portlet light bordered">
                <h5 class="sbold">Profile Picture</h5>
                <hr>
                <div class="fileinput fileinput-new fullwidth" data-provides="fileinput">
                    <div class="fileinput-new thumbnail fullwidth img-frame" >
                        <img src="{{ has_photo($info->profile_picture) }}" alt="" /> 
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail fullwidth img-frame"> </div>
                    <div>
                        <span class="btn default btn-file">
                        <span class="fileinput-new"> Select Profile Picture </span>
                        <span class="fileinput-exists"> Change </span>
                        <input type="file" name="file"> </span>
                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Update User</button>
        @if( $info->status == 'inactived' )
        <a href="#" class="popup btn"
            data-href="{{ URL::route($view.'.delete', $info->id) }}" 
            data-toggle="modal"
            data-target=".popup-modal" 
            data-title="Confirm Move to Trash"
            data-body="Are you sure you want to move to trash ID: <b>#{{ $info->id }}</b>?"><i class="fa fa-trash-o"></i> Move to trash</a>  
        @endif
    </div>
</form>

@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
