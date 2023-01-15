<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Demo extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->config->item('Template');
		$this->load->model('Test_model');
        # if(!$this->session->userdata('is_logged_in')){redirect(base_url());}
	}
	public function index(){
		 //define('BASEPATH', TRUE);
		 $id='1';
		 $datars = $this->Test_model->get_member_by_id($id);
		  $data=array("title" => 'Demo',
             "Data"=> $datars,
             "content_view" => 'demo',
			); 
        //echo '<pre>'; print_r($data); echo '</pre>';  Die();
		$this->output->cache($data);
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache"); 
		echo '<pre>data=>'; print_r($data); echo '</pre>';  Die();
		$this->load->view('Template/Template',$data);
		
	}
}
