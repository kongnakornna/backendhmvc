<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Na extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('fnc_helper', 'form'));
		$this->load->library(array('encrypt', 'session', 'user_agent', 'pagination'));
		$this->load->model('Na_model', 'db1');

	}

	public function index()
	{
		echo 'test';
	}

	public function email()
	{
		$this->load->model('email_model', 'email');
		$this->db1->setpage(5);
		$data['data'] = $this->email->pages();
		$data['pages'] = $this->db1->pagination();
		$data['setting'] = $this->db1->settings();
		$data['num_rows'] = $this->email->num_rows();

		$this->email->add();
		$this->email->delete();

		$this->load->view('member/email', $data);
	}
	

}


/* End of file member.php */
/* Location: ./application/controllers/member.php */