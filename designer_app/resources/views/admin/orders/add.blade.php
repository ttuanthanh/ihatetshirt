@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }} <small>

@if( @$info )
    Product Details
@else
    Select Product
@endif

</small> 
<div class="pull-right">
    @if( @$info )
    <a href="{{ route('admin.orders.add') }}" class="btn"> Change Product</a>
    @else
    <a href="{{ route('admin.orders.add-custom') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Custom Product</a>    
    @endif
 
    <a href="{{ URL::route($view.'.index') }}" class="btn"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>    
</div>
</h1>
<!-- END PAGE TITLE-->

@include('notification')

@if( @$info )
<form action="{{ route('admin.orders.add', query_vars()) }}" id="form-product" method="post" data-url="{{ route('frontend.get-quote') }}">
{{ csrf_field() }}
<input type="hidden" name="pid" value="{{ $info->id }}">
<input type="hidden" name="type" value="{{ Input::old('type') }}">
<input type="hidden" name="image" value="{{ $image = Input::old('image') }}">
<input type="hidden" name="color_index" value="{{ $color_index = Input::old('color_index') }}">
<input type="hidden" name="color_hex" value="{{ Input::old('color_hex') }}">
<input type="hidden" name="color_title" value="{{ Input::old('color_title') }}">
<input type="hidden" name="unit_price" value="{{ $unit_price = Input::old('unit_price') }}">
<input type="hidden" name="total_price" value="{{ $total_price = Input::old('total_price') }}">

<div class="row">
    <div class="col-md-7">
        <div class="portlet light bordered">


        <div class="row">
            <div class="col-md-2 col-sm-4 col-xs-4">

                <a href="{{ $image ? has_image($image) : $info->image }}" class="btn-img-preview" data-title="{{ $info->post_title }}">
                    <img src="{{ $image ? has_image($image) : str_replace('large', 'thumb', $info->image) }}" class="fullwidth img-preview"> 
                </a>
                
            </div>
            <div class="col-md-10 col-sm-8 col-xs-8">
                <h4>{{ $info->post_title }}</h4> 
                {{ amount_formatted(@$info->starting_price) }}<br>
                <small class="text-muted uppercase">{{ @$info->sku }}</small><br>
            </div>
        </div>

        <hr>        


     <h5 class="sbold">Available color</h5>


        @if( $info->product_design )

        <h5>Preview available colors : <b class="color-title">{{ Input::old('color_title') }}</b></h5>

        @if(  $colors = json_decode($info->product_design, true) )
            <p>{{ count($colors) }} Color{{ is_plural($colors) }}</p>
        @endif


        <div class="color-swatches m-t-20">
        @foreach( json_decode($info->product_design) as $c_k => $color )
            <a href="javascript:void(0);" class="color-swatch {{ Input::old('color_index')==$c_k ? 'actived' : '' }}" 
            data-toggle="tooltip" 
            data-index="{{ $c_k }}"
            data-placement="top" 
            style="background-color: {{ $color->color }};" 
            data-type="{{ $color->color == '#ffffff' ? 0 : 1 }}"
            data-hex="{{ $color->color }}"
            data-src="{{ has_image($color->image[0]->url) }}" 
            data-image="{{ $color->image[0]->url }}" 
            data-title="{{ $color->color_title }}"></a>
        @endforeach 
        </div>
        @else
        <p class="alert alert-info">No available colors.</p>
        @endif

            <hr>

            @if($info->size)
            <h5><b>Enter Sizes to calculate your price</b> :</h5>
            <span>( Minimum order is 6 pieces )</span>

            <?php $s = Input::old('size'); ?>

            <div class="row margin-top-20">
                @foreach(json_decode($info->size) as $s_k => $size)
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                    {{ $size->name }}
                    <input type="number" name="size[]" class="form-control numeric" 
                    onchange="get_quote();" 
                    onkeyup="get_quote();"
                    min="0" 
                    value="{{ $s[$s_k] ? $s[$s_k] : ($s_k==0?6:'') }}">              
                </div>
                @endforeach 
            </div>
            <p class="msg-quantity text-danger error-msg"></p>
            @else
            <p class="alert alert-info">No available sizes.</p>
            @endif

            <hr>

            <h5 class="sbold">Decoration : </h5>
            <div class="row m-t-20">
                <div class="col-md-6">
                    <label>Front Colors:</label>
                    {{ Form::select('front_color', shirt_colors(), Input::old('front_color'), ['class' => 'form-control', 'onchange' => 'get_quote();']) }}        
                </div>
                <div class="col-md-6">
                    <label>Back Colors:</label>
                    {{ Form::select('back_color', shirt_colors(), Input::old('back_color'), ['class' => 'form-control', 'onchange' => 'get_quote();']) }}     
                </div>
            </div>

    
        </div>

    </div>
    <div class="col-md-5">

        <div class="portlet light bordered">
            <h5 class="sbold">Customer Details</h5>
            <hr>
            
            <p>Search from <b>Existing Customer</b> to fill form below</p>

            <div class="row">
                <div class="col-md-12">
                    {{ Form::select('customer', ['' => 'Select Customer'] + $customers, Input::old('customer'), ['class' => 'form-control select2']) }}                             
                </div>
            </div>

            <hr>

            <div class="billing-address">
                <div class="row">
                    <div class="col-md-6">
                        <label>First Name</label>
                        <input type="text" name="billing_firstname" class="form-control" value="{{ Input::old('billing_firstname', $info->billing_firstname) }}">    
                        {!! $errors->first('billing_firstname','<span class="help-block text-danger">:message</span>') !!}                                
                    </div>
                    <div class="col-md-6">
                        <label>Last Name</label>
                        <input type="text" name="billing_lastname" class="form-control" value="{{ Input::old('billing_lastname', $info->billing_lastname) }}">   
                        {!! $errors->first('billing_lastname','<span class="help-block text-danger">:message</span>') !!}                                 
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-12">
                        <label>Email Address</label>
                        <input type="email" name="billing_email" class="form-control" value="{{ Input::old('billing_email', $info->billing_email) }}">     
                        {!! $errors->first('billing_email','<span class="help-block text-danger">:message</span>') !!}                               
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-6">
                        <label>Phone</label>
                        <input type="text" name="billing_telephone" class="form-control" value="{{ Input::old('billing_telephone', $info->billing_telephone) }}">    
                        {!! $errors->first('billing_telephone','<span class="help-block text-danger">:message</span>') !!}                                
                    </div>
                    <div class="col-md-6">
                        <label>Company</label>
                        <input type="text" name="billing_company" class="form-control" value="{{ Input::old('billing_company', $info->billing_company) }}"> 
                        {!! $errors->first('billing_company','<span class="help-block text-danger">:message</span>') !!}                                   
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-6">
                        <label>Address line 1</label>
                        <input type="text" name="billing_street_address_1" class="form-control" value="{{ Input::old('billing_street_address_1', $info->billing_street_address_1) }}"> 
                        {!! $errors->first('billing_street_address_1','<span class="help-block text-danger">:message</span>') !!}                                   
                    </div>
                    <div class="col-md-6">
                        <label>Address line 2</label>
                        <input type="text" name="billing_street_address_2" class="form-control" value="{{ Input::old('billing_street_address_2', $info->billing_street_address_2) }}">   
                        {!! $errors->first('billing_street_address_2','<span class="help-block text-danger">:message</span>') !!}                                 
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-6">
                        <label>City</label>
                        <input type="text" name="billing_city" class="form-control" value="{{ Input::old('billing_city', $info->billing_city) }}">   
                        {!! $errors->first('billing_city','<span class="help-block text-danger">:message</span>') !!}                                 
                    </div>
                    <div class="col-md-6">
                        <label>State</label>
                        <input type="text" name="billing_state" class="form-control" value="{{ Input::old('billing_state', $info->billing_state) }}">  
                        {!! $errors->first('billing_state','<span class="help-block text-danger">:message</span>') !!}                                  
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-6">
                        <label>Postcode / ZIP</label>
                        <input type="text" name="billing_zip_code" class="form-control" value="{{ Input::old('billing_zip_code', $info->billing_zip_code) }}"> 
                        {!! $errors->first('billing_zip_code','<span class="help-block text-danger">:message</span>') !!}                                   
                    </div>
                    <div class="col-md-6">
                        <label>Country</label>
                        {{ Form::select('billing_country', ['' => 'Select Country'] + countries(), Input::old('billing_country', $info->billing_country), ['class' => 'form-control select2']) }}            
                        {!! $errors->first('billing_country','<span class="help-block text-danger">:message</span>') !!}                
                    </div>
                </div>

                <hr>

                <h4>Shipping Method</h4>

                <table class="table table-hover">
                    @foreach($shippings as $shipping)
                    <tr>
                        <td width="1">

                            <div class="mt-radio-list">
                                <label class="mt-radio mt-radio-outline">
                            <input type="radio" id="{{ $shipping->id }}" name="shipping_method" value="{{ $shipping->id }}" {{ checked($default_shipping, $shipping->id) }}>
                               <span></span>
                    </label> 

                        </div>
                        </td>
                        <td>
                        
                             <label class="shipping" for="{{ $shipping->id }}">
                            
                            <div>{{ $shipping->post_title }}</div>
                            <span class="text-muted">{{ $shipping->post_content }}</span><br>
                                <span>{{ $shipping->post_name==0 ? 'FREE' : amount_formatted($shipping->post_name) }}</span>
                           
                        </label>

                        </td>
                    </tr>                             
                    @endforeach
                </table>

                <h4>Paypal Method</h4>

                <div class="mt-radio-list">
                @foreach($payments as $payment_k => $payment_v)
                @if( $payment_k != 'default' && @$payment_v->status == 1)
                    <label class="mt-radio mt-radio-outline">
                   <input type="radio" name="payment_method" value="{{ $payment_k }}" {{ checked(Input::old('payment_method', $payments->default), $payment_k) }}>
                    {{ $payment_v->value }}
                        <span></span>
                    </label> 
                @endif
                @endforeach
                </div>

                <!-- START error message -->
                {!! $errors->first('payment','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->   


            </div>


        </div>

    </div>  
</div>  


<div class="form-actions">
    <div class="row">
        <div class="col-xs-5">
            <button class="btn btn-primary"><i class="fa fa-check"></i> Add to Order</button>    
        </div>
        <div class="col-xs-3">
            <label>Unit price:</label>
            <h5 class="no-margin sbold"> <span class="unit-price">{{ amount_formatted($unit_price) }}</span></h5>
        </div>
        <div class="col-xs-3">
            <label>Total price:</label>
            <h5 class="no-margin sbold"> <span class="total-price">{{ amount_formatted($total_price) }}</span></h5>
        </div>
    </div>
</div>

</form>

    @else
    <div class="portlet light bordered">

    @include('admin.orders.products')

    </div>
    @endif    

@endsection

@section('style')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

<style>
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
table.dataTable.no-footer {
    border-bottom-color: #e7ecf1;   
}   
</style>
@stop

@section('plugin_script') 
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
@stop

@section('script')
<script>


$(document).on('change', '[name=customer]', function() {
    $('.billing-address .form-control').val('').trigger('change');
    $('.help-block').html('');
    var id = $(this).val();
    if( id ) {
    $.get('?id='+id, function(res){
        var val = JSON.parse(res);
        $.each(val, function(k, v){
            $('[name='+k+']').val(v).trigger('change');
        });
    });        
    }
}); 

$(document).on('click', '.color-swatch', function() {
    var image = $(this).data('image')
        src = $(this).data('src'), 
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
    $('[name="image"]').val(image);
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

@if( ! @$color_index )
$('.color-swatch:first-child').click();
@endif


</script>
@stop
