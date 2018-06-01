@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }} <small>Add</small> 
    <a href="{{ URL::route($view.'.index', ['post_type' => $post_type]) }}" class="btn pull-right"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>
</h1>
<!-- END PAGE TITLE-->

@include('notification')


<form method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-8">
            <div class="portlet light bordered">
                <input type="text" class="form-control input-lg" name="title" placeholder="Enter title here ..." value="{{ Input::old('title') }}">
                {!! $errors->first('title','<span class="help-block text-danger">:message</span>') !!}

                <div class="margin-top-10">
                    <textarea name="content" class="tinymce">{{ Input::old('content') }}</textarea>               
                </div>
            </div>
            <div class="portlet light bordered">
                <h5 class="sbold">Short Description</h5>
                <textarea name="excerpt" class="form-control" rows="6">{{ Input::old('excerpt') }}</textarea>
            </div>
            <div class="portlet light bordered">
                <h5 class="sbold">Meta Tags</h5>
                <hr>

                <h5>Meta Title</h5>
                <input type="text" name="meta_title" class="form-control" value="{{ Input::old('meta_title') }}">
                <span class="help-inline">The title is in a title bar of Web browsers, user's history, bookmarks, or in search results.</span>

                <h5>Meta Keyword</h5>
                <input type="text" name="meta_keyword" class="form-control" value="{{ Input::old('meta_keyword') }}">
                <div class="help-inline">The keyword describe the Web page in the content attribute.</div>

                <h5>Meta Description</h5>
                <textarea name="meta_description" class="form-control" rows="5">{{ Input::old('meta_description') }}</textarea>
                <span class="help-inline">The Description the Web page in the content attribute.</span> 
            </div>
        </div>
        <div class="col-md-4">
            <div class="portlet light bordered">
                <h5 class="sbold">Post Status</h5>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mt-radio-inline">
                            <label class="mt-radio mt-radio-outline">
                                <input name="status" type="radio" value="published" {{ checked(Input::old('status', 'published'), 'published') }}> Published
                                <span></span>
                            </label> 
                            <label class="mt-radio mt-radio-outline">
                                <input name="status" type="radio" value="unpublished" {{ checked(Input::old('status'), 'unpublished') }}> Unpublished
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            @if( $post_type == 'post' )
            <div class="portlet light bordered">
                <h5 class="sbold">Categories</h5>
                <hr>

                <div class="mt-checkbox-list" style="overflow-y:auto; max-height:200px;">
                <label class="mt-radio mt-radio-outline">
                    <input type="radio" value="0" name="category" {{ checked(Input::old('category'), 0) }}><span></span> Uncategorised
                </label>
                {!! radio_ordered_menu($categories, 0, Input::old('category')) !!}
                </div>

            </div>
            @endif

            @if( $post_type == 'post' )
            <div class="portlet light bordered">
                <h5 class="sbold">Tags</h5>
                <div class="margin-top-20">
                    <input type="text" class="form-control input-large" name="tags" value="{{ Input::old('tags') }}" data-role="tagsinput">                
                </div>
            </div>
            @endif

            <div class="portlet light bordered">
                <h5 class="sbold">Page Template</h5>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::select('template', theme_templates(), Input::old('template'), ['class' => 'form-control select2']) }}
                    </div>
                </div>
            </div>

            <div class="portlet light bordered">
                <h5 class="sbold">Settings</h5>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt-checkbox-list">
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="hidden" name="settings[title]" value="show">
                                <input name="settings[title]" type="checkbox" value="hide" {{ checked(@$s['title'], 'hide') }}> 
                                <span></span> Hide Title
                            </label> 

                            @if( $post_type == 'post' )
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="hidden" name="settings[tags]" value="show">
                                <input name="settings[tags]" type="checkbox" value="hide" {{ checked(@$s['tags'], 'hide') }}> 
                                <span></span> Hide Tags
                            </label>
                            @endif
                            
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="hidden" name="settings[subscription]" value="hide">
                                <input name="settings[subscription]" type="checkbox" value="show" {{ checked(@$s['subscription'], 'show') }}>
                                <span></span> Show Subscription Form
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="portlet light bordered">
                <h5 class="sbold">Featured Image</h5>
                <hr>

                <div class="media-single">
                <input type="hidden" name="image" value="">
                @if( $image = Input::old('image') )
                <li>
                    <div class="media-thumb">
                    <img src="{{ $image }}">
                    <input type="hidden" name="image" value="{{ $image }}">
                    <a href="" class="delete-media"><i class="fa fa-trash-o"></i></a>
                    </div>
                </li>
                @endif
                </div>
                <a href="" class="filemanager btn btn-default" data-target=".media-single" data-type="images">Select featured image</a>

            </div>
            
        </div>
    </div>
    <div class="form-actions">
        <button class="btn btn-primary"><i class="fa fa-check"></i> Add {{ $post_type }}</button> 
    </div>
</form>
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
