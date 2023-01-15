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
				if(!$this->session->userdata('is_logged_in')){ redirect(base_url()); }
    }
public function index(){
	$input=@$this->input->post(); 
	if($input==null){$input=@$this->input->get();   }
		$language = $this->lang->language;
		$breadcrumb[] = $language['dashboard'];
		$admin_id = $this->session->userdata('admin_id');
		$admin_type = $this->session->userdata('admin_type');
		/*
	/////////////cache////////////
			$time_cach_set_min=$this->config->item('time_cach_set_min');
			$time_cach_set=$this->config->item('time_cach_set');
			#$cachetime=$time_cach_set_min;
			$cachetime=60*60*60*24*365;
			$lang=$this->lang->line('lang'); 
			$langs=$this->lang->line('langs'); 
			$cachekey='ListSelect_menu_dashboard_'.$lang;
			##Cach Toools Start######
			//cachefile 
			$input=@$this->input->post(); 
			if($input==null){ $input=@$this->input->get();}
			$deletekey=@$input['deletekey'];
			if($deletekey==''){$deletekey=null;}else{$deletekey=1;}
			$cachetype='2'; 
			$this->load->model('Cachtool_model');
			$sql=null;
			$cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
			$cachechklist=$cachechk['list'];
			// echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechk);echo'</pre>'; Die();
			if($cachechklist!=null){    // IN CACHE
				$temp = $cachechklist;
				#echo'1 Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
			}else{                      // NOT IN CACHE
				///// ***** เอา FUNCTION ที่ทำงานท่อนเดิม มาใส่ตรงนี้ ******
				$ListSelect=$this->Api_model_na->user_menu($this->session->userdata('admin_type'));
				$sql=null;
				$cachechklist=$this->Cachtool_model->cachedbsetkey($sql,$ListSelect,$cachekey,$cachetime,$cachetype,$deletekey);
				#echo'2 Form SQL <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
			}
	
	/////////////cache////////////
	*/
    //$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
    $loadfile="admintype".$admin_type.".json";
	$admin_menu=LoadJSON($loadfile);
	$data=array(#"ListSelect" => $cachechklist,
					//"breadcrumb"=>$breadcrumb,
					"admin_menu" => $admin_menu,
					//"headtxt" =>"dashboard",
					//"msg" =>"ok",
					"content_view" =>'admin/dashboard',
					);
	 #echo'<pre> data =>';print_r($data);echo'</pre>'; Die();
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