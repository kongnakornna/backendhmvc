<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Campaign_libraries {

    public function get_campaign_content($array, $error = '', $type = 'vdo', $template = 'list', $icon = '') {
        $CI = & get_instance();
        $CI->load->model('connect_db_model', 'cdm');
        $CI->load->library('trueplook');
        $CI->load->library('memcache');
        $return = '';
        $no = 1;
        $icon_flag = 1;

        foreach ($array as $k => $v) {
            //table#id#position
            $content = explode('#', $v);
            $path = '';
            $thumb = '';
            switch ($content[0]) {

                case "mul_content":
                    $sql = "select ms.mul_thumbnail_file,ms.mul_source_id,mc.mul_content_subject,mc.mul_content_id,mc.member_id,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id from mul_content mc left join mul_source ms on mc.mul_content_id=ms.mul_content_id where mc.mul_content_id='" . $content[1] . "' and mc.mul_content_status=1 limit 0,1 ";

                    if ($CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]) == '') {

                        $result = $CI->cdm->_query_row($sql, 'select');
                        $CI->memcache->set('get_content_campaign_' . $content[0] . $content[1], $result, 3600 * 24 * 7);
                    }
                    $result = $CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]);
                    $rs = $result[0];
                    $data['post_name'] = $CI->trueplook->get_display_name($rs['member_id'], 'link');
                    if ($rs['mul_source_id'] <> '') {
                        $data['view'] = $CI->trueplook->getViewNumber($rs['mul_source_id'], 7);
                    } else {
                        $data['view'] = $CI->trueplook->getViewNumber($rs['mul_content_id'], 0);
                    }
                    $data['subject'] = $rs['mul_content_subject'];
                    $data['subject_all'] = $rs['mul_content_subject'];
                    $data['add_date'] = $rs['add_date'];
                    if ($rs['mul_filename'] <> '') {
                        $path = $rs['mul_destination'];
                        if ($rs['mul_thumbnail_file'] <> '') {
                            $thumb = $rs['mul_thumbnail_file'];
                        } else {
                            $thumb = substr($rs['mul_filename'], 0, -4) . '_128x96.png';
                        }
                    }
                    if ($error == '') {
                        $thumb_error = base_url() . "assets/images/thumb/campaign/olympic/thumb.png";
                    } else {

                        if (preg_match("/aec/", $error) or preg_match("/onet/", $error)) {

                            $thumb_error = $error;
                        } else {

                            if ($rs['mul_filename'] <> '') {
                                if ($rs['mul_type_id'] == 'a') {
                                    $thumb_error = base_url() . 'assets/images/icon/snd126x95px.jpg';
                                }
                                if ($rs['mul_type_id'] == 'f') {
                                    $thumb_error = base_url() . 'assets/images/icon/swf126x95px.jpg';
                                }
                                if ($rs['mul_type_id'] == 'd') {
                                    $thumb_error = base_url() . 'assets/images/icon/doc126x95px.jpg';
                                }
                            } else {
                                $thumb_error = base_url() . 'assets/images/icon/doc126x95px.jpg';
                            }
                        }
                    }
                    if ($rs['mul_filename'] <> '' and $rs['mul_type_id'] == 'v') {
                        $data['thumb'] = $CI->trueplook->image_resize(128, 96, $path, $thumb);
                    } else {
                        $data['thumb'] = $thumb_error;
                    }

                    //$data['link']='http://www.trueplookpanya.com/true/knowledge_detail.php?mul_content_id='.$rs['mul_content_id'].'&mul_source_id='.$rs['mul_source_id'].' ';
                    $data['link'] = base_url() . 'cms_detail/knowledge/' . $rs['mul_content_id'] . '-' . $rs['mul_source_id'] . '/';



                    break;


                case "mul_source":
                    $sql = "select mc.mul_content_subject as mul_content_subject_sub ,ms.mul_thumbnail_file,ms.mul_source_id,ms.mul_title as mul_content_subject,mc.mul_content_id,mc.member_id,mc.add_date,ms.mul_destination,ms.mul_filename,ms.mul_type_id from mul_content mc left join mul_source ms on mc.mul_content_id=ms.mul_content_id where ms.mul_source_id='" . $content[1] . "' and mc.mul_content_status=1 limit 0,1 ";

                    if ($CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]) == '') {

                        $result = $CI->cdm->_query_row($sql, 'select');
                        $CI->memcache->set('get_content_campaign_' . $content[0] . $content[1], $result, 3600 * 24 * 7);
                    }
                    $result = $CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]);
                    $rs = $result[0];
                    $data['post_name'] = $CI->trueplook->get_display_name($rs['member_id'], 'link');
                    if ($rs['mul_source_id'] <> '') {
                        $data['view'] = $CI->trueplook->getViewNumber($rs['mul_source_id'], 7);
                    } else {
                        $data['view'] = $CI->trueplook->getViewNumber($rs['mul_content_id'], 0);
                    }

                    if ($rs['mul_content_subject'] == '') {
                        $data['subject'] = $rs['mul_content_subject_sub'];
                    } else {
                        $data['subject'] = $rs['mul_content_subject'];
                    }

                    $data['subject_all'] = $rs['mul_content_subject'];
                    if ($rs['mul_filename'] <> '') {
                        $path = $rs['mul_destination'];
                        if ($rs['mul_thumbnail_file'] <> '') {
                            $thumb = $rs['mul_thumbnail_file'];
                        } else {
                            $thumb = substr($rs['mul_filename'], 0, -4) . '_128x96.png';
                        }
                    }
                    if ($error == '') {
                        $thumb_error = base_url() . "assets/images/thumb/campaign/olympic/thumb.png";
                    } else {

                        if (preg_match("/aec/", $error) or preg_match("/onet/", $error)) {

                            $thumb_error = $error;
                        } else {
                            if ($rs['mul_filename'] <> '') {
                                if ($rs['mul_type_id'] == 'a') {
                                    $thumb_error = base_url() . 'assets/images/icon/snd126x95px.jpg';
                                }
                                if ($rs['mul_type_id'] == 'f') {
                                    $thumb_error = base_url() . 'assets/images/icon/swf126x95px.jpg';
                                }
                                if ($rs['mul_type_id'] == 'd') {
                                    $thumb_error = base_url() . 'assets/images/icon/doc126x95px.jpg';
                                }
                            } else {
                                $thumb_error = base_url() . 'assets/images/icon/doc126x95px.jpg';
                            }
                        }
                    }
                    if ($rs['mul_filename'] <> '' and $rs['mul_type_id'] == 'v') {
                        $data['thumb'] = $CI->trueplook->image_resize(128, 96, $path, $thumb);
                    } else {
                        $data['thumb'] = $thumb_error;
                    }

                    //	$data['link']='http://www.trueplookpanya.com/true/knowledge_detail.php?mul_content_id='.$rs['mul_content_id'].'&mul_source_id='.$rs['mul_source_id'].' ';					

                    $data['link'] = base_url() . 'cms_detail/knowledge/' . $rs['mul_content_id'] . '-' . $rs['mul_source_id'] . '/';
                    $data['add_date'] = $rs['add_date'];

                    break;


                case "cms":

                    $sql = "select * from cms  WHERE record_status = '1' and cms_id='" . $content[1] . "' LIMIT 0,1";

                    if ($CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]) == '') {

                        $result = $CI->cdm->_query_row($sql, 'select');
                        $CI->memcache->set('get_content_campaign_' . $content[0] . $content[1], $result, 3600 * 24 * 7);
                    }
                    $result = $CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]);
                    $rs = $result[0];
                    $data['post_name'] = $CI->trueplook->get_display_name($rs['member_id'], 'link');
                    $data['view'] = $CI->trueplook->getViewNumber($rs['cms_id'], 21);
                    $data['subject'] = $rs['cms_subject'];
                    $data['subject_all'] = $rs['cms_subject'];
                    if ($rs['image_filename_m'] <> '') {
                        $path = $rs['thumb_path'];
                        $thumb = $rs['image_filename_m'];
                    }

                    $full_path = $CI->trueplook->set_media_path_full('image', 'no_year');
                    if (!file_exists($full_path . $path . '/' . $thumb)) {
                        $sql_thumb_2 = "select * from cms_file where cms_id='" . $content[1] . "' limit 0,1 ";
                        $result_thumb_1 = $CI->cdm->_query_row($sql_thumb_2, 'select');
                        $rs_thumb = $result_thumb_1[0];
                        $path = $rs_thumb['file_path'];
                        $thumb = substr($rs_thumb['file_name'], 0, -4) . '_128x96.png';
                    }


                    if ($thumb <> '') {
                        $data['thumb'] = $CI->trueplook->image_resize(128, 96, $path, $thumb);
                    } else {
                        $data['thumb'] = $thumb_error;
                    }

                    if ($content[2] == 'sarapan') {

                        $data['link'] = 'http://www.trueplookpanya.com/true/sarapan_detail.php?cms_id=' . $rs['cms_id'];
                    } else if ($content[2] == 'guidance') {

                        $data['link'] = 'http://www.trueplookpanya.com/true/guidance_detail.php?cms_id=' . $rs['cms_id'];
                    } else if ($content[2] == 'ethic') {

                        $data['link'] = 'http://www.trueplookpanya.com/true/ethic_detail.php?cms_id=' . $rs['cms_id'];
                    } else if ($content[2] == 'entertainment') {
                        $data['link'] = 'http://www.trueplookpanya.com/new/cms_detail/entertainment/' . $rs['cms_id'] . '/';
                    }

                    $data['add_date'] = $rs['add_date'];


                    break;

                case "youtube":
                    $sql = "select * from youtube where youtube_id = '" . $content[1] . "' and youtube_status=1 ";
                    if ($CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]) == '') {

                        $result = $CI->cdm->_query_row($sql, 'select');
                        $CI->memcache->set('get_content_campaign_' . $content[0] . $content[1], $result, 3600 * 24 * 7);
                    }
                    $result = $CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]);
                    $rs = $result[0];
                    $data['post_name'] = $CI->trueplook->get_display_name($rs['member_id'], 'link');
                    $data['view'] = $CI->trueplook->getViewNumber($rs['youtube_id'], 18);
                    $data['subject'] = $rs['youtube_subject'];
                    $data['subject_all'] = $rs['youtube_subject'];

                    $data['thumb'] = 'http://i3.ytimg.com/vi/' . $rs['watch_code'] . '/default.jpg';
                    $data['link'] = base_url().'clipded/index/' . $rs['youtube_id'];
                    $data['add_date'] = $rs['add_date'];

                    break;

                case "tv":
                    $sql = "select tv.*,vdo.*,program.tv_name from tv_program_episode tv left join tv_program_episode_vdo vdo on tv.tv_episode_id=vdo.tv_episode_id left join tv_program program on program.tv_id=tv.tv_id  where tv.tv_episode_id='" . $content[1] . "' and tv.record_status=1 ";
                    if ($CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]) == '') {

                        $result = $CI->cdm->_query_row($sql, 'select');
                        $CI->memcache->set('get_content_campaign_' . $content[0] . $content[1], $result, 3600 * 24 * 7);
                    }
                    $result = $CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]);
                    $rs = $result[0];
                    $data['post_name'] = $CI->trueplook->get_display_name($rs['update_by'], 'link');
                    $data['view'] = $CI->trueplook->getViewCenter($content[1], 'tv_program_episode', 'media');
                    $data['subject'] = $rs['tv_name'] . ' : ' . $rs['tv_episode_name'];
                    $data['subject_all'] = $rs['tv_name'] . ' : ' . $rs['tv_episode_name'];
                    if ($rs['tv_vdo_filename'] <> '') {
                        $path = $rs['tv_vdo_path'];
                        $thumb = substr($rs['tv_vdo_filename'], 0, -4) . '_128x96.png';
                    }

                    if ($rs['tv_vdo_filename'] <> '') {
                        $data['thumb'] = $CI->trueplook->image_resize(128, 96, $path, $thumb);
                    } else {
                        if ($rs['upload_type'] == 'Youtube') {
                            $data['thumb'] = 'http://i3.ytimg.com/vi/' . $rs['youtube_code'] . '/mqdefault.jpg';
                        } else {
                            $data['thumb'] = $thumb_error;
                        }
                    }

                    $data['link'] = 'http://www.trueplookpanya.com/new/tv_program_detail/' . $rs['tv_id'] . '/' . $rs['tv_episode_id'] . '/';
                    $data['add_date'] = $rs['add_date'];

                    break;


                case "examination":

                    $sql = "select * from course_examination where exam_id='" . $content[1] . "' and exam_status='yes' ";
                    if ($CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]) == '') {

                        $result = $CI->cdm->_query_row($sql, 'select');
                        $CI->memcache->set('get_content_campaign_' . $content[0] . $content[1], $result, 3600 * 24 * 7);
                    }
                    $result = $CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]);
                    $rs = $result[0];
                    $data['post_name'] = $CI->trueplook->get_display_name($rs['member_id'], 'link');
                    $data['view'] = $CI->trueplook->getViewNumber($rs['exam_id'], 2);
                    $data['subject'] = $CI->trueplook->limitText($rs['exam_name'], 50);
                    $data['subject_all'] = $rs['exam_name'];
                    $data['level'] = $CI->trueplook->get_icon_level($rs['mul_level_id']);
                    $date = explode(' ', $rs['exam_add_date']);
                    $data['add_date'] = $CI->trueplook->data_format('small', 'th', $date[0]);
                    $data['link'] = 'http://www.trueplookpanya.com/true/examination_display.php?exam_id=' . $rs['exam_id'];

                    break;

                case "entertainment":
                    $CI->load->library('truelife_api');
                    if ($CI->memcache->get('get_content_campaign_' . $content[0] . $content[1]) == '') {

                        $result = $CI->truelife_api->get_content_detail($content[1]);

                        $CI->memcache->set('get_content_campaign_' . $content[0] . $content[1], $result, 3600 * 24 * 7);
                    }

                    $rs = $result[0];
                    $data['post_name'] = 'ทีมงานทรูปลูกปัญญา';
                    $data['view'] = number_format($rs['views']);
                    $data['subject'] = $rs['title'];
                    $data['subject_all'] = $rs['title'];
                    $data['thumb'] = 'http://dynamic.tlcdn2.com/api/image/get?h=92&w=128&url=' . $rs['thumbnail'];
                    $data['link'] = 'http://www.trueplookpanya.com/new/cms_detail/entertainment/' . $content[1] . '/';



                    break;
            } // $switch $content[0]

            if ($content[0] == 'examination') {

                $return.='
                                    <div class="campaign_show_line_exam">
										<div><a href="' . $data['link'] . '" target="_blank" title="' . $data['subject_all'] . '">' . $data['subject'] . '</a></div>
                                        <div>
                                        		<div style="float:left"><a href="' . $data['link'] . '" target="_blank" title="' . $data['subject_all'] . '">' . $data['level'] . '</a></div>
                                        		<div style="float: left; margin-left:5px">
                                                	<div>โพส : ' . $data['add_date'] . '</div>
                                                    <div>' . $data['view'] . ' views</div>
                                                </div>
                                                <div style="clear:left"></div>
                                        </div>
                                    
                                    </div>
								';

                if ($no >= 3) {
                    $no = 1;
                    $return.='<div style="clear:left"></div>';
                } else {
                    $no++;
                }
            } else {
                $thistime = mktime(0, 0, 0, date('n'), date('j'), date('Y')) - (3600 * 24 * 60);
                $date = explode(' ', $data['add_date']);
                list($y, $m, $d) = explode('-', $date[0]);
                $content_time = mktime(0, 0, 0, $m, $d, $y);
                $icon_new = '';
                if ($thistime < $content_time) {
                    $icon_new = '<img src="http://www.trueplookpanya.com/new/assets/images/icon/icon_new.gif" border="0" alt="content_new">';
                } else if($icon != '' and ++$k <= $icon) {
                    $icon_new = '<img src="http://www.trueplookpanya.com/new/assets/images/icon/icon_new.gif" border="0" alt="content_new">';
                }

                
                $return.='
								<div class="campaign_show_line">
                                	<div class="campaign_thumb"><a href="' . $data['link'] . '" target="_blank" title="' . $data['subject_all'] . '"><img src="' . $data['thumb'] . '" width="128" height="96" border="0" alt="' . $data['subject'] . '" /></a></div>
                                    <div>
                                    	<div><a href="' . $data['link'] . '" target="_blank" title="' . $data['subject_all'] . '">' . $data['subject'] . '</a> ' . $icon_new . '</div>
                                        
                                        <div>view : ' . $data['view'] . '</div>
                                    </div>
                                </div>
								';

                if ($no >= 4) {
                    $no = 1;
                    $return.='<div style="clear:left"></div>';
                } else {
                    $no++;
                }
            }
        }// foreach $array


        $return.='<div style="clear:left"></div>';
        return $data;
    }

}

?>