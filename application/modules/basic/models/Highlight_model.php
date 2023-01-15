<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Highlight_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }    
    
    public function get_highlight($id = 0){
			$this->db->select('*');
			$this->db->from('_highlight');
			if($id > 0) $this->db->where('highlight_id', $id);
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    public function del_highlight($id = 0){
			$this->db->where('highlight_id', $id);
			$this->db->delete('_highlight');
	}

}