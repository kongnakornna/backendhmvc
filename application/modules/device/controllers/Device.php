<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Device extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        ob_start('ob_gzhandler');
		//  Check Device
        $this->load->model('Device_model');
    }
	
    public function index()
    {
        //redirect(base_url(), "refresh", 301);
        //exit;
		
		$device = $this->Device_model->get_device();
        if($device === "desktop"){
            //$this->desktop->vw_category();
			
			 echo 'desktop';
			
        } else {
           //$this->mobile->vw_category();
		    echo 'mobile';
        }
		
    }
    
    public function test() 
    {
		$this->load->model('Device_model');
        $device = $this->Device_model->get_device();
        if($device === "desktop"){
            //$this->desktop->vw_category();
			
			 echo 'desktop';
			
        } else {
           //$this->mobile->vw_category();
		    echo 'mobile';
        }
        
    }
    public function devicetest() 
    {
        $device = $this->Device_model->get_device();
        if($device === "desktop"){
            //$this->desktop->vw_category();
			
			 echo 'desktop';
			
        } else {
           //$this->mobile->vw_category();
		    echo 'mobile';
        }
        
    }
    
}

/* End of file welcome.php 

$this->load->model('device/Device_model');
		$device = $this->Device_model->get_device();
        if($device === "desktop"){
            //$this->desktop->vw_category();
			
			 echo 'desktop';
			
        } else {
           //$this->mobile->vw_category();
		    echo 'mobile';
        }
        
*/
/* Location: ./application/controllers/welcome.php */