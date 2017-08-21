<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Card extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('connect_db_model', 'cdm');
        $this->load->library('encrypt');
        ob_clean();
    }

    public function jsonView($zone_id = 0) {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        if ($zone_id != 0) {
            $sql1 = "SELECT group_id,pattern_id,weight,zone_id FROM card_group WHERE zone_id=$zone_id AND status=1 ORDER BY weight ASC";
            $data = $this->cdm->_query_row($sql1, "select");
            $result = array();
            foreach ($data as $key => $value) {
                $sql2 = "SELECT * FROM card_item ct WHERE ct.group_id=" . $value['group_id'];
                $result[] = array(
                        "pattern_id" => $value['pattern_id'],
                        "group_id" => $value['group_id'],
                        "zone_id" => $value['zone_id'],
                        "data" => $this->cdm->_query_row($sql2, "select")
                );
            }
//      header('Content-Type: application/json; charset=utf-8');
            $enc = $this->encrypt->encode(json_encode($result, JSON_UNESCAPED_UNICODE));
            echo $enc;
        } else {
            http_response_code(404);
            $result = array("status" => '404', "description" => "Not found data!");
            $enc = $this->encrypt->encode(json_encode($result, JSON_UNESCAPED_UNICODE));
//      header('Content-Type: application/json; charset=utf-8');            
            echo $enc;
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
