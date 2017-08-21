<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajaxpagination_model extends CI_Model
{

 //set table name to be used by all functions
 var $table = 'sd_sensor_log';

 function fetch_record($limit, $start)
 {
  $this->db->limit($limit, $start);
  $query = $this->db->get($this->data);
  return ($query->num_rows() > 0)  ? $query->result() : FALSE;

 }

 function record_count()
 {
  return $this->db->count_all_results('data');
 }

}