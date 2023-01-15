<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
//class User extends CI_Controller{
class Api extends REST_Controller{
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
        $this->load->model("user/User_model",'model_user');
        $this->db->cache_off();
      $this->load->helper('cookie', 'session');
      // Load form helper library
      $this->load->helper('form');
      // Load form validation library
      $this->load->library('form_validation');
      // Load session library
      $this->load->library('session');
    }
public function index_get(){
ob_end_clean();
$dataall=array('na'=>500);
$module_name='User Module';
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
// http://connexted.local/user/api/menu
public function menu_get(){
 		//http://connexted.local/user/menu?menu_id=2&deletekey=1
		//http://connexted.local/user/api/menu?menu_id=2&deletekey=1
		$input=@$this->input->post(); //deletekey=>1 = delete
		if($input==null){$input=@$this->input->get();} 
			$menu_id=@$input['menu_id'];
			$cache_type=@$input['cache_type'];
			$menu_id=@$input['menu_id'];
		if($cache_type==null){
			$cache_type=1;     
		}

		$deletekey=@$input['deletekey'];
		$menurs=$this->load->User_model->menu($menu_id,$cache_type,$deletekey);
		$menu=$menurs['rs'];
		$menu_arr=array();

		if(is_array($menu)){
			foreach($menu as $key =>$w){
				$arr=array();
				$menu_id=(int)$w->menu_id;
				$arr['a']['menu_id']=$menu_id;
				$arr['a']['menu_id2']=$w->menu_id2;
				$arr['a']['title']=$w->title;
				$arr['a']['url']=$w->url;
				$arr['a']['parent']=$w->parent;
				$arr['a']['menu_alt']=$w->menu_alt;
				$arr['a']['option']=$w->option;
				$arr['a']['order_by']=$w->order_by;
				$arr['a']['order_by2']=$w->order_by2;
				$arr['a']['icon']=$w->icon;
				$arr['a']['params']=$w->params;
				$arr['a']['lang']=$w->lang;
				#################################
				$menu2rs=$this->load->User_model->submenu($menu_id,$cache_type,$deletekey);
				$menu2=$menu2rs['rs'];
				$menu22_arr=array();

				if(is_array($menu2)){
					foreach($menu2 as $key2 =>$w2){
						$arr2=array();
						$menu2_id=$w2->menu_id;
						$arr2['b']['menu_id']=$menu2_id;
						$arr2['b']['menu_id2']=$w2->menu_id2;
						$arr2['b']['title']=$w2->title;
						$arr2['b']['url']=$w2->url;
						$arr2['b']['parent']=$w2->parent;
						$arr2['b']['menu_alt']=$w2->menu_alt;
						$arr2['b']['option']=$w2->option;
						$arr2['b']['order_by']=$w2->order_by;
						$arr2['b']['order_by2']=$w2->order_by2;
						$arr2['b']['icon']=$w2->icon;
						$arr2['b']['params']=$w2->params;
						$arr2['b']['lang']=$w2->lang;
						$submenurs=$this->load->User_model->submenu($menu2_id,$cache_type,$deletekey);
						$submenu=$submenurs['rs'];
						$arr2['b']['submenu']=$submenu;
						$menu22_arr[]=$arr2['b'];
					}                       
				}
				#################################
				$arr['a']['submenu']=$menu22_arr;
				$menu_arr[]=$arr['a'];
			}                       
		}
$dataall=$menu_arr;	   
if($dataall!==null){
$this->response(array('header'=>array(
				'title'=>'HTTP_BAD_REQUEST',
                        'module'=>'usermenu',
				'message'=>'Data could not be found',
				'status'=>FALSE, 
				'code'=>204), 
				'data'=> dataall,)
                        ,204);
                        die();
}else{   
$this->response(array('header'=>array(
				'title'=>'REST_Controller::HTTP_NOT_FOUND',
                        'module'=>'usermenu',
				'message'=>' DATA OK',
				'status'=>FALSE,
				'code'=>404), 
                        'data'=> null)
                        ,404);
                        die();
}
}




}