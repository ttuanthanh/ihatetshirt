@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }} 
    <a href="{{ URL::route($view.'.index') }}" class="btn pull-right"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>
</h1>
<!-- END PAGE TITLE-->

@include('notification')

<div class="tabbable-custom nav-justified">
<ul class="nav nav-tabs  uppercase">
    <li class="{{ Input::get('tab') == 'order-details' ? 'active' : '' }}">
        <a href="?tab=order-details">Order Details </a>
    </li>
    <li class="{{ Input::get('tab') == 'apparel-order' ? 'active' : '' }}">
        <a href="?tab=apparel-order">Apparel Order </a>
    </li>
    <li class="{{ Input::get('tab') == 'ship-date' ? 'active' : '' }}">
        <a href="?tab=ship-date">Ship Date</a>
    </li>
    <li class="{{ Input::get('tab') == 'artwork' ? 'active' : '' }}">
        <a href="?tab=artwork">Artwork </a>
    </li>  
    <li class="{{ Input::get('tab') == 'proof' ? 'active' : '' }}">
        <a href="?tab=proof">Proof </a>
    </li>       
</ul>
</div>


<form method="post" enctype="multipart/form-data" class="form-horizontal">
{{ csrf_field() }}


<div class="row">
    <div class="col-md-8">

        <div class="portlet light bordered">

            <h3>Order #{{ $info->id }} details</h3>

            <table class="table table-bordered table-striped margin-top-20">
                <thead>
                    <tr>
                        <th>Apparel Order</th>
                        <th>Ship Date</th>
                        <th>Artwork</th>
                        <th>Proof</th>
                        <th>Print Ready</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.edit', [$info->id, 'tab' => 'apparel-order']) }}">
                            {{ count($garments) ? status_ico(1) : status_ico(0) }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.edit', [$info->id, 'tab' => 'ship-date']) }}">
                            @if( $info->approve_ship_date )
                            <span class="badge badge-success uppercase sbold">{{ date_formatted($info->shipping_date) }}</span>
                            @else
                            <span class="badge badge-danger uppercase sbold">{{ date_formatted($info->shipping_date) }}</span>                            
                            @endif
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.edit', [$info->id, 'tab' => 'artwork']) }}">
                            <?php 
                                $artwork_count = count(glob("assets/uploads/orders/".$info->id."/artwork/*.*"));
                                $has_artwork = '<span class="badge badge-danger uppercase sbold">NO</span>';
                                if( $info->approve_proof && $artwork_count>0 || @$info->approve_artwork) {
                                    $has_artwork = '<span class="badge badge-success uppercase sbold">YES</span>';
                                } elseif( $artwork_count>0 ) {
                                    $has_artwork = '<span class="badge badge-warning uppercase sbold">YES</span>';
                                }
                                echo $has_artwork;
                            ?>    
                            </a>                          
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.edit', [$info->id, 'tab' => 'proof']) }}">
                            <?php 
                                $proof_count = count(glob("assets/uploads/orders/".$info->id."/proof/*.*"));
                                $has_proof = '<span class="badge badge-danger uppercase sbold">NO</span>';
                                if( $info->approve_proof && $proof_count>0 ) {
                                    $has_proof = '<span class="badge badge-success uppercase sbold">YES</span>';
                                } elseif( $proof_count>0 ) {
                                    $has_proof = '<span class="badge badge-warning uppercase sbold">YES</span>';
                                }
                                echo $has_proof;
                            ?>     
                            </a>                       
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.edit', [$info->id, 'tab' => 'proof']) }}">
                            @if( $info->approve_proof && $proof_count>0 ) 
                            {{ status_ico(1) }}
                            @else
                            {{ status_ico(0) }}                            
                            @endif
                            </a>
                        </td>
                    </tr>
                </tbody>
        </table>


        </div>
        @if(Input::get('tab') == 'order-details')
            @include('admin.orders.tabs.tab1')
        @elseif(Input::get('tab') == 'apparel-order')
            @include('admin.orders.tabs.tab2')
        @elseif(Input::get('tab') == 'ship-date')
            @include('admin.orders.tabs.tab3')
        @elseif(Input::get('tab') == 'artwork')
            @include('admin.orders.tabs.tab4')
        @elseif(Input::get('tab') == 'proof')
            @include('admin.orders.tabs.tab5')
        @endif


        <div class="portlet light bordered">
            <?php $detail = json_decode($info->post_content); ?>
            <h5 class="sbold">Art Information:</h5>

            <table class="table table-striped table-hover table-bordered margin-top-20">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Color</th>
                    <th>Sizes</th>
                    <th></th>
                </tr>
            </thead>
            @foreach($detail->orders as $o_token => $order)
            <tr>
                <td width="300">
                
                @if( @$order->design_id )
                <a href="{{ route('admin.customers.designs.view', $order->design_id) }}" target="_blank">{{ $order->name }}</a>
                @else
                {{ $order->name }}
                @endif

                </td>
                <td>                
                    <span class="box-color" style="background-color: {{ @$order->design->color_hex }}"></span>  {{ @$order->design->color_title }}
              </td>
              <td>

                <?php $sizes = @$order->sizes; ?>                    

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
                <a href="#" class="btn btn-success btn-xs edit-product-details" 
                data-edit="{{ json_encode(['name' => $order->name, 'color_hex' => @$order->design->color_hex, 'color_title' => @$order->design->color_title, 'sizes' => $sizes, 'token' => $o_token]) }}">Edit</a>

          </td>
            </tr>
            @endforeach
                
            </table>


        </div>

    </div>
    <div class="col-md-4">

        <div class="portlet light bordered">
            <h5 class="sbold">General Details</h5>
            <hr>

            <label>Oder Date:</label>

            <div class="row">
                <div class="col-md-6">
                    <div class="input-icon">
                        <i class="fa fa-calendar"></i>
                        <input type="text" name="order_date" class="form-control datepicker" value="{{ date_formatted_b($info->order_date) }}"> 
                    </div>      
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <?php $time = explode(':', $info->order_time); ?>
                        <input type="text" name="order_time_h" class="form-control numeric" maxlength="2" value="{{ @$time[0] }}"> 
                        <span class="input-group-addon">:</span>
                        <input type="text" name="order_time_m" class="form-control numeric" maxlength="2" value="{{ @$time[1] }}"> 
                    </div>      
                </div>
            </div>

            <div class="margin-top-10">
                <label>Order Status:</label>
                {{ Form::select('status', order_status(), $info->post_status, ['class' => 'form-control']) }}                           
            </div>


            <div class="margin-top-10">
                <label>Tracking Number:</label>
                <input type="text" name="tracking_number" class="form-control" value="{{ $info->tracking_number }}">                                
            </div>

            <div class="margin-top-10">
                <label>PO Number:</label>
                <input type="text" name="po_number" class="form-control" value="{{ $info->po_number }}">                                
            </div>

            <div class="margin-top-10">
                <label>Due Date:</label>
                <div class="input-icon">
                    <i class="fa fa-calendar"></i>
                    <input type="text" name="due_date" class="form-control datepicker" value="{{ date_formatted_b($info->due_date) }}"> 
                </div>                          
            </div>

        </div>

        <div class="portlet light bordered">
            <h5 class="sbold">Order Notes</h5>
            <hr>
{{--             Order status changed by bulk edit: Order status changed from On hold to Completed. --}}
            
            <div class="order-notes">

                @if( $info->order_notes )
                <div class="margin-bottom-10 n-c">
                    <div class="note-content">{{ $info->order_notes }}</div>
                    <span class="help-inline">added on <b>{{ date('F d, Y', strtotime($info->created_at)) }}</b> at 
                        <b>{{ date('H:i', strtotime($info->created_at)) }}</b> by <b>Customer</b></span>  
                </div>
                @endif

                @foreach($notes as $note)
                <div class="margin-bottom-10 n-c">
                    <div class="note-content">{{ $note->post_content }}</div>
                    <span class="help-inline">added on <b>{{ date('F d, Y', strtotime($info->created_at)) }}</b> at 
                        <b>{{ date('H:i', strtotime($info->created_at)) }}</b> by <b>{{ $note->post_title }}</b> 
                        @if( $note->post_author == Auth::user()->id)
                        <a href="#" data-id="{{ $note->id }}" class="delete-note pull-right btn-xs margin-top-10"><i class="fa fa-remove"></i></a></span>  
                        @endif
                </div>
                @endforeach
                
            </div>

            <h5 class="sbold">Add Note</h5>
            <textarea class="form-control" id="note" rows="5" placeholder="Enter your note here ..."></textarea>

            <div class="margin-top-10 text-right">
                <button type="button" class="btn btn-primary btn-note" data-id="{{ $info->id }}" data-tab="{{ Input::get('tab') }}" data-type="add_note">Add</button>            
            </div>

        </div>
    </div>  
</div>  


<div class="form-actions">
    <button class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
    <a href="#" class="popup btn"
        data-href="" 
        data-toggle="modal"
        data-target=".popup-modal" 
        data-title="Confirm Delete Permanently"
        data-body="Are you sure you want to delete permanently ID: <b>#123</b>?"><i class="fa fa-trash-o"></i> Move to trash</a>  
</div>

</form>





<div class="modal fade" id="products">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <a href="{{ route('admin.orders.add-custom', ['id' => $info->id]) }}" 
                    class="btn btn-primary pull-right btn-sm"><i class="fa fa-plus"></i> Add Custom Produdct</a>
                <h4 class="modal-title">Select Product</h4>
            </div>
            <div class="modal-body">
                <div class="load-products">
                    @include('admin.orders.products')                                
                </div>
                <div class="load-product"></div>
            </div>
        </div>
    </div>
</div>    

<div class="modal fade" id="order-detail">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Edit Order</h4>
            </div>
            <form method="post" action="{{ route('admin.orders.update-order') }}">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $info->id }}">
                <input type="hidden" name="token">

                <div class="modal-body">

                <h5>Product Name</h5>
                <textarea name="name" class="form-control" rows="4"></textarea>

                <h5>Unit Price</h5>
                <input type="text" name="price" class="form-control"  value="">

                <h5>Quantity</h5>
                <input type="text" name="quantity" class="form-control"  value="">
            

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-check"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="product-detail">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"></h4>
            </div>
            <form method="post" action="{{ route('admin.orders.update-product') }}">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $info->id }}">
                <input type="hidden" name="token">

                <div class="modal-body">

                <h5>Color Hex</h5>
                <input type="text" name="color_hex" class="form-control colorpicker" data-control="hue" value="">
                <h5>Color Title</h5>
                <input type="text" name="color_title" class="form-control"  value="">

                <h5>Sizes</h5>
                <div class="margin-top-10 mt-repeater">
                    <div data-repeater-list="artinfo">

                        <div data-repeater-item class="mt-repeater-item">
                            <div class="mt-repeater-row">
                            <table class="table-sizes">
                                <tr>
                                    <td width="10%">
                                        <input type="text" name="size" placeholder="Size" class="form-control"/>           
                                    </td>
                                    <td width="10%">
                                        <input type="text" name="quantity" placeholder="Quantity" class="form-control numeric text-right"/>            
                                    </td>
                                    <td width="1%">
                                        <a href="javascript:;" data-repeater-delete class=" mt-repeater-delete">
                                            <i class="fa fa-close"></i>
                                        </a>            
                                    </td>
                                </tr>
                            </table>
                            </div>
                        </div>

                    </div>
                    <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add">
                        <i class="fa fa-plus"></i> Add Size</a>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-check"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('style')
<style>
.n-c {
    border: 1px  solid #dcdcdc;
}
.order-notes {
    max-height: 600px;
    overflow: auto;
    padding-right: 10px    
}
.note-content {
    background: #eef1f5;
    position: relative; 
    padding: 10px;
    margin-bottom: 10px;
    font-size: 13px;
}
.note-content:after {
    content: '';
    position: absolute;
    bottom: -15px;
    left: 20px;
    border-width: 15px 15px 0 0;
    border-style: solid;
    border-color: #eef1f5 transparent;
}



.color-swatch {
    border: 2px solid #fff;
    cursor: pointer;
    display: inline-block;
    height: 30px;
    margin: 4px;
    outline: 1px solid #ccc !important;
    padding: 2px;
    width: 30px;
}   
.color-swatch:hover {
    border-radius: 20px;
    box-shadow: 0 0 1px 1px #000;
    outline: 0 !important;
}
.color-swatch.actived {
    border-radius: 20px;
    box-shadow: 0px 0px 10px 0 #000;
    outline: 0 !important;
}
</style>
@stop

@section('plugin_script') 
@stop

@section('script')
<script>
var token = $('[name="csrf-token"]').attr('content');

$('.order-notes').animate({scrollTop: $('.order-notes').get(0).scrollHeight}, 1);

$(document).on('click', '.edit-details', function(e){
    e.preventDefault();
    var target = $(this).data('target'); 

    $(target+'-details').hide();
    $(target+'-form').show();
});


$(document).on('click', '.change-product', function() {
    $('.load-product').hide();
    $('.load-products').show();
});
$(document).on('click', '.select-product', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url + '&order_id={{ $info->id }}', function(res) {
        $('.load-products').hide();
        $('#products .load-product').show().html(res);
        $('.color-swatch:first-child').click();
    })

})

$(document).on('click', '.add-garment', function(e){
    $('[name=add_garment]').val(1);
});

$(document).on('click', '.edit-product-details', function(e) {
    e.preventDefault();
    var val = $(this).data('edit');

    $('.mt-repeater-item').remove();
    
    $('[name=color_hex]').val(val.color_hex);
    $('[name=color_title]').val(val.color_title);
    $('.minicolors-swatch-color').css('background-color', val.color_hex);

    c=0;
    $.each(val.sizes, function(k, v){
        if( v ) {
            $('.mt-repeater-add').trigger('click'); 
            $('[name="artinfo['+c+'][size]"]').val(k);
            $('[name="artinfo['+c+'][quantity]"]').val(v);            
            c++;
        }
    });

    $('#product-detail .modal-title').html(val.name);
    $('#product-detail [name=token]').val(val.token);
    $('#product-detail').modal('show');
});

$(document).on('click', '.edit-order-details', function(e) {
    e.preventDefault();
    var val = $(this).data('edit');
    $('#order-detail [name=name]').val(val.name);
    $('#order-detail [name=price]').val(val.price);
    $('#order-detail [name=quantity]').val(val.qty);
    $('#order-detail [name=token]').val(val.token);

    $('#order-detail').modal('show');
})






$(document).on('click', '.btn-note', function(e){
    e.preventDefault();
    var note = $('#note').val(), id = $(this).data('id'), tab = $(this).data('tab'); 
    $.post('', { 'id':id, 'action' : 'add-note', 'note':note, 'tab':tab, '_token':token }, function(res) {
        $('.order-notes').append(res);
        $('.order-notes').animate({scrollTop: $('.order-notes').get(0).scrollHeight}, 2000);
        $('#note').val('');
    });
});

$(document).on('click', '.delete-note', function(e){
    e.preventDefault();
    var id = $(this).data('id'); 
    $(this).closest('.n-c').slideUp();
    $.post('', { 'id':id, 'action' : 'delete-note',  '_token':token });
});

$(document).on('click', '[name="shipping_method"]', function(){
    var fee = $(this).data('fee');
    $('[name="shipping_fee"]').val(fee);
    $('.shipping_fee').html(fee.toFixed(2));
    update_checkout();
});

function update_checkout() {
    var discount = $('[name=discount]').val(),
    shipping_fee = $('[name=shipping_charge]').val(),
    deposit = $('[name=deposit]').val() ? $('[name=deposit]').val() : 0,
    subtotal = $('.subtotal').data('val');

    var total = (parseFloat(subtotal) + parseFloat(shipping_fee)) - parseFloat(discount);
    var balance = total - parseFloat(deposit);
    console.log(deposit);
    $('.total').html(total.toFixed(2));
    $('.balance').html(balance.toFixed(2));
    $('[name=total]').val(total.toFixed(2));
}


$(document).on('click', '.color-swatch', function() {
    var src = $(this).data('image'), 
        title = $(this).data('title'), 
        hex = $(this).data('hex'), 
        type = $(this).data('type'), 
        hex = $(this).data('hex'), 
        title = $(this).data('title'), 
        index = $(this).data('index');

    $('.color-swatch').removeClass('actived');
    $(this).addClass('actived');

    $('.img-product').attr('src', src);
    $('.color-title').html(title);
    $('[name="type"]').val(type);
    $('[name="image"]').val(src);
    $('[name="color_index"]').val(index);
    $('[name="color_hex"]').val(hex);
    $('[name="color_title"]').val(title);
    $('.img-preview').attr('src', src);
    $('.btn-img-preview').attr('href', src);

    get_quote();
});

function get_quote() {
    formElement = document.querySelector("#form-product");
    $.ajax({
        url: $('#form-product').data('url'), 
        type: "POST",           
        data: new FormData( formElement ), 
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,
        cache: false,             
        processData:false,     
        success: function(data) 
        { 
            val = JSON.parse(data);

            $.each( val.msg, function( key, value ) {
              $('.msg-'+key).html(value);
            });   

            $('.unit-price').html(val.unit_price);
            $('.total-price').html(val.total_price);

            $('[name=unit_price]').val(val._unit_price);
            $('[name=total_price]').val(val._total_price);

        }
    });
}
</script>
@stop
