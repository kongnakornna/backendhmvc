<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Admin_team_model');
       // $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){

		$ListSelect = $this->Api_model_smart->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;
		$breadcrumb[] = $language['workflow'];

			$team_list = $this->Admin_team_model->get_admin_team();
			//Debug($team_list);
			//die();

			$data = array(				
					"admin_team" => $team_list,
					"content_view" => 'tmon/workflow',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					"success" => 'Admin Team'
			);
		$action='1';
		//**************Log activity
		$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => 1,
					"ref_type" => 0,
					"ref_title" => "ACCESS TO..  ".'[Workflow Module]'."",
					"action" => $action
		);
		$this->Admin_log_activity_model->store($log_activity);
		//**************Log activity
			$this->load->view('template/template',$data);
	}
	
}

/*
window.setInterval(function(){
  /// call your function here
}, 5000); // for every 5 sec
*/