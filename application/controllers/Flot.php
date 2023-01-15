<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Flot extends MY_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Admin_team_model');
        $this->load->model('Sensorconfig_model');      
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;
		$breadcrumb[] = $language['interactivechart'];
		     $hw1='1';  $hw2='2';  $hw3='4';
			$sensortemphumi_id='1';
			$sensorwaterleak_id='5';
			$hwsensor1 = $this->Sensorconfig_model->all_sensor_config($sensortemphumi_id,$hw1);
			$hwsensor2 = $this->Sensorconfig_model->all_sensor_config($sensortemphumi_id,$hw2);
			$hwsensor3= $this->Sensorconfig_model->all_sensor_config($sensorwaterleak_id,$hw3);
			#Debug($hwsensor1); echo '*****'; Debug($hwsensor2); echo '*****'; Debug($hwsensor3); echo '*****';  Die();
			$team_list = $this->Admin_team_model->get_admin_team();
			//Debug($team_list); die();

			$data = array(				
					"admin_team" => $team_list,
					"hwsensor1"=>$hwsensor1,
					"hwsensor2"=>$hwsensor2,
					"hwsensor3"=>$hwsensor3,
					"content_view" => 'tmon/charttmon2',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					"success" => 'Admin Team'
			);

			$this->load->view('template/template',$data);
	}
	
}