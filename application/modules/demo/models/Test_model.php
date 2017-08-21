<?php
class Test_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
    public function get_member_by_id($id){
     		$this->db->cache_on();
     		$this->db->select('*');
     		$this->db->from('_admin');
     		$this->db->where('admin_id', $id);
     		$query=$this->db->get();
     		$query->result_array(); 
     		$result=$query;
     		return $result->result_array(); 
		 }   
}