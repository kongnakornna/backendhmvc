<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dev extends CI_Controller {

	public function index(){

		$language = $this->lang->language;
		$admin_id = $this->session->userdata('admin_id');
		$admin_type = $this->session->userdata('admin_type');
		$ListSelect = $this->Api_model->user_menu($admin_type);

		$breadcrumb[] = 'Dev tool';
		$breadcrumb[] = 'Memcached Admin';

		$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"content_view" => 'tool/memcached',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */