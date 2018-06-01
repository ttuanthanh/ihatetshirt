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
            <div class="row">
                <div class="col-md-7">

                    <div class="form-group">
                        <div class="col-md-5 control-label">Site Title</div>
                        <div class="col-md-7">             
                            <input type="text" name="site_title" class="form-control" value="{{ @$info->site_title }}">
                        </div>
                    </div>

                     <div class="form-group">
                        <div class="col-md-5 control-label">Site Meta Keyword</div>
                        <div class="col-md-7">             
                            <textarea class="form-control" name="meta_keyword" rows="5">{{ @$info->meta_keyword }}</textarea>
                        </div>
                    </div>

                     <div class="form-group">
                        <div class="col-md-5 control-label">Site Meta Description</div>
                        <div class="col-md-7">             
                            <textarea class="form-control" name="meta_description" rows="5">{{ @$info->meta_description }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-5 control-label">Footer Title</div>
                        <div class="col-md-7">             
                            <input type="text" name="copy_right" class="form-control" value="{{ @$info->copy_right }}">
                        </div>
                    </div>                    
                    
                </div>
                <div class="col-md-5">
                    <div class="form-group">

                        <div class="col-md-4 control-label">Site Logo</div>
                        <div class="col-md-7">             
                            <input type="hidden" name="image" value="">
                            <div class="media-single">
                            @if( @$info->logo )
                            <li>
                                <div class="media-thumb">
                                    <img src="{{ has_image($info->logo) }}">
                                    <input type="hidden" name="image" value="{{ $info->logo }}">
                                    <a href="" class="delete-media"><i class="fa fa-trash-o"></i></a>
                                </div>
                            </li>
                            @endif
                            </div>
                            <a href="" class="filemanager btn btn-default" data-target=".media-single" data-type="images">Select Logo</a>


                        </div>

                    </div>
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
