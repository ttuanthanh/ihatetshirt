@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }} <small>Edit</small> 
    <div class="pull-right">
        <a href="{{ URL::route($view.'.index') }}" class="btn"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>
        <a href="{{ URL::route($view.'.add') }}" class="btn"><i class="fa fa-plus"></i> Add New</a>        
    </div>
</h1>
<!-- END PAGE TITLE-->

@include('notification')

<div class="tabbable-custom nav-justified">
<ul class="nav nav-tabs uppercase">
    <li class="{{ Input::get('tab', 1) == 1 ? 'active' : '' }}">
        <a href="?tab=1">Product Information </a>
    </li>
    <li class="{{ Input::get('tab') == 2 ? 'active' : '' }}">
        <a href="?tab=2">Product Design </a>
    </li>   
</ul>
</div>

<form method="post" enctype="multipart/form-data">
{{ csrf_field() }}

@if( Input::get('tab', 1) == 1 )

<div class="row">
    <div class="col-md-8">

        <div class="portlet light bordered">
            <input type="text" class="form-control input-lg" name="name" placeholder="Product Name" value="{{ Input::old('name', $info->post_title) }}">
            {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}

            <input type="hidden" id="post-name" value="{{ Input::old('slug', $info->post_name) }}">

            <!-- BEGIN SLUG -->
            <div class="input-group permalink edit-slug-form margin-top-10">
                <div class="domain pgroup"><label class="sbold">Permalink :</label> <a>{{ url('/') }}/<span class="slug-text">{{ Input::old('slug', $info->post_name) }}</span></a></div>
                <a href="#" class="btn btn-default btn-sm edit-slug" type="button">Edit</i></a>
            </div>

            <div class="input-group permalink update-slug-form margin-top-10" style="display:none;">
                <div class="domain pgroup"><label class="sbold">Permalink :</label> {{ url('/') }}/</div>
                <input name="slug" class="form-control pgroup slug-field" type="text" value="{{ Input::old('slug', $info->post_name) }}">
                <a href="#" class="btn btn-primary btn-sm update-slug" type="button">OK</i></a>
                <a href="#" class="btn btn-default btn-sm cancel-slug">Cancel</a>
            </div>
            <!-- END SLUG -->

            <div class="margin-top-10">
                <textarea name="description" class="tinymce">{{ Input::old('description', $info->post_content) }}</textarea>               
            </div>
        </div>
    
        <div class="portlet light bordered">
            <h5 class="sbold">Product Size Info</h5>
            <textarea name="product_size_info" class="form-control tinymce" rows="1">{{ Input::old('product_size_info', $info->product_size_info) }}</textarea>
        </div>

        <div class="portlet light bordered">
            <h5 class="sbold">Short Description</h5>
            <textarea name="excerpt" class="form-control" rows="6">{{ Input::old('excerpt', $info->excerpt) }}</textarea>
        </div>

        <div class="portlet light bordered">
            <h5 class="sbold">Product Data</h5>

            <div class="row">
                <div class="col-md-4 col-sm-3 col-xs-3">
                    <ul class="nav nav-tabs tabs-left">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab"> General </a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab"> Orders </a>
                        </li>
                        <li>
                            <a href="#tab_3" data-toggle="tab"> Sizes </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8 col-sm-9 col-xs-9">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">

                            <div class="row">
                                <label class="col-md-5 control-label">SKU</label>
                                <div class="col-md-7">
                                    <input type="text" name="sku" class="form-control" value="{{ Input::old('sku', $info->sku) }}">
                                </div>
                            </div>
                            <div class="row margin-top-10">
                                <label class="col-md-5 control-label">Starting Price</label>
                                <div class="col-md-7">

                                    <div class="input-group">
                                        <span class="input-group-addon">{{ currency_symbol(App\Setting::get_setting('currency')) }}</span>
                                        <input type="text" name="starting_price" class="form-control numeric" value="{{ Input::old('starting_price', $info->starting_price) }}">
                                    </div>
                    
                                </div>
                            </div>
                            <div class="row margin-top-10">
                                <label class="col-md-5 control-label">Sales Price</label>
                                <div class="col-md-7">

                                    <div class="input-group">
                                        <span class="input-group-addon">{{ currency_symbol(App\Setting::get_setting('currency')) }}</span>
                                        <input type="text" name="sales_price" class="form-control numeric" value="{{ Input::old('sales_price', $info->sales_price) }}">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_2">
                            <div class="row">
                                <label class="col-md-5 control-label">Minimum Purchase Quantity</label>
                                <div class="col-md-7">
                                    <input type="text" name="minimum_price_quantity" class="form-control" value="{{ Input::old('minimum_price_quantity', $info->minimum_price_quantity) }}">
                                </div>
                            </div>
                            <div class="row margin-top-10">
                                <label class="col-md-5 control-label">Maximum Purchase Quantity</label>
                                <div class="col-md-7">
                                    <input type="text" name="maximum_price_quantity" class="form-control" value="{{ Input::old('maximum_price_quantity', $info->maximum_price_quantity) }}">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_3">

                            <!-- START SIZES -->
                            <table>
                                <tr>
                                    <td width="50%">
                                        <h5>Name</h5>       
                                    </td>
                                    <td width="25%">
                                        <h5>Price White</h5>        
                                    </td>
                                    <td width="25%">
                                        <h5>Price Color</h5>        
                                    </td>
                                    <td width="1%">     
                                    </td>
                                </tr>
                            </table>


                            <div class="form-group mt-repeater">
                                <div data-repeater-list="size">

                                    @if( $sizes = Input::old('size', json_decode(@$info->size, true)) )
                                    @foreach( $sizes as $size )
                                    <div data-repeater-item class="mt-repeater-item">
                                        <div class="mt-repeater-row">
                                        <table class="table-sizes">
                                            <tr>
                                                <td width="50%">
                                                    <input type="text" name="name" placeholder="" class="form-control" value="{{ $size['name'] }}" />           
                                                </td>
                                                <td width="25%">
                                                    <input type="text" name="price_white" placeholder="0.00" class="form-control text-right" value="{{ $size['price_white'] }}"  />            
                                                </td>
                                                <td width="25%">
                                                    <input type="text" name="price_color" placeholder="0.00" class="form-control text-right" value="{{ $size['price_color'] }}"  />            
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
                                    @endforeach
                                    @else
                                    <div data-repeater-item class="mt-repeater-item">
                                        <div class="mt-repeater-row">
                                        <table class="table-sizes">
                                            <tr>
                                                <td width="50%">
                                                    <input type="text" name="name" placeholder="" class="form-control"/>           
                                                </td>
                                                <td width="25%">
                                                    <input type="text" name="price_white" placeholder="0.00" class="form-control text-right"/>            
                                                </td>
                                                <td width="25%">
                                                    <input type="text" name="price_color" placeholder="0.00" class="form-control text-right" />            
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
                                    @endif

                                </div>
                                <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add">
                                    <i class="fa fa-plus"></i> Add New</a>
                            </div>
                            <!-- END SIZES -->

                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="portlet light bordered">
            <h5 class="sbold">Meta Tags</h5>
            <hr>

            <h5>Meta Title</h5>
            <input type="text" name="meta_title" class="form-control" value="{{ Input::old('meta_title', $info->meta_title) }}">
            <span class="help-inline">The title is in a title bar of Web browsers, user's history, bookmarks, or in search results.</span>

            <h5>Meta Keyword</h5>
            <input type="text" name="meta_keyword" class="form-control" value="{{ Input::old('meta_keyword', $info->meta_keyword) }}">
            <div class="help-inline">The keyword describe the Web page in the content attribute.</div>

            <h5>Meta Description</h5>
            <textarea name="meta_description" class="form-control" rows="5">{{ Input::old('meta_description', $info->meta_description) }}</textarea>
            <span class="help-inline">The Description the Web page in the content attribute.</span> 
        </div>

    </div>
    <div class="col-md-4">

            <div class="portlet light bordered">
                <h5 class="sbold">Post Status</h5>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mt-radio-inline">
                            <label class="mt-radio mt-radio-outline">
                                <input name="status" type="radio" value="published" {{ checked(Input::old('status', $info->post_status), 'published') }}> Published
                                <span></span>
                            </label> 
                            <label class="mt-radio mt-radio-outline">
                                <input name="status" type="radio" value="unpublished" {{ checked(Input::old('status', $info->post_status), 'unpublished') }}> Unpublished
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="portlet light bordered">
                <h5 class="sbold">Categories</h5>
                <hr>

                <div class="mt-checkbox-list" style="overflow-y:auto; max-height:200px;">
                <input type="hidden" name="category" value="0">
                {!! checkbox_ordered_menu($categories, 0, Input::old('category', json_decode($info->category))) !!}
                </div>

            </div>

            <div class="portlet light bordered">
                <h5 class="sbold">Tags</h5>
                <div class="margin-top-20">
                    <input type="text" class="form-control input-large" name="tags" value="{{ Input::old('tags', $info->tags) }}" data-role="tagsinput">                
                </div>
            </div>

            <div class="portlet light bordered">
                <h5 class="sbold">Featured Image</h5>
                <hr>

                <div class="media-single">
                <input type="hidden" name="image" value="">
                @if( $image = Input::old('image', $info->image) )
                <li>
                    <div class="media-thumb">
                    <img src="{{ has_image(str_replace('large', 'medium', $image)) }}">
                    <input type="hidden" name="image" value="{{ $image }}">
                    <a href="" class="delete-media"><i class="fa fa-trash-o"></i></a>
                    </div>
                </li>
                @endif
                </div>
                <a href="" class="filemanager btn btn-default" data-target=".media-single" data-type="images">Select featured image</a>

            </div>

        <div class="portlet light bordered">
            <h5 class="sbold">Product Gallery</h5>
            <hr>

            <ul class="media-gallery sortable">
                <input type="hidden" name="gallery" value="">
                @if( $galleries = Input::old('gallery', json_decode($info->gallery)) )
                @foreach($galleries as $gallery)
                <li>
                    <div class="media-thumb">
                    <img src="{{ has_image(str_replace('large', 'thumb', $gallery)) }}">
                    <input type="hidden" name="gallery[]" value="{{ $gallery }}">
                    <a href="" class="delete-media"><i class="fa fa-trash-o"></i></a>
                    </div>
                </li>
                @endforeach
                @endif
            </ul>
            <p class="help-block">Hover the image to re-order or remove</p>
            <a href="" class="filemanager btn btn-default" data-target=".media-gallery" data-mode="gallery" data-type="images"><i class="fa fa-plus"></i> Add product gallery images</a>

        </div>

    </div>
</div>
@else
<input type="hidden" name="design" value="1">
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="table-responsive margin-top-20">
            <table class="table table-hover table-bordered" id="product-design">
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-center">Color</th>
                        <th class="text-center">Color Title</th>
                        <th class="text-center" width="100">Price</th>
                        <th class="text-center">Front</th>
                        <th class="text-center">Back</th>
                        <th class="text-center">Left</th>
                        <th class="text-center">Right</th>
                        <th width="1"></th>
                    </tr>                    
                </thead>
                <tbody class="sortable">
                    <?php 
                        $i=1; 
                        $rows = json_decode($info->product_design, true);
                    ?>
                    @if( $rows )
                        @foreach($rows as $row)
                        <tr>
                            <td><div class="sort"><i class="fa fa-arrows-v"></i></div></td>
                            <td>                            
                                <a href="#colors-modal" class="p-color" data-toggle="modal">
                                    <div class="c" style="background-color: {{ @$row['color'] }};"></div>
                                </a>
                                <input type="hidden" class="input-color" name="product[{{ $i }}][color]" value="{{ @$row['color'] }}">
                            </td>
                            <td>
                                <input type="text" class="form-control input-color-title" name="product[{{ $i }}][color_title]" value="{{ @$row['color_title'] }}">
                            </td>
                            <td>
                                <input type="text" class="form-control numeric text-right" name="product[{{ $i }}][price]" value="{{ @$row['price'] }}" maxlength="10">
                            </td>
                            <td class="text-center valign front">
                                <input type="hidden" class="input-image" name="product[{{ $i }}][image][0][url]" value="{{ @$row['image'][0]['url'] }}">
                                <input type="hidden" class="input-attributes" name="product[{{ $i }}][image][0][attr]" value="{{ @$row['image'][0]['attr'] }}">
                                <input type="hidden" name="product[{{ $i }}][image][0][name]" value="front">
                                <a href="javascript:void(0)" class="btn btn-xs btn-block btn-configure" data-name="front">
                                <img src="{{ has_image(@$row['image'][0]['url']) }}" class="img-thumb"><br>
                                Configure</a>
                            </td>
                            <td class="text-center valign back">
                                <input type="hidden" class="input-image" name="product[{{ $i }}][image][1][url]" value="{{ @$row['image'][1]['url'] }}">
                                <input type="hidden" class="input-attributes" name="product[{{ $i }}][image][1][attr]" value="{{ @$row['image'][1]['attr']}}">
                                <input type="hidden" name="product[{{ $i }}][image][1][name]" value="back">
                                <a href="javascript:void(0)" class="btn btn-xs btn-block btn-configure" data-name="back">
                                <img src="{{ has_image(@$row['image'][1]['url']) }}" class="img-thumb"><br>
                                Configure</a>
                            </td>
                            <td class="text-center valign left">
                                <input type="hidden" class="input-image" name="product[{{ $i }}][image][2][url]" value="{{ @$row['image'][2]['url']}}">
                                <input type="hidden" class="input-attributes" name="product[{{ $i }}][image][2][attr]" value="{{ @$row['image'][2]['attr'] }}">
                                <input type="hidden" name="product[{{ $i }}][image][2][name]" value="left">
                                <a href="javascript:void(0)" class="btn btn-xs btn-block btn-configure" data-name="left">
                                <img src="{{ has_image(@$row['image'][2]['url']) }}" class="img-thumb"><br>
                                Configure</a>
                            </td>
                            <td class="text-center valign right">
                                <input type="hidden" class="input-image" name="product[{{ $i }}][image][3][url]" value="{{ @$row['image'][3]['url'] }}">
                                <input type="hidden" class="input-attributes" name="product[{{ $i }}][image][3][attr]" value="{{ @$row['image'][3]['attr'] }}">
                                <input type="hidden" name="product[{{ $i }}][image][3][name]" value="right">
                                <a href="javascript:void(0)" class="btn btn-xs btn-block btn-configure" data-name="right">
                                <img src="{{ has_image(@$row['image'][3]['url']) }}" class="img-thumb"><br>
                                Configure</a>
                            </td>
                            <td class="text-center valign">
                                <a href="javascript:void(0)" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-remove"></i></a>
                            </td>
                        </tr>
                        <?php $i++; ?>
                        @endforeach
                    @endif
                </tbody>
            </table>
            </div>


        </div>
    </div>
</div>

@endif

<div class="form-actions">

    @if( Input::get('tab') == 2 )
        <button class="btn btn-primary btn-update" type="button"><i class="fa fa-check"></i> Update {{ $single }}</button>    
        <button class="btn btn-success btn-add-color" type="button"><i class="fa fa-plus"></i> Add Color</button>
    @else
    <button class="btn btn-primary"><i class="fa fa-check"></i> Update {{ $single }}</button>    
    @endif

    @if( $info->post_status == 'unpublished' )
    <a href="#" class="popup btn"
        data-href="{{ URL::route($view.'.delete', $info->id) }}" 
        data-toggle="modal"
        data-target=".popup-modal" 
        data-title="Confirm Move to Trash"
        data-body="Are you sure you want to move to trash ID: <b>#{{ $info->id }}</b>?"><i class="fa fa-trash-o"></i> Move to trash</a>  
    @endif
</div>
</form>

 
<div class="modal fade" id="colors-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Select Color</h4>
            </div>
            <div class="modal-body">
                
                <div class="row margin-bottom-20">
                    <div class="col-md-5">
                        <input type="text" name="color_title" class="form-control" placeholder="Color Title">                
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="color_hex" class="form-control colorpicker" data-control="hue" placeholder="Color Hex">                
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary btn-block btn-set-color"><i class="fa fa-plus"></i> Set Color</button>
                    </div>
                </div>

                <input type="text" id="find-color" class="form-control" placeholder="Find color ..." onkeyup="findColors()">
                <ul id="select-colors" class="select-colors margin-top-20">
                    @foreach($colors as $color)
                        <li>
                            <a href="javascript:;" data-color="{{ $color->post_content }}" data-title="{{ $color->post_title }}"><span style="background-color: {{ $color->post_content }};"></span>
                            <div>{{ $color->post_title }}</div>                                                            
                            </a>
                        </li>
                    @endforeach
                </ul>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="configure-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Configure Product Design</h4>
            </div>
            <div class="modal-body">

<div class="row">
    <div class="col-md-9">

        <div class="containment">
          <div class="draggable resizable ui-widget-content"></div>
        </div>

    </div>
    <div class="col-md-3">

        <table width="100%">
            <tr>
                <td width="40">
                    <div class="p-color">
                        <div class="c color-hex" style="background-color: #ff5319;"></div> 
                    </div>             
                </td>
                <td><span class="color-title">Orange</span></td>
            </tr>
        </table>

        <div class="margin-top-10">
            Product view name: <strong class="product-name uppercase">LEFT</strong>    
        </div>
        <hr>
            <i class="glyphicon glyphicon-move"></i> Click area design to move and resize object.
        <hr>

        <div class="row">
            <div class="col-md-12">
                <label>Area Design Color</label>
              <input type="text" name="hex" class="form-control colorpicker input-area-design-color" data-control="hue">
            </div>
        </div>

        <div class="row margin-top-10">
            <div class="col-md-12">
                <h5>Height</h5>
                <div class="input-group">
                    <input type="number" class="form-control numeric input-attr" name="height" min="0">
                    <span class="input-group-addon">PX</span>
                </div>
            </div>
            <div class="col-md-12">
                <h5>Width</h5>
                <div class="input-group">
                    <input type="number" class="form-control numeric input-attr" name="width" min="0">
                    <span class="input-group-addon">PX</span>
                </div>
            </div>
        </div>

        <div class="row margin-top-10">
            <div class="col-md-12">
                <h5>Top</h5>
                <div class="input-group">
                    <input type="number" class="form-control numeric input-attr" name="top" min="0">
                    <span class="input-group-addon">PX</span>
                </div>
            </div>
            <div class="col-md-12">
                <h5>Left</h5>
                <div class="input-group">
                    <input type="number" class="form-control numeric input-attr" name="left" min="0">
                    <span class="input-group-addon">PX</span>
                </div>
            </div>
        </div>

                <button class="btn btn-primary btn-save btn-lg btn-block margin-top-20"><i class="fa fa-check"></i> Save</button>

            </div>
        </div>          



            </div>


            <div class="modal-footer">

            <div class="pull-left">

                <button class="btn btn-default" onclick="design_area_move('up')"><i class="glyphicon glyphicon-arrow-up"></i></button>
                <button class="btn btn-default" onclick="design_area_move('down')"><i class="glyphicon glyphicon-arrow-down"></i></button>
                <button class="btn btn-default" onclick="design_area_move('left')"><i class="glyphicon glyphicon-arrow-left"></i></button>
                <button class="btn btn-default" onclick="design_area_move('right')"><i class="glyphicon glyphicon-arrow-right"></i></button>
                <button class="btn btn-default" onclick="design_area_move('center')"><i class="glyphicon glyphicon-align-center"></i></button>

                <button class="btn btn-success filemanager" data-target=".containment" data-mode="configure-product" data-type="images">Set Image Design</button>



            </div>



        <button class="btn btn-danger btn-xs btn-remove">Delete Product Design</button>    
    </div>

        </div>
    </div>
</div>


@endsection

@section('style')

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>
.img-thumb { min-height: 55px; background-color: #eef1f5; }
#resizable { width: 150px; height: 150px; padding: 0.5em; }
#resizable h3 { text-align: center; margin: 0; }
.valign { vertical-align: middle !important; }
.draggable { 
    border: 1px dashed #d83f3f;
    cursor: pointer;
    background: #00000057;
}
.containment { 
    width: 620px; 
    height:620px; 
    border:2px solid #ccc;
    margin: 0 auto;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
}      
.ui-resizable-handle {
    background-color: #FFF;
    border: 2px solid #428BCA;
    height: 10px;
    width: 10px;
}

.ui-resizable-se {
    cursor: se-resize;
    width: 10px !important;
    height: 10px !important;
    right: -5px !important;
    bottom: -5px !important;
}

.p-color { 
    border: 1px solid #9E9E9E;   
    display: inline-block;
    background: url(../../../assets/uploads/transparent-bg.png);
    background-size: 200%;
 }
.p-color .c {
    height: 26px;
    width: 26px;
}    
.select-colors {
    padding: 0;
}
.select-colors li {
    width: calc(23.95% - 2px);
    display: inline-block;
    margin: 0 4px 10px;
    float: left;
}
.select-colors li a {
    width: 100%;
    background-color: #FAFAFA;
    border: 1px solid #CCC;
    border-radius: 4px;
    color: #333;
    padding: 8px;
    text-decoration: none;
    display: table-cell;
    text-transform: uppercase;
    font-size: 11px;
    font-weight: 600;
    height: 50px;
    vertical-align: middle;
}
.select-colors li a:hover {
    background-color: #e2e2e2;
    border: 1px solid #969696;
}
.select-colors li a div {
    display: inline-block;
    width: 83px;    
}
.select-colors li a span {
    height: 20px;
    width: 20px;
    border-radius: 10px;
    display: inline-block;
    margin: -2px 5px -5px 0;
    border: 1px solid #9E9E9E;
}
.sort {
    border: 1px solid #dedede;
    padding: 5px 10px;
    text-align: center;
    border-radius: 25px;
}
#product-design tbody tr {
    cursor: move;

}
</style>
@stop

@section('plugin_script') 
@stop

@section('script')
<script>

var blockUImsg = 'Updating product design ...';

$('.colorpicker').each(function() {
    $(this).minicolors({
        change: function(hex, opacity) {
        $('.draggable').css('border-color', hex);     
        },
        theme: 'bootstrap'
    });
});

$(document).on('click', '.btn-configure', function(){
    var index = $(this).closest('tr').index(),
        color_hex = $(this).closest('tr').find('.input-color').val(), 
        color_title = $(this).closest('tr').find('.input-color-title').val(), 
        product_name = $(this).data('name'),
        image = $(this).closest('td').find('.input-image').val(),
        src = $(this).closest('td').find('img').attr('src'),
        attr = $(this).closest('td').find('.input-attributes').val();

    if( image ) {
        var attr = JSON.parse(attr);
        $('#configure-modal .btn-remove').show();
        $('.draggable').css({ width : attr.width, height : attr.height, top : attr.top, left : attr.left });
        design_area_move();
        $('#configure-modal .containment').css({'background-image': 'url('+src+')'});
        $('#configure-modal .containment').attr('data-large', image);
    } else {
        $('#configure-modal .btn-remove').hide();
        $('.draggable').css({ width : '220px', height: '283px' });
        design_area_move('center');

        $('#configure-modal .containment').css({'background-image': ''});
    }
    $('#configure-modal .input-area-design-color').val(attr.borderColor);
    
    if( attr.borderColor ) {
        $('.minicolors-swatch-color').css('background-color', attr.borderColor);
        $('.draggable').css('border-color', attr.borderColor);
    } else {
        $('.minicolors-swatch-color').css('background-color', '');   
        $('.draggable').css('border-color', '#fff');     
    }

    $('#colors-modal').attr('data-index', index );
    $('#configure-modal .color-hex').css({'background-color': color_hex});
    $('#configure-modal .color-title').html(color_title);
    $('#configure-modal .product-name').html(product_name);


    $('#configure-modal').modal();
});

$(document).on('click', '.btn-update', function() { 
    submit_ajax_form_data();
});

$(document).on('keyup click', '.input-attr', function() {
    var d = $('.draggable'), 
        h = d.outerHeight(), 
        w = d.outerWidth(), 
        t = d.css("top"), 
        l = d.css("left");
    
    drag_resize();

    $('.draggable').css($(this).attr('name'), parseFloat($(this).val()) );

});

function drag_resize() {
    if( ! $('.draggable').hasClass('ui-draggable') ) {
        $('.draggable').draggable({ containment: ".containment", scroll: false,  
            drag: function() { update_position(); },
            stop: function() { update_position(); }
        }).
        resizable({ containment: "parent",
            handles: "ne, se, sw, nw",
            resize: function() { update_position(); },
            stop: function() { update_position(); }
        });
    }
}
//   $('#configure-modal').modal();

$(document).on('click', '.btn-remove', function(e) {
    var index = $('#colors-modal').attr('data-index'),  
        name = $('#configure-modal .product-name').html();

    $('#product-design tbody tr').eq(index).find('.'+name+' img').attr('src', ''); 
    $('#product-design tbody tr').eq(index).find('.'+name+' .input-image').val(''); 

    $('#configure-modal').modal('hide');
    submit_ajax_form_data();

});

$(document).on('click', '.btn-save', function(e) {
    var index = $('#colors-modal').attr('data-index'),  
        name = $('#configure-modal .product-name').html();

        var bg = $('.containment').css("background-image")
            .replace(/.*\s?url\([\'\"]?/, '')
            .replace(/[\'\"]?\).*/, '');
        var bg = bg != 'none' ? bg : '';
        
        var bginput = $('.containment').attr("data-large");


    $('#product-design tbody tr').eq(index).find('.'+name+' img').attr('src', bg); 
    $('#product-design tbody tr').eq(index).find('.'+name+' .input-image').val(bginput); 

    var d = $('.draggable'), 
        h = d.outerHeight() + 'px', 
        w = d.outerWidth() + 'px', 
        t = d.css("top"), 
        l = d.css("left"),
        c = $('.input-area-design-color').val();

    attributes = {width : w, height: h, top: t, left: l, borderColor: c}

    $('#product-design tbody tr').eq(index).find('.'+name+' .input-attributes').val( JSON.stringify(attributes) ); 

    $('#configure-modal').modal('hide');
    submit_ajax_form_data();

});

function update_position() {

    var d = $('.draggable'), 
        h = d.outerHeight(), 
        w = d.outerWidth(), 
        t = d.css("top").replace('px', ''), 
        l = d.css("left").replace('px', '');

    $('[name=height]').val(h);
    $('[name=width]').val(w);
    $('[name=top]').val(t);
    $('[name=left]').val(l);
}

$('.containment').on('click', function(e) {
    $('.draggable').resizable().resizable('destroy').draggable().draggable('destroy');
}).on('click', '.draggable', function(e) {
    drag_resize();
    e.stopPropagation();
});

function design_area_move(position) {

    drag_resize();

    var o = $('.draggable'),
        w = o.width(),
        h = o.height(),
        p = o.position();
        
    if(typeof o != 'undefined' && position)
    {
        switch(position){
            case 'left':
                var left = o.css('left');
                    o.css('left', parseFloat(left) - 1);
                break;
            case 'right':
                var left = o.css('left');
                    o.css('left', parseFloat(left) + 1);
                break;
            case 'center':
                var left = (620 - w)/2,
                    top = (620 - h)/2;
                o.css({'top': top + 'px', 'left': left + 'px' });                       
                break;
            case 'up':
                var top = o.css('top');
                    o.css('top', parseFloat(top) - 1);
                break;
            case 'down':
                var top = o.css('top');
                    o.css('top', parseFloat(top) + 1);
                break;
        }
    }

    update_position();
}



$(document).on('click', '.p-color', function() {
    var index = $(this).closest('tr').index(); 
    $('#colors-modal').attr('data-index', index );
    $('#find-color').val('').trigger('keyup');

    var hex = $(this).closest('tr').find('.input-color').val(),
    title = $(this).closest('tr').find('.input-color-title').val();

    $('[name=color_hex]').val(hex); 
    $('[name=color_title]').val(title);
    $('.minicolors-swatch-color').css('background-color', hex);
});


$(document).on('click', '.btn-set-color', function() {
    var index = $('#colors-modal').attr('data-index'),  
        $this = $(this);

    var color = $('[name=color_hex]').val(), 
        title = $('[name=color_title]').val();

    if( index ) {
        add_design(index, color, title);
    } else {
        var i = $('#product-design tbody tr').length;
        $.get('?i='+i, function(res) {
            $('#product-design tbody').append(res);
            $("html, body").animate({ scrollTop: $(document).height() }, 1000);
            add_design(i, color, title);
        });        
    }
});

$(document).on('click', '#select-colors a', function() {

    var index = $('#colors-modal').attr('data-index'),  
        $this = $(this);

    var color = $this.data('color'), 
        title = $this.data('title');

    if( index ) {
        add_design(index, color, title);
    } else {
        var i = $('#product-design tbody tr').length;
        $.get('?i='+i, function(res) {
            $('#product-design tbody').append(res);
            $("html, body").animate({ scrollTop: $(document).height() }, 1000);
            add_design(i, color, title);
        });        
    }

});

function add_design(index, color, title) {

    $('#product-design tbody tr').eq(index).find('.c').css('background-color', color); 
    $('#product-design tbody tr').eq(index).find('.input-color').val(color); 
    $('#product-design tbody tr').eq(index).find('.input-color-title').val(title);     
    $('#colors-modal').modal('hide');
}

$(document).on('click', '.btn-delete', function() {
    $(this).closest('tr').hide('slow', function(){
        $(this).remove();  
    });
});

$(document).on('click', '.btn-add-color', function() {
    $('#colors-modal').attr('data-index', '' );
    $('#find-color').val('').trigger('keyup');
    $('#colors-modal').modal('show');
});

function findColors() {
    // Declare variables
    var input, filter, ul, li, a, i;
    input = document.getElementById('find-color');
    filter = input.value.toUpperCase();
    ul = document.getElementById("select-colors");
    li = ul.getElementsByTagName('li');

    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

function submit_ajax_form_data() {
    formElement = document.querySelector("form");
    blockUI(blockUImsg);
    $.ajax({
        url: '', // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method 
        data: new FormData( formElement ), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false,        // To send DOMDocument or non processed data file it is set to false
        success: function(response)   // A function to be called if request succeeds
        { 
            console.log(response);
            $.unblockUI();  
        }
    });
}
</script>
@stop
