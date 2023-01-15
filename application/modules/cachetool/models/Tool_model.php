<?php
class Tool_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
   public function delete_cache_uri($uri){
      //http://localhost/cihmvcdev3/cachtool/delete_cache_uri?uri=cachtool/test
      $post=@$this->input->post();
      $get=@$this->input->get();
      if($uri==Null){
         $uri=@$post['uri'];
        $uri=@$get['uri'];
      }
      
      $uri_string=$uri;
      #Deletes cache for /foo/bar
       $CI =& get_instance();
       $path = $CI->config->item('cache_path');
       $path = rtrim($path, DIRECTORY_SEPARATOR);
       $cache_path = ($path == '') ? APPPATH.'' : $path;
       $uri= $CI->config->item('base_url').$uri_string;
       $cache_path.= md5($uri);
       $unlinkstatus=@unlink($cache_path);
       if($unlinkstatus==1){
         $masange='Delete Cache key '.$cache_path.' Complate';
       }else{
           $masange='Not have Cache key '.$cache_path;
       }
       $cache_path_delete=array("uri" => $uri,
                                   "path"=> $path,
                                   "cache_path"=> $cache_path,
                                   "unlink_status" => $unlinkstatus,
                                   "masange" => $masange,
                      			         ); 
       #echo '<pre>cache_path_delete=>'; print_r($cache_path_delete); echo '</pre>'; 
       return $cache_path_delete;
       
  }
   public function clear_all_cache(){
      $CI =& get_instance();
      $path = $CI->config->item('cache_path');
      $cache_path = ($path == '') ? APPPATH.'' : $path;
      $handle = opendir($cache_path);
      while (($file = readdir($handle))!== FALSE){
          //Leave the directory protection alone
          if ($file != '.htaccess' && $file != 'index.html'){
             @unlink($cache_path.'/'.$file);
          }
      }
      closedir($handle);
      $cachedelete=array("cache_path" => $cache_path,
                         "masange" => 'Clear All',
                      		); 
       return $cachedelete;
  }
	
}