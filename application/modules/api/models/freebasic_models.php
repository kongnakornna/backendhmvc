<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Freebasic_models extends CI_Model {

    public $prefix = "cvs_";
    public $table = "shelf_detail";
    public $sornsard_id = array(40, 41, 44, 45, 46, 47, 48, 49, 51, 52, 53, 54, 101, 102, 103, 104, 115, 116, 120, 121, 129, 130, 131, 132);
    public $cach_timeout = 259200; //24*3 hr

    function getKnowledge($limit = 5) {
        $this->db = $this->load->database('select', TRUE);
        $this->db->select('*');
        $this->db->from('mul_content');
        $this->db->where('mul_content_status', 1);
        $this->db->order_by('add_date', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        $arr = array();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data;
        } else {
            return false;
        }
    }

    public function getKnowledgeDepartment($start = 1, $limit = 20) {
        $this->db = $this->load->database('select', TRUE);
        $this->db->select('*');
        $this->db->from('mul_content');
        $this->db->where('mul_content_status', 1);
        $this->db->order_by('add_date', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        $arr = array();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data;
        } else {
            return false;
        }
    }

    public function getSubject() {
//        SELECT * FROM mul_category WHERE mul_category_id in(1000,2000,3000,4000,5000,6000,7000,8000,100,500,300,700,9000)
        $this->db = $this->load->database('select', TRUE);
        $this->db->select('*');
        $this->db->from('mul_category');
        $this->db->where('mul_category_id', 'in(1000,2000,3000,4000,5000,6000,7000,8000,100,500,300,700,9000)');
        $query = $this->db->get();
        return $query;
    }

    public function getKnowledgeSearchCount($mul_level_id = 0, $mul_category_id = 0) {
        if ($mul_level_id != null) {
            $this->db = $this->load->database('select', TRUE);
            $this->db->select('count(*) as knowledge_search_count');
            $this->db->from('mul_content');
            $this->db->where('mul_content_status', 1);
            if ($mul_level_id != 0) {
                $this->db->where('mul_content.mul_level_id', $mul_level_id);
            }
            if ($mul_category_id != 0) {
                $this->db->where('mul_category.mul_parent_id', $mul_category_id);
            }
            $this->db->join('mul_category', 'mul_content.mul_category_id=mul_category.mul_category_id', 'inner');
            $this->db->order_by('add_date', 'desc');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $data = $query->result_array();
                return $data;
            } else {
                return false;
            }
        }
    }

    public function getKnowledgeSearch($limit = 0, $mul_level_id = 0, $mul_category_id = 0, $end = 0) {
        $this->db = $this->load->database('select', TRUE);
        $this->db->select('mul_category.*,mul_content.*');
        $this->db->from('mul_content');
        $this->db->where('mul_content_status', 1);
        if ($mul_level_id != 0) {
            $this->db->where('mul_content.mul_level_id', $mul_level_id);
        }
        if ($mul_category_id != 0) {
            $this->db->where('mul_category.mul_parent_id', $mul_category_id);
        }
        $this->db->join('mul_category', 'mul_content.mul_category_id=mul_category.mul_category_id', 'inner');
        $this->db->order_by('add_date', 'desc');

        if ($limit != 0) {
            if ($end != 0) {
                $this->db->limit($limit, $end);
            } else {
                $this->db->limit($limit);
            }
        }
        $query = $this->db->get();
        $arr = array();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data;
        } else {
            return false;
        }
    }

    function getKnowledgeCountIndex() {
        $this->db = $this->load->database('select', TRUE);
        $this->db->select('count(mul_content.mul_content_id) as knowledge_count');
        $this->db->from('mul_content');
        $this->db->where('mul_content_status', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getKnowledgeFixed($limit = 5, $mul_content_id = 0) {
        $this->db = $this->load->database('select', TRUE);
        $this->db->select('mul_category.*,mul_content.*');
        $this->db->from('mul_content');
        $this->db->where('mul_content_status', 1);
        $this->db->where('mul_content_id', $mul_content_id);
        $this->db->join('mul_category', 'mul_content.mul_category_id=mul_category.mul_category_id', 'inner');
        $this->db->order_by('add_date', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        $arr = array();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data;
        } else {
            return false;
        }
    }

    function getKnowledgeFixedSource($mul_content_id = 0) {
        $this->db->select('mul_source.*');
        $this->db->from('mul_source');
        $this->db->where('mul_source.mul_content_id', $mul_content_id);
        $query2 = $this->db->get();
        if ($query2->num_rows() > 0) {
            $data = $query2->result_array();
            return $data;
        } else {
            return false;
        }
    }

    function getCmsByCatID($category_id = "", $limit = 20, $end = 0, $order_by = 'cms.cms_id DESC') {//$mul_view_table = 21,
        if (!$category_id) {
            return false;
        } else {
            $this->db = $this->load->database('select', TRUE);
            $this->db->select('cms.*,cms_file.file_name,cms_file.file_path');
            $this->db->from('cms');
            $this->db->join('cms_file', 'cms_file.cms_id = cms.cms_id ', 'left');

            if (is_array($category_id)) {
                $this->db->where_in('cms.cms_category_id', $category_id);
            } else {
                $this->db->where('cms.cms_category_id', $category_id);
            }

            $this->db->where('cms.record_status', 1);
            $this->db->order_by($order_by);
            $this->db->group_by('cms.cms_id');
            if ($limit != 0) {
                if ($end != 0) {
                    $this->db->limit($limit, $end);
                } else {
                    $this->db->limit($limit);
                }
            }
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $data = $query->result();
                return $data;
            } else {
                return false;
            }
        }
    }

    function getCmsByCatIDCount($category_id = "") {//$mul_view_table = 21,
        if (!$category_id) {
            return false;
        } else {
            $this->db = $this->load->database('select', TRUE);
            $this->db->select('count(cms.cms_id) as cms_count');
            $this->db->from('cms');
            $this->db->join('cms_file', 'cms_file.cms_id = cms.cms_id ', 'left');
            if (is_array($category_id)) {
                $this->db->where_in('cms.cms_category_id', $category_id);
            } else {
                $this->db->where('cms.cms_category_id', $category_id);
            }
            $this->db->where('cms.record_status', 1);
            $this->db->order_by('cms.cms_id DESC');
            $this->db->group_by('cms.cms_id');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $data = $query->num_rows();
                return $data;
            } else {
                return false;
            }
        }
    }

    function getCmsByCatIDFixed($category_id = "", $limit = 9, $cms_id = 0, $order_by = 'cms.cms_id DESC') {
        if (!$category_id) {
            return false;
        } else {
            $this->db = $this->load->database('select', TRUE);
            $this->db->select('cms.*,cms_file.file_name,cms_file.file_path');
            $this->db->from('cms');
            $this->db->join('cms_file', 'cms_file.cms_id = cms.cms_id ', 'left');

            if (is_array($category_id)) {
                $this->db->where_in('cms.cms_category_id', $category_id);
            } else {
                $this->db->where('cms.cms_category_id', $category_id);
            }

            $this->db->where('cms.record_status', 1);
            $this->db->where('cms.cms_id', $cms_id);
            $this->db->order_by($order_by);
            $this->db->group_by('cms.cms_id');
            if ($limit)
                $this->db->limit($limit);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $data = $query->result();
                return $data;
            } else {
                return false;
            }
        }
    }

    public function getAdmission($limit = 9, $end = 0) {
        $this->db = $this->load->database('select', TRUE);
        $this->db->select('sonsart_news.*,sonsart_university.*,sonsart_news.add_date as add_date');
        $this->db->from('sonsart_news');
        $this->db->join('sonsart_university', 'sonsart_university.id=sonsart_news.university_id', 'left');
        $this->db->where('sonsart_news.record_status', 1);
        $this->db->order_by('sonsart_news.news_id', 'DESC');
        if ($end != 0) {
            $this->db->limit($limit,$end);
        } else {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        }
    }

    public function getAdmissionCount() {
        $this->db = $this->load->database('select', TRUE);
        $this->db->select('sonsart_news.*,sonsart_university.*,sonsart_news.add_date as add_date');
        $this->db->from('sonsart_news');
        $this->db->join('sonsart_university', 'sonsart_university.id=sonsart_news.university_id', 'left');
        $this->db->where('sonsart_news.record_status', 1);
        $this->db->order_by('sonsart_news.news_id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->num_rows();
            return $data;
        }
    }

    public function getAdmissionFixed($limit = 9, $sonsart_id = 0) {
        $this->db = $this->load->database('select', TRUE);
        $this->db->select('sonsart_news.*,sonsart_university.*,sonsart_news.add_date as add_date');
        $this->db->from('sonsart_news');
        $this->db->join('sonsart_university', 'sonsart_university.id=sonsart_news.university_id', 'left');
        $this->db->where('sonsart_news.record_status', 1);
        $this->db->where('sonsart_news.news_id', $sonsart_id);
        $this->db->order_by('sonsart_news.news_id', 'DESC');
        if ($limit)
            $this->db->limit($limit);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        }
    }

}
