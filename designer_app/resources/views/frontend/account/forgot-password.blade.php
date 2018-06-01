@extends('templates.fullwidth')

@section('content')

<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    Forget Password?
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

            @if($token)

              <div class="alert alert-info">
                <strong>Note:</strong> You must complete this last step to access your account.
              </div>

                <div class="form-group">
                      <input type="password" id="new_password" name="new_password" class="form-control form-control-lg" placeholder="New Password" value="{{ Input::old('new_password') }}">
                      <!-- START error message -->
                      {!! $errors->first('new_password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                      <!-- END error message -->
                </div>

              <div class="form-group">
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control form-control-lg" placeholder="Confirm New Password" value="{{ Input::old('new_password_confirmation') }}">
                <!-- START error message -->
                {!! $errors->first('new_password_confirmation','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                <!-- END error message -->
              </div>

               <div class="form-group">
                  <button type="submit" class="btn btn-primary">Change Password</button>
                    or <a href="{{ URL::route($view.'.login') }}" class=""> Login </a>
                </div>

            @else

            <p>Please enter the email address for your account. A verification code will be sent to you. Once you have received the verification code, you will be able to choose a new password for your account.</p>

            <div class="form-group">
                <input type="text" id="inputEmail" name="email" class="form-control form-control-lg" placeholder="Email" value="{{ Input::old('email') }}">

                  <!-- START error message -->
                  {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                  <!-- END error message -->
            </div>

           <div class="form-group">
              <button type="submit" class="btn btn-primary">Send Request</button>
                or <a href="{{ URL::route($view.'.login') }}" class=""> Login </a>
            </div>

            @endif
          </div>

            </div>
        </div>
    </form>

      
    </div>
</div>

@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
