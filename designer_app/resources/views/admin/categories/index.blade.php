@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{{ $label }} <small>{{ $count }} item{{ is_plural($count) }}</small></h1>
<!-- END PAGE TITLE-->

@include('notification')

<div class="row">
    <div class="col-md-4">    

        <div class="portlet light bordered">

            <h5>Add New {{ $single }}</h5>

            <hr>

            <form id="form-category" method="post" enctype="multipart/form-data" action="{{ URL::route($view.'.add', query_vars()) }}">

                {{ csrf_field() }}
                
                <h5>Name</h5>

                <input type="text" name="name" class="form-control" value="{{ Input::old('name') }}">
                <span class="help-inline">The name is how it appears on your site.</span>
                {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}

                <h5>Slug</h5>
                <input type="text" name="slug" class="form-control slug-field" value="{{ Input::old('slug') }}">
                <span class="help-inline">The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</span>
                {!! $errors->first('slug','<span class="help-block text-danger">:message</span>') !!}

                <h5>Parent Category</h5>
                {{ Form::select('parent', ['' => 'Uncategorised'] + $post->select_posts(['post_type' => $post_type]), Input::old('parent'), ['class' => 'form-control select2'] ) }}
                <span class="help-inline">Categories, unlike tags, can have a hierarchy. You might have a Jazz category, and under that have children categories for Bebop and Big Band. Totally optional.</span>   

                <h5>Description</h5>
                <textarea name="description" class="form-control" rows="5">{{ Input::old('description') }}</textarea>
                <span class="help-inline">The description is not prominent by default; however, some themes may show it.</span>

                <div class="media-single margin-top-20">
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
                <a href="" class="filemanager btn btn-default" data-target=".media-single" data-type="images">Select image</a>

                <hr>


            </form>

        </div>


    </div>
    <div class="col-md-8">

        @if(Input::get('type') == 'trash')
        <a href="{{ URL::route($view.'.index', query_vars('type=0&s=0&action=0&page=0')) }}">All ({{ number_format($all) }})</a> | 
        <a href="{{ URL::route($view.'.index', query_vars('type=trash&s=0&action=0&page=0')) }}" class="sbold">Trashed ({{ number_format($trashed) }})</a>
        @else
        <a href="{{ URL::route($view.'.index', query_vars('type=0&s=0&action=0&page=0')) }}" class="sbold">All ({{ number_format($all) }})</a> | 
        <a href="{{ URL::route($view.'.index', query_vars('type=trash&s=0&action=0&page=0')) }}">Trashed ({{ number_format($trashed) }})</a>
        @endif

        <form method="get">
        <input type="hidden" name="post_type" value="{{ Input::get('post_type') }}">    
        @if( Input::get('type') )
        <input type="hidden" name="type" value="{{ Input::get('type') }}">
        @endif

        <div class="row margin-top-10">
            <div class="col-md-5">
                <div class="input-group">
                    <select class="form-control" name="action">
                        <option value="">Bulk Actions</option>
                        @if( Input::get('type') == 'trash' )
                        <option value="restore">Restore</option>
                        <option value="destroy">Delete Permanently</option>
                        @else                
                        <option value="trash">Move to Trash</option>
                        @endif
                    </select>     
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary" type="button">Apply</button>
                    </span>
                </div>
            </div>
            <div class="col-md-7">
                <div class="input-group">
                    <input type="text" class="form-control" name="s" placeholder="Enter Search ..." value="{{ Input::get('s') }}">  
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary" type="button">Search</button>
                    </span>
                </div>
            </div>
        </div>

        <div class="portlet light bordered margin-top-10">
            <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="1">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" value="1" name="" id="check_all">
                            <span></span>
                        </label>                     
                        </th>
                        <th width="70"></th>
                        <th width="400">Name</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row): ?>
                    <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
                    <tr class="tr-row-lg has-row-actions">
                        <td>
                            @if( $row->post_status == 'actived' )
                            <div class="disabled-checkbox"></div>
                            @else
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="checkbox" value="{{ $row->id }}" name="ids[]" class="checkboxes">
                                <span></span>
                            </label>           
                            @endif 
                        </td>
                        <td class="text-center">
                            @if($postmeta->image)
                            <a href="{{ $postmeta->image }}" class="btn-img-preview" data-title="{{ $row->post_title }}">
                                <img src="{{ str_replace('large', 'thumb', $postmeta->image) }}" class="img-thumbnail"> 
                            </a>
                            @else
                                <img src="{{ has_image() }}" class="img-thumbnail"> 
                            @endif
                        </td>
                        <td>
                            <h5 href="" class="sbold"><a href="{{ URL::route($view.'.edit', [$row->id, 'post_type' => $post_type]) }}">{{ $row->post_title }}</a></h5>

                            <div class="row-actions">
                                <span class="text-muted">ID : {{ $row->id }}</span> | 
                                @if( Input::get('type') == 'trash' )
                                <a href="#" class="popup"
                                    data-href="{{ URL::route($view.'.restore', [$row->id, query_vars()]) }}" 
                                    data-toggle="modal"
                                    data-target=".popup-modal" 
                                    data-title="Confirm Restore"
                                    data-body="Are you sure you want to restore ID: <b>#{{ $row->id }}</b>?">Restore</a> | 
                                <a href="#" class="popup"
                                    data-href="{{ URL::route($view.'.destroy', [$row->id, query_vars()]) }}" 
                                    data-toggle="modal"
                                    data-target=".popup-modal" 
                                    data-title="Confirm Delete Permanently"
                                    data-body="Are you sure you want to delete permanently ID: <b>#{{ $row->id }}</b>?">Delete Permanently</a>
                                @else
                                    <a href="{{ URL::route($view.'.edit', [$row->id, 'post_type' => $post_type]) }}">Edit</a> |   
                                    @if( $row->post_status == 'actived' )
                                    <span class="text-muted">Move to trash</span>
                                    @else
                                    <a href="#" class="popup"
                                        data-href="{{ URL::route($view.'.delete', [$row->id, query_vars()]) }}" 
                                        data-toggle="modal"
                                        data-target=".popup-modal" 
                                        data-title="Confirm Move to Trash"
                                        data-body="Are you sure you want to move to trash ID: <b>#{{ $row->id }}</b>?">Move to trash</a>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td class="text-right">{{  $row->categoryCount }}</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            @if( ! count($rows) )
                <div class="well">No categories found.</div>
            @else
            {{ $rows->appends(['post_type' => Input::get('post_type')])->links() }}
            @endif

            </div>

        </div>   
        </form>     

    </div>    
</div>

<div class="form-actions">
<button class="btn btn-primary btn-submit">Add New {{ $single }}</button>                    
</div>

@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
<script>
$(document).on('click', '.btn-submit', function(){ 
    $('#form-category').submit();
});
</script>
@stop
