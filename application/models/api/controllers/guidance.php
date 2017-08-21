<?php
//if (!defined('BASEPATH')) exit('No direct script access allowed'); //header('Content-type: text/html; charset=utf-8');

class guidance extends REST_Controller {

	public function __construct()
	{
		parent::__construct();

		//header('Access-Control-Allow-Origin: *');
		//header('Content-Type: application/json; charset=utf-8');

        //ob_clean
		$this->load->model('guidance_model');
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
   
   public function category_get($cat = null){
		$json = null;
		$isDetail = false;
		if($this->input->get("detail")==1){
			$isDetail = true;
		}
		
		$cat_id=0;
		$thisID = $this->input->get("cat_id");
		if($thisID){
			$cat_id=null;
		}
		
		$qResult = $this->guidance_model->get_categoryListById($cat_id,$isDetail,$thisID);
		foreach ($qResult as $k=> $v){
			$qResult[$k]['category'] = $this->guidance_model->get_categoryListById($v["id"],$isDetail);
			foreach ($qResult[$k]["category"] as $kk => $vv){
				$qResult[$k]["category"][$kk]["content"] = $this->guidance_model->get_cmsListById($vv["id"],$isDetail);
			}
		}
		//var_dump($qResult);

		$json['header'] = $this->_set_response()['response'];
		$json['data'] = $qResult;
		$this->response($json);
   }
   public function detail_get($type = 'content' , $id = null){
		$json = null;
		$isDetail = true;
		
		if(is_null($id)){ 
			$json= $this->_set_response_error("ID is required");
			$this->response($json);
			return;
		}
		
		if($type=='content'){
			$arr = $this->guidance_model->get_cmsListById(null,$isDetail,$id);
		}elseif($type=='category'){
			$arr = $this->guidance_model->get_categoryListById(null,$isDetail,$id);
		}else{
			$json= $this->_set_response_error("type not match");
			$this->response($json);
			return;
		}
		$qResult[$type] = $arr;
		$qResult["relate_content"] = array();
		$qResult["relate_exam"] = array();
		

		$json['header'] = $this->_set_response()['response'];
		$json['data'] = $qResult;
		$this->response($json);
   }
}
