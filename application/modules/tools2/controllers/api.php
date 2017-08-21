<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); header('Content-type: text/html; charset=utf-8');

class Api extends REST_Controller {

  
  function __construct() {
    parent::__construct();
	ob_clean();
	header('Content-Type: text/html; charset=utf-8');
    $this->load->model('oauth_model');
    $this->load->model('member_model');
    $this->load->library('TPPY_Oauth');
  } 
  
  function valid_email($str) {
      return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
  }
  
  private function login($username, $password, $app_id, $scopes, $ep) {
    // CHANNELING ID
    if(!empty($ep)){
      $arr = explode('|', $ep);
      if(count($arr) === 7){
        $type=$arr[0];
        $id=$arr[1];
        $email=$arr[2];
        $name=$arr[3];
        $time=$arr[4];
        $app_id=$arr[5];
        $hash=$arr[6];

        if($type=='facebook') {
          $app_data = $this->oauth_model->get_appdata_by_appid($app_id);
          $app_secret=$app_data->app_secret;
          // CHECK HASH
          if(MD5("$type|$id|$email|$name|$time|$app_id|$app_secret") == $hash) {
            if(!$this->valid_email($email)){
              // return array('error'=>'INVALID EMAIL FORMAT'); 
              $email = $id . "@FB.com";
            }
          
            $profiles = $this->member_model->get_profile_by_facebookid($id);
            if(!$profiles){
              $profiles = $this->member_model->get_profile_by_email($email);
              if($profiles) {
                $update = $this->member_model->update(array('acc_user_facebook'=>$id), $profile['user_id']);  // UPDATE field : acc_user_facebook
                $profile = $this->member_model->get_profile_by_email($email);
              }else{  // GOTO REGISTER
                $username =  $email;
                $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
                $insert_data = array (
                  'user_username'=> $email,
                  'user_password'=> MD5($password),
                  'psn_display_name'=> $name,
                  'psn_display_image'=>"http://graph.facebook.com/$id/picture?type=large",
                  'user_email'=> $email,
                  'user_create_date'=> date('Y-m-d H:i:s'),
                  'user_create_ip'=> ip_client(),
                  'user_group'=> 'MEM',
                  'user_password_tmp'=>'',
                  'user_status'=>1,
                  'acc_user_facebook'=>$id,
                  'user_active_date'=>date('Y-m-d H:i:s'),
                );
                
                $user_id = $this->member_model->insert($insert_data);
                $profile = $this->member_model->get_profile_by_facebookid($google_id);
                $this->load->library('sendActivateEmail');
                $this->member_library->sendActivateEmail($user_id, $email, $username, $password);
                return $profile;
              }
            }
            if($profiles){
              $profile = $this->member_model->check_user_status($profiles, 'en');
              return $profile;
            }else{
              return array('error'=>'INVALID HASH'); 
            }
          }else{
            return array('error'=>'INVALID HASH'); 
          }
        } else if($type=='google'){
           return array('error'=>'WE DISABLED GOOGLE ID'); 
        } else {
           return array('error'=>'INVALID PARAMETER [NO TYPE]'); // INVALID PARAMETER [FUNCTION WRONG FORMAT]
        }
      } else {
        return array('error'=>'INVALID PARAMETER [FUNCTION WRONG FORMAT]'); // INVALID PARAMETER [FUNCTION WRONG FORMAT]
      }
    } 
    
    // NORMAL ID
    if(!empty($username) && !empty($password) && !empty($app_id) && !empty($scopes) ) {
      $profiles = $this->member_model->get_profile_by_username_or_email_password($username, $password);
      // _vd($profiles);
      if($profiles){
        $profile = $this->member_model->check_user_status($profiles, 'en');
        return $profile;
      }else{
        return array('error'=>'INVALID USERNAME OR PASSWORD'); // INVALID USERNAME OR PASSWORD
      }
    }
    return array('error'=>'INVALID PARAMETER [FUNCTION WRONG FORMAT]'); // INVALID PARAMETER [FUNCTION WRONG FORMAT]
  }

  public function authorize_get() {
    $username=$this->get('username');
    $password=$this->get('password');
    $app_id=$this->get('app_id');
    $scopes=$this->get('scopes');
    $redirect_uri=$this->get('redirect_uri');
    $user_uuid=$this->get('uuid');
    $ep=$this->get('ep');
     
    if(!empty($scopes) && !empty($app_id) && !empty($redirect_uri) && !empty($user_uuid) && ( !empty($username) && !empty($password) ) || ( !empty($ep) )  ) {
      $app_data = $this->oauth_model->get_appdata_by_appid($app_id);
      if($app_data) { // มี Application นี้ในโลกหรือไม่
        if(strpos_array($redirect_uri, explode(',', $app_data->app_domain))) { // domain ตรงหรือเปล่า
          $member_data = $this->login($username, $password, $app_id, $scopes, $ep);
		  #echo '<pre> $member_data=>'; print_r($member_data); echo '</pre>';Die();
          if($member_data && empty($member_data['error'])) { // ไอดีนี้มีจริงหรือไม่
                    // $expired = gmmktime(); 
            $code = $this->tppy_oauth->generate_code($member_data['user_id'], $app_id, $app_data->app_secret, $user_uuid,  $scopes, $this->input->ip_address());
            $this->response(array('response'=>array('status'=>true, 'message'=>"success",'code'=>200), 'data'=> array('code'=>$code)), 200);
          } else {
            $this->response(array('response'=>array('status'=>false, 'message'=>$member_data['error'],'code'=>400), 'data'=> null), 400);
          }
        } else {
          $this->response(array('response'=>array('status'=>false, 'message'=>'invalid redirect_uri','code'=>400), 'data'=> null), 400);
        }
      } else {
        $this->response(array('response'=>array('status'=>false, 'message'=>'normal error','code'=>401), 'data'=> null), 401);
      }
    } else {
      $this->response(array('response'=>array('status'=>false, 'message'=>'invalid parameter','code'=>400), 'data'=> null), 400);
    }
  }
  public function accesstoken_get() {
    $code = $this->input->get('code', FALSE);
    $app_secret = $this->input->get('app_secret', TRUE);
    $app_id = $this->input->get('app_id', TRUE);
    $redirect_uri = $this->input->get('redirect_uri', TRUE);
    $state = $this->input->get('state', TRUE);
// _vd($app_id);

        // _vd($code); die;
    if(!empty($code) && !empty($app_secret) && !empty($app_id)) {
      $app_data =  $this->oauth_model->get_appdata_by_appid($app_id);
      if($app_data && $app_secret == $app_data->app_secret) {
        $access_token_data = $this->tppy_oauth->parse_code($code, $app_secret);
        if($access_token_data && $access_token_data->user_uuid) {
          $user_uuid = $access_token_data->user_uuid;
          if(gmmktime() < $access_token_data->expired) {
            $user_data = $this->member_model->get_profile_by_userid($access_token_data->user_id);
            $expire = $app_data->app_expire_time+gmmktime();
            $encode_token = $this->tppy_oauth->generate_token($access_token_data->user_id, $user_data['member_id'], $access_token_data->app_id, $access_token_data->scopes, $user_uuid, $app_data->app_expire_time);
            $short_profile=$this->member_model->get_profile_by_user_id_short($access_token_data->user_id);

            $this->response(array('response'=>array('status'=>true, 'message'=>"success",'code'=>200), 'data'=> array(
              'accesstoken'=>array(
              'token'=>$encode_token, 'state'=>urlencode($state), 'redirect_uri'=>urldecode($redirect_uri), 'expire' => $expire
              ), 'profile'=>$short_profile
            )), 200);
          } else {
            $this->response(array('response'=>array('status'=>false, 'message'=>'timeout access_token','code'=>400), 'data'=> null), 400);
          }
        } else {
           $this->response(array('response'=>array('status'=>false, 'message'=>'invalid code','code'=>400), 'data'=> null), 400);
        }
      } else {
         $this->response(array('response'=>array('status'=>false, 'message'=>'invalid app_secret','code'=>400), 'data'=> null), 400);
      }
    }else{
      $this->response(array('response'=>array('status'=>false, 'message'=>'invalid parameter','code'=>400), 'data'=> null), 400);
    }
  }
  public function renewtoken_get() {
    $token=$this->get('token', FALSE);
    $uuid=$this->get('uuid');
    
    $token_data = $this->tppy_oauth->parse_token($token);
    $checktoken = $this->tppy_oauth->check_token($token_data, 'profile');

    if($checktoken['status']) {
      if($token_data->user_uuid == $uuid) {
        $app_data =  $this->oauth_model->get_appdata_by_appid($token_data->app_id);
        $token=$this->tppy_oauth->generate_token($token_data->user_id, $token_data->member_id, $token_data->app_id, $stoken_data->scopes, $token_data->user_uuid, $app_data->app_expire_time);
        $short_profile=$this->member_model->get_profile_by_user_id_short($token_data->user_id);
        $this->response(array('response'=>array('status'=>true, 'message'=>"success",'code'=>200), 'data'=> array(
        'accesstoken'=>array(
          'token'=>$token, 'state'=>'', 'redirect_uri'=>'', 'expire' => $expire
          ), 'profile'=>$short_profile
        )), 200);
      }else{
        $this->response(array('response'=>array('status'=>false, 'message'=>"Invalid uuid",'code'=>401), 'data'=> null), 401);
      }
    }else{
      $this->response(array('response'=>array('status'=>false, 'message'=>$checktoken['message'],'code'=>(int)$checktoken['code']), 'data'=> null), (int)$checktoken['code']);
    }
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  public function profile_get($token=''){
    $token = $this->input->get('token', FALSE, $token);
    $token_data = $this->tppy_oauth->parse_token($token);
    $checktoken = $this->tppy_oauth->check_token($token_data, 'profile');
	$this->load->library('session');
	$session_data=$this->session->all_userdata();
    if($checktoken['status']) {
      $this->response(array(
		  'response'=>array(
			'status'=>true, 
			'message'=>'success',
			'token'=>$token,
			'token_data'=>$token_data,
			'session_data'=>$session_data,
			'code'=>200), 
			'data'=> $this->member_model->get_profile_by_userid($token_data->user_id)), 200);
    }else{
      $this->response(array('response'=>array('status'=>false, 'message'=>$checktoken['message'],'code'=>401), 'data'=> null), 401);
    }
  }
}