<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Tools extends Public_Controller {
  
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
	  $CI = & get_instance();
      // $this->template->add_stylesheet('assets/colorbox/theme3/colorbox.css');
      // $this->template->add_javascript('assets/colorbox/jquery.colorbox-min.js');
      // $this->template->add_stylesheet('assets/tppy_v1/bower_components/jquery-ui/themes/base/jquery-ui.min.css');
      // $this->template->add_stylesheet('assets/tppy_v1/bower_components/jquery-ui/themes/base/jquery-ui.structure.min.css');
      // $this->template->add_stylesheet('assets/tppy_v1/bower_components/jquery-ui/themes/base/jquery-ui.theme.css');
      // $this->template->add_stylesheet('assets/jquery-select/css/bootstrap-select.min.css');
      // $this->template->add_stylesheet('assets/css/bootstrap/css/bootstrap.css');
      // $this->template->add_javascript('assets/css/bootstrap/js/bootstrap.min.js');
      ob_start('ob_gzhandler');
		//  Check Device
        $this->load->model('device/device_model');
      $this->load->library('TPPY_Oauth');
   }
   
   private function internalonly(){
	   if(!is_internal()){
		   echo "internal access only";
		   exit();
	   }
   }
   public function device()
    {
        //redirect(base_url(), "refresh", 301);
        //exit;
		$this->load->model('device/device_model');
		$device = $this->device_model->get_device();
        if($device === "desktop"){
            //$this->desktop->vw_category();
			
			 echo 'desktop';
			
        } else {
           //$this->mobile->vw_category();
		    echo 'mobile';
        }
		
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
      $arr['public'] = array();
	  $arr['internal'] = array();
	  
	  $arr['public']=[
         'PUBLIC TOOLS ('.ip_client().')'=>'-',
         'Text Count'=> '/tools/txtcount',
		 'Test fonts'=> 'tools/fonts',
         'Byte Count'=> 'https://mothereff.in/byte-counter',
		 'Form test'=>'/tools/apitest',
      ];
	  
	  if(is_internal()){
		  $arr['internal']=[
				 'AUTHORIZE'=>'-',
				 'Authorize'=> '/tools/test-api',
				 'SERVER TOOLS'=>'-',
				 'Display Header'=> '/tools/display-header',
				 'PHP INFO'=> '/tools/info',
				 'Hasher'=> '/tools/hasher',
				 'STREAM'=>'-',
				 'Stream CH ทรูปลูกปัญญา HD'=> '/tools/stream-html?ch=139',
				 'Stream CH สามเณรปูกปัญญา HD'=> '/tools/stream-html?ch=333',
				 'Stream API'=> '/tools/stream-api',
			  ];
	  }
	  
	  $data['list'] = array_merge($arr["public"], $arr["internal"]);

      $this->template->view('index', $data);
   }

   public function partapi(){
      $this->internalonly();
	  
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
      $this->internalonly();
	  foreach($_SERVER as $header=>$val)
      {
         echo '<b style="color:red">'.$header .' : </b> '. $val .'</br>';
      }
   }
   public function txtcount(){
      $this->load->view('txtcount');
   }
   public function hasher() {
      $this->internalonly();
	  
	  $this->load->view('hasher');
   }
   public function info(){
      $this->internalonly();
	  
	  $this->load->view('info');
   }
   public function test_api() {
      $this->internalonly();
	  
	  $data=array();
      if($this->input->post()){

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
         $url=site_url("/member/api/authorize?username=$user_username&password=$user_password&app_id=$app_id&scopes=$scopes&redirect_uri=$redirect_uri&uuid=$uuid");
         $code_data = json_decode(file_get_contents($url), FALSE);
         $data['authorize_url']=$url;
         // TOKEN
         $url=site_url("/member/api/accesstoken?code=".$code_data->data->code."&app_id=$app_id&redirect_uri=$redirect_uri&state=$state&app_secret=$app_secret");
         $token_data= json_decode(file_get_contents($url), FALSE);
         $data['token_url']=$url;
         $data['token']=$token_data->data->accesstoken->token;
         // PROFILE
         $url=site_url("/member/api/profile?token=".$token_data->data->accesstoken->token);
         $profile_data= json_decode(file_get_contents($url), FALSE);
         $data['profile_url']=$url;

      }
      $this->template->view('test-api', $data);
   }

   public function stream_html() {
      $this->internalonly();
	  
	  $this->load->view('test-rest', $data);
   }
   public function stream_api() {
      $this->internalonly();
	  
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
      $this->internalonly();
	  
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
      $this->internalonly();
	  
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
      $this->internalonly();
	  
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
   
   public function fonts()
   {
	   $this->load->view('fonts',null);
   }
   public function fonts2()
   {
	   $this->load->view('fonts_bak1',null);
   }
   
   public function fonts_unitTest1()
   {
	   $this->load->view('fonts_unitTest1',null);
   }
   
   public function testk(){
	    $CI = & get_instance();
		_vd($CI->trueplook->number_format_thousands(7));// 
		_vd($CI->trueplook->number_format_thousands(49));// 
		_vd($CI->trueplook->number_format_thousands(500));// 
		_vd($CI->trueplook->number_format_thousands(3000));//  -3k
		_vd($CI->trueplook->number_format_thousands(2345));//  
		_vd($CI->trueplook->number_format_thousands(10000));//  
		_vd($CI->trueplook->number_format_thousands(35568));//  -35.5k
		_vd($CI->trueplook->number_format_thousands(905000));//  -905k
		_vd($CI->trueplook->number_format_thousands(5500000));//  -5.5m
		_vd($CI->trueplook->number_format_thousands(88800000));//  -88.8m
		_vd($CI->trueplook->number_format_thousands(745000000));//  -745m
		_vd($CI->trueplook->number_format_thousands(2000000000));//  -2b
		_vd($CI->trueplook->number_format_thousands(22200000000));//  -22.2b
		_vd($CI->trueplook->number_format_thousands(1000000000000));//  -1t (1 trillion)
   }
   
}
