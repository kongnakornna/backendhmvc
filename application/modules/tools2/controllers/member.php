<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Member extends TPPY_Controller {   
  public function __construct() {
    parent::__construct();
    $this->template->add_stylesheet('assets/tppy_v1/bower_components/jquery-ui/themes/base/jquery-ui.min.css');
    $this->template->add_stylesheet('assets/tppy_v1/bower_components/jquery-ui/themes/base/jquery-ui.structure.min.css');
    $this->template->add_stylesheet('assets/tppy_v1/bower_components/jquery-ui/themes/base/jquery-ui.theme.css');
    $this->template->add_stylesheet('assets/jquery-select/css/bootstrap-select.min.css');
    $this->template->add_stylesheet('assets/css/bootstrap/css/bootstrap.css');
    $this->template->add_javascript('assets/css/bootstrap/js/bootstrap.min.js');
    $this->template->add_stylesheet('assets/bootstrap/bootstrap-tooltip/css/bootstrap.css');
    $this->template->add_stylesheet('assets/bootstrap/bootstrap-tooltip/css/bootstrap.css');
    $this->template->add_javascript('assets/bootstrap/bootstrap-tooltip/js/bootstrap.js');
    $this->template->add_stylesheet('assets/bootstrap/bootstrap-tooltip/css/bootstrap.css');
    $this->template->add_javascript('assets/bootstrap/bootstrap-tooltip/js/bootstrap.js');
    $this->load->model('member_model');
    $this->load->model('users/user_model');
    $this->load->library('encrypt');
    $this->load->library('form_validation');
    $this->load->library('TPPY_Oauth');
    $this->load->model('oauth_model');
    $this->load->library('TPPY_Member');
    $this->load->library('Member_Library');
    
  }
  public function index() {
     $this->loginfb();
  }

  public function loginfb() {
    $data = array();
    $scopes = $this->input->get('scopes', TRUE);
    $app_id = $this->input->get('app_id', TRUE);
    $page_url = $this->input->get('page_url', TRUE);
    $state = $this->input->get('state', TRUE);
    $redirect_uri = $this->input->get('redirect_uri', TRUE);
    
    if(strpos($page_url, '/member/') || strrpos($page_url, 'register')){
      $page_url = '';
    }

    if($this->input->post()) {
      $user_username_email = $this->input->post('user_username');
      $user_password = $this->input->post('user_password');
      $profiles = $this->member_model->get_profile_by_username_or_email_password($user_username_email, MD5($user_password));
    }

    if(!empty($profiles)) {
      $profile = $this->member_model->check_user_status($profiles);
      if(empty($profile['error'])) {
        if($app_id) {
          $app_data = $this->oauth_model->get_appdata_by_appid($app_id);  
          $code = $this->tppy_oauth->generate_code($profile['user_id'], $app_data->app_id, $app_data->app_secret, $this->tppy_oauth->get_user_uuid(),  $scopes, $this->input->ip_address());
          $page_url = $redirect_uri. (strpos($redirect_uri,'?') ? '&' : '?' ) .'code='.$code. (!empty($state) ? '&state='.urlencode($state) : '');
        }
        $this->setMemberCookieSession($profile); // LOGIN 
        // die;
        $this->redirect_type($page_url);
      } else {
        $data['error']="<p>".$profile['error']."</p>";
      }
    }elseif($this->input->post())  { // รหัสผ่านผิด หรือไม่มี username นี้ในระบบ
       $data['error']='<p>ชื่อผู้ใช้ หรือรหัสผ่าน ไม่ถูกต้อง <br/>หรือกด "<a href="'.site_url('/member/forgot').'">ที่นี่</a>" เพื่อขอรหัสผ่านใหม่ได้ค่ะ</p>';
    }
    #echo '<pre> $data=>'; print_r($data); echo '</pre>';
    $this->template->view('/member/form_login', $data);
    
  }
  
  /* LOGIN */
  public function login() {
    $data = array();
    $scopes = $this->input->get('scopes', TRUE);
    $app_id = $this->input->get('app_id', TRUE);
    $page_url = $this->input->get('page_url', TRUE);
    $state = $this->input->get('state', TRUE);
    $redirect_uri = $this->input->get('redirect_uri', TRUE);
    
    if(strpos($page_url, '/member/') || strrpos($page_url, 'register')){
      $page_url = '';
    }

    if($this->input->post()) {
      $user_username_email = $this->input->post('user_username');
      $user_password = $this->input->post('user_password');
      $profiles = $this->member_model->get_profile_by_username_or_email_password($user_username_email, MD5($user_password));
    }

    if(!empty($profiles)) {
      $profile = $this->member_model->check_user_status($profiles);
      if(empty($profile['error'])) {
        if($app_id) {
          $app_data = $this->oauth_model->get_appdata_by_appid($app_id);  
          $code = $this->tppy_oauth->generate_code($profile['user_id'], $app_data->app_id, $app_data->app_secret, $this->tppy_oauth->get_user_uuid(),  $scopes, $this->input->ip_address());
          $page_url = $redirect_uri. (strpos($redirect_uri,'?') ? '&' : '?' ) .'code='.$code. (!empty($state) ? '&state='.urlencode($state) : '');
        }
        $this->setMemberCookieSession($profile); // LOGIN 
        // die;
        $this->redirect_type($page_url);
      } else {
        $data['error']="<p>".$profile['error']."</p>";
      }
    }elseif($this->input->post())  { // รหัสผ่านผิด หรือไม่มี username นี้ในระบบ
       $data['error']='<p>ชื่อผู้ใช้ หรือรหัสผ่าน ไม่ถูกต้อง <br/>หรือกด "<a href="'.site_url('/member/forgot').'">ที่นี่</a>" เพื่อขอรหัสผ่านใหม่ได้ค่ะ</p>';
    }
    #echo '<pre> $data=>'; print_r($data); echo '</pre>';
    $this->template->view('/member/form_login', $data);
  }
  public function logout(){
    $this->session->unset_userdata('user_session');
    $result = $this->input->set_cookie(array(
      'name' => 'PLGVINGLYJO',
      'value' =>  null,//($User_class, 'okubyqnhur'),
      'expire' => -1,
    ));
    redirect('/home');
  }
  public function setMemberCookieSession($profile=array(), $cookieTime = 3600) {    
    // SESSION 
    $User_class = (object)$profile;

    $User_class->id = $profile['user_id'];
    $User_class->group_id = $profile['group_code'];
    $User_class->last_login = time();
    $User_class->email = $profile['user_email'];
    $User_class->first_name = $profile['psn_firstname'];
    $User_class->last_name = $profile['psn_lastname'];
    $User_class->member_id = $profile['member_id'];
    $User_class->avatar = $profile['psn_display_image'];
    $User_class->blog_id = $profile['blog_id'];
    $User_class->psn_display_name = $profile['psn_display_name'];
    $User_class->member_usrname = $profile['user_username'];
    $User_class->token_key = md5($rs['user_id'] . $rs['province_id'] . $rs['blog_id'] . $rs['occ_id']);
    // $profile['token_key'];
    $User_class->folder_path = $profile['folder_path'];
    $User_class->member_folder_path = $this->tppy_member->get_member_folder($profile['user_id']);
    $User_class->member_folder_url = $this->tppy_member->get_member_url($profile['user_id']);
    /* TOKEN*/
    $this->load->model('oauth_model');
    $this->load->library('TPPY_Oauth');
    $app_data = $this->oauth_model->get_appdata_by_appid("1000001");
    $user_uuid=$this->tppy_oauth->get_user_uuid();
    $encode_token = $this->tppy_oauth->generate_token($profile['user_id'], $profile['member_id'], $app_data->app_id, "profile", $user_uuid, $app_data->app_expire_time);
    $User_class->token = $encode_token;
    /* TOKEN*/
    
    // $this->session->set_userdata('user_session', $User_class);
    
    $update_data = array('user_login_date'=>date('Y-m-d H:i:s'));
    
    $update = $this->member_model->update($update_data, $profile['user_id'], false);
    

// $this->load->library('encrypt');
        // COOKIE 
      $result = $this->input->set_cookie(array(
          'name' => 'PLGVINGLYJO',
          'value' =>  base64encrypt(serialize($User_class), 'p^lg:viNg:l=yjo'),//($User_class, 'okubyqnhur'),
          'expire' => 259200,
      ));
        
          // _vd(serialize($User_class));die; 
    // foreach($profile as $k => $v){
      // $result = $this->input->set_cookie(array(
          // 'name' => $k,
          // 'value' =>  base64_encode(base64_encode(base64_encode($v))),
          // 'expire' => $cookieTime,
      // ));
    // }
    
        // var_dump($profile, $update_data);
    // die;
  }
  public function facebookConnect_Test() {
    $redirect_uri = $this->input->get('page_url', TRUE);
    $type = $this->input->get('type');
    $state = $this->input->get('state');
    
    $scopes = $this->input->get('scopes', TRUE);
    $app_id = $this->input->get('app_id', TRUE);
    $page_url = $this->input->get('page_url', TRUE);

    $CI = & get_instance();
    $CI->config->load("facebook", TRUE);
    $config = $CI->config->item('facebook');
    $this->load->library('Facebook', $config);
    _vd($config['appId']);
    $userId = $this->facebook->getUser();
    _vd($userId);
    if ($userId == 0) {
      $data['url'] = $this->facebook->getLoginUrl(array('scope' => 'email'));
      $this->redirect_type($data['url']);
    } else {
      $facebook_data = $this->facebook->api('/me?fields=id,name,email,verified');
_vd($facebook_data);

$facebook_data = $this->facebook->api('/me?fields=id,name,email,verified');
_vd($facebook_data);
    }
  }
  public function facebookConnect() {
    $redirect_uri = $this->input->get('page_url', TRUE);
    $type = $this->input->get('type');
    $state = $this->input->get('state');
    $scopes = $this->input->get('scopes', TRUE);
    $app_id = $this->input->get('app_id', TRUE);
    $page_url = $this->input->get('page_url', TRUE);

    $CI = & get_instance();
    $CI->config->load("facebook", TRUE);
    $config = $CI->config->item('facebook');
    $this->load->library('Facebook', $config);
    $userId = $this->facebook->getUser();
    #####################################
    /*
    echo '<pre> $redirect_uri=>'; print_r($redirect_uri); echo '</pre>';
    echo '<pre> $type=>'; print_r($type); echo '</pre>';
    echo '<pre> $state=>'; print_r($state); echo '</pre>';
    echo '<pre> $scopes=>'; print_r($scopes); echo '</pre>';
    echo '<pre> $app_id=>'; print_r($app_id); echo '</pre>';
    echo '<pre> $page_url=>'; print_r($page_url); echo '</pre>';
    */
    
    $facebook_data = $this->facebook->api('/me?fields=id,name,email,verified');
      $facebook_email = $facebook_data['email'];
      $facebook_id = $facebook_data['id'];
      $facebook_firstname = $facebook_data['name'];
      $profile = $this->member_model->get_profile_by_facebookid($facebook_id);
    
    /*
    echo '<pre> $config=>'; print_r($config); echo '</pre>';  
	echo '<pre> $facebook_data=>'; print_r($facebook_data); echo '</pre>'; 
    echo '<pre> $userId=>'; print_r($userId); echo '</pre>';
    echo '<pre> $profile=>'; print_r($profile); echo '</pre>';
    Die();
    */
    
    
    #####################################
    if ($userId == 0) {
      $data['url'] = $this->facebook->getLoginUrl(array('scope' => 'email'));
      $this->redirect_type($data['url']);
    }else{
      $facebook_data = $this->facebook->api('/me?fields=id,name,email,verified');
	  //echo '<pre> $facebook_data=>'; print_r($facebook_data); echo '</pre>';Die();
      $facebook_email = $facebook_data['email'];
      $facebook_id = $facebook_data['id'];
      $facebook_firstname = $facebook_data['name'];

      if(!$this->valid_email($facebook_email)) {
        // exit("<script>alert('ไม่สามารถเข้าสู่ระบบด้วยเฟสบุ๊คได้ กรุณาตรวจสอบไอดีเฟสบุ๊คของท่าน')</script>");
        $facebook_email = $facebook_id . "@FB.com";
      }
      $profile = $this->member_model->get_profile_by_facebookid($facebook_id);
      
      /*
       echo '<pre> $facebook_email=>'; print_r($facebook_email); echo '</pre>';
       echo '<pre> $facebook_id=>'; print_r($facebook_id); echo '</pre>';
       echo '<pre> $facebook_firstname=>'; print_r($facebook_firstname); echo '</pre>';
       echo '<pre> $profile=>'; print_r($profile); echo '</pre>';Die();
      */
      
      
      if($profile) {
        /**/
        
        $check = $this->member_model->check_user_status($profile);
        if(!empty($check['error'])){
          $error='<p>'.$check['error'].'</p>';
          echo "<script>window.opener.set_error('$error'); this.close();</script>"; exit;
        }
        
        /**/
        
        // $user_username = $profile['user_username'];
        // $user_password = $profile['user_password'];// $this->input->post('user_password');
        if($app_id) {
          $app_data = $this->oauth_model->get_appdata_by_appid($app_id);  
          $code = $this->tppy_oauth->generate_code($profile['user_id'], $app_data->app_id, $app_data->app_secret, $this->tppy_oauth->get_user_uuid(),  $scopes, $this->input->ip_address());
          $page_url = $redirect_uri. (strpos($redirect_uri,'?') ? '&' : '?' ) .'code='.$code. (!empty($state) ? '&state='.urlencode($state) : '');
        }
        $this->setMemberCookieSession($profile); // LOGIN
        $this->redirect_type($page_url, $type);
      } else {
        $profile = $this->member_model->get_profile_by_email($facebook_email);
        if($profile) {
          $update = $this->member_model->update(array('acc_user_facebook'=>$facebook_id), $profile['user_id']);  // UPDATE field : acc_user_facebook
          if($app_id) {
            $app_data = $this->oauth_model->get_appdata_by_appid($app_id);  
            $code = $this->tppy_oauth->generate_code($profile['user_id'], $app_data->app_id, $app_data->app_secret, $this->tppy_oauth->get_user_uuid(),  $scopes, $this->input->ip_address());
            $page_url = $redirect_uri. (strpos($redirect_uri,'?') ? '&' : '?' ) .'code='.$code. (!empty($state) ? '&state='.urlencode($state) : '');
          }
          $this->setMemberCookieSession($profile); // LOGIN 
          $this->redirect_type($page_url, $type);
        } else { // register by facebook account
          $data = array(
            'email'=> $facebook_email,
            'id'=> $facebook_id,
            'name'=> $facebook_firstname,
            'type'=>'facebook'
          );
          $encode = strtr($this->encrypt->encode(json_encode($data), $this->member_library->register_key), '+/', '-_');
          $this->redirect_type("/member/register/$encode", $type);
        }
      }
    }
  }
  public function facebookConnect2017(){
	   $code = $this->input->get('code');
	   if($code==Null){
	   	 $code = $this->input->post('code');
	   }
	   $this->load->model('api/tppyfacebook_model');
	   $fblogin2017=$this->tppyfacebook_model->facebookcallback();
	   $status=(int)$fblogin2017['status'];
	   // echo '<pre> $fblogin2017=>'; print_r($fblogin2017); echo '</pre>';
	   // echo '<pre> $status=>'; print_r($status); echo '</pre>';
	   if($status==404){
	   		echo 'Error please reconnect..<hr>';
	   		$this->load->model('api/tppyfacebook_model');
	     	$fblogin2017=$this->tppyfacebook_model->fblogin2017a();
	     	echo '<pre> $fblogin2017=>'; print_r($fblogin2017); echo '</pre>'; 
	     	Die();
	   }elseif($status==200){
		   $data=$fblogin2017['data'];
		   $data_name=$data['name'];
		   $data_first_name=$data['first_name'];
		   $data_gender=$data['gender'];
		   $data_email=$data['email'];
		   $data_locationname=$data['locationname'];
		   $data_birthday=$data['birthday'];
		   $data_fid=$data['fid'];
		   $data_profileid=$data['profileid'];
		   #########
		    $data_fb=array('name' =>$data_name,
						   'first_name' =>$data_first_name,
						   'gender' =>$data_gender,
						   'email' =>$data_email,
						   'locationname' =>$data_locationname,
						   'fid' =>$data_fid,
						   'profileid' =>$data_profileid,
						   'birthday' =>$data_birthday,
						   );
		   echo '<pre> $data_fb=>'; print_r($data_fb); echo '</pre><hr>';
		   echo '<pre> $fblogin2017=>'; print_r($fblogin2017); echo '</pre>';
		   //echo '<pre> $data=>'; print_r($data); echo '</pre>';  
		}else{
			echo'Non case'; Die();  
		}	
	Die();      
  }
  public function facebookConnect2017b() {
   // Pass session data over.
	session_start();
	// Include the required dependencies.
	//require( __DIR__.'/third_party/Facebook/autoload.php' );
    require(APPPATH.'third_party/Facebook/autoload.php');
    // Initialize the Facebook PHP SDK v5.
    $facebook_config=$this->config->config['facebook'];
    $app_id=$facebook_config['api_id'];
    $app_secret=$facebook_config['app_secret'];
    $app_version=$facebook_config['version'];
    $redirect_url=$facebook_config['redirect_url'];
    $redirect_url_login=$facebook_config['redirect_url_login'];
    $app_permissions=$facebook_config['permissions'];
    
	$fb = new Facebook\Facebook([
	  'app_id'                => $app_id,
	  'app_secret'            => $app_secret,
	  'default_graph_version' => $app_version,
	]);
    #echo '<pre> $facebook_config=>'; print_r($facebook_config); echo '</pre>';
    #echo '<pre> $app_permissions=>'; print_r($app_permissions); echo '</pre>';
    #echo '<pre> $fb=>'; print_r($fb); echo '</pre>'; //die();
  

 

echo '<pre> $code=>'; print_r($code); echo '</pre>';
 # Facebook PHP SDK v5: Check Login Status Example
 
// Choose your app context helper
$helper = $fb->getCanvasHelper();
//$helper = $fb->getPageTabHelper();
//$helper = $fb->getJavaScriptHelper();
 
// Grab the signed request entity
$sr = $helper->getSignedRequest();
 
// Get the user ID if signed request exists
$user = $sr ? $sr->getUserId() : null;
 
if ( $user ) {
  try {
 
    // Get the access token
    $accessToken = $helper->getAccessToken();
  } catch( Facebook\Exceptions\FacebookSDKException $e ) {
 
    // There was an error communicating with Graph
    echo $e->getMessage();
    exit;
  }
}
/*	
	$datauploads = array('loginhelper'=>$loginhelper,
		                'helper' => $helper,
		                'sr' => $sr,
		                'user' => $user,
		                'accessToken' => $accessToken,
		                'getMessage' => $getMessage,
		                'fb' => $fb,
		        ); 
		        
    echo '<pre> $datauploads=>'; print_r($datauploads); echo '</pre>'; die();
    */
    $redirect_uri = $this->input->get('page_url', TRUE);
    $type = $this->input->get('type');
    $state = $this->input->get('state');
    $scopes = $this->input->get('scopes', TRUE);
    $app_id = $this->input->get('app_id', TRUE);
    $page_url = $this->input->get('page_url', TRUE);

    $this->load->model('api/public_model');
    $data=$this->load->public_model->facebookcallback();
    echo '<pre> $data=>'; print_r($data); echo '</pre>';Die(); 
    
    
    
    $CI = & get_instance();
    $CI->config->load("facebook", TRUE);
    $config = $CI->config->item('facebook');
    $this->load->library('Facebook', $config);
    $userId = $this->facebook->getUser();
    #####################################
    /*
    echo '<pre> $redirect_uri=>'; print_r($redirect_uri); echo '</pre>';
    echo '<pre> $type=>'; print_r($type); echo '</pre>';
    echo '<pre> $state=>'; print_r($state); echo '</pre>';
    echo '<pre> $scopes=>'; print_r($scopes); echo '</pre>';
    echo '<pre> $app_id=>'; print_r($app_id); echo '</pre>';
    echo '<pre> $page_url=>'; print_r($page_url); echo '</pre>';
    */
    
    $facebook_data = $this->facebook->api('/me?fields=id,name,email,verified');
      $facebook_email = $facebook_data['email'];
      $facebook_id = $facebook_data['id'];
      $facebook_firstname = $facebook_data['name'];
      $profile = $this->member_model->get_profile_by_facebookid($facebook_id);
    
     
    echo '<pre> $config=>'; print_r($config); echo '</pre>';  
	echo '<pre> $facebook_data=>'; print_r($facebook_data); echo '</pre>'; 
    echo '<pre> $userId=>'; print_r($userId); echo '</pre>';
    echo '<pre> $profile=>'; print_r($profile); echo '</pre>';
    Die();
    
    
    
    #####################################
    if ($userId == 0) {
      $data['url'] = $this->facebook->getLoginUrl(array('scope' => 'email'));
      $this->redirect_type($data['url']);
    } else {
      $facebook_data = $this->facebook->api('/me?fields=id,name,email,verified');
	  echo '<pre> $facebook_data=>'; print_r($facebook_data); echo '</pre>';Die();
      $facebook_email = $facebook_data['email'];
      $facebook_id = $facebook_data['id'];
      $facebook_firstname = $facebook_data['name'];

      if(!$this->valid_email($facebook_email)) {
        // exit("<script>alert('ไม่สามารถเข้าสู่ระบบด้วยเฟสบุ๊คได้ กรุณาตรวจสอบไอดีเฟสบุ๊คของท่าน')</script>");
        $facebook_email = $facebook_id . "@FB.com";
      }
      $profile = $this->member_model->get_profile_by_facebookid($facebook_id);
      
      /*
       echo '<pre> $facebook_email=>'; print_r($facebook_email); echo '</pre>';
       echo '<pre> $facebook_id=>'; print_r($facebook_id); echo '</pre>';
       echo '<pre> $facebook_firstname=>'; print_r($facebook_firstname); echo '</pre>';
       echo '<pre> $profile=>'; print_r($profile); echo '</pre>';Die();
      */
      
      
      if($profile) {
        /**/
        
        $check = $this->member_model->check_user_status($profile);
        if(!empty($check['error'])){
          $error='<p>'.$check['error'].'</p>';
          echo "<script>window.opener.set_error('$error'); this.close();</script>"; exit;
        }
        
        /**/
        
        // $user_username = $profile['user_username'];
        // $user_password = $profile['user_password'];// $this->input->post('user_password');
        if($app_id) {
          $app_data = $this->oauth_model->get_appdata_by_appid($app_id);  
          $code = $this->tppy_oauth->generate_code($profile['user_id'], $app_data->app_id, $app_data->app_secret, $this->tppy_oauth->get_user_uuid(),  $scopes, $this->input->ip_address());
          $page_url = $redirect_uri. (strpos($redirect_uri,'?') ? '&' : '?' ) .'code='.$code. (!empty($state) ? '&state='.urlencode($state) : '');
        }
        $this->setMemberCookieSession($profile); // LOGIN
        $this->redirect_type($page_url, $type);
      } else {
        $profile = $this->member_model->get_profile_by_email($facebook_email);
        if($profile) {
          $update = $this->member_model->update(array('acc_user_facebook'=>$facebook_id), $profile['user_id']);  // UPDATE field : acc_user_facebook
          if($app_id) {
            $app_data = $this->oauth_model->get_appdata_by_appid($app_id);  
            $code = $this->tppy_oauth->generate_code($profile['user_id'], $app_data->app_id, $app_data->app_secret, $this->tppy_oauth->get_user_uuid(),  $scopes, $this->input->ip_address());
            $page_url = $redirect_uri. (strpos($redirect_uri,'?') ? '&' : '?' ) .'code='.$code. (!empty($state) ? '&state='.urlencode($state) : '');
          }
          $this->setMemberCookieSession($profile); // LOGIN 
          $this->redirect_type($page_url, $type);
        } else { // register by facebook account
          $data = array(
            'email'=> $facebook_email,
            'id'=> $facebook_id,
            'name'=> $facebook_firstname,
            'type'=>'facebook'
          );
          $encode = strtr($this->encrypt->encode(json_encode($data), $this->member_library->register_key), '+/', '-_');
          $this->redirect_type("/member/register/$encode", $type);
        }
      }
    }
  }
  public function fblogin2017(){
  	 $this->load->model('api/tppyfacebook_model');
     $fblogin2017=$this->tppyfacebook_model->fblogin2017();
     echo '<pre> $fblogin2017=>'; print_r($fblogin2017); echo '</pre>';
     $link=$fblogin2017['link'];
     echo $link;
     
	     
}
  
  
  function valid_email($str) {
      return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
  }
  public function googleConnect() {    
    $state = $this->input->get('state');
    $code = $this->input->get('code', TRUE);
    $type = $this->input->get('type');
    
    if($code){
       $response = $this->post_return_response('https://accounts.google.com/o/oauth2/token', array(
          'code' => $code,
          'redirect_uri' => 'http://www.trueplookpanya.com/member/googleConnect?type=popup',
          'client_id' => '783425256556-01nddfjoj72eqvd9tise3e1l8nv9tjdf.apps.googleusercontent.com',
          'client_secret' => 'hJg1H9GoYIa1MGVFnCUU5pRl',
          'grant_type' => 'authorization_code',
       ));
       $access_token_array =  json_decode(trim($response), true);
       
       $access_token =  $access_token_array['access_token'];
       if($access_token) {
        if($state) {
          $state_arr = array();
          parse_str ($state, $state_arr);
          //print_r($state_arr);
          
          $scopes = isset($state_arr['scopes']) ? $state_arr['scopes'] : '';
          $app_id = isset($state_arr['app_id']) ? $state_arr['app_id'] : null ;
          $redirect_uri = isset($state_arr['page_url']) ? $state_arr['page_url'] : '/';
          $state = isset($state_arr['state']) ? $state_arr['state'] : '';
        }
        // GET PROFILE 
        $url = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=".$access_token;
        $json =  trim(file_get_contents($url));
        
        $google_data = json_decode($json, true);
        
        $google_email = $google_data['email'];
        $google_id = $google_data['id'];
        $google_firstname = $google_data['name'];

        $profile = $this->member_model->get_profile_by_googleid($google_id);
        if($profile){
           
          $check = $this->member_model->check_user_status($profile);
          if(!empty($check['error'])){
            $error='<p>'.$check['error'].'</p>';
            echo "<script>window.opener.set_error('$error'); this.close();</script>"; exit;
          }
          
          if($app_id) {
            $app_data = $this->oauth_model->get_appdata_by_appid($app_id);  
            $code = $this->tppy_oauth->generate_code($profile['user_id'], $app_data->app_id, $app_data->app_secret, $this->tppy_oauth->get_user_uuid(),  $scopes, $this->input->ip_address());
            $page_url = $redirect_uri. (strpos($redirect_uri,'?') ? '&' : '?' ) .'code='.$code. (!empty($state) ? '&state='.urlencode($state) : '');
          }
          $this->setMemberCookieSession($profile); // LOGIN 
          
          $this->redirect_type($redirect_uri);
        } else {
          $profile = $this->member_model->get_profile_by_email($google_email);
          if($profile){
            $update = $this->member_model->update(array('acc_user_google'=>$google_id), $profile['user_id']);  // UPDATE field : acc_user_google
            if($app_id) {
              $app_data = $this->oauth_model->get_appdata_by_appid($app_id);  
              $code = $this->tppy_oauth->generate_code($profile['user_id'], $app_data->app_id, $app_data->app_secret, $this->tppy_oauth->get_user_uuid(),  $scopes, $this->input->ip_address());
              $page_url = $redirect_uri. (strpos($redirect_uri,'?') ? '&' : '?' ) .'code='.$code. (!empty($state) ? '&state='.urlencode($state) : '');
            }
            $this->setMemberCookieSession($profile); // LOGIN 
            
            $this->redirect_type($page_url, $type);
          } else {
            // register by google account
            $data = array(
              'email'=> $google_email,
              'id'=> $google_id,
              'name'=> $google_firstname,
              'type'=>'google'
            );
            $encode = strtr($this->encrypt->encode(json_encode($data), $this->member_library->register_key), '+/', '-_');
            $this->redirect_type("/member/register/$encode", $type);
          }
        }
      } else {
        $this->redirect_type($redirect_uri, $type);
      }
       
    }
  }
  public function redirect_type($redirect_uri=null, $type="page") {
    if(empty($redirect_uri)){
      $redirect_uri = '/home';
    }
    if($type=='popup'){
      echo "<script>window.opener.document.location.href =\"$redirect_uri\";this.close();</script>";
    }else{
      redirect($redirect_uri);
    }
  }
  public function post_return_response($url, $data) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
      $result = curl_exec($ch);
      curl_close($ch);
      return $result;
  }   
  /* REGISTER */ 
  // private $register_key="1e300bc3368121ffd251a8f4f10ee407";
  private $pattern_username = "/^[a-zA-Z][a-zA-Z0-9_-]{2,15}$/";
  private $pattern_email = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";
  public function register($encode=null) {
    $this->load->helper(array('form', 'url'));

    $this->form_validation->set_rules('user_username', 'ชื่อสมาชิก', 'trim|required|xss_clean|strtolower|callback_checkAvalaibleUsername');
    $this->form_validation->set_rules('user_password', 'รหัสผ่าน', 'trim|required|xss_clean');
    $this->form_validation->set_rules('user_password_conf', 'ยืนยันรหัสผ่าน', 'trim|required|xss_clean|matches[user_password]');
    $this->form_validation->set_rules('psn_display_name', 'ชื่อที่แสดง', 'trim|required|xss_clean');
    $this->form_validation->set_rules('user_email', 'อีเมล', 'trim|required|xss_clean|strtolower|callback_checkAvalaibleEmail');
    $this->form_validation->set_rules('g-recaptcha-response', 'ยืนยันตัวตน', 'trim|required|xss_clean|callback_checkReCaptcha');
    $this->form_validation->set_rules('social_type', 'social_type', 'trim');
    $this->form_validation->set_rules('social_id', 'social_id', 'trim');

    if($this->input->post()){

      if ($this->form_validation->run()) {
        // SAVE
        $insert_data = array(
          'user_username'=> $this->input->post('user_username'),
          'user_password'=> md5($this->input->post('user_password')),
          'psn_display_name'=> $this->input->post('psn_display_name'),
          'user_email'=> $this->input->post('user_email'),
          'user_create_date'=> date('Y-m-d H:i:s'),
          'user_create_ip'=> ip_client(),
          'user_update_date'=>date('Y-m-d H:i:s'),
          'user_group'=> 'MEM',
          'user_password_tmp'=>'',
          'user_status'=>0,
        );
   
        if($this->input->post('social_type') == 'google'){
          $insert_data['acc_user_google'] = $this->input->post('social_id');
        }else if($this->input->post('social_type') == 'facebook'){
          $insert_data['acc_user_facebook'] = $this->input->post('social_id');
        }else if($this->input->post('social_type') == 'twitter'){
          $insert_data['acc_user_twitter'] = $this->input->post('social_id');
        }
 
        $user_id = $this->member_model->insert($insert_data);
        $user_email = $this->input->post('user_email');
        $active_data = array(
          'insert_id' => $user_id,
          'user_email'=>$user_email,//$this->input->post('user_email'),
        );
        
        $this->member_library->sendActivateEmail($user_id, $user_email, $this->input->post('user_username'), $this->input->post('user_password'));

        $this->template->view('form_register_success', $insert_data);
      } else {
        $this->template->view('/member/form_register');
      }
    } else {
      if($encode){
        // REGISTER โดย GG+ && FB
        $data = json_decode($this->encrypt->decode(strtr($encode, '-_', '+/'), $this->member_library->register_key), TRUE);
        $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        $username = $data['email'];
        $user_email = $data['email'];
        $insert_data = array(
          'user_username'=> $username,
          'user_password'=> md5($password),
          'psn_display_name'=> $data['name'],
          'user_email'=> $data['email'],
          'user_create_date'=> date('Y-m-d H:i:s'),
          'user_create_date'=> date('Y-m-d H:i:s'),
          'user_create_ip'=> ip_client(),
          'user_group'=> 'MEM',
          'user_password_tmp'=>'',
          'user_status'=>1,
          'user_active_date'=>date('Y-m-d H:i:s'),
        );
            
        if($data['type'] == 'facebook') {
          $insert_data['acc_user_facebook'] =$data['id'];
          $insert_data['psn_display_image']="http://graph.facebook.com/".$data['id']."/picture?type=large";
          $user_id = $this->member_model->insert($insert_data);
          $profile = $this->member_model->get_profile_by_facebookid($data['id']);
          $this->member_library->sendActivateEmail($user_id, $user_email, $username, $password);
          $this->setMemberCookieSession($profile); // LOGIN 
          $this->redirect_type('/');
        }else if($data['type'] == 'google') {
          $insert_data['acc_user_google'] =$data['id'];
          
          $user_id = $this->member_model->insert($insert_data);
          $profile = $this->member_model->get_profile_by_googleid($data['id']);
          $this->member_library->sendActivateEmail($user_id, $user_email, $username, $password);
          $this->setMemberCookieSession($profile); // LOGIN 
          $this->redirect_type('/');
        }else {
          $this->template->view('/member/form_register');
        }
      }else{
        $this->template->view('/member/form_register');
      }
    }
  }
  public function checkAvalaibleUsername($user_username, $output=null) {
    $result = FALSE;
    if(preg_match($this->pattern_username, $user_username)) {
      // ระบบใหม่
      $data = $this->member_model->checkAvalaibleUsername($user_username);
      if(!$data) {
        $result = TRUE;
      }
    }
    if($output=='JSON'){
      if(!preg_match($this->pattern_username, $user_username)){
        echo 'invalid';
      }else{
         echo $result ? 'ok' : 'no';
      }
    }else{
      return $result;
    }
  }
  public function checkAvalaibleEmail($user_email, $output=null){
    $result = FALSE;
    if(preg_match($this->pattern_email, $user_email)) {
      $data = $this->member_model->checkAvalaibleEmail($user_email);
      if(!$data) {
        $result = TRUE;
      }
    }
    
    if($output=='JSON'){
      if(!preg_match($this->pattern_email, $user_email)){
        echo 'invalid';
      }else{
         echo $result ? 'ok' : 'no';
      }
    }else{
      return $result;
    }
  }
  public function checkReCaptcha($recaptcha_response) {
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query(array(
              'secret' => '6LfhigkTAAAAAL9JB1FVAHc9hu0lpb89j8ck_42J',
              'response' => $recaptcha_response 
            ))
        )
    );

    $context  = stream_context_create($opts);
    $result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $array_result = json_decode($result);
    return $array_result->success;
  } 
  public function activate($activate_code=null) {
    
    $this->form_validation->set_rules('user_email', 'อีเมล', 'trim|required|xss_clean|strtolower');
    if($this->input->post()){
      if($this->form_validation->run()){
        $user_email=$this->input->post('user_email');
        $sql="SELECT * FROM users_account WHERE user_email=%user_email% AND user_status=0";
        $query=$this->db->query($sql, array('%user_email%'=>$user_email));
        if($query->num_rows() > 0){
          $profile=$query->row();
          $this->member_library->sendActivateEmail($profile->user_id, $profile->user_email);
          $data['message'] = 'ระบบได้ทำการส่งอีเมลล์ไปให้ท่านแล้วค่ะ กรุณาทำการยืนยันใหม่อีกครั้งค่ะ';
        }
        $data['message'] = 'ท่านได้ทำการยืนยันตนไปแล้วค่ะ';
      }else{
        $data['form'] = true;
        $data['error'] = 'เกิดข้อผิดพลาด รูปแบบของอีเมลล์ ไม่ถูกต้อง กรุณาลองใหม่อีกครั้งค่ะ';
        $data['message'] = '<b style="color:red">เกิดข้อผิดพลาดในการ Activate Email</b> <br>กรุณากรอก Email ของท่านอีกครั้ง ระบบจะส่ง Email <br>กลับไปหาท่านเพื่อให้ยืนยันตน อีกครั้งหนึ่ง ขออภัยในความไม่สะดวกค่ะ';
      }
      $this->template->view('/member/form_activate', $data);
      $user_email=$this->input->post('user_email');
    }
    
    if($activate_code) {
      $active_data = json_decode($this->encrypt->decode(strtr($activate_code, '-_', '+/'), $this->member_library->register_key), TRUE);
      // _vd($active_data); die;
      $data = array();
      if($active_data) {
        $user_id = $active_data['user_id'];
        $user_email =  $active_data['user_email'];

        $profile = $this->member_model->get_profile_by_userid($user_id);
        if($profile){
          if($profile['user_active_date']==null && $profile['user_active_date']==0) {
            if(!$this->member_model->checkAvalaibleEmail($user_email))
            {
              $update_data = array('user_active_date'=>date('Y-m-d H:i:s'), 'user_update_date'=>date('Y-m-d H:i:s'), 'user_status'=>1, 'user_email'=>$user_email);

              $copy = $this->member_model->insert_into($user_id);
              $update = $this->member_model->update($update_data, $user_id);
              // LOGIN
              $this->setMemberCookieSession($profile);  
              $data['message'] = 'ทำการยืนยันสมาชิกแล้วค่ะ';
              $data['profile'] = $profile;
               
            } else {
              $data['message'] = 'อีเมลล์นี้ถูกทำการ active โดยไอดีอื่นแล้วค่ะ กรุณาเปลี่ยนอีเมลล์แล้วทำการอัพเดตใหม่ค่ะ';
              $data['form'] = true;
              
              if($this->input->post('user_email')) {
                $user_email = $this->input->post('user_email');
                $this->member_library->sendActivateEmail($user_id, $user_email);
              }
            }
          } else {
            $data['message'] = 'ท่านเคยทำการ active email แล้่วค่ะ';
          }
        } else {
          $data['message'] = 'Invalid activate code ';
        }
      } else {
        $data['form'] = true;
        $data['message'] = '<b style="color:red">เกิดข้อผิดพลาดในการ Activate Email</b> <br>กรุณากรอก Email ของท่านอีกครั้ง ระบบจะส่ง Email <br>กลับไปหาท่านเพื่อให้ activate  อีกครั้งหนึ่ง ขออภัยในความไม่สะดวกค่ะ';
      }
    }else{
      $data['form'] = true;
        $data['message'] = '<br>กรุณากรอก Email ของท่านอีกครั้ง ระบบจะส่ง Email <br>กลับไปหาท่านเพื่อให้ activate  อีกครั้งหนึ่ง ขออภัยในความไม่สะดวกค่ะ';
    }
    $this->template->view('/member/form_activate', $data);
  }  
  /* PROFILE */
  public function profile() {
    $user_id = $this->tppy_member->get_member_profile()->user_id;
    if($user_id > 0){
      if($this->input->post()) {
          $data = $this->member_model->get_profile_by_userid_noblog($user_id);

          $data['psn_display_name'] = $this->input->post('psn_display_name'); 
          $data['psn_sex'] = $this->input->post('psn_sex');
          $data['psn_firstname'] = $this->input->post('psn_firstname');
          $data['psn_lastname'] = $this->input->post('psn_lastname');
          $data['psn_id_number'] = $this->input->post('psn_id_number');
          $data['psn_birthdate'] = $this->input->post('psn_birthdate');//!empty($this->input->post('psn_birthdate')) ? $this->input->post('psn_birthdate') : '0000-00-00 00:00:00';
          $data['psn_tel'] = $this->input->post('psn_tel');
          $data['psn_address'] = $this->input->post('psn_address');
          $data['psn_province'] = $this->input->post('psn_province');
          $data['psn_postcode'] = $this->input->post('psn_postcode');
          $data['job_name'] = $this->input->post('job_name');
          $data['job_edu_degree'] = $this->input->post('job_edu_degree');
          $data['job_edu_name'] = $this->input->post('job_edu_name');
          $data['job_edu_level'] = $this->input->post('job_edu_level');
          $data['user_update_date'] = date('Y-m-d H:i:s');
          
          //var_dump($_FILES['psn_display_image_file']['name']);

         if($_FILES['psn_display_image_file']['name']) {
            $upload_dir = $this->tppy_member->get_member_profile()->member_folder_path . DIRECTORY_SEPARATOR . 'profile';
            if ($_FILES['psn_display_image_file']['name']) {
            $upload_result = $this->do_upload('psn_display_image_file', $this->tppy_member->get_member_profile()->member_folder_url . '/profile', $upload_dir, 'profile' , 'gif|jpg|png|jpeg', 2097152);
              if (empty($upload_result['error'])) {
                $update_image_data['image'] = $upload_result['url'];
                $data['psn_display_image'] = $update_image_data['image'] ;
              }
            }
          }

          if(!empty($data['psn_sex']) && !empty($data['psn_display_name'])){
            //var_dump($data); die;
            $update_data = $this->member_model->update($data , $user_id);
            if($update_data > 0 ) {
              $this->tppymemcached->delete("memberprofile_" . $user_id);
              
              $profile = $this->member_model->get_profile_by_userid($user_id, TRUE);
              $this->setMemberCookieSession($profile);
            }
            echo "<script>alert('แก้ไขข้อมูลแล้วค่ะ'); window.location.href='".base_url('/member/profile/'.$user_id)."'</script>";
          }else{
            $this->template->view('/member/form_profile_edit', $data);
          }
      } else {
          $this->tppymemcached->delete("memberprofile_" . $user_id);
          $data = $this->member_model->get_profile_by_userid($user_id, TRUE);
          $this->template->view('/member/form_profile_edit', $data);
      }
    }else{
      $this->redirect_type('/member/login');
    }
  }
  public function do_upload($file, $url, $path, $filename, $allow_type, $max_size) {
  $config['upload_path'] = $path;
  $config['allowed_types'] = $allow_type;
  $config['max_size'] = $max_size;
  $config['overwrite'] = TRUE;
  $config['file_name'] = $filename;
  $this->load->library('upload');
  $this->upload->initialize($config);
  if (!is_dir($path)) {
    mkdir($path, 0777, TRUE);
    chmod($path, 0777);
  }

  $data = array();
  if ($this->upload->do_upload($file)) {
    $data = array(
        'error' => 0,
        'url' => $url . '/' . $this->upload->data()['file_name']
    );
  } else {
    $data = array(
        'error' => $this->upload->display_errors(),
        'url' => ''
    );
  }
  return $data;
}
  /*CHANGE PASS*/
  public function change() {
     $user_id = $this->tppy_member->get_member_profile()->user_id;
     $data = array();
     if($user_id > 0){
        $this->form_validation->set_rules('old_user_password', 'รหัสผ่าน', 'trim|required|xss_clean');
        $this->form_validation->set_rules('user_password', 'รหัสผ่าน', 'trim|required|xss_clean');
        $this->form_validation->set_rules('user_password_conf', 'ยืนยันรหัสผ่าน', 'trim|required|xss_clean|matches[user_password]');
        $this->form_validation->set_rules('g-recaptcha-response', 'ยืนยันตัวตน', 'trim|required|xss_clean|callback_checkReCaptcha');
        
        if ($this->form_validation->run()) {
        // SAVE
         $old_user_password = $this->input->post('old_user_password');
         $user_password = $this->input->post('user_password');
         $profile=$this->member_model->get_profile_by_userid($user_id);
         
          if($profile['user_password'] == MD5($old_user_password)){
             $update_result = $this->member_model->update(array('user_password'=>MD5($user_password)) , $user_id);
             // if($update_result > 0) {
               $this->member_library->sendChangePassEmail($profile['user_username'], $user_password, $profile['user_email'], $profile['psn_display_name']);
              // CLEAR MEMCACHE
              $this->tppy_oauth->force_clear_token_by_userid($user_id);
               
              $data['error'] = 'เปลี่ยนรหัสผ่านแล้วค่ะ';
             // }
          }else{
            $data['error'] = 'รหัสผ่านผิดกรุณาตรวจสอบรหัสผ่านเดิมค่ะ';
          }
        }
        $this->template->view('/member/form_changepassword', $data);
     }else{
      $this->redirect_type('/member/login');
    }
  }
  /*RESET PASS*/
  public function forgot() {
    //$this->form_validation->set_rules('user_username', 'ชื่อผู้ใช้', 'trim|required|xss_clean');
    $this->form_validation->set_rules('user_email', 'อีเมล', 'trim|required|xss_clean');

    if ($this->form_validation->run()) {
    // SAVE
     $user_email = $this->input->post('user_email');
     $profile = $this->member_model->get_profile_by_email($user_email);
      if($profile['user_email'] == $user_email){
         $this->member_library->sendResetPassword($profile['user_username'], $profile['user_id'], $profile['user_email'], $profile['user_update_date']); 
         $data['success'] = 'ระบบได้ทำการส่ง Email ไปให้ท่านแล้ว กรุณาตรวจสอบที่ อีเมล ของท่าน';
         $this->template->view('/member/form_forgotpassword', $data);
      }else{
        $data['error'] = 'ไม่พบอีเมลล์นี้ในระบบ กรุณาตรอจสอบค่ะ';
        $this->template->view('/member/form_forgotpassword', $data);
      }
    }else{
      $this->template->view('/member/form_forgotpassword');
    }
  }
  public function reset($user_id, $code) {
     $data = json_decode($this->encrypt->decode(strtr($code, '-_', '+/'), $this->member_library->register_key), TRUE);
     $d_user_id = $data['user_id'];
     $d_user_email = $data['user_email'];
     $d_user_update_date = $data['user_update_date'];
     // _vd($data);
     if($d_user_id == $user_id) {
       $profile = $this->member_model->get_profile_by_userid($user_id, TRUE);
       if($profile) {
         if($profile['user_update_date'] == $d_user_update_date){
           $this->form_validation->set_rules('user_password', 'รหัสผ่าน', 'trim|required|xss_clean');
          $this->form_validation->set_rules('user_password_conf', 'ยืนยันรหัสผ่าน', 'trim|required|xss_clean|matches[user_password]');
           if($this->input->post()){
             $user_password = $this->input->post('user_password');
             $update = $this->member_model->update(array('user_password'=>md5($user_password), 'user_update_date'=>date('Y-m-d H:i:s')), $user_id);
             if($update){
               $this->member_library->sendChangePassEmail($profile['user_username'], $user_password, $profile['user_email'], $profile['psn_display_name']);
               $data['error'] = 'คุณได้ทำการเปลี่ยนรหัสผ่านใหม่แล้วค่ะ กรุณาล๊อกอินเข้าระบบด้วยรหัสผ่านใหม่ค่ะ';
               $this->template->view('/member/form_resetpassword', $data);
             }else{
               $data['error'] = 'กรุณาลองใหม่ค่ะ';
               $this->template->view('/member/form_resetpassword', $data);
             }
           }else{
             $this->template->view('/member/form_resetpassword');
           }
         }else{
            $data['error'] = '[003] โค๊ดในการตั้งรหัสผ่านผิดค่ะกรุณาตรวจสอบค่ะ';//. $profile['user_update_date'] . '::' . $d_user_update_date;
            $this->template->view('/member/form_resetpassword', $data);
         }
       } else {
          $data['error'] = '[001] โค๊ดในการตั้งรหัสผ่านผิดค่ะกรุณาตรวจสอบค่ะ';
          $this->template->view('/member/form_resetpassword', $data);
       }
     }else{
       $data['error'] = '[002] โค๊ดในการตั้งรหัสผ่านผิดค่ะกรุณาตรวจสอบค่ะ';
       $this->template->view('/member/form_resetpassword', $data);
     }
     
     
  }
  /* MEMBER GATEWAY */
  public function gateway(){
    $token = $this->input->get('token', false);
    $tg = $this->input->get('tg', false);
    
    $token_data = $this->tppy_oauth->parse_token($token);
    $checktoken = $this->tppy_oauth->check_token($token_data, 'profile');
    if($checktoken['status']) {
      $profile = $this->member_model->get_profile_by_userid($token_data->user_id);
      $this->setMemberCookieSession($profile); // LOGIN 
      $url='';
      switch($tg) {
        case 'profile' : $url='/member/profile'; break;
        case 'change' : $url='/member/change'; break;
      }
      redirect($url);
    }else{
      redirect('/');
    }
  }
  /* GROUP CONTRACT */
  public function group_contract($group_id=0) { 
    $this->dbEdit = $this->load->database('edit', TRUE);
    $user_id = $this->tppy_member->get_member_profile()->user_id;
   
    if($user_id >0) {
      if($this->input->post()) {
        if($this->input->post('new_group_name')){
          $new_group_name=$this->input->post('new_group_name');
           $this->dbEdit->insert('users_contract_group', array('user_id'=>$user_id , 'group_name'=>$new_group_name));
           $group_id=$this->dbEdit->insert_id();
           redirect('/member/group-contract/'.$group_id);
        }
        
        if($this->input->post('edit_group_name')){
          $edit_group_name=$this->input->post('edit_group_name');
           $this->dbEdit->update('users_contract_group', array('group_name'=>$edit_group_name), array('user_id'=>$user_id , 'group_id'=>$group_id));
        }
        
        if($this->input->post('new_user')){
          $users=$this->input->post('new_user');
          foreach(explode(',', $users) as $v) {
            $this->dbEdit->insert('users_contract_map', array('contract_user_id'=>$v, 'group_id'=>$group_id));
          }
        }

        if($this->input->post('DEL')) {
          $users=$this->input->post('users');
          foreach($users as $v) {
            $this->dbEdit->delete('users_contract_map', array('contract_user_id'=>$v, 'group_id'=>$group_id));
          }
        }
      }

      if($this->input->get('DELGROUP')) {
        $this->dbEdit->delete('users_contract_group', array('user_id'=>$user_id, 'group_id'=>$group_id));
        $this->dbEdit->delete('users_contract_map', array('group_id'=>$group_id));
        redirect('/member/member/group-contract');
      }
      
      if($group_id>0) {
        $sql ="SELECT * FROM users_contract_group ug WHERE ug.group_id=%group_id% AND ug.user_id=%user_id%";
        $query=$this->db->query($sql, array(
          '%group_id%'=>$group_id,
          '%user_id%'=>$user_id,
        ));
        $group_data = $query->row_array(); 
        if($group_data){
          $data['group']=$group_data;
          $sql = "SELECT ua.user_id
          , COALESCE(uc.contract_user_nick, NULLIF(TRIM(CONCAT(ua.psn_firstname,' ', ua.psn_lastname)),''), ua.psn_display_name, ua.user_username) contract_user_nick
          , ua.psn_display_image
          , ua.user_email
          FROM users_contract_map um
          INNER JOIN users_contract_group ug ON ug.group_id=um.group_id 
          INNER JOIN users_account ua  ON ua.user_id=um.contract_user_id 
          LEFT JOIN users_contract uc ON uc.user_id=um.contract_user_id
          WHERE ug.group_id=%group_id% AND ug.user_id=%user_id%";
          $query=$this->db->query($sql, array(
            '%group_id%'=>$group_id,
            '%user_id%'=>$user_id,
          ));
          // echo $this->db->last_query(); die;
          $data['list']=$query->result_array();    
        }else{
          redirect('/member/group-contract/');
        }
      } else {
        $data['group']=array(
          'group_id'=>0,
          'group_name'=>'ผู้ติดต่อทั้งหมด',
        );
        $sql = "SELECT DISTINCT(ua.user_id)
        , COALESCE(uc.contract_user_nick, NULLIF(TRIM(CONCAT(ua.psn_firstname,' ', ua.psn_lastname)),''), ua.psn_display_name, ua.user_username) contract_user_nick
        , ua.psn_display_image
        , ua.user_email
        FROM users_contract_map um
        INNER JOIN users_contract_group ug ON ug.group_id=um.group_id 
        INNER JOIN users_account ua  ON ua.user_id=um.contract_user_id 
        LEFT JOIN users_contract uc ON uc.user_id=um.contract_user_id
        WHERE ug.user_id=%user_id%";
        $query=$this->db->query($sql, array(
          '%user_id%'=>$user_id,
        ));
        $data['list']=$query->result_array();    
      }
      
      $sql ="SELECT ug.*, COUNT(um.contract_user_id) count_member FROM users_contract_group ug 
      LEFT JOIN users_contract_map um ON ug.group_id=um.group_id
      WHERE ug.user_id=%user_id%
      GROUP BY ug.group_id
      ";
      $query=$this->db->query($sql, array(
        '%group_id%'=>$group_id,
        '%user_id%'=>$user_id,
      ));
      // echo $this->db->last_query(); die;
      $data['group_list']=$query->result_array(); 
        
      $this->template->view('/member/form_group_contract', $data);
    }else{
      redirect('login');
    }
  }
  public function group_contract_search($group_id=0) {
    $user_email = $this->input->get('q');
    $user_id = $this->tppy_member->get_member_profile()->user_id;
    $sql = "SELECT ua.user_id
    , CONCAT(COALESCE(uc.contract_user_nick, NULLIF(TRIM(CONCAT(ua.psn_firstname,' ', ua.psn_lastname)),''), ua.psn_display_name, ua.user_username) , ' [',user_email,']') contract_user_nick
    , user_email
    FROM users_account ua 
    LEFT JOIN users_contract uc ON uc.user_id=ua.user_id
    WHERE 1=1
    AND (
    ua.user_email LIKE %user_email% 
    OR ua.psn_display_name LIKE %user_email% 
    OR ua.psn_firstname LIKE %user_email% 
    OR ua.psn_lastname LIKE %user_email%
    )
    AND user_status=1
    ORDER BY user_id LIMIT 30";
    $query=$this->db->query($sql, array(
      '%user_id%'=>$user_id,
      '%user_email%'=>"%$user_email%",
    ));
    // echo $this->db->last_query();
    $list=$query->result_array();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($list);
    
    //$this->view->view('/member/form_group_contract', $data);
  }
  /* KEEP ALIVE SESSION */
  public function keep_alive(){
  /*
    $user_id = $this->tppy_member->get_member_profile()->user_id;

    if($user_id > 0){
      $profile = $this->member_model->get_profile_by_userid($user_id);
      $this->setMemberCookieSession($profile);
    }
    */
  }
}