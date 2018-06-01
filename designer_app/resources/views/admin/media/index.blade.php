@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $label }} <small>{{ $count = count($files) }} file{{ is_plural($count) }}</small>
    <div class="pull-right">
    @if( Input::get('folder') )
        <a href="{{ $back }}" class="btn"><i class="fa fa-long-arrow-left"></i> Back</a>
    @endif
    <a href="{{ URL::route($view.'.add', query_vars()) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Upload</a>
    <a class="btn blue btn-outline sbold" data-toggle="modal" href="#mkdir"> Create Folder </a>
    </div>
</h1>
<!-- END PAGE TITLE-->

<p>{!! media_breadcrumbs() !!}</p>

@include('notification')

<input type="text" name="s" class="form-control input-lg" placeholder="Search for ...">

<div class="media-manager margin-top-20">
@foreach($contents as $content)

    @if($content['type'] == 'file')
    <div class="media-thumb file" id="{{ $content['id'] }}" data-id="{{ $content['name'] }}" data-large="{{ has_image($content['large']) }}">
        <div class="attachment-preview">
            <div class="thumb file">
                <img src="{{ $content['thumb'] }}" class="tooltips" data-container="body" data-placement="bottom" data-original-title="{{ $content['name'] }}">
            </div>                    
        </div>
    </div>
    @else

    <div class="media-thumb folder" data-url="{{ $content['url'] }}" data-id="{{ $content['name'] }}">
        <div class="attachment-preview">
            <div class="thumb folder">

                @if( $content['count'] )
                <img src="{{ asset('assets/uploads/folder-o.png') }}">
                @else
                <img src="{{ asset('assets/uploads/folder.png') }}">                
                @endif
                <br>   
                <small>{{ strtoupper(str_limit($content['name'], 12)) }}</small>
            </div>        
        </div>
    </div>
    @endif

@endforeach    
</div>

<div class="modal fade" id="media-modal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Attachment Details</h4>
            </div>
            <div class="modal-body text-center">
                <img src="" style="max-width: 100%;">
                <hr>

                <div class="mt-clipboard-container">
                    <span class="media-filename sbold"></span>
                </div>
                    <a href="javascript:;" class="btn btn-xs mt-clipboard" data-clipboard-action="copy" data-clipboard-target=".media-filename">
                    <i class="icon-note"></i> Copy URL </a>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger btn-delete-media" data-id="">Delete Permanently</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="mkdir">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Create Folder</h4>
            </div>
            <form method="post" action="{{ route('admin.media.mkdir') }}">
                {{ csrf_field() }}
                <input type="hidden" name="folder" value="{{ Input::get('folder') }}">
                <div class="modal-body">
                    <input type="text" name="new" class="form-control input-lg" placeholder="Folder Name">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

@section('style')
<style>
.media-manager { 
    display: inline-block;
    width: 100%;
    overflow-y: scroll;
    height: 460px;
}
.media-thumb { 
    display: inline-table;
    width: calc(12.5% - 10px);
    margin: 0 5px 10px;
    float: left;
    height: 124px;
}
.attachment-preview {
    border: 1px solid #9E9E9E;
    -webkit-box-shadow: inset 0 0 15px rgba(0,0,0,.1), inset 0 0 0 1px rgba(0,0,0,.05);
    box-shadow: inset 0 0 15px rgba(0,0,0,.1), inset 0 0 0 1px rgba(0,0,0,.05);
    background: #eee;
    cursor: pointer;
    position: relative;
    vertical-align: middle;
    display: table-cell;
}
.media-thumb:hover, .media-thumb.selected {
    border: 2px solid #5b9dd9;
    -webkit-box-shadow: inset 0 0 2px 3px #fff, inset 0 0 0 7px #5b9dd9;
    box-shadow: 0 0 0px 0px #5b9dd9, 0 0 1px 1px #5b9dd9;
    outline: 0;
}  
.attachment-preview .thumb.folder {
    text-align: center;
}
.attachment-preview .thumb.file img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

@media only screen and (max-width: 850px) {
 .media-thumb { width: calc(16.5% - 10px);  }
}
@media only screen and (max-width: 650px) {
 .media-thumb { width: calc(25% - 10px);  }
}
@media only screen and (max-width: 450px) {
 .media-thumb { width: calc(33.33% - 10px); }
}
@media only screen and (max-width: 350px) {
 .media-thumb { width: calc(50% - 10px);  }
}


</style>
@stop

@section('plugin_script') 
    <script src="{{ asset('assets/global/plugins/clipboardjs/clipboard.min.js') }}" type="text/javascript"></script>
@stop

@section('script')
<script>
var delete_url = '{{ URL::route('admin.media.delete', query_vars()) }}', 
token = $('meta[name="csrf-token"]').attr('content');

$(document).on('click', '.media-thumb.file', function() {
    $('#media-modal').modal();
    var img = $(this).data('large'), id = $(this).attr('id');
    $('.modal-body img').attr('src', img);
    $('.btn-delete-media').attr('data-id', id);
    $('.media-filename').html(img);

/*    if( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
    } else {
        $(this).addClass('selected');
    }*/

});


$(document).on('click', '.btn-delete-media', function(){
    var id = $(this).attr('data-id');
    $('#media-modal').modal('hide');
    $( '#'+id ).fadeOut( "slow", function() {
      $.post(delete_url, { 'filename': id, '_token' : token });
    });
});

$(document).on('click', '.media-thumb.folder', function(){
    var url = $(this).attr('data-url');
    location.href = '?folder='+url;
});

$(window).on('resize', function(){
    media_height();
});

$(document).ready(function() {
    media_height();
});

function media_height() {
    var cw = $('.media-thumb').width();
    $('.media-thumb').css({'height':cw+'px'});    
}

$(document).on('keyup', '[name=s]', function() {
    $('.media-thumb').hide();
    var txt = $(this).val();
    $('.media-thumb').each(function(){
       if($(this).attr('data-id').toUpperCase().indexOf(txt.toUpperCase()) != -1){
           $(this).show();
       }
    });
});
</script>
@stop
