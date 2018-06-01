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

class SubscriberController extends Controller
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

        $this->post_type = 'subscriber';
        $this->view      = 'admin.subscribers';
        $this->single    = 'Subscriber';
        $this->label     = 'Subscribers';

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

        $rules = [
            'email' => 'required|email|unique:posts,post_title',
        ];    

        $msg = ['email.unique' => 'Email address has been already subscribed!'];

        $validator = Validator::make(Input::all(), $rules, $msg);

        if( ! $validator->passes() ) {
            return json_encode(['error' => true, 'msg' => $validator->errors()]);
        }

        $post = $this->post;

        $post->post_author  = 0;
        $post->post_title   = Input::get('email');                
        $post->created_at   = date('Y-m-d H:i:s');
        $post->post_status  = 'actived';
        $post->post_type    = $this->post_type;

        if( $post->save() ) {
            return json_encode([
                'error' => false, 
                'msg' => 'You have successfully subscribed! Thanks for subscribing to our newsletter!'
            ]);
        } 

    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {
        $this->post->findOrFail($id)->delete();
        $msg = 'Selected '.strtolower($this->single).' has been move to trashed!';
        return Redirect::route($this->view.'.index')
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
   
    public function export()
    {   
        $date = date('Y-m-d');
        // output headers so that the file is downloaded rather than displayed
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="subscribers.csv"');
         
        // do not cache the file
        header('Pragma: no-cache');
        header('Expires: 0');
         

        // create a file pointer connected to the output stream
        $file = fopen('php://output', 'w');
        // send the column headers
     
        // output each row of the data
        $data_new=array();

        $rows = $this->post
                     ->where('post_type', $this->post_type)
                     ->orderBy('id', 'DESC')
                     ->get();

        $data_new  = array('Email', 'Date', 'Time');  
        
        fputcsv($file, $data_new);

        foreach ($rows as $row)
        {   
            $data_new  = array(
                'from' => $row->post_title,
                'Date' => date_formatted($row->created_at),
                'Time' => date('h:i A', strtotime($row->created_at))
            );   

            fputcsv($file, $data_new);
        }

        exit;
    }

    //--------------------------------------------------------------------------
     

            

  
}
