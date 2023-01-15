<?php
class Cachetoolv1_model extends CI_Model{
   public function __construct(){
		parent::__construct();
	}
   public function delete_cache_filename($uri_string,$filecachename){
       $CI =& get_instance();
       $path = $CI->config->item('cache_path');
       $path = rtrim($path, DIRECTORY_SEPARATOR);
       $cache_path = ($path == '') ? APPPATH.'' : $path;
       $uri= $CI->config->item('base_url').$uri_string;
       $cache_path=$cache_path.$filecachename;
       if(file_exists($cache_path)) { 
          $masangefile='Have file '.$cache_path;
      }else{
          $masangefile='Not Have file '.$cache_path;
      }
       $unlinkstatus=@unlink($cache_path);
       if($unlinkstatus==1){
         $masange='Delete Cache key '.$cache_path.' Complate';
         $status='1';
       }else{
           $masange='Not have Cache key '.$cache_path;
           $status='0';
       }
         $cache_path_delete=array("uri" => $uri,
                                  "masangefile" => $masangefile,
                                   "path"=> $path,
                                   "cache_path"=> $cache_path,
                                   "unlink_status" => $unlinkstatus,
                                   "masange" => $masange,
                                   "status" => $status,
                      			         ); 
       #echo '<pre>cache_path_delete=>'; print_r($cache_path_delete); echo '</pre>'; Die();
       return $cache_path_delete;
       
  } 
   public function delete_cache_filesession($uri_string,$filecachename){
       $CI =& get_instance();
       $path = $CI->config->item('sess_save_path');
       $path = rtrim($path, DIRECTORY_SEPARATOR);
       $cache_path = ($path == '') ? APPPATH.'' : $path;
       $uri= $CI->config->item('base_url').$uri_string;
       $cache_path=$cache_path.$filecachename;
       if(file_exists($cache_path)) { 
          $masangefile='Have file '.$cache_path;
      }else{
          $masangefile='Not Have file '.$cache_path;
      }
       $unlinkstatus=@unlink($cache_path);
       if($unlinkstatus==1){
         $masange='Delete Cache key '.$cache_path.' Complate';
         $status='1';
       }else{
           $masange='Not have Cache key '.$cache_path;
           $status='0';
       }
         $cache_path_delete=array("uri" => $uri,
                                  "masangefile" => $masangefile,
                                   "path"=> $path,
                                   "cache_path"=> $cache_path,
                                   "unlink_status" => $unlinkstatus,
                                   "masange" => $masange,
                                   "status" => $status,
                      			         ); 
       #echo '<pre>cache_path_delete=>'; print_r($cache_path_delete); echo '</pre>'; Die();
       return $cache_path_delete;
       
  }   
   public function delete_cache_filename_db($uri_string,$dirfile,$filecachename){
       $CI =& get_instance();
       $path = $CI->config->item('cache_path');
       $path = rtrim($path, DIRECTORY_SEPARATOR);
       $cache_path = ($path == '') ? APPPATH.'' : $path;
       $uri= $CI->config->item('base_url').$uri_string;
       $cache_path=$cache_path.$dirfile.'/'.$filecachename;
       
      if(file_exists($cache_path)) { 
          $masangefile='Have file '.$cache_path;
      }else{
          $masangefile='Not Have file '.$cache_path;
      }
      //echo '<pre> masangefile=>'; print_r($masangefile); echo '</pre>';
      //echo '<pre> cache_path=>'; print_r($cache_path); echo '</pre>'; Die();
       $unlinkstatus=@unlink($cache_path);
       if($unlinkstatus==1){
         $masange='Delete Cache key '.$cache_path.' Complate';
         $status='1';
       }else{
           $masange='Not have Cache key '.$cache_path;
           $status='0';
       }
         $cache_path_delete=array("uri" => $uri,
                                  "masangefile" => $masangefile,
                                   "path"=> $path,
                                   "cache_path"=> $cache_path,
                                   "unlink_status" => $unlinkstatus,
                                   "masange" => $masange,
                                    "status" => $status,
                      			         ); 
        // echo '<pre>cache_path_delete=>'; print_r($cache_path_delete); echo '</pre>'; Die();
       return $cache_path_delete;
       
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
      #$this->db->cache_delete_all();
      $cachedelete=array("cache_path" => $cache_path,
                         "masange" => 'Clear All',
                      		); 
       return $cachedelete;
  }
   public function clear_all_cache_db(){
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
      $this->db->cache_delete_all();
      $cachedelete=array("cache_path" => $cache_path,
                         "masange" => 'Clear All',
                      		); 
       return $cachedelete;
  }
   public function is_cache_valid($cache_name,$lifespan){
        if (file_exists(CACHE_DIR.$cache_name)) {
            $last_date = file_get_contents(CACHE_DIR.$cache_name);
			//echo '<pre>last_date=>'; print_r($last_date); echo '</pre>'; Die();
            if (abs($last_date - time()) < $lifespan) {
                return true;
            }else{
                $file_put_contents=file_put_contents(CACHE_DIR.$cache_name,time());
				//echo '<pre>file_put_contents=>'; print_r($file_put_contents); echo '</pre>'; Die();
                return false;
            }
        }else{
            file_put_contents(CACHE_DIR.$cache_name,time());
            return true;
        }

    }
   public function is_cache_valid_db($cache_name,$lifespan){
        if (file_exists(CACHE_PATH_DB.$cache_name)) {
            $last_date = file_get_contents(CACHE_PATH_DB.$cache_name);
			//echo '<pre>last_date=>'; print_r($last_date); echo '</pre>'; Die();
            if (abs($last_date - time()) < $lifespan) {
                return true;
            }else{
                $file_put_contents=file_put_contents(CACHE_PATH_DB.$cache_name,time());
				//echo '<pre>file_put_contents=>'; print_r($file_put_contents); echo '</pre>'; Die();
                return false;
            }
        }else{
            file_put_contents(CACHE_PATH_DB.$cache_name,time());
            return true;
        }

    }
   public function dlete_cache_db_dir($dirfile){
	  $this->load->helper('path');
	  $non_existent_directory ='./file/dbcache/'.$dirfile.'/';
	  $dir=$non_existent_directory;
		if(!is_dir($dir)){ 
		  //echo '<pre>ไม่มี Directory=>'; print_r($dirfile); echo '</pre>';
		  $cache_path_delete=array("dir" => $dirfile,
											 "masange" =>'ไม่มี Directory'.$path,
											 "status" => 0,
                      			         ); 
		}else{
			//echo '<pre>มี Directory=>'; print_r($dirfile); echo '</pre>';  
			$path=$dir;
			$this->load->helper("file"); // load the helper
			delete_files($path, true); // delete all files/folders
			 if(rmdir($path)){
				 //echo '<pre> Deleted=>'; print_r($path); echo '</pre>'; 
					$cache_path_delete=array("dir" => $dirfile,
											 "masange" =>'Deleted'.$path,
											 "status" => 1,
                      			         ); 				 
			 }else{ 
				 //echo '<pre> Not Deleted=>'; print_r($path); echo '</pre>'; 
				    $cache_path_delete=array("dir" => $dirfile,
											 "masange" =>'Not Deleted'.$path,
											 "status" => 0,
                      			         ); 
			} 
		}
		return $cache_path_delete;
	}
}





/*

$this->cache->delete('cache_item_id') - for deleting individual cache thru ID
$this->cache->clean() - for deleting ALL cache

$wildcard = 'latest';
$all_cache = $this->cache->cache_info();
foreach ($all_cache as $cache_id => $cache) :
   if (strpos($cache_id, $wildcard) !== false) :
      $this->cache->delete($cache_id);
   endif;
endforeach;


 $path = $CI->config->item('cache_path');
  $cache_path = ($path == '') ? APPPATH.'cache/' : $path;
  .
  .
  .
  $uri =  $CI->config->item('base_url').
    $CI->config->item('index_page').
    $CI->uri->uri_string();
  $cache_path .= md5($uri);

/*****************


if ( ! function_exists('delete_cache')){
    function delete_cache($uri_string){
        $CI =& get_instance();
        $path = $CI->config->item('cache_path');
        $cache_path = ($path == '') ? APPPATH.'cache/' : $path;

        $uri =  $CI->config->item('base_url').
            $CI->config->item('index_page').
            $uri_string;

        $cache_path .= md5($uri);

        if (file_exists($cache_path))
        {
            return unlink($cache_path);
        }
        else
        {
            return TRUE;
        }
    }
}
# usage:
# delete_cache('/blog/comments/123');
*******************/
/*
How to “reset” CodeIgniter active record for consecutive queries?
Use

I'm using CodeIgniter and have a case where two tables 
(projects and tasks) need to be updated with a value right after one another 
(active column needs to be set to "n"). The code I am using is:

function update($url,$id){
    $this->db->where('url', $url);
    $this->db->update('projects', array('active' => 'n'));
    $this->db->where('eventid', $id);
    $this->db->update('tasks', array('active' => 'n'));
}

$this->db->start_cache();
Before starting query building and
$this->db->stop_cache();
After ending query building. Also, use
$this->db->flush_cache();
After stop cache.
function update($url, $id){
    $this->db->where('url', $url);
    $this->db->update('projects', array('active' => 'n'));
    $this->db->where('url', $url);
    $this->db->where('eventid', $id);
    $this->db->update('tasks', array('active' => 'n'));
}

// Alternatively
function update($url, $id){
    $where_bit = array(
        'url' => $url,
    );
    $this->db->update('projects', array('active' => 'n'), $where_bit);
    $where_bit['event_id'] = $id;
    $this->db->update('tasks', array('active' => 'n'), $where_bit);
}

*/