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

</div>



<div class="portlet light bordered margin-top-10">

    <table class="table table-bordered color-code text-center margin-top-10">
        <tr>
            <td><h5 class="sbold">Color Code : </h5></td>
            <td class="cc-yellow">Past Completed Order</td>
            <td class="cc-grey">Normal</td>
            <td class="cc-green">Shipped</td>
            <td class="cc-orange">Past Due</td>
            <td>
                <i class="fa fa-close badge badge-danger"></i> Problem Reported
            </td>
            <td>
                <i class="fa fa-exclamation badge badge-warning"></i> Due Date is Tomorrow
            </td>
            <td>
                <i class="fa fa-exclamation badge badge-danger"></i> Ship Date is Today
            </td>
        </tr>
    </table>
    <table class="table table-bordered table-bordered-2 datatable">
        <thead>
            <tr>
                <td width="1">
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" value="1" name="" id="check_all">
                    <span></span>
                </label>                     
                </td>
                <th width="250">Order</th>
                <th class="text-center">
                    <i class="fa fa-user btn-icon helptip tooltips" data-container="body" data-placement="bottom" data-original-title="Apparel Order"></i>
                </th>
                <th class="text-center" width="1">
                    <i class="fa fa-truck btn-icon helptip tooltips" data-container="body" data-placement="bottom" data-original-title="Ship Date"></i>
                </th>      
                <th class="text-center">
                    <i class="fa fa-image btn-icon helptip tooltips" data-container="body" data-placement="bottom" data-original-title="Artwork"></i>
                </th>
                <th class="text-center">
                    <i class="fa fa-star btn-icon helptip tooltips" data-container="body" data-placement="bottom" data-original-title="Proof"></i>
                </th>
                <th class="text-center">
                    <i class="fa fa-print btn-icon helptip tooltips" data-container="body" data-placement="bottom" data-original-title="Print Ready"></i>
                </th>

                <th class="text-center">Tracking No.</th>
                <th class="text-center">Order Date</th>
                <th class="text-center">Due Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row): ?>
            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>

            <?php $past_due = strtotime(date('Y-m-d')) >= strtotime(@$postmeta->due_date) ? 'past-due' : ''; ?>
            <tr class="tr-row-lg has-row-actions {{ $row->post_status }} {{ $past_due }}">
                <td>
                    @if( in_array($row->post_status, ['completed', 'shipped']) )
                    <div class="disabled-checkbox"></div>
                    @else
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" value="{{ $row->id }}" name="ids[]" class="checkboxes">
                        <span></span>
                    </label>           
                    @endif  

                    <div class="margin-top-10">
                    @if( date('Y-m-d') == @$postmeta->shipping_date )
                    <i class="fa fa-exclamation badge badge-warning"></i>
                    @endif

                    @if( date('Y-m-d', strtotime('tomorrow')) == @$postmeta->due_date )
                    <i class="fa fa-exclamation badge badge-danger"></i>
                    @endif                        
                    </div>

                </td>
                <td>
                    <a href="{{ URL::route($view.'.edit', [$row->id, 'tab' => 'order-details']) }}" class="sbold">#{{ $row->id }}</a> 
                    by 
                    {{ ucwords(@$postmeta->billing_firstname.' '.@$postmeta->billing_lastname) }}
                    <div>{{ @$postmeta->billing_email }}</div>

                    @if( @$postmeta->po_number )
                    <span class="text-muted small">PO# {{ $postmeta->po_number }}</span>
                    @endif

                    @if( $comment = $post->where('post_type', 'order_comment')->where('parent', $row->id)->count() )
                    <div class="pull-right helptip tooltips" data-container="body" data-placement="bottom" data-original-title="COMMENT">
                    <i class="fa fa-comment"></i> {{ number_format($comment) }}                        
                    </div>
                    @endif

                    <div class="row-actions">
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


                            <a href="{{ URL::route($view.'.edit', [$row->id, 'tab' => 'order-details']) }}">View</a> |   
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

                <td>
                    <?php $garments = $post->where('post_type', 'garment')->where('parent', $row->id)->count(); ?>
                    <a href="{{ route('admin.orders.edit', [$row->id, 'tab' => 'apparel-order']) }}">
                    {{ $garments ? status_ico(1) : status_ico(0) }}
                    </a>
                </td>
                <td class="text-center">

                    <a href="{{ route('admin.orders.edit', [$row->id, 'tab' => 'ship-date']) }}">
                    @if( @$postmeta->approve_ship_date )
                    <span class="badge badge-success uppercase sbold">{{ date_formatted($postmeta->shipping_date) }}</span>
                    @else
                    <span class="badge badge-danger uppercase sbold">{{ date_formatted(@$postmeta->shipping_date) }}</span>                            
                    @endif
                    </a>

                </td>
                <td>
                    <a href="{{ route('admin.orders.edit', [$row->id, 'tab' => 'artwork']) }}">
                    <?php 
                        $artwork_count = count(glob("assets/uploads/orders/".$row->id."/artwork/*.*"));
                        $has_artwork = '<span class="badge badge-danger uppercase sbold">NO</span>';
                        if( @$postmeta->approve_proof && $artwork_count>0 || @$postmeta->approve_artwork) {
                            $has_artwork = '<span class="badge badge-success uppercase sbold">YES</span>';
                        } elseif( $artwork_count>0 ) {
                            $has_artwork = '<span class="badge badge-warning uppercase sbold">YES</span>';
                        }
                        echo $has_artwork;
                    ?>    
                    </a>                          
                </td>
                <td>
                    <a href="{{ route('admin.orders.edit', [$row->id, 'tab' => 'proof']) }}">
                    <?php 
                        $proof_count = count(glob("assets/uploads/orders/".$row->id."/proof/*.*"));
                        $has_proof = '<span class="badge badge-danger uppercase sbold">NO</span>';
                        if( @$postmeta->approve_proof && $proof_count>0 ) {
                            $has_proof = '<span class="badge badge-success uppercase sbold">YES</span>';
                        } elseif( $proof_count>0 ) {
                            $has_proof = '<span class="badge badge-warning uppercase sbold">YES</span>';
                        }
                        echo $has_proof;
                    ?>     
                    </a>                       
                </td>
                <td>
                    <a href="{{ route('admin.orders.edit', [$row->id, 'tab' => 'proof']) }}">
                    @if( @$postmeta->approve_proof && $proof_count>0 ) 
                    {{ status_ico(1) }}
                    @else
                    {{ status_ico(0) }}                            
                    @endif
                    </a>
                </td>

                <td class="text-center">{{ @$postmeta->tracking_number }}</td>
                <td class="text-center">
                    {{ date_formatted($row->created_at) }}<br>
                    <span class="text-muted">{{ time_ago($row->created_at) }}</span>
                </td>
                <td class="text-center">
                    {{ date_formatted(@$postmeta->due_date) }}
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    @if( ! count($rows) )
        <div class="well">No orders found.</div>
    @endif



    {{ $rows->links() }}

</div>
</form>

@endsection

@section('style')

<style>
.color-code td {
    vertical-align: middle !important;
}
.cc-yellow {
    background-color: #FFEB3B;
}
.cc-grey {
    background-color: #D8E4EA;
}
.color-code .cc-red {
    background-color: #E53225;
}
.cc-green {
    background-color: #8BDC65;
}
.cc-orange {
    background-color: #FF9800;
}
.badge.fa-exclamation {
    font-size: 18px !important;
    padding: 6px 10px 19px !important;
    border: 1px solid #c2cad8;
    border-radius: 40px !important;
}
.badge.fa-close {
    font-size: 17px !important;
    padding: 7px 8px 21px !important;
    border: 1px solid #c2cad8;
    border-radius: 40px !important;

}
table.dataTable tbody tr.pending {
    background-color: #D8E4EA;
}
table.dataTable tbody tr.completed {
    background-color: #FFEB3B;
}
table.dataTable tbody tr.shipped {
    background-color: #8BDC65;
}
table.dataTable tbody tr.pending.past-due,
table.dataTable tbody tr.processing.past-due {
    background-color: #FF9800;
}

</style>
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
