<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update extends MY_Controller {

    public function __construct()    {
        parent::__construct();
    }

	public function index(){
			$this->view();
	}

	public function view(){
			
			$update_view = "http://localhost/backend-siamdara-com/api/v1/api/update_view.php?time=".time();
			$update_view_daily = "http://localhost/backend-siamdara-com/api/v1/api/update_view_daily.php?time=".time();

			$display_view = $this->api_model->normal_request($update_view);
			echo "<hr>$display_view";

			$display_daily = $this->api_model->normal_request($update_view_daily);
			echo "<hr>$display_daily<hr>";

	}
}