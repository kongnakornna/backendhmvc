<?php if(!defined('BASEPATH')) exit('No direct script allowed');
class M_main extends CI_Model{
public function  get_user($q) {
		return $this->db->get_where('m_user',$q);
          $sql="select * from m_user where $q"; 
          $query=$this->db->query($sql);
          $dataresult=$query->result();
          echo '<pre> $sql-> '; print_r($sql); echo '</pre>';
          echo '<pre> $dataresult-> '; print_r($dataresult); echo '</pre>'; die();
          
        return $dataresult;     
    }	
}