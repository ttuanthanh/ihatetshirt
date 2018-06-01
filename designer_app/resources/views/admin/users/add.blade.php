@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }} <small>Add</small> 
    <a href="{{ URL::route($view.'.index') }}" class="btn pull-right"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>
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
                                <input type="text" name="firstname" class="form-control" value="{{ Input::old('firstname') }}">
                                {!! $errors->first('firstname','<span class="help-block text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Last Name</label>
                            <div class="col-md-8">
                                <input type="text" name="lastname" class="form-control" value="{{ Input::old('lastname') }}">
                                {!! $errors->first('lastname','<span class="help-block text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Email Address</label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-envelope"></i>
                                    <input type="text" name="email" class="form-control" value="{{ Input::old('email') }}"> 
                                    {!! $errors->first('email','<span class="help-block text-danger">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Username</label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-user"></i>
                                    <input type="text" name="username" class="form-control" value="{{ Input::old('username') }}"> 
                                    {!! $errors->first('username','<span class="help-block text-danger">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-lock"></i>
                                    <input type="password" name="password" class="form-control" value="{{ Input::old('password') }}"> 
                                    {!! $errors->first('password','<span class="help-block text-danger">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Group</label>
                            <div class="col-md-8">
                                {{ Form::select('group', $groups, Input::old('group'), ['class' => 'form-control select2'] )  }}
                                {!! $errors->first('group','<span class="help-block text-danger">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="portlet light bordered">
                <h5 class="sbold">Status</h5>
                <hr>
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        <input name="status" type="radio" value="actived" {{ checked(Input::old('status', 'actived'), 'actived') }}> Actived
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        <input name="status" type="radio" value="inactived" {{ checked(Input::old('status'), 'inactived') }}> Inactived
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="portlet light bordered">
                <h5 class="sbold">Profile Picture</h5>
                <hr>
                <div class="fileinput fileinput-new fullwidth" data-provides="fileinput">
                    <div class="fileinput-new thumbnail fullwidth img-frame" >
                        <img src="{{ has_photo('') }}" alt="" /> 
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
        <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Add User</button> 
    </div>
</form>

@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
