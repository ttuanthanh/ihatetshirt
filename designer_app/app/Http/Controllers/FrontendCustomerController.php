<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Setting;
use App\Post;
use App\PostMeta;



class FrontendCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $user;
    protected $usermeta;
    protected $setting;
    protected $post;
    protected $postmeta;


    public function __construct(User $user, UserMeta $usermeta, Setting $setting, Post $post, PostMeta $postmeta)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->setting  = $setting;
        $this->post     = $post;
        $this->postmeta = $postmeta;

        $this->view      = 'frontend.account';

        $this->middleware(function ($request, $next) {
            
            if( Auth::check() ) {
                $this->user_id = Auth::user()->id;
                $this->info = $this->user->find( $this->user_id );
                foreach ($this->info->usermetas as $usermeta) {
                    $this->info[$usermeta->meta_key] = $usermeta->meta_value;
                }                
            }

            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function register() {
        
        $data['view'] = $this->view;
                $data['email'] = $this->post->where('post_type', 'email')
                                            ->where('post_status', 'actived')
                                            ->where('post_name', 'register')
                                            ->first();

        if( Input::get('_token') )
        {
            $validator = Validator::make(Input::all(), User::$registerRules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.register')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user = $this->user;

            $user->firstname    = Input::get('firstname');
            $user->lastname     = Input::get('lastname');
            $user->password     = Hash::make( Input::get('password') );  
            $user->email        = Input::get('email');
            $user->group        = 'customer';
            $user->status       = 'inactived';   
            $user->verify_token =  $token = strtolower(str_random(64));
            $user->usermeta     = json_encode( Input::only(['email']) );  

            if( $user->save() ) {                    

                // BEGIN EMAIL CONFIRMATION
                $data['email'] = $this->post->where('post_type', 'email')
                                            ->where('post_name', 'register')
                                            ->first();
                                            
                if( $data['email']->post_status == 'actived' ) {
                    $confirm_url = route('frontend.account.confirm', $token);

                    $patterns = [
                        '/{firstname}/'     => Input::get('firstname'),
                        '/{lastname}/'      => Input::get('lastname'),
                        '/{email}/'         => Input::get('email'),
                        '/{password}/'      => Input::get('password'),
                        '/{date}/'          => date('F d, Y'),
                        '/{confirm_url}/'   => $confirm_url,
                    ];

                    $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
                    $data['site_title']  = $this->setting->get_setting('site_title');
                    $data['admin_email'] = $this->setting->get_setting('admin_email');
                    $data['inputs']      = Input::all();

                    Mail::send('emails.default', $data, function($message) use ($data)
                    {
                        $message->from($data['admin_email'], $data['site_title'])
                                ->to($data['inputs']['email'])
                                ->subject( $data['email']->post_title );
                    });
                }
                // END EMAIL CONFIRMATION

                return Redirect::route($this->view.'.register')
                               ->with('success','New user has been added!');
            } 
        }

        return view($this->view.'.register', $data);       
    }
    
    //--------------------------------------------------------------------------

    public function login()
    {
        
        $data['view'] = $this->view;

        if( Auth::check() ) {
            $auth = Auth::user();
            return Redirect::route('frontend.home');
        }

        if(Input::get('_token')) {

            $insertRules = [
                'email'    => 'required',
                'password' => 'required',
            ];

            $validator = Validator::make(Input::all(), $insertRules);

            if($validator->passes()) {

                $field = filter_var(Input::get('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
                $remember = (Input::has('remember')) ? true : false;
                
                $credentials = [
                    'email'     => Input::get('email'),
                    'password'  => Input::get('password'),   
                    'group'     => 'customer',
                    'status'    => 'actived'         
                ];

                if(Auth::attempt($credentials, $remember)) {               
                    $auth = Auth::user();

                    Session::put('user_id', $auth->id);
                    $this->usermeta->update_meta($auth->id, 'last_login', date('Y-m-d H:i:s'));                
                    
                    return Redirect::route('frontend.account.profile');

                } 

                return Redirect::route($this->view.'.login')
                               ->with('error','Invalid email or password')
                               ->withInput();
            }

            return Redirect::route($this->view.'.login', query_vars())
                           ->withErrors($validator)
                           ->withInput(); 
        }

        return view($this->view.'.login', $data);
    }

    //--------------------------------------------------------------------------


    public function logout()
    {
        Auth::logout();
        Session::flash('success','You are now logged out!');
        return Redirect::route($this->view.'.login');
    }
    
    //--------------------------------------------------------------------------

    public function forgot_password($token ='')
    {
        $data['view'] = $this->view;

        if( Auth::check() ) {
            $auth = Auth::user();
            return Redirect::route('frontend.home');
        }

        $data['token'] = $token;
        
        if($token) {

            $u = $this->user->where('forgot_password_token', $token)->first();

            if(!$u) return Redirect::route($this->view.'.login');

            if(Input::get('_token') ) {

                $validator = Validator::make(Input::all(), User::$newPassword);
    
                if( ! $validator->passes() ) {                                        
                    return Redirect::route($this->view.'.forgot-password', $token)
                                   ->withErrors($validator)
                                   ->withInput();
                }

                $u->password = Hash::make(Input::get('new_password'));
                $u->forgot_password_token = NULL;

                if( $u->save() ) {              
                    $user_id = $u->id;
                    
                    Auth::loginUsingId($u->id);
                    Session::put('user_id', $user_id);

                    $this->usermeta->update_meta($user_id, 'last_login', date('Y-m-d H:i:s'));     

                    return Redirect::route('frontend.account.profile')
                                   ->with('success','You have successfully changed your password.');

                } 
       
            }

        } else {

            if(Input::get('_token') ) {

                $validator = Validator::make(Input::all(), User::$forgotPassword);
    
                if($validator->passes()) {

                    $token = str_random(64);
                    $email = Input::get('email');

                    $u = $this->user->where('email', $email)->where('status', 'actived')->first();

                    if( $u ) {              

                        $u->forgot_password_token = $token;
                        $u->save();

                        // BEGIN EMAIL CONFIRMATION
                        $data['email'] = $this->post->where('post_type', 'email')
                                                    ->where('post_name', 'forgot-password')
                                                    ->first();

                        if( $data['email']->post_status == 'actived' ) {

                            $change_password_url = route('frontend.account.forgot-password', $token);

                            $patterns = [
                                '/{firstname}/'   => $u->firstname,
                                '/{lastname}/'    => $u->lastname,
                                '/{email}/'       => $u->email,
                                '/{date}/'        => date('F d, Y'),
                                '/{change_password_url}/'   => $change_password_url
                            ];

                            $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
                            $data['site_title']  = $this->setting->get_setting('site_title');
                            $data['admin_email'] = $this->setting->get_setting('admin_email');

                            Mail::send('emails.default', $data, function($message) use ($data)
                            {
                                $message->from($data['admin_email'], $data['site_title'])
                                        ->to(Input::get('email'))
                                        ->subject( $data['email']->post_title );
                            });
                        }
                        // END EMAIL CONFIRMATION

                        return Redirect::route($this->view.'.forgot-password')
                                       ->with('success','Forgot password link has been sent to your email address. Please check your inbox or spam folder.');
                    } 

                    return Redirect::route($this->view.'.forgot-password')
                                   ->with('warning','Sorry, Your email address has been deactivated! Please contact your administrator.');

                } 
                    
                return Redirect::route($this->view.'.forgot-password')
                               ->withErrors($validator)
                               ->withInput();

            }



        }

        return view($this->view.'.forgot-password', $data);
    }

    //--------------------------------------------------------------------------


    public function profile()
    {

        $data['view']   = $this->view;
        $data['post']   = $this->post;

        $data['info'] = $this->info;

        if( Input::get('_token') ) {
            $rules = [
                'email'      => 'required|email|max:64|unique:users,email,'.$this->user_id.',id',
                'firstname'  => 'required|min:1|max:32',
                'lastname'   => 'required|min:1|max:32',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.profile')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user = $this->user->find( $this->user_id );

            $user->fill( Input::all() );   

            $user->usermeta   = json_encode( Input::except(['_token']) ); 
            $user->updated_at = date('Y-m-d H:i:s');

            if( Input::hasFile('profile_picture') ) {

                $file = Input::file('profile_picture');
                $string     = strtolower(str_random(16));
                $imageFile  = $file->getRealPath();
                $ext        = $file->getClientOriginalExtension();
                $path = 'assets/uploads/users/'.$this->user_id.'/';
                $pic   = $path.$string.'.'.$ext;
                
                if( ! file_exists($path) ) mkdir($path);

                \Image::make($imageFile)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->crop(300, 300, null, null)->save($pic);
                compress($pic, $pic, 70);

                if( file_exists($this->info->profile_picture) ) {
                    unlink($this->info->profile_picture);
                }

                $this->usermeta->update_meta($this->user_id, 'profile_picture', $pic);       
            }  

            if( $user->save() ) {
                return Redirect::route($this->view.'.profile')
                               ->with('success','Your profile has been updated!');
            }             
        }

        return view($this->view.'.profile', $data);
    }

    //--------------------------------------------------------------------------

    public function change_password()
    {

        $data['view']   = $this->view;
        $data['post']   = $this->post;

        $data['info'] = $this->info;

        if( Input::get('_token') ) {

            $rules = [
                'new_password'              => 'required|min:6|max:32|confirmed',
                'new_password_confirmation' => 'required|min:6',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {                
                return Redirect::route($this->view.'.change-password')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user = $this->user->find( $this->user_id );

            $user->password   = Hash::make( Input::get('new_password') );
            $user->updated_at = date('Y-m-d H:i:s');

            // BEGIN EMAIL CONFIRMATION
            $data['email'] = $this->post->where('post_type', 'email')
                                        ->where('post_name', 'change-password')
                                        ->first();

            if( $data['email']->post_status == 'actived' ) {
                
                $user->forgot_password_token = $token = strtolower(str_random(64));

                $change_password_url = route('frontend.account.forgot-password', $token);
                $forgot_password_url = route('frontend.account.forgot-password');

                $patterns = [
                    '/{firstname}/'   => $this->info->firstname,
                    '/{lastname}/'    => $this->info->lastname,
                    '/{email}/'       => $this->info->email,
                    '/{password}/'    => Input::get('new_password'),
                    '/{date}/'        => date('F d, Y'),
                    '/{change_password_url}/'  => $change_password_url,
                    '/{forgot_password_url}/'  => $forgot_password_url
                ];

                $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
                $data['site_title']  = $this->setting->get_setting('site_title');
                $data['admin_email'] = $this->setting->get_setting('admin_email');

                Mail::send('emails.default', $data, function($message) use ($data)
                {
                    $message->from($data['admin_email'], $data['site_title'])
                            ->to($data['info']->email)
                            ->subject( $data['email']->post_title );
                });
            }
            // END EMAIL CONFIRMATION

            if( $user->save() ) {        
                return Redirect::route($this->view.'.change-password')
                               ->with('success','Your password has been changed!');
            }             
        }

        return view($this->view.'.change-password', $data);
    }

    //--------------------------------------------------------------------------

    public function designs()
    {

        $data['info'] = $this->info;

        $data['rows'] = $this->post->where('post_type', 'customer_design')
                                   ->where('post_author', $this->user_id)
                                   ->orderBy('id', 'DESC')
                                   ->paginate(16);

        return view($this->view.'.designs', $data);
    }

    //--------------------------------------------------------------------------

    public function view_design($id='')
    {

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }
        
        $data['comment'] = $this->postmeta->get_meta($id, 'comment');        
        $data['content'] = json_decode($info->post_content);

        return view($this->view.'.view-design', $data);
    }

    //--------------------------------------------------------------------------

}
