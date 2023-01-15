<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Cachemange_librares {
var $cache_data = array();
function __construct(){
      $this->_ci=& get_instance();
      $this->CI=& get_instance(); 
      $CI=& get_instance();
      $CI->load->database();
      $this->CI->load->library('session');
      $this->CI->load->helper('cookie','session');
      //$this->_ci->load->model('Admin_model');
}
// Function to get the client ip address
public function get_client_ip_env() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}
// Function to get the client ip address
public function get_client_ip_server() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}
//Get User IP
public function get_user_ip(){
        $ip='';
        if (function_exists('file_get_contents')) {
            $ipify   = @file_get_contents('https://api.ipify.org');
            if (filter_var($ipify, FILTER_VALIDATE_IP)) {
                $ip = $ipify;
            }
        } else {
            $client  = @$_SERVER['HTTP_CLIENT_IP'];
            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $remote  = $_SERVER['REMOTE_ADDR'];
            if (filter_var($client, FILTER_VALIDATE_IP)) {
                $ip = $client;
            } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
                $ip = $forward;
            } else {
                $ip = $remote;
            }
        }
        return $ip;
    }
public function cachedb($sql,$query_result=array(),$cachekey,$cachetime,$cachetype,$deletekey){
if($query_result!=null){
     $queryresult=$query_result;
     /*
     echo '<hr> cachedb 1 sql=> '.$sql; 
     echo '<br>cachekey=>'.$cachekey; 
     echo '<br>cachetime=>'.$cachetime; 
     echo '<br>cachetype=>'.$cachetype; 
     echo '<br>deletekey=>'.$deletekey; 
     echo'<pre> 0->query_result=>';print_r($query_result);echo'<pre>'; 
     */
}
else{
     $query_result='0';
/*
     echo '<hr> cachedb 2 sql=> '.$sql; 
     echo '<br>cachekey=>'.$cachekey; 
     echo '<br>cachetime=>'.$cachetime; 
     echo '<br>cachetype=>'.$cachetype; 
     echo '<br>deletekey=>'.$deletekey; 
     echo'<pre> 0->query_result=>';print_r($query_result);echo'<pre>'; 
*/
}
if($cachetype==null){$cachetype=5;}
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->CI->db->cache_delete($cachekey);
 $this->CI->db->cache_delete_all();
}  
###########DB SQL query Start##########
if($sql!==null){
     // Create Turn caching on
     $this->CI->db->cache_on(); 
     // Turn caching off for this one query
     // $this->CI->db->cache_off();
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
}else{
     $this->CI->db->cache_on(); 
     $dataresult=$query_result;  
}
###########DB SQL query End ###########
$dataall=array('message'=>'Data form database query save to dbcache',
				'status'=>FALSE, 
				'list'=>$dataresult,
                        'count'=>(int)count($dataresult),
                        'cachetime'=>(int)$cachetime,
                        'cachekey'=>$cachekey,
                        'cacheinfo'=>null);                  
/* ############# 1-->cachedb  End##############  */  
return $dataall;
}
elseif($cachetype==2){
/* ############# 2-->cachefile  Start##############  */ 
$this->CI->load->driver('cache');
$cache_is_supported=$this->cache->file->is_supported();
if($deletekey==1){
 $deletekeysdata=$this->cache->file->delete($cachekey);
}else{$deletekeysdata='null';}
$path=$this->config->item('cache_path');
$get_metadata=$this->cache->file->get_metadata($cachekey);
$dataresult=$this->cache->file->get($cachekey);
if($dataresult){
             $status_msg='form cache file';
             $cache_info=$this->cache->file->cache_info($cachekey);
             $dataall=array('message'=>'Data form filecache',
					'status'=>TRUE, 
					'list'=>$dataresult,
                              'count'=>(int)count($dataresult),
                              'cachetime'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>$cache_info);       
}elseif(!$dataresult){    
###########DB SQL query Start##########
if($sql!==null){
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();  
     // echo '<pre>  dataresult1-> '; print_r($dataresult); echo '</pre>'; //die();
}else{   
 $get_metadata=$this->cache->file->get_metadata($cachekey);
 $dataresult=$this->cache->file->get($cachekey);
 // echo '<pre>get_key->'; print_r($dataresult); echo '</pre>';// die();
 $cache_is_supported=$this->cache->file->is_supported();
if(!$dataresult){ 
     $this->cache->file->save($cachekey,$dataresult,$cachetime);
/* 
      echo '<pre>A->cache_is_supported->'; print_r($cache_is_supported); echo '</pre>';
      echo '<pre>cachekey->'; print_r($cachekey); echo '</pre>';
      echo '<pre>get_metadata->'; print_r($get_metadata); echo '</pre>';
      echo '<pre>rs dataresult2->'; print_r($dataresult); echo '</pre>'; 
      //die();
*/
}else{
/*
      echo '<pre>B->cache_is_supported->'; print_r($cache_is_supported); echo '</pre>';
      echo '<pre>cachekey->'; print_r($cachekey); echo '</pre>';
      echo '<pre>get_metadata->'; print_r($get_metadata); echo '</pre>';
      echo '<pre>rs dataresult2->'; print_r($dataresult); echo '</pre>'; 
*/
      //die();
}
 

}
###########DB SQL query End ###########


if(!$dataresult){$this->cache->file->save($cachekey,$dataresult,$cachetime);}
$cache_info=$this->cache->file->cache_info($cachekey);
          $dataall=array('message'=>'Data form database query save to filecache',
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'cachetime'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>$cache_info);                  
}
#############################################

#echo '<pre>  dataall-> '; print_r($dataall); echo '</pre>'; die();
/* ############# 1-->cachedb  End##############  */  
return $dataall;
}
elseif($cachetype==3){
$key=$cachekey;
/* ############# 3-->Redis   Start##############  */  
#################################################################
$this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
$connection_redis=$this->cache->redis->connection();
$connection=$connection_redis['status'];
$key=$cachekey;
$getdata=$this->cache->redis->getkeysdata($key);

$getkeysdata=$getdata['getdata'];
if($deletekey==1){
$deletekeysdata=$this->cache->redis->deletekeysdata($cachekey);
}else{$deletekeysdata='null';}
#############################################
$redis_is_supported=$this->cache->redis->is_supported();
$file_is_supported=$this->cache->file->is_supported();
if($redis_is_supported==1 || $file_is_supported==1){
  #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
  #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
}
#############################################
if(!$getkeysdata){ 
###########DB SQL query Start##########
if($sql!==null){
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
     #echo '<pre> $dataresult-> '; print_r($dataresult); echo '</pre>'; die();
     #$jsondata=json_encode($dataresult, JSON_NUMERIC_CHECK);
}else{
     $dataresult=$query_result;  
}
###########DB SQL query End ###########


$results_data=$this->cache->redis->setkeysdata($cachekey,$dataresult);
//$redis_data=$results_data;
$redis_data=$results_data['getdata'];
$message='form db query data ';
$status=0;
###########DB SQL query End ###########
}elseif($getkeysdata){ 
###########form Redis Start ###########
$redis_data=$getkeysdata;
$status=1;
$message='form Redis cache data';
###########form Redis End ###########
}
 
$dataall=array('message'=>$message,
               'Connection'=>$connection,
			'status'=>$status, 
			'list'=>$redis_data,
               'redis_is_supported'=>$redis_is_supported,
               'file_is_supported'=>$file_is_supported,
               'list_count'=>count($redis_data),
               'cachekey'=>$cachekey,
               );
                              
                              
                              
#############################################
/* ############# 3-->Redis  End##############  */    
return $dataall;
}
elseif($cachetype==4){
$this->CI->load->library('Memcached_library');
/* ############# 4-->Memory  memcached  Start##############  */  
##########*******memcache*******############
//Load library
$this->CI->load->library('Memcached_library');     
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     
###########DB SQL query Start##########
if($sql!==null){
     $query=$this->CI->db->query($sql);
     $resultsdata=$query->result();
}else{
     $resultsdata=$query_result;  
}
###########DB SQL query End ###########

     // Lets store the results
     //$this->memcached_library->add($cachekey,$resultsdata);
     $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         #'info'=>$cacheinfo
                         );
}
# echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
#return $resultsdata;
##################memcached_result###########################
/* ############# 4-->Memory  memcached  End##############  */   
}
elseif($cachetype==5){
/* ############# 4-->DB SQL Start##############  */    
###########DB SQL query Start##########
if($sql!==null){
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
}else{
     $dataresult=$query_result;  
}
###########DB SQL query End ###########

$dataall=array('message'=>'Data form database ',
			'status'=>FALSE, 
			'list'=>$dataresult,
               'count'=>(int)count($dataresult),
               );
/* ############# 4-->DB SQL End##############  */  
return $dataall;  
}
############cache##################
}
public function clear_all_cache_file(){
$CI =& get_instance();
$path = $CI->config->item('cache_path');
//echo 'path=>'.$path; die();
//$cache_path=($path == '') ? APPPATH.'cache/' : $path;
$cache_path=null;
if($cache_path==null){
     $cache_path=$path;
}
echo $cache_path;
$handle = opendir($cache_path);
while (($file = readdir($handle))!== FALSE) {
        //Leave the directory protection alone
if ($file != '.htaccess' && $file != 'index.html'){
           @unlink($cache_path.'/'.$file);
        }
    }
closedir($handle);
}
public function cachedbsetkey($sql,$queryrs=array(),$cachekey,$cachetime,$cachetype,$deletekey){
$query_result=$queryrs;
############cache##################
/*
echo '<hr>';
echo '<br>sql=>'.$sql; 
echo '<br>cachekey=>'.$cachekey; 
echo'<hr><pre> query_result=>';print_r($query_result);echo'<pre> <hr>';die();
echo '<br>cachetime=>'.$cachetime; 
echo '<br>cachetype=>'.$cachetype; 
echo '<br>deletekey=>'.$deletekey; Die();
*/
############cache##################
if($cachetype!=1 || $cachetype!=5){$sql=null;}
if($cachetype==null){$cachetype=4;}
/*
echo '<br> cachetype=>'.$cachetype; 
echo '<br> sql=>'.$sql; 
Die();
*/
/* ############# cache DB Start##############  */  
if($cachetype==1 && $sql!=null){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){$this->CI->db->cache_delete_all();}  
###########DB SQL query Start###########
// Create Turn caching on
$this->CI->db->cache_on(); 
// Turn caching off for this one query
// $this->CI->db->cache_off();
$query=$this->CI->db->query($sql);
$dataresult=$query->result();
###########DB SQL query End ###########
          $dataall=array('message'=>'Data form database query save to dbcache',
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'cachetime'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>null);                  



/* ############# 1-->cachedb  End##############  */  
return $dataall;
}
/* ############# cache DB End##############  */  

/* ############# cachefile Start##############  */  
if($cachetype==2){
/* ############# 2-->cachefile  Start##############  */ 
$this->CI->load->driver('cache');
$path=$this->config->item('cache_path');
$cache_is_supported=$this->cache->file->is_supported();
$this->cache->file->save($cachekey,$query_result,$cachetime);
$cache_info=$this->cache->file->cache_info($cachekey);
$dataall=array('message'=>'Data form database sql query save to filecache',
			'status'=>FALSE, 
			'list'=>$query_result,
               'count'=>(int)count($query_result),
               'cachetime'=>(int)$cachetime,
               'cachekey'=>$cachekey,
               'cacheinfo'=>$cache_info,
               );            
return $dataall;
}
/* ############# cachefile End##############  */  

/* ############# cache Redis Start##############  */  
if($cachetype==3){
$key=$cachekey;
/* ############# 3-->Redis   Start##############  */  
#################################################################
$this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
$connection_redis=$this->cache->redis->connection();
$connection=$connection_redis['status'];
$key=$cachekey;
$getdata=$this->cache->redis->getkeysdata($key);
$getkeysdata=$getdata['getdata'];
if($deletekey==1){
     $deletekeysdata=$this->cache->redis->deletekeysdata($cachekey);
}else{
     $deletekeysdata=null;
}
#############################################
$redis_is_supported=$this->cache->redis->is_supported();
$file_is_supported=$this->cache->file->is_supported();
if($redis_is_supported==1 || $file_is_supported==1){
  #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
  #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
}
#############################################
if(!$getkeysdata){ 
     $message='Redis cache data is null ';
     $status=0;
     $dataall=array('message'=>$message,
				'status'=>$status, 
				'list'=>null,
                    'count'=>0,
                    'cachetime'=>(int)$cachetime,
                    'cachekey'=>$cachekey,
                    'cacheinfo'=>null);
}elseif($getkeysdata){ 
     $redis_data=$getkeysdata;
     $status=1;
     $message='form Redis cache data';
     $dataall=array('message'=>$message,
				'status'=>$status, 
				'list'=>$redis_data,
                    'count'=>count($redis_data),
                    'cachetime'=>(int)$cachetime,
                    'cachekey'=>$cachekey,
                    'cacheinfo'=>null);
} 
return $dataall;
}
/* ############# cache Redis End##############  */  

/* ############# cache Memcached Start##############  */  
if($cachetype==4){
$this->CI->load->library('Memcached_library');
/* ############# 4-->Memory  memcached  Start##############  */  
##########*******memcache*******############
//Load library
$this->CI->load->library('Memcached_library');     
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
          $message='memcached is null';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>null,
                         'count'=>0,
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>null,
                         'info'=>null,
                         );
}
else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         'info'=>$cacheinfo,
                         );
}
# echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
#return $resultsdata;
##################memcached_result###########################
/* ############# 4-->Memory  memcached  End##############  */   
}
/* ############# cache Memcached End##############  */  

if($cachetype==5 && $sql!=null){
/* ############# 4-->DB SQL Start##############  */    
########################################################
###########DB SQL query Start###########
$query=$this->CI->db->query($sql);
$dataresult=$query->result();
###########DB SQL query End ###########
$dataall=array('message'=>'Data form database ',
			'status'=>FALSE, 
			'list'=>$dataresult,
               'count'=>(int)count($dataresult),
               );
/* ############# 4-->DB SQL End##############  */  
return $dataall;  
}
}
public function session_cookie_get(){
      $this->CI->load->library('session');
      $this->CI->load->helper('cookie','session');
      $data=array('COOKIE'=>$_COOKIE,
                  'SESSION'=>$_SESSION,
                  );
return $data; 
}
public function session_set($sesdata){
      $this->CI->load->library('session');
      $this->CI->session->set_userdata($sesdata);
} 
public function setDataCookie($cookie_name,$cookie_value,$time){
$this->CI->load->helper('cookie');
if($time==null){$time=3600;}
$cookie=array(
            'name' => $cookie_name,
            'value' => $cookie_value,                            
            'expire' => (time()+$time),      
            'path' => "/",                                                                          
            'secure' =>false
        );
$cookiert=$this->CI->input->set_cookie($cookie);
}
public function deleteCookie($cookie_name,$cookie_value){
$this->CI->load->helper('cookie');  	
$cookie=array(
            'name' => $cookie_name,
            'value' => $cookie_value,        
            'path' => "/",                                                                           
            'secure' => false
        );
delete_cookie($cookie);
return $cookie;
} 
public function sessDestroy(){
//session_destroy();
$this->CI->session->sess_destroy();      
}
public function fooredirect(){
                $this->CI->load->helper('url');
                redirect();
}
public function barbase_url(){
                echo $this->CI->config->item('base_url');
}
}
