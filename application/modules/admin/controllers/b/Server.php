<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Server extends CI_Controller {

	function index(){

			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));
			$current_server_time = date("Y")."/".date("m")."/".date("d")." ".date("H:i:s");
			$data = array(
				"ListSelect" => $ListSelect,
				"current_server_time" => $current_server_time,
				"content_view" => 'time'
			);
			$this->load->view('template',$data);
	}

	function gettime(){
			
			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));

			$current_server_time = date("Y")."/".date("m")."/".date("d")." ".date("H:i:s");
			$data = array(
				"ListSelect" => $ListSelect,
				"current_server_time" => $current_server_time,
				"content_view" => 'time'
			);
			$this->load->view('template',$data);
	}

}