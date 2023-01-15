<?php if(!defined('BASEPATH'))exit('No direct script access allowed');
class Mapexamquestionlesson_model extends CI_Model {
function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
} 
public function index(){
      echo 'Mapexamquestionlesson_model';
	}
public function map_exam_question_lesson($lesson_id) {      
  $cache_time='60';
  /*
  $sql="select * from mul_map_exam_question_lesson l left join cvs_course_question q on l.question_id=q.id left join cvs_course_examination e on q.exam_id=e.id left join mul_lesson ls on l.lesson_id=ls.lesson_id where l.lesson_id=$lesson_id";
  */
$sql="select * 
,(select mul_category_name from mul_category_2017 where mul_category_id=ls.mul_category_id)as mul_category_name
,(select mul_level_name  from  mul_level  where mul_level_id=ls.mul_level_id)as mul_level_name
 from mul_map_exam_question_lesson l 
 left join cvs_course_question q on l.question_id=q.id 
 left join cvs_course_examination e on q.exam_id=e.id 
 left join mul_lesson ls on l.lesson_id=ls.lesson_id 
 where l.lesson_id=$lesson_id"; 
	$cache_key=$sql;
		//$this->tppymemcached->delete($cache_key);
 $cache_arrResult=$this->tppymemcached->get($cache_key);
	 if($cache_arrResult!==Null) {
			$query=$this->db->query($sql);
			if($query){
				$cache_arrResult['rows']=$query->num_rows();
				$cache_arrResult['list']=$query->result_array(); 
			}else{
				$cache_arrResult = false;
			}
	  $this->tppymemcached->set($cache_key,$cache_arrResult,60);
		 }		
		return $cache_arrResult;
	}
}