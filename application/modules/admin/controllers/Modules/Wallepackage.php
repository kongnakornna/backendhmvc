<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Wallepackage extends MY_Controller {
	
	public function __construct(){
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
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			//$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = 			$notification_dara_list = array();
 

			$loadfile = "admintype".$admin_type.".json";
			$admin_menu = LoadJSON($loadfile);

			$data = array(
					"ListSelect" => $ListSelect,
					//"breadcrumb"=>$breadcrumb,
					"admin_menu" => $admin_menu,
 
			);

		$data['content_view'] = 'modules/wallet/Walletype';
		$this->load->view('template/template',$data);
	}
	
	public function walle(){
	
	}

}

/* End of file api.php */
/* Location: ./application/controllers/api.php */