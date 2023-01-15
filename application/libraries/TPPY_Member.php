<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TPPY_Member {
    public $CI;

    function __construct() {
        $this->CI = & get_instance();
    }

    
    function get_member_profile($index=null){
      $user_data = $this->CI->session->userdata('user_session');
      if($user_data){
        if(!empty($index)){
          return $user_data->$index;
        }
        return $user_data;
      }
    }
    
    
    function get_member_folder($user_id =0){
      if(empty($user_id)){
        $user_id = $this->get_member_profile()->user_id;
      }
      $start = floor($user_id / 2500) * 2500;
      return join(DIRECTORY_SEPARATOR , 
        array($this->CI->config->item('root_base_path').'tppy', 
        'member', 
        'm_'. $start.'_'.($start+2500),
        $user_id,
      )); 
      
    }
    
    function get_member_url($user_id){
      if(empty($user_id)){
        $user_id = $this->get_member_profile()->user_id;
      } 
        
      $start = floor($user_id / 2500) * 2500;
      return join('/' , 
        array($this->CI->config->item('root_base_url').'tppy', 
        'member', 
        'm_'. $start.'_'.($start+2500),
        $user_id,
      )); 
      }
      
      function get_display_name(){
        $psn_firstname = $this->get_member_profile()->psn_firstname;
        $psn_lastname = $this->get_member_profile()->psn_lastname;
        $psn_display_name = $this->get_member_profile()->psn_display_name;
        $user_username = $this->get_member_profile()->user_username;
        if(!empty($psn_firstname) && !empty($psn_lastname)){
          return "$psn_firstname $psn_lastname";
        }else if(!empty($psn_display_name)){
          return $psn_display_name;
        }else{
          return $user_username;
        }
      }
}
