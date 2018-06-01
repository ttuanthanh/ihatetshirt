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

class SettingController extends Controller
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
    protected $permission;
    protected $request;

    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Permission $permission, Request $request)
    {
        $this->user       = $user;
        $this->usermeta   = $usermeta;
        $this->post       = $post;
        $this->postmeta   = $postmeta;
        $this->setting    = $setting;
        $this->permission = $permission;
        $this->request    = $request;

        $this->view = 'admin.settings';    

        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::check() ? Auth::user()->id : '';
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function general()
    {
        $data = array();
        
        $data['label'] = 'General Settings';                                      
        $data['view']  = $this->view;
        $data['post']  = $this->post;                         
        $data['setting']  = $this->setting;        

        $data['info'] = (object)$this->setting->get()->pluck('value', 'key')->toArray();

        if ( Input::get('_token') ) 
        {   
            $inputs = Input::except(['_token']);

            $inputs['logo'] = $inputs['image'];

            $this->setting->update_metas($inputs);

            return Redirect::route($this->view.'.general')
                           ->with('success','Changes saved.');
        }

        return view($this->view.'.general', $data);
    }

    //--------------------------------------------------------------------------

    public function shop()
    {
        $data['label'] = 'Shop Settings';                                      
        $data['view']   = $this->view;
        $data['post']   = $this->post;     
        $data['setting']  = $this->setting;      

        $data['info'] = (object)$this->setting->get()->pluck('value', 'key')->toArray();

        if ( Input::get('_token') ) 
        {   
            $inputs = Input::except(['_token']);

            $inputs['invoice_logo'] = $inputs['image'];

            $this->setting->update_metas($inputs);

            return Redirect::route($this->view.'.shop')
                           ->with('success','Changes saved.');
        }

        return view($this->view.'.shop', $data);
    }

    //--------------------------------------------------------------------------

    public function designer()
    {
        $data['label'] = 'Online Designer';                                      
        $data['view']   = $this->view;
        $data['post']   = $this->post;     
        $data['setting']  = $this->setting;      
        
        $data['info'] = (object)$this->setting->get()->pluck('value', 'key')->toArray();

        if ( Input::get('_token') ) 
        {   
            $inputs = Input::except(['_token']);

            $this->setting->update_metas($inputs);

            return Redirect::route($this->view.'.designer')
                           ->with('success','Changes saved.');
        }

        return view($this->view.'.designer', $data);
    }

    //--------------------------------------------------------------------------

    public function payments()
    {
        $data['label'] = 'Payment Methods';                                      
        $data['view']   = $this->view;
        $data['post']   = $this->post;     

        $data['payment'] = json_decode($this->setting->get_setting('payment_method'));

        if( Input::get('_token') )
        {
            $inputs['payment_method'] = array_to_json(Input::get('payment'));

            $this->setting->update_metas($inputs);

            return Redirect::route($this->view.'.payments')
                           ->with('success', 'Payment method has been updated!');
        }

        return view($this->view.'.payments', $data);
    }
    
    //--------------------------------------------------------------------------

    public function price_table()
    {
        $data['label'] = 'Price Table';                                      
        $data['view']   = $this->view;
        $data['post']   = $this->post;     

        $data['pricing'] = json_decode($this->setting->get_setting('price-table'), true);

        if( Input::get('_token') )
        {
            $inputs['price-table'] = array_to_json(Input::get('pricing'));

            $this->setting->update_metas($inputs);

            return Redirect::route($this->view.'.price-table')
                           ->with('success', 'Price table has been updated!');
        }

        return view($this->view.'.price-table', $data);
    }
    
    //--------------------------------------------------------------------------

    public function emails()
    {
        $data['label'] = 'Email Notifications';                                      
        $data['view']   = $this->view;
        $data['post']   = $this->post;     

        $tab = Input::get('tab', 'register');

		$tabs = [
			'register',
			'change-password',
			'forgot-password',
			'saved-design',
			'order-details',
			'order-status',
		];

		if( ! in_array($tab, $tabs) ) abort(404);

        $data['info'] = $info = $this->post->where('post_name', $tab)->where('post_type', 'email')->first();
        if($info) {
			foreach ($info->postmetas as $postmeta) {
			    $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
			}  	       	
        }


        if( Input::get('_token') )
        {
            $rules = [
                'subject' => 'required',
            ];    
   
            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.emails', ['tab' => $tab])
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $post = $this->post->where('post_name', $tab)->where('post_type', 'email')->first();

            if( ! $post ) {
            	$post = $this->post;
            }

            $post->post_author  = $this->user_id;
            $post->post_content = Input::get('content');                
            $post->post_title   = Input::get('subject');
            $post->post_name    = $tab;
            $post->post_status  = Input::get('status');
            $post->post_type    = 'email';
            $post->updated_at   = date('Y-m-d H:i:s');
            
            if( $post->save() ) {
                $this->postmeta->update_meta($post->id, 'emails', Input::get('emails'));
                return Redirect::route($this->view.'.emails', ['tab' => $tab])
                               ->with('success', 'Email has been updated!');
            } 
        }

        return view($this->view.'.emails', $data);
    }
    
    //--------------------------------------------------------------------------

}
