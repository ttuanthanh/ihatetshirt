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

<form method="post" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-8">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <input type="text" name="name" class="form-control input-lg" value="{{ Input::old('name', $info->post_title) }}" placeholder="Group Name">
                    {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}
                </div>
            </div>      

            <div class="portlet light bordered">
                <h5 class="sbold">Permission</h5>
                <hr>
                <?php $mods = json_decode($info->post_content, true); ?>
                <div class="table-responsive">
                  <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                           <th width="1" colspan="2">
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="checkbox" id="check_all">
                                <span></span>
                            </label>        
                            <label for="check_all" class="sbold">All Modules</label>
                            </th>
                            <th>Roles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($modules as $module => $mod)
                        <tr>
                            <td width="1">
                                <?php $checked = @$mods[$module] ? 'checked' : ''; ?>
                                <!-- Front Parent -->
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input id="{{ $module }}" type="checkbox" class="parent_checkbox checkboxes" value="{{ $module }}" {{ $checked }}/>
                                    <span></span>
                                </label>        
                                <!-- Backend Parent -->  
                            </td>
                            <td width="200">
                                <label for="{{ $module }}">
                                {{ ucwords(str_replace('-', ' ', $module)) }}<br>
                                <small class="text-muted">{{ @$mod['note'] }}</small>
                                </label>
                            </td>
                            <td>
                                @foreach($mod as $roles => $role)
                                <?php
                                    $checked = '';
                                    if(@$mods[$module]) {
                                        $checked = in_array($roles, @$mods[$module]) ? 'checked' : ''; 
                                    }
                                ?>
                                <label class="{{ $module.'-'.$roles }}  mt-checkbox mt-checkbox-outline">
                                <input name="{{ $module }}[]" class="checkboxes {{ $module }}" data-name="{{ $module }}" type="checkbox" value="{{  $roles }}" 
                                {{ $checked }}/> 
                                    <span></span>
                          
                                {{ $role }}</label><br>
                                @endforeach
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>  
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="portlet light bordered">
                <div class="row">
                    <div class="col-md-12">
                        <i class="fa fa-calendar"></i> Created on <span class="sbold h5">{{ date_formatted($info->created_at) }}</span> @  <span class="sbold h5">{{ time_formatted($info->created_at) }}</span>       
                    </div>
                    <div class="col-md-12 margin-top-10">
                        <i class="fa fa-calendar"></i> Updated on <span class="sbold h5">{{ date_formatted($info->updated_at) }}</span> @  <span class="sbold h5">{{ time_formatted($info->updated_at) }}</span>       
                    </div>
                </div>
            </div>
            <div class="portlet light bordered">
                <h5 class="sbold">Status</h5>
                <hr>
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        <input name="status" type="radio" value="actived" {{ checked(Input::old('status', $info->post_status), 'actived') }}> Actived
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        <input name="status" type="radio" value="inactived" {{ checked(Input::old('status', $info->post_status), 'inactived') }}> Inactived
                        <span></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <button class="btn btn-primary"><i class="fa fa-check"></i> Update Group</button>
        
        @if( $info->post_status == 'inactived' )
        <a href="#" class="popup btn"
            data-href="{{ URL::route($view.'.delete', $info->id) }}" 
            data-toggle="modal"
            data-target=".popup-modal" 
            data-title="Confirm Move to Trash"
            data-body="Are you sure you want to move to trash ID: <b>#{{ $info->id }}</b>?"><i class="fa fa-trash-o"></i> Move to trash</a>
        @endif  
    </div>
</form>
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
<script>
//On Click Check All
$(document).on('click change','input[id="check_all"]',function() {
    var checkboxes = $('.checkboxes');
    if ($(this).is(':checked')) {
        checkboxes.prop("checked" , true);
        checkboxes.closest('span').addClass('checked');
    } else {
        checkboxes.prop( "checked" , false );
        checkboxes.closest('span').removeClass('checked');
    }
});

//Document ready Check All
$(document).ready(function(){
    //Hide all main checkboxes
    $('.main_modules').hide();
    var a = $(".checkboxes");
    if(a.length == a.filter(":checked").length){
        $('#check_all').prop("checked" , true);
        $('#check_all').closest('span').addClass('checked');
    }
});

//Parent checkboxes
$('.parent_checkbox').click(function() {
    $class = $(this).attr('id');
    var checkboxes = $('.' + $class);
    if ($(this).is(':checked')) {
        checkboxes.prop("checked" , true);
        checkboxes.closest('span').addClass('checked'); 
    } else {
        checkboxes.prop( "checked" , false );
        checkboxes.closest('span').removeClass('checked');
    }
    if($('.parent_checkbox').filter(":checked").length == $('.parent_checkbox').length){
        $('#check_all').prop("checked" , true);
        $('#check_all').closest('span').addClass('checked');
    } else {
        $('#check_all').prop("checked" , false);
        $('#check_all').closest('span').removeClass('checked');
    }
});


//Children checkboxes
$('.checkboxes').click(function() {
    var name = $(this).data('name');
    var $parent = $('input#' + name);
    var a = $('.' + name);        
    if(a.filter(":checked").length > 0){
        $parent.prop("checked" , true);
        $parent.closest('span').addClass('checked');
    } else {
        $parent.prop( "checked" , false );
        $parent.closest('span').removeClass('checked');
    }
});
</script>
@stop