<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>{{ App\Setting::get_setting('site_title') }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="{{ asset('css/fonts.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}"  rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}"  rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}"  rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"  rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}"  rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}"  rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ asset('assets/global/css/components-md.min.css') }}"  rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ asset('assets/global/css/plugins-md.min.css') }}"  rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{ asset('assets/pages/css/login-4.min.css') }}"  rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->

        <link href="{{ asset('assets/customs/style.css') }}"  rel="stylesheet" type="text/css" />

        <!-- END THEME LAYOUT STYLES -->
        <link rel="icon" href="{{ asset('favicon-32x-32.png') }}" sizes="32x32" />
        <link rel="icon" href="{{ asset('favicon-192x192.png') }}" sizes="192x192" />

        </head>
    <!-- END HEAD -->


    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ has_image(App\Setting::get_setting('logo')) }}" width="200"/>
            </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->

  <form class="login-form" action="" method="post">
      {{ csrf_field() }}

        @include('notification')

        @if($token)
          <div class="alert alert-info">
            <strong>Note:</strong> You must complete this last step to access your account.
          </div>

    <div class="form-group">
          <label for="new_password" class="sr-only">New Password</label>
      <div class="input-icon">
        <i class="fa fa-lock"></i>
          <input type="password" id="new_password" name="new_password" class="form-control" placeholder="New Password" value="{{ Input::old('new_password') }}">
          </div>
          <!-- START error message -->
          {!! $errors->first('new_password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
          <!-- END error message -->
    </div>

      <div class="form-group">
        <label for="new_password_confirmation" class="sr-only">Confirm New Password</label>
      <div class="input-icon">
        <i class="fa fa-lock"></i>
        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Confirm New Password" value="{{ Input::old('new_password_confirmation') }}">
        </div>
        <!-- START error message -->
        {!! $errors->first('new_password_confirmation','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
        <!-- END error message -->
      </div>


    <div class="form-actions">
      <a href="{{ URL::route('auth.login') }}"class="btn btn-default"><i class="m-icon-swapleft"></i> Back </a>

      <button type="submit" class="btn blue pull-right">
      Change Password <i class="m-icon-swapright m-icon-white"></i>
      </button>
    </div>

        @else


    <h3>Forget Password ?</h3>
    <p>We will find your account.</p>

    <div class="form-group">
      <div class="input-icon">
        <i class="fa fa-envelope"></i>
        <input type="text" id="inputEmail" name="email" class="form-control" placeholder="Email" value="{{ Input::old('email') }}">
      </div>
      <!-- START error message -->
      {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
      <!-- END error message -->
    </div>

    <div class="form-actions">
      <a href="{{ URL::route('auth.login') }}" class="btn btn-default"><i class="m-icon-swapleft"></i> Back </a>

      <button type="submit" class="btn blue pull-right">
      Submit <i class="m-icon-swapright m-icon-white"></i>
      </button>
    </div>


        @endif
          <input type="hidden" name="op" value="1">

      </form>
            <!-- END LOGIN FORM -->


        </div>
        <!-- END LOGIN -->
        <!-- BEGIN COPYRIGHT -->
        <div class="copyright">{{ date('Y') }} &copy; {{ App\Setting::get_setting('copy_right') }}</div>
        <!-- END COPYRIGHT -->
        <!--[if lt IE 9]>
        <script src="{{ asset('assets/global/plugins/respond.min.js') }}" ></script>
        <script src="{{ asset('assets/global/plugins/excanvas.min.js') }}" ></script> 
        <script src="{{ asset('assets/global/plugins/ie8.fix.min.js') }}" ></script> 
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ asset('assets/global/plugins/jquery.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"  type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/backstretch/jquery.backstretch.min.js') }}"  type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{ asset('assets/global/scripts/app.min.js') }}"  type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script>
        var Login = function () {

            var handleLogin = function() {
                $('.login-form').validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input
                        rules: {
                            email: {
                                required: true
                            },
                        },

                        messages: {
                            email: {
                                required: "Email is required."
                            },
                        },

                        invalidHandler: function (event, validator) { //display error alert on form submit   
                            $('.alert-danger', $('.login-form')).show();
                        },

                        highlight: function (element) { // hightlight error inputs
                            $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                        },

                        success: function (label) {
                            label.closest('.form-group').removeClass('has-error');
                            label.remove();
                        },

                        errorPlacement: function (error, element) {
                            error.insertAfter(element.closest('.input-icon'));
                        },

                        submitHandler: function (form) {
                            form.submit();
                        }
                    });

                    $('.login-form input').keypress(function (e) {
                        if (e.which == 13) {
                            if ($('.login-form').validate().form()) {
                                $('.login-form').submit();
                            }
                            return false;
                        }
                    });
            }

            
            return {
                //main function to initiate the module
                init: function () {
                    
                    handleLogin();

                    // init background slide images
                    $.backstretch([
                        "{{ asset('assets/pages/media/bg/1.jpg') }}",
                        "{{ asset('assets/pages/media/bg/2.jpg') }}",
                        "{{ asset('assets/pages/media/bg/3.jpg') }}",
                        "{{ asset('assets/pages/media/bg/4.jpg') }}"
                        ], {
                          fade: 1000,
                          duration: 8000
                        }
                    );
                }
            };

        }();

        jQuery(document).ready(function() {
            Login.init();
        });            
        </script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>