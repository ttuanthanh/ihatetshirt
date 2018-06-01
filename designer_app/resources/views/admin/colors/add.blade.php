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
                        <label class="col-md-4 control-label">Name</label>
                        <div class="col-md-8">
                            <input type="text" name="name" class="form-control" value="{{ Input::old('name') }}">
                            {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">HEX</label>
                        <div class="col-md-8">
                            <input type="text" name="hex" class="form-control colorpicker" data-control="hue" value="{{ Input::old('hex') }}">
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
                    <input name="status" type="radio" value="actived" {{ checked(Input::old('status', 'actived'), 'actived') }}> Actived
                    <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                    <input name="status" type="radio" value="inactived" {{ checked(Input::old('status'), 'inactived') }}> Inactived
                    <span></span>
                </label>
            </div>
        </div>

    </div>  
</div>  


<div class="form-actions">
    <button class="btn btn-primary"><i class="fa fa-check"></i> Add {{ $single }}</button>
</div>

</form>
@endsection

@section('style')
<link href="{{ asset('assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-minicolors/jquery.minicolors.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('plugin_script') 
<script src="{{ asset('assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js') }}" type="text/javascript"></script>
@stop

@section('script')
<script>
$('.colorpicker').each(function() {
    $(this).minicolors({
        control: $(this).attr('data-control') || 'hue',
        defaultValue: $(this).attr('data-defaultValue') || '',
        inline: $(this).attr('data-inline') === 'true',
        letterCase: $(this).attr('data-letterCase') || 'lowercase',
        opacity: $(this).attr('data-opacity'),
        position: $(this).attr('data-position') || 'bottom left',
        change: function(hex, opacity) {
            if (!hex) return;
            if (opacity) hex += ', ' + opacity;
            if (typeof console === 'object') {
                console.log(hex);
            }
        },
        theme: 'bootstrap'
    });
});
</script>
@stop
