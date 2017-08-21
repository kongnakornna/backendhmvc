<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Tools2 extends Public_Controller {
   public function addViewVal($content_id=0, $table_id='ams_news_directapply'){
    // $this->load->library('tppy_utils');
    /* 
      1 => MUL_CONTENT
      2 => MUL_SOURCE
      3 => CMS
      4 => TV_PROGRAM 
      5 => TV_PROGRAM_EPISODE 
    */
    echo $this->tppy_utils->ViewNumberSet($content_id, $table_id, 1);
    
    // echo $this->tppy_utils->addViewVal($content_id, $table_id);
  }
   public function __construct() {
      parent::__construct();
      ob_clean();
		header('Content-Type: text/html; charset=utf-8');
      // $this->template->add_stylesheet('assets/colorbox/theme3/colorbox.css');
      // $this->template->add_javascript('assets/colorbox/jquery.colorbox-min.js');
      // $this->template->add_stylesheet('assets/tppy_v1/bower_components/jquery-ui/themes/base/jquery-ui.min.css');
      // $this->template->add_stylesheet('assets/tppy_v1/bower_components/jquery-ui/themes/base/jquery-ui.structure.min.css');
      // $this->template->add_stylesheet('assets/tppy_v1/bower_components/jquery-ui/themes/base/jquery-ui.theme.css');
      // $this->template->add_stylesheet('assets/jquery-select/css/bootstrap-select.min.css');
      // $this->template->add_stylesheet('assets/css/bootstrap/css/bootstrap.css');
      // $this->template->add_javascript('assets/css/bootstrap/js/bootstrap.min.js');

      $this->load->library('TPPY_Oauth');
   }
   public function test_bootstrap(){
    $this->template->set_theme('trueplookpanya', 'default', 'themes');
    $this->template->view('test_bootstrap');
   }
   public function memcached_stat(){
     error_reporting (E_ALL);
     echo gethostname();
     $this->load->library('Tppymemcached');
     // _vd($this->Tppymemcached->add('test'));
    if(!empty($_GET['flush'])){
       $this->tppymemcached->flush();
       echo '<br/>FLUSH COMPLETE<br/>';
     }
     
     _pr($this->tppymemcached->getstats());

   }
   public function index(){
      $data['list']=[
         'AUTHORIZE'=>'-',
         'Authorize'=> '/tools2/test-api',
         'SERVER TOOLS'=>'-',
         'Display Header'=> '/tools2/display-header',
         'Text Count'=> '/tools2/txtcount',
		 'Test fonts'=> 'tools2/fonts',
         'Byte Count'=> 'https://mothereff.in/byte-counter',
         'PHP INFO'=> '/tools2/info',
         'Hasher'=> '/tools2/hasher',
         'STREAM'=>'-',
         'Stream CH ทรูปลูกปัญญา HD'=> '/tools/stream-html?ch=139',
         'Stream CH สามเณรปูกปัญญา HD'=> '/tools/stream-html?ch=333',
         'Stream API'=> '/tools2/stream-api',
         'member'=> '/tools2/member',
         
      ];

      $this->template->view('index', $data);
   }
   public function partapi(){
      $url='http://mobilepush.truelife.com/v3/rest/?format=json';
      $data=array();
      $options = array(
         CURLOPT_RETURNTRANSFER => true,   // return web page
         CURLOPT_ENCODING       => "utf-8",     // handle compressed
         CURLOPT_CONNECTTIMEOUT => 5,    // time-out on connect
         CURLOPT_TIMEOUT        => 5,    // time-out on response
      );

      $ch = curl_init($url);

      curl_setopt_array($ch, $options);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

      $content  = curl_exec($ch);

      curl_close($ch);

      var_dump($content);
   }
   public function display_header() {
      foreach($_SERVER as $header=>$val)
      {
         echo '<b style="color:red">'.$header .' : </b> '. $val .'</br>';
      }
   }
   public function txtcount(){
      $this->load->view('txtcount');
   }
   public function hasher() {
      $this->load->view('hasher');
   }
   public function info(){
      $this->load->view('info');
   }
   public function test_api() {
      $this->load->model('api/crypt_model');
      $data=array();
      if($this->input->post()){
		$post=$this->input->post();
	     #echo '<pre> $post=>'; print_r($post); echo '</pre>';Die();
         $app_id="1000002";
         $scopes="profile,email";
         $uuid=$this->tppy_oauth->get_user_uuid();
         $redirect_uri = urlencode(site_url());
         $state="";
         $app_secret="9fbe2ba6bc88990d2078348f41fe75a2";
		 #####################################################
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
		#echo '<pre> $accesstoken=>'; print_r($accesstoken); echo '</pre>'; echo '<pre> $profile=>'; print_r($profile); echo '</pre>'; Die();
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
	 #echo '<pre> $datases=>'; print_r($datases); echo '</pre>';      
	 #echo '<pre> $session_data=>'; print_r($session_data); echo '</pre>';Die();
	##############cookie	    		
	$this->load->helper('cookie'); 
	$cookiename='trueplookpanya.com';
	$cookie_data = array('name'=>$cookiename,
					'value'  => $datases,
					'expire' => '86500',
					'domain' => site_url('login'),
	    );
	//setcookie($cookie_name, $cookie_data, time() + (86400 * 30), "/");
	#echo '<pre> $cookie_data=>'; print_r($cookie_data); echo '</pre>';Die();     
	#echo '<pre> $data=>'; print_r($data); echo '</pre>';Die();
    }
	 #echo '<pre> $data=>'; print_r($data); echo '</pre>';Die();
      $this->template->view('test-api', $data);
   }
   public function stream_html() {
      $this->load->view('test-rest', $data);
   }
   public function stream_api() {
      $key = "@doo-plookpanya@";
      $timespan=300;

      $ch = $this->input->get('ch') ? $this->input->get('ch') : 139;
      $uuid = $this->input->get('uuid') ? $this->input->get('uuid') :'be02b035-7c18-4fe9-89bf-60d1961b72dc';
      $agent =$this->input->get('agent') ? $this->input->get('agent') : 'IOS';
      $ts = $this->input->get('ts') ? $this->input->get('ts') : time();
      $hash = $this->input->get('hash') ? $this->input->get('hash') : MD5("$ch|$uuid|$agent|$ts|$key");
      $url=site_url("/mobile/api/stream/getStreamUrl?ch=$ch&uuid=$uuid&agent=$agent&ts=$ts&hash=$hash");
      $data['url']=$url;
      $this->template->view('stream_api', $data);
      // echo "<a href='$url' target='_blank'>LINK</a>";
   }
   public function ping_email($hostname){
      $pingresult = exec("/bin/ping -n 3 $hostname", $outcome, $status);
      if (0 == $status) {
         $status = "alive";
      } else {
         $status = "dead";
      }
      echo "The $hostname, is  ".$status;
      if (0 == $status) {
         echo "<br/>The IP address of $hostname is  ".gethostbyname($hostname);
      }
   }
   public function telnet_email($hostname, $port){
      echo "Current HOST : ".gethostname() . "<br/>";
      $pingresult = exec("/bin/telnet -n 3 $hostname $port", $outcome, $status);
      if (0 == $status) {
         $status = "alive";
      } else {
         $status = "dead";
      }
      echo "The $hostname, is $status ";
      echo "<br/>Message : <pre>";
      echo _vd($outcome);
      echo "</pre>";
      if (0 == $status) {
         echo "<br/>The IP address of $hostname is  ".gethostbyname($hostname);
      }
   }
   public function test_email(){
      $this->load->library('email');
      $this->load->library('member/Member_Library');
      // _vd($this->member_library->sendActivateEmail(500000, 'oatiz@hotmail.com', $username='', $password=''));
      // $this->email->clear();
      // $this->email->from('admin@trueplookpanya.com', 'TruePlookpanya.Com');
      // $this->email->to('g.regularz@gmail.com');
      // $this->email->subject('Trueplookpanya.com - ยินดีต้อนรับสู่สมาชิกทรูปลูกปัญญาดอทคอม');
      // $this->email->message('asdasd');
      // $this->email->send();
      // echo $this->email->print_debugger();
      $user_id=500000;
      $user_email= 'oatiz@hotmail.com';
      $username='oatiz';
      $password='123456';

      $encode = strtr($this->encrypt->encode(json_encode(array('user_email'=>$user_email, 'user_id'=>$user_id)), $this->register_key), '+/', '-_');
      $data['activation_code'] = $encode;
      $data['username'] = $username;
      $data['password'] = $password;

      $email_message = $this->load->view('member/member/email_register', $data, TRUE);

      $this->email->clear();
      $this->email->from('admin@trueplookpanya.com', 'TruePlookpanya.Com');
      $this->email->to($user_email);
      $this->email->subject('Trueplookpanya.com - ยินดีต้อนรับสู่สมาชิกทรูปลูกปัญญาดอทคอม');
      $this->email->message($email_message);
      $this->email->send();

      echo $email_message;
      // echo $this->email->print_debugger();
   }
   public function apitest(){
      if (!is_internal()) {
         echo "Internal Access Only <script>alert('internal only');</script>";
         redirect('home/index');
         exit();
      }
      $this->load->view('apitest');
   }
   public function time_server(){
      echo "MYSQL : " . $this->db->query('SELECT NOW() as mysqltime')->row()->mysqltime;
      echo "<br/>PHP : " . date("Y-m-d H:i:s");
   }
   public function fonts(){
	   $this->load->view('fonts',null);
   }
   public function base64_decrypt($string, $key) {
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
   public function encryptbase64test(){
      header('Content-Type: text/html; charset=utf-8');
      // Model
      $this->load->model('api/crypt_model');
      // key
	  $key= $this->crypt_model->key();
	  // data
	  $datasend=time();
	  $data_encrypt = $this->crypt_model->base64_encrypt($datasend,$key);
	  echo '<pre> ข้อมูล $datasend==>'; print_r($datasend); echo '</pre>';
	  echo '<pre> เข้ารหัส $data_encrypt==>'; print_r($data_encrypt); echo '</pre>';
	  $data_decrypt = $this->crypt_model->base64_decrypt($data_encrypt,$key);
	  echo '<pre> ถอดรหัส $data_decrypt==>'; print_r($data_decrypt); echo '</pre>';
	  Die();
    }
   public function stream_api_test() {
      $key = "@doo-plookpanya@";
      $timespan=300;

      $ch = $this->input->get('ch') ? $this->input->get('ch') : 139;
      $uuid = $this->input->get('uuid') ? $this->input->get('uuid') :'be02b035-7c18-4fe9-89bf-60d1961b72dc';
      $agent =$this->input->get('agent') ? $this->input->get('agent') : 'IOS';
      $ts = $this->input->get('ts') ? $this->input->get('ts') : time();
      $hash = $this->input->get('hash') ? $this->input->get('hash') : MD5("$ch|$uuid|$agent|$ts|$key");
      $url=site_url("/mobile/api/stream/getStreamUrl?ch=$ch&uuid=$uuid&agent=$agent&ts=$ts&hash=$hash");
      $data['url']=$url;
      echo '<pre> $data==>'; print_r($data); echo '</pre>';
      // echo "<a href='$url' target='_blank'>LINK</a>";
   }
   public function logintest(){
      $this->template->view('logintest');
   }
   public function ma(){
                $this->load->view('ma/index.html');
            }
}
