<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
error_reporting(1);

class Freebasic extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('connect_db_model', 'cdm');
        $this->load->model('campaign_model', 'camp');
        $this->load->model('freebasic_models', 'fbm');
        $this->load->library('encrypt');
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();
    }

    public function getLevelShort($id) {
        $_level = array(
                '01' => 'อ.1',
                '02' => 'อ.2',
                '03' => 'อ.3',
                '11' => 'ป.1',
                '12' => 'ป.2',
                '13' => 'ป.3',
                '21' => 'ป.4',
                '22' => 'ป.5',
                '23' => 'ป.6',
                '31' => 'ม.1',
                '32' => 'ม.2',
                '33' => 'ม.3',
                '41' => 'ม.4',
                '42' => 'ม.5',
                '43' => 'ม.6'
        );
        return $_level[$id];
    }

    public function knowledge($limit = 5, $mul_content_id = 0, $start = 0) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        if ($mul_content_id != 0) {
            $res = $this->fbm->getKnowledgeFixed($limit, $mul_content_id);
            $res[0]['mul_source_data'] = $this->fbm->getKnowledgeFixedSource($mul_content_id);
            echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        } else {
            if ($start != 0) {
                $res = $this->fbm->getKnowledgeDepartment($start, $limit);
                echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
            } else {
                $res = $this->fbm->getKnowledge($limit);
                echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
            }
        }
    }

    public function knowledgeCountIndex() {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        $res = $this->fbm->getKnowledgeCountIndex();
        echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    }

    public function knowledgeSearch($limit = 20, $level = 0, $subject = 0, $end = 0) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        if ($end == 0) {
            $res = $this->fbm->getKnowledgeSearch($limit, $level, $subject);
        } else {
            $res = $this->fbm->getKnowledgeSearch($limit, $level, $subject, ($end * 10));
        }
        $res['counter'] = $this->fbm->getKnowledgeSearchCount($level, $subject);
        echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    }

    public function knowledgeHi($limit = 5) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        $res = $this->fbm->getHighlightKnowledge($limit);
        echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    }

    public function direct($limit = 5, $cms_id = 0, $end = 0) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        if ($cms_id != 0) {
            //ข่าว admissions
            $res['admission'] = $this->fbm->getAdmissionFixed($limit, $cms_id);
            echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        } else {
            //ข่าว admissions
            $res['admission'] = $this->fbm->getAdmission($limit, $end);
            $res['counter'] = $this->fbm->getAdmissionCount();
            echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        }
    }

    public function guidance($limit = 5, $guidance = 0, $cms_id = 0, $end = 0) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        if ($guidance != 0) {
            switch ($guidance) {
                case 1:
                    //พี่แนะน้อง
                    if ($cms_id != 0) {
                        $res['guidance_penanong'] = $this->fbm->getCmsByCatIDFixed(92, $limit, $cms_id);
                    } else {
                        $res['guidance_penanong'] = $this->fbm->getCmsByCatID(92, $limit, $end);
                        $res['counter'] = $this->fbm->getCmsByCatIDCount(92);
                    }
                    echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
                    break;
                case 2:
                    //คนต้นแบบ
                    if ($cms_id != 0) {
                        $res['guidance_idol'] = $this->fbm->getCmsByCatIDFixed(93, $limit, $cms_id);
                    } else {
                        $res['guidance_idol'] = $this->fbm->getCmsByCatID(93, $limit, $end);
                        $res['counter'] = $this->fbm->getCmsByCatIDCount(93);
                    }
                    echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
                    break;
                case 3:
                    //บทความน่าอ่าน
                    if ($cms_id != 0) {
                        $res['guidance_article'] = $this->fbm->getCmsByCatIDFixed(94, $limit, $cms_id);
                    } else {
                        $res['guidance_article'] = $this->fbm->getCmsByCatID(94, $limit, $end);
                        $res['counter'] = $this->fbm->getCmsByCatIDCount(94);
                    }
                    echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
                    break;

                default:
                    break;
            }
        } else {
            //พี่แนะน้อง
            $res['guidance_penanong'] = $this->fbm->getCmsByCatID(92, $limit);
            //คนต้นแบบ
            $res['guidance_idol'] = $this->fbm->getCmsByCatID(93, $limit);
            //บทความน่าอ่าน
            $res['guidance_article'] = $this->fbm->getCmsByCatID(94, $limit);

            echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        }
    }

    public function dhama($limit = 5, $cms_id = 0, $end = 0) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        //บทความธรรมะ
        $res = [];
        if ($cms_id != 0) {
            $res['dhama_article'] = $this->fbm->getCmsByCatIDFixed(74, $limit, $cms_id);
        } else {
            $res['dhama_article'] = $this->fbm->getCmsByCatID(74, $limit, $end);
            $res['counter'] = $this->fbm->getCmsByCatIDCount(74);
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
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
