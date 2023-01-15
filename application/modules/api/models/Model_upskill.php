<?php
class Model_upskill  extends CI_Model{
#####################
public function __construct(){
parent::__construct();
     //Load library
     $this->load->library('Memcached_library');
}
// Function to get the client ip address
function get_client_ip_env() {
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
function get_client_ip_server() {
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
public function cachedb($sql,$cachekey,$cachetime,$cachetype,$deletekey){
############cache##################
/*
echo 'cachedb <hr> sql=> '.$sql; 
echo '<br>cachekey=>'.$cachekey; 
echo '<br>cachetime=>'.$cachetime; 
echo '<br>cachetype=>'.$cachetype; 
echo '<br>deletekey=>'.$deletekey; 
echo '<hr>'; #Die();
*/ 
if($cachetype==null){$cachetype=4;}
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
$this->load->library('Memcached_library');
/* ############# 4-->Memory  memcached  Start##############  */  
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');     
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
############cache##################
}
public function get_upsloguser_config($ups_user_id,$cat,$score,$type,$deletekey){
# $type=1 // insert
# $type=2 // update
# $type=3 // select

#echo '<pre> ups_user_id=>'; print_r($ups_user_id); echo '</pre>';  
#echo '<pre> score=>'; print_r($score); echo '</pre>';  
#echo '<pre> type=>'; print_r($type); echo '</pre>';  //die();

if($score!==null){$deletekey=1;}
if($type!==3){$deletekey=1;}
if($cat==null){
$ups_item_id=null;
$sql="select * from ups_log  
where ups_user_id=$ups_user_id and ups_type=-1 
order by log_id desc limit 1";
$cachekey='upskill-ups-log-user_id-'.$ups_user_id.'-stsus-1';
}elseif($cat!==null){
$ups_item_id=$cat;
$sql="select * from ups_log
where ups_user_id=$ups_user_id and ups_item_id=$cat and ups_type=-1 
order by log_id desc limit 1";   
$cachekey='upskill-ups-log-user_id-'.$ups_user_id.'-stsus-1-category-'.$cat;
}
$cachetime='3600';
$cachetype='4';
$data_from_cache=$this->cachedb($sql,$cachekey,$cachetime,$cachetype,$deletekey);
$list_data=$data_from_cache['list'];
$list_data_count=count($list_data);
if($type==3){   
if($list_data_count>0){$type=2;}
if($list_data_count<=0){$type=1;$score='0';}    
}

#echo '<pre> ups_user_id=>'; print_r($ups_user_id); echo '</pre>';  
#echo '<pre> score=>'; print_r($score); echo '</pre>';  
#echo '<pre> type=>'; print_r($type); echo '</pre>';  die();
// $ups_item_id
if($score!==null){
#################################     
if(($score!==null && ($list_data_count==0 || $list_data_count==null || $list_data_count=='')) ||$type==1){
# $type=1 // insert
$table='ups_log';
     $ups_type='-1';
     $create_by_ip=$this->get_client_ip_env();
     $update_by_ip=$this->get_client_ip_env();
     $create_date=date('Y-m-d h:i:s');
     $update_date=date('Y-m-d h:i:s');
     $datainsert=array('ups_user_id'=>$ups_user_id,
                       'ups_item_id'=>$ups_item_id,
                       'ups_type'=>$ups_type,
                       'title'=>'Config score user id '.$ups_user_id,
                       'visited'=>$score,
                       'status'=>1,
                       'start_date'=>$start_date,
                       'due_date'=>$due_date,
                       'create_by_ip'=>$create_by_ip,
                       'update_by_ip'=>$update_by_ip,
                       'create_by_user'=>$ups_user_id,
                       'update_by_user'=>$ups_user_id,
                       'create_date'=>$create_date,
                       'update_date'=>$update_date,
                    );
     $DBSelect=$this->db; 
     $result_insert=$this->db->insert($table,$datainsert);   
     if($result_insert==1){
$insert_id=$this->db->insert_id();
$deletekey=1;
$data_from_cache=$this->cachedb($sql,$cachekey,$cachetime,$cachetype,$deletekey);
$list_data=$data_from_cache['list'];
$list_data_count=count($list_data);
          $dataall=$list_data;
          return $dataall; 
     } 
}
elseif($score!==null && $type==2 && $cat==!null){
# $type=2 // update
     $filddb1='ups_user_id';
     $filddb2='ups_type';
     $filddb2_data='-1';
     $filddb3='ups_item_id';
     $filddb3_data=$cat;
     $table='ups_log';
         $sdate=date('Y-m-d h:i:s');
         $update_date=date('Y-m-d h:i:s');
         $update_by_ip=$this->get_client_ip_env();
         $dataupdate=array('ups_item_id'=>$ups_item_id,
                        'visited'=>$score,
                        'title'=>'Config score user id '.$ups_user_id,
                        'update_by_ip'=>$update_by_ip,
                        'update_by_user'=>$ups_user_id,
                        'update_date'=>$update_date,
                        'start_date'=>$sdate,
                    ); 
/*              
     echo '<pre> filddb1=>'; print_r($filddb1); echo '</pre>';  
     echo '<pre> filddb2=>'; print_r($filddb2); echo '</pre>'; 
     echo '<pre> ups_user_id=>'; print_r($ups_user_id); echo '</pre>'; 
     echo '<pre> filddb2_data=>'; print_r($filddb2_data); echo '</pre>'; 
     echo '<pre> table=>'; print_r($table); echo '</pre>'; 
     echo '<pre> dataupdate=>'; print_r($dataupdate); echo '</pre>';  
*/               
     $result_data=$this->db->where($filddb1,$ups_user_id);     
     $result_data=$this->db->where($filddb2,$filddb2_data);
     //if($cat!==null){
       $result_data=$this->db->where($filddb3,$filddb3_data);   
     //}
	$result_data=$this->db->update($table,$dataupdate);  
	$resultdata=$result_data;
     //echo '<pre> resultdata=>'; print_r($resultdata); echo '</pre>';  die();
     $rsdata=$this->db->query($sql);
     $data=$rsdata->result_array();   
$deletekey=1;
$data_from_cache=$this->cachedb($sql,$cachekey,$cachetime,$cachetype,$deletekey);
$dataall=$data_from_cache['list'];
$list_data_count=count($list_data);   
#echo '<pre> resultdata=>'; print_r($resultdata); echo '</pre>';  die();
return $dataall;       
}
#################################   
}else{
     $dataall=$list_data;
     return $dataall; 
} 
}
public function get_category($category_id,$deletekey,$cachetype){
/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL
*/

if($cachetype==null){$cachetype=4;}
if($category_id==null){
   $sql="select * from ups_category where status=1";
   $cachekey="key-upskill-ups-category-status-1";  
}
elseif($category_id!==null){
   $sql="select * from ups_category where category_id=$category_id and status=1";
   $cachekey="key-upskill-ups-category-id-".$category_id."-status-1";  
}
//Load library
$cachetime='600';
// Lets try to get the key
############cachekey##################
############cache deletekey##################
if($deletekey==null){$deletekey='';}
############################################
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
                         'type'=>'Memcached',
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
                         'type'=>'Memcached',
                         #'info'=>$cacheinfo
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
#return $resultsdata;
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
############cache##################
}
public function get_type($deletekey,$cachetype){
/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL
*/
if($cachetype==null){$cachetype=4;}
$sql="select * from ups_type where status=1";
$cachekey="key-ups-type-status";  
$cachetime='600';
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');
// Lets try to get the key
############cachekey##################
############cache deletekey##################
if($deletekey==null){$deletekey='';}
############################################
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
#return $resultsdata;
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
############cache##################
}
public function get_count_ups_item($category_id,$type_id,$task_id,$item_id,$deletekey,$cachetype){
/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL  
*/
if($cachetype==null){$cachetype=4;}

$sql.="select count(item.item_id) as count_item
from ups_item as item 
left join ups_task as task on task.task_id=item.ups_task_id
left join ups_category as cate on cate.status=1 and cate.category_id=task.ups_category_id 
left join ups_type as type on type.type_id=item.ups_type_id
where item.status=1 ";
if($category_id!==null){
$sql.=" and cate.category_id=$category_id";
}
if($type_id!==null){
$sql.=" and item.ups_type_id=$type_id";
}
if($task_id!==null){
$sql.=" and item.ups_task_id=$task_id";
}
if($item_id!==null){
$sql.=" and item.item_id=$item_id";
}

$cachekey="key-count-upskill-ups-item-category-id-".$category_id."-type-id-".$type_id."-task-id-".$task_id."-item-id-".$item_id; 

/*     
echo '<pre>category_id=>';print_r($category_id); echo '</pre>';
echo '<pre>type_id=>';print_r($type_id); echo '</pre>';
echo '<pre>item_id=>';print_r($item_id); echo '</pre>';
echo '<pre>sql=>';print_r($sql); echo '</pre>';Die();
*/
$cachetime='60';
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');
// Lets try to get the key
############cachekey##################
############cache deletekey##################
if($deletekey==null){$deletekey='';}
############################################
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
#return $resultsdata;
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
############cache##################
}
public function get_ups_item($category_id,$type_id,$task_id,$item_id,$page,$perpage,$user_id,$deletekey,$cachetype){
if($perpage==null){$perpage=1000;}
$count=$this->get_count_ups_item($category_id,$type_id,$task_id,$item_id,$deletekey,$cachetype);
$rs_counts=$count['list'];
$search_count=$rs_counts['0'];
$searchcount=$search_count->count_item;
$num_rows=$searchcount;
$rec_count=$num_rows;
$totalpage=$rec_count/$perpage;
#$total_page=round($totalpage,0);
#$total_page=round($totalpage,0, PHP_ROUND_HALF_UP); 
$total_page=round($totalpage,0, PHP_ROUND_HALF_EVEN);   
if(isset($page)){$page =$page;}else{$page=1;}
if($page>=$total_page){$page=$total_page;}
$start=($page-1)* $perpage;
if($page==0 ||$page==1){$start=0;}

/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL
*/
if($cachetype==null){$cachetype=4;}
/*
if($user_id!==null){
$sql.="select type.type_name,cate.title as category,task.task_title
,task.task_description,cate.category_id,item.*,logs.log_id,logs.visited
,logs.status as log_status
,logs.start_date as log_start_date
,logs.due_date as log_due_date
,logs.create_date as log_create_date
,logs.update_date as log_update_date
,logs.title as log_title
from ups_item as item 
left join ups_task as task on task.task_id=item.ups_task_id
left join ups_category as cate on cate.status=1 and cate.category_id=task.ups_category_id 
left join ups_type as type on type.type_id=item.ups_type_id
left join ups_log as logs on logs.ups_item_id=item.item_id
where item.status=1 ";
}elseif($user_id==null){}   
*/ 
       
$sql.="select type.type_name,cate.title as category,task.task_title
,task.task_description,cate.category_id,item.*
from ups_item as item 
left join ups_task as task on task.task_id=item.ups_task_id
left join ups_category as cate on cate.status=1 and cate.category_id=task.ups_category_id 
left join ups_type as type on type.type_id=item.ups_type_id
where item.status=1 ";


if($category_id!==null){
$sql.=" and cate.category_id=$category_id";
}
/*
if($user_id!==null){
$sql.=" and logs.ups_user_id=$user_id ";
}
*/ 
if($type_id!==null){
$sql.=" and item.ups_type_id=$type_id";
}
if($task_id!==null){
$sql.=" and item.ups_task_id=$task_id";
}

if($item_id!==null){
$sql.=" and item.item_id=$item_id";
}
$sql.=" order by  item.sort asc";
$sql.=" limit $start,$perpage";
/*
echo '<pre>category_id=>';print_r($category_id); echo '</pre>';
echo '<pre>type_id=>';print_r($type_id); echo '</pre>';
echo '<pre>item_id=>';print_r($item_id); echo '</pre>';
echo '<pre>sql=>';print_r($sql); echo '</pre>';Die();
*/
$cachekey="key-upskill-ups-item-category-id-".$category_id."-type-id-".$type_id."-task-id-".$task_id."-item-id-".$item_id; 
$cachetime='600';
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');
// Lets try to get the key
############cachekey##################
############cache deletekey##################
if($deletekey==null){$deletekey='';}
############################################
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
                         'sql'=>$sql,
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
     //$this->memcached_library->add($cachekey,$resultsdata);
     $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'num_rows'=>$num_rows,
                         'total_page'=>$total_page,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
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
                         'num_rows'=>$num_rows,
                         'total_page'=>$total_page,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         #'info'=>$cacheinfo
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
#return $resultsdata;
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
############cache##################
}
public function get_typyname($type_id,$item_id,$deletekey,$cachetype){
/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL
##############
1 Exam 
2 Cmsblog
3 Learning
4 TV Program
5 Other
*/
if($cachetype==null){$cachetype=4;}
if($type_id==1 && $item_id!==null){
$sql="select exm.exam_name as typyname from cvs_course_examination as exm where exm.id=$item_id limit 1";
$cachekey="key-upskill-typyname-examination-id-".$item_id;  
}
elseif($type_id==2 && $item_id!==null){
$sql="select cms.title as typyname from cmsblog_detail as cms where cms.idx=$item_id limit 1";
$cachekey="key-upskill-typyname-cmsblog-detail-id-".$item_id;  
}
elseif($type_id==3 && $item_id!==null){
$sql="select mc.mul_content_subject as typyname from mul_content mc where mc.mul_content_id=$item_id limit 1";
$cachekey="key-upskill-typyname-mul-content-id-".$item_id;  
}
elseif($type_id==4 && $item_id!==null){
$sql="select tv.tv_name as typyname from tv_program as tv where tv.tv_id=$item_id limit 1";
$cachekey="key-upskill-typyname-tv-program-id-".$item_id;  
}
elseif($item_id!==null){
$sql="select exm.exam_name as typyname from cvs_course_examination as exm where exm.id=$item_id limit 1";
$cachekey="key-upskill-typyname-examination-id-".$item_id;  
}
$cachetime='600';
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');
// Lets try to get the key
############cachekey##################
############cache deletekey##################
if($deletekey==null){$deletekey='';}
############################################
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
#return $resultsdata;
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
############cache##################
}
public function get_count_get_ups_task($category_id,$deletekey,$cachetype){

/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL
*/

if($cachetype==null){$cachetype=4;}
if($category_id==null){
$sql="select count(task_id) as rows_counts
from ups_task as task
left join ups_category as cate on cate.status=1
where task.status=1";
$cachekey="key-upskill-ups-counts";  
}elseif($category_id!==null){
$sql="select count(task_id) as rows_counts
from ups_task as task
left join ups_category as cate on cate.status=1
where task.status=1 and task.ups_category_id=$category_id";
$cachekey="key-upskill-ups-counts-task-category-id-".$category_id;  
}

$cachetime='60';
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');
// Lets try to get the key
############cachekey##################
############cache deletekey##################
if($deletekey==null){$deletekey='';}
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
#return $resultsdata;
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
############cache##################
}
public function get_ups_task($category_id,$item_id,$page,$perpage,$deletekey,$cachetype){
if($perpage==null){$perpage=100;}
$count=$this->get_count_get_ups_task($category_id,$deletekey,$cachetype);
$rs_counts=$count['list'];
$search_count=$rs_counts['0'];
$searchcount=$search_count->count_item;
$num_rows=$searchcount;
$rec_count=$num_rows;
$totalpage=$rec_count/$perpage;
$total_page=round($totalpage,0); 
if(isset($page)){$page =$page;}else{$page=1;}
if($page>=$total_page){$page=$total_page;}
$start=($page-1)* $perpage;
if($page==0 ||$page==1){$start=0;}

/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL
*/
// ,$page,$perpage

if($cachetype==null){$cachetype=4;}
if($category_id==null){
$sql="select  cate.title as category,task.*
from ups_task as task
left join ups_category as cate on cate.status=1
where task.status=1 and task.ups_category_id=1
order by  task.sort asc
limit $start,$perpage";
$cachekey="key-upskill-ups-task-category-id-".$category_id;  
}elseif($category_id!==null){
$sql="select  cate.title as category,task.*
from ups_task as task
left join ups_category as cate on cate.status=1 and cate.category_id=task.ups_category_id 
where task.status=1 and task.ups_category_id=$category_id order by  task.sort asc limit $start,$perpage";
$cachekey="key-upskill-ups-task-category-id-".$category_id;  
}
$cachetime='600';
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');
// Lets try to get the key
############cachekey##################
############cache deletekey##################
if($deletekey==null){$deletekey='';}
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
                         'sql'=>$sql,
                         #'info'=>$cacheinfo
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
#return $resultsdata;
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
############cache##################
}
public function get_update_sort_task($data,$id){
#echo '<pre>';print_r($id); 
#echo '<pre>';print_r($data); echo '</pre>'; Die();	
$this->db->where('task_id',$id);
$result_data=$this->db->update('ups_task',$data);  
#echo '<pre> $result_data=>';print_r($result_data);
#echo '<pre> data';print_r($data); echo '</pre>'; Die();
if($result_data==1){$result_data=1;}else{ $result_data=0;}
return $result_data;     
} 
public function get_update_sort_item($data,$id){
#echo '<pre>';print_r($id); 
#echo '<pre>';print_r($data); echo '</pre>'; Die();	
$this->db->where('item_id',$id);
$result_data=$this->db->update('ups_item',$data);  
#echo '<pre> $result_data=>';print_r($result_data);
#echo '<pre> data';print_r($data); echo '</pre>'; Die();
if($result_data==1){$result_data=1;}else{ $result_data=0;}
return $result_data;     
} 
public function get_update_sort_item2($data,$id,$cat_id,$type){  
##########################################  
/*
echo '<pre>';print_r($id); 
echo '<pre>';print_r($cat_id); echo '</pre>'; 
echo '<pre>';print_r($type); echo '</pre>'; 

*/
$this->db->where('item_id',$id);
$this->db->where('ups_task_id',$cat_id);  

/*
if($type==1){
$this->db->where('ups_task_id',$cat_id);  
}
if($type==2){
$this->db->where('ups_type_id',$cat_id);  
}
*/

$result_data=$this->db->update('ups_item',$data);  
#echo '<pre> $result_data=>';print_r($result_data);
#echo '<pre> data';print_r($data); echo '</pre>'; Die();
if($result_data==1){$result_data=1;}else{ $result_data=0;}
return $result_data;     
} 
public function get_logscore_user_id($exam_id,$user_id,$cachetype,$deletekey){
/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL  
*/
if($cachetype==null){$cachetype=4;}
$sql="select logscore.id as log_id,exam.id as exam_id,exam.exam_name
,users.user_username,users.psn_firstname as firstname,users.psn_lastname as lastname
,logscore.score_value as score_user,logscore.duration_sec as duration
,logscore.date_update as date
,(select COUNT(*) cnt from cvs_course_question where exam_id=exam.id) as  score_exam
from cvs_course_exam_score as logscore 
inner join cvs_course_examination as exam on logscore.exam_id=exam.id
inner join users_account as users on users.user_id=logscore.user_id
where logscore.exam_id=$exam_id and logscore.user_id=$user_id  order by logscore.id asc ";

/*     
echo '<pre>category_id=>';print_r($category_id); echo '</pre>';
echo '<pre>type_id=>';print_r($type_id); echo '</pre>';
echo '<pre>item_id=>';print_r($item_id); echo '</pre>';
echo '<pre>sql=>';print_r($sql); echo '</pre>';Die();
*/
$cachetime='60';
// Lets try to get the key
############cachekey##################
$cachekey="key-exam-log-score-".$exam_id."-userid-".$user_id; 
############cache deletekey##################
if($deletekey==null){$deletekey='';}
############################################
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
$this->load->library('Memcached_library');
/* ############# 4-->Memory  memcached  Start##############  */  
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');     
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
#return $resultsdata;
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
############cache##################
}
public function get_item_by_id($item_id,$cachetype,$deletekey){
/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL
*/
if($cachetype==null){$cachetype=4;}
$sql="select item.* from ups_item as item  where item.item_id=$item_id and item.status=1";
$cachekey="key-ups-item-id-$item_id-status-1";  
$cachetime='600';
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');
// Lets try to get the key
############cachekey##################
############cache deletekey##################
if($deletekey==null){$deletekey='';}
############################################
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
#return $resultsdata;
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
############cache##################
}
public function get_upsloguser($ups_user_id,$ups_item_id,$status,$start_date,$due_date,$deletekey){
// visited 0,1
// status  0,1
$sql="select * from ups_item  where item_id=$ups_item_id and status=1";
$rsdata=$this->db->query($sql);
$itembyid=$rsdata->result_array();
#$itembyid=$this->get_item_by_id($ups_item_id,4,$deletekey);
#echo '<pre> ups_item_id=>';print_r($ups_item_id); echo '</pre>'; 
#echo '<pre> deletekey=>';print_r($deletekey); echo '</pre>'; 
#echo '<pre> itembyid=>';print_r($itembyid); echo '</pre>'; Die();	
$itemid=$itembyid['0'];
//echo '<pre>item id=>';print_r($itemid); echo '</pre>';
$item_id=$itemid['item_id'];
$item_content_id=$itemid['item_content_id'];
$item_title=$itemid['item_title'];
$item_url=$itemid['item_url'];
$arritem=array('item_id'=>$item_id,
               'item_content_id'=>$item_content_id,
               'item_title'=>$item_title,
               'item_url'=>$item_url,
               );
               
///echo '<pre>arritem=>';print_r($arritem); echo '</pre>';  Die();	

$sql="select logs.* from ups_log as logs where logs.ups_user_id=$ups_user_id and logs.ups_item_id=$ups_item_id limit 1";
if($status==0 || $status==null || $status==''){
$rsdata=$this->db->query($sql);
$data=$rsdata->result_array();
$numrows=(int)$rsdata->num_rows();  

#echo '<pre>data=>';print_r($data); echo '</pre>';  
#echo '<pre>numrows=>';print_r($numrows); echo '</pre>';  Die();	
if($numrows==0){
###########################  
if($start_date==null){ 
$visited=1; 
$start_date=date('Y-m-d h:i:s'); 
$due_date=date('Y-m-d h:i:s');
}elseif(
$start_date!==null){ 
$visited=null; 
$due_date=null;
}
if($start_date!==null){
$table='ups_log';
     $ups_type='3';
     $create_by_ip=$this->get_client_ip_env();
     $update_by_ip=$this->get_client_ip_env();
     $create_date=date('Y-m-d h:i:s');
     $update_date=date('Y-m-d h:i:s');
     $datainsert=array('ups_user_id'=>$ups_user_id,
                       'ups_item_id'=>$ups_item_id,
                       'ups_type'=>$ups_type,
                       'title'=>$item_title,
                       'visited'=>$visited,
                       'status'=>1,
                       'start_date'=>$start_date,
                       'due_date'=>$due_date,
                       'create_by_ip'=>$create_by_ip,
                       'update_by_ip'=>$update_by_ip,
                       'create_by_user'=>$ups_user_id,
                       'update_by_user'=>$ups_user_id,
                       'create_date'=>$create_date,
                       'update_date'=>$update_date,
                    );
     $DBSelect=$this->db; 
     $result_insert=$this->db->insert($table,$datainsert);   
     if($result_insert==1){
          $insert_id=$this->db->insert_id();
     }else{}   

}
# if($due_date!==null){} // เวลาที่เปิดดู
########################### 
$rsdata=$this->db->query($sql);
$data=$rsdata->result_array(); 
}
else{
$rsdata=$this->db->query($sql);
$data=$rsdata->result_array();   
$datas=$data['0'];
$visited=(int)$datas['visited'];
$sdate=$datas['start_date'];
/*
echo '<pre>datas=>';print_r($datas); echo '</pre>';  
echo '<pre>visited=>';print_r($visited); echo '</pre>'; Die();	
*/
if($visited==1){
$message='form sql database query visited 1';
/*
echo 'message=>'.$message; 
echo '<pre>datas=>';print_r($datas); echo '</pre>';  
echo '<pre>visited=>';print_r($visited); echo '</pre>'; Die();	
*/
$dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$data['0'],
                         'item'=>$arritem,
                         'num_rows'=>$numrows,
                         'cachekey'=>null,
                         'sql'=>$sql,
                         #'info'=>$cacheinfo
                         );
     return $dataall;   
     die();
}   

// $start_date
     $id=$ups_item_id;
     $filddb='ups_item_id';
     $filddb1='ups_user_id';
     $table='ups_log';
     if($due_date==null){$due_date=date('Y-m-d h:i:s');}
     $update_date=date('Y-m-d h:i:s');
     $update_by_ip=$this->get_client_ip_env();
     ###############################################
     /*
     $dataupdate=array('title'=>$item_title,
                       'due_date'=>$due_date,
                       'update_by_ip'=>$update_by_ip,
                       'update_by_user'=>$ups_user_id,
                       'update_date'=>$update_date,
                    );
     */  
     
     
     
     if($start_date==null){
          $visited=1; 
         if($sdate==null){$sdate=date('Y-m-d h:i:s');}
         $mdate=date('Y-m-d h:i:s');
         $dataupdate=array('visited'=>$visited,
                       'title'=>$item_title,
                       'update_by_ip'=>$update_by_ip,
                       'update_by_user'=>$ups_user_id,
                       'update_date'=>$update_date,
                       'start_date'=>$sdate,
                       'due_date'=>$mdate,
                    ); 
     }else{
          $dataupdate=array('visited'=>0,
                       'title'=>$item_title,
                       'update_by_ip'=>$update_by_ip,
                       'update_by_user'=>$ups_user_id,
                       'update_date'=>$update_date,
                       'start_date'=>$start_date,
                    ); 
     }

//echo '<pre> dataupdate=>';print_r($dataupdate); echo '</pre>'; Die();	
                
     ###############################################
             $result_data=$this->db->where($filddb1,$ups_user_id);     
             $result_data=$this->db->where($filddb,$id);
		   $result_data=$this->db->update($table,$dataupdate);  
		   $resultdata=$result_data;
$rsdata=$this->db->query($sql);
$data=$rsdata->result_array();
}
$message='form sql database query';
$dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$data['0'],
                         'item'=>$arritem,
                         'num_rows'=>$numrows,
                         'cachekey'=>null,
                         'sql'=>$sql,
                         #'info'=>$cacheinfo
                         );
return $dataall;  
}



else{   
##########*******cache*******############     
$cachetype=4;
############cache key####################
$cachekey="key-upskill-ups-log-user_id-".$ups_user_id.'-item_id-'.$ups_item_id; 
$cachetime='3600';
############cache deletekey##############
if($deletekey==null){$deletekey='';}
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
                         'sql'=>$sql,
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
$this->load->library('Memcached_library');
/* ############# 4-->Memory  memcached  Start##############  */       
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');

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
     //$this->memcached_library->add($cachekey,$resultsdata);
     $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata['0'],
                         'item'=>$arritem,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         #'info'=>$cacheinfo
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata['0'],
                         'item'=>$arritem,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         #'info'=>$cacheinfo
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
#return $resultsdata;
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
##########*******cache*******############
 }  
} 
public function get_log_item_user_type($item_id,$user_id,$ups_type_id,$cachetype,$deletekey){
if($cachetype==null){$cachetype=4;}
$sql="select logs.log_id,item.item_id,cate.category_id,type.type_name,cate.title as category
,task.task_title
,logs.title as log_title
,logs.start_date as log_start_date
,logs.due_date as log_due_date
,logs.visited
from ups_log as logs
left join ups_item as item on  logs.ups_item_id=item.item_id
left join ups_task as task on task.task_id=item.ups_task_id
left join ups_category as cate on cate.status=1 and cate.category_id=task.ups_category_id 
left join ups_type as type on type.type_id=item.ups_type_id
where item.item_id=$item_id and logs.ups_user_id=$user_id and item.ups_type_id=$ups_type_id  ";
$cachetime='60';
############cachekey###############
$cachekey="key-logs-item-$item_id-user-$user_id-type-$ups_type_id."; 
############cache deletekey########
if($deletekey==null){$deletekey='';}
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
$this->load->library('Memcached_library');
/* ############# 4-->Memory  memcached  Start##############  */  
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');     
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
#return $resultsdata;
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
############cache##################
}
public function get_ans_question_true($question_id,$status,$cachetype,$deletekey){
if($cachetype==null){$cachetype=4;}
$sql.="select ans.id as id_ans,exam.id as exam_id,question_id as id_question
,exam.exam_name as examination_name
,(select question_detail from  cvs_course_question where id=ans.question_id)as detail_question
,ans.answer_detail as detail_answer
,ans.answer_ans as status_answer
,answer_comment
from cvs_course_answer as ans 
inner join cvs_course_examination as  exam on exam.id=(select exam_id from  cvs_course_question where id=ans.question_id)
where ans.question_id=$question_id ";
if($status!==null){
 $sql.=" and ans.answer_ans='true' ";    
}
$sql.=" order by ans.id asc ";
$cachetime='3600';
############cachekey###############
$cachekey="key-upskill-get-ans-question-$question_id"; 
############cache deletekey########
if($deletekey==null){$deletekey='';}
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
$this->load->library('Memcached_library');
/* ############# 4-->Memory  memcached  Start##############  */  
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');     
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
#return $resultsdata;
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
############cache##################
}
public function get_ups_item_user_log($categoryid,$task_id,$user_id,$cachetype,$deletekey){
if($cachetype==null){$cachetype=4;}
$sql.="select type.type_name,cate.title as category,task.task_title
,task.task_description,cate.category_id,item.item_id,logs.log_id,logs.visited
,logs.status as log_status
,logs.start_date as log_start_date
,logs.due_date as log_due_date
,logs.create_date as log_create_date
,logs.update_date as log_update_date
,logs.title as log_title
from ups_item as item 
left join ups_task as task on task.task_id=item.ups_task_id
left join ups_category as cate on cate.status=1 and cate.category_id=task.ups_category_id 
left join ups_type as type on type.type_id=item.ups_type_id
left join ups_log as logs on logs.ups_item_id=item.item_id
where item.status=1 ";
if($categoryid!==null){
 $sql.=" and cate.category_id=$categoryid";    
}
if($task_id!==null){
 $sql.="  and item.ups_task_id=$task_id";    
}
if($user_id!==null){
 $sql.=" and logs.ups_user_id=$user_id";    
}
$sql.=" order by item.item_id asc ";
$cachetime='3600';
############cachekey###############
$cachekey="key-upskill-ups-log-item-category_id-$categoryid-user-$user_id-task-id-$task_id";
############cache deletekey########
if($deletekey==null){$deletekey='';}
############cache##################
if($cachetype==1){
/* ############# 1-->cachedb  Start##############  */   
if($deletekey==1){
 #$deletekeysdata=$this->db->cache_delete($cachekey);
 $this->db->cache_delete_all();
}  
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
$this->load->library('Memcached_library');
/* ############# 4-->Memory  memcached  Start##############  */  
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');     
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
#return $resultsdata;
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
############cache##################
}
public function loguserexam_get($exam_id,$user_id,$cachetype,$deletekey){
####################################################
ob_end_clean();
$cachetype='4';
$exam_log=$this->get_logscore_user_id($exam_id,$user_id,$cachetype,$deletekey);
$exam_log_list=$exam_log['list']; 
$logexam_arr=array();
if(is_array($exam_log_list)){
foreach($exam_log_list as $k2 =>$vm){
$arr2=array();
      $log_id=(int)$vm->log_id;
      $k2=(int)$k2;
      $ar['c']['no']=$k2;
      $score_exam=(int)$vm->score_exam;
      $score_user=(int)$vm->score_user;
      $ar['c']['score_exam']=$score_exam;
      $ar['c']['score_user']=$score_user;
      
      if($k2>0){
          $k3=$k2-1;
          //$ar['c']['no2']=$k3;
          $d3=$exam_log_list[$k3];
          $score_exam1=(int)$d3->score_exam;
          $score_user1=(int)$d3->score_user;
          $log_id2=(int)$d3->log_id;
          $ar['c']['log_id2']=$log_id2;
          $percent_score1=($score_user1*100)/$score_exam1;
          $percentscore1=(int)round($percent_score1,0,PHP_ROUND_HALF_UP);  
         // $ar['c']['score_exam1']=$score_exam1;
         // $ar['c']['score_user1']=$score_user1;
         // $ar['c']['percentscore1']=$percentscore1; 
      }elseif($k2==0){
          //$ar['c']['no2']=$k2;
          $ar['c']['log_id2']=$log_id;
          $score_exam1=(int)$score_exam;
          $score_user1=(int)$score_user; 
          $percent_score1=($score_user1*100)/$score_exam1;
          $percentscore1=(int)round($percent_score1,0,PHP_ROUND_HALF_UP);  
         // $ar['c']['score_exam1']=$score_exam1;
         // $ar['c']['score_user1']=$score_user1;
         // $ar['c']['percentscore1']=$percentscore1; 
      }
      $ar['c']['log_id']=$log_id;
      $ar['c']['exam_id']=$vm->exam_id;
      $ar['c']['date']=$vm->date;
/*
      $ar['c']['duration']=$vm->duration;
      $ar['c']['exam_name']=$vm->exam_name;
      $ar['c']['firstname']=$vm->firstname;
      $ar['c']['lastname']=$vm->lastname;
*/
      $percent_score=($score_user*100)/$score_exam;
      $percentscore=(int)round($percent_score,0,PHP_ROUND_HALF_UP);  
      $ar['c']['percent_score']=$percentscore;
      $ar['c']['percent']=$percentscore.' %';
      
      if($percentscore>$percentscore1){
          $ar['c']['score_status']=1;
      }elseif($percentscore==$percentscore1){
          $ar['c']['score_status']=0;
      }elseif($percentscore<$percentscore1){
          $ar['c']['score_status']='-1';
      }
      $percent_score2=(int)round($percent_score,0,PHP_ROUND_HALF_UP); 
      $ar['c']['score_percent']=$percent_score2;
      $ar['c']['user_id']=$user_id;
      $ar['c']['user_username']=$vm->user_username;
 $logexam_arr[]=$ar['c'];
 }
}else{$logexam_arr=null;}
#######################
$logexamarr=$logexam_arr;
//echo'<hr> <pre>  logexamarr=>';print_r($logexamarr);echo'<pre> <hr>';  die();
return $logexamarr;
}
##########################################
}

/*
###################******JWT&encode****##############
// encode JWT 
$name='Mr Kongnakorn';
$lastname='Jantakun';
$token_data=array('name'=>$name,'lastname'=>$lastname);
$this->load->helper('jwt');
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$time_setting=60;
$issued_at=time();
$issued_at_date=(date("Y-m-d H:i:s",$issued_at));
$expiration_time=$issued_at+$time_setting;  
$expiration_time_date=(date("Y-m-d H:i:s",$expiration_time));
$payload=array('token_data'=>$token_data,
               'issued_at'=>$issued_at,
               'issued_at_date'=>$issued_at_date,
               'expiration_time'=>$expiration_time,
               'expiration_time_date'=>$expiration_time_date,
               'time_setting'=>$time_setting,
               );
$token=JWT::encode($payload,$key,$algorithm);
####JSON####   
#########################################

#########################################
###################******JWT&decode****##############
$token=$this->input->get('token');
// decode  JWT 
$this->load->helper('jwt');
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$data_decode_jwt=JWT::decode($token,$key,array($algorithm)); 
#echo'<hr> <pre> data_decode_jwt=>';print_r($data_decode_jwt);echo'<pre> <hr>'; //Die(); 
#echo(date("Y-m-d",$expiration_time));
$timenow_de=time();
$time_now_de=(date("Y-m-d H:i:s",$timenow_de));
$issued_at=$data_decode_jwt->issued_at;
$time_setting=$data_decode_jwt->time_setting;
$expiration_time=$data_decode_jwt->expiration_time;
$datadecode=array('token_data'=>$data_decode_jwt->token_data,
                  'issued_at'=>$issued_at,
                  'expiration_time'=>$expiration_time,
                  'time_setting'=>$time_setting,
                  'time_issued_at'=>(date("Y-m-d H:i:s",$issued_at)),
                  'time_expiration'=>(date("Y-m-d H:i:s",$expiration_time)),
                  'timenow'=>$timenow_de,
                  'time_now'=>$time_now_de,
                  );
#echo'<hr><pre> datadecode=>';print_r($datadecode);echo'<pre> <hr>'; Die();  
$now=time();
$now=(int)$now;
$issued_at=(int)$issued_at;
$timecul=($now-$issued_at);
if($timecul>$time_setting){
     $msg_time='Expired';
     $time_st=0;
     $datars=null;
     
}else{ 
     $msg_time='On time not Expired yet';
     $time_st=1;
     $datars=$datadecode;
}if($time_st==1){
     $dataalls=array('module'=>'jwt token',
                'data'=>$datars,
                'msg_time'=>$msg_time,
                'timecul'=>$timecul,
                'status_time'=>$time_st,
                'status'=>false,
                );
}else{
     $dataalls=array('module'=>'jwt token',
                'data'=>null,
                'msg_time'=>$msg_time,
                'timecul'=>$timecul,
                'status_time'=>$time_st,
                'status'=>true,
                );           
 }
###################******JWT&****##############
####JSON####              
########################################
########################################
*/
?>