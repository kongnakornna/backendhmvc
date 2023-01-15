<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
header('Content-type: text/html; charset=utf-8');

class V2 extends TPPY_Controller {

    public function __construct() {
        parent::__construct();
        if (!is_internal()) {
            show_404();
        }
    }

    function check_IE() {
        $version = 0;
        if (preg_match('/(?i)msie [5-8]/', $_SERVER['HTTP_USER_AGENT'])) {
            $version = 'IE<=8';
        } else {
            $version = 'IE>8';
        }
        return $version;
    }

    public function Detect() {
        $this->load->library('Mobile_Detect');
        $detect = new Mobile_Detect();
        if ($detect->isMobile() && !$detect->isTablet()) {
            $isDetect = "mobile";
        } else if ($detect->isTablet()) {
            $isDetect = "tablet";
        } else {
            $isDetect = NULL;
        }
        return $isDetect;
    }

    public function detail($content_id = null, $source_id = '') {

        $this->load->helper('text');

        $data['IE_version'] = $this->check_IE();
        $data['isDetect'] = $this->Detect();

        $data['top_banner'] = json_decode(file_get_contents(base_url() . 'api/banner/bannerV3/11'));


        $rs = [];
        if (!empty($source_id)) {
            //echo 'มีวิดีโอ';

            $vdo = json_decode(file_get_contents(base_url() . 'api/knowledgebase/vdocontent/' . $content_id . '/' . $source_id));

            if (empty($vdo->other)) {
                show_404();
            }


            $data['info'] = $vdo->other[0];
            $context = $this->getNameContext($vdo->other[0]->context_id);
            $data['info']->context_name = $context->context_name;
            $data['info']->context_level = $context->context_level;
            $data['info']->cat_super_name = $this->getNameSubject($data['info']->cat_super_id);



            if ($data['isDetect'] == '') {
                $data['info']->vdo_width = 600;
                $data['info']->vdo_height = 350;
            } else if ($data['isDetect'] == 'mobile') {
                $data['info']->vdo_width = 350;
                $data['info']->vdo_height = 250;
            } else if ($data['isDetect'] == 'tablet') {
                $data['info']->vdo_width = 600;
                $data['info']->vdo_height = 350;
            }
            $this->template->meta_og_image = $data['info']->thumbnail;
        } else {
            //echo 'ไม่มีวิดีโอ';
            
            if(!empty($content_id)){
            $rs = json_decode(file_get_contents(base_url() . 'api/knowledgebase/othercontent/' . $content_id));

            if (empty($rs->other)) {
                show_404();
            }

            $data['info'] = $rs->other[0];
            $context = $this->getNameContext($rs->other[0]->context_id);
            $data['info']->context_name = $context->context_name;
            $data['info']->context_level = $context->context_level;
            $data['info']->cat_super_name = $this->getNameSubject($data['info']->cat_super_id);
            }else{
                show_404();
            }
        }

//        if(){
//            
//        }


        $meta_title = $data['info']->content_subject . " : คลังความรู้ ทรูปลูกปัญญา";

        $meta_desc = $data['info']->content_subject . " "
                . "ระดับชั้น " . $data['info']->cat_level_name . " "
                . "วิชา " . $data['info']->cat_super_name . " "
                . "สาระ " . $data['info']->cat_name . " "
                . "ตัวชี้วัด " . $data['info']->context_level . " " . $data['info']->context_name . " "
                . " " . $data['info']->content_stage . " "
                . " " . $data['info']->cat_level_name . " "
                . " ติว แนวข้อสอบ education สาระ บทความ คลังความรู้ ทรูปลูกปัญญา";

        $this->template->meta_title = $meta_title;
        $this->template->meta_description = $meta_desc;
        $this->template->meta_keywords = $this->add_tag($data['info']->keyword) . ','
                . 'นานา สาระ,ความรู้ ,สาระน่ารู้ ,ความรู้รอบตัว,'
                . "ระดับชั้น " . $data['info']->cat_level_name . ","
                . "" . $data['info']->cat_super_name . ","
                . "" . $data['info']->cat_name . ","
                . "" . $data['info']->context_level . "," . $data['info']->context_name;


        $this->template->meta_og_title = $meta_title;
        $this->template->meta_site_name = 'ทรูปลูกปัญญาดอทคอม';
        $this->template->meta_og_description = $meta_desc;

        $this->template->meta_twitter_card = 'summary';
        $this->template->meta_twitter_title = $meta_title;
        $this->template->meta_twitter_description = $meta_desc;


//        echo '<pre>';
//        print_r($data['info']);
//        echo '</pre>';

        $this->template->view('data_detail', $data);
    }

    private function add_tag($param) {
        $cms_tag = explode(' ', $param);
        $cms_tag2 = implode(',', $cms_tag);
        return $cms_tag2;
    }

    private function getNameContext($context_id = 0) {
        $data['context'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getcontext'));
        $text = new stdClass();

        foreach ($data['context'] as $key => $value) {
            if ($value->knowledge_context_id == $context_id) {
                $textL = $value->knowledge_context_name;
            }
        }

        $info = split(") ", $textL);
        $text->context_name = $info[1];
        $text->context_level = $info[0] . ")";
        return $text;
    }

    private function getNameSubject($subject_id = 0) {
        $data['subject'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getcategory'));
        $text = "";

        foreach ($data['subject'] as $key => $value) {
            if ($value->mul_category_id == $subject_id) {
                $text = $value->mul_category_name;
            }
        }

        return $text;
    }

    private function default_select($data, $category, $level, $conetxt) {
        $rs = [];
        if ($data['subject_id'] == 0 || $data['subject_id'] == "") {
            $rs['subject']['id'] = 0;
            $rs['subject']['name'] = "ค้นหาจากรายวิชา";
            $rs['subject']['nav_name'] = "ทุกรายวิชา";
        } else {
            $rs['subject']['id'] = $data['subject_id'];
            foreach ($category as $key => $value) {
                if ($rs['subject']['id'] == $value->mul_category_id) {
                    $rs['subject']['name'] = $value->mul_category_name;
                    $rs['subject']['nav_name'] = $value->mul_category_name;
                }
            }
        }

        if ($data['level_id'] == 0 || $data['level_id'] == "") {
            $rs['level']['id'] = 0;
            $rs['level']['name'] = "ค้นหาจากระดับชั้น";
            $rs['level']['nav_name'] = "ทุกระดับชั้น";
        } else {
            $rs['level']['id'] = $data['level_id'];
            foreach ($level as $key => $value) {
                if ($rs['level']['id'] == $value->mul_level_id) {
                    $rs['level']['name'] = $value->mul_level_name;
                    $rs['level']['nav_name'] = $value->mul_level_name;
                }
            }
        }

        if ($data['knowledge_id'] == 0 || $data['knowledge_id'] == "") {
            $rs['knowledge']['id'] = 0;
            $rs['knowledge']['name'] = "ค้นหาจากสาระการเรียนรู้ย่อย";
            $rs['knowledge']['nav_name'] = "ทุกสาระการเรียนรู้";
        } else {
            $rs['knowledge']['id'] = $data['knowledge_id'];
            foreach ($conetxt as $key => $value) {
                if ($rs['knowledge']['id'] == $value->knowledge_context_id) {
                    $rs['knowledge']['name'] = $value->knowledge_context_name;
                    $rs['knowledge']['nav_name'] = $value->knowledge_context_name;
                }
            }
        }
        $rs['text_search'] = $data['q'];

        if ($data['q'] == "") {
            $rs['nav_search'] = $rs['subject']['nav_name'] . " > " . $rs['level']['nav_name'] . " > " . $rs['knowledge']['nav_name'];
        } else {
            $rs['nav_search'] = $rs['subject']['nav_name'] . " > " . $rs['level']['nav_name'] . " > " . $rs['knowledge']['nav_name'] . " > " . $rs['text_search'];
        }
        return $rs;
    }

    public function ajax_exam($content_id) {
        $input = $this->input->get();
        $content_id = 0;
        $data['exam_relate'] = json_decoder((file_get_contents(base_url() . "api/getrelate/exam/" . $content_id . "/9/order=last?sid=" . $input['sid'] . "&lid=" . $input['lid'] . "&cid=" . $input['cid'])));

        $this->load->view('load_exam', $data);
    }

    public function ajax_upsale_crosssale($content_id) {
        $input = $this->input->get();

        $data['isDetect'] = $this->Detect();
        $data['upsale_relate'] = json_decoder((file_get_contents(base_url() . "api/getrelate/knowledgeupsale/" . $content_id . '/12/rand')));
        $data['crosssale_relate'] = json_decoder((file_get_contents(base_url() . "api/getrelate/knowledgecrosssale/" . $content_id . "/12/rand")));

//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';
        $this->load->view('load_up_cross', $data);
    }

    public function ajax_vdo($content_id = 0, $source_id = '') {

        //$data['IE_version'] = $this->check_IE();

        $rs = [];
        if (!empty($source_id)) {
            // echo 'มีวิดีโอ';

            $arr = json_decode(file_get_contents(base_url() . 'api/knowledgebase/vdocontent/' . $content_id));

            
            //echo intval($source_id);
            //$data['right_relate']['vdo'] = object_to_array($arr);

            foreach ($arr->other as $key => $value) {
                if ($value->content_id_child != intval($source_id)) {
                    $data['right_relate']['vdo'][] = object_to_array($value);
                }
            }


            //echo count($data['right_relate']['vdo']);

            if (count($data['right_relate']['vdo']) < 5) {
                
                $max = 5;
                $box_old = count($data['right_relate']['vdo']);
                $loop = $max - $box_old;

                $sid = $this->input->get('sid');
                $lid = $this->input->get('lid');
                $cid = $this->input->get('cid');


                $box2 = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/0/' . $loop . '/?sid=' . $sid . "&lid=" . $lid. "&cid=" . $cid));
                
                foreach ($box2['data'] as $key => $v) {
                    if($v->content_id==$content_id){
                        unset($box2['data'][$key]);
                    }
                }
                //$cal_1['data'] = array_merge($data['right_relate']['vdo'], $box2['data']);
                $data['right_relate']['content'] = $box2;
                
                //_pr($data['right_relate']);
                
                //$data['right_relate']['vdo'] = $this->get_Length_Relate($data['right_relate']['vdo'], $content_id, 5);
            }
            //$this->template->meta_og_image = $data['info']->thumbnail;
        } else {
            //  echo 'ไม่มีวิดีโอ';
            //$data['right_relate']['content'];
            $box1 = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/' . $content_id . '/5'));


            //$box1['data'][0]['ccccc'] = 55555555555555555555;
            //_pr($box1['data']);

            if (count($box1['data']) < 5) {
                $max = 5;
                $box_old = count($box1['data']);
                $loop = $max - $box_old;

                $sid = $this->input->get('sid');
                $lid = $this->input->get('lid');


                $box2 = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/0/' . $loop . '/?sid=' . $sid . "&lid=" . $lid));
                
                foreach ($box2['data'] as $key => $v) {
                    if($v->content_id==$content_id){
                        unset($box2['data'][$key]);
                    }
                }
                // echo 'befor add';
                // _pr($box2);
                //echo 'หลัง ADD';
                $cal_1['data'] = array_merge($box1['data'], $box2['data']);
                // _pr($cal_1['data']);
                //echo 'รอบสอง ADD';
                if (count($cal_1['data']) < 5) {

                    $max = 5;
                    $box_old = count($cal_1['data']);
                    $loop = $max - $box_old;
                    $box3 = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/0/' . $loop . '/?sid=' . $sid));
                    
                    foreach ($box3['data'] as $key => $v) {
                    if($v->content_id==$content_id){
                        unset($box3['data'][$key]);
                    }
                }
                    
                    $box_end['data'] = array_merge($cal_1['data'], $box3['data']);

                    //end
                    $data['right_relate']['content'] = $box_end;
                } else {
                    $data['right_relate']['content'] = $cal_1;
                }
            } else {
                $data['right_relate']['content'] = $box1;
            }
        }
        $this->load->view('load_vdo_relate', $data);
    }

    private function get_Length_Relate($data, $content_id, $size) {
        $sum_data = count($data);

        $arr = [];
        $data_leng = $size;
        $loop = $data_leng - $sum_data;

//        echo 'sum:'.$sum_data.'<br>';
//        echo 'loop:'.$loop.'<br>';

        $data_loop = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/' . $content_id . '/' . $loop))['data'];

        $addArray = [];
        foreach ($data_loop as $key => $value) {
            //$data[] = $value;
            $addArray[$key]['content_id_child'] = "";
            $addArray[$key]['content_id'] = $value['content_id'];
            $addArray[$key]['thumbnail'] = $value['thumbnail'];
            $addArray[$key]['content_subject'] = $value['topic'];
            $addArray[$key]['content_url'] = $value['url'];
        }

        $arr = array_merge($data, $addArray);

        return $arr;
    }

}
