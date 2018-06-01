<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config, Response, View, DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;
use App\Stripe;


/** All Paypal Details class **/
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Api\ShippingAddress;




class FrontendController extends Controller
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
    protected $stripe;
    protected $request;

    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Stripe $stripe, Request $request)
    {
        $this->user       = $user;
        $this->usermeta   = $usermeta;
        $this->post       = $post;
        $this->postmeta   = $postmeta;
        $this->setting    = $setting;
        $this->request    = $request;
        $this->stripe = $stripe;

        $this->view = 'frontend';

        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::check() ? Auth::user()->id : 0;
            return $next($request);
        });

        /** setup PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    //--------------------------------------------------------------------------

    public function home()
    {
        $data['features'] =  $this->post
                    ->select('posts.*', 'm1.meta_value as featured')
                    ->from('posts')
                    ->join('postmeta AS m1', function ($join) {
                        $join->on('posts.id', '=', 'm1.post_id')
                            ->where('m1.meta_key', '=', 'featured')
                            ->where('meta_value', 1);
                        })
                    ->where('post_type', 'customer_design')
                    ->where('post_status', 'published')
                    ->limit(8)
                    ->orderBy(DB::raw('RAND()'))
                    ->get();


        return view($this->view.'.home', $data);
    }

    //--------------------------------------------------------------------------

    public function contact($id='')
    {

        $rules = [
            'name'    => 'required',
            'email'   => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ];

        $validator = Validator::make(Input::all(), $rules);

        if( ! $validator->passes() ) {
            return Redirect::back()
                           ->withErrors($validator)
                           ->withInput(); 
        }

  
        $data['site_title']  = $this->setting->get_setting('site_title');
        $data['admin_email'] = $this->setting->get_setting('admin_email');
        $data['content'] = Input::get('message');

        Mail::send('emails.default', $data, function($message) use ($data)
        {
            $message->from( Input::get('email'), Input::get('firstname') )
                    ->to( $data['admin_email'], $data['site_title'] )
                    ->subject( Input::get('subject') );
        });

        return Redirect::back()
                       ->with('success','New user has been added!');

     
    }

    //--------------------------------------------------------------------------

    public function products($category='', $product='')
    {
        $data['category'] = $category;

        $sort = explode('-', Input::get('sort', 'post_name-asc'));

        $c=1;
        $data['categories'][0] = array(
            'id'=> 1, 
            'parent_id' => 0, 
            'slug' => '',
            'href'=> route('frontend.products'), 
            'name' => 'All Categories'
        );
        
        $categories = $this->post->where('post_type', 'product-category')
                                 ->where('post_status', 'actived')
                                 ->get();

        foreach ($categories as $cat) {
            $data['categories'][$c++] = array(
                'id'=> $cat->id, 
                'href'=> route('frontend.products', $cat->post_name), 
                'parent_id' => $cat->parent, 
                'slug' => $cat->post_name,                 
                'name' => ucwords($cat->post_title)
            );
        }



        $data['cat'] = $cat = @$this->post->where('post_name', $category)->first();

        $data['rows'] = $this->post
            ->select('posts.*', 'm1.meta_value as category', 'm2.meta_value as price')
            ->from('posts')
            ->join('postmeta AS m1', function ($join) use ($cat, $category) {
            $join->on('posts.id', '=', 'm1.post_id')
                ->where('m1.meta_key', '=', 'category');
                if( $category && $cat) {
                    $join->where('meta_value', 'LIKE', '%'.$cat->id.'%');
                }
            })
            ->join('postmeta AS m2', function ($join) {
                $join->on('posts.id', '=', 'm2.post_id')
                ->where('m2.meta_key', '=', 'starting_price');
            })
            ->orderBy($sort[0], $sort[1])
            ->where('post_type', 'product')
            ->where('post_status', 'published')
            ->paginate(16);

        return view($this->view.'.products', $data);
     
    }

    //--------------------------------------------------------------------------

    public function product($slug='')
    {

        $data['info'] = $info = $this->post->where('post_name', $slug )->where('post_type', 'product')->firstOrFail();
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        return view($this->view.'.product', $data);
     
    }

    //--------------------------------------------------------------------------

    public function results($slug='')
    {
        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->search($search)
                             ->whereIn('post_type', ['post','page','product'])
                             ->paginate(15);

        return view($this->view.'.posts.results', $data);
     
    }

    //--------------------------------------------------------------------------
 
    public function checkout_minicart()
    {
        $data['rows'] = Session::get('cart');

        return view($this->view.'.mini-cart', $data);
     
    }

    //--------------------------------------------------------------------------

    public function checkout_update()
    {

        $cart = Session::get('cart');

        $subtotal = 0;
        foreach ($cart['orders'] as $order) {
            $subtotal += $order['total_price'];
        }

        $date = date('Y-m-d');

        $coupon =  $this->post
                    ->select('posts.*', 'm1.meta_value as start_date', 'm2.meta_value as end_date')
                    ->from('posts')
                    ->join('postmeta AS m1', function ($join) use ($date) {
                        $join->on('posts.id', '=', 'm1.post_id')
                            ->where('m1.meta_key', '=', 'start_date')
                            ->where('m1.meta_value', '<=', $date);
                    })
                    ->join('postmeta AS m2', function ($join) use ($date) {
                        $join->on('posts.id', '=', 'm2.post_id')
                            ->where('m2.meta_key', '=', 'end_date')
                            ->where('m2.meta_value', '>=', $date);
                    })
                    ->where('post_name', Input::get('coupon'))
                    ->where('post_type', 'coupon')
                    ->where('post_status', 'published')
                    ->first();

        if( $coupon ) {
            foreach ($coupon->postmetas as $postmeta) {
                $coupon[$postmeta->meta_key] = $postmeta->meta_value;
            }    

            if( $coupon->discount_type == 'percent' ) {
                $discount_amount = $coupon->coupon_value / 100;
                $cart['discount'] = $cart['subtotal'] * $discount_amount; 
            } else {
                $cart['discount'] = $coupon->coupon_value;             
            }

            $cart['coupon_code']   = $coupon->post_name;
            $cart['coupon_id']     =  $coupon->id;        
            $cart['coupon_amount'] =  $coupon->coupon_value;
        } else {
            $cart['coupon_code']   = '';
            $cart['coupon_id']     = '';        
            $cart['coupon_amount'] = '';
            $cart['discount'] = 0;               
        }
        
        $cart['subtotal'] = round($subtotal, 2);

        $cart['shipping_method_id'] = $shipping_id = Input::get('shipping');
        $shipping = $this->post->where('post_type', 'shipping-method')->where('id', $shipping_id)->first();
        $cart['shipping_fee'] = $shipping->post_name;

        $quantity = 0;
        foreach ($cart['orders'] as $order) {
            $quantity += $order['quantity'];
        }

        $cart['discount'] = round($cart['discount'], 2);
        $cart['shipping_fee'] = round($cart['shipping_fee'], 2);

        $cart['quantity'] = $quantity;               

        $shipping_charge = ($cart['subtotal'] - $cart['discount']) * ($cart['shipping_fee'] / 100);
        $cart['shipping_charge'] = $shipping_charge;
        $cart['total'] = ($cart['subtotal'] - $cart['discount']) + $shipping_charge;         

        $cart['_discount'] = amount_formatted($cart['discount']);               
        $cart['_total'] = amount_formatted($cart['total']);               
        $cart['_shipping_charge'] = amount_formatted($cart['shipping_charge']);               
              

        if( $this->request->ajax() ) {
            Session::put('cart', $cart);
            return json_encode($cart);
        }

    }

    //--------------------------------------------------------------------------
  
    public function checkout()
    {        
        $data['post'] = $this->post;
        $data['cart'] = $cart = Session::get('cart');


        if( Input::get('_token') ) {

            $rules = [
                'billing_firstname'         => 'required',
                'billing_lastname'          => 'required',
                'billing_telephone'         => 'required',
                'billing_email'             => 'required|email',
                'billing_street_address_1'  => 'required',
                'billing_city'              => 'required',
                'billing_state'             => 'required',
                'billing_zip_code'          => 'required',
                'billing_country'           => 'required',
            ];

            $shipping_rules = [];

            if( Input::get('same_as_billing') ) {
                $shipping_rules = [
                    'shipping_firstname'         => 'required',
                    'shipping_lastname'          => 'required',
                    'shipping_telephone'         => 'required',
                    'shipping_email'             => 'required|email',
                    'shipping_street_address_1'  => 'required',
                    'shipping_city'              => 'required',
                    'shipping_state'             => 'required',
                    'shipping_zip_code'          => 'required',
                    'shipping_country'           => 'required',
                ];

                $rules = $rules + $shipping_rules;
            }


            if( Input::get('payment') == 'credit_card' ) {
                $cc_rules = [
                    'credit_card_holder'     => 'required',
                    'credit_card_number'     => 'required|numeric',
                    'credit_card_code'       => 'required|numeric|digits:3',
                    'credit_card_month'      => 'required',
                    'credit_card_year'       => 'required',
                ];

                $rules = $rules  + $cc_rules + $shipping_rules;
            }

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }
        
            $inputs = Input::except(['_token', 'shipping', 'payment']);

            $post = $this->post;

            $post->post_author  = $this->user_id;
            $post->post_content = array_to_json($cart);                
            $post->post_title   = 'Order';
            $post->post_name    = $reload = strtolower(str_random(32));
            $post->post_type    = 'order';
            $post->post_status  = 'pending';

            $ship_day = $this->postmeta->get_meta(Input::get('shipping'), 'ship_day');    

            $inputs['order_date'] = date('Y-m-d');
            $inputs['order_time'] = date('H:s');
            $inputs['due_date']   = $shipdate = date('Y-m-d', strtotime('+'.$ship_day.' day', strtotime(date('Y-m-d')) ));

            $inputs['shipping_method'] = Input::get('shipping');
            $inputs['payment_method']  = Input::get('payment');
            $inputs['shipping_fee']    = round($cart['shipping_fee'], 2);
           
            $subtotal = 0;
            foreach ($cart['orders'] as $order) {
                $subtotal += $order['total_price'];
            }

            $shipping_charge = ($subtotal - $cart['discount']) * ($cart['shipping_fee'] / 100);

            $inputs['shipping_charge'] = round($shipping_charge, 2);
            $inputs['deposit']         = 0;
            $inputs['discount']        = round($cart['discount'], 2);
            $inputs['subtotal']        = round($subtotal, 2);
            $inputs['total']           = ($subtotal - $cart['discount']) + $shipping_charge;       
            $inputs['payment_fee']     = $inputs['total'];
            $inputs['coupon_code']     = $cart['coupon_code'];
            $inputs['shipping_date']   = $shipdate;
            $inputs['approve_proof']   = 0; 
            $inputs['tracking_number'] = '';



            $data['customer_email'] = $inputs['billing_email'];

            if( ! Input::get('same_as_billing') ) {
                $inputs['shipping_firstname']        = $inputs['billing_firstname'];       
                $inputs['shipping_lastname']         = $inputs['billing_lastname'];        
                $inputs['shipping_company']          = $inputs['billing_company'];       
                $inputs['shipping_telephone']        = $inputs['billing_telephone'];       
                $inputs['shipping_email']            = $inputs['billing_email'];       
                $inputs['shipping_street_address_1'] = $inputs['billing_street_address_1'];
                $inputs['shipping_street_address_2'] = $inputs['billing_street_address_2'];
                $inputs['shipping_city']             = $inputs['billing_city'];            
                $inputs['shipping_state']            = $inputs['billing_state'];           
                $inputs['shipping_zip_code']         = $inputs['billing_zip_code'];        
                $inputs['shipping_country']          = $inputs['billing_country'];       
            }

            if( $inputs['payment_method'] == 'paypal' ) {

                $payer = new Payer();
                $payer->setPaymentMethod('paypal');

                $items = [];   

                foreach ($cart['orders'] as $order) {

                    $item = new Item();
                    $item->setName($order['name']) /** item name **/
                        ->setCurrency('USD')
                        ->setQuantity($order['quantity'])
                        ->setPrice($order['unit_price']); /** unit price **/

                    $items[] = $item;
                }

                if( $cart['shipping_charge'] ) {
                    $item = new Item();
                    $item->setName('Shipping Fee')
                        ->setCurrency('USD')
                        ->setQuantity(1)
                        ->setPrice($inputs['shipping_charge']);

                    $items[] = $item;       
                }
                             

                if( $cart['discount'] ) {
                    $item = new Item();
                    $item->setName('Discount')
                        ->setCurrency('USD')
                        ->setQuantity(1)
                        ->setPrice(-$inputs['discount']);

                    $items[] = $item;     
                }

                $item_list = new ItemList();
                $item_list->setItems($items);


                $amount = new Amount();
                $amount->setCurrency('USD')
                    ->setTotal($inputs['total']);

                $transaction = new Transaction();
                $transaction->setAmount($amount)
                    ->setItemList($item_list)
                    ->setDescription("Payment for custom T-shirt ordered by ".$inputs['billing_email']);

                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(URL::route('frontend.checkout.paypal'))
                    ->setCancelUrl(URL::route('frontend.checkout'));

                $payment = new Payment();
                $payment->setIntent('Sale')
                    ->setPayer($payer)
                    ->setRedirectUrls($redirect_urls)
                    ->setTransactions(array($transaction));
                    /** dd($payment->create($this->_api_context));exit; **/
                try {
                    $payment->create($this->_api_context);
                } catch (\PayPal\Exception\PPConnectionException $ex) {
                    if (\Config::get('app.debug')) {
                        return Redirect::back()->withInput()->with('error', 'Connection timeout');
                    } else {
                        return Redirect::back()->withInput()->with('error', 'Some error occur, sorry for inconvenient');
                    }
                }

                foreach($payment->getLinks() as $link) {
                    if($link->getRel() == 'approval_url') {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }

                /** add payment ID to session **/
                Session::put('checkout_inputs', $inputs);
                if(isset($redirect_url)) {
                    /** redirect to paypal **/
                    return Redirect::away($redirect_url);
                }

                return Redirect::route('paypal.checkout')->withInput()->with('error', 'Unknown error occurred');
            }

            if( $inputs['payment_method'] == 'credit_card' ) {

                $user_info = array(
                    'Firstname'        => $inputs['billing_firstname'],       
                    'Lastname'         => $inputs['billing_lastname'],        
                    'Company'          => $inputs['billing_company'],       
                    'Telephone'        => $inputs['billing_telephone'],       
                    'Email'            => $inputs['billing_email'],       
                    'Street Address 1' => $inputs['billing_street_address_1'],
                    'Street Address 2' => $inputs['billing_street_address_2'],
                    'City'             => $inputs['billing_city'],            
                    'State'            => $inputs['billing_state'],           
                    'Zip Code'         => $inputs['billing_zip_code'],        
                    'Country'          => countries($inputs['billing_country']),     
                );

                $payment_object = $this->stripe->stripe_create_charge($user_info, $inputs);                   

                if(!$payment_object['payment']) {
                    return Redirect::back()->withInput()->with('error', $payment_object['msg']);
                }
            }

            if( $post->save() ) {
                
                $inputs['order_id'] = $post->id;

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
                }

                  // BEGIN EMAIL CONFIRMATION
                $data['email'] = $this->post->where('post_type', 'email')
                                            ->where('post_name', 'order-details')
                                            ->first();

                if( $data['email']->post_status == 'actived' ) {

                    $data['info'] = $info = $this->post->find($post->id);
                    foreach ($info->postmetas as $postmeta) {
                        $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
                    }      

                    $table = \View::make($this->view.'.checkout.order-complete-table', $data);

                    $patterns = [
                        '/{firstname}/'     => $inputs['billing_firstname'],
                        '/{lastname}/'      => $inputs['billing_lastname'],
                        '/{date}/'          => date_formatted($inputs['order_date']),
                        '/{total}/'         => amount_formatted($inputs['total']),
                        '/{order_number}/'  => $post->id,
                        '/{order_complete_url}/' => route('frontend.checkout.completed', ['order' => $reload]),
                        '/{table}/'         => $table->render(),
                    ];

                    $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
                    $data['site_title']  = $this->setting->get_setting('site_title');
                    $data['admin_email'] = $this->setting->get_setting('admin_email');

                    Mail::send('emails.default', $data, function($message) use ($data)
                    {
                        $message->from($data['admin_email'], $data['site_title'])
                                ->to($data['customer_email'])
                                ->subject( $data['email']->post_title );
                    });
                }
                 // END EMAIL CONFIRMATION 

                Session::forget('cart');

                return Redirect::route($this->view.'.checkout.completed', ['order' => $reload])
                               ->with('success', 'Order has been completed!');
            } 

        }

        
        $data['cart'] = $cart = Session::get('cart');

        $data['payments'] = $payments = json_decode($this->setting->get_setting('payment_method'));
        $data['default_shipping'] = @$cart['shipping_method_id'] ? $cart['shipping_method_id'] : $this->setting->get_setting('shipping_method');

        
        $data['shippings'] = $minfos = $this->post->where('post_type', 'shipping-method')->orderBy('post_name', 'ASC')->get();
        //var_dump($minfo[0]['postmetas'][1]['attributes']);
        
        $sid = 0;
        foreach ($minfos as $minfo) {
            //$data['shippings'][$postmeta->meta_key] = $postmeta->meta_value;
        
            foreach ($minfo->postmetas as $postmeta) {
                $data['shippings'][$sid][$postmeta->meta_key] = $postmeta->meta_value;
            }
            $sid++;
        }
        //var_dump($data['shippings'][1]);
        if( $this->user_id ) {
            $data['info'] = $info = $this->user->find($this->user_id);
            foreach ($info->usermetas as $usermeta) {
                $data['info'][$usermeta->meta_key] = $usermeta->meta_value;
            }            
        }

        return view($this->view.'.checkout', $data);
     
    }

    //--------------------------------------------------------------------------

    public function checkout_paypal()
    {   

        $inputs = Session::get('checkout_inputs');
        $cart = Session::get('cart');

        if( !$inputs || !$cart ) {
            return Redirect::route('frontend.checkout');            
        }

        $post = $this->post;

        $post->post_author  = $this->user_id;
        $post->post_content = array_to_json($cart);                
        $post->post_title   = 'Order';
        $post->post_name    = $reload = strtolower(str_random(32));
        $post->post_type    = 'order';
        $post->post_status  = 'pending';

       if( $post->save() ) {
                

            foreach ($inputs as $meta_key => $meta_val) {
                $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
            }

              // BEGIN EMAIL CONFIRMATION
            $data['email'] = $this->post->where('post_type', 'email')
                                        ->where('post_name', 'order-details')
                                        ->first();

            if( $data['email']->post_status == 'actived' ) {

                $data['info'] = $info = $this->post->find($post->id);
                foreach ($info->postmetas as $postmeta) {
                    $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
                }      

                $table = \View::make($this->view.'.checkout.order-complete-table', $data);

                $patterns = [
                    '/{firstname}/'     => $inputs['billing_firstname'],
                    '/{lastname}/'      => $inputs['billing_lastname'],
                    '/{date}/'          => date_formatted($inputs['order_date']),
                    '/{total}/'         => amount_formatted($inputs['total']),
                    '/{order_number}/'  => $post->id,
                    '/{order_complete_url}/' => route('frontend.checkout.completed', ['order' => $reload]),
                    '/{table}/'         => $table->render(),
                ];

                $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
                $data['site_title']  = $this->setting->get_setting('site_title');
                $data['admin_email'] = $this->setting->get_setting('admin_email');

                Mail::send('emails.default', $data, function($message) use ($data)
                {
                    $message->from($data['admin_email'], $data['site_title'])
                            ->to($data['customer_email'])
                            ->subject( $data['email']->post_title );
                });
            }
             // END EMAIL CONFIRMATION 

            Session::forget('cart');
            Session::forget('checkout_inputs');

            return Redirect::route($this->view.'.checkout.completed', ['order' => $reload])
                           ->with('success', 'Order has been completed!');
        } 

    }

    //--------------------------------------------------------------------------

    public function checkout_completed()
    {
        $data['post'] = $this->post;

        $token = Input::get('order');

        $data['info'] = $info = $this->post->where('post_name', $token)->firstOrFail();
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }      

        return view($this->view.'.checkout.completed', $data);
    }

    //--------------------------------------------------------------------------

}
