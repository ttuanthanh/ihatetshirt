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
                        <label class="col-md-4 control-label">Coupon Name</label>
                        <div class="col-md-8">
                            <input type="text" name="coupon_name" class="form-control" value="{{ Input::old('coupon_name', $info->post_title) }}">
                            {!! $errors->first('coupon_name','<span class="help-block text-danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Coupon Code</label>
                        <div class="col-md-8">
                            <input type="text" name="coupon_code" class="form-control" value="{{ Input::old('coupon_code', $info->post_name) }}">
                            {!! $errors->first('coupon_code','<span class="help-block text-danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Coupon Value</label>
                        <div class="col-md-8">
                            <input type="text" name="coupon_value" class="form-control numeric" value="{{ Input::old('coupon_value', $info->coupon_value) }}">
                            {!! $errors->first('coupon_value','<span class="help-block text-danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Discount Type</label>
                        <div class="col-md-8">

                            <div class="mt-radio-inline">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="discount_type" value="total" {{ checked(Input::old('discount_type', $info->discount_type), 'total') }}> Total
                                    <span></span>
                                </label>
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="discount_type" value="percent" {{ checked(Input::old('discount_type', $info->discount_type), 'percent') }}> Percent
                                    <span></span>
                                </label>
                            </div>
                            {!! $errors->first('discount_type','<span class="help-block text-danger">:message</span>') !!}

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Coupon Type</label>
                        <div class="col-md-8">
                            {{ Form::select('coupon_type', coupon_types(), Input::old('coupon_type', $info->coupon_type), ['class' => 'form-control']) }}
                            {!! $errors->first('coupon_type','<span class="help-block text-danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Start Date</label>
                            <div class="col-md-4">
                                <div class="input-icon">
                                    <i class="fa fa-calendar"></i>
                                    <input type="text" class="form-control datepicker" name="start_date" value="{{ Input::old('start_date', date_formatted_b($info->start_date)) }}"> 
                                </div>
                                {!! $errors->first('start_date','<span class="help-block text-danger">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">End Date</label>
                            <div class="col-md-4">
                                <div class="input-icon">
                                    <i class="fa fa-calendar"></i>
                                    <input type="text" class="form-control datepicker" name="end_date" value="{{ Input::old('end_date', date_formatted_b($info->end_date)) }}"> 
                                </div>
                                {!! $errors->first('end_date','<span class="help-block text-danger">:message</span>') !!}
                            </div>
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
                    <i class="fa fa-calendar"></i> Created on <span class="sbold h5">{{ date_formatted($info->created_at) }}</span> @  <span class="sbold h5">{{ time_formatted($info->created_at) }}</span>       
                </div>
                <div class="col-md-12 margin-top-10">
                    <i class="fa fa-calendar"></i> Updated on <span class="sbold h5">{{ date_formatted($info->updated_at) }}</span> @  <span class="sbold h5">{{ time_formatted($info->updated_at) }}</span>       
                </div>
            </div>
        </div>
        <div class="portlet light bordered">
            <h5 class="sbold">Status</h5>
            <hr>
            <div class="mt-radio-inline">
                <label class="mt-radio mt-radio-outline">
                    <input name="status" type="radio" value="published" {{ checked(Input::old('status', $info->post_status), 'published') }}> Published
                    <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                    <input name="status" type="radio" value="unpublished" {{ checked(Input::old('status', $info->post_status), 'unpublished') }}> Unpublished
                    <span></span>
                </label>
            </div>
        </div>

    </div>  
</div>  


<div class="form-actions">
    <button class="btn btn-primary"><i class="fa fa-check"></i> Update Coupon</button>
    @if( $info->post_status == 'unpublished' )
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
