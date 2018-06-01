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
                        <label class="col-md-4 control-label">Name</label>
                        <div class="col-md-8">
                            <input type="text" name="name" class="form-control" value="{{ Input::old('name', $info->post_title) }}">
                            {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">HEX</label>
                        <div class="col-md-8">
                            <input type="text" name="hex" class="form-control colorpicker" data-control="hue" value="{{ Input::old('hex', $info->post_content) }}">
                            {!! $errors->first('hex','<span class="help-block text-danger">:message</span>') !!}
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
                    <input name="status" type="radio" value="actived" {{ checked(Input::old('status', $info->post_status), 'actived') }}> Actived
                    <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                    <input name="status" type="radio" value="inactived" {{ checked(Input::old('status', $info->post_status), 'inactived') }}> Inactived
                    <span></span>
                </label>
            </div>
        </div>

    </div>  
</div>  


<div class="form-actions">
    <button class="btn btn-primary"><i class="fa fa-check"></i> Update {{ $single }}</button>
</div>

</form>
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
