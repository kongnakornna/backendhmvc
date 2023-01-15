<?php
class Csv_model extends CI_Model{
function __construct() {
        parent::__construct();
  
}   
function select_school_student($limit,$orderby,$citizen_id=null) {   
     $this->db->select('*');
	$this->db->from('tbl_school_student');
     if($citizen_id!==null){$this->db->where('citizen_id',$citizen_id);}
     $this->db->order_by("idx",$orderby);
     $this->db->limit($limit);
     $query = $this->db->get();
     $num_rows=$query->num_rows();
	$rs=$query->result_array(); 
     if($num_rows>0){
          $data=array('rs'=>$rs,'num_rows'=>$num_rows);
          return $data;
     }else{
            return FALSE;
     }
} 
function get_tbl_school_student() {     
        $query = $this->db->get('tbl_school_student');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
}   
function insert_csv($data) {		
#####################################
          $this->db->select('*');
		$this->db->from('tbl_school_student');
		$this->db->where('citizen_id',$data['citizen_id']);
          $this->db->where('std_firstname_en',$data['std_firstname_en']);
          $this->db->where('std_lastname_en',$data['std_lastname_en']);
          $this->db->where('father_citizen_id',$data['father_citizen_id']);
		$query=$this->db->get();
          $num_rows=$query->num_rows();
		$rs=$query->result_array(); 
#####################################       
/*
 echo'<hr><pre>  rs=>';print_r($rs);echo'<pre> <hr>'; 
 echo'<hr><pre>  num_rows=>';print_r($num_rows);echo'<pre> <hr>'; 
 Die(); 	
*/
if($num_rows==0 ||$num_rows==null){
        $this->db->insert('tbl_school_student', $data);
           $datart=(int)'1';
          return $datart;
}else{
#echo'<hr><pre> data=>';print_r($data);echo'<pre> <hr>';  
$idcard=$data['citizen_id'];
$firstname=$data['std_firstname_en'];
$lastname=$data['std_lastname_en'];
#echo'<hr><pre> idcard=>';print_r($idcard);echo'<pre> <hr>'; Die();
$this->db->where('citizen_id',$idcard);
$this->db->where('std_firstname_en',$firstname);
$this->db->where('std_lastname_en',$lastname);
$udaters=$this->db->update('tbl_school_student', $data);
#echo'<hr><pre> udaters=>';print_r($udaters);echo'<pre> <hr>'; 
$datart=(int)'1';
return $datart;
}		
#####################

	
    }
function insert_csv_temp($data){		
#####################################
          $this->db->select('*');
		$this->db->from('tbl_school_student');
		$this->db->where('citizen_id',$data['citizen_id']);
          $this->db->where('std_firstname_en',$data['std_firstname_en']);
          $this->db->where('std_lastname_en',$data['std_lastname_en']);
          $this->db->where('father_citizen_id',$data['father_citizen_id']);
		$query=$this->db->get();
          $num_rows=$query->num_rows();
		$rs=$query->result_array(); 
#####################################       
/*
 echo'<hr><pre>  rs=>';print_r($rs);echo'<pre> <hr>'; 
 echo'<hr><pre>  num_rows=>';print_r($num_rows);echo'<pre> <hr>'; 
 Die(); 	
*/
if($num_rows==0 ||$num_rows==null){
        $this->db->insert('tbl_school_student', $data);
           $datart=(int)'1';
          return $datart;
}
else{
#echo'<hr><pre> data=>';print_r($data);echo'<pre> <hr>';  
$idcard=$data['citizen_id'];
$firstname=$data['std_firstname_en'];
$lastname=$data['std_lastname_en'];
#echo'<hr><pre> idcard=>';print_r($idcard);echo'<pre> <hr>'; Die();
$this->db->where('citizen_id',$idcard);
$this->db->where('std_firstname_en',$firstname);
$this->db->where('std_lastname_en',$lastname);
$udaters=$this->db->update('tbl_school_student', $data);
#echo'<hr><pre> udaters=>';print_r($udaters);echo'<pre> <hr>'; 
$datart=(int)'1';
return $datart;
}		
#####################
  }
}
/*END OF FILE*/