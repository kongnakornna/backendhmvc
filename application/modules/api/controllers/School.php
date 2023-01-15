<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

/* response list
$this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
$this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
$this->response([
	'status' => FALSE,
	'message' => 'No users were found'
], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
*/

class School extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
		
		ob_clean();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		
		$this->load->model("school_model");
    }
 public function index_get(){
	 $this->schoollist_get();
 }
 public function schoollist_get($req = array()){
	
	$json['header']['title'] = 'API : School';
	$json['header']['status'] = '200';
	$json['header']['description'] = 'ok';
	
	$filter = array();
	$filter["q"]=$this->input->get("q") ? $this->input->get("q") : "";
	$filter["limit"]=$this->input->get("limit") ? $this->input->get("limit") : "";
	$filter["offset"]=$this->input->get("offset") ? $this->input->get("offset") : "";
	$filter["order"]=$this->input->get("order") ? $this->input->get("order") : "";
	
	$json['data'] = $this->school_model->_getSchool($filter);
	
	
	$this->response($json, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
 }
 public function schooldetail_get($school_code=NULL){
	$json['header']['title'] = 'API : School';
	$json['header']['status'] = '200';
	$json['header']['description'] = 'ok';
	
	$filter = array();
	if(!is_null($school_code)){
		$filter["schoolcode"]=$school_code;
	}else{
		$this->response("School Code Required.", REST_Controller::HTTP_BAD_REQUEST);
	}
	
	$json['data'] = $this->school_model->_getSchool($filter);
	
	
	$this->response($json, REST_Controller::HTTP_OK); // OK (200) being the HTTP response cod
 }
 public function test_get(){
	 $this->response("not use now", REST_Controller::HTTP_BAD_REQUEST);
 }
}
