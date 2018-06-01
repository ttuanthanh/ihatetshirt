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

        <div class="tab-content margin-top-20">


            <input type="hidden" name="designer_upload" value="0">
            <input type="hidden" name="designer_qrcode" value="0">
            <input type="hidden" name="designer_hand_draw" value="0">
            <input type="hidden" name="designer_word_cloud" value="0">

            <div class="form-group">
                <div class="col-md-3 control-label">Designer Tips</div>
                <div class="col-md-5">             
                    <textarea name="designer_tips" class="form-control" rows="5">{{ @$info->designer_tips }}</textarea>
                </div>
            </div>

            <div class="form-group hide">
                <div class="col-md-3 control-label"></div>
                <div class="col-md-5">             

                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" name="designer_upload" value="1" {{ checked(@$info->designer_upload, 1) }}> Enabled upload
                            <span></span>
                        </label>
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" name="designer_qrcode" value="1" {{ checked(@$info->designer_qrcode, 1) }}> Enabled QR Code
                            <span></span>
                        </label>
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" name="designer_hand_draw" value="1" {{ checked(@$info->designer_hand_draw, 1) }}> Enabled Hand Draw
                            <span></span>
                        </label>
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" name="designer_word_cloud" value="1" {{ checked(@$info->designer_word_cloud, 1) }}> Enabled Word Cloud
                            <span></span>
                        </label>
                    </div>

                </div>
            </div>


            <div class="form-group">
                <div class="col-md-3 control-label">Google <a href="https://fonts.google.com/" target="_blank">Fonts</a></div>
                <div class="col-md-5">         
                    <div>
                        <input type="text" class="form-control input-large" name="fonts" value="{{ @$info->fonts }}" data-role="tagsinput">                
                    </div>
                </div>
            </div>

            @if( @$info->fonts )
             <?php 
            foreach( explode( ',', $setting->get_setting('fonts')) as $font ) {
                $fonts[$font] = $font;
            }
            ?>
            <div class="form-group">
                <div class="col-md-3 control-label">Default Font</div>
                <div class="col-md-5">             
                    {{ Form::select('default_font', $fonts , @$info->default_font, ['class' => 'form-control select2']) }}
                </div>
            </div>
            @endif

            <div class="form-group">
                <div class="col-md-3 control-label">Default font size</div>
                <div class="col-md-2">             
                    <input type="text" name="default_font_size" class="form-control numeric" value="{{ @$info->default_font_size }}">
                </div>
            </div>

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
