@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $label }} <small>{{ $count }} item{{ is_plural($count) }}</small>
    <a href="{{ URL::route($view.'.add') }}" class="btn pull-right"><i class="fa fa-plus"></i> Add New</a>
</h1>
<!-- END PAGE TITLE-->

@include('admin.settings.tab')

<div class="portlet light bordered">

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
                    <th>Description</th>
                    <th width="90" class="text-right">Ship Day</th>
                    <th width="120" class="text-right">Price</th>
                    <th class="text-center">Default</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach($rows as $row): ?>
            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
                <tr class="tr-row-md has-row-actions">
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
                    <td width="350">
                    
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
                            @if( $row->post_status == 'actived' )
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
                    <td>{{ str_limit($row->post_content, 125) }}</td>
                    <td class="text-right">{{ @$postmeta->ship_day }} day{{ is_plural(@$postmeta->ship_day) }}</td>
                    <td class="text-right">
                        {{ $row->post_name==0? 'FREE' : number_format($row->post_name).' %' }} 
                    </td>
                    <td class="text-center">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="radio" name="default[]" data-id="{{ $row->id }}" class="set-default" {{ checked($row->id, $default) }}>
                            <span></span>
                        </label>
                    </td>
                    <td>{{ status_ico($row->post_status) }}</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        @if( ! count($rows) )
            <div class="well">No {{ strtolower($label) }} found.</div>
        @endif

    </div>

    {{ $rows->links() }}
    
</form>

</div>

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
