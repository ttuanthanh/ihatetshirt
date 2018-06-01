@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }} <small>Add</small> 
    <a href="{{ URL::route($view.'.index') }}" class="btn pull-right"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>
</h1>
<!-- END PAGE TITLE-->

<form method="post" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <div class="form-body">

                        <div class="form-group">
                            <label class="col-md-3 control-label">Name</label>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control input-lg" value="{{ Input::old('name') }}" placeholder="Group Name">
                                {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Status</label>
                            <div class="col-md-8">
                                <div class="mt-radio-inline">
                                    <label class="mt-radio mt-radio-outline">
                                        <input name="status" type="radio" value="actived" {{ checked(Input::old('status', 'actived'), 'actived') }}> Actived
                                        <span></span>
                                    </label>
                                    <label class="mt-radio mt-radio-outline">
                                        <input name="status" type="radio" value="inactived" {{ checked(Input::old('status'), 'inactived') }}> Inactived
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <button class="btn btn-primary"><i class="fa fa-check"></i> Add Group</button>
    </div>
</form>
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
