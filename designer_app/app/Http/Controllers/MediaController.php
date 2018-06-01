<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config, File, Response, Image;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;
use SVG\SVGImage;

class MediaController extends Controller
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

        $this->view      = 'admin.media';
        $this->label     = 'Media Library';

        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::check() ? Auth::user()->id : '';
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function index()
    {
        $folder = Input::get('folder');

        // Check if URI segment has slash or file not exist
        if( $folder && substr($folder, -1) != '/' || !file_exists( 'uploads/'.$folder ) ) {
            return Redirect::route('admin.media.index', query_vars('folder=0'));            
        }    

        $data = media_library();

        $data['view']    = $this->view;                                      
        $data['label']   = $this->label; 

        if( Input::get('access_method') == 'frame' ) {
            return view($this->view.'.frame.index', $data);
        }

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {
        $data['view']    = $this->view;                                      
        $data['label']   = $this->label; 

        if( Input::get('access_method') == 'frame' ) {
            return view($this->view.'.frame.add', $data);
        }

        return view($this->view.'.add', $data);
    }

    //--------------------------------------------------------------------------

    public function upload() {

        $input = Input::all();

        $rules = array(
            'file' => 'max:9999999993000',
        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails())
        {
            return Response::make($validation->errors->first(), 400);
        }

        $file = Input::file('file');

        $imageFile  = $file->getRealPath();

        $ext        = $file->getClientOriginalExtension();
        $name       = str_replace([' ', '-'], '_', ucwords( pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ));
        $path       = 'uploads/'.Input::get('folder');  
        $string     = $name.'-'.strtolower(str_random(8));

        if( $ext == 'svg' ) {
            /* Original */
            $svg_name   = $path.$string.'.'.$ext;
            move_uploaded_file($imageFile, $svg_name); 

            if (extension_loaded('imagick')) {
                $dir = $_SERVER['DOCUMENT_ROOT'].str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
                $large_path = $dir.$path.$string.'-large.png';

                /* Large PNG */
                $im = new \Imagick(  $dir.$svg_name ); 
                $im->transparentPaintImage($im->getImageBackgroundColor(), 0, 10000,FALSE);
                $im->setImageFormat('png');
                $im->writeImage($large_path);
                $im->destroy();

                /* thumb */
                $medium_path   = $path.$string.'-thumb.png';
                \Image::make($large_path)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->crop(300, 300, null, null)->save($medium_path);
                compress($medium_path, $medium_path, 70);

            }

        } else {

            $thumbnail_path   = $path.'/'.$string.'-thumb.'.$ext;
            $upload_success = \Image::make($imageFile)->resize(150, '', function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(150, 150, null, null)->save($thumbnail_path);
            compress($thumbnail_path, $thumbnail_path, 70);

            /* Medium */
            $medium_path   = $path.'/'.$string.'-medium.'.$ext;
            \Image::make($imageFile)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(300, 300, null, null)->save($medium_path);
            compress($medium_path, $medium_path, 70);

            /* Large */
            $large_path   = $path.'/'.$string.'-large.'.$ext;
            \Image::make($imageFile)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($large_path);

        }

        $data['filename'] = $string;

        return Response::json($data, 200);

    }

    //--------------------------------------------------------------------------

    public function delete()
    {
        $dir = 'uploads/'.Input::get('folder');

        foreach (glob($dir.'*-'.Input::get('filename')."-*") as $file) {
           unlink($file);
        }
    }

    //--------------------------------------------------------------------------

    public function unlink()
    {
        $file = Input::get('file');
        unlink($file);
    }

    //--------------------------------------------------------------------------

    public function mkdir()
    {
        $path = Input::get('folder').str_replace([' ', '-'], '_', Input::get('new'));
        $folder = 'uploads/'.$path;
        if( file_exists($folder) ) {
            return Redirect::back()
                           ->with('error', 'Unable to create folder!');  
        } else {
            mkdir( $folder );         
            return Redirect::route($this->view.'.add', ['folder' => $path.'/'])
                           ->with('success', 'New folder has been created!');     
        }                   
    }

    //--------------------------------------------------------------------------

}
