<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_model extends CI_Model{
    public $table = '_projects';

    public function get_all() {
		//$this->db->select('*');
		//$this->db->from('_projects');
		//$query = $this->db->get();
		//$result_array=$query->result_array(); 
		//return $result_array;
        return $this->db->get($this->table)->result();
    }
    
    public function get($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }
  
    public function add($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows();
    }

}

/* End of file Project_model.php */
/* Location: ./application/models/Project_model.php */