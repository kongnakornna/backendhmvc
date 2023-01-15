<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clipded_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }    
    
    public function get_clipded($clipded_id = 0){

			$this->db->select('c.*, v.title_name as title, v.create_date, v.lastupdate_date, v.countclick as countview, v.lang, p.file_name, p.folder');
			$this->db->from('_clipded c');

			$this->db->join('_video v', 'c.clip_id = v.video_id2 and v.status = 1 and v.approve = 1 and lang = "th"');
			$this->db->join('_picture p', 'v.video_id2  = p.ref_id and p.ref_type = 4 AND p.`default`=1 ', 'left');

			if($clipded_id > 0) $this->db->where('clipded_id', $clipded_id);

			$this->db->order_by('c.order', 'ASC');
			$this->db->order_by('clipded_id', 'DESC');

			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    public function set_clipded($clip_id, $data){
			$this->db->select('*');
			$this->db->from('_clipded');
			$this->db->where('clip_id', intval($clip_id));
			$query = $this->db->get();
			
			if($query->num_rows == 0){
					$insert = $this->db->insert('_clipded', $data);
					//Debug($this->db->last_query());
					//return $insert;
			}else{
					$this->db->set('order', $data['order'], FALSE);
					$this->db->where('clip_id', intval($clip_id));
					$this->db->update('_clipded');
					//Debug($this->db->last_query());
			}
	}

    public function remove_clipded($clip_id = 0){
			$this->db->where('clip_id', intval($clip_id));
			$this->db->delete('_clipded');
			//Debug($this->db->last_query());
	}
}