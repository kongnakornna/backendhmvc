<?php
class Columnist_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function get_status($id){
        
    	$this->db->select('status');
    	$this->db->from('_columnist');
    	$this->db->where('columnist_id', $id);
    	//$this->db->where('lang', $language);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    
    }        

    public function get_max_id(){

		//$language = $this->lang->language['lang'];
		$this->db->select('max(columnist_id) as max_id');
		$this->db->from('_columnist');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 

    }

    public function get_data_by_id($id_map){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_columnist');
		$this->db->where('columnist_id', $id_map);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function get_data($columnist_id=null, $chkstatus = null, $search_string=null, $order = 'create_date', $order_type='Asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];

		$this->db->select('*');
		$this->db->from('_columnist');
		//$this->db->join('_dara_type as dt', '(dp.dara_type_id=dt.dara_type_id_map AND dt.lang="'.$language.'"');

		if($columnist_id != null && $columnist_id > 0){
			//$this->db->where('dara_profile_id_map', $dara_profile_id_map);
			$this->db->where('columnist_id', $columnist_id);
		}
		if($chkstatus) $this->db->where('status', 1);
		$this->db->where('status !=', 2);

		//if($dara_profile_id_map <= 0) $this->db->where('lang', $language);

		if($search_string){
			$this->db->like('columnist_name', $search_string);
		}

		/*if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('create_date', $order_type);
		}*/
		//$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();		
		//echo $this->db->last_query();
		
		return $query->result_array();
    }

    function count_all($columnist_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_columnist');
		if($id_map != null && $id_map != 0){
			$this->db->where('columnist_id', $columnist_id);
		}
		if($search_string){
			$this->db->like('columnist_name', $search_string);
		}
		/*if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('create_date', 'Asc');
		}*/
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($columnist_id = 0, $data){

			//Debug($data);
			if($columnist_id > 0){
					$this->db->where('columnist_id', $columnist_id);
					$this->db->update('_columnist', $data);
					//echo $this->db->last_query();
					//die();
					$report = array();
					//$report['error'] = $this->db->_error_number();
					//$report['message'] = $this->db->_error_message();
					if($report !== 0){
						//echo $this->db->last_query();
						return true;
					}else{
						return false;
					}					
			}else{
					$this->db->insert('_columnist', $data);
					//echo $this->db->last_query();
					$insert = $this->db->insert_id();
					//die();
					return $insert;
			}
	}
	
    function delete_data($columnist_id){
		$data = array(
				'status' => 2
		);
		$this->db->where('columnist_id', $columnist_id);
		$this->db->update('_columnist', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_by_admin($columnist_id){
		$this->db->where('columnist_id', $columnist_id);
		$this->db->delete('_columnist'); 
	}
 
}
?>	
