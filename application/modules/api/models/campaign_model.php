<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Campaign_model extends DataMapper {

    public $prefix = "cvs_";
    public $table = "campaigns";

    function get_code($code) {
        return preg_replace("/[^0-9]/", "", $code);
    }

    function get_subsection($id) {

        $this->db->select('*')
                ->from('cvs_campaign_sub_section')
                ->where('main_section_id', $id)
                ->order_by('sort ASC');
        return $this->db->get()->result();
    }

    function get_sidebar($id) {

        $this->db->select('*')
                ->from('cvs_campaign_sidebar')
                ->where('campaign_id', $id)
                ->order_by('sort ASC');
        return $this->db->get()->result();
    }

    function get_sectiondetail_main($id) {

        $this->db->select('*')
                ->from('cvs_campaign_section_detail')
                ->where('main_section_id', $id)
                ->where('sub_section_id', '0')
                ->order_by('sort ASC');
        return $this->db->get()->result();
    }

    function get_sectiondetail($id) {

        $this->db->select('*')
                ->from('cvs_campaign_section_detail')
                ->where('sub_section_id', $id)
                ->order_by('sort ASC');
        return $this->db->get()->result();
    }

    function get_main($id) {
        $this->db->select('*')
                ->from('cvs_campaign_main_section')
                ->where(array('campaign_id' => $id, 'status' => 1))
                ->order_by('sort ASC');
        return $this->db->get()->result();
    }

    function get_content($link) {
        $CI = & get_instance();
        $CI->load->library('trueplook');
        $CI->load->library('truelife_api');
        $CI->load->library('convert_vdo_v3');
        $link = rtrim($link, "/");
        if (strpos($link, 'cms_detail/knowledge')):
            if (strpos($link, '-')):
                $array1 = explode("-", $link);
                $id = end(explode("/", $array1[0]));
                $id = $this->get_code($id);
                $source_id = $array1[1];
            else:
                $ids = explode("/", $link);
                $id = $ids[6];
                $source_id = '';
            endif;
            if ($source_id <> ''):
                $result = $this->db->query("select ms.mul_title,ms.mul_thumbnail_file,ms.mul_source_id,mc.mul_content_subject,mc.mul_content_id,mc.member_id,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id,mc.mul_content_text,mc.mul_tag from mul_content mc INNER JOIN mul_source ms on mc.mul_content_id=ms.mul_content_id where mc.mul_content_id='" . $id . "' and mc.mul_content_status=1 and ms.mul_source_id =  '" . $source_id . "' order by ms.weight desc,ms.mul_source_id asc limit 0,1 ")->result();
            else:
                $result = $this->db->query("select ms.mul_title,ms.mul_thumbnail_file,ms.mul_source_id,mc.mul_content_subject,mc.mul_content_id,mc.member_id,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id,mc.mul_content_text,mc.mul_tag from mul_content mc INNER JOIN mul_source ms on mc.mul_content_id=ms.mul_content_id where mc.mul_content_id='" . $id . "' and mc.mul_content_status=1 order by ms.weight desc,ms.mul_source_id asc limit 0,1 ")->result();
            endif;
            $rs = $result[0];
            $subject = ($rs->mul_title <> '') ? $rs->mul_title : $rs->mul_content_subject;
            $data['subject'] = $CI->trueplook->limitText($subject, 60);
            $data['link'] = $link;
            //print "select mul_destination,mul_thumbnail_file, mul_image_file FROM mul_source WHERE mul_content_id = '".$id."' AND mul_source_id = '".$ids[7]."' ";
            $sFile = $this->db->query("select mul_destination,mul_thumbnail_file, mul_image_file FROM mul_source WHERE mul_content_id = '" . $id . "' AND mul_source_id = '" . $source_id . "' order by weight desc,mul_source_id asc")->result();
            $file = $sFile[0];
            // $path = $rs->mul_destination;
            // if ($rs->mul_thumbnail_file <> '') {
            //     $thumb = $rs->mul_thumbnail_file;
            // } else {
            //     $thumb = substr($rs->mul_filename, 0, -4) . '_128x96.png';
            // }
            if ($rs->mul_filename <> '') {
                $path = $rs->mul_destination;
                if ($rs->mul_thumbnail_file <> '') {
                    $thumb = $rs->mul_thumbnail_file;
                } else {
                    $thumb = substr($rs->mul_filename, 0, -4) . '_320x240.png';
                }
            }
            //$data['thumb'] = $CI->trueplook->image_resize(128, 96, $path, $thumb);
            if ($rs->mul_filename <> '') {
                if ($rs->mul_type_id == 'a') {
                    $thumb_error = base_url() . 'new/assets/images/icon/snd126x95px.jpg';
                }
                if ($rs->mul_type_id == 'f') {
                    $thumb_error = base_url() . 'new/assets/images/icon/swf126x95px.jpg';
                }
                if ($rs->mul_type_id == 'd') {
                    $thumb_error = base_url() . 'new/assets/images/icon/doc126x95px.jpg';
                }
            } else {
                $thumb_error = base_url() . 'new/assets/images/icon/doc126x95px.jpg';
            }
            //
            if ($rs->mul_filename <> '' and $rs->mul_type_id == 'v') {
                //////
                $data['thumb'] = $CI->trueplook->image_resize(128, 96, $path, $thumb);

                //$data['thumb'] = 'http://www.trueplookpanya.com'.$CI->trueplook->get_media_path('image') . $path . '/' . $thumb;
            } else {
                $data['thumb'] = $thumb_error;
            }
            if ($file->mul_thumbnail_file <> '' || $file->mul_image_file <> ''):
                $thb = ($file->mul_image_file <> '') ? $file->mul_image_file : $file->mul_thumbnail_file;
                $data['thumb'] = 'http://www.trueplookpanya.com/data/product/media/' . $file->mul_destination . '/' . $thb;
            endif;
            $data['short_detail'] = mb_substr(strip_tags($rs->mul_content_text), 50);
            $date = explode(' ', $rs->add_date);
            $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
        elseif (strpos($link, 'cms_detail/general_knowledge') || strpos($link, 'cms_detail/guidance') || strpos($link, 'cms_detail/news') || strpos($link, 'entertainment/')):
            if (strpos($link, 'cms_detail/general_knowledge')):
                $array1 = explode("general_knowledge", $link);
                $id = end(explode("/", $array1[1]));
            elseif (strpos($link, 'cms_detail/news')):
                $array1 = explode("cms_detail/news", $link);
                $id = explode("/", $array1[1]);
                $id = $id[1];
            elseif (strpos($link, 'entertainment/')):
                $id = end(explode("entertainment/", $link));
            else:
                $array1 = explode("cms_detail/guidance", $link);
                $id = explode("/", $array1[1]);
                $id = $id[1];
            endif;
            $id = $this->get_code($id);
            $result = $this->db->query("select files.file_size,files.cms_file_id,files.file_type,cms.credit_by,cms.cms_category_id,cms.cms_detail_short as cms_detail,cms.cms_id,cms.cms_subject as cms_subject,cms.member_id,cms.add_date,cms.thumb_path as cms_file_path,files.file_name,cms.image_filename_m as cms_file_name,cms.image_filename_s as cms_file_name_s,cms.thumb_path  ,cms.image_filename_l from cms left join cms_file files on cms.cms_id=files.cms_id where cms.cms_id='" . $id . "'  ")->result();
            $rs = $result[0];
            $data['subject'] = $CI->trueplook->limitText($rs->cms_subject, 50);
            $data['link'] = $link;
            $path = $rs->cms_file_path;
            if ($rs->cms_file_name_s <> '') { //
                $thumb = $rs->cms_file_name_s;
            } elseif ($rs->cms_file_name <> '') {
                $thumb = $rs->cms_file_name;
            } else {
                $thumb = substr($rs->file_name, 0, -4) . '_128x96.png';
            }
            if ($rs->image_filename_l <> '' || $rs->cms_file_name_s <> ''):
                $thb = ($rs->image_filename_l <> '') ? $rs->image_filename_l : $rs->cms_file_name_s;
                $data['thumb'] = 'http://www.trueplookpanya.com/data/product/media/' . $rs->cms_file_path . '/' . $thb;
            else:
                $data['thumb'] = 'http://www.trueplookpanya.com' . $CI->trueplook->get_media_path('image') . $path . '/' . $thumb;
            endif;

            $data['short_detail'] = $rs->cms_detail;
            $date = explode(' ', $rs->add_date);
            $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
        //print_r($data);
        //$data['views'] = $CI->trueplook->getViewNumber($id, 21); 
        elseif (strpos($link, 'clipded') || strpos($link, 'knowledge_youtube')):
            if (strpos($link, 'clipded')):
                $id = end(explode("www.trueplookpanya.com/new/clipded/index/", $link));
                $id = $this->get_code($id);
            else:
                $id = end(explode("www.trueplookpanya.com/true/knowledge_youtube.php?youtube_id=", $link));
                $id = $this->get_code($id);
            endif;
            $result = $this->db->query("select * from youtube where youtube_id = '" . $id . "' and youtube_status=1 ")->result();
            $rs = $result[0];
            $data['subject'] = $CI->trueplook->limitText($rs->youtube_subject, 50);
            $data['link'] = $link;
            $data['thumb'] = 'http://i3.ytimg.com/vi/' . $rs->watch_code . '/default.jpg';
            $data['short_detail'] = $rs->youtube_subject;
            $date = explode(' ', $rs->add_date);
            $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
        elseif (strpos($link, 'ethic_detail')):
            $id = end(explode("cms_id=", $link));
            $id = $this->get_code($id);
            $result = $this->db->query("select files.file_size,files.cms_file_id,files.file_type,cms.credit_by,cms.cms_category_id,cms.cms_detail_long as cms_detail,cms.cms_id,cms.cms_subject as cms_subject,cms.member_id,cms.add_date,cms.thumb_path as cms_file_path,files.file_name,cms.image_filename_m as cms_file_name,cms.image_filename_s as cms_file_name_s,cms.thumb_path from cms left join cms_file files on cms.cms_id=files.cms_id where cms.cms_id='" . $id . "'  ")->result();
            $rs = $result[0];
            $data['subject'] = $CI->trueplook->limitText($rs->cms_subject, 50);
            $data['link'] = $link;
            $path = $rs->cms_file_path;
            if ($rs->cms_file_name <> '') {
                $thumb = $rs->cms_file_name;
            } else {
                $thumb = substr($rs->file_name, 0, -4) . '_128x96.png';
            }
            $data['thumb'] = 'http://www.trueplookpanya.com' . $CI->trueplook->get_media_path('image') . $path . '/' . $thumb;
            $data['short_detail'] = $rs->cms_detail;
            $date = explode(' ', $rs->add_date);
            $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]); elseif (strpos($link, 'new/tv_program_detai')):
            $array1 = explode("new/tv_program_detail", $link);
            $id = explode("/", $array1[1]);
            $id = $this->get_code($id);
            $result = $this->db->query("select tv.*,vdo.*,program.tv_name from tv_program_episode tv left join tv_program_episode_vdo vdo on tv.tv_episode_id=vdo.tv_episode_id left join tv_program program on program.tv_id=tv.tv_id  where tv.tv_episode_id='" . $id[2] . "' and tv.record_status=1 ")->result();
            $rs = $result[0];

            $data['post_name'] = $CI->trueplook->get_display_name($rs->update_by, 'link');
            $data['view'] = $CI->trueplook->getViewCenter($content[1], 'tv_program_episode', 'media');
            $data['subject'] = $rs->tv_name . ' : ' . $rs->tv_episode_name;
            $data['subject_all'] = $rs->tv_name . ' : ' . $rs->tv_episode_name;
            if ($rs->tv_vdo_filename <> '') {
                $path = $rs->tv_vdo_path;
                $thumb = substr($rs->tv_vdo_filename, 0, -4) . '_128x96.png';
            }
            if ($rs->tv_vdo_filename <> '') {
                $data['thumb'] = $CI->trueplook->image_resize(320, 240, $path, $thumb);
            } else {
                if ($rs->upload_type == 'Youtube') {
                    $data['thumb'] = 'http://i3.ytimg.com/vi/' . $rs->youtube_code . '/mqdefault.jpg';
                } else {
                    $data['thumb'] = $thumb_error;
                }
            }
            $data['link'] = $link;
            $date = explode(' ', $rs->add_date);
            $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]); elseif (strpos($link, 'tv_program_detai')):
            $array1 = explode("tv_program_detail", $link);
            $id = explode("/", $array1[1]);
            $id = $this->get_code($id);
            $result = $this->db->query("select tv.*,vdo.*,program.tv_name from tv_program_episode tv left join tv_program_episode_vdo vdo on tv.tv_episode_id=vdo.tv_episode_id left join tv_program program on program.tv_id=tv.tv_id  where tv.tv_episode_id='" . $id[2] . "' and tv.record_status=1 ")->result();
            $rs = $result[0];

            $data['post_name'] = $CI->trueplook->get_display_name($rs->update_by, 'link');
            $data['view'] = $CI->trueplook->getViewCenter($content[1], 'tv_program_episode', 'media');
            $data['subject'] = $rs->tv_name . ' : ' . $rs->tv_episode_name;
            $data['subject_all'] = $rs->tv_name . ' : ' . $rs->tv_episode_name;
            if ($rs->tv_vdo_filename <> '') {
                $path = $rs->tv_vdo_path;
                $thumb = substr($rs->tv_vdo_filename, 0, -4) . '_128x96.png';
            }
            if ($rs->tv_vdo_filename <> '') {
                $data['thumb'] = $CI->trueplook->image_resize(320, 240, $path, $thumb);
            } else {
                if ($rs->upload_type == 'Youtube') {
                    $data['thumb'] = 'http://i3.ytimg.com/vi/' . $rs->youtube_code . '/mqdefault.jpg';
                } else {
                    $data['thumb'] = $thumb_error;
                }
            }
            $data['link'] = $link;
            $date = explode(' ', $rs->add_date);
            $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]); elseif (strpos($link, 'entertainment_api')):
            $array1 = explode("entertainment_api", $link);
            $id = explode("/", $array1[1]);
            $id = $id[1];
            $id = $this->get_code($id);
            $truelife_XML = 'http://api.platform.truelife.com/cms/content/' . $id;
            $file = file_get_contents($truelife_XML);
            $v = $CI->convert_vdo_v3->xml2array($file);

            $data['subject'] = $v['data']['title'];
            $data['subject_all'] = $v['data']['title'];
            $data['thumb'] = $v['data']['thumbnail'];
            $data['description'] = $v['data']['description'];
            $data['rs_vdo'] = $v['data']['trailer']['item']['files']['item'];
            $data['api_view'] = $v['data']['views'];
            $data['api_type'] = $v['data']['lifestyle'];
            $date = explode(' ', $v['data']['created_date']);
            $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
            $data['link'] = $link;

        elseif (strpos($link, 'sonsart_news_detail')):
            $array1 = explode("sonsart_news_detail", $link);
            $id = explode("/", $array1[1]);
            $id = $id[1];
            $id = $this->get_code($id);
            $result = $this->db->query("select * from sonsart_news  where news_id='" . $id . "'  and record_status=1 ")->result();
            $rs = $result[0];

            $data['post_name'] = $CI->trueplook->get_display_name($rs->update_by, 'link');
            $data['view'] = $CI->trueplook->getViewCenter($content[1], 'tv_program_episode', 'media');
            $data['subject'] = $rs->news_title;
            $data['subject_all'] = $rs->news_short_detail;
            $data['thumb'] = $CI->trueplook->image_resize(320, 240, $rs->news_path, $rs->news_banner_file);
            $data['path'] = $rs->news_path;
            $data['banner'] = $rs->news_banner_file;
            $data['link'] = $link;
            $date = explode(' ', $rs->add_date);
            $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);

        elseif (strpos($link, 'examination_display.php') || strpos($link, 'doexam')):
            if (strpos($link, 'doexam')) {
                $id = end(explode("/", $link));
                $id = $this->get_code($id);
            } else {
                $id = end(explode("=", $link));
                $id = $this->get_code($id);
            }
            $result = $this->db->query("select * from cvs_course_examination where id='" . $id . "' and exam_status='yes'")->result();
            $rs = $result[0];
            //$data['post_name'] = $CI->trueplook->get_display_name($rs->member_id, 'link');
            //$data['view'] = $CI->trueplook->getViewNumber($rs['exam_id'], 2);
            $data['icon_subject'] = "http://static.trueplookpanya.com/trueplookpanya/media/home/icon_subject/" . $rs->mul_root_id . ".png";
            $data['subject'] = $CI->trueplook->limitText($rs->exam_name, 50);
            $data['subject_all'] = $rs->exam_name;
            $data['level'] = $CI->trueplook->get_icon_level($rs->mul_level_id);
            $date = explode(' ', $rs->exam_add_date);
            $data['exam_code'] = $rs->exam_code;
			$data['exam_add_date'] = $rs->exam_add_date;
            $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
            $data['link'] = 'http://www.trueplookpanya.com/examination/doexam/' . $id;
            $selNumQuestion = $this->db->query(" select count(exam_id) as num_question from cvs_course_question where exam_id = '" . $id . "' ")->result();
            $rs2 = $selNumQuestion[0];
            $data['num_q'] = $rs2->num_question;
        endif;
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    function check_content($link) {
        
		
		$CI = & get_instance();
        $CI->load->library('trueplook');
        $CI->load->library('truelife_api');
        $CI->load->library('convert_vdo_v3');
        $link = rtrim($link, "/");
        if (strpos($link, 'cms_detail/knowledge')):
            if (strpos($link, '-')):
                $array1 = explode("-", $link);
                $id = end(explode("/", $array1[0]));
                $id = $this->get_code($id);
                $source_id = $array1[1];
            else:
                $ids = explode("/", $link);
                $id = $ids[6];
                $source_id = '';
            endif;
            if ($source_id <> ''):
                $result = $this->db->query("select ms.mul_source_id,ms.mul_title,ms.mul_thumbnail_file,ms.mul_source_id,mc.mul_content_subject,mc.mul_content_id,mc.member_id,mc.mul_content_text,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id,mc.mul_tag from mul_content mc INNER JOIN mul_source ms on mc.mul_content_id=ms.mul_content_id where mc.mul_content_id='" . $id . "' and mc.mul_content_status=1 and ms.mul_source_id =  '" . $source_id . "' order by ms.weight desc,ms.mul_source_id asc")->result();
            else:
                $result = $this->db->query("select ms.mul_source_id,ms.mul_title,ms.mul_thumbnail_file,ms.mul_source_id,mc.mul_content_subject,mc.mul_content_id,mc.member_id,mc.mul_content_text,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id,mc.mul_tag from mul_content mc INNER JOIN mul_source ms on mc.mul_content_id=ms.mul_content_id where mc.mul_content_id='" . $id . "' and mc.mul_content_status=1 order by ms.weight desc,ms.mul_source_id asc")->result();
            endif;
            $rs = $result;
            foreach ($rs as $key => $value) {
                $subject = ($value->mul_title <> '') ? $value->mul_title : $value->mul_content_subject;
                $datas['subject'] = $CI->trueplook->limitText($subject, 60);
                if ($source_id <> '') {
                    $datas['link'] = $link;
                } else {
                    $datas['link'] = $link . '-' . ($value->mul_source_id);
                }
                $sFile = $this->db->query("select mul_destination,mul_thumbnail_file, mul_image_file FROM mul_source WHERE mul_content_id = '" . $id . "' AND mul_source_id = '" . $source_id . "' order by weight desc,mul_source_id asc")->result();
                $file = $sFile[0];

                if ($value->mul_filename <> '') {
                    $path = $value->mul_destination;
                    if ($value->mul_thumbnail_file <> '') {
                        $thumb = $value->mul_thumbnail_file;
                    } else {
                        $thumb = substr($value->mul_filename, 0, -4) . '_320x240.png';
                    }
                }
                //$data['thumb'] = $CI->trueplook->image_resize(128, 96, $path, $thumb);
                if ($value->mul_filename <> '') {
                    if ($value->mul_type_id == 'a') {
                        $thumb_error = base_url() . 'new/assets/images/icon/snd126x95px.jpg';
                    }
                    if ($value->mul_type_id == 'f') {
                        $thumb_error = base_url() . 'new/assets/images/icon/swf126x95px.jpg';
                    }
                    if ($value->mul_type_id == 'd') {
                        $thumb_error = base_url() . 'new/assets/images/icon/doc126x95px.jpg';
                    }
                } else {
                    $thumb_error = base_url() . 'new/assets/images/icon/doc126x95px.jpg';
                }
                //
                if ($value->mul_filename <> '' and $value->mul_type_id == 'v') {



                    $datas['thumb'] = $CI->trueplook->image_resize(320, 240, $path, $thumb);
                    //$datas['thumb'] = 'http://www.trueplookpanya.com'.$CI->trueplook->get_media_path('image') . $path . '/' . $thumb;
                } else {
                    $datas['thumb'] = $thumb_error;
                }
                if ($file->mul_thumbnail_file <> '' || $file->mul_image_file <> ''):
                    $thb = ($file->mul_image_file <> '') ? $file->mul_image_file : $file->mul_thumbnail_file;
                    $datas['thumb'] = 'http://www.trueplookpanya.com/data/product/media/' . $file->mul_destination . '/' . $thb;
                endif;
                $datas['short_detail'] = mb_substr(strip_tags($rs->mul_content_text), 50);
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            }

        elseif (strpos($link, 'knowledge/detail')|| strpos($link, 'education/learning/detail') || strpos($link, 'learning/detail')):
            if (strpos($link, '-')):
                
               if (strpos($link, '-')):
                $array1 = explode("-", $link);
                $id = end(explode("/", $array1[0]));
                $id = $this->get_code($id);
                $source_id = $array1[1];
                endif;
                
            else:
               if(strpos($link, 'education/learning/detail')){ 
                $ids = explode("/", $link);
                if (count($ids) < 7) {
                    $id = $ids[6];
                    $source_id = '';
                } else {
                    $id = $ids[6];
                    $source_id = $ids[7];
                }
               }else{
                $ids = explode("/", $link);
                if (count($ids) < 6) {
                    $id = $ids[5];
                    $source_id = '';
                } else {
                    $id = $ids[5];
                    $source_id = $ids[6];
                }   
               }
            endif;
            if ($source_id != ''):
                $result = $this->db->query("select ms.mul_content_id,ms.mul_title,ms.mul_thumbnail_file,ms.mul_source_id,mc.mul_content_subject,mc.mul_content_id,mc.member_id,mc.mul_content_text,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id,mc.mul_tag from mul_content mc INNER JOIN mul_source ms on mc.mul_content_id=ms.mul_content_id where mc.mul_content_id='" . $id . "' and mc.mul_content_status=1 and ms.mul_source_id =  '" . $source_id . "' order by ms.weight desc,ms.mul_source_id asc")->result();
            else:
                $result = $this->db->query("select ms.mul_content_id,ms.mul_title,ms.mul_thumbnail_file,ms.mul_source_id,mc.mul_content_subject,mc.mul_content_id,mc.member_id,mc.mul_content_text,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id,mc.mul_tag from mul_content mc INNER JOIN mul_source ms on mc.mul_content_id=ms.mul_content_id where mc.mul_content_id='" . $id . "' and mc.mul_content_status=1 order by ms.weight desc,ms.mul_source_id asc")->result();
            endif;
            $rs = $result;
            foreach ($rs as $key => $value) {
                // $subject = ($value->mul_title <> '') ? 
                        // ($value->mul_title != $value->mul_content_subject) ?
                        // $value->mul_content_subject." : ".$value->mul_title : $value->mul_title : $value->mul_content_subject;
				$subject = ($value->mul_title <> '') ? $value->mul_title : $value->mul_content_subject;
                $datas['subject'] = $CI->trueplook->limitText($subject, 60);
                if ($source_id <> '') {
                    $datas['link'] = $link;
                } else {
                    $datas['link'] = $link . '-' . ($value->mul_source_id);
                }
                $sFile = $this->db->query("select mul_destination,mul_thumbnail_file, mul_image_file FROM mul_source WHERE mul_content_id = '" . $id . "' AND mul_source_id = '" . $source_id . "' order by weight desc,mul_source_id asc")->result();
                $file = $sFile[0];

                if ($value->mul_filename <> '') {
                    $path = $value->mul_destination;
                    if ($value->mul_thumbnail_file <> '') {
                        $thumb = $value->mul_thumbnail_file;
                    } else {
                        $thumb = substr($value->mul_filename, 0, -4) . '_320x240.png';
                    }
                }
                //$data['thumb'] = $CI->trueplook->image_resize(128, 96, $path, $thumb);
                if ($value->mul_filename <> '') {
                    if ($value->mul_type_id == 'a') {
                        $thumb_error = base_url() . 'new/assets/images/icon/snd126x95px.jpg';
                    }
                    if ($value->mul_type_id == 'f') {
                        $thumb_error = base_url() . 'new/assets/images/icon/swf126x95px.jpg';
                    }
                    if ($value->mul_type_id == 'd') {
                        $thumb_error = base_url() . 'new/assets/images/icon/doc126x95px.jpg';
                    }
                } else {
                    $thumb_error = base_url() . 'new/assets/images/icon/doc126x95px.jpg';
                }
                if ($value->mul_filename <> '' and $value->mul_type_id == 'v') {
                    $datas['thumb'] = $CI->trueplook->image_resize(320, 240, $path, $thumb);
                    //$datas['thumb'] = 'http://www.trueplookpanya.com'.$CI->trueplook->get_media_path('image') . $path . '/' . $thumb;
                } else {
                    $datas['thumb'] = $thumb_error;
                }
                if ($file->mul_thumbnail_file <> '' || $file->mul_image_file <> ''):
                    $thb = ($file->mul_image_file <> '') ? $file->mul_image_file : $file->mul_thumbnail_file;
                    //$datas['thumb'] = 'http://www.trueplookpanya.com/data/product/media/' . $file->mul_destination . '/' . $thb;
					$datas['thumb'] = $CI->trueplook->image_resize(520, 440, $file->mul_destination, $thb);
                endif;
                $datas['short_detail'] = mb_substr(strip_tags($rs->mul_content_text), 50);
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            }

        elseif (strpos($link, 'cms_detail/general_knowledge') || strpos($link, 'cms_detail/guidance') || strpos($link, 'cms_detail/news') || strpos($link, 'entertainment/') || strpos($link, 'admissions/article/detail') ):
            if (strpos($link, 'cms_detail/general_knowledge')):
                $array1 = explode("general_knowledge", $link);
                $id = end(explode("/", $array1[1]));
            elseif (strpos($link, 'cms_detail/news')):
                $array1 = explode("cms_detail/news", $link);
                $id = explode("/", $array1[1]);
                $id = $id[1];
            elseif (strpos($link, 'entertainment/')):
                $id = end(explode("entertainment/", $link));
            elseif (strpos($link, 'admissions/article/detail')):
                $id = end(explode("admissions/article/detail", $link));
            else:
                $array1 = explode("cms_detail/guidance", $link);
                $id = explode("/", $array1[1]);
                $id = $id[1];
            endif;
            $id = $this->get_code($id);
			if($id>49999){
				// new cmsblog
				$result = $this->db->query("select cms.idx as content_id,cms.title as topic,cms.description_short as short_detail,CONCAT('".$this->config->item('static_url')."', COALESCE(cms.thumb_path, 'no-image.png')) thumbnail,cms.start_date as addDateTime from cmsblog_detail cms where record_status=1 and cms.idx=" . $id)->result();
				$rs = $result;
				foreach ($rs as $key => $value) {
					$datas['subject']=$CI->trueplook->limitText($value->topic,50);
					$datas['link']="http://www.trueplookpanya.com/admissions/article/detail/$id";
					$datas['thumb']=$value->thumbnail;
					$datas['short_detail']=$value->short_detail;
					$datas['add_date']=$CI->trueplook->data_format('small', 'th',$value->addDateTime);
					$data[]=$datas;
				}
			}else{
				// old cms
				$result = $this->db->query("select files.file_size,files.cms_file_id,files.file_type,cms.credit_by,cms.cms_category_id,cms.cms_detail_short as cms_detail,cms.cms_id,cms.cms_subject as cms_subject,cms.member_id,cms.add_date,cms.thumb_path as cms_file_path,files.file_name,cms.image_filename_m as cms_file_name,cms.image_filename_s as cms_file_name_s,cms.thumb_path  ,cms.image_filename_l from cms left join cms_file files on cms.cms_id=files.cms_id where cms.cms_id='" . $id . "'  ")->result();
				$rs = $result;
				foreach ($rs as $key => $value) {
					$datas['subject'] = $CI->trueplook->limitText($value->cms_subject, 50);
					$datas['link'] = $link;
					$path = $value->cms_file_path;
					if ($value->cms_file_name_s <> '') { //
						$thumb = $value->cms_file_name_s;
					} elseif ($value->cms_file_name <> '') {
						$thumb = $value->cms_file_name;
					} else {
						$thumb = substr($value->file_name, 0, -4) . '_128x96.png';
					}
					if ($value->image_filename_l <> '' || $value->cms_file_name_s <> ''):
						$thb = ($value->image_filename_l <> '') ? $value->image_filename_l : $value->cms_file_name_s;
						$datas['thumb'] = 'http://www.trueplookpanya.com/data/product/media/' . $value->cms_file_path . '/' . $thb;
					else:
						$datas['thumb'] = 'http://www.trueplookpanya.com' . $CI->trueplook->get_media_path('image') . $path . '/' . $thumb;
					endif;
					$datas['short_detail'] = $value->cms_detail;
					$date = explode(' ', $value->add_date);
					$datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
					$data[] = $datas;
				}
			}
            
		elseif (strpos($link, 'knowledge/content/')):
			$array1 = explode("knowledge/content/", $link);
			$array2 = explode("/", $array1[1]);
			$id = $array2[0];
			if(strpos($id,"-")){
				$array3 = explode("-",$id);
				$id=$array3[0];
			}
            $id = $this->get_code($id);
			$result = $this->db->query("select cms.idx as content_id,cms.title as topic,cms.description_short as short_detail,CONCAT('".$this->config->item('static_url')."', COALESCE(cms.thumb_path, 'no-image.png')) thumbnail,cms.start_date as addDateTime from cmsblog_detail cms where record_status=1 and cms.idx=" . $id)->result();
			$rs = $result;
			foreach ($rs as $key => $value) {
				$datas['subject']=$CI->trueplook->limitText($value->topic,50);
				$datas['link']="http://www.trueplookpanya.com/knowledge/content/$id-cpg";
				$datas['thumb']=$value->thumbnail;
				$datas['short_detail']=$value->short_detail;
				$datas['add_date']=$CI->trueplook->data_format('small', 'th',$value->addDateTime);
				$data[]=$datas;
			}
		
        elseif (strpos($link, 'clipded') || strpos($link, 'knowledge_youtube')):
            if (strpos($link, 'clipded')):
                $id = end(explode("www.trueplookpanya.com/new/clipded/index/", $link));
                $id = $this->get_code($id);
            else:
                $id = end(explode("www.trueplookpanya.com/true/knowledge_youtube.php?youtube_id=", $link));
                $id = $this->get_code($id);
            endif;
            $result = $this->db->query("select * from youtube where youtube_id = '" . $id . "' and youtube_status=1 ")->result();
            $rs = $result;
            foreach ($rs as $key => $value) {
                $datas['subject'] = $CI->trueplook->limitText($value->youtube_subject, 50);
                $datas['link'] = $link;
                $datas['thumb'] = 'http://i3.ytimg.com/vi/' . $value->watch_code . '/default.jpg';
                $datas['short_detail'] = $value->youtube_subject;
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            } elseif (strpos($link, 'ethic_detail')):
            $id = end(explode("cms_id=", $link));
            $id = $this->get_code($id);
            $result = $this->db->query("select files.file_size,files.cms_file_id,files.file_type,cms.credit_by,cms.cms_category_id,cms.cms_detail_long as cms_detail,cms.cms_id,cms.cms_subject as cms_subject,cms.member_id,cms.add_date,cms.thumb_path as cms_file_path,files.file_name,cms.image_filename_m as cms_file_name,cms.image_filename_s as cms_file_name_s,cms.thumb_path from cms left join cms_file files on cms.cms_id=files.cms_id where cms.cms_id='" . $id . "'  ")->result();
            $rs = $result;
            foreach ($rs as $key => $value) {
                $datas['subject'] = $CI->trueplook->limitText($value->cms_subject, 50);
                $datas['link'] = $link;
                $path = $value->cms_file_path;
                if ($value->cms_file_name <> '') {
                    $thumb = $value->cms_file_name;
                } else {
                    $thumb = substr($value->file_name, 0, -4) . '_128x96.png';
                }
                $datas['thumb'] = 'http://www.trueplookpanya.com' . $CI->trueplook->get_media_path('image') . $path . '/' . $thumb;
                $datas['short_detail'] = $value->cms_detail;
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            } elseif (strpos($link, 'tv_program_detai')):
            $array1 = explode("tv_program_detail", $link);
            $id = explode("/", $array1[1]);
            $id = $this->get_code($id);
            $result = $this->db->query("select tv.*,vdo.*,program.tv_name from tv_program_episode tv left join tv_program_episode_vdo vdo on tv.tv_episode_id=vdo.tv_episode_id left join tv_program program on program.tv_id=tv.tv_id  where tv.tv_episode_id='" . $id[2] . "' and tv.record_status=1 ")->result();
            $rs = $result;
            foreach ($rs as $key => $value) {

                $datas['post_name'] = $CI->trueplook->get_display_name($value->update_by, 'link');
                $datas['view'] = $CI->trueplook->getViewCenter($content[1], 'tv_program_episode', 'media');
                $datas['subject'] = $value->tv_name . ' : ' . $value->tv_episode_name;
                $datas['subject_all'] = $value->tv_name . ' : ' . $value->tv_episode_name;
                if ($value->tv_vdo_filename <> '') {
                    $path = $value->tv_vdo_path;
                    $thumb = substr($value->tv_vdo_filename, 0, -4) . '_128x96.png';
                }
                if ($value->tv_vdo_filename <> '') {
                    $datas['thumb'] = $CI->trueplook->image_resize(320, 240, $path, $thumb);
                } else {
                    if ($value->upload_type == 'Youtube') {
                        $datas['thumb'] = 'http://i3.ytimg.com/vi/' . $value->youtube_code . '/mqdefault.jpg';
                    } else {
                        $datas['thumb'] = $thumb_error;
                    }
                }
                $datas['link'] = $link;
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            } elseif (strpos($link, 'entertainment_api')):
            $array1 = explode("entertainment_api", $link);
            $id = explode("/", $array1[1]);
            $id = $id[1];
            $id = $this->get_code($id);
            $truelife_XML = 'http://api.platform.truelife.com/cms/content/' . $id;
            $file = file_get_contents($truelife_XML);
            $v = $CI->convert_vdo_v3->xml2array($file);

            $data['subject'] = $v['data']['title'];
            $data['subject_all'] = $v['data']['title'];
            $data['thumb'] = $v['data']['thumbnail'];
            $data['description'] = $v['data']['description'];
            $data['rs_vdo'] = $v['data']['trailer']['item']['files']['item'];
            $data['api_view'] = $v['data']['views'];
            $data['api_type'] = $v['data']['lifestyle'];
            $date = explode(' ', $v['data']['created_date']);
            $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
            $data['link'] = $link;

        elseif (strpos($link, 'sonsart_news_detail')):
            $array1 = explode("sonsart_news_detail", $link);
            $id = explode("/", $array1[1]);
            $id = $id[1];
            $id = $this->get_code($id);
            $result = $this->db->query("select * from sonsart_news  where news_id='" . $id . "'  and record_status=1 ")->result();
            $rs = $result;

            foreach ($rs as $key => $value) {
                $datas['post_name'] = $CI->trueplook->get_display_name($value->update_by, 'link');
                $datas['subject'] = $value->news_title;
                $datas['subject_all'] = $value->news_short_detail;
                $datas['thumb'] = $CI->trueplook->image_resize(320, 240, $value->news_path, $value->news_banner_file);
                $datas['path'] = $value->news_path;
                $datas['banner'] = $value->news_banner_file;
                $datas['link'] = $link;
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            } elseif (strpos($link, 'examination_display.php') || strpos($link, 'doexam')):
            if (strpos($link, 'doexam')) {
                $id = end(explode("/", $link));
                $id = $this->get_code($id);
            } else {
                $id = end(explode("=", $link));
                $id = $this->get_code($id);
            }
            $result = $this->db->query("select * from cvs_course_examination where id='" . $id . "' and exam_status='yes' ")->result();
            $rs = $result;
            foreach ($rs as $key => $value) {
                $datas['post_name'] = $CI->trueplook->get_display_name($value->member_id, 'link');
//        $datas['view'] = $CI->trueplook->getViewNumber($value->exam_id, 2);
                $datas['icon_subject'] = "http://static.trueplookpanya.com/trueplookpanya/media/home/icon_subject/" . $value->mul_root_id . ".png";

                $datas['level_name'] = $this->db->query("select mul_level_name from mul_level where mul_level_id in(" . $value->mul_level_id . ")")->result()[0]->mul_level_name;

                $datas['subject'] = $CI->trueplook->limitText($value->exam_name, 50);
                $datas['subject_all'] = $value->exam_name;
                //$datas['level'] = $CI->trueplook->get_icon_level($value->mul_level_id);
                $date = explode(' ', $value->exam_add_date);
                $datas['exam_code'] = $value->exam_code;
				$datas['exam_add_date'] = $value->exam_add_date;
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $datas['link'] = 'http://www.trueplookpanya.com/examination/doexam/' . $id;
                $selNumQuestion = $this->db->query(" select count(exam_id) as num_question from cvs_course_question where exam_id = '" . $id . "' ")->result();
                $rs2 = $selNumQuestion[0];
                $datas['num_q'] = $rs2->num_question;
                $data[] = $datas;
            }
        endif;
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    function check_content_new($link) {
        $CI = & get_instance();
        $CI->load->library('trueplook');
        $CI->load->library('truelife_api');
        $CI->load->library('convert_vdo_v3');
        $link = rtrim($link, "/");

        if (strpos($link, 'cms_detail/knowledge')):
            if (strpos($link, '-')):
                $array1 = explode("-", $link);
                $id = end(explode("/", $array1[0]));
                $id = $this->get_code($id);
                $source_id = $array1[1];
            else:
                $ids = explode("/", $link);
                $id = $ids[6];
                $source_id = '';
            endif;
            if ($source_id <> ''):
                $result = $this->db->query("select ms.mul_source_id,ms.mul_title,ms.mul_thumbnail_file,ms.mul_source_id,mc.mul_content_subject,mc.mul_content_id,mc.member_id,mc.mul_content_text,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id,mc.mul_tag from mul_content mc INNER JOIN mul_source ms on mc.mul_content_id=ms.mul_content_id where mc.mul_content_id='" . $id . "' and mc.mul_content_status=1 and ms.mul_source_id =  '" . $source_id . "' order by ms.weight desc,ms.mul_source_id asc")->result();
            else:
                $result = $this->db->query("select ms.mul_source_id,ms.mul_title,ms.mul_thumbnail_file,ms.mul_source_id,mc.mul_content_subject,mc.mul_content_id,mc.member_id,mc.mul_content_text,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id,mc.mul_tag from mul_content mc INNER JOIN mul_source ms on mc.mul_content_id=ms.mul_content_id where mc.mul_content_id='" . $id . "' and mc.mul_content_status=1 order by ms.weight desc,ms.mul_source_id asc")->result();
            endif;
            $rs = $result;
            foreach ($rs as $key => $value) {
                $subject = ($value->mul_title <> '') ? $value->mul_title : $value->mul_content_subject;
                $datas['subject'] = $CI->trueplook->limitText($subject, 60);
                if ($source_id <> '') {
                    $datas['link'] = $link;
                } else {
                    $datas['link'] = $link . '-' . ($value->mul_source_id);
                }
                $sFile = $this->db->query("select mul_destination,mul_thumbnail_file, mul_image_file FROM mul_source WHERE mul_content_id = '" . $id . "' AND mul_source_id = '" . $source_id . "' order by weight desc,mul_source_id asc")->result();
                $file = $sFile[0];

                if ($value->mul_filename <> '') {
                    $path = $value->mul_destination;
                    if ($value->mul_thumbnail_file <> '') {
                        $thumb = $value->mul_thumbnail_file;
                    } else {
                        $thumb = substr($value->mul_filename, 0, -4) . '_320x240.png';
                    }
                }
                //$data['thumb'] = $CI->trueplook->image_resize(128, 96, $path, $thumb);
                if ($value->mul_filename <> '') {
                    if ($value->mul_type_id == 'a') {
                        $thumb_error = base_url() . 'new/assets/images/icon/snd126x95px.jpg';
                    }
                    if ($value->mul_type_id == 'f') {
                        $thumb_error = base_url() . 'new/assets/images/icon/swf126x95px.jpg';
                    }
                    if ($value->mul_type_id == 'd') {
                        $thumb_error = base_url() . 'new/assets/images/icon/doc126x95px.jpg';
                    }
                } else {
                    $thumb_error = base_url() . 'new/assets/images/icon/doc126x95px.jpg';
                }
                //
                if ($value->mul_filename <> '' and $value->mul_type_id == 'v') {



                    $datas['thumb'] = $CI->trueplook->image_resize(320, 240, $path, $thumb);
                    //$datas['thumb'] = 'http://www.trueplookpanya.com'.$CI->trueplook->get_media_path('image') . $path . '/' . $thumb;
                } else {
                    $datas['thumb'] = $thumb_error;
                }
                if ($file->mul_thumbnail_file <> '' || $file->mul_image_file <> ''):
                    $thb = ($file->mul_image_file <> '') ? $file->mul_image_file : $file->mul_thumbnail_file;
                    $datas['thumb'] = 'http://www.trueplookpanya.com/data/product/media/' . $file->mul_destination . '/' . $thb;
                endif;
                $datas['short_detail'] = mb_substr(strip_tags($rs->mul_content_text), 50);
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            }

        elseif (strpos($link, 'knowledge/detail')):
            if (strpos($link, '-')):
                $array1 = explode("-", $link);
                $id = end(explode("/", $array1[0]));
                $id = $this->get_code($id);
                $source_id = $array1[1];
            else:
                $ids = explode("/", $link);
                if (count($ids) < 6) {
                    $id = $ids[5];
                    $source_id = '';
                } else {
                    $id = $ids[5];
                    $source_id = $ids[6];
                }
            endif;
            if ($source_id != ''):
                $result = $this->db->query("select ms.mul_content_id,ms.mul_title,ms.mul_thumbnail_file,ms.mul_source_id,mc.mul_content_subject,mc.mul_content_id,mc.member_id,mc.mul_content_text,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id,mc.mul_tag from mul_content mc INNER JOIN mul_source ms on mc.mul_content_id=ms.mul_content_id where mc.mul_content_id='" . $id . "' and mc.mul_content_status=1 and ms.mul_source_id =  '" . $source_id . "' order by ms.weight desc,ms.mul_source_id asc")->result();
            else:
                $result = $this->db->query("select ms.mul_content_id,ms.mul_title,ms.mul_thumbnail_file,ms.mul_source_id,mc.mul_content_subject,mc.mul_content_id,mc.member_id,mc.mul_content_text,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id,mc.mul_tag from mul_content mc INNER JOIN mul_source ms on mc.mul_content_id=ms.mul_content_id where mc.mul_content_id='" . $id . "' and mc.mul_content_status=1 order by ms.weight desc,ms.mul_source_id asc")->result();
            endif;
            $rs = $result;
            foreach ($rs as $key => $value) {
                $subject = ($value->mul_title <> '') ? $value->mul_title : $value->mul_content_subject;
                $datas['subject'] = $CI->trueplook->limitText($subject, 60);
                if ($source_id <> '') {
                    $datas['link'] = $link;
                } else {
                    $datas['link'] = $link . '-' . ($value->mul_source_id);
                }
                $sFile = $this->db->query("select mul_destination,mul_thumbnail_file, mul_image_file FROM mul_source WHERE mul_content_id = '" . $id . "' AND mul_source_id = '" . $source_id . "' order by weight desc,mul_source_id asc")->result();
                $file = $sFile[0];

                if ($value->mul_filename <> '') {
                    $path = $value->mul_destination;
                    if ($value->mul_thumbnail_file <> '') {
                        $thumb = $value->mul_thumbnail_file;
                    } else {
                        $thumb = substr($value->mul_filename, 0, -4) . '_320x240.png';
                    }
                }
                //$data['thumb'] = $CI->trueplook->image_resize(128, 96, $path, $thumb);
                if ($value->mul_filename <> '') {
                    if ($value->mul_type_id == 'a') {
                        $thumb_error = base_url() . 'new/assets/images/icon/snd126x95px.jpg';
                    }
                    if ($value->mul_type_id == 'f') {
                        $thumb_error = base_url() . 'new/assets/images/icon/swf126x95px.jpg';
                    }
                    if ($value->mul_type_id == 'd') {
                        $thumb_error = base_url() . 'new/assets/images/icon/doc126x95px.jpg';
                    }
                } else {
                    $thumb_error = base_url() . 'new/assets/images/icon/doc126x95px.jpg';
                }
                if ($value->mul_filename <> '' and $value->mul_type_id == 'v') {
                    $datas['thumb'] = $CI->trueplook->image_resize(320, 240, $path, $thumb);
                    //$datas['thumb'] = 'http://www.trueplookpanya.com'.$CI->trueplook->get_media_path('image') . $path . '/' . $thumb;
                } else {
                    $datas['thumb'] = $thumb_error;
                }
                if ($file->mul_thumbnail_file <> '' || $file->mul_image_file <> ''):
                    $thb = ($file->mul_image_file <> '') ? $file->mul_image_file : $file->mul_thumbnail_file;
                    $datas['thumb'] = 'http://www.trueplookpanya.com/data/product/media/' . $file->mul_destination . '/' . $thb;
                endif;
                $datas['short_detail'] = mb_substr(strip_tags($rs->mul_content_text), 50);
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            }


        elseif (strpos($link, 'cms_detail/general_knowledge') || strpos($link, 'cms_detail/guidance') || strpos($link, 'cms_detail/news') || strpos($link, 'entertainment/')):
            if (strpos($link, 'cms_detail/general_knowledge')):
                $array1 = explode("general_knowledge", $link);
                $id = end(explode("/", $array1[1]));
            elseif (strpos($link, 'cms_detail/news')):
                $array1 = explode("cms_detail/news", $link);
                $id = explode("/", $array1[1]);
                $id = $id[1];
            elseif (strpos($link, 'entertainment/')):
                $id = end(explode("entertainment/", $link));
            else:
                $array1 = explode("cms_detail/guidance", $link);
                $id = explode("/", $array1[1]);
                $id = $id[1];
            endif;
            $id = $this->get_code($id);
            $result = $this->db->query("select files.file_size,files.cms_file_id,files.file_type,cms.credit_by,cms.cms_category_id,cms.cms_detail_short as cms_detail,cms.cms_id,cms.cms_subject as cms_subject,cms.member_id,cms.add_date,cms.thumb_path as cms_file_path,files.file_name,cms.image_filename_m as cms_file_name,cms.image_filename_s as cms_file_name_s,cms.thumb_path  ,cms.image_filename_l from cms left join cms_file files on cms.cms_id=files.cms_id where cms.cms_id='" . $id . "'  ")->result();
            $rs = $result;
            foreach ($rs as $key => $value) {
                $datas['subject'] = $CI->trueplook->limitText($value->cms_subject, 50);
                $datas['link'] = $link;
                $path = $value->cms_file_path;
                if ($value->cms_file_name_s <> '') { //
                    $thumb = $value->cms_file_name_s;
                } elseif ($value->cms_file_name <> '') {
                    $thumb = $value->cms_file_name;
                } else {
                    $thumb = substr($value->file_name, 0, -4) . '_128x96.png';
                }
                if ($value->image_filename_l <> '' || $value->cms_file_name_s <> ''):
                    $thb = ($value->image_filename_l <> '') ? $value->image_filename_l : $value->cms_file_name_s;
                    $datas['thumb'] = 'http://www.trueplookpanya.com/data/product/media/' . $value->cms_file_path . '/' . $thb;
                else:
                    $datas['thumb'] = 'http://www.trueplookpanya.com' . $CI->trueplook->get_media_path('image') . $path . '/' . $thumb;
                endif;
                $datas['short_detail'] = $value->cms_detail;
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
//        $datas['views'] = $CI->trueplook->getViewNumber($id, 21);
                $data[] = $datas;
            }

        elseif (strpos($link, 'clipded') || strpos($link, 'knowledge_youtube')):
            if (strpos($link, 'clipded')):
                $id = end(explode("www.trueplookpanya.com/new/clipded/index/", $link));
                $id = $this->get_code($id);
            else:
                $id = end(explode("www.trueplookpanya.com/true/knowledge_youtube.php?youtube_id=", $link));
                $id = $this->get_code($id);
            endif;
            $result = $this->db->query("select * from youtube where youtube_id = '" . $id . "' and youtube_status=1 ")->result();
            $rs = $result;
            foreach ($rs as $key => $value) {
                $datas['subject'] = $CI->trueplook->limitText($value->youtube_subject, 50);
                $datas['link'] = $link;
                $datas['thumb'] = 'http://i3.ytimg.com/vi/' . $value->watch_code . '/default.jpg';
                $datas['short_detail'] = $value->youtube_subject;
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            } elseif (strpos($link, 'ethic_detail')):
            $id = end(explode("cms_id=", $link));
            $id = $this->get_code($id);
            $result = $this->db->query("select files.file_size,files.cms_file_id,files.file_type,cms.credit_by,cms.cms_category_id,cms.cms_detail_long as cms_detail,cms.cms_id,cms.cms_subject as cms_subject,cms.member_id,cms.add_date,cms.thumb_path as cms_file_path,files.file_name,cms.image_filename_m as cms_file_name,cms.image_filename_s as cms_file_name_s,cms.thumb_path from cms left join cms_file files on cms.cms_id=files.cms_id where cms.cms_id='" . $id . "'  ")->result();
            $rs = $result;
            foreach ($rs as $key => $value) {
                $datas['subject'] = $CI->trueplook->limitText($value->cms_subject, 50);
                $datas['link'] = $link;
                $path = $value->cms_file_path;
                if ($value->cms_file_name <> '') {
                    $thumb = $value->cms_file_name;
                } else {
                    $thumb = substr($value->file_name, 0, -4) . '_128x96.png';
                }
                $datas['thumb'] = 'http://www.trueplookpanya.com' . $CI->trueplook->get_media_path('image') . $path . '/' . $thumb;
                $datas['short_detail'] = $value->cms_detail;
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            } elseif (strpos($link, 'tv_program_detai')):
            $array1 = explode("tv_program_detail", $link);

            $id = explode("/", $array1[1]);
            $id = $this->get_code($id);
            $result = $this->db->query("select tv.*,vdo.*,program.tv_name from tv_program_episode tv left join tv_program_episode_vdo vdo on tv.tv_episode_id=vdo.tv_episode_id left join tv_program program on program.tv_id=tv.tv_id  where tv.tv_episode_id='" . $id[2] . "' and tv.record_status=1 ")->result();
            $rs = $result;

            foreach ($rs as $key => $value) {

                $datas['post_name'] = $CI->trueplook->get_display_name($value->update_by, 'link');
                $datas['view'] = $CI->trueplook->getViewCenter($content[1], 'tv_program_episode', 'media');
                $datas['subject'] = $value->tv_name . ' : ' . $value->tv_episode_name;
                $datas['subject_all'] = $value->tv_name . ' : ' . $value->tv_episode_name;
                if ($value->tv_vdo_filename <> '') {
                    $path = $value->tv_vdo_path;
                    $thumb = substr($value->tv_vdo_filename, 0, -4) . '_128x96.png';
                }
                if ($value->tv_vdo_filename <> '') {
                    $datas['thumb'] = $CI->trueplook->image_resize(320, 240, $path, $thumb);
                } else {
                    if ($value->upload_type == 'Youtube') {
                        $datas['thumb'] = 'http://i3.ytimg.com/vi/' . $value->youtube_code . '/mqdefault.jpg';
                    } else {
                        $datas['thumb'] = $thumb_error;
                    }
                }
                $datas['link'] = $link;
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            } elseif (strpos($link, 'entertainment_api')):
            $array1 = explode("entertainment_api", $link);
            $id = explode("/", $array1[1]);
            $id = $id[1];
            $id = $this->get_code($id);
            $truelife_XML = 'http://api.platform.truelife.com/cms/content/' . $id;
            $file = file_get_contents($truelife_XML);
            $v = $CI->convert_vdo_v3->xml2array($file);

            $data['subject'] = $v['data']['title'];
            $data['subject_all'] = $v['data']['title'];
            $data['thumb'] = $v['data']['thumbnail'];
            $data['description'] = $v['data']['description'];
            $data['rs_vdo'] = $v['data']['trailer']['item']['files']['item'];
            $data['api_view'] = $v['data']['views'];
            $data['api_type'] = $v['data']['lifestyle'];
            $date = explode(' ', $v['data']['created_date']);
            $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
            $data['link'] = $link;

        elseif (strpos($link, 'sonsart_news_detail')):
            $array1 = explode("sonsart_news_detail", $link);
            $id = explode("/", $array1[1]);
            $id = $id[1];
            $id = $this->get_code($id);
            $result = $this->db->query("select * from sonsart_news  where news_id='" . $id . "'  and record_status=1 ")->result();
            $rs = $result;

            foreach ($rs as $key => $value) {
                $datas['post_name'] = $CI->trueplook->get_display_name($value->update_by, 'link');
                $datas['subject'] = $value->news_title;
                $datas['subject_all'] = $value->news_short_detail;
                $datas['thumb'] = $CI->trueplook->image_resize(320, 240, $value->news_path, $value->news_banner_file);
                $datas['path'] = $value->news_path;
                $datas['banner'] = $value->news_banner_file;
                $datas['link'] = $link;
                $date = explode(' ', $value->add_date);
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $data[] = $datas;
            } elseif (strpos($link, 'examination_display.php') || strpos($link, 'doexam')):
            if (strpos($link, 'doexam')) {
                $id = end(explode("/", $link));
                $id = $this->get_code($id);
            } else {
                $id = end(explode("=", $link));
                $id = $this->get_code($id);
            }
            $result = $this->db->query("select * from cvs_course_examination where id='" . $id . "' and exam_status='yes' ")->result();
            $rs = $result;
            foreach ($rs as $key => $value) {
                $datas['post_name'] = $CI->trueplook->get_display_name($value->member_id, 'link');
//        $datas['view'] = $CI->trueplook->getViewNumber($value->exam_id, 2);
                $datas['icon_subject'] = "http://static.trueplookpanya.com/trueplookpanya/media/home/icon_subject/" . $value->mul_root_id . ".png";

                $datas['level_name'] = $this->db->query("select mul_level_name from mul_level where mul_level_id in(" . $value->mul_level_id . ")")->result()[0]->mul_level_name;

                $datas['subject'] = $CI->trueplook->limitText($value->exam_name, 50);
                $datas['subject_all'] = $value->exam_name;
                //$datas['level'] = $CI->trueplook->get_icon_level($value->mul_level_id);
                $date = explode(' ', $value->exam_add_date);
                $datas['exam_code'] = $value->exam_code;
				$datas['exam_add_date'] = $value->exam_add_date;
                $datas['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                $datas['link'] = 'http://www.trueplookpanya.com/examination/doexam/' . $id;
                $selNumQuestion = $this->db->query(" select count(exam_id) as num_question from cvs_course_question where exam_id = '" . $id . "' ")->result();
                $rs2 = $selNumQuestion[0];
                $datas['num_q'] = $rs2->num_question;
                $data[] = $datas;
            }
        endif;
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}
