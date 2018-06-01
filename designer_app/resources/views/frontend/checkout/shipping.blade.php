<input type="hidden" name="same_as_billing" value="0">

<h5>
    <label style="margin-bottom: 0;">
        Ship to different address? <input type="checkbox" name="same_as_billing" value="1" {{ checked(Input::old('same_as_billing'), 1) }}>
    </label>    
</h5>
<hr>



<div class="shipping-info" style="{{ Input::old('same_as_billing') ? '' : 'display:none;' }}">
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>First Name <span class="required">*</span></label>
                <input type="text" class="form-control" name="shipping_firstname" placeholder="" value="{{ Input::old('shipping_firstname', @$info->shipping_firstname) }}"> 
                <!-- START error message -->
                {!! $errors->first('shipping_firstname','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->                    
            </div>
            <div class="col-md-6">
                <label>Last Name <span class="required">*</span></label>
                <input type="text" class="form-control" name="shipping_lastname" placeholder="" value="{{ Input::old('shipping_lastname', @$info->shipping_lastname) }}"> 
                <!-- START error message -->
                {!! $errors->first('shipping_lastname','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->                    
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
                <label>Company</label>
                <input type="text" class="form-control" name="shipping_company" placeholder="" value="{{ Input::old('shipping_company', @$info->shipping_company) }}">                 
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>Email Address <span class="required">*</span></label>
                <input type="email" class="form-control" name="shipping_email" placeholder="" value="{{ Input::old('shipping_email', @$info->shipping_email) }}"> 
                <!-- START error message -->
                {!! $errors->first('shipping_email','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->                    
            </div>
            <div class="col-md-6">
                <label>Telephone <span class="required">*</span></label>
                <input type="text" class="form-control" name="shipping_telephone" placeholder="" value="{{ Input::old('shipping_telephone', @$info->shipping_telephone) }}"> 
                <!-- START error message -->
                {!! $errors->first('shipping_telephone','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->                    
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>Street Address 1 <span class="required">*</span></label>
                <input type="text" class="form-control" name="shipping_street_address_1" placeholder="" value="{{ Input::old('shipping_street_address_1', @$info->shipping_street_address_1) }}"> 
                <!-- START error message -->
                {!! $errors->first('shipping_street_address_1','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->                    
            </div>
            <div class="col-md-6">
                <label>Street Address 2</label>
                <input type="text" class="form-control" name="shipping_street_address_2" placeholder="" value="{{ Input::old('shipping_street_address_2', @$info->shipping_street_address_2) }}"> 
                <!-- START error message -->
                {!! $errors->first('shipping_street_address_2','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->                 
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>City <span class="required">*</span></label>
                <input type="text" class="form-control" name="shipping_city" placeholder="" value="{{ Input::old('shipping_city', @$info->shipping_city) }}"> 
                <!-- START error message -->
                {!! $errors->first('shipping_city','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->                    
            </div>
            <div class="col-md-6">
                <label>State <span class="required">*</span></label>
                <input type="text" class="form-control" name="shipping_state" placeholder="" value="{{ Input::old('shipping_state', @$info->shipping_state) }}"> 
                <!-- START error message -->
                {!! $errors->first('shipping_state','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->                 
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>Zip Code / Postal Code <span class="required">*</span></label>
                <input type="text" class="form-control" name="shipping_zip_code" placeholder="" value="{{ Input::old('shipping_zip_code', @$info->shipping_zip_code) }}"> 
                <!-- START error message -->
                {!! $errors->first('shipping_zip_code','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->                    
            </div>
            <div class="col-md-6">
                <label>Country <span class="required">*</span></label>
                {{ Form::select('shipping_country', ['' => 'Select Country'] + countries(), Input::old('shipping_country', @$info->shipping_country), ['class' => 'form-control']) }}
                <!-- START error message -->
                {!! $errors->first('shipping_country','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->                 
            </div>
        </div>
    </div>    
</div>


<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <label>Order Notes</label>
            <textarea class="form-control" name="order_notes" placeholder="Notes about your order e.g. Special notes for delivery." rows="5">{{ Input::old('order_notes') }}</textarea>                  
        </div>
    </div>
</div>