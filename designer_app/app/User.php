<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input, Request, Auth, URL, Mail;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'status',
        'group',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'forgot_password_token', 'verify_token'
    ];

    public static $unsets = ['_token', 'op', 'file', 'firstname', 'lastname', 'email', 'status'];

    /**
     * The rules applied when creating a item
     */
    public static $insertRules = [
        'firstname'  => 'required|min:1|max:32',
        'lastname'   => 'required|min:1|max:32',
        'username'   => 'required|min:4|max:32|unique:users,username',
        'email'      => 'required|email|max:64|unique:users,email',
        'password'   => 'required|min:4|max:32',
        'group'      => 'required',
    ];    

    public static $registerRules = [
        'firstname'  => 'required|min:1|max:32',
        'lastname'   => 'required|min:1|max:32',
        'email'      => 'required|email|max:64|unique:users,email',
        'password'   => 'required|min:6|max:32|confirmed',
        'password_confirmation' => 'required|min:6',
    ];    

    public static $forgotPassword = [
        'email' => 'required|email|max:64|exists:users,email',
    ];

    public static $newPassword = [
        'new_password'              => 'required|min:6|max:64|confirmed',
        'new_password_confirmation' => 'required|min:6',
    ];

    public function usermetas()
    {
        return $this->hasMany('App\UserMeta', 'user_id');
    }

    
    public function scopeSearch($query, $data = array(), $selects = array(), $queries = array()) {

        $q = array();


        /* Select */
        $s=1;
        foreach($selects as $select) {
            $s_data = array('select' => $select, 's' => $s);
            $query->join("usermeta AS m{$s}", function ($join) use ($s_data) {            
                $select = $s_data['select'];
                $s = $s_data['s'];
                $join->on("users.id", '=', "m{$s}.user_id")
                     ->where("m{$s}.meta_key", '=', $select);
            });
            $select_data[] = "m{$s}.meta_value as ".$select;
            $s++;
        }

        /* Search */
        foreach($queries as $q) {
            if( isset($data[$q]) ) {
                if($data[$q] != '') {

                    $s_data = array('select' => $q, 's' => $s, 'data' => $data);
                    $query->join("usermeta AS m{$s}", function ($join) use ($s_data) {
                        $select = $s_data['select'];
                        $where = @$s_data['data'][$select];
                        $s = $s_data['s'];
                        $join->on("users.id", '=', "m{$s}.user_id")
                             ->where("m{$s}.meta_key", '=', $select)
                             ->where("m{$s}.meta_value", '=', $where);
                    });    
                
                }
            }
            $s++;
        }

        $select_data[] = 'users.*';

        $query->select($select_data)
        ->from('users');

        if( isset($data['s']) ) {
            if($data['s'] != '')
            $query->where('users.usermeta', 'LIKE', '%'.$data['s'].'%');
        }

        if( isset($data['status']) ) {
            if($data['status'] != '')
            $query->where('users.status', $data['status']);
        }

        if( isset($data['group']) ) {
            if($data['group'] != '')
            $query->where('users.group', $data['group']);
        }

        if( isset($data['email']) ) {
            if($data['email'] != '')
            $query->where('users.email', 'LIKE', '%'.$data['email'].'%');
        }

        if( isset($data['type']) ) {
            if($data['type'] == 'trash')
            $query->withTrashed()->where('users.deleted_at', '<>', '0000-00-00');
        }


        return $query;
    }

    public function get_meta($key, $value)
    {
        return UserMeta::get_meta($key, $value);
    }

  
    public function send_email_verification($user_id) {
        $info = User::find($user_id); 
        $info->verify_token = $token = str_random(64);
        $info->save();

        $mail['url']  = URL::route('auth.verify', $token);
        $mail['site_name'] = $site_name = ucwords(Setting::get_setting('site_title'));

        $mail['email_support'] = Setting::get_setting('admin_email');
        $mail['email_title']   = 'Please verify your '.$site_name.' account';
        $mail['email_subject'] = 'Please verify your '.$site_name.' account';


        $mail['email'] = $info->email;
        
        Mail::send('emails.verify', $mail, function($message) use ($mail)
        {
            $message->from($mail['email_support'], $mail['email_title']);
            $message->to($mail['email'])->subject($mail['email_subject']);
        });        
    }

    public function getGroupNameAttribute() {
        $post = Post::where('post_name', $this->group);
        if( $post->exists() ) 
            return $post->first()->post_title;
    }

    public function getFullnameAttribute() {
      return ucwords(strtolower($this->firstname.' '.$this->lastname));
    }

    public static function force_destroy($id='') {

        $user = User::withTrashed()->findOrFail($id);

        /* Delete all related records in usermeta table */
        UserMeta::where('user_id', $id)->delete();

        /* Delete all related images */
        $dir = 'uploads/users/'.$id;
        if( file_exists($dir) ) { 
            array_map('unlink', glob("$dir/*.*"));
            rmdir($dir);    
        }

        /* Delete records in user table */
        $user->forceDelete();
  
    }

}

