<?php

#################################
public function getListall($exam_id='',$limit=100,$exam_name='') {
if($exam_id!==Null){
$sql = "SELECT * from cvs_course_examination  where id=$exam_id order by id asc limit $limit ";
}else if($exam_name!==Null){ 
$sql = "SELECT * from cvs_course_examination where  exam_name like '%$exam_name%' order by id asc limit $limit ";
}else{
$sql = "SELECT * from cvs_course_examination order by id asc limit $limit ";
}
#########*****cache se*****#############
$cachetime='600';
$cachekey="key-getListall-".$sql;
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $numrows=$this->db->query($sql)->num_rows();
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'info'=>$cacheinfo);
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'info'=>$cacheinfo);
}
##########*******memcache*******############
return $dataall;
}
public function getcountall($exam_name='') {
if($exam_name!==Null){
$sql = "SELECT count(id) as countrow from cvs_course_examination where  exam_name like '%$exam_name%'";
}else{
$sql = "SELECT count(id) as countrow from cvs_course_examination ";
}
#echo 'sql=>'.$sql;
$data = $this->db->query($sql)->result_array();
$data=$data['0']; 
#########*****cache*****#############
$cache_key = "getcountall_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
$data=$data['0'];
################
$this->tppymemcached->set($cache_key,$rs,300);
}
#########*****cache*****#############
   
            return $data;
        }
public function getcourse_examscore($exam_id='',$member_id='') {
//echo '<pre> $exam_id=>'; print_r($exam_id); echo '</pre>';  
//echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>';  die();
$order_by='desc';
$limit=1;
if($limit>500){$limit=500;}
#########*****cache*****#############
$cache_key = "getcourse_examscore_".$exam_id.'_'.$member_id.$order_by.$limit;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
$this->db->select('score.*,max(score.score_value) as score_max,course.exam_name as examination_name,users_account.psn_id_number as id_number,users_account.psn_firstname as firstname,users_account.psn_lastname as lastname,users_account.psn_display_image as image_member,users_account.user_email');
$this->db->from('cvs_course_exam_score as score'); 
$this->db->join('cvs_course_examination as course', 'score.exam_id=course.id', 'left');
$this->db->join('users_account', 'users_account.member_id=score.member_id', 'left');
$this->db->where('score.exam_id',$exam_id);
$this->db->where('score.member_id',$member_id);
$this->db->group_by('score.id');  
$this->db->order_by("score.score_value",$order_by);
$this->db->limit($limit);
$query = $this->db->get();
			 //echo '<pre> $query=>'; print_r($query); echo '</pre>';  die();
     $data = $query->result();
				 $data=$data['0'];


################
$this->tppymemcached->set($cache_key,$data,300);
}
#########*****cache*****#############
     
     
            return $data;
        }
public function getcourse_examscore_log($exam_id='',$member_id='') {
//echo '<pre> $exam_id=>'; print_r($exam_id); echo '</pre>';  
//echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>';  die();
$order_by='desc';
$limit=100;
if($limit>500){$limit=500;}  
#########*****cache*****#############
$cache_key = "getcourse_examscore_log_".$exam_id.'_'.$member_id.$order_by.$limit;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){

################
				 $this->db->select('score.*,course.exam_name as examination_name,users_account.psn_firstname as firstname,users_account.psn_lastname as lastnam');
     $this->db->from('cvs_course_exam_score as score'); 
    $this->db->join('users_account', 'users_account.member_id=score.member_id', 'left');       
				 $this->db->join('cvs_course_examination as course', 'score.exam_id=course.id', 'left');
				 $this->db->where('score.exam_id',$exam_id);
				 $this->db->where('users_account.member_id',$member_id);
     $this->db->group_by('score.id');  
     $this->db->order_by("score.date_update",$order_by);
     $this->db->limit($limit);
     $query = $this->db->get();
				 //echo '<pre> $query=>'; print_r($query); echo '</pre>';  die();
     $data = $query->result();
				 $data=$data['0'];
################
$this->tppymemcached->set($cache_key,$data,300);
}
#########*****cache*****#############
     
     
     
            return $data;
        }
public function getcourse_examscoremax($exam_id='') {
//echo '<pre> $exam_id=>'; print_r($exam_id); echo '</pre>';  
//echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>';  die();
$order_by='desc';
$limit=10;
if($limit>500){$limit=500;}    
#########*****cache*****#############
$cache_key="getcourse_examscoremax_".$exam_id.'_'.$order_by.$limit;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){

################
$this->db->distinct();
$this->db->select('score.*,max(score.score_value) as score_max,count(question.id) as question_num,course.exam_name as examination_name,users_account.psn_id_number as id_number,users_account.psn_firstname as firstname,users_account.psn_lastname as lastname,users_account.psn_display_image as image_member,users_account.user_email');
$this->db->from('cvs_course_exam_score as score'); 
$this->db->join('cvs_course_examination as course', 'score.exam_id=course.id', 'left');
$this->db->join('users_account', 'users_account.member_id=score.member_id', 'left');
$this->db->join('cvs_course_question as question', 'question.exam_id=score.exam_id', 'left');
$this->db->where('score.exam_id',$exam_id);
$this->db->group_by('score.id');  
$this->db->order_by("score.score_value",$order_by);
$this->db->order_by("score.date_update",'asc');
$this->db->limit($limit);
$query = $this->db->get();
$num_rows=$query->num_rows();  
//echo '<pre> $query=>'; print_r($query); echo '</pre>';  die();
$data = $query->result();
################
$this->tppymemcached->set($cache_key,$data,300);
}
#########*****cache*****#############


            return $data;
        }
public function status_data($id,$enable){
$data['exam_status'] = $enable;
$result_data=$this->db->where('exam_id',$id);
$result_data=$this->db->update('cvs_course_examination',$data);  
//echo '<pre> $result_data=>'; print_r($result_data); echo '</pre>'; Die();
if($result_data=='yes'){
     $result_data='yes';
}else{
     $result_data='no';
}
return $result_data;    
}   
public function update_status($data,$id){
	#echo '<pre>';print_r($id); echo '<pre>';print_r($data); echo '</pre>'; Die();	
	$result_data=$this->db->where('exam_id',$id);
	$result_data=$this->db->update('cvs_course_examination',$data);  
	//debug($result_data);die();
	if($result_data=='yes'){
			$result_data='no';
	}else{
			$result_data='yes';
	}
return $result_data;    
}	
public function get_examination_index() {
				$keyword=@$this->input->get('keyword');
				$sort=@$this->input->get('sort');
				$order=@$this->input->get('order');
				if($order==Null){$order='desc';}
				$status=@$this->input->get('status');
				$perpage=@$this->input->get('per_page');
				if($perpage==Null){$perpage=100;}
				 if($perpage>500){$perpage=500;}
				$offset=@$this->input->get('offset');
				if($offset==Null){$offset=1;}         
#########*****cache*****#############
$cache_key="get_examination_index_".$keyword.'_'.$sort.'_'.$order.'_'.$status.'_'.$perpage.'_'.$offset;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){

################
$this->db->distinct();
$this->db->select('dc.*, ces.exam_status public_status,(SELECT COUNT(id) FROM cvs_course_question cc WHERE cc.exam_id=dc.id) question_count,(select mul_level_name from mul_level where mul_level_id=dc.mul_level_id) level_name,(select mul_category_name from mul_category_2014 where mul_category_id=dc.mul_root_id) subject_name');
$this->db->from('cvs_course_examination as dc'); 
$this->db->join('cvs_course_exam_share as ces', 'ces.exam_id=dc.id', 'left');
$this->db->like('dc.exam_name', $keyword);
$this->db->group_by('dc.id');  
$this->db->order_by("dc.id",$order_by);
$this->db->order_by("dc.exam_add_date",'desc');
$this->db->limit($perpage, $offset);
$query = $this->db->get();
$num_rows=$query->num_rows();  
#echo '<pre> $query=>'; print_r($query); echo '</pre>';  die();
$data=$query->result();
################
$this->tppymemcached->set($cache_key,$data,300);
}
#########*****cache*****#############
return $data;
}
public function get_examination_user_log() {
				$exam_id=@$this->input->get('exam_id');
				$member_id=@$this->input->get('member_id');
				$user_id=@$this->input->get('user_id');
				$order_by=@$this->input->get('order');
				if($order_by==Null){$order_by='desc';}
				$perpage=@$this->input->get('per_page');
				if($perpage==Null){$perpage='100';}
				$offset=@$this->input->get('offset');
				if($offset==Null){$offset='0';}

      
#########*****cache*****#############
$cache_key="get_examination_user_log_".$exam_id.'_'.$member_id.'_'.$user_id.'_'.$order_by.'_'.$perpage.'_'.$offset;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){

################
				 $this->db->distinct();
				 $this->db->select('score.id as log_id
				 ,score.exam_id	
				 ,users_account.user_id
				 ,mul_category.mul_category_id
				 ,mul_category.mul_parent_id
				 ,mul_level.mul_level_id as mul_level_id
				 ,mul_category.mul_category_name as mul_category_name
				 ,mul_level.mul_level_name as mul_level_name
				 ,course.exam_name as examination_name
				 ,count(question.id) as question_total
				 ,score.score_value as score
				 ,course.exam_time as examination_setting_time_min 
				 ,score.duration_sec as examination_durationtime_sec
				 ,course.exam_percent as  percent_pass
				 ,score.answer_value as answer_json
				 ,score.date_update as log_date
				 ,score.exam_type as examinationtype
				 ,users_account.psn_display_name as  user_display_name
				 ,users_account.user_username as user_username
				 ,users_account.user_email as user_email
				 ,users_account.psn_display_image as user_image
				 ');
                 $this->db->from('cvs_course_exam_score as score'); 
                 $this->db->join('users_account', 'users_account.member_id=score.member_id', 'left');  
				 //$this->db->join('users_account', 'users_account.user_id=score.user_id', 'left');  
				 $this->db->join('cvs_course_examination as course', 'score.exam_id=course.id', 'left');
				 $this->db->join('mul_level', 'mul_level.mul_level_id=course.mul_level_id', 'left');
				 $this->db->join('cvs_course_question as question', 'question.exam_id=score.exam_id', 'left');
				 $this->db->join('mul_category_2014 as mul_category', 'mul_category.mul_category_id=course.mul_root_id', 'left');
				 if($member_id==Null){}else{$this->db->where('users_account.member_id',$member_id);}
				 if($user_id==Null){}else{$this->db->where('users_account.user_id',$user_id);}
				 $this->db->where('question.exam_id IS NOT NULL');
                 $this->db->group_by('score.id');  
                 $this->db->order_by("score.date_update",$order_by);
                 $this->db->limit($perpage,$offset);
                 $query = $this->db->get();
				 $num_rows=$query->num_rows();  
     $data= $query->result();
				 /*
				  echo '<pre> $exam_id=>'; print_r($exam_id); echo '</pre>';  
				  echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>';
				  echo '<pre> $order_by=>'; print_r($order_by); echo '</pre>';
				  echo '<pre> $perpage=>'; print_r($perpage); echo '</pre>';
				  echo '<pre> $offset=>'; print_r($offset); echo '</pre>';
				  echo '<pre> query'; print_r($query); echo '</pre>';  //die();
				  echo '<pre> data=>'; print_r($data); echo '</pre>';  die();
				  */
################
$this->tppymemcached->set($cache_key,$data,300);
}
#########*****cache*****#############
      
      
            return $data;
        }
public function get_question_user_log($exam_id,$member_id){
$sql = "SELECT * from cvs_course_question where  exam_id=$exam_id amd member_id=$member_id";
#echo 'sql=>'.$sql;           
#########*****cache*****#############
$cache_key = "get_question_user_log_".$exam_id.$member_id.$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$data=$this->db->query($sql)->result_array();
################
$this->tppymemcached->set($cache_key,$data,300);
}
#########*****cache*****#############
            
            
            return $data;
        }
public function get_course_exam_scorelistalllog(){
$member_id=@$this->input->get('member_id');
$user_id=@$this->input->get('user_id');
if($user_id==Null){
$sql = "SELECT COUNT(DISTINCT exam_id) AS row FROM cvs_course_exam_score where member_id=$member_id";
}else{
$sql = "SELECT COUNT(DISTINCT exam_id) AS row FROM cvs_course_exam_score where user_id=$user_id";
}
//echo 'sql=>'.$sql; Die();
#########*****cache*****#############
$cache_key = "get_course_exam_scorelistalllog_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$data = $this->db->query($sql)->result_array();
$data=$data['0'];
################
$this->tppymemcached->set($cache_key,$data,300);
}
#########*****cache*****#############
   
   
			 //echo '<pre>data=>';print_r($data); echo '</pre>'; Die();	
            return $data;
        }
public function get_examinationgraphbycourse($exam_id='',$startdate='',$enddate=''){
#$startdate=strtotime($startdate); $enddate=strtotime($enddate);
#echo '$startdate=>'.$startdate.' $enddate=>'.$enddate; die();
$startdate=gmdate("Y-m-d", $startdate);
$enddate=gmdate("Y-m-d", $enddate);
$limit=200;
$sql = "SELECT
        AA.idx
		,u.user_id
        ,u.user_username
        ,u.psn_display_name
        ,u.user_email
        ,AA.score_value*1 score_value
        ,AA.duration_sec
        ,AA.date_update
		,AA.exam_id
		,ce.exam_name
        from ( 
        select
        a.id idx
        ,a.member_id
        ,score_value score_value
        ,duration_sec
        ,date_update
		,exam_id
         FROM `cvs_course_exam_score` a
        where exam_id=$exam_id
        AND date_update BETWEEN '$startdate 00:00:00' AND '$enddate 23:59:59'
        ) AA  
        left join  `users_account` u on AA.member_id=u.member_id
		left join `cvs_course_examination` ce on AA.exam_id=ce.id
        order by score_value desc,duration_sec asc,date_update asc limit $limit
      "; 
			  //echo 'sql=>'.$sql; Die();
            $data = $this->db->query($sql)->result_array();
			 //echo '<pre>data=>';print_r($data); echo '</pre>'; Die();	
            return $data;
        }
public function get_examinationgraphbycourse_row(){
$exam_id=@$this->input->get('exam_id');
$sql = "select * from `cvs_course_examination` where id=$exam_id";
//echo 'sql=>'.$sql; Die();
#########*****cache*****#############
$cache_key="get_examinationgraphbycourse_row_".$exam_id.$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
################
			$query=$this->db->query($sql);
			$data= $query->result(); 
			$data=$data['0'];
			$num_rows=$query->num_rows();  
################
$this->tppymemcached->set($cache_key,$data,300);
}
#########*****cache*****#############
         
//echo '<pre>data=>';print_r($data); echo '</pre>'; Die();	
return $data;
}
public function get_examinationgraphbycourse_scoreallanswer($exam_id='',$startdate='',$enddate=''){
$exam_id=@$this->input->get('exam_id');
$startdate=gmdate("Y-m-d", $startdate);
$enddate=gmdate("Y-m-d", $enddate);
$sql = "SELECT  count( score_value ) as totalscore FROM `cvs_course_exam_score` WHERE `exam_id` ='$exam_id' AND date_update BETWEEN '$startdate 00:00:00' AND '$enddate 23:59:59' GROUP BY score_value";
//echo 'sql=>'.$sql; Die();
#########*****cache*****#############
$cache_key="get_examinationgraphbycourse_scoreallanswer_".$exam_id.$startdate.$enddate.$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
			$query=$this->db->query($sql);
			$data= $query->result(); 
			$num_rows=$query->num_rows();  
################
$this->tppymemcached->set($cache_key,$data,300);
}
#########*****cache*****#############
 
 
           
//echo '<pre>data=>';print_r($data); echo '</pre>'; Die();	
return $data;
}
public function get_examinationgraphbycourse_count(){
$exam_id=@$this->input->get('exam_id');
$sql = "SELECT count(*) as total from cvs_course_question where exam_id=$exam_id";
			#echo 'sql=>'.$sql; Die();
			$query=$this->db->query($sql);
			$data= $query->result(); 
			$num_rows=$query->num_rows();  
            $data=$data['0'];
			 //echo '<pre>data=>';print_r($data); echo '</pre>'; Die();	
            return $data;
        } 
###2017-10
public function get_lesson($limit=50,$id=''){
 if($id==Null){
$sql="SELECT *,(select mul_category_name from mul_category where mul_category_id=lesson.mul_category_id) v1_cat_super_name
   ,(select mul_level_name from mul_level where mul_level_id=lesson.mul_level_id) mul_level_name
   from mul_lesson as lesson  order by lesson_id asc limit $limit ";
   #echo '<pre>  $sql=>';print_r($sql); echo '<pre>'; die();
#########*****cache*****#############
$cache_key = "join_mul_level_mul_mul_lesson0_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {

################
   $rsdata= $this->db->query($sql);
   $rs=$rsdata->result_array();
   $numrows=$this->db->query($sql)->num_rows();
   $data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows,);

################
$this->tppymemcached->set($cache_key,$data,300);
}
#########*****cache*****#############
   
   
   #echo '<pre> data=>'; print_r($data); echo '</pre>';  Die(); 
  
 }else{
  $sql="SELECT *,(select mul_category_name from mul_category where mul_category_id=lesson.mul_category_id) v1_cat_super_name
   ,(select mul_level_name from mul_level where mul_level_id=lesson.mul_level_id) mul_level_name
   from mul_lesson as lesson  where lesson.lesson_id=$id order by lesson_id asc limit $limit";
   #echo '<pre>  $sql=>';print_r($sql); echo '<pre>'; die();

   
#########*****cache*****#############
$cache_key = "join_mul_level_mul_mul_lesson1_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {

################
   $rsdata= $this->db->query($sql);
   $rs=$rsdata->result_array();
   $numrows=$this->db->query($sql)->num_rows();
   $data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows,);

################
$this->tppymemcached->set($cache_key,$data,300);
}
#########*****cache*****#############
   
   
   
   #echo '<pre> data=>'; print_r($data); echo '</pre>';  Die(); 
 }

return $data; 
}   
public function where_mul_map_level_lesson($id,$type){
if($type==1){
$sql="SELECT lesson_id,mul_level_id
,(select mul_level_name from mul_level where mul_level_id=map.mul_level_id) mul_level_name 
,(select lesson_name from mul_lesson where lesson_id=map.lesson_id) lesson_name 
from mul_map_level_lesson as map  where map.mul_level_id=$id order by  map.lesson_id asc";
 }elseif($type==2){
$sql="SELECT lesson_id,mul_level_id
,(select mul_level_name from mul_level where mul_level_id=map.mul_level_id) mul_level_name 
,(select lesson_name from mul_lesson where lesson_id=map.lesson_id) lesson_name 
from mul_map_level_lesson as map  where map.lesson_id=$id order by  map.mul_level_id asc";
} 

#########*****cache*****#############
$cache_key = "join_mul_level_mul_map_level_lesson_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {

################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows,);


################
$this->tppymemcached->set($cache_key,$rs,300);
}
#########*****cache*****#############



#echo '<pre>   sql=>'; print_r($sql); echo '</pre>';  Die(); 
return $rs; 
}        
#####################**2017-10-03**#####################
public function where_map_examquestionlesson($id,$type){
if($type==1){
$sql="select * 
,(select exam_name from  cvs_course_examination  where id=q.exam_id)as examname
,(select mul_category_name from  mul_category_2017  where mul_category_id=ls.mul_category_id)as category_name
,(select mul_level_name from  mul_level where mul_level_id=ls.mul_level_id)as level_name
from mul_map_exam_question_lesson l 
left join cvs_course_question q on l.question_id=q.id
left join cvs_course_examination e on q.exam_id=e.id
left join mul_lesson ls on l.lesson_id=ls.lesson_id
where l.lesson_id=$id order by idx asc";
 }elseif($type==2){
/*
$sql="select distinct e.exam_name,q.question_detail,ans.answer_detail,ans.answer_ans as answer,e.exam_percent,q.exam_id,q.id as qid
,(select mul_level_name from  mul_level where mul_level_id=ls.mul_level_id)as level_name
,(select mul_category_name from  mul_category_2017  where mul_category_id=ls.mul_category_id)as category_name
from mul_map_exam_question_lesson l 
left join cvs_course_question q on l.question_id=q.id
left join cvs_course_answer ans on ans.question_id=q.id
left join cvs_course_examination e on q.exam_id=e.id
left join mul_lesson ls on l.lesson_id=ls.lesson_id
where l.question_id=$id and ans.answer_ans='true' order by idx asc";
*/ 
// $sql="select ans.answer_detail,ans.answer_ans as ans,ls.lesson_name,l.idx,q.exam_id,l.question_id,l.lesson_id 
 $sql="select *
,(select exam_name from  cvs_course_examination  where id=q.exam_id)as examname
,(select mul_category_name from  mul_category_2017  where mul_category_id=ls.mul_category_id)as category_name
,(select mul_level_name from  mul_level where mul_level_id=ls.mul_level_id)as level_name
from mul_map_exam_question_lesson l 
left join cvs_course_question q on l.question_id=q.id
left join cvs_course_answer ans on ans.question_id=q.id
left join cvs_course_examination e on q.exam_id=e.id
left join mul_lesson ls on l.lesson_id=ls.lesson_id
where l.question_id=$id ";
 
} 


$cache_key = "join_cvs_course_examination_mul_map_exam_question_lesson_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
#$data = array('sql'=>$sql,'numrows'=>$numrows,'rs'=>$rs,);
$data = array('numrows'=>$numrows,'rs'=>$rs,);
################
$this->tppymemcached->set($cache_key,$data,3600);
}




#echo '<pre>   sql=>'; print_r($sql); echo '</pre>';  Die(); 
return $data; 
}
public function where_course_examination($id){
$sql="select * 
,(select mul_category_name from  mul_category_2017  where mul_category_id=exa.mul_root_id)as category_name
,(select mul_level_name from  mul_level where mul_level_id=exa.mul_level_id)as level_name
,(select count(id) from cvs_course_question where exam_id=exa.id)as question_total
from cvs_course_examination as exa where exa.id=$id";
$cache_key = "where_course_examination_category_level_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows,);
################
$this->tppymemcached->set($cache_key,$rs,3600);
}
 
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows,);
################

 #echo '<pre>   sql=>'; print_r($sql); echo '</pre>';  Die(); 
return $rs; 
}  
public function where_question($id){
$sql="select distinct q.*
,(select exam_name from  cvs_course_examination where id=q.exam_id)as exam_name
from cvs_course_question as q where q.exam_id=$id order by q.id asc";

 
$cache_key = "na_cvs_course_questionss_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {

################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'numrows'=>$numrows,'rs'=>$rs);
################
$this->tppymemcached->set($cache_key,$data,3600);
}
/*
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'numrows'=>$numrows,'rs'=>$rs);
################
*/
#echo '<pre>   sql=>'; print_r($sql); echo '</pre>';  Die(); 
return $data; 
} 
public function where_question_id($id){
$sql="select distinct q.*
,(select exam_name from  cvs_course_examination where id=q.exam_id)as exam_name
from cvs_course_question as q where q.id='$id' ";

 
$cache_key = "where_question_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){

################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'numrows'=>$numrows,'rs'=>$rs);
################
$this->tppymemcached->set($cache_key,$data,3600);
}
 
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'numrows'=>$numrows,'rs'=>$rs);
################
#echo '<pre>   sql=>'; print_r($sql); echo '</pre>';  Die(); 
return $data; 
} 
public function where_map_choice($id){
$sql="select ans.id as ans_id,ans.answer_detail as detail,ans.answer_ans as answer
,(select question_detail from  cvs_course_question where id=ans.question_id)as question_detail
from cvs_course_answer as ans where question_id='$id' order by id asc";


$cache_key = "cache_where_map_choice_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){

################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'numrows'=>$numrows,'rs'=>$rs);
################
$this->tppymemcached->set($cache_key,$data,3600);
}

#echo '<pre>   sql=>'; print_r($sql); echo '</pre>';  Die(); 
return $data; 
}
public function where_map_choice_all($id){
$sql="select question_id as id_question,ans.id as id_ans,ans.answer_detail as detail_answer,ans.answer_ans as status_answer,answer_comment
,(select question_detail from  cvs_course_question where id=ans.question_id)as detail_question
from cvs_course_answer as ans where question_id='$id' order by id asc";

$cache_key = "na_cvs_course_answer_where_map_choice_all_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){

################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'numrows'=>$numrows,'rs'=>$rs);
################
$this->tppymemcached->set($cache_key,$data,3600);
}


#echo '<pre>   sql=>'; print_r($sql); echo '</pre>';  Die(); 


return $data; 
} 
public function where_map_choice_true($id){
$sql="select question_id as id_question,ans.id as id_ans,ans.answer_detail as detail_answer,ans.answer_ans as status_answer,answer_comment
,(select question_detail from  cvs_course_question where id=ans.question_id)as detail_question
from cvs_course_answer as ans where question_id=$id  and answer_ans='true' order by id asc";
$cache_key = "na_cvs_course_answer_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'numrows'=>$numrows,'rs'=>$rs);
################
$this->tppymemcached->set($cache_key,$data,3600);
}


#echo '<pre>   sql=>'; print_r($sql); echo '</pre>';  Die(); 


return $data; 
} 
public function where_mul_lesson_id($id){
$sql="SELECT *
,(select mul_category_name from mul_category where mul_category_id=lesson.mul_category_id) v1_cat_super_name
,(select mul_level_name from mul_level where mul_level_id=lesson.mul_level_id) mul_level_name
from mul_lesson as lesson  where lesson.lesson_id=$id order by lesson_id asc ";
#echo '<pre>  $sql=>';print_r($sql); echo '<pre>'; die();

$cache_key = "mul_lesson_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){

################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
#$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows,);
$data = array('rs'=>$rs,'numrows'=>$numrows);
################
     $this->tppymemcached->set($cache_key,$data,300);
}
 



#echo '<pre> data=>'; print_r($data); echo '</pre>';  Die(); 
return $data; 
}   
public function where_examination_id($id){
#$sql="select ls.lesson_id,ls.lesson_name  
$sql="select ls.mul_level_id,ls.mul_category_id,ls.lesson_id,ls.lesson_name,map_content.mul_content_id
,(select mul_content_subject from  mul_content where mul_content_id=map_content.mul_content_id)as  content_subject
,(select mul_level_name from  mul_level where mul_level_id=ls.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=ls.mul_category_id)as mul_category_name
,(select name from  mul_education_book where mul_education_book_id=ls.mul_education_book_id)as book_name
from mul_map_exam_question_lesson l 
left join mul_lesson ls on l.lesson_id=ls.lesson_id 
left join mul_map_content_lesson map_content on map_content.lesson_id=l.lesson_id 
where l.question_id=$id";
#echo '<pre>  $sql=>';print_r($sql); echo '<pre>'; 
$cache_key = "namul_map_content_lesson_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
#############
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('rs'=>$rs,'numrows'=>$numrows,);
#############
$this->tppymemcached->set($cache_key,$data,300);
}
#echo '<pre> sql=>';print_r($sql); echo '<pre>'; 
#echo '<pre> data=>'; print_r($data); echo '</pre>'; // Die(); 
return $data; 
} 
public function where_count_lesson_mul_map_exam_question_lesson_exam_id1($id){
#$sql="select ls.lesson_id,ls.lesson_name  
$sql="select q.exam_id,map.*,q.question_detail as question
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map.lesson_id)as count_lesson
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select lesson_name from mul_lesson where lesson_id=map.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select mul_level_id from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_id
,(select mul_category_id from mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_id
,(select mul_education_book_id from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as mul_education_book_id
,(select id from  cvs_course_examination where id=exam.id)as exam_id
from mul_map_exam_question_lesson as map   
left join cvs_course_question as q on q.id=map.question_id
left join cvs_course_examination as exam on exam.id=q.exam_id
left join cvs_course_answer as ans on q.id=ans.question_id
left join mul_lesson as lesson on lesson.lesson_id=map.lesson_id
where q.exam_id=$id and ans.answer_ans='true'";
#echo '<pre>  $sql=>';print_r($sql); echo '<pre>'; 
$cache_key = "where_count_lesson_mul_map_exam_question_lesson_exam_id1_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
#############
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('rs'=>$rs,'sql'=>$sql,'numrows'=>$numrows,);
#############
$this->tppymemcached->set($cache_key,$data,300);
}
#echo '<pre> sql=>';print_r($sql); echo '<pre>'; 
#echo '<pre> data=>'; print_r($data); echo '</pre>'; // Die(); 
return $data; 
} 
public function where_count_lesson_mul_map_exam_question_lesson_exam_id($id){
#$sql="select ls.lesson_id,ls.lesson_name  
$sql="select distinct map_eql.lesson_id 
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map_eql.lesson_id)as count_lesson
,(select lesson_name from mul_lesson where lesson_id=map_eql.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
,(select name from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as book_name
,(select mul_level_id from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_id
,(select mul_category_id from mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_id
,(select mul_education_book_id from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as mul_education_book_id
,(select id from  cvs_course_examination where id=exam.id)as exam_id
from  mul_map_exam_question_lesson as map_eql   
left join mul_lesson as lesson on lesson.lesson_id=map_eql.lesson_id
left join cvs_course_question as question on question.id=map_eql.question_id
left join cvs_course_examination as exam on exam.id=question.exam_id
where exam.id=$id";
#echo '<pre>  $sql=>';print_r($sql); echo '<pre>'; 
$cache_key = "where_count_lesson_mul_map_exam_question_lesson_exam_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
#############
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('rs'=>$rs,'numrows'=>$numrows,);
#############
$this->tppymemcached->set($cache_key,$data,300);
}
#echo '<pre> sql=>';print_r($sql); echo '<pre>'; 
#echo '<pre> data=>'; print_r($data); echo '</pre>'; // Die(); 
return $data; 
} 
public function where_mul_map_content_lessonid($lesson_id){
#echo '<pre> lesson_id=>'; print_r($lesson_id); echo '</pre>';
$sql="SELECT * 
,(select mul_content_text from  mul_content where mul_content_id=map.mul_content_id)as mul_content_text
from mul_map_content_lesson as map  where map.lesson_id=$lesson_id ";
$cache_key = "where_mul_map_content_lessonid_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
#############
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
#############
$this->tppymemcached->set($cache_key,$data,300);
}
//echo '<pre>   $rs=>'; print_r($rs); echo '</pre>';  Die(); 
return $data; 
}
public function where_mul_map_exam_question_lesson_content($lesson_id){
#echo '<pre> lesson_id=>'; print_r($lesson_id); echo '</pre>';
$sql="SELECT lesson.lesson_name as lesson_name_parent,lesson.lesson_id,lesson.lesson_parent_id,lesson.mul_content_id
,(select lesson_name from mul_lesson where lesson_id=lesson.lesson_parent_id)as lesson_name
,(select mul_content_text from  mul_content where mul_content_id=content_lesson.mul_content_id)as mul_content_text
from mul_map_exam_question_lesson as map  
left join mul_lesson as lesson on lesson.lesson_id=map.lesson_id
left join mul_map_content_lesson as content_lesson on content_lesson.lesson_id=map.lesson_id
where map.lesson_id=$lesson_id ";
$cache_key = "where_mul_map_exam_question_lesson_content_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
#############
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
#############
$this->tppymemcached->set($cache_key,$data,300);
}
//echo '<pre>   $rs=>'; print_r($rs); echo '</pre>';  Die(); 
return $data; 
}
public function where_map_examquestion_examlessontrue($exam_id,$lesson_id){
#echo '<pre> lesson_id=>'; print_r($lesson_id); echo '</pre>';
$sql="select q.exam_id,map.*,q.question_detail as question,q.question_detail as question_detail,ans.id as id_ans,ans.answer_ans,ans.answer_detail  as answer
,lesson.lesson_name,exam.exam_name
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select lesson_name from mul_lesson where lesson_id=map.lesson_id)as lesson_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
from mul_map_exam_question_lesson as map   
left join cvs_course_question as q on q.id=map.question_id
left join cvs_course_examination as exam on exam.id=q.exam_id
left join cvs_course_answer as ans on q.id=ans.question_id
left join mul_lesson as lesson on lesson.lesson_id=map.lesson_id
where q.exam_id=$exam_id and ans.answer_ans='true' and map.lesson_id=$lesson_id";
$cache_key = "where_mul_map_exam_questionss_exam_id_lesson_id_true_".$sql;
//$this->tppymemcached->delete($cache_key); 
$data=$this->tppymemcached->get($cache_key);
if(!$data){
#############
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
#############
$numrows=$this->db->query($sql)->num_rows();
$data = $rs; //array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
$this->tppymemcached->set($cache_key,$data,300);
}
//echo '<pre>   $rs=>'; print_r($rs); echo '</pre>';  Die(); 
return $data; 
}
public function where_mul_map_exam_question_lesson_id($lesson_id){
#echo '<pre> lesson_id=>'; print_r($lesson_id); echo '</pre>';
$sql="select * from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.lesson_id=$lesson_id ";
$cache_key = "where_mul_map_exam_question_lesson_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
#############
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
#############
$this->tppymemcached->set($cache_key,$data,300);
}
//echo '<pre>   $data=>'; print_r($data); echo '</pre>';  Die(); 
return $data; 
}
public function where_mul_map_exam_question_lesson_id_exam_id($lesson_id,$exam_id){
$sql="select * from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.lesson_id=$lesson_id and  q.exam_id=$exam_id";
$cache_key = "where_mul_map_exam_question_lesson_id_exam_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
#############
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
#############
$this->tppymemcached->set($cache_key,$data,300);
}
//echo '<pre>   $data=>'; print_r($data); echo '</pre>';  Die(); 
return $data; 
}
public function scoredisplay_exam_score($exam_id){
		$sql = "SELECT COUNT(*) cnt FROM cvs_course_question WHERE exam_id=$exam_id";
		$cache_key = "QuestionCNT_ScoreDisplay_exam_score_member2018_".$sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$question_total = $this->tppymemcached->get($cache_key)) {
			$question_total=$this->db->query($sql)->row()->cnt;
			$this->tppymemcached->set($cache_key, $question_total,600);
		}
		$questiontotal=$question_total;
		#$total_answer_percent=($total_answer_true*100)/$question_total;
       
$sql2 = "SELECT score_value, count( score_value ) as total 
FROM cvs_course_exam_score 
WHERE exam_id='$exam_id' 
GROUP BY score_value";
$cache_key = "ScoreValue_ScoreDisplay_exam_score_members2018_".$sql2;
		//$this->tppymemcached->delete($cache_key);
		if (!$scoreAllAnswer = $this->tppymemcached->get($cache_key)) {
			$scoreAllAnswer = $this->db->query($sql2)->result_array();
			$this->tppymemcached->set($cache_key, $scoreAllAnswer,600);
		}
      
      if ($scoreAllAnswer) {
          foreach ($scoreAllAnswer as $value) {
              $statScore[$value['score_value']] = $value['total'];
          }
      }

      for ($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
      }
      
  //echo '<pre>$scoreArr=>'; print_r($scoreArr); echo '</pre>';   Die(); 
  
     $data = ($scoreArr);
     
return $data; 
}
public function scoredisplay_exam_score_school($exam_id,$schoolname='',$level=''){
		$sql = "SELECT COUNT(*) cnt FROM cvs_course_question WHERE exam_id=$exam_id";
		$cache_key = "QuestionCNT_ScoreDisplay_exam_score_school_memberm1_".$sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$question_total = $this->tppymemcached->get($cache_key)) {
			$question_total=$this->db->query($sql)->row()->cnt;
			$this->tppymemcached->set($cache_key, $question_total,300);
		}
		$questiontotal=$question_total;
		#$total_answer_percent=($total_answer_true*100)/$question_total;
 if($schoolname==Null&& $level==Null){
  $sql2 = "SELECT score_value, count( score_value ) as total 
FROM cvs_course_exam_score 
WHERE exam_id='$exam_id' 
GROUP BY score_value";
 }else if($schoolname!==Null && $level==Null){
  $sql2 = "SELECT score_value, count( score_value ) as total 
FROM cvs_course_exam_score 
WHERE exam_id='$exam_id' and user_id in (select user_id from users_account where job_edu_name='$schoolname')
GROUP BY score_value";
 }else if($schoolname!==Null && $level!==Null){
  $sql2 = "SELECT score_value, count( score_value ) as total 
FROM cvs_course_exam_score 
WHERE exam_id='$exam_id' and user_id in (select user_id from users_account where job_edu_name='$schoolname' and job_edu_level='$level')
GROUP BY score_value";
 }       
$cache_key = "ScoreValue_ScoreDisplay_exam_score_school_memberm1_".$sql2;
		//$this->tppymemcached->delete($cache_key);
		if (!$scoreAllAnswer = $this->tppymemcached->get($cache_key)) {
			$scoreAllAnswer = $this->db->query($sql2)->result_array();
			$this->tppymemcached->set($cache_key, $scoreAllAnswer,60);
		}
      
      if ($scoreAllAnswer) {
          foreach ($scoreAllAnswer as $value) {
              $statScore[$value['score_value']] = $value['total'];
          }
      }

      for ($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
      }
      
  //echo '<pre>$scoreArr=>'; print_r($scoreArr); echo '</pre>';   Die(); 
  
     $data = ($scoreArr);
     
return $data; 
}
public function scoredisplay_exam_score_and_course_exam_score_member($exam_id,$job_edu_name,$job_edu_level){
		$sql = "SELECT COUNT(*) cnt FROM cvs_course_question WHERE exam_id=$exam_id";
		$cache_key = "QuestionCNT_ScoreDisplay_examscoremembers1s_$sql";
		#$this->tppymemcached->delete($cache_key);
		if (!$question_total = $this->tppymemcached->get($cache_key)) {
			$question_total=$this->db->query($sql)->row()->cnt;
			$this->tppymemcached->set($cache_key, $question_total,300);
		}
		$questiontotal=$question_total;
 /*   
$sql = "SELECT score.score_value, count(score.score_value ) as total 
FROM cvs_course_exam_score as  score
left join cvs_course_examination as exam on exam.id=score.exam_id
left join users_account as users on users.member_id=score.member_id
WHERE score.exam_id=$exam_id and users.job_edu_name='$job_edu_name' and users.job_edu_level='$job_edu_level'  
GROUP BY score.score_value  order by  score.score_value desc ";
*/
 
$sql="SELECT score.score_value, count(score.score_value ) as total FROM cvs_course_exam_score as  score left join cvs_course_examination as exam on exam.id=score.exam_id left join users_account as users on users.member_id=score.member_id WHERE score.exam_id=$exam_id and users.job_edu_name='$job_edu_name' and users.job_edu_level='$job_edu_level' GROUP BY score.score_value  order by  score.score_value desc";
 
$cache_key = "scoredisplay_exam_score_and_courseexamscoremembers1s_$sql";
		#$this->tppymemcached->delete($cache_key);
		if (!$scoreAllAnswer = $this->tppymemcached->get($cache_key)) {
			$query2 = $this->db->query($sql)->result_array();
			$this->tppymemcached->set($cache_key, $query2,300);
		}
      
      if ($scoreAllAnswer) {
          foreach ($scoreAllAnswer as $value) {
              $statScore[$value['score_value']] = $value['total'];
          }
      }

      for ($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
      }

  //echo '<pre>$sql=>'; print_r($sql); echo '</pre>';  
  //echo '<pre>$scoreArr=>'; print_r($scoreArr); echo '</pre>';   //Die(); 
  
     $data = ($scoreArr);
     
return $data; 
}
public function where_cvs_course_exam_score_member($exam_id,$member_id='',$order='desc',$limit='1'){
 if($member_id==Null){
  //$sql="SELECT distinct * from cvs_course_exam_score as score  where score.exam_id='$exam_id' order by score.id desc";

########################
if($order=='asc'){
$sql="select distinct *,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  where score.exam_id=$exam_id  order by score.id asc limit 1";
}else{
$sql="select distinct *,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  where score.exam_id=$exam_id  order by score.id desc limit 1"; 
}
########################
  
 }else{
#################################### 
if($order=='asc'){
$sql="select distinct *,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  where score.exam_id=$exam_id and score.member_id='$member_id' order by score.id asc limit 1";
}else{
$sql="select distinct *,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  where score.exam_id=$exam_id and score.member_id='$member_id' order by score.id desc limit 1";
}
#################################### 
 }
 #echo '<pre>   $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "namExaminationcvs_course_exam_score_".$sql;
$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
$rsdata= $this->db->query($sql);
$numrows=$this->db->query($sql)->num_rows();
$rss=$rsdata->result_array();
$data=array('sql'=>$sql,'rs'=>$rss,'numrows'=>$numrows,);
//echo '<pre>dataqq=>'; print_r($data); echo '</pre>';  Die(); 
$this->tppymemcached->set($cache_key,$data,300);
}
 //echo '<pre>   $data=>'; print_r($data); echo '</pre>';  Die(); 
 
return $data; 
}
public function where_cvs_course_exam_score_member_user_id($exam_id,$user_id,$order='desc',$limit='10000'){
 $sql="select distinct *
,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total
,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  
where score.exam_id='$exam_id' and score.user_id='$user_id' 
order by score.id desc limit $limit";
$cache_key = "where_cvs_course_exam_score_member_user_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
$rsdata= $this->db->query($sql);
$numrows=$this->db->query($sql)->num_rows();
$rss=$rsdata->result_array();
$data=array('sql'=>$sql,'rs'=>$rss,'numrows'=>$numrows,);
//echo '<pre>dataqq=>'; print_r($data); echo '</pre>';  Die(); 
$this->tppymemcached->set($cache_key,$data,300);
}
//echo '<pre>   $data=>'; print_r($data); echo '</pre>';  Die(); 
 
return $data; 
}
public function where_cvs_course_exam_score_member_limit($exam_id,$member_id,$limit){
 if($member_id==Null){
  //$sql="SELECT distinct * from cvs_course_exam_score as score  where score.exam_id='$exam_id' order by score.id desc";
$sql="select distinct *,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  where score.exam_id='$exam_id'  order by score.id desc limit $limit";
 }else{
   $sql="select distinct *,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  where score.exam_id='$exam_id' and score.member_id='$member_id' order by score.id desc limit $limit";
 }
$cache_key = "na_where_cvs_course_exam_score_member_limit_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
$rsdata= $this->db->query($sql);
$numrows=$this->db->query($sql)->num_rows();
$rss=$rsdata->result_array();
$data=array('sql'=>$sql,'rs'=>$rss,'numrows'=>$numrows,);
//echo '<pre>dataqq=>'; print_r($data); echo '</pre>';  Die(); 
$this->tppymemcached->set($cache_key,$data,300);
}
#echo '<pre>   $data=>'; print_r($data); echo '</pre>';  Die(); 
return $data; 
}
public function where_cvs_course_exam_answer($question_id,$id){
$sql="SELECT distinct * from cvs_course_answer  where question_id='$question_id' and id='$id'   order by  id desc ";
$cache_key = "kmExamination_cvs_course_answer_".$sql;
//$this->tppymemcached->delete($cache_key);
$rs=$this->tppymemcached->get($cache_key);
if(!$rs) { 
$rsdata= $this->db->query($sql);
$rss=$rsdata->result_array();
$rs=$rss['0'];
$answer_ans=$rs['answer_ans'];
$this->tppymemcached->set($cache_key,$rs,300);
}
#echo '<pre>sql=>';print_r($sql);echo'</pre>'; echo '<pre>rs=>'; print_r($rs); echo '</pre>';  
if($answer_ans=='true'){
 $scoredata=1;
}else{
 $scoredata=0;
}

//echo '<pre>sql=>';print_r($sql);echo'</pre>'; echo '<pre>rs=>'; print_r($rs); echo '</pre>';  Die(); 
return $scoredata; 
}
public function where_cvs_course_exam_score($exam_id,$member_id){
$sql="SELECT distinct * from cvs_course_exam_score as score  where score.exam_id='$exam_id'  and score.member_id='$member_id' order by id desc ";
$cache_key = "psExamination_cvs_course_exam_score_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
$this->tppymemcached->set($cache_key,$data,300);
}
//echo '<pre>sql=>';print_r($sql);echo'</pre>'; echo '<pre>rs=>'; print_r($rs); echo '</pre>';  Die(); 
return $data; 
}
public function base64_encrypt($string, $key) {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result.=$char;
        }

        return base64_encode($result);
}
public function base64_decrypt($string, $key) {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result.=$char;
        }
        return $result;
}
public function getAllScorePercent($exam_id) {
       $CI = & get_instance();
       $key = "getAllScorePercent_" . $exam_id;
       $row = $CI->tppymemcached->get($key);
//$row='';
        if ($row) {
            return $row;
        } else {
            if (!$exam_id)
                return false;
            $sql = "SELECT `score_value` , count( score_value ) as total
                FROM `cvs_course_exam_score`
                WHERE `exam_id` ='" . $exam_id . "'
                GROUP BY score_value";
            $query = $this->db->query($sql);
//        $key = "getViewNumber_content_" . $content_id . "_table_" . $table;
            if ($query->num_rows() > 0) {
                $row = $query->result_array();
                $CI->tppymemcached->set($key, $row);
                return $row;
            } else
                return false;
        }
    }
public function where_cvs_course_exam_answer_all_true($question_id){
$sql="SELECT distinct * from cvs_course_answer  where question_id='$question_id' and answer_ans='true'  order by  id desc ";
$cache_key = "qaExamination_where_cvs_course_exam_answer_all_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
$rsdata= $this->db->query($sql);
$rss=$rsdata->result_array();
$data=$rss['0'];
$this->tppymemcached->set($cache_key,$data,300);
}
 /*
$rsdata= $this->db->query($sql);
$rss=$rsdata->result_array();
$data=$rss['0'];
*/
//echo '<pre>$sql=>'; print_r($sql); echo '</pre>';echo '<pre>$data=>'; print_r($data); echo '</pre>';  Die(); 
return $data; 
}
public function where_cvs_course_exam_answer_all_user($question_id,$id){
$sql="SELECT distinct * from cvs_course_answer  where question_id='$question_id' and id='$id' order by  id desc ";
$cache_key = "qaExamination_where_cvs_course_exam_answer_all_".$sql;
 
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
$rsdata= $this->db->query($sql);
$rss=$rsdata->result_array();
$data=$rss['0'];
$this->tppymemcached->set($cache_key,$data,300);
}
 
/*
$rsdata= $this->db->query($sql);
$rss=$rsdata->result_array();
$rs=$rss['0'];
*/
#echo '<pre>$sql=>'; print_r($sql);echo '</pre>';echo'<pre>$rs=>'; print_r($rs);echo '</pre>'; Die(); 

return $data; 
}
public function get_profile_by_userid($user_id){
$sql="SELECT *, 'upload' as folder_path FROM users_account u LEFT OUTER JOIN blog ON blog.member_id = u.member_id WHERE user_id=$user_id";
//echo '<pre> $sql=>'; print_r($sql); echo '</pre>'; 
//$sql="SELECT * FROM users_account WHERE user_id=$user_id";
$cache_key = "get_profile_by_userid.'.$sql";
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
//$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
$data=$rs['0'];
################
  $this->tppymemcached->set($cache_key,$data,300);
}
//echo '<pre> $data=>'; print_r($data); echo '</pre>'; //Die(); 
return $data; 
}
public function get_profile_by_userid2($user_id) {
      $sql = "SELECT *, 'upload' as folder_path FROM users_account u
      LEFT OUTER JOIN blog ON blog.member_id = u.member_id
      WHERE user_id=$user_id";
      $query = $this->db->query($sql);
if($query->num_rows()){
       $result = $query->row_array();
if(empty($result['blog_id'])){
        $blog_id = $this->insert_blog($result['user_id'], $result['member_id']);
        $result['blog_id'] = $blog_id;
}
        return $result;
}
      return null;
  }
public function where_mul_map_content_lessonid_join($lesson_id){
$sql="select distinct content.mul_content_id,ls.lesson_name,ls.lesson_id,ls.lesson_parent_id,content.mul_content_subject,content.mul_content_text,concat('http://www.trueplookpanya.com/learning/detail/',content.mul_content_id) AS content_url
,concat('http://www.trueplookpanya.com/api/knowledgebase/content/',content.mul_content_id) AS content_api_url 
,concat('http://static.trueplookpanya.com/',content.thumbnail_path,content.thumbnail_name) AS thumbnail 
,(select view_count from mul_content where mul_content_id=content.mul_content_id)as view_count
,(select view_count from mul_content where mul_content_id=content.mul_content_id)as view
from mul_map_content_lesson map 
left join mul_lesson ls on map.lesson_id=ls.lesson_id 
left join mul_content  content on content.mul_content_id=map.mul_content_id 
where map.lesson_id=$lesson_id";
$cache_key = "na_where_mul_map_content_lessonid_join_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
#############
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('rs'=>$rs,'numrows'=>$numrows,);
#############
$this->tppymemcached->set($cache_key,$data,300);
}
#echo '<pre> sql=>';print_r($sql); echo '<pre>'; 
#echo '<pre> data=>'; print_r($data); echo '</pre>'; // Die(); 
return $data; 
} 
public function where_user_by_school($job_edu_name){
$sql="select  * from users_account  where  job_edu_name LIKE '$job_edu_name%'";
$cache_key = "where_user_by_school_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
#############
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows,);
#############
$this->tppymemcached->set($cache_key,$data,300);
}
#echo '<pre> sql=>';print_r($sql); echo '<pre>'; 
#echo '<pre> data=>'; print_r($data); echo '</pre>'; // Die(); 
return $data; 
} 
public function where_user_by_school_level($job_edu_name,$job_edu_level){
$sql="select  * from users_account  where  job_edu_name LIKE '$job_edu_name%' and job_edu_level LIKE '%$job_edu_level%'";
$cache_key = "where_user_by_school_level_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
#############
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows,);
#############
$this->tppymemcached->set($cache_key,$data,300);
}
#echo '<pre> sql=>';print_r($sql); echo '<pre>'; 
#echo '<pre> data=>'; print_r($data); echo '</pre>'; // Die(); 
return $data; 
} 
public function where_exam_id_job_edu_name_user_by_school_between_($exam_id,$job_edu_name,$datestart,$enddate){
$sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id)as exam_score_user
from  users_account as users   
left join cvs_course_exam_score score on score.member_id=users.member_id
left join cvs_course_examination exam on exam.id=score.exam_id
where users.job_edu_name LIKE '%$job_edu_name%'  and score.exam_id='$exam_id' and score.date_update between '$datestart 00:00:00' AND '$enddate 23:59:59'
order by users.user_id asc,exam_score_user,score.id desc limit 10000";
$cache_key = "where_exam_id_job_edu_name_user_by_school_between_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
#############
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows,);
#############
$this->tppymemcached->set($cache_key,$data,300);
}
#echo '<pre> sql=>';print_r($sql); echo '<pre>'; 
#echo '<pre> data=>'; print_r($data); echo '</pre>'; // Die(); 
return $data; 
} 
public function where_exam_id_job_edu_job_edu_level_name_user_by_school_between_($exam_id,$job_edu_name,$job_edu_level,$datestart,$enddate){
$sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id)as exam_score_user
from  users_account as users   
left join cvs_course_exam_score score on score.member_id=users.member_id
left join cvs_course_examination exam on exam.id=score.exam_id
where users.job_edu_name LIKE '%$job_edu_name%' and   users.job_edu_level LIKE '%$job_edu_level%'  and score.exam_id='$exam_id' and score.date_update between '$datestart 00:00:00' AND '$enddate 23:59:59'
order by users.user_id asc,exam_score_user,score.id desc limit 10000";
$cache_key = "where_exam_id_job_edu_job_edu_level_name_user_by_school_between_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
#############
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows,);
#############
$this->tppymemcached->set($cache_key,$data,300);
}
#echo '<pre> sql=>';print_r($sql); echo '<pre>'; 
#echo '<pre> data=>'; print_r($data); echo '</pre>'; // Die(); 
return $data; 
} 
public function examinationlistwhere_array2s($array){
#$sql="SELECT * from cvs_course_examination where id in ($array) and exam_status='yes' order by id asc";
$sql="SELECT * from cvs_course_examination where id in ($array)  order by FIND_IN_SET (id,'$array')";
 #$sql="SELECT * from cvs_course_examination where id in ($array)  order by id asc";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "examinationlistwhere_array2s_'.$array";
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data) {
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function examinationlistwhere_array2($array){
$sql="SELECT * from cvs_course_examination where id in ($array)  order by FIND_IN_SET (id,'$array')";
$cache_key = "examinationlistwhere_array_2_'.$array";
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function examinationlistwhere_array($array){
#$sql="SELECT * from cvs_course_examination where id in ($array) and exam_status='yes' order by id asc";
# $sql="SELECT * from cvs_course_examination where id in ('$array')  order by FIND_IN_SET (id,'$array')";
 $sql="SELECT * from cvs_course_examination where id in ($array)  order by id asc";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "where_cvs_course_examination_listallss_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function examination_distinct_edu_name_edu_level_exam_id($job_edu_name,$job_edu_level,$exam_id){

/*

$sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  member_id=users.member_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  users_account as users   
left join cvs_course_exam_score score on score.member_id=users.member_id
left join cvs_course_examination exam on exam.id=score.exam_id
where users.job_edu_name LIKE '%$job_edu_name%' and   users.job_edu_level LIKE '%$job_edu_level%'  and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";


*/
/*
$sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  member_id=users.member_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  users_account as users   
left join cvs_course_exam_score score on score.member_id=users.member_id
left join cvs_course_examination exam on exam.id=score.exam_id
where users.job_edu_name='$job_edu_name' and   users.job_edu_level='$job_edu_level'  and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";

*/
/*
$sql_old="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  member_id=users.member_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  users_account as users   
left join cvs_course_exam_score score on score.member_id=users.member_id
left join cvs_course_examination exam on exam.id=score.exam_id
where users.job_edu_name='$job_edu_name' and   users.job_edu_level='$job_edu_level'  and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";

$sql4="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  member_id=users.member_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  users_account as users   
left join cvs_course_exam_score score on score.member_id=users.member_id
left join cvs_course_examination exam on exam.id=score.exam_id
where users.job_edu_name='$job_edu_name' and   users.job_edu_level='$job_edu_level'  and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";
*/
$sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  user_id=users.user_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000 and users.job_edu_name='$job_edu_name' and   users.job_edu_level='$job_edu_level'  and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";

 #echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "where_examination_distinct_edu_name_edu_level_exam_id_".$sql;
#$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function examination_distinct_edu_name_exam_id($job_edu_name,$exam_id){
$sql="
select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id)as exam_score_user
from  users_account as users   
left join cvs_course_exam_score score on score.member_id=users.member_id
left join cvs_course_examination exam on exam.id=score.exam_id
where users.job_edu_name LIKE '%$job_edu_name%' and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "where_examination_distinct_edu_name_exam_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function examination_maxscore_score_users_exam_id($exam_id,$id,$type=''){
if($type=='' || $type==1){
 $sql="select max(score_value) as score from cvs_course_exam_score where exam_id='$exam_id' and member_id='$id'";
}else{
 $sql="select max(score_value) as score from cvs_course_exam_score where exam_id='$exam_id' and member_id='$id'";
}
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "where_examination_maxscore_score_users_exam_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function examination_maxid_score_users_exam_id($exam_id,$id,$type=''){
if($type=='' || $type==1){
 $sql="select score_value as score from cvs_course_exam_score where exam_id='$exam_id' and member_id='$id' order by id limit 1";
}else{
 $sql="select score_value as score from cvs_course_exam_score where exam_id='$exam_id' and user_id='$id'order by id limit 1";
}
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "where_examination_maxid_score_users_exam_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function examination_maxid_score_question_total_users_exam_id($exam_id,$id,$type=''){
if($type=='' || $type==1){
 $sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id)as exam_score_user
from  users_account as users   
left join cvs_course_exam_score score on score.member_id=users.member_id
left join cvs_course_examination exam on exam.id=score.exam_id
where users.member_id= '$id'  and score.exam_id='$exam_id' order by users.user_id asc,score.id desc limit 1";
}else{
 $sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id)as exam_score_user
from  users_account as users   
left join cvs_course_exam_score score on score.member_id=users.member_id
left join cvs_course_examination exam on exam.id=score.exam_id
where users.user_id= '$id'  and score.exam_id='$exam_id' order by users.user_id asc,score.id desc limit 1";
}
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "where_examination_maxid_score_question_total_users_exam_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function count_user_edu_name_level($job_edu_name,$job_edu_level){
$sql="select count(user_id)as count_user from  users_account as users where users.job_edu_name='$job_edu_name' and users.job_edu_level='$job_edu_level'";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "count_user_edu_name_level_data_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function count_user_edu_name($job_edu_name){
$sql="select count(user_id)as count_user from  users_account as users where users.job_edu_name LIKE '%$job_edu_name%'";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "count_user_edu_name_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function mul_lesson_id_exam_id_question_id($lesson_id,$exam_id,$question_id){
$sql="select q.exam_id,map.*,q.question_detail as question,ans.id as id_ans,ans.answer_ans,ans.answer_detail  as answer
from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
left join cvs_course_answer as ans on q.id=ans.question_id
where map.lesson_id='$lesson_id' and q.exam_id='$exam_id' and map.question_id='$question_id' ";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "mul_lesson_id_exam_id_question_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function cvs_course_question_id($exam_id){
$sql="select id from cvs_course_question where  exam_id='$exam_id'";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "cvs_course_question_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function cvs_course_answer_qid_type($question_id,$type=1){
 if($type==1){
$sql="select id,answer_ans,question_id  from cvs_course_answer where  question_id='$question_id'";
}else{
$sql="select id,answer_ans,question_id  from cvs_course_answer where  question_id='$question_id' and answer_ans='true'";
}

#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "cvs_course_answer_qid_type_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $rs; 
}
public function total_answer_true($result_set){
$sql="select COUNT(*) cnt FROM cvs_course_answer WHERE id IN($result_set) AND answer_ans='true'";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "total_answer_true_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
####chart
public function chart_score_value($exam_id){
//$sql="SELECT score_value ,count(score_value) as total FROM cvs_course_exam_score WHERE exam_id='$exam_id' GROUP BY score_value";
$sql="SELECT score.exam_id,score.score_value,count(score.score_value) as total 
FROM cvs_course_exam_score  as score
left join cvs_course_question as exam on exam.id=score.exam_id
WHERE score.exam_id='$exam_id' GROUP BY score_value ";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "chart_score_value_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function chart_score_value_member_id($exam_id,$member_id){
$sql="SELECT score.exam_id,score.score_value,count(score.score_value) as total 
FROM cvs_course_exam_score  as score
left join cvs_course_question as exam on exam.id=score.exam_id
WHERE score.exam_id='$exam_id' and member_id='$member_id' GROUP BY score_value ";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "chart_score_value_member_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function chart_score_ansall($exam_id){
$sql="SELECT * FROM cvs_course_question WHERE exam_id='$exam_id'";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "chart_score_ansall_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function chart_score_question_id($exam_id){
$sql="SELECT id as question_id FROM cvs_course_question qu WHERE qu.exam_id ='$exam_id' ORDER BY id ASC";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "chart_score_question_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function chart_lesson_name_exam_id($exam_id){
$sql="select distinct map_eql.lesson_id 
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map_eql.lesson_id)as countlesson
,(select lesson_name from mul_lesson where lesson_id=map_eql.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
from  mul_map_exam_question_lesson as map_eql   
left join mul_lesson as lesson on lesson.lesson_id=map_eql.lesson_id
left join cvs_course_question as question on question.id=map_eql.question_id
left join cvs_course_examination as exam on exam.id=question.exam_id
where exam.id='$exam_id'";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "chart_lesson_name_exam_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function where_in_cvs_course_answer_true($coursequestionid){
$sql="select id from cvs_course_answer where question_id IN($coursequestionid) and answer_ans='true' ";
$cache_key = "where_in_cvs_course_answer_true_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function where_in_question_lesson_id_ans_true($coursequestionid,$lesson_id){
 /*
$sql="select *
,(select id from cvs_course_answer where question_id=map.question_id and answer_ans='true')as id_ans_true
from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.question_id IN($coursequestionid) and map.lesson_id='$lesson_id' ";
*/

$sql="select exam_id,question_id
,(select id from cvs_course_answer where question_id=map.question_id and answer_ans='true')as id_ans_true
,lesson_id
from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.question_id IN($coursequestionid) and map.lesson_id='$lesson_id' ";
$cache_key = "where_in_question_lesson_id_ans_true_".$sql;
#$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 

} 
public function job_edu_name_users_account($job_edu_name){
 /*
$sql="select distinct(users.job_edu_level)  
,(select count(user_id) from users_account where job_edu_level=users.job_edu_level and job_edu_name=users.job_edu_name)as user_count
from  users_account as users   
where users.job_edu_name='$job_edu_name' ";
*/
$sql="select distinct(users.job_edu_level)  
,(select count(user_id) from users_account where job_edu_level=users.job_edu_level and job_edu_name=users.job_edu_name)as user_count
from  users_account as users   
where users.job_edu_name='$job_edu_name'  and job_edu_level is not null and user_username like 'MT%'";
$cache_key = "job_edu_name_users_account_allmt1_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function job_edu_name_users_account_like($job_edu_name){
$sql="select distinct(job_edu_level)  
,(select count(user_id) from users_account where job_edu_level=users.job_edu_level and job_edu_name LIKE '%$job_edu_name%')as user_count
from  users_account as users   
where users.job_edu_name LIKE '%$job_edu_name%' ";
$cache_key = "job_edu_name_users_account_like_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function job_edu_name_users_account_countdata_row($exam_id,$job_edu_name,$job_edu_level){
$sql="select distinct users.member_id
from cvs_course_exam_score  as score
left join cvs_course_examination as exam on exam.id=score.exam_id
left join users_account as users on users.member_id=score.member_id
where score.exam_id='$exam_id' and users.job_edu_name='$job_edu_name' and  users.job_edu_level='$job_edu_level'";
$cache_key = "job_edu_name_users_account_countdata_row_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function where_cvs_course_answer($id){
$sql="select ans.id as ans_id,ans.answer_detail as detail,ans.answer_ans as answer
,(select question_detail from  cvs_course_question where id=ans.question_id)as question_detail
from cvs_course_answer as ans where question_id='$id' order by id asc";
$cache_key = "where_cvs_course_answer_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function getSelectdata_exam_id($array='13439',$name="exam_id",$default){	
   $this->load->helper('common');
   # echo '<pre>$array=>'; print_r($array); echo '</pre>';
   # echo '<pre>$name=>'; print_r($name); echo '</pre>';
   # echo '<pre>$default=>'; print_r($default); echo '</pre>'; # die();
   #$first = "--- please select subcategory ---";
   $first = "--- กรุณาเลือก ---";
   if($array!==Null){
    $rows=$this->get_category_by_parent_id($array);
   }else{
     $rows=$this->get_category_by_parent_id($array='13439');
   }
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption((int)$row['id'], $row['exam_name']);
	    	}
      
      //echo '<pre>$opt=>'; print_r($opt); echo '</pre>'; 
      //echo '<pre>$rows=>'; print_r($rows); echo '</pre>';
      //die();
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
}  
public function get_category_by_parent_id($array){
#$sql = "SELECT * from cvs_course_examination where id in ($array) and exam_status='yes' order by id asc";
#$sql = "SELECT * from cvs_course_examination where id in ($array)  order by id asc";
$sql = "SELECT * from cvs_course_examination where id in ($array) ORDER BY FIND_IN_SET(id, '".$array."')";

$cache_key = "where_cvs_course_examination_get_category_by_parent_ids_".$sql;
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
################
  $this->tppymemcached->set($cache_key,$data,360);
}
	return $data; 
}
public function avg_school_level_exam_id($job_edu_name,$job_edu_level,$exam_id){
$sql="select avg(score.score_value) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000 and users.job_edu_name='$job_edu_name' and  users.job_edu_level='$job_edu_level'and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";
$cache_key = "avg_school_level_exam_id'.$sql.";
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('rs'=>$rs,'numrows'=>$numrows,);
$this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
} 
##########  school  name
public function examinationbyschool($delete_cache_key=0){
$sql="select distinct users.job_edu_name
,(select count(user_id) from users_account where job_edu_name=users.job_edu_name)as user_total
,(select count(distinct score.user_id) as score_count from cvs_course_exam_score  as score left join users_account as usr on usr.member_id=score.member_id where  usr.job_edu_name=users.job_edu_name)as score_total
from users_account as users 
where users.job_edu_name in ('โรงเรียนวัดยานนาวา','โรงเรียนวัดพระยาสุเรนทร์ (บุญมีอนุกูล)','โรงเรียนวัดหงส์ปทุมาวาส','โรงเรียนพระแม่มารีพระโขนง','โรงเรียนวัดเทพลีลา','สาธิตม.บ้านสมเด็จฯ (ประถม)','โรงเรียนบีคอนเฮาส์แย้มสอาดรังสิต','โรงเรียนหนองจอกกงลิบฮัวเคียว','โรงเรียนอนุบาลนนทบุรี','โรงเรียนเซนต์โยเซฟ บางนา','โรงเรียนวัดแสนเกษม','โรงเรียนบ้านลำต้นกล้วย','โรงเรียนโชคชัยกระบี่','โรงเรียนโชคชัยลาดพร้าว','โรงเรียนพระหฤทัยดอนเมือง','โรงเรียนเขมะสิริอนุสสรณ์','โรงเรียนโชคชัยรังสิต','โรงเรียนวัดบึงทองหลาง','โรงเรียนวัดนิมมานรดี','โรงเรียนสามเสนนอก','โรงเรียนสายน้ำทิพย์','โรงเรียนสากลศึกษา บางบัวทอง','โรงเรียนวัดเวฬุวนาราม(สินทรัพย์อนุสรณ์)') order by users.user_id asc";
$cache_key = "select_data_examination_count_by_schools_score_".$sql;
//$this->tppymemcached->delete($cache_key);
if($delete_cache_key==1){ $this->tppymemcached->delete($cache_key); }
$data=$this->tppymemcached->get($cache_key);
if(!$data){
  ################
  $rsdata= $this->db->query($sql);
  $rs=$rsdata->result_array();
  $numrows=$this->db->query($sql)->num_rows();
  $data=array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
  ################
  $this->tppymemcached->set($cache_key,$data,300);
}

 //echo '<pre> array=>'; print_r($array); echo '</pre>';
 //echo '<pre> data=>'; print_r($data); echo '</pre>'; Die();

	return $data; 
}
public function examinationbyschoolcountall($delete_cache_key=0){
 /*
$sql="select  count(distinct users.user_id) as count_user_all
from users_account as users 
where users.job_edu_name in ('โรงเรียนวัดยานนาวา','โรงเรียนวัดพระยาสุเรนทร์ (บุญมีอนุกูล)','โรงเรียนวัดหงส์ปทุมาวาส','โรงเรียนพระแม่มารีพระโขนง','โรงเรียนวัดเทพลีลา','โรงเรียนสาธิตบ้านสมเด็จ')  order by users.user_id asc";
*/
$sql="select  count(distinct users.user_id) as count_user_all
from users_account as users 
where users.job_edu_name in ('โรงเรียนวัดยานนาวา','โรงเรียนวัดพระยาสุเรนทร์ (บุญมีอนุกูล)','โรงเรียนวัดหงส์ปทุมาวาส','โรงเรียนพระแม่มารีพระโขนง','โรงเรียนวัดเทพลีลา','สาธิตม.บ้านสมเด็จฯ (ประถม)','โรงเรียนบีคอนเฮาส์แย้มสอาดรังสิต','โรงเรียนหนองจอกกงลิบฮัวเคียว','โรงเรียนอนุบาลนนทบุรี','โรงเรียนเซนต์โยเซฟ บางนา','โรงเรียนวัดแสนเกษม','โรงเรียนบ้านลำต้นกล้วย','โรงเรียนโชคชัยกระบี่','โรงเรียนโชคชัยลาดพร้าว','โรงเรียนพระหฤทัยดอนเมือง','โรงเรียนเขมะสิริอนุสสรณ์','โรงเรียนโชคชัยรังสิต','โรงเรียนวัดบึงทองหลาง','โรงเรียนวัดนิมมานรดี','โรงเรียนสามเสนนอก','โรงเรียนสายน้ำทิพย์','โรงเรียนสากลศึกษา บางบัวทอง','โรงเรียนวัดเวฬุวนาราม(สินทรัพย์อนุสรณ์)') order by users.user_id asc";
$cache_key = "select_data_examination_count_by_schools_score_countall_".$sql;
//$this->tppymemcached->delete($cache_key);
if($delete_cache_key==1){ $this->tppymemcached->delete($cache_key); }
$data=$this->tppymemcached->get($cache_key);
if(!$data){
  ################
  $rsdata= $this->db->query($sql);
  $rs=$rsdata->result_array();
  $numrows=$this->db->query($sql)->num_rows();
  $data=array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
  ################
  $this->tppymemcached->set($cache_key,$data,300);
}

 //echo '<pre> array=>'; print_r($array); echo '</pre>';
 //echo '<pre> data=>'; print_r($data); echo '</pre>'; Die();

	return $data; 
}
##########
public function reportbyschoolall_job_edu($exam_id,$job_edu_name){
$sql="select distinct users.member_id
from cvs_course_exam_score  as score
left join cvs_course_examination as exam on exam.id=score.exam_id
left join users_account as users on users.member_id=score.member_id
where score.exam_id=$exam_id and users.job_edu_name='$job_edu_name' and job_edu_level is not null
and user_username like 'MT%'";
$cache_key = "reportbyschoolalls_job_edu_".$sql;
#$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,3600);
}
return $data; 
}
public function schoolscoredisplay_exam_score_and_course_exam_score_member($exam_id,$job_edu_name){
		$sql = "SELECT COUNT(*) cnt FROM cvs_course_question WHERE exam_id=$exam_id";
		$cache_key = "QuestionCNT_ScoreDisplay_school_examscoremembers1s_$sql";
		#$this->tppymemcached->delete($cache_key);
		if (!$question_total = $this->tppymemcached->get($cache_key)) {
			$question_total=$this->db->query($sql)->row()->cnt;
			$this->tppymemcached->set($cache_key, $question_total,300);
		}
		$questiontotal=$question_total;
 
$sql="SELECT score.score_value, count(score.score_value ) as total FROM cvs_course_exam_score as  score left join cvs_course_examination as exam on exam.id=score.exam_id left join users_account as users on users.member_id=score.member_id WHERE score.exam_id=$exam_id and users.job_edu_name='$job_edu_name'  GROUP BY score.score_value  order by  score.score_value desc";
 
$cache_key = "school_scoredisplay_exam_score_and_courseexamscoremembers1s_$sql";
		#$this->tppymemcached->delete($cache_key);
		if (!$scoreAllAnswer = $this->tppymemcached->get($cache_key)) {
			$query2 = $this->db->query($sql)->result_array();
			$this->tppymemcached->set($cache_key, $query2,300);
		}
      
      if ($scoreAllAnswer) {
          foreach ($scoreAllAnswer as $value) {
              $statScore[$value['score_value']] = $value['total'];
          }
      }

      for ($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
      }

  //echo '<pre>$sql=>'; print_r($sql); echo '</pre>';  
  //echo '<pre>$scoreArr=>'; print_r($scoreArr); echo '</pre>';   //Die(); 
  
     $data = ($scoreArr);
     
return $data; 
}
public function school_examination_distinct_edu_name_edu_level_exam_id($job_edu_name,$exam_id){
$sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  user_id=users.user_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000 and users.job_edu_name='$job_edu_name' and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";
$cache_key = "schools_where_examination_distinct_edu_name_edu_level_exam_id_".$sql;
#$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function school_count_user_all($job_edu_name){
$sql="select distinct  COUNT(DISTINCT users.member_id) AS count_user
from   users_account as users 
where  users.job_edu_name='$job_edu_name' and job_edu_level is not null
and user_username like 'MT%' ";
$cache_key = "school_count_user_alls_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,3600);
}
return $data; 
}
public function avg_school_level_exam_all($job_edu_name,$exam_id){
$sql="select avg(score.score_value) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000 and users.job_edu_name='$job_edu_name' and  score.exam_id=$exam_id order by users.user_id asc,score.id desc";
$cache_key = "avg_school_level_exam_all'.$sql.";
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('rs'=>$rs,'numrows'=>$numrows,);
$this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
} 
public function examinationdistinctallschoolexamid($exam_id){
$sql="select distinct users.user_id,users.member_id
,COALESCE(NULLIF(concat(users.psn_firstname,' '),''),users.psn_display_name) as firstname
,COALESCE(NULLIF(concat(users.psn_lastname),''),'-') as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  user_id=users.user_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  cvs_course_exam_score as score     
left join users_account as users on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000 and  score.exam_id=$exam_id and users.user_id!='' order by users.user_id asc,score.id desc";

 #echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "examinationdistinctallschoolexamid_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function examination_distinct_allschool_exam_id($exam_id){
$sql="select distinct users.user_id,users.member_id
,COALESCE(NULLIF(concat(users.psn_firstname,' '),''),users.psn_display_name) as firstname
,COALESCE(NULLIF(concat(users.psn_lastname),''),'-') as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  user_id=users.user_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  cvs_course_exam_score as score     
left join users_account as users on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000 and  score.exam_id=$exam_id and users.user_id!='' order by users.user_id asc,score.id desc";

 #echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "where_examination_distinct_allschoolss_exam_idn_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function count_user_all(){
$sql="select count(user_id)as count_user from  users_account as users where users.job_edu_name in ('โรงเรียนวัดยานนาวา','โรงเรียนวัดพระยาสุเรนทร์ (บุญมีอนุกูล)','โรงเรียนวัดหงส์ปทุมาวาส','โรงเรียนพระแม่มารีพระโขนง','โรงเรียนวัดเทพลีลา','สาธิตม.บ้านสมเด็จฯ (ประถม)','โรงเรียนบีคอนเฮาส์แย้มสอาดรังสิต','โรงเรียนหนองจอกกงลิบฮัวเคียว','โรงเรียนอนุบาลนนทบุรี','โรงเรียนเซนต์โยเซฟ บางนา','โรงเรียนวัดแสนเกษม','โรงเรียนบ้านลำต้นกล้วย','โรงเรียนโชคชัยกระบี่','โรงเรียนโชคชัยลาดพร้าว','โรงเรียนพระหฤทัยดอนเมือง','โรงเรียนเขมะสิริอนุสสรณ์','โรงเรียนโชคชัยรังสิต','โรงเรียนวัดบึงทองหลาง','โรงเรียนวัดนิมมานรดี','โรงเรียนสามเสนนอก','โรงเรียนสายน้ำทิพย์','โรงเรียนสากลศึกษา บางบัวทอง','โรงเรียนวัดเวฬุวนาราม(สินทรัพย์อนุสรณ์)') ";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "count_user_edu_name_level_datan_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function count_user_all_where_id($exam_id){
$sql="select distinct count(*)as count_user 
from  cvs_course_exam_score as score 
left join users_account as users on score.user_id=users.user_id
where exam_id=$exam_id ";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "data_count_user_all_where_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function avg_school_alls_exam_id($exam_id){
$sql="select avg(score.score_value) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000 and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";
$cache_key = "avg_avg_school_alls_exam_idn'.$sql.";
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('rs'=>$rs,'numrows'=>$numrows,);
$this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
} 
##########201801
public function count_user_answer_exam_id($exam_id){
$sql="select count(*) from cvs_course_exam_score where exam_id=$exam_id";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "count_user_answer_exam_id_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
//2018
public function examination_distinct_edu_name_edu_level_exam_id2018($exam_id){

$sql="select distinct  users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,score.*,exam.*
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  user_id=users.user_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  cvs_course_exam_score as score 
left join users_account as users on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000 and score.user_id!=''and score.answer_value!='' and users.psn_display_name!='' and score.exam_id=$exam_id order by users.user_id asc,score.id desc";


/*
$sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  user_id=users.user_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000   and score.exam_id=$exam_id order by users.user_id asc,score.id desc  ";
*/

 #echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "where_examination_distinct_edu_name_edu_level_exam_id2018_".$sql;
#$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function count_user_edu_name_level2018($exam_id){
$sql="select distinct count(score.user_id)as count_user
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000  and score.exam_id=$exam_id";
#echo '<pre> $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "count_user_edu_name_level_dataid2018_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$data=$rsdata->result_array();
################
  $this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
}
public function school_count_user_all2018($job_edu_name){
$sql="select distinct  COUNT(DISTINCT users.member_id) AS count_user
from   users_account as users 
where  users.job_edu_name='$job_edu_name' and job_edu_level is not null
and user_username like 'MT%' ";
$cache_key = "school_count_user_alls2018_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('sql'=>$sql,'rs'=>$rs,'numrows'=>$numrows);
################
  $this->tppymemcached->set($cache_key,$data,3600);
}
return $data; 
}
public function avg_school_level_exam_all2018($job_edu_name,$exam_id){
$sql="select avg(score.score_value) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000  and  score.exam_id=$exam_id order by users.user_id asc,score.id desc";
$cache_key = "avg_school_level_exam_all2018'.$sql.";
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('rs'=>$rs,'numrows'=>$numrows,);
$this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
} 
public function avg_school_level_exam_id20108($exam_id){
/* 
$sql="select avg(score.score_value) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000  and (users.job_edu_name!='' or users.job_edu_name='')and  (users.job_edu_level!='' or users.job_edu_level='')  and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";
*/

$sql="select avg(score.score_value) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  cvs_course_exam_score score  
left join users_account as users on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000 and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";


$cache_key = "avg_school_level_exam_id'.$sql.";
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = array('rs'=>$rs,'numrows'=>$numrows,);
$this->tppymemcached->set($cache_key,$data,300);
}
return $data; 
} 
public function where_cvs_course_exam_score_user_id2018($exam_id,$user_id){
 //$sql="SELECT distinct * from cvs_course_exam_score as score  where score.exam_id='$exam_id' order by score.id desc";
 $sql="select distinct *,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  where score.exam_id=$exam_id and score.user_id='$user_id' order by score.id desc limit 1";
 #echo '<pre>   $sql=>'; print_r($sql); echo '</pre>';  Die();
$cache_key = "namExaminationcvs_course_exam_score_user_id2018_".$sql;
//$this->tppymemcached->delete($cache_key);
$data=$this->tppymemcached->get($cache_key);
if(!$data){
$rsdata= $this->db->query($sql);
$numrows=$this->db->query($sql)->num_rows();
$rss=$rsdata->result_array();
$data=array('sql'=>$sql,'rs'=>$rss,'numrows'=>$numrows,);
//echo '<pre>dataqq=>'; print_r($data); echo '</pre>';  Die(); 
$this->tppymemcached->set($cache_key,$data,300);
}
 //echo '<pre>   $data=>'; print_r($data); echo '</pre>';  Die(); 
 
return $data; 
}