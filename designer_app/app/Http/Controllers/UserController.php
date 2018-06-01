<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $user;
    protected $usermeta;
    protected $post;
    protected $postmeta;
    protected $setting;
    protected $request;


    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Request $request)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->setting  = $setting;
        $this->request  = $request;

        $this->view      = 'admin.users';
        $this->single    = 'User';
        $this->label     = 'Users';

        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::check() ? Auth::user()->id : '';
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function index()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;

        parse_str( query_vars(), $search );

        $data['rows'] = $this->user
                             ->search($search)
                             ->where('group', '!=', 'customer')
                             ->paginate(Input::get('rows', 15));

        $data['count'] = $this->user
                              ->where('group', '!=', 'customer')
                              ->search($search)
                              ->count();

        $data['all'] = $this->user->where('group', '!=', 'customer')->count();

        $data['trashed'] = $this->user->withTrashed()
                                      ->where('group', '!=', 'customer')
                                      ->where('deleted_at', '<>', '0000-00-00')
                                      ->count();

        /* Perform bulk actions */                                      
        if( Input::get('action') == 'trash' ) {
            foreach( Input::get('ids') as $id ) {
                User::find($id)->delete();
            }
            return Redirect::route($this->view.'.index')
                           ->with('success','Selected user has been move to trashed!');
        }

        if( Input::get('action') == 'restore') {
            foreach( Input::get('ids') as $id ) {
                $user = User::withTrashed()->findOrFail($id);
                $user->restore();
            }
            return Redirect::route($this->view.'.index', ['type' => 'trash'])
                           ->with('success','Selected user has been restored!');
        }

        if( Input::get('action') == 'destroy') {
            foreach( Input::get('ids') as $id ) {
                $this->user->force_destroy($id);
            }
            return Redirect::route($this->view.'.index', ['type' => 'trash'])
                           ->with('success','Selected user has been deleted permanently!');
        }

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function profile()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;

        $data['info'] = $info = $this->user->find( $this->user_id );
        foreach ($info->usermetas as $usermeta) {
            $data['info'][$usermeta->meta_key] = $usermeta->meta_value;
        }

        $user = $this->user->find( $this->user_id );
        
        $profile_picture = $data['info']->profile_picture;

        if( Input::get('op') == 2) {
            
            $rules = [
                'new_password'              => 'required|min:4|max:64|confirmed',
                'new_password_confirmation' => 'required|min:4',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {                
                return Redirect::route($this->view.'.profile')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user->password   = Hash::make( Input::get('new_password') );
            $user->updated_at = date('Y-m-d H:i:s');

            if( $user->save() ) {

                return Redirect::route($this->view.'.profile')
                               ->with('success','Password has been updated!');
            } 

        }


        if( Input::get('op') == 1) {
            
            $rules = [
                'email'      => 'required|email|max:64|unique:users,email,'.$this->user_id.',id',
                'username'   => 'required|max:64|unique:users,username,'.$this->user_id.',id',
                'firstname'  => 'required|min:1|max:32',
                'lastname'   => 'required|min:1|max:32',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.profile')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user->fill( Input::all() );   

            $user->usermeta   = json_encode( Input::except(['_token', 'password', 'status', 'group']) ); 
            $user->updated_at = date('Y-m-d H:i:s');

            if( Input::hasFile('file') ) {
                $pic = upload_image(Input::file('file'), 'users/'.$this->user_id, $profile_picture, 'compress');
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

    public function add()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;

        $data['groups'] = ['' => 'Select Group', 'admin' => 'Administrator'] 
            + $this->post->where(['post_type' => 'group', 'post_status' => 'actived'])
                   ->pluck('post_title', 'post_name')
                   ->toArray();
                   
        if( Input::get('_token') )
        {
            $validator = Validator::make(Input::all(), User::$insertRules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.add')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user = $this->user;

            $user->fill( Input::all() );   
        
            $user->password = Hash::make( Input::get('password') );  
            $user->usermeta = json_encode( Input::except(['_token', 'password', 'status', 'group']) );   

            if( $user->save() ) {
                
                $id = $user->id;

                if( Input::hasFile('file') ) {
                    $pic = upload_image(Input::file('file'), 'users/'.$id, '');
                    $this->usermeta->update_meta($id, 'profile_picture', $pic);       
                }          

                return Redirect::route($this->view.'.edit', $id)
                               ->with('success','New user has been added!');
            } 
        }

        return view($this->view.'.add', $data);
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;

        $data['groups'] = ['' => 'Select Group', 'admin' => 'Administrator'] 
            + $this->post->where(['post_type' => 'group', 'post_status' => 'actived'])
                   ->pluck('post_title', 'post_name')
                   ->toArray();

        $data['info'] = $info = $this->user->find( $id);
        foreach ($info->usermetas as $usermeta) {
            $data['info'][$usermeta->meta_key] = $usermeta->meta_value;
        }

        if( Input::get('_token') )
        {
            $rules = [
                'email'      => 'required|email|max:64|unique:users,email,'.$id.',id',
                'username'   => 'required|max:64|unique:users,username,'.$id.',id',
                'firstname'  => 'required|min:1|max:32',
                'lastname'   => 'required|min:1|max:32',
                'group'      => 'required',
            ];         	

            if( $password = Input::get('password') ) {
                $rules['password'] = 'required|min:4|max:32';
            }

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.edit', $id)
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user = $this->user->find( $id );

            $user->fill( Input::all() );   
            
            if( $password ) {
                $user->password = Hash::make( $password );
            }

            $user->usermeta   = json_encode( Input::except(['_token', 'password', 'status', 'group']) ); 
            $user->updated_at = date('Y-m-d H:i:s');

            if( Input::hasFile('file') ) {
                $pic = upload_image(Input::file('file'), 'users/'.$id, $info->profile_picture, 'compress');
                $this->usermeta->update_meta($id, 'profile_picture', $pic);       
            }            
                                
            if( $user->save() ) {
                return Redirect::route($this->view.'.edit', $id)
                               ->with('success','User has been updated!');
            } 
        }

        return view($this->view.'.edit', $data);
    }

    //--------------------------------------------------------------------------
  
    public function delete($id)
    {
        $this->user->findOrFail($id)->delete();
        return Redirect::route($this->view.'.index')
                       ->with('success','Selected user has been move to trashed!');
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $user = $this->user->withTrashed()->findOrFail($id);
        $user->restore();
        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success','Selected user has been restored!');
    }

    //--------------------------------------------------------------------------
  
    public function destroy($id)
    {   
        $this->user->force_destroy($id);

        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success','Selected user has been deleted permanently!');
    }

    //--------------------------------------------------------------------------
    
    public function login($id)
    {
        Auth::loginUsingId($id);        

        Session::put('user_id', $id);

        return Redirect::route('admin.general.dashboard');

    }

    //--------------------------------------------------------------------------

}
