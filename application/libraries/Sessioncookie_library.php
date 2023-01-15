<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Sessioncookie_library{
private $_ci;
function __construct(){
      $this->ci =& get_instance();
      $CI =& get_instance();
	  $this->ci->load->library('session');
	  $this->ci->load->helper('cookie');
	  $this->ci->config->load('cacheconfig');
      $this->cachetime = $this->ci->config->item('cachetime');
 
     }
// Function to get the client ip address
public function get_client_ip_env() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
     }
// Function to get the client ip address
public function get_client_ip_server() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
     }
//Get User IP  ###########################################
public function get_user_ip(){
        $ip='';
        if (function_exists('file_get_contents')) {
            $ipify   = @file_get_contents('https://api.ipify.org');
            if (filter_var($ipify, FILTER_VALIDATE_IP)) {
                $ip = $ipify;
            }
        }else{
            $client  = @$_SERVER['HTTP_CLIENT_IP'];
            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $remote  = $_SERVER['REMOTE_ADDR'];
            if (filter_var($client, FILTER_VALIDATE_IP)) {
                $ip = $client;
            } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
                $ip = $forward;
            } else {
                $ip = $remote;
            }
        }
        return $ip;
    }
########################
//session_cookie
public function sessioncookie_get(){
      $data=array('COOKIE'=>$_COOKIE,
                  'SESSION'=>$_SESSION,
                  );
     return $data; 
     }
#######################
public function sessionset($sesdata){
      //echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'</pre>';Die();
      //$this->CI->load->library('session');
      $this->ci->session->set_userdata($sesdata);
     } 
###########################################
public function cookieset($cookie_name,$cookie_value,$time){
     #echo'<hr><pre>  cookie_name=>';print_r($cookie_name);echo'</pre>';  echo'<hr><pre>  cookie_value=>';print_r($cookie_value);echo'</pre>'; echo'<hr><pre>  time=>';print_r($time);echo'</pre>';Die();
          //$this->CI->load->helper('cookie');
          if($time==null){$time=60*60*60*24;}
          $cookie=array(
                    'name' => $cookie_name,
                    'value' => $cookie_value,                            
                    'expire' => (time()+$time),      
                    'path' => "/",                                                                          
                    'secure' =>false  // false //true 
               );
               
          #echo'<hr><pre>  cookie=>';print_r($cookie);echo'</pre>';Die();
          $cookiert=$this->ci->input->set_cookie($cookie);
          }
###########################################
public function sessdestroy(){
     //session_destroy();
    $this->ci->session->sess_destroy();      
     }
###########################################
public function deletecookie($cookie_name,$cookie_value){
     //$this->CI->load->helper('cookie');  	
     $cookie=array(
               'name' => $cookie_name,
               'value' => $cookie_value,  
               #'expire' => '0',      
               'path' => "/",                                                                           
               'secure' => false
          );
     delete_cookie($cookie);
     return $cookie;
     } 
###########################################
}

/*
// set cookie 
$cookie = array(
        'name'   => 'home_set',
        'value'  => 'home page Change',
        'expire' => time()+86500,
        'domain' => '.localhost',
        'path'   => '/',
        'prefix' => 'arjun_',
        );
 
set_cookie($cookie);
 
// get cookie
 
get_cookie('arjun_home_set');
 
// delete cookie
$cookie = array(
    'name'   => 'home_set',
    'value'  => '',
    'expire' => '0',
    'domain' => '.localhost',
    'prefix' => 'arjun_'
    );
 
delete_cookie($cookie);

*/