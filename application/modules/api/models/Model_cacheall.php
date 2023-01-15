<?php
class Model_cacheall extends CI_Model{
public function __construct(){
	parent::__construct();
}
public function get_cachedresult($cachetype,$deletekey,$order_by,$limit){ 
############################################  
/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
41-->Memory memcached
5-->Data base SQL
*/
if($cachetype==''){$cachetype=2;}
# sql command
if($order_by==''){$order_by='desc';}
if($limit==''){$limit='5';}
$sql="SELECT * FROM user_log order by id $order_by limit $limit";
############cachekey##################
$cachekey='mul-user-log-order-by-'.$order_by.'-limit-'.$limit;
############cachetime##################
$cachetime='3600';

############cache deletekey##################
if($deletekey==''){$deletekey='';}
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){ $this->db->cache_delete_all();}  
###########DB SQL query Start###########
// Create Turn caching on
$this->db->cache_on(); 
// Turn caching off for this one query
// $this->db->cache_off();
$query=$this->db->query($sql);
$dataresult=$query->result();
###########DB SQL query End ###########
          $dataall=array('message'=>'Data form database query save to dbcache',
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'time sec'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>null);                  



/* ############# 1-->cachedb  End##############  */  
return $dataall;
}
elseif($cachetype==2){
/* ############# 2-->cachefile  Start##############  */ 
$this->load->driver('cache');
$cache_is_supported=$this->cache->file->is_supported();
if($deletekey==1){
 $deletekeysdata=$this->cache->file->delete($cachekey);
}else{$deletekeysdata='Null';}
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
                              'time sec'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>$cache_info);
       
}elseif(!$dataresult){     
###########DB SQL query Start###########
$query=$this->db->query($sql);
$dataresult=$query->result();
###########DB SQL query End ###########
if($dataresult){$this->cache->file->save($cachekey,$dataresult,$cachetime);}
$cache_info=$this->cache->file->cache_info($cachekey);
          $dataall=array('message'=>'Data form database query save to filecache',
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'time sec'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>$cache_info);                  
}
#############################################
/* ############# 1-->cachedb  End##############  */  
return $dataall;
}
elseif($cachetype==3){
$key=$cachekey;
/* ############# 3-->Redis   Start##############  */  
#################################################################
$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
$connection_redis=$this->cache->redis->connection();
$connection=$connection_redis['status'];
$key=$cachekey;
$getdata=$this->cache->redis->getkeysdata($key);

$getkeysdata=$getdata['getdata'];
if($deletekey==1){
$deletekeysdata=$this->cache->redis->deletekeysdata($cachekey);
}else{$deletekeysdata='Null';}
#############################################
$redis_is_supported=$this->cache->redis->is_supported();
$file_is_supported=$this->cache->file->is_supported();
if($redis_is_supported==1 || $file_is_supported==1){
  #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
  #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
}
#############################################
if(!$getkeysdata){ 
###########DB SQL query Start###########
$query=$this->db->query($sql);
$dataresult=$query->result();
#echo '<pre> $dataresult-> '; print_r($dataresult); echo '</pre>'; die();
#$jsondata=json_encode($dataresult, JSON_NUMERIC_CHECK);
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
/* ############# 4-->Memory  memcached  Start##############  */   
# $this->load->library('Memcached_library');
# $resultsdata=$this->memcached_library->get($cachekey);
##################memcached_result###########################
$this->load->driver('cache');
if($this->cache->memcached->is_supported()){
#################
if($deletekey==1){$dataall=$this->cache->memcached->delete($cachekey);}
$dataget=$this->cache->memcached->get($cachekey);
//echo '<pre> get achekey=> '; print_r($dataget); echo '</pre>'; die();
if(!$dataget){
###########DB SQL query Start###########
$query=$this->db->query($sql);
$dataresult=$query->result();
###########DB SQL query End ###########
           $this->cache->memcached->save($cachekey,$dataresult,$cachetime);
           $cache_info=$this->cache->memcached->cache_info();
           $dataall=array('message'=>'Data form database query save to memcached',
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'time sec'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>$cache_info);
}elseif($dataget){
             $cache_info=$this->cache->memcached->cache_info();
             $dataall=array('message'=>'Data form memcached',
					    'status'=>TRUE, 
					     'list'=>$dataget,
                              'count'=>(int)count($dataget),
                              'time sec'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>$cache_info);
        }
 }
 return $dataall;
 //echo '<pre> dataall=> '; print_r($dataall); echo '</pre>'; die();
##################memcached_result###########################
/* ############# 4-->Memory  memcached  End##############  */   
}
elseif($cachetype==5){
/* ############# 4-->DB SQL Start##############  */    
########################################################
###########DB SQL query Start###########
$query=$this->db->query($sql);
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
elseif($cachetype==41){
$this->load->library('Memcached_library');
/* ############# 4-->Memory  memcached  Start##############  */       
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
     ###########DB SQL query Start###########
     $query=$this->db->query($sql);
     $resultsdata=$query->result();
     ###########DB SQL query End ###########
     // Lets store the results
     $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         #'info'=>$cacheinfo
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
                         #'info'=>$cacheinfo
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
##################memcached_result###########################
/* ############# 41-->Memory  memcached  End##############  */   
}
############cache##################

}
###############
}
?>