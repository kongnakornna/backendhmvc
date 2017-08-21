<?php
class Examination_center_model extends CI_Model{
	private $DBSelect, $DBEdit;
	public function __construct(){
		parent::__construct();
		 header('Content-Type: text/html; charset=utf-8');
			$CI = & get_instance();
		  //$this->load->database('default');  
	}
      
        public function getListall($exam_id='',$limit=100,$exam_name='') {
			if($exam_id!==Null){
				$sql = "SELECT * from cvs_course_examination  where id=$exam_id order by id asc limit $limit ";
			}else if($exam_name!==Null){ 
				$sql = "SELECT * from cvs_course_examination where  exam_name like '%$exam_name%' order by id asc limit $limit ";
			}else{
				$sql = "SELECT * from cvs_course_examination order by id asc limit $limit ";
			}
			#echo 'sql=>'.$sql;
            $data = $this->db->query($sql)->result_array();
            return $data;
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
            return $data;
        }
		public function getcourse_examscore($exam_id='',$member_id='') {
					//echo '<pre> $exam_id=>'; print_r($exam_id); echo '</pre>';  
					//echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>';  die();
				 $order_by='desc';
				 $limit=1;
				 if($limit>500){$limit=500;}
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
            return $data;
        }
		public function getcourse_examscore_log($exam_id='',$member_id='') {
					//echo '<pre> $exam_id=>'; print_r($exam_id); echo '</pre>';  
					//echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>';  die();
				 $order_by='desc';
				 $limit=100;
				 if($limit>500){$limit=500;}
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
            return $data;
        }
		public function getcourse_examscoremax($exam_id='') {
								//echo '<pre> $exam_id=>'; print_r($exam_id); echo '</pre>';  
					//echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>';  die();
				 $order_by='desc';
				 $limit=10;
				 if($limit>500){$limit=500;}
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
                 $data = $query->result();
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
            return $data;
        }
        public function get_question_user_log($exam_id,$member_id){
			$sql = "SELECT * from cvs_course_question where  exam_id=$exam_id amd member_id=$member_id";
			#echo 'sql=>'.$sql;
            $data = $this->db->query($sql)->result_array();
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
            $data = $this->db->query($sql)->result_array();
			$data=$data['0'];
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
			$query=$this->db->query($sql);
			$data= $query->result(); 
			$data=$data['0'];
			$num_rows=$query->num_rows();  
           
			 //echo '<pre>data=>';print_r($data); echo '</pre>'; Die();	
            return $data;
        }
		public function get_examinationgraphbycourse_scoreallanswer($exam_id='',$startdate='',$enddate=''){
			$exam_id=@$this->input->get('exam_id');
			$startdate=gmdate("Y-m-d", $startdate);
			$enddate=gmdate("Y-m-d", $enddate);
			// $sql = "SELECT `score_value` , count( score_value ) as total FROM `cvs_course_exam_score` WHERE `exam_id` ='$exam_id' AND date_update BETWEEN '$start 00:00:00' AND '$end 23:59:59' GROUP BY score_value";
			$sql = "SELECT  count( score_value ) as totalscore FROM `cvs_course_exam_score` WHERE `exam_id` ='$exam_id' AND date_update BETWEEN '$startdate 00:00:00' AND '$enddate 23:59:59' GROUP BY score_value";
			
			//echo 'sql=>'.$sql; Die();
			$query=$this->db->query($sql);
			$data= $query->result(); 
			
			$num_rows=$query->num_rows();  
           
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
}


/*
				$this->db->distinct();
				#Create where clause
				$this->db->select('id_cer');
				$this->db->from('revokace');
				$where_clause = $this->db->_compile_select();
				$this->db->_reset_select();
				#Create main query
				$this->db->select('*');
				$this->db->from('certs');
				$this->db->where("`id` NOT IN ($where_clause)", NULL, FALSE);
				
	$where.= '(';
    $where.= 'admin_trek.trek='."%$search%".'  AND ';
    $where.= 'admin_trek.state_id='."$search".'  OR ';
    $where.= 'admin_trek.difficulty='."$search".' OR ';
    $where.= 'admin_trek.month='."$search".'  AND ';
    $where.= 'admin_trek.status = 1)';

    $this->db->select('*');
    $this->db->from('admin_trek');
    $this->db->join('admin_difficulty',admin_difficulty.difficulty_id = admin_trek.difficulty');
    $this->db->where($where); 
    $query = $this->db->get();
   
   $query="from_id, (SELECT COUNT(id) FROM user_messages WHERE from_id=1223 AND status=1) AS sent_unread,
            (SELECT COUNT(id) FROM user_messages WHERE from_id=1223 AND status=2) AS sent_read";
      $query_run=$this->db->select($query);
      $query_run->where('from_id', $member_id);
      $query_run->group_by('from_id');

      $result = $query_run->get('user_messages');
      //echo $this->db->last_query();
       return $result->row();
  
  
  
SELECT dc.*, ces.exam_status public_status
	  ,(SELECT COUNT(id) FROM cvs_course_question cc WHERE cc.exam_id=dc.id) question_count
      ,(select mul_level_name from mul_level where mul_level_id=dc.mul_level_id) level_name
	  ,(select mul_category_name from mul_category_2014 where mul_category_id=dc.mul_root_id) subject_name
      FROM cvs_course_examination dc 
      LEFT JOIN cvs_course_exam_share ces ON ces.exam_id=dc.id
  
 public function get_search_results($main_keyword, $perpage, $offset) {
  $this->db->select('*');
  $this->db->from('vbc_vacation_item_attri');
  $this->db->where('v_item_city', $main_keyword);
  $this->db->limit($perpage, $offset);
  $query = $this->db->get();
  $result = $query->result();
  return $result;
}
 
			 $this->db->distinct();
				 $this->db->select('dc.*, ces.exam_status public_status,(SELECT COUNT(id) FROM cvs_course_question cc WHERE cc.exam_id=dc.id) question_count,(select mul_level_name from mul_level where mul_level_id=dc.mul_level_id) level_name,(select mul_category_name from mul_category_2014 where mul_category_id=dc.mul_root_id) subject_name');
                 $this->db->from('cvs_course_examination as dc'); 
                 $this->db->join('cvs_course_exam_share as ces', 'ces.exam_id=dc.id', 'left');
                // $this->db->where('dc.id',$id);
				 $this->db->like('dc.exam_name', $keyword);
				 //$this->db->or_like('exam_name', $keyword);
				// Produces: WHERE column LIKE '%keyword%' OR column LIKE '%simple%'  
                 $this->db->group_by('dc.id');  
				 $this->db->order_by("dc.id",$order_by);
				 $this->db->order_by("dc.exam_add_date",'desc');
                 $this->db->limit($perpage, $offset);
                 $query = $this->db->get();
				 $num_rows=$query->num_rows();  
				 //echo '<pre> $query=>'; print_r($query); echo '</pre>';  die();
                 $data = $query->result();
            return $data;
        }
 
*/
/*
SELECT dc.*, ces.exam_status public_status,(SELECT COUNT(id) FROM cvs_course_question cc WHERE cc.exam_id=dc.id) question_count
      , (select mul_level_name from mul_level where mul_level_id=dc.mul_level_id) level_name
	  , (select mul_category_name from mul_category_2014 where mul_category_id=dc.mul_root_id) subject_name
      FROM cvs_course_examination dc 
      LEFT JOIN cvs_course_exam_share ces ON ces.exam_id=dc.id
       ORDER BY exam_update_date desc limit 10		
*/