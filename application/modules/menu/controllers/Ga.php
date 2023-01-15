<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ga extends MY_Controller {

    public function __construct()    {
        parent::__construct();

        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }else if($this->session->userdata['admin_type'] > 3){
            redirect(base_url());
        }
    }

	public function index(){

		//$startTime = microtime(true);
		//$this->benchmark->mark('code_start');
		//$this->load->driver('cache');
		//$this->load->model('chart_model');

		$this->load->library('google');
		//$this->load->helper('google_analytics');

		$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));
		$result_top_view = $this->google->topview();
		//$val = 'hot-news/thai-news/1066362';
		//$result_new = $this->google->pageview($val, -7);

		$breadcrumb[] = 'Google analytics';

		$data = array(
			"admin_menu" => $this->menufactory->getMenu(),
			"result_top_view" => $result_top_view,
			//"result_new" => $result_new,
			"breadcrumb" => $breadcrumb,
			"ListSelect" => $ListSelect,
			"content_view" => 'admin/ga',
		);
		$this->load->view('template',$data);
	}

}