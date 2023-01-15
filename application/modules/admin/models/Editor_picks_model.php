<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Editor_picks_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }    
    
    public function get_editor_picks($id = 0){
			$this->db->select('*');
			$this->db->from('_editor_Picks');
			if($id > 0) $this->db->where('editor_picks_id', $id);
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    public function del_editor_picks($id = 0){
			$this->db->where('editor_Picks_id', $id);
			$this->db->delete('_editor_Picks');
	}

    function set_editor_picks($id = 0, $data){

			$this->db->select('*');
			$this->db->from('_editor_picks');
			$this->db->where('editor_picks_id', $id);
			$query = $this->db->get();
			//Debug($this->db->last_query());
			//Debug($query->num_rows);
			//die();
			if($query->num_rows == 0){
					$insert = $this->db->insert('_editor_picks', $data);
					//Debug($this->db->last_query());
					return $insert;
			}
	}

    function update_order_highlight(){
			$this->db->set('order', '`order`  + 1', FALSE); 
			$this->db->update('_editor_picks');
	}

    function remove_editor_picks($id = 0){
			if($id > 0){
					$this->db->where('editor_picks_id', $id);
					$this->db->where('ref_type', 1);
					$this->db->delete('_editor_picks'); 
			}
	}

}