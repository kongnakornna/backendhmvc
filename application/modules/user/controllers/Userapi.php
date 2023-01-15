<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
//class User extends CI_Controller{
class User extends REST_Controller{
public $link_assets = [];
public $script_assets = [];
public $assets = "assets/";
public $links = [];
public $scripts = [];
function __construct(){
        // Construct the parent class
        parent::__construct();
        date_default_timezone_set('Asia/Bangkok');
        $this->load->model("User_model");
        $this->db->cache_off();
        $this->load->helper('cookie', 'session');
        $this->load->library('connexted_library');
        $this->user_id =$_COOKIE['userid'];
        $this->user_avatar=$this->connexted_library->checkImageExists("connexted/assets/images/user/", $this->user_id, "png");
    }
public function index(){
    //$this->api_get();
    echo 'User Module';
}
public function get_ip_address() {
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
			$ip=$ipaddress;
			$ip = strpos($ip, ',') > 0 ? trim(reset(explode(',', $ip))) : trim($ip);
        return $ip;
    }
public function base64_encrypt_get($string, $key) {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result.=$char;
        }

        return base64_encode($result);
    }
public function base64_decrypt_get($string, $key) {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result.=$char;
        }

        return $result;
    }
public function base64_encrypt_post($string, $key) {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result.=$char;
        }

        return base64_encode($result);
    }
public function base64_decrypt_post($string, $key) {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result.=$char;
        }

        return $result;
    }
public function serialize_get($dataset) {
        $result=serialize($dataset);
        return $result;
}
public function unserialize_get($dataset) {
        $result=unserialize($dataset);
        return $result;
}
public function implode_get($array) {
        $result=implode(",", $array);
        return $result;
}
public function explode_get($array) {
        $result=explode(",", $array);
        return $result;
}
##################
public function api_get(){
ob_end_clean();
$dataall='na';//array('na'=>500);
$module_name='na';
if($dataall!==''){
$this->response(array('header'=>array(
				'title'=>'REST_Controller::HTTP_OK',
                        'module'=>$module_name,
				'message'=>' DATA OK',
				'status'=>TRUE,
				'code'=>200), 
				'data'=> $dataall)
                        ,200);
                        die();
}
elseif($dataall==''){
$this->response(array('header'=>array(
				'title'=>'HTTP_BAD_REQUEST',
                        'module'=>$module_name,
				'message'=>'Data could not be found',
				'status'=>FALSE, 
				'code'=>204), 
				'data'=> null,)
                        ,204);
                        die();
}
else{   
$this->response(array('header'=>array(
				'title'=>'REST_Controller::HTTP_NOT_FOUND',
                        'module'=>$message,
				'message'=>' DATA OK',
				'status'=>FALSE,
				'code'=>404), 
                        'data'=> null)
                        ,404);
                        die();
}
}
##################
}