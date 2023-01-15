<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); header('Content-type: text/html; charset=utf-8');

class Management_model extends CI_Model {
  
    function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
    }

    public function get_table($table = "tbl_user_2018",$filters=[],$or_where = null)
    {
        $data = [];
        $this->db->select('*');
        if(isset($filters['select'])){
            $this->db->select($filters['select']);
            unset($filters['select']);
        }
        if(isset($filters['q']) && $filters['q']){
            $this->db->like('CONCAT_WS(" ", user_id, concat(ifnull(salutation,""),ifnull(name,"")," ",ifnull(surname,"")), username, email )', $filters['q']);

        }
        unset($filters['q']);
        if(isset($filters['limit'])){
            if(isset($filters['ofset'])){
              $this->db->limit($filters['limit'],$filters['ofset']);
                unset($filters['ofset']);
            }else{
              $this->db->limit($filters['limit']);
            }
            unset($filters['limit']);
        }
        if(isset($filters['order_by'])){
            $this->db->order_by($filters['order_by'], 'ASC');
            unset($filters['order_by']);
        }
        if(isset($filters['group_by']) && $filters['group_by']){
            $this->db->group_by($filters['group_by']);
            unset($filters['group_by']);
        }
        $this->db->where($filters);
        if(isset($or_where) && $or_where){
            $this->db->where($or_where);
        }
        $this->db->from($table);
        $query_transes = $this->db->get();
        $data = $query_transes->result_object();
        // echo $this->db->last_query();
        return $data;
    }

    public function update_table($data = array())
    {
        $process = $this->db->update($data['table'], $data['update'], $data['where']);
        // $result = $this->db->get_compiled_update();
        if($process){
          return true;
        }else{
          return false;
        }
    }


}