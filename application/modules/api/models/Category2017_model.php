<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category2017_model extends CI_Model{           
public function get_status($id,$deletekey){
##########*******memcache*******############
$cachekey="key-mul-category-2017-".$status;
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
    	$this->db->select('mul_category_id, mul_parent_id,mul_category_name');
    	$this->db->from('mul_category_2017');
    	$this->db->where('mul_category_id', $id);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
	$resultsdata=$query->result_array();		
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
}        
public function get_max_order($deletekey){
##########*******memcache*******############
$cachekey="key-mul-category-2017-max-order";
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
		$this->db->select('max(mul_category_id) as max_order');
		$this->db->from('mul_category_2017');
		$query = $this->db->get();
	$resultsdata=$query->result_array();		
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
}
public function get_max_id($deletekey){
##########*******memcache*******############
$cachekey="key-mul-category-2017-max-id";
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
		//$language = $this->lang->language['lang'];
		$this->db->select('max(mul_category_id) as max_id');
		$this->db->from('mul_category_2017');
		$query = $this->db->get();
	$resultsdata=$query->result_array();		
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
return $resultsdata;  
}
public function getSelectCat($mul_parent_id='',$name="mul_category_id",$default='1000',$deletekey){	
 #Debug($mul_parent_id); die();
   #$first = "--- please select category---";
   $first = "--- กรุณาเลือก ---";
   if($mul_parent_id!==Null){
    $mul_parent_id=(int)$mul_parent_id;
    $rows=$this->get_category_by_parent_id($mul_parent_id);
   }else{
     $rows=$this->get_category_by_parent_id($mul_parent_id='0');
   }
		 #Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption((int)$row['mul_category_id'], $row['mul_category_name']);
	    	}
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
    }    
public function getSelectSubCat($mul_parent_id='',$name="mul_category_parent_id",$default,$deletekey){	
  #Debug($mul_parent_id); Debug($default); die();
   #$first = "--- please select subcategory ---";
   $mul_parent_id=(int)$mul_parent_id;
   $first = "--- กรุณาเลือกทักษะ ---";
   if($mul_parent_id!==Null){
    $rows=$this->get_category_by_parent_id($mul_parent_id);
   }else{
     $rows=$this->get_category_by_parent_id($mul_parent_id='0');
   }
		 #Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption((int)$row['mul_category_id'], $row['mul_category_name']);
	    	}
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
}  
public function get_category_by_id($mul_category_id,$deletekey){
$mul_category_id=(int)$mul_category_id;
##########*******memcache*******############
$cachekey="key-mul-category-2017-mul-category-id-".$mul_category_id;
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
		$this->db->select('*');
		$this->db->from('mul_category_2017');
		$this->db->where('mul_category_id',$mul_category_id);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		//Debug($this->db->last_query());
	$resultsdata=$query->result_array();		
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
return $resultsdata;  
}
public function get_category_by_parent_id($mul_parent_id,$deletekey){
##########*******memcache*******############
$cachekey="key-mul-category-2017-mul-parent_id-".$mul_parent_id;
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
if($mul_parent_id==Null){
$sql="SELECT *  FROM mul_category_2017 
where (mul_parent_id=0 and mul_category_id>=1000 and mul_category_id<=9000) 
or mul_category_id=9999 or mul_category_id=10000 order by mul_category_id asc";
}else{
$sql="SELECT *  FROM mul_category_2017  where mul_parent_id=$mul_parent_id order by mul_category_id asc";
}
$resultsdata=$this->db->query($sql)->result_array();
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
return $resultsdata;  
}
public function getSelectCatall($mul_parent_id='',$name="mul_category_parent_id",$default,$deletekey){	
 #Debug($mul_parent_id); die();
   #$first = "--- please select subcategory ---";
   $mul_parent_id=(int)$mul_parent_id;
   $first = "--- กรุณาเลือกทักษะ ---";
   if($mul_parent_id!==Null){
    $rows=$this->get_category_by_parent_id_all($mul_parent_id);
   }else{
     $rows=$this->get_category_by_parent_id_all($mul_parent_id='0');
   }
		 #Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption((int)$row['mul_category_id'], $row['mul_category_name']);
	    	}
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
}  
public function get_category_by_parent_id_all($level_id,$deletekey){   
##########*******memcache*******############
/* ปฐมวัย- อ.3 */
$mulcategory_array0=array("0","1","2","3"); 
/* ปฐมศึกษา- ป.6 */
$mulcategory_array1=array("10","11","12","13","20","21","22","22","23"); 
/* มัธยมต้น-ม.3 */
$mulcategory_array2=array("30","31","32","33"); 
/* มัธยมปลาย-ม.5 */
$mulcategory_array3=array("40","41","42"); 
/* มัธยมปลาย-ม.6 */
$mulcategory_array4=array("43"); 
/* ไม่ระบุระดับชั้น */ 
$mulcategory_array5=array("100"); 
if(in_array($level_id,$mulcategory_array0)){
     $mulcategory_arrays=$mulcategory_array0;
     #$mulcategory_array='100,300,500,700,500,9600,9601,9602,9603';
     $mulcategory_array='100,300,500,700,500,9600';
}elseif(in_array($level_id,$mulcategory_array1)){
     $mulcategory_arrays=$mulcategory_array1;
     $mulcategory_array='1000,2000,3000,4000,5000,6000,7000,8000';
}elseif(in_array($level_id,$mulcategory_array2)){
     $mulcategory_arrays=$mulcategory_array2;
     $mulcategory_array='1000,2000,3000,4000,5000,6000,7000,8000';
}elseif(in_array($level_id,$mulcategory_array3)){
     $mulcategory_arrays=$mulcategory_array3;
     $mulcategory_array='1000,2000,3000,4000,5000,6000,7000,8000,9000,9001,9002,9003,9004,9005,9006,9007,9008,9009,9010,9011,9012,9013,9014,9015,9600,9700';
}elseif(in_array($level_id,$mulcategory_array4)){
     $mulcategory_arrays=$mulcategory_array4;
     $mulcategory_array='1000,2000,3000,4000,5000,6000,7000,8000,9000,9806,9001,9002,9003,9004,9005,9006,9007,9008,9009,9010,9011,9012,9013,9014,9015,9600,9700';
         
}elseif(in_array($level_id,$mulcategory_array5)){
     $mulcategory_arrays=$mulcategory_array5;
     $mulcategory_array='9000,9001,9002,9003,9004,9005,9006,9007';
}else{$level_id='';}
$mulcategoryarray=implode(",",$mulcategory_arrays);
#################
$search=',';
$replace='-';
$string=$mulcategoryarray;
$result_set2=str_replace($search,$replace,$string);
#################   
 
$cachekey="key-mulcategory-2017-mulparent_id-".$result_set2;
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

if($level_id==Null){
$sql="select * from mul_category_2017 
where (mul_parent_id=0 and mul_category_id>=1000 and mul_category_id<=9000) 
or mul_category_id=9999 or mul_category_id=10000 order by mul_category_id asc";
}else{
$sql="select * from mul_category_2017 where mul_category_id in($mulcategory_array) order by mul_category_id asc";
}
$resultsdata=$this->db->query($sql)->result_array();
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
                         'sql'=>$sql,
                         'level_id'=>$level_id,
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
#return $resultsdata; 
return $dataall;
}
public function get_category($mul_parent_id='0',$limit_start=0,$listpage=20000,$deletekey){
##########*******memcache*******############
$cachekey="key-mulcategory-2017-mul-category-id-asc";
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
		$this->db->select('*');
		$this->db->from('mul_category_2017');
		if($mul_parent_id != null && $mul_parent_id > 0){
		$this->db->where('mul_parent_id', $category_id);
		}
		$this->db->order_by('mul_category_id', 'asc');
		//$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		$resultsdata=$query->result_array();		
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
return $resultsdata; 
}     
}
 