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

class CouponController extends Controller
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

        $this->post_type = 'coupon';
        $this->view      = 'admin.coupons';
        $this->single    = 'Coupon';
        $this->label     = 'Coupons';

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

        if( Input::get('_token') )
        {
            $rules = [
                'coupon_name'   => 'required',
                'coupon_code'   => 'required',
                'coupon_value'  => 'required',
                'discount_type' => 'required',
                'coupon_type'   => 'required',
                'start_date'    => 'required',
                'end_date'      => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.add')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::except(['_token', 'coupon_name', 'coupon_code', 'status']);

            $post = $this->post;

            $post->post_author  = $this->user_id;
            $post->post_content = json_encode($inputs);                
            $post->post_title   = Input::get('coupon_name');
            $post->post_name    = Input::get('coupon_code');
            $post->post_status  = Input::get('status');
            $post->post_type    = $this->post_type;

            $inputs['start_date'] = date_formatted_b($inputs['start_date']);
            $inputs['end_date']   = date_formatted_b($inputs['end_date']);

            if( $post->save() ) {
                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
                }

                return Redirect::route($this->view.'.edit', $post->id)
                               ->with('success', 'New '.$this->post_type.' has been added!');
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

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        if( Input::get('_token') )
        {
            $rules = [
                'coupon_name'   => 'required',
                'coupon_code'   => 'required',
                'coupon_value'  => 'required',
                'discount_type' => 'required',
                'coupon_type'   => 'required',
                'start_date'    => 'required',
                'end_date'      => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.edit', $id)
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::all();

            $inputs = Input::except(['_token', 'coupon_name', 'coupon_code', 'status']);

            $post = $this->post->find( $id );

            $post->post_author  = $this->user_id;
            $post->post_content = json_encode($inputs);                
            $post->post_title   = Input::get('coupon_name');
            $post->post_name    = Input::get('coupon_code');
            $post->post_status  = Input::get('status');
            $post->post_type    = $this->post_type;
            $post->updated_at   = date('Y-m-d H:i:s');
            
            $inputs['start_date'] = date_formatted_b($inputs['start_date']);
            $inputs['end_date']   = date_formatted_b($inputs['end_date']);

            if( $post->save() ) {
                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_val));
                }

                return Redirect::route($this->view.'.edit', $id)
                               ->with('success', $this->single.' has been updated!');
            } 
        }

        return view($this->view.'.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {
        $this->post->findOrFail($id)->delete();
        $msg = 'Selected '.$this->post_type.' has been move to trashed!';
        return Redirect::route($this->view.'.index')
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $post = $this->post->withTrashed()->findOrFail($id);
        $post->restore();
        $msg = 'Selected '.$this->post_type.' has been restored!';
        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------

  
    public function destroy($id)
    {   
        $this->postmeta->where('post_id', $id)->delete(); 
        $post = $this->post->withTrashed()->find($id);
        $post->forceDelete();
        $msg = 'Selected '.$this->post_type.' has been deleted permanently!';
        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------
    
}
