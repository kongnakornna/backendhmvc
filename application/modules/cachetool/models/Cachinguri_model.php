<?php
class Cachinguri_model extends CI_Model{
public function __construct(){
		parent::__construct();
  $this->load->driver('cache');  
	}
public function delete_cache($uri_string=null){
    $CI =& get_instance();
    $path = $CI->config->item('cache_path');
    $path = rtrim($path, DIRECTORY_SEPARATOR);
	$cache_path_dir=FCPATH.'file/cache/';
    $cache_path = ($path == '') ? APPPATH.'cache/' : $path;
    $uri =  $CI->config->item('base_url').
            $CI->config->item('index_page').
            $uri_string;

    $cache_path .= md5($uri);
    return unlink($cache_path);
}
public function clear_path_cache($uri){
    $CI =& get_instance();
	$path = $CI->config->item('cache_path');
	$cache_path_dir=FCPATH.'file/cache/';
	$cache_path = ($path == '') ? APPPATH.'cache/' : $path;
    $uri =  $CI->config->item('base_url').
    $CI->config->item('index_page').
    $uri;
$cache_path .= md5($uri);

    return @unlink($cache_path);
}
/** Clears all cache from the cache directory*/
public function clear_all_cache(){
	$CI =& get_instance();
	$path = $CI->config->item('cache_path');
	$cache_path_dir=FCPATH.'file/cache/';
	$cache_path = ($path == '') ? APPPATH.'cache/' : $path;
    $handle = opendir($cache_path);
    while (($file = readdir($handle))!== FALSE) 
    {
        //Leave the directory protection alone
        if ($file != '.htaccess' && $file != 'index.html')
        {
           @unlink($cache_path.'/'.$file);
        }
    }
    closedir($handle);
}	
}