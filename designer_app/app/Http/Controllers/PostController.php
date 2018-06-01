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

class PostController extends Controller
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

        $this->view      = 'admin.posts';
        $this->post_type = 'page';
        $this->single    = 'Page';
        $this->label     = 'Pages';

        if( Input::get('post_type') == 'post' ) {
            $this->post_type = 'post';
            $this->single    = 'Post';
            $this->label     = 'Posts';
        }

        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::check() ? Auth::user()->id : '';
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function index()
    {
        $data['single']    = $this->single;                                      
        $data['label']     = $this->label; 
        $data['view']      = $this->view;
        $data['post']      = $this->post;
        $data['post_type'] = $this->post_type;

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
            return Redirect::route($this->view.'.index', ['post_type' => $this->post_type])
                           ->with('success','Selected '.$this->post_type.' has been move to trashed!');
        }

        if( Input::get('action') == 'restore') {
            foreach( Input::get('ids') as $id ) {
                $user = Post::withTrashed()->findOrFail($id);
                $user->restore();
            }
            return Redirect::route($this->view.'.index', ['post_type' => $this->post_type, 'type' => 'trash'])
                           ->with('success','Selected '.$this->post_type.' has been restored!');
        }

        if( Input::get('action') == 'destroy') {
            foreach( Input::get('ids') as $id ) {
                PostMeta::where('post_id', $id)->delete(); 
                $post = Post::withTrashed()->find($id);
                $post->forceDelete();
            }
            return Redirect::route($this->view.'.index', ['post_type' => $this->post_type, 'type' => 'trash'])
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
        $data['post_type'] = $this->post_type;

        if( Input::get('_token') )
        {
            $rules = [
                'title' => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.add', query_vars())
                               ->withErrors($validator)
                               ->withInput(); 
            }
            $inputs = Input::except(['_token', 'title', 'content', 'slug', 'status']);

            $post = $this->post;

            $post->post_author  = $this->user_id;
            $post->post_content = Input::get('content');                
            $post->post_title   = $title = Input::get('title');
            $post->post_name    = text_to_slug($title);
            $post->post_type    = $this->post_type;
            $post->post_status  = Input::get('status');

            if( $post->save() ) {
                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
                }

                return Redirect::route($this->view.'.edit', [$post->id, query_vars()])
                               ->with('success', 'New '. strtolower($this->single).' has been added!');
            } 
        }

        $c=1;

        $data['categories'] = [];
        foreach ($this->post->where(['post_type' => 'post-category'])->get() as $category) {
            $data['categories'][$c++] = array(
                'id'=> $category->id, 
                'parent_id' => $category->parent, 
                'name' => $category->post_title
            );
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
        $data['post_type'] = $this->post_type;

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        if( Input::get('_token') )
        {
            $rules = [
                'title'    => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.edit', [$id, query_vars()])
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::except(['_token', 'title', 'content', 'slug', 'status']);
            
            $post = $this->post->find( $id );

            $post->post_author  = $this->user_id;
            $post->post_content = Input::get('content');                
            $post->post_title   = $title = Input::get('title');
            $post->post_name    = Input::get('slug') ? Input::get('slug') : text_to_slug($title);
            $post->post_type    = $this->post_type;
            $post->post_status  = Input::get('status');
            $post->updated_at   = date('Y-m-d H:i:s');
            
            if( $post->save() ) {
                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_val));                                         
                }

                return Redirect::route($this->view.'.edit', [$id, query_vars()])
                               ->with('success', $this->single.' has been updated!');
            } 
        }

        $c=1;                                              

        $data['categories'] = [];
        foreach ($this->post->where(['post_type' => 'post-category'])->get() as $category) {
            $data['categories'][$c++] = array(
                'id'=> $category->id, 
                'parent_id' => $category->parent, 
                'name' => $category->post_title
            );
        }

        $data['blog'] = ($this->post_type == 'post') ? strtolower($info->categoryList) : '/';

        return view($this->view.'.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {
        $this->post->findOrFail($id)->delete();
        $msg = 'Selected '.strtolower($this->single).' has been move to trashed!';
        return Redirect::route($this->view.'.index', ['post_type' => $this->post_type])
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $post = $this->post->withTrashed()->findOrFail($id);
        $post->restore();
        $msg = 'Selected '.strtolower($this->single).' has been restored!';
        return Redirect::route($this->view.'.index', ['post_type' => $this->post_type, 'type' => 'trash'])
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------

    public function destroy($id)
    {   
        $this->postmeta->where('post_id', $id)->delete(); 
        $post = $this->post->withTrashed()->find($id);
        $post->forceDelete();
        $msg = 'Selected '.strtolower($this->single).' has been deleted permanently!';
        return Redirect::route($this->view.'.index', ['post_type' => $this->post_type, 'type' => 'trash'])
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------

    public function post($category='', $slug='')
    {   
        $data['category'] = $category;
        $data['slug']     = $slug;

        $data['cat'] = $cat = $this->post->where('post_name', $category)->first();

        $rows = $this->post
            ->select('posts.*', 'm1.meta_value as category')
            ->from('posts')
            ->join('postmeta AS m1', function ($join) use ($cat) {
                    $join->on('posts.id', '=', 'm1.post_id')
                        ->where('m1.meta_key', '=', 'category')
                        ->where('meta_value', 'LIKE', '%'.@$cat->id.'%');
                    });
        
        $data['rows'] = $rows->paginate(16);

        if( $slug ) {
            
            if( $category ) {
                $this->post->where( 'post_type', 'post-category')
                           ->where('post_name', $category)
                           ->where('post_status', 'actived')
                           ->firstOrFail();                
            }

            $data['info'] = $info = $this->post->where( 'post_name', $slug )
                                               ->where('post_status', 'published')
                                               ->whereIn('post_type', ['post', 'page'])
                                               ->firstOrFail();
     
            foreach ($info->postmetas as $postmeta) {
                $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
            }

            $data['s'] = json_decode($info->settings);

            $data['template'] = $info->template ? 'templates.'.$info->template : 'templates.default';

            return view('frontend.posts.single', $data);            
        } else {

            $is_page = $this->post->where( 'post_type', 'page')
                       ->where('post_name', $category)
                       ->where('post_status', 'published')
                       ->first();            

            if( $is_page ) {
                return $this->post('', $is_page->post_name);
            } else {

                if( ! @$cat->id )     
                    return abort(404);           
            }
            
            if( ! $rows->count() ) 
                return abort(404);

            if( file_exists( '../resources/views/frontend/posts/'.$category.'.blade.php' ) ) {
                return view('frontend.posts.'.$category, $data);                        
            }
            
            return view('frontend.posts.index', $data);     

        }
    }

    //--------------------------------------------------------------------------
        
}
