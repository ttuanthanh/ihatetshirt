@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }} <small>Add</small> 
    <a href="{{ URL::route($view.'.index') }}" class="btn pull-right"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>
</h1>
<!-- END PAGE TITLE-->

@include('notification')

<form method="post" action="" enctype="multipart/form-data">
{{ csrf_field() }}
<div class="row">
    <div class="col-md-8">

        <div class="portlet light bordered">
            <h5 class="sbold">Clipart Name</h5>
            <input type="text" class="form-control" name="name" placeholder="Clipart Name" value="{{ Input::old('name') }}">
            {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}

            <h5 class="sbold">Category</h5>
            {{ Form::select('category', ['' => 'Uncategorised'] + $post->select_posts(['post_type' => 'clipart-category']), Input::old('category'), ['class' => 'form-control select2'] ) }}
            {!! $errors->first('category','<span class="help-block text-danger">:message</span>') !!}

            <h5 class="sbold margin-top-20">Description</h5>
            <textarea class="form-control" name="description" rows="6">{{ Input::old('description') }}</textarea>
        </div>

    </div>
    <div class="col-md-4">

        <div class="portlet light bordered">
            <h5 class="sbold">Clipart Image</h5>
            <hr>
                <div class="media-single">
                <input type="hidden" name="image" value="">
                @if( $image = Input::old('image') )
                <li>
                    <div class="media-thumb">
                    <img src="{{ has_image(str_replace('large', 'medium', $image)) }}">
                    <input type="hidden" name="image" value="{{ $image }}">
                    <a href="" class="delete-media"><i class="fa fa-trash-o"></i></a>
                    </div>
                </li>
                @endif
                </div>
                <a href="" class="filemanager btn btn-default" data-target=".media-single" data-type="images">Select Clipart</a>

        </div>

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


<!-- BEGIN FORM ACTIONS -->
<div class="form-actions">
    <button class="btn btn-primary"><i class="fa fa-check"></i> Add {{ $single }}</button>
</div>
<!-- END FORM ACTIONS -->

</form>
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
