<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Overview extends MY_Controller {

    public function __construct()    {
        parent::__construct();

        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){

		$admin_id = $this->session->userdata('admin_id');
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
		$notification_birthday = $this->Api_model->notification_birthday();

		$data = array(
			"admin_menu" => $this->menufactory->getMenu(),
			"ListSelect" => $ListSelect,
			"notification_birthday" => $notification_birthday,
		);
		$data['content_view'] = 'admin/overview';
		$this->load->view('template/template',$data);
	}
	
}