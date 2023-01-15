<?php
class Credit_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function getSelect($default = 0, $name = "credit_id", $id = "credit_id", $type = "Multi"){//Dropdown List
    		
    		//$language = $this->lang->language;
			//$first = "--- ".$language['please_select']." ---";
	    	$rows = $this->get_data();
			//Debug($rows);
	    	$opt = array();
	    	//$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row['credit_id'], $row['credit_name']);
	    	}

			if($type == "Multi")
				$data = MultiSelectList( $opt, $name, 'class="chosen-select"', 'value', 'text', $default);
			else
				$data = selectList( $opt, $name, 'class="chosen-select"', 'value', 'text', $default);
	    	return $data;
    }

    public function get_status($id){
        
    	$this->db->select('credit_name, status');
    	$this->db->from('_credit');
    	$this->db->where('credit_id', $id);
    	//$this->db->where('lang', $language);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    }
        
    public function get_max_id(){

		//$language = $this->lang->language['lang'];
		$this->db->select('max(credit_id) as max_id');
		$this->db->from('_credit');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 

    }

    public function get_data_by_id($id_map){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_credit');
		$this->db->where('credit_id', $id_map);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function get_data($credit_id=null, $search_string=null, $order = 'create_date', $order_type='Asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];

		$this->db->select('*');
		$this->db->from('_credit ');
		//$this->db->join('_dara_type as dt', '(dp.dara_type_id=dt.dara_type_id_map AND dt.lang="'.$language.'"');

		if($credit_id != null && $credit_id > 0){
			//$this->db->where('dara_profile_id_map', $dara_profile_id_map);
			$this->db->where('credit_id', $credit_id);
		}

		//if($dara_profile_id_map <= 0) $this->db->where('lang', $language);

		if($search_string){
			$this->db->like('credit_name', $search_string);
		}
		$this->db->where('status !=', 2);

		/*if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('create_date', $order_type);
		}*/
		//$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();		
		//debug($this->db->last_query());
		
		return $query->result_array();
    }

    public function chk_date($credit_id=null, $search_string=null){
		
		$language = $this->lang->language['lang'];

		$this->db->select('*');
		$this->db->from('_credit ');
		//$this->db->join('_dara_type as dt', '(dp.dara_type_id=dt.dara_type_id_map AND dt.lang="'.$language.'"');

		if($credit_id != null && $credit_id > 0){
			//$this->db->where('dara_profile_id_map', $dara_profile_id_map);
			$this->db->where('credit_id', $credit_id);
		}

		//if($dara_profile_id_map <= 0) $this->db->where('lang', $language);

		if($search_string){
			$this->db->like('credit_name', $search_string);
		}

		/*if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('create_date', $order_type);
		}*/
		//$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();		
		//debug($this->db->last_query());
		
		return $query->result_array();
    }

    function count_all($credit_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_credit');
		if($id_map != null && $id_map != 0){
			$this->db->where('credit_id', $credit_id);
		}
		if($search_string){
			$this->db->like('credit_name', $search_string);
		}
		/*if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('create_date', 'Asc');
		}*/
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($credit_id = 0, $data){

			if($credit_id > 0){
					$this->db->where('credit_id', $credit_id);
					$this->db->update('_credit', $data);
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
					$this->db->insert('_credit', $data);
					//echo $this->db->last_query();
					$insert = $this->db->insert_id();
					return $insert;
			}
	}

	function status_credit($id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('credit_id', $id);
		$this->db->update('_credit', $data);
		
		Debug($this->db->last_query());
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}	
	
    function delete_data($credit_id){
		$data = array(
				'status' => 2
		);
		$this->db->where('credit_id', $credit_id);
		$this->db->update('_credit', $data);
		
		Debug($this->db->last_query());
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_by_admin($credit_id){
		$this->db->where('credit_id', $credit_id);
		$this->db->delete('_credit'); 
	}
 
}
?>	
