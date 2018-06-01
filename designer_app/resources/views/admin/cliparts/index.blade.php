@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $label }} <small>{{ $count }} item{{ is_plural($count) }}</small>
    <a href="{{ URL::route($view.'.add') }}" class="btn pull-right"><i class="fa fa-plus"></i> Add New</a>
</h1>
<!-- END PAGE TITLE-->

@include('notification')

@include('partials.filter')

<form method="get">

@if( Input::get('type') )
<input type="hidden" name="type" value="{{ Input::get('type') }}">
@endif

<div class="row margin-top-10">
    <div class="col-md-3">
        <div class="input-group">
            <select class="form-control" name="action">
                <option value="">Bulk Actions</option>
                @if( Input::get('type') == 'trash' )
                <option value="restore">Restore</option>
                <option value="destroy">Delete Permanently</option>
                @else                
                <option value="trash">Move to Trash</option>
                @endif
            </select>     
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary" type="button">Apply</button>
            </span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            {{ Form::select('parent', ['' => 'All Categories'] + $categories, Input::get('parent'), ['class' => 'form-control select2']) }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input type="text" class="form-control" name="s" placeholder="Enter Search ..." value="{{ Input::get('s') }}">  
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary" type="button">Search</button>
            </span>
        </div>
    </div>
</div>


<div class="margin-top-20">
    <label class="mt-checkbox mt-checkbox-outline">
        <input type="checkbox" value="1" name="" id="check_all">
        <span></span> 
        Select All
    </label>     
</div>
 

<div class="row">    
@foreach($rows as $row)
<?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
<div class="col-md-3 col-sm-3">
    <div class="portlet light bordered">

           @if( $row->post_status == 'actived' )
            <label class="mt-checkbox mt-checkbox-disabled">
                <span></span> 
            </label>  
            @else
            <label class="mt-checkbox mt-checkbox-outline sbold">
                <input type="checkbox" value="{{ $row->id }}" name="ids[]" class="checkboxes">
                <span></span> 
            </label>           
            @endif  

            <div class="pull-right">
                {{ status_ico($row->post_status) }}
            </div>

          @if( $postmeta->image )
            <a href="{{ has_image(str_replace('-large.png', '.svg', $postmeta->image)) }}" class="btn-img-preview" data-title="{{ $row->post_title }}">
                <img src="{{ has_image(str_replace('-large.png', '.svg', $postmeta->image)) }}" class="fullwidth" height="150"> 
            </a>
            @else
            <img src="{{ has_image() }}" class="img-responsive img-thumb"> 
            @endif           

            <h5 class="sbold">{{ $row->post_title }}</h5>

            <div class="small uppercase">
            @if( Input::get('type') == 'trash' )
            <a href="#" class="popup"
                data-href="{{ URL::route($view.'.restore', $row->id) }}" 
                data-toggle="modal"
                data-target=".popup-modal" 
                data-title="Confirm Restore"
                data-body="Are you sure you want to restore ID: <b>#{{ $row->id }}</b>?">Restore</a> | 
            <a href="#" class="popup"
                data-href="{{ URL::route($view.'.destroy', $row->id) }}" 
                data-toggle="modal"
                data-target=".popup-modal" 
                data-title="Confirm Delete Permanently"
                data-body="Are you sure you want to delete permanently ID: <b>#{{ $row->id }}</b>?">Delete Permanently</a>
            @else
                <a href="{{ URL::route($view.'.edit', $row->id) }}">Edit</a> |   
                @if( $row->post_status == 'actived' )
                <span class="text-muted">Move to trash</span>
                @else
                <a href="#" class="popup"
                    data-href="{{ URL::route($view.'.delete', $row->id) }}" 
                    data-toggle="modal"
                    data-target=".popup-modal" 
                    data-title="Confirm Move to Trash"
                    data-body="Are you sure you want to move to trash ID: <b>#{{ $row->id }}</b>?">Move to trash</a>
                @endif
            @endif                
            </div>
            <?php $category = @$post->find($row->parent)->post_title; ?>
            <h5>{{ $category ? $category : 'uncategorised' }}</h5>
    </div> 
</div>
@endforeach
</div>


    @if( ! count($rows) )
        <div class="well">No cliparts found.</div>
    @endif
    
    {{ $rows->links() }}

</form>
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
