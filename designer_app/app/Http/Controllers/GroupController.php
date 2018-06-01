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
use App\Permission;

class GroupController extends Controller
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
    protected $permission;


    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Request $request, Permission $permission)
    {
        $this->user       = $user;
        $this->usermeta   = $usermeta;
        $this->post       = $post;
        $this->postmeta   = $postmeta;
        $this->setting    = $setting;
        $this->request    = $request;
        $this->permission = $permission;

        $this->post_type = 'group';
        $this->view      = 'admin.groups';
        $this->single    = 'Group';
        $this->label     = 'Groups';

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
        $data['user']   = $this->user;

        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->search($search)
                             ->where('post_type', $this->post_type)
                             ->orderBy('id', 'DESC')
                             ->paginate(15);

        $data['count'] = $this->post
                              ->search($search)
                              ->where('post_type', $this->post_type)
                              ->count();

        $data['all'] = $this->post->where('post_type', $this->post_type)->count();

        $data['trashed'] = $this->post->withTrashed()
                                      ->where('post_type', $this->post_type)
                                      ->where('deleted_at', '<>', '0000-00-00')
                                      ->count();

        /* Perform bulk actions */                                      
        if( Input::get('action') == 'trash' ) {
            foreach( Input::get('ids') as $id ) {
                Post::find($id)->delete();
            }
            return Redirect::route($this->view.'.index')
                           ->with('success','Selected '.$this->post_type.' has been move to trashed!');
        }

        if( Input::get('action') == 'restore') {
            foreach( Input::get('ids') as $id ) {
                $user = Post::withTrashed()->findOrFail($id);
                $user->restore();
            }
            return Redirect::route($this->view.'.index', ['type' => 'trash'])
                           ->with('success','Selected '.$this->post_type.' has been restored!');
        }

        if( Input::get('action') == 'destroy') {
            foreach( Input::get('ids') as $id ) {
                PostMeta::where('post_id', $id)->delete(); 
                $post = Post::withTrashed()->find($id);
                $post->forceDelete();
            }
            return Redirect::route($this->view.'.index', ['type' => 'trash'])
                           ->with('success','Selected '.$this->post_type.' has been deleted permanently!');
        }

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;

        if( Input::get('_token') )
        {
            $rules = ['name' => 'required'];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.add')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $post = $this->post;

            $post->post_author  = $this->user_id;
            $post->post_title   = Input::get('name');
            $post->post_content = '';
            $post->post_name    = text_to_slug(Input::get('name'));
            $post->post_type    = $this->post_type;
            $post->post_status  = Input::get('status');

            if( $post->save() ) {
                return Redirect::route($this->view.'.edit', $post->id)
                               ->with('success','New group has been added!');
            } 
        }

        return view($this->view.'.add', $data);
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {
        $data['single']     = $this->single;                                      
        $data['label']      = $this->label; 
        $data['view']       = $this->view;
        $data['post']       = $this->post;
        $data['permission'] = $this->permission;

        $data['info']    = $post = $this->post->find($id);
        $data['modules'] = $this->permission->roles($id); 

        if( Input::get('_token') )
        {
            $rules = ['name' => 'required'];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.edit', $id)
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $post->post_author  = $this->user_id;
            $post->post_title   = Input::get('name');
            $post->post_content = json_encode(Input::except(['_token', 'name', 'status']));
            $post->post_name    = text_to_slug(Input::get('name'));
            $post->post_status  = Input::get('status');
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {
                return Redirect::route($this->view.'.edit', $id)
                               ->with('success','Group has been updated!');
            } 
        }

        return view($this->view.'.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {
        $this->post->findOrFail($id)->delete();
        return Redirect::route($this->view.'.index')
                       ->with('success','Selected '.$this->post_type.' has been move to trashed!');
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $post = $this->post->withTrashed()->findOrFail($id);
        $post->restore();
        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success','Selected '.$this->post_type.' has been restored!');
    }

    //--------------------------------------------------------------------------

  
    public function destroy($id)
    {   
        $this->postmeta->where('post_id', $id)->delete(); 
        $post = $this->post->withTrashed()->find($id);
        $post->forceDelete();

        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success','Selected '.$this->post_type.' has been deleted permanently!');
    }

    //--------------------------------------------------------------------------
    
}
