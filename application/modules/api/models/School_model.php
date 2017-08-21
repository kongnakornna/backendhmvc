<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); header('Content-type: text/html; charset=utf-8');

class school_model extends CI_Model {
  
  private $edit;
  public function __construct() {
    parent::__construct();    
    $CI =& get_instance();
    // $this->load->library('tppymemcached');
  }
  public function _getSchool($arrFilter=array()){
		$sql = "select 
					*
					from tbl_school_master s 
					where 1";
		
		$q=isset($arrFilter['q'])  ? $arrFilter['q'] : "";
		if(!empty($q)){
			$sql .=" AND CONCAT_WS(' ', s.school_code, s.school_name, s.school_code_8) LIKE '%".$q."%'";
		}
		$code=isset($arrFilter['schoolcode'])  ? $arrFilter['schoolcode'] : "";
		if(!empty($code)){
			$sql .=" AND s.school_code='".$code."'";
		}
		
		$order = isset($arrFilter['order'])  ? $arrFilter['order'] : ""; 
		if(empty($order)){
			if($order=="schoolcode"){
				$sql .= " order by school_code";
			}elseif($order=="projectcost"){
				$sql .= " order by project_code";
			}
		}else{
			$sql .= " order by school_name asc";
		}
		
		$offset =  isset($arrFilter['offset'])  ? $arrFilter['offset'] : ""; 
		$limit =  isset($arrFilter['limit'])  ? $arrFilter['limit'] : ""; 
		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;	
			}
			}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;	
		}else{
			$sql .= " limit 100";
		}
			
		$qResult = $this->db->query($sql);
		$data = $qResult->result_array();
		return $data;
  }

}