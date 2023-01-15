<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Overview extends MY_Controller {

    public function __construct()    {
        parent::__construct();
		$this->load->library('session');
        $this->load->driver('session');
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		redirect(base_url());	
	}
	
}