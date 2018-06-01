<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config, Image;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;


class DesignerController extends Controller
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

        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::check() ? Auth::user()->id : '';
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function designer() {

        $data['setting'] = $this->setting;

        if( Input::get('action') == 'start' ) {
            $start_design = Session::get('start_design');
            $data['start_design'] = $start_design; 
        } 

        if( $this->request->ajax() ) {

            $category_id = Input::get('category');

            if( $category_id ) {
                $data['products'] = $this->post
                    ->select('posts.*', 'm1.meta_value as category')
                    ->from('posts')
                    ->join('postmeta AS m1', function ($join) use ($category_id) {
                        $join->on('posts.id', '=', 'm1.post_id')
                            ->where('m1.meta_key', '=', 'category');
                            if( $category_id != 'all' ) {
                                $join->where('meta_value', 'LIKE', '%'.$category_id.'%');
                            }
                        })
                    ->where('post_type', 'product')
                    ->get();
                return view('frontend.designer.products.list', $data);                    
            }

            $product_id = Input::get('color');
            
            if( $product_id ) {
                $data['product'] = $this->post->find($product_id);
                $data['colors'] = json_decode($this->postmeta->get_meta($product_id, 'product_design'), true);
                return view('frontend.designer.products.colors', $data);  
            }


        }
        
        $data['products'] = $this->post->where('post_type', 'product')
                                       ->where('post_status', 'published')
                                       ->get();

        $data['categories'] = $this->post
                     ->where('post_type', 'product-category')
                     ->orderBy('post_title', 'ASC')
                     ->get()
                     ->pluck('post_title', 'id')
                     ->toArray();

        $data['colors'] = $this->post->where('post_type', 'color')
                                    ->where('post_status', 'actived')
                                    ->orderBy('post_title', 'ASC')                                    
                                    ->get()
                                    ->pluck('post_title', 'post_content');

        return view('frontend.designer.index', $data);
    }

    //--------------------------------------------------------------------------

    public function fonts() {

    }

    //--------------------------------------------------------------------------

    public function products($id='') {   

        $products = array();

        $rows = $this->post
            ->select('posts.*', 'm1.meta_value as category')
            ->from('posts')
            ->join('postmeta AS m1', function ($join) use ($id) {
                $join->on('posts.id', '=', 'm1.post_id')
                    ->where('m1.meta_key', '=', 'category');
                    if( $id != 'all' ) {
                        $join->where('meta_value', 'LIKE', '%'.$id.'%');
                    }
                })
            ->where('post_type', 'product')
            ->where('post_status', 'published')
            ->get();

        foreach ($rows as $row) {
            $postmeta = get_meta( $row->postMetas()->get() );

            $products[] = [
                'id' => $row->id,
                'name' => $row->post_title,
                'price' => @$postmeta->starting_price,
                'category' => $postmeta->category,
                'currency' => "USD",
                'thumb' => has_image(str_replace('large', 'thumb', $postmeta->image)),
                'image' => has_image($postmeta->image),
                'sub_images' => $postmeta->gallery,
                'description' => $postmeta->excerpt
            ];
        }


        $data = [
            'status' => true,
            'products' => $products
       ];


        return json_encode($data);

    }

    //--------------------------------------------------------------------------

    public function product($id='') {   

        $product = json_decode($this->postmeta->get_meta($id, 'product_design'), true);
        $index = Input::get('i');

        // Get selected product from session of start_design triggered from single product 
        $start_design = Session::get('start_design');        
        if( $index == 1 && $start_design ) {
            $images = $product[$index]['image'];
        } else {
            $images = $index != 'undefined' ? $product[$index]['image'] : $product[1]['image'];
        }

        $encode_images = str_replace('uploads', url('uploads/'),  json_encode($images));
        
        $data = [
            "status" => true,
            "images" => json_decode($encode_images, true),
            "message" => "Images Loaded Successfully"
        ];

        return json_encode($data);
    }
 

    //--------------------------------------------------------------------------

    public function attributes($id='') {   

        $product = json_decode($this->postmeta->get_meta($id, 'product_design'), true);
        return str_replace('px', '', $product[1]['image'][0]['attr']);
    }
    
    //--------------------------------------------------------------------------
        
    public function publish() {   


        $rules = [
            'design_title' => 'required',
            'email' => 'required|email',
        ];    

        $validator = Validator::make(Input::all(), $rules);

        if( ! $validator->passes() ) {
            $msg = ['error' => true, 'msg' => $validator->errors()];
            return json_encode($msg);
        }

        $result = array();
        $filenames = array();

        $reload = Input::get('reload');
        $product_id = Input::get('product_id');
        $color_index = Input::get('colorIndex');

        $product_design = json_decode($this->postmeta->get_meta($product_id, 'product_design'), true);
 
        if( $reload != 'undefined' ) {
            $post = $this->post->where('post_name', $reload)->first();
        } else {
            $post = $this->post;
            $post->post_name = $reload = strtolower(str_random(32));
        }
            
        $path = 'assets/uploads/designs/';

        if( ! file_exists($path) ) mkdir($path);

        $png_filetime = [];
        $png = json_decode(Input::get('png'), true);
        foreach ($png as $key => $value) {
            if(!empty($value) && $value != null){
                $png_design = $path.$reload.'-'.$key.'.png';  
                $img = \Image::make($value)->save($png_design);              
                $png_filetime[] = filemtime($png_design);
            }
        }

        $svg = json_decode(Input::get('svg'), true);
        foreach ($svg as $key => $value) {
            if(!empty($value) && $value != null){
                $svg_design = $path.$reload.'-'.$key.'.svg';  
                file_put_contents($svg_design, file_get_contents($value));    
            }
        }

        parse_str(Input::get('team'), $team);

        $details = [
            'product_id'    => $product_id,
            'color_index'   => $color_index,
            'image'         => glob($path.$reload."-*")[0],
            'png_filetime'  => json_encode($png_filetime),
            'details'       => $product_design[$color_index],
            'object'        => json_decode(Input::get('object')),
            'comment'       => Input::get('comment'),
            'add_name'      => json_decode(Input::get('add_name')),
            'add_number'    => json_decode(Input::get('add_number')),
            'team'          => $team['team'],
            'start_design'  => $start_design = Session::get('start_design')
        ];

        $product = @$this->post->find($product_id);

        $post->post_author  = $this->user_id ? $this->user_id : 0;
        $post->post_title   = @$product->post_title; 


        $post->post_content = json_encode($details);
        $post->post_type    = 'customer_design';
        $post->post_status  = 'published';
        $post->save();



        $inputs['comment'] = Input::get('comment');
        $inputs['email'] = $data['customer_email'] = Input::get('email');
        $inputs['design_title'] = Input::get('design_title') ? Input::get('design_title') : 'Design #'.$post->id;

        foreach ($inputs as $meta_key => $meta_val) {
            $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
        }


        $result['status']   = true;
        $result['filename'] = asset('assets/uploads/designs/1.png');
        $result['message']  = 'Your design has been saved.';
        $result['token']    = $reload;
        $result['error']    = false;
        $result['msg']      = '';

        // Add to cart
        if( Input::get('action') == 'add' ) {

            $cart = Session::get('cart');
            if( ! $cart ) {
                $cart = [
                    'coupon_id'     => '',
                    'coupon_code'   => '',
                    'coupon_amount' => 0,
                    'shipping_method_id' => 0,
                    'payment_method' => '',
                    'subtotal'      => $start_design['_unit_price'],
                    'shipping_fee'  => 0,
                    'discount'      => 0,
                    'shipping_charge' => 0,
                    'quantity'      => $start_design['quantity'],
                    'total'         => $start_design['_total_price'],
                ];            
            }

            $cart['orders'][$reload] = array(
                'name'        => @$product->post_title,
                'image'       => 'assets/uploads/designs/'.$reload.'-0.png',      
                'quantity'    => $start_design['quantity'],
                'unit_price'  => $start_design['_unit_price'],
                'total_price' => $start_design['_total_price'],
                'design_id'   => $post->id,
                'product_id'  => @$product->id,
                'slug'        => @$product->post_name,
                'token'       => $reload,
                'design'      => $start_design['inputs'],
                'sizes'       => $start_design['sizes']
            );

            $quantity = 0;
            foreach ($cart['orders'] as $order) {
                $quantity += $order['quantity'];
            }

            $cart['quantity'] = $quantity;  

            Session::put('cart', $cart);
        }


        if( $inputs['email'] && !Session::get('save_notify') ) {

            // BEGIN EMAIL CONFIRMATION
            $data['email'] = $this->post->where('post_type', 'email')
                                        ->where('post_name', 'saved-design')
                                        ->first();

            if( $data['email']->post_status == 'actived' ) {

                $change_password_url = route('frontend.designer.index', ['reload' => $reload]);

                $patterns = [
                    '/{url_design}/' => $change_password_url
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

            Session::put('save_notify', $reload);
        }

        return json_encode($result);
    }

    //--------------------------------------------------------------------------
    
    public function cliparts($category='') {   

        $cliparts = $this->post
                     ->search([], ['image'], ['image'])
                     ->where('post_type', 'clipart')
                     ->where('parent', $category)
                     ->get()
                     ->pluck('image')
                     ->toArray();

        $encode_images = str_replace(['uploads', '-large.png'], [url('uploads/'), '.svg'],  json_encode($cliparts));
        

        $data = [
            'status' => true,
            'page' => $category,
            'loadMore' => 0,
            'data_result' =>  json_decode($encode_images, true)
       ];

        return json_encode($data);

    }

    //--------------------------------------------------------------------------


    public function settings()
    {   


    $clipart_categories = $this->post
        ->join('posts AS p', function ($join) {
        $join->on('p.id', '=', 'posts.parent')
             ->where('p.post_type', 'clipart-category');
        })->where('posts.post_type', 'clipart')
        ->get()
        ->pluck('post_title', 'id')
        ->toArray();

        $product_categories = $this->post
             ->where('post_type', 'product-category')
             ->orderBy('post_title', 'ASC')
             ->get()
             ->pluck('post_title', 'id')
             ->toArray();


        foreach( explode( ',', $this->setting->get_setting('fonts')) as $font ) {
            $fonts[] = array('name' => $font);
        }

        $default_product_id = @$this->setting->get_setting('default_product');

        // Get selected product from session of start_design triggered from single product 
        $start_design = Session::get('start_design');        
        if( $start_design ) {
            $default_product_id = $start_design['inputs']['pid'];
        }

        $default_product = $this->post->find( $default_product_id );
        
        if( $default_product ) {
            foreach ($default_product->postmetas as $postmeta) {
                $default_product[$postmeta->meta_key] = $postmeta->meta_value;
            }            
        }

        $productAttr = json_decode($this->postmeta->get_meta($default_product_id, 'product_design'), true);

        $detail = '';
        if( $reload = Input::get('reload') ) {
            $reload = $detail = $this->post->where('post_name', $reload)->first();
            foreach ($detail->postmetas as $postmeta) {
                $detail[$postmeta->meta_key] = $postmeta->meta_value;
            }
        }

        $product_attributes = str_replace('px', '', $productAttr[1]['image'][0]['attr']);
        $default_image = has_image($productAttr[1]['image'][0]['url']);
        $color_index = $start_design ? $start_design['inputs']['color_index'] : 1;

        if( $start_design ) {
            $product_attributes = str_replace('px', '', $productAttr[$start_design['inputs']['color_index']]['image'][0]['attr']);
            $default_image = has_image($start_design['inputs']['image']);
        }

        $sizes = '';
        if( @$default_product->size ) {      
            $sizes = array_pluck(json_decode($default_product->size, true), 'name');
        }

        $data = [
            'status' => 1,
            'settings' => [
                'notification_messages' => [
                    'GENERAL_ERROR' => "Error. Unknown system response. Controller: ProductController",
                    'CANVAS_EMPTY' => "Select product to perform this action",
                    'IMAGE_SIZE' => "Image is too large (MAX SIZE: 300*300)",
                    'WORDCLOUD_EMPTY' => "Please enter some words for generate wordcloud.",
                    'TYPE_NUMBER_REQUIRED' => "Only numbers is allowed",
                ],
                'request_url' => [
                    'LOAD_PRODUCTS' => URL::route('frontend.designer.products'),
                    'LOAD_PRODUCT_SUB_IMAGES' => URL::route('frontend.designer.product'),
                    'LOAD_GRAPHICS' => URL::route('frontend.designer.cliparts'),
                    'LOAD_COLOR_SCHEME' => url('designer-tool/inc/theme.php'),
                    'SAVE_DESIGN' => URL::route('frontend.designer.publish'),
                    'MINI_CART' => URL::route('frontend.checkout.mini-cart'),
                    'UPLOAD_IMAGE' => route('frontend.designer.upload')                    
                ],
                'general_settings' => [
                    'loadMore' => 0,
                    'graphicsPage' => 1,
                    'reload' => $reload,
                    'defaultProductId' => $default_product_id,
                    'defaultProductAttr' => $product_attributes,
                    'colorIndex' => $color_index,
                    'defaultProductImage' => $default_image,
                    'detail' =>  $detail,
                    'quantity' => 1,
                    'sizes' => json_encode($sizes),
                    'defaultPrice' => number_format(@$default_product->starting_price, 2),
                    'defaultCurrency' => currency_symbol($this->setting->get_setting('currency')),
                    'defaultProductTitle' => @$default_product->post_title,
                    'qrCode' => 'qrcode',
                    'enter_drawing_mode' => 'Enter Drawing Mode',
                    'drawing_mode_selector' => 'Pencil',
                    'drawing_line_width' => 30,
                    'drawing_color' => '#000000',
                    'drawing_line_shadow' => 0,
                    'primaryColor' => '#26C4E2',
                    'secondaryColor' => '#ffffff',
                    'graphicsCategory' => '',
                    'graphicsCategories' => $clipart_categories,
                    'productCategories' => $product_categories,
                    'fonts' => $fonts,
                    'textDefaults' => array(
                        'originX' => 'left',
                        'scaleX' => 1,
                        'scaleY' => 1,
                        'fontFamily' => $this->setting->get_setting('default_font'),
                        'fontSize' => $this->setting->get_setting('default_font_size'),
                        'fill' => '#000000',
                        'textAlign' => 'left'
                    ),
                    'productCategory' => '',
                    'productByName' => 'DESC',
                    'productByNames' => [
                        'ASC' => 'ASC',
                        'DESC' => 'DESC'
                    ],
                    'predicate' => 'id',
                    'reverse' => ''
                ],
            'social_settings' => ['fb_app_id' => '1.0199447684698E+14']
            ],
            'message'=> 'Settings Loaded Successfully'
        ];

        return json_encode( $data );
    }

    //--------------------------------------------------------------------------

    function upload() {

        $mimes = 'image/gif,image/png,image/jpeg,image/vnd.adobe.photoshop,image/svg+xml,application/pdf,application/postscript,application/octet-stream';

        $rules = [
            'file' => 'required|mimetypes:'.strtoupper( $mimes).','.$mimes.'|max:200000',
        ];    
        $msg = [
            'file.mimetypes' => 'The file must be a file of type: png, jpg, gif, pdf, psd, ai, eps, svg.',
            'file.max' => 'The file may not be greater than 200 MB.'
        ]; 

        $validator = Validator::make(Input::all(), $rules, $msg);

        if( ! $validator->passes() ) {
            $msg = ['error' => true, 'msg' => $validator->errors()];
            return json_encode($msg);
        }

        $file = Input::file('file');

        $ext = $file->getClientOriginalExtension();
        $imageFile  = $file->getRealPath();
        $random = strtolower(str_random(16));


        $png_design = 'assets/uploads/graphics/'.$random.'.png';

        $dir = $_SERVER['DOCUMENT_ROOT'].str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
        $magic_path = $dir.'assets/uploads/graphics/'.$random.'.png';

        $im = new \Imagick(  $imageFile ); 
        if( Input::get('bg')==1) {
            $im->transparentPaintImage($im->getImageBackgroundColor(), 0, 10000,FALSE);
        }

        $im->setImageFormat('png');
        $im->writeImage($magic_path);
        $im->destroy();

        $upload_image = Session::get('upload_image') ? Session::get('upload_image') : array(); 

        $upload_image = array_prepend($upload_image, $png_design);
        Session::put('upload_image', $upload_image);  


        $img = Image::make($png_design)->resize(300, '', function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($png_design);


        return json_encode(['error' => false, 'msg' => asset($png_design)]);
    }

    //--------------------------------------------------------------------------

    function repaint() {    

        $color = Input::get('color');
        $x = Input::get('x');
        $y = Input::get('y');
        $img = Input::get('img');
        $orig = Input::get('orig');


        $random = strtolower(str_random(16));

        $file = pathinfo( parse_url( $img, PHP_URL_PATH ) );
        $folder = 'assets/uploads/graphics/';

        if (extension_loaded('imagick')) {
            $dir = $_SERVER['DOCUMENT_ROOT'].str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
            $magic_path = $dir.$folder.$file['basename'];

            $im = new \Imagick($magic_path);

            $val = 65535/40;
            //divide by fuzz dilution, 1 is none
            $val = floatval($val/0.5);

            $target = $im->getImagePixelColor($x, $y);
            $im->floodfillPaintImage($color, $val, $target, $x, $y, false);

            $im->setImageFormat('png');


            $fileorig = pathinfo( parse_url( $orig, PHP_URL_PATH ) );

            if( str_contains($file['filename'], '-paint-') ) {
                $lastfile = substr($file['filename'], strrpos($file['filename'], '-') + 1);

                $newfile = str_replace('-'.$lastfile , '-'.$lastfile, $file['basename']);

                if( $file['filename'] == $fileorig['filename'] ) {
                    $newfile = str_replace('-'.$lastfile , '-'.$random, $fileorig['basename']);
                }

            } else {
                $newfile = str_replace('.'.$file['extension'], '-paint-'.$random.'.'.$file['extension'], $fileorig['basename']);    
            }

            $filename = $folder.$newfile;
            $magic_path = $dir.$filename;

            $im->writeImage($magic_path);
            $im->destroy();

            $filemtime = filemtime($magic_path);

            return asset($filename."?".$filemtime);

        }
    }

    //--------------------------------------------------------------------------

    function clear_repaint() {    
        $type = Input::get('type');
        $orig = Input::get('orig');
        $src = Input::get('src');

        $folder = 'assets/uploads/graphics/';
        $fileorig = pathinfo( parse_url( $orig, PHP_URL_PATH ) );
        $filesrc = pathinfo( parse_url( $src, PHP_URL_PATH ) );

        if( str_contains($fileorig['filename'], '-paint-') ) {
            if( $type == 'save' ) {
                unlink($folder.$fileorig['basename']);
            }
        }

        if( str_contains($filesrc['filename'], '-paint-') ) {
            if( $type == 'cancel' ) {
                unlink($folder.$filesrc['basename']);
            }
        }
           
    }

    //--------------------------------------------------------------------------

}
