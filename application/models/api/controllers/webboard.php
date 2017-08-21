<?php
//if (!defined('BASEPATH')) exit('No direct script access allowed'); //header('Content-type: text/html; charset=utf-8');

class webboard extends REST_Controller {

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
   
   public function getcategoryname_get($categoryid=0, $getFullParent=true){
	   $filter['getFullParent']=$getFullParent;
	   $this->load->model('webboard_model');
	   $json['header'] = $this->_set_response()['response'];
	   $json['data'] = $this->webboard_model->getCategoryName($categoryid,$filter);
	   $this->response($json);
   }

	public function categorylist_get($order = '', $limit = 10) {
		$json = null;
		$this->load->model('webboard_model');

		// if(isset($_GET['cat']) && !empty($_GET['cat'])){
			// $category = trim($_GET['cat']);
		// }
		// if(isset($_GET['room']) && !empty($_GET['room'])){
			// $room = trim($_GET['room']);
		// }
		// if(isset($_GET['parentcat']) && !empty($_GET['parentcat'])){
			// $parent_category = $_GET['parentcat'];
		// }
		$room = $this->input->get('room');
		$category = $this->input->get('cat');
		$parent_category = $this->input->get('parentcat');

		$limit = ($limit > 100) ? 100 : $limit;
		if($order=='name') { $v_order='wc.wb_category_name asc'; }
		else { $v_order=''; }

		$filter = array();
		$filter['parentcat'] = $parent_category;
		$qResult = $this->webboard_model->getCategory($room, $category , $v_order, $limit, $offset = null, $filter);
		$json['header']['title'] = 'Webboard getCategoryList';
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
		$this->load->model('webboard_model');
		$filter=array();

		if(isset($_GET['q']) && !empty($_GET['q'])){
			//$topicSearch = mysql_prep($_GET['q']);
			$topicSearch = trim($_GET['q']);
			//echo 'q='.$topicSearch.'\n';
			//var_dump($_GET); die;
		}
		if(isset($_GET['order']) && !empty($_GET['order'])){
			$order = $_GET['order'];
		}

		if(isset($_GET['pageSize']) && !empty($_GET['pageSize'])){
			$pageSize=$this->input->get('pageSize');
		}else{ $pageSize = 10; }

		
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
		elseif($order=='lasttopic') { $v_order = 'wp.wb_post_id desc'; }
		else { $v_order=''; }
		
		if($this->input->get('room_id')){
			$room=$this->input->get('room_id');
		}
		if($this->input->get('category_id')){
			$categoryid=$this->input->get('category_id');
		}
		
		if($this->input->get('pin')){
			$flag_pin=$this->input->get('pin');
			if($flag_pin == "1"){
				$filter['isPin']=1;
			}else{$filter['isPin']=Null;}
			//echo '$filter[isPin]=>'.$filter['isPin']; Die();
		}
		
		
		$qResult = $this->webboard_model->getTopic($room, $categoryid, $topicSearch , $v_order, $rowStart, $rowEnd , null , $filter);
		$json['header']['title'] = 'Webboard_getTopicListByCategoryId';
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


	public function topicdetail_get() {
	
	
	    $categoryid=@$this->input->get('category_id');  
			if($categoryid==Null){$categoryid=Null;}
		$room=@$this->input->get('room');
			if($room==Null){$room=Null;}
		$topicSearch=@$this->input->get('topicSearch');
			if($topicSearch==Null){$topicSearch=Null;}
		$order_reply=@$this->input->get('order_reply');
			if($order_reply==Null){$order_reply=Null;}
		$limit_reply=@$this->input->get('limit_reply');
			if($limit_reply==Null){$limit_reply=20;}
		$offset=@$this->input->get('offset');
			if($offset==Null){$offset=0;}
		$topicid=@$this->input->get('topicid');
			if($topicid==Null){$topicid=Null;}
			
			
		$json = null;
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

		if($order=='lastreply') { $v_order='reply_datetime desc'; }
		else { $v_order=''; }
		

		// get Topic
		$qResult = $this->webboard_model->getTopic($room, null, null , null, 1, null, $topicid);
		$json['header']['title'] = 'Webboard_getTopicDetailByTopicId';
		$json['header']['link_all'] = base_url().'true/webboard.php';
		$json['header']['code'] = 200;
		$json['header']['status'] = true;
		$json['header']['message'] = 'Success';
		$json['header']['remark'] = 'api/webboard/topicdetail?topicid=wb_post_id';
		$json['header']['type'] = 'HTTP GET  HTTP_OK The request has succeeded';
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
					$arr['room']['category']['topic']['viewcount'] = $this->webboard_model->getViewNumber($v_result['wb_post_id'], '15');//$v_result['viewcount'];
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
				
				//og // node Social
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:app_id'] = "704799662982418";
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:title'] = htmlspecialchars($v_result['wb_subject']);
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:type'] = 'article';
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:locale'] = 'th_TH';
					$$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:url'] = $v['web_url'].$menu_code;
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:image'] = $v_result['psn_display_image'];
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:site_name'] = 'trueplookpanya.com';
					$wb_detail= htmlspecialchars($v_result['wb_detail']);
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:description'] = $this->trueplook->limitText(strip_tags(str_replace('\'', ' ', str_replace('\"', ' ', $wb_detail))), 200);
				

				// get Reply
				$qResultR = $this->webboard_model->getTopicDetail($topicid, $v_order, $pageSize, $rowStart);
				if(isset($qResultR) and $qResultR) {
					foreach ($qResultR as $v_resultR) {
						if($v_resultR['wb_reply_id']!=null || !empty($v_resultR['wb_reply_id'])){
							$arr['room']['category']['topic']['reply']['replyId'] = $v_resultR['wb_reply_id'];
							$arr['room']['category']['topic']['reply']['replyDetail'] = htmlspecialchars($v_resultR['reply_detail']);
							$arr['room']['category']['topic']['reply']['replyBy'] = htmlspecialchars($v_resultR['replyBy']);
							$arr['room']['category']['topic']['reply']['replyByImage'] = $v_resultR['replyByImage'];
							$arr['room']['category']['topic']['reply']['replyDate'] = $v_resultR['reply_datetime'];
							$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = $arr['room']['category']['topic']['reply'];
						}else{
							$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = null;
						}
					}
				}else{
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = null;
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
	public function test_get(){
		$this->load->model('webboard_model');
		//$a = $this->webboard_model->getTopic(6, $cat_id, null, null, 3, null, null, array('isPin' => true));
		
		$boardCat = array(25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58);
		$a = $this->webboard_model->getTopic(6, $boardCat, null, "wp.wb_category_id asc, wp.wb_post_id, wp.add_date DESC", 5); //true is room id 6 and $boardCat
		var_dump($a);
	}
	

        
}
