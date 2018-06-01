@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $label }} <small>{{ $count }} item{{ is_plural($count) }}</small>

</h1>
<!-- END PAGE TITLE-->

@include('notification')

@include('partials.filter')

<form method="get">
@if( Input::get('post_author') )
<input type="hidden" name="post_author" value="{{ Input::get('post_author') }}">
@endif

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

<div class="table-responsive margin-top-10">
    <div class="portlet light bordered">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="1">
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" value="1" name="" id="check_all">
                    <span></span>
                </label>                     
                </th>
                <th  width="250">Design By</th>
                <th>Details</th>
                <th width="200"></th>
                <th width="100">Size</th>
                <th width="100">Price</th>
                <th width="1">
                    <i class="fa fa-star helptip tooltips"  data-container="body" data-placement="bottom" data-original-title="Featured"></i>
                </th>
                <th class="text-center" width="120">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $index => $row): ?>
            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
            <?php $content = json_decode($row->post_content); ?>
            <tr class="tr-row-lg has-row-actions">
                <td>
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" value="{{ $row->id }}" name="ids[]" class="checkboxes">
                    <span></span>
                </label>           
                </td>
                <td>
                    <h5 class="sbold no-margin"><a href="{{ URL::route('frontend.designer.index', ['reload' => @$row->post_name]) }}" target="_blank">
                        {{ @$postmeta->design_title }}</a> by <span class="text-muted">{{ @$postmeta->email }}</span>
                    </h5>
                    </span>

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
                            <a href="{{ URL::route($view.'.view', $row->id) }}">View</a> |   
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
                <td width="1">                  

                    <a href="{{ asset(@$content->image.'?'.json_decode($content->png_filetime)[0]) }}" class="btn-img-preview" data-title="{{ @$content->details->color_title }}">
                        <img src="{{ asset(@$content->image.'?'.json_decode($content->png_filetime)[0]) }}" class="img-responsive"> 
                    </a>
                </td>
                <td>
                    <div class="order-status">
                        {{ $row->post_title }}<br>
                        <span class="box-color" style="background-color: {{ @$content->details->color }};"></span> {{ @$content->details->color_title }}               
                    </div>
                </td>
                <td>
                    
                </pre>                        
                    <?php $sizes = @$content->start_design->sizes; ?>
                    @if( $sizes )
                    <div class="order-status">
                    @foreach($sizes as $size_k => $size_v)
                        @if($size_v)
                        {{ $size_k }} ( <span class="text-muted"><b>{{ $size_v }}</b></span> ) <br>
                        @endif
                    @endforeach
                    </div>
                    @endif
                </td>
                <td>
                    @if( @$content->start_design->unit_price )
                    <small class="text-muted">UNIT PRICE</small><br>
                    <span class="text-primary">{{ $content->start_design->unit_price }}</span><br>

                    <small class="text-muted">TOTAL PRICE</small><br>
                    <span class="text-primary">{{ $content->start_design->total_price }}</span>
                    @endif
                </td>
                <td>
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" data-id="{{ $row->id }}" class="set-featured" {{ checked(@$postmeta->featured, 1) }}>
                        <span></span>
                    </label>                    
                </td>
                <td class="text-center">
                    {{ date_formatted($row->created_at) }}<br>
                    <span class="text-muted">{{ time_ago($row->created_at) }}</span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    @if( ! count($rows) )
        <div class="well">No customer designs found.</div>
    @endif

    </div>

</div>
</form>
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
<script>
$(document).on('click', '.set-featured', function(){
    var id = $(this).data('id'), 
    token = $('[name="csrf-token"]').attr('content');
    $.post('', { '_token' : token, 'id' : id, val :  $(this).is(':checked') }, function(res){
        console.log( res );
    });
});
</script>
@stop
