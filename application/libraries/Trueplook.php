<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Trueplook {

  private $encrypted_key = 'Trueplookpanya.Com';
  public $cookie_expire = 86500;
  //public $year_path=date('Y');
  public $year_path = '2013';
  public $month_th = array('', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
  public $month_en = array('', 'January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
  public $month_th_small = array('', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.');
  public $date_array = array('Mon' => 'จันทร์', 'Tue' => 'อังคาร', 'Wed' => 'พุธ', 'Thu' => 'พฤหัสบดี', 'Fri' => 'ศุกร์', 'Sat' => 'เสาร์', 'Sun' => 'อาทิตย์');
  public $date_array_en = array('Mon' => 'Mon', 'Tue' => 'Tue', 'Wed' => 'Wed', 'Thu' => 'Thu', 'Fri' => 'Fri', 'Sat' => 'Sat', 'Sun' => 'Sun');
  public $date_array_en_num = array('1' => 'Mon', '2' => 'Tue', '3' => 'Wed', '4' => 'Thu', '5' => 'Fri', '6' => 'Sat', '7' => 'Sun');
  public $date_array_th_num = array('1' => 'จันทร์', '2' => 'อังคาร', '3' => 'พุธ', '4' => 'พฤหัสบดี', '5' => 'ศุกร์', '6' => 'เสาร์', '7' => 'อาทิตย์');
  public $teenage_category = array('1000', '2000', '3000', '4000', '5000', '6000', '7000', '8000');
  public $child_category = array('0100', '0300', '0500', '0700');
  public $replace_check = array(' ', '\'', '\"', '(', ')', '&', ';');

  public function get_ip_address() {

    if (getenv(HTTP_X_FORWARDED_FOR))
      $ip = getenv(HTTP_X_FORWARDED_FOR);
    else
      $ip = getenv(REMOTE_ADDR);

    return $ip;
  }

  public function check_member_online() {
    $CI = & get_instance();
    if ($CI->secure->is_auth()) {
      $user = $CI->secure->get_user_session();
      $top_data['check_member'] = $user->member_id;
      $top_data['member_user'] = $user->member_usrname;
      $top_data['check_group_code'] = "";
      $top_data['token_key'] = $user->token_key;
      $top_data['member_username'] = '<span style="color:green">online</span>';
      $top_data['path_img_member'] = $user->avatar;
      $top_data['psn_display_name'] = $user->psn_display_name;
      $top_data['folder_path'] = "";
      $top_data['psn_email'] = $user->email;
    } else {
      $top_data['member_username'] = 'Guest';
    }
    return $top_data;
//        $CI->load->library('tppymemcached');
//        $CI->load->model('connect_db_model', 'cdm');
//        session_start();
//        
//        if ($_SESSION['user_session']->member_usrname <> '' ) {
//            $top_data['check_member'] = $this->get_cookie('member_id');
//            $top_data['member_user'] = $this->get_cookie('member_usrname');
//            $top_data['check_group_code'] = $this->get_cookie('group_code');
//            $top_data['token_key'] = $this->get_cookie('token_key');
//            $top_data['member_username'] = '<span style="color:green">online</span>';
//            $sql = "select * FROM `personnel` WHERE member_id = '" . $this->get_cookie('member_id') . "'";
//            if ($CI->tppymemcached->get('check_member_online_' . $this->get_cookie('member_id')) == '') {
//                $res = $CI->cdm->_query_row($sql, 'default');
//                $CI->tppymemcached->set('check_member_online_' . $this->get_cookie('member_id'), $res);
//            }
//            $res = $CI->tppymemcached->get('check_member_online_' . $this->get_cookie('member_id'));
//            $personnel = $res[0];
//            $blog_path = "/data/product/blog/images/" . $personnel['folder_path'] . "/folder_";
//            if ($personnel['psn_picture'] == "") {
//                $path_img = "./blog/images/upload/ex_pic.gif";
//            } else {
//                $path_img = $blog_path . $this->get_cookie('member_usrname') . "/" . $personnel['psn_picture'];
//            }
//
//            $top_data['path_img_member'] = $path_img;
//            $top_data['member_display_name'] = $personnel->psn_display_name;
//            $top_data['folder_path'] = $personnel['folder_path'];
//            $top_data['psn_email'] = $personnel->psn_email;
//        } else {
//            $top_data['member_username'] = 'Guest';
//        }
//        return $top_data;
  }

  public function set_nav_menu($nav) {

    $count_array = count($nav);
    $nav_no = 1;
    foreach ($nav as $nav_k => $nav_v) {
      if ($nav_no == $count_array) {
        $nav_menu.='<div class="nav_top_menu nav_menu_active">' . $nav_k . '</div>';
      } else {
        $nav_menu.='<div class="nav_top_menu"><a href="' . $nav_v . '" title="Go to ' . $nav_k . ' ">' . $nav_k . '  >&nbsp;</a></div>';
      }
      $nav_no++;
    }

    return $nav_menu;
  }

  public function set_title_link_path($title) {

    $return = str_replace(' ', '-', strip_tags(trim($title)));
    return $return;
  }

  public function get_display_name($member_id, $type = 'name') {
    if ($member_id == '') {
      return 'ทีมงานทรูปลูกปัญญา';
    }
    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');

    $sql = "select m.group_code,p.psn_display_name,b.blog_id,p.psn_name,p.psn_lastname from personnel p left join blog b on p.member_id=b.member_id left join member m on m.member_id=p.member_id where p.member_id='" . $member_id . "' limit 0,1 ";
//        if ($CI->tppymemcached->get('get_display_name_' . $member_id . '_' . $type) == '') {
//
//            $result = $CI->cdm->_query_row($sql, 'select');
//            $CI->tppymemcached->set('get_display_name_' . $member_id . '_' . $type, $result);
//        }
//
//        $display = $CI->tppymemcached->get('get_display_name_' . $member_id . '_' . $type);
    $display = $CI->cdm->_query_row($sql, 'select');
    if ($type == 'name') {

      $return = $display[0]['psn_display_name'];
    } elseif ($type == 'fullname') {
      $return = $display[0]['psn_name'] . ' ' . $display[0]['psn_lastname'];
    } else {

      if ($display[0]['group_code'] != 'SUP' && $display[0]['group_code'] != 'ADM' && $display[0]['group_code'] != 'EDT') {

        $link = $display[0]['blog_id'];
      } else {

        $link = 9631;
      }

      $return = '<a href="http://www.trueplookpanya.com/true/blog_friend_main.php?friend_blog_id=' . $link . '" title="เข้าสู่ blog ของ ' . $display[0]['psn_display_name'] . ' ">' . $display[0]['psn_display_name'] . '</a>';
    }

    return $return;
  }
  
      public function new_get_member_thumb($member_id, $w, $h = null, $clear = null) {

        $CI = & get_instance();
        $CI->load->library('tppymemcached');
        $CI->load->model('connect_db_model', 'cdm');
        $sql = "SELECT u.* ,
        REPLACE(psn_display_image, CONCAT('/', member_id,'.jpg'), '') folder_path
        FROM users_account u WHERE member_id = '$member_id'";

        if (isset($clear)) {
            $CI->tppymemcached->delete('get_thumbnail_member_' . $w . '_' . $member_id);
        }

        if ($CI->tppymemcached->get('get_thumbnail_member_' . $w . '_' . $member_id) == '') {
            $res = $CI->cdm->_query_row($sql, 'select');
            $personnel = $res[0];
            $image = $personnel['psn_display_image'];
            $username = $personnel['user_username'];
            $folder_path = $personnel['folder_path'];
            
            if (!filter_var($image , FILTER_VALIDATE_URL) === false) {
              // $path_img = $image;
              $path_img = site_url("/teamtrueblog/cutresize/?img=".urlencode($image)."&w=$w&h=$h");
            } else if($image!=''){ // ต้องเอาออกนะ -*-
              $path_img =  site_url("/new/cutresize/re/$w/$w/". str_replace('/', '-', "images/$folder_path/folder_$username") ."/$member_id.jpg/blog");
            }else{
              $path_img = site_url("/data/product/blog/images/upload/folder_plook/MEM0009631.jpg");
            }

            $CI->tppymemcached->set('get_thumbnail_member_' . $w . '_' . $member_id, $path_img);
        }
        
       return  $CI->tppymemcached->get('get_thumbnail_member_' . $w . '_' . $member_id);
    }

  public function get_member_thumb($member_id, $w, $h = null) {
    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');
    $sql = "SELECT p.folder_path,p.psn_picture,m.group_code,m.member_usrname,b.blog_id FROM personnel p left join member m on p.member_id=m.member_id left join blog b on p.member_id=b.member_id WHERE p.member_id = '" . $member_id . "'";

    if ($CI->tppymemcached->get('get_thumbnail_member_' . $member_id) == '') {
      $res = $CI->cdm->_query_row($sql, 'select');
      $CI->tppymemcached->set('get_thumbnail_member_' . $member_id, $res);
    }

    $mem = $CI->tppymemcached->get('get_thumbnail_member_' . $member_id);

    $personnel = $mem[0];
    $blog_path = "/data/product/blog/images/" . $personnel['folder_path'] . "/folder_";
    $check_path = "/data/product/trueplookpanya/www/product/blog/images/" . $personnel['folder_path'] . "/folder_";
    if (!file_exists($check_path . $personnel['member_usrname'] . "/" . $personnel['psn_picture'])) {
      $path_img = "/data/product/blog/images/upload_2/folder_taeIntouch1302/MEM0120935.jpg";
    } else {
      if ($personnel['group_code'] != 'SUP' && $personnel['group_code'] != 'ADM' && $personnel['group_code'] != 'EDT' && $personnel['psn_picture'] != '') {

        $path_img = $blog_path . $personnel['member_usrname'] . "/" . $personnel['psn_picture'];
        $link = $personnel['blog_id'];
      } else {
        $path_img = '/data/product/blog/images/upload/folder_plook/MEM0009631.jpg';
        $link = 9631;
      }
    }

    $img = '<a href="http://www.trueplookpanya.com/true/blog_friend_main.php?friend_blog_id=' . $link . '" title="เข้าสู่ blog ของ ' . $personnel['psn_display_name'] . ' "><img src="' . $path_img . '" alt="เข้าสู่ blog ของ ' . $personnel['psn_display_name'] . ' " border=0 width="' . $w . '" height="' . $h . '" /></a>';
    return $img;
  }

  public function set_navigator($nav) {

    $count_array = count($nav);
    $nav_no = 1;
    foreach ($nav as $nav_k => $nav_v) {
      if ($nav_no == $count_array) {
        $nav_menu.='<span class="nav_active">' . $nav_k . '</span>';
      } else {
        $nav_menu.='<a href="' . $nav_v . '" title="Go to ' . $nav_k . ' ">' . $nav_k . '  &gt; &nbsp;</a>';
      }
      $nav_no++;
    }

    return $nav_menu;
  }

  public function get_tag_link($tag, $explode = ',') {

    $tag = explode($explode, $tag);
    $nums = count($tag);
    if ($nums > 0) {
      foreach ($tag as $k => $v) {
        $no = $k + 1;
        if ($nums == $no) {
          $link.='<a href="/true/search_premium.php?q=' . $v . '" title="ค้นหาคำว่า ' . $v . '" target="_blank">' . $v . '</a>';
        } else {
          $link.='<a href="/true/search_premium.php?q=' . $v . '" title="ค้นหาคำว่า ' . $v . '" target="_blank">' . $v . '</a> , ';
        }
      }
    } else {
      $link = '&nbsp;';
    }

    return $link;
  }

  public function set_media_path_full($type, $year_type = 'yes') {
    switch ($type) {

      case 'vdo':
        $path = '/data/product/trueplookpanya/www/static/trueplookpanya/' . $this->year_path . '/';
        //$path='/data/product/trueplookpanya/www/product/media/'.$this->year_path.'/';
        break;

      case 'image':
        $path = '/data/product/trueplookpanya/www/product/media/' . $this->year_path . '/';
        break;
    }

    if ($year_type <> 'yes') {
      $path = substr($path, 0, -5);
    }

    return $path;
  }

  public function get_media_path($type, $folder = 'media') {

    switch ($type) {
      case 'vdo':
        $path = 'http://static.trueplookpanya.com/trueplookpanya/';
        //$path='/data/static/trueplookpanya/';
        //$path='/data/product/'.$folder.'/';
        break;

      case 'image':

        //$path='http://www.trueplookpanya.com/data/product/'.$folder.'/';
        $path = '/data/product/' . $folder . '/';
        break;
    }

    return $path;
  }

  /* 			public function image_resize_cut($w,$h,$path){
    //$main_path='http://www.trueplookpanya.com/data/product/media/';
    $main_path='/data/product/media/';
    $resize_cut=$main_path.'ImageCutCenter.php?w='.$w.'&h='.$h.'&pathimg='.$path;
    return htmlspecialchars($resize_cut);

    }
   */

  public function image_resize_cut($w, $h, $path, $file, $mainfolder = 'media') {
    if ($mainfolder == 'media') {
      $resize_cut = 'http://www.trueplookpanya.com/new/cutcenter/re/' . $w . '/' . $h . '/' . str_replace('/', '-', $path) . '/' . $file;
    } else {
      $resize_cut = 'http://www.trueplookpanya.com/new/cutcenter/re/' . $w . '/' . $h . '/' . str_replace('/', '-', $path) . '/' . $file . '/' . $mainfolder . '/';
    }
    return htmlspecialchars($resize_cut);
  }

  public function image_resize($w, $h, $path, $file, $mainfolder = 'media') {

    if ($mainfolder == 'media') {
      $resize_cut = 'http://www.trueplookpanya.com/new/cutresize/re/' . $w . '/' . $h . '/' . str_replace('/', '-', $path) . '/' . $file;
      //$resize_cut='http://www.trueplookpanya.com/new/cutresize/re/'.$w.'/'.$h.'/'.str_replace('/','-',$path).'/'.$file;
    } else {
      $resize_cut = 'http://www.trueplookpanya.com/new/cutresize/re/' . $w . '/' . $h . '/' . str_replace('/', '-', $path) . '/' . $file . '/' . $mainfolder . '/';
    }
    return htmlspecialchars($resize_cut);
  }

  /* 		public function image_resize($w,$path,$h=null){

    //$main_path='http://www.trueplookpanya.com/data/product/media/';
    $main_path='/data/product/media/';
    $path_full=$this->set_media_path_full('image','noyear');
    $resize=$main_path.$path;
    //$resize=$main_path.'ImageSizeMedia.php?w='.$w.'&h='.$h.'&img='.$path;
    return htmlspecialchars($resize);

    } */

  public function image_resize_api($w, $path) {

    //$main_path='http://www.trueplookpanya.com/data/product/media/';
    $main_path = '/data/product/media/';
    $resize = $main_path . 'ImageSizeMedia_api.php?w=' . $w . '&img=' . $path;
    return $resize;
  }

  public function clear_logout() {

    $cookie_list = array('member_id', 'primary_id', 'member_usrname', 'member_password', 'group_code', 'admin_remember_login', 'admin_member_id', 'admin_primary_id', 'admin_member_usrname', 'admin_member_password', 'admin_group_code', 'admin_tokenkey');
    foreach ($cookie_list as $cookie_name) {
      delete_cookie($cookie_name);
    }

    return true;
  }

  // ================  Function Old Web =======================//

  function get_cookie($cookie_name) {
    if(!isset($_SESSION)) 
        session_start(); 
    if ($_COOKIE[$cookie_name] == '' && $_SESSION['user_session']->$cookie_name <> '') {
      return $_SESSION['user_session']->$cookie_name;
    } else {
      if (isset($_COOKIE[$cookie_name])) {
        if ($cookie_name == 'token_key') {
          return $_COOKIE[$cookie_name];
        } else {
          return base64_decode(base64_decode(base64_decode($_COOKIE[$cookie_name])));
        }
      } else {
        return false;
      }
    }
  }

  function _set_cookie($cookie_time = 14400, $cookie_name, $cookie_value) { // 14400 = 4 hours
    setcookie($cookie_name, base64_encode(base64_encode(base64_encode($cookie_value))), time() + $cookie_time, "/");
    //setcookie( $cookie_name, $cookie_value, time()+$cookie_time , "/");
    return true;
  }

  // ================  Function Old Web =======================//

  public function Pagination($page, $pagelen, $total_rows, $url, $DBtype) {

    $next = '';
    $first = '';


    $all_page = ceil($total_rows / $pagelen);
    if ($page > 1) {

      $prev = $page - 1;
    } else {

      $prev = 1;
    }

    if ($page < $all_page) {

      $next = $page + 1;
    } else {

      $next = $all_page;
    }

    $first = 1;
    $last = $all_page;

    $page_show = 6;
    if ($all_page - 1 < $page_show) {

      $page_show = $all_page - 1;
    }

    $page_range = 3;
    if (($page - $page_range) < 1) {
      $p_run = 1;
    } else {
      if (($all_page - $page) >= 3) {

        $p_run = $page - $page_range;
      } else {

        $start_num = 3 - ($all_page - $page);
        $p_run = ($page - $page_range) - $start_num;
      }
    }

    if ($p_run <= 0) {
      $p_run = 1;
    }

    $change_page = "
					<span class=\"nav\">
						<a href=\"" . $url . $first . "/ \" class=\"first\" title=\"first page\"><span>First</span></a>
						<a href=\"" . $url . $prev . "/ \" class=\"previous\" title=\"previous page\"><span>Previous</span></a>
					</span>
					<span class=\"pages\">
					";

    //	for($p=1; $p<=$all_page;$p++){
    for ($p = $p_run; $p <= ($page_show + $p_run); $p++) {
      if ($p == $page) {

        $change_page.="
							
								<a href=\"" . $url . $p . "/ \" title=\"page " . $p . "\" class=\"active\"><span>" . $p . "</span></a>
				
							
							";
      } else {

        $change_page.="
					
								<a href=\"" . $url . $p . "/ \" title=\"page " . $p . "\"><span>" . $p . "</span></a>
				
							
							";
      }
    }
    $change_page.="
					</span>
					<span class=\"nav\">
						<a href=\"" . $url . $next . "/ \" class=\"next\" title=\"next page\"><span>Next</span></a>
						<a href=\"" . $url . $last . "/\" class=\"last\" title=\"last page\"><span>Last</span></a>
					</span>
					";

    return $change_page;
  }

  public function Pagination_main($page, $pagelen, $total_rows, $url, $html = '') {

    $next = '';
    $first = '';


    $all_page = ceil($total_rows / $pagelen);
    if ($page > 1) {

      $prev = $page - 1;
    } else {

      $prev = 1;
    }

    if ($page < $all_page) {

      $next = $page + 1;
    } else {

      $next = $all_page;
    }

    $first = 1;
    $last = $all_page;


    $page_show = 6;
    if ($all_page - 1 < $page_show) {

      $page_show = $all_page - 1;
    }

    $page_range = 3;
    if (($page - $page_range) < 1) {
      $p_run = 1;
    } else {
      if (($all_page - $page) >= 3) {

        $p_run = $page - $page_range;
      } else {

        $start_num = 3 - ($all_page - $page);
        $p_run = ($page - $page_range) - $start_num;
      }
    }

    if ($p_run <= 0) {
      $p_run = 1;
    }

    $change_page = "
					<span class=\"nav\">
						<a href=\"" . $url . "/" . $first . "/" . $html . "\"  class=\"first\" title=\"first page\"><span>First</span></a>
						<a href=\"" . $url . "/" . $prev . "/" . $html . "\" class=\"previous\" title=\"previous page\"><span>Previous</span></a>
					</span>
					<span class=\"pages\">
					";

    for ($p = $p_run; $p <= ($page_show + $p_run); $p++) {
      if ($p == $page) {

        $change_page.="
							
								<a href=\"" . $url . "/" . $p . "/" . $html . "\" title=\"page " . $p . "\" class=\"pageTV active\"  id=\"page_" . $p . "\"><span>" . $p . "</span></a>
				
							
							";
      } else {

        $change_page.="
					
								<a href=\"" . $url . "/" . $p . "/" . $html . "\"  title=\"page " . $p . "\" id=\"page_" . $p . "\" class=\"pageTV\"><span>" . $p . "</span></a>
				
							
							";
      }
    }
    $change_page.="
					</span>
					<span class=\"nav\">
						<a href=\"" . $url . "/" . $next . "/" . $html . "\" class=\"next\" title=\"next page\"><span>Next</span></a>
						<a href=\"" . $url . "/" . $last . "/" . $html . "\" class=\"last\" title=\"last page\"><span>Last</span></a>
					</span>
					";

    return $change_page;
  }

  public function Pagination_tv($tv_id, $tv_episode_id, $page, $pagelen, $total_rows) {


    $next = '';
    $first = '';


    $all_page = ceil($total_rows / $pagelen);
    if ($page > 1) {

      $prev = $page - 1;
    } else {

      $prev = 1;
    }

    if ($page < $all_page) {

      $next = $page + 1;
    } else {

      $next = $all_page;
    }

    $first = 1;
    $last = $all_page;


    $page_show = 6;
    if ($all_page - 1 < $page_show) {

      $page_show = $all_page - 1;
    }

    $page_range = 3;
    if (($page - $page_range) < 1) {
      $p_run = 1;
    } else {
      if (($all_page - $page) >= 3) {

        $p_run = $page - $page_range;
      } else {

        $start_num = 3 - ($all_page - $page);
        $p_run = ($page - $page_range) - $start_num;
      }
    }

    if ($p_run <= 0) {
      $p_run = 1;
    }


    $change_page = "
					<span class=\"nav\">
						<a href=\"javascript:void(0)\" onclick=\"tv_page=" . $first . ";change_page_tv('" . $tv_id . "','" . $tv_episode_id . "','" . $first . "')\" \" class=\"first\" title=\"first page\"><span>First</span></a>
						<a href=\"javascript:void(0)\" onclick=\"tv_page=" . $prev . ";change_page_tv('" . $tv_id . "','" . $tv_episode_id . "','" . $prev . "')\" \" class=\"previous\" title=\"previous page\"><span>Previous</span></a>
					</span>
					<span class=\"pages\">
					";

    for ($p = $p_run; $p <= ($page_show + $p_run); $p++) {
      if ($p == $page) {

        $change_page.="
							
								<a href=\"javascript:void(0)\" onclick=\"tv_page=" . $p . ";change_page_tv('" . $tv_id . "','" . $tv_episode_id . "','" . $p . "')\" \" title=\"page " . $p . "\" class=\"pageTV active\"  id=\"page_" . $p . "\"><span>" . $p . "</span></a>
				
							
							";
      } else {

        $change_page.="
					
								<a href=\"javascript:void(0)\" onclick=\"tv_page=" . $p . ";change_page_tv('" . $tv_id . "','" . $tv_episode_id . "','" . $p . "')\" \" title=\"page " . $p . "\" id=\"page_" . $p . "\" class=\"pageTV\"><span>" . $p . "</span></a>
				
							
							";
      }
    }
    $change_page.="
					</span>
					<span class=\"nav\">
						<a href=\"javascript:void(0)\" onclick=\"tv_page=" . $next . ";change_page_tv('" . $tv_id . "','" . $tv_episode_id . "','" . $next . "')\" \" class=\"next\" title=\"next page\"><span>Next</span></a>
						<a href=\"javascript:void(0)\" onclick=\"tv_page=" . $last . ";change_page_tv('" . $tv_id . "','" . $tv_episode_id . "','" . $last . "')\" class=\"last\" title=\"last page\"><span>Last</span></a>
					</span>
					";

    return $change_page;
  }

  public function Pagination_cms_ajax($type, $id, $page, $pagelen, $total_rows, $url, $order = 'update') {

    $next = '';
    $first = '';


    $all_page = ceil($total_rows / $pagelen);
    if ($page > 1) {

      $prev = $page - 1;
    } else {

      $prev = 1;
    }

    if ($page < $all_page) {

      $next = $page + 1;
    } else {

      $next = $all_page;
    }

    $first = 1;
    $last = $all_page;


    $page_show = 6;
    if ($all_page - 1 < $page_show) {

      $page_show = $all_page - 1;
    }

    $page_range = 3;
    if (($page - $page_range) < 1) {
      $p_run = 1;
    } else {
      if (($all_page - $page) >= 3) {

        $p_run = $page - $page_range;
      } else {

        $start_num = 3 - ($all_page - $page);
        $p_run = ($page - $page_range) - $start_num;
      }
    }

    if ($p_run <= 0) {
      $p_run = 1;
    }

    $change_page = "
					<span class=\"nav\">
						<a href=\"javascript:void(0)\" onclick=\"change_page_template('" . $type . "','" . $id . "','" . $first . "','" . $pagelen . "','" . $order . "','" . $url . "')\" \" class=\"first\" title=\"first page\"><span>First</span></a>
						<a href=\"javascript:void(0)\" onclick=\"change_page_template('" . $type . "','" . $id . "','" . $prev . "','" . $pagelen . "','" . $order . "','" . $url . "')\" \" class=\"previous\" title=\"previous page\"><span>Previous</span></a>
					</span>
					<span class=\"pages\">
					";

    for ($p = $p_run; $p <= ($page_show + $p_run); $p++) {
      if ($p == $page) {

        $change_page.="
							
								<a href=\"javascript:void(0)\" onclick=\"change_page_template('" . $type . "','" . $id . "','" . $p . "','" . $pagelen . "','" . $order . "','" . $url . "')\" \" title=\"page " . $p . "\" class=\"pageTV_" . $type . " active\"  class=\"page_" . $p . "\"><span>" . $p . "</span></a>
				
							
							";
      } else {

        $change_page.="
					
								<a href=\"javascript:void(0)\" onclick=\"change_page_template('" . $type . "','" . $id . "','" . $p . "','" . $pagelen . "','" . $order . "','" . $url . "')\" \" title=\"page " . $p . "\" class=\"page_" . $p . "_" . $type . " \" class=\"pageTV\"><span>" . $p . "</span></a>
				
							
							";
      }
    }
    $change_page.="
					</span>
					<span class=\"nav\">
						<a href=\"javascript:void(0)\" onclick=\"change_page_template('" . $type . "','" . $id . "','" . $next . "','" . $pagelen . "','" . $order . "','" . $url . "')\" \" class=\"next\" title=\"next page\"><span>Next</span></a>
						<a href=\"javascript:void(0)\" onclick=\"change_page_template('" . $type . "','" . $id . "','" . $last . "','" . $pagelen . "','" . $order . "','" . $url . "')\" class=\"last\" title=\"last page\"><span>Last</span></a>
					</span>
					";

    return $change_page;
  }

  public function _get_cookie($cookie) {
    $CI = & get_instance();
    $CI->load->library('encrypt');
    $decode = $CI->encrypt->decode($cookie, $this->encrypted_key);
    return $decode;
  }

  public function _encode_cookie($value) {
    $CI = & get_instance();
    $CI->load->library('encrypt');
    $encrypted = $CI->encrypt->encode($value, $this->encrypted_key);

    return $encrypted;
  }

  public function passGen($len) {

    $code = "adcdefghijklmnpqrstuvwxyz123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
    srand((double) microtime() * 1000000);
    for ($i = 0; $i < $len; $i++) {
      $passgen.=$code[rand() % strlen($code)];
    }
    return $passgen;
  }

  public function add_doc($file, $main_path, $id, $type, $name) {

    if (!empty($_FILES)) {
      if ($name <> '') {
        $tempFile = $file;
        $surname = substr($name, -4);
      } else {
        $tempFile = $file['tmp_name'];
        $surname = substr($file['name'], -4);
      }
      $file_name = $id . "-" . $type . $surname;
      $target_path = $main_path . $file_name;
      if (move_uploaded_file($tempFile, $target_path)) {
        $result = array('true', $file_name);
        return $result;
      } else { // else move_uploaded_file
        $result = array('false', '');
        return $result;
      } // if move_uploaded_file
    }// if empty
  }

  public function add_image($file, $main_path, $id, $w, $section, $name = '') {
    $CI = & get_instance();
    $CI->load->library('image_lib');

    if (!empty($_FILES)) {
      if ($name <> '') {
        $tempFile = $file;
        $surname = substr($name, -4);
      } else {
        $tempFile = $file['tmp_name'];
        $surname = substr($file['name'], -4);
      }


      if (strtolower($surname) == '.jpg' or strtolower($surname) <> '.gif' or strtolower($surname) == '.png') {

        $passgen = rand(0000, 9999);
        $img_name = $section . $id . $passgen . $surname;
        $target_path = $main_path . $img_name;

        if (copy($tempFile, $target_path)) {
          list($w1, $h1) = getimagesize($target_path);
          if ($w1 > $w) {
            $percent = $w / $w1;
            $h = $h1 * $percent;
            /* $im=imagecreatetruecolor($w,$h); 
              if ($surname==".gif"){
              $im1=imagecreatefromgif($target_path);
              imagecopyresampled($im,$im1,0,0,0,0,$w,$h,$w1,$h1);
              imagegif($im,$target_path,100);
              }else if ($surname==".png"){
              $im1=imagecreatefrompng($target_path);
              imagecopyresampled($im,$im1,0,0,0,0,$w,$h,$w1,$h1);
              imagejpeg($im,$target_path,100);
              }else{
              $im1=imagecreatefromjpeg($target_path);
              imagecopyresampled($im,$im1,0,0,0,0,$w,$h,$w1,$h1);
              imagejpeg($im,$target_path,100);
              }
              imagedestroy($im);
              imagedestroy($im1); */
            $config['image_library'] = 'gd2';
            $config['source_image'] = $target_path;
            $config['width'] = $w;
            $config['height'] = $h;
            $config['new_image'] = $target_path;
            $CI->image_lib->clear();
            $CI->image_lib->initialize($config);
            $CI->image_lib->resize();
          } // if  $w	

          unlink($tempFile);
          $result = array('true', $img_name);
          return $result;
        } else { // else move_uploaded_file
          $result = array('false', '');
          return $result;
        } // if move_uploaded_file
      }// Check File
    }// if empty
  }

  public function add_image_fix_name($file, $main_path, $img_name, $w) {
    $CI = & get_instance();
    $CI->load->library('image_lib');
    if (!empty($_FILES)) {
      $fileTypes = array('jpg', 'png', 'gif');
      $fileParts = pathinfo($file['name']);
      if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
        $tempFile = $file['tmp_name'];
        $target_path = $main_path . $img_name;
        if (copy($tempFile, $target_path)) {
          list($w1, $h1) = getimagesize($target_path);
          if ($w1 > $w) {
            $percent = $w / $w1;
            $h = $h1 * $percent;
            $config['image_library'] = 'gd2';
            $config['source_image'] = $target_path;
            $config['width'] = $w;
            $config['height'] = $h;
            $config['new_image'] = $target_path;
            $CI->image_lib->clear();
            $CI->image_lib->initialize($config);
            $CI->image_lib->resize();
          } // if  $w	

          unlink($tempFile);
          $result = 'true';
          return $result;
        } else { // else move_uploaded_file
          $result = 'false';
          return $result;
        } // if move_uploaded_file
      }// Check File
    }// if empty
  }

  function edit_image($file, $main_path, $id, $w, $section, $editImage, $oldpic, $name = '') {
    $CI = & get_instance();
    $CI->load->library('image_lib');
    if ($editImage == "edit" or $editImage == '') {

      if ($name <> '') {
        $tempFile = $file;
        $surname = substr($name, -4);
      } else {
        $tempFile = $file['tmp_name'];
        $surname = substr($file['name'], -4);
      }

      if (strtolower($surname) == '.jpg' or strtolower($surname) <> '.gif' or strtolower($surname) == '.png') {
        $passgen = rand(0000, 9999);
        $img_name = $section . $id . $passgen . $surname;
        $target_path = $main_path . $img_name;
        if (move_uploaded_file($tempFile, $target_path)) {
          list($w1, $h1) = getimagesize($target_path);
          if ($w1 > $w) {
            $percent = $w / $w1;
            $h = $h1 * $percent;
            $config['image_library'] = 'gd2';
            $config['source_image'] = $target_path;
            $config['width'] = $w;
            $config['height'] = $h;
            $config['new_image'] = $target_path;
            $CI->image_lib->clear();
            $CI->image_lib->initialize($config);
            $CI->image_lib->resize();
          } // if  $w	



          unlink($tempFile);
          $result = array('true', $img_name);

          if ($editImage == "edit") {
            unlink($main_path . $oldpic);
          }
        }
      }// Check File
    } else if ($editImage == "old") {
      $result = array('true', $oldpic);
    }


    return $result;
  }

  public function add_vdo($file, $main_path, $id, $section, $text) {



    $tempFile = $file['tmp_name'];
    $surname = substr($file['name'], -4);
    $type = substr($file['name'], -3);
    $passgen = $this->passGen(10);
    $img_name = $text . $section . $id . $passgen . $surname;

    $target_path = $main_path . $img_name;

    if (copy($tempFile, $target_path)) {
      unlink($tempFile);

      $result = array('true', $img_name, $type, $file['size']);
      return $result;
    } else { // else move_uploaded_file
      $result = array('false', '', '', '');
      return $result;
    } // if move_uploaded_file
  }

  public function add_file($file, $main_path, $id, $section, $text) {



    $tempFile = $file['tmp_name'];
    $surname = substr($file['name'], -4);
    $type = substr($file['name'], -3);
    $passgen = $this->passGen(10);
    $img_name = $file['name'];
    $target_path = $main_path . $img_name;
    if (copy($tempFile, $target_path)) {

      unlink($tempFile);

      $result = array('true', $img_name, $type, $file['size']);
      return $result;
    } else { // else move_uploaded_file
      $result = array('false', '', '', '');
      return $result;
    } // if move_uploaded_file
  }

  public function edit_vdo($file, $main_path, $id, $section, $text, $editImage, $oldpic) {


    if ($editImage == "edit" or $editImage == '') {

      $tempFile = $file['tmp_name'];
      $surname = substr($file['name'], -4);
      $type = substr($file['name'], -3);
      $passgen = $this->passGen(10);
      $img_name = $text . $section . $id . $passgen . $surname;
      $target_path = $main_path . $img_name;
      if (move_uploaded_file($tempFile, $target_path)) {

        unlink($tempFile);
        $result = array('true', $img_name, $type, $file['size']);

        if ($editImage == "edit") {
          unlink($main_path . $oldpic);
        }
      }
    } else if ($editImage == "old") {
      $result = array('true', $oldpic, '', '');
    }

    return $result;
  }

  public function edit_vdo_fix($file, $main_path, $filename, $editImage, $oldpic) {


    if ($editImage == "edit" or $editImage == '') {

      $tempFile = $file['tmp_name'];
      $fileParts = pathinfo($file['name']);
      $img_name = $filename;
      $target_path = $main_path . $img_name;
      if (move_uploaded_file($tempFile, $target_path)) {

        unlink($tempFile);
        $result = array('true', $img_name, $fileParts['extension'], $file['size']);

        if ($editImage == "edit") {
          //unlink($main_path.$oldpic);
        }
      }
    } else if ($editImage == "old") {
      $result = array('true', $oldpic, '', '');
    }

    return $result;
  }

  public function get_day_name($date, $lang = 'th') {


    $time = date('D', $date);
    if ($lang == 'th') {
      $day = $this->date_array;
    } else {
      $day = $this->date_array_en;
    }

    return $day[$time];
  }

  public function data_format($format, $lang, $date) {

    switch ($format) {

      case 'full':
        if ($lang == 'th') {

          list($y, $m, $d) = explode("-", $date);
          $date_format = number_format($d) . " " . $this->month_th[number_format($m)] . " " . ($y + 543);
        } else {

          list($y, $m, $d) = explode("-", $date);
          $date_format = number_format($d) . " " . $this->month_en[number_format($m)] . " " . ($y);
        }
        break;
      case 'small':
        if ($lang == 'th') {

          list($y, $m, $d) = explode("-", $date);
          $date_format = number_format($d) . " " . $this->month_th_small[number_format($m)] . " " . substr(($y + 543), -2);
        }
        break;

      default:
        if ($lang == 'th') {

          list($y, $m, $d) = explode("-", $date);
          $date_format = number_format($d) . " " . $this->month_th[number_format($m)] . " " . ($y + 543);
        }

        break;
    }


    return $date_format;
  }

  public function month_format($format, $lang, $month) {
    switch ($format) {

      case 'full':
        if ($lang == 'th') {
          $month_format = $this->month_th[number_format($month)];
        } else {
          $month_format = $this->month_en[number_format($month)];
        }
        break;
      case 'small':
        if ($lang == 'th') {
          $month_format = $this->month_th_small[number_format($month)];
        }
        break;

      default:
        if ($lang == 'th') {
          $month_format = $this->month_th[number_format($month)];
        }

        break;
    }


    return $month_format;
  }

  public function alert($text, $linkurl = '') {

    $alert = "<script>alert('" . $text . "')";
    if ($linkurl <> '') {

      $alert.=";location.href='" . $linkurl . "' ";
    }

    $alert .="</script>";

    return $alert;
  }

  public function select_date($date_db, $name, $id, $val = '') {

    $select = '<select name="' . $name . '" id="' . $id . '"><option value="">--เลือกชั่วโมง--</option>';
    for ($i = 0; $i <= 23; $i++) {
      if ($i < 10) {
        $hour = '0' . $i;
      } else {
        $hour = $i;
      }

      $select.='<option value="' . $hour . '" ';

      if ($hour == $val) {
        $select.="selected";
      }

      $select .='>' . $hour . '</option>';
    }

    $select .='</select>';

    return $select;
  }

  public function select_time($date_db, $name, $id, $val = '') {

    $select = '<select name="' . $name . '" id="' . $id . '"><option value="">--เลือกนาที--</option>';
    for ($i = 0; $i <= 60; $i++) {
      if ($i < 10) {
        $hour = '0' . $i;
      } else {
        $hour = $i;
      }

      if (($i % 5) == 0) {

        $select.='<option value="' . $hour . '" ';

        if ($hour == $val) {
          $select.="selected";
        }

        $select .='>' . $hour . '</option>';
      }
    }

    $select .='</select>';

    return $select;
  }

  public function section($id, $name, $val = '') {

    $select = '<select name="' . $name . '" id="' . $name . '_' . $id . '"><option value="">--เลือกตอน--</option>';
    for ($i = 0; $i <= 10; $i++) {

      if ($i == 0) {
        $sec = 'Tralier';
        $nums = 999;
      } else {
        $sec = $i;
        $nums = $i;
      }

      $select.='<option value="' . $nums . '" ';

      if ($nums == $val) {
        $select.="selected";
      }

      $select .='>' . $sec . '</option>';
    }

    $select .='</select>';

    return $select;
  }

  public function get_mb($size) {

    $mb = $size / 1000000;
    $mb = number_format($mb, 2);

    return $mb;
  }

  public function limitText($text = null, $limit = 234) {


    $text = strip_tags($text);

    $text = str_replace("&nbsp;", " ", $text);

    $text = preg_replace('/\n+/', " ", strip_tags(trim($text)));

    $ret_text = "";

    $text_len = mb_strlen($text, 'UTF-8');

    if ($text_len <= $limit)
      $ret_text = $text;
    else
      $ret_text = mb_substr($text, 0, $limit, 'UTF-8') . " ...";

    return $ret_text;
  }

  //////////////////////  
  public function ViewNumberGet($content_id=0, $table_id=0){
        $CI = & get_instance();
    $CI->load->library('TPPY_Utils');
    
    return $CI->tppy_utils->ViewNumberGet($content_id, $table_id);
    // $CI = & get_instance();
    // $CI->load->library('tppymemcached');
    
    // switch($table_id) { 
      // case 'mul_content' : $field_name='mul_content_id'; $table_name='mul_content'; $view_count_field_name='view_count'; break; /* mul_content : 0*/ 
      // case 'mul_source' : $field_name='mul_source_id'; $table_name='mul_source'; $view_count_field_name='view_count'; break; /* mul_source : 7*/
      // case 'cms' : $field_name='cms_id'; $table_name='cms'; $view_count_field_name='view_count'; break; /* CMS : 21 */
      // case 'tv_program' : $field_name='content_id'; $table_name='tv_program'; $view_count_field_name='view_count'; break; /* TV_PROGRAM */
      // case 'tv_program_episode' : $field_name='content_child_id'; $table_name='tv_program_episode'; $view_count_field_name='view_count'; break; /* TV_PROGRAM_EPISODE */
      // default : return 0; break;
    // }
    
    // $key = "vc_$table_id+$content_id";
    // if(!$content_view_count=$CI->tppymemcached->get($key)){
      // $CI->db->select($view_count_field_name)
                   // ->from($table_name)
                   // ->where($field_name, $content_id);
      // $content_view_count=intval($CI->db->get()->row()->$view_count_field_name, 0);
      // $CI->tppymemcached->set($key, intval($content_view_count));
    // }
    // return $content_view_count;
  }
  public function ViewNumberSet($content_id=0, $table_id=0){
        $CI = & get_instance();
    $CI->load->library('TPPY_Utils');
    
      return $CI->tppy_utils->ViewNumberSet($content_id, $table_id);
      // $CI = & get_instance();
      // $CI->load->library('tppymemcached');
      // $key = "vc_$table_id+$content_id";
      // $this->ViewNumberGet($content_id, $table_id);
      
      // switch($table_id) { 
        // case 'mul_content' : $field_name='mul_content_id'; $table_name='mul_content'; $view_count_field_name='view_count'; break; /* mul_content : 0*/ 
        // case 'mul_source' : $field_name='mul_source_id'; $table_name='mul_source'; $view_count_field_name='view_count'; break; /* mul_source : 7*/
        // case 'cms' : $field_name='cms_id'; $table_name='cms'; $view_count_field_name='view_count'; break; /* CMS : 21 */
        // case 'tv_program' : $field_name='content_id'; $table_name='tv_program'; $view_count_field_name='view_count'; break; /* TV_PROGRAM */
        // case 'tv_program_episode' : $field_name='content_child_id'; $table_name='tv_program_episode'; $view_count_field_name='view_count'; break; /* TV_PROGRAM_EPISODE */
        // default : return 0; break;
      // }
      
      // $content_view_count = $CI->tppymemcached->increment($key, 1);
      // if($content_view_count < 30 || $content_view_count % 7 == 0){
        // $DBEdit=$CI->load->database('edit',TRUE);
        // $DBEdit->where($field_name, $content_id)
                    // ->set($view_count_field_name, $content_view_count)
                    // ->update($table_name);
        // $CI->tppymemcached->set($key, intval($content_view_count));
      // }
      // return $content_view_count;
  }
////////////////////// 
  
  public function getViewNumber($content_id, $table) {
    switch($table) { 
      case 0 : return $this->ViewNumberGet($content_id, 'mul_content'); break; /* mul_content : 0*/ 
      case 7 : return $this->ViewNumberGet($content_id, 'mul_source'); break; /* mul_source : 7*/
      case 21 : return $this->ViewNumberGet($content_id, 'cms'); break; /* CMS : 21 */
      case 15 :  return $this->ViewNumberGet($content_id, 'webboard_post'); break; /* CMS : 15 */
	  //case 19 :  return $this->ViewNumberGet($content_id, 'lesson_plan'); break; /* lesson_plan : 19 */
    }
      
    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $key = "getViewNumber_allgey_content_" . $content_id . "_table_" . $table;
    //$query = mysql_query($sql, $DBSelect);
//        $view = 0;
    if ($view = $CI->tppymemcached->get($key)) {
      return $view;
    } else {
      $CI->load->model('connect_db_model', 'cdm');
	  //$CI->cdm->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
      $sql = "SELECT mul_view_value FROM mul_sum_view WHERE mul_content_id = '" . $content_id . "' AND mul_view_table = '" . $table . "' ";
      $query = $CI->cdm->_query_row($sql, 'select');
	  //$CI->cdm->query("COMMIT");
      //_pr($query);//จำ
      //exit;//จำ
      if ($query) {
        $view = number_format((int)$query[0]['mul_view_value']);
        $CI->tppymemcached->set($key, $view);
      }
    }
    return $view;
    //echo $sql;
//        $num_views_cache = $CI->tppymemcached->get("getViewNumber_allgey_content_" . $content_id . "_table_" . $table);
    //if($query){
//        return number_format($num_views_cache[0]['mul_view_value']);
  }

  public function getViewNumber_project($content_id, $table) {

    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');
    $sql = "SELECT sum_value FROM cmt_02_sum_view WHERE mul_content_id = '" . $content_id . "' AND contest_year = '" . $table . "' ";
    //$query = mysql_query($sql, $DBSelect);
    if ($CI->tppymemcached->get("getViewNumber_project_allgey_content_" . $content_id . "_table_" . $table) == '') {
      $query = $CI->cdm->_query_row($sql, 'select_competition');
      $CI->tppymemcached->set("getViewNumber_project_allgey_content_" . $content_id . "_table_" . $table, $query);
    }
    //echo $sql;
    $num_views_cache = $CI->tppymemcached->get("getViewNumber_project_allgey_content_" . $content_id . "_table_" . $table);
    //if($query){

    return number_format($num_views_cache[0]['sum_value']);
  }

  public function getVoteNumber_project($content_id, $table) {

    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');
    $sql = "SELECT sum_value FROM cmt_02_sum_vote WHERE mul_content_id = '" . $content_id . "' AND contest_year = '" . $table . "' ";

    $num_vote = $CI->cdm->_query_row($sql, 'select_competition');


    return number_format($num_vote[0]['sum_value']);
  }

  public function addViewVal_project($content_id, $table) {

    if (is_numeric($content_id)) {

      $CI = & get_instance();
      $CI->load->library('tppymemcached');
      $CI->load->model('connect_db_model', 'cdm');
      $table_query = 'cmt_02_sum_view';
      $ip = $this->get_ip_address();
      //$check_time='-2 hours';
      $check_val = 5;
      $check_time = '-10 minutes';
      $content_id = intval($content_id);
      $table = intval($table);
      if ($table == 7) {

        $content_id = substr('000000' . $content_id, -6);
      }
      $memcache_view_time = $CI->tppymemcached->get("view_project_time_" . $table . "_" . $content_id);
      $memcache_view_ip = $CI->tppymemcached->get("view_project_ip_" . $table . "_" . $content_id);
      if (!$memcache_view_value = $CI->tppymemcached->get("view_project_value_" . $table . "_" . $content_id)) {
        $CI->tppymemcached->set("view_project_value_" . $table . "_" . $content_id, 1, 3600 * 24 * 7);
        $CI->tppymemcached->set("view_project_time_" . $table . "_" . $content_id, time(), 3600 * 24 * 7);
        $CI->tppymemcached->set("view_project_ip_" . $table . "_" . $content_id, $ip, 3600 * 24 * 7);
      } else {

        if ($memcache_view_value > $check_val or $memcache_view_time <= strtotime($check_time)) {
          $val = $memcache_view_value;
          $sql = "select * from " . $table_query . " where mul_content_id='" . $content_id . "' and contest_year='" . $table . "' ";
          $nums = $CI->cdm->_nums($sql, 'select_competition');
          if ($nums > 0) {
            $result = $CI->cdm->_query_row($sql, 'select_competition');
            $val = $result[0]['sum_value'] + $val;
            $data = array('sum_value' => $val, 'last_ip_address' => $ip);
            $where = array('mul_content_id' => $content_id, 'contest_year' => $table);
            $result = $CI->cdm->_query_update($data, $where, $table_query, 'edit_competition');
            //	echo 'edit';
          } else {
            $data = array('mul_content_id' => $content_id, 'sum_value' => $memcache_view_value, 'contest_year' => $table, 'last_ip_address' => $ip, 'last_date_time' => date('Y-m-d H:i:s'));
            $result = $CI->cdm->_query_insert($data, $table_query, 'edit_competition');
            //	echo 'insert';
          }

          $CI->tppymemcached->delete("view_project_value_" . $table . "_" . $content_id);
          $CI->tppymemcached->delete("view_project_time_" . $table . "_" . $content_id);
          $CI->tppymemcached->delete("view_project_ip_" . $table . "_" . $content_id);
        } else {
          //if ($ip <> $memcache_view_ip){ //เอา Check IP ออก 
          $CI->tppymemcached->set("view_project_ip_" . $table . "_" . $content_id, $ip, 3600 * 24 * 7);
          $CI->tppymemcached->increment("view_project_value_" . $table . "_" . $content_id);
          //}
        } //END $memcache_view_value>200 or $memcache_view_time<=strtotime($check_time)
      }// END $memcache_view_value==false || $memcache_view_time==false
    } else {
      exit();
    }
  }

// END addViewVal

  public function getViewCenter($content_id, $table, $group) {

    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');
    $table_query = $group . '_sum_view';
    $sql = "select COALESCE(content_view_val,0) as content_view_val from " . $table_query . " where content_view_table='" . $table . "' and content_view_id='" . $content_id . "'  ";

    if ($CI->tppymemcached->get('getViewConter_' . $table . '_' . $group . '_' . $content_id) == '') {

      $result = $CI->cdm->_query_row($sql, 'select');
      $CI->tppymemcached->set('getViewConter_' . $table . '_' . $group . '_' . $content_id, $result, 1200);
    }

    $result = $CI->tppymemcached->get('getViewConter_' . $table . '_' . $group . '_' . $content_id);
	if($result[0]['content_view_val'] != "" || !empty($result[0]['content_view_val'])) {
		$return_view = number_format(str_replace(',','',$result[0]['content_view_val']));
	}

    return $return_view;
  }

  public function addViewVal_old($content_id, $table) {

    if (is_numeric($content_id)) {
      switch($table) { 
        case 0 : return $this->ViewNumberSet($content_id, 'mul_content'); break; /* mul_content : 0*/ 
        case 7 : return $this->ViewNumberSet($content_id, 'mul_source'); break; /* mul_source : 7*/
        case 21 : return $this->ViewNumberSet($content_id, 'cms'); break; /* CMS : 21 */
        case 15 :  return $this->ViewNumberSet($content_id, 'webboard_post'); break; /* CMS : 15 */
		//case 19 :  return $this->ViewNumberSet($content_id, 'lesson_plan'); break; /* lesson_plan : 19 */
      }
      $CI = & get_instance();
      $CI->load->library('tppymemcached');
      $CI->load->model('connect_db_model', 'cdm');
      $table_query = 'mul_sum_view';
      $ip = $this->get_ip_address();
      //$check_time='-2 hours';
      $check_val = 5;
      $check_time = '-10 minutes';
      $content_id = intval($content_id);
      $table = intval($table);
      if ($table == 7) {

        $content_id = substr('000000' . $content_id, -6);
      }
      $memcache_view_time = $CI->tppymemcached->get("view_time_" . $table . "_" . $content_id);
      $memcache_view_ip = $CI->tppymemcached->get("view_ip_" . $table . "_" . $content_id);
      if (!$memcache_view_value = $CI->tppymemcached->get("view_value_" . $table . "_" . $content_id)) {
        $CI->tppymemcached->set("view_value_" . $table . "_" . $content_id, 1, 3600 * 24 * 7);
        $CI->tppymemcached->set("view_time_" . $table . "_" . $content_id, time(), 3600 * 24 * 7);
        $CI->tppymemcached->set("view_ip_" . $table . "_" . $content_id, $ip, 3600 * 24 * 7);
      } else {

        if ($memcache_view_value > $check_val or $memcache_view_time <= strtotime($check_time)) {
          $val = $memcache_view_value;
          $sql = "select * from " . $table_query . " where mul_content_id='" . $content_id . "' and mul_view_table='" . $table . "' ";
          $nums = $CI->cdm->_nums($sql, 'select');
          if ($nums > 0) {
            $result = $CI->cdm->_query_row($sql, 'select');
            $val = $result[0]['mul_view_value'] + $val;
            $data = array('mul_view_value' => $val, 'last_ip_address' => $ip);
            //$where = array('mul_content_id' => $content_id, 'mul_view_table' => $table);
            $where = 'mul_content_id = "' . $content_id . '" AND mul_view_table = "' . $table . '" ';
            $result = $CI->cdm->_query_update($data, $where, $table_query, 'edit');
            //	echo 'edit';
          } else {
            $data = array('mul_content_id' => $content_id, 'mul_view_value' => $memcache_view_value, 'mul_view_table' => $table, 'last_ip_address' => $ip);
            $result = $CI->cdm->_query_insert($data, $table_query, 'edit');
            //	echo 'insert';
          }

          $CI->tppymemcached->delete("view_value_" . $table . "_" . $content_id);
          $CI->tppymemcached->delete("view_time_" . $table . "_" . $content_id);
          $CI->tppymemcached->delete("view_ip_" . $table . "_" . $content_id);
        } else {
          //if ($ip <> $memcache_view_ip){ //เอา Check IP ออก 
          $CI->tppymemcached->set("view_ip_" . $table . "_" . $content_id, $ip, 3600 * 24 * 7);
          $CI->tppymemcached->increment("view_value_" . $table . "_" . $content_id);
          //}
        } //END $memcache_view_value>200 or $memcache_view_time<=strtotime($check_time)
      }// END $memcache_view_value==false || $memcache_view_time==false
    } else {
      exit();
    }
  }

// END addViewVal

  public function addViewVal($content_id, $table, $group) {

    if (is_numeric($content_id)) {
      
      switch($table) { 
        case 0 : return $this->ViewNumberSet($content_id, 'mul_content'); break; /* mul_content : 0*/ 
        case 7 : return $this->ViewNumberSet($content_id, 'mul_source'); break; /* mul_source : 7*/
        case 21 : return $this->ViewNumberSet($content_id, 'cms'); break; /* CMS : 21 */
        case 15 :  return $this->ViewNumberSet($content_id, 'webboard_post'); break; /* CMS : 15 */
		//case 19 :  return $this->ViewNumberSet($content_id, 'lesson_plan'); break; /* lesson_plan : 19 */
      }
      $CI = & get_instance();
      $CI->load->library('tppymemcached');
      $CI->load->model('connect_db_model', 'cdm');
      $table_query = $group . '_sum_view';
      $ip = $this->get_ip_address();
      //$check_time='-2 hours';
      $check_val = 3;
      $check_time = '-10 minutes';
      $memcache_view_value = $CI->tppymemcached->get("view_value_" . $table . "_" . $content_id . "_" . $group);
      $memcache_view_time = $CI->tppymemcached->get("view_time_" . $table . "_" . $content_id . "_" . $group);
      $memcache_view_ip = $CI->tppymemcached->get("view_ip_" . $table . "_" . $content_id . "_" . $group);
      if ($memcache_view_value == false) {
        $CI->tppymemcached->set("view_value_" . $table . "_" . $content_id . "_" . $group, 1, 3600 * 24 * 7);
        $CI->tppymemcached->set("view_time_" . $table . "_" . $content_id . "_" . $group, time());
        $CI->tppymemcached->set("view_ip_" . $table . "_" . $content_id . "_" . $group, $ip);
      } else {

        if ($memcache_view_value > $check_val or $memcache_view_time <= strtotime($check_time)) {
          $val = $memcache_view_value;
          $sql = "select view_id,content_view_val from " . $table_query . " where content_view_id='" . $content_id . "' and content_view_table='" . $table . "' ";
          $nums = $CI->cdm->_nums($sql, 'select');
          if ($nums > 0) {
            $result = $CI->cdm->_query_row($sql, 'select');
            $val = $result[0]['content_view_val'] + $val;
            $data = array('content_view_val' => $val);
            $where = array('content_view_id' => $content_id, 'content_view_table' => $table);
            $result = $CI->cdm->_query_update($data, $where, $table_query, 'edit');
            //	echo 'edit';
          } else {
            $data = array('content_view_id' => $content_id, 'content_view_val' => 1, 'content_view_table' => $table);
            $result = $CI->cdm->_query_insert($data, $table_query, 'edit');
            //	echo 'insert';
          }

          $CI->tppymemcached->set("view_value_" . $table . "_" . $content_id . "_" . $group, 0);
          $CI->tppymemcached->set("view_time_" . $table . "_" . $content_id . "_" . $group, time());
          $CI->tppymemcached->set("view_ip_" . $table . "_" . $content_id . "_" . $group, 0);
        } else {
          //if ($ip <> $memcache_view_ip){ //เอา Check IP ออก 
          $CI->tppymemcached->set("view_ip_" . $table . "_" . $content_id . "_" . $group, $ip);
          $CI->tppymemcached->increment("view_value_" . $table . "_" . $content_id . "_" . $group);
          //}
        } //END $memcache_view_value>200 or $memcache_view_time<=strtotime($check_time)
      }// END $memcache_view_value==false || $memcache_view_time==false
    } else {
      exit();
    }
  }

// END addViewVal

  public function get_filevdo_name($name, $filetype_all, $filetype = 'large') {

    $filename = substr($name, 0, -4);
    $file_type = explode(',', $filetype_all);

    if (in_array($filetype, $file_type)) {
      $return = $filename . '_' . $filetype . '.mp4';
    } else {
      $return = $filename . '.mp4';
    }

    return $return;
  }

//END get_filevdo_name

  public function get_image_name($name, $size = '128x96') {

    $filename = substr($name, 0, -4);
    return $filename . '_' . $size . '.png';
  }

// END get_image_name

  public function get_level($level_id = 0, $type = 'all', $name = 'level_id') {
    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');

    if ($type == 'all') {

      $sql = "select * from mul_level order by mul_level_id ASC ";
    } else if ($type == 'primary') {

      $sql = "select * from mul_level where mul_level_id<>mul_level_parent_id and mul_level_id > 10 order by mul_level_id ASC ";
    } else {
      $sql = "select * from mul_level where mul_level_id=mul_level_parent_id order by mul_level_id ASC ";
    }


    if ($CI->tppymemcached->get('get_level_centent_' . $level_id . '_' . $type) == '') {

      $result = $CI->cdm->_query_row($sql, 'select');
      $CI->tppymemcached->set('get_level_centent_' . $level_id . '_' . $type, $result, 7200);
    }

    $rs = $CI->tppymemcached->get('get_level_centent_' . $level_id . '_' . $type);
    $return_level = '<select name="' . $name . '" id="' . $name . '"><option value="">-- เลือกระดับชั้น --</option> ';

    foreach ($rs as $k => $v) {

      if ($v['mul_level_id'] == $level_id) {

        $select = 'selected';
      } else {
        $select = '';
      }

      $return_level.='<option value="' . $v['mul_level_id'] . '" ' . $select . '>' . $v['mul_level_name'] . '</option>';
    }


    $return_level.='</select> ';

    return $return_level;
  }

  public function get_cagegory($category_id = 0) {
    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');

    $sql = "select * from mul_category where mul_category_id=mul_parent_id order by mul_category_id ASC ";

    if ($CI->tppymemcached->get('get_category_centent_' . $category_id . '_' . $type) == '') {

      $result = $CI->cdm->_query_row($sql, 'select');
      $CI->tppymemcached->set('get_category_centent_' . $category_id . '_' . $type, $result, 7200);
    }

    $rs = $CI->tppymemcached->get('get_category_centent_' . $category_id . '_' . $type);
    $return_level = '<select name="category_id" id="category_id"><option value="">-- เลือกหมวดหมู่วิชา --</option> ';

    foreach ($rs as $k => $v) {

      if ($v['mul_category_id'] == $category_id) {

        $select = 'selected';
      } else {
        $select = '';
      }

      $return_level.='<option value="' . $v['mul_category_id'] . '" ' . $select . '>' . $v['mul_category_name'] . '</option>';

      $sql_sub = "select * from mul_category where mul_parent_id='" . $v['mul_category_id'] . "' order by mul_category_id ASC ";
      $result_sub = $CI->cdm->_query_row($sql_sub, 'select');
      foreach ($result_sub as $kk => $vv) {
        if ($vv['mul_category_id'] == $category_id) {

          $select = 'selected';
        } else {
          $select = '';
        }

        $return_level.='<option value="' . $vv['mul_category_id'] . '" ' . $select . '>----' . $vv['mul_category_name'] . '</option>';
      }
    }


    $return_level.='</select> ';

    return $return_level;
  }

  public function get_cagegory8($category_id = 0) {
    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');

    $sql = "select * from mul_category where mul_category_id=mul_parent_id and mul_category_id BETWEEN '1000' and '8000' order by mul_category_id ASC ";

    if ($CI->tppymemcached->get('get_cagegory8' . $category_id . '_' . $type) == '') {

      $result = $CI->cdm->_query_row($sql, 'select');
      $CI->tppymemcached->set('get_cagegory8' . $category_id . '_' . $type, $result, 7200);
    }

    $rs = $CI->tppymemcached->get('get_cagegory8' . $category_id . '_' . $type);
    $return_level = '<select name="category_id" id="category_id"><option value="">-- เลือกหมวดหมู่วิชา --</option> ';

    foreach ($rs as $k => $v) {

      if ($v['mul_category_id'] == $category_id) {

        $select = 'selected';
      } else {
        $select = '';
      }

      $return_level.='<option value="' . $v['mul_category_id'] . '" ' . $select . '>' . $v['mul_category_name'] . '</option>';
    }


    $return_level.='</select> ';

    return $return_level;
  }

  public function get_provinct_select($province_id = 0, $name = 'provinct_id') {
    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');

    $sql = "select * from province order by province_name asc";

    if (!$rs = $CI->tppymemcached->get('get_provinct_select' . $province_id)) {

      $result = $CI->cdm->_query_row($sql, 'select');
      $CI->tppymemcached->set('get_provinct_select' . $province_id, $result, 7200);
      $rs = $CI->tppymemcached->get('get_provinct_select' . $province_id);
    }


    $return_province = '<select name="' . $name . '" id="' . $name . '"><option value="">-- เลือกจังหวัด --</option> ';

    foreach ($rs as $k => $v) {

      if ($v['province_id'] == $province_id) {

        $select = 'selected';
      } else {
        $select = '';
      }

      $return_province.='<option value="' . $v['province_id'] . '" ' . $select . '>' . $v['province_name'] . '</option>';
    }


    $return_province.='</select> ';

    return $return_province;
  }

  public function get_banner($position) {

    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');

    switch ($position) {

      case "top242":

        $sql = "select * from banner_center where banner_id in (2,30,13,16) and record_status=1 ";

        break;

      case "tv242":

        $sql = "select * from banner_center where banner_id in (2,1,13,15) and record_status=1 ";

        break;

      case "tvleft242":

        $sql = "select * from banner_center where banner_id in (2,1,13,15) and record_status=1 ";

        break;

      case "top996":

        $sql = "select * from banner_center where  banner_id in (9) and record_status=1 ";

        break;

      case "topupload":

        $sql = "select * from banner_center where banner_id in (11) and record_status=1 ";

        break;

      case "know242":

        $sql = "select * from banner_center where banner_id in (1,15,7,6) and record_status=1  ";

        break;

      case "dhama242":

        $sql = "select * from banner_center where banner_id in (19,14,17) and record_status=1  ";

        break;

      case "dhama996x143":

        $sql = "select * from banner_center where banner_id in (8) and record_status=1  ";

        break;

      case "campaign_relate":

        $sql = "select * from banner_center where banner_id in (27,13,32) and record_status=1 ";

        break;

      case "olympic_relate":

        $sql = "select * from banner_center where banner_id in (27,13,28) and record_status=1 ";

        break;

      case "dhama996x90":

        $sql = "select * from banner_center where banner_id in (18) and record_status=1  ";

        break;
    }

    if ($CI->tppymemcached->get('get_banner_show_' . $position) == '') {

      $result = $CI->cdm->_query_row($sql, 'select');
      $CI->tppymemcached->set('get_banner_show_' . $position, $result);
    }

    $rs = $CI->tppymemcached->get('get_banner_show_' . $position);
    foreach ($rs as $k => $v) {
      $path = $this->get_media_path('image');
      if ($position == 'dhama242' or $position == 'tvleft242' or $position == 'campaign_relate' or $position == 'olympic_relate') {

        $banner .='<a href="' . htmlspecialchars($v['banner_link']) . '" title="' . htmlspecialchars($v['banner_desc']) . '" class="bnSide" target="_blank"><img src="' . $path . $v['banner_image_path'] . '/' . $v['banner_image'] . ' " border="0" alt="' . htmlspecialchars($v['banner_desc']) . '" /></a>';
      } else if ($position == 'dhama996x90' or $position == 'dhama996x143' or $position == 'top996' or $position == 'topupload') {

        $banner .='<a href="' . htmlspecialchars($v['banner_link']) . '" title="' . htmlspecialchars($v['banner_desc']) . '" target="_blank"><img src="' . $path . $v['banner_image_path'] . '/' . $v['banner_image'] . ' " border="0" alt="' . htmlspecialchars($v['banner_desc']) . '" /></a>';
      } else {

        switch ($k) {

          case 0 : $banner .='<a href="' . htmlspecialchars($v['banner_link']) . '" title="' . htmlspecialchars($v['banner_desc']) . '" class="first" target="_blank"><img src="' . $path . $v['banner_image_path'] . '/' . $v['banner_image'] . ' " border="0" alt="' . htmlspecialchars($v['banner_desc']) . '" width="242" /></a>';
            break;
          case 1 : $banner .='<a href="' . htmlspecialchars($v['banner_link']) . '" title="' . htmlspecialchars($v['banner_desc']) . '" target="_blank"><img src="' . $path . $v['banner_image_path'] . '/' . $v['banner_image'] . ' " border="0" alt="' . htmlspecialchars($v['banner_desc']) . '" width="242" /></a>';
            break;
          case 2 : $banner .='<a href="' . htmlspecialchars($v['banner_link']) . '" title="' . htmlspecialchars($v['banner_desc']) . '" target="_blank"><img src="' . $path . $v['banner_image_path'] . '/' . $v['banner_image'] . ' " border="0" alt="' . htmlspecialchars($v['banner_desc']) . '" width="242" /></a>';
            break;
          case 3 : $banner .='<a href="' . htmlspecialchars($v['banner_link']) . '" title="' . htmlspecialchars($v['banner_desc']) . '" class="last" target="_blank"><img src="' . $path . $v['banner_image_path'] . '/' . $v['banner_image'] . ' " border="0" alt="' . htmlspecialchars($v['banner_desc']) . '" width="242" /></a>';
            break;
        }
      }
    }
    return $banner;
  }

  public function show_banner($id, $not_in = null) {
    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');
    $path = $this->get_media_path('image');
    $sql_banner_main = "select * from recommend_center_group where group_id='" . $id . "' and group_type_id='2' ";
    $result_banner_main = $CI->cdm->_query_row($sql_banner_main, 'select');
    $rs_info = $result_banner_main[0];
    list($w, $h) = explode('x', $rs_info['group_size']);
    if ($rs_info['group_position'] == 'center') {
      $class = '';
    } else {
      $class = 'bnSide';
    }
    if ($id == 15) {
      $sql_banner = "select a.*,b.* from recommend_center_group_list a left join banner_center b on a.recommend_id=b.banner_id where a.group_id='" . $id . "' and a.record_status=1 and b.banner_id <> '" . $not_in . "' order by rand(" . date('H') . ") limit 0,1 ";
    } else {
      $sql_banner = "select a.*,b.* from recommend_center_group_list a left join banner_center b on a.recommend_id=b.banner_id where a.group_id='" . $id . "' and a.record_status=1 and b.banner_id <> '" . $not_in . "' order by a.position_no ASC limit 0," . $rs_info['group_limit'] . "  ";
    }
    if ($CI->tppymemcached->get('cache_banner_set_' . $id . '_' . $not_in) == '') {

      $result_banner = $CI->cdm->_query_row($sql_banner, 'select');
      $CI->tppymemcached->set('cache_banner_set_' . $id . '_' . $not_in, $result_banner);
    }

    $rs = $CI->tppymemcached->get('cache_banner_set_' . $id . '_' . $not_in);

    foreach ($rs as $k => $v) {
      if ($rs_info['group_position'] == 'center') {
        if ($k == 0) {
          $class = 'first';
        } else if ($k == $rs_info['group_limit'] - 1) {
          $class = 'last';
        } else {
          $class = '';
        }
      }

      $banner .='<a href="' . htmlspecialchars($v['banner_link']) . '" title="' . htmlspecialchars($v['banner_desc']) . '" class="' . $class . '" target="_blank"><img src="' . $path . $v['banner_image_path'] . '/' . $v['banner_image'] . ' " border="0" alt="' . htmlspecialchars($v['banner_desc']) . '" width="' . $w . '" height="' . $h . '"/></a>';
    }

    return $banner;
  }

  public function show_banne_fade($id, $not_in = null) {
    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');
    $path = $this->get_media_path('image');
    $sql_banner_main = "select * from recommend_center_group where group_id='" . $id . "' and group_type_id='2' ";
    $result_banner_main = $CI->cdm->_query_row($sql_banner_main, 'select');
    $rs_info = $result_banner_main[0];
    list($w, $h) = explode('x', $rs_info['group_size']);
    if ($rs_info['group_position'] == 'center') {
      $class = '';
    } else {
      $class = 'bnSide';
    }

    $sql_banner = "select a.*,b.* from recommend_center_group_list a left join banner_center b on a.recommend_id=b.banner_id where a.group_id='" . $id . "' and a.record_status=1 and b.banner_id <> '" . $not_in . "' order by a.position_no ASC limit 0," . $rs_info['group_limit'] . "  ";
    if ($CI->tppymemcached->get('cache_banner_set_' . $id) == '') {

      $result_banner = $CI->cdm->_query_row($sql_banner, 'select');
      $CI->tppymemcached->set('cache_banner_set_' . $id, $result_banner);
    }

    $rs = $CI->tppymemcached->get('cache_banner_set_' . $id);

    foreach ($rs as $k => $v) {
      if ($rs_info['group_position'] == 'center') {
        if ($k == 0 || $k == 4) {
          $class = 'first';
        } else if ($k == 3 || $k == $rs_info['group_limit'] - 1) {
          $class = 'last';
        } else {
          $class = '';
        }
      }

      if ($k == 0) {
        $banner.='<div id="banner_242_top_zone1">';
      } else if ($k == 4) {
        $banner.='</div><div id="banner_242_top_zone2">';
      }
      $banner .='<a href="' . htmlspecialchars($v['banner_link']) . '" title="' . htmlspecialchars($v['banner_desc']) . '" class="' . $class . '" target="_blank"><img src="' . $path . $v['banner_image_path'] . '/' . $v['banner_image'] . ' " border="0" alt="' . htmlspecialchars($v['banner_desc']) . '" width="' . $w . '" height="' . $h . '"/></a>';
    }

    $banner .='</div>';
    return $banner;
  }

  public function add_user_point($table, $member_id, $point, $ip, $content_id) {
    /*

      table=30 คือ คะแนนที่ได้จากการเข้าร่วม Campaign

     */
    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');

    $data = array(
        'scorekeep_content_id' => $content_id,
        'scorekeep_value' => $point,
        'scorekeep_table' => $table,
        'member_id' => $member_id,
        'scorekeep_type' => 0,
        'user_ip' => $ip,
        'scorekeep_datetime' => date('Y-m-d H:i:s')
    );
    $result = $CI->cdm->_query_insert($data, 'user_scorekeep', 'edit');

    if ($result[0]) {

      $sqlUp = "UPDATE blog SET blog_point = blog_point + " . $point . " WHERE member_id = '" . $member_id . "' ";
      $result_blog = $CI->cdm->_query_update_manual($sqlUp, 'edit');
    }
  }

  public function get_icon_doc($type) {

    switch ($type) {

      case "d":$return_icon = '<img src="' . base_url() . 'assets/images/icon/icon_18x_doc.gif" width="18" height="18" alt="" border="0"  />';
        break;
      case "f":$return_icon = '<img src="' . base_url() . 'assets/images/icon/icon_18x_swf.gif" width="18" height="18" alt="" border="0" />';
        break;
      case "v":$return_icon = '<img src="' . base_url() . 'assets/images/icon/icon_18x_vdo.gif" width="18" height="18" alt="" border="0"  />';
        break;
      case "a":$return_icon = '<img src="' . base_url() . 'assets/images/icon/icon_18x_snd.gif" width="18" height="18" alt="" border="0" />';
        break;
      default :$return_icon = '<img src="' . base_url() . 'assets/images/icon/icon_18x_doc.gif" width="18" height="18" alt="" border="0" />';
        break;
    }

    return $return_icon;
  }

  public function get_icon_level($type, $size = 'small') {

    if ($size == 'small') {

      $return_icon = '<img src="' . base_url() . 'assets/images/icon/level/' . $type . '.png" width="30" height="30" alt="" border="0" />';
    } else if ($size == 'large') {

      $return_icon = '<img src="' . base_url() . 'assets/images/icon/level/over/' . $type . '.png" width="48" height="48" alt="" border="0" />';
    }

    return $return_icon;
  }

  public function get_level_name($level_id) {

    switch ($level_id) {

      case 11: $level_name = 'ประถมศึกษาปีที่ 1';
        break;
      case 12: $level_name = 'ประถมศึกษาปีที่ 2';
        break;
      case 13: $level_name = 'ประถมศึกษาปีที่ 3';
        break;
      case 21: $level_name = 'ประถมศึกษาปีที่ 4';
        break;
      case 22: $level_name = 'ประถมศึกษาปีที่ 5';
        break;
      case 23: $level_name = 'ประถมศึกษาปีที่ 6';
        break;
      case 31: $level_name = 'มัธยมศึกษาปีที่ 1';
        break;
      case 32: $level_name = 'มัธยมศึกษาปีที่ 2';
        break;
      case 33: $level_name = 'มัธยมศึกษาปีที่ 3';
        break;
      case 41: $level_name = 'มัธยมศึกษาปีที่ 4';
        break;
      case 42: $level_name = 'มัธยมศึกษาปีที่ 5';
        break;
      case 43: $level_name = 'มัธยมศึกษาปีที่ 6';
        break;
    }

    return $level_name;
  }

  public function get_category_name($cat_type = 'cms', $cat_id) {

    $CI = & get_instance();
    $CI->load->model('cms_list_model', 'cms_model');

    $result = $CI->cms_model->_query_category_name($cat_type, $cat_id);
    $return_cat_name = $result[0]['cat_name'];

    return $return_cat_name;
  }

  public function get_category_parent($cat_type = 'cms', $cat_id) {

    $CI = & get_instance();
    $CI->load->model('cms_list_model', 'cms_model');

    $result = $CI->cms_model->_query_category_parent_id($cat_type, $cat_id);
    $return_cat_name = $result[0]['cat_parent_id'];

    return $return_cat_name;
  }

  public function get_vdo_qty($file, $file_play) {

    $source_array = array('medium', '480p', '720p', '1080p', '320p', '140p', 'medium', 'hd720p', 'hd480p', 'large');
    foreach ($source_array as $k => $v) {

      if ($check_ok <> 1) {

        if (file_exists(substr($file, 0, -4) . '_' . $v . '.mp4')) {

          $vdo = substr($file_play, 0, -4) . '_' . $v . '.mp4';
          $check_ok = 1;
        }
      }
    }

    if ($vdo == '') {

      $vdo = $file_play;
    }

    /* 	 if(file_exists(substr($file,0,-4).'_medium.mp4')){

      $vdo =substr($file_play,0,-4).'_medium.mp4';

      }else if(file_exists(substr($file,0,-4).'_hd720p.mp4')){

      $vdo = substr($file_play,0,-4).'_hd720p.mp4';

      }else if(file_exists(substr($file,0,-4).'_hd480p.mp4')){

      $vdo = substr($file_play,0,-4).'_hd480p.mp4';

      }else if(file_exists(substr($file,0,-4).'_large.mp4')){

      $vdo= substr($file_play,0,-4).'_large.mp4';

      }else{

      $vdo= $file_play;

      }
     */
    return $vdo;
  }

  public function check_hd($file, $file_play) {
    $source_array = array('1080p', '720p', 'hd720p');

    foreach ($source_array as $k => $v) {
      if (file_exists(substr($file, 0, -4) . '_' . $v . '.mp4')) {

        $vdo = substr($file_play, 0, -4) . '_' . $v . '.mp4';
      }
    }

    return $vdo;
  }

  public function check_exists($type = 'image', $path, $filename, $year = 'noyear') {
    $path_full_check = $this->set_media_path_full($type, $year);

    if (file_exists($path_full_check . $path . '/' . $filename)) {
      return 1;
    } else {
      return 0;
    }
  }

  public function check_format_url($name = null) {

    $url = str_replace($this->replace_check, '-', $name);
    return $url;
  }

  public function social_share($link) {

    $social = '<div class="campaign_social" style="margin:auto;height:50px; margin-top:0px">
                    <div style="float: right">
                       <div id="fb-root"></div>
                        <script>(function(d, s, id) {
                          var js, fjs = d.getElementsByTagName(s)[0];
                          if (d.getElementById(id)) return;
                          js = d.createElement(s); js.id = id;
                          js.src = "//connect.facebook.net/th_TH/all.js#xfbml=1";
                          fjs.parentNode.insertBefore(js, fjs);
                        }(document, "script", "facebook-jssdk"));</script>
                        <div class="fb-like" data-href="' . $link . '" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
                    </div>
                    <div style="float: right">
                        <a href="' . $link . '" class="twitter-share-button" data-lang="en">Tweet</a>
                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    </div>
                    <div style="float:right">
                        <div class="g-plusone" data-size="medium" data-href="' . $link . '"></div>
                        <script type="text/javascript">
                          window.___gcfg = {lang:"th"};
                          (function() {
                            var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
                            po.src = "https://apis.google.com/js/plusone.js";
                            var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
                          })();
                        </script>
                    </div>
                    <div style="clear:right"></div>
                </div>';

    return $social;
  }

  public function social_share_no_height($link) {

    $social = '<div class="campaign_social">
                        <div style="float: left">
                            <iframe src="//www.facebook.com/plugins/like.php?href=' . $link . '&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>
                        </div>
                        <div style="float: left">
                            <a href="' . $link . '" class="twitter-share-button" data-lang="en" data-count="horizontal">Tweet</a>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                        </div>
                        <div style="float:left">
                            <div class="g-plusone" data-size="medium" data-href="' . $link . '"></div>
                            <script type="text/javascript">
                              window.___gcfg = {lang:"th"};
                              (function() {
                                var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
                                po.src = "https://apis.google.com/js/plusone.js";
                                var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
                              })();
                            </script>
                        </div>
                        <div style="clear:left"></div>
                    </div>';
    return $social;
  }

  public function social_share_other($link) {
    $social = '<div class="campaign_social" style="height:50px">
                        <div style="float: right">
                            <iframe src="//www.facebook.com/plugins/like.php?href=' . $link . '&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>
                        </div>
                        <div style="float: right">
                            <a href="' . $link . '" class="twitter-share-button" data-lang="en">Tweet</a>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                        </div>
                        <div style="float:right">
                            <div class="g-plusone" data-size="medium" data-href="' . $link . '"></div>
                            <script type="text/javascript">
                              window.___gcfg = {lang:"th"};
                              (function() {
                                var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
                                po.src = "https://apis.google.com/js/plusone.js";
                                var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
                              })();
                            </script>
                        </div>
                        <div style="clear:right"></div>
                    </div>';
    return $social;
  }

  public function social_share_box($link) {

    $social = '<table>
                    <tr>
                        <td>
                           <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = "//connect.facebook.net/th_TH/all.js#xfbml=1";
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, "script", "facebook-jssdk"));</script>
                            <div class="fb-like" data-href="' . $link . '" data-layout="box_count" data-action="like" data-show-faces="true" data-share="false"></div>
                        </td>
                        <td style="float: right">
                            <a href="' . $link . '" class="twitter-share-button" data-lang="en" data-count="vertical">Tweet</a>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                        </td>
                        <td style="float:right">
                            <div class="g-plusone" data-size="tall" data-href="' . $link . '"></div>
                            <script type="text/javascript">
                              window.___gcfg = {lang:"th"};
                              (function() {
                                var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
                                po.src = "https://apis.google.com/js/plusone.js";
                                var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
                              })();
                            </script>
                        </td>
                    </tr>
                </table>';

    return $social;
  }

  public function social_share_face($link, $title) {

    $social = '<iframe src="//www.facebook.com/plugins/like.php?href=' . $link . '&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>';

    return $social;
  }

  public function get_province_name($id) {

    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');

    $sql = "select province_name from province where province_id='" . $id . "' ";
    if (!$rs = $CI->tppymemcached->get('get_province_name' . $id)) {

      $result = $CI->cdm->_query_row($sql, 'select');
      $CI->tppymemcached->set('get_province_name' . $id, $result, 3600 * 24 * 7);
      $rs = $CI->tppymemcached->get('get_province_name' . $id);
    }

    return $rs[0]['province_name'];
  }

  public function get_device()
  {
        $CI = & get_instance();
        $CI->load->library('user_agent');
        if($CI->agent->is_mobile('ipad'))
        {
            $device = "desktop";
        }
        else if($CI->agent->is_mobile())
        {
            $device = "mobile";
        }
        else
        {
            $device = "desktop";
        }
        ///// Fix Device ////
        //$device = "mobile";
        return $device;
  }

  public function facebook_box($page_name = 'Trueplookpanya', $title = 'คลังความรู้ออนไลน์ที่ใหญ่ที่สุดของประเทศไทย', $w = 242, $h = 525) {

    $device = $this->get_device();
    if($device === "desktop"){
        $facebook = '
          <div id="fb-root"></div>
          <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=1489934151228253";
            fjs.parentNode.insertBefore(js, fjs);
          }(document, \'script\', \'facebook-jssdk\'));</script>
          <div class="fb-page" data-href="https://www.facebook.com/' . $page_name . '" data-width="' . $w . '" data-height="' . $h . '" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/' . $page_name . '"><a href="https://www.facebook.com/' . $page_name . '">' . $title . '</a></blockquote></div></div>';
     }else{
        $facebook = '';
     }

    return $facebook;
  }

  /* -------- ลูกโป่ง-------- */

  function check_bump($member_id = null) {

    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');
    $CI->load->library('trueplook');

    $selPendingQuestion = "select cam_qa_id from campaign_member_answer where member_id ='" . $member_id . "' and answer_flag <> 0 and answer_date_time > (NOW() - INTERVAL 15 MINUTE) ";
    $q_pending_question = $CI->cdm->_query_row($selPendingQuestion, 'select');
    /* $row_pending_question = mysql_fetch_assoc( $q_pending_question );
      mysql_free_result( $q_pending_question ); */

    $selQuestion = "select cam_qa_id from campaign_qa where record_status = '1' and cam_qa_id not in (select member_score_answer_list from campaign_member_score where member_id = '" . $member_id . "' ) ";
    $q_question = $CI->cdm->_query_row($selQuestion, 'select');
    /* 	$row_question = mysql_fetch_assoc( $q_question );
      mysql_free_result( $q_question ); */


    if ($q_pending_question[0]['cam_qa_id'] == '' && $q_question[0]['cam_qa_id']) {  //ยังไม่ได้ตอบใน 15 นาที และมีคำถามเหลือใน stock
      return true; // ปิดลูกโปร่ง
    } else {
      return false;
    }
  }

  //ส่งลูกโป่งคำถามอะไร ?
  function get_bump_question($member_id = null) {
    $CI = & get_instance();
    $CI->load->library('tppymemcached');
    $CI->load->model('connect_db_model', 'cdm');
    $CI->load->library('trueplook');
    $return_text = "";

    //ดูจากที่ pending ไว้
    $selQuestion = "select * from campaign_qa where cam_qa_id in (select cam_qa_id from campaign_member_answer where member_id = '" . $member_id . "' and answer_flag = 0) ";
    $q_question = $CI->cdm->_query_row($selQuestion, 'select');
    $row_question = $q_question[0];

    if (!$row_question['cam_qa_id']) {


      //ดูลูกโป่งที่ยังไม่เคยปล่อย
      $selQuestion = "select * from campaign_qa where record_status = '1' and cam_qa_id not in (select member_score_answer_list from campaign_member_score where member_id = '" . $member_id . "' ) order by RAND() ";
      $q_question = $CI->cdm->_query_row($selQuestion, 'select');
      $row_question = $q_question[0];
    }

    $return_text .= '<form name="frmCampaignBump" method="post" action="' . $_SERVER['REQUEST_URI'] . '" style="display:inline;">' . "\n";
    $return_text .= '<div align="center" style="font-weight:bold; font-size:1.25em;">&quot;ลูกโป่งประลองไอคิว&quot; </div>' . "\n";
    $return_text .= '<div style="font-weight:bold; font-size:1.25em; margin-top:10px;">คำุถาม : ' . $row_question['cam_question'] . '</div>' . "\n";

    if ($row_question['cam_question_file_type'])
      $return_text .= '<div align="center"><img src="/data/product/media/CAMPAIGN/question_' . $row_question['cam_qa_id'] . '.' . $row_question['cam_question_file_type'] . '" alt="" /></div>' . "\n";

    $return_text .= '<div style="margin-left:50px;">' . "\n";
    $return_text .= '<div style="margin-top:5px; font-size:1.15em; cursor:pointer"><label for="cam_answer_id_1"><input type="radio" name="cam_answer" id="cam_answer_id_1" value="1" />' . $row_question['cam_answer1'] . '</label></div>' . "\n";
    $return_text .= '<div style="margin-top:5px; font-size:1.15em; cursor:pointer"><label for="cam_answer_id_2"><input type="radio" name="cam_answer" id="cam_answer_id_2" value="2" />' . $row_question['cam_answer2'] . '</label></div>' . "\n";
    $return_text .= '<div style="margin-top:5px; font-size:1.15em; cursor:pointer"><label for="cam_answer_id_3"><input type="radio" name="cam_answer" id="cam_answer_id_3" value="3" />' . $row_question['cam_answer3'] . '</label></div>' . "\n";
    $return_text .= '<div style="margin-top:5px;"> &nbsp; <input type="button" value="ส่งคำตอบ" onclick="campaign_answer_choose(\'' . $row_question['cam_qa_id'] . '\', \'' . $member_id . '\')" /></div>' . "\n";
    $return_text .= '</div>' . "\n";

    if ($row_question['cam_extend_url']) {
      $return_text .= '<div style="margin-top:10px;font-weight:bold; font-size:1.15em; text-decoration:underline;">ตัวช่วย</div>' . "\n";
      $return_text .= '<div style="font-size:1.15em;">หากคุณไม่ทราบหรือไม่แน่ใจ สามารถดูข้อมูลเพิ่มเติมได้โดย <a href="javascript: campaign_answer_pending(\'' . $row_question['cam_qa_id'] . '\', \'' . $member_id . '\', \'' . $row_question['cam_extend_url'] . '\')" style="font-weight:bold; font-size:1em; color:#cc0000;">กดที่นี่</a> และพบกับลูกโป่งคำถามข้อนี้อีกครั้งค่ะ</div>' . "\n";
    }
    $return_text .= '</form>' . "\n";
    return $return_text;
  }

  /* -------- ลูกโป่ง-------- */

  public function Pagination_get($page, $pagelen, $total_rows, $url) {

    $next = '';
    $first = '';


    $all_page = ceil($total_rows / $pagelen);
    if ($page > 1) {

      $prev = $page - 1;
    } else {

      $prev = 1;
    }

    if ($page < $all_page) {

      $next = $page + 1;
    } else {

      $next = $all_page;
    }

    $first = 1;
    $last = $all_page;


    $page_show = 6;
    if ($all_page - 1 < $page_show) {

      $page_show = $all_page - 1;
    }

    $page_range = 3;
    if (($page - $page_range) < 1) {
      $p_run = 1;
    } else {
      if (($all_page - $page) >= 3) {

        $p_run = $page - $page_range;
      } else {

        $start_num = 3 - ($all_page - $page);
        $p_run = ($page - $page_range) - $start_num;
      }
    }

    if ($p_run <= 0) {
      $p_run = 1;
    }

    $change_page = "
					<span class=\"nav\">
						<a href=\"" . $url . "&page=" . $first . "\"  class=\"first\" title=\"first page\"><span>First</span></a>
						<a href=\"" . $url . "&page=" . $prev . "\" class=\"previous\" title=\"previous page\"><span>Previous</span></a>
					</span>
					<span class=\"pages\">
					";

    for ($p = $p_run; $p <= ($page_show + $p_run); $p++) {
      if ($p == $page) {

        $change_page.="
							
								<a href=\"" . $url . "&page=" . $p . "\" title=\"page " . $p . "\" class=\"pageTV active\"  id=\"page_" . $p . "\"><span>" . $p . "</span></a>
				
							
							";
      } else {

        $change_page.="
					
								<a href=\"" . $url . "&page=" . $p . "\"  title=\"page " . $p . "\" id=\"page_" . $p . "\" class=\"pageTV\"><span>" . $p . "</span></a>
				
							
							";
      }
    }
    $change_page.="
					</span>
					<span class=\"nav\">
						<a href=\"" . $url . "&page=" . $next . "\" class=\"next\" title=\"next page\"><span>Next</span></a>
						<a href=\"" . $url . "&page=" . $last . "\" class=\"last\" title=\"last page\"><span>Last</span></a>
					</span>
					";

    return $change_page;
  }

  /* function get_pagename() {

    $ci =& get_instance();

    $ci->config->load('pagename');

    $arr_pagename = $ci->config->item('page');

    $arr_pagedetail = $ci->config->item('page_detail');

    $segment1 = $ci->uri->segment(1);

    $segment2 = $ci->uri->segment(2);

    $segment3 = $ci->uri->segment(3);

    $pathurl = $_SERVER['PATH_INFO'];


    if(isset($segment1) and array_key_exists($segment1, $arr_pagename)) {

    if(is_array($arr_pagename[$segment1])) {

    $segment2_new = (isset($arr_pagename[$segment1][$segment2]) and $arr_pagename[$segment1][$segment2]) ? $arr_pagename[$segment1][$segment2] : $segment2;

    if(isset($segment3) and $segment3 and is_numeric($segment3)) {

    return $segment1.'_'.$segment2_new.'_'.$segment3;

    } else {

    return $segment1.'_'.$segment2_new;

    }

    } else if(isset($segment1) and array_key_exists($segment1, $arr_pagedetail)) {

    if(isset($segment3) and $segment3 and is_numeric($segment3)) {

    return $segment1.'_'.$segment2.'_'.$segment3;

    } else {

    return $segment1.'_'.$segment2;

    }

    } else {

    return $segment1;

    }

    } else {

    return false;

    }

    } */

  function get_pagename() {

    $pagename = false;

    $ci = & get_instance();

    $ci->config->load('pagename');

    $arr_pagename = $ci->config->item('page');

    $arr_pagedetail = $ci->config->item('page_detail');

    $segment1 = $ci->uri->segment(1);

    $segment2 = $ci->uri->segment(2);

    $segment3 = $ci->uri->segment(3);

    if (!preg_match('/^[a-zA-Z0-9_-]+$/i', $segment3)) {
      $segment3 = '';
    }
    //echo $segment1;

    if (isset($segment1) and $segment1 and $arr_pagename[$segment1]) {  //case 1 
      $pagename = (is_array($arr_pagename[$segment1])) ? $segment1 . '-1' : $arr_pagename[$segment1];
      //$pagename = (is_array($arr_pagename[$segment1])) ? $segment1.'-1' : $arr_pagename[$segment1].'-1';
      //Ex. trueplookpanya.com/new/tv_live/ = tv_live
    }

    if ((isset($segment1) and $segment1 and is_array($arr_pagename[$segment1])) and ( isset($segment2) and $segment2 and $arr_pagename[$segment1][$segment2])) { //case 2 
      $pagename = (is_array($arr_pagename[$segment1][$segment2])) ? $segment1 . '_' . $segment2 : $segment1 . '_' . $arr_pagename[$segment1][$segment2];
      //$pagename = (is_array($arr_pagename[$segment1][$segment2])) ? $segment1.'_'.$segment2.'-2' : $segment1.'_'.$arr_pagename[$segment1][$segment2].'-2';
      //Ex. trueplookpanya.com/new/knowledge_list/all-2000/ = knowledge_list_คณิตศาสตร์
    } else if ((isset($segment1) and $segment1 and $arr_pagename[$segment1]) and ( isset($segment2) and $segment2)) { //case 3
      $pagename = (is_array($arr_pagename[$segment1])) ? $segment1 . '_' . $segment2 : $arr_pagename[$segment1] . '_' . $segment2;
      //$pagename = (is_array($arr_pagename[$segment1])) ? $segment1.'_'.$segment2.'-3' : $arr_pagename[$segment1].'_'.$segment2.'-3';
      //Ex. trueplookpanya.com/new/tv_program_detail/121/สอนศาสตร์-:-PAT-1-ความถนัดทางคณิตศาสตร์/ = tv_program_detail_121
    }

    if ((isset($segment1) and $segment1 and is_array($arr_pagename[$segment1])) and ( isset($segment2) and $segment2 and is_array($arr_pagename[$segment1][$segment2])) and ( isset($segment3) and $segment3 and $arr_pagename[$segment1][$segment2][$segment3])) { //case 4 
      $pagename = (is_array($arr_pagename[$segment1][$segment2][$segment3])) ? $segment1 . '_' . $segment2 . '_' . $segment3 : $segment1 . '_' . $segment2 . '_' . $arr_pagename[$segment1][$segment2][$segment3];
      //$pagename = (is_array($arr_pagename[$segment1][$segment2][$segment3])) ? $segment1.'_'.$segment2.'_'.$segment3.'-4' : $segment1.'_'.$segment2.'_'.$arr_pagename[$segment1][$segment2][$segment3].'-4';
      //Ex. trueplookpanya.com/new/cms_list/guidance/92/ = cms_list_guidance_พี่แนะน้อง​
    } else if ((isset($segment1) and $segment1 and is_array($arr_pagename[$segment1])) and ( isset($segment2) and $segment2 and $arr_pagename[$segment1][$segment2]) and ( isset($segment3) and $segment3)) { //case 5 
      $pagename = (is_array($arr_pagename[$segment1][$segment2])) ? $segment1 . '_' . $segment2 . '_' . $segment3 : $segment1 . '_' . $arr_pagename[$segment1][$segment2] . '_' . $segment3;
      //$pagename = (is_array($arr_pagename[$segment1][$segment2])) ? $segment1.'_'.$segment2.'_'.$segment3.'-5' : $segment1.'_'.$arr_pagename[$segment1][$segment2].'_'.$segment3.'-5'; 
    } else if ((isset($segment1) and $segment1 and $arr_pagename[$segment1]) and ( isset($segment2) and $segment2) and ( isset($segment3) and $segment3)) { //case 6
      $search = array('-');

      if (isset($segment1) and array_key_exists($segment1, $arr_pagedetail) and is_numeric(str_replace($search, '', $segment3))) {

        $pagename = (is_array($arr_pagename[$segment1])) ? $segment1 . '_' . $segment2 . '_' . $segment3 : $arr_pagename[$segment1] . '_' . $segment2 . '_' . $segment3;
        //$pagename = (is_array($arr_pagename[$segment1])) ? $segment1.'_'.$segment2.'_'.$segment3.'-6' : $arr_pagename[$segment1].'_'.$segment2.'_'.$segment3.'-6';
        //Ex. trueplookpanya.com/new/tv_program_detail/104/839/สอนศาสตร์ = tv_program_detail_66_1309 
        //หรือ trueplookpanya.com/new/cms_detail/entertainment_api/3041009/ = cms_detail_entertainment_api_3041009
        //หรือ trueplookpanya.com/new/cms_detail/knowledge/26371-038827/ = cms_detail_knowledge_26371-038827
      }
    }
    return $pagename;
  }

  public function get_vdo_url($path, $file, $hd = true,$type="learning") {
    $vdoFile = '';
	
	if($type=="cmsblog"){
			// check for cmsblog
			$pathCheckFile_static = "/data/product/trueplookpanya/www/static/";
			$url_static = 'http://static.trueplookpanya.com/';
			
	}else{
			//check for learning
			$pathCheckFile_static = "/data/product/trueplookpanya/www/static/trueplookpanya/media/";
			$url_static = 'http://static.trueplookpanya.com/trueplookpanya/media/';
			
			$pathCheckFile_www = "/data/product/trueplookpanya/www/product/media/";
			$url_www = "http://www.trueplookpanya.com/data/product/media/";
	}
	
	
	$pathCheckFile = $pathCheckFile_static;
	$url = $url_static;
	$filePath = $path . "/" . $file;
	if ($hd)
	  $source_array = array('1080p', '720p', 'medium', '480p', '320p', '140p', 'medium', 'hd720p', 'hd480p', 'large');
	else
	  $source_array = array('medium', '480p', '720p', '1080p', '320p', '140p', 'medium', 'hd720p', 'hd480p', 'large');
	
	$check_ok = false;
	foreach ($source_array as $k => $v) {
	  if ($check_ok == false) {
		if (file_exists($pathCheckFile . substr($filePath, 0, -4) . '_' . $v . '.mp4')) {
		  $vdoFile = substr($file, 0, -4) . '_' . $v . '.mp4';
		  $check_ok = true;
		}
	  }
	}
	
	if ($vdoFile == '') {	// พวกที่ไม่ผ่าน mediaconverter
	  if (file_exists($pathCheckFile . substr($filePath, 0, -4) . '.mp4')) {
		$vdoFile = substr($file, 0, -4) . '.mp4';
	  } elseif (file_exists($pathCheckFile . $filePath)) {
		$vdoFile = $file;
	  }else{
		  // check old one
		  $pathCheckFile = $pathCheckFile_www;
		  $url = $url_www;
		  if (file_exists($pathCheckFile . substr($filePath, 0, -4) . '.mp4')) {
			  $vdoFile = substr($file, 0, -4) . '.mp4';
		  }elseif (file_exists($pathCheckFile . $filePath)) {
			  $vdoFile = $file;
		  }
	  }
	}
	

    if ($vdoFile) {
      return $url . $path . "/" . $vdoFile;
    } else {
      return $type.'-video-not-found';
    }
  }

  function get_vdo_thumb_url($old_path, $file_path, $file_id) {
    //$pathCheckFile = "/data/product/trueplookpanya/www/static/trueplookpanya/media/";
    if (getimagesize($old_path)) {
      $url = $old_path;
    } else {
      $url = 'http://static.trueplookpanya.com/trueplookpanya/media/' . $file_path . '/CMS_' . $file_id . '_4_thumb.jpg';
    }
    return $url;
  }

  function get_vdo_thumb_by_file($file_path, $file_id) {
    //FILE_CSA_5000115_1_thumb.jpg
    if (getimagesize($old_path)) {
      $url = $old_path;
    } else {
      $url = 'http://static.trueplookpanya.com/trueplookpanya/media/' . $file_path . '/' . substr($file_id, 0, -4) . '_4_thumb.jpg';
    }
    return $url;
  }

  function replace_char_url($str) {
    $array = array('[', ']');
    return str_replace($array, '', $str);
  }
  
  function userlog($member_id, $task, $menu, $date, $ref_id, &$DBLink, $detail){

		/*$log = "INSERT INTO user_log SET ";
		$log .= "member_id = '".$member_id."' , ";
		$log .= "task='".$task."', ";
		$log .= "menu='".$menu."', ";
		$log .= "updated_date='".$date."', ";
		$log .= "ref_id='".$ref_id."' ";*/
		
		$CI = & get_instance();
		$CI->load->model('connect_db_model', 'cdm');
		
		$ip = $this->get_ip_address();
		$table_query = 'user_log';
		$data = array('member_id' => $member_id, 'task' => $task, 'menu' => $menu, 'updated_date' => date('Y-m-d H:i:s'), 'ref_id' => $ref_id, 'user_ip' => $ip, 'detail' => $detail);
        $result = $CI->cdm->_query_insert($data, $table_query, 'edit');


	}
	
	
  public function convert_plainText2HTML($s){
		$url_pattern = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
		$s = preg_replace($url_pattern, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $s);
		$s = nl2br($s,false);
		return $s;
  }
  
	public function youtube_fetch_highest_res ($videoid) {
		$resolutions = array('maxresdefault', 'hqdefault', 'mqdefault');     
		foreach($resolutions as $res) {
			$imgUrl = "http://i.ytimg.com/vi/$videoid/$res.jpg";
                        if(@getimagesize(($imgUrl))){
                            return $imgUrl;
                        }else{
                            return "http://i.ytimg.com/vi/$videoid/mqdefault.jpg";
                        } 
				
		}
	}
	
	public function number_format_thousands($num) {
		  $x = round($num); 
		  // if($x>9999){	// เลขหลักหมื่น ค่อย convert to k
			  $x_number_format = number_format($x);
			  $x_array = explode(',', $x_number_format);
			  $x_parts = array('K', 'M', 'B', 'T');
			  $x_count_parts = count($x_array) - 1;
			  $x_display = $x;
			  $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
			  $x_display .= $x_parts[$x_count_parts - 1];
		  // }else{
			  // $x_display = number_format($x);
		  // }
		  return $x_display;
	}

}
?>