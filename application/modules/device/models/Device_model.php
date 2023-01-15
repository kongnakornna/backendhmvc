<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Device_model extends CI_Model {
function __construct(){
        parent::__construct();
        $this->load->library('user_agent');
    }   
public function get_device(){
	    $this->load->library('user_agent');
		if ($this->agent->is_browser()){
						$agent = $this->agent->browser().' '.$this->agent->version();
						$device = "desktop";
						$device_ststus=1;
		}elseif ($this->agent->is_robot()){
						$agent = $this->agent->robot();
						$device = "desktop";
						$device_ststus=1;
		}elseif ($this->agent->is_mobile()){
						$agent = $this->agent->mobile();
						$device = "mobile";
						$device_ststus=2;
		}else{
						$agent = 'Unidentified User Agent';
						$device = "Unidentified";
						$device_ststus=3;
	}
        
        ///// Fix Device ////
        //$device = "mobile";
        
        return $device;
        
    }  
public function get_device_all(){
	$this->load->library('user_agent');
		if ($this->agent->is_browser()){
						$agent = $this->agent->browser().' '.$this->agent->version();
						$device = "desktop";
						$device_ststus=1;
		}elseif ($this->agent->is_robot()){
						$agent = $this->agent->robot();
						$device = "desktop";
						$device_ststus=1;
		}elseif ($this->agent->is_mobile()){
						$agent = $this->agent->mobile();
						$device = "mobile";
						$device_ststus=2;
		}else{
						$agent = 'Unidentified User Agent';
						$device = "Unidentified";
						$device_ststus=3;
	}
	  $device=array('agent'=>$agent,
					'device'=>$device,
					'device_ststus'=>$device_ststus
					);
	   return $device;
}
	
}