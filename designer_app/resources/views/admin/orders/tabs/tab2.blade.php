
<div class="portlet light bordered">
	<h5 class="sbold">Garment</h5>
	<hr>
	<div class="portlet-body form">
		<input type="hidden" name="add_garment">

	    <div>

	    	<div class="row">
		    	<div class="col-md-6">
		    		<div class="row margin-top-10">
		    			<div class="col-md-12">
			    			<label>Distributor</label>
			    			<input type="text" name="garment[distributor]" class="form-control" value="{{ @$garment->distributor }}">				    				
		    			</div>
		    		</div>
		    		<div class="row margin-top-10">
		    			<div class="col-md-12">
			    			<label>Invoice Number</label>
			    			<input type="text" name="garment[invoice_number]" class="form-control" value="{{ @$garment->invoice_number }}">				    				
		    			</div>
		    		</div>
		    		<div class="row margin-top-10">
		    			<div class="col-md-12">
			    			<label>Date Ordered</label>
							<div class="input-icon">
								<i class="fa fa-calendar"></i>
								<input type="text" name="garment[date_ordered]" class="form-control datepicker" value="{{ @$garment->date_ordered }}"> 
							</div>					    				
		    			</div>
		    		</div>
		    		<div class="row margin-top-10">
		    			<div class="col-md-12">
			    			<label>ETA</label>
							<div class="input-icon">
								<i class="fa fa-calendar"></i>
								<input type="text" name="garment[eta]" class="form-control datepicker" value="{{ @$garment->eta }}"> 
							</div>					    				
		    			</div>
		    		</div>
		    	</div>

		    	<div class="col-md-6">

		    		<div class="row margin-top-10">
		    			<div class="col-md-12">
			    			<label>Tracking number</label>
			    			<input type="text" name="garment[tracking_number]" class="form-control" value="{{ @$garment->tracking_number }}">				    				
		    			</div>
		    		</div>
		    		<div class="row margin-top-10">
		    			<div class="col-md-12">
			    			<label>Cost</label>
			    			<input type="text" name="garment[cost]" class="form-control text-right numeric" value="{{ @$garment->cost }}">				    				
		    			</div>
		    		</div>
		    		<div class="row margin-top-10">
		    			<div class="col-md-12">
			    			<label>Misc. Fees</label>
			    			<input type="text" name="garment[misc]" class="form-control text-right numeric" value="{{ @$garment->misc }}">				    				
		    			</div>
		    		</div>

		    	</div>
	    	</div>

	    	<div class="margin-top-20">
	    		<button class="btn btn-primary add-garment"><i class="fa fa-check"></i> {{ Input::get('edit') ? 'Update Garment' : 'Add Garment' }}</button>
	    	</div>
		
	    </div>

	</div>

</div>

<div class="portlet light bordered">
	<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Distributor</th>
				<th class="text-center" width="120">Date</th>
				<th class="text-right" width="100">Cost</th>
				<th class="text-right" width="100">Misc. Fees</th>
			</tr>
		</thead>
		<tbody>
			<?php $total_cost = 0; ?>
			@foreach($garments as $garment)
			<?php $content = json_decode(@$garment->post_content); ?>
			<tr>
				<td>
					<h5 class="sbold">{{ $content->distributor }}</h5>
					<span class="text-muted uppercase small">Invoice No.</span> : {{ $content->invoice_number }}<br>
					<span class="text-muted uppercase small">Tracking No.</span> : {{ $content->tracking_number }}

					<div class="small uppercase margin-top-10">
					<a href="{{ route('admin.orders.edit', [$info->id, 'tab' => 'apparel-order', 'edit' => @$garment->id]) }}">Edit</a> | 
					<a href="{{ route('admin.orders.edit', [$info->id, 'tab' => 'apparel-order', 'delete' => @$garment->id]) }}">Delete</a>
					</div>
				</td>
				<td>
					@if( $content->date_ordered )
					<div>
						<h5 class="no-margin small uppercase text-muted">Date Ordered</h5>
						{{ date_formatted(date_formatted_b($content->date_ordered)) }}						
					</div>
					@endif
					
					@if( $content->eta )
					<div class="margin-top-10">
						<h5 class="no-margin small uppercase text-muted">ETA</h5>
						{{ date_formatted(date_formatted_b($content->eta)) }}						
					</div>
					@endif
				</td>
				<td class="text-right">
					{{ amount_formatted($content->cost) }}
					<?php $total_cost += $content->cost; ?>
				</td>
				<td class="text-right">{{ amount_formatted($content->misc) }}</td>
			</tr>
			@endforeach

		</tbody>
		<tbody>		
		<tfoot>
			<tr>
				<td colspan="1"></td>
				<td><h5 class="sbold">Total Cost</h5></td>
				<td colspan="2"><h4 class="sbold text-primary">{{ amount_formatted($total_cost) }}</h4></td>
			</tr>
		</tfoot>	
	</table>

	</div>


</div>