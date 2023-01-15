<?php
class Gallery_type_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function get_status($id){
        
    	$this->db->select('status');
    	$this->db->from('_gallery_type');
    	$this->db->where('gallery_type_id2', $id);
    	$query = $this->db->get();
    	return $query->result_array();
    
    }
        

    public function get_max_id(){

		$this->db->select('max(gallery_type_id) as max_id');
		$this->db->from('_gallery_type');

		$query = $this->db->get();
		return $query->result_array(); 

    }

    public function get_data_by_id($gallery_type_id){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_gallery_type');
		$this->db->where('gallery_type_id2', $gallery_type_id);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function getSelectCat($default = 0, $first = "",$name = "gallery_type_id"){
    		
    		$language = $this->lang->language;
    		
    		//debug($language);
    		//die();    		
    		if($first == "") $first = "--- ".$language['please_select']." ---";
    		
	    	//if($default == 0) $rows = $this->get_category(null, 1);
	    	//else $rows = $this->get_category($default, 1);
	    	$rows = $this->get_data();
	    	
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row['gallery_type_id2'], $row['gallery_type_name']);
	    	}
	    	return selectList( $opt, $name, 'class="form-control"', 'value', 'text', $default);
    }

    public function get_data($gallery_type_id=null, $search_string=null, $order = 'create_date', $order_type='Asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];

		$this->db->select('*');
		$this->db->from('_gallery_type');
		//$this->db->join('_dara_type as dt', '(dp.dara_type_id=dt.dara_type_id_map AND dt.lang="'.$language.'"');

		if($gallery_type_id != null && $gallery_type_id > 0){
			$this->db->where('gallery_type_id', $gallery_type_id);
		}

		$this->db->where('lang', $language);

		//if($dara_profile_id_map <= 0) $this->db->where('lang', $language);

		if($search_string){
			$this->db->like('gallery_type_name', $search_string);
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

    function count_all($gallery_type_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_gallery_type');
		if($gallery_type_id != null && $gallery_type_id != 0){
			$this->db->where('gallery_type_id', $gallery_type_id);
		}
		if($search_string){
			$this->db->like('gallery_type_name', $search_string);
		}
		/*if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('create_date', 'Asc');
		}*/
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($gallery_type_id = 0, $data){

			if($credit_id > 0){
					$this->db->where('gallery_type_id', $gallery_type_id);
					$this->db->update('_gallery_type', $data);
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
					$this->db->insert('_gallery_type', $data);
					//echo $this->db->last_query();
					$insert = $this->db->insert_id();
					return $insert;
			}
	}
	
    function delete_data($gallery_type_id){
		$data = array(
				'status' => 2
		);
		$this->db->where('gallery_type_id2', $gallery_type_id);
		$this->db->update('_gallery_type', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_by_admin($gallery_type_id){
		$this->db->where('gallery_type_id2', $gallery_type_id);
		$this->db->delete('_gallery_type'); 
	}
 
}
?>	
