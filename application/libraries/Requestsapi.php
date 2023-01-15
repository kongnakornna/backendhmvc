<?php 
//defined('BASEPATH') OR exit('No direct script access allowed');

class Requestsapi {
	public function call($urlapi){  
	        require_once APPPATH."/third_party/Requests/Requests.php";
			$this->load->helper('url'); 
			 $this->load->helper('file'); 
			 header('Content-Type: text/html; charset=utf-8');
			// include Requests
			Requests::register_autoloader();  
			$apiurl=base_url();
			$url=$apiurl.$urlapi;
			//echo $url; Die();
			$request = Requests::get($url, array('Accept' => 'application/json'));
			return $request;
			 
    } 
}