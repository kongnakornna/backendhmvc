<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
error_reporting(1);

class Campaigns2 extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('connect_db_model', 'cdm');
        $this->load->model('campaign_model', 'camp');
        $this->load->library('encrypt');
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();
    }

    public function index($campaign_id = 0) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        if ($campaign_id != 0) {
            $res = $this->cdm->_query_row('SELECT * FROM campaign_main WHERE status=1 AND campaign_id=' . $campaign_id . ' ORDER BY campaign_id DESC', 'select');
            foreach ($res as $key => $value) {
                $res[$key]['campaign_group'] = $this->cdm->_query_row('SELECT * '
                        . 'FROM '
                        . 'campaign_group '
                        . 'WHERE status=1 '
                        . 'AND campaign_id=' . $value['campaign_id'] . ' '
                        . 'ORDER BY weight DESC,add_datetime ASC,campaign_group_id ASC', 'select');
                foreach ($res[$key]['campaign_group'] as $key2 => $value2) {
                    $res[$key]['campaign_group'][$key2]['campaign_sub_group'] = $this->cdm->_query_row(''
                            . 'SELECT * '
                            . 'FROM '
                            . 'campaign_sub_group '
                            . 'WHERE '
                            . 'status=1 AND '
                            . 'campaign_group_id=' . $value2['campaign_group_id'] . ' '
                            . 'ORDER BY weight DESC,add_datetime ASC,campaign_sub_group_id ASC', 'select');
                    foreach ($res[$key]['campaign_group'][$key2]['campaign_sub_group'] as $key3 => $value3) {
                        $res[$key]['campaign_group'][$key2]['campaign_sub_group'][$key3]['campaign_item'] = $this->cdm->_query_row('SELECT * '
                                . 'FROM '
                                . 'campaign_item '
                                . 'WHERE '
                                . 'campaign_sub_group_id=' . $value3['campaign_sub_group_id'] . ' '
                                . 'ORDER BY position_id DESC,timestamp ASC,campaign_item_id ASC', 'select');
                        foreach ($res[$key]['campaign_group'][$key2]['campaign_sub_group'][$key3]['campaign_item'] as $key4 => $value4) {
                            if ($value4['getsub'] != 1) {
                                $res[$key]['campaign_group'][$key2]['campaign_sub_group'][$key3]['campaign_item'][$key4]['data_url'][] = json_decode($this->camp->check_content($value4['url_link']))[0];
                            } else {
                                $res[$key]['campaign_group'][$key2]['campaign_sub_group'][$key3]['campaign_item'][$key4]['data_url'] = json_decode($this->camp->check_content($value4['url_link']));
                            }
                        }
                    }
                }
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        } else {
            $res = $this->cdm->_query_row('SELECT * FROM campaign_main WHERE status=1 ORDER BY campaign_id DESC', 'select');
            foreach ($res as $key => $value) {
                $res[$key]['campaign_group'] = $this->cdm->_query_row('SELECT * '
                        . 'FROM '
                        . 'campaign_group '
                        . 'WHERE status=1 '
                        . 'AND campaign_id=' . $value['campaign_id'] . ' '
                        . 'ORDER BY weight DESC,add_datetime ASC', 'select');
                foreach ($res[$key]['campaign_group'] as $key2 => $value2) {
                    $res[$key]['campaign_group'][$key2]['campaign_sub_group'] = $this->cdm->_query_row(''
                            . 'SELECT * '
                            . 'FROM '
                            . 'campaign_sub_group '
                            . 'WHERE '
                            . 'status=1 AND '
                            . 'campaign_group_id=' . $value2['campaign_group_id'] . ' '
                            . 'ORDER BY weight DESC,add_datetime ASC', 'select');
                    foreach ($res[$key]['campaign_group'][$key2]['campaign_sub_group'] as $key3 => $value3) {
                        $res[$key]['campaign_group'][$key2]['campaign_sub_group'][$key3]['campaign_item'] = $this->cdm->_query_row('SELECT * '
                                . 'FROM '
                                . 'campaign_item '
                                . 'WHERE '
                                . 'campaign_sub_group_id=' . $value3['campaign_sub_group_id'] . ' '
                                . 'ORDER BY position_id DESC,timestamp ASC', 'select');
                        foreach ($res[$key]['campaign_group'][$key2]['campaign_sub_group'][$key3]['campaign_item'] as $key4 => $value4) {
                            if ($value4['getsub'] != 1) {
                                $res[$key]['campaign_group'][$key2]['campaign_sub_group'][$key3]['campaign_item'][$key4]['data_url'][] = json_decode($this->camp->check_content($value4['url_link']))[0];
                            } else {
                                $res[$key]['campaign_group'][$key2]['campaign_sub_group'][$key3]['campaign_item'][$key4]['data_url'] = json_decode($this->camp->check_content($value4['url_link']));
                            }
                        }
                    }
                }
            }
			//var_dump($res);die();
			///echo '<pre> $res=>'; print_r($res); echo '</pre>';  die();
			
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        }
    }

    public function listThemeAll() {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        $sql = "SELECT * FROM campaign_main WHERE status=1 ORDER BY campaign_id DESC";
        $res = $this->cdm->_query_row($sql, 'select');
        echo json_encode($res);
    }

    public function listThemeById($campaign_id = 0) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        if ($campaign_id != 0) {
            $sql = "SELECT * FROM campaign_main WHERE status=1 AND campaign_id=$campaign_id ORDER BY campaign_id DESC";
            $res = $this->cdm->_query_row($sql, 'select');
            echo json_encode($res);
        } else {
            $res = array(
                "status" => 404
            );
            echo json_encode($res);
        }
    }

    public function listGroupOfThemeById($campaign_id = 0) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        if ($campaign_id != 0) {
            $res = $this->cdm->_query_row('SELECT * '
                    . 'FROM '
                    . 'campaign_group '
                    . 'WHERE status=1 '
                    . 'AND campaign_id=' . $campaign_id . ' '
                    . 'ORDER BY campaign_group_id ASC,inde DESC', 'select');
            echo json_encode($res);
        } else {
            $res = array(
                "status" => 404
            );
            echo json_encode($res);
        }
    }

    public function listSubGroupOfGroupById($group_id = 0) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        if ($group_id != 0) {
            $res = $this->cdm->_query_row(''
                    . 'SELECT * '
                    . 'FROM '
                    . 'campaign_sub_group '
                    . 'WHERE '
                    . 'status=1 AND '
                    . 'campaign_group_id=' . $group_id . ' '
                    . 'ORDER BY campaign_sub_group_id ASC,weight DESC', 'select');
            echo json_encode($res);
        } else {
            $res = array(
                "status" => 404
            );
            echo json_encode($res);
        }
    }

    public function listItemOfsubGroupById($sub_group_id = 0) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        if ($sub_group_id != 0) {
            $res = $this->cdm->_query_row('SELECT * '
                    . 'FROM '
                    . 'campaign_item '
                    . 'WHERE '
                    . 'campaign_sub_group_id=' . $sub_group_id . ' '
                    . 'ORDER BY campaign_item_id ASC,position_id DESC', 'select');
            echo json_encode($res);
        } else {
            $res = array(
                "status" => 404
            );
            echo json_encode($res);
        }
    }

    public function checkUrl() {
        if ($this->getMethod() != 'POST') {
            http_response_code(500);
            exit();
        }
        if (isset($_POST)) {
            $url = $_POST['url'];
            $res = $this->camp->check_content($url);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($res);
        } else {
            $res = array(
                "status" => 404
            );
            echo json_encode($res);
        }
    }

    public function getcontent($url = "http://www.trueplookpanya.dev/new/cms_detail/knowledge/25/") {
        $d = 'http://www.trueplookpanya.dev/new/cms_detail/knowledge/25';
        $res = $this->camp->get_content($url);
        echo $res;
    }

    public function getcontentid($id = '', $mul_id = '00') {
        //$url = 'http://www.trueplookpanya.dev/new/cms_detail/knowledge/' . $id;
        $res = $this->getrelate($id, $mul_id);
        print_r($res);
        
    }
    private function getrelate($id = '', $mul_id = '00') {
        //$url = 'http://www.trueplookpanya.com/knowledge/v2/detail/' . $id;
        $url = 'http://www.trueplookpanya.com/new/cms_detail/knowledge/' . $id;
        //$res = $this->camp->check_content_knowledge($url);
        $res = $this->camp->check_content($url);
        $data = json_decode($res);
        $arr_new = [];
        foreach ($data as $key => $value) {
            if($value->mul_source_id == $mul_id){
                unset($data[$key]);
            }else{
                $arr_new[]=$value;
            }
        }
        
        return json_encode($arr_new,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE);
    }

    function getMethod() {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'PUT':
                    return 'PUT';
                    break;
                case 'DELETE':
                    return 'DELETE';
                    break;
                case 'POST':
                    return 'POST';
                    break;
                case 'GET':
                    return 'GET';
                    break;
                default :
                    return false;
                    break;
            }
        }
    }

}
