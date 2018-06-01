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
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="1">
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" value="1" name="" id="check_all">
                    <span></span>
                </label>                     
                </th>
                <th width="250">Name</th>
                <th>Code</th>
                <th>Value</th>
                <th>Type</th>
                <th class="text-right">Count</th>
                <th class="text-center">Date Start</th>
                <th class="text-center">Date End</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row): ?>
            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
            <tr class="tr-row-md has-row-actions">
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
                <td>{{ $row->post_name }}</td>
                <td>{{ discount_value($postmeta->coupon_value, $postmeta->discount_type) }}</td>
                <td>{{ coupon_types($postmeta->coupon_type) }}</td>
                <td class="text-right">
                    {{ number_format(App\Postmeta::where('meta_key', 'coupon_code')->where('meta_value', $row->post_name)->count()) }}
                </td>
                <td class="text-center">{{ date_formatted($postmeta->start_date) }}</td>
                <td class="text-center">{{ date_formatted($postmeta->end_date) }}</td>
                <td class="text-center">{{ status_ico($row->post_status) }}</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    @if( ! count($rows) )
        <div class="well">No coupons found.</div>
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
