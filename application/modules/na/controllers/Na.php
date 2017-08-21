<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Na extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->config->item('Template');
	}
	public function index(){
	     // echo '<pre> Template=>'; print_r('Template'); echo '</pre>';  //Die();
		  $data = array(
			 "title" => 'Na',
             "Data"=> 'Data',
             "content_view" => 'Na',
			); 
          //echo '<pre>'; print_r($data); echo '</pre>';  Die();
		$this->load->view('Template/Template',$data);
	}
}
