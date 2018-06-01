@extends('templates.fullwidth')

@section('content')
<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    Account Registration
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="content bg-white">
    <div class="container">


    @if ($message = Session::get('success'))
        <div class="col-md-6 col-centered alert alert-success">Your account has been created and an activation link has been sent to the email address you entered. Note that you must activate the account by clicking on the activation link when you get the email before you can login.</div>
    @else
        <form action="" method="post">

            {!! csrf_field() !!}

            <div class="row">

                <div class="col-md-6 col-centered">

                @include('notification')

                <div class="form-bg-1">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="firstname" placeholder="First Name" value="{{ Input::old('firstname') }}"> 
                                <!-- START error message -->
                                {!! $errors->first('firstname','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->                    
                            </div>                    
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="{{ Input::old('lastname') }}"> 
                                <!-- START error message -->
                                {!! $errors->first('lastname','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->                    
                            </div>     
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="Email Address" value="{{ Input::old('email') }}"> 
                        <!-- START error message -->
                        {!! $errors->first('email','<span class="help-block text-danger">:message</span>') !!}
                        <!-- END error message -->
                    </div>

                    <div class="form-group">

                        <div class="row">
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" placeholder="Password" value="{{ Input::old('password') }}">
                                <!-- START error message -->
                                {!! $errors->first('password','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->                
                            </div>                    
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" value="{{ Input::old('password_confirmation') }}">
                                <!-- START error message -->
                                {!! $errors->first('password_confirmation','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->                
                            </div>     
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary"> Register </button>
                        </div>
                        <div class="col-md-6">
                            <p class="text-right">Already have an account? <a href="{{ route('frontend.account.login') }}">Log In</a></p>
                        </div>
                    </div>
                    
                    <hr>

                    <p class="text-center">Forgot your password? no worries, click 
                        <a href="{{ URL::route($view.'.forgot-password') }}"> here </a> to reset your password. 
                    </p>           

                </div>

                </div>
            </div>

        </form>
        <!-- END LOGIN FORM -->
    @endif
    </div>
</div>

@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop

