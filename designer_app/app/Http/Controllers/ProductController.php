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

class ProductController extends Controller
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

        $this->post_type = 'product';
        $this->view      = 'admin.products';
        $this->single    = 'Product';
        $this->label     = 'Products';

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

        if( $this->request->ajax() ) {
            $this->setting->update_meta('default_product', Input::get('id'));
            return;              
        }

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
        
        $data['default'] = @$this->setting->get_setting('default_product');

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
                'name' => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.add')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::except(['_token', 'name', 'description', 'slug', 'status']);

            $post = $this->post;

            $post->post_author  = $this->user_id;
            $post->post_content = Input::get('description');                
            $post->post_title   = $title = Input::get('name');
            $post->post_name    = text_to_slug($title);
            $post->post_type    = $this->post_type;
            $post->post_status  = Input::get('status');

            if( $post->save() ) {
                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
                }

                return Redirect::route($this->view.'.edit', $post->id)
                               ->with('success', 'New '. strtolower($this->single).' has been added!');
            } 
        }

        $c=1;

        $data['categories'][0] = array(
            'id'=> 1, 
            'parent_id' => 0, 
            'name' => 'Uncategorised'
        );
        foreach ($this->post->where(['post_type' => 'product-category'])->get() as $category) {
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

        if( $this->request->ajax() ) {
            if(  Input::get('design') ) {
                $this->postmeta->update_meta($id, 'product_design', array_to_json(Input::get('product')));
                return 'success';
            } else {
                $data['i'] = $i = Input::get('i') + 1; 
                return view($this->view.'.product-design', $data);            
            }
        }

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        if( Input::get('_token') )
        {
            if( Input::get('tab') != 2 ) {
                $rules = ['name' => 'required'];    
                $validator = Validator::make(Input::all(), $rules);
                if( ! $validator->passes() ) {
                    return Redirect::route($this->view.'.edit', $id)
                                   ->withErrors($validator)
                                   ->withInput(); 
                }
                    
                $inputs = Input::except(['_token', 'name', 'description', 'slug', 'status', 'product']);
                
                $post = $this->post->find( $id );

                $post->post_author  = $this->user_id;
                $post->post_content = Input::get('description');                
                $post->post_title   = $title = Input::get('name');
                $post->post_name    = Input::get('slug') ? Input::get('slug') : text_to_slug($title);
                $post->post_type    = $this->post_type;
                $post->post_status  = Input::get('status');
                $post->updated_at   = date('Y-m-d H:i:s');

                $sizes = '';
                foreach(Input::get('size') as $size) {
                    if($size['name']) $sizes[] = $size;               
                }    
                $inputs['size'] = $sizes;               


                if( $post->save() ) {
                    foreach ($inputs as $meta_key => $meta_val) {
                        $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_val));
                    }

                    return Redirect::route($this->view.'.edit', $id)
                                   ->with('success', $this->single.' has been updated!');
                } 
            } else {              
                $this->postmeta->update_meta($id, 'product_design', array_to_json(Input::get('product')));
                return Redirect::route($this->view.'.edit', [$id, query_vars()])
                               ->with('success', $this->single.' has been updated!');
            }
        }


        $c=1;

        $data['categories'][0] = array(
            'id'=> 1, 
            'parent_id' => 0, 
            'name' => 'Uncategorised'
        );
        foreach ($this->post->where(['post_type' => 'product-category'])->get() as $category) {
            $data['categories'][$c++] = array(
                'id'=> $category->id, 
                'parent_id' => $category->parent, 
                'name' => $category->post_title
            );
        }

        $data['colors'] = $this->post->where('post_type', 'color')
        ->where('post_status', 'actived')
        ->orderBy('post_title', 'ASC')
        ->get();

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
    
    public function get_quote()
    {   

        if( in_array( Input::get('action'), ['save-modal', 'start']) ) {

            $start_design = Session::get('start_design');
            $data['start_design'] = $start_design; 

            $pid = Input::get('pid') ? Input::get('pid') : $start_design['inputs']['pid'];
         

            $data['info'] = $info = $this->post->find($pid);
            foreach ($info->postmetas as $postmeta) {
                $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
            }

            if( ! $start_design ) {
                $color_index = Input::get('index');
                $product_design = json_decode($info->product_design, true);

                $data['start_design']['inputs'] = [
                    'pid'           => $pid,
                    'type'          => @$product_design[$color_index]['color'] == '#ffffff' ? 0 : 1,
                    'image'         => $product_design[$color_index]['image'][0]['url'],
                    'color_index'   => $color_index,
                    'color_hex'     => $product_design[$color_index]['color'],
                    'color_title'   => $product_design[$color_index]['color_title'],
                    'front_color'   => '',
                    'back_color'    => '',
                    'size'          => [6]
                ]; 
            }

            if( $token = Input::get('reload') ) {                
                $reload = $this->post->where('post_name', $token)->first();   

                $data['email'] = $this->postmeta->get_meta($reload->id, 'email');
                $data['design_title'] = $this->postmeta->get_meta($reload->id, 'design_title');

                $content = json_decode($reload->post_content, true);
                $data['start_design']['inputs']  = $content['start_design']['inputs'];
            }

            return view('frontend.designer.save-modal', $data);            
        }



        $product_id  = Input::get('pid');
        $size        = Input::get('size');
        $color       = Input::get('type');
        $front_color = Input::get('front_color');
        $back_color  = Input::get('back_color');
        $sizes = array();

        $quantity = $price_product = $price_total = 0;

        $product = $this->post->find($product_id);
        foreach ($product->postmetas as $postmeta) {
            $product[$postmeta->meta_key] = $postmeta->meta_value;
        }

        $arr_attribute = json_decode($product->size, true);
   
        if($color == 0){ //white
            for($i = 0; $i < count($size); $i++){      
                $price_product += $size[$i] * $arr_attribute[$i]['price_white'];
                $quantity += $size[$i];
                $sizes[$arr_attribute[$i]['name']] = $size[$i];

            }
        } else { //color

            for($i = 0; $i < count($size); $i++){     
                $price_product += $size[$i] * $arr_attribute[$i]['price_color'];
                $quantity += $size[$i];
                $sizes[$arr_attribute[$i]['name']] = $size[$i];
            }

        }


        $msg['quantity'] = '';

        if( ! $quantity ) {
            $msg['quantity'] = 'Please select size and enter quantity. ';
        }
        if( $quantity < 6 ) {
            $msg['quantity'] .= 'Minimum order is 6 pieces';
        }

        $data = [
            'quantity'     => number_format(0), 
            'unit_price'   => amount_formatted(0),
            'total_price'  => amount_formatted(0),
            '_total_price' => 0,
            '_unit_price'  => 0,
            'msg' => $msg,
        ];

        if( $quantity ) {

            $pricing = json_decode($this->setting->get_setting('price-table'), true);

            $shipping_box_fee = $pricing['shipping_box_fee'];
            $shipping_box     = $pricing['shipping_box'];
            $front_location   = $pricing['front_location'];
            $back_location    = $pricing['back_location'];

            // SELECT THE LARGER PRINT LOCATION FOR FRONT PRICE
            if ($front_color < $back_color ){        
                $front_color = $back_color;
                $back_color  = 0;
            }

            $price_front = 0;            
            if( $front_color ) {
                for($i = 0; $i < count($front_location); $i++){
                    if($quantity >= $front_location[$i]['from'] && $quantity <= $front_location[$i]['to']){                    
                        $price_front = $front_location[$i]['color'][$front_color];
                        break;
                    }
                }                
            }

            $price_back = 0;
            if( $back_color ) {
                for($i = 0; $i < count($back_location); $i++){
                    if($quantity >= $back_location[$i]['from'] && $quantity <= $back_location[$i]['to']){                    
                        $price_back = $back_location[$i]['color'][$back_color];
                        break;
                    }
                }                    
            }
            

            $price_print = ($price_front + $price_back) * $quantity;
            $price_total = $price_product + $price_print;
            
            // Adding Setup charges : num of color * $20
            $price_total += ( $front_color + $back_color ) * 20;     

            // Get shipping fee per box
            $number_boxes = 0;
            for($i = 0; $i < count($shipping_box); $i++){
                if($quantity >= $shipping_box[$i]['from'] && $quantity <= $shipping_box[$i]['to']){                    
                    $number_boxes = $shipping_box[$i]['box'];
                    break;
                }
            }    
            
            $price_total += ($number_boxes * $shipping_box_fee);

            // Addding 8% tax            
            $price_total += ($price_total * 8 ) /100;

            $price_total = round($price_total, 2);
            $unit_price  = round($price_total/$quantity, 2);

            $data['quantity']     = $quantity;
            $data['unit_price']   = amount_formatted($unit_price);
            $data['total_price']  = amount_formatted($unit_price * $quantity);
            $data['_unit_price']  = str_replace(',', '', number_format($unit_price, 2));
            $data['_total_price'] = str_replace(',', '', number_format($unit_price * $quantity, 2));
            $data['msg']          = $msg; 
            $data['inputs']       = Input::all();
            $data['sizes']        = $sizes;
        }

        Session::put('start_design', $data);

        if( $this->request->ajax() ) {
            return json_encode($data);            
        }

        return Redirect::route('frontend.designer.index', ['action' => 'start']);
     
    }

    //--------------------------------------------------------------------------
        
}
