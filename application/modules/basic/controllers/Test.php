<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	function index(){
			//$this->load->library("MenuFactory");

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
			);

			$data['content_view'] = 'test';
			$this->load->view('template/template',$data);
	}

	function member(){
			//$this->load->library("MenuFactory");

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
			);

			$data['content_view'] = 'test_member';
			$this->load->view('template/template',$data);
	}

	function add(){
			//$this->load->library("MenuFactory");

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
			);

			$data['content_view'] = 'basic/form_exam';
			$this->load->view('template/template',$data);
	}

	function element(){
			//$this->load->library("MenuFactory");
			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
			);
			$data['content_view'] = 'basic/test';
			$this->load->view('template/template',$data);
	}

	function debug(){

			//$this->load->helper('debug');
			//$this->load->library("MenuFactory");
			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
			);
			$data['content_view'] = 'debug';
			$this->load->view('template/template',$data);
			//$this->load->view('debug');
	}

	function info(){

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
			);
			$data['content_view'] = 'info';
			$this->load->view('template/template',$data);
			//$this->load->view('debug');
	}

	function ckeditor(){

			//$this->load->helper('debug');
			//$this->load->library("MenuFactory");
			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
			);
			$data['content_view'] = 'ckeditor';
			$this->load->view('template/template',$data);
			//$this->load->view('debug');
	}

	function agent(){
			$this->load->library('user_agent');

			if ($this->agent->is_browser())
			{
				$agent = $this->agent->browser().' '.$this->agent->version();
			}
			elseif ($this->agent->is_robot())
			{
				$agent = $this->agent->robot();
			}
			elseif ($this->agent->is_mobile())
			{
				$agent = $this->agent->mobile();
			}
			else
			{
				$agent = ' Unidentified User Agent';
			}

			echo $agent."<br>";

			echo $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.) 
	}

}