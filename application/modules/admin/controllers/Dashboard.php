<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Dashboard extends MY_Controller {
   public function __construct()    {
   parent::__construct();
    			$this->load->model('Admin_team_model');
    			$this->load->library("AdminFactory");
    			$this->load->library("MenuFactory");
    			$this->load->library('session');
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }
	public function index(){
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
     "content_view" =>'admin/dashboard',
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
	public function dashboardtheme(){
			$provinceid = '54';  
		    $geoid='2';  
		    $countries_id='209';
			#echo'<hr><pre>  $countries_id=>';print_r($countries_id);echo'<pre> <hr>'; die();
			$this->load->model('Countries_model');
			$this->load->model('Geography_model');
			$this->load->model('Province_model');
			$this->load->model('Village_model');
/////////////////
            $session_id = $this->session->userdata('session_id');
    		$userinput=$this->session->userdata('user_name');
    		$user_id= $this->session->userdata('admin_id');
            $admin_id= $this->session->userdata('admin_id');
    		$user_name=$this->session->userdata('user_name');
    		$admin_type=$this->session->userdata('admin_type');
    		$name=$this->session->userdata('name');
    		$lastname=$this->session->userdata('lastname');
    		$email=$this->session->userdata('email');
    		$domain=$this->session->userdata('domain');
    		$department=$this->session->userdata('department');	
    		$admin_password=$this->session->userdata('admin_password');
/////////////////
/*
        echo '$user_id='.$user_id.'<br>';
        echo '$admin_id='.$admin_id.'<br>';
        echo '$user_name='.$user_name.'<br>';
        echo '$admin_type='.$admin_type.'<br>';
        echo '$admin_password='.$admin_password.'<br>';
        echo '$email='.$email.'<br>';
        echo '$name='.$name.'<br>';
        echo '$lastname='.$lastname.'<br>';
        Die();    
  */   
			$ListSelect = $this->Api_model->user_menu($admin_type);
			$notification_birthday = $this->Api_model->notification_birthday();
            ################
			$language = $this->lang->language;			
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
            $get_countries = $this->Countries_model->get_countries_by_id($countries_id);
			//Debug($get_countries);
			$object = json_decode(json_encode($get_countries), TRUE);
            $countriesname=$object[0]['countries_name'];
            
            
            $get_geography = $this->Geography_model->get_geography_by_id($geoid);
			//Debug($get_geography);
			$object = json_decode(json_encode($get_geography), TRUE);
            $geoname=$object[0]['geo_name'];
            
            $get_province = $this->Province_model->get_province_by_id($provinceid,$language);
			//Debug($get_province);
			$object = json_decode(json_encode($get_province), TRUE);
            $provincename=$object[0]['province_name'];
            
            $breadcrumb[] = '<a href="'.base_url('countries').'">'.$language['countries'].$countriesname.'</a>';			        $breadcrumb[] = '<a href="'.base_url('geography').'">'.$language['geography'].$geoname.'</a>';
            $breadcrumb[] = '<a href="'.base_url('province').'">'.$language['province'].$provincename.'</a>';  
            ################



		$data = array(
			"admin_menu" => $this->menufactory->getMenu(),
			"ListSelect" => $ListSelect,
			//"notification_birthday" => $notification_birthday,
		);
		$data['content_view'] = 'admin/dashboard';
		 #echo'<hr><pre>  $data=>';print_r($data);echo'<pre> <hr>';  Die();
		$this->load->view('template/template',$data);
	}
}