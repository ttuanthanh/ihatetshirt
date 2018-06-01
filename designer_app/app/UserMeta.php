<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{

	protected $primaryKey = 'id';
	
	public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usermeta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'user_id',
		'meta_key',
		'meta_value',
	];

	/**
	 * The rules applied when creating a item
	 */
	public static $insertRules = [
		'meta_key' => 'required',
	];		

    public static function insert_meta($user_id, $key, $value) {
    	$usermeta = new UserMeta();
        $usermeta->user_id    = $user_id;
        $usermeta->meta_key   = $key;
        $usermeta->meta_value = $value;
        $usermeta->save();
    }

    public static function update_meta($user_id, $key, $value) {
		if($user_id) {
	        $usermeta = UserMeta::where('user_id', $user_id)->where('meta_key', $key)->first();
	        if($usermeta) { 
	            $usermeta->meta_value = $value;
	            $usermeta->save();
	        } else {
	            UserMeta::insert_meta($user_id, $key, $value);
	        }
    	}
    }

    public static function get_meta($user_id, $key, $array = false) {
    	if($user_id) {
	        $usermeta = UserMeta::where('user_id', $user_id)->where('meta_key', $key)->first();
	        $meta = @$usermeta->meta_value;    		
            return $array ? json_decode($meta) : $meta;
    	}
    }

    public static function get_metas($user_id) {
        $usermetas = UserMeta::where('user_id', $user_id)->get();
        foreach ($usermetas as $usermeta) {
            $user[$usermeta->meta_key] = $usermeta->meta_value;
        }
        return (object)$user;
        
    }
}
