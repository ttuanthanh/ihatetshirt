@extends('layouts.designer')

@section('content')


@include('frontend.designer.tool')

<div id="qrcode"></div>
<div id="wordcloud"></div>
<div class="css_gen"></div>
<div class="svgElements"></div>

<div class="modal fade" id="get-quote" tabindex="1" data-keyboard="false" data-backdrop="static" data-url="{{ route('frontend.get-quote', ['action' => 'save-modal', query_vars()]) }}">
    <div class="modal-dialog"></div>
</div>

<div class="modal fade" id="edit-colors" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select Ink Colors</h4>
            </div>
            <div class="modal-body">
                    
                <div class="row">
                    <div class="col-md-4">
                        <img src="" class="img-thumbnail screen-img">
                    </div>
                    <div class="col-md-8">
                        <h5 class="sbold">Select Ink Colors</h5>
                        <p>Select the colors that appear in your design.<br></p>
                        <p>This helps us determine the pricing based on the number of colors in your design.</p>
                        <p class="text-warning"><b>Note</b>: this function not change color of image.<br> Maximum color is <b>6</b>.</p>


                        <ul id="screen-colors" class="colors">
                            @foreach($colors as $color_k => $color_v)
                            <li>
                                <span class="bg-color tooltips c-{{ $color_k }}" 
                                title="{{ $color_v }}" 
                                data-color="{{ $color_k }}"
                                style="background-color:{{ $color_k }};"></span>
                            </li>
                            @endforeach
                        </ul>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <span class="msg-color text-danger sbold"></span>
                <button type="button" class="btn btn-primary btn-choose-color" 
                onclick="angular.element('#productApp').scope().chooseColors()">Choose Colors</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="repaint-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Paint Tool</h4>
            </div>
            <div class="modal-body">
                <img src="{{ asset('assets/uploads/loaders/4.gif') }}" class="paint-loader" style="display:none;">
                <div class="list-color">
                    <button class="dropdown-color btn btn-color btn-repaint" style="background:#000000;" data-color="#000000" data-target="repaint">
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <span class="text-repaint">Black</span>
                </div>   


                <div class="paint-result text-center">
                    <img src="" data-click="0">    
                </div>   

                <div id="repaint-colors" style="display:none;"></div>

                <div class="row margin-top-20">
                    <div class="col-md-3">
                        <button class="btn btn-primary btn-save-repaint" onclick="clear_repaint('save')"><i class="fa fa-check"></i> Save</button>                     
                    </div>
                    <div class="col-md-9 text-right">
                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true" onclick="clear_repaint('cancel')">Cancel</button>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="graphic-remove-bg">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Confirm Remove Background</h4>
            </div>
            <div class="modal-body">
                <h4 class="text-center">Do you want to remove background colors?</h4>

                <div class="margin-top-20">
                    <div class="btn-group btn-group-justified">
                        <a href="#" class="btn blue btn-choose-color" 
                        onclick="angular.element('#productApp').scope().onFileSelect(1)">YES</a>
                        <a href="#" class="btn green btn-choose-color" 
                        onclick="angular.element('#productApp').scope().onFileSelect(0)">NO</a>
                    </div>                
                </div>   

            </div>

        </div>
    </div>
</div>


<?php $colors = App\Post::where(['post_type' => 'color'])->get(); ?>
<div class="popup-colors">
    <h5>Click to change color</h5>
<ul>
    @foreach($colors as $color)
    <li>
        <span class="bg-color tooltips c-#FFB500" 
        title="{{ $color->post_title }}" 
        data-color="{{ $color->post_content }}"
        style="background-color:{{ $color->post_content }};"></span>
    </li>
    @endforeach 
</ul>   
<div class="input-group">
 <input type="text" name="hex" class="colorpickr" data-control="hue" readonly>
    <div class="input-group-btn">
        <button class="btn blue btn-select-hex" type="button">OK</button>
    </div>
</div>


</div>


<script>
 var SETTINGS_INIT_URL = '{{ URL::route('frontend.designer.settings') }}',
     APP_URL = '{{ url('/') }}';
</script>

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/slick/slick.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/slick/slick-theme.css') }}"/>


<link href="{{ asset('assets/global/plugins/socicon/socicon.css') }}" rel="stylesheet" type="text/css" />
<style>
.colorpickr {
    width: 100%;
    height: 34px;    
}
.btn-color {
    background: url('{{ asset('assets/uploads/transparent-bg.png')}}');
    background-size: 200%;
}
.graphic_types > div {
	line-height: 30px;
	height: auto;
}	
.modal-backdrop, .modal-backdrop.fade.in {
    position: fixed;   
}
.modal-open .modal {
    z-index: 99999;    
}
.color-box {
    height: 25px;
    width: 25px;
    border: 1px solid #9E9E9E;
    display: inline-block;
    margin: 0 0 -8px 5px;
}
.sortable {
    cursor: move;
}
.ul_layer_canvas.sortable li:hover {
    background: #f1f1f1;    
} 
.ui-sortable-helper {
    background: #ffe997 !important;
}
.popup-colors {
    z-index: 99999 !important;
}
.graphic_types > div {
    margin: 0 5px 10px 5px !important;
}
.canvas-container-outer {
    max-width: 620px !important;    
}

.table-team td {
    padding: 0 10px 0 0;
}   
.btn-color {
    border: 1px solid #9E9E9E !important;
}

.popup-colors {
    margin-top: 5px;
    position: absolute;
    width: 258px;
    background: #fff;
    border: 1px solid #9E9E9E;
    z-index: 9;
    overflow-y: auto;
    max-height: 251px;
    padding: 10px;
    box-shadow: 0 2px 4px 0 rgba(0,0,0,.25);
    display: none;
}
.popup-colors ul li {
    display: inline-block;
}
.btn-color {
    height: 42px;
    border-radius: 5px !important;
}
.name-number-data {
    display: none;
}
.paint-loader, .canvas-loader {
    position: absolute;
    top: calc(50% - 63px);
    left: calc(50% - 63px);
    background: #ffffffdb;
    border-radius: 125px;
    width: 126px;    
    z-index: 1;  
    box-shadow: 1px 0 20px 11px #000000ab;
}
.canvas-container-outer {
    position: relative;
}
.blur {
  -webkit-filter: blur(35px);
  -moz-filter: blur(35px);
  -o-filter: blur(35px);
  -ms-filter: blur(35px);
  filter: blur(35px);
}

.nav-tabs { border: none; }
.nav-tabs li {
    float: left;
    margin: 0 .5em 0 0;
}
.nav-tabs li a {
    position: relative;
    border-bottom: 1px solid #aeaeae!important;
    background: #ddd;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#ddd));
    background-image: -webkit-linear-gradient(top, #fff, #ddd);
    background-image: -moz-linear-gradient(top, #fff, #ddd);
    background-image: -ms-linear-gradient(top, #fff, #ddd);
    background-image: -o-linear-gradient(top, #fff, #ddd);
    background-image: linear-gradient(to bottom, #fff, #ddd);
    font-size: .8em;
    float: left;
    text-decoration: none;
    color: #444;
    text-shadow: 0 1px 0 rgba(255,255,255,.8);
    -webkit-border-radius: 5px 0 0 0;
    -moz-border-radius: 5px 0 0 0;
    border-radius: 5px 0 0 0;
    -moz-box-shadow: 0 2px 2px rgba(0,0,0,.4);
    -webkit-box-shadow: 0 2px 2px rgba(0,0,0,.4);
    box-shadow: 0 2px 2px rgba(0,0,0,.4);
    padding: 10px 8px;
}
.nav-tabs li a::after {
content: '';
    position: absolute;
    z-index: 1;
    top: 0;
    right: -.5em;
    bottom: 0;
    width: 1em;
    background: #ddd;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#ddd));
    background-image: -webkit-linear-gradient(top, #fff, #ddd);
    background-image: -moz-linear-gradient(top, #fff, #ddd);
    background-image: -ms-linear-gradient(top, #fff, #ddd);
    background-image: -o-linear-gradient(top, #fff, #ddd);
    background-image: linear-gradient(to bottom, #fff, #ddd);
    -moz-box-shadow: 2px 2px 2px rgba(0,0,0,.4);
    -webkit-box-shadow: 2px 2px 2px rgba(0,0,0,.4);
    box-shadow: 2px 2px 2px rgba(0,0,0,.4);
    -webkit-transform: skew(10deg);
    -moz-transform: skew(10deg);
    -ms-transform: skew(10deg);
    -o-transform: skew(10deg);
    transform: skew(10deg);
    -webkit-border-radius: 0 5px 0 0;
    -moz-border-radius: 0 5px 0 0;
    border-radius: 0 5px 0 0;
}  
.nav-tabs li.active a, .nav-tabs li.active a::after {
    background: #fff;
    z-index: 9;
}
.slick-prev::before, .slick-next::before {
    opacity: 0.5;
    font-size: 30px;  
    color: #3d3c3a !important;
}
.slick-prev, .slick-next, .slick-arrow:focus {
    z-index: 2;
    top: 45%;
}

.slick-arrow:hover {
    opacity: 1;
}
.slick-prev {
    left: -25px;
}
.slick-next {
    right: -15px;
}
.slick-slider {
    width: 90%;
    margin: 0 auto;    
}
.slick-thumb  { 
    width: calc(100% - 14px) !important;
    margin: 0 0 20px 7px;
    border: 1px solid #ccc;
    border-radius: 5px;
}   

.graphic_options ul li > div {
    background-image: linear-gradient(to bottom, #fff, #ddd);
    border: 1px solid #c5c5c5;
    padding: 2px 0 7px;
    box-shadow: 1px 0 3px #cacaca;
}
.md-button  {    
    background-image: linear-gradient(to bottom, #fff, #ddd) !important;
}
button.md-button:hover:not([disabled]) {
    color: #616161 !important;
    background-image: linear-gradient(to bottom, #ddd, #fff) !important;
}
.slick-slide img {
    padding: 10px;
}
.form-group .flex {
    border: 1px solid rgb(203, 203, 203);
    border-radius: 5px;
    margin: 5px 0 25px;    
}


.ui-widget.ui-widget-content {
    border: 1px solid #000000;
    background: #000000d1;
    color: #fff;
    position: absolute;
    z-index: 99999;
}
</style>
@stop


@section('plugin_script') 

<script src="{{ asset('designer-tool/assets/angular.js') }}"></script>

<script src="{{ asset('designer-tool/assets/angular-animate.js') }}"></script>
<script src="{{ asset('designer-tool/assets/angular-aria.js') }}"></script>

<script src="{{ asset('designer-tool/assets/angular-material.js') }}"></script>

<script src="{{ asset('designer-tool/assets/ng-file-upload/angular-file-upload.js') }}"></script>
<script src="{{ asset('designer-tool/assets/ng-file-upload/angular-file-upload-shim.js') }}"></script>

<script src="{{ asset('designer-tool/assets/qr-code/raphael-2.1.0-min.js') }}"></script>
<script src="{{ asset('designer-tool/assets/qr-code/qrcodesvg.js') }}"></script>

<script src='{{ asset('designer-tool/assets/word-cloud/d3.v3.min.js') }}'></script>
<script src='{{ asset('designer-tool/assets/word-cloud/d3.layout.cloud.js') }}'></script>

<script src="{{ asset('designer-tool/assets/angular-sanitize.min.js') }}"></script>
<script src="{{ asset('designer-tool/assets/ng-scrollbar.min.js') }}"></script> 

<script src="{{ asset('designer-tool/assets/fabric/fabric.js') }}"></script>
<script src="{{ asset('designer-tool/assets/fabric/fabric.min.js') }}"></script>
<script src="{{ asset('designer-tool/assets/fabric/fabricCanvas.js') }}"></script>
<script src="{{ asset('designer-tool/assets/fabric/fabricConstants.js') }}"></script>
<script src="{{ asset('designer-tool/assets/fabric/fabricDirective.js') }}"></script>
<script src="{{ asset('designer-tool/assets/fabric/fabricDirtyStatus.js') }}"></script>
<script src="{{ asset('designer-tool/assets/fabric/fabricUtilities.js') }}"></script>
<script src="{{ asset('designer-tool/assets/fabric/fabricWindow.js') }}"></script>
<script src="{{ asset('designer-tool/assets/fabric/fabric.curvedText.js') }}"></script>
<script src="{{ asset('designer-tool/assets/fabric/fabric.customiseControls.js') }}"></script>

<script src="{{ asset('designer-tool/assets/colorpicker/bootstrap-colorpicker-module.js') }}"></script>
<script src="{{ asset('designer-tool/js/application.js') }}"></script>

<script src="{{ asset('designer-tool/assets/file/fileSaver.js') }}"></script>
<script src="{{ asset('designer-tool/assets/pdf/jspdf.debug.js') }}"></script>

<script src="{{ asset('assets/global/plugins/jquery-repeater/jquery.repeater.js') }}" type="text/javascript"></script>

 <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
<script src="{{ asset('css/jquery-ui.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/plugins/slick/slick.min.js') }}"></script>



<script>
function init_slick() {
    $('.slick').slick({
      infinite: true,
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 4,
      adaptiveHeight: true
    });    
}
init_slick();


function clear_repaint(type) {
    if( $('.paint-result img').attr('data-click') == 1 ) {
        $.ajax({
            url: '{{ route('frontend.designer.clear-repaint') }}', 
            type: "POST",           
            data: { 
                'orig' : $('.paint-result img').attr('data-orig'),
                'src' : $('.paint-result img').attr('src'),
                'type': type
             }, 
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},    
            success: function(res) { 

            }
        });
    }
}

$(document).on('click', '.btn-paint-tool', function(e) {
    $('#repaint-modal').modal('show');
    var src = $('.screen-img').attr('src')
    $('.paint-result img').attr('src', src).attr('data-orig', src);  
    $('#repaint-colors').html('');          
    $('.paint-result img').attr('data-click', 0); 

});

$(document).on('click', '.btn-save-repaint', function(e) {
    var img = $('.paint-result img').attr('src');                    
    angular.element('#productApp').scope().setSrc(img);
    $('#repaint-modal').modal('hide');
    $('.screen-img').attr('src', img);

    var colors = $('#print-color-added span').map(function() {
        return [$(this).data('color')];
    }).get();

    $('#repaint-colors span').each(function() {
        var color = $(this).text();
        if( $.inArray(color, colors)==-1 ) {    
            $('#print-color-added').append('<span class="bg-colors" data-color="'+color+'" style="background-color:'+color+';"></span>'); 
        }
    });

    angular.element('#productApp').scope().choosePrintColors();

});



getSearchParams = function (k){
    var p={};
    location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
    return k?p[k]:p;
};

$(document).on('click', '.start-d', function(e) {
    $('a.graphics').click();
});

$(document).on('click', '.canvas_sub_image li', function(e) {
    $('.canvas_sub_image li').removeClass('selected');
    $(this).addClass('selected');
});



$(document).on('click', '.btn-publish', function(e) {
    e.preventDefault();
    url = $('#get-quote').data('url');
    var pid = $('body').attr('data-product-id'),
        reload = getSearchParams('reload'), 
        index = $('body').attr('data-color-index');

    $.get(url, {'pid':pid,'index':index,'reload':reload}, function(res) {
        $('#get-quote .modal-dialog').html(res);

        $('#get-quote').modal('show');
        $('.d-colors span:visible').each(function(i){
           $('input.sp-'+i).val($(this).html());
           $('span.sp-'+i).html($(this).html());
        });

        get_quote();

        var sum = 0;
        $('.d-colors span:visible').each(function(){
            sum += parseFloat($(this).text()); 
        });

        $('.start-note').show();
        $('.customer-details').hide();

        if( sum ) {
            $('.customer-details').show();
            $('.start-note').hide();
            
            if( $('#design_title').val() && $('#email').val()  ) {
                $('.start-note').hide();
                $('.customer-details').hide();
            }
        }


    });
});


$(".select2").select2();  
$(document).on('change', '[name=category]', function() {
    var id = $(this).val();
    $('.product-list').hide();
    $('.product-colors').hide();
    $('.product-loader').show();
    $('.slick').slick('unslick');

    $.get('', {'category': id }, function(res) {
        $('.product-loader').hide();
        $('.product-list').fadeIn('fast').html(res);
        init_slick();

    });
});

$(document).on('click', '.img-thumb', function() {
    var id = $(this).data('id'),
        sizes = $(this).data('sizes');
    $('.product-list').hide();
    $('.product-loader').show();

    $('body').attr('data-sizes', JSON.stringify(sizes));
    $.get('', {'color': id }, function(res) {
        $('.product-loader').hide();
        $('.product-colors').fadeIn('fast').html(res);
        $('.tooltips').tooltip();
    });
});

$(document).on('click', '.btn-edit-colors', function() {
    $('#edit-colors').modal('show');
    $('#screen-colors li span').removeClass('selected');
    $('#print-color-added span').each(function() {
        var color = $(this).data('color');
        $('#screen-colors [data-color="'+color+'"]').addClass('selected');
    });

});



$(document).on('click', '.btn-choose-color', function() {
    if( $('#screen-colors li span.selected').length ) {
        $('#print-color-added, .msg-color').html('');
        $('#screen-colors li span.selected').each(function() {
            var color = $(this).data('color');
            $('#print-color-added').append('<span class="bg-colors" data-color="'+color+'" style="background-color: '+color+';"></span>');
        });
        $('#edit-colors').modal('hide');        
    } else {
        $('.msg-color').html('Please select color above then click this button.');
    }
});

$(document).on('click', '#screen-colors .bg-color', function() {

    if( $('#screen-colors li span.selected').length < 6 ) {
        $('.msg-color').html('');
    } else {
        $('.msg-color').html('Maximum color is 6.');
        if( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            $('.msg-color').html('');
        }
        return false;
    }
    $(this).toggleClass('selected');
});

$(document).on('click', '.product-colors .bg-color', function() {
    $('.bg-color').removeClass('selected');
    $(this).addClass('selected');
});


$(document).on('click', '.btn-change-product', function(e) {
    e.preventDefault();
    $('.slick').slick('unslick');
    $('.product-list').show();
    $('.product-colors').hide();
    init_slick();
});

$(document).on('click', '.mini-cart .dropdown-toggle', function(e) {
    $('.mini-cart .dropdown-menu').slideToggle();
});
$(document).on('click', '.btn-calculate', function(e) {
    $('.result').show();
    get_quote();
});


function get_quote() {
    formElement = document.querySelector("#form-product");
    $.ajax({
        url: $('#form-product').attr('action'), 
        type: "POST",           
        data: new FormData( formElement ), 
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,
        cache: false,             
        processData:false,     
        success: function(data) 
        { 
            val = JSON.parse(data);

            $.each( val.msg, function( key, value ) {
              $('.msg-'+key).html(value);
            });   
            $('.total-qty').html(val.quantity);
            $('.unit-price').html(val.unit_price);
            $('.total-price').html(val.total_price);

            $('.btn-buy').addClass('disabled');
            if( parseInt(val.quantity) >= 6 ) {
                $('.btn-buy').removeClass('disabled');
            }

        }
    });
}

var meta_description = $('[name="description"]').attr('content');    
function shareOnFacebook() {
    u = location.href;
    window.open('http://www.facebook.com/share.php?u=' + encodeURIComponent(u) + '&title=' + encodeURIComponent(meta_description), 'popupwindow', 'scrollbars=yes,width=800,height=400');
    return false;

}
function shareOnTwitter() {
    u = location.href;
    t = document.title;
    window.open('https://twitter.com/share?url=' + encodeURIComponent(u) + '&text=' + encodeURIComponent(meta_description), 'popupwindow', 'scrollbars=yes,width=800,height=400');
    return false;
}
function shareOnGooglePlus() {
    u = location.href;
    t = document.title;
    popwin = window.open('https://plus.google.com/share?url=' + encodeURIComponent(u) + '&title=' + encodeURIComponent(meta_description), 'sharer', 'toolbar=0,status=0,width=626,height=436');
    return false;
}
function shareOnPinterest() {
    u = location.href;
    t = document.title;
    popwin = window.open('https://pinterest.com/pin/create/bookmarklet/?url=' + encodeURIComponent(u) + '&description=' + encodeURIComponent(meta_description) + '&media=', 'sharer', 'toolbar=0,status=0,width=626,height=436');
    return false;
}

// document.addEventListener('contextmenu', event => event.preventDefault());
function load_back_design() {
    if( $('body').attr('data-index') != 1 ) {
       $('#subimg-1').trigger('click');    
    }    
}


$(document).on('click', '.team-font li', function() {
    var font = $(this).text(),
        name = $(this).data('name');
     $('.font-'+name).find('.object-font-family-preview').text(font).css({'font-family':font});
    if( $('[data-name="'+name+'"]').is(':checked') ) {
        load_back_design();
    }
});
$(document).on('click', '.toggle-team', function() {
    load_back_design();
    $($(this).data('target')).toggle();
    var name = $(this).data('name'), 
        color = $('.btn-'+name).attr('data-color'),
        font = $('.font-'+name).find('.object-font-family-preview').html();

    if( $(this).is(':checked') ) {
        angular.element('#productApp').scope().addNameNumber(name, color, font);
    } else {
        angular.element('#productApp').scope().removeNameNumber(name);        
    }

    if( $('.toggle-team:checked').length ) {
        load_sizes();
        $('.name-number-data').show();
    } else {
        $('.mt-repeater-item').remove();
        $('.mt-repeater-add').click();
        $('.name-number-data').hide();        
    }

});

$(document).on('click', '#my-tab-content .bg-color', function() {
    // $('.toggle-team').prop('checked', false);
    $('.canvas-loader').show();
    $('.canvas-container').addClass('blur');

    $('.mt-repeater-item').remove();
    $('.mt-repeater-add').click();
    $('.name-number-data, .team-name, .team-number').hide();   

    setTimeout(function () {
        load_sizes();
    }, 100);
});

$(document).on('click', '.btn-select-hex', function(e) {
    e.preventDefault();
    var color = $('[name="hex"]').val(),
        target = $('.popup-colors').attr('data-target');

    $('.btn-'+target).css({'background': color }).attr('data-color', color);
    $('.text-'+target).html(color);
    $('.popup-colors').slideUp();

    $('.input-'+target).val(color).trigger('change');

    if( target == 'add-name' || target == 'add-number' ) {
        $('.btn-add-number, .btn-add-name').css({'background': color }).attr('data-color', color);
        if( $('[data-name="'+target+'"]').is(':checked') ) {
            load_back_design();
        }
        if( $('[data-name="add-number"]').is(':checked') ) {
            angular.element('#productApp').scope().nameNumberFill(color, 'add-number'); 
        }
        if( $('[data-name="add-name"]').is(':checked') ) {
            angular.element('#productApp').scope().nameNumberFill(color, 'add-name');                      
        }
    }
});

$(document).on('click', '.popup-colors span', function() {
    var color = $(this).data('color'),
        title = $(this).attr('title'),
        target = $('.popup-colors').attr('data-target'),
        oldcolor = $('.btn-'+target).attr('data-color');

    $('.btn-'+target).css({'background': color }).attr('data-color', color);
    $('.text-'+target).html(title);
    $('.popup-colors').slideUp();

    $('.input-'+target).val(color).trigger('change');

    if( target.match(/clipart-/g) ) {
        angular.element('#productApp').scope().clipartFill(target, color); 
    }

    if( target == 'add-name' || target == 'add-number' ) {
        $('.btn-add-number, .btn-add-name').css({'background': color }).attr('data-color', color);
        if( $('[data-name="'+target+'"]').is(':checked') ) {
            load_back_design();
        }
        if( $('[data-name="add-number"]').is(':checked') ) {
            angular.element('#productApp').scope().nameNumberFill(color, 'add-number'); 
        }
        if( $('[data-name="add-name"]').is(':checked') ) {
            angular.element('#productApp').scope().nameNumberFill(color, 'add-name');                      
        }
    }

});


$(document).on('click', '.btn-color', function() {
    var target = $('.popup-colors').attr('data-target');
    $('[name="hex"]').val('').trigger('keyup'); 

    $('.popup-colors').css({
        'left': $(this).offset().left,
        'top': $(this).offset().top       
    }).attr('data-target', $(this).attr('data-target'));

    if(target) {
        if( target == $(this).data('target') ) {
            $('.popup-colors').slideToggle();
        } else {
            $('.popup-colors').hide().slideDown();
        }
    } else {
        $('.popup-colors').slideDown();  
    }
});



function load_sizes() {
    $('.team-sizes').empty();
    $.each(JSON.parse($('body').attr('data-sizes')), function(k, v){
        $('.team-sizes').append($('<option>', { 
            value: v,
            text : v 
        }));
    });    
}

var init_repeater = function() {
    $('.mt-repeater').each(function(){
        $(this).repeater({
            show: function () {
                $(this).slideDown();
                if( $('[name="add_name"]').is(':checked') ) $('.team-name').show();
                if( $('[name="add_number"]').is(':checked') )  $('.team-number').show();         
                load_sizes();
            },
            hide: function (deleteElement) {
                if(confirm('Are you sure you want to delete this row?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {

            }

        });
    });
}
init_repeater();

$(document).ready(function(e) {
    $('.sortable').sortable({ 
        containment: 'body',   
        helper: 'clone', 
       start: function(e, ui) {
            // creates a temporary attribute on the element with the old index
            $(this).attr('data-previndex', ui.item.index()+1);
        },
        stop: function(e, ui) {

            var n = ui.item.index()+1;
            var o = $(this).attr('data-previndex');
            $(this).removeAttr('data-previndex');

            var bf = o-n;
            var sb = n-o + 1;
            
            id = $(ui.item).attr('data-id');

            if(o > n) {
                for( var c=0; c<bf; c++) {
                    $('.forward-'+id).trigger('click');
                }
            } else {
                for( var c=1; c<sb; c++) {
                    $('.backward-'+id).trigger('click');
                }
            }

        }
    });
    $('.sortable').disableSelection();
});



$(document).on('click', '.paint-result img', function(e) {

    var target = $(this).offset(),
        color = $('.btn-repaint').attr('data-color');
    x = e.offsetX || (e.pageX - target.left);
    y = e.offsetY || (e.pageY - target.top);

    if( ! color ) return false;

    $(this).attr('data-click', 1);

    var data = {
        "x": x,
        "y": y,
        'color': color,
        'img': $(this).attr('src'),
        'orig': $(this).attr('data-orig')
    };

    var colors = $('#repaint-colors span').map(function() {
        return [$(this).text()];
    }).get();

    if( $.inArray(color, colors)==-1 ) {    
      $('#repaint-colors').append('<span>'+color+'</span>');
    }

    $('.paint-loader').show();

    $.ajax({
        url: '{{ route('frontend.designer.repaint') }}', 
        type: "POST",           
        data: data, 
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},    
        success: function(src) { 
             $('.paint-result img').attr('src', src);
             $('.paint-loader').hide();
        }
    });

});
function findColors() {
    $('.graphic_types .ng-scope').hide();
    var txt = $('#find-graphic-types').val();
    $('.graphic_types .ng-scope').each(function(){
       if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
           $(this).show();
       }
    });
}
$(document).on('click', '.back_to_graphic', function(){
    $('#find-graphic-types').val('').trigger('keyup');
});
$(document).on('click', '.btn-checkout, .external a', function(){
    window.onbeforeunload = null;
});

</script>
@stop

@section('script')
