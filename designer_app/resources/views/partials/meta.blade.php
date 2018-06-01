
<?php 
	$logo             = has_image(App\Setting::get_setting('logo'));
	$site_title       = App\Setting::get_setting('site_title');
	$meta_keyword     = App\Setting::get_setting('meta_keyword');
	$meta_description = App\Setting::get_setting('meta_description');
?>
	<title>{{ @$info->post_title ? $info->post_title : $site_title }}</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<meta name="description" content="{{ @$info->meta_description ? $info->meta_description : $meta_description }}"/>
	<meta name="keywords" content="{{ @$info->meta_keyword ? $info->meta_keyword : $meta_keyword }}"/>
	<meta name="robots" content="noodp"/>
	<meta charset="utf-8">

	<meta property="og:locale" content="en_PH">
	<meta property="og:type" content="website">
	<meta property="og:title" content="{{ @$info->meta_title ? $info->meta_title : $site_title }}">
	<meta property="og:description" content="{{ @$info->meta_description ? $meta_description : $meta_description }}">
	<meta property="og:url" content="{{ Request::url() }}">
	<meta property="og:site_name" content="{{ $site_title }}">    
	<meta property="og:image" content="{{ @$info->image ? $info->image : $logo }}">
	<meta property="og:image:width" content="450"/>
	<meta property="og:image:height" content="298"/>

	<meta name="twitter:card" content="summary">
	<meta name="twitter:description" content="{{ @$info->meta_description ? $info->meta_description : $meta_description }}">
	<meta name="twitter:title" content="{{ @$info->meta_title ? $info->meta_title : $site_title }}">
	<meta name="twitter:image" content="{{ @$info->image ? $info->image : $logo }}">

	<link rel="canonical" href="{{ Request::url() }}">
