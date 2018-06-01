@extends('templates.fullwidth')

@section('content')

<div class="section featured-designs">
    <div class="container">
        @include('notification')

        @if( $cart ) 
		<form method="post" id="checkout" data-update-url="{{ route('frontend.checkout.update') }}">
		{{ csrf_field() }}

        <div class="row">
            <div class="col-md-8">
                <div class="form-bg-1">

                	<div class="row">
                		<div class="col-md-6">
                			@include('frontend.checkout.billing')
                		</div>
                		<div class="col-md-6 border-left">
                			@include('frontend.checkout.shipping')
                		</div>
                	</div>


                </div>
            </div>
            <div class="col-md-4">
                <div class="form-bg-1">
                    <div class="checkout-overlay">
                        <div class="loading"><img src="{{ asset('assets/uploads/loaders/4.gif') }}"> Updating ...</div>
                    </div>
                    <h5>Your Order</h5>

                    <table class="table  table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-right" width="80">Price</th>
                                <th class="text-right" width="80">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $subtotal=0; ?>
                            @foreach($cart['orders'] as $order)
                            <tr>
                                <td><img src="{{ asset($order['image']) }}" width="30"> 
                                    <a href="{{ route('frontend.designer.index', ['reload' => @$order['token']]) }}">{{ $order['name'] }}</a>
                                	 x <strong>{{ $order['quantity'] }}</strong></td>
                                <td class="text-right">{{ amount_formatted($order['unit_price']) }}</td>
                                <td class="text-right">{{ amount_formatted($order['total_price']) }}</td>
                            </tr>
                            <?php $subtotal += $order['total_price']; ?>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><b>Subtotal</b></td>
                                <td class="text-right" colspan="2"><b>{{ amount_formatted($subtotal) }}</b></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label><b>Shipping</b></label>
                         			<table class="table-shipping">
                                        @foreach($shippings as $shipping)
                                       	<tr>
                                       		<td>
                                       			<input type="radio" id="{{ $shipping->id }}" name="shipping" value="{{ $shipping->id }}" {{ checked($default_shipping, $shipping->id) }}>
                                       		</td>
                                       		<td>
                                       		
                                       		     <label class="shipping" for="{{ $shipping->id }}">
                                                
                                                <b class="text-muted">{{ $shipping->post_title }}</b><br>
                                                <span class="text-muted">{{ $shipping->post_content }}</span><br>
                                                <!--<span>{{ $shipping->post_name==0 ? 'FREE' : $shipping->post_name.'%' }}</span>-->
                                                <span>{{ date("D, M j", strtotime("$shipping->ship_day weekdays"))  }}</span>
                                               
                                            </label>

                                       		</td>
                                       	</tr>                             
                                        @endforeach 
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">
                                    <div class="form-group">
                                        <input type="" name="coupon" placeholder="Coupon Code" value="{{ @$cart['coupon_code'] }}">
                                        <button type="button" class="update-discount">Apply Coupon</button>
                                        <p class="msg-coupon"></p>
                                        <a href="#" class="remove-coupon" style="{{ @$cart['coupon_code'] ? '' : 'display:none;' }}">Remove Coupon</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Discount</b></td>
                                <td class="text-right" colspan="2">
                                    <b class="text-danger">- <span class="discount">{{ amount_formatted(@$cart['discount']) }}</span></b>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Rush service</b></td>
                                <td class="text-right" colspan="2">
                                    <span class="shipping-fee">{{ amount_formatted(@$cart['shipping_charge']) }}</span>
                                </td>
                            </tr>

                            <tr>
                                <td><b>Total</b></td>
                                <td class="text-right" colspan="2">
                                    <h5><b class="total">{{ amount_formatted(@$cart['total']) }}</b></h5>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="form-group">
                        @foreach($payments as $payment_k => $payment_v)
                        @if( $payment_k != 'default' && @$payment_v->status == 1)
                        <div>
                            <label>
                            <input type="radio" name="payment" value="{{ $payment_k }}" {{ checked(Input::old('payment', $payments->default), $payment_k) }}>
                            {{ $payment_v->value }}
                            </label>
                        </div>
                        @endif
                        @endforeach
                        <!-- START error message -->
                        {!! $errors->first('payment','<span class="help-block text-danger">:message</span>') !!}
                        <!-- END error message -->     

                        <div style="{{ Input::old('payment', $payments->default)=='credit_card' ? '' : 'display: none;' }}" class="credit-card-form">
                            <div class="form-group">
                                <label><b>Card Holder</b></label>
                                <input type="text" name="credit_card_holder" class="form-control" value="{{ Input::old('credit_card_holder') }}">
                                {!! $errors->first('credit_card_holder','<span class="help-block text-danger">:message</span>') !!}
                            </div>

                            <div class="row form-group">
                                <div class="col-md-7">
                                <label><b>Card Number</b></label>
                                <input type="number" name="credit_card_number" class="form-control" value="{{ Input::old('credit_card_number') }}">
                                {!! $errors->first('credit_card_number','<span class="help-block text-danger">:message</span>') !!}
                            </div>
                             <div class="col-md-5">
                                <label><b>CVV</b></label>
                                <input type="password" name="credit_card_code" class="form-control" value="{{ Input::old('credit_card_code') }}" maxlength="3">
                                {!! $errors->first('credit_card_code','<span class="help-block text-danger">:message</span>') !!}
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-7">
                                    <label><b>Month</b></label>
                                    {{ Form::select('credit_card_month', getMonths(), Input::old('credit_card_month'), ['class' => 'form-control']) }}
                                    <!-- START error message -->
                                    {!! $errors->first('credit_card_month','<span class="help-block text-danger">:message</span>') !!}
                                    <!-- END error message -->
                                </div>
                                <div class="col-md-5">
                                    <label><b>Year</b></label>
                                    {{ Form::select('credit_card_year', get_cc_years(), Input::old('credit_card_year'), ['class' => 'form-control']) }}
                                    <!-- START error message -->
                                    {!! $errors->first('credit_card_year','<span class="help-block text-danger">:message</span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>                      
                        </div>
                    

                    </div>
                    <div class="form-group">				
                        <button type="submit" class="btn btn-primary btn-block">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
		</form>
		@else

		<div class="text-center">
			<h6>Your shopping cart is empty. </h6><br>
			<a href="{{ route('frontend.products') }}" class="btn btn-primary">Continue Shopping</a>
		</div>

		@endif

    </div>
</div>

@endsection

@section('style')
<style>
.container { font-size: 14px; }
.checkout-overlay .loading {
    color: #353333;
    margin: 50% auto;
    text-align: center;
    background: #fff;
    width: 160px;
    padding: 15px;
    font-size: 1.2em;
    border-radius: 5px;
}
.checkout-overlay { 
    width: 100%;
    background: #000000c9;
    position: absolute;
    top: 0;
    bottom: 0;
	left: 0;
    z-index: 1;
    display: none;
}
.required {
	color: red;
}
.form-control {
	font-size: 1.15em;
}
.form-group {
    font-size: .9em;
}
.border-left {
    border-left: 1px solid #d9d9d9;	
}
.table-shipping label {
    cursor: pointer;
    width: 100%;
}
</style>
@stop

@section('plugin_script') 
@stop

@section('script')
<script>
$(document).on('click', '.update-discount, [name=shipping]', function(){
	update_checkout($(this));
});

$(document).on('click', '.remove-coupon', function(e){
	e.preventDefault();	

	$('[name="coupon"]').val('')
    $('.msg-coupon').html('<b class="text-danger">Coupon code has been empty.</b>');   
    $(this).hide();

	update_checkout($(this));
});

$(document).on('click', '[name="same_as_billing"]', function(){
	$('.shipping-info').slideToggle();
	$('.shipping-info .help-block').html('');
});




function update_checkout($this) {
	$('.checkout-overlay').show();
    formElement = document.querySelector("#checkout");
    $.ajax({
        url: $('#checkout').data('update-url'), 
        type: "POST",           
        data: new FormData( formElement ), 
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,
        cache: false,             
        processData:false,     
        success: function(data) 
        { 
        	val = JSON.parse(data);
        	console.log(val);
        	$('.discount').html(val._discount);
        	$('.total').html(val._total);
            $('.shipping-fee').html(val._shipping_charge);

        	if( $this.hasClass('update-discount') ) {
	        	if( val.coupon_code ) {
		        	$('.msg-coupon').html('<b class="text-success">Coupon code applied successfully.</b>');
					$('.remove-coupon').show();
	        	} else {
	        		if( $('[name="coupon"]').val() ) {
			        	$('.msg-coupon').html('<b class="text-danger">Coupon code not found.</b>');        		
						$('.remove-coupon').hide();
	        		}	        		
	        	}        		

        	}
			$('.checkout-overlay').hide();
        }
    });
}

$(document).on('click', '[name="payment"]', function() {
    var val = $(this).val();
     $('.credit-card-form').slideUp();
    if(val=='credit_card') {
        $('.credit-card-form').slideDown();
    }
});

</script>
@stop
