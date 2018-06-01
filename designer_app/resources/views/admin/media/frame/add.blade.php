@extends('layouts.basic')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Upload New Media
    <div class="pull-right">
        @if( Input::get('folder') )
            <a href="{{ URL::route('admin.media.index', query_vars()) }}" class="btn"><i class="fa fa-long-arrow-left"></i> Back</a>
        @endif
        <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>    
    </div>
</h1>
<!-- END PAGE TITLE-->


<div class="row">
    <div class="col-md-12">
        <form action="{{ URL::route('admin.media.upload', query_vars()) }}" class="dropzone dropzone-file-area" id="my-dropzone">

            
        </form>
    </div>
</div>


@endsection

@section('style')
<style>
.dropzone-file-area {
    overflow: auto;
    height: -webkit-fill-available; 
}
#my-dropzone .btn { display:none !important; }    
</style>
@stop

@section('plugin_script') 
        <script src="{{ asset('assets/global/plugins/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
@stop

@section('script')
<script>
var delete_url = '{{ URL::route('admin.media.delete') }}', 
token = $('meta[name="csrf-token"]').attr('content');

var FormDropzone = function () {

    return {
        //main function to initiate the module
        init: function () {  

            Dropzone.options.myDropzone = {
                paramName: "file", 
                dictDefaultMessage: '<h3 class="sbold">Drop files here <br> or <br> <span class="text-muted">click to upload</span></h3>',
                 dictResponseError: 'Error uploading file!',
                 addRemoveLinks : false,
                 acceptedFiles: 'image/*',
                headers: {
                    'X-CSRF-Token': token
                },

           init: function() {
                    this.on("addedfile", function(file) {
                        // Create the remove button
                        var removeButton = Dropzone.createElement("<a href='javascript:;'' class='btn red btn-sm btn-block'>Remove</a>");
                        
                        // Capture the Dropzone instance as closure.
                        var _this = this;

                        // Listen to the click event
                        removeButton.addEventListener("click", function(e) {
                            // Make sure the button click doesn't submit the form:
                            e.preventDefault();
                            e.stopPropagation();

                            if(file.xhr){
                                var data = JSON.parse(file.xhr.response);
                                $.post(delete_url, { 'filename': data.filename, '_token' : token });
                            }

                          // Remove the file preview.
                          _this.removeFile(file);
                          // If you want to the delete the file on the server as well,
                          // you can do the AJAX request here.
                        });

                        // Add the button to the file preview element.
                        file.previewElement.appendChild(removeButton);
                    });
                }            
            }
        }
    };
}();

jQuery(document).ready(function() {    
   FormDropzone.init();
});      
</script>
@stop
