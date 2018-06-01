<?php 
/* 
    Template Name: Contact Us
*/
?>
<!doctype html>
<html class="no-js" lang="{{ config('app.locale') }}">

    @include('partials.frontend.head') 

    <body>

        @include('partials.frontend.header')

    @if( @$s->title == 'show' )
    <div class="page-title"><div class="container"><div class="row"><div class="col-md-12"><h1>
    {!! $info->post_title !!}
    </h1></div></div></div></div>
    @endif

        @if( $address = App\Setting::get_setting('address') ) 

		<iframe src="https://maps.google.com/maps?q={{ $address }}&t=&z=16&ie=UTF8&iwloc=&output=embed" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen=""></iframe>
        @endif

<div class="content bg-white"> 

        <div class="container">


        <div class="row">

            <div class="col-md-6">

    <form action="{{ route('frontend.contact') }}" method="post">

        {!! csrf_field() !!}

			<?php 
			$fullname = $email = '';
			if( Auth::check() ) {
				$user = @Auth::user();
				$fullname = name_formatted($user->id);
				$email =  @Auth::user()->email;
			}
			?>

            @include('notification')

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" 
                        placeholder="Name" value="{{ Input::old('name', $fullname) }}"> 
                        <!-- START error message -->
                        {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}
                        <!-- END error message -->                    
                    </div>                    
                    <div class="col-md-6">
                        <label>Email Address</label>
                        <input type="email" class="form-control" name="email" placeholder="Email Address" value="{{ Input::old('lastname', $email) }}"> 
                        <!-- START error message -->
                        {!! $errors->first('email','<span class="help-block text-danger">:message</span>') !!}
                        <!-- END error message -->                    
                    </div>     
                </div>
            </div>

            <div class="form-group">
                <label>Subject</label>
                <input type="text" class="form-control" name="subject" placeholder="Subject" value="{{ Input::old('subject') }}"> 
                <!-- START error message -->
                {!! $errors->first('subject','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->
            </div>


            <div class="form-group">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="5" placeholder="Message">{{ Input::old('message') }}</textarea>
                <!-- START error message -->
                {!! $errors->first('message','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->
            </div>


            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Send </button>
                


    </form>        	



            </div>

    <div class="col-md-6">
        @yield('content')        	
    </div>



        </div>

        </div>      
      
</div>

		@if( @$s->subscription == 'show' )
		    @include('partials.frontend.subscribe')
		@endif

        @include('partials.frontend.footer') 

    </body>
</html>