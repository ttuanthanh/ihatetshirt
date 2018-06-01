<h5>Billing Information</h5>
<hr>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>First Name <span class="required">*</span></label>
            <input type="text" class="form-control" name="billing_firstname" placeholder="" value="{{ Input::old('billing_firstname', @$info->billing_firstname) }}"> 
            <!-- START error message -->
            {!! $errors->first('billing_firstname','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->                    
        </div>
        <div class="col-md-6">
            <label>Last Name <span class="required">*</span></label>
            <input type="text" class="form-control" name="billing_lastname" placeholder="" value="{{ Input::old('billing_lastname', @$info->billing_lastname) }}"> 
            <!-- START error message -->
            {!! $errors->first('billing_lastname','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->                    
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <label>Company</label>
            <input type="text" class="form-control" name="billing_company" placeholder="" value="{{ Input::old('billing_company', @$info->billing_company) }}">                 
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>Email Address <span class="required">*</span></label>
            <input type="email" class="form-control" name="billing_email" placeholder="" value="{{ Input::old('billing_email', @$info->billing_email) }}"> 
            <!-- START error message -->
            {!! $errors->first('billing_email','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->                    
        </div>
        <div class="col-md-6">
            <label>Telephone <span class="required">*</span></label>
            <input type="text" class="form-control" name="billing_telephone" placeholder="" value="{{ Input::old('billing_telephone', @$info->billing_telephone) }}"> 
            <!-- START error message -->
            {!! $errors->first('billing_telephone','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->                    
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>Street Address 1 <span class="required">*</span></label>
            <input type="text" class="form-control" name="billing_street_address_1" placeholder="" value="{{ Input::old('billing_street_address_1', @$info->billing_street_address_1) }}"> 
            <!-- START error message -->
            {!! $errors->first('billing_street_address_1','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->                    
        </div>
        <div class="col-md-6">
            <label>Street Address 2</label>
            <input type="text" class="form-control" name="billing_street_address_2" placeholder="" value="{{ Input::old('billing_street_address_2', @$info->billing_street_address_2) }}"> 
            <!-- START error message -->
            {!! $errors->first('billing_street_address_2','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->                 
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>City <span class="required">*</span></label>
            <input type="text" class="form-control" name="billing_city" placeholder="" value="{{ Input::old('billing_city', @$info->billing_city) }}"> 
            <!-- START error message -->
            {!! $errors->first('billing_city','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->                    
        </div>
        <div class="col-md-6">
            <label>State <span class="required">*</span></label>
            <input type="text" class="form-control" name="billing_state" placeholder="" value="{{ Input::old('billing_state', @$info->billing_state) }}"> 
            <!-- START error message -->
            {!! $errors->first('billing_state','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->                 
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>Zip Code / Postal Code <span class="required">*</span></label>
            <input type="text" class="form-control" name="billing_zip_code" placeholder="" value="{{ Input::old('billing_zip_code', @$info->billing_zip_code) }}"> 
            <!-- START error message -->
            {!! $errors->first('billing_zip_code','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->                    
        </div>
        <div class="col-md-6">
            <label>Country <span class="required">*</span></label>
            {{ Form::select('billing_country', ['' => 'Select Country'] + countries(), Input::old('billing_country', @$info->billing_country), ['class' => 'form-control']) }}
            <!-- START error message -->
            {!! $errors->first('billing_country','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->                 
        </div>
    </div>
</div>