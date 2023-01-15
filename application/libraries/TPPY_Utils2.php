<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TPPY_Utils {
// $this->load->library('TPPY_Utils');
// $this->tppy_utils->ViewNumberGet($content_id=0, $table_name=0);
// $this->tppy_utils->ViewNumberSet($content_id=0, $table_name=0);
// 'mul_content','mul_source', 'cms', 'tv_program', 'tv_program_episode' 
public function ViewNumberGet($content_id=0, $table_name=0){
    $CI = & get_instance();
    $CI->load->library('Memcached_library');
    switch($table_name) { 
      case 'mul_content' : $field_name='mul_content_id'; $view_count_field_name='view_count'; break; /* mul_content : 0*/ 
      case 'mul_source' : $field_name='mul_source_id';  $view_count_field_name='view_count'; break; /* mul_source : 7*/
      case 'cms' : $field_name='cms_id'; $view_count_field_name='view_count'; break; /* CMS : 21 */
      case 'cmsblog_detail' : $field_name='idx'; $view_count_field_name='view_count'; break; /* CMS : 21 */
      case 'tv_program' : $field_name='content_id';$view_count_field_name='view_count'; break; /* TV_PROGRAM */
      case 'tv_program_episode' : $field_name='content_child_id'; $view_count_field_name='view_count'; break; /* TV_PROGRAM_EPISODE */
      case 'ams_news_directapply' :  $field_name='news_id';  $view_count_field_name='news_view'; break;
      case 'webboard_post' :  $field_name='wb_post_id';  $view_count_field_name='view_count';  $last_update_date ='last_update_date';break;
      case 'ams_news_camp' :  $field_name='camp_id';  $view_count_field_name='viewcount'; break;
      case 'lesson_plan' :  $field_name='lesson_plan_id';  $view_count_field_name='view_count'; break;
      case 'cvs_game_quiz_main' :  $field_name='id';  $view_count_field_name='view'; break;
      case 'cvs_course_examination' :  $field_name='id';  $view_count_field_name='view_count'; break;
      default : return 0; break;
    }
    // echo 'asd';
    $key = "vc_$table_name+$content_id";
    if(!$content_view_count=$CI->memcached_library->get($key)){
      $CI->db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
	  $CI->db->select($view_count_field_name)
                   ->from($table_name)
                   ->where($field_name, $content_id);
      $content_view_count= (int)$CI->db->get()->row()->$view_count_field_name;
	  $CI->db->query("COMMIT");
      $CI->memcached_library->set($key, $content_view_count);
    }
    
    return $content_view_count;
  }
public function ViewNumberSet($content_id=0, $table_name=0, $increment_by=1){
      $CI = & get_instance();
      $CI->load->library('Memcached_library');
      $key = "vc_$table_name+$content_id";
      
      //echo '<pre> key=>'; print_r($key); echo '</pre>';Die();
      
      $update_update_field = '';
      $use_session=true;
      switch($table_name) { 
        case 'mul_content' : $field_name='mul_content_id'; $table_name='mul_content'; $view_count_field_name='view_count'; $update_update_field ='mul_update_date'; break; /* mul_content : 0*/ 
        case 'mul_source' : $field_name='mul_source_id'; $table_name='mul_source'; $view_count_field_name='view_count'; $update_update_field ='mul_source_update_datetime'; break; /* mul_source : 7*/
        case 'cms' : $field_name='cms_id'; $table_name='cms'; $view_count_field_name='view_count'; break; /* CMS : 21 */
        case 'cmsblog_detail' : $use_session=true; $field_name='idx'; $view_count_field_name='view_count'; $update_date ='update_date'; break; /*USE SESSION*/
        case 'tv_program' : $field_name='content_id'; $table_name='tv_program'; $view_count_field_name='view_count'; break; /* TV_PROGRAM */
        case 'tv_program_episode' : $field_name='content_child_id'; $table_name='tv_program_episode'; $view_count_field_name='view_count'; break; /* TV_PROGRAM_EPISODE */
        case 'ams_news_directapply' :  $field_name='news_id';  $view_count_field_name='news_view'; break;
        case 'webboard_post' :  $field_name='wb_post_id';  $view_count_field_name='view_count';  $last_update_date ='last_update_date';break;
        case 'ams_news_camp' :  $field_name='camp_id';  $view_count_field_name='viewcount'; break;
        case 'lesson_plan' :  $field_name='lesson_plan_id';  $view_count_field_name='view_count'; break;
        case 'cvs_game_quiz_main' :  $field_name='id';  $view_count_field_name='view'; break;
		case 'smt_content' :  $field_name='content_id';  $view_count_field_name='view_count'; break;
        case 'cvs_course_examination' :  $field_name='id';  $view_count_field_name='view_count'; break;
        default : return 0; break;
      }
      
      $content_view_count = $this->ViewNumberGet($content_id, $table_name);
      
      if($content_view_count!== false) {
        if($use_session){
          if(!$CI->session->userdata($key)){
            $CI->session->set_userdata($key, '1');
            $content_view_count = $CI->memcached_library->increment($key, $increment_by);
          }
        }else{
          $content_view_count = $CI->memcached_library->increment($key, $increment_by);
        }
        
        if(($content_view_count < 10 || $content_view_count % 29 == 0 || $use_session)){
          $DBEdit=$CI->load->database('edit',TRUE);
          $DBEdit->where($field_name, $content_id);
          $DBEdit->where("$view_count_field_name < ", $content_view_count); // ถ้าใน DB มากกว่า memcache มันจะไม่ + เพิ่ม
          $DBEdit->set($view_count_field_name, $content_view_count);
          if(!empty($update_update_field)){
            $DBEdit->set($update_update_field, $update_update_field, FALSE);
          }
          $DBEdit->update($table_name);
        }
      }
      //echo '<pre> tppy utls content_view_count=>'; print_r($content_view_count); echo '</pre>';Die();
      return $content_view_count;
  }
/*
public function image_thumb($source_image, $width = 0, $height = 0, $crop = FALSE, $props = array()) {
    $CI = & get_instance();
    $CI->load->library('image_cache');

    $props['source_image'] = '/' . str_replace(base_url(), '', $source_image);
    $props['width'] = $width;
    $props['height'] = $height;
    $props['crop'] = $crop;

    $CI->image_cache->initialize($props);
    $image = $CI->image_cache->image_cache();
    $CI->image_cache->clear();
// var_dump($image);
    return $image;
  }
*/
/*
public function isAdmin($zoneName="",$token = ""){
		$CI = & get_instance();
		$r = false;
		
		$CI->load->library('TPPY_Oauth');	
		if($token=="session"){
			if ($CI->session->userdata('user_session') != NULL && $CI->session->userdata('user_session') != '') {
				$this->me = $CI->session->userdata('user_session');
			}
		}else{
			if (isset($token) && $token != '' && $token != NULL) {
				$token_data = $CI->tppy_oauth->parse_token($token);
				$CI->load->model("member_model");
				$valid_data = $CI->member_model->get_profile_by_userid($token_data->user_id);
				if($valid_data){
					$this->me = (object)$valid_data;
				}
				//var_dump($token_data,$valid_data,$this->me);
			}
		}
		 
		
		if($this->me){
			switch ($zoneName) {
				case "superadmin" :
					if(intval($this->me->user_permission) ==1){
						$r = true;
					}
				case "knowledge" :
					if (intval($this->me->user_permission) == 12 || intval($this->me->user_permission) == 1) {
						$r = true;
					}
				case "admissions" :
					if (intval($this->me->user_permission) == 6 || intval($this->me->user_permission) == 1) {
						$r = true;
					}
				default :
					if(intval($this->me->user_permission) > 0){
						$r = true;
					}
			}
		}
		return $r;
  } 
*/
}