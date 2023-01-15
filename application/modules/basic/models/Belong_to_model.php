<?php
class Belong_to_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function getSelect($default = 0,$name = "belong_to_id"){
    		
    		$language = $this->lang->language;    		
			//$first = "--- ".$language['please_select']." ---";
	    	$rows = $this->get_data();

			//Debug($default);
			//Debug($rows);
	    	$opt = array();
	    	//$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row->belong_to_id, $row->belong_to);
	    	}
	    	//return selectList( $opt, $name, 'class="chosen-select"', 'value', 'text',$default);
	    	return MultiSelectList( $opt, $name, 'class="chosen-select"', 'value', 'text', $default);
			//die();
    }

    public function get_status($id){
        
    	$this->db->select('status');
    	$this->db->from('_belong_to');
    	$this->db->where('belong_to_id', $id);
    	//$this->db->where('lang', $language);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    }
        
    public function get_max_id(){

		//$language = $this->lang->language['lang'];
		$this->db->select('max(belong_to_id) as max_id');
		$this->db->from('_belong_to');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 

    }

    public function get_data_by_id($id_map){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_belong_to');
		$this->db->where('belong_to_id', $id_map);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function get_data($belong_to_id=null, $search_string=null, $order = 'create_date', $order_type='Asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];
		$prefix = 'sd';

		$this->db->select('*');
		$this->db->select('(SELECT admin_username FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`='.$prefix.'_belong_to.`create_by`) AS admin');
		$this->db->from('_belong_to ');
		//$this->db->join('_dara_type as dt', '(dp.dara_type_id=dt.dara_type_id_map AND dt.lang="'.$language.'"');

		if($belong_to_id != null && $belong_to_id > 0){
			//$this->db->where('dara_profile_id_map', $dara_profile_id_map);
			$this->db->where('belong_to_id', $belong_to_id);
		}

		//if($dara_profile_id_map <= 0) $this->db->where('lang', $language);

		if($search_string){
			$this->db->like('belong_to', $search_string);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('create_date', $order_type);
		}
		//$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_object();
    }

    function count_all($belong_to_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_belong_to');
		if($id_map != null && $id_map != 0){
			$this->db->where('belong_to_id', $belong_to_id);
		}
		if($search_string){
			$this->db->like('belong_to', $search_string);
		}
		/*if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('create_date', 'Asc');
		}*/
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($belong_to_id = 0, $data){

			if($belong_to_id > 0){
					$this->db->where('belong_to_id', $belong_to_id);
					$this->db->update('_belong_to', $data);
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
					$this->db->insert('_belong_to', $data);
					//echo $this->db->last_query();
					$insert = $this->db->insert_id();
					return $insert;
			}
	}

	function status_belong_to($id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('belong_to_id', $id);
		$this->db->update('_belong_to', $data);
		
		return $this->db->last_query();
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}	
	
    function delete_data($belong_to_id){
		$data = array(
				'status' => 2
		);
		$this->db->where('belong_to_id', $belong_to_id);
		$this->db->update('_belong_to', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_by_admin($belong_to_id){
		$this->db->where('belong_to_id', $belong_to_id);
		$this->db->delete('_belong_to'); 
	}
 
}
?>	
