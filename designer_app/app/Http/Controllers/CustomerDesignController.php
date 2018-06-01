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

class CustomerDesignController extends Controller
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

        $this->post_type = 'customer_design';
        $this->view      = 'admin.customers.designs';
        $this->single    = 'Customer Design';
        $this->label     = 'Customer Designs';

        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::check() ? Auth::user()->id : '';
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function index()
    {
        if( $this->request->ajax() ) {
            $val = Input::get('val') == 'true' ? 1 : 0;
            $this->postmeta->update_meta(Input::get('id'), 'featured', $val);
            return;              
        }

        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;
        
        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->search($search)
                             ->where('post_type', $this->post_type)
                             ->orderBy(Input::get('sort', 'id'), Input::get('order', 'DESC'))
                             ->paginate(Input::get('rows', 15));

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
                           ->with('success','Selected '.strtolower($this->single).' has been move to trashed!');
        }

        if( Input::get('action') == 'restore') {
            foreach( Input::get('ids') as $id ) {
                $user = Post::withTrashed()->findOrFail($id);
                $user->restore();
            }
            return Redirect::route($this->view.'.index', ['type' => 'trash'])
                           ->with('success','Selected '.strtolower($this->single).' has been restored!');
        }

        if( Input::get('action') == 'destroy') {
            foreach( Input::get('ids') as $id ) {
                PostMeta::where('post_id', $id)->delete(); 
                $post = Post::withTrashed()->find($id);

                $dir = 'assets/uploads/designs/'.$post->post_name;
                foreach (glob($dir."-*") as $file) {
                    unlink($file);
                }

                $post->forceDelete();
            }
            return Redirect::route($this->view.'.index', ['type' => 'trash'])
                           ->with('success','Selected '.strtolower($this->single).' has been deleted permanently!');
        }

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function view($id='')
    {

        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }
        
        $data['comment'] = $this->postmeta->get_meta($id, 'comment');        
        $data['content'] = json_decode($info->post_content);

        return view($this->view.'.view', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {
        $this->post->findOrFail($id)->delete();
        
        $msg = 'Selected '.strtolower($this->single).' has been move to trashed!';

        return Redirect::route($this->view.'.index', query_vars())
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $post = $this->post->withTrashed()->findOrFail($id);
        $post->restore();

        $msg = 'Selected '.strtolower($this->single).' has been restored!';

        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------

  
    public function destroy($id)
    {   
        $this->postmeta->where('post_id', $id)->delete(); 
        $post = $this->post->withTrashed()->find($id);

        $dir = 'assets/uploads/designs/'.$post->post_name;
        foreach (glob($dir."-*") as $file) {
          unlink($file);
        }

        $post->forceDelete();

        $msg = 'Selected '.strtolower($this->single).' has been deleted permanently!';

        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------
    
}
