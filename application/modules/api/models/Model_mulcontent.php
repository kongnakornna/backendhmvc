<?php
class Model_mulcontent extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database('api'); // load database name api
	}
    // Show data all
	public function list_all_data($order_by,$limit='100'){ 
		// Create Turn caching on
        $this->db->cache_on(); 
        // Turn caching off for this one query
        // $this->db->cache_off();
		
		$urlcache=base_url('dbcache/');
		$filecache=base_url('True+Mul_leveltb_code');
		//$this->db->cache_delete($urlcache,$filecache);
		
		$sql = "SELECT * FROM tb_code order by idx $order_by limit $limit";
		$query = $this->db->query($sql);
		$data = $query->result();
		if($data) {
		    return $data;
		} else {
		     return false;
		}
	}
	// Where
	public function read_where($id){ 
		$sql = "SELECT * FROM tb_code where idx=".$id."";
		$query = $this->db->query($sql);
		$data = $query->result();
		if($data) {
		    return $data;
		} else {
		     return false;
		}
		}
	
	public function read_where_id($id){ 
		$this->db->select('*');
		$this->db->from('tb_code');
		$this->db->where('idx', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }   
    
    public function insert($data){
    	$data_insert=$data;
		//debug($data);Die();
		//$id=$data['idx'];
		$tableName=$data['tableName'];
		//echo '$tableName==>'; debug($tableName);Die();
		//$sql = "SELECT * FROM tb_code where idx=".$id."";
		$sql = "SELECT * FROM tb_code where tableName='$tableName'";
		#debug($sql);Die();
		$query = $this->db->query($sql);
		$num_rows=(int)$query->num_rows(); //debug($num_rows);Die();
		$data = $query->result();
		if($num_rows==0){
			#debug($data_insert);Die();
			$insert = $this->db->insert('tb_code', $data_insert);
			//debug($insert);Die();
			//return $query_insert;
			$result_data=1;
		}else{
			$result_data= 'Error have insert data is duplicate';
		}
		return $result_data;
    }
	
	function store_insert($data){
    	$data_insert=$data;
		//debug($data);Die();
		//$id=$data['idx'];
		$tableName=$data['tableName'];
		//echo '$tableName==>'; debug($tableName);Die();
		//$sql = "SELECT * FROM tb_code where idx=".$id."";
		$sql = "SELECT * FROM tb_code where tableName='$tableName'";
		#debug($sql);Die();
		$query = $this->db->query($sql);
		$num_rows=(int)$query->num_rows(); //debug($num_rows);Die();
		$data = $query->result();
		if($num_rows==0){
			#debug($data_insert);Die();
			$insert = $this->db->insert('tb_code', $data_insert);
			//debug($insert);Die();
			//return $query_insert;
			$result_data=1;
		}else{
			$result_data= 'Error have insert data is duplicate';
		}
		return $result_data;
    }
		
    public function update($data,$id){
	   #echo'$id=>'.$id; echo'$data=>';debug($data);die();
       $result_data=$this->db->where('idx',$id);
       $result_data=$this->db->update('tb_code',$data);  
       //debug($result_data);die();
       if($result_data==1){
	   	$result_data=1;
	   }else{
	   	$result_data=0;
	   }
       return $result_data;    
    }
    public function delete($id){
    	$sql = "SELECT * FROM tb_code where idx=".$id."";
		$query = $this->db->query($sql);
		$num_rows=(int)$query->num_rows();  //debug($num_rows);
		$data = $query->result();
		if($num_rows==0){
			return $num_rows;
		}else{
			$query_delete=$this->db->query("delete from tb_code where idx=$id");
			if (!$query_delete) {
			    $result_delete = 'Error!';
			    $result_data_delete= 0;
			}else if($query_delete) {
			   $result_delete= 'Delete Success';
			   $result_data_delete= 1;
			}
        //echo '$result_delete=>'; echo $result_delete; debug($query_delete); Die();
        return $result_data_delete;
		}
        
    }
	
}


