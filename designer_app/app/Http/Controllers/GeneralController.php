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

class GeneralController extends Controller
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
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->setting  = $setting;
        $this->permission = $permission;
        $this->request = $request;
    }

    //--------------------------------------------------------------------------

    public function dashboard()
    {

        $data['c_new_order'] = $this->post->where('post_type', 'order')->where('post_status', 'pending')->count();

        $data['c_order_today'] = $this->post
                                    ->select('posts.*', 'm1.meta_value as order_date')
                                    ->from('posts')
                                    ->join('postmeta AS m1', function ($join) {
                                        $join->on('posts.id', '=', 'm1.post_id')
                                            ->where('m1.meta_key', '=', 'order_date')
                                            ->where('meta_value', date('Y-m-d'));
                                        })
                                    ->where('post_type', 'order')
                                    ->where('post_status', 'pending')
                                    ->count();

        $data['c_ship_today']  = $this->post
                                    ->select('posts.*', 'm1.meta_value as shipping_date')
                                    ->from('posts')
                                    ->join('postmeta AS m1', function ($join) {
                                        $join->on('posts.id', '=', 'm1.post_id')
                                            ->where('m1.meta_key', '=', 'shipping_date')
                                            ->where('meta_value', date('Y-m-d'));
                                        })
                                    ->where('post_type', 'order')
                                    ->count();

        $data['c_posts']     = $this->post->where('post_type', 'post')->where('post_status', 'published')->count();
        $data['c_customers'] = $this->user->where('group', 'customer')->where('status', 'actived')->count();
        $data['c_designs']   = $this->post->where('post_type', 'customer_design')->count();

        $data['c_products'] = $this->post->where('post_type', 'product')->where('post_status', 'published')->count();
        $data['c_cliparts'] = $this->post->where('post_type', 'clipart')->where('post_status', 'actived')->count();
        $data['c_coupons']  = $this->post->where('post_type', 'coupon')->where('post_status', 'published')->count();

        return view('admin.general.dashboard', $data);
    }

    //--------------------------------------------------------------------------



}
