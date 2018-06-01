<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{

	protected $primaryKey = 'id';
	
	public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'postmeta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'post_id',
		'meta_key',
		'meta_value',
	];

	/**
	 * The rules applied when creating a item
	 */
	public static $insertRules = [
		'meta_key' => 'required',
	];		

    public static function insert_meta($post_id, $key, $value) {
    	$postmeta = new PostMeta();
        $postmeta->post_id    = $post_id;
        $postmeta->meta_key   = $key;
        $postmeta->meta_value = $value;
        $postmeta->save();
    }

    public static function update_meta($post_id, $key, $value) {
        $postmeta = PostMeta::where('post_id', $post_id)->where('meta_key', $key)->first();
        if($postmeta) {
            $postmeta->meta_value = $value;
            $postmeta->save();
        } else {
            PostMeta::insert_meta($post_id, $key, $value);
        }
    }


    public static function get_metas($post_id) {
        $post = array();
        $postmetas = PostMeta::where('post_id', $post_id)->get();
        foreach ($postmetas as $postmeta) {
            $post[$postmeta->meta_key] = $postmeta->meta_value;
        }
        return (object)$post;
    }

    public static function get_meta($post_id, $key, $array = false) {
        if($post_id) {
            $postmeta = PostMeta::where('post_id', $post_id)->where('meta_key', $key)->first();
            $meta = @$postmeta->meta_value;         
            return $array ? json_decode($meta) : $meta;  
        }
    }

}
