<?php
class Model_cacheapi extends CI_Model{
#####################
public function __construct(){
parent::__construct();
		#$this->load->database('api'); // load database name api
          $this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
          //Load library
          $this->load->library('Memcached_library');
}
public function memcache_manual($deletekey){
// Load library
// manual connection to Mamcache
$memcache=new Memcache;
#$memcachehost=$this->config->load['memcachehost'];
#$memcachepost=$this->config->load['memcachepost'];
$memcache->connect('localhost','11211');
     $order_by='asc';
     $limit='3';
     $timecache='3600';
     $keycache="test_key";
     //if($deletekey==1){ $memcache->delete($keycache); }

        $dataresult=$memcache->get($keycache);
        if($dataresult!=null){
            $status='cache data '.$timecache.' seconds ';
            $dataall=array('data'=>$dataresult,'message'=>$status,);
        }else{
             
            $sql = "SELECT * FROM ams_entrance order by ent_id $order_by limit $limit";
		  $query = $this->db->query($sql);
		  $dataresult = $query->result();
            $memcache->set($keycache,$dataresult,false,$timecache); 
            // 10 seconds
            $status='Real data set time cache key'.$timecache.' seconds';
            $dataall=array('data'=>$dataresult,'message'=>$status,);
        }
     
return $dataall;
    }
public function list_all_data_ams_entrance($order_by='desc',$limit='100'){ 
	// Create Turn caching on
        $this->db->cache_on(); 
        // Turn caching off for this one query
        // $this->db->cache_off();
		#$urlcache=base_url('dbcache/');
		#$filecache=base_url('True+Mul_leveltb_code');
		//$this->db->cache_delete($urlcache,$filecache);
		$sql = "SELECT * FROM ams_entrance order by ent_id $order_by limit $limit";
		$query = $this->db->query($sql);
		$data = $query->result();
		if($data) {
		    return $data;
		} else {
		     return false;
		}
}
public function get_memcached_result($deletekey,$order_by,$limit){
##################memcached ci3 ###########################
$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
if($this->cache->memcached->is_supported()){
#######Var##########
if($order_by==''){$order_by='asc';}
if($limit==''){$limit='5';}
$sql = "SELECT * FROM ams_entrance order by ent_id $order_by limit $limit";
#################
#$cachekey='key_ams_entrance'.$sql;
$cachekey='key-ams-entrance-order_by-'.$order_by.'-limit-'.$limit;
$cachetime='300';
if($deletekey==1){$dataall=$this->cache->memcached->delete($cachekey);}
$data=$this->cache->memcached->get($cachekey);
if($data==null){
###########DB SQL query Start###########
$query = $this->db->query($sql);
$dataresult = $query->result();
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
##################memcached ci3 ###########################
 return $dataall;
}
public function get_redis_result($deletekey,$order_by,$limit){
##################redis ci3 ###########################
$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
#################
#$order_by='desc';$limit='10';
$sql = "SELECT * FROM ams_entrance order by ent_id $order_by limit $limit";
#################
$cachekey='redis_key_ams_entrance';
$redistime='300';
if($deletekey==1){ $dataall=$this->cache->redis->delete($cachekey);}
if($this->cache->redis->is_supported() || $this->cache->file->is_supported()) {}
 #$data=$this->cache->redis->get($cachekey);
 $data=$this->getDirectValue($cachekey);
 #echo '<pre> redis->get'; print_r($data); echo '</pre>'; Die();
if($data){
            
             $cache_info=$this->cache->redis->cache_info();
             $dataall=array('message'=>'Data form redis',
					    'status'=>TRUE, 
					     'list'=>$data,
                              'count'=>(int)count($data),
                              'time sec'=>(int)$redistime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>$cache_info);
}else{
###########DB SQL query Start###########
		  $query = $this->db->query($sql);
		  $dataresult = $query->result();
            $jsondata=json_encode($dataresult, JSON_NUMERIC_CHECK);
###########DB SQL query End ###########
           $this->cache->redis->save($cachekey,$jsondata,$redistime);
           $cache_info=$this->cache->redis->cache_info();
           $dataall=array('message'=>'Data form database query save to redis',
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'time sec'=>(int)$redistime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>$cache_info);
}
##################redis ci3 ###########################
 return $dataall;
}
public function get_where_u_id($u_id){
$this->load->driver('cache');
#$this->db->cache_delete('api', 'admissiontrainer');
// Turn caching on
$this->db->cache_on();
// Turn caching off for this one query
#$this->db->cache_off();
#$this->db->cache_delete_all();

	$this->db->select('*');
	$this->db->from('ams_entrance');
	$this->db->where('u_id', $u_id);
	$query = $this->db->get();
	return $query->result_array(); 
}
public function get_file_where_u_id($u_id){
	$this->db->select('*');
	$this->db->from('ams_entrance');
	$this->db->where('u_id', $u_id);
	$query = $this->db->get();
	return $query->result_array(); 
}
public function get_memcachedrs($deletekey,$order_by,$limit){
##################memcached ci3 ###########################
$this->load->driver('cache');
if($this->cache->memcached->is_supported()){
#######Var##########
if($order_by==''){$order_by='asc';}
if($limit==''){$limit='5';}
$sql = "SELECT * FROM ams_entrance order by ent_id $order_by limit $limit";
#################
#$cachekey='key_ams_entrance'.$sql;
###############################
$cachekey='key-ams-entrance-order_by-'.$order_by.'-limit-'.$limit;
$this->load->library('encrypt');
$encrypted_string = $this->encrypt->encode($cachekey);
//$key=$this->config->load->item('encryption_key');
#$encrypted_string_key = $this->encrypt->encode($cachekey, $key);
$decode_string = $this->encrypt->decode($encrypted_string);
###############################
$cachetime='300';
if($deletekey==1){$dataall=$this->cache->memcached->delete($cachekey);}
$data=$this->cache->memcached->get($cachekey);
if($data==null){
###########DB SQL query Start###########
$query = $this->db->query($sql);
$dataresult = $query->result();
###########DB SQL query End ###########
           $this->cache->memcached->save($cachekey,$dataresult,$cachetime);
           $cache_info=$this->cache->memcached->cache_info();
           //$cache_get_metadata=$this->cache->memcached->get_metadata($cachekey);
           $dataall=array('message'=>'Data form database query save to memcached',
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'time sec'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         //'cache_get_metadata'=>$cache_get_metadata,
                         'cacheinfo'=>$cache_info);
        }else{
             $cache_info=$this->cache->memcached->cache_info();
             $dataall=array('message'=>'Data form memcached',
					    'status'=>TRUE, 
					     'list'=>$data,
                              'count'=>(int)count($data),
                              'time sec'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'encrypted-key'=>$encrypted_string,
                              'decode-key'=>$decode_string,
                              'cacheinfo'=>$cache_info);
        }
 }
##################memcached ci3 ###########################
 return $dataall;
}
public function cvs_course_examination_array($array,$deletekey){
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
$sql="SELECT * from cvs_course_examination where id in ($array)  order by id asc";
$cachekey="key-where-array-cvs_course_examination-".$sql;
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
// If the key does not exist it could mean the key was never set or expired
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
					'status'=>TRUE, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'info'=>$cacheinfo);
     }else{
            // Output
            // Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>FALSE, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'info'=>$cacheinfo);
     }
return $dataall;
}
#####################
}
?>