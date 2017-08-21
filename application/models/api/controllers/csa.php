<?php
//if (!defined('BASEPATH')) exit('No direct script access allowed'); //header('Content-type: text/html; charset=utf-8');

class CSA extends REST_Controller {

	public function __construct()
	{
		parent::__construct();

		//header('Access-Control-Allow-Origin: *');
		//header('Content-Type: application/json; charset=utf-8');

        //ob_clean();
	}

	private function _set_response(){
      return array('response'=> array(
         'status'=>TRUE,
         "massage"=>'success',
         'code'=>200
      ));
   }
   private function _set_not_found(){
      return array('response'=> array(
         'status'=>FALSE,
         "massage"=>'Data not found',
         'code'=>404
      ),
      'data'=>[]);
   }
   private function _set_response_error($msg = "unkown error", $code = 400){
      return array('response'=> array(
         'status'=>FALSE,
         "massage"=>$msg,
         'code'=>$code
      ),
      'data'=>[]);
   }

	public function categorylist_get($order = '', $limit = 10) {
		$json = null;
		$this->load->model('CSA_model');
		$this->load->model('webboard_model');


		$limit = ($limit > 100) ? 100 : $limit;
		if($order=='name') { $v_order='wc.wb_category_name asc'; }
		else { $v_order=''; }
		$room = 4;	// fix for CSA category

		$qResult = $this->webboard_model->getCategory($room, null , $v_order, $limit);
		$json['header']['title'] = 'CSA_getCategoryList';
		$json['header']['link_all'] = base_url().'true/webboard.php';
		$arr_result = array();
		if(isset($qResult) and $qResult) {
			$compData='';
			foreach ($qResult as $v_result) {
				$arr = array();
				if($compData!=$v_result['wb_room_id']){
					$arr['room']['roomId'] = $v_result['wb_room_id'];
					$arr['room']['roomName'] = $v_result['wb_room_name'];
					$arr_result['room'][] = $arr['room'];
				}
				$arr['category']['categoryId'] = $v_result['wb_category_id'];
				$arr['category']['categoryName'] = htmlspecialchars($v_result['wb_category_name']);
				$arr['category']['categoryDescription'] = htmlspecialchars($v_result['wb_category_desc']);
				$arr['category']['web_url_go'] = 'http://www.trueplookpanya.com/true/webboard_list.php?cateid='.$v_result['wb_category_id'];
				$arr_result['category'][] = $arr['category'];
				//$arr_result[] = $arr;
				$compData = $v_result['wb_room_id'];
			}

		}
		$json['data'] = $arr_result;
        $this->tppymemcached->set($key, $json, 259200);
		//echo json_encode($json, JSON_UNESCAPED_UNICODE);
		$this->response($json);
	}

	public function topiclist_get($categoryid = null, $order = '') {
		$json = null;
		$this->load->model('CSA_model');
		$this->load->model('webboard_model');

		if(isset($_GET['q']) && !empty($_GET['q'])){
			//$topicSearch = mysql_prep($_GET['q']);
			$topicSearch = trim($_GET['q']);
			//echo 'q='.$topicSearch.'\n';
			//var_dump($_GET); die;
		}

		$pageSize = 10;
		if(isset($_GET['pageNo']) && !empty($_GET['pageNo'])){
			$pageNo = trim($_GET['pageNo']);
		}
		else {
			$pageNo = 1;
		}
		//$limit = ($limit > 100) ? 100 : $limit;
		$rowStart = (($pageNo-1) * $pageSize);
		$rowEnd = ($pageNo * $pageSize);

		if($order=='lastreply') { $v_order='wp.last_update_date desc'; }
		else { $v_order=''; }

		if($this->input->get('room')){
			$room=$this->input->get('room');
		}else{
			$room = 4;	// fix for CSA category
		}

		$qResult = $this->webboard_model->getTopic($room, $categoryid, $topicSearch , $v_order, $pageSize, $rowStart);
		$json['header']['title'] = 'CSA_getTopicListByCategoryId';
		$json['header']['link_all'] = base_url().'true/webboard.php';
		$arr_result = array();
		if(isset($qResult) and $qResult) {
			$compData1='';
			$compData2='';
			$i=-1;
			$j=-1;
			foreach ($qResult as $v_result) {
				$arr = array();
				if($compData1!=$v_result['wb_room_id']){
					$i++;
					$arr['room']['roomId'] = $v_result['wb_room_id'];
					$arr['room']['roomName'] = $v_result['wb_room_name'];
					$arr_result['room'][$i] = $arr['room'];
				}
				if($compData2!=$v_result['wb_category_id']){
					$j++;
					$arr['room']['category']['categoryId'] = $v_result['wb_category_id'];
					$arr['room']['category']['categoryName'] = htmlspecialchars($v_result['wb_category_name']);
					$arr['room']['category']['categoryDescription'] = htmlspecialchars($v_result['wb_category_desc']);
					$totalRows = $this->webboard_model->getCountTopic($v_result['wb_category_id']);
					$pageTotal = ceil($totalRows / $pageSize);
					$arr['room']['category']['rowCount'] = $totalRows;
					$arr['room']['category']['rowRange'] = trim(($rowStart+1).'-'.$rowEnd);
					$arr['room']['category']['pageTotal'] = $pageTotal;
					$arr_result['room'][$i]['category'][$j] = $arr['room']['category'];
				}
				$arr['room']['category']['topic']['topicId'] = $v_result['wb_post_id'];
				$arr['room']['category']['topic']['topicTitle'] = htmlspecialchars($v_result['wb_subject']);
				$arr['room']['category']['topic']['topicDetail'] = htmlspecialchars($v_result['wb_detail']);
				$arr['room']['category']['topic']['createBy'] = htmlspecialchars($v_result['psn_display_name']);
				$arr['room']['category']['topic']['createByImage'] = $v_result['psn_display_image'];
				$arr['room']['category']['topic']['createDate'] = $v_result['add_date'];
				$arr['room']['category']['topic']['lastupdateDate'] = $v_result['last_update_date'];
				$arr['room']['category']['topic']['web_url_go'] = 'http://www.trueplookpanya.com/true/webboard_detail.php?postid='.$v_result['wb_post_id'];
				$arr['room']['category']['topic']['flagPin'] = $v_result['flag_pin'];
				$arr['room']['category']['topic']['viewCount'] = $this->webboard_model->getViewNumber($v_result['wb_post_id'], '15');//$v_result['viewcount'];
				$arr['room']['category']['topic']['replyCount'] = $this->webboard_model->getCountReply($v_result['wb_post_id']);
				$lastReply = $this->webboard_model->getLastestReply($v_result['wb_post_id']);
				if($lastReply)
				{
					$arr['room']['category']['topic']['lastReplyId'] = $lastReply['id'];
					$arr['room']['category']['topic']['lastReplyName'] = $lastReply['name'];
					$arr['room']['category']['topic']['lastReplyImage'] = $lastReply['display_image'];
					$arr['room']['category']['topic']['lastReplyDate'] = $lastReply['date'];
				}
				else
				{
					$arr['room']['category']['topic']['lastReplyId'] = '';
					$arr['room']['category']['topic']['lastReplyName'] = '';
					$arr['room']['category']['topic']['lastReplyImage'] = '';
					$arr['room']['category']['topic']['lastReplyDate'] = '';
				}

				$arr_result['room'][$i]['category'][$j]['topic'][] = $arr['room']['category']['topic'];

				$compData1 = $v_result['wb_room_id'];
				$compData2 = $v_result['wb_category_id'];
			}
		}
		$json['data'] = $arr_result;
		$json['header']['search'] = $q;
		$json['header']['orderby'] = $order;
		$json['header']['pageNo'] = $pageNo;
		$json['header']['pageSize'] = $pageSize;
        $this->tppymemcached->set($key, $json, 259200);
		//echo json_encode($json, JSON_UNESCAPED_UNICODE);
		$this->response($json);
	}


	public function topicdetail_get($topicid = null, $order_reply = '', $limit_reply = 10) {
		$json = null;
		$this->load->model('CSA_model');
		$this->load->model('webboard_model');

		$order=$order_reply;

		$pageSize = $limit_reply;
		$pageSize = ($pageSize > 100) ? 100 : $pageSize;
		if(isset($_GET['pageNo']) && !empty($_GET['pageNo'])){
			$pageNo = trim($_GET['pageNo']);
		}
		else {
			$pageNo = 1;
		}
		$rowStart = (($pageNo-1) * $pageSize);
		$rowEnd = ($pageNo * $pageSize);

		if($order=='lastreply') { $v_order='wr.reply_datetime desc'; }
		else { $v_order=''; }
		$room = 4;	// fix for CSA category

		// get Topic
		$qResult = $this->webboard_model->getTopic($room, null, null , null, 1, null, $topicid);
		$json['header']['title'] = 'CSA_getTopicDetailByTopicId';
		$json['header']['link_all'] = base_url().'true/webboard.php';
		$arr_result = array();
		if(isset($qResult) and $qResult) {
			$compData1='';
			$compData2='';
			$comData3='';
			$i=-1;
			$j=-1;
			$k=-1;
			foreach ($qResult as $v_result) {
				$arr = array();
				if($compData1!=$v_result['wb_room_id']){
					$i++;
					$arr['room']['roomId'] = $v_result['wb_room_id'];
					$arr['room']['roomName'] = $v_result['wb_room_name'];
					$arr_result['room'][$i] = $arr['room'];
				}
				if($compData2!=$v_result['wb_category_id']){
					$j++;
					$arr['room']['category']['categoryId'] = $v_result['wb_category_id'];
					$arr['room']['category']['categoryName'] = htmlspecialchars($v_result['wb_category_name']);
					$arr['room']['category']['categoryDescription'] = htmlspecialchars($v_result['wb_category_desc']);
					$arr_result['room'][$i]['category'][$j] = $arr['room']['category'];
				}
				if($compData3!=$v_result['wb_post_id']){
					$k++;
					$arr['room']['category']['topic']['topicId'] = $v_result['wb_post_id'];
					$arr['room']['category']['topic']['topicTitle'] = htmlspecialchars($v_result['wb_subject']);
					$arr['room']['category']['topic']['topicDetail'] = htmlspecialchars($v_result['wb_detail']);
					$arr['room']['category']['topic']['createBy'] = htmlspecialchars($v_result['psn_display_name']);
					$arr['room']['category']['topic']['createByImage'] = $v_result['psn_display_image'];
					$arr['room']['category']['topic']['createDate'] = $v_result['add_date'];
					$arr['room']['category']['topic']['viewcount'] = $v_result['viewcount'];
					$arr['room']['category']['topic']['lastupdateDate'] = $v_result['last_update_date'];
					$arr['room']['category']['topic']['web_url_go'] = 'http://www.trueplookpanya.com/true/webboard_detail.php?postid='.$v_result['wb_post_id'];
					$arr['room']['category']['topic']['flagPin'] = $v_result['flag_pin'];
					$totalRows = $this->webboard_model->getCountReply($v_result['wb_post_id']);
					$pageTotal = ceil($totalRows / $pageSize);
					$arr['room']['category']['topic']['rowCount'] = $totalRows;
					$arr['room']['category']['topic']['rowRange'] = trim(($rowStart+1).'-'.$rowEnd);
					$arr['room']['category']['topic']['pageTotal'] = $pageTotal;
					$arr_result['room'][$i]['category'][$j]['topic'][] = $arr['room']['category']['topic'];
				}

				// get Reply
				$qResultR = $this->webboard_model->getTopicDetail($topicid, $v_order, $pageSize, $rowStart);
				if(isset($qResultR) and $qResultR) {
					foreach ($qResultR as $v_resultR) {
						$arr['room']['category']['topic']['reply']['replyId'] = $v_resultR['wb_reply_id'];
						$arr['room']['category']['topic']['reply']['replyDetail'] = htmlspecialchars($v_resultR['reply_detail']);
						$arr['room']['category']['topic']['reply']['replyBy'] = htmlspecialchars($v_resultR['replyBy']);
						$arr['room']['category']['topic']['reply']['replyByImage'] = $v_resultR['replyByImage'];
						$arr['room']['category']['topic']['reply']['replyDate'] = $v_resultR['reply_datetime'];
						$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = $arr['room']['category']['topic']['reply'];
					}
				}

				$compData1 = $v_result['wb_room_id'];
				$compData2 = $v_result['wb_category_id'];
				$compData3 = $v_result['wb_post_id'];
			}
		}


		$json['data'] = $arr_result;
		$json['header']['orderby'] = $order;
		$json['header']['pageNo'] = $pageNo;
		$json['header']['pageSize'] = $pageSize;
        $this->tppymemcached->set($key, $json, 259200);
		//echo json_encode($json, JSON_UNESCAPED_UNICODE);
		$this->response($json);
	}

	public function schoolmaster_get($orderby = null, $limit = 100, $offset = null) {
		$json = null;
		$this->load->model('CSA_model');

		$id = null;
		$name = null;
		$tvs = null;

		if(isset($_GET['id']) && !empty($_GET['id'])){
			$id = trim($_GET['id']);
		}
		if(isset($_GET['name']) && !empty($_GET['name'])){
			$name = trim($_GET['name']);
		}
		if(isset($_GET['tvs']) && !empty($_GET['tvs'])){
			$tvs = trim($_GET['tvs']);
		}

		$qResult = $this->CSA_model->getSchoolMaster($orderby, $limit , $offset,$id , $name , $tvs);
		$arr_result = array();
		if(isset($qResult) and $qResult) {
			$json['response']['status'] = true;
			$json['response']['message'] = 'success';
			$json['response']['code'] = 200;
			$arr_result = $qResult;
			//var_dump($qResult); exit();
			foreach($arr_result as $key => $v){
				$arrOnet = $this->CSA_model->getONETdata($v->id , $type="master");
				$arr_result[$key]->onet_data = $arrOnet;
			}
		}else{
			$json['response']['status'] = false;
			$json['response']['message'] = 'no data';
			$json['response']['code'] = 400;
		}
		$json['data'] = $arr_result;
        $this->tppymemcached->set($key, $json, 259200);

        //echo json_encode($json, JSON_UNESCAPED_UNICODE);
		$this->response($json);
	}

	public function sync_get()
	{
		$CI = & get_instance();
		$data = array();
		$this->load->model('CSA_model');

		$last=$this->get('last', TRUE, time());
		$isData=$this->get('data', TRUE, false);

		$qResult=$this->CSA_model->getSyncData($last,$isData);
		if($qResult){
			$data = $this->_set_response();
			$arr_result = $qResult["data"];
			//var_dump($qResult); exit();
			if($arr_result["insert"]){
				foreach($arr_result["insert"] as $key => $v){
					$arrOnet = $this->CSA_model->getONETdata($v->id , $type="master");
					$arr_result["insert"][$key]->onet_data = $arrOnet;
				}
			}
			if($arr_result["update"]){
				foreach($arr_result["update"] as $key => $v){
					$arrOnet = $this->CSA_model->getONETdata($v->id , $type="master");
					$arr_result["update"][$key]->onet_data = $arrOnet;
				}
			}
			$data['data'] = $arr_result;
		}else{
			$data = $this->_set_response_error("[e01] wrong date", 404);
		}
		$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
	}

	public function readydump_get()
	{
		$CI = & get_instance();
		$last=$this->get('last', TRUE, time());

		$data = array();
		$this->load->model('CSA_model');
		$data = $this->_set_response();
		$data['data'] = $this->CSA_model->_isReadyDump($last);

		$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
	}

	public function listschoolstate_get()
	{
		$CI = & get_instance();
		$data = array();
		$this->load->model('trueplookpanyaschools/Csa_model');
		$data = $this->_set_response();
		$data['data'] = $this->Csa_model->schoolStateList();


		$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
	}
	public function listschoolunder_get()
	{
		$CI = & get_instance();
		$data = array();
		$this->load->model('trueplookpanyaschools/Csa_model');
		$data = $this->_set_response();
		$data['data'] = $this->Csa_model->schoolUnderList();

		$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
	}
	public function listschoollevel_get()
	{
		$CI = & get_instance();
		$data = array();
		$this->load->model('trueplookpanyaschools/Csa_model');
		$data = $this->_set_response();
		$data['data'] = $this->Csa_model->schoolLevelList();

		$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
	}
	public function listschoolgroup_get()
	{
		$CI = & get_instance();
		$data = array();
		$this->load->model('trueplookpanyaschools/Csa_model');
		$data = $this->_set_response();
		$data['data'] = $this->Csa_model->schoolGroupList();

		$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
	}
	public function listinternetisp_get()
	{
		$CI = & get_instance();
		$data = array();
		$this->load->model('trueplookpanyaschools/Csa_model');
		$data = $this->_set_response();
		$data['data'] = $this->Csa_model->getInternet();

		$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
	}
	public function listinternetspeed_get()
	{
		$CI = & get_instance();
		$data = array();
		$this->load->model('trueplookpanyaschools/Csa_model');
		$data = $this->_set_response();
		$data['data'] = $this->Csa_model->getInternetSpeed();

		$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
	}
}
