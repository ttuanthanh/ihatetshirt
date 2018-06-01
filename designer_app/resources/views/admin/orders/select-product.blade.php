<form action="{{ route('admin.orders.add-custom') }}" id="form-product" method="post" data-url="{{ route('frontend.get-quote') }}">
{{ csrf_field() }}
<input type="hidden" name="pid" value="{{ $info->id }}">
<input type="hidden" name="type" value="{{ Input::old('type') }}">
<input type="hidden" name="image" value="{{ $image = Input::old('image') }}">
<input type="hidden" name="color_index" value="{{ $color_index = Input::old('color_index') }}">
<input type="hidden" name="color_hex" value="{{ Input::old('color_hex') }}">
<input type="hidden" name="color_title" value="{{ Input::old('color_title') }}">
<input type="hidden" name="unit_price" value="{{ $unit_price = Input::old('unit_price') }}">
<input type="hidden" name="total_price" value="{{ $total_price = Input::old('total_price') }}">

<input type="hidden" name="name" value="{{ $info->post_title }}">
<input type="hidden" name="order_id" value="{{ Input::get('order_id') }}">

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">


        <div class="row">
            <div class="col-md-2 col-sm-4 col-xs-4">
                <a href="{{ $image ? $image : $info->image }}" class="btn-img-preview" data-title="{{ $info->post_title }}">
                    <img src="{{ $image ? $image : str_replace('large', 'thumb', $info->image) }}" class="fullwidth img-preview"> 
                </a>
                
            </div>
            <div class="col-md-10 col-sm-8 col-xs-8">
                <h4>{{ $info->post_title }}</h4> 
                {{ amount_formatted(@$info->starting_price) }}<br>
                <small class="text-muted uppercase">{{ @$info->sku }}</small><br>
                <button type="button" class="change-product btn btn-default pull-right">Change Product</button>

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

</div>  



    <div class="row">
        <div class="col-xs-5">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Add to Order</button>    
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


</form>
