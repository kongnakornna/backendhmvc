<?php
//if (!defined('BASEPATH')) exit('No direct script access allowed'); //header('Content-type: text/html; charset=utf-8');

class quiz extends REST_Controller {

	public function __construct()
	{
		parent::__construct();

		//header('Access-Control-Allow-Origin: *');
		//header('Content-Type: application/json; charset=utf-8');

        //ob_clean
		$this->load->model('quiz_model');
	}

	private function _set_response(){
      return array('response'=> array(
         'status'=>TRUE,
         "massage"=>'success',
         'code'=>200
      ));
   }
   private function _set_not_found(){
      return array('header'=> array(
         'status'=>FALSE,
         "massage"=>'Data not found',
         'code'=>404
      ),
      'data'=>[]);
   }
   private function _set_response_error($msg = "unkown error", $code = 400){
      return array('header'=> array(
         'status'=>FALSE,
         "massage"=>$msg,
         'code'=>$code
      ),
      'data'=>[]);
   }
   
   public function test_get(){
		$json = null;
		$arrFilter = array();

		//$token = "fPiLuIx9VlgTo9OyN-R0sjLTk3PzIzODY9NTJAJVZTQldYKCwlPDI7RUJIRTJDRTg9M0E2OkIyQkI3Ny86NDw4M0FGNTlHMjg5MGRmeCU_KERMWVZEU0ZZWCQtIzU1NjAzNDInOyNKTEFRUUQnMiI8Ozg-MjMoPSRFSkJXS1NYJnw=";
		// $zone = "all";
		// $token = "session";
		// $qResult = $this->tppy_utils->isAdmin($zone , $token);
		
		//$qResult = $this->quiz_model->get_quizMain($arrFilter);
		
		//var_dump($qResult);
		$json['header'] = $this->_set_response()['response'];
		$json['data'] = $qResult;
		$this->response($json);
   }
   
   public function quizcategory_get(){
		$json = null;
		$arrFilter = array();
		
	   if($catid = $this->input->get("cat_id")){
			$arrFilter['category_id'] = $catid;
		}
		
		
		$qResult = $this->quiz_model->get_quizCategory($arrFilter);
		//var_dump($qResult);

		$json['header'] = $this->_set_response()['response'];
		$json['data'] = $qResult;
		$this->response($json);
   }
   
   public function quizlist_get(){
		$json = null;
		$arrFilter = array();
		
		
		if($catid = $this->input->get("cat_id")){
			$arrFilter['category_id'] = $catid;
		}
		if($q = $this->input->get("q")){
			$arrFilter['textSearch'] = $q;
		}
		
		if($order = $this->input->get("order")){
			$arrFilter['order'] = $order;
		}
		
		if (isset($_GET['limit']) && !empty($_GET['limit'])) {
            $limit = trim($_GET['limit']);
        }else{
			$limit = 20;
		}
		$arrFilter['limit'] = $limit;
		
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = trim($_GET['offset']);
			$arrFilter['offset'] = $offset;
        }		
		
		$qResult = $this->quiz_model->get_quizMain($arrFilter);
		//var_dump($qResult);

		$json['header'] = $this->_set_response()['response'];
		$json['data'] = $qResult;
		$this->response($json);
   }
   public function quizdetail_get($quiz_id=0){
		$json = null;
		$arrFilter = array();
		
		if($quiz_id==0){
			$this->response($this->_set_not_found());
			return;
		}
		$qResult = $this->quiz_model->get_quizDetail($quiz_id);
		//var_dump($qResult);

		$json['header'] = $this->_set_response()['response'];
		$json['data'] = $qResult;
		$this->response($json);
   }
   public function quizanswer_get($quiz_id=0,$score_value=0){
		$json = null;
		$arrFilter = array();
		
		if($quiz_id==0){
			$this->response($this->_set_not_found());
			return;
		}
		$qResult = $this->quiz_model->get_AnswerByScore($quiz_id,$score_value);
		//var_dump($qResult);

		$json['header'] = $this->_set_response()['response'];
		$json['data'] = $qResult;
		$this->response($json);
   }
}
