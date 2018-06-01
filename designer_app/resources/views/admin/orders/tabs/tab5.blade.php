
                
<div class="portlet light bordered">
	<h5 class="sbold">Proof</h5>
	<hr>
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
			    	<a href="{{ has_image($art_gellery) }}" class="btn btn-xs btn-default btn-img-preview" data-title="Proof #{{ $ag+1 }}"><i class="fa fa-search-plus"></i></a>	    		
		    	</div>
		    </div>
	    </li>
	    @endforeach

	</ul>

	<input type="file" name="gallery[]" class="form-control" accept="image/*, .pdf, .ai, .eps, .svg, .psd"  multiple>

</div>

<div class="portlet light bordered">

	<div class="portlet-body form">

	    <div class="row">
	    	<div class="col-md-12">
				<h5 class="sbold">Description</h5>
				<textarea class="form-control" rows="5" name="proof_description">{{ $info->proof_description }}</textarea>

				<h5 class="margin-top-20 sbold">Ready to print?</h5>

		            <label class="mt-checkbox mt-checkbox-outline">
		                <input type="hidden" value="0" name="approve_proof">
		                <input type="checkbox" value="1" name="approve_proof" {{ checked($info->approve_proof, 1) }}>
		                <span></span>
		                Approve Proof
		            </label>   

		            @if( $info->approve_proof )
		            	<div class="m-heading-1 border-green bordered alert alert-success">
		            		<strong>Approved : </strong> {{ date_formatted($info->approve_proof_date) }} @ {{ date('H:s', strtotime($info->approve_proof_date)) }}
		            	</div>
		            @else
	            		<input type="hidden" name="approve_proof_date" value="{{ date('Y-m-d H:i:s') }}">
		            @endif

			</div>
    	</div>


	</div>

</div>

