@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }} <small>Edit</small> 
    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn pull-right"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>
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
                        <label class="col-md-3 control-label">Name</label>
                        <div class="col-md-9">
                            <input type="text" name="name" class="form-control" value="{{ Input::old('name', $info->post_title) }}">
                            <span class="help-inline">The name is how it appears on your site.</span>
                            {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Slug</label>
                        <div class="col-md-9">
                            <input type="text" name="slug" class="form-control slug-field" value="{{ Input::old('slug', $info->post_name) }}">
                            <span class="help-inline">The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</span>
                            {!! $errors->first('slug','<span class="help-block text-danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Parent Category</label>
                        <div class="col-md-9">
                            {{ Form::select('parent', ['' => 'Uncategorised'] + $post->select_posts(['post_type' => $post_type]), Input::old('parent', $info->parent), ['class' => 'form-control select2'] ) }}
                            <span class="help-inline">Categories, unlike tags, can have a hierarchy. You might have a Jazz category, and under that have children categories for Bebop and Big Band. Totally optional.</span>   
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Description</label>
                        <div class="col-md-9">
                            <textarea name="description" class="form-control" rows="5">{{ Input::old('description', $info->post_content) }}</textarea>
                            <span class="help-inline">The description is not prominent by default; however, some themes may show it.</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Image</label>
                        <div class="col-md-5">
                        
                         <div class="media-single">
                            @if($info->image)
                            <li>
                                <div class="media-thumb">
                                <img src="{{ has_image(str_replace('large', 'medium', $info->image)) }}">
                                <input type="hidden" name="image" value="{{ $info->image }}">
                                <a href="" class="delete-media"><i class="fa fa-trash-o"></i></a>
                                </div>
                            </li>
                            @endif
                        </div>

                        <a href="" class="filemanager btn btn-default" data-target=".media-single" data-type="images">Select image</a>

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
                    <i class="fa fa-calendar"></i> Created on <span class="sbold h5">{{ date_formatted($info->created_at) }}</span> @ <span class="sbold h5">{{ time_formatted($info->created_at) }}</span>          
                </div>
                <div class="col-md-12 margin-top-10">
                    <i class="fa fa-calendar"></i> Updated on <span class="sbold h5">{{ date_formatted($info->updated_at) }}</span> @ <span class="sbold h5">{{ time_formatted($info->updated_at) }}</span>       
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
        
    </div>
</div>


<div class="form-actions">
    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Update Category</button>
    @if( $info->post_status == 'inactived' )
    <a href="#" class="popup btn"
        data-href="{{ URL::route($view.'.delete', [$info->id, query_vars()]) }}" 
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
