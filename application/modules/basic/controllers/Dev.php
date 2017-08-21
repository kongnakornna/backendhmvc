<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

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

	public function clear_catch(){

		$language = $this->lang->language;
		$admin_id = $this->session->userdata('admin_id');
		$admin_type = $this->session->userdata('admin_type');
		$ListSelect = $this->Api_model->user_menu($admin_type);

		$breadcrumb[] = 'Dev tool';
		$breadcrumb[] = 'Clear Catch files';

		$gen_api = $this->config->config['api'].'/api/web-api.php';
		$api_key = $this->config->config['api_key'];

		$link = $gen_api.'?method=DeleteCache&key='.$api_key.'&gen_file=1';

		$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"content_view" => 'tool/clear_catch_files',
					"link" => $link,
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);
	}

}