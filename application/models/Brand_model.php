<?php
class Brand_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function get_status($id){
    	$this->db->select('status');
    	$this->db->from('_brand');
    	$this->db->where('brand_id2', $id);
    	$query = $this->db->get();
    	return $query->result_array();
    }
        

    public function get_max_id(){
		$this->db->select('max(brand_id) as max_id');
		$this->db->from('_brand');
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_data_by_id($brand_id){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_brand');
		$this->db->where('brand_id2', $brand_id);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function getSelectCat($default = 0, $first = "",$name = "brand_id"){
    		
    		$language = $this->lang->language;
    		
    		//debug($language);
    		//die();    		
    		if($first == "") $first = "--- ".$language['please_select']." ---";
			else $first = "--- ".$first." ---";
    		
	    	//if($default == 0) $rows = $this->get_category(null, 1);
	    	//else $rows = $this->get_category($default, 1);
	    	$rows = $this->get_data();
	    	
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row->brand_id, $row->brand_name);
	    	}
	    	return selectList( $opt, $name, 'class="form-control"', 'value', 'text', $default);
    }

    public function get_data($brand_id=null, $search_string=null, $order = 'create_date', $order_type='Asc', $limit_start = 0, $listpage = 20){
		
		//$language = $this->lang->language['lang'];
		$prefix = 'sd';

		$this->db->select('*');
		$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_brand.create_by) AS create_by_name');
		$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_brand.lastupdate_by) AS lastupdate_by_name');

		$this->db->from('_brand');
		//$this->db->join('_dara_type as dt', '(dp.dara_type_id=dt.dara_type_id_map AND dt.lang="'.$language.'"');

		if($brand_id != null && $brand_id > 0){
			$this->db->where('brand_id', $brand_id);
		}

		//$this->db->where('lang', $language);

		//if($dara_profile_id_map <= 0) $this->db->where('lang', $language);

		if($search_string){
			$this->db->like('brand_name', $search_string);
		}

		/*if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('create_date', $order_type);
		}*/
		//$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();		
		//echo $this->db->last_query();
		
		return $query->result_object();
    }

    function count_all($brand_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_brand');
		if($brand_id != null && $brand_id != 0){
			$this->db->where('brand_id', $brand_id);
		}
		if($search_string){
			$this->db->like('brand_name', $search_string);
		}
		/*if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('create_date', 'Asc');
		}*/
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($brand_id = 0, $data){

			if($brand_id > 0){
					$this->db->where('brand_id', $brand_id);
					$this->db->update('_brand', $data);
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
					$this->db->insert('_brand', $data);
					//echo $this->db->last_query();
					$insert = $this->db->insert_id();
					return $insert;
			}
	}
	
    function remove_img($brand_id, $dataobj = null){

		if($dataobj == null)
			$dataobj = array(
				'logo' => ''
			);

		$this->db->where('brand_id', $brand_id);
		$this->db->update('_brand', $dataobj);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    function delete_data($brand_id){
		$data = array(
				'status' => 2
		);
		$this->db->where('brand_id2', $brand_id);
		$this->db->update('_brand', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_by_admin($brand_id){
		$this->db->where('brand_id2', $brand_id);
		$this->db->delete('_brand'); 
	}
 
}
?>	
