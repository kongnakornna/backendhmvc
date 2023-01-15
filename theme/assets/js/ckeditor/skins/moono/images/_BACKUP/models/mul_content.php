<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mul_content extends DataMapper {

    public $prefix = "";
    public $table = "mul_content";

    public function getKnowledgeByCateId($level_id, $cate_id, $sortby = 'last', $limit = 8) {
        $this->db->set_dbprefix('');
        $CI = & get_instance();
        if ($level_id == '' || $cate_id == '') {
            return false;
        }
        $key = 'knowledge_home_' . $level_id . "_" . $cate_id . '_' . $sortby . '_' . $limit;
        if ($data = $CI->tppymemcached->get($key)) {
            return $data;
        } else {
            $this->load->model('mul_level');
            $this->load->model('mul_category');
            if ($sortby == 'last'):
                $sql = "SELECT c.mul_content_subject,msv.mul_view_value, c.mul_content_id,s.mul_title, s.mul_destination, s.mul_filename, s.mul_image_file, s.mul_thumbnail_file,s.mul_source_id,c.mul_content_id,s.mul_type_id
						FROM mul_content c
						LEFT JOIN mul_source s ON c.mul_content_id = s.mul_content_id
						LEFT JOIN mul_sum_view msv ON s.mul_source_id = msv.mul_content_id
						WHERE s.mul_type_id =  'v'
						AND c.mul_content_status =  '1'
						AND c.mul_category_id =  '" . $cate_id . "'
						AND c.mul_level_id =  '" . $level_id . "'
						AND msv.mul_view_table = 7				
						ORDER BY s.mul_source_update_datetime DESC 
						LIMIT 0 ," . $limit;
            else:
                $sql = "SELECT c.mul_content_subject,msv.mul_view_value, c.mul_content_id,s.mul_title, s.mul_destination, s.mul_filename, s.mul_image_file, s.mul_thumbnail_file,s.mul_source_id,c.mul_content_id,s.mul_type_id
						FROM mul_content c
						LEFT JOIN mul_source s ON c.mul_content_id = s.mul_content_id
						LEFT JOIN mul_sum_view msv ON s.mul_source_id = msv.mul_content_id
						WHERE s.mul_type_id =  'v'
						AND c.mul_content_status =  '1'
						AND c.mul_category_id =  '" . $cate_id . "'
						AND c.mul_level_id =  '" . $level_id . "'
						AND msv.mul_view_table = 7
						GROUP BY s.mul_source_id
						ORDER BY msv.mul_view_value DESC 
						LIMIT 0 ," . $limit;
            endif;
            $query = $this->db->query($sql);

            if ($query->num_rows() > 0) {
                // level
                $levelObj = new mul_level();
                $levelDetail = $levelObj->select('mul_level_name')->where('mul_level_id', $level_id)->get();
                $level_name = $levelDetail->mul_level_name;
                // category
                $cateObj = new mul_category();
                $cateDetail = $cateObj->select('mul_category_name')->where('mul_category_id', $cate_id)->get();
                $category_name = $cateDetail->mul_category_name;
                foreach ($query->result() as $value) {
                    if ($value->mul_filename <> '') {
                        $path = $value->mul_destination;
                        $thumb = substr($value->mul_filename, 0, -4) . '_128x96.png';
                        $arr_content['thumb'] = $CI->trueplook->image_resize(50, 50, $path, $thumb);
                    }
                    $arr_content['title'] = $value->mul_title;
                    if ($arr_content['title'] == '') {
                        $arr_content['title'] = $value->mul_content_subject;
                    }

                    $arr_content['view'] = number_format($value->mul_view_value);
                    ////
                    $arr_content['id'] = $value->mul_content_id;
                    $arr_content['level_name'] = $level_name;
                    $arr_content['category_name'] = $category_name;
                    // get link
                    $arr_content['link'] = base_url() . "new/cms_detail/knowledge/" . $value->mul_content_id . "-" . $value->mul_source_id . "/";

                    $data['data'][] = (object) $arr_content;
                }
                $CI->tppymemcached->set($key, $data);
                return $data['data'];
            } else {
                return false;
            }
        }
    }

}
