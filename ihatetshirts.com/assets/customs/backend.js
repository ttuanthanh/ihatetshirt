$('.update-usermeta').on('switchChange.bootstrapSwitch', function (event, state) {
    var url 	= $(this).data('url');
    var id 		= $(this).data('id');
    var key     = $(this).data('key');
    var val_on  = $(this).data('on-val');
    var val_off = $(this).data('off-val');

    data = { 'id' : id, 'key' : key, 'value' : (state) ? val_on : val_off }
    $.post(url, data);  
}); 

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


//Children checkboxes
$('.checkboxes').click(function() {
    var a = $(".checkboxes");        
    if(a.length == a.filter(":checked").length){
        $('#check_all').prop("checked" , true);
        $('#check_all').closest('span').addClass('checked');
    } else {
        $('#check_all').prop("checked" , false);
        $('#check_all').closest('span').removeClass('checked');            
    }

});


$(document).on('click','.btn', function() {
    var val = $(this).data('loader');
    if(val) {
        $(this).html(val).attr('disabled', 'disabled');        
    }
});

$(document).on('click','.delete', function() {
    var $this  = $(this);
    var $title = $this.data('title');
    var $body  = $this.data('body');
    var $href  = $this.data('href');
    var $target = $(this).attr('target');

    $target = ($target == '_blank') ? '_blank' : '';

    $('.delete-modal a.confirm').attr('data-target', $target);
    $('.delete-modal a.confirm').attr('data-href', $href);
    $('.delete-modal .modal-title').html($title);
    $('.delete-modal .modal-body').html($body);
});

$(document).on('click','.delete-modal .modal-footer a', function(e) {
    e.preventDefault();
    $(this).html('Processing ...').attr('disabled', 'disabled');

    var $target = $(this).attr('data-target');
    var $href = $(this).attr('data-href');

    if($target == '_blank') {
        $('.modal').modal('hide');
        $(this).html('Confirm').removeAttr('disabled');
        window.open($href);
    } else {
        location.href = $href;    
    }

});

$(document).on('click', '.btn-view', function(){
	var href = $(this).data('href');
	$('.view-modal img').attr('src', href);
	$('.view-modal .btn-download').attr('href', href);
});


$(document).on('click','.pop-modal', function() {

    var $this   = $(this);
    var $title  = $this.data('title');
    var $body    = $this.data('body');
    var $href   = $this.data('href');
    var $target = $(this).attr('target');

    $target = ($target == '_blank') ? '_blank' : '';

    $($target+' form').attr('action', $href);
    $($target+' a.confirm').attr('data-target', $target);
    $($target+' a.confirm').attr('data-href', $href);
    $($target+' .modal-title').html($title);
    $($target+' .modal-msg').html($body);
});

$(document).on('click','.pop-modal .modal-footer a', function(e) {
    e.preventDefault();
    $(this).html('Processing ...').attr('disabled', 'disabled');

    var $target = $(this).attr('data-target');
    var $href = $(this).attr('data-href');

    if($target == '_blank') {
        $('.modal').modal('hide');
        $(this).html('Confirm').removeAttr('disabled');
        window.open($href);
    } else {
        location.href = $href;    
    }

});


$(document).on('click', '.send-msg', function(e) {
e.preventDefault();
    var url = $(this).data('url');
    var fullname = $(this).data('fullname');

    $('#message .fullname').html(fullname);
    $('#message form').attr('action', url);
});




var app_init = function() {

    $('.datepicker').datepicker({
        autoclose: true,
        todayBtn: true,
        clearBtn: true,
        format: "mm-dd-yyyy"
    });
    $(".datepicker").inputmask({
          mask: "99-99-9999",
          placeholder: "MM-DD-YYYY",
    }); 
    $('.daterange-picker').datepicker({
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        todayBtn: true,
        clearBtn: true,
        minDate: true,
        autoclose: true
    });
    $(".daterange-picker input").inputmask({
          mask: "99-99-9999",
          placeholder: "MM-DD-YYYY",
    }); 
    $('.select2').select2({ width: '100%'});
    $('.rtip').tooltip({ placement: 'top', title: 'Required' });


    $('.btn-preview').on('click', function(e) {
        e.preventDefault();

        var formData = $(this).closest('form');
        var href = $(this).attr('href');

        obj = form_to_json(formData);

        var str = Object.keys(obj).map(function(key) {
            return encodeURIComponent(key) + '=' + encodeURIComponent(obj[key]);
        }).join('&');

        window.open(href, "_blank", "toolbar=no, scrollbars=no, resizable=no, top=0, left=0, width=800, height=1000, menubar=0, titlebar=0");
    });

    // $('.modal').modal('hide');
}

var init_repeater = function() {
    $('.mt-repeater').each(function(){
        $(this).repeater({
            show: function () {
                $(this).slideDown();
               app_init();
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


var initTable = function () {

var table = $('.datatable');

    table.dataTable({

        // Internationalisation. For more info refer to http://datatables.net/manual/i18n
        "language": {
            "aria": {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            },
            "emptyTable": "No data available in table",
            "info": "Showing _START_ to _END_ of _TOTAL_ records",
            "infoEmpty": "No records found",
            "infoFiltered": "(filtered1 from _MAX_ total records)",
            "lengthMenu": "Show _MENU_",
            "search": "Search:",
            "zeroRecords": "No matching records found",
            "paginate": {
                "previous":"Prev",
                "next": "Next",
                "last": "Last",
                "first": "First"
            }
        },

        // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
        // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
        // So when dropdowns used the scrollable div should be removed. 
        //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

        "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
        "pagingType": "bootstrap_extended",

        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        // set the initial value
        "pageLength": 5,
        "columnDefs": [{  // set default column settings
            'orderable': false,
            'targets': [0]
        }, {
            "searchable": false,
            "targets": [0]
        }],
        "order": [
            [1, "asc"]
        ] // set first column as a default sort by asc
    });
}

initTable();


$(document).on('click', '.btn-submit', function() {
    $('.form-submit').submit();
});


$(".timepicker").inputmask("hh:mm t", {
      mask: "h:s t\\M",
      placeholder: "00:00 °M",
      alias: "datetime",
      hourFormat: "12",
      casing:'upper'
}); 


$(document).on('click','.popup', function() {
    var $this  = $(this);
    var $title = $this.data('title');
    var $body  = $this.data('body');
    var $href  = $this.data('href');
    var $target = $(this).attr('target');

    $target = ($target == '_blank') ? '_blank' : '';

    $('.popup-modal a.confirm').attr('data-target', $target);
    $('.popup-modal a.confirm').attr('data-href', $href);
    $('.popup-modal .modal-title').html($title);
    $('.popup-modal .modal-body').html($body);
});

$(document).on('click','.popup-modal .modal-footer a', function(e) {
    e.preventDefault();
    $(this).html('Processing ...').attr('disabled', 'disabled');

    var $target = $(this).attr('data-target');
    var $href = $(this).attr('data-href');

    location.href = $href;   

});

$(document).on('keyup', '.percent', function() {
    var target = $(this).data('target');
    var rate = $(this).val() / 100;
    $(target).val(rate.toFixed(2));
});


// Convert serialized data into JSON
function form_to_json (selector) {
    var ary = $(selector).serializeArray();
    var obj = {};
    for (var a = 0; a < ary.length; a++) obj[ary[a].name] = ary[a].value;
    return obj;
}


function number_format(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

$(document).on('click', '.set-click', function(e) {
    e.preventDefault();
    var target = $(this).data('target');
    var val = $(this).data('val');
    $(target).val(val);
});


$(".form-save").on('submit', function(e) {
    e.preventDefault();

    var formData = $(this),
        url      = formData.attr('action'),
        tab      = $(this).find('li.active a').attr('href');

    blockUI(blockUImsg);
    $('.msg-error').html('');

    $.ajax({
        url: url, // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method 
        data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false,        // To send DOMDocument or non processed data file it is set to false
        success: function(response)   // A function to be called if request succeeds
        {
            var IS_JSON = true;
            try {
                var data = JSON.parse(response);
            } catch(err){
                IS_JSON = false;
            } 

            if( IS_JSON ) {
                $.each(data.details, function(key, val) {
                    $('#'+key).html('<span class="text-danger help-inline">'+val+'</div>');
                });
            } else {
                $('.load-details').html( response );
                $('.tab-pane').removeClass('active');
                $(tab).addClass('active');
                init_repeater();
                app_init();
                initTable();
            }
            
            $.unblockUI();  

        }
    });
});


$(document).on('click', '.btn-img-preview', function(e){
    e.preventDefault();
    var title = $(this).data('title'),
        href  = $(this).attr('href');
    
    $('#img-preview').modal('show');
    $('#img-preview .modal-title').html(title);
    $('#img-preview img').attr('src', href);

});


$(document).on('click', '.ajax-save, .btn-save', function(e) {
    var $this    = $(this),
        target   = $this.data('target'),
        formData = $(target);
    formData.submit();
});   

$(".numeric").keypress(function( event ){
    if(event.which != 13) {
        if(event.which < 46 || event.which >= 58 || event.which == 47) {
            event.preventDefault();
        }
        if(event.which == 46 && $(this).val().indexOf('.') != -1) {
            event.preventDefault();
        }
    }
});

$('.mt-clipboard').each(function(){
    var clipboard = new Clipboard(this);    

    clipboard.on('success', function(e) {
        paste_text = e.text;
        console.log(paste_text);
    });
});

jQuery(document).ready(function() {    
   app_init();
});