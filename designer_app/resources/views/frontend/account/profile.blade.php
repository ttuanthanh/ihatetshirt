@extends('templates.fullwidth')

@section('content')
<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    My Profile
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="content bg-white">
    <div class="container">


<form action="" method="post" enctype="multipart/form-data">

    {!! csrf_field() !!}

    <div class="row">
        <div class="col-md-3">
            @include('frontend.account.sidebar')
        </div>
        <div class="col-md-9">

            @include('notification')

            <div class="form-bg-1">



        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label>Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control upload" data-target=".profile-pic" accept="image/*">
                    <!-- START error message -->
                    {!! $errors->first('profile_picture','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                    
                </div>     
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label>First Name</label>
                    <input type="text" class="form-control" name="firstname" placeholder="First Name" value="{{ Input::old('firstname', $info->firstname) }}"> 
                    <!-- START error message -->
                    {!! $errors->first('firstname','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                    
                </div>                    
                <div class="col-md-6">
                    <label>Last Name</label>
                    <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="{{ Input::old('lastname', $info->lastname) }}"> 
                    <!-- START error message -->
                    {!! $errors->first('lastname','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                    
                </div>   
            </div> 
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label>Email Address</label>
                    <input type="email" class="form-control" name="email" placeholder="Email Address" value="{{ Input::old('email', $info->email) }}"> 
                    <!-- START error message -->
                    {!! $errors->first('email','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                    
                </div>                    
                <div class="col-md-6">
                    <label>Telephone</label>
                    <input type="text" class="form-control" name="telephone" placeholder="Telephone" value="{{ Input::old('telephone', $info->telephone) }}"> 
                    <!-- START error message -->
                    {!! $errors->first('telephone','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                    
                </div>  
            </div> 
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label>Street Address 1</label>
                    <input type="text" class="form-control" name="street_address_1" placeholder="Street Address 1" value="{{ Input::old('street_address_1', $info->street_address_1) }}"> 
                    <!-- START error message -->
                    {!! $errors->first('street_address_1','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                    
                </div>                    
                <div class="col-md-6">
                    <label>Street Address 2</label>
                    <input type="text" class="form-control" name="street_address_2" placeholder="Street Address 2" value="{{ Input::old('street_address_2', $info->street_address_2) }}"> 
                    <!-- START error message -->
                    {!! $errors->first('street_address_2','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                 
                </div>   
            </div> 
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label>City</label>
                    <input type="text" class="form-control" name="city" placeholder="City" value="{{ Input::old('city', $info->city) }}"> 
                    <!-- START error message -->
                    {!! $errors->first('city','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                    
                </div>                    
                <div class="col-md-6">
                    <label>State</label>
                    <input type="text" class="form-control" name="state" placeholder="State" value="{{ Input::old('state', $info->state) }}"> 
                    <!-- START error message -->
                    {!! $errors->first('state','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                 
                </div>   
            </div> 
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label>Zip Code / Postal Code</label>
                    <input type="text" class="form-control" name="zip_code" placeholder="Zip Code / Postal Code" value="{{ Input::old('zip_code', $info->zip_code) }}"> 
                    <!-- START error message -->
                    {!! $errors->first('zip_code','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                    
                </div>                    
                <div class="col-md-6">
                    <label>Country</label>
                    {{ Form::select('country', ['' => 'Select Country'] + countries(), Input::old('country', $info->country), ['class' => 'form-control']) }}
                    <!-- START error message -->
                    {!! $errors->first('country','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                 
                </div>   
            </div> 
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save Changes </button>
        
   

            </div>

        </div>
    </div>

</form>
        
    </div>
</div>

@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
<script>
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var target = $(input).data('target');
        reader.onload = function (e) {
            $(target).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(".upload").change(function () {
    readURL(this);
});    
</script>
@stop

