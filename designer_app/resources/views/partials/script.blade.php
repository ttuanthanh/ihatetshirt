<!--[if lt IE 9]>
<script src="{{ asset('assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/excanvas.min.js') }}"></script> 
<script src="{{ asset('assets/global/plugins/ie8.fix.min.js') }}"></script> 
<![endif]-->


<!-- BEGIN CORE PLUGINS -->
<script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-repeater/jquery.repeater.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/pages/scripts/ui-sweetalert.js') }}"></script>
<script src="{{ asset('assets/plugins/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js') }}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{ asset('assets/layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

  

@yield('plugin_script')

@yield('script')

@yield('filter_script')

<script src="{{ asset('assets/customs/backend.js') }}" type="text/javascript"></script>

<script>
var media_url = '{{ URL::route('admin.media.index') }}';
var token = $('[name="csrf-token"]').attr('content');

tinymce.PluginManager.add('dgmedia', function(editor, url) {
    // Add a button that opens a window
    editor.addButton('dgmedia', {
        text: 'Add Media',
        icon: false,
        onclick: function() {
            window.open(media_url+'?access_method=frame&mode=editor&target=.tinymce', 'MediaManager', 'width=860, height=640');
        }
    }); 

});
tinymce.init({
    selector: ".tinymce",
    menubar: true,
    toolbar_items_size: 'small',
    statusbar: true,
    height : 350,
    convert_urls: false,    
    branding: false,
    setup: function(editor) {
        editor.addButton('mybutton', {
            text: 'My button',
            icon: false,
            onclick: function() {
                editor.insertContent('Main button');
            }
        });
    },
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu dgmedia wordcount"
    ],
    toolbar: "code | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | dgmedia"
});

$(document).on('click', '.filemanager', function(e){
    e.preventDefault();
    var mode = $(this).data('mode') ? $(this).data('mode') : 'single';
    var type = $(this).data('type') ? '&type='+$(this).data('type') : '';
    var target = $(this).data('target');

    window.open(media_url+'?access_method=frame&mode='+mode+'&target='+target+type, 'MediaManager', 'width=980, height=640');
});

$(document).on('click', '.delete-media', function(e){
    e.preventDefault();
    $(this).closest('li').remove();
});

$(document).on('click', '.x-media', function(e){
    e.preventDefault();
    var url = $(this).data('url'), file = $(this).data('file');
    $.post(url, { '_token':token,'file': file });
});



$(document).on('click', '.edit-slug', function(e){
    e.preventDefault();
    $('.edit-slug-form').hide();
    $('.update-slug-form').show();
});
$(document).on('click', '.update-slug', function(e){
    e.preventDefault();
    var slug = $('.slug-field').val();
    $('#post-name').val(slug);
    $('.slug-text').text(slug);
    $('.edit-slug-form').show();
    $('.update-slug-form').hide();
});

$(document).on('click', '.domain a', function(e){
    e.preventDefault();
    window.open($(this).text(), '_blank'); 
});

$(document).on('click', '.cancel-slug', function(e){
    e.preventDefault();
    var slug = $('#post-name').val();
    $('.slug-field').val(slug);
    $('.edit-slug-form').show();
    $('.update-slug-form').hide();
});

$(document).on('keyup', '.slug-field', function() {
    var str = $(this).val();
    var slug = str.toLowerCase().replace(/[_\W]+/g, "-").replace(/\s+/g, '-').replace(/[-]+/g, '-');
    $(this).val(slug);
});

$(document).on('blur', '.slug-field', function() {
  $(this).val( $(this).val().replace(/^-+|-+$/g, '') );
});

$('.colorpicker').each(function() {
    $(this).minicolors({
        control: $(this).attr('data-control') || 'hue',
        defaultValue: $(this).attr('data-defaultValue') || '',
        inline: $(this).attr('data-inline') === 'true',
        letterCase: $(this).attr('data-letterCase') || 'lowercase',
        opacity: $(this).attr('data-opacity'),
        position: $(this).attr('data-position') || 'bottom left',
        change: function(hex, opacity) {
            if (!hex) return;
            if (opacity) hex += ', ' + opacity;
            if (typeof console === 'object') {
                console.log(hex);
            }
        },
        theme: 'bootstrap'
    });
});

$('.sortable').sortable({ containment: 'body',   helper: 'clone', stop: function( event, ui ) { /* sort here */ } });
$('.sortable').disableSelection();

function blockUI(msg) {
    $.blockUI({
        message: '<img src="{{ asset('assets/uploads/loaders/1.gif') }}" /> '+msg,
        boxed: true,
        css: { padding: '20px'}
    });

    /* 
       window.setTimeout(function() {
            $.unblockUI();
        }, 1000);    
    */
}
</script>

