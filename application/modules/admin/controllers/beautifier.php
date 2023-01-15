<?php

class Beautifier extends MY_Controller {

    public function __construct()    {
		parent::__construct();
        $this->load->library('parser');
        $this->load->helper('html');
        $this->load->helper('string');
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){

		$language = $this->lang->language;
		$admin_id = $this->session->userdata('admin_id');
		$admin_type = $this->session->userdata('admin_type');
		$ListSelect = $this->api_model->user_menu($admin_type);

		$breadcrumb[] = 'Dev tool';
		$breadcrumb[] = 'Online JavaScript beautifier';

		$url = 'http://jsbeautifier.org/';

		$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"content_view" => 'tool/iframe',
					"url" => $url,
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template',$data);	
	}

}