<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Playbackground extends MY_Controller {

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
		$breadcrumb[] = $language['playbackground'];

			$team_list = $this->Admin_team_model->get_admin_team();
			//Debug($team_list);
			//die();

			$data = array(				
					"admin_team" => $team_list,
					"content_view" => 'tmon/playbackground',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					//"success" => 'Admin Team'
			);

			$this->load->view('template/template',$data);
	}
	
}

/*
window.setInterval(function(){
  /// call your function here
}, 5000); // for every 5 sec
*/