@extends('templates.fullwidth')

@section('content')
<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    Change Password
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="content bg-white">
    <div class="container">


<form action="" method="post" enctype="multipart/form-data">

    {!! csrf_field() !!}

    <div class="row">
        <div class="col-md-3">
            @include('frontend.account.sidebar')
        </div>
        <div class="col-md-9">

        @include('notification')

            <div class="form-bg-1">

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label>New Password</label>
                    <input type="password" class="form-control" name="new_password" placeholder="New Password" value="{{ Input::old('new_password') }}"> 
                    <!-- START error message -->
                    {!! $errors->first('new_password','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->                    
                </div>                
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">

                    <label>Confirm New Password</label>
                    <input type="password" class="form-control" name="new_password_confirmation" placeholder="Confirm New Password" value="{{ Input::old('new_password_confirmation')  }}"> 
                    <!-- START error message -->
                    {!! $errors->first('new_password_confirmation','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->      

                </div>
            </div>              
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Change Password </button>
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

