<?php
class Zodiac_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        //$this->load->database();
    }

    public function getSelect($default = 0,$name = "zid", $first = ""){
    		
    		$language = $this->lang->language;    		
			$first = "--- ".$language['please_select']." ---";
    		
	    	$rows = $this->get_content();	    	
	    	$opt = array();

			if($first != "" ) $opt[]	= makeOption(0, $first);

	    	//$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row['zid'], $row['zodiac_name']);
	    	}
	    	//return selectList( $opt, $name, 'class="chosen-select"', 'value', 'text',$default);
	    	//return MultiSelectList( $opt, $name, 'class="chosen-select"', 'value', 'text',$default);
	    	return selectList( $opt, $name, 'class="form-control"', 'value', 'text', $default);
    }
     
   public function get_content_id($zid){

		$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_zodiac');
		$this->db->where('zid', $zid);
		//$this->db->where('lang', $language);
		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_array(); 

    }

    public function get_content($status=null, $order_by='zid', $order_type='Asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];

		$this->db->select('*');
		$this->db->from('_zodiac');
		$this->db->order_by('zid', 'ASC');
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

    function store($id = 0, $data){

			if($id > 0){
					$this->db->where('zid', $id);
					$this->db->update('_zodiac', $data);
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
					$insert = $this->db->insert('_zodiac', $data);
					//echo $this->db->last_query();
					return $insert;
			}
	}

    function remove_img($zid, $dataobj = null){

		$data = array(
				'icon' => ''
		);
		$this->db->where('zid', $zid);
		$this->db->update('_zodiac', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    /*function delete_zodiac($zid){

		$data = array(
				'status' => 2
		);
		$this->db->where('zid', $zid);
		$this->db->update('_zodiac', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_zodiac_by_admin($zid){
		$this->db->where('zid', $zid);
		$this->db->delete('_zodiac'); 
	}*/
 
}
?>	
