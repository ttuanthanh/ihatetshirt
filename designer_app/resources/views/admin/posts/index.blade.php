@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $label }} <small>{{ $count }} item{{ is_plural($count) }}</small>
    <a href="{{ URL::route($view.'.add', ['post_type' => $post_type]) }}" class="btn pull-right"><i class="fa fa-plus"></i> Add New</a>
</h1>
<!-- END PAGE TITLE-->

@include('notification')

@include('partials.filter')

<form method="get">

<input type="hidden" name="post_type" value="{{ $post_type }}">    

@if( Input::get('type') )
<input type="hidden" name="type" value="{{ Input::get('type') }}">
@endif

<div class="row margin-top-10">
    <div class="col-md-3">
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
    <div class="col-md-4">
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
                <th>Title</th>
                @if( Input::get('post_type') == 'post' )
                <th>Category</th>
                @endif
                <th>Author</th>
                <th class="text-center">Created At</th>
                <th class="text-center">Updated At</th>
                <th class="text-center">Status</th>
                <th><i class="fa fa-star helptip tooltips"  data-container="body" data-placement="bottom" data-original-title="Featured"></i></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row): ?>
            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
            <tr class="tr-row-lg has-row-actions">
                <td>
                @if( $row->post_status == 'published' )
                <div class="disabled-checkbox"></div>
                @else
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" value="{{ $row->id }}" name="ids[]" class="checkboxes">
                    <span></span>
                </label>           
                @endif
                </td>
                <td width="350">

                    <h5 class="sbold no-margin">
                        @if( Input::get('type') == 'trash' )
                        {{ $row->post_title }}
                        @else
                        <a href="{{ URL::route($view.'.edit', $row->id) }}">{{ $row->post_title }}</a>
                        @endif
                    </h5>
                

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
           
                            @if(  $row->post_type == 'post' )
                            <a href="{{ URL::route('frontend.post', [$row->categoryRoute, $row->post_name]) }}" target="_blank">View</a> |   
                            @else
                            <a href="{{ URL::route('frontend.post', $row->post_name) }}" target="_blank">View</a> |   
                            @endif

                            <a href="{{ URL::route($view.'.edit', [$row->id, 'post_type' => $post_type]) }}">Edit</a> |   
                            @if( $row->post_status == 'published' )
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
                
                @if( Input::get('post_type') == 'post' )
                <td><span class="text-muted category-list">{{ $row->categoryList }}</span></td>
                @endif

                <td><a href="{{ URL::route('admin.users.edit', $row->post_author) }}">{{ $row->authorName }}</a></td>
                <td class="text-center">
                    {{ date_formatted($row->created_at) }}<br>
                    <span class="text-muted">{{ time_ago($row->created_at) }}</span>
                </td>
                <td class="text-center">
                    {{ date_formatted($row->updated_at) }}<br>
                    <span class="text-muted">{{ time_ago($row->updated_at) }}</span>
                </td>
                <td class="text-center">{{ status_ico($row->post_status) }}</td>
                <td><a href=""><i class="fa fa-star text-warning"></i></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    @if( ! count($rows) )
        <div class="well">No {{ str_plural($post_type) }} found.</div>
    @endif

    </div>

    {{ $rows->links() }}
    
</div>
</form>

@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
