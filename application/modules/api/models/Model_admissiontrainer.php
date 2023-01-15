<?php
class Model_admissiontrainer extends CI_Model{
#####################
public function __construct(){
	parent::__construct();
		#$this->load->database('api'); // load database name api
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
public function get_memcached_result($deletekey){
##################memcached ci3 ###########################
$this->load->driver('cache');
if($this->cache->memcached->is_supported()){
#######Var##########
$order_by='desc';
$limit='10';
$sql = "SELECT * FROM ams_entrance order by ent_id $order_by limit $limit";
#################
#$cachekey='key_ams_entrance'.$sql;
$cachekey='key_ams_entrance';
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
public function get_redis_result($deletekey){
##################redis ci3 ###########################
$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
#################
$order_by='desc';$limit='10';
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
public function getDirectValue($key){
$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
return $this->cache->redis->get($key);
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
#####################
}
?>