<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); header('Content-type: text/html; charset=utf-8');
class Facebookapi extends REST_Controller {
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
    public function profile2_get($token=''){
        $token = $this->input->get('token', FALSE, $token);
        $token_data = $this->tppy_oauth->parse_token($token);
        $checktoken = $this->tppy_oauth->check_token($token_data, 'profile');
        if($token==Null){

            }else{
                    $token_data = $this->tppy_oauth->parse_token($token);
            $checktoken = $this->tppy_oauth->check_token($token_data, 'profile');
            }

            #echo '<pre> $token=>'; print_r($token); echo '</pre>'; Die();
            if($token==Null){
                $this->load->library('session');
                    $session_data=$this->session->all_userdata();
                    $token=Null;
            }else{
                    $this->load->library('session');
                    $session_data=@$this->session->all_userdata();
                    $user_id=@$session_data['user_id'];
                    #echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>'; Die();
                    if($user_id<>''){
                             $session_data=$this->session->sess_destroy(); 

                             //Clear session
                            }

            }
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
    public function profile_post(){

            $user_id = @$this->input->post('id');
        $token = @$this->input->post('token');

        if($token==Null){

            }else{
             $token_data = $this->tppy_oauth->parse_token($token);
         $checktoken = $this->tppy_oauth->check_token($token_data, 'profile');	
            }


            #echo '<pre> $token=>'; print_r($token); echo '</pre>'; Die();
            $this->load->library('user_agent');
            $platform=$this->agent->platform(); 
            if ($this->agent->is_browser()){
            $agent = $this->agent->browser().' '.$this->agent->version();
            }elseif ($this->agent->is_robot()){
            $agent = $this->agent->robot();
            }elseif ($this->agent->is_mobile()){
            $agent = $this->agent->mobile();
            }else{
            $agent = 'Unidentified User Agent';
            } 
            $agentdata=array('platform'=>$platform,'agent'=>$agent);
            // Model
          $this->load->model('api/crypt_model');
          // key
              $key= $this->crypt_model->key();
              // data
              $hash = $this->input->post('hash');
              #echo '<pre> $hash=>'; print_r($hash); echo '</pre>'; Die();
              #$data_encrypt = $this->crypt_model->base64_encrypt($hash,$key);
              $data_decrypt = $this->crypt_model->base64_decrypt($hash,$key);

               #echo '<pre> $hash=>'; print_r($hash); echo '</pre>';
               #echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>';
               #echo '<pre> $data_decrypt=>'; print_r($data_decrypt); echo '</pre>'; Die();

              if($token==Null){
                     $cryptkey=$user_id;
              }else{
                     $cryptkey=$token; 
              }
                #echo '<pre> $token=>'; print_r($token); echo '</pre>'; 
                #echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>';
                #echo '<pre> $cryptkey=>'; print_r($cryptkey); echo '</pre>';
                #echo '<pre> $data_decrypt=>'; print_r($data_decrypt); echo '</pre>'; Die();

              if($data_decrypt==$cryptkey){
                    /*
                     $this->response(array(
                            'response'=>array(
                                                             'status'=>true, 
                                                             'message'=>'Successful,Key is matching',
                                                             'code'=>401),'data'=> null)
                                                             ,401);
                                                             Die();
                    */
              }else{
                     $this->response(array('response'=>array(
                                                             'status'=>false, 
                                                             'message'=>'Error,key not matching',
                                                             'code'=>401),'data'=> null)
                                                             ,401);
                            Die();
              }
            if($user_id!=='' && $token==Null){
                $this->load->library('session');
                    $session_data=$this->session->all_userdata();
                    $user_id_session=$session_data['user_id'];
                    #echo '<pre> $user_id_session=>'; print_r($user_id_session); echo '</pre>'; Die();
                    #echo '<pre> $session_data=>'; print_r($session_data); echo '</pre>'; Die();
                    if($user_id_session==Null){
                  $this->response(array(
                  'response'=>array(
                          'status'=>false, 
                          'message'=>'Not Log In ,Press login System',
                          'code'=>401), 
                          'data'=> null), 401);
                }
                    $token=$user_id;
                    $token_user_id=$user_id;
                    $token_data=Null;
                    $status=True;
            $message='API Post Web Successful';
            }else{
                    $this->load->library('session');
                    $session_data=@$this->session->all_userdata();
                    $user_id=@$session_data['user_id'];
                    #echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>'; Die();
                    $token_user_id=$token_data->user_id;
                    $status=$checktoken['status'];
            $message='API app Post '.$checktoken['message'];
                    if($user_id!==''){
                             #$session_data=$this->session->sess_destroy(); 

                             //Clear session
                            }

            }
            #echo '<pre> $status=>'; print_r($status); echo '</pre>'; Die();
        if($status==1 || $status!==Null) {
          $this->response(array(
                      'response'=>array(
                            'user_agent'=>$agentdata,
                            #'hashvaluekey'=>$hash,
                            'status'=>true, 
                            'message'=>$message,
                            'token'=>$token,
                            'token_data'=>$token_data,
                            'session_data'=>$session_data,
                            'code'=>200), 
                            'data'=> $this->member_model->get_profile_by_userid($token_user_id)), 200);
        }else{
          $this->response(array(
          'response'=>array(
                  'status'=>false, 
                  'message'=>$message,
                  'code'=>401), 
                  'data'=> null), 401);
        }
      }
    public function profile_get(){

            $user_id = @$this->input->get('id');
        $token = @$this->input->get('token');
        $hash = $this->input->get('hashkey');

        if($token==Null){

            }else{
             $token_data = $this->tppy_oauth->parse_token($token);
         $checktoken = $this->tppy_oauth->check_token($token_data, 'profile');	
            }


            #echo '<pre> $token=>'; print_r($token); echo '</pre>'; Die();
            $this->load->library('user_agent');
            $platform=$this->agent->platform(); 
            if ($this->agent->is_browser()){
            $agent = $this->agent->browser().' '.$this->agent->version();
            }elseif ($this->agent->is_robot()){
            $agent = $this->agent->robot();
            }elseif ($this->agent->is_mobile()){
            $agent = $this->agent->mobile();
            }else{
            $agent = 'Unidentified User Agent';
            } 
            $agentdata=array('platform'=>$platform,'agent'=>$agent);
            // Model
          $this->load->model('api/crypt_model');
          // key
              $key= $this->crypt_model->key();
              // data


               #$data_encrypt = $this->crypt_model->base64_encrypt($hash,$key);
               $data_decrypt = $this->crypt_model->base64_decrypt($hash,$key);
               #echo '<pre> $hash=>'; print_r($hash); echo '</pre>'; 
               #echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>';
               #echo '<pre> $data_decrypt=>'; print_r($data_decrypt); echo '</pre>';Die();

                if($token==Null){}else{
                    $data_encrypt = $this->crypt_model->base64_encrypt($token,$key);
                    $data_decrypt = $this->crypt_model->base64_decrypt($data_encrypt,$key);
                    }

              if($token==Null){
                     $cryptkey=$user_id;
              }else{
                     $cryptkey=$token; 
              }
                #echo '<pre> $token=>'; print_r($token); echo '</pre>'; 
                #echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>';
                #echo '<pre> $cryptkey=>'; print_r($cryptkey); echo '</pre>';
                #echo '<pre> $data_decrypt=>'; print_r($data_decrypt); echo '</pre>'; Die();

              if($data_decrypt==$cryptkey){
                    /* 
                     $this->response(array(
                            'response'=>array(
                                                             'status'=>true, 
                                                             'message'=>'Successful,Key is matching',
                                                             'code'=>200),'data'=> null)
                                                             ,200);
                                                             Die();
                     */
              }elseif($data_decrypt!==$cryptkey){
                     $this->response(array('response'=>array(
                                                             'status'=>false, 
                                                             'message'=>'Error,key not matching',
                                                             'code'=>401),'data'=> null)
                                                             ,401);
                            Die();
              }
            if($user_id!=='' && $token==Null){
                $this->load->library('session');
                    $session_data=$this->session->all_userdata();
                    $user_id_session=$session_data['user_id'];
                    #echo '<pre> $user_id_session=>'; print_r($user_id_session); echo '</pre>'; Die();
                    #echo '<pre> $session_data=>'; print_r($session_data); echo '</pre>'; Die();
                    if($user_id_session==Null){
                  $this->response(array(
                  'response'=>array(
                          'status'=>false, 
                          'message'=>'Not Log In ,Press login System',
                          'code'=>401), 
                          'data'=> null), 401);
                }
                    $token=$user_id;
                    $token_user_id=$user_id;
                    $token_data=Null;
                    $status=True;
            $message='API Get Web Successful';
            }else{
                    $this->load->library('session');
                    $session_data=@$this->session->all_userdata();
                    $user_id=@$session_data['user_id'];
                    #echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>'; Die();
                    $token_user_id=$token_data->user_id;
                    $status=$checktoken['status'];
            $message='API app Get '.$checktoken['message'];
                    if($user_id!==''){
                             #$session_data=$this->session->sess_destroy(); 

                             //Clear session
                            }

            }
            #echo '<pre> $status=>'; print_r($status); echo '</pre>'; Die();
        if($status==1 || $status!==Null) {
          $this->response(array(
                      'response'=>array(
                            'user_agent'=>$agentdata,
                            #'hashvaluekey'=>$hash,
                            'status'=>true, 
                            'message'=>$message,
                            'token'=>$token,
                            'token_data'=>$token_data,
                            'session_data'=>$session_data,
                            'code'=>200), 
                            'data'=> $this->member_model->get_profile_by_userid($token_user_id)), 200);
        }else{
          $this->response(array(
          'response'=>array(
                  'status'=>false, 
                  'message'=>$message,
                  'code'=>401), 
                  'data'=> null), 401);
        }
      }
    public function logincheck_post(){
                      $data=array();
                      $message='Web Service Restfull API';
                  if($this->input->post()){
                            $post=$this->input->post();
                         #echo '<pre> $post=>'; print_r($post); echo '</pre>';Die();



                         // Model key
                          $this->load->model('api/crypt_model');
                              $key= $this->crypt_model->key();
                              $apikey=$this->crypt_model->apikey();
                              $hash=$post['hashkey'];
                              $hash_key=$apikey;
                          $data_encrypt = $this->crypt_model->base64_encrypt($hash_key,$key);
                              $data_decrypt = $this->crypt_model->base64_decrypt($hash,$key);
                           /* 
                           echo '<pre> $hash=>'; print_r($hash); echo '</pre>';
                           echo '<pre> $data_encrypt=>'; print_r($data_encrypt); echo '</pre>';
                           echo '<pre> $data_decrypt=>'; print_r($data_decrypt); echo '</pre>';
                           echo '<pre> $hash_key=>'; print_r($hash_key); echo '</pre>';
                           echo '<pre> $post=>'; print_r($post); echo '</pre>';Die();
                           */
                         // Cjack api key
                         if($data_decrypt!==$hash_key){
                                    $this->response(array('response'=>array(
                                                             'status'=>false, 
                                                             'message'=>'Error,key not matching',
                                                             'code'=>401),'data'=> null)
                                                             ,401);
                                                            Die();
                                    }



                     $app_id="1000002";
                     $scopes="profile,email";
                     $uuid=$this->tppy_oauth->get_user_uuid();
                     $redirect_uri = urlencode(site_url());
                     $state="";
                     $app_secret="9fbe2ba6bc88990d2078348f41fe75a2";
                     $user_username=$this->input->post('user_username');
                     $user_password=MD5($this->input->post('user_password'));
                     /* * * * ACCOUNT * * * */
                     // AUTHORIZE
                     $url=site_url("/tools2/api/authorize?username=$user_username&password=$user_password&app_id=$app_id&scopes=$scopes&redirect_uri=$redirect_uri&uuid=$uuid");
                    #echo '<pre> $url=>'; print_r($url); echo '</pre>';Die();
                            $code_data = json_decode(file_get_contents($url), FALSE);
                     $data['authorize_url']=$url;
                     // TOKEN
                     $url=site_url("/tools2/api/accesstoken?code=".$code_data->data->code."&app_id=$app_id&redirect_uri=$redirect_uri&state=$state&app_secret=$app_secret");
                     $token_data= json_decode(file_get_contents($url), FALSE);
                     $data['token_url']=$url;
                     $data['token']=$token_data->data->accesstoken->token;
                     // PROFILE
                     $url=site_url("/tools2/api/profile?token=".$token_data->data->accesstoken->token);
                     $profile_data= json_decode(file_get_contents($url), FALSE);
                     $data['profile_url']=$url;
                             $profile_data= json_decode(file_get_contents($url), FALSE);
                     $data['profile_url']=$url;
                             #echo '<pre> $token_data=>'; print_r($token_data); echo '</pre>'; //Die();
                             $token_data2=$token_data->data;
                              $accesstoken=$token_data2->accesstoken;
                              $profile=$token_data2->profile;
                              $token=$accesstoken->token;
                              $token_ses=$token;
                              $expire_token_ses=$accesstoken->expire;
                              $profile_ses=$profile;
                              $user_id=$profile_ses->user_id;
                              $psn_display_name=$profile_ses->psn_display_name;
                              $user_email=$profile_ses->user_email;
                              $psn_display_image=$profile_ses->psn_display_image;
                              $datases=array('scopes'=>$scopes,
                                                    //'app_id'=>$app_id,
                                                     'user_id'=>$user_id,
                                                     'user_email'=>$user_email, 
                                                     'psn_display_image'=>$psn_display_image,
                                                     'psn_display_name'=>$psn_display_name, 
                                                     'uuid'=>$uuid,
                                                     //'token'=>$token_ses,
                                                     'expire'=>$expire_token_ses,
                                                     'redirect_uri'=>$redirect_uri,
                                                     //'state'=>$state,
                                                     //'app_secret'=>$app_secret,
                                                     //'user_username'=>$user_username,
                                                     //'user_password'=>$user_password,
                                                    );
                    #echo '<pre> $datases=>'; print_r($datases); echo '</pre>';     Die();	
                    ##############session
                    $this->load->library('session');
                    $this->session->set_userdata($datases);
                    $session_data=$this->session->all_userdata();
                    $data['session_data']=$session_data;
                }else{
                            $this->response(array(
                     'response'=>array(
                          'status'=>false, 
                          'message'=>'Post Method Only',
                          'code'=>401), 
                          'data'=> null), 401);
                    }
                            $this->load->library('session');
                            $session_data=@$this->session->all_userdata();
                            $user_id=@$session_data['user_id'];
                            #echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>'; Die();
                            $token_user_id=$user_id;

                    if($token_user_id==Null){
                            $this->response(array(
                     'response'=>array(
                          'status'=>false, 
                          'message'=>'Plase login ',
                          'code'=>401), 
                          'data'=> null), 401);
                          Die();
                    }	
               if($user_id==Null) {
                       $this->response(array(
                  'response'=>array(
                          'status'=>false, 
                          'message'=>$message,
                          'code'=>401), 
                          'data'=> null), 401);
                }else{
                      $this->response(array(
                              'response'=>array(
                                    'user_agent'=>$agentdata,
                                    #'hashvaluekey'=>$hash,
                                    'status'=>true, 
                                    'message'=>$message,
                                    'token'=>$token,
                                    'token_data'=>$token_data,
                                    'session_data'=>$session_data,
                                    'code'=>200), 
                                    'data'=> $this->member_model->get_profile_by_userid($token_user_id)), 200);
                }
            }
    public function logout_get(){
                    $session_data=$this->session->sess_destroy();
                    $this->response(array(
                     'response'=>array(
                          'status'=>false, 
                          'message'=>'Logout Successful',
                          'code'=>401), 
                          'data'=> null), 401);
                          Die();
            }	       
    ///
    public function facebookcall_get(){
        $this->load->model('api/public_model');
        $redirect_uri = @$this->input->get('page_url', TRUE);
        $type = @$this->input->get('type');
        $state = @$this->input->get('state');
        $scopes = @$this->input->get('scopes', TRUE);
        $app_id = @$this->input->get('app_id', TRUE);
        $page_url = @$this->input->get('page_url', TRUE);
        $data=$this->load->public_model->facebookcallback();
        if($data){
            $this->response(array('response'=>array(
                              'status'=>false, 
                              'message'=>'Facebookcall',
                              'code'=>200), 
                              'data'=> $data), 200);
                              Die();
            }else{
               
                        $this->response(array('response'=>array(
                              'status'=>false, 
                              'message'=>'Error Facebookcall',
                              'code'=>404), 
                              'data'=> Null), 404);
                              Die();
                } 
                    
        }
    public function fblogin_get(){
      $this->load->model('api/public_model');
      $data=$this->load->public_model->facebookcallback();
      $fb = new Facebook\Facebook([
            'app_id' => '577675689014841',
            'app_secret' => '3e8e7e8650c369085e9da98306b8b4ee',
            'default_graph_version' => 'v2.4',
          ]);

     $helper = $fb->getRedirectLoginHelper();

     $permissions = ['email','user_location','user_birthday','publish_actions']; 
  // For more permissions like user location etc you need to send your application for review

     $loginUrl = $helper->getLoginUrl('http://localhost/sampleproject/login/fbcallback', $permissions);

     header("location: ".$loginUrl);
  }
        
        
}