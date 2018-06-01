
<div class="portlet light bordered">

	<div class="portlet-body form">

	    <div>

	    	<div class="row">
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

						<h5 class="sbold text-muted">Email address:</h5>
						<p><a href="">{{ $info->shipping_email }}</a></p>

						<h5 class="sbold text-muted">Phone:</h5>
						<p>{{ $info->shipping_telephone }}</p>
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
				    			{{ Form::select('shipping_country', countries(), $info->shipping_country, ['class' => 'form-control select2'] ) }}			    				
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

				<div class="col-md-6">

					<label class="text-muted"><i class="fa fa-calendar"></i> Order date:</label> 
					<p>{{ date('F d, Y', strtotime($info->order_date)) }} @ {{ $info->order_time }}</p>

					<label class="text-muted"><i class="fa fa-truck"></i> Shipping:</label> <p>{{ @$post->find($info->shipping)->post_title }}</p>
					<label class="text-muted"><i class="fa fa-calendar"></i> Date require:</label> <p>
					<?php 
						$date_required = strtotime($info->shipping_date) - strtotime($info->order_date);
						echo  date('z', $date_required)." business days";
					?>
					</p>		

					<div class="row">
						<div class="col-md-6">
							<h5 class="sbold">Shipping Date</h5>
							<div class="input-icon">
								<i class="fa fa-calendar"></i>
								<input type="text" class="form-control datepicker" name="shipping_date" value="{{ date_formatted_b($info->shipping_date) }}"> 
							</div>		
						</div>
						<div class="col-md-6">
							<h5 class="sbold">Shipping Time</h5>
							<?php $s_time = explode(':', $info->shipping_time); ?>
							<div class="input-group">
								<input type="text" name="shipping_time_h" class="form-control numeric" maxlength="2" value="{{ @$s_time[0] }}"> 
								<span class="input-group-addon">:</span>
								<input type="text" name="shipping_time_m" class="form-control numeric" maxlength="2" value="{{ @$s_time[1] }}"> 
							</div>      
						</div>	
					</div>

		            <label class="mt-checkbox mt-checkbox-outline margin-top-20">
		                <input type="hidden" value="0" name="approve_ship_date">
		                <input type="checkbox" value="1" name="approve_ship_date" {{ checked($info->approve_ship_date, 1) }}>
		                <span></span>
		                Approve Ship Date
		            </label>   

		            @if( $info->approve_ship_date )
		            	<div class="m-heading-1 border-green bordered alert alert-success">
		            		<strong>Approved : </strong> {{ date_formatted($info->shipping_date) }} @ 12:00
		            	</div>
		            @endif

				</div>
	    	</div>

	    </div>

	</div>

</div>

