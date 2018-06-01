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

<form method="post" action="" enctype="multipart/form-data">
{{ csrf_field() }}
<div class="row">
    <div class="col-md-8">

        <div class="portlet light bordered">
            <h5 class="sbold">Clipart Name</h5>
            <input type="text" class="form-control" name="name" placeholder="Clipart Name" value="{{ Input::old('name', $info->post_title) }}">
            {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}

            <h5 class="sbold">Category</h5>
            {{ Form::select('category', ['' => 'Uncategorised'] + $post->select_posts(['post_type' => 'clipart-category']), Input::old('category', $info->parent), ['class' => 'form-control select2'] ) }}
            {!! $errors->first('category','<span class="help-block text-danger">:message</span>') !!}

            <h5 class="sbold margin-top-20">Description</h5>
            <textarea class="form-control" name="description" rows="6">{{ Input::old('description', $info->post_content) }}</textarea>
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
                    <input name="status" type="radio" value="actived" {{ checked(Input::old('status', $info->post_status), 'actived') }}> Actived
                    <span></span>
                </label> 
                <label class="mt-radio mt-radio-outline">
                    <input name="status" type="radio" value="inactived" {{ checked(Input::old('status', $info->post_status), 'inactived') }}> Inactived
                    <span></span>
                </label>
            </div>
        </div>

        <div class="portlet light bordered">
            <h5 class="sbold">Clipart Image</h5>
            <hr>
            <ul class="media-single">
                @if($info->image)
                <li>
                    <div class="media-thumb">
                    <img src="{{ has_image($info->image) }}">
                    <input type="hidden" name="image" value="{{ $info->image }}">
                    <a href="" class="delete-media"><i class="fa fa-trash-o"></i></a>
                    </div>
                </li>
                @endif
            </ul>
            <div class="cleafix"></div>
            <a href="" class="filemanager btn btn-default" data-target=".media-single" data-mode="single" data-type="images">Select clipart</a>
        </div>
                    
    </div>
</div>


<!-- BEGIN FORM ACTIONS -->
<div class="form-actions">
    <button class="btn btn-primary"><i class="fa fa-check"></i> Update {{ $single }}</button>
    @if( $info->post_status == 'inactived' )
    <a href="#" class="popup btn"
        data-href="{{ URL::route($view.'.delete', $info->id) }}" 
        data-toggle="modal"
        data-target=".popup-modal" 
        data-title="Confirm Move to Trash"
        data-body="Are you sure you want to move to trash ID: <b>#{{ $info->id }}</b>?"><i class="fa fa-trash-o"></i> Move to trash</a>  
    @endif
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
