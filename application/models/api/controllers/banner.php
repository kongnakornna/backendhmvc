<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
error_reporting(1);

class Banner extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('select', TRUE);
        $this->db_edit = $this->load->database('edit', TRUE);
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();
    }

    public function zone($zone_id = 0, $limit = 9) {
        if ($this->getMethod() == 'GET') {
            if ($zone_id != 0) {
                $this->db->select('banner_id,banner_topic,banner_detail,banner_image,banner_link1,banner_linkmsg1')->from('banner_ad ban')->where('banner_type_id', $zone_id)->where('record_status', 1)->limit($limit)->order_by('banner_id', 'DESC');
                $resp = $this->db->get();
                if ($resp->num_rows() > 0) {
                    $data = $resp->result_array();
                    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
                } else {
                    http_response_code(404);
                    echo json_encode(array('status' => 404, 'desc' => 'not found data!'), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
                }
            } else {
                http_response_code(500);
                echo json_encode(array('status' => 500, 'desc' => 'not found data!'), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
            }
        } else {
            http_response_code(500);
            echo json_encode(array('status' => 500, 'desc' => 'Error to connect api : your ip add to blacklist'), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        }
    }

    function bannerV3($banner_id = 0, $limit = 9) {

        if ($this->getMethod() == 'GET') {
            $CI = & get_instance();
            $this->db = $this->load->database('select', TRUE);
            $this->db->set_dbprefix('');
            $key = "home_banner_header";
            $data = $CI->tppymemcached->get($key);
            if ($data) {
                return $data;
            } else {
                $data = array();
                $query = $this->db
                        ->select('*')
                        ->from('cvs_banner')
                        ->where('banner_categories_id =', $banner_id)
                        ->where('status', 1)
                        ->order_by('sort', 'desc')->limit($limit)
                        ->get();
                if ($query->num_rows() > 0) {
                    $result = $query->result();
                    $arr = array();
                    foreach ($result as $key => $value) {
                        $fileImg = explode('/', $value->image);
                        $arr[$key]['title'] = $value->title;
                        $arr[$key]['thumbnail'] = get_file_url($value->image);
                        $arr[$key]['link'] = $value->link;
                        $arr[$key]['file_path'] = 'http://static.trueplookpanya.com/tppy/'.$fileImg[0] . '/' . $fileImg[1].'/';
                        $arr[$key]['file_image'] = $fileImg[2];
                    }
                    $data = $arr;
                    $CI->tppymemcached->set($key, $data, $this->cach_timeout);
                }
                //http_response_code(500);
                echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
            }
        }
    }

    function bannerV2($groupid = 0, $limit = 10) {
        if ($this->getMethod() == 'GET') {
            $CI = & get_instance();
            $this->db = $this->load->database('select', TRUE);
            $this->db->set_dbprefix('');
            $key = "home_banner_header_v2";
            $data = $CI->tppymemcached->get($key);
            if ($data) {
                return $data;
            } else {
                $data = array();

                $this->load->model('Banner_model');
                $result = $this->Banner_model->bannerV2($groupid, $limit);
                if ($result) {
                    $data = array();
                    $data['response']['status'] = true;
                    $data['response']['message'] = 'success';
                    $data['response']['code'] = 200;

                    $arr = array();
                    foreach ($result as $key => $value) {
                        $arr[$key]['title'] = $value->title;
                        $arr[$key]['thumbnail'] = $value->thumbnail;
                        $arr[$key]['link'] = $value->link;
                    }
                    $data["data"] = $arr;
                    $CI->tppymemcached->set($key, $data, $this->cach_timeout);
                } else {
                    $data = array();
                    $data['response']['status'] = false;
                    $data['response']['message'] = 'no data';
                    $data['response']['code'] = 400;
                }
                //http_response_code(500);
                echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
            }
        }
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
