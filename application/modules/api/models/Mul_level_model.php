<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mul_level_model extends CI_Model{   
public function get_status($status,$deletekey){
##########*******memcache*******############
$cachekey="key-mul-level-status-".$status;
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
     // Modify this Query to your liking!
###########DB SQL query Start###########
    	$this->db->select('mul_level_id,name,mul_level_parent_id,mul_level_name');
    	$this->db->from('mul_level');
    	$this->db->where('status', $status);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
	$resultsdata=$query->result_array();		
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
public function get_max_order($deletekey){    
##########*******memcache*******############
$cachekey="key-mul-level-max_order";
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
		$this->db->select('max(mul_level_id) as max_order');
		$this->db->from('mul_level');
		$query = $this->db->get();
		$resultsdata=$query->result_array();		
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
public function get_max_id($deletekey){   
##########*******memcache*******############
$cachekey="key-mul-level-max_id";
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
		$this->db->select('max(mul_level_id) as max_id');
		$this->db->from('mul_level');
		$query = $this->db->get();
		$resultsdata=$query->result_array();		
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
public function getSelectlevel($id='',$name="mul_level_id",$default){	
   #Debug($id); die();
   #$first = "--- please select---";
   $first = "--- กรุณาเลือกชั้น ---";
   if($id!==Null){
    
    $rows=$this->get_category_by_id($id);
   }else{
     $rows=$this->get_category_all();
   }
		 #Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){ 
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row['mul_level_id'], $row['mul_level_name']);
	    	}
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
    }    
public function get_level_by_id($id,$deletekey){
##########*******memcache*******############
$cachekey="key-get-level-by-id-".$id;
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
     // Modify this Query to your liking!
###########DB SQL query Start###########
          $this->db->select('*');
		$this->db->from('mul_level');
		$this->db->where('mul_level_id', $id);
		$query=$this->db->get();
		$resultsdata=$query->result_array();		
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
public function get_level_all($deletekey){
##########*******memcache*******############
$cachekey="key-list-mul-level-all";
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
     // Modify this Query to your liking!
###########DB SQL query Start###########
		$this->db->select('*');
		$this->db->from('mul_level');
		$query = $this->db->get();
		$resultsdata=$query->result_array();		
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
                         'info'=>$cacheinfo
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
                         'info'=>$cacheinfo
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
#return $resultsdata;  
return $dataall;  
}
public function get_level_by_parent_id($parent_id,$deletekey){
##########*******memcache*******############
$cachekey="key-list-mul-level-parent_id-".$parent_id;
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
     // Modify this Query to your liking!
###########DB SQL query Start###########
		$this->db->select('*');
		$this->db->from('mul_level');
		$this->db->where('mul_level_id', $parent_id);
		$query = $this->db->get();
		$resultsdata=$query->result_array();		
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
public function get_level($parent_id='0',$limit_start=0,$listpage=200,$deletekey){        
##########*******memcache*******############
$cachekey="key-mul-level-parentid-".$parent_id."-limitstart-".$limit_start."-end-".$listpage;
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
          $this->db->select('*');
		$this->db->from('mul_level');
		if($parent_id != null && $parent_id > 0){
			$this->db->where('mul_level_parent_id', $parent_id);
		}
		$this->db->order_by('mul_level_id', 'asc');
		//$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		$resultsdata=$query->result_array();		
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
} 