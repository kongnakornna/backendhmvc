<?php
class exam_log_model extends CI_Model {
    private $DBSelect, $DBEdit;
    public function __construct() {
        parent::__construct();
		$CI = & get_instance();
    }
	
	public function exam_info($exam_id=0,$arrayFilter=array()){
		
		$arrWhere = array('@exam_id'=>$exam_id);
		// get exam
		$sql = "select * from cvs_course_examination where id=@exam_id";
		$query = $this->db->query($sql,$arrWhere);
		if($query){
			$arrExam = $query->result_array();
			$arrResult["exam_list"] = $arrExam;
			foreach ($arrExam as $k => $v){
				$arrWhere = array( '@row_exam_id'=>$v['id']);
				// get question
				$sql = "select * from cvs_course_question where exam_id=@row_exam_id";
				$query = $this->db->query($sql,$arrWhere);
				$arrQuestion = $query->result_array();
				$arrResult["exam_list"][$k]["question_list"] = $arrQuestion;
				foreach ($arrQuestion as $kk => $vv){
					$arrWhere = array( '@row_question_id'=>$vv['id']);
					// get answer
					$sql = "select * from cvs_course_answer where question_id=@row_question_id";
					$query = $this->db->query($sql,$arrWhere);
					$arrAnswer = $query->result_array();
					$arrResult["exam_list"][$k]["question_list"][$kk]["answer_list"] = $arrAnswer;
					
					// foreach ($arrAnswer as $kkk => $vvv){
						// $arrResult["exam_list"][$k]["question_list"][$kk]["answer_list"][$kkk]["recheck_ans"] = $this->isAnswerCorrect($vvv['id']);
					// }
				}
			}
		}else{
			$arrResult = false;
		}
		return $arrResult;
	}
	public function lesson_info($question_id=null,$arrayFilter=array()){
		$arrWhere = array('@question_id'=>$question_id);
		$sql = "select distinct * 
					from mul_map_exam_question_lesson map
					left join mul_lesson l on map.lesson_id=l.lesson_id and l.status=1
					where question_id=@question_id";
		$query = $this->db->query($sql,$arrWhere);
		$arrResult = $query->result_array();		
		if($arrResult){
			foreach($arrResult as $k=>$v){
				$arrWhere = array( '@row_lesson_id'=>$v['lesson_id']);
				$sql = "select mul_content_id from mul_map_content_lesson where lesson_id=@row_lesson_id";
				$query = $this->db->query($sql,$arrWhere);
				$arrContent = $query->result_array();		
				$arrResult[$k]["lesson_content_map"] = $arrContent;
				// get content relate
				$arrRelate = array();
				foreach ($arrContent as $kk=>$vv){
					 $this->load->model('api/getRelate_model');
					 $req = array();
					 $req['this_content_id'] = $vv["mul_content_id"];
					 $req['type'] = "all";
					 $arrRelate[] = $this->getRelate_model->get_Learning_list($req);
				}
				$arrResult[$k]["relate_content_list"] = $arrRelate;
			}
		}
		
		return $arrResult;
	}
	public function user_score($exam_id=null,$user_id=null,$arrayFilter=array()){
		$arrWhere = array();
		
		if(!is_null($exam_id)){
			$arrWhere = array_merge($arrWhere,array( '@exam_id' => $exam_id));
		}else{
			return "ERROR : exam_id is required!!!";
		}
		if(!is_null($user_id)){
			$arrWhere = array_merge($arrWhere,array( '@user_id' => $user_id));
		}
		// get exam
		$sql = "select * from cvs_course_exam_score 
					where exam_id=COALESCE(@exam_id,exam_id) and user_id=COALESCE(@user_id,user_id)
					and id>=(select min(id) from cvs_course_exam_score where date_update>(select exam_add_date from cvs_course_examination where id=@exam_id))
		";
		$query = $this->db->query($sql,$arrWhere);
		if($query){
			$arrUserScore = $query->result_array();	
			$arrResult["user_score"] = $arrUserScore;

			foreach($arrUserScore as $k => $v){
				$ansValue = $v["answer_value"];
				$ansValue = unserialize($ansValue);
				// check answer value (true false)
				if(is_array($ansValue)){
					$ii = 0;
					foreach($ansValue as $kk => $vv){
						$qid = (int)$kk;
						$aid = (int)$vv;
						$arrResult["user_score"][$k]["ans_array"][$ii]["question_id"] = $qid;
						$arrResult["user_score"][$k]["ans_array"][$ii]["answer_id"] = $aid;
						$arrResult["user_score"][$k]["ans_array"][$ii]["isCorrect"] = $this->isAnswerCorrect($aid);
						$arrResult["user_score"][$k]["ans_array"][$ii]["lesson_list"] = $this->lesson_info($qid);
						$ii++;
					}
				}
			}
		}else{
			$arrResult = false;
		}
		return $arrResult;
	}
	public function isAnswerCorrect($answerID){
		$arrWhere = array('@answer_id'=>$answerID);
		$sql = "select count(*) cnt from cvs_course_answer where id=@answer_id and answer_ans='true'";
		$r = $this->db->query($sql,$arrWhere)->row()->cnt;
		if($r > 0){
			return true;
		}else{
			return false;
		}
	}
}	