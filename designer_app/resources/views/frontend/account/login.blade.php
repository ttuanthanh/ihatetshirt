@extends('templates.fullwidth')

@section('content')

<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                   Login to your account
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="content bg-white">
    <div class="container">

        <form class="login-form" action="" method="post">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-6 col-centered">

                    @include('notification')

                    <div class="form-bg-1">

                    <div class="form-group">
                            <input type="text" class="form-control" name="email" placeholder="Email Address" value="{{ Input::old('email') }}"> 
                        <!-- START error message -->
                        {!! $errors->first('email','<span class="help-block text-danger">:message</span>') !!}
                        <!-- END error message -->
                    </div>

                    <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password" value="{{ Input::old('password') }}">        
                        <!-- START error message -->
                        {!! $errors->first('password','<span class="help-block text-danger">:message</span>') !!}
                        <!-- END error message -->
                    </div>

                    <div class="form-group">    
                        <label>
                            <input type="checkbox" name="remember" value="1" /> Remember me
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Log In </button>
                        </div>
                        <div class="col-md-6">
                            <p class="text-right">No account yet? <a href="{{ route('frontend.account.register') }}">Register</a></p>
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

    </div>
</div>        
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
