<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mail, Auth, Request, Input, DB;


class Post extends Model
{
    use SoftDeletes;

	protected $primaryKey = 'id';
	
	public $timestamps = true;

    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'post_author',
		'post_content',
		'post_title',
		'post_status',
		'post_name',
		'post_type',
	];

	/**
	 * The rules applied when creating a item
	 */
	public static $insertRules = [
		'post_content' => 'required',
	];		


    public function scopeSearch($query, $data = array(), $selects = array(), $queries = array()) {

        $q = array();

        $s=1;

        if($selects) {
            /* Select */
            foreach($selects as $select) {
                $s_data = array('select' => $select, 's' => $s);
                $query->join("postmeta AS m{$s}", function ($join) use ($s_data) {            
                    $select = $s_data['select'];
                    $s = $s_data['s'];
                    $join->on("posts.id", '=', "m{$s}.post_id")
                         ->where("m{$s}.meta_key", '=', $select);
                });
                $select_data[] = "m{$s}.meta_value as ".$select;
                $s++;
            }
        }

        if( $queries ) {

            /* Search */
            foreach($queries as $q) {
                $s_data = array('select' => $q, 's' => $s, 'data' => $data);

                if( isset($data[$q]) ) {
                    if($data[$q] != '') {
                        $query->join("postmeta AS m{$s}", function ($join) use ($s_data) {
                            $select = $s_data['select'];
                            $where = @$s_data['data'][$select];
                            $s = $s_data['s'];
                            $join->on("posts.id", '=', "m{$s}.post_id")
                                 ->where("m{$s}.meta_key", '=', $select)
                                 ->where("m{$s}.meta_value", '=', $where);
                        });                    
                    }
                } elseif($q == 'date') {             
                        $query->join("postmeta AS m{$s}", function ($join) use ($s_data) {
                            $from = date_formatted_b($s_data['data']['from']);
                            $to   = date_formatted_b($s_data['data']['to']);
                            $s = $s_data['s'];

                            $join->on("posts.id", '=', "m{$s}.post_id")
                                 ->where("m{$s}.meta_key", '=', 'date')
                                 ->whereBetween("m{$s}.meta_value", [$from, $to]);
                        });    
                }
                $s++;
            }

            $select_data[] = 'posts.*';

            $query->select($select_data)
            ->from('posts');

        }

        if( isset($data['s']) ) {
            if($data['s'] != '')
            $query->where('posts.post_title', 'LIKE', '%'.$data['s'].'%');
        }

        if( isset($data['post_id']) ) {
            if($data['post_id'] != '')
            $query->where('posts.id', $data['post_id']);
        }

        if( isset($data['post_title']) ) {
            if($data['post_title'] != '')
            $query->where('posts.post_title', $data['post_title']);
        }

        if( isset($data['post_name']) ) {
            if($data['post_name'] != '')
            $query->where('posts.post_name', $data['post_name']);
        }

        if( isset($data['post_status']) ) {
            if($data['post_status'] != '') {
                $status = explode('|', $data['post_status']);
                $op = @$status[1] ? $status[1] : '=';
                $query->where('posts.post_status', $op, $status[0]);
            }
        }

        if( isset($data['parent']) ) {
            if($data['parent'] != '') {
                $query->where('posts.parent', $data['parent']);
            }
        }

        if( isset($data['post_content']) ) {
            if($data['post_content'] != '') {
                $post_content = explode('|', $data['post_content']);
                $query->where('posts.post_content', 'LIKE', '%'.$post_content[0].'%');
            }
        }

        if( isset($data['post_author']) ) {
            if($data['post_author'] != '')
            $query->where('posts.post_author', $data['post_author']);
        }

        if( isset($data['post_type']) ) {
            if($data['post_type'] != '')
            $query->where('posts.post_type', $data['post_type']);
        }

        if( isset($data['type']) ) {
            if($data['type'] == 'trash')
            $query->withTrashed()->where('posts.deleted_at', '<>', '0000-00-00');
        }

        return $query;
    }

    public function select_posts($search = array(), $select = array(), $query = array()) {
        return Post::search($search, $select, $query)
                   ->where('post_status', 'actived')
                   ->orderBy('post_title', 'ASC')
                   ->pluck('post_title', 'id')
                   ->toArray();
    }

    public function getAuthorNameAttribute() {
        return $this->user->firstname.' '.$this->user->lastname;
    }

    public function getCategoryCountAttribute() {
        $data = PostMeta::where('meta_key', 'category')
            ->where('meta_value', 'LIKE', '%'.(string)$this->id.'%')
            ->count();
        $data += Post::where('parent', $this->id)->count();
        return number_format($data);
    }

    public function getCategoryListAttribute() {
        $category = json_decode($this->get_meta($this->id, 'category'));

        if( $category ) {

            if( is_array($category) ) {
                $data = Post::whereIn('id', $category)
                            ->where('post_status', 'actived')
                            ->pluck('post_title')
                            ->toArray();                
                return ucwords(implode(', ', $data));            
            } else {
                $data = Post::where('id', $category)
                            ->where('post_status', 'actived')
                            ->first();                    
                return $data->post_title;            
            }

        }

        return 'uncategorised';
    }

    public function getCategoryRouteAttribute() {
        $category = $this->get_meta($this->id, 'category');

        if( $category ) {
            $data = Post::where('id', $category)
                        ->where('post_status', 'actived')
                        ->pluck('post_name')
                        ->toArray();

            return implode('-', $data);     
        }

        return 'uncategorised';
    }

    public function add_order_note($order_id, $note = '', $post_name = '', $post_type = 'order_note') {

        $user_id = Auth::User()->id;

        $post = new Post();
        $post->post_author  = $user_id;
        $post->post_name    = $post_name;
        $post->post_title   = name_formatted($user_id, 'f l');
        $post->parent       = $order_id;
        $post->post_content = $note;     
        $post->post_status  = 'actived';
        $post->post_type    = $post_type;
        $post->created_at   = date('Y-m-d H:i:s');
        $post->save();

        return $post;
    }

    public function user() {
        return $this->belongsTo('App\User', 'post_author', 'id');
    }

    public function get_meta($id, $key) {
        return PostMeta::get_meta($id, $key);
    }    

    public function get_usermeta($id, $key) {
        return UserMeta::get_meta($id, $key);
    }   

    public function postmetas() {
        return $this->hasMany('App\PostMeta', 'post_id');
    }


}
