<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Setting;
use App\Post;
use App\PostMeta;
use App\Stripe;



class StripeController extends Controller
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
    protected $stripe;


    public function __construct(User $user, UserMeta $usermeta, Setting $setting, Post $post, PostMeta $postmeta, Stripe $stripe)
    {
        $this->user = $user;
        $this->usermeta = $usermeta;
        $this->setting = $setting;
        $this->post = $post;
        $this->postmeta = $postmeta;
        $this->stripe = $stripe;
    }

    //--------------------------------------------------------------------------

    public function select_plan()
    {

        $user_id = Auth::User()->id;

        $data['info'] = $info = $this->user->find($user_id);

        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }



        $data['info']->post = $post = $this->post
                        ->select('posts.*', 'm1.meta_value as payment_object')
                        ->from('posts')
                        ->join('postmeta AS m1', function ($join) use ($user_id) {
                            $join->on('posts.id', '=', 'm1.post_id')
                                 ->where('m1.meta_key', '=', 'payment_object');
                        })
                     ->where('post_name', 'subscription_plan')
                     ->where('post_type', 'payment')
                     ->where('post_author', $user_id)
                     ->orderBy('id', 'DESC')
                     ->first();


        if( Input::get('_token') ) {

            $plan_id = Input::get('plan');
            
            $updateRules = [
                'credit_card_number' => 'required|min:16|max:16',
                'credit_card_code'   => 'required|min:3|max:3',
                'credit_card_month'  => 'required',
                'credit_card_year'   => 'required',
            ];    

            if( $plan_id ) {
                $updateRules['agree'] = 'required';
            }

            $msg = ['agree.required' => 'You must agree to the Terms of Sale.'];

            $validator = Validator::make(Input::all(), $updateRules, $msg);

            if( ! $validator->passes() ) {
                return Redirect::route('owner.billings.select_plan')
                               ->withErrors($validator)
                               ->withInput(); 
                               
            }

            if( $plan_id ) {
    
                $plan = packages($plan_id);
     
                if($info->stripe_customer) {

                    $user_info = (object)array(
                        'firstname'      => $info->firstname,
                        'lastname'       => $info->lastname,
                        'email'          => $info->email,
                        'country'        => $info->country,
                        'payment_object' => @$data['info']->post->payment_object
                    );
                    $plan->payment_object = $this->stripe->stripe_update_charge($user_info, Input::all());

                } else {
                    $user_info = (object)array(
                        'firstname' => $info->firstname,
                        'lastname'  => $info->lastname,
                        'email'     => $info->email,
                        'country'   => $info->country,
                        'plan_id'   => $plan_id
                    );
                    $plan->payment_object = $this->stripe->stripe_create_charge($user_info, Input::all());                   

                }

                if(!$plan->payment_object['payment']) {
                    return Redirect::back()->withInput()->with('error', $plan->payment_object['msg']);
                }

                $this->usermeta->update_meta($user_id, 'stripe_customer', $plan->payment_object['customer']);
                   
            }


            $usermetas = array(
                'credit_card_number'     => Input::get('credit_card_number'),
                'credit_card_code'       => Input::get('credit_card_code'),
                'credit_card_month'      => Input::get('credit_card_month'),
                'credit_card_year'       => Input::get('credit_card_year'),
            );

            if($info->stripe_customer) {
                $this->stripe->stripe_update_card($info->stripe_customer, $usermetas);
            }

            foreach ($usermetas as $u_meta_key => $u_meta_value) {
                $this->usermeta->update_meta($user_id, $u_meta_key, $u_meta_value);
            }   
            

            return Redirect::route('owner.billings.index')
                           ->with('success','Your payment information has been updated!');
        }

        $data['rows'] = $this->post->where('post_type', 'plan')
                                   ->where('post_status', 'actived')
                                   ->get();

        return view('owner.billings.select-plan', $data);
    }

    //--------------------------------------------------------------------------


}
