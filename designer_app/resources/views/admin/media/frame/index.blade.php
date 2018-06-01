@extends('layouts.media')

@section('content')
<style>
.header-fixed {
    position: fixed;
    width: 100%;
    z-index: 9;
    background: #f5f5f5;
    border-bottom: 1px solid #dcdcdc;
    top: 0;
    padding: 10px 30px;
    left: 0;
}
</style>
<!-- BEGIN PAGE TITLE-->
<div class="header-fixed"> 
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-5">
        <h4>{{ $label }} <small>{{ $count = count($files) }} file{{ is_plural($count) }}</small></h4>
        </div>        

        <div class="col-md-6 col-sm-6 col-xs-7 text-right">
        @if( Input::get('folder') )
            <a href="{{ $back }}" class="btn btn-xs"><i class="fa fa-long-arrow-left"></i> Back</a>
        @endif
        <a href="{{ URL::route($view.'.add', query_vars()) }}" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Upload</a>
        <button type="button" class="btn btn-success btn-xs btn-insert"><i class="fa fa-check"></i> Insert</button>
        </div>

    </div>

{!! media_breadcrumbs() !!}
</div>
<!-- END PAGE TITLE-->


@include('notification')

<div style="margin: 70px 0 20px 0;">
    <input type="text" name="s" class="form-control input-lg" placeholder="Search for ...">    
</div>

<div>

@foreach($contents as $content)

    @if($content['type'] == 'file')
    <div class="media-thumb file" data-id="{{ $content['name'] }}" 
    data-thumb="{{ $content['thumb'] }}" 
    data-medium="{{ $content['medium'] }}" 
    data-large="{{ $content['large'] }}" 
    data-full="{{ $content['full'] }}">
        <div class="attachment-preview">
            <div class="thumb file">
                <img src="{{ $content['thumb'] }}" class="tooltips" data-container="body" data-placement="top" data-original-title="{{ $content['name'] }}">
            </div>                    
        </div>
    </div>
    @else
    <a href="{{ URL::route('admin.media.index', query_vars('folder=0')) }}&folder={{ $content['url'] }}">
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
    </a>
    @endif

@endforeach    
</div>

<div class="modal fade" id="media-modal" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Attachment Details</h4>
            </div>
            <div class="modal-body text-center">
                <img src="" style="max-width: 100%;">
                <hr>
                <span class="media-filename sbold"></span>
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
@endsection

@section('style')
<style>
.media-manager { 
    margin: 70px 0;
    display: inline-block;
    width: 100%;
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

@stop

@section('script')
<script>
 var delete_url = '{{ URL::route('admin.media.delete', query_vars()) }}',
token = $('meta[name="csrf-token"]').attr('content');

$(document).on('click', '.media-thumb.file', function() {

/*    $('#media-modal').modal();
    var img = $(this).data('large'), id = $(this).attr('id');
    $('.modal-body img').attr('src', img);
    $('.btn-delete-media').attr('data-id', id);
    $('.media-filename').html(img);*/


var mode = getSearchParams('mode');

if( mode == 'gallery' ) {
    if( $(this).hasClass('selected')  ) {
        $(this).removeClass('selected');
    } else {
        $(this).addClass('selected');
    }   
}

if( mode == 'configure-product' || mode == 'single' || mode == 'editor' ) {
    $('.media-thumb').removeClass('selected');

    if( $(this).hasClass('selected')  ) {
        $(this).removeClass('selected');
    } else {
        $(this).addClass('selected');
    }   
}



});

$(document).on('click', '.btn-insert', function(){
    // 

    filepath = '';

    var target = getSearchParams('target');
    var mode = getSearchParams('mode');
    var imgtype = ["gif", "jpeg", "png", "jpg"];

    if( mode == 'editor' ) {

        var path = $('.media-thumb.selected').attr('data-large');     

        var filename = path.split('/').pop().replace(/\.[^/.]+$/, "");

        if ($.inArray( path.split('.').pop().toLowerCase(), imgtype) < 0) {
            var file = '<a href="'+path+'">'+filename+'</a>';
        } else {
            var file = '<img src="'+path+'">';
        }

        $( target, window.opener.tinymce.activeEditor.insertContent(file) );
    }

    if( mode == 'single' ) {
        var medium = $('.media-thumb.selected').attr('data-medium');
        var large = $('.media-thumb.selected').attr('data-large');

        filepath = '<li><div class="media-thumb"><img src="'+medium+'"><input type="hidden" name="image" value="'+large+'"><a href="" class="delete-media"><i class="fa fa-trash-o"></i></a></div></li>';
    }


    if( mode == 'gallery' ) {
        $('.media-thumb.selected').each(function(){
            var thumb = $(this).attr('data-thumb');
            var large = $(this).attr('data-large');

            filepath += '<li><div class="media-thumb"><img src="'+thumb+'"><input type="hidden" name="gallery[]" value="'+large+'"><a href="" class="delete-media"><i class="fa fa-trash-o"></i></a></div></li>';
        });     
    }

    if( mode == 'configure-product' ) {
        var full = $('.media-thumb.selected').attr('data-full');
        var large = $('.media-thumb.selected').attr('data-large');
        $( target, window.opener.document ).css({'background-image': 'url('+full+')'}).attr('data-large', large);
    }

    if( mode == 'single' ) { 
        $( target, window.opener.document ).html(filepath);
    }

    if( mode == 'gallery' ) { 
        $( target, window.opener.document ).append(filepath);
    }

    window.close();

});

function getSearchParams(k){
    var p={};
    location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
    return k?p[k]:p;
}

$(document).on('click', '.btn-delete-media', function(){
    var id = $(this).attr('data-id');
    $('#media-modal').modal('hide');
    $( '#'+id ).fadeOut( "slow", function() {
      $.post(delete_url, { 'filename': id, '_token' : token });
    });
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
