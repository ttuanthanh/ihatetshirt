@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $label }} <small>{{ $count }} item{{ is_plural($count) }}</small> </h1>
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
                <th width="70">Details</th>
                <th width="300"></th>
                <th>Design</th>
                <th class="text-center">Date Registered</th>
                <th class="text-center">Last Login</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row): ?>
            <?php $usermeta = get_meta( $row->userMetas()->get() ); ?>

            <tr class="tr-row-lg has-row-actions">
                <td>
                    @if( $row->status == 'actived' )
                    <div class="disabled-checkbox"></div>
                    @else
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" value="{{ $row->id }}" name="ids[]" class="checkboxes">
                        <span></span>
                    </label>           
                    @endif              
                </td>
                <td>
                    @if( @$usermeta->profile_picture )
                    <a href="{{ has_photo($usermeta->profile_picture) }}" class="btn-img-preview" data-title="{{ $row->fullname }} ( {{ $row->email }} )">
                        <img src="{{ has_photo($usermeta->profile_picture) }}" class="img-responsive img-thumb"> 
                    </a>
                    @else
                        <img src="{{ has_photo() }}" class="img-responsive img-thumb"> 
                    @endif
                </td>
                <td>
                    <h5 class="sbold no-margin"><a href="{{ URL::route($view.'.view', $row->id) }}">{{ $row->fullname }}</a></h5>
                    <span class="text-muted">{{ $row->email }}</span>

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
                            <a href="{{ URL::route($view.'.view', $row->id) }}">view</a> |                    
                            @if( $row->status == 'actived' )
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
                <td>

                <a href="{{ route('admin.customers.designs.index', ['post_author' => $row->id]) }}">
                    <?php $design_count = $post->where(['post_type' => 'customer_design', 'post_author' => $row->id])->count(); ?>
                    <b>{{ number_format($design_count) }}</b> <span class="text-muted">Design{{ is_plural($design_count) }}</span></a>             
       
                </td>
                <td class="text-center">
                    {{ date_formatted($row->created_at) }}<br>
                    <span class="text-muted">{{ time_ago($row->created_at) }}</span>
                </td>
                <td class="text-center">
                    @if( @$usermeta->last_login )
                    {{ date_formatted($usermeta->last_login) }}<br>
                    <span class="text-muted">{{ time_ago($usermeta->last_login) }}</span>
                    @endif
                </td>
                <td class="text-center">{{ status_ico($row->status) }}</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    @if( ! count($rows) )
        <div class="well">No {{ strtolower($label) }} found.</div>
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
