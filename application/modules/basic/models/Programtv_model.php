<?php
class Programtv_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        //$this->load->database();
    }

    public function get_status($id){
    	//$language = $this->lang->language['lang'];
    	$this->db->select('title, status');
    	$this->db->from('_program');
    	$this->db->where('program_id2', $id);
    	//$this->db->where('lang', $language);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    }
        
    public function get_max_order(){
		//$language = $this->lang->language['lang'];
		$this->db->select('max(order_by) as max_order');
		$this->db->from('_program');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_max_id(){
		//$language = $this->lang->language['lang'];
		$this->db->select('max(program_id) as max_id');
		$this->db->from('_program');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 
    }
    
    public function validate_program($keyword){
    
		$this->db->select('*');
		$this->db->from('_program');
		$this->db->where('program_text', trim($keyword));
		$query = $this->db->get();		
		//echo $this->db->last_query();
		return $query->result_object(); 
		
    }
    
    public function getSelect($default = 0,$name = "program_id"){
    		
    		//$language = $this->lang->language;    		
			//$first = "--- ".$language['please_select']." ---";
	    	$rows = $this->get_content_all();
	    	$opt = array();
	    	//$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row['program_id'], $row['program_text']);
	    	}
	    	//return selectList( $opt, $name, 'class="chosen-select"', 'value', 'text',$default);
	    	return MultiSelectList( $opt, $name, 'class="chosen-select"', 'value', 'text',$default);
    }
     
   public function get_content_id($program_id){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_program');
		$this->db->where('program_id2', $program_id);
		//$this->db->where('lang', $language);
		$query = $this->db->get();		
		//echo $this->db->last_query();
		return $query->result_array(); 

    }
     
   public function get_content_in_date($channel_id, $date){

		$language = $this->lang->language['lang'];
		$this->db->select('_program.*, _channel.channel_name');
		$this->db->from('_program');
		$this->db->join('_channel', '_program.channel_id = _channel.channel_id');
		$this->db->where('_program.channel_id', $channel_id);
		$this->db->where('_program.date', $date);
		$this->db->where('_program.lang', $language);

		$this->db->order_by('time');

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		//die();
		return $query->result_array(); 

    }

   public function get_content_all($channel_id = 0, $filter_date = '', $keyword = ''){
		
		$language = $this->lang->language['lang'];

		/*$this->db->select('_program.*, _channel.channel_name, _channel.channel_icon');
		$this->db->from('_program');
		//$this->db->join('_channel', '_program.channel_id = _channel.channel_id2 AND sd_program.lang = sd_channel.lang', 'left');
		$this->db->where('_program.lang', $language);*/

		$this->db->select('_program.*, (SELECT channel_name FROM sd_channel WHERE sd_program.`channel_id` = sd_channel.`channel_id2` AND `sd_channel`.`lang` =  "'.$language.'") AS channel_name');
		$this->db->from('_program');

		$this->db->where('_program.lang', $language);
		$this->db->where('_program.status !=', 2);

		if($filter_date != '') $this->db->where('_program.date', $filter_date);
		if($channel_id != 0) $this->db->where('_program.channel_id', $channel_id);
		if($keyword != '') $this->db->like('_program.title', trim($keyword));

		$this->db->order_by('lastupdate_date', 'DESC');

		$this->db->limit(100, 0);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array(); 
    }

    public function get_content($program_status=null, $order_by='program_id', $order_type='Asc', $limit_start = 0, $listpage = 20){
		
		//$language = $this->lang->language['lang'];
		$this->db->select('_program.program_id');
		$this->db->select('_program.program_text');
		$this->db->select('_program.create_date');
		$this->db->select('_program.status');

		$this->db->from('_program');
		/*if($program_id != null && $program_id > 0){
			$this->db->where('program_id', $program_id);
		}*/
		/*if($search_string){
			$this->db->like('program_text', $search_string);
		}*/
		//if($program_id){
			//$this->db->where('status', $cat_status);
		//}

		//$this->db->where('lang', $language);
		$this->db->order_by($order_by, $order_type);
		$this->db->limit($listpage, $limit_start);
		//$this->db->query('SELECT * FROM `sd_program`');

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

    function count_program($program_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_program');
		if($program_id != null && $program_id != 0){
			$this->db->where('program_id', $program_id);
		}
		if($search_string){
			$this->db->like('program_text', $search_string);
		}

		if($order){
			$this->db->order_by($order, 'Asc');
		}

		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($id = 0, $data){

			if($id > 0){
					$this->db->where('program_id', $id);
					$this->db->update('_program', $data);
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
					$insert = $this->db->insert('_program', $data);
					//echo $this->db->last_query();
					//echo "<hr>";
					return $insert;
			}
	}

    public function add_batch(&$data = array(), $debug = 0){
					if($debug == 1) Debug($data);
					$insert = $this->db->insert_batch('_program', $data);
					if($debug == 1) Debug($this->db->last_query());
					return $insert;
    }

	function status_programtv($program_id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('program_id2', $program_id);
		$this->db->update('_program', $data);
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
	
    function delete_program($program_id){

		$data = array(
				'status' => 2
		);
		$this->db->where('program_id2', $program_id);
		$this->db->update('_program', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_program_by_admin($program_id){
		$this->db->where('program_id', $program_id);
		$this->db->delete('_program'); 
	}
 
}
?>	
