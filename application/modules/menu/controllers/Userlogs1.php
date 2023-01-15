<?php
class Userlogs1 extends MY_Controller {
	function index(){
			
			$language = $this->lang->language;
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			$logs_list = $this->Admin_log_activity_model->view_log2();
			$breadcrumb[] = $language['activity_logs'];

			$data = array(
					"ListSelect" => $ListSelect,
					"logs_list" => $logs_list,
					"breadcrumb" => $breadcrumb,
					"content_view" => 'admin/user_logs'
			);
			$this->load->view('template/template',$data);
	}
}