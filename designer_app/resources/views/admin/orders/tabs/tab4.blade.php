
                
<div class="portlet light bordered">

	<h5 class="sbold">Art Information</h5>
	<hr>

    <label class="mt-checkbox mt-checkbox-outline">
        <input type="hidden" value="0" name="approve_artwork">
        <input type="checkbox" value="1" name="approve_artwork" {{ checked($info->approve_artwork, 1) }}>
        <span></span>
        Approve Artwork
    </label>   

    @if( $info->approve_artwork )
    	<div class="m-heading-1 border-green bordered alert alert-success">
    		<strong>Approved : </strong> {{ date_formatted($info->approve_artwork_date) }} @ {{ date('H:s', strtotime($info->approve_artwork_date)) }}
    	</div>
    @else
    	<input type="hidden" name="approve_artwork_date" value="{{ date('Y-m-d H:i:s') }}">    
    @endif

	<?php 
	$arts_gallery = glob("assets/uploads/".$file_assets."/*.*"); 
	array_multisort(array_map('filemtime', $arts_gallery), SORT_NUMERIC, SORT_ASC, $arts_gallery);
	?>
	<ul class="media-gallery">

		@foreach($arts_gallery as $ag => $art_gellery)
	    <li>
			<div class="media-thumb">	    	
		    	<img src="{{ has_image($art_gellery) }}">
		    	<a href="" class="delete-media x-media" data-file="{{ $art_gellery }}" data-url="{{ route('admin.media.unlink') }}"><i class="fa fa-trash-o"></i></a>
		    	<div class="action">
			    	<a href="{{ has_image($art_gellery) }}" class="btn btn-xs btn-default" download><i class="fa fa-download"></i></a>
			    	<a href="{{ has_image($art_gellery) }}" class="btn btn-xs btn-default btn-img-preview" data-title="Artwork #{{ $ag+1 }}"><i class="fa fa-search-plus"></i></a>	    		
		    	</div>
		    </div>
	    </li>
	    @endforeach

	</ul>

	<input type="file" name="gallery[]" class="form-control" accept="image/*, .pdf, .ai, .eps, .svg, .psd" multiple>

   

</div>

<div class="portlet light bordered">

	<div class="portlet-body form">

	    <div class="row">
	    	<div class="col-md-6">					
				<h5 class="sbold">PO Number</h5>
				<hr>

				<label class="sbold text-muted">Name:</label>
				<p>{{ $info->billing_firstname.' '.$info->billing_lastname }}</p>

				<label class="sbold text-muted">Email address:</label>
				<p><a href="mailto:{{ $info->billing_email }}">{{ $info->billing_email }}</a></p>

				<label class="sbold text-muted">Phone:</label>
				<p>{{ $info->billing_telephone }}</p>	


	    	</div>

			<div class="col-md-6">
				<h5 class="sbold">Artwork Schedule</h5>
				<hr>

				<h5 class="sbold">Due date</h5>
				<div class="input-icon">
					<i class="fa fa-calendar"></i>
					<input type="text" class="form-control datepicker" name="art_due_date" value="{{ date_formatted_b($info->art_due_date) }}"> 
				</div>

				<h5 class="sbold">Type</h5>
				{{ Form::select('art_type', art_types(), $info->art_type, ['class' => 'form-control select2'] ) }}

	            <label class="mt-checkbox mt-checkbox-outline margin-top-20">
	            	<input type="hidden" name="art_rush" value="0">
	                <input type="checkbox" value="1" name="art_rush" {{ checked($info->art_rush, 1) }}>
	                <span></span>
	                Rush
	            </label>   

			</div>

    	</div>



	</div>

</div>

