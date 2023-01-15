<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Firebase\JWT\JWT;
class Auth extends BD_Controller {
function __construct(){
// Construct the parent class
parent::__construct();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('M_main', 'M_main');
}
public function login_post(){
        $u = $this->post('username'); //Username Posted
        $p = sha1($this->post('password')); //Pasword Posted
        $q = array('username' => $u); //For where query condition
        $kunci = $this->config->item('thekey');
        //echo '<pre> kunci-> '; print_r($kunci); echo '</pre>'; 
        #$kunci = $this->config->item('apirestkey');
        $invalidLogin = ['status' => 'Invalid Login การเข้าสู่ระบบผิด ']; //Respon if login invalid
        $val = $this->M_main->get_user($q)->row(); 
        //Model to get single data row from database base on username 
     //echo '<pre> q-> '; print_r($q); echo '</pre>';
     //echo '<pre> kunci-> '; print_r($kunci); echo '</pre>'; 
     //echo '<pre> val-> '; print_r($val); echo '</pre>'; die();
        if($this->M_main->get_user($q)->num_rows() == 0){$this->response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);}
		$match = $val->password;   //Get password for user from database
        if($p == $match){  //Condition if password matched
        	$token['id'] = $val->id;  //From here
            $token['username'] = $u;
            $date = new DateTime();
            $token['iat'] = $date->getTimestamp();
            $token['exp'] = $date->getTimestamp() + 60*60*5; //To here is to generate token
            $output['token'] = JWT::encode($token,$kunci ); //This is the output token
            $this->set_response($output, REST_Controller::HTTP_OK); //This is the respon if success
        }
        else {
            $this->set_response($invalidLogin, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
        }
    }
public function login_get(){
        $u = $this->get('username'); //Username Posted
        $p = sha1($this->get('password')); //Pasword Posted
        $q = array('username' => $u); //For where query condition
        $kunci = $this->config->item('thekey');
        //echo '<pre> kunci-> '; print_r($kunci); echo '</pre>'; 
        #$kunci = $this->config->item('apirestkey');
        $invalidLogin = ['status' => 'Invalid Login การเข้าสู่ระบบผิด ']; //Respon if login invalid
        $val = $this->M_main->get_user($q); 
        //Model to get single data row from database base on username 
 echo '<pre> q-> '; print_r($q); echo '</pre>';
 echo '<pre> kunci-> '; print_r($kunci); echo '</pre>'; 
 echo '<pre> val-> '; print_r($val); echo '</pre>'; die();
        if($this->M_main->get_user($q)->num_rows() == 0){$this->response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);}
		$match = $val->password;   //Get password for user from database
        if($p == $match){  //Condition if password matched
        	$token['id'] = $val->id;  //From here
            $token['username'] = $u;
            $date = new DateTime();
            $token['iat'] = $date->getTimestamp();
            $token['exp'] = $date->getTimestamp() + 60*60*5; //To here is to generate token
            $output['token'] = JWT::encode($token,$kunci ); //This is the output token
           

           $secure_key = $this->config->item('thekey');
         
           $decoded=JWT::decode($output,$secure_key);
           echo '<pre> output-> '; print_r($output); echo '</pre>';
           echo '<pre> decoded-> '; print_r($decoded); echo '</pre>'; die();
          //$this->set_response($output, REST_Controller::HTTP_OK);
            
 
            
             //This is the respon if success
        }else{
            $this->set_response($invalidLogin, REST_Controller::HTTP_NOT_FOUND); 
            //This is the respon if failed
        }
    }
}