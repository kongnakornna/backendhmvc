<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms_template_teacher {

    public function get_text_menu_header() {

        $menu_array = array('<img src="' . base_url() . 'new/assets/images/icon/contact_icon.png" width="83" height="24" border="0" alt="ติดต่อเรา"/>' => 'http://www.trueplookpanya.com/true/about_us.php');
        $all_menu = count($menu_array);
        $no = 1;
        foreach ($menu_array as $k => $v) {
            if ($no == $all_menu) {

                $return_menu.='<a href="' . $v . '" >' . $k . '</a>';
            } else {

                $return_menu.='<a href="' . $v . '" >' . $k . '</a> | ';
            }

//            
//            $data_title_2['title']=$k;
//             $data_title_2['title_link']=$v;

            $no++;
        }
//         echo '<pre>';
//        print_r($data_title_2);
//        echo '</pre>';

        return $return_menu;
    }

//get_text_menu_header

    public function get_submenu_header_list($data, $class = null, $cate_id = null) {
        $dataCount = 0;
        foreach ($data as $k => $v) {

            if (rawurldecode(current_url()) . '/' == $v) {

                $header_class = 'cms_template_top_submenu_header_list_hover';
            } else {

                $header_class = '';
            }

            if ($cate_id <> '') {
                if (preg_match('/\/' . $cate_id . '/', $v)) {
                    $header_class = 'cms_template_top_submenu_header_list_hover';
                } else {
                    $header_class = '';
                }
            }

            $return_menu.='<div class="cms_template_top_submenu_header_list ' . $class . ' ' . $header_class . '  "><a href="' . $v . '" title="' . $k . '" targer="_self">' . $k . '</a></div>';
            $data_title_2[$dataCount]['title'] = $k;
            $data_title_2[$dataCount]['title_link'] = $v;
            $dataCount++;
        }
        $return_menu.=' <div style="clear:right"></div>';

//        echo '<pre>';
//        print_r($data_title_2);
//        echo '</pre>';



        return $data_title_2;
    }

//get_submenu_header_list

    public function get_cms_content($data = null, $type = 1, $table_content = 'cms', $table_view = 0, $content_limit = 1, $path_link = null, $target = '_self') {
        $CI = & get_instance();
        $CI->load->library('trueplook');
        $CI->load->library('memcache');
        $file_image['d'] = base_url() . 'new/assets/images/icon/doc126x95px.jpg';
        $file_image['a'] = base_url() . 'new/assets/images/icon/snd126x95px.jpg';
        $file_image['f'] = base_url() . 'new/assets/images/icon/swf126x95px.jpg';
        $file_image[null] = base_url() . 'new/assets/images/icon/doc126x95px.jpg';



        $return_data2;


        if (count($data) > 0) {
            $return_data = '<div>';


            $no_line = 1;
            foreach ($data as $k => $v) {


                //------- Set Data -----//
                if ($table_content == 'youtube') {
                    $img_thumbnail = "http://i3.ytimg.com/vi/" . $v['cms_file_path'] . "/default.jpg";
                    $content_view = $CI->trueplook->getViewNumber($v['cms_id'], 18);
                    $content_link = htmlspecialchars($path_link . $v['cms_id']);
                } else {

                    if ($table_content == 'mul_content') {

                        if ($v['mul_source_id'] == '') {
                            $source_id = '00';
                        } else {
                            $source_id = $v['mul_source_id'];
                        }
                        if ($source_id == '00') {
                            $content_link = htmlspecialchars($path_link . $v['cms_id']) . '/';
                        } else {
                            $content_link = htmlspecialchars($path_link . $v['cms_id'] . '-' . $source_id) . '/';
                        }
                        $file_name = $CI->trueplook->get_image_name($v['cms_file_name']);
                        if ($v['mul_source_id'] == NULL) {
                            $content_view = $CI->trueplook->getViewNumber($v['cms_id'], 0);
                        } else {
                            $content_view = $CI->trueplook->getViewNumber($v['mul_source_id'], 7);
                            $v['cms_id'] = $v['mul_source_id'];
                        }
                    } else if ($table_content == 'news_center') {

                        $content_view = $CI->trueplook->getViewCenter($v['cms_id'], $v['cms_category_id'], 'news');
                        $content_link = htmlspecialchars($path_link . $v['cms_id']) . '/';
                        $file_name = $v['cms_file_name'];
                        if (!file_exists($CI->trueplook->set_media_path_full('image', 'no-year') . $v['cms_file_path'] . '/' . $file_name) or $file_name == '') {

                            $file_name = $v['cms_file_name_s'];
                        }
                    } else if ($table_content == 'tv') {

                        $content_view = $CI->trueplook->getViewCenter($v['cms_id'], 'tv_program_episode', 'media');
                        $content_link = htmlspecialchars($path_link . $v['tv_id'] . '/' . $v['cms_id']) . '/';
                    } else if ($table_content == 'webboard_post') {
                        //$content_view = $CI->trueplook->getViewNumber($v['cms_id'], $table_view);
                        $content_view = $CI->trueplook->getViewNumber($v['cms_id'], $table_view);
                        //_vd($content_view);
                        //$content_view = $v['view_count'] != null?$v['view_count']:$CI->trueplook->getViewNumber($v['cms_id'], $table_view);
                        $content_link = htmlspecialchars($path_link . $v['cms_id']);
                        $file_name = $v['cms_file_name'];
                        if (!file_exists($CI->trueplook->set_media_path_full('image', 'no-year') . $v['cms_file_path'] . '/' . $file_name) or $file_name == '') {

                            $file_name = $v['cms_file_name_s'];
                        }
                    }else {
                        
                        
                        $CI->load->library('TPPY_Utils');
                        
                        //$content_view = $CI->trueplook->getViewNumber($v['cms_id'], $table_view);
                        $content_view = $CI->tppy_utils->ViewNumberGet($v['cms_id'], $table_content);
                        //_vd($content_view);
                        //$content_view = $v['view_count'] != null?$v['view_count']:$CI->trueplook->getViewNumber($v['cms_id'], $table_view);
                        $content_link = htmlspecialchars($path_link . $v['cms_id']);
                        $file_name = $v['cms_file_name'];
                        if (!file_exists($CI->trueplook->set_media_path_full('image', 'no-year') . $v['cms_file_path'] . '/' . $file_name) or $file_name == '') {

                            $file_name = $v['cms_file_name_s'];
                        }
                    }


                    if ($v['mul_thumbnail_file'] <> '') {

                        $file_name = $v['mul_thumbnail_file'];
                        //$file_name = $v['cms_file_name'];
                    }
                    $file_name = $v['cms_file_name'];
                    $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], $file_name);

                    if ($v['mul_type_id'] <> 'v' and $table_content == 'mul_content') {

                        $img_thumbnail = $file_image[$v['mul_type_id']];
                    }
                }
                if ($table_content == 'truelife_api') {

                    $content_title_full = $v['title'];
                    $content_view = number_format($v['views']);
                    $show_date = explode(" ", $v['published_date']);
                    $content_date = $CI->trueplook->data_format('small', 'th', $show_date[0]);

                    $post_by = 'ทีมงานทรูปลูกปัญญา';
                    $content_link = htmlspecialchars($path_link . $v['id']) . '/';
                    $img_thumbnail = 'http://dynamic.tlcdn2.com/api/image/get?h=92&w=128&url=' . $v['thumbnail'];
                    $content_detail = $CI->trueplook->limitText($v['description'], 250);
                } else {

                    if ($v['cms_subject'] <> '') {
                        $content_title_full = $v['cms_subject'];
                    } else {
                        $content_title_full = $v['cms_subject_sub'];
                    }

                    $show_date = explode(" ", $v['add_date']);
                    $content_date = $CI->trueplook->data_format('small', 'th', $show_date[0]);
                    $post_by = $CI->trueplook->get_display_name($v['member_id'], 'link');
                    $content_detail = $CI->trueplook->limitText($v['cms_detail'], 250);
                }
                //-------End Set Data -----//

                if ($k + 1 < $content_limit) {

                    $border_none = "border-bottom:1px dashed #CCC";
                } else {

                    $border_none = "border-bottom:none";
                }

                //Thumbnail constant

                $date = explode(" ", $v['add_date']);
//                list($y, $m, $d) = explode("-", $date[0]);
//
//                //echo ",D=".$d.",m=".$m.",Y=".$y."<br/>";
//                
//                $timestamp = mktime(0, 0, 0, $m, $d, $y);
//                
//                $limit_date = mktime(0, 0, 0, date("m"), date("j"), date("Y")) - (3600 * 24 * 2);

                if ($timestamp > $limit_date) {
                    $new_icon = "<img src=\"" . base_url() . "assets/images/icon/icon_new.gif\" width=\"19\" height=\"9\" border=\"0\" />&nbsp;";
                } else {
                    $new_icon = "";
                }

                if ($content_view > 100) {

                    $hot_icon = "<img src=\"" . base_url() . "assets/images/icon/img_hot_animate.gif\" width=\"19\" height=\"9\" border=\"0\" />";
                } else {

                    $hot_icon = "";
                }






                switch ($type) {

                    case 1:

                        $content_title = $CI->trueplook->limitText($content_title_full, 60);
                        $social = $CI->trueplook->social_share_face($content_link . '/', $content_title_full);

                        //$return_data2['title_show'] = $content_title;
                        //echo $content_title_full;



                        $return_data.='
													  <div style="' . $border_none . '" class="cms_template_content_line_type1">
														  
														  <div class="cms_template_thumbnail template_left_box"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="110" height="80" border="0" /></a></div>
														  <div class="cms_template_content_type1 template_right_box">
														  
															  <div><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a> ' . $new_icon . $hot_icon . '</div>';

                        if ($show_detail <> 'hide') {

                            $return_data.='<div style="font-size:90%">
																		  <div>' . $CI->trueplook->limitText($v['cms_detail_short'], 70) . '</div>
																		 
															</div>
															<div class="cms_template_info_content">
																		  <div style="float:left">' . $content_view . ' views | ' . $content_date . '</div>
																		  <div style="float:right; margin:5px 10px 0px 0px; height:30px; overflow:hidden; width:100px;text-align:right">' . $social . '</div>
																		  <div style="clear:both"></div>
															</div>';
                        } else {


                            $return_data.=' <div class="cms_template_info_content">
																		  <div style="float:left">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																		  <div style="float:right; margin:5px 10px 0px 0px; height:30px; overflow:hidden; width:100px;text-align:right">' . $social . '</div>
																		  <div style="clear:both"></div>
															</div>';
                        }



                        $return_data.=' </div>
														   
														  <div class="clearfix"></div>
														  
													  </div>';

                        break; // case 1

                    case 2 :

                        $content_title = $CI->trueplook->limitText($content_title_full, 60);
                        $img_thumbnail = $CI->trueplook->image_resize(360, 172, $v['cms_file_path'], $file_name);
                        //v2
                        $return_data2[$k]['title_show'] = $content_title;

                        $return_data2[$k]['link_show'] = $content_link;
                        $return_data2[$k]['img_show'] = $img_thumbnail;
                        $return_data2[$k]['date_show'] = $content_date;
                        $return_data2[$k]['date_view'] = $content_view;



                        if (!($k % 2)) {

                            $margin = "template_left_box";
                        } else {

                            $margin = "template_right_box";
                        }
                        $social = $CI->trueplook->social_share_face($content_link . '/', $content_title_full);
                        $return_data.='
													  <div class="cms_template_content_line_type2 ' . $margin . '">
														  
														  <div class="cms_template_thumbnail template_left_box"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="110" height="80" border="0" /></a></div>
														  <div class="cms_template_content_type1 template_right_box">
														  
															  <div><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a> ' . $new_icon . $hot_icon . '</div>';

                        if ($show_detail <> 'hide') {

                            $return_data.='<div style="font-size:90%">
																		  <div>' . $CI->trueplook->limitText($v['cms_detail_short'], 70) . '</div>
																		 
															</div>
															<div class="cms_template_info_content">
																		  <div style="float:left">' . $content_view . ' views | ' . $content_date . '</div>
																		  <div style="float:right; margin:5px 10px 0px 0px; height:30px; overflow:hidden; width:100px;text-align:right">' . $social . '</div>
																		  <div style="clear:both"></div>
															</div>';
                        } else {


                            $return_data.=' <div class="cms_template_info_content">
																		  <div style="float:left">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																		  <div style="float:right; margin:5px 10px 0px 0px; height:30px; overflow:hidden; width:100px;text-align:right">' . $social . '</div>
																		  <div style="clear:both"></div>
															</div>';
                        }

                        $return_data.=' </div>
														  
														  <div class="clearfix"></div>
														  
													  </div>
													 
													  ';

                        if ($k % 2) {

                            $return_data.='<div class="clearfix"></div>';
                        }

                        break; //case 2

                    case 3 :


                        $content_title = $CI->trueplook->limitText($content_title_full, 35);
                        $img_thumbnail = $CI->trueplook->image_resize(750, 360, $v['cms_file_path'], $file_name);
                        $return_data.='
														<div class="cms_template_content_line_type3">
						
															<div class="cms_template_thumbnail"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="110" height="80" border="0" /></a></div>
															<div class="cms_template_content_type3">
																
																<div><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
																<div class="cms_template_info_content">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																
															</div>
															
														
														</div>	';

                        //v2
                        $return_data2[$k]['title_show'] = $content_title_full;
                        if ($v['cms_detail_short'] != "") {
                            $return_data2[$k]['detail_short'] = $v['cms_detail_short'];
                        }
                        //$v['cms_detail_short'];
                        $return_data2[$k]['link_show'] = $content_link;
                        $return_data2[$k]['img_show'] = $img_thumbnail;
                        $return_data2[$k]['date_show'] = $content_date;
                        $return_data2[$k]['date_view'] = number_format($content_view);
                        $return_data2[$k]['post_by'] = $post_by;

                        break; //case 3

                    case 4 :

                        if ($k % 2) {
                            $back = "#FFF";
                        } else {
                            $back = "#f8f8f8";
                        }

                        $content_title = $CI->trueplook->limitText($content_title_full, 35);
                        $type_icon = $CI->trueplook->get_icon_doc($v['cms_file_type']);
                        $img_thumbnail = $CI->trueplook->image_resize(750, 360, $v['cms_file_path'], $file_name);

                        $return_data.='
														
														  <div style="background-color:' . $back . '" class="cms_template_content_line_type4">
															  <div class="cms_template_content_type4 template_left_box"><img src="' . base_url() . 'assets/images/icon/buttet_sqrgrey.gif" width="5" height="5" alt="" style="margin-right:10px" /><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
															  <div class="cms_template_content_icon_type4 template_right_box">' . $type_icon . '</div>
															  <div class="clearfix"></div>
														  </div>
													
														';
                        $return_data2[$k]['title_show'] = $content_title_full;

                        if ($v['cms_detail_short'] != "") {
                            $return_data2[$k]['detail_short'] = $v['cms_detail_short'];
                        }
                        $return_data2[$k]['link_show'] = $content_link;
                        $return_data2[$k]['img_show'] = $img_thumbnail;
                        $return_data2[$k]['date_show'] = $content_date;
                        $return_data2[$k]['date_view'] = $content_view;
                        $return_data2[$k]['post_by'] = $post_by;
                        
                       
                        
                        break;

                    case 5 :
                        if ($k % 2) {
                            $back = "#FFF";
                        } else {
                            $back = "#f8f8f8";
                        }
                        $content_title = $CI->trueplook->limitText($content_title_full, 60);
                        //$level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);


                        $level_icon = $this->getLevelShort($v['cms_level_id']);

//                        if ($level_icon=="") {
//                            $level_icon = $v['cms_level_id'];
//                        }

                        $level_icon_color = $v['cms_level_id'][0];
                        $level_supj_icon = $this->getSubj($v['cms_category_name']);

                        $return_data.='
													<div style="padding:5px 5px; background-color:' . $back . '; height:25px" >
														<div style="width:60%; float:left" class="cms_template_content_type5"><img src="' . base_url() . 'assets/images/icon/buttet_sqrgrey.gif" width="5" height="5" alt="" style="margin-right:10px" /><a href="' . $content_link . '" title="แผนที่ ' . $v['lesson_plan_no'] . ' ' . $v['lesson_sub_subject'] . '" target="' . $target . '">แผนที่ ' . $v['lesson_plan_no'] . ' ' . $CI->trueplook->limitText($v['lesson_sub_subject'], 45) . '</a></div>
														<div style="width:5%; float:left; text-align:center">' . $level_icon . '</div>
														<div style="width:15%; float:left; text-align:center">' . $v['cms_category_name'] . '</div>
														<div style="width:20%; float:left; text-align:right">' . $content_view . ' views</div>
														<div class="clearfix"></div>
													</div>
													';

                        $return_data2[$k]['lesson_plan_no'] = $v['lesson_plan_no'];
                        $return_data2[$k]['lesson_sub_subject'] = $CI->trueplook->limitText($v['lesson_sub_subject'], 45);

                        $return_data2[$k]['link_show'] = $content_link;

                        //$level_icon = base_url() . 'new/assets/images/icon/level/'.$v['cms_level_id'].'.png';
                        $return_data2[$k]['level_icon_color'] = $level_icon_color;
                        $return_data2[$k]['level_icon'] = $level_icon;
                        if ($level_supj_icon == "") {
                            $return_data2[$k]['level_supj_icon'] = "http://static.trueplookpanya.com/trueplookpanya/media/home/icon_subject/8000.png";
                        } else {
                            $return_data2[$k]['level_supj_icon'] = "http://static.trueplookpanya.com/trueplookpanya/media/home/icon_subject/" . $level_supj_icon . ".png";
                        }

                        $return_data2[$k]['date_view'] = $content_view;
                        $return_data2[$k]['cms_category_name'] = $v['cms_category_name'];

                        break;

                    case 6 :
                        if ($k % 2) {
                            $back = "#FFF";
                        } else {
                            $back = "#f8f8f8";
                        }
                        $content_title = $CI->trueplook->limitText($content_title_full, 40);
                        $return_data.='
													<div style="padding:5px 5px; background-color:' . $back . '; height:25px">
														<div style="width:20%; float:left">' . $content_date . '</div>
														<div style="width:45%; float:left" class="cms_template_content_type5"><a href="' . $content_link . '" title="' . $content_title_full . '" target="' . $target . '">' . $content_title . '</a></div>
														<div style="width:25%; float:left; text-align:center">' . $v['add_by'] . '</div>
														
														<div style="width:10%; float:left; text-align:right">' . $content_view . ' views</div>
														<div class="clearfix"></div>
													</div>
													';

                        $return_data2[$k]['title_show'] = $content_title_full;
                        $return_data2[$k]['link_show'] = $content_link;
                        //$return_data2[$k]['img_show'] = $img_thumbnail;
                        $return_data2[$k]['date_show'] = $content_date;
                        $return_data2[$k]['date_view'] = $content_view;
                        $return_data2[$k]['post_by'] = $v['add_by'];
                        break;

                    case 7 :

                        $content_title = $CI->trueplook->limitText($content_title_full, 100);
                        $social = $CI->trueplook->social_share_other($content_link);
                        $type_icon = $CI->trueplook->get_icon_doc($v['cms_file_type']);
                        $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                        if ($table_content == 'truelife_api') {
                            $img_thumbnail = 'http://dynamic.tlcdn2.com/api/image/get?h=96&w=128&url=' . $v['thumbnail'];
                            $content_link = htmlspecialchars($path_link . $v['id']);
                        } else {

                            $img_thumbnail = $CI->trueplook->image_resize(128, 96, $v['cms_file_path'], $file_name);
                            if ($v['mul_type_id'] <> 'v' and $table_content == 'mul_content') {

                                $img_thumbnail = $file_image[$v['mul_type_id']];
                            }
                        }
                        $return_data.='
															
															<div class="cms_list_line_area">
        	
																<div class="cms_list_line_thumbnail">
																	<a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="128" height="96" border="0" /></a>
																</div>
																<div class="cms_list_line_detail">
																	
																	 <div style="font-weight:bold"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
																	 <div style="margin-top:5px;color:#666">' . $content_detail . '</div>
															  		<div class="cms_template_info_content">
																		  <div style="float:left">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																		  <div style="float:right; margin:5px -40px 0px 0px; height:30px; overflow:hidden; width:300px;text-align:right">' . $social . '</div>
																</div>
																	
																</div>
																<div class="cms_list_line_icon">' . $type_icon . '</div>
																<div class="clearfix"></div>
															</div>
															
													';


                        break;

                    case 8:

                        $content_title = $CI->trueplook->limitText($content_title_full, 60);
                        $social = $CI->trueplook->social_share_face($content_link . '/', $content_title_full);
                        $return_data.='
															
															<div class="cms_template_content_line_type8">
                    	
																<div >
																	<div class="template_left_box cms_template_thumbnail_45x45"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="45" height="45" border="0" /></a></div>
																	<div class="cms_template_content_line_type8_title"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a> ' . $new_icon . $hot_icon . '</div>
																	<div style="clear:left"></div>
																</div>
																<div class="cms_template_info_content">
																		  <div style="float:left">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																		  <div style="float:right; margin:5px 10px 0px 0px; height:30px; overflow:hidden; width:100px;text-align:right">' . $social . '</div>
																		  <div style="clear:both"></div>
															</div>
																
															</div>
															
													';

                        break;

                    case 9:

                        $content_title = $CI->trueplook->limitText($content_title_full, 40);
                        $social = $CI->trueplook->social_share_face($content_link . '/', $content_title_full);
                        $return_data.='
													  <div style="' . $border_none . '" class="cms_template_content_line_type9">
														  
														  <div class="cms_template_thumbnail_45x45 template_left_box"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="45" height="45" border="0" /></a></div>
														  <div class="cms_template_content_type9 template_right_box" >
														  
															  <div><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a> ' . $new_icon . $hot_icon . '</div>';

                        if ($show_detail <> 'hide') {

                            $return_data.=' 
															<div style="font-size:90%">
																		  <div>' . $CI->trueplook->limitText($v['cms_detail_short'], 70) . '</div>
																		 
															</div>
															<div class="cms_template_info_content">
																		  <div style="float:left">' . $content_view . ' views | ' . $content_date . '</div>
																		  <div style="float:right; margin:5px 10px 0px 0px; height:30px; overflow:hidden; width:100px;text-align:right">' . $social . '</div>
																		  <div style="clear:both"></div>
															</div>';
                        } else {


                            $return_data.=' <div class="cms_template_info_content">
																		  <div style="float:left">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																		  <div style="float:right; margin:5px 10px 0px 0px; height:30px; overflow:hidden; width:100px;text-align:right">' . $social . '</div>
																		  <div style="clear:both"></div>
															</div>';
                        }


                        $return_data.='</div>
														  
														  <div class="clearfix"></div>
														  
													  </div>';

                        break;

                    case 10 :

                        $content_title = $CI->trueplook->limitText($content_title_full, 100);
                        $social = $CI->trueplook->social_share_other($content_link);
                        $type_icon = $CI->trueplook->get_icon_doc($v['cms_file_type']);
                        $img_thumbnail = $CI->trueplook->image_resize_cut(70, 70, $v['cms_file_path'], $file_name);
                        if ($v['mul_type_id'] <> 'v' and $table_content == 'mul_content') {

                            $img_thumbnail = $file_image[$v['mul_type_id']];
                        }

                        $return_data.='
															
															<div class="cms_list_line_area">
        	
																<div class="cms_list_line_thumbnail_70x70">
																	<a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="70" height="70" border="0" /></a>
																</div>
																<div class="cms_list_line_detail">
																	
																	 <div style="font-weight:bold" class="cms_template_news_link"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
																	 <div style="margin-top:5px;color:#666">' . $content_detail . '</div>
															  		<div class="cms_template_info_content">
																		  <div style="float:left">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																		  <div style="float:right; margin:5px -90px 0px 0px; height:30px; overflow:hidden; width:300px;text-align:right">' . $social . '</div>
																	</div>
																	
																</div>
																<div class="cms_list_line_icon">
																	<div>' . $type_icon . '</div>
																	
																</div>
																<div class="clearfix"></div>
															</div>
															
													';


                        break;

                    case 11:

                        $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                        if ($v['cms_subject'] == '') {
                            $content_title_full = $v['cms_subject_sub'];
                        }


                        $content_title = $CI->trueplook->limitText($content_title_full, 100);
                        $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], $file_name);
                        if ($v['mul_type_id'] <> 'v' and $table_content == 'mul_content') {

                            $img_thumbnail = $file_image[$v['mul_type_id']];
                        }
                        $return_data.='
                            <div class="cms_template_knowledge_vdo_zone_line">
                                <div>
                                    <div class="cms_template_knowledge_vdo_zone_thumb">
                                        <a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="110" height="75" border="0" />
                                    </a>
                                    </div>
                                    <div class="cms_template_knowledge_vdo_zone_title"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="cms_template_info_content">
                                    <div style="float:left">' . $level_icon . '</div>
                                    <div style="float:left; margin-left:5px">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        ';
                        if ($k == 2) {
                            $return_data.='<div style="clear:both"></div>';
                        }

                        break;

                    case 12:
                        $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                        if ($v['cms_subject'] == '') {
                            $content_title_full = $v['cms_subject_sub'];
                        }
                        $content_title = $CI->trueplook->limitText($content_title_full, 25);
                        $return_data.='
													<div style="' . $border_none . '" class="cms_template_knowledge_text_zone_line">
														<div class="cms_template_knowledge_text_zone_icon"><img src="http://www.trueplookpanya.com/new/assets/images/icon/buttet_sqrgrey.gif" alt="" /> </div>
														<div class="cms_template_knowledge_text_zone_title">
															
															<div><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
															<div class="cms_template_info_content" style="margin-top:1px">
																	<div>' . $content_view . ' views | ' . $content_date . '</div>
															</div>
																
														</div>
														<div style="float:right; padding-top:0px">' . $level_icon . '</div>
														<div class="clearfix"></div>
												   </div>
												';


                        break;

                    case 13:

                        $member_thumb = $CI->trueplook->get_member_thumb($v['member_id'], 40, 40);
                        $return_data.='
													<div style="' . $border_none . '" class="cms_template_top_post_line">
														<div class="cms_template_top_post_thumb">' . $member_thumb . '</div>
														<div class="cms_template_top_post_by">' . $post_by . '</div>
														<div class="cms_template_top_post_nums">' . $v['sumcnt'] . ' <span style="font-size:90%">คอนเทนท์</span></div>
														<div class="clearfix"></div>
													</div>
												';

                        break;

                    case 14:
                        $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                        if ($v['cms_subject'] == '') {
                            $content_title_full = $v['cms_subject_sub'];
                        }
                        $content_title = $CI->trueplook->limitText($content_title_full, 100);
                        $return_data.='
													<div style="width:350px;  min-height:30px; float:left; margin-left:10px; margin-bottom:10px">
														<div style="width:128px; height:96px; background-color:#CCC; border:1px solid #CCC; float:left"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="128" height="96" border="0" /></a></div>
														<div style="float:left; width:210px; margin-left:5px">
															
															<div style="max-height:60px; width:205px; overflow:hidden"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
															<div class="cms_template_info_content" style=" padding-top:3px">
																	<div style="float:left">' . $level_icon . '</div>
																	<div style="float:left; margin-left:5px">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																	<div class="clearfix"></div>
															</div>
														
														</div>
													</div>
												';

                        if ($k % 2) {
                            $return_data.=' <div class="clearfix"></div>';
                        }

                        break;

                    case 15:

                        if ($v['cms_subject'] == '') {
                            $content_title_full = $v['cms_subject_sub'];
                        }
                        $content_title = $CI->trueplook->limitText($content_title_full, 100);
                        $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                        $type_icon = $CI->trueplook->get_icon_doc($v['cms_file_type']);
                        $return_data.='
													<div style="padding-left:5px; margin-top:5px; border-bottom:1px dashed #CCC; padding-bottom:5px">
                    	
													  <div style="width:5px; height:5px; float:left; padding-top:12px"><img src="http://www.trueplookpanya.com/new/assets/images/icon/buttet_sqrgrey.gif" width="5" height="5" /></div>
													  <div style="width:335px;float:left; margin-left:5px; padding-top:5px"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>                    
													  <div style="width:18px; height:18px; float:left; margin-left:5px;padding-top:5px">' . $type_icon . '</div>
													  <div style="width:30px; height:30px; float:left; margin-left:12px">' . $level_icon . '</div>
													   <div style="width:100px;float:left; margin-left:5px; padding-top:5px; text-align:right">' . $content_view . ' views</div>
														<div style="width:100px;float:left; margin-left:5px; padding-top:5px; text-align:right">' . $content_date . '</div>
														 <div style="width:100px;float:left; margin-left:5px; padding-top:5px; text-align:right">15 ก.ค. 55</div>
													  <div style="clear:left"></div>
												  </div>
												';

                        break;

                    case 16:

                        $return_data.='
													<div style=" float:left; width:32%;margin:0px 0px 15px 5px"><div style="float:left;padding-top:7px"><img src="' . base_url() . 'assets/images/icon/icon_circle_red.gif" width="5" height="5" alt=""  /></div><div style="float:left;margin-left:5px; width:200px"><a href="' . base_url() . 'knowledge_list/' . $v['cms_level_id'] . '-' . $v['cms_category_id'] . '/update/' . $v['knowledge_context_id'] . '/">' . $v['knowledge_context_name'] . '</a></div><div style="clear:left"></div></div>
												';
                        if (!(($k + 1) % 3)) {

                            $return_data.='<div style="clear:left"></div>';
                        }


                        break;

                    case 17:

                        $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                        if ($v['cms_subject'] == '') {
                            $content_title_full = $v['cms_subject_sub'];
                        }


                        $content_title = $CI->trueplook->limitText($content_title_full, 100);
                        if ($file_name == '') {
                            $file_name = $v['cms_file_name_s'];
                        }
                        $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], $file_name);
                        if ($v['mul_type_id'] <> 'v' and $table_content == 'mul_content') {

                            $img_thumbnail = $file_image[$v['mul_type_id']];
                        }
                        $return_data.='
                                    <div class="cms_template_knowledge_vdo_zone_line" style="border-bottom:1px dashed #ccc; margin:10px auto 0px; padding-bottom:10px">
                                        <div>
                                            <div class="cms_template_knowledge_vdo_zone_thumb">
                                                <a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="110" height="75" border="0" /></a>
                                            </div>
                                            <div class="cms_template_knowledge_vdo_zone_title"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                    <div class="cms_template_info_content">';
                        if ($table_content == 'mul_content') {
                            $return_data.='<div style="float:left">' . $level_icon . '</div>';
                        }
                        $return_data.='<div style="float:left; margin-left:5px">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																	<div class="clearfix"></div>
																</div>
															</div>
															
												';

                        break;

                    case "18":
                        $content_link = htmlspecialchars('http://www.trueplookpanya.com/true/examination_display.php?exam_id=' . $v['cms_id']);
                        $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                        $content_title = $CI->trueplook->limitText($v['cms_subject'], 25);
                        $return_data.='
												
													<div class="campaign_show_line_exam" style="width:200px">
														<div><a href="' . $content_link . '" target="_blank" title="' . $v['cms_subject'] . '">' . $content_title . '</a></div>
														<div>
																<div style="float:left"><a href="' . $content_link . '" target="_blank" title="' . $v['cms_subject'] . '">' . $level_icon . '</a></div>
																<div style="float: left; margin-left:5px">
																	<div>โพส : ' . $content_date . '</div>
																	<div>' . $content_view . ' views</div>
																</div>
																<div style="clear:left"></div>
														</div>
													
													</div>
												';
                        if (!(($k + 1) % $content_limit)) {

                            $return_data.='<div style="clear:left"></div>';
                        }

                        break;

                    case 19:


                        $content_title = $CI->trueplook->limitText($content_title_full, 100);
                        $img_thumbnail = 'http://dynamic.tlcdn1.com/api/image/get?h=80&w=110&url=' . $v['thumbnail'];
                        if ($v['mul_type_id'] <> 'v' and $table_content == 'mul_content') {

                            $img_thumbnail = $file_image[$v['mul_type_id']];
                        }
                        $date = explode(' ', $v['created_date']);
                        $content_date = $CI->trueplook->data_format('full', 'th', $date[0]);

                        $return_data.='
															
															<div class="cms_template_knowledge_vdo_zone_line" style="border-bottom:1px dashed #ccc; margin:10px auto 0px; padding-bottom:10px">
																<div>
																	<div class="cms_template_knowledge_vdo_zone_thumb">
																			<a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="110" height="75" border="0" /></a>
																			
																	</div>
																	<div class="cms_template_knowledge_vdo_zone_title"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
																	<div class="clearfix"></div>
																</div>
																<div class="cms_template_info_content">';
                        $return_data.='<div style="float:left; margin-left:5px">' . $v['views'] . ' views | ' . $content_date . '<br />ทรูปลูกปัญญา</div>
																	<div class="clearfix"></div>
																</div>
															</div>
															
												';

                        break;

                    case 20:

                        $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                        if ($v['cms_subject'] == '') {
                            $content_title_full = $v['cms_subject_sub'];
                        }


                        $content_title = $CI->trueplook->limitText($content_title_full, 70);
                        $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], $file_name);
                        if ($v['mul_type_id'] <> 'v' and $table_content == 'mul_content') {

                            $img_thumbnail = $file_image[$v['mul_type_id']];
                        }
                        $return_data.='
															
															<div class="cms_template_knowledge_vdo_zone_line"  style="width:235px">
																<div>
																	<div class="cms_template_knowledge_vdo_zone_thumb">
																			<a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="110" height="75" border="0" /></a>
																			
																	</div>
																	<div class="cms_template_knowledge_vdo_zone_title"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
																	<div class="clearfix"></div>
																</div>
																<div class="cms_template_info_content">
																	<div style="float:left">' . $level_icon . '</div>
																	<div style="float:left; margin-left:5px">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																	<div class="clearfix"></div>
																</div>
															</div>
															
												';
                        if ($no_line >= 4) {

                            $return_data.='<div style="clear:both"></div></div><div>';
                            $no_line = 1;
                        } else {

                            $no_line++;
                        }

                        break;


                    case 21 :

                        $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                        if ($v['cms_subject'] == '') {
                            $content_title_full = $v['cms_subject_sub'];
                        }


                        $content_title = $CI->trueplook->limitText($content_title_full, 70);
                        $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], $file_name);
                        if ($v['mul_type_id'] <> 'v' and $table_content == 'mul_content') {

                            $img_thumbnail = $file_image[$v['mul_type_id']];
                        }

                        if ($table_content == 'tv') {

                            if ($CI->trueplook->check_exists('image', $v['tv_vdo_path'], substr($v['cms_file_name'], 0, -4) . '.png')) {

                                $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], substr($v['cms_file_name'], 0, -4) . '.png');
                            } else {

                                $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], substr($v['cms_file_name'], 0, -4) . '.jpg');
                            }

                            if (is_null($v['cms_file_name']) or $v['cms_file_name'] == '') {


                                if ($v['upload_type'] == 'youtube') {

                                    $img_thumbnail = 'http://i3.ytimg.com/vi/' . $v['youtube_code'] . '/mqdefault.jpg';
                                } else {

                                    $file_name = $v['tv_vdo_filename'];
                                    if ($CI->trueplook->check_exists('image', $v['tv_vdo_path'], $CI->trueplook->get_image_name($v['tv_vdo_filename'], '320x240'))) {

                                        $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['tv_vdo_path'], $CI->trueplook->get_image_name($v['tv_vdo_filename'], '320x240'));
                                    } else {

                                        $img_thumbnail = 'http://www.trueplookpanya.com/new/cutresize/re/110/74/images/trueplook.png/TV/';
                                    }
                                }
                            }

                            $post_by = 'ทรูปลูกปัญญา';
                            $content_title = $CI->trueplook->limitText($v['tv_name'], 30) . ':' . $content_title = $CI->trueplook->limitText($content_title_full, 30);
                            $content_title_full = 'รายการ ' . $v['tv_name'] . ' : ' . $content_title_full;
                            $content_link = base_url() . 'tv_program_detail/' . $v['tv_id'] . '/' . $v['cms_id'] . '/' . $CI->trueplook->check_format_url($content_title_full) . '/';
                        }
                        $return_data.='
															
															<div class="cms_template_knowledge_vdo_zone_line"  style="width:238px">
																<div>
																	<div class="cms_template_knowledge_vdo_zone_thumb">
																			<a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="110" height="75" border="0" /></a>
																			
																	</div>
																	<div class="cms_template_knowledge_vdo_zone_title"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
																	<div class="clearfix"></div>
																</div>
																<div class="cms_template_info_content">
																	<div style="float:left; margin-left:5px">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																	<div class="clearfix"></div>
																</div>
															</div>
															
												';
                        if ($no_line >= 4) {

                            $return_data.='<div style="clear:both"></div></div><div>';
                            $no_line = 1;
                        } else {

                            $no_line++;
                        }

                        break; //case 21


                    case 22:

                        $content_title = $CI->trueplook->limitText($content_title_full, 60);
                        $social = $CI->trueplook->social_share_face($content_link . '/', $content_title_full);

                        if ($k < 5) {
                            $return_data.='
													  <div style="' . $border_none . ' ;width:240px; margin-bottom:5px; padding:5px 0px">

															<div style="float:left" class="cms_template_thumbnail_45x45"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="45" height="45" border="0" /></a></div>
															<div style="float:left; margin-left:5px; width:180px" class="cms-news-topten">
																<div><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a> ' . $new_icon . $hot_icon . '</div>
																	  <div>' . $content_view . ' views</div>

															</div>
																									  
														  <div class="clearfix"></div>
														  
													  </div>';
                        } else {

                            if ($k % 2) {
                                $back = "#f3fff3";
                            } else {
                                $back = "#e1f8de";
                            }
                            $return_data.='<div style="width:240px;background-color:' . $back . '" class="cms_template_content_line_type4">
															  <div class="cms_template_content_type4 template_left_box" style="margin-left:5px; padding-left:0px;width:240px">
																	  <div style="float:left; margin-top:7px"><img src="' . base_url() . 'assets/images/icon/buttet_sqrgrey.gif" width="5" height="5" alt=""/></div>
																	  <div style="float:left; margin-left:10px; width:220px" class="cms-news-topten"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
																	   <div class="clearfix"></div>
															   </div>
															   <div class="clearfix"></div>
														  </div>';
                        }

                        break; //case 22


                    case 23 :

                        if (in_array($k, array(1, 2, 5, 6, 9, 10, 13, 14, 17, 18))) {
                            $back = "#FFF";
                        } else {
                            $back = "#f8f8f8";
                        }

                        $content_title = $CI->trueplook->limitText($content_title_full, 35);
                        $type_icon = $CI->trueplook->get_icon_doc($v['cms_file_type']);
                        $return_data.='
														
														  <div style="background-color:' . $back . '" class="cms_template_content_line_type4_half">
															  <div class="cms_template_content_type4 template_left_box"><img src="' . base_url() . 'assets/images/icon/buttet_sqrgrey.gif" width="5" height="5" alt="" style="margin-right:10px" /><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
															  <div class="cms_template_getViewNumber_icon_type4 template_right_box">' . $type_icon . '</div>
															  <div class="clearfix"></div>
														  </div>
													
														';
                        break;

                    case 24:

                        $content_title = $CI->trueplook->limitText($content_title_full, 40);
                        $social = $CI->trueplook->social_share_face($content_link . '/', $content_title_full);
                        $return_data.='
													  <div style="border-bottom:1px dashed #CCC" class="cms_template_content_line_type9_half">
														  
														  <div class="cms_template_thumbnail_45x45 template_left_box"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="45" height="45" border="0" /></a></div>
														  <div class="cms_template_content_type9 template_right_box" >
														  
															  <div><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a> ' . $new_icon . $hot_icon . '</div>';

                        if ($show_detail <> 'hide') {

                            $return_data.=' 
															<div style="font-size:90%">
																		  <div>' . $CI->trueplook->limitText($v['cms_detail_short'], 70) . '</div>
																		 
															</div>
															<div class="cms_template_info_content">
																		  <div style="float:left">' . $content_view . ' views | ' . $content_date . '</div>
																		  <div style="float:right; margin:5px 10px 0px 0px; height:30px; overflow:hidden; width:100px;text-align:right">' . $social . '</div>
																		  <div style="clear:both"></div>
															</div>';
                        } else {


                            $return_data.=' <div class="cms_template_info_content">
																		  <div style="float:left">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																		  <div style="float:right; margin:5px 10px 0px 0px; height:30px; overflow:hidden; width:100px;text-align:right">' . $social . '</div>
																		  <div style="clear:both"></div>
															</div>';
                        }


                        $return_data.='</div>
														  
														  <div class="clearfix"></div>
														  
													  </div>';

                        break;
                    case 25:
                        $content_title = $CI->trueplook->limitText($content_title_full, 60);
                        $img_thumbnail = $CI->trueplook->image_resize(370, 370, $v['cms_file_path'], $file_name);
                        //v2
                        $return_data2[$k]['title_show'] = $content_title;
                        $return_data2[$k]['link_show'] = $content_link;
                        $return_data2[$k]['img_show'] = $img_thumbnail;
                        $return_data2[$k]['date_show'] = $content_date;
                        $return_data2[$k]['date_view'] = $content_view;

                        break;

                    case 26:
                        $content_title = $CI->trueplook->limitText($content_title_full, 100);
                        //$img_thumbnail = $CI->trueplook->image_resize(370, 370, $v['cms_file_path'], $file_name);
                        //v2
                        $return_data2[$k]['title_show'] = $content_title;
                        $return_data2[$k]['link_show'] = $content_link;
                        $return_data2[$k]['date_show'] = $content_date;
                        $return_data2[$k]['date_view'] = $content_view;

                        break;
                }
            }// Foreach

            $return_data.='<div style="clear:both"></div></div>';
        } else {

            $return_data.='<div class="cms_template_empty_data">ไม่มีข้อมูล</div>';
        } // IF Count

        return $return_data2;
    }

//get_cms_content

    public function nanasara_top_menu() {

        $data['top_menu_header'] = $this->get_text_menu_header();
        $data['main_menu_template'] = 'นอกห้องเรียน';
        $data['sub_menu_template'] = '<a href="' . base_url() . 'general_knowledge/" target="_self" title="นานาสาระ">นานาสาระ</a>';
        $data['bg'] = 'bg_general_knowledge.jpg';
        $data['repeat'] = 'no-repeat';
        $data['bg_size'] = 'background-size:100%';
        $list_menu_top_line2 = array('Rising Star' => 'http://www.trueplookpanya.com/true/star_project.php', 'ความรู้ทั่วไป' => base_url() . 'cms_list/general_knowledge/41/', 'กิจกรรมนอกห้องเรียน' => base_url() . 'knowledge_list/all-9300/', 'สื่อน่ารู้' => base_url() . 'knowledge_list/all-9200/', 'วันนี้เมื่อวันวาน' => 'http://www.trueplookpanya.com/true/flashback.php', 'พิพิธภัณฑ์โดนใจ' => 'http://www.trueplookpanya.com/true/museum.php');
        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line2);
        $list_menu_top_line1 = array('บุคคลสำคัญ' => 'http://www.trueplookpanya.com/true/world_famous_people.php', 'คลิปเด็ดแปลไทย' => base_url() . 'clipded/all/0000', 'เคล็ดลับคู่ครัว' => base_url() . 'cms_list/general_knowledge/43/', 'นวัตกรรมเทคโนโลยี​' => base_url() . 'cms_list/general_knowledge/51/', 'สาระน่ารู้เกี่ยวกับคอมพิวเตอร์​' => base_url() . 'cms_list/general_knowledge/53/');
        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line1);

        return $data;
    }

    public function guidance_top_menu($cate_id = '') {

        $data['top_menu_header'] = $this->get_text_menu_header();
        $data['bg'] = 'bg_guidance.jpg';
        $data['repeat'] = 'repeat-x';
        $data['main_menu_template'] = 'นอกห้องเรียน';
        $data['bg_size'] = 'background-size:100%';
        $data['sub_menu_template'] = '<a href="' . base_url() . 'guidance/" target="_self" title="แนะแนว">แนะแนว</a>';
        $list_menu_top_line2 = array('ข่าวการศึกษา' => base_url() . 'cms_list/news/95/', 'ข่าวสอบตรง / Admissions' => base_url() . 'guidance/admission/', 'รู้ก่อนเรียน​' => 'http://www.trueplookpanya.com/true/guidance_sitemap.php');
        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line2, '', $cate_id);
        $list_menu_top_line1 = array('Webboard แนะแนว​' => 'http://www.trueplookpanya.com/true/webboard_list.php?cateid=13', 'คนต้นแบบ' => base_url() . 'cms_list/guidance/93/', 'บทความน่าอ่าน' => base_url() . 'cms_list/guidance/94/', 'พี่แนะน้อง' => base_url() . 'cms_list/guidance/92/');
        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line1, '', $cate_id);


        return $data;
    }

    public function knowledge_top_menu($id = null) {

        $data['top_menu_header'] = $this->get_text_menu_header();
        if ($id == '') {
            $data['bg'] = 'bg_knowledge_main.jpg';
        } else {
            $data['bg'] = 'bg_' . $id . '.jpg';
        }
        $data['repeat'] = 'repeat-x';
        $data['main_menu_template'] = 'ในห้องเรียน';
        $data['bg_size'] = 'background-size:100%';
        $data['sub_menu_template'] = '<a href="' . base_url() . 'knowledge/" target="_self" title="คลังความรู้">คลังความรู้</a>';
        //$list_menu_top_line1=array('ข่าวการศึกษา'=>'link','ข่าวสอบตรง / Admissions'=>'gggg','คนต้นแบบ'=>'kkkk','บทความแนะแนว'=>'ggg','พี่แนะน้อง'=>'kkkk','รู้ก่อนเรียน'=>'ggg');
        //$data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line1);

        return $data;
    }

    public function entertainment_top_menu() {

        $data['top_menu_header'] = $this->get_text_menu_header();
        $data['main_menu_template'] = 'นอกห้องเรียน';
        $data['sub_menu_template'] = '<a href="' . base_url() . 'entertainment/" target="_self" title="บันเทิง">บันเทิง</a>';
        $data['bg'] = 'bg_entertainment.jpg';
        $data['repeat'] = 'no-repeat';
        $data['bg_size'] = 'background-size:100%';
//        $list_menu_top_line2 = array('แหวกแนว' => base_url() . 'cms_list/entertainment/แหวกแนว/', 'เชิงศิลป์' => base_url() . 'cms_list/entertainment/เชิงศิลป์/', 'ชิลล์เอาท์' => base_url() . 'cms_list/entertainment/ชิลล์เอาท์/', 'วาไรตี้ท่องเที่ยว' => base_url() . 'cms_list/entertainment/31/', 'นิทานสอนใจ' => base_url() . 'cms_list/entertainment/62/', 'เกมมหาสนุก' => base_url() . 'cms_list/entertainment/61/', 'ภาพยนตร์' => base_url() . 'cms_list/entertainment/movie/');
        $list_menu_top_line2 = array('วาไรตี้ท่องเที่ยว' => base_url() . 'cms_list/entertainment/31/', 'นิทานสอนใจ' => base_url() . 'cms_list/entertainment/62/', 'เกมมหาสนุก' => base_url() . 'cms_list/entertainment/61/', 'ภาพยนตร์' => base_url() . 'cms_list/entertainment/movie/');
        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line2);
//        $list_menu_top_line1 = array('เที่ยวผ่านเลนส์' => base_url() . 'cms_list/entertainment/เที่ยวผ่านเลนส์/', 'สูตรเด็ด เคล็ดลับ​' => base_url() . 'cms_list/entertainment/food/', 'เจ้าถิ่นนำทาง' => base_url() . 'cms_list/entertainment/เจ้าถิ่นนำทาง/', 'รักษ์โลก' => base_url() . 'cms_list/entertainment/รักษ์โลก/', 'รักษ์สุขภาพ' => base_url() . 'cms_list/entertainment/รักษ์สุขภาพ/', 'QUIZ อ่านใจทายนิสัย' => 'http://www.trueplookpanya.com/game/quiz');
        $list_menu_top_line1 = array('QUIZ อ่านใจทายนิสัย' => 'http://www.trueplookpanya.com/game/quiz');
        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line1);

        return $data;
    }

    public function teacher_top_menu($cate_id = '') {

        $data['top_menu_header'] = $this->get_text_menu_header();
        $data['main_menu_template'] = 'นอกห้องเรียน';
        $data['sub_menu_template'] = '<a href="' . base_url() . 'teacher/" target="_self" title="มุมคุณครู">มุมคุณครู</a>';
        $data['bg'] = 'bg_teacher.jpg';
        $data['repeat'] = 'no-repeat';
        $data['bg_size'] = 'background-size:100%';

//        $list_menu_top_line2 = array('ครูต้นแบบ' => base_url() . 'cms_list/teacher/25/', 'เทคนิคการสอน' => base_url() . 'cms_list/teacher/24/', 'บทความวิชาการ/มาตรฐานการศึกษา' => base_url() . 'cms_list/teacher/23/', 'ข่าวแวดวงครู' => base_url() . 'cms_list/teacher/21/');
//        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line2, '', $cate_id);
//        $list_menu_top_line1 = array('แผนการสอน' => 'http://www.trueplookpanya.com/true/lesson_plan_list.php', 'ผลงานวิชาการและผลงานวิจัย' => 'http://www.trueplookpanya.com/true/teacher_portfolio_list.php', 'กฏหมายสำหรับครู' => base_url() . 'cms_list/teacher/26/');
//        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line1, '', $cate_id);
//       
        $dataT;
        $list_menu_top_line2 = array('ครูต้นแบบ' => base_url() . 'new/cms_list/teacher/25/', 'เทคนิคการสอน' => base_url() . 'new/cms_list/teacher/24/', 'ข่าวแวดวงครู' => base_url() . 'new/cms_list/teacher/21/');
        $dataT[0] = $this->get_submenu_header_list($list_menu_top_line2, '', $cate_id);
        $list_menu_top_line1 = array('แผนการสอน' => 'http://www.trueplookpanya.com/true/lesson_plan_list.php', 'กฏหมายสำหรับครู' => base_url() . 'new/cms_list/teacher/26/', 'ผลงานวิชาการและผลงานวิจัย' => 'http://www.trueplookpanya.com/true/teacher_portfolio_list.php', 'บทความวิชาการ/มาตรฐานการศึกษา' => base_url() . 'new/cms_list/teacher/23/');
        $dataT[1] = $this->get_submenu_header_list($list_menu_top_line1, '', $cate_id);


        $sumCount = 0;
        foreach ($dataT as $values) {

            foreach ($values as $valueIn) {
                $data['list_menu_top'][$sumCount]['title'] = $valueIn['title'];
                $data['list_menu_top'][$sumCount]['title_link'] = $valueIn['title_link'];
                $sumCount++;
            }
        }

//        echo '<pre>';
//        print_r($data['list_menu_top']);
//        echo '</pre>';
        return $data;
    }

    public function news_top_menu($cate_id = '') {

        $data['top_menu_header'] = $this->get_text_menu_header();
        $data['bg'] = 'bg_news.jpg';
        $data['repeat'] = 'repeat-x';
        $data['bg_size'] = 'background-size:100%';
        $data['main_menu_template'] = '<a href="' . base_url() . 'news/" target="_self" title="ข่าว">ข่าว</a>';
        $data['sub_menu_template'] = 'no';
        $list_menu_top_line2 = array('ข่าวทรูปลูกปัญญา' => base_url() . 'cms_list/news/141/', 'ข่าวธรรมะ' => base_url() . 'cms_list/news/86/', 'ข่าวกิจกรรม/ค่าย' => base_url() . 'cms_list/news/69/', 'เรื่องฮิตติดกระแส' => base_url() . 'cms_list/news/139/');
        $list_menu_top_line1 = array('ข่าวครู' => base_url() . 'cms_list/teacher/21/', 'ข่าวทุน' => base_url() . 'cms_list/news/136/', 'ข่าวสอบตรง / Admissions' => base_url() . 'guidance/admission/', 'ข่าวการศึกษา' => base_url() . 'cms_list/news/95/');
        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line1, '', $cate_id);
        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line2, '', $cate_id);


        return $data;
    }

    public function english_top_menu() {

        $data['top_menu_header'] = $this->get_text_menu_header();
        $data['main_menu_template'] = 'นอกห้องเรียน';
        $data['sub_menu_template'] = '<a href="' . base_url() . 'english/" target="_self" title="ฝึกภาษาอังกฤษกับทรูปลูกปัญญา">ฝึกภาษาอังกฤษกับทรูปลูกปัญญา</a>';
        $data['bg'] = 'bg_teacher.jpg';
        $data['repeat'] = 'no-repeat';
        $data['bg_size'] = 'background-size:100%';
        $list_menu_top_line2 = array('Speaking' => base_url() . 'cms_list/english/25/', 'Writing' => base_url() . 'cms_list/english/24/', 'Grammar' => base_url() . 'cms_list/english/23/', 'Volcabulary' => base_url() . 'cms_list/english/21/');
        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line2);
        $list_menu_top_line1 = array('ติวเข้ม-ติวสอบ' => base_url() . 'cms_list/english/', 'English for work' => base_url() . 'cms_list/english/', 'Listening' => base_url() . 'cms_list/english/26/');
        $data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line1);

        return $data;
    }

    public function youtube_top_menu($id = null) {

        $data['top_menu_header'] = $this->get_text_menu_header();
        if ($id == '') {
            $data['bg'] = 'bg_knowledge_main.jpg';
        } else {
            $data['bg'] = 'bg_' . $id . '.jpg';
        }
        $data['repeat'] = 'repeat-x';
        $data['main_menu_template'] = 'คลังความรู้';
        $data['bg_size'] = 'background-size:100%';
        $data['sub_menu_template'] = '<a href="' . base_url() . 'clipded/" target="_self" title="คลิปเด็ด แปลไทย">คลิปเด็ด แปลไทย</a>';
        $data['cms_arrow'] = 'cms_green_arrow.png';
        //$list_menu_top_line1=array('ข่าวการศึกษา'=>'link','ข่าวสอบตรง / Admissions'=>'gggg','คนต้นแบบ'=>'kkkk','บทความแนะแนว'=>'ggg','พี่แนะน้อง'=>'kkkk','รู้ก่อนเรียน'=>'ggg');
        //$data['list_menu_top'].=$this->get_submenu_header_list($list_menu_top_line1);

        return $data;
    }

    public function nanasara_right_menu() {

        $CI = & get_instance();
        $CI->load->library('memcache');
        $CI->load->model('nanasara_model', 'nana_db');
        $CI->load->library('trueplook');

        $data['yom_header'] = 'วันนี้เมื่อวันวาน';
        $month_array = $CI->trueplook->month_th;
        $data['yom_date'] = 'วันที่ ' . date('d') . ' ' . $month_array[date('n')];
        $now = date('Y-m-d');
        $curr_date = substr($now, 5, 5);

        $result_yom = $CI->nana_db->_nanasara_query_db('yom', $curr_date);
        $data['yom_img'] = $CI->trueplook->image_resize(200, 150, 'MEMO/', $result_yom[0]['memo_img']);
        $data['yom_name'] = $result_yom[0]['memo_topic'];
        $data['yom_detail'] = $CI->trueplook->limitText(strip_tags($result_yom[0]['memo_desc']), 200);
        $data['yom_viewall'] = 'http://www.trueplookpanya.com/true/flashback.php';


        $data['banner_relate'] = $CI->trueplook->show_banner(14);

        return $data;
    }

    public function teacher_right_menu() {

        $CI = & get_instance();
        $CI->load->library('trueplook');
        $data['banner_relate'] = $CI->trueplook->show_banner(14);

        return $data;
    }

    public function guidance_right_menu() {

        $CI = & get_instance();
        $CI->load->library('trueplook');
        $data['banner_relate'] = $CI->trueplook->show_banner(14);

        return $data;
    }

    public function entertainment_right_menu() {

        $CI = & get_instance();
        $CI->load->library('trueplook');
        $data['banner_relate'] = $CI->trueplook->show_banner(14);

        return $data;
    }

    public function news_right_menu() {

        $CI = & get_instance();
        $CI->load->library('trueplook');
        $data['banner_relate'] = $CI->trueplook->show_banner(14);

        return $data;
    }

    public function get_level_box_highschool($type = 'all', $id = null) {

        $CI = & get_instance();
        $CI->load->library('trueplook');
        $teenage_category = array('1000', '2000', '3000', '4000', '5000', '6000', '7000', '8000');

        $return = '
					
					 <div class="level_button_line_header">
                	<div class="level_button_line_header_1">
                    	<div class="level_button_line_header_img_left"><img src="' . base_url() . 'assets/images/bar/bar_knowledge_left.png" width="24" height="39" alt=""  /></div>
                        <div class="level_button_line_header_img_center">สาระการเรียนรู้ระดับประถมศึกษาและมัธยมศึกษา</div>
                        <div class="level_button_line_header_img_right"><img src="' . base_url() . 'assets/images/bar/bar_knowledge_right.png" width="26" height="36" alt=""  /></div>
                    </div>
                </div>
                
                <div style="border:none; width:740px; margin-top:10px">
                ';
        foreach ($teenage_category as $k => $v) {
            if ($k == 3 or $k == 7) {
                $margin_right = '0px';
            } else {
                $margin_right = '10px';
            }

            if ($type == 'all') {
                $return .='
						  <div style="margin-right:' . $margin_right . '" id="button_knowledge_link_' . $v . '" class="button_knowledge_link" onclick="location.href=\'' . base_url() . 'knowledge_list/all-' . $v . '\' ">
						  
							  <div class="button_knowledge_link_thumb"><img src="' . base_url() . 'assets/images/icon/knowledge/menu_knowledge_icon_' . $v . '.png" width="40" height="40" border="0"  /></div>
							  <div class="button_knowledge_link_text">' . $CI->trueplook->get_category_name('knowledge', $v) . '</div>
							  <div class="clearfix"></div>
							  
						  </div>
						 ';
            } else if ($type == 'list') {

                $return .='
						  <div style="margin-right:' . $margin_right . '" id="button_knowledge_link_' . $v . '" class="button_knowledge_link">
						  
							  <div class="button_knowledge_link_thumb"><a href="' . base_url() . 'knowledge_list/all-' . $v . '/" title="วิชา' . $CI->trueplook->get_category_name('knowledge', $v) . ' "><img src="' . base_url() . 'assets/images/icon/knowledge/menu_knowledge_icon_' . $v . '.png" width="40" height="40" border="0"  /></a></div>
							  <div class="button_knowledge_link_text"><a href="' . base_url() . 'knowledge_list/all-' . $v . '/" title="วิชา' . $CI->trueplook->get_category_name('knowledge', $v) . ' ">' . $CI->trueplook->get_category_name('knowledge', $v) . '</a></div>
							  <div class="clearfix"></div>
							  
						  </div>
						 ';
            } else if ($type == 'examination_all') {

                $return .='
						  <div style="margin-right:' . $margin_right . '" id="button_knowledge_link_' . $v . '" class="button_knowledge_link">
						  
							  <a href= "' . base_url() . 'examination/' . $id . '/' . $v . '"><div class="button_knowledge_link_thumb"><img src="' . base_url() . 'assets/images/icon/knowledge/menu_knowledge_icon_' . $v . '.png" width="40" height="40" border="0"  /></div>
							  <div class="button_knowledge_link_text">' . $CI->trueplook->get_category_name('knowledge', $v) . '</div>
							  <div class="clearfix"></div>
							  </a>
							  
						  </div>
						 ';
            }
        }

        $return .='
                	<div class="clearfix"></div>
                </div>';

        return $return;
    }

    public function get_level_box_kindergarten($type = null, $id = null) {
        $CI = & get_instance();
        $CI->load->library('trueplook');
        $child_category = array('0100', '0300', '0500', '0700');
        //$child_category_name=array('ความรู้เกี่ยวกับตัวเด็ก','บุคคลและสถานที่แวดล้อม','ธรรมชาติรอบตัว','สิ่งต่างๆ รอบตัวเด็ก');
        $return = '
				 <div class="level_button_line_header">
                	<div class="level_button_line_header_1">
                    	<div class="level_button_line_header_img_left"><img src="' . base_url() . 'assets/images/bar/bar_knowledge_left.png" width="24" height="39" alt=""  /></div>
                        <div class="level_button_line_header_img_center">สาระการเรียนรู้ระดับปฐมวัย</div>
                        <div class="level_button_line_header_img_right"><img src="' . base_url() . 'assets/images/bar/bar_knowledge_right.png" width="26" height="36" alt=""  /></div>
                    </div>
                </div>
                
                <div  id="young_level_button_line">
                	';
        foreach ($child_category as $k => $v) {
            if ($k == 0) {
                $margin_left = '0px';
            } else {
                $margin_left = '8px';
            }
            switch ($type) {

                case "knowledge":
                    $return.='
									 <div style="background:url(' . base_url() . 'assets/images/icon/knowledge/menu_knowledge_' . $v . '.png) no-repeat;margin-left:' . $margin_left . '" id="cms_template_bar_knowledge_text_main_' . $v . '" class="cms_template_knowledge_young_button" onclick="location.href=\'' . base_url() . 'knowledge_list/all-' . $v . '/\' ">
                    					<div  id="cms_template_bar_knowledge_text_' . $v . '" class="cms_template_bar_alpha">' . $CI->trueplook->get_category_name('knowledge', $v) . '</div>
									 </div>';

                    break;

                case "examination":
                    $return.='
								
									<div style="background:url(' . base_url() . 'assets/images/icon/knowledge/menu_knowledge_' . $v . '.png) no-repeat;margin-left:' . $margin_left . '" id="cms_template_bar_knowledge_text_main_' . $v . '" class="cms_template_knowledge_young_button" onclick="location.href=\'' . base_url() . 'examination/' . $id . '/' . $v . '/\' ">
										<div id="cms_template_bar_knowledge_text_' . $v . '"class="cms_template_bar_alpha">' . $CI->trueplook->get_category_name('knowledge', $v) . '
										</div>
									</div>';
                    break;

                default:
                    $return.='
									 <div style="background:url(' . base_url() . 'assets/images/icon/knowledge/menu_knowledge_' . $v . '.png) no-repeat;margin-left:' . $margin_left . '" id="cms_template_bar_knowledge_text_main_' . $v . '" class="cms_template_knowledge_young_button" onclick="location.href=\'' . base_url() . 'knowledge_list/all-' . $v . '/\' ">
                    					<div  id="cms_template_bar_knowledge_text_' . $v . '" class="cms_template_bar_alpha">' . $CI->trueplook->get_category_name('knowledge', $v) . '</div>
									 </div>';
            }
        }

        $return.='<div class="clearfix"></div>
                    
                </div>
				';

        return $return;
    }

    public function get_level_box($type = null, $id = null, $order = null, $context = null, $level = 'all') {

        $icon_1 = array('01', '02', '03');
        $icon_2 = array('11', '12', '13', '21', '22', '23');
        $icon_3 = array('31', '32', '33', '41', '42', '43');

        $return = '
				<div>';



        if ($id >= 1000 and $id <= 8000) {

            $return.='<div style="float:right; padding:5px; background-color: #C9F; margin-left:10px" class="border_radius">';



            foreach ($icon_3 as $k => $v) {

                if ($k <> 0) {
                    $margin = '5px';
                } else {
                    $margin = '0px';
                }

                switch ($type) {

                    case "knowledge":

                        $link = base_url() . 'knowledge_list/' . $v . '-' . $id . '/' . $order . '/';

                        break;

                    case "examination":

                        //$link=base_url().'knowledge_list/'.$v.'-'.$id.'/'.$order.'/'.$context.'/';

                        break;
                }

                if ($v == $level) {
                    $or = '_ro';
                    $return.='
						<div style="float:left;margin-left:' . $margin . '"><a href="' . $link . '" title="" id="' . $v . '" name="' . $id . '" class="show_knowledge_context"><img src="' . base_url() . 'assets/images/icon/level/over/' . $v . $or . '.png" width="40" height="40" alt="" border="0" /></a></div>
					';
                } else {
                    $or = '';
                    $return.='
						<div style="float:left;margin-left:' . $margin . '"><a href="' . $link . '" title="" id="' . $v . '" name="' . $id . '" class="show_knowledge_context"><img src="' . base_url() . 'assets/images/icon/level/over/' . $v . $or . '.png" width="40" height="40" alt="" border="0" onmouseover="$(this).attr(\'src\',\'' . base_url() . 'assets/images/icon/level/over/' . $v . '_ro.png\')" onmouseout="$(this).attr(\'src\',\'' . base_url() . 'assets/images/icon/level/over/' . $v . '.png\')" /></a></div>
					';
                }
            }

            $return.='<div style="clear:left"></div></div>
					<div style="float:right; padding:5px; background-color:#0CC ; margin-left:10px" class="border_radius">';



            foreach ($icon_2 as $k => $v) {

                if ($k <> 0) {
                    $margin = '5px';
                } else {
                    $margin = '0px';
                }

                switch ($type) {

                    case "knowledge":

                        $link = base_url() . 'knowledge_list/' . $v . '-' . $id . '/' . $order . '/';

                        break;

                    case "examination":

                        break;
                }

                if ($v == $level) {
                    $or = '_ro';
                    $return.='
						<div style="float:left;margin-left:' . $margin . '"><a href="' . $link . '" title="" id="' . $v . '" name="' . $id . '" class="show_knowledge_context"><img src="' . base_url() . 'assets/images/icon/level/over/' . $v . $or . '.png" width="40" height="40" alt="" border="0" /></a></div>
					';
                } else {
                    $or = '';
                    $return.='
						<div style="float:left;margin-left:' . $margin . '"><a href="' . $link . '" title="" id="' . $v . '" name="' . $id . '" class="show_knowledge_context"><img src="' . base_url() . 'assets/images/icon/level/over/' . $v . $or . '.png" width="40" height="40" alt="" border="0" onmouseover="$(this).attr(\'src\',\'' . base_url() . 'assets/images/icon/level/over/' . $v . '_ro.png\')" onmouseout="$(this).attr(\'src\',\'' . base_url() . 'assets/images/icon/level/over/' . $v . '.png\')" /></a></div>
					';
                }
            }

            $return.='<div style="clear:left"></div></div>';
        }

        if ($id == '0100' or $id == '0300' or $id == '0500' or $id == '0700') {
            $return.='<div style="float:right; padding:5px; background-color: #FC0" class="border_radius">';

            foreach ($icon_1 as $k => $v) {

                if ($k <> 0) {
                    $margin = '5px';
                } else {
                    $margin = '0px';
                }

                switch ($type) {

                    case "knowledge":

                        $link = base_url() . 'knowledge_list/' . $v . '-' . $id . '/' . $order . '/';

                        break;

                    case "examination":

                        break;
                }

                if ($v == $level) {
                    $or = '_ro';
                } else {
                    $or = '';
                }

                if ($v == $level) {
                    $or = '_ro';
                    $return.='
						<div style="float:left;margin-left:' . $margin . '"><a href="' . $link . '" title="" id="' . $v . '" name="' . $id . '" class="show_knowledge_context"><img src="' . base_url() . 'assets/images/icon/level/over/' . $v . $or . '.png" width="40" height="40" alt="" border="0" /></a></div>
					';
                } else {
                    $or = '';
                    $return.='
						<div style="float:left;margin-left:' . $margin . '"><a href="' . $link . '" title="" id="' . $v . '" name="' . $id . '" class="show_knowledge_context"><img src="' . base_url() . 'assets/images/icon/level/over/' . $v . $or . '.png" width="40" height="40" alt="" border="0" onmouseover="$(this).attr(\'src\',\'' . base_url() . 'assets/images/icon/level/over/' . $v . '_ro.png\')" onmouseout="$(this).attr(\'src\',\'' . base_url() . 'assets/images/icon/level/over/' . $v . '.png\')" /></a></div>
					';
                }
            }

            $return .='<div style="clear:left"></div></div>';
        }


        $return.='<div style="clear:right"></div></div>';

        return $return;
    }

    public function get_level_box_examination($id) {

        $icon_1 = array('01', '02', '03');
        $icon_2 = array('11', '12', '13', '21', '22', '23');
        $icon_3 = array('31', '32', '33', '41', '42', '43');

        if ($id >= 1000 and $id <= 8000) {
            $margin_right = "100px";
        } else if ($id < 1000 && $id != null) {
            $margin_right = "300px";
        } else {
            $margin_right = "15px";
        }

        $return = '<div style="margin:10px ' . $margin_right . ' 20px 0px; text-align:center">';

        #============== มัธยม =================#
        if ($id != '0100' and $id !== '0300' and $id != '0500' and $id != '0700') {
            $return.='<div style="float:right; padding:5px; background-color: #C9F; margin-left:10px" class="border_radius">';

            foreach ($icon_3 as $k => $v) {

                if ($k <> 0) {
                    $margin = '5px';
                } else {
                    $margin = '0px';
                }

                $link = (empty($id)) ? base_url() . "examination/" . $v . "" : base_url() . "examination/" . $v . "/" . $id . "";
                $return.='
								<div style="float:left;margin-left:' . $margin . '"><a href="' . $link . '" title="" id="' . $v . '" name="' . $id . '" class="show_knowledge_context"><img src="' . base_url() . 'assets/images/icon/level/over/' . $v . '.png" width="40" height="40" alt="" border="0" /></a></div>
							';
            }

            $return.='<div style="clear:left"></div></div>';


            #============== ประถม =================#

            $return .='<div style="float:right; padding:5px; background-color:#0CC ; margin-left:10px" class="border_radius">';

            foreach ($icon_2 as $k => $v) {

                if ($k <> 0) {
                    $margin = '5px';
                } else {
                    $margin = '0px';
                }

                $link = (empty($id)) ? base_url() . "examination/" . $v . "" : base_url() . "examination/" . $v . "/" . $id . "";
                $return.='
								<div style="float:left;margin-left:' . $margin . '"><a href="' . $link . '" title="" id="' . $v . '" name="' . $id . '" class="show_knowledge_context" ><img src="' . base_url() . 'assets/images/icon/level/over/' . $v . '.png" width="40" height="40" alt="" border="0" /></a></div>';
            }

            $return.='<div style="clear:left"></div></div>';
        }

        #============== ประฐมวัย =================#
        if ($id == '' or $id == '0100' or $id == '0300' or $id == '0500' or $id == '0700') {
            $return.='<div style="float:right; padding:5px; background-color: #FC0" class="border_radius">';

            foreach ($icon_1 as $k => $v) {

                if ($k <> 0) {
                    $margin = '5px';
                } else {
                    $margin = '0px';
                }

                $link = (empty($id)) ? base_url() . "examination/" . $v . "" : base_url() . "examination/" . $v . "/" . $id . "";
                $return.='
								<div style="float:left;margin-left:' . $margin . '"><a href="' . $link . '" title="" id="' . $v . '" name="" class="show_knowledge_context" ><img src="' . base_url() . 'assets/images/icon/level/over/' . $v . '.png" width="40" height="40" alt="" border="0" /></a></div>
							';
            }
            $return .='<div style="clear:left"></div></div>';
        }







        $return.='<div style="clear:right"></div>
					</div>';

        return $return;
    }

    public function get_category_right_knowledge($id) {

        $CI = & get_instance();
        $CI->load->library('trueplook');

        $child_category = $CI->trueplook->child_category;
        $teenage_category = $CI->trueplook->teenage_category;


        $return = '<div>
							<div style="padding:5px 3px; background-color:#ffcc00; font-weight:bold; margin-bottom:5px">ระดับปฐมวัย</div>
						';
        foreach ($child_category as $k => $v) {

            if ($v == $id) {

                $active = 'cms_knowledge_right_menu_active';
            } else {

                $active = '';
            }
            $return.='
						<div class="cms_knowledge_right_menu ' . $active . ' "><img src="http://www.trueplookpanya.com/new/assets/images/icon/buttet_sqrgrey.gif" width="5" height="5" alt="' . $CI->trueplook->get_category_name('knowledge', $v) . '"  /> <a href="' . base_url() . 'knowledge_list/all-' . $v . '/" title="' . $CI->trueplook->get_category_name('knowledge', $v) . '">' . $CI->trueplook->get_category_name('knowledge', $v) . '</a></div>';
        }

        $return.='</div>
				<div style="margin:10px auto 0px; padding-bottom:5px; border-bottom:1px solid #ccc">
            	<div style="padding:5px 3px; background-color:#ffcc00; font-weight:bold; margin-bottom:5px">ระดับประถมศึกษาและมัธยมศึกษา</div>';

        foreach ($teenage_category as $k => $v) {
            if ($v == $id) {

                $active = 'cms_knowledge_right_menu_active';
            } else {

                $active = '';
            }
            $return.='
						<div class="cms_knowledge_right_menu ' . $active . ' " onclick="location.href=\'' . base_url() . 'knowledge_list/all-' . $v . '\' "><div style="float:left"><img src="' . base_url() . 'assets/images/icon/knowledge/menu_knowledge_icon_' . $v . '.png" width="40" height="40" border="0"  /></div><div style="float:left; width:170px; margin-left:5px"><a href="' . base_url() . 'knowledge_list/all-' . $v . '/" title="' . $CI->trueplook->get_category_name('knowledge', $v) . '">' . $CI->trueplook->get_category_name('knowledge', $v) . '</a></div><div style="clear:left"></div></div>';
        }

        $return.='</div>';

        return $return;
    }

    public function get_cms_content_array($data = null, $type = 1, $table_content = 'cms', $table_view = 0, $start = 0, $content_limit = 1, $path_link = null, $target = '_self') {
        $CI = & get_instance();
        $CI->load->library('trueplook');
        $CI->load->library('memcache');
        $file_image['d'] = base_url() . 'assets/images/icon/doc126x95px.jpg';
        $file_image['a'] = base_url() . 'assets/images/icon/snd126x95px.jpg';
        $file_image['f'] = base_url() . 'assets/images/icon/swf126x95px.jpg';
        $file_image[null] = base_url() . 'assets/images/icon/doc126x95px.jpg';

        if (count($data) > 0) {
            $return_data = '<div>';
            $no_line = 1;
            for ($i = $start; $i < $content_limit + $start; $i++) {

                $v = $data[$i];

                if ($v <> '') {

                    //------- Set Data -----//
                    if ($table_content == 'youtube') {
                        $img_thumbnail = "http://i3.ytimg.com/vi/" . $v['cms_file_path'] . "/default.jpg";
                        $content_view = $CI->trueplook->getViewNumber($v['cms_id'], 18);
                        $content_link = htmlspecialchars($path_link . $v['cms_id']);
                    } else {

                        if ($table_content == 'mul_content') {

                            if ($v['mul_source_id'] == '') {
                                $source_id = '00';
                            } else {
                                $source_id = $v['mul_source_id'];
                            }
                            if ($source_id == '00') {
                                $content_link = htmlspecialchars($path_link . $v['cms_id']) . '/';
                            } else {
                                $content_link = htmlspecialchars($path_link . $v['cms_id'] . '-' . $source_id) . '/';
                            }
                            $file_name = $CI->trueplook->get_image_name($v['cms_file_name']);
                            if ($v['mul_source_id'] == NULL) {
                                $content_view = $CI->trueplook->getViewNumber($v['cms_id'], 0);
                            } else {
                                $content_view = $CI->trueplook->getViewNumber($v['mul_source_id'], 7);
                                $v['cms_id'] = $v['mul_source_id'];
                            }
                        } else if ($table_content == 'news_center') {

                            $content_view = $CI->trueplook->getViewCenter($v['cms_id'], $v['cms_category_id'], 'news');
                            $content_link = htmlspecialchars($path_link . $v['cms_id']) . '/';
                            $file_name = $v['cms_file_name'];
                            if (!file_exists($CI->trueplook->set_media_path_full('image', 'no-year') . $v['cms_file_path'] . '/' . $file_name) or $file_name == '') {

                                $file_name = $v['cms_file_name_s'];
                            }
                        } else if ($table_content == 'tv') {

                            $content_view = $CI->trueplook->getViewCenter($v['cms_id'], 'tv_program_episode', 'media');
                            $content_link = htmlspecialchars($path_link . $v['tv_id'] . '/' . $v['cms_id']) . '/';
                        } else {
                            $content_view = $CI->trueplook->getViewNumber($v['cms_id'], $table_view);
                            $content_link = htmlspecialchars($path_link . $v['cms_id']);
                            $file_name = $v['cms_file_name'];
                            if (!file_exists($CI->trueplook->set_media_path_full('image', 'no-year') . $v['cms_file_path'] . '/' . $file_name) or $file_name == '') {

                                $file_name = $v['cms_file_name_s'];
                            }
                        }


                        if ($v['mul_thumbnail_file'] <> '') {

                            $file_name = $v['mul_thumbnail_file'];
                        }

                        $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], $file_name);
                        if ($v['mul_type_id'] <> 'v' and $table_content == 'mul_content') {

                            $img_thumbnail = $file_image[$v['mul_type_id']];
                        }
                    }
                    if ($table_content == 'truelife_api') {

                        $content_title_full = $v['title'];
                        $content_view = number_format($v['views']);
                        $show_date = explode(" ", $v['published_date']);
                        $content_date = $CI->trueplook->data_format('small', 'th', $show_date[0]);
                        $post_by = 'ทีมงานทรูปลูกปัญญา';
                        $content_link = htmlspecialchars($path_link . $v['id']) . '/';
                        $img_thumbnail = 'http://dynamic.tlcdn2.com/api/image/get?h=92&w=128&url=' . $v['thumbnail'];
                        $content_detail = $CI->trueplook->limitText($v['description'], 250);
                    } else {

                        if ($v['cms_subject'] <> '') {
                            $content_title_full = $v['cms_subject'];
                        } else {
                            $content_title_full = $v['cms_subject_sub'];
                        }

                        $show_date = explode(" ", $v['add_date']);
                        $content_date = $CI->trueplook->data_format('small', 'th', $show_date[0]);
                        $post_by = $CI->trueplook->get_display_name($v['member_id'], 'link');
                        $content_detail = $CI->trueplook->limitText($v['cms_detail'], 250);
                    }
                    //-------End Set Data -----//

                    if ($k + 1 < $content_limit) {

                        $border_none = "border-bottom:1px dashed #CCC";
                    } else {

                        $border_none = "border-bottom:none";
                    }

                    //Thumbnail constant

                    switch ($type) {



                        case 'thumb':

                            if ($v['cms_level_id'] <> 0 and $v['cms_level_id'] <> '') {
                                $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                            }
                            if ($v['cms_subject'] == '') {
                                $content_title_full = $v['cms_subject_sub'];
                            }


                            $content_title = $CI->trueplook->limitText($content_title_full, 70);
                            $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], $file_name);
                            if ($v['mul_type_id'] <> 'v' and $table_content == 'mul_content') {

                                $img_thumbnail = $file_image[$v['mul_type_id']];
                            }

                            if ($table_content == 'tv') {

                                if ($CI->trueplook->check_exists('image', $v['tv_vdo_path'], substr($v['cms_file_name'], 0, -4) . '.png')) {

                                    $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], substr($v['cms_file_name'], 0, -4) . '.png');
                                } else {

                                    $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], substr($v['cms_file_name'], 0, -4) . '.jpg');
                                }

                                if (is_null($v['cms_file_name']) or $v['cms_file_name'] == '') {


                                    if ($v['upload_type'] == 'youtube') {

                                        $img_thumbnail = 'http://i3.ytimg.com/vi/' . $v['youtube_code'] . '/mqdefault.jpg';
                                    } else {

                                        $file_name = $v['tv_vdo_filename'];
                                        if ($CI->trueplook->check_exists('image', $v['tv_vdo_path'], $CI->trueplook->get_image_name($v['tv_vdo_filename'], '320x240'))) {

                                            $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['tv_vdo_path'], $CI->trueplook->get_image_name($v['tv_vdo_filename'], '320x240'));
                                        } else {

                                            $img_thumbnail = 'http://www.trueplookpanya.com/new/cutresize/re/110/74/images/trueplook.png/TV/';
                                        }
                                    }
                                }

                                $post_by = 'ทรูปลูกปัญญา';
                                $content_title = $CI->trueplook->limitText($v['tv_name'], 30) . ':' . $content_title = $CI->trueplook->limitText($content_title_full, 30);
                                $content_title_full = 'รายการ ' . $v['tv_name'] . ' : ' . $content_title_full;
                                $content_link = base_url() . 'tv_program_detail/' . $v['tv_id'] . '/' . $v['cms_id'] . '/' . $CI->trueplook->check_format_url($content_title_full) . '/';
                            }


                            $return_data.='
															
															<div class="cms_template_knowledge_vdo_zone_line"  style="width:235px">
																<div>
																	<div class="cms_template_knowledge_vdo_zone_thumb">
																			<a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="110" height="75" border="0" /></a>
																			
																	</div>
																	<div class="cms_template_knowledge_vdo_zone_title"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
																	<div class="clearfix"></div>
																</div>
																<div class="cms_template_info_content">
																	<div style="float:left">' . $level_icon . '</div>
																	<div style="float:left; margin-left:5px">' . $content_view . ' views | ' . $content_date . '<br />' . $post_by . '</div>
																	<div class="clearfix"></div>
																</div>
															</div>
															
												';
                            if ($no_line >= 4) {

                                $return_data.='<div style="clear:both"></div></div><div>';
                                $no_line = 1;
                            } else {

                                $no_line++;
                            }

                            break;

                        case "exam":
                            $content_link = htmlspecialchars('http://www.trueplookpanya.com/true/examination_display.php?exam_id=' . $v['cms_id']);
                            $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                            $content_title = $v['cms_subject'];
                            $return_data.='
												
													<div class="campaign_show_line_exam" style="width:200px">
														<div><a href="' . $content_link . '" target="_blank" title="' . $v['cms_subject'] . '">' . $content_title . '</a></div>
														<div style="color:#406591">วิชา ' . $CI->trueplook->get_category_name('knowledge', $v['mul_root_id']) . '</div>
														<div>
																<div style="float:left"><a href="' . $content_link . '" target="_blank" title="' . $v['cms_subject'] . '">' . $level_icon . '</a></div>
																<div style="float: left; margin-left:5px">
																	<div>โพส : ' . $content_date . '</div>
																	<div>' . $content_view . ' views</div>
																</div>
																<div style="clear:left"></div>
														</div>
													
													</div>
												';
                            if ($no_line >= 4) {

                                $return_data.='<div style="clear:both"></div></div><div>';
                                $no_line = 1;
                            } else {

                                $no_line++;
                            }

                            break;


                        case 'thumb-list' :
                            if ($v['cms_level_id'] <> 0 and $v['cms_level_id'] <> '') {
                                $level_icon = $CI->trueplook->get_icon_level($v['cms_level_id']);
                            }
                            if ($v['cms_subject'] == '') {
                                $content_title_full = $v['cms_subject_sub'];
                            }


                            $content_title = $CI->trueplook->limitText($content_title_full, 150);
                            $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], $file_name);
                            if ($v['mul_type_id'] <> 'v' and $table_content == 'mul_content') {

                                $img_thumbnail = $file_image[$v['mul_type_id']];
                            }

                            if ($table_content == 'tv') {

                                if ($CI->trueplook->check_exists('image', $v['tv_vdo_path'], substr($v['cms_file_name'], 0, -4) . '.png')) {

                                    $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], substr($v['cms_file_name'], 0, -4) . '.png');
                                } else {

                                    $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['cms_file_path'], substr($v['cms_file_name'], 0, -4) . '.jpg');
                                }

                                if (is_null($v['cms_file_name']) or $v['cms_file_name'] == '') {


                                    if ($v['upload_type'] == 'youtube') {

                                        $img_thumbnail = 'http://i3.ytimg.com/vi/' . $v['youtube_code'] . '/mqdefault.jpg';
                                    } else {

                                        $file_name = $v['tv_vdo_filename'];
                                        if ($CI->trueplook->check_exists('image', $v['tv_vdo_path'], $CI->trueplook->get_image_name($v['tv_vdo_filename'], '320x240'))) {

                                            $img_thumbnail = $CI->trueplook->image_resize(110, 74, $v['tv_vdo_path'], $CI->trueplook->get_image_name($v['tv_vdo_filename'], '320x240'));
                                        } else {

                                            $img_thumbnail = 'http://www.trueplookpanya.com/new/cutresize/re/110/74/images/trueplook.png/TV/';
                                        }
                                    }
                                }

                                $post_by = 'ทรูปลูกปัญญา';
                                $content_title = $CI->trueplook->limitText($v['tv_name'], 30) . ':' . $content_title = $CI->trueplook->limitText($content_title_full, 30);
                                $content_title_full = 'รายการ ' . $v['tv_name'] . ' : ' . $content_title_full;
                                $content_link = base_url() . 'tv_program_detail/' . $v['tv_id'] . '/' . $v['cms_id'] . '/' . $CI->trueplook->check_format_url($content_title_full) . '/';
                            }
                            $return_data.='
															
															<div class="cms_template_knowledge_vdo_zone_line"  style="width:480px">
																<div>
																	<div class="cms_template_knowledge_vdo_zone_thumb">
																			<a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" ><img src="' . $img_thumbnail . '" alt="' . htmlspecialchars($content_title_full) . '"  width="110" height="75" border="0" /></a>
																			
																	</div>
																	<div class="cms_template_knowledge_vdo_zone_title" style="width:360px; margin-left:3px">
																	
																		<div class="search_header_text"><a href="' . $content_link . '" title="' . htmlspecialchars($content_title_full) . '" target="' . $target . '" >' . $content_title . '</a></div>
																		<div style="margin:3px auto"><div style="float:left">' . $level_icon . '</div><div  class="search_text_detail">' . $content_title = $CI->trueplook->limitText(strip_tags($v['cms_detail']), 90) . '</div><div style="clear:left"></div></div>
																		<div style="color:#999" class="search_info_link">' . $content_view . ' views | ' . $content_date . ' | ' . $post_by . '</div>
																		
																	</div>
																	<div class="clearfix"></div>
																</div>
																
															</div>
															
												';
                            if ($no_line >= 2) {

                                $return_data.='<div style="clear:both"></div></div><div>';
                                $no_line = 1;
                            } else {

                                $no_line++;
                            }

                            break; //case 21
                    }
                }
            }// Foreach

            $return_data.='<div style="clear:both"></div></div>';
        } else {

            $return_data.='<div class="cms_template_empty_data">ไม่มีข้อมูล</div>';
        } // IF Count



        return $return_data;
    }

//get_cms_content
    //nack 

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

    public function getSubj($id) {
        $_subject_order = array(
            'ภาษาไทย' => '1000',
            'คณิตศาสตร์' => '2000',
            'วิทยาศาสตร์' => '3000',
            'สังคมศึกษา ศาสนา และวัฒนธรรม' => '4000',
            'สุขศึกษาและพลศึกษา' => '5000',
            'ศิลปะ' => '6000',
            'การงานอาชีพและเทคโนโลยี' => '7000',
        );
        return $_subject_order[$id];
    }

    public function getlinkCms($cms_id = 0, $cat_id = 0, $path_link = "") {//setlink
        $link = "";
        
        if ($cat_id==21) {//ข่าวครู
            $link = htmlspecialchars($path_link . "new/cms_list/news/" . $cms_id);
        }else if ($cat_id==95) {//ข่าวการศึกษา
           $link = htmlspecialchars($path_link . "new/cms_list/news/" . $cms_id);
        }else if ($cat_id==143) {//ข่าวราชกาล
            $link = htmlspecialchars($path_link . "new/cms_list/teacher/" . $cms_id);
        }
        
        return link;
    }

}

// Class
?>