<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Emergency extends MY_Controller {

    public function __construct()    {
        parent::__construct();

        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->Api_model_na->user_menu($admin_type);

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
					);

					$data['content_view'] = 'admin/dashboard';
					$this->load->view('template/template',$data);

	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */