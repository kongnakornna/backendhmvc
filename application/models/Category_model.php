<?php
class Category_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        //$this->load->database();
    }

    public function get_status($id){
    	$language = $this->lang->language['lang'];
    
    	$this->db->select('category_name, order_by, status');
    	$this->db->from('_category');
    	$this->db->where('category_id_map', $id);
    	$this->db->where('lang', $language);
    	$query = $this->db->get();
    	//Debug($this->db->last_query());    	
    	return $query->result_array();    
    }
        
    public function get_max_order(){

		$language = $this->lang->language['lang'];

		$this->db->select('max(order_by) as max_order');
		$this->db->from('_category');

		$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 

    }

    public function get_max_id(){

		//$language = $this->lang->language['lang'];
		$this->db->select('max(category_id) as max_id');
		$this->db->from('_category');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 

    }

    public function getSelectCat($default = 0,$name = "category_id", &$access_cat = ''){
    		
    		$language = $this->lang->language;

    		$first = "--- ".$language['please_select'].$language['category']." ---";    		
	    	//if($default == 0) $rows = $this->get_category(null, 1);
	    	//else $rows = $this->get_category($default, 1);

			//Debug($this->session->userdata);
			//Debug($access_cat);

			/*if($type == '') 
				$rows = $this->get_category(null, 1);
			else
				$rows = $this->get_category(null, 1, strtolower($type));*/

			$rows = $this->get_category(null, 1);

			//Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
					$row = @$rows[$i];

					if($this->session->userdata('admin_type') > 3){
							if($access_cat){
									foreach($access_cat as $arr => $val){
											//echo "$arr => $val<br>";
											if($row['category_id_map'] == $val) $opt[]	= makeOption($row['category_id_map'], $row['category_name']);
									}
							}
					}else{
							$opt[]	= makeOption($row['category_id_map'], $row['category_name']);
					}
	    	}
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
    }
     
   public function get_category_by_id($category_id_map){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_category');
		$this->db->where('category_id_map', $category_id_map);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		
		//Debug($this->db->last_query());
		return $query->result_array(); 

    }

    public function get_category($category_id=null, $cat_status=null, $type = '', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];

		$this->db->select('_category.category_id');
		$this->db->select('_category.category_id_map');
		$this->db->select('_category.category_name');
		$this->db->select('_category.lang');
		$this->db->select('_category.order_by');
		$this->db->select('_category.create_date');
		$this->db->select('_category.status');
		$this->db->from('_category');

		if($category_id != null && $category_id > 0){
			$this->db->where('category_id', $category_id);
		}
		$this->db->where('lang', $language);
		$this->db->where('status !=', 2);

		/*if($search_string){
			$this->db->like('category_name', $search_string);
		}*/

		if($type != ''){
			if($type == 'column'){
				//$this->db->where('type', $type);
				//$this->db->or_where('type', 'tv');
				$this->db->where("(`type` =  'column' OR `type` =  'tv')");
			}else
				$this->db->where('type', $type);
		}
		
		if($cat_status){
			$this->db->where('status', $cat_status);
		}
		
		/*if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('order_by', 'Asc');
		}*/
		$this->db->order_by('order_by', 'Asc');
		$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		$data1 = $query->result_array();		
		return $data1;
    }

    function count_products($category_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_category');
		if($category_id != null && $category_id != 0){
			$this->db->where('category_id', $category_id);
		}
		if($search_string){
			$this->db->like('category_name', $search_string);
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
		$insert = $this->db->insert('_category', $data);
	    return $insert;
	}

    function update_category($category_id, $data){

		$this->db->where('category_id', $category_id);
		$this->db->update('_category', $data);
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

    function update_category2($category_id, $data){

		$this->db->where('category_id_map', $category_id);
		$this->db->update('_category', $data);
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

	function update_order($category_id, $order = 1){

		$data['order_by'] = $order;
		$this->db->where('category_id_map', $category_id);
		$this->db->update('_category', $data);
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
		$this->db->update('_category');
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
		$this->db->update('_category');
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
		$this->db->update('_category');

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
		$this->db->update('_category');

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function status_category($category_id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('category_id_map', $category_id);
		$this->db->update('_category', $data);
		
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


    function delete_category($category_id){

		$data = array(
				'status' => 2
		);
		$this->db->where('category_id_map', $category_id);
		$this->db->update('_category', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_category_by_admin($category_id){
		$this->db->where('category_id_map', $category_id);
		$this->db->delete('_category'); 
	}
 
}
?>	
