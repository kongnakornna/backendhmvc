<?php
class Model_dictionary extends CI_Model{
#####################
public function __construct(){
parent::__construct();
#$this->load->database('api'); // load database name api
}
public function search_all_distinct($word,$typesearch='like',$deletekey,$cachetype){
/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL
*/
if($cachetype==''){$cachetype=4;}
if($typesearch=='like'){
$sql="select distinct word from dictionary where word=$word";
############cachekey##################
$cachekey="key-dictionary-word-search-all-distinct-".$word;
}else{
$sql="select distinct word from dictionary where word like '".$word."%' order by word ASC ";
############cachekey##################
$cachekey="key-dictionary-word-search-all-distinct-like-".$word;
}
############cachetime##################
$cachetime='600';

// Lets try to get the key
############cache deletekey##################
if($deletekey==''){$deletekey='';}
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
                         'sql'=>$sql,
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
                         'sql'=>$sql,
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
               'sql'=>$sql,
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
##########*******memcache*******############
//Load library
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
                         'sql'=>$sql,
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
                         'sql'=>$sql,
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
               'sql'=>$sql,
			'list'=>$dataresult,
               'count'=>(int)count($dataresult),
               );
/* ############# 4-->DB SQL End##############  */  
return $dataall;  
}
############cache##################
}
public function search_count($word,$deletekey,$cachetype){
/*
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL
*/
if($cachetype==''){$cachetype=4;}
$sql="select count(word_id) as count_rows from dictionary where word like '$word%'";
############cachekey##################
$cachekey="key-dictionary-word-".$word;
############cachetime##################
$cachetime='600';
// Lets try to get the key
############cache deletekey##################
if($deletekey==''){$deletekey='';}
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
                         'sql'=>$sql,
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
                         'sql'=>$sql,
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
               'sql'=>$sql,
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
##########*******memcache*******############
//Load library
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
                         'sql'=>$sql,
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
                         'sql'=>$sql,
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
               'sql'=>$sql,
			'list'=>$dataresult,
               'count'=>(int)count($dataresult),
               );
/* ############# 4-->DB SQL End##############  */  
return $dataall;  
}
############cache##################
}
public function search_word($word,$page,$perpage,$sort_by,$deletekey,$cachetype){
$cachetype=(int)$cachetype;
$perpage=(int)$perpage;
$page=(int)$page;
if($cachetype==''){$cachetype=4;}
if($perpage==''){$perpage=10;}
if($sort_by==''||$sort_by=='asc'){$sort_by='asc';}else{$sort_by='desc';}
/*
//$word,$page,$perpage,$sort_by,$deletekey,$cachetype
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL
*/
if($cachetype==''){$cachetype=4;}
$rec_limit=$perpage;
$searchcount=$this->search_count($word,$deletekey,$cachetype);
$search_counts=$searchcount['list'];
$search_count=$search_counts['0'];
$searchcount=$search_count->count_rows;
$num_rows=$searchcount;
$rec_count=$num_rows;

$totalpage=$rec_count/$perpage;
$total_page=round($totalpage,0);
if($total_page<1){$total_page=1;} 
if(isset($page)){$page =$page;}else{$page=1;}
if($page>=$total_page){$page=$total_page;}
if($page>1){
$start=($page-1)* $perpage;     
}else{
$start=0;
}
if(($total_page<=1)||($total_page<=$page)){ $start=0;}
if($word==''){
$sql="select * from dictionary order by word $sort_by limit $start,$perpage"; 
############cachekey##################
$cachekey="key-dictionary-word-".$word.'-sort-by-'.$sort_by;
}
else{
$sql="select * from dictionary where word like '$word%'  order by word $sort_by limit $start, $perpage";  
############cachekey##################
$cachekey="key-dictionary-word-".$word.'-page-'.$page.'-end-'.$perpage.'-sort-by-'.$sort_by;
}
/*
echo '<pre> total_page-> '; print_r($total_page); echo '</pre>';
echo '<pre> Page-> '; print_r($page); echo '</pre>';
echo '<pre> Page2-> '; print_r($page2); echo '</pre>';
echo '<pre> num_rows-> '; print_r($rec_limit); echo '</pre>';
echo '<pre> offset-> '; print_r($offset); echo '</pre>';
echo '<pre> rec_count-> '; print_r($rec_count); echo '</pre>';
echo '<pre> sql-> '; print_r($sql); echo '</pre>'; 
echo '<pre> cachekey-> '; print_r($cachekey); echo '</pre>';die(); 
*/
############cachetime##################
$cachetime='300';
// Lets try to get the key
############cache deletekey##################
if($deletekey==''){$deletekey='';}
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
                         'cachetime'=>$cachetime,
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
                         'sql'=>$sql,
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'cachetime'=>(int)$cachetime,
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
                         'sql'=>$sql,
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'cachetime'=>(int)$cachetime,
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
               'sql'=>$sql,
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
##########*******memcache*******############
//Load library
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
if($resultsdata==''){
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
                         'sql'=>$sql,
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'cachetime'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         #'info'=>$cacheinfo
                         );
     #echo '<pre> dataall-> '; print_r($dataall); echo '</pre>'; die(); 
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
                         'sql'=>$sql,
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'cachetime'=>(int)$cachetime,
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
               'sql'=>$sql,
			'list'=>$dataresult,
               'count'=>(int)count($dataresult),
               );
/* ############# 4-->DB SQL End##############  */  
return $dataall;  
}
############cache##################
}
public function autosearch_word($word,$page,$perpage,$sort_by,$deletekey,$cachetype){
if($cachetype==''){$cachetype=4;}
if($page==''){$page=0;}
$page2=$page;
if($perpage==''){$perpage=10;}
if($sort_by==''||$sort_by=='asc'){
     $sort_by='asc';
}else{
     $sort_by='desc';
}

/*
//$word,$page,$perpage,$sort_by,$deletekey,$cachetype
1-->cachedb
2-->cachefile
3-->Redis
4-->Memory memcached
5-->Data base SQL
*/
if($cachetype==''){$cachetype=4;}
$rec_limit=$perpage;
$searchcount=$this->search_count($word,$deletekey,$cachetype);
$search_counts=$searchcount['list'];
$search_count=$search_counts['0'];
$searchcount=$search_count->count_rows;
$num_rows=$searchcount;
$rec_count=$num_rows;
$totalpage=$rec_count/$perpage;
$total_page=round($totalpage, 0); 

if($page2!==''){$page=$page2+1;$offset=$rec_limit*$page;}else{$page=0;$offset=0;}
$left_rec=$rec_count-($page * $rec_limit);
if($searchcount<=$perpage){$offset=0;  }
if($page2==''||$page2==0||$page2==1){$offset=0;  }
if($page2==$num_rows){$offset=0;   }
if($word==''){
$sql="select word from dictionary order by word $sort_by limit $offset,$rec_limit"; 
############cachekey##################
$cachekey="key-dictionary-autoword-".$word.'-sort-by-'.$sort_by;
}
else{
$sql="select word from dictionary where word like '$word%'  order by word $sort_by limit $offset, $rec_limit";  
############cachekey##################
$cachekey="key-dictionary-autoword-".$word.'-page-'.$page2.'-end-'.$perpage.'-sort-by-'.$sort_by;
}
/*
echo '<pre> totalpage-> '; print_r($totalpage); echo '</pre>';
echo '<pre> Page-> '; print_r($page); echo '</pre>';
echo '<pre> Page2-> '; print_r($page2); echo '</pre>';
echo '<pre> num_rows-> '; print_r($rec_limit); echo '</pre>';
echo '<pre> offset-> '; print_r($offset); echo '</pre>';
echo '<pre> rec_count-> '; print_r($rec_count); echo '</pre>';
echo '<pre> sql-> '; print_r($sql); echo '</pre>'; 
echo '<pre> cachekey-> '; print_r($cachekey); echo '</pre>';die(); 
*/
############cachetime##################
$cachetime='300';
// Lets try to get the key
############cache deletekey##################
if($deletekey==''){$deletekey='';}
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
                         'cachetime'=>$cachetime,
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
                         'sql'=>$sql,
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'cachetime'=>(int)$cachetime,
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
                         'sql'=>$sql,
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'cachetime'=>(int)$cachetime,
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
               'sql'=>$sql,
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
##########*******memcache*******############
//Load library
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
if($resultsdata==''){
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
                         'sql'=>$sql,
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'cachetime'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         #'info'=>$cacheinfo
                         );
     #echo '<pre> dataall-> '; print_r($dataall); echo '</pre>'; die(); 
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
                         'sql'=>$sql,
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'cachetime'=>(int)$cachetime,
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
               'sql'=>$sql,
			'list'=>$dataresult,
               'count'=>(int)count($dataresult),
               );
/* ############# 4-->DB SQL End##############  */  
return $dataall;  
}
############cache##################
}
public function array_a_to_z (){
$array_a_to_z = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );
return $array_a_to_z;  
}
public function array_kor_to_hor(){
#$array_kor_to_hor = array( 'ก','ข','ฃ','ค','ฅ','ง', 'จ', 'ฉ', 'ช', 'ซ', 'ญ', 'ฎ', 'ฐ', 'ณ', 'ด', 'ต', 'ถ', 'ท', 'ธ', 'น', 'บ', 'ป', 'ผ', 'ฝ', 'พ', 'ฟ', 'ภ', 'ม', 'ย', 'ร', 'ฤ', 'ล', 'ว', 'ศ', 'ส', 'ห', 'อ', 'ฮ');
$array_kor_to_hor = array( 'ก','ข','ค','ง', 'จ', 'ฉ', 'ช', 'ซ', 'ญ', 'ฎ', 'ฐ', 'ณ', 'ด', 'ต', 'ถ', 'ท', 'ธ', 'น', 'บ', 'ป', 'ผ', 'ฝ', 'พ', 'ฟ', 'ภ', 'ม', 'ย', 'ร', 'ฤ', 'ล', 'ว', 'ศ', 'ส', 'ห', 'อ', 'ฮ');
return $array_kor_to_hor;  
}
}
?>