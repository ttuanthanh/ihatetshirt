@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $label }} <small>{{ $count }} item{{ is_plural($count) }}</small> 
    <a href="{{ URL::route($view.'.add') }}" class="btn pull-right"><i class="fa fa-plus"></i> Add New</a>
</h1>
<!-- END PAGE TITLE-->

@include('notification')

@include('partials.filter')

<form method="get">

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
                <th width="1"></th>
                <th width="245">Name</th>
                <th width="100">SKU</th>
                <th width="90" class="text-right">Price</th>
                <th width="150">Categories</th>
                <th class="text-center" width="1">Status</th>
                <th width="1"><i class="fa fa-star helptip tooltips"  data-container="body" data-placement="bottom" data-original-title="Featured"></i></th>
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
                <td>
                    @if( $postmeta->image )
                    <a href="{{ $postmeta->image }}" class="btn-img-preview" data-title="{{ $row->post_title }}">
                        <img src="{{ has_image(str_replace('large', 'thumb', $postmeta->image)) }}" class="img-thumb"> 
                    </a>
                    @else
                    <img src="{{ has_image() }}" class="img-thumb"> 
                    @endif
                </td>
                <td>
                    <a href="{{ URL::route($view.'.edit', $row->id) }}" class="sbold">{{ $row->post_title }}</a>

                    <div class="row-actions">
                        <span class="text-muted">ID : {{ $row->id }}</span> | 
                        @if( Input::get('type') == 'trash' )
                        <a href="#" class="popup"
                            data-href="{{ URL::route($view.'.restore', $row->id) }}" 
                            data-toggle="modal"
                            data-target=".popup-modal" 
                            data-title="Confirm Restore"
                            data-body="Are you sure you want to restore ID: <b>#{{ $row->id }}</b>?">Restore</a> | 
                        <a href="#" class="popup"
                            data-href="{{ URL::route($view.'.destroy', $row->id) }}" 
                            data-toggle="modal"
                            data-target=".popup-modal" 
                            data-title="Confirm Delete Permanently"
                            data-body="Are you sure you want to delete permanently ID: <b>#{{ $row->id }}</b>?">Delete Permanently</a>
                        @else
                            <a href="{{ URL::route($view.'.edit', $row->id) }}">Edit</a> |   
                            @if( $row->post_status == 'published' )
                            <span class="text-muted">Move to trash</span>
                            @else
                            <a href="#" class="popup"
                                data-href="{{ URL::route($view.'.delete', $row->id) }}" 
                                data-toggle="modal"
                                data-target=".popup-modal" 
                                data-title="Confirm Move to Trash"
                                data-body="Are you sure you want to move to trash ID: <b>#{{ $row->id }}</b>?">Move to trash</a>
                            @endif
                        @endif
                    </div>
                </td>
                <td><small class="text-muted">{{ @$postmeta->sku }}</small></td>
                <td class="text-right">{{ amount_formatted(@$postmeta->starting_price) }}</td>
                <td><span class="text-muted category-list">{{ $row->categoryList }}</span></td>
                <td class="text-center">{{ status_ico($row->post_status) }}</td>
                <td class="text-center">
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="radio" name="default[]" data-id="{{ $row->id }}" class="set-default" {{ checked($row->id, $default) }}>
                        <span></span>
                    </label>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    @if( ! count($rows) )
        <div class="well">No products found.</div>
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
<script>
$(document).on('click', '.set-default', function(){
    var id = $(this).data('id'), 
    token = $('[name="csrf-token"]').attr('content');
    $.post('', { '_token' : token, 'id' : id }, function(res){
    });
});
</script>
@stop
