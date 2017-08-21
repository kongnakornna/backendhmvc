<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Dashboard extends MY_Controller {
 public function __construct()    {
        parent::__construct();
			$this->load->model('admin/Admin_team_model');
			$this->load->library("AdminFactory");
			$this->load->library("MenuFactory");
			$this->load->library('session');
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }
 public function index(){		
	$this->dashboard();
 }
 public function dashboard(){
			$language = $this->lang->language;
			$breadcrumb[] = $language['dashboard'];
			$admin_id = $this->session->userdata('admin_id');
			$admin_type = $this->session->userdata('admin_type');
/*
   $ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			//$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
			$loadfile = "admintype".$admin_type.".json";
			$admin_menu = LoadJSON($loadfile);

			$data = array(
					"ListSelect" => $ListSelect,
					//"breadcrumb"=>$breadcrumb,
					"admin_menu" => $admin_menu,
 
			);
 */
    /////////////cache////////////
   	$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    $time_cach_set_min=$this->config->item('time_cach_set_min');
    $time_cach_set=$this->config->item('time_cach_set');
    $timecache=$time_cach_set_min;
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs'); 
   	$key='dashboard_'.$lang;
       if (!$data_cach= $this->cache->get($key)){
       $ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			   //$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			    $notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
        $loadfile = "admintype".$admin_type.".json";
			     $admin_menu = LoadJSON($loadfile);
        $data_cach= array("ListSelect" => $ListSelect,
       					            //"breadcrumb"=>$breadcrumb,
       					              "admin_menu" => $admin_menu,
                          //"headtxt" =>"dashboard",
                          //"msg" =>"ok",
                          "content_view" =>'admin/dashboard',
                   			);

            // Save into the cache for 5 minutes
            $this->cache->file->save($key,$data_cach,$timecache);
       }
      $data=$data_cach;
   	/////////////cache////////////
		 
		$this->load->view('template/template',$data);
	
	}
}