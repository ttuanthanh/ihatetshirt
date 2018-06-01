
<div class="portlet light bordered">


	<div class="portlet-body form">

	    <div>
	    	<?php $detail = json_decode($info->post_content); ?>

	    	<div class="row">
		    	<div class="col-md-6">

		    		<h5 class="sbold">
		    			Billing Details 
		    		</h5>

					<div class="margin-top-20 billing-details">
						<a href="" class="pull-right edit-details" data-target=".billing"><i class="icon icon-pencil"></i></a>

						<h5 class="sbold text-muted">Address:</h5>
						<p>{{ ucwords($info->billing_firstname.' '.$info->billing_lastname) }}<br>
						{{ $info->billing_street_address_1 }}
						{{ $info->billing_street_address_2 }}
						{{ $info->billing_city }}
						{{ $info->billing_state }}
						{{ $info->billing_zip_code }}
						@if( $info->billing_country )
						{{ countries($info->billing_country) }}
						@endif
						</p>

						@if( $info->billing_company )
						<h5 class="sbold text-muted">Company:</h5>
						<p>{{ $info->billing_company }}</p>
						@endif

						@if( $info->billing_email )
						<h5 class="sbold text-muted">Email address:</h5>
						<p>{{ $info->billing_email }}</p>
						@endif

						@if( $info->billing_telephone )
						<h5 class="sbold text-muted">Phone:</h5>
						<p>{{ $info->billing_telephone }}</p>
						@endif
					</div>

					<!-- BEGIN BILLING DETAILS -->
		    		<div class="billing-form" style="display:none;">
			    		<div class="row">
			    			<div class="col-md-6">
				    			<label>First Name</label>
				    			<input type="text" name="billing_firstname" class="form-control" value="{{ $info->billing_firstname }}">				    				
			    			</div>
			    			<div class="col-md-6">
				    			<label>Last Name</label>
				    			<input type="text" name="billing_lastname" class="form-control" value="{{ $info->billing_lastname }}">				    				
			    			</div>
			    		</div>
			    		<div class="row margin-top-10">
			    			<div class="col-md-12">
				    			<label>Company</label>
				    			<input type="text" name="billing_company" class="form-control" value="{{ $info->billing_company }}">				    				
			    			</div>
			    		</div>
			    		<div class="row margin-top-10">
			    			<div class="col-md-6">
				    			<label>Address line 1</label>
				    			<input type="text" name="billing_street_address_1" class="form-control" value="{{ $info->billing_street_address_1 }}">				    				
			    			</div>
			    			<div class="col-md-6">
				    			<label>Address line 2</label>
				    			<input type="text" name="billing_street_address_2" class="form-control" value="{{ $info->billing_street_address_2 }}">				    				
			    			</div>
			    		</div>
			    		<div class="row margin-top-10">
			    			<div class="col-md-6">
				    			<label>City</label>
				    			<input type="text" name="billing_city" class="form-control" value="{{ $info->billing_city }}">				    				
			    			</div>
			    			<div class="col-md-6">
				    			<label>State</label>
				    			<input type="text" name="billing_state" class="form-control" value="{{ $info->billing_state }}">				    				
			    			</div>
			    		</div>
			    		<div class="row margin-top-10">
			    			<div class="col-md-6">
				    			<label>Postcode / ZIP</label>
				    			<input type="text" name="billing_zip_code" class="form-control" value="{{ $info->billing_zip_code }}">				    				
			    			</div>
			    			<div class="col-md-6">
				    			<label>Country</label>
				    			{{ Form::select('billing_country', ['' => 'Select Country'] + countries(), $info->billing_country, ['class' => 'form-control select2'] ) }}			    				
			    			</div>
			    		</div>
    					<div class="row margin-top-10">
			    			<div class="col-md-6">
				    			<label>Email Address</label>
				    			<input type="text" name="billing_email" class="form-control" value="{{ $info->billing_email }}">				    				
			    			</div>
			    			<div class="col-md-6">
				    			<label>Phone</label>
				    			<input type="text" name="billing_telephone" class="form-control" value="{{ $info->billing_telephone }}">				    	
			    			</div>
			    		</div>


			    	</div>
		    	</div>
		    	<!-- END BILLING DETAILS -->

		    	<div class="col-md-6">
		    		<h5 class="sbold">Shipping Details</h5>

					<div class="margin-top-20 shipping-details">
						
						<a href="" class="pull-right edit-details" data-target=".shipping"><i class="icon icon-pencil"></i></a>

						<h5 class="sbold text-muted">Address:</h5>
						<p>{{ ucwords($info->shipping_firstname.' '.$info->shipping_lastname) }}<br>
						{{ $info->shipping_street_address_1 }}
						{{ $info->shipping_street_address_2 }}
						{{ $info->shipping_city }}
						{{ $info->shipping_state }}
						{{ $info->shipping_zip_code }}
						@if($info->shipping_country)
						{{ countries($info->shipping_country) }}
						@endif
						</p>

						@if( $info->shipping_company )
						<h5 class="sbold text-muted">Company:</h5>
						<p>{{ $info->shipping_company }}</p>
						@endif

						@if( $info->shipping_email )
						<h5 class="sbold text-muted">Email address:</h5>
						<p>{{ $info->shipping_email }}</p>
						@endif

						@if( $info->shipping_telephone )
						<h5 class="sbold text-muted">Phone:</h5>
						<p>{{ $info->shipping_telephone }}</p>
						@endif
					</div>

					<!-- BEGIN SHIPPING DETAILS -->
		    		<div class="shipping-form" style="display:none;">
			
						    		<div class="row">
			    			<div class="col-md-6">
				    			<label>First Name</label>
				    			<input type="text" name="shipping_firstname" class="form-control" value="{{ $info->shipping_firstname }}">				    				
			    			</div>
			    			<div class="col-md-6">
				    			<label>Last Name</label>
				    			<input type="text" name="shipping_lastname" class="form-control" value="{{ $info->shipping_lastname }}">				    				
			    			</div>
			    		</div>
			    		<div class="row margin-top-10">
			    			<div class="col-md-12">
				    			<label>Company</label>
				    			<input type="text" name="shipping_company" class="form-control" value="{{ $info->shipping_company }}">				    				
			    			</div>
			    		</div>
			    		<div class="row margin-top-10">
			    			<div class="col-md-6">
				    			<label>Address line 1</label>
				    			<input type="text" name="shipping_street_address_1" class="form-control" value="{{ $info->shipping_street_address_1 }}">				    				
			    			</div>
			    			<div class="col-md-6">
				    			<label>Address line 2</label>
				    			<input type="text" name="shipping_street_address_2" class="form-control" value="{{ $info->shipping_street_address_2 }}">				    				
			    			</div>
			    		</div>
			    		<div class="row margin-top-10">
			    			<div class="col-md-6">
				    			<label>City</label>
				    			<input type="text" name="shipping_city" class="form-control" value="{{ $info->shipping_city }}">				    				
			    			</div>
			    			<div class="col-md-6">
				    			<label>State</label>
				    			<input type="text" name="shipping_state" class="form-control" value="{{ $info->shipping_state }}">				    				
			    			</div>
			    		</div>
			    		<div class="row margin-top-10">
			    			<div class="col-md-6">
				    			<label>Postcode / ZIP</label>
				    			<input type="text" name="shipping_zip_code" class="form-control" value="{{ $info->shipping_zip_code }}">				    				
			    			</div>
			    			<div class="col-md-6">
				    			<label>Country</label>
				    			{{ Form::select('shipping_country', ['' => 'Select Country'] + countries(), $info->shipping_country, ['class' => 'form-control select2'] ) }}			    				
			    			</div>
			    		</div>
    					<div class="row margin-top-10">
			    			<div class="col-md-6">
				    			<label>Email Address</label>
				    			<input type="text" name="shipping_email" class="form-control" value="{{ $info->shipping_email }}">				    				
			    			</div>
			    			<div class="col-md-6">
				    			<label>Phone</label>
				    			<input type="text" name="shipping_telephone" class="form-control" value="{{ $info->shipping_telephone }}">				    	
			    			</div>
			    		</div>

		    		</div>
		    		<!-- END SHIPPING DETAILS -->



		    	</div>
    		
	    	</div>

	    </div>

	</div>

</div>

<div class="portlet light bordered">
	<a href="#products" class="btn btn-primary btn-sm pull-right margin-bottom-10" data-toggle="modal"><i class="fa fa-plus"></i> Add New</a>

	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th width="70"></th>
				<th width="300">Product</th>
				<th class="text-right">Price</th>
				<th class="text-right">QTY</th>
				<th class="text-right">Total</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($detail->orders as $o_token => $order)
			<tr>
	            <td>
	                <a href="{{ has_image($order->image) }}" class="btn-img-preview" data-title="{{ $order->name }}">
	                    <img src="{{ has_image($order->image) }}" class="img-responsive img-thumb"> 
	                </a>
	            </td>
	            <td>
	            	{{ $order->name }}
	            	<div class="margin-top-10">
	            	<a href="#" class="btn btn-default btn-xs edit-order-details" 
                data-edit="{{ json_encode(['name' => $order->name, 'price' => $order->unit_price, 'qty' => $order->quantity, 'token' => $o_token]) }}">Edit</a>
	            		
	            	</div>
	            </td>
	            <td class="text-right">{{ amount_formatted($order->unit_price) }}</td>
	            <td class="text-right">{{ number_format($order->quantity) }}</td>
	            <td class="text-right">{{ amount_formatted($order->total_price) }}</td>
	            <td>
	            	<a href="{{ route('admin.orders.remove-order', ['id' => $info->id, 'token' => $o_token]) }}" class="text-danger"><i class="fa fa-trash-o"></i></a>
	            </td>
			</tr>
			@endforeach

		</tbody>
	</table>

	<div class="edit-checkout-form" style="display:none;">
		<input type="hidden" name="shipping_charge" value="{{ $info->shipping_charge }}">
		<input type="hidden" name="total" value="{{ number_format($info->total) }}">

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<div class="col-md-12">
					<label class="sbold">Payment Method</label>
					{{ Form::select('payment_method', payment_method(), @$info->payment_method, ['class' => 'form-control select2']) }}
					</div>
				</div>	
				<div class="form-group">
					<div class="col-md-12">
						<label class="sbold">Shipping Method</label>

						<div class="mt-radio-list">
							@foreach($shipping_methods as $shipping_method)
						    <label class="mt-radio mt-radio-outline">
						    	<?php 
				  	 				$shipping_charge = ($info->subtotal - $info->discount) * ($shipping_method->post_name / 100);
						    	?>
						        <input type="radio" name="shipping_method" value="{{ $shipping_method->id }}" data-fee="{{ $shipping_charge  }}" {{ checked($shipping_method->id, $info->shipping_method) }}> {{ $shipping_method->post_title }}
						        <div class="text-muted">{{ $shipping_method->post_content }}</div>
						        {{ $shipping_method->post_name==0? 'FREE' : number_format($shipping_method->post_name).'%' }} 
						        <span></span>
						    </label>
							@endforeach
						</div>
						
					</div>
				</div>
			

			</div>
			<div class="col-md-6">

				<div class="form-group">
					<div class="col-md-12">
					<label class="sbold">Sub Total :</label><br>
					<span class="subtotal" data-val="{{ number_format($info->subtotal, 2) }}">{{ amount_formatted($info->subtotal) }}</span>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
					<label class="sbold">Shipping Fee :</label><br>
					<span class="shipping_fee">
						{{ number_format($info->shipping_charge, 2) }}
					</span>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
					<label class="sbold">Discount :</label>
					<input type="number" name="discount" class="form-control text-right numeric" value="{{ $info->discount }}" onkeyup="update_checkout();">
					</div>
				</div>		

				<div class="form-group">
					<div class="col-md-12">
					<label class="sbold">Total :</label><br>
					{{ currency_symbol(App\Setting::get_setting('currency')) }} <span class="total">{{ number_format($info->total, 2) }}</span>
					</div>
				</div>

				@if( $info->payment_fee )
				<div class="form-group">
					<div class="col-md-12">
					<label class="sbold">Payment Fee:</label><br>
					<span class="balance">{{ amount_formatted($info->payment_fee) }}</span>
					</div>
				</div>
				@else
				<div class="form-group">
					<div class="col-md-12">
					<label class="sbold">Desposit :</label>
					<input type="number" name="deposit" class="form-control text-right numeric" value="{{ $info->deposit }}" onkeyup="update_checkout();">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
					<label class="sbold">Balance :</label><br>
					{{ currency_symbol(App\Setting::get_setting('currency')) }} <span class="balance">{{ number_format($info->total - $info->deposit, 2) }}</span>
					</div>
				</div>				
				@endif

			</div>
		</div>

	</div>

	<div class="edit-checkout-details">

	<div class="row">
		<div class="col-md-6">
			<p><span class="text-muted">Payment Method:</span><br> 
				@if( @$info->payment_method )
				<b class="text-primary">{{ payment_method(@$info->payment_method) }}</b>
				@else
				Not set
				@endif
			</p> 				
			<p><span class="text-muted">Payment Fee:</span><br> 
				<b class="text-primary">{{ amount_formatted($info->payment_fee) }}</b>
			</p> 			
			
			<p class="no-margin"><span class="text-muted">Shipping Method:</span><br> 
				@if($info->shipping_method)
				<b class="text-primary">{{ @$post->find($info->shipping_method)->post_title }}</b>
				@else
				Not set
				@endif
			</p>    
		</div>
		<div class="col-md-6">
			
			<a href="" class="edit-details pull-right" data-target=".edit-checkout"><i class="icon icon-pencil"></i></a>

			<table class="table table-borderless">
				<tr class="sbold">
					<td class="text-right">Sub Total: 
					</td>
					<td class="text-right" width="100">{{ amount_formatted($info->subtotal) }}</td>
				</tr>
				<tr>
					<td class="text-right">
					Shipment Fee: 
					</td>
					<td class="text-right">{{ amount_formatted($info->shipping_charge) }}</td>
				</tr>
				<tr>
					<td class="text-right">Discount:</td>
					<td class="text-right">{{ amount_formatted($info->discount) }}</td>
				</tr>
				<tr class="sbold">
					<td class="text-right">Total: </td>
					<td class="text-right">{{ amount_formatted($info->total) }}</td>
				</tr>
				@if( $info->payment_fee )
				<tr class="sbold">
					<td class="text-right">Payment Fee: </td>
					<td class="text-right">{{ amount_formatted($info->payment_fee) }}</td>
				</tr>
				@else
				<tr>
					<td class="text-right">Desposit: </td>
					<td class="text-right">{{ amount_formatted($info->deposit) }}</td>
				</tr>
				<tr class="sbold">
					<td class="text-right">Balance: </td>
					<td class="text-right">{{ amount_formatted($info->total - $info->deposit) }}</td>
				</tr>
				@endif
			</table>					
		</div>
	</div>


	</div>

</div>