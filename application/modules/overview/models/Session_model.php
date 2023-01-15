<?php
class Session_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }    

    function get_sessions($last_activity = 0){
			$this->db->select('*');
			$this->db->from('_sessions');
			$this->db->where('last_activity <', $last_activity);

			$query = $this->db->get();
			//Debug($this->db->last_query());
			$result = $query->result_object();
			return $result;
	}

    function remove_sessions($last_activity = 0){

			if($last_activity > 0){
					$this->db->where('last_activity <', $last_activity);
					$this->db->delete('_sessions'); 
					//Debug($this->db->last_query());
			}else
					$this->db->delete('_sessions');
	}
 
}
?>	
