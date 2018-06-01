@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }}
    <a href="{{ URL::route($view.'.index') }}" class="btn pull-right"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>
</h1>
<!-- END PAGE TITLE-->

@include('notification')

<form method="post" enctype="multipart/form-data" class="form-horizontal">
    
    {{ csrf_field() }}

    <div class="form-body">
        <div class="row">
            <div class="col-md-8">
                <div class="portlet light bordered">
                    <h4>Customer Information</h4>
                    <div class="form-group margin-top-30">
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
                                <input type="email" name="email" class="form-control" value="{{ Input::old('email', $info->email) }}"> 
                                {!! $errors->first('email','<span class="help-block text-danger">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Telepnone Number</label>
                        <div class="col-md-8">
                            <div class="input-icon">
                                <i class="fa fa-phone"></i>
                                <input type="text" name="telephone" class="form-control" value="{{ Input::old('telephone', $info->telephone) }}"> 
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Password</label>
                        <div class="col-md-8">
                            <div class="input-icon">
                                <i class="fa fa-lock"></i>
                                <input type="password" class="form-control" name="password" placeholder="Password"  value="{{ Input::old('password') }}"> 
                            </div>
                            <span class="help-block small">Leave blank if you don't want to change it</span>
                            
                            <!-- START error message -->
                            {!! $errors->first('password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>

                </div>
                <div class="portlet light bordered">


                    <h4>Billing Address</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <label>First Name</label>
                            <input type="text" name="billing_firstname" class="form-control" value="{{ Input::old('billing_firstname', $info->billing_firstname) }}">    
                            {!! $errors->first('billing_firstname','<span class="help-block text-danger">:message</span>') !!}                                
                        </div>
                        <div class="col-md-6">
                            <label>Last Name</label>
                            <input type="text" name="billing_lastname" class="form-control" value="{{ Input::old('billing_lastname', $info->billing_lastname) }}">   
                            {!! $errors->first('billing_lastname','<span class="help-block text-danger">:message</span>') !!}                                 
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <label>Email Address</label>
                            <input type="email" name="billing_email" class="form-control" value="{{ Input::old('billing_email', $info->billing_email) }}">     
                            {!! $errors->first('billing_email','<span class="help-block text-danger">:message</span>') !!}                               
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-6">
                            <label>Phone</label>
                            <input type="text" name="billing_telephone" class="form-control" value="{{ Input::old('billing_telephone', $info->billing_telephone) }}">    
                            {!! $errors->first('billing_telephone','<span class="help-block text-danger">:message</span>') !!}                                
                        </div>
                        <div class="col-md-6">
                            <label>Company</label>
                            <input type="text" name="billing_company" class="form-control" value="{{ Input::old('billing_company', $info->billing_company) }}"> 
                            {!! $errors->first('billing_company','<span class="help-block text-danger">:message</span>') !!}                                   
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-6">
                            <label>Address line 1</label>
                            <input type="text" name="billing_street_address_1" class="form-control" value="{{ Input::old('billing_street_address_1', $info->billing_street_address_1) }}"> 
                            {!! $errors->first('billing_street_address_1','<span class="help-block text-danger">:message</span>') !!}                                   
                        </div>
                        <div class="col-md-6">
                            <label>Address line 2</label>
                            <input type="text" name="billing_street_address_2" class="form-control" value="{{ Input::old('billing_street_address_2', $info->billing_street_address_2) }}">   
                            {!! $errors->first('billing_street_address_2','<span class="help-block text-danger">:message</span>') !!}                                 
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-6">
                            <label>City</label>
                            <input type="text" name="billing_city" class="form-control" value="{{ Input::old('billing_city', $info->billing_city) }}">   
                            {!! $errors->first('billing_city','<span class="help-block text-danger">:message</span>') !!}                                 
                        </div>
                        <div class="col-md-6">
                            <label>State</label>
                            <input type="text" name="billing_state" class="form-control" value="{{ Input::old('billing_state', $info->billing_state) }}">  
                            {!! $errors->first('billing_state','<span class="help-block text-danger">:message</span>') !!}                                  
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-6">
                            <label>Postcode / ZIP</label>
                            <input type="text" name="billing_zip_code" class="form-control" value="{{ Input::old('billing_zip_code', $info->billing_zip_code) }}"> 
                            {!! $errors->first('billing_zip_code','<span class="help-block text-danger">:message</span>') !!}                                   
                        </div>
                        <div class="col-md-6">
                            <label>Country</label>
                            {{ Form::select('billing_country', ['billing_country' => 'Select Country'] + countries(), Input::old('billing_country', $info->billing_country), ['class' => 'form-control select2']) }}            
                            {!! $errors->first('billing_country','<span class="help-block text-danger">:message</span>') !!}                
                        </div>
                    </div>

                    <hr>

                    <h4>Shipping Address</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <label>First Name</label>
                            <input type="text" name="shipping_firstname" class="form-control" value="{{ Input::old('shipping_firstname', $info->shipping_firstname) }}">    
                            {!! $errors->first('shipping_firstname','<span class="help-block text-danger">:message</span>') !!}                                
                        </div>
                        <div class="col-md-6">
                            <label>Last Name</label>
                            <input type="text" name="shipping_lastname" class="form-control" value="{{ Input::old('shipping_lastname', $info->shipping_lastname) }}">   
                            {!! $errors->first('shipping_lastname','<span class="help-block text-danger">:message</span>') !!}                                 
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-12">
                            <label>Email Address</label>
                            <input type="email" name="shipping_email" class="form-control" value="{{ Input::old('shipping_email', $info->shipping_email) }}">     
                            {!! $errors->first('shipping_email','<span class="help-block text-danger">:message</span>') !!}                               
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-6">
                            <label>Phone</label>
                            <input type="text" name="shipping_telephone" class="form-control" value="{{ Input::old('shipping_telephone', $info->shipping_telephone) }}">    
                            {!! $errors->first('shipping_telephone','<span class="help-block text-danger">:message</span>') !!}                                
                        </div>
                        <div class="col-md-6">
                            <label>Company</label>
                            <input type="text" name="shipping_company" class="form-control" value="{{ Input::old('shipping_company', $info->shipping_company) }}"> 
                            {!! $errors->first('shipping_company','<span class="help-block text-danger">:message</span>') !!}                                   
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-6">
                            <label>Address line 1</label>
                            <input type="text" name="shipping_street_address_1" class="form-control" value="{{ Input::old('shipping_street_address_1', $info->shipping_street_address_1) }}"> 
                            {!! $errors->first('shipping_street_address_1','<span class="help-block text-danger">:message</span>') !!}                                   
                        </div>
                        <div class="col-md-6">
                            <label>Address line 2</label>
                            <input type="text" name="shipping_street_address_2" class="form-control" value="{{ Input::old('shipping_street_address_2', $info->shipping_street_address_2) }}">   
                            {!! $errors->first('shipping_street_address_2','<span class="help-block text-danger">:message</span>') !!}                                 
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-6">
                            <label>City</label>
                            <input type="text" name="shipping_city" class="form-control" value="{{ Input::old('shipping_city', $info->shipping_city) }}">   
                            {!! $errors->first('shipping_city','<span class="help-block text-danger">:message</span>') !!}                                 
                        </div>
                        <div class="col-md-6">
                            <label>State</label>
                            <input type="text" name="shipping_state" class="form-control" value="{{ Input::old('shipping_state', $info->shipping_state) }}">  
                            {!! $errors->first('shipping_state','<span class="help-block text-danger">:message</span>') !!}                                  
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-6">
                            <label>Postcode / ZIP</label>
                            <input type="text" name="shipping_zip_code" class="form-control" value="{{ Input::old('shipping_zip_code', $info->shipping_zip_code) }}"> 
                            {!! $errors->first('shipping_zip_code','<span class="help-block text-danger">:message</span>') !!}                                   
                        </div>
                        <div class="col-md-6">
                            <label>Country</label>
                            {{ Form::select('shipping_country', ['shipping_country' => 'Select Country'] + countries(), Input::old('shipping_country', $info->shipping_country), ['class' => 'form-control select2']) }}            
                            {!! $errors->first('shipping_country','<span class="help-block text-danger">:message</span>') !!}                
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

                        @if( $info->last_login )
                        <div class="col-md-12 margin-top-10">
                            <i class="fa fa-calendar"></i> Last login <span class="sbold h5">{{ date_formatted($info->last_login) }}</span> @ <span class="sbold h5">{{ time_formatted($info->last_login) }}</span>       
                        </div>
                        @endif

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
    </div>
    <div class="form-actions">
        <button class="btn btn-primary"><i class="fa fa-check"></i> Update {{ $single }}</button>
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
