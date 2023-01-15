<?php
class Subcategory_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        //$this->load->database();
    }

    public function get_status($id){
    
    	$language = $this->lang->language['lang'];
    
    	$this->db->select('subcategory_name, order_by, status');
    	$this->db->from('_subcategory');
    	$this->db->where('subcategory_id_map', $id);
    	$this->db->where('lang', $language);
    	$query = $this->db->get();
    	
    	//echo $this->db->last_query();
    	
    	return $query->result_array();
    
    }
        
    public function get_max_order($category_id = 0){

		$language = $this->lang->language['lang'];

		$this->db->select('max(order_by) as max_order');
		$this->db->from('_subcategory');

		if($category_id > 0) $this->db->where('category_id', $category_id);

		$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_max_id(){

		//$language = $this->lang->language['lang'];
		$this->db->select('max(subcategory_id) as max_id');
		$this->db->from('_subcategory');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function getSelectSubcat($category_id = 0, $default = 0,$name = "subcategory_id"){
    
    	$language = $this->lang->language;
    	//debug($language);
    	$first = "--- ".$language['please_select'].$language['subcategory']." ---";

    	$rows = $this->get_subcategory($category_id, null, 1);
    	$opt = array();
    	$opt[]	= makeOption(0,$first);
    	for($i=0;$i<count($rows);$i++){
    		$row = @$rows[$i];
			if($row['status'] == 1) $opt[]	= makeOption($row['subcategory_id_map'], $row['subcategory_name']);
    	}
    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
    }

    public function get_dd_list($category_id, $lang = ''){

		$language = $this->lang->language['lang'];
		
		$this->db->select('*');
		$this->db->from('_subcategory');

		$this->db->where('category_id', $category_id);
		$this->db->where('lang', $language);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object(); 
    }

    public function get_subcategory_by_id($subcategory_id_map, $lang = ''){

		$language = $this->lang->language['lang'];
		
		$this->db->select('*');
		$this->db->from('_subcategory');

		$this->db->where('subcategory_id_map', $subcategory_id_map);
		//$this->db->where('category_id', $category_id);

		if($lang != '') $this->db->where('lang', $language);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object(); 
    }

    public function get_subcategory($category_id=0, $subcategory_id=null, $subcat_status=null, $order='order_by', $order_type='Asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];
	    
		$this->db->select('_subcategory.subcategory_id');
		$this->db->select('_subcategory.subcategory_id_map');
		$this->db->select('_subcategory.subcategory_name');
		$this->db->select('_subcategory.lang');
		$this->db->select('_subcategory.order_by');
		$this->db->select('_subcategory.create_date');
		$this->db->select('_subcategory.status');
		$this->db->from('_subcategory');

		if($subcategory_id != null && $subcategory_id > 0){
			$this->db->where('subcategory_id', $subcategory_id);
		}

		if($category_id != null && $category_id > 0){
			$this->db->where('category_id', $category_id);
		}

		$this->db->where('lang', $language);
		$this->db->where('status !=', 2);

		/*if($search_string){
			$this->db->like('subcategory_name', $search_string);
		}*/
		if($subcat_status){
			$this->db->where('status', $subcat_status);
		}		

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('order_by', $order_type);
		}

		$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		//echo $this->db->last_query();
		//die();		
		return $query->result_array();
    }

    function count_products($subcategory_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_subcategory');
		if($subcategory_id != null && $subcategory_id != 0){
			$this->db->where('subcategory_id', $subcategory_id);
		}
		if($search_string){
			$this->db->like('subcategory_name', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('order_by', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store_product($data){
		$insert = $this->db->insert('_subcategory', $data);
	    return $insert;
	}

    function update_subcategory($subcategory_id, $data){

		$this->db->where('subcategory_id', $subcategory_id);
		$this->db->update('_subcategory', $data);
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function update_order($subcategory_id, $order = 1){

		$data['order_by'] = $order;
		$this->db->where('subcategory_id_map', $subcategory_id);
		$this->db->update('_subcategory', $data);
		//Debug($this->db->last_query());

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function update_orderid_to_down($order, $max){

		$this->db->set('order_by', 'order_by + 1', FALSE); 
		$this->db->where('order_by >=', $order); 
		$this->db->where('order_by <=', $max); 
		$this->db->update('_subcategory');
		//Debug($this->db->last_query());

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}	

	function update_orderid_to_up($order, $min){

		$this->db->set('order_by', 'order_by - 1', FALSE); 
		$this->db->where('order_by >', $min); 
		$this->db->where('order_by <=', $order); 
		$this->db->update('_subcategory');
		//Debug($this->db->last_query());

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function update_orderadd(){

		$this->db->set('order_by', 'order_by + 1', FALSE); 
		$this->db->update('_subcategory');

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function update_orderdel($order){

		$this->db->set('order_by', 'order_by - 1', FALSE); 
		$this->db->where('order_by >', $order); 
		$this->db->update('_subcategory');

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function status_subcategory($subcategory_id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('subcategory_id_map', $subcategory_id);
		$this->db->update('_subcategory', $data);
		
		//echo $this->db->last_query();
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}	
	
    function delete_subcategory($subcategory_id){

		$data = array(
				'status' => 2
		);
		$this->db->where('subcategory_id_map', $subcategory_id);
		$this->db->update('_subcategory', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_subcategory_by_admin($subcategory_id){
		$this->db->where('subcategory_id', $subcategory_id);
		$this->db->delete('_subcategory'); 
	}
 
}
?>	
