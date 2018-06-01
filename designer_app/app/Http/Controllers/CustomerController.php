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

class CustomerController extends Controller
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

        $this->group     = 'customer';
        $this->view      = 'admin.customers';
        $this->single    = 'Customer';
        $this->label     = 'Customers';

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
                              ->where('group', $this->group)
                             ->search($search)
                             ->orderBy(Input::get('sort', 'id'), Input::get('order', 'DESC'))
                             ->paginate(Input::get('rows', 15));

        $data['count'] = $this->user
                              ->where('group', $this->group)
                              ->search($search)
                              ->count();

        $data['all'] = $this->user->where('group', $this->group)->count();

        $data['trashed'] = $this->user->withTrashed()
                                      ->where('group', $this->group)
                                      ->where('deleted_at', '<>', '0000-00-00')
                                      ->count();


        /* Perform bulk actions */                                      
        if( Input::get('action') == 'trash' ) {
            foreach( Input::get('ids') as $id ) {
                User::find($id)->delete();
            }
            return Redirect::route($this->view.'.index')
                           ->with('success','Selected '.$this->group.' has been move to trashed!');
        }

        if( Input::get('action') == 'restore') {
            foreach( Input::get('ids') as $id ) {
                $user = User::withTrashed()->findOrFail($id);
                $user->restore();
            }
            return Redirect::route($this->view.'.index', ['type' => 'trash'])
                           ->with('success','Selected '.$this->group.' has been restored!');
        }

        if( Input::get('action') == 'destroy') {
            foreach( Input::get('ids') as $id ) {
                $this->user->force_destroy($id);
            }
            return Redirect::route($this->view.'.index', ['type' => 'trash'])
                           ->with('success','Selected '.$this->group.' has been deleted permanently!');
        }

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function view($id='')
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;

        $data['info'] = $info = $this->user->find( $id);
        foreach ($info->usermetas as $usermeta) {
            $data['info'][$usermeta->meta_key] = $usermeta->meta_value;
        }

        if( Input::get('_token') )
        {
            $rules = [
                'email' => 'required|email|max:64|unique:users,email,'.$id.',id',
            ];    

            if( $password = Input::get('password') ) {
                $rules['password'] = 'required|min:6|max:32';
            }
    
            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.view', $id)
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user = $this->user->find( $id );

            $user->fill( Input::all() );   
            
            if( $password = Input::get('password') ) {
                $user->password = Hash::make( $password );
            }

            $user->updated_at = date('Y-m-d H:i:s');            
            $user->usermeta   = json_encode( Input::except(['_token', 'password', 'status']) ); 

            if( Input::hasFile('file') ) {
                $file = Input::file('file');
                $string     = strtolower(str_random(16));
                $imageFile  = $file->getRealPath();
                $ext        = $file->getClientOriginalExtension();

                $pic   = 'assets/uploads/users/'.$id.'/'.$string.'.'.$ext;
                \Image::make($imageFile)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->crop(300, 300, null, null)->save($pic);
                compress($pic, $pic, 70);

                if( file_exists($info->profile_picture) ) {
                    unlink($info->profile_picture);
                }

                $this->usermeta->update_meta($id, 'profile_picture', $pic);       
            }            


            if( $user->save() ) {
                $inputs =  Input::except(['_token', 'password', 'status', 'firstname', 'lastname', 'email']);

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->usermeta->update_meta($id, $meta_key, array_to_json($meta_val));
                }

                return Redirect::route($this->view.'.view', $id)
                               ->with('success', $this->single.' has been updated!');
            } 
        }

        return view($this->view.'.view', $data);
    }

    //--------------------------------------------------------------------------
  
    public function delete($id)
    {
        $this->user->findOrFail($id)->delete();
        return Redirect::route($this->view.'.index', query_vars())
                       ->with('success','Selected '.$this->group.' has been move to trashed!');
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $user = $this->user->withTrashed()->findOrFail($id);
        $user->restore();
        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success','Selected '.$this->group.' has been restored!');
    }

    //--------------------------------------------------------------------------

  
    public function destroy($id)
    {   
        $this->usermeta->where('user_id', $id)->delete();
        $user = $this->user->withTrashed()->find($id);
        $user->forceDelete();

        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success','Selected '.$this->group.' has been deleted permanently!');
    }

    //--------------------------------------------------------------------------

}
