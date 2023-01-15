<?php
class Admin_team_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        //$this->load->database();
    }

 	public function totalteam($startdate=NULL,$enddate= NULL){
  		$this->db->distinct();
		$this->db->select('*');
		$this->db->from('_admin_team');
		if($startdate != null && $enddate != null){
			$this->db->where('create_date BETWEEN "'. $startdate. '" and "'. $enddate.'"');
		}
		$this->db->order_by('create_date', 'desc');
		$this->db->order_by('admin_team_id', 'asc');
  		return $this->db->count_all_results();
 	}
     
    public function get_status($id){
    
    	//$language = $this->lang->language['lang'];
    
    	$this->db->select('admin_team_title, status');
    	$this->db->from('_admin_team');
    	$this->db->where('admin_team_id', $id);
    	//$this->db->where('lang', $language);
    	$query = $this->db->get();
    	
    	//echo $this->db->last_query();
    	
    	return $query->result_array();
    
    }
        
    public function get_max_order(){

		//$language = $this->lang->language['lang'];

		$this->db->select('max(order_by) as max_order');
		$this->db->from('_admin_team');
		//$this->db->where('lang', $language);

		$query = $this->db->get();
		return $query->result_array(); 

    }

    public function get_max_id(){

		//$language = $this->lang->language['lang'];
		$this->db->select('max(admin_team_id) as max_id');
		$this->db->from('_admin_team');
		//$this->db->where('lang', $language);

		$query = $this->db->get();
		return $query->result_array(); 

    }

    public function getSelectCat($default = 0,$name = "admin_team_id"){
    		
    		$language = $this->lang->language;
    		
    		//debug($language);
    		//die();    		
    		$first = "--- ".$language['please_select']." ---";
    		
	    	//if($default == 0) $rows = $this->get_admin_team(null, 1);
	    	//else $rows = $this->get_admin_team($default, 1);
	    	$rows = $this->get_admin_team(null, 1);
	    	
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row['admin_team_id'], $row['admin_team_title']);
	    	}
	    	return selectList( $opt, $name, 'class="form-control"', 'value', 'text',$default);
    }
     
   public function get_admin_team_by_id($admin_team_id){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_admin_team');
		$this->db->where('admin_team_id', $admin_team_id);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function get_admin_team($pageIndex = 1, $limit = 100,$startdate= null,$enddate= null) {
		$language = $this->lang->language['lang'];
		// Turn caching on for this one query
		//$this->db->cache_on();
		// Turn caching off for this one query
		//$this->db->cache_off();
		//$this->db->count_all('_admin_team');
		$this->db->distinct();
		$this->db->select('*');
		$this->db->from('_admin_team');
		//$this->db->join('_hardware', 'sd_hardware.hardware_name = sd_admin_team.sensor_hwname');
		//$this->db->where('create_date', $date); 
		if($startdate != null && $enddate != null){
			$this->db->where('create_date BETWEEN "'. $startdate. '" and "'. $enddate.'"');
		}
		$this->db->order_by('create_date', 'desc');
		$this->db->order_by('admin_team_id', 'asc');
		$this->db->distinct('admin_team_id');
		$this->db->limit($limit, ($pageIndex - 1) * $limit);
        //Clears all existing cache files
		//$this->db->cache_delete_all();
		$query = $this->db->get();

		 //Debug($this->db->last_query());
		 //die();
		return $query->result_array();
	}

    function count_products($admin_team_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_admin_team');
		if($admin_team_id != null && $admin_team_id != 0){
			$this->db->where('admin_team_id', $admin_team_id);
		}
		if($search_string){
			$this->db->like('admin_team_title', $search_string);
		}

		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($id = 0, $data){

			if($id > 0){
					$this->db->where('admin_team_id', $id);
					$this->db->update('_admin_team', $data);
					$report = array();
					////$report['error'] = $this->db->_error_number();
					////$report['message'] = $this->db->_error_message();
					if($report !== 0){
						return true;
					}else{
						return false;
					}					
			}else{
					$insert = $this->db->insert('_admin_team', $data);
					//echo $this->db->last_query();
					return $insert;
			}
	}

    function update_admin_team($admin_team_id, $data){

		$this->db->where('admin_team_id', $admin_team_id);
		$this->db->update('_admin_team', $data);
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function update_order($admin_team_id, $order = 1){

		$data['order_by'] = $order;
		$this->db->where('admin_team_id', $admin_team_id);
		$this->db->update('_admin_team', $data);
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
		$this->db->update('_admin_team');
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
		$this->db->update('_admin_team');
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
		$this->db->update('_admin_team');

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
		$this->db->update('_admin_team');

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function status_admin_team($admin_team_id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('admin_team_id', $admin_team_id);
		$this->db->update('_admin_team', $data);
		
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

    public function get_status2($id){
    
    	//$language = $this->lang->language['lang'];
     //echo $id;die();
    	$this->db->select('*');
    	$this->db->from('_admin');
    	$this->db->where('admin_id', $id);
    	//$this->db->where('lang', $language);
    	$query = $this->db->get();
    	
    	//echo $this->db->last_query();
    	
    	return $query->result_array();
    
    }

	function status_admin_team2($admin_id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('admin_id', $admin_id);
		$this->db->update('_admin', $data);
		
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
    public function get_status3($id){
    
    	//$language = $this->lang->language['lang'];
     //echo $id;die();
    	$this->db->select('*');
    	$this->db->from('_user');
    	$this->db->where('user_id2', $id);
    	//$this->db->where('lang', $language);
    	$query = $this->db->get();
    	
    	//echo $this->db->last_query();
    	
    	return $query->result_array();
    
    }
	function status_admin_team3($user_id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('user_id2', $user_id);
		$this->db->update('_user', $data);
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
    function delete_admin_team($admin_team_id){

		$data = array(
				'status' => 2
		);
		$this->db->where('admin_team_id', $admin_team_id);
		$this->db->update('_admin_team', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}



    function get_admin_teame_edit($admin_team_id=null, $_status=null, $order='order_by', $order_type='Asc', $limit_start = 0, $listpage = 20){
		
		//$language = $this->lang->language['lang'];
	    
		//$this->db->select('_admin_team.admin_team_id');
		//$this->db->select('_admin_team.admin_team_title');
		//$this->db->select('_admin_team.lang');
		//$this->db->select('_admin_team.order_by');
		//$this->db->select('_admin_team.create_date');
		$this->db->select('*');
		$this->db->from('_admin_team');

		if($admin_team_id != null && $admin_team_id > 0){
			$this->db->where('admin_team_id', $admin_team_id);
		}

		//$this->db->where('lang', $language);
		$this->db->where('status =', 1);

		/*if($search_string){
			$this->db->like('admin_team_title', $search_string);
		}*/
		
		if($_status){

			$this->db->where('status', $_status);

		}
		
		/*if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('order_by', $order_type);
		}*/
		//$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		//die();
		
		return $query->result_array();
    }

    
    
    

	function delete_admin_team_by_admin($admin_team_id){
		$this->db->where('admin_team_id', $admin_team_id);
		$this->db->delete('_admin_team'); 
	}
 
}
?>	
