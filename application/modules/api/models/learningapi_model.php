<?php
class Learningapi_model extends CI_Model{
private $DBSelect, $DBEdit;
public function __construct(){
parent::__construct();
		 header('Content-Type: text/html; charset=utf-8');
			$CI = & get_instance();
		  //$this->load->database('default');  
	}
public function where_mul_map_content_lessonid($lesson_id){
#echo '<pre> lesson_id=>'; print_r($lesson_id); echo '</pre>';
$sql="SELECT * 
,(select mul_content_text from  mul_content where mul_content_id=map.mul_content_id)as mul_content_text
from mul_map_content_lesson as map  where map.lesson_id=$lesson_id ";


$cache_key = "nnamul_map_content_lesson_'.$sql";
//$this->tppymemcached->delete($cache_key);
$rs=$this->tppymemcached->get($cache_key);
if($rs) {
    $rs=$rs;
}else{   
#############
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
#############
$this->tppymemcached->set($cache_key,$rs,600);
}


//echo '<pre>   $rs=>'; print_r($rs); echo '</pre>';  Die(); 
return $rs; 
}

}
 