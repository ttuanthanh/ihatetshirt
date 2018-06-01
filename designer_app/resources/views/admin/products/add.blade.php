@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }} <small>Add</small> 
    <a href="{{ URL::route($view.'.index') }}" class="btn pull-right"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>
</h1>
<!-- END PAGE TITLE-->

@include('notification')

<form method="post" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="row">
    <div class="col-md-8">

        <input type="hidden" id="post-name" value="gildan-5000">

        <div class="portlet light bordered">
            <input type="text" class="form-control input-lg" name="name" placeholder="Product Name" value="{{ Input::old('name') }}">
            {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}

            <div class="margin-top-10">
                <textarea name="description" class="tinymce">{{ Input::old('description') }}</textarea>               
            </div>
        </div>

        <div class="portlet light bordered">
            <h5 class="sbold">Product Size Info</h5>
            <textarea name="product_size_info" class="form-control tinymce" rows="1">{{ Input::old('product_size_info') }}</textarea>
        </div>

        <div class="portlet light bordered">
            <h5 class="sbold">Short Description</h5>
            <textarea name="excerpt" class="form-control" rows="6">{{ Input::old('excerpt') }}</textarea>
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
                                    <input type="text" name="sku" class="form-control" value="{{ Input::old('sku') }}">
                                </div>
                            </div>
                            <div class="row margin-top-10">
                                <label class="col-md-5 control-label">Starting Price</label>
                                <div class="col-md-7">
                                    <input type="text" name="starting_price" class="form-control" value="{{ Input::old('starting_price') }}">
                                </div>
                            </div>
                            <div class="row margin-top-10">
                                <label class="col-md-5 control-label">Sales Price</label>
                                <div class="col-md-7">
                                    <input type="text" name="sales_price" class="form-control" value="{{ Input::old('sales_price') }}">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_2">
                            <div class="row">
                                <label class="col-md-5 control-label">Minimum Purchase Quantity</label>
                                <div class="col-md-7">
                                    <input type="text" name="minimum_price_quantity" class="form-control" value="{{ Input::old('minimum_price_quantity') }}">
                                </div>
                            </div>
                            <div class="row margin-top-10">
                                <label class="col-md-5 control-label">Maximum Purchase Quantity</label>
                                <div class="col-md-7">
                                    <input type="text" name="maximum_price_quantity" class="form-control" value="{{ Input::old('maximum_price_quantity') }}">
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

                                    @if( Input::old('size') )
                                    @foreach( Input::old('size')  as $size )
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
            <input type="text" name="meta_title" class="form-control" value="{{ Input::old('meta_title') }}">
            <span class="help-inline">The title is in a title bar of Web browsers, user's history, bookmarks, or in search results.</span>

            <h5>Meta Keyword</h5>
            <input type="text" name="meta_keyword" class="form-control" value="{{ Input::old('meta_keyword') }}">
            <div class="help-inline">The keyword describe the Web page in the content attribute.</div>

            <h5>Meta Description</h5>
            <textarea name="meta_description" class="form-control" rows="5">{{ Input::old('meta_description') }}</textarea>
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
                                <input name="status" type="radio" value="published" {{ checked(Input::old('status', 'published'), 'published') }}> Published
                                <span></span>
                            </label> 
                            <label class="mt-radio mt-radio-outline">
                                <input name="status" type="radio" value="unpublished" {{ checked(Input::old('status'), 'unpublished') }}> Unpublished
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
                {!! checkbox_ordered_menu($categories, 0, Input::old('category')) !!}
                </div>

            </div>

            <div class="portlet light bordered">
                <h5 class="sbold">Tags</h5>
                <div class="margin-top-20">
                    <input type="text" class="form-control input-large" name="tags" value="{{ Input::old('tags') }}" data-role="tagsinput">                
                </div>
            </div>

            <div class="portlet light bordered">
                <h5 class="sbold">Featured Image</h5>
                <hr>

                <div class="media-single">
                <input type="hidden" name="image" value="">
                @if( $image = Input::old('image') )
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
                @if( $galleries = Input::old('gallery') )
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

<div class="form-actions">
    <button class="btn btn-primary"><i class="fa fa-check"></i> Add {{ $single }}</button>
</div>
</form>
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
