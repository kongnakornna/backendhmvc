<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Wordlink extends Public_Controller {

    public function __construct() {
        parent::__construct();
        ob_clean();
        $this->load->library('tppymemcached');
        $this->db = $this->load->database('select', TRUE);
        $this->db_edit = $this->load->database('edit', TRUE);
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

    public function index($zone_id = 0) {
        $data = array();
        if ($zone_id != 0) {
            $key = "tranding_z_" . $zone_id;
            $list = $this->tppymemcached->get($key);
            if (is_array($list)) {
                $data['list'] = $list;
                $this->load->view('wordlink', $data);
            } else {
                $this->db->select("*,tb_code.meaning");
                $this->db->from("word_link");
                $this->db->join("tb_code", "word_link.zone_id=tb_code.idx");
                $this->db->where("zone_id", $zone_id);
                $this->db->order_by("weight", "DESC");
                $resp = $this->db->get();
                $this->reverse_memcache();
                $data['list'] = $resp->result_array();
                $this->load->view('wordlink', $data);
            }
        }
    }

    public function upcount($list_id = 0) {
        if ($this->getMethod() == 'PUT') {
            if ($list_id != 0) {
                $this->db->select('count');
                $this->db->from('word_link');
                $this->db->where('id', $list_id);
                $resp = $this->db->get();
                $counter = $resp->result_array();
                $counter = $counter[0]['count'];
                $counter = $counter + 1;
                $UpdateCount = array(
                        "count" => $counter
                );
                $this->db_edit->where('id', $list_id);
                $this->db_edit->update('word_link', $UpdateCount);
                $this->reverse_memcache();
            }
        }
    }

    public function teacherview($zone_id = 14) {
        $data = array();
        if ($zone_id != 0) {
            $key = "tranding_z_" . $zone_id;
            $list = $this->tppymemcached->get($key);
            if (is_array($list)) {
                $data['list'] = $list;
                $this->load->view('wordlink-teacher', $data);
            } else {
                $this->db->select("*,tb_code.meaning");
                $this->db->from("word_link");
                $this->db->join("tb_code", "word_link.zone_id=tb_code.idx");
                $this->db->where("zone_id", $zone_id);
                $this->db->order_by("weight", "DESC");
                $resp = $this->db->get();
                $this->reverse_memcache();
                $data['list'] = $resp->result_array();
                $this->load->view('wordlink-teacher', $data);
            }
        }
    }

    public function force($zone_id = 13) {
        $this->db->select("*,tb_code.meaning");
        $this->db->from("word_link");
        $this->db->join("tb_code", "word_link.zone_id=tb_code.idx");
        $this->db->where("zone_id", $zone_id);
        $this->db->order_by("weight", "DESC");
        $resp = $this->db->get();
        $data['list'] = $resp->result_array();
        $this->load->view('wordlink', $data);
    }

    public function json($zone_id = 13) {
        header('Content-Type: application/json; charset=utf-8');

        $this->db->select("title,url,zone_id,count,weight");
        $this->db->from("word_link");
        $this->db->join("tb_code", "word_link.zone_id=tb_code.idx");
        $this->db->where("zone_id", $zone_id);
        $this->db->order_by("weight", "DESC");
        $resp = $this->db->get();
        $data['list'] = $resp->result_array();
        $this->load->view('wordlink-json', $data);
    }

    public function reverse_memcache($zone_id = 0) {
        if ($zone_id != 0) {
            $this->db->select("*,tb_code.meaning");
            $this->db->from("word_link");
            $this->db->join("tb_code", "word_link.zone_id=tb_code.idx");
            $this->db->where("zone_id", $zone_id);
            $this->db->order_by("weight", "DESC");
            $resp2 = $this->db->get();
            $key = "tranding_z_" . $zone_id;
            if ($resp2->num_rows > 0) {
                $data['list'] = $resp2->result_array();
                if (is_array($this->tppymemcached->get($key))) {
                    $this->tppymemcached->delete($key, 0);
                    $this->tppymemcached->set($key, $data['list']);
                } else {
                    $this->tppymemcached->set($key, $data['list']);
                }
            }
        }
    }

}
