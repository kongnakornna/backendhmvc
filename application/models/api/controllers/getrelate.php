<?php

//(defined('BASEPATH')) OR exit('No direct script access allowed');
class getRelate extends REST_Controller {

    public function __construct() {
        parent::__construct();
        //if (ob_get_length() > 0) {       
        ob_clean();
        //}
        //ini_set('display_errors', '1');
        //error_reporting(E_ALL);
    }

    private function _set_response() {
        return array('response' => array(
                'status' => TRUE,
                "massage" => 'success',
                'code' => 200
        ));
    }

    private function _set_not_found() {
        return array('response' => array(
                'status' => FALSE,
                "massage" => 'Data not found',
                'code' => 404
            ),
            'data' => []);
    }

    private function _set_response_error($msg = "unkown error", $code = 400) {
        return array('response' => array(
                'status' => FALSE,
                "massage" => $msg,
                'code' => $code
            ),
            'data' => []);
    }
	
	// new KNOWLEDGE = LEARNING : start
	
	public function learning_get() {

        $req = [];

        $req['subject_id'] = $_GET['sid'];
        $req['level_id'] = $_GET['lid'];
        $req['knowledge_id'] = $_GET['ssid'];
		$req['context_id'] = $_GET['cid'];
        $req['q'] = $_GET['q'];

        $req['start'] = $_GET['limit'];
        $req['end'] = $_GET['offset'];
		$req['order'] = $_GET['order'];
        $req['type'] = "all"; //$_GET['type'];
		$req['labeltagList'] = $_GET['lt'];
		
		$json['header']['title'] = 'getRelate Learning';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		
		$this->load->model('getRelate_model');
        $qResult = $this->getRelate_model->get_Learning_list($req);
        $json['data'] = $qResult;
		
        $this->response($json);
	}
	public function learningdetail_get($mul_content_id = 0){
		
		$json['header']['title'] = 'getRelate Learning';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		
		$this->load->model('getRelate_model');
        $qResult = $this->getRelate_model->get_Learning_detail($mul_content_id);
        $json['data'] = $qResult;
		
        $this->response($json);
	}
	public function learningdetailpreview_get($mul_content_id = 0){
		
		$json['header']['title'] = 'getRelate Learning preview';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		
		$this->load->model('getRelate_model');
		$arrFilter["record_status"]="all";
		$arrFilter["isUniverseContent"]=true;
        //$qResult = $this->getRelate_model->get_Learning_detail($mul_content_id);
		$content_id=$mul_content_id;
		$qResult = $this->getRelate_model->get_Learning_detail($content_id,$arrFilter);
        $json['data'] = $qResult;
		
        $this->response($json);
	}
	// new KNOWLEDGE = LEARNING : end
    public function getdetailcontent_get($content_id = null) {
        $json = null;
        $this->load->model('getRelate_model');
        $qResult = $this->getRelate_model->getDetail_content($content_id, 'mul_content');
        //var_dump($qResult);

        $json['header']['title'] = 'get Content Detail';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = $v['child_view_count']; //$this->trueplook->getViewNumber($v['content_child_id'], 7); 
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr['d']['sub_category_id'] = $v['sub_category_id'];
                $arr['d']['sub_category_name'] = $v['sub_category_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }
	
    public function knowledgesource_get($content_id = null) {
        $json = null;
        $this->load->model('getRelate_model');

        $limit = null;
        $offset = null;
        $orderby = null;
        $qResult = $this->getRelate_model->getRelate_source($content_id, $limit, $offset, $orderby);
        //var_dump($qResult);

        $json['header']['title'] = 'getRelate Content';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                if ($v['content_child_id'] == '') {
                    $arr['d']['viewcount'] = $v['content_view_count']; //$this->trueplook->getViewNumber($v['content_id'], 0); //$v['viewcount'];
                } else {
                    $arr['d']['viewcount'] = $v['child_view_count']; //$this->trueplook->getViewNumber($v['content_child_id'], 7); //$v['viewcount'];
                }
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr['d']['sub_category_id'] = $v['sub_category_id'];
                $arr['d']['sub_category_name'] = $v['sub_category_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;

        $this->response($json);
    }

    public function knowledgecontent_get($content_id = null, $limit = 20, $order = "random") {
        $json = null;
        $subject_id = null;
        $level_id = null;
        $context_id = null;

        if (isset($_GET['sid']) && !empty($_GET['sid'])) {
            $subject_id = trim($_GET['sid']);
        }
        if (isset($_GET['cid']) && !empty($_GET['cid'])) {
            $context_id = trim($_GET['cid']);
        }
        if (isset($_GET['lid']) && !empty($_GET['lid'])) {
            $level_id = trim($_GET['lid']);
        }

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }

        $this->load->model('getRelate_model');

        //$limit = null;
        //$offset = null;
        //$orderby = null;

        $req["isActivity"] = FALSE;

        if ($subject_id >= 1000 && $subject_id < 9000) {
            $qResult = $this->getRelate_model->getRelate_content($content_id, $limit, $offset, $order, $subject_id, $level_id, $context_id);
        } else {
            
            $req['start'] = $_GET['start'] == '' ? 0 : $_GET['start'];
            $req['end'] = $_GET['end']=='' ? 10 : $_GET['end'];
            
            
            if (isset($_GET['mul_content_id']) && !empty($_GET['mul_content_id'])) {
                $req['mul_content_id'] = trim($_GET['mul_content_id']);
            }
            if (isset($_GET['mul_source_id']) && !empty($_GET['mul_source_id'])) {
                $req['mul_source_id'] = trim($_GET['mul_source_id']);
            }

            $req['category_id'] = $subject_id;
            $req["isUniverseContent"] = TRUE;
            $req["isActivity"] = TRUE;
            
            
            $sql = $this->getRelate_model->_getContent_query_lite('all', $req);
            //_pr($sql);
            $qResult = $this->db->query($sql)->result_array();
        }



        //var_dump($qResult);

        $json['header']['title'] = 'getRelate Content';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];

                if ($req["isActivity"]) {
                    $arr['d']['content_child_id'] = $v['content_id_child'];
                    $arr['d']['topic'] = $v['content_subject'];
                    $arr['d']['url'] = $v['content_url'];
                    $arr['d']['thumbnail'] = $v['thumbnail'];
                } else {
                    $arr['d']['topic'] = $v['title'];
                    $arr['d']['url'] = $v['url'];
                    $arr['d']['thumbnail'] = $v['thumbnail'];
                }

                if ($v['content_child_id'] == '') {
                    $arr['d']['viewcount'] = $v['content_view_count']; //$this->trueplook->getViewNumber($v['content_id'], 0); //$v['viewcount'];
                } else {
                    $arr['d']['viewcount'] = $v['child_view_count']; //$this->trueplook->getViewNumber($v['content_child_id'], 7); //$v['viewcount'];
                }
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                if ($v['context_id'] != null) {
                    $arr['d']['context_id'] = $v['context_id'];
                    $arr['d']['context_name'] = $v['context_name'];
                } else {
                    $arr['d']['context_id'] = "";
                    $arr['d']['context_name'] = '';
                }
                $arr['d']['sub_category_id'] = $v['sub_category_id'];
                $arr['d']['sub_category_name'] = $v['sub_category_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }

    public function cms_get($content_id = null, $limit = 50, $order = 'random') {
        $json = null;

        $catid = null;
        if (isset($_GET['catid']) && !empty($_GET['catid'])) {
            $catid = trim($_GET['catid']);
        }

        $this->load->model('getRelate_model');

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }

        //$limit = null;
        //$offset = null;
        //$orderby = null;
        $qResult = $this->getRelate_model->getRelate_cms($content_id, $limit, $offset, $order, $catid);
        //var_dump($qResult);

        $json['header']['title'] = 'getRelate CMS';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = intval($v['viewcount']); //$this->trueplook->getViewNumber($v['content_id'], 21); 
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }

    public function admissionnews_get($content_id = null, $limit = 50, $order = 'random') {
        $json = null;

        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
        }
        if (isset($_GET['f']) && !empty($_GET['f'])) {
            $f = trim($_GET['f']);
        }
        
        if (isset($_GET['limit']) && !empty($_GET['limit'])) {
            $limit = trim($_GET['limit']);
        }
        
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = trim($_GET['offset']);
        }
        
        if (isset($_GET['order']) && !empty($_GET['order'])) {
            $order = trim($_GET['order']);
        }
        
        $this->load->model('getRelate_model');

        $order = trim(strtolower($order));

        //$limit = null;
        //$offset = null;
        //$orderby = null;
        $qResult = $this->getRelate_model->getRelate_admissionNews($content_id, $limit, $offset, $order, $f, $q, $arrFilter);
        //var_dump($qResult);

        $json['header']['title'] = 'getRelate AdmissionNews';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['short_detail'] =$v['short_detail'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = intval($v['viewcount']); //$this->trueplook->getViewNumber($v['content_id'], 21); 
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }

    public function exam_get($content_id = null, $limit = 20, $order = "random") {
        $json = null;
        $subject_id = null;
        $level_id = null;
        $context_id = null;

        if (isset($_GET['sid']) && !empty($_GET['sid'])) {
            $subject_id = trim($_GET['sid']);
        }
        if (isset($_GET['cid']) && !empty($_GET['cid'])) {
            $context_id = trim($_GET['cid']);
        }
        if (isset($_GET['lid']) && !empty($_GET['lid'])) {
            $level_id = trim($_GET['lid']);
        }

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }

        $this->load->model('getRelate_model');

        //$limit = null;
        //$offset = null;
        //$orderby = null;	// FORCE not order by ; for faster query
        $qResult = $this->getRelate_model->getRelate_exam($content_id, $limit, $offset, $order, $subject_id, $level_id, $context_id);
        //var_dump($qResult);

        $json['header']['title'] = 'getRelate Examination';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }

		////tv program
    public function tvprogram_get($content_id = null, $limit = 50, $order = "random") {
        $json = null;
        $l1 = null;
        $l2 = null;

        if (isset($_GET['l1']) && !empty($_GET['l1'])) {
            $l1 = trim($_GET['l1']);
        }
        if (isset($_GET['l2']) && !empty($_GET['l2'])) {
            $l2 = trim($_GET['l2']);
        }
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
        }

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }

        $this->load->model('getRelate_model');

        //$limit = null;
        //$offset = null;
        //$orderby = null;
        $qResult = $this->getRelate_model->getRelate_tvprogram($content_id, $limit, $offset, $order, $l1, $l2,$q);
        //var_dump($qResult);

        $json['header']['title'] = 'getRelate TV Program';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                //$arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['viewcount'] = $this->trueplook->getViewCenter($v['content_id'], 'tv_program', 'media');
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }

    public function tvprogramepisode_get($content_id = null, $limit = 50, $order = "random") {
        $json = null;
        $l1 = null;
        $l2 = null;

        if (isset($_GET['l1']) && !empty($_GET['l1'])) {
            $l1 = trim($_GET['l1']);
        }
        if (isset($_GET['l2']) && !empty($_GET['l2'])) {
            $l2 = trim($_GET['l2']);
        }

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }

        $this->load->model('getRelate_model');

        //$limit = null;
        //$offset = null;
        //$orderby = null;
        $qResult = $this->getRelate_model->getRelate_tvprogram_episode($content_id, $limit, $offset, $order, $l1, $l2);
        //var_dump($qResult);

        $json['header']['title'] = 'getRelate TV Program';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                //$arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['viewcount'] = $this->trueplook->getViewCenter($v['content_child_id'], 'tv_program_episode', 'media');
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }

    public function knowledgeupsale_get($content_id = null, $limit = 50, $order = "random") {
        $json = null;
        $result1 = array();
        $result2 = array();

        $this->load->model('getRelate_model');


        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }
        $filter = array();
        $filter['contentType'] = "sourceOnly";

        $contentResult = $this->getRelate_model->getDetail_content($content_id, "mul_content");
        //var_dump($contentResult);exit();
        if (is_array($contentResult)) {
            foreach ($contentResult as $key => $v) {
                $subject_id = $v["mul_category_id"];
                $level_id = $v["mul_level_id"];
                $context_id = $v['context_id'];


                $result1 = $this->getRelate_model->getRelate_content(null, 5, null, $order, $subject_id, $level_id, $context_id, $filter);
                if (empty($result1)) {
                    $result1 = $this->getRelate_model->getRelate_content(null, 5, null, $order, null, $level_id, null, $filter);
                }
                if (empty($result1)) {
                    $result1 = $this->getRelate_model->getRelate_content(null, 5, null, $order, $subject_id, null, null, $filter);
                }

                $result2 = $this->getRelate_model->getRelate_tvprogram(null, 5, null, $order, $l1 = 1, $l2);
            }
        }
        if (empty($result1)) {
            $result1 = $this->getRelate_model->getRelate_content(null, 5, null, 'last', null, null, null);
        }
        $qResult = array_merge($result1, $result2);

        $json['header']['title'] = 'getRelate Content';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }

    public function knowledgecrosssale_get($content_id = null, $limit = 50, $order = "random") {
        $json = null;
        $result1 = array();
        $result2 = array();

        $this->load->model('getRelate_model');

        $contentResult = $this->getRelate_model->getDetail_content($content_id, "mul_content");

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }
        $filter = array();
        $filter['contentType'] = "sourceOnly";

        //var_dump($contentResult);exit();
        if (is_array($contentResult)) {
            foreach ($contentResult as $key => $v) {
                $subject_id = $v["mul_category_id"];
                $level_id = $v["mul_level_id"];
                $context_id = $v['context_id'];


                $result1 = $this->getRelate_model->getRelate_content(null, 3, null, $order, $subject_id = null, $level_id, $context_id = null, $filter);
                $result2 = $this->getRelate_model->getRelate_content(null, 2, null, $order, $subject_id, $level_id = null, $context_id = null, $filter);
                $result3 = $this->getRelate_model->getRelate_tvprogram(null, 1, null, $order, $l1 = 1, $l2);
                $result4 = $this->getRelate_model->getRelate_tvprogram(null, 2, null, $order, $l1 = 2, $l2);
                $result5 = $this->getRelate_model->getRelate_tvprogram(null, 1, null, $order, $l1 = 3, $l2);
            }
        }
        $qResult = array_merge($result1, $result2, $result3, $result4, $result5);

        $json['header']['title'] = 'getRelate Content';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }

    public function newscamp_get($content_id = null, $limit = 50, $order = 'random') {
        $json = null;

        $arrFilter = array();

        $this->load->model('getRelate_model');

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }

        //$limit = null;
        //$offset = null;
        //$orderby = null;
        $qResult = $this->getRelate_model->getRelate_ams_newscamp($content_id, $limit, $offset, $order, $arrFilter);
        //var_dump($qResult);

        $json['header']['title'] = 'getRelate NewsCamp';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['short_detail'] = $v['short_detail'];
                $arr['d']['camp_date_start'] = $v['camp_date_start'];
                $arr['d']['camp_date_end'] = $v['camp_date_end'];
                $arr['d']['register_date_start'] = $v['register_date_start'];
                $arr['d']['register_date_end'] = $v['register_date_end'];
                $arr['d']['announce_date'] = $v['announce_date'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }
	
	public function lessonplan_get($content_id = null) {
        $json = null;
        $subject_id = null;
        $level_id = null;
        $context_id = null;

        if (isset($_GET['sid']) && !empty($_GET['sid'])) {
            $subject_id = trim($_GET['sid']);
        }
        if (isset($_GET['cid']) && !empty($_GET['cid'])) {
            $context_id = trim($_GET['cid']);
        }
        if (isset($_GET['lid']) && !empty($_GET['lid'])) {
            $level_id = trim($_GET['lid']);
        }

        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
        }
        
        if (isset($_GET['limit']) && !empty($_GET['limit'])) {
            $limit = trim($_GET['limit']);
        }else{
			$limit = 20;
		}
        
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = trim($_GET['offset']);
        }
        
        if (isset($_GET['order']) && !empty($_GET['order'])) {
            $order = trim($_GET['order']);
        }
        
        $this->load->model('getRelate_model');

        $order = trim(strtolower($order));

        $arrFilter = array();
		$arrFilter['textSearch'] = $q;
		$arrFilter['mul_category_id'] = $subject_id;
		$arrFilter['mul_level_id'] = $level_id;
		$arrFilter['context_id'] = $context_id;
        $qResult = $this->getRelate_model->getRelate_lessonplan($content_id, $limit, $offset, $order, $arrFilter);
        //var_dump($qResult);		

        $json['header']['title'] = 'getRelate LessonPlan';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $json['data'] = $qResult;
        $this->response($json);
    }
	
	public function lessonplandetail_get($content_id = null) {
		
		$this->load->model('getRelate_model');
		$qResult = $this->getRelate_model->getRelate_lessonplan_detail($content_id);
		
		$json['header']['title'] = 'getRelate LessonPlan Detail';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $json['data'] = $qResult;
        //$this->tppymemcached->set($key, $json, 259200);
        $this->response($json);
	}
	
	public function cmsblog_get($content_id = null) {
        $json = null;
		
		if (isset($_GET['id']) && !empty($_GET['id'])) {
            $this_content_id = trim($_GET['id']);
        }
        if (isset($_GET['menu']) && !empty($_GET['menu'])) {
            $cat = trim($_GET['menu']);
        }
        if (isset($_GET['lt']) && !empty($_GET['lt'])) {
            $labeltag = trim($_GET['lt']);
        }

        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
        }
        
		//-- context start
		if (isset($_GET['sid']) && !empty($_GET['sid'])) {
            $sid = trim($_GET['sid']);
        }
		if (isset($_GET['lid']) && !empty($_GET['lid'])) {
            $lid = trim($_GET['lid']);
        }
		if (isset($_GET['ssid']) && !empty($_GET['ssid'])) {
            $ssid = trim($_GET['ssid']);
        }
		if (isset($_GET['cid']) && !empty($_GET['cid'])) {
            $cid = trim($_GET['cid']);
        }
		if (isset($_GET['mapcontext']) && !empty($_GET['mapcontext'])) {
            $mapcontext = trim($_GET['mapcontext']);
        }
		//-- context end
		
        if (isset($_GET['limit']) && !empty($_GET['limit'])) {
            $limit = trim($_GET['limit']);
        }else{
			$limit = 20;
		}
        
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = trim($_GET['offset']);
        }
        
        if (isset($_GET['order']) && !empty($_GET['order'])) {
            $order = trim($_GET['order']);
			$order = trim(strtolower($order));
        }
        
        $this->load->model('getRelate_model');


        $arrFilter = array();
		$arrFilter['textSearch'] = $q;
		$arrFilter['categoryList'] = $cat;
		$arrFilter['labeltagList']=$labeltag;
		$arrFilter['this_content_id'] = $this_content_id;
		if($mapcontext==1){
			$arrFilter['isMapContext'] = true;
			$arrFilter['knowledge_id'] = $ssid;
			$arrFilter['subject_id'] = $sid;
			$arrFilter['context_id'] = $cid;
			$arrFilter['level_id'] = $lid;
		}
        $qResult = $this->getRelate_model->get_cmsblogList($limit, $offset, $order, $arrFilter);
        //var_dump($qResult);		

        $json['header']['title'] = 'getRelate CMS Blog';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $json['data'] = $qResult;
        $this->response($json);
    }
	public function cmsblogdetail_get($content_id = null) {
		
		$this->load->model('getRelate_model');
		$qResult = $this->getRelate_model->getDetail_cmsblog($content_id);
		
		$json['header']['title'] = 'getRelate CMS Blog Detail';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $json['data'] = $qResult;
        $this->response($json);
	}
	
	public function cmsblogdetailrelate_get($content_id = null) {
        $json = null;
		
		$json['header']['title'] = 'getRelate CMS Blog';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelate_model');
		$qResult = $this->getRelate_model->getRelate_cmsblog_forDetail($content_id);
        $json['data'] = $qResult;
        $this->response($json);
	}
	public function cmsblogcategory_get() {
        $json = null;
		
		$cat = "";
        if (isset($_GET['menu']) && !empty($_GET['menu'])) {
            $cat = trim($_GET['menu']);
        }
		
		$json['header']['title'] = 'getRelate CMS Blog';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelate_model');
		$qResult = $this->getRelate_model->get_cmsblogCategoryInfo($cat);
        $json['data'] = $qResult;
        $this->response($json);
	}
	public function cmsblogcategorycode_get($cmsblog_id=null , $category_id = null) {
        $json = null;
		
		$json['header']['title'] = 'getRelate CMS Blog';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelate_model');
		$qResult = $this->getRelate_model->get_cmsblogCategoryCode($cmsblog_id, $category_id);
        $json['data'] = $qResult;
        $this->response($json);
	}
	public function cmsblogcountcontent_get($category_id = null,$zone_id=1) {
        $json = null;
		
		$json['header']['title'] = 'getRelate CMS Blog Count';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelate_model');
		
		$filter['menu'] = $category_id;
		$filter['zone_id'] = $zone_id;
		$qResult = $this->getRelate_model->get_cmsblogCountContent($filter);
        $json['data'] = $qResult;
        $this->response($json);
	}
	
	// วันนี้เมื่อวันวาน History & Future
	public function cmsbloghistory_get($dateNo=null,$monthNo=null,$yearNo=null) {
		$json = null; 
		$arrFilter = array();
		
		if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
			$arrFilter['q'] = $q;
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
		
		$json['header']['title'] = 'getRelate CMS Blog History';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelate_model');
		$qResult = $this->getRelate_model->get_cmsblog_history($dateNo, $monthNo, $yearNo, $arrFilter);
        //var_dump($qResult);
        $json['data'] = $qResult;
        $this->response($json);
	}
	public function cmsblogfuture_get($dateNo=null,$monthNo=null,$yearNo=null) {
		$json = null; 
		$arrFilter = array();
		
		if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
			$arrFilter['q'] = $q;
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
		
		$json['header']['title'] = 'getRelate CMS Blog Future';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelate_model');
		$qResult = $this->getRelate_model->get_cmsblog_future($dateNo, $monthNo, $yearNo, $arrFilter);
        //var_dump($qResult);
        $json['data'] = $qResult;
        $this->response($json);
	}
	
	// Blog
	public function bloggersummary_get($sumType = 'daily' ,$zone_id = 1, $category_id = null) {
        $json = null;
		
		$json['header']['title'] = 'getRelate CMS Blog Count';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelate_model');
		
		$filter['sumType'] = $sumType;
		//$filter['lastXdays'] = 50;
		$filter['zone_id'] = $zone_id;
		$filter['menu'] = $category_id;
		$qResult = $this->getRelate_model->get_blogger_summary($filter);
        $json['data'] = $qResult;
        $this->response($json);
	}
	
	
	public function test_get(){
		 // Leaderboard
		 $this->load->model('api/getRelate_model');
            $filter = array();
            $filter['sumType'] = 'weekly';
            $filter['zone_id'] = 1;
            $filter['limit'] = 4;
            $data['blogger_weekly'] = $this->getRelate_model->get_blogger_summary($filter);
			$data['content_weekly'] = $this->getRelate_model->get_content_summary($filter);
			
			
			var_dump($data);
	}
}
