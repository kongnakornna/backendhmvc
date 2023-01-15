<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_pagination_model extends CI_Model
{
     var $table = 'sd_user_data';
    
     function getUserRecords($limit, $start)
     {
          $this->db->limit($limit, $start);
          $query = $this->db->get($this->table);
          return $query->result_array();
     }
    
     function countTotalRecord()
     {
         return $this->db->count_all($this->table);
     }

}