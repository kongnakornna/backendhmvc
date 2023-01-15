<?php

class quiz_model extends CI_Model {

    private $DBSelect, $DBEdit;

    public function __construct() {
        parent::__construct();
		$CI = & get_instance();
    }
	
	public function get_quizCategory($arrFilter=array()){
		$DBSelect = $this->load->database('select', TRUE);
		$criteria = ""; $orderby = ""; $sqllimit = "";
		
		$cid = $arrFilter['category_id'];
		if($cid!=null){
			$criteria .= " and id in ($cid)";
		}
		
		// -- order by
		$orderby = "id desc";
		
		
		$sql = "select * from cvs_game_categories
					where 1 ".$criteria."
					order by ".$orderby.$sqllimit;
		// echo $sql;
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$query = $DBSelect->query($sql);
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = array();
			}
			$this->tppymemcached->set($cache_key, $arrResult,300);
		}
		return $arrResult;
	}
	 
	 public function get_quizMain($arrFilter=array()){
		$DBSelect = $this->load->database('select', TRUE);
		$criteria = ""; $orderby = ""; $sqllimit = "";
		
		$this_id = $arrFilter['this_id'];
		if($this_id!=null){
			$criteria .= " and g.id in ($this_id)";
		}
		
		$ignore_id = $arrFilter['ignore_id'];
		if($ignore_id!=null){
			$criteria .= " and g.id not in ($ignore_id)";
		}
		
		$cat_id = $arrFilter['category_id'];
		if($cat_id!=null){
			$criteria .= " and g.game_cate_id in ($cat_id)";
		}
		
		$textSearch = $arrFilter['textSearch'];
		if($textSearch!=null){
			$criteria .= " and (g.title='".$textSearch."' or g.title like '%".$textSearch."%')";
		}
		
		$record_status = $arrFilter['record_status'];
		if($record_status=='all'){
			
		}elseif($record_status!=null){
			$criteria .= " and g.status in (".$record_status.")";
		}else{
			$criteria .= " and g.status=1";
		}
		
		if($arrFilter['moreCriteria'] && $arrFilter['moreCriteria']!=""){
			$criteria .= $arrFilter['moreCriteria'];
		}
		
		// -- order by
		$order = $arrFilter['order'];
		if($order=='view'){
			$orderby = " viewcount desc";
		}elseif($order=='play'){
			$orderby = " playcount desc";
		}elseif($order!=null){
			$orderby = $order;
		}else{
			$orderby = " g.id desc";
		}
		
		// -- limit
		$limit = $arrFilter['limit'];
		if($limit==null){
			$limit = 5;
		}
		$offset = $arrFilter['offset'];
		if($offset==null){
			$offset = 0;
		}
		$sqllimit = " limit ".$offset.",".$limit;
		
		$sql = "select 
					 g.id as game_id
					 ,g.title as topic
					 ,g.description as description
					 ,concat('http://static.trueplookpanya.com/tppy/',g.thumb) as thumbnail
					 ,concat('http://www.trueplookpanya.com/game/quiz/play/',g.id) as web_url
					 ,g.view as viewcount
					 ,g.played as playcount
					 ,(select count(*) cnt from cvs_game_quiz_question where quiz_main_id=g.id) questioncount
					 ,g.cdate as addDate
					 ,g.status as recordStatus
					 ,g.meta_title as meta_title
					 ,g.meta_description as meta_description
					 ,g.game_cate_id as category_id
					 ,c.title as category_name
					 -- ,c.image as category_image
					from cvs_game_quiz_main g
					 left join cvs_game_categories c on g.game_cate_id=c.id
					where 1 ".$criteria."
					order by ".$orderby.$sqllimit;
		
		//echo $sql;
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$query = $DBSelect->query($sql);
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = array();
			}
			$this->tppymemcached->set($cache_key, $arrResult,31);
		}
		
		// get fresh data
		foreach($arrResult as $k=>$v){
			$quiz_id = $v["game_id"];
			$arrResult[$k]["viewcount"] = $this->tppy_utils->ViewNumberGet($quiz_id,"cvs_game_quiz_main");
		}
		
		return $arrResult;
	}
	
	public function get_quizDetail($quiz_id=0, $arrFilter=array()){
		$DBSelect = $this->load->database('select', TRUE);
		$criteria = ""; $orderby = ""; $sqllimit = ""; $arrResult = array();
		
		$cache_key = "QUIZ_DETAIL+".$quiz_id;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			
			$filter = array();
			$filter['this_id']=$quiz_id;
			$arrQuiz = $this->get_quizMain($filter);
			if($arrQuiz){
				$arrResult = $arrQuiz[0];
				
				// get question list
				$sql = "SELECT id as question_id,question as question_title, quiz_main_id as game_id 
							,concat('http://static.trueplookpanya.com/tppy/',image) as question_image
							,question_youtube as question_youtube
							FROM cvs_game_quiz_question 
							WHERE quiz_main_id=%quiz_id%";
				$arrQuestion = $this->db->query($sql, array(
				  '%quiz_id%'=> $quiz_id,
				));
				if($arrQuestion){
					$arrResult['question_list'] = $arrQuestion->result_array();
					foreach($arrResult['question_list'] as $k => $v){
						$question_id = $v['question_id'];
						// get choice list
						$sql = "SELECT id as choice_id, description as choice_title
									,concat('http://static.trueplookpanya.com/tppy/',thumb) as choice_thumbnail
									,concat('http://static.trueplookpanya.com/tppy/',image) as choice_image
									, score as choice_score, answer as choice_answer, quiz_question_id as question_id
							FROM cvs_game_quiz_choice 
							WHERE quiz_question_id=%question_id%";
						$arrChoice = $this->db->query($sql, array(
						  '%question_id%'=> $question_id,
						));
						if($arrChoice){
							$arrResult['question_list'][$k]['choice_list']=$arrChoice->result_array();
						}
					}
				}
				
				// get answer list
				$sql = "SELECT id as answer_id
							,title as answer_title,description as answer_description, min as score_min, max as score_max
							,concat('http://static.trueplookpanya.com/tppy/',thumb) as answer_thumbnail
							,concat('http://static.trueplookpanya.com/tppy/',image) as answer_image
							, quiz_main_id as game_id 
							FROM cvs_game_quiz_answer 
							WHERE quiz_main_id=%quiz_id%";
				$arrAnswer = $this->db->query($sql, array(
				  '%quiz_id%'=> $quiz_id,
				));
				if($arrAnswer){
					$arrResult['answer_list'] = $arrAnswer->result_array();
				}
			}

			
			$this->tppymemcached->set($cache_key, $arrResult,60);
		}
		
		// set viewcount
		// $this->set_viewcount($quiz_id);
		$this->tppy_utils->ViewNumberSet($quiz_id,"cvs_game_quiz_main");
		
		// get fresh data
		$arrResult["viewcount"] = $this->tppy_utils->ViewNumberGet($quiz_id,"cvs_game_quiz_main");

		return $arrResult;
	}
	
	public function get_AnswerByScore($quiz_id=0,$score_value=0){
		$arrResult = array();
		$cache_key = "QUIZ_ANSWER+".$quiz_id."+".$score_value;
		
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			
			$sql = "select id as answer_id
						,title as answer_title,description as answer_description, '$score_value' as score_value, min as score_min, max as score_max
						,concat('http://static.trueplookpanya.com/tppy/',thumb) as answer_thumbnail
						,concat('http://static.trueplookpanya.com/tppy/',image) as answer_image
						, quiz_main_id as game_id 
						from cvs_game_quiz_answer where quiz_main_id=%quiz_id% and min<=%score_value% and max>=%score_value%
						limit 1";
			$arrAnswer = $this->db->query($sql, array(
			  '%quiz_id%'=> $quiz_id,
			  '%score_value%'=>$score_value,
			));
			if($arrAnswer){
				$arrResult = $arrAnswer->result_array();
			}
			
			$this->tppymemcached->set($cache_key, $arrResult,60);
		}
		$this->set_playcount($quiz_id);
		return $arrResult;
	}
	
	public function set_playcount($quiz_id=0){
		$sql = "update cvs_game_quiz_main set played=played+1 where id=%quiz_id%";
		$arrAnswer = $this->db->query($sql, array(
			  '%quiz_id%'=> $quiz_id,
		));
		if($arrAnswer){
			return true;
		}else{
			return false;
		}
	}
	public function set_viewcount($quiz_id=0){
		$sql = "update cvs_game_quiz_main set view=view+1 where id=%quiz_id%";
		$arrAnswer = $this->db->query($sql, array(
			  '%quiz_id%'=> $quiz_id,
		));
		if($arrAnswer){
			return true;
		}else{
			return false;
		}
	}
}	