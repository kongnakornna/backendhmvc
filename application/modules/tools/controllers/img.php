<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Img extends Public_Controller {
  
   public function __construct() {
      parent::__construct();
      ob_clean();
	  header('Access-Control-Allow-Origin: *');
	  header('Content-Type: text/html; charset=utf-8');
	  $CI = & get_instance();
	  $this->template->set_theme('trueplookpanya', 'default', 'themes');
   }
   
   // public function index($w=0,$h=0)
   // {
	   // $this->img($w,$h);
   // }
   
   public function index($w=0, $h=0)
   {
	   if ($w==0) $w=1280;
	   if ($h==0) $h=720;
	   $data = array();
	   $data['w'] = $w;
	   $data['h'] = $h;
	   $data['img'] = "http://static.trueplookpanya.com/cmsblog/2783/58783/thumb_file_w".$w."_h".$h.".jpg";
	   $data["url"] =  "http://www.trueplookpanya.com/tools/img/index/".$w."/".$h;
	   $this->load->view('img',$data);
   }
}