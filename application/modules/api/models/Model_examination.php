<?php
class Model_examination extends CI_Model{
#####################
public function __construct(){
parent::__construct();
	#$this->load->database('api'); // load database name api
     //Load library
     $this->load->library('Memcached_library');
}
public function encryptcode($q) {
    $cryptKey  = 'tyyptruemd5';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded);
}
public function decryptcode($q){
    $cryptKey  = 'tyyptruemd5';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
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
public function examinationlistwhere_array($array,$deletekey){
##################memcached ci3 ###########################
$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
if($this->cache->memcached->is_supported()){
#######Var##########
$sql="SELECT * from cvs_course_examination where id in ($array)  order by id asc";
#################
$cachekey='key-where-array-cvs_course_examination-'.$sql;
$cachetime='300';
if($deletekey==1){$dataall=$this->cache->memcached->delete($cachekey);}
$data=$this->cache->memcached->get($cachekey);
if($data==null){
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
        }else{
             $cache_info=$this->cache->memcached->cache_info();
             $dataall=array('message'=>'Data form memcached',
					    'status'=>TRUE, 
					     'list'=>$data,
                              'count'=>(int)count($data),
                              'time sec'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>$cache_info);
        }
 }
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##################memcached ci3 ###########################
 return $dataall;
}
public function memcache_manual($array,$deletekey){
// Load library
// manual connection to Mamcache
$memcache=new Memcache;
#$memcachehost=$this->config->load['memcachehost'];
#$memcachepost=$this->config->load['memcachepost'];
$memcache->connect('TPPYMem1.ppm.in.th','11211');
$cache_info=null;//$memcache->getStats();
     $cachetime='3600';
     $sql="SELECT * from cvs_course_examination where id in ($array)  order by id asc";
     $cachekey="key-where-array-cvs_course_examination-".$sql;
     //if($deletekey==1){ $memcache->delete($cachekey); }

        $dataresult=$memcache->get($cachekey);
        if($dataresult!=null){
            $status='cache manual Data form database query save to memcached'.$cachetime.' seconds ';
            $dataall=array('message'=>$status,
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'time sec'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>$cache_info);
        }else{
             
            #######Var##########
		  $query = $this->db->query($sql);
		  $dataresult = $query->result();
            $memcache->set($cachekey,$dataresult,false,$cachetime); 
            // 10 seconds
            $status='cache manual Real data set time cache key'.$cachetime.' seconds';
            $dataall=array('message'=>$status,
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'time sec'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>$cache_info);
        }
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die();     
return $dataall;
}
public function cvs_course_examination_array($array,$deletekey){
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
$sql1="SELECT * from cvs_course_examination where id in ($array) order by id asc";
$sql="SELECT * from cvs_course_examination where id in ($array) order by FIND_IN_SET (id,'$array')";
$search=',';
$replace='-';
$string=$array;
$array2=str_replace($search,$replace,$string);
$cachekey="key-where-array-cvs_course_examination-set-examid-".$array2;
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
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
            //$this->memcached_library->add($cachekey,$resultsdata,$cachetime);
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
}
public function where_course_examination($exam_id,$deletekey,$cachetype){
/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
41-->Memory memcached
5-->Data base SQL
*/
if($cachetype==''){$cachetype=4;}
$sql="select *,(select mul_category_name from  mul_category_2017  where mul_category_id=exa.mul_root_id)as category_name
,(select mul_level_name from  mul_level where mul_level_id=exa.mul_level_id)as level_name
,(select count(id) from cvs_course_question where exam_id=exa.id)as question_total 
from cvs_course_examination as exa where exa.id=$exam_id";
$cachekey="key-cvs-course-question-id-".$exam_id;
$cachetime='600';
##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');
// Lets try to get the key
############cachekey##################
############cache deletekey##################
if($deletekey==''){$deletekey='';}
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
public function where_course_examination2($exam_id,$deletekey){
$sql="select *,(select mul_category_name from  mul_category_2017  where mul_category_id=exa.mul_root_id)as category_name
,(select mul_level_name from  mul_level where mul_level_id=exa.mul_level_id)as level_name
,(select count(id) from cvs_course_question where exam_id=exa.id)as question_total 
from cvs_course_examination as exa where exa.id=$exam_id";
$cachekey="key-cvs-course-question-id-".$exam_id;

##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
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
}
public function wherequestion($question_id,$deletekey){ 
#echo '<pre>question_id=>';print_r($question_id);echo '</pre>';   
#echo '<pre>deletekey=>';print_r($deletekey);echo '</pre>';  Die();
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
$sql="select distinct q.*,(select exam_name from  cvs_course_examination where id=q.exam_id)as exam_name from cvs_course_question as q where q.exam_id=$question_id order by q.id asc";
$cachekey="key-where-question-id-".$question_id;
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
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
}
public function where_cvs_course_exam_score_member($exam_id,$user_id,$order='desc',$limit='1',$log_id){
/*
echo '<pre>$exam_id=>';print_r($exam_id);echo '</pre>';   
echo '<pre>$user_id=>';print_r($user_id);echo '</pre>'; 
echo '<pre>$order=>';print_r($order);echo '</pre>'; 
echo '<pre>$limit=>';print_r($limit);echo '</pre>';  Die();
*/

//Load library
$this->load->library('Memcached_library');
$cachetime='600';

if($user_id==Null){
#################################### 
if($order=='asc'){
$sql.="select distinct score.*,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total
,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  
where score.exam_id=$exam_id ";
if($log_id==''){}else{$sql.=" and score.id=$log_id ";}
$sql.=" order by score.id asc limit 1";
}else{
$sql.="select distinct score.*
,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  
where score.exam_id=$exam_id "; 
if($log_id==''){}else{$sql.=" and score.id=$log_id ";}
$sql.=" order by score.id desc limit 1";
}
#################################### 
}else{
#################################### 
if($order=='asc'){
$sql.="select distinct score.*,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total
,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  
where score.exam_id=$exam_id and score.user_id=$user_id ";
if($log_id==''){}else{$sql.=" and score.id=$log_id ";}
$sql.=" order by score.id asc limit 1";

}else{
$sql.="select distinct score.*,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total
,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  
where score.exam_id=$exam_id and score.user_id=$user_id ";
if($log_id==''){}else{$sql.=" and score.id=$log_id ";}
//$sql.=" order by score.id desc ";
$sql.=" limit 1";
}
#################################### 
}
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();


if($log_id==''){
     $cachekey="key-cvs-course-exam-score-exam_id-".$exam_id.'-userid-'.$user_id;
}else{
     $cachekey="key-cvs-course-exam-score-exam_id-".$exam_id.'-userid-'.$user_id.'-log_id-'.$log_id;   
}

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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
}
public function where_course_examination_id($id,$deletekey){
$sql="select * 
,(select mul_category_name from  mul_category_2017  where mul_category_id=exa.mul_root_id)as category_name
,(select mul_level_name from  mul_level where mul_level_id=exa.mul_level_id)as level_name
,(select count(id) from cvs_course_question where exam_id=exa.id)as question_total
from cvs_course_examination as exa where exa.id=$id";
$cachekey="key-where_course-examination-category2017-level-id".$id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############
return $dataall;
}
public function where_map_choice_true($id,$deletekey){
$sql="select question_id as id_question,ans.id as id_ans,ans.answer_detail as detail_answer,ans.answer_ans as status_answer,answer_comment
,(select question_detail from  cvs_course_question where id=ans.question_id)as detail_question
from cvs_course_answer as ans where question_id=$id  and answer_ans='true' order by id asc";
$cachekey="key-cvs-course-answer-true-id".$id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
}
public function where_map_choice($id,$deletekey){
$sql="select ans.id as ans_id,ans.answer_detail as detail,ans.answer_ans as answer
,(select question_detail from  cvs_course_question where id=ans.question_id)as question_detail
from cvs_course_answer as ans where question_id='$id' order by id asc";
$cachekey="key-where-map-choice-id".$id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
}
public function score_value_exam_id($exam_id,$deletekey){
$sql="SELECT score_value, count( score_value ) as total 
FROM cvs_course_exam_score WHERE exam_id=$exam_id GROUP BY score_value";
$cachekey = "score-value-exam-id-".$exam_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function score_value_exam_id_users_group($exam_id,$school_name,$school_level,$deletekey){ 
$sql1="SELECT score.score_value,count(score.score_value) as total FROM cvs_course_exam_score as score "; 
/*
$sql1="SELECT score.score_value,count(score.score_value) as total
,users.user_id,users.psn_firstname,users.psn_lastname
,users.job_edu_name as edu_name
,users.job_edu_level as edu_level
FROM cvs_course_exam_score as score
";  
*/   
if($school_name==null && $school_level==null){
//$sql2=" left join users_account as users on score.user_id=users.user_id WHERE score.exam_id=$exam_id  and users.psn_firstname!='' GROUP BY score.score_value";   
$sql2=" WHERE score.exam_id=$exam_id GROUP BY score.score_value";   
$cachekey="score-value-exam-id-users-group".$exam_id;
}elseif($school_name!==null && $school_level==null){
$sql2=" left join users_account as users on score.user_id=users.user_id
WHERE score.exam_id=$exam_id  and users.psn_firstname!='' and users.job_edu_name like '$school_name%' GROUP BY score.score_value";  
$cachekey="score-value-exam-id-users-group-".$exam_id.'-school_name-'.$school_name;
}elseif($school_name!==null && $school_level!==null){
$sql2=" left join users_account as users on score.user_id=users.user_id
WHERE score.exam_id=$exam_id  and users.psn_firstname!='' and users.job_edu_name like '$school_name%' and users.job_edu_level like '$school_level%' GROUP BY score.score_value";   
$cachekey="score-value-exam-id-users-group-".$exam_id.'-school_name-'.$school_name.'-job-edu-level-'.$school_level;
}       
$sql=$sql1.$sql2;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'sql'=>$sql,
                         'cachekey'=>$cachekey,
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
                         'sql'=>$sql,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############
//echo'<pre> $dataall=>';print_r($dataall);echo'</pre>';Die(); 
#return $dataall;
return $resultsdata;
#return $data;
}
public function score_value_exam_id_job_edu_name($exam_id,$job_edu_name,$deletekey){
$sql="SELECT score_value, count( score_value ) as total 
FROM cvs_course_exam_score 
WHERE exam_id=$exam_id and user_id in (select user_id from users_account where job_edu_name like'$job_edu_name%')
GROUP BY score_value";
$cachekey = "score-value-exam-id-".$exam_id.'-job-edu-name-'.$job_edu_name;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'sql'=>$sql,
                         'cachekey'=>$cachekey,
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
                         'sql'=>$sql,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function score_count_cnt($exam_id,$deletekey){
$sql = "SELECT COUNT(*) cnt FROM cvs_course_question WHERE exam_id=$exam_id";
$cachekey = "QuestionCNT-ScoreDisplay-exam_score-member-".$exam_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function where_count_lesson_mul_map_exam_question_lesson_exam_id($id,$deletekey){
$sql="select distinct map_eql.lesson_id 
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map_eql.lesson_id)as count_lesson
,(select lesson_name from mul_lesson where lesson_id=map_eql.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
,(select name from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as book_name
,(select mul_level_id from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_id
,(select mul_category_id from mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_id
,(select mul_education_book_id from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as mul_education_book_id
,(select id from  cvs_course_examination where id=exam.id)as exam_id
from  mul_map_exam_question_lesson as map_eql   
left join mul_lesson as lesson on lesson.lesson_id=map_eql.lesson_id
left join cvs_course_question as question on question.id=map_eql.question_id
left join cvs_course_examination as exam on exam.id=question.exam_id
where exam.id=$id  group by map_eql.lesson_id order by exam.id asc";
#echo '<pre>  $sql=>';print_r($sql); echo '<pre>'; 
#$cache_key = "where_count_lesson_mul_map_exam_question_lesson_exam_id_".$sql;
$cachekey="where-count-lesson-mul-map-exam-question-lesson-exam-id-".$id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function where_count_lesson_mul_map_exam_question_lesson_exam_id_mul_content($exam_id,$deletekey){
$sql="select distinct map_eql.lesson_id 
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map_eql.lesson_id)as count_lesson
,(select lesson_name from mul_lesson where lesson_id=map_eql.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
,(select name from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as book_name
,(select mul_level_id from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_id
,(select mul_category_id from mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_id
,(select mul_education_book_id from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as mul_education_book_id
,(select id from  cvs_course_examination where id=exam.id)as exam_id
,content.mul_content_subject,content.mul_content_text,concat('http://www.trueplookpanya.com/learning/detail/',content.mul_content_id) AS content_url
from  mul_map_exam_question_lesson as map_eql   
left join mul_lesson as lesson on lesson.lesson_id=map_eql.lesson_id
left join cvs_course_question as question on question.id=map_eql.question_id
left join cvs_course_examination as exam on exam.id=question.exam_id
left join mul_map_content_lesson as map  on map.lesson_id=lesson.lesson_id 
left join mul_content  content on content.mul_content_id=map.mul_content_id 
where exam.id=$exam_id and content.mul_content_id!='' group by map_eql.lesson_id";
$query=$this->db->query($sql);
$results=$query->result();
$resultsdata=$results['0'];
$total_answer_true=$resultsdata;
$cnt=$total_answer_true->cnt;
$this->load->library('Memcached_library');
$cachetime='300';
#################################### 
$search=',';
$replace='-';
$string=$idin;
$set=str_replace($search,$replace,$answer_set);
$cachekey="key-count-lesson-mul-map-exam-question-lesson-mul-content-exam-id".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
#return $dataall;
}
public function where_mul_map_exam_question_lesson_id($lesson_id){
$sql="select * from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.lesson_id=$lesson_id";
$cachekey="where-mul-map-exam-question-lesson-id-".$lesson_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function where_mul_map_exam_question_lesson_id_exam_id($lesson_id,$exam_id,$deletekey){
$sql="select * from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.lesson_id=$lesson_id and  q.exam_id=$exam_id";
$cachekey="where-mul-map-exam-question-lesson-id-".$lesson_id.'-exam-id-'.$exam_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function get_where_mul_map_content_lessonid_join($lesson_id,$deletekey){
$sql="select distinct content.mul_content_id,ls.lesson_name,ls.lesson_id,ls.lesson_parent_id,content.mul_content_subject,content.mul_content_text,concat('http://www.trueplookpanya.com/learning/detail/',content.mul_content_id) AS content_url
,concat('http://www.trueplookpanya.com/api/knowledgebase/content/',content.mul_content_id) AS content_api_url 
,concat('http://static.trueplookpanya.com/',content.thumbnail_path,content.thumbnail_name) AS thumbnail 
,(select view_count from mul_content where mul_content_id=content.mul_content_id)as view_count
,(select view_count from mul_content where mul_content_id=content.mul_content_id)as view
from mul_map_content_lesson map 
left join mul_lesson ls on map.lesson_id=ls.lesson_id 
left join mul_content  content on content.mul_content_id=map.mul_content_id 
where map.lesson_id=$lesson_id";
$cachekey="get-where-mul-map-content-lessonid-join-".$lesson_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
#return $data;
}
public function get_where_mul_map_content_lessonid_join_lessonset($lessonset,$deletekey){
$sql="select distinct content.mul_content_id,ls.lesson_name,ls.lesson_id,ls.lesson_parent_id,content.mul_content_subject,content.mul_content_text,concat('http://www.trueplookpanya.com/learning/detail/',content.mul_content_id) AS content_url
,concat('http://www.trueplookpanya.com/api/knowledgebase/content/',content.mul_content_id) AS content_api_url 
,concat('http://static.trueplookpanya.com/',content.thumbnail_path,content.thumbnail_name) AS thumbnail 
,(select view_count from mul_content where mul_content_id=content.mul_content_id)as view_count
,(select view_count from mul_content where mul_content_id=content.mul_content_id)as view
from mul_map_content_lesson map 
left join mul_lesson ls on map.lesson_id=ls.lesson_id 
left join mul_content  content on content.mul_content_id=map.mul_content_id 
where map.lesson_id in($lessonset)";
$search=',';
$replace='-';
$string=$idin;
$set=str_replace($search,$replace,$lessonset);
$cachekey="get-where-mul-map-content-lessonid-join-lessonset-".$set;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
#return $data;
}
public function job_edu_name_users_account($school_name,$deletekey){
$sql1="select distinct(users.job_edu_level)  
,(select count(user_id) from users_account where job_edu_level=users.job_edu_level and job_edu_name=users.job_edu_name)as user_count
from  users_account as users   
where users.job_edu_name='$school_name'and job_edu_level is not null";
$sql2="select distinct(users.job_edu_level)  
,(select count(user_id) from users_account where job_edu_level=users.job_edu_level and job_edu_name=users.job_edu_name)as user_count
from  users_account as users   
where users.job_edu_name like '$school_name%' and job_edu_level is not null";
$sql=$sql2;
$cachekey="key-job-edu-name-users-account-all-school_name-".$school_name; 
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
#return $data;
}
public function job_edu_name_users_account_count($school_name,$deletekey){
$sql1="select  count(user_id)  as user_count from  users_account as users  
where users.job_edu_name='$school_name' and job_edu_level is not null";
$sql2="select  count(user_id)  as user_count from  users_account as users  
where users.job_edu_name like '$school_name%'and job_edu_level is not null";
$sql=$sql2;
$cachekey="key-job-edu-name-users-account-all-count-school_name-".$school_name; 
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
#return $data;
}
public function school_examination_distinct_edu_name_edu_level_exam_id2($exam_id,$deletekey){
$sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  user_id=users.user_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000  and score.exam_id=$exam_id order by users.user_id asc,score.id desc";
$cachekey="key-schools-where-examination-distinct-edu-name-edu-level-exam_id2-".$exam_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
#return $data;
}
public function school_users($school_name,$school_level,$deletekey){
if($school_level==''||$school_level==null){
 $sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree,users.user_email as email
from  users_account as users   
where  users.job_edu_name like '$school_name%' order by users.user_id asc";   
$cachekey="key-school-users-".$school_name; 
}elseif($school_level!==''){
$sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree,users.user_email as email
from  users_account as users   
where  users.job_edu_name like '$school_name%' and users.job_edu_level='$school_level' order by users.user_id asc";
$cachekey="key-school-users-".$school_name.'-school-level-'.$school_level;
}

##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
#return $data;
}
public function school_users_exam_score($school_name,$school_level,$scorestatus,$deletekey){
if($school_level==''||$school_level==null){
$sql1="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree,users.user_email as email
,score.answer_value,score.score_value,score.exam_id,score.duration_sec
from  users_account as users   
left join cvs_course_exam_score as  score on score.user_id=users.user_id";
if($scorestatus==''){
$sql2=" where  users.job_edu_name like '$school_name%' group by users.user_id asc"; 
$scorestatus=0; 
}else{
$sql2=" where  users.job_edu_name like '$school_name%' and score.exam_id!='' group by users.user_id asc"; 
  
}
$sql=$sql1.$sql2;
$cachekey="key-school-exam-score-users-".$school_name.'-status'.$scorestatus; 
}elseif($school_level!==''){
$sql1="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree,users.user_email as email
,score.answer_value,score.score_value,score.exam_id,score.duration_sec
from  users_account as users   
left join cvs_course_exam_score as  score on score.user_id=users.user_id ";
if($scorestatus==''){
$sql2=" where  users.job_edu_name like '$school_name%' and users.job_edu_level='$school_level' group by users.user_id asc";  
$scorestatus=0; 
}else{
$sql2=" where  users.job_edu_name like '$school_name%' and users.job_edu_level='$school_level' and score.exam_id!='' group by users.user_id asc"; 
  
}
$sql=$sql1.$sql2;
$cachekey="key-school-exam-score-users-".$school_name.'-school-level-'.$school_level.'-status'.$scorestatus; 
}
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
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
                         );
}
##########*******memcache*******############
return $dataall;
//return $resultsdata;
#return $data;
}
public function score_avg($exam_id,$school_name,$school_level,$deletekey){
/*
echo '<pre>$exam_id=>';print_r($exam_id);echo '</pre>';   
echo '<pre>$school_name=>';print_r($school_name);echo '</pre>'; 
echo '<pre>$school_level=>';print_r($school_level);echo '</pre>'; 
*/
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
if($school_name==''&& $school_name=='' && $school_level==''){
$sql="select ROUND(avg(score.score_value)) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000  and  score.exam_id=$exam_id  
order by users.user_id asc,score.id desc";
$cachekey="key-score-avg-examid-".$exam_id;
}elseif($school_name!==''&& $school_level==''){
$sql="select ROUND(avg(score.score_value)) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000  and  score.exam_id=$exam_id and users.job_edu_name like'$school_name%'
order by users.user_id asc,score.id desc"; 
$cachekey="key-score-avg-examid-".$exam_id.'-jobedu_name-'.$school_name;
}elseif($school_name!=='' && $school_level!==''){
$sql="select ROUND(avg(score.score_value)) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000  and  score.exam_id=$exam_id and users.job_edu_name like'$school_name%' and users.job_edu_level like'$school_level%'
order by users.user_id asc,score.id desc"; 
$cachekey="key-score-avg-examid-".$exam_id.'-jobedu_name-'.$school_name.'-edu_level-'.$school_level;
}
#################################### 
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();

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
                         );
}
##########*******memcache*******############
return $resultsdata;
}
public function lesson_examination_by_exam_id($exam_id,$deletekey){
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 

$sql="select distinct map_eql.lesson_id 
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map_eql.lesson_id)as count_lesson
,(select lesson_name from mul_lesson where lesson_id=map_eql.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
,(select name from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as book_name
,(select mul_level_id from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_id
,(select mul_category_id from mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_id
,(select mul_education_book_id from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as mul_education_book_id
,(select id from  cvs_course_examination where id=exam.id)as exam_id
from  mul_map_exam_question_lesson as map_eql   
left join mul_lesson as lesson on lesson.lesson_id=map_eql.lesson_id
left join cvs_course_question as question on question.id=map_eql.question_id
left join cvs_course_examination as exam on exam.id=question.exam_id
where exam.id=$exam_id";
$cachekey="key-lesson-examination-by-exam_id-".$exam_id;

#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
##########*******memcache*******############
return $resultsdata;
}
public function lesson_examination_question_true_by_exam_id($exam_id,$lesson_id,$deletekey){
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$sql="select q.exam_id,map.*,q.question_detail as question,q.question_detail as question_detail,ans.id as id_ans,ans.answer_ans,ans.answer_detail  as answer
,lesson.lesson_name,exam.exam_name
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select lesson_name from mul_lesson where lesson_id=map.lesson_id)as lesson_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
from mul_map_exam_question_lesson as map   
left join cvs_course_question as q on q.id=map.question_id
left join cvs_course_examination as exam on exam.id=q.exam_id
left join cvs_course_answer as ans on q.id=ans.question_id
left join mul_lesson as lesson on lesson.lesson_id=map.lesson_id
where q.exam_id=$exam_id and ans.answer_ans='true' and map.lesson_id=$lesson_id";
$cachekey="key-lesson-examination-question-true-by-exam_id-".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function mul_map_exam_question_lesson_by_lesson_id($lesson_id,$deletekey){
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$sql="select * from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.lesson_id=$lesson_id";
$cachekey="key-mul-map-exam-question-lesson-by-lesson_id-".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function total_answer_true($result_set,$deletekey) {
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$sql="select COUNT(*) cnt FROM cvs_course_answer WHERE id IN($result_set2) AND answer_ans='true'";
$search=',';
$replace='-';
$string=$result_set;
$result_set2=str_replace($search,$replace,$string);
$cachekey="key-total-answer-true-".$result_set2;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function count_user_edu_name_level($job_edu_name,$job_edu_level,$deletekey){
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$sql="select count(user_id)as count_user from  users_account as users where users.job_edu_name='$job_edu_name' and users.job_edu_level='$job_edu_level'";
$cachekey="key-count-user-edu_name-'.$job_edu_name.'-level-".$job_edu_level;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function chart_question_lesson_name_exam_id($exam_id,$deletekey){
$sql="select distinct map_eql.lesson_id 
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map_eql.lesson_id)as count_question_lesson
,(select lesson_name from mul_lesson where lesson_id=map_eql.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
from  mul_map_exam_question_lesson as map_eql   
left join mul_lesson as lesson on lesson.lesson_id=map_eql.lesson_id
left join cvs_course_question as question on question.id=map_eql.question_id
left join cvs_course_examination as exam on exam.id=question.exam_id
where exam.id=$exam_id";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$cachekey="key-chart-question-lesson_name-exam-id-".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function where_in_question_lesson_id_ans_true($coursequestionid,$lesson_id,$deletekey){    
 /*
$sql="select *
,(select id from cvs_course_answer where question_id=map.question_id and answer_ans='true')as id_ans_true
from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.question_id IN($coursequestionid) and map.lesson_id='$lesson_id' ";
*/

$sql="select exam_id,question_id
,(select id from cvs_course_answer where question_id=map.question_id and answer_ans='true')as id_ans_true
,lesson_id
from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.question_id IN($coursequestionid) and map.lesson_id='$lesson_id' ";
 
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$coursequestionid;
$coursequestionid=str_replace($search,$replace,$string);
$cachekey="key-where-in-question-lesson-id'.$lesson_id.'-ans-true-in".$coursequestionid;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function count_scoredisplay_exam_score($exam_id,$deletekey){
$sql = "SELECT COUNT(*) cnt FROM cvs_course_question WHERE exam_id=$exam_id";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$coursequestionid;
$coursequestionid=str_replace($search,$replace,$string);
$cachekey="key-count-scoredisplay-exam-score-exam-id".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function scoredisplay_exam_score_school($exam_id,$job_edu_name,$job_edu_level,$deletekey){
if($job_edu_name==Null&& $job_edu_level==Null){
$sql="SELECT score_value, count( score_value ) as total FROM cvs_course_exam_score WHERE exam_id='$exam_id' GROUP BY score_value";
 }else if($job_edu_name!==Null && $job_edu_level==Null){
$sql="select score_value,count( score_value )as total 
from cvs_course_exam_score 
where exam_id=$exam_id and user_id in (select user_id from users_account where job_edu_name like'$job_edu_name%')
group by score_value ";
}else if($job_edu_name!==Null && $job_edu_level!==Null){
$sql="SELECT score_value, count( score_value ) as total 
FROM cvs_course_exam_score 
WHERE exam_id='$exam_id' and user_id in (select user_id from users_account where job_edu_name like'$job_edu_name' and job_edu_level='$job_edu_level')
GROUP BY score_value";
} 
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$coursequestionid;
$coursequestionid=str_replace($search,$replace,$string);
$cachekey="key-scoredisplay-exam-score-school-exam-id".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function avg_school_level_exam_id($job_edu_name,$job_edu_level,$exam_id,$deletekey){
$sql="select avg(score.score_value) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000 and users.job_edu_name='$job_edu_name' and  users.job_edu_level='$job_edu_level'and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$coursequestionid;
$coursequestionid=str_replace($search,$replace,$string);
$cachekey="key-avg-schools-level-exam-id".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
###school###
public function school_name_examination($schoolset,$deletekey){
if($schoolset==''){
$schoolset=" 'โรงเรียนวัดยานนาวา','โรงเรียนวัดพระยาสุเรนทร์ (บุญมีอนุกูล)','โรงเรียนวัดหงส์ปทุมาวาส','โรงเรียนพระแม่มารีพระโขนง','โรงเรียนวัดเทพลีลา','สาธิตม.บ้านสมเด็จฯ (ประถม)','โรงเรียนบีคอนเฮาส์แย้มสอาดรังสิต','โรงเรียนหนองจอกกงลิบฮัวเคียว','โรงเรียนอนุบาลนนทบุรี','โรงเรียนเซนต์โยเซฟ บางนา','โรงเรียนวัดแสนเกษม','โรงเรียนบ้านลำต้นกล้วย','โรงเรียนโชคชัยกระบี่','โรงเรียนโชคชัยลาดพร้าว','โรงเรียนพระหฤทัยดอนเมือง','โรงเรียนเขมะสิริอนุสสรณ์','โรงเรียนโชคชัยรังสิต','โรงเรียนวัดบึงทองหลาง','โรงเรียนวัดนิมมานรดี','โรงเรียนสามเสนนอก','โรงเรียนสายน้ำทิพย์','โรงเรียนสากลศึกษา บางบัวทอง','โรงเรียนวัดเวฬุวนาราม(สินทรัพย์อนุสรณ์)' ";        
}
$sql="select distinct users.job_edu_name
,(select count(user_id) from users_account where job_edu_name=users.job_edu_name)as user_total
,(select count(distinct score.user_id) as score_count from cvs_course_exam_score  as score left join users_account as usr on usr.member_id=score.member_id where  usr.job_edu_name=users.job_edu_name)as score_total
from users_account as users 
where users.job_edu_name in ($schoolset) order by users.user_id asc";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$schoolset;
$schoolset=str_replace($search,$replace,$string);
$cachekey="key-school-name-examination-".$schoolset;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function where_map_examquestion_examlessontrue($exam_id,$lesson_id,$deletekey){
$sql="select q.exam_id,map.*,q.question_detail as question,q.question_detail as question_detail,ans.id as id_ans,ans.answer_ans,ans.answer_detail  as answer
,lesson.lesson_name,exam.exam_name
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select lesson_name from mul_lesson where lesson_id=map.lesson_id)as lesson_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
from mul_map_exam_question_lesson as map   
left join cvs_course_question as q on q.id=map.question_id
left join cvs_course_examination as exam on exam.id=q.exam_id
left join cvs_course_answer as ans on q.id=ans.question_id
left join mul_lesson as lesson on lesson.lesson_id=map.lesson_id
where q.exam_id=$exam_id and ans.answer_ans='true' and map.lesson_id=$lesson_id";
 
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$schoolset;
$schoolset=str_replace($search,$replace,$string);
$cachekey="key-where-mul-map-exam-questionss-exam-id-'.$exam_id.'-lesson-id-true-".$lesson_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
### insert
public function inserttabledata($table,$data){
$this->db->insert($table,$data); 
$rows=$this->db->affected_rows(); 
$insert_id=$this->db->insert_id(); 
if($rows!=1){
     $insert=0;
     $insert_msg='false';
}else{
     $insert=1; 
     $insert_msg='true';
}
$dataall=array('insert_id'=>$insert_id,
               'insert'=>$insert,
               'insert_msg'=>$insert_msg,
               );
return $dataall;
}
### insert
public function inserttabledata1($table,$data){
$this->db->insert($table,$data); 
$rows=$this->db->affected_rows(); 
$insert_id=$this->db->insert_id(); 
if($rows!=1){
     $insert=0;
     $insert_msg='false';
}else{
     $insert=1; 
     $insert_msg='true';
}
$dataall=array('insert_id'=>$insert_id,
               'insert'=>$insert,
               'insert_msg'=>$insert_msg,
               );
return $dataall;
}
### update
public function updatetabledata($table,$data,$fild,$value){
$this->db->where($fild, $value);
$this->db->update($table, $data);
return ($this->db->affected_rows() > 0);
}
### delete
public function deletetabledata($table,$fild,$value){
$this->db->where($fild,$value);
$this->db->delete($table);
return ($this->db->affected_rows()<=0);
}
###########***cvs_course_examination##########
public function where_id_in_cvs_course_examination_findinset($array,$deletekey){
#$sql1="select * from cvs_course_examination where id in ($array)  order by id asc";
$sql="select * from cvs_course_examination where id in ($array)  order by FIND_IN_SET (id,'$array')";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$array;
$schoolset=str_replace($search,$replace,$string);
$cachekey="key-where-id-in-cvs-course-examination-findinset-".$schoolset;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
###########***users_account##########
public function where_users_account_user_id_in($user_id,$deletekey){
/*
$sql2="select users.user_id,users.user_username,users.member_id
,users.psn_display_name,users.psn_firstname,users.psn_lastname,users.psn_sex,users.job_edu_name,users.job_edu_level
,ufavorite.content_key,ufavorite.content_id
,logs.ref_id,logs.user_ip,logs.detail as log_detail,logs.updated_date
from users_account as users
inner join users_favorite as ufavorite on ufavorite.user_id=users.user_id 
inner join user_log as logs on logs.member_id=users.member_id 
where users.user_id in ($user_id)  
order by users.user_id asc";
*/
$sql="select users.* from users_account as users where users.user_id in ($user_id)  order by users.user_id asc";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$idin;
$schoolset=str_replace($search,$replace,$string);
$cachekey="key-where-users-account-user-id-in-".$user_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function where_user_log_pagination_member_id($member_id,$limit,$offset,$order='desc',$deletekey){
$sql="select logs.* from user_log as logs where logs.member_id='$member_id' order by logs.id $order limit $limit offset $offset";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$idin;
$schoolset=str_replace($search,$replace,$string);
$cachekey="key-where-user-log-pagination-member-id-".$member_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
          $results_data=$query->result();
          $resultsdata=$results_data->getdata;
     ###########DB SQL query End ###########
            // Lets store the results
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
#################
public function record_users_account($user_id,$datelog,$limit,$deletekey){
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
$search=',';
$replace='-';
$string=$idin;
$schoolset=str_replace($search,$replace,$string);
$cachekey="key-users-account-logs-".$user_id;
#################################### 
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
$sql="select users.member_id,users.user_id,users.psn_firstname as  firstname,users.psn_lastname as lastname,users.psn_display_name as display_name,users.psn_sex,users.user_username,users.user_email,users.psn_address,users.psn_display_image,users.psn_postcode,users.psn_postcode,users.job_edu_name,users.job_edu_level,users.psn_id_number
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
from users_account as users 
where users.user_id=$user_id  order by users.user_id asc";
$query=$this->db->query($sql);
$resultsdata=$query->result();
$row=$query->num_rows();
//echo '<pre>$resultsdata=>';print_r($resultsdata);echo '</pre>';   
$rs1=$resultsdata['0'];
//echo '<pre>$rs1=>';print_r($rs1);echo '</pre>';  Die();
$member_id=$rs1->member_id;
#############
if($limit==''){$limit=50;}
if($datelog==''){
$sql2="select id,member_id,task,menu,updated_date from user_log where  member_id='$member_id' order by id desc limit $limit";  
}else{
$sql2="select id,member_id,task,menu,updated_date from user_log where  member_id='$member_id' and updated_date like'$datelog%' order by id desc limit $limit";
}

$query2=$this->db->query($sql2);
$resultsdata2=$query2->result();
$row2=$query2->num_rows();
############
if($datelog==''){
$sql3="select * from user_history where  member_id='$member_id' order by history_id desc limit $limit";
}else{
$sql3="select * from user_history where  member_id='$member_id' and history_datetime like'$datelog%' order by history_id desc limit $limit";
}
$query3=$this->db->query($sql3);
$resultsdata3=$query3->result();
$row3=$query3->num_rows();
############
$resultsdata=array('profile'=>$rs1,
            'member_log'=>$resultsdata2,
            'user_history'=>$resultsdata3,
            'row'=>$row2,
           );
###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$data,
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;

}
public function where_examination_question_answer($exam_id,$deletekey){
$sql="select exam.id as exam_id,q.id as question_id,ans.question_id as question_id_true,ans.id as id_ans
from cvs_course_examination as exam
inner join cvs_course_question as q on q.exam_id=exam.id
left join cvs_course_answer as ans on  ans.question_id=q.id and ans.answer_ans='true'
where exam.id in($exam_id)";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$cachekey="key-where-examination-question-answer-".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
###examination start##########
public function get_searchall_count1($keyword,$level_id,$category_id,$searchtype,$deletekey){   
if($keyword=='ONET' ||$keyword=='onet' ||$keyword=='o-net'||$keyword=='o_net'||$keyword=='Onet'){$keyword='O-NET';} 
if($perpage==''){$perpage=100;}
####################################
##########*******memcache*******############
$search=',';
$replace='-';
$string=$idin;
$schoolset=str_replace($search,$replace,$string);
$cachekey='key-cvscourseexaminationkeyword-count-'.$keyword.'-level_id-'.$level_id.'-category_id-'.$category_id.'-page-'.$page;
##############
//Load library
$this->load->library('Memcached_library');
$cachetime='60';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
###########DB SQL query Start###########
$start=($page-1)*$perpage;
$sql.="select distinct dc.id as content_id,dc.*,ces.exam_status as public_status
,(select count(id) from cvs_course_question cc where cc.exam_id=dc.id) question_count
,(select mul_level_name from mul_level where mul_level_id=dc.mul_level_id) level_name
,(select mul_category_name from mul_category_2014 where mul_category_id=dc.mul_root_id) subject_name
,dc.view_count as views
from cvs_course_examination as dc 
inner join cvs_course_exam_share as ces on ces.exam_id=dc.id
where dc.exam_status='yes'  and ces.exam_status='shared' ";
if($searchtype=="member_id"){
$sql .=" and dc.member_id LIKE %$keyword% "; 
}else if($searchtype=="exam_code"){
$sql .=" and dc.exam_code LIKE %$keyword% "; 
}else if($searchtype=="question_text"){
$sql .=" and dc.id in (select exam_id from cvs_course_question where question_detail like %$keyword%)"; 
}else if($keyword!=null){
$sql.=" and dc.exam_name like '%$keyword%'";   
}
if($level_id!=null){
$sql.=" and dc.mul_level_id=$level_id";   
}if($category_id!=null){
$sql.=" and dc.mul_root_id=$category_id ";   
}
$query=$this->db->query($sql);
$results=$query->result();
$row=$query->num_rows(); 
############ 
$data=$results['0'];
$resultsdata=$data;
###########DB SQL query End ###########
            // Lets store the results
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
                         #'info'=>$cacheinfo
                         );
}
##########*******memcache*******############
return $resultsdata;
#return $dataall;
}

public function get_searchall_count($keyword,$level_id,$category_id,$searchtype,$deletekey){   
if($keyword=='ONET' ||$keyword=='onet' ||$keyword=='o-net'||$keyword=='o_net'||$keyword=='Onet'|| $keyword=='O-Net'){$keyword='O-NET';} 
##########*******memcache*******############
$search=',';
$replace='-';
$string=$idin;
$schoolset=str_replace($search,$replace,$string);
$cachekey='key-count-cvscourseexamination-keyword-count1-'.$keyword.'-level_id-'.$level_id.'-category_id-'.$category_id.'-searchtype-'.$searchtype;
##############
//Load library
$this->load->library('Memcached_library');
#$cachetime='300';
$cachetime=60*60*4;
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
###########DB SQL query Start###########
$sql.="select count(dc.id)as coun_rows
from cvs_course_examination as dc 
inner join cvs_course_exam_share as ces on ces.exam_id=dc.id
where dc.exam_status='yes'  and ces.exam_status='shared'
";
if($searchtype=="member_id"){
$sql .=" and dc.member_id LIKE %$keyword% "; 
}else if($searchtype=="exam_code"){
$sql .=" and dc.exam_code LIKE %$keyword% "; 
}else if($searchtype=="question_text"){
$sql .=" and dc.id in (select exam_id from cvs_course_question where question_detail like %$keyword%)"; 
}else if($keyword!=null){
$sql.=" and (dc.exam_name like '%$keyword%' or dc.exam_code like '%$keyword%')";   
}
if($level_id!=null){
###########################
if($level_id==10){
$sql.=" and (dc.mul_level_id=10 or dc.mul_level_id=11 or dc.mul_level_id=12 or dc.mul_level_id=13)";   
}elseif($level_id==20){
$sql.=" and (dc.mul_level_id=20 or dc.mul_level_id=21 or dc.mul_level_id=22 or dc.mul_level_id=23)";   
}elseif($level_id==30){
$sql.=" and (dc.mul_level_id=30 or dc.mul_level_id=31 or dc.mul_level_id=32 or dc.mul_level_id=33)";   
}elseif($level_id==40){
$sql.=" and (dc.mul_level_id=40 or dc.mul_level_id=41 or dc.mul_level_id=42 or dc.mul_level_id=43)";   
}else{
   $sql.=" and dc.mul_level_id=$level_id";   
}  
###########################
}if($category_id!=null){
$sql.=" and dc.mul_root_id=$category_id ";   
}
$query=$this->db->query($sql);
$results=$query->result();
$row=$query->num_rows(); 
############ 
$data=$results['0'];
$resultsdata=$data;
###########DB SQL query End ###########
            // Lets store the results
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
                         #'info'=>$cacheinfo
                         );
}
##########*******memcache*******############
return $resultsdata;
#return $dataall;
}
public function get_searchall($keyword,$level_id,$category_id,$perpage,$page,$searchtype,$deletekey){    
if($keyword=='ONET' ||$keyword=='onet' ||$keyword=='o-net'||$keyword=='o_net'||$keyword=='Onet'|| $keyword=='O-Net'){$keyword='O-NET';} 
if($perpage==''){$perpage=10;}
####################################
##########*******memcache*******############
$search=',';
$replace='-';
$string=$idin;
$schoolset=str_replace($search,$replace,$string);
$cachekey='key-cvscourseexaminationkeyword-'.$keyword.'-level_id-'.$level_id.'-category_id-'.$category_id.'-page-'.$page;
##############
//Load library
$this->load->library('Memcached_library');
#$cachetime='300';
$cachetime=60*60*4;
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
###########DB SQL query Start###########
$start=($page-1)*$perpage;
$sql.="select distinct dc.id as content_id,dc.*,ces.exam_status as public_status
,(select count(id) from cvs_course_question cc where cc.exam_id=dc.id) question_count
,(select mul_level_name from mul_level where mul_level_id=dc.mul_level_id) level_name
,(select mul_category_name from mul_category_2014 where mul_category_id=dc.mul_root_id) subject_name
,dc.view_count as views
from cvs_course_examination as dc 
inner join cvs_course_exam_share as ces on ces.exam_id=dc.id
where dc.exam_status='yes'  and ces.exam_status='shared' ";
if($searchtype=="member_id"){
$sql .=" and dc.member_id LIKE %$keyword% "; 
}else if($searchtype=="exam_code"){
$sql .=" and dc.exam_code LIKE %$keyword% "; 
}else if($searchtype=="question_text"){
$sql .=" and dc.id in (select exam_id from cvs_course_question where question_detail like %$keyword%)"; 
}else if($keyword!=null){
$sql.=" and (dc.exam_name like '%$keyword%' or dc.exam_code like '%$keyword%')";   
}
if($level_id!=null){
###########################
if($level_id==10){
$sql.=" and (dc.mul_level_id=10 or dc.mul_level_id=11 or dc.mul_level_id=12 or dc.mul_level_id=13)";   
}elseif($level_id==20){
$sql.=" and (dc.mul_level_id=20 or dc.mul_level_id=21 or dc.mul_level_id=22 or dc.mul_level_id=23)";   
}elseif($level_id==30){
$sql.=" and (dc.mul_level_id=30 or dc.mul_level_id=31 or dc.mul_level_id=32 or dc.mul_level_id=33)";   
}elseif($level_id==40){
$sql.=" and (dc.mul_level_id=40 or dc.mul_level_id=41 or dc.mul_level_id=42 or dc.mul_level_id=43)";   
}else{
   $sql.=" and dc.mul_level_id=$level_id";   
}  
###########################
}if($category_id!=null){
$sql.=" and dc.mul_root_id=$category_id ";   
}
$sql.=" order by dc.exam_add_date desc limit $start,$perpage";
$query=$this->db->query($sql);
$resultsdata=$query->result();
$row=$query->num_rows(); 	
###########DB SQL query End ###########
            // Lets store the results
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
#################################### 
return $dataall;
#return $resultsdata;
}

public function get_views_user($content_id){    
#################################### 
###########DB SQL query Start###########
$sql.="select view as views_user from cvs_stat_all  where category_id=2 ";   
$sql.=" and content_id=$content_id";
$query=$this->db->query($sql);
$resultsdata=$query->result();
$row=$query->num_rows(); 
############ 
###########DB SQL query End ###########
return $resultsdata['0'];
}
public function get_views_catgory($limit){    
#################################### 
###########DB SQL query Start###########
$sql.="select 
x.id,exam_name
,s.view
from cvs_course_examination x
inner join cvs_stat_all s on x.id=s.content_id and category_id=2
limit $limit";   
$sql.=" and content_id=$content_id";
$query=$this->db->query($sql);
$resultsdata=$query->result();
$row=$query->num_rows(); 
############ 
###########DB SQL query End ###########
return $resultsdata['0'];
}
public function get_course_answer_true($setansuser){    
#################################### 
###########DB SQL query Start###########
$sql.="SELECT COUNT(*) cnt FROM cvs_course_answer ";   
$sql.="WHERE id IN($setansuser) AND answer_ans='true' ";
$query=$this->db->query($sql);
$resultsdata=$query->result();
$row=$query->num_rows(); 
############ 
###########DB SQL query End ###########
return $resultsdata['0'];
}
public function get_examid($exam_id,$deletekey){    
##########*******memcache*******############
$cachekey='key-get-exam-id-'.$exam_id;
//Load library
$this->load->library('Memcached_library');
$cachetime='300';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
###########DB SQL query Start###########
/*
$sql.="select dc.*, ces.exam_status public_status
,(select count(id) from cvs_course_question cc where cc.exam_id=dc.id) question_count
,(select mul_level_name from mul_level where mul_level_id=dc.mul_level_id) level_name
,(select mul_category_name from mul_category_2014 where mul_category_id=dc.mul_root_id) subject_name
,stat.view as views
from cvs_course_examination dc 
inner join cvs_course_exam_share ces on ces.exam_id=dc.id
inner join cvs_stat_all stat on stat.content_id=dc.id 
where stat.category_id=2 and dc.id=$exam_id ";  
$sql.=" group by dc.id";  

*/

$sql.="select distinct dc.id as content_id,dc.*,ces.exam_status as public_status
,(select count(id) from cvs_course_question cc where cc.exam_id=dc.id) question_count
,(select mul_level_name from mul_level where mul_level_id=dc.mul_level_id) level_name
,(select mul_category_name from mul_category_2014 where mul_category_id=dc.mul_root_id) subject_name
,dc.view_count as views
from cvs_course_examination dc 
inner join cvs_course_exam_share ces on ces.exam_id=dc.id
inner join cvs_stat_all stat on stat.content_id=dc.id 
where  dc.id=$exam_id ";  
$sql.=" group by dc.id";  

$query=$this->db->query($sql);
$resultsdata=$query->result();
$row=$query->num_rows(); 
###########DB SQL query End ###########
            // Lets store the results
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
                         #'info'=>$cacheinfo
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
}
public function get_searchrootid($keyword,$level_id,$category_id,$perpage,$page,$searchtype,$deletekey){ 
if($perpage==''){$perpage=100;}
####################################
##########*******memcache*******############
$search=',';
$replace='-';
$string=$idin;
$schoolset=str_replace($search,$replace,$string);
$cachekey='key-cvscourseexaminationkeyword-byrootid-'.$keyword.'-level_id-'.$level_id.'-category_id-'.$category_id.'-page-'.$page;
##############
//Load library
$this->load->library('Memcached_library');
$cachetime='60';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
###########DB SQL query Start###########
$start=($page-1)*$perpage;
/*
$sql.="select distinct dc.mul_root_id
,(select mul_category_name from mul_category_2017 where mul_category_id=dc.mul_root_id) subject_name
from cvs_course_examination dc 
inner join cvs_course_exam_share ces on ces.exam_id=dc.id
inner join cvs_stat_all stat on stat.content_id=dc.id 
where dc.mul_root_id!='' and stat.category_id=2
";
*/
$sql.="select distinct dc.mul_root_id
,(select mul_category_name from mul_category_2017 where mul_category_id=dc.mul_root_id) subject_name
from cvs_course_examination dc 
inner join cvs_course_exam_share ces on ces.exam_id=dc.id
where dc.mul_root_id!='' ";
if($searchtype=="member_id"){
$sql.=" and dc.member_id LIKE %$keyword% "; 
}else if($searchtype=="exam_code"){
$sql.=" and dc.exam_code LIKE %$keyword% "; 
}else if($searchtype=="question_text"){
$sql.=" and dc.id in (select exam_id from cvs_course_question where question_detail like %$keyword%)"; 
}else if($keyword!=null){
$sql.=" and dc.exam_name like '%$keyword%'";   
}
if($level_id!=null){
$sql.=" and dc.mul_level_id=$level_id";   
}if($category_id!=null){
$sql.=" and dc.mul_root_id=$category_id ";   
}
$sql.=" limit $start,$perpage";
$query=$this->db->query($sql);
$resultsdata=$query->result();
$row=$query->num_rows(); 	
###########DB SQL query End ###########
            // Lets store the results
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
                         #'info'=>$cacheinfo
                         );
}
#################################### 
#return $dataall;
return $resultsdata;
}
public function get_searchall_root_id($keyword,$level_id,$category_id,$per,$page,$searchtype,$deletekey){   $perpage=$per;
if($perpage==''){$perpage=100;}
####################################
##########*******memcache*******############
$search=',';
$replace='-';
$string=$idin;
$schoolset=str_replace($search,$replace,$string);
$cachekey='key-cvscourseexamination-keyword-'.$keyword.'-level_id-'.$level_id.'-category_id-'.$category_id.'-page-'.$page;
##############
//Load library
$this->load->library('Memcached_library');
$cachetime='60';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
###########DB SQL query Start###########
$start=($page-1)*$perpage;
/*
$sql.="select dc.*, ces.exam_status public_status
,(select count(id) from cvs_course_question cc where cc.exam_id=dc.id) question_count
,(select mul_level_name from mul_level where mul_level_id=dc.mul_level_id) level_name
,(select mul_category_name from mul_category_2014 where mul_category_id=dc.mul_root_id) subject_name
,stat.view as views
from cvs_course_examination dc 
inner join cvs_course_exam_share ces on ces.exam_id=dc.id
inner join cvs_stat_all stat on stat.content_id=dc.id 
where stat.category_id=2
";
*/
$sql.="select distinct dc.id as content_id,dc.*
,ces.exam_status as public_status
,(select count(id) from cvs_course_question cc where cc.exam_id=dc.id) question_count
,(select mul_level_name from mul_level where mul_level_id=dc.mul_level_id) level_name
,(select mul_category_name from mul_category_2014 where mul_category_id=dc.mul_root_id) subject_name
,dc.view_count as views
from cvs_course_examination as dc 
inner join cvs_course_exam_share as ces on ces.exam_id=dc.id
where dc.id!=''
";
if($searchtype=="member_id"){
$sql .=" and dc.member_id LIKE %$keyword% "; 
}else if($searchtype=="exam_code"){
$sql .=" and dc.exam_code LIKE %$keyword% "; 
}else if($searchtype=="question_text"){
$sql .=" and dc.id in (select exam_id from cvs_course_question where question_detail like %$keyword%)"; 
}else if($keyword!=null){
$sql.=" and dc.exam_name like '%$keyword%'";   
}
if($level_id!=null){
$sql.=" and dc.mul_level_id=$level_id";   
}if($category_id!=null){
$sql.=" and dc.mul_root_id=$category_id ";   
}
$sql.=" limit $start,$perpage";
$query=$this->db->query($sql);
$resultsdata=$query->result();
$row=$query->num_rows(); 	
###########DB SQL query End ###########
            // Lets store the results
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
                         #'info'=>$cacheinfo
                         );
}
#################################### 
#return $dataall;
return $resultsdata;
}
public function where_cvs_course_exam_score_user_id($exam_id,$user_id,$limit,$orderby,$log_id,$deletekey){

if($orderby==''){ $orderby='desc';}
if($orderby=='asc'){ $orderby='asc';}
if($orderby=='dsc' || $orderby=='desc'){ $orderby='desc';}else{ $orderby='desc';}

if($user_id==''){
$sql.="select exam.id as exam_id,exam.exam_name,logscore.*
from cvs_course_exam_score as logscore 
inner join cvs_course_examination as exam on logscore.exam_id=exam.id
where logscore.exam_id=$exam_id ";
}else{
$sql.="select exam.id as exam_id,exam.exam_name
,users.user_username,users.psn_firstname as firstname,users.psn_lastname as lastname,logscore.*
from cvs_course_exam_score as logscore 
inner join cvs_course_examination as exam on logscore.exam_id=exam.id
inner join users_account as users on users.user_id=logscore.user_id
where logscore.exam_id=$exam_id ";
}
if($user_id=='' || $user_id==0){}else{$sql.=" and logscore.user_id=$user_id";}
if($log_id==''|| $log_id==0){}else{$sql.=" and logscore.id=$log_id "; }
if($orderby==''){$sql.=" order by logscore.id desc "; }else{$sql.=" order by logscore.id $orderby";}
if($limit!=''){$sql.=" limit $limit ";}else{$sql.=" limit 1";}

/*
echo '<pre>exam_id=>';print_r($exam_id);echo '</pre>';  
echo '<pre>user_id=>';print_r($user_id);echo '</pre>'; 
echo '<pre>limit=>';print_r($limit);echo '</pre>';  
echo '<pre>orderby=>';print_r($orderby);echo '</pre>';  
echo '<pre>log_id=>';print_r($log_id);echo '</pre>';  
echo '<pre>deletekey=>';print_r($deletekey);echo '</pre>'; 
echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
*/
$this->load->library('Memcached_library');
$cachetime='600';
 $cachekey="key-where-cvs-course-exam".$exam_id."-score-user_id-".$user_id.'-log_id-'.$log_id.'-limit-'.$limit.'-orderby-'.$orderby;
#################################### 
##########*******memcache*******############
$resultsdata=$this->memcached_library->get($cachekey);
/*
echo '<pre>exam_id=>';print_r($exam_id);echo '</pre>';  
echo '<pre>user_id=>';print_r($user_id);echo '</pre>'; 
echo '<pre>limit=>';print_r($limit);echo '</pre>';  
echo '<pre>orderby=>';print_r($orderby);echo '</pre>';  
echo '<pre>log_id=>';print_r($log_id);echo '</pre>';  
echo '<pre>deletekey=>';print_r($deletekey);echo '</pre>'; 
echo '<pre>sql=>';print_r($sql);echo '</pre>'; 
echo '<pre>memcache resultsdata=>';print_r($resultsdata);echo '</pre>';  Die();
*/

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
          $insert_id = $this->db->insert_id();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'id'=>$insert_id,
                         'log_id'=>$insert_id,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
return $resultsdata;
}
public function cvs_course_answer_count_score($answer_set,$deletekey){
$sql="SELECT COUNT(*) cnt FROM cvs_course_answer WHERE id IN($answer_set) AND answer_ans='true'";
$query=$this->db->query($sql);
$results=$query->result();
$resultsdata=$results['0'];
$total_answer_true=$resultsdata;
$cnt=$total_answer_true->cnt;
$this->load->library('Memcached_library');
$cachetime='300';
#################################### 
$search=',';
$replace='-';
$string=$idin;
$set=str_replace($search,$replace,$answer_set);
$cachekey="key-cvs-course-answer-count-score-".$set;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function users_account_id($user_id,$deletekey){
$sql="select user.user_username as username,user.psn_display_name
,user.psn_firstname as firstname,user.psn_lastname as lastname,user.psn_sex as sex_id
,COALESCE(NULLIF(concat(user.psn_firstname,' ',user.psn_lastname),''),user.psn_display_name) as user_fullname
,case user.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,user.psn_display_image,user.user_email as email
,user.job_edu_name,user.job_edu_level
from users_account as user where user.user_id=$user_id";
$query=$this->db->query($sql);
$results=$query->result();
$resultsdata=$results['0'];
$total_answer_true=$resultsdata;
$cnt=$total_answer_true->cnt;
$this->load->library('Memcached_library');
$cachetime='300';
#################################### 
/*
$search=',';$replace='-';$string=$idin;
$set=str_replace($search,$replace,$answer_set);
*/
$cachekey="key-users-account-id-".$user_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
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
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function ViewNumberGet($content_id,$table,$cachetime,$deletekey){ 
if($content_id==''){$content_id=0;}
if($table==''){$table='cvs_course_examination';}
if($cachetime==''){$cachetime=300;}
if($deletekey==''){$deletekey='';}
###############################################  
$this->load->library('Memcached_library');  
if($table=='mul_content'){ 
$field_name='mul_content_id'; $view_count_field_name='view_count';
}elseif($table=='mul_source'){ 
 $field_name='mul_source'; $view_count_field_name='view_count';
}elseif($table=='cms'){ 
$field_name='cms_id'; $view_count_field_name='view_count';
}elseif($table=='cmsblog_detail'){ 
$field_name='idx'; $view_count_field_name='view_count';
}elseif($table=='tv_program'){ 
$field_name='content_id';$view_count_field_name='view_count';
}elseif($table=='tv_program_episode'){ 
$field_name='content_child_id'; $view_count_field_name='view_count';
}elseif($table=='ams_news_directapply'){ 
$field_name='news_id';  $view_count_field_name='news_view';
}elseif($table=='webboard_post'){ 
$field_name='wb_post_id';  $view_count_field_name='view_count';  $last_update_date ='last_update_date';
}elseif($table=='ams_news_camp'){ 
$field_name='camp_id';  $view_count_field_name='viewcount'; 
}elseif($table=='lesson_plan'){ 
$field_name='lesson_plan_id';  $view_count_field_name='view_count';  
}elseif($table=='cvs_game_quiz_main'){ 
$field_name='id';  $view_count_field_name='view'; 
}elseif($table=='cvs_course_examination'){ 
$field_name='id';  $view_count_field_name='view_count'; 
}
##############################################
$this->load->library('Memcached_library');  
$value=$content_id;
$cachekey="vc_".$table."+".$field_name."+".$content_id;
$key=$cachekey;
$increment_by=1;
###################################
if($deletekey==1){
     $this->memcached_library->delete($cachekey);
}
###################################
$cacheget=$this->memcached_library->get($cachekey);
$sql="select * from $table  where  $field_name=$content_id limit 1";
if(!$cacheget){
###########DB SQL query Start###########
$querys=$this->db->query($sql);
$resultsdatas=$querys->result();
$resultsdatas=$resultsdatas['0'];
$viewold=$resultsdatas->view_count;
 $this->memcached_library->add($cachekey,$viewold,$cachetime);
//$this->memcached_library->set($cachekey,$viewold,$cachetime);
###########DB SQL query Start###########
 $fromstaus='form data base mysql';
 $view_cache='';
}else{
 $viewold=$cacheget;
 $fromstaus='form memcache';
 $view_cache=$viewold;
}
################################

/*
$use_session=true;
if($use_session){ 
$this->load->library('session');
if($deletekey==1){$this->session->unset_userdata($cachekey);}
if($use_session){
     $sessionkey=$this->session->userdata($cachekey);
if(!$sessionkey){
          $this->session->set_userdata($cachekey,'1');
          $content_view_count=$this->memcached_library->increment($cachekey,$increment_by);
          }
}else{
          $content_view_count=$this->memcached_library->increment($key,$increment_by);
}
}if($content_view_count==''){ $content_view_count=0;}

*/

$sessionkey='';
$content_view_count=$this->memcached_library->increment($cachekey,$increment_by);


if(($content_view_count<10 || $content_view_count %29==0 || $use_session)){
###########DB SQL query Start###########
$sql2="select * from $table  where  $field_name=$content_id and view_count < $content_view_count";
     $querys=$this->db->query($sql2);
     $resultsdatas=$querys->result();
     $resultsdatas=$resultsdatas['0'];
     $viewold=$resultsdatas->view_count;
     if($resultsdatas!=''){
          $data=array('view_count'=>$contentviewcount);
          $this->db->where($field_name,$value);
          $this->db->update($table,$data);
     }
}
$view_count=$contentviewcount;
##############################################
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
$datainfo=array('view_count'=>$view_count,
               'view_cache'=>$view_cache,
               'view_key'=>$cachekey,
               'cacheget'=>$cacheget,
               'from'=>$fromstaus,
               'table'=>$table,
               'field_name'=>$field_name,
               'field_name2'=>$view_count_field_name,
               'field_valule'=>$content_id,
               'cachetime'=>$cachetime,
               'sessionkey'=>$sessionkey,
               'sql'=>$sql,
               'sql2'=>$sql2,
               'content_view_count'=>$content_view_count,
               'info_cache'=>$cacheinfo);
return $datainfo;
}
###examination End##########
 
##Upskill
// $comma_separated = implode(",", $array);
// $comma_separated = explode(",", $array);
public function where_count_lesson_mul_map_exam_question_lesson_arrayexam_id($examarrayid,$deletekey){

$sql="select distinct map_eql.lesson_id 
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map_eql.lesson_id)as count_lesson
,(select lesson_name from mul_lesson where lesson_id=map_eql.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
,(select name from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as book_name
,(select mul_level_id from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_id
,(select mul_category_id from mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_id
,(select mul_education_book_id from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as mul_education_book_id
,(select id from  cvs_course_examination where id=exam.id)as exam_id
from  mul_map_exam_question_lesson as map_eql   
left join mul_lesson as lesson on lesson.lesson_id=map_eql.lesson_id
left join cvs_course_question as question on question.id=map_eql.question_id
left join cvs_course_examination as exam on exam.id=question.exam_id
where exam.id in($examarrayid) group by map_eql.lesson_id  order by exam.id asc";
#echo '<pre>  $sql=>';print_r($sql); echo '<pre>'; 
#$cache_key = "where_count_lesson_mul_map_exam_question_lesson_exam_id_".$sql;
$search=',';$replace='-';$string=$examarrayid;
$examarrayidar=str_replace($search,$replace,$string);
$cachekey="where-count-lesson-mul-map-exam-question-lesson-exam-id-".$examarrayidar;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
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
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
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
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function where_cvs_course_exam_score_member_in($exam_id_in,$user_id,$order,$limit,$log_id_in,$deletekey){
if($log_id_in==null){$log_id_in=null;}
if($exam_id_in==null){ return null; die(); }
/*
echo'<hr><pre> exam_id_in=>';print_r($exam_id_in);echo'<pre>';  
echo'<pre> user_id=>';print_r($user_id);echo'<pre>';  
echo'<pre> order=>';print_r($order);echo'<pre>';
echo'<pre> log_id_in=>';print_r($log_id_in);echo'<pre>';
echo'<pre> limit=>';print_r($limit);echo'<pre>';
echo'<pre> deletekey=>';print_r($deletekey);echo'<pre><hr>';  //Die();
*/
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
if($user_id==Null){
#################################### 
if($order=='asc'){
$sql.="select distinct score.*
,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total
,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  
where score.exam_id in($exam_id_in)";
if($log_id_in==''){}else{$sql.=" and score.id in($log_id_in) ";}
$sql.=" order by score.id asc";
#$sql.=" group by  score.exam_id";
$sql.=" limit $limit";
}elseif($order=='desc' || $order==null){
$sql.="select distinct score.*
,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  
where score.exam_id in($exam_id_in)"; 
if($log_id_in==''){}else{$sql.=" and score.id in($log_id_in) ";}
$sql.=" order by score.id desc";
#$sql.=" group by  score.exam_id";
$sql.=" limit $limit";
}
#################################### 
}
else{
#################################### 
if($order=='asc'){
$sql.="select distinct score.*
,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total
,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
,(select exam_name from cvs_course_examination where id=score.exam_id)as exam_name
from cvs_course_exam_score as score  
where score.exam_id in($exam_id_in) and score.user_id=$user_id ";
if($log_id_in==''){}else{$sql.=" and score.id in($log_id_in)";}
//$sql.=" order by score.id asc";
$sql.=" order by score.exam_id,score.id asc";
#$sql.=" group by  score.exam_id";
$sql.=" limit $limit";

}elseif($order=='desc' || $order==null){
$sql.="select distinct score.*
,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total
,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
,(select exam_name from cvs_course_examination where id=score.exam_id)as exam_name
from cvs_course_exam_score as score  
where score.exam_id in($exam_id_in) and score.user_id=$user_id ";
if($log_id_in==''){}else{$sql.=" and score.id in($log_id_in)";}
//$sql.=" order by score.id desc ";
#$sql.=" order by score.id desc";
$sql.=" order by score.exam_id,score.id desc";
#$sql.=" group by  score.exam_id";
$sql.=" limit $limit";
}
#################################### 
}

$search=',';$replace='-';$string=$exam_id_in;
$examidin=str_replace($search,$replace,$string);
if($log_id_in==''){
     $cachekey="key-cvs-course-exam-score-exam_id-in-".$examidin.'-userid-'.$user_id;
}else{
     $cachekey="key-cvs-course-exam-score-exam_id-in-".$examidin.'-userid-'.$user_id.'-log_id_in-'.$log_id_in;   
}
//echo '<pre>sql=>';print_r($sql);echo '</pre>';   
//echo '<pre>cachekey=>';print_r($cachekey);echo '</pre>';  Die();

##########*******memcache*******############
############cache##################
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

public function where_question_total_exam_id_in($exam_id_in,$cachetype,$deletekey){
if($exam_id_in==null){
     return null; die();
}

if($log_id_in==null){$log_id_in=null;}
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
$sql="select count(question.id) as question_total
from cvs_course_question as question 
where question.exam_id in ($exam_id_in) ";
$search=',';$replace='-';$string=$exam_id_in;
$examidin=str_replace($search,$replace,$string);
$cachekey="key-question-total-exam-id-in-".$examidin;   
##########*******memcache*******############
############cache##################
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
public function where_exam_sumscore_member_in($exam_id_in,$user_id,$cachetype,$deletekey){
     if($exam_id_in==null){ return null; die(); }
if($log_id_in==null){$log_id_in=null;}
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
$sql.="select sum(score.score_value) as sum_score 
from cvs_course_exam_score as score 
where score.exam_id in ($exam_id_in) ";
$sql.=" and score.user_id=$user_id";

$search=',';$replace='-';$string=$exam_id_in;
$examidin=str_replace($search,$replace,$string);
$cachekey="key-sum-score-exam_id-in-".$examidin.'-userid-'.$user_id;   
##########*******memcache*******############
############cache##################
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
                         'sql'=>$sql,
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
public function exam_lesson_log_user_in($exam_id_in,$log_id_in,$user_id,$cachetype,$deletekey){
if($cachetype==null){$cachetype=4;}
$sql.="select distinct map_eql.lesson_id 
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map_eql.lesson_id)as count_lesson
,(select lesson_name from mul_lesson where lesson_id=map_eql.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
,(select name from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as book_name
,(select mul_level_id from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_id
,(select mul_category_id from mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_id
,(select mul_education_book_id from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as mul_education_book_id
,(select id from  cvs_course_examination where id=exam.id)as exam_id
,(select count(id)from cvs_course_question where exam_id=exam.id)as count_question
,score.score_value
from  mul_map_exam_question_lesson as map_eql   
left join mul_lesson as lesson on lesson.lesson_id=map_eql.lesson_id
left join cvs_course_question as question on question.id=map_eql.question_id
left join cvs_course_examination as exam on exam.id=question.exam_id
left join  cvs_course_exam_score as score on score.exam_id=exam.id
where exam.id in($exam_id_in) 
and score.id in($log_id_in) and score.user_id=$user_id ";
$sql.=" order by exam.id asc ";
$sql.=" group by map_eql.lesson_id ";
$cachetime='3600';
############cachekey###############
$search=',';$replace='-';$string=$log_id_in;
$logidin=str_replace($search,$replace,$string);
$search2=',';$replace2='-';$string2=$exam_id_in;
$examidin=str_replace($search2,$replace2,$string2);
$cachekey="key-exam-$examidin-lesson-in-log-$logidin-user-$user_id"; 
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
###################
public function questionmaplesson($exam_id,$deletekey){
$sql="select * from mul_map_exam_question_lesson as map left join cvs_course_question as q on q.id=map.question_id where q.exam_id=$exam_id";
/*
$search=',';$replace='-';$string=$datain;
$adata=str_replace($search,$replace,$string);
*/
############cache##################
if($deletekey==null){$deletekey=null;}
$cachekey="key-question-map-lesson-".$exam_id;   
$cachetime=(int)'600';
$cachetype=(int)'4';
###################################
/*
echo 'sql=>'.$sql; 
echo '<br>cachekey=>'.$cachekey; 
echo '<br>cachetime=>'.$cachetime; 
echo '<br>cachetype=>'.$cachetype; 
echo '<br>deletekey=>'.$deletekey; //Die();
*/
###################################
$datacachedb=$this->cachedb($sql,$cachekey,$cachetime,$cachetype,$deletekey);
//echo '<pre>datacachedb=>';print_r($datacachedb);echo'</pre>'; Die();
return $datacachedb;
############cache##################
}
public function where_exam_score_member_in_desc($examidin,$userid,$deletekey){
if($examidin==null){ return null; die(); }
if($userid==null){ return null; die(); }
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
$sql.="select score.exam_id,score.score_value as score_value_user
,(select count(*) from cvs_course_question where exam_id=score.exam_id)as score_exam_all
,score.user_id
,(select exam_name from cvs_course_examination where id=score.exam_id)as exam_name
from cvs_course_exam_score as score  
where score.exam_id in($examidin) and score.user_id=$userid
order by score.id desc limit 1";
$search=',';$replace='-';$string=$examidin;
$examidin=str_replace($search,$replace,$string);
$cachekey="key-exam-score-member-in-desc-".$examidin.'-userid-'.$user_id;
##########*******memcache*******############
############cache##################
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

}
?>