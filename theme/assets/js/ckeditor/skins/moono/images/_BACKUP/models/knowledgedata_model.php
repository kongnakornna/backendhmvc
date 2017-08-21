<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Knowledgedata_model extends CI_Model {
    
     function getWebboard($limit = 14) {
        $webboard = array(9 => 'ห้องนักเรียน', 10 => "ห้องพักครู", 13 => "ห้องแนะแนว", 12 => "ห้องนั่งเล่น");
        $CI = & get_instance();
        $this->db = $this->load->database('select', TRUE);
        $this->db->set_dbprefix('');
        $key = "home_webboard_" . $limit;
        $CI->tppymemcached->delete($key);
        $data = $CI->tppymemcached->get($key);
        if ($data) {
//            exit;
            return $data;
        } else {
            $loop = 0;
            foreach ($webboard as $id => $value) {
                $data[$id]['title'] = $value;
                $data[$id]['webboard'] = $this->getWebboardByCateID($id, $limit); //$CI->webboard->getWebboardByCateID($id, 5);
                $loop++;
            }
            $CI->tppymemcached->set($key, $data, $this->cach_timeout);
        }
        return $data;
    }
    
    function getWebboardsearch($limit = 5) {
        $webboard = array(9 => 'ห้องนักเรียน');
        $CI = & get_instance();
        $this->db = $this->load->database('select', TRUE);
        $this->db->set_dbprefix('');
        $key = "home_webboard_" . $limit;
        $CI->tppymemcached->delete($key);
        $data = $CI->tppymemcached->get($key);
        if ($data) {
//            exit;
            return $data;
        } else {
            $loop = 0;
            foreach ($webboard as $id => $value) {
                $data[$id]['title'] = $value;
                $data[$id]['webboard'] = $this->getWebboardByCateID($id, $limit); //$CI->webboard->getWebboardByCateID($id, 5);
                $loop++;
            }
            $CI->tppymemcached->set($key, $data, $this->cach_timeout);
        }
        return $data;
    }
    
    private function getWebboardByCateID($cate_id, $limit = 4) {
        $CI = & get_instance();
        if ($cate_id == '') {
            return false;
        }
        $key = 'webboard_home_' . $cate_id . "_" . $limit;
        $CI->tppymemcached->delete($key);
        $this->load->model('api/webboard_model');
        $this->load->library('trueplook');
        
        if ($data = $CI->tppymemcached->get($key)) {
            return $data;
        } else {
            $query = $this->db
                    ->select('*')
                    ->from('webboard_post')
//                    ->join('webboard_category', 'webboard_category.wb_category_id=webboard_post.wb_category_id', 'left')
                    ->where('webboard_post.wb_category_id', $cate_id)
                    ->where('webboard_post.wb_status', 1)
                    ->order_by('webboard_post.wb_post_id', 'DESC')
                    ->limit($limit)
                    ->get();
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $value) {
                    $arr_content['title'] = $value->wb_subject;
                    $arr_content['id'] = $value->wb_post_id;
                    $arr_content['display_name'] = $this->trueplook->get_display_name($value->member_id, 'name');
//                    $arr_content['cate_name'] = $value->wb_category_name;
                    $arr_content['link'] = base_url() . 'true/webboard_detail.php?postid=' . $value->wb_post_id;
//                    $arr_content['view'] = $CI->trueplook->getViewNumber($value->wb_post_id, 15);
//                    $replyObj = new webboard_reply();
                    //$arr_content['nums_reply'] = $replyObj->where('reply_status', 1)->where('wb_post_id', $value->wb_post_id)->count();
                    $arr_content['nums_reply'] = $this->webboard_model->getCountReply($value->wb_post_id);
                    //$arr_content['nums_reply'] = $this->webboard_model->getCountReply($value->wb_post_id);
                    $data[] = (object) $arr_content;
                    $CI->tppymemcached->set($key, $data);
                }
                return $data;
            }
        }
    }
    
    function getInfographic() {
        //$CI = & get_instance();
        //$this->db = $this->load->database('select', TRUE);
        //$key = "home_infographic";
        //$data = $CI->tppymemcached->get($key);
        //if ($data) {
        //    return $data;
        //} else {
            $this->db->select('*');
            $this->db->from('cvs_infographic');
            $this->db->where('infographic_cate_id !=', '0');
            $this->db->where('status', '1');
            $this->db->order_by('sort', 'desc');
            $this->db->order_by('cdate', 'desc');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $data = $query->row_array();
                $arr['title'] = $data['title'];
                $arr['thumbnail'] = get_file_url($data['image_full']);
                $arr['link'] = site_url('infographic/detail/'.$data['id'].'/'.$data['title']);
                $arr['image'] = get_file_url($data['image_full']);
                //$CI->tppymemcached->set($key, $arr, $this->cach_timeout);
                return $arr;
            }
        //}
    }
}
