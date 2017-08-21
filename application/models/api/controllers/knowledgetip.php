<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class KnowledgeTip extends Public_Controller {

	public function __construct()
	{
		parent::__construct();
		//if (ob_get_length() > 0) {       
		  ob_clean();
		//}
		//ini_set('display_errors', '1');
		//error_reporting(E_ALL);
	}
	
	public function get1tip()
	{
		$this->gettip(null,1,"RAND()");
	}
	
	public function gettip($group_id = null, $limit = 20, $orderby = null)
	{
		$json = null;
		$this->load->model('KnowledgeTip_model');
		
		if(isset($group_id))
			$arrWhere = array('groupID'=>$group_id);
		
		$qResult = $this->KnowledgeTip_model->getTip($arrWhere, $limit, $offset, $orderby);
		//var_dump($qResult);
		
		$json['header']['title'] = 'KnowledgeTip';
		$json['header']['status'] = '200';
		$json['header']['description'] = 'ok';
		$arr_result = array();
		if(isset($qResult) and $qResult) {
			$compData='';
			foreach ($qResult as $v_result) {
				$arr = array();
				$arr['d']['idx'] = $v_result['id'];
				$arr['d']['msg_topic'] = htmlspecialchars($v_result['msg_topic']);
				$arr['d']['msg_detail'] = htmlspecialchars(str_replace('"','',$v_result['msg_detail']));
				$arr_result[] = $arr['d'];
				//$arr_result[] = $arr;
				
			}
			
		}
		
		$json['data'] = $arr_result;
		$this->tppymemcached->set($key, $json, 259200);
		//header('Content-Type: application/json');
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
	}
	
}	