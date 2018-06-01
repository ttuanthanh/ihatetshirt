<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
  /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

	public $timestamps = false;

	//----------------------------------------------------------------	

	public static function get_setting($key) {
		$data = Setting::where('key', $key)->first();
    return $data ? $data->value : '';
	}

	//----------------------------------------------------------------

    public static function insert_meta($key, $value) {
      $postmeta = new Setting();
      $postmeta->key   = $key;
      $postmeta->value = $value;
      $postmeta->save();
    }

	//----------------------------------------------------------------

    public static function update_meta($key, $value) {
        $postmeta = Setting::where('key', $key)->first();
        if($postmeta) {
            $postmeta->value = $value;
            $postmeta->save();
        } else {
            Setting::insert_meta($key, $value);
        }
    }

	//----------------------------------------------------------------

  public function update_metas($inputs) {
    foreach($inputs as $key => $val) {
        $setting = Setting::where('key', $key)->first();
        if( ! $setting ) {
            $setting = new Setting();
        }
        $setting->key   = $key;
        $setting->value = $val;
        $setting->save();                    
    }   
  }

  //----------------------------------------------------------------

}
