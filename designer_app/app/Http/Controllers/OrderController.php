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

class OrderController extends Controller
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

        $this->post_type = 'order';
        $this->view      = 'admin.orders';
        $this->single    = 'Order';
        $this->label     = 'Orders';

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
        $data['post']   = $this->post;

        $data['rows'] = $this->post
                             ->where('post_type', 'product')
                             ->where('post_status', 'published')
                             ->orderBy('id', 'DESC')
                             ->get();


        $data['customers'] = $this->user
                             ->where('group', 'customer')
                             ->where('status', 'actived')
                             ->orderBy('id', 'DESC')
                             ->get()
                             ->pluck('fullname', 'id')
                             ->toArray();

        if( $id = Input::get('id') ) {
            $data['info'] = $info = $this->post->find( $id );
            foreach ($info->postmetas as $postmeta) {
                $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
            }            
        }


        if( Input::get('_token') )
        {
            $rules = [
                'billing_firstname' => 'required',
                'billing_lastname'  => 'required',
                'billing_email'     => 'required|email',
                'billing_telephone' => 'required',
                'billing_street_address_1' => 'required',
                'billing_city'      => 'required',
                'billing_state'     => 'required',
                'billing_zip_code'  => 'required',
                'billing_country'   => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.add', query_vars())
                               ->withErrors($validator)
                               ->withInput(); 
            }


            $post = $this->post;
            
            $design = ['pid', 'type', 'image', 'color_index', 'color_hex', 'color_title','size', 'front_color', 'back_color'];        

            $inputs = Input::except( array_merge($design, ['_token', 'unit_price', 'total_price', 'customer', 'id']) );

            $cart = Session::get('start_design');
            $get_design = Input::only( $design );

            $post->post_author  = $this->user_id;
            $post->post_name    = $token = strtolower(str_random(32));

            $cart['name']  = $info->post_title;

            $cart['unit_price']  = $cart['_unit_price'];
            $cart['total_price'] = $cart['_total_price'];
            $cart['image'] = $get_design['image'] = Input::get('image');
            $order['orders'][$token] = $cart + ['design' => $get_design];

            $post->post_content = array_to_json($order);                
            $post->post_title   = 'Order';
            $post->post_type    = 'order';
            $post->post_status  = 'pending';

            $ship_day = $this->postmeta->get_meta($shipping_id, 'ship_day');    

            $inputs['order_date'] = date('Y-m-d');
            $inputs['order_time'] = date('H:s');
            $inputs['due_date']   = $shipdate = date('Y-m-d', strtotime('+'.$ship_day.' day', strtotime(date('Y-m-d')) ));

            $shipping_id = Input::get('shipping_method');
            $shipping = $this->post->where('post_type', 'shipping-method')->where('id', $shipping_id)->first();


            $ship_day = $this->postmeta->get_meta($shipping_id, 'ship_day');    

            $inputs['shipping_method'] = $shipping_id;
            $inputs['payment_method']  = Input::get('payment_method');
            $inputs['shipping_fee']    = $shipping->post_name;
            $inputs['deposit']         = 0;
            $inputs['discount']        = 0;
            $inputs['subtotal']        = Input::get('total_price');
            $inputs['total']           = $inputs['subtotal'] + $inputs['shipping_fee'];            
            $inputs['coupon_code']     = '';
            $inputs['shipping_date']   = $shipdate;
            $inputs['shipping_date']   = date('Y-m-d', strtotime('+'.$ship_day.' day', strtotime(date('Y-m-d')) ));
            $inputs['approve_proof']   = $inputs['payment_fee'] = 0; 
            $inputs['tracking_number'] = '';
            $inputs['shipping_firstname']        = $inputs['billing_firstname'];       
            $inputs['shipping_lastname']         = $inputs['billing_lastname'];        
            $inputs['shipping_company']          = $inputs['billing_company'];       
            $inputs['shipping_telephone']        = $inputs['billing_telephone'];       
            $inputs['shipping_email']            = $inputs['billing_email'];       
            $inputs['shipping_street_address_1'] = $inputs['billing_street_address_1'];
            $inputs['shipping_city']             = $inputs['billing_city'];            
            $inputs['shipping_state']            = $inputs['billing_state'];           
            $inputs['shipping_zip_code']         = $inputs['billing_zip_code'];        
            $inputs['shipping_country']          = $inputs['billing_country'];         

            if( $post->save() ) {

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, $meta_val);
                }

                return Redirect::route($this->view.'.edit', [$post->id, 'tab' => 'order-details'])
                               ->with('success', 'New '. strtolower($this->single).' has been added!');
            } 
        }

        $data['payments'] = $payments = json_decode($this->setting->get_setting('payment_method'));
        $data['default_shipping'] = Input::old('shipping_method') ? Input::old('shipping_method') : $this->setting->get_setting('shipping_method');
        $data['shippings'] = $this->post->where('post_type', 'shipping-method')->orderBy('post_name', 'ASC')->get();

        if( $this->request->ajax() ) {

            if( Input::get('action') == 'add' ) {

            }

            return view($this->view.'.select-product', $data);

            $data['user'] = $user = $this->user->find( Input::get('id') );
            foreach ($user->usermetas as $usermeta) {
                $data['user'][$usermeta->meta_key] = $usermeta->meta_value;
            }  
            return json_encode($data['user']);            
        }

        return view($this->view.'.add', $data);
    }
    //--------------------------------------------------------------------------

    public function remove_order()
    {
        $id = Input::get('id');
        $token = Input::get('token');

        if( $token ) {

            $post = $this->post->find($id);

            $content = json_decode($post->post_content, true);

            unset($content['orders'][$token]);

            $post->post_content = json_encode($content);

            $post->save();

            $info = $this->post->find( $id );
            foreach ($info->postmetas as $postmeta) {
                $info[$postmeta->meta_key] = $postmeta->meta_value;
            }   

            $subtotal= 0;
            foreach ($content['orders'] as $order) {
                $subtotal += $order['total_price'];
            }


            $inputs['subtotal']        = $subtotal;
            $inputs['total']           = ($subtotal + $info['shipping_fee']) - $info['discount'];


            foreach ($inputs as $meta_key => $meta_val) {
                $this->postmeta->update_meta($post->id, $meta_key, $meta_val);
            }

        }

        return Redirect::route($this->view.'.edit', [$post->id, 'tab' => 'order-details'])
                        ->with('success', 'Selected product has been removed!');
    }

    //--------------------------------------------------------------------------

    public function update_order()
    {
        $id = Input::get('id');
        $token = Input::get('token');

        if( $token ) {

            $post = $this->post->find($id);

            $content = json_decode($post->post_content, true);
            
            $content['orders'][$token]['quantity'] = Input::get('quantity');
            $content['orders'][$token]['unit_price'] = Input::get('price');
            $content['orders'][$token]['total_price'] = Input::get('price') * Input::get('quantity');
            $content['orders'][$token]['name'] = Input::get('name');

            $post->post_content = json_encode($content);

            $post->save();

            $info = $this->post->find( $id );
            foreach ($info->postmetas as $postmeta) {
                $info[$postmeta->meta_key] = $postmeta->meta_value;
            }   

            $subtotal= 0;
            foreach ($content['orders'] as $order) {
                $subtotal += $order['total_price'];
            }


            $inputs['subtotal']        = $subtotal;
            $inputs['total']           = ($subtotal + $info['shipping_fee']) - $info['discount'];


            foreach ($inputs as $meta_key => $meta_val) {
                $this->postmeta->update_meta($post->id, $meta_key, $meta_val);
            }

        }

        return Redirect::back()
                    ->with('success', 'Selected order has been updated!');
    }
   
    //--------------------------------------------------------------------------

    public function update_product()
    {
        $id = Input::get('id');
        $token = Input::get('token');

        if( $token ) {

            $post = $this->post->find($id);

            $content = json_decode($post->post_content, true);
            
            $size = [];
            foreach(Input::get('artinfo') as $art) {
                $size[$art['size']] = $art['quantity'];
            }

            $content['orders'][$token]['sizes'] = $size;
            $content['orders'][$token]['design']['color_hex'] = Input::get('color_hex');
            $content['orders'][$token]['design']['color_title'] = Input::get('color_title');

            $post->post_content = json_encode($content);
        }

        $post->save();

        return Redirect::back()
                        ->with('success', 'Selected product has been removed!');
    }

    //--------------------------------------------------------------------------

    public function add_custom()
    {
        $design = Session::get('start_design');
        
        $id = Input::get('id');
        $token = strtolower(str_random(32));

        if( $order_id = Input::get('order_id') ) {
            $post = $this->post->find($order_id);

            $content = json_decode($post->post_content, true);

            $content['orders'][$token] = [
                "quantity"      => $design['quantity'],
                "unit_price"    => Input::get('unit_price'),
                "total_price"   => Input::get('total_price'),
                "sizes"         => $design['sizes'],
                "name"          => Input::get('name'),
                "image"         => 'uploads/'.array_last(explode('/', Input::get('image'))),
                "design"        => ['color_hex' => Input::get('color_hex'), 'color_title' => Input::get('color_title')]
            ];

            $post->post_content = json_encode($content);
            $post->save();


            $info = $this->post->find( $order_id );
            foreach ($info->postmetas as $postmeta) {
                $info[$postmeta->meta_key] = $postmeta->meta_value;
            }   

            $subtotal= 0;
            foreach ($content['orders'] as $order) {
                $subtotal += $order['total_price'];
            }


            $inputs['subtotal']        = $subtotal;
            $inputs['total']           = ($subtotal + $info['shipping_fee']) - $info['discount'];
        

            foreach ($inputs as $meta_key => $meta_val) {
                $this->postmeta->update_meta($post->id, $meta_key, $meta_val);
            }


        } elseif( $id ) {

            $post = $this->post->find($id);

            $content = json_decode($post->post_content, true);

            $content['orders'][$token] = [
                "quantity"      => 0,
                "unit_price"    => 1,
                "total_price"   => 0,
                "sizes"         => [],
                "name"          => "Custom Product",
                "image"         => "",
                "design"        => ['color_hex' => '#000000', 'color_title' => 'Black']
            ];

            $post->post_content = json_encode($content);
            $post->save();
        } else {
            
            $post = $this->post;

            $post->post_author  = $this->user_id;
            $post->post_name    = $token = strtolower(str_random(32));

            $content['orders'][$token] = [
                "quantity"      => 0,
                "unit_price"    => 1,
                "total_price"   => 0,
                "sizes"         => [],
                "name"          => "Custom Product",
                "image"         => "",
                "design"        => ['color_hex' => '#000000', 'color_title' => 'Black']
            ];

            $post->post_content = array_to_json($content);                
            $post->post_title   = 'Order';
            $post->post_type    = 'order';
            $post->post_status  = 'pending';

            $inputs['order_date'] = date('Y-m-d');
            $inputs['order_time'] = date('H:s');
            $inputs['due_date']   = date('Y-m-d');

            $inputs['shipping_method'] = '';
            $inputs['payment_method']  = '';
            $inputs['shipping_fee']    = 0;
            $inputs['deposit']         = 0;
            $inputs['discount']        = 0;
            $inputs['due_date']        = '';
            $inputs['subtotal']        = $inputs['payment_fee'] = 0;
            $inputs['total']           = $inputs['subtotal'] + $inputs['shipping_fee'];
            $inputs['coupon_code']     = '';
            $inputs['shipping_date']   = '';
            $inputs['approve_proof']   = 0; 
            $inputs['tracking_number'] = '';

            $post->save();

            foreach ($inputs as $meta_key => $meta_val) {
                $this->postmeta->update_meta($post->id, $meta_key, $meta_val);
            }

        }

        return Redirect::route($this->view.'.edit', [$post->id, 'tab' => 'order-details'])
                        ->with('success', 'Custom product has been added!');


    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {
        $tab = Input::get('tab');

        $data['file_assets'] = 'orders/'.$id.'/'.$tab;

        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;


        $data['rows'] = $this->post
                             ->where('post_type', 'product')
                             ->where('post_status', 'published')
                             ->orderBy('id', 'DESC')
                             ->get();

        $data['notes'] = $this->post->whereIn('post_type', ['order_comment', 'order_note'])->where('parent', $id)->orderBy('id', 'ASC')->get();
        $data['garments'] = $this->post->where('post_type', 'garment')->where('parent', $id)->get();
        $data['shipping_methods'] = $this->post->where('post_type', 'shipping-method')->get();

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        if( $this->request->ajax() ) {
            $order_id = Input::get('id');

            if( Input::get('action') == 'delete-note' ) {
                return $this->post->find($order_id)->forceDelete();
            }

            if( Input::get('action') == 'add-note' ) {

                $data['note'] = $this->post->add_order_note($order_id, Input::get('note'), Input::get('tab'), 'order_comment');

                return view($this->view.'.note', $data);                    
            }         
        }

        if( in_array($tab, ['apparel-order'] ) ) {
            if( $edit_garment = Input::get('edit') ) {
                $data['garment'] = json_decode($this->post->find($edit_garment)->post_content);
            }

            if( $delete_garment = Input::get('delete') ) {
                $this->post->find($delete_garment )->forceDelete();
                return Redirect::back()
                               ->with('success', 'Garment has been deleted!');
            }
        }

        if( Input::get('_token') )
        {
            $inputs = Input::except(['_token', 'status', 'order_time_h', 'order_time_m', 'shipping_time_h', 'shipping_time_m', 'tab', 'garment']);

            $post = $this->post->find( $id );

            $post->post_status  = Input::get('status');
            $post->updated_at   = date('Y-m-d H:i:s');

            $inputs['order_date'] = date_formatted_b($inputs['order_date']);
            $inputs['order_time'] = Input::get('order_time_h').':'.Input::get('order_time_m');
            $inputs['due_date']   = date_formatted_b($inputs['due_date']);

            if( in_array($tab, ['artwork', 'proof']) ) {
                if( Input::file('gallery') ) {
                    foreach (Input::file('gallery') as $arts_gallery) {

                        $ext = $arts_gallery->getClientOriginalExtension();
                        $imageFile  = $arts_gallery->getRealPath();

                        $random = strtolower(str_random(16));

                        $png_design = 'assets/uploads/'.$data['file_assets'].'/'.$random.'.png';

                        $dir = $_SERVER['DOCUMENT_ROOT'].str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
                        $magic_path = $dir.$png_design;

                        $im = new \Imagick(  $imageFile ); 
                        $im->setImageFormat('png');
                        $im->writeImage($magic_path);
                        $im->destroy();

                    }                             
                }
                $this->post->add_order_note($id, 'Update '.Input::get('tab'), Input::get('tab'), 'order_note');
            }

            if( in_array($tab, ['apparel-order']) && Input::get('add_garment') ) {
                $status = 'added!';
                $garment = $this->post;
                if($edit_garment) {
                    $status = 'updated!';
                    $garment = $this->post->find($edit_garment);
                }

                $garment->post_author  = $this->user_id;
                $garment->post_content = array_to_json(Input::get('garment'));
                $garment->parent       = $id;
                $garment->post_type    = 'garment';
                $garment->save();

                return Redirect::route('admin.orders.edit', [$id, 'tab' => 'apparel-order'])
                               ->with('success', 'Garment has been '.$status);   
            }


            if( Input::get('art_due_date') )
            $inputs['art_due_date'] = date_formatted_b($inputs['art_due_date']);

            if( Input::get('shipping_time_h') ) {
                $inputs['shipping_time'] = Input::get('shipping_time_h').':'.Input::get('shipping_time_m');
            }

            if( Input::get('shipping_date') ) {                
                $inputs['shipping_date'] = date_formatted_b($inputs['shipping_date']);

                if( $info->shipping_date !=  $inputs['shipping_date']) 
                $this->post->add_order_note($id, 'Changed ship date to '.$inputs['shipping_date'], Input::get('tab'), 'order_note');
            }


            if( $info->post_status != 'shipped' && Input::get('status') == 'shipped' ) {

                $data['customer_email'] = $info->billing_email;

                // BEGIN EMAIL CONFIRMATION
                $data['email'] = $this->post->where('post_type', 'email')
                                            ->where('post_name', 'order-status')
                                            ->first();

                if( $data['email']->post_status == 'actived' ) {

                    $data['info'] = $info = $this->post->find($post->id);
                    foreach ($info->postmetas as $postmeta) {
                        $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
                    }      

                    $patterns = [
                        '/{firstname}/'     => $inputs['billing_firstname'],
                        '/{lastname}/'      => $inputs['billing_lastname'],
                        '/{date}/'          => date_formatted($inputs['order_date']),
                        '/{total}/'         => amount_formatted($inputs['total']),
                        '/{order_number}/'  => $post->id,
                        '/{status}/'        => $post->post_status,
                        '/{total}/'         => amount_formatted($info->total),
                    ];



                    $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
                    $data['site_title']  = $this->setting->get_setting('site_title');
                    $data['admin_email'] = $this->setting->get_setting('admin_email');

                    $data['subject'] = preg_replace(array_keys($patterns), $patterns, $data['email']->post_title);

                    Mail::send('emails.default', $data, function($message) use ($data)
                    {
                        $message->from($data['admin_email'], $data['site_title'])
                                ->to($data['customer_email'])
                                ->subject( $data['subject'] );
                    });
                }
                 // END EMAIL CONFIRMATION 
            }

            if( $post->save() ) {

                if( Input::hasFile('file') ) {
                    $pic = upload_image(Input::file('file'), 'companies', $info->company_logo);
                    $inputs['company_logo'] = $pic;       
                }

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_val));
                }

                return Redirect::back()
                               ->with('success', $this->single.' has been updated!');
            } 
        }

        return view($this->view.'.edit', $data);
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
        $post->forceDelete();

        $msg = 'Selected '.strtolower($this->single).' has been deleted permanently!';

        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------
    
}
