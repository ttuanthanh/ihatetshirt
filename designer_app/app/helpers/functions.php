<?php
function is_plural($string) {
  return ($string > 1) ? 's' : '';
}

//----------------------------------------------------------------

function ordinal($num) {
  if (!in_array(($num % 100),array(11,12,13))){
    switch ($num % 10) {
      // Handle 1st, 2nd, 3rd
      case 1:  return $num.'st';
      case 2:  return $num.'nd';
      case 3:  return $num.'rd';
    }
  }
  return $num.'th';
}

//----------------------------------------------------------------

function get_cc_months($val ='') {  
  foreach (range(1, 12) as $month) {
    $m =sprintf('%02d', $month);
    $data[$m] = getMonths($m); 
  } 

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function get_cc_years($val ='') {  
  foreach (range(date('Y'), date('Y')+7) as $year) {
    $data[$year] = $year; 
  } 

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------


function code_to_text($val ='') {
  return ucwords(str_replace('_', ' ', $val));  
}

//----------------------------------------------------------------

function stars_review($count = 0) {
  $star = '';
  if($count) {
    $c = explode('.', $count);
    foreach(range(1, $c[0]) as $s) {
      $star .= '<i class="fa fa-star"></i> ';  
    }

    if(@$c[1]) $star .= '<i class="fa fa-star-half"></i> ';   
  }

  echo $star;
}

//----------------------------------------------------------------

function array_val_formatted($key, $val) {
  
  $data = '';

  $file = [
    "profile_picture",
    "proof_of_insurance"
  ];

  $hidden = ['facebook_access_token'];

  $is_numeric = ['exemption_amount', 'exemption_rate', 'excess'];

  if( in_array($key, $hidden) ) {
    return false;
  }

  if( in_array($key, $file) ) {
    $data = '';
  }  elseif( $key == 'payroll_period' ) {
    $data = payroll_period($val);
  } elseif( $key == 'tax_status' ) {
    $data = tax_status($val);
  } elseif( $key == 'civil_status' ) {
    $data = civil_status($val);
  }  elseif( in_array($key, $is_numeric) ) {
      $data = number_format($val, 2);
  } else {
    $data = $val;
  }

  return $data;
}

//----------------------------------------------------------------

function editFile($file, $key, $value, $seperator ='=') {
  $fh = fopen($file,'r+');
  // string to put username and passwords
  $i=1;
  $contents = '';
  while(!feof($fh)) {
    $user = explode($seperator,fgets($fh));

    // take-off old "\r\n"
    $file_key = @trim($user[0]);
    $file_val = @trim($user[1]);
    // check for empty indexes

    if($i == 1) {
      $contents .= "TIMEZONE" . '=' . "$value\n";       
    }

    if ($file_key == $key) {
        $file_val = ""; 
    }

    if (!empty($file_key) AND !empty($file_val)) {
        $contents .= $file_key . $seperator . $file_val;
        $contents .= "\n";
    }
    if($file_key == '') {
        $contents .= "\n";
    }
    $i++;
   }

  // using file_put_contents() instead of fwrite()
  file_put_contents($file, $contents);
  fclose($fh);  
}

//----------------------------------------------------------------

function selected($val, $post) {
  return ($val == $post) ? 'selected="selected"' : '';  
}

//----------------------------------------------------------------

function actived($val, $post) {
  return ($val == $post) ? 'active' : ''; 
}

//----------------------------------------------------------------

function checked($val, $post) {
  return ($val == $post) ? 'checked="checked"' : '';  
}

//----------------------------------------------------------------

function status($val ='') {
  return ($val == 1) ? 'Active' : 'Inactive'; 
}

//----------------------------------------------------------------

function time_zone($val='') {

  $timezone = (array)json_decode(file_get_contents('data/timezone.json'));

  if( $val ) {
      foreach($timezone as $s) {
        foreach($s as $k => $v) {
          $data[$k] = $v;
        }
      }
    return $data[$val];
  } else {
    foreach($timezone as $k => $v) {
      $data[$k] = (array)$v;
    }

    return $data;
  }
}

//----------------------------------------------------------------

function currencies($val='') {

  $timezone = (array)json_decode(file_get_contents('data/currencies.json'));

  foreach($timezone as $k => $v) {
    $data[$k] = $v->name;
  }

  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function currency_symbol( $val = '' ) {

  $timezone = (array)json_decode(file_get_contents('data/currencies.json'));

  foreach($timezone as $k => $v) {
    $data[$k] = $v->symbol_native;
  }

  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function amount_formatted($amount = '') {
  if( is_numeric($amount) ) {
    $currency = App\Setting::get_setting('currency');     
    return currency_symbol($currency).' '.number_format($amount, 2);
  }
}

//----------------------------------------------------------------

function sort_array_multidim(array $array, $order_by) {
    //TODO -c flexibility -o tufanbarisyildirim : this error can be deleted if you want to sort as sql like "NULL LAST/FIRST" behavior.
    if(!is_array($array[0]))
        throw new Exception('$array must be a multidimensional array!',E_USER_ERROR);
    $columns = explode(',',$order_by);
    foreach ($columns as $col_dir)
    {
        if(preg_match('/(.*)([\s]+)(ASC|DESC)/is',$col_dir,$matches))
        {
            if(!array_key_exists(trim($matches[1]),$array[0]))
                trigger_error('Unknown Column <b>' . trim($matches[1]) . '</b>',E_USER_NOTICE);
            else
            {
                if(isset($sorts[trim($matches[1])]))
                    trigger_error('Redundand specified column name : <b>' . trim($matches[1] . '</b>'));
                $sorts[trim($matches[1])] = 'SORT_'.strtoupper(trim($matches[3]));
            }
        }
        else
        {
            throw new Exception("Incorrect syntax near : '{$col_dir}'",E_USER_ERROR);
        }
    }
    //TODO -c optimization -o tufanbarisyildirim : use array_* functions.
    $colarr = array();
    foreach ($sorts as $col => $order)
    {
        $colarr[$col] = array();
        foreach ($array as $k => $row)
        {
            $colarr[$col]['_'.$k] = strtolower($row[$col]);
        }
    }
   
    $multi_params = array();
    foreach ($sorts as $col => $order)
    {
        $multi_params[] = '$colarr[\'' . $col .'\']';
        $multi_params[] = $order;
    }
    $rum_params = implode(',',$multi_params);
    eval("array_multisort({$rum_params});");
    $sorted_array = array();
    foreach ($colarr as $col => $arr)
    {
        foreach ($arr as $k => $v)
        {
            $k = substr($k,1);
            if (!isset($sorted_array[$k]))
                $sorted_array[$k] = $array[$k];
            $sorted_array[$k][$col] = $array[$k][$col];
        }
    }
    return array_values($sorted_array);
}

//----------------------------------------------------------------

function has_photo($path ='') {
  if($path) {

    if( file_exists($path) ) {
      return asset($path);    
    }
  }
  // Default
  return asset('assets/uploads/avatar.png');
}

//----------------------------------------------------------------

function has_image($path ='') {
  if($path) {
    if( file_exists($path) ) {
      return asset($path);    
    }
  }
  // Default
  return asset('assets/uploads/no-image-found.jpg');
}

//----------------------------------------------------------------

function time_formatted($date ='') {  
  if( $date == '' || $date == '0000-00-00' || $date == '1970-01-01') return '';
  return date('H:i', strtotime($date));
}
//----------------------------------------------------------------

function date_formatted($date ='') {  
  if( $date == '' || $date == '0000-00-00' || $date == '1970-01-01') return '';
  if (preg_match("/\d{4}\-\d{2}-\d{2}/", $date)) {
      return date('d-M-Y', strtotime($date));
  } else {
      return date('Y-m-d', strtotime($date));
  }
}

//----------------------------------------------------------------

function date_formatted_b($date ='') {  
  if( $date == '' || $date == '0000-00-00' || $date == '1970-01-01') return '';

  if (preg_match("/\d{4}\-\d{2}-\d{2}/", $date)) {
      return date('m-d-Y', strtotime($date));
  } else {
      list($m, $d, $y) = explode('-', $date);
      return $y.'-'.$m.'-'.$d;
  }
}

//----------------------------------------------------------------

function name_formatted($user_id, $format = 'l, f') { 
  $d = App\User::find($user_id);

  $split_format = str_split($format);
  $name ='';
  foreach ($split_format as $char) {
    
    if (preg_match('/[a-zA-Z]/', $char)) {
      $n = ($char == 'l') ? 'lastname' : 'firstname';
      $name .= @$d->$n;
    } else {
      $name .= $char;
    }
  }
  
  return ucwords($name);
}

//----------------------------------------------------------------

function time_ago($time_ago) {

    $time_ago     = strtotime($time_ago);
    $cur_time     = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds      = $time_elapsed ;
    $minutes      = round($time_elapsed / 60 );
    $hours        = round($time_elapsed / 3600);
    $days         = round($time_elapsed / 86400 );
    $weeks        = round($time_elapsed / 604800);
    $months       = round($time_elapsed / 2600640 );
    $years        = round($time_elapsed / 31207680 );

    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "1 minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}

//----------------------------------------------------------------

function array_to_json($val='') {

  if( is_array($val) ) {
    $val = json_encode($val);
  }

  return $val;
}

//----------------------------------------------------------------

function text_to_slug($val='') {
  return str_replace([' ', '(',')', "'"], ['-','',''], strtolower($val));
}

//----------------------------------------------------------------

function active_form($form, $segment) {

  if($segment == $form) {
    return 'active';
  } elseif($segment > $form) {
    return 'done';
  }
}

//----------------------------------------------------------------

function query_vars($query ='') {

  $qs = $_SERVER['QUERY_STRING'];
  $vars = array();

  if($query == '') return $qs;

    parse_str($_SERVER['QUERY_STRING'], $qs);
    
    foreach ($qs as $key => $value) {     
      $vars[$key] = $value;

      if($value == '0') {
        unset($vars[$key]);   
      }
    }
 
    parse_str($query, $queries);
    
    foreach ($queries as $key => $value) {      
      $vars[$key] = $value;

      if($value == '0') {
        unset($vars[$key]);   
      }
    }

    return $vars;
}

//----------------------------------------------------------------

function get_meta($rows = array()) {
  $data = array();
  foreach($rows as $row) { 
    $data[$row->meta_key] = $row->meta_value;
  }

  return (object)$data;
}

//----------------------------------------------------------------

function user_status($val ='') {

  $data = array(
    'actived'   => 'Actived',
    "inactived" => "Inactived",
  );
  
  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function per_page($val ='') {

  $data = array(
    15 => 15,
    25 => 25,
    50 => 50,
    100 => 100,
  );
  
  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function array_to_text($rows=array(), $type='') {
  $data = array();

  foreach($rows as $row) {

    if($type == 'practice_types') {
      $data[] = practice_types($row);
    }

  }


  return implode(', ', $data);
}

//----------------------------------------------------------------

function str_mask( $str, $start = 0, $length = null ) {
    $mask = preg_replace ( "/\S/", "*", $str );
    if( is_null ( $length )) {
        $mask = substr ( $mask, $start );
        $str = substr_replace ( $str, $mask, $start );
    }else{
        $mask = substr ( $mask, $start, $length );
        $str = substr_replace ( $str, $mask, $start, $length );
    }
    return $str;
}

//----------------------------------------------------------------

function compress($source, $destination, $quality) {

  $info = getimagesize($source);

  if ($info['mime'] == 'image/jpeg') 
    $image = imagecreatefromjpeg($source);

  elseif ($info['mime'] == 'image/gif') 
    $image = imagecreatefromgif($source);

  elseif ($info['mime'] == 'image/png') 
    $image = imagecreatefrompng($source);

  imagejpeg($image, $destination, $quality);

  return $destination;
}

//----------------------------------------------------------------

function cURL($url, $header=NULL, $cookie=NULL, $p=NULL) {
    $ch = curl_init();

  
  $ipku = $_SERVER['REMOTE_ADDR'];
  $ip = array("REMOTE_ADDR: $ipku", "HTTP_X_FORWARDED_FOR: $ipku");


    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_NOBODY, $header);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($ch, CURLOPT_COOKIE, $cookie);

    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

  curl_setopt($ch,CURLOPT_HTTPHEADER,$ip);

  /*
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_COOKIEJAR,$cookie);
  curl_setopt($ch, CURLOPT_COOKIEFILE,$cookie);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT,'Opera/9.80 (Series 60; Opera Mini/6.5.27309/34.1445; U; en) Presto/2.8.119 Version/11.10');
  */

    if ($p) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
    }
    $result = curl_exec($ch);

    curl_close($ch);

    return $result;

}

//----------------------------------------------------------------

function upload_image($file, $path, $old_file ='', $quality = 'compress', $set_width = '', $height ='') {

    $compress      = false;

    $filename   = str_random(16);
    $imageFile  = $file->getRealPath();
    $ext        = $file->getClientOriginalExtension();
    $path       = 'assets/uploads/'.$path;

    if( ! $set_width ) {
      $width = App\Setting::get_setting('img_width');
      if( !$width ) $width = 230;
    } 
    
    if( $quality == 'compress' ) {
      $compress      =  App\Setting::get_setting('img_compress');
      $compress_rate = App\Setting::get_setting('img_compress_rate');
      $compress_rate = $compress_rate ? $compress_rate : 100;
    }

    if( ! file_exists($path) ) mkdir($path, 0755,true);

    $profile_pic = $path.'/'.$filename.'.png';  
    if( file_exists($old_file) ) unlink($old_file);

    $img = \Image::make($imageFile)->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    })->save($profile_pic);
    if($compress) {
          compress($profile_pic, $profile_pic, $compress_rate);
    }

    return $profile_pic;
}



//----------------------------------------------------------------

function tax_exemption($status, $dependent = 0, $code = false) {    

    $data = civil_status($status).' w/ '.$dependent.' dependent'.is_plural($dependent);

    if( $code ) {
        $data = ($status=='single' ? 'S' : 'ME') . ($dependent ? $dependent : '');
    }

    return $data;
}

function get_days_diff($date_start ='', $date_end ='') {
  $date_start = date_formatted_b($date_start);
  $date_end = date_formatted_b($date_end);
  $days = (strtotime($date_end) - strtotime($date_start)) / (60 * 60 * 24);

  return $days == 0 ? 1 : $days;
}

function stock_qty($qty = 0) {

  $level = App\Setting::get_setting('stock_level');

  if($qty == 0) {
    return '<span class="text-danger sbold small">NONE</span>';    
  }

  if($qty <= $level) {
    return '<span class="text-danger">'.number_format($qty).'</span>';
  }

  return number_format($qty);
}

function str_metric($val ='', $qty = 0) {
  return ($qty > 1) ? str_plural($val) : $val;
}


function get_past_months($month) {

  foreach (range(1, $month) as $m) {
    $month = date('M', strtotime(date('Y-'.$m.'-d')));

      $data[$m] = $month;
  }

  return $data;
}

function link_ordered_menu($array, $parent_id = 0, $actived='') {
    $menu_html = '<ul class="ordered-menu">';
    foreach($array as $element) {
        if($element['parent_id']==$parent_id) {
            $checked = @in_array($element['id'], $data) ? 'checked' : '';
            $menu_html .= '<li><a href="'.$element['href'].'"  class="'. actived($actived, $element['slug']) .'">'.$element['name'];
            $menu_html .= link_ordered_menu($array, $element['id'], $actived);
            $menu_html .= '</a></li>';
        }
    }
    $menu_html .= '</ul>';
    return $menu_html;
}

function checkbox_ordered_menu($array, $parent_id = 0, $data) {
    $menu_html = '<ul class="ordered-menu">';
    foreach($array as $element) {
        if($element['parent_id']==$parent_id) {
            $checked = @in_array($element['id'], $data) ? 'checked' : '';
            $menu_html .= '<li><label class="mt-checkbox mt-checkbox-outline">';
            $menu_html .= '<input type="checkbox" value="'.$element['id'].'" name="category[]" '.$checked.'>';
            $menu_html .= '<span></span> '.$element['name'];
            $menu_html .= '</label>';
            $menu_html .= checkbox_ordered_menu($array,$element['id'], $data);
            $menu_html .= '</li>';
        }
    }
    $menu_html .= '</ul>';
    return $menu_html;
}

function radio_ordered_menu($array, $parent_id = 0, $id) {
    $menu_html = '<ul class="ordered-menu">';
    foreach($array as $element) {
        if($element['parent_id']==$parent_id) {
            $checked = $element['id'] == $id ? 'checked' : '';
            $menu_html .= '<li><label class="mt-radio mt-radio-outline">';
            $menu_html .= '<input type="radio" value="'.$element['id'].'" name="category" '.$checked.'>';
            $menu_html .= '<span></span> '.$element['name'];
            $menu_html .= '</label>';
            $menu_html .= radio_ordered_menu($array,$element['id'], $id);
            $menu_html .= '</li>';
        }
    }
    $menu_html .= '</ul>';
    return $menu_html;
}

function dropdown_ordered_menu($array, $parent_id = 0, $depth=0) {
    $menu_html = '';
    $nbsp = str_repeat('&nbsp;', $depth * 1);
    foreach($array as $element) {
        if($element['parent_id']==$parent_id) {
            $menu_html .= '<option value="'.$element['id'].'">'.$nbsp.$element['name'].'</option>';
            $menu_html .= dropdown_ordered_menu($array,$element['id'], $depth);
        } else {
          $depth++;

        }
    }

    return $menu_html;
}


function _cleanup_header_comment( $str ) {
  return trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $str));
}

function theme_templates() {

  $dir = str_replace([$_SERVER['HTTP_HOST'], '/public', '/index.php'], ['designer_app', ''], $_SERVER['SCRIPT_FILENAME']);

  $files =  glob($dir.'/resources/views/templates/*');

  foreach ( $files as $file => $full_path ) {
    $path = explode('/', $full_path);
    $temp = str_replace(['.blade', '.php'], '', end($path) );
    if ( ! preg_match( '|Template Name: (.*)$|mi', file_get_contents( $full_path ), $header ) ) {
      continue;
    }
    $templates[$temp] = $header[1];
  }
  return $templates;
} 

function media_breadcrumbs() {
  $folder = Input::get('folder');
  
  $data = '<i class="fa fa-folder-o"></i> ';
  if( $folder ) {
    $data .= '<a href="'.URL::route('admin.media.index', query_vars('folder=0')).'">Assets</a>';
  } else {
    $data .= '<span class="text-muted">Assets</span>';
  }

  $am = Input::get('access_method') ? 'access_method='.Input::get('access_method').'&' : '';

  $link ='';
  $folders = array_filter( explode('/', $folder)  );
  $i=0;
  $len = count( $folders );
  foreach ($folders as $f) {
    if( $i == $len-1) {
      $data .= ' / <span class="text-muted">'.$f.'</span>';
    } else {
    $link = $link . $f .'/'; 
    $data .= ' / <a href="?'.$am.'folder='.$link.'">'.$f.'</a>';
    }
    $i++;
  }
  return $data;
}

function media_library() {
    $contents = array();

    $folder = Input::get('folder');

    $dir = 'uploads/'.$folder;

    $data['files'] = $files = array_merge(glob($dir."*-thumb*"));
    array_multisort(array_map('filemtime', $files), SORT_NUMERIC, SORT_DESC, $files);
    foreach ($files as $file) {
        $f = explode("/", $file);
        $name = end($f);

        $medium = str_replace('thumb', 'large', $file);
        if( file_exists(str_replace('thumb', 'medium', $file)) ) {
          $medium = str_replace('thumb', 'medium', $file);
        }

        $contents[] = array('type' => 'file', 
            'thumb'  => has_image($file), 
            'medium' => has_image($medium), 
            'large'  => $dir.str_replace('thumb', 'large', $name), 
            'full'   => has_image($dir.str_replace('thumb', 'large', $name)), 
            'name'   => str_replace('_',' ', substr($name, 0, strpos($name, "-"))), // strstr($name,'.',true) 
            'id'     => explode("-", $name)[1]
          );
    }

    $directories = glob($dir . '*' , GLOB_ONLYDIR);
    foreach ($directories as $directory) {
        $d = explode("/", $directory);
        $name = end($d);
        $contents[] = array(
          'type' => 'folder', 
          'name' => str_replace('_', ' ', $name), 
          'url' => $folder.$name.'/', 
          'count' => count(glob("$dir/".$name."/*")) 
        );
    }

    $data['contents'] = $contents;

    $am = Input::get('access_method') ? 'access_method='.Input::get('access_method').'&' : '';

    $filter = array_filter(explode('/', $folder));
    $end = end($filter);
    if( count(explode('/', $folder)) > 2 ) {
        $segment = $am.'folder='.str_replace('/'.$end.'/', '/', $folder);
        $data['back'] =   URL::route('admin.media.index', $segment);
    } else {
        $data['back'] =  URL::route('admin.media.index', query_vars('folder=0'));
    }

    return $data;
}

function shirt_colors() {
  
  $data[''] = 'Select number of colors';

  foreach (range(1, 6) as $c) {
    $data[$c] = $c.' Color'.is_plural($c);
  }

  return $data;
}

function get_cc_type($str, $format = 'string') {
    if (empty($str)) {
        return false;
    }

    $matchingPatterns = [
        'visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
        'mastercard' => '/^5[1-5][0-9]{14}$/',
        'amex' => '/^3[47][0-9]{13}$/',
        'diners' => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
        'discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
        'jcb' => '/^(?:2131|1800|35\d{3})\d{11}$/',
        'any' => '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/'
    ];

    $ctr = 1;
    foreach ($matchingPatterns as $key=>$pattern) {
        if (preg_match($pattern, $str)) {
            return $format == 'string' ? $key : $ctr;
        }
        $ctr++;
    }
}