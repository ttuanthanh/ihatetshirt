@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $label }}</h1>
<!-- END PAGE TITLE-->

@include('admin.settings.tab')

<form class="form-horizontal" method="post">
{{ csrf_field() }}
<div class="portlet light bordered">
    <div class="portlet-body">

        <div class="tab-content">

            <!-- BEGIN TAB CONTENT -->
            <div class="form-group">
                <div class="col-md-3 control-label">Admin Email</div>
                <div class="col-md-5">             
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input type="text" class="form-control" name="admin_email" value="{{ @$info->admin_email }}"> 
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 control-label">Currency</div>
                <div class="col-md-5">             
                    {{ Form::select('currency', currencies(), @$info->currency, ['class' => 'form-control select2']) }}
                </div>
            </div>

             <div class="form-group">
                <div class="col-md-3 control-label">Address</div>
                <div class="col-md-5">             
                     <textarea class="form-control" name="address" rows="4">{{ @$info->address }}</textarea>
                </div>
            </div>

             <div class="form-group">
                <div class="col-md-3 control-label">Telephone</div>
                <div class="col-md-5">             
                     <input type="text" name="telephone" class="form-control" value="{{ @$info->telephone }}">
                </div>
            </div>

             <div class="form-group">
                <div class="col-md-3 control-label">Coupon</div>
                <div class="col-md-5">             
                    <input type="hidden" name="coupon" value="0">
                    <div class="mt-checkbox-inline">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" name="coupon" value="1" {{ checked(@$info->coupon, 1) }}> Enabled
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>

             <div class="form-group">
                <div class="col-md-3 control-label">Invoice Logo</div>
                <div class="col-md-3">             
                    <input type="hidden" name="image" value="">
                    <div class="media-single">
                    @if( @$info->invoice_logo )
                    <li>
                        <div class="media-thumb">
                             <img src="{{ has_image($info->invoice_logo) }}">
                             <input type="hidden" name="image" value="{{ $info->invoice_logo }}">
                            <a href="" class="delete-media"><i class="fa fa-trash-o"></i></a>
                        </div>
                    </li>
                    @endif
                    </div>
                    <a href="" class="filemanager btn btn-default" data-target=".media-single" data-type="images">Select Logo</a>

                </div>
            </div>
            <!-- END TAB CONTENT -->



        </div>

    </div>
</div>

<div class="form-actions">
    <button class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button> 
</div>

</form>
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
