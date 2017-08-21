<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Device_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        
        $this->load->library('user_agent');
    }
    
    public function get_device()
    {
        
        if($this->agent->is_mobile('ipad'))
        {
            $device = "desktop";
        } 
        else if($this->agent->is_mobile())
        {
            $device = "mobile";
        }
        else 
        {
            $device = "desktop";
        }
        
        if($this->input->get_post("device") !== FALSE)
        {
            $device = $this->input->get_post("device");
        }
        
        ///// Fix Device ////
        //$device = "mobile";
        
        return $device;
        
    }
    
}