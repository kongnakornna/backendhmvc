<?php
//(defined('BASEPATH')) OR exit('No direct script access allowed');
class center extends REST_Controller {
	##### START #####
    public function __construct() {
        parent::__construct();
        //if (ob_get_length() > 0) {       
        ob_clean();
		date_default_timezone_set('asia/bangkok');
		$this->load->model('getRelates_model');
		$this->load->model('getRelate_model');
		$this->load->model('Examination_center_model');
		$this->load->model('webboardapi_model');
		$this->load->model('crypt_model');
		$this->load->model('centermobile_model');
		$this->load->model('center_model');
		$this->load->model('public_model');
        // Oauth zone Start
        $this->load->library('TPPY_Oauth');
        $token = $this->get('token', FALSE, '');
		$this->me=$this->tppy_oauth->parse_token($token);
        $this->load->model('favorite/favorite_model');
        $this->myfav=$this->get('myfav', 0);
		if($this->myfav==1){
			$valid_data=$this->tppy_oauth->check_token($this->me, 'profile');
			if(!$valid_data['status']) {
			  $this->response(array('response'=>array('status'=>$valid_data['status'], 'message'=>$valid_data['message'],'code'=>(int)$valid_data['code']), 'data'=> array($data)), $valid_data['http_code']);
			}
		}
		///////////////
		function isJSON($string){
		   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
		}
		function isJSON2($string){
		   return is_string($string) && is_array(json_decode($string, true)) ? true : false;
		}
		//////////////
		// Oauth zone End
		/////////////////
		$this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('TPPY_Utils');
        $this->load->model('admissions/admissions_models', 'admissions_model');
		$this->load->model('admissions/campnews_model');
        $this->load->model('mobile/admission_model', 'amm');
        $this->load->model('api/banner_model', 'banner_model');
       // $this->load->model('api/webboardapi_model');
       // $this->load->model('api/getRelate_model');
        $this->load->helper('tppy_helper');
		/////////////////
		 
        header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json; charset=utf-8');
		
		
        //}
        //ini_set('display_errors', '1');
        //error_reporting(E_ALL);
    }
    public function  index_get() {
		$this->cmsbloglist_get();
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
	///////////////////////	
	public function  cryptkey_get() {
				 /////////////
				 $this->load->model('api/Crypt_model');
				 $key=$this->Crypt_model->key(); // key ที่ต้องใช้
				 $string='Test@gmail.comนะ';
				 $apikey = $this->Crypt_model->apikey();
				 $base64_encrypt = $this->Crypt_model->base64_encrypt($string,$key);
				 $base64_decrypt = $this->Crypt_model->base64_decrypt($base64_encrypt,$key); 
				 $base_url=base_url('');
				 $dataurl=$base_url.'api/center/cryptkey';
				 $datamodel='plookpanya/canvas/application/modules/api/models/Crypt_model.php';
				 
				 $data=array('url api' => $dataurl,
							 'model call' => $datamodel,
							 'data send' => $string,
							 'data encrypt' => $base64_encrypt,
							 'data decrypt' => $base64_decrypt);
				
			 //echo '<pre> $key==>'; print_r($key); echo '</pre>'; 
			 //echo '<pre> $string==>'; print_r($string); echo '</pre>'; 
			 //echo '<pre> $base64_encrypt==>'; print_r($base64_encrypt); echo '</pre>'; //Die();
			 //echo '<pre> $base64_decrypt==>'; print_r($base64_decrypt); echo '</pre>';  Die();
				 ////////////////
			 $count=count($data);
			 if($data){
				$this->response(array('header'=>array(
										'title'=>'key',
										'model'=>'modules/api/models\Crypt_model',
										'function'=>'base64_encrypt,base64_decrypt',
										'version ' => '2.0',
										'description' => 'List data',
										'message' => 'REST DONE',
										'remarks' => 'HTTP GET',
										'message'=>'Success',
										'status'=>true,
										'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'key',
										'model'=>'api/model/Crypt_model',
										'function'=>'cryptkey_get',
										'version ' => '2.0',
										'description' => 'Null',
										'message' => 'REST Error 404',
										'remarks' => 'HTTP GET',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
		  }	  
	public function  cryptkeyedittor_get() {
				 /////////////
				 $this->load->model('api/crypt_model');
				 $key=$this->crypt_model->key(); // key ที่ต้องใช้
				 //$keyna2=$key;
				 //$string='cryptkeyedittor';
				 $keyna=@$this->input->get('key');
				 $keyna2=@$this->input->get('key2');
				 $string=$keyna;
				 $apikey = $this->crypt_model->apikey();
				 $base64_encrypt = $this->crypt_model->base64_encrypt($string,$keyna2);
				 $base64_decrypt = $this->crypt_model->base64_decrypt($base64_encrypt,$keyna2); 
				 
				 $keyuploads = $this->crypt_model->uploads(); 
				 
				 #echo '<pre> $base64_encrypt==>'; print_r($base64_encrypt); echo '</pre>';
				 #echo '<pre> $base64_decrypt==>'; print_r($base64_decrypt); echo '</pre>';  Die();
				 if($keyna==''){
				 	$data=0;	
				 }else{
				 		if($keyna==$keyuploads){
				 			$data=1;	
						 }else{
						 	$data=0;
						 }
				 }
				 
				// $base_url=base_url('');
				// $dataurl=$base_url.'api/center/cryptkeyedittor';
				// $datamodel='plookpanya/canvas/application/modules/api/models/crypt_model.php';
				 
				 
				 
				 
				 
				 
				 /*
				 $data=array('url api' => $dataurl,
							 //'model call' => $datamodel,
							 //'data send' => $string,
							 //'data encrypt' => $base64_encrypt,
							 'data' => $base64_decrypt);
				*/
			 //echo '<pre> $key==>'; print_r($key); echo '</pre>'; 
			 //echo '<pre> $string==>'; print_r($string); echo '</pre>'; 
			 //echo '<pre> $base64_encrypt==>'; print_r($base64_encrypt); echo '</pre>'; //Die();
			 //echo '<pre> $base64_decrypt==>'; print_r($base64_decrypt); echo '</pre>';  Die();
				 ////////////////
			 if($data){
				$this->response(array('header'=>array(
										'title'=>'cryptkeyedittor',
										'message' => 'REST DONE',
										'remarks' => 'HTTP GET',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'cryptkeyedittor',
										'message' => 'REST Error 404',
										'remarks' => 'HTTP GET',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=>0),404);
				}
		  }	///////////////////////	
	public function  login_get() {

	//login?username=adminna&password=123456 
	$user_username = @$this->input->get('username');
	$password = @$this->input->get('password');
	if($user_username<>'' || $password<>''){
	
	$user_password=md5($password);
	$this->load->model('api/Crypt_model');
	$memkey=$this->Crypt_model->get_profile_username($user_username,$user_password);
	}else{ }
	//echo '<pre> $memkey==>'; print_r($memkey); echo '</pre>'; Die();
		if($memkey==0){
		$urllogin=base_url().'api/center/login?username='.$user_username.'&password='.$password;
		?>

		<form action="<?php echo $urllogin;?>" method="get" enctype="ret" name="form1" id="form1">
			<table width="428" border="0">
			<tr>
			  <td colspan="2"><div align="center">Login API </div></td>
			</tr>
			<tr>
			  <td width="92">User Name </td>
			  <td width="326"><input name="username" type="text" id="username" /></td>
			</tr>
			<tr>
			  <td>Password</td>
			  <td><input name="password" type="password" id="password" /></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td><input type="submit" name="Submit" value="LOGIN" /></td>
			</tr>
		  </table>
		  </form>
		
		<?php	
		}else{
			$memberid=$memkey->member_id; 
			$this->load->model('api/Crypt_model');
			$key=$this->Crypt_model->key2(); // key ที่ต้องใช้
			#echo 'key'.$key; Die(); // keyCaLl@!truEpLOOkPaYa
			$string='MEM0001148';
			$base64_encrypt = $this->Crypt_model->base64_encrypt($string,$key);
			//echo '<pre> $base64_encrypt==>'; print_r($base64_encrypt); echo '</pre>';
			redirect('api/center/listapi?apikeys='.$base64_encrypt.'', 'location'); Die();
		}
	}
	public function listapi_get(){
		// api?format=xml
       ob_clean();
       $this->load->helper('url');
       $api_url=base_url('api');
	   $headerapi=array('title' => 'Trueplookpanya web service restful api Dev use PHP/MySQL',
					'status' => 200,
					'Version ' => '1.0 and 2.0',
					'description' => 'List data',
					'message' => 'REST DONE',
	        		'remarks' => 'HTTP GET',
	        		'support format' => 'json,jsonp,xml,php,csv,html,serialized,array',
	        		'example format'=>'uri+ ?format=xml',
	        		'tool Call'=>'https://github.com/rmccue/Requests',
	        		'tool Dev'=>'https://github.com/chriskacerguis/codeigniter-restserver',
					'local path'=>'/www/plookpanya/canvas/application/modules/api/',
					'var path'=>'/canvas/application/modules/api/',
					'call' => $api_url.'/call/'
                    );
       $dataapi=array(
 //////////////////// center Module
 /*
	   'Module center learning' =>array('module' => 'center','function' => 'learning',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล learning',
					'url' => $api_url.'/center/learning',),
	   'Module center exam' =>array('module' => 'center','function' => 'exam',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล exam',
					'url' => $api_url.'/center/exam',),
	   'Module center mul_content' =>array('Module' => 'center','function' => 'learningdetail',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล mul_conten',
					'url' => $api_url.'/center/learningdetail?mul_content_id=500',),
	   'Module center knowledgecontent' =>array('module' => 'center','function' => 'knowledgecontent',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล knowledgecontent',
					'url' => $api_url.'/center/knowledgecontent',),
	   'Module center cms' =>array('module' => 'center','function' => 'cms',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล cms แบบไม่ระบุหมวดหมู่',
					'url' => $api_url.'/center/cms',),	
       'Module center cms->catid' =>array('module' => 'center','function' => 'cms',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล cms  ระบุหมวดหมู่ ส่งค่า catid ',
					'url' => $api_url.'/center/cms?catid=136',),	
	   'Module center cms->content_id&catid' =>array('module' => 'center','function' => 'cms',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล cms ระบุหมวดหมู่และบทความ ส่งค่า content_id และ catid',
					'url' => $api_url.'/center/cms?content_id=50009&catid=21',),	
	   'Module center admissionnews' =>array('module' => 'center','function' => 'admissionnews',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล admissionnews all',
					'url' => $api_url.'/center/admissionnews',),	
	   'Module center tvprogram' =>array('module' => 'center','function' => 'tvprogram',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล center TV Program all',
					'url' => $api_url.'/center/tvprogram',),	
       'Module center cmsblog' =>array('module' => 'center','function' => 'cmsblog',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล cmsblog',
					'url' => $api_url.'/center/cmsblog?format=jsonp',),
	   'Module center cmsblogdetail->content_id' =>array('module' => 'center','function' => 'cmsblogdetail(content_id)',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล center cmsblogdetail content_id',
					'url' => $api_url.'/centerv2/cmsblogdetail?content_id=50013',),		 
	   'Module center cmsblogdetailrelate->content_id' =>array(
					'module' => 'center',
					'function' => 'cmsblogdetailrelate(content_id)',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล center cmsblogdetailrelate content_id',
					'url' => $api_url.'/centerv2/cmsblogdetailrelate?content_id=50001',),		
	   'Module center cmsblogcategory' =>array(
					'module' => 'center',
					'function' => 'cmsblogcategory',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล center cmsblogdetailrelate content_id',
					'url' => $api_url.'/centerv2/cmsblogcategory',),	
  
	   'Module center cmsblogcategorycode' =>array(
					'module' => 'center',
					'function' => 'cmsblogcategorycode',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล center cmsblogdetailrelate cmsblog_id & category_id ',
					'url' => $api_url.'/centerv2/cmsblogcategorycode?cmsblog_id=1&category_id=0',),	
	   'Module center cmsblogcountcontent' =>array(
					'module' => 'center',
					'function' => 'cmsblogcountcontent',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล center cmsblogcountcontent ',
					'url' => $api_url.'/center/cmsblogcountcontent',),	
	   'Module center cmsbloghistory' =>array(
					'module' => 'center',
					'function' => 'cmsbloghistory',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล center cmsbloghistory ',
					'url' => $api_url.'/centerv2/cmsbloghistory',),	
	   'Module center cmsblogfuture' =>array(
					'module' => 'center',
					'function' => 'cmsblogfuture',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล center cmsblogfuture ',
					'url' => $api_url.'/center/cmsblogfuture',),	
	   'Module center bloggersummary' =>array(
					'module' => 'center',
					'function' => 'bloggersummary',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล center bloggersummary  sumType =daily,zone_id=1',
					'url' => $api_url.'/center/bloggersummary',),	
 //////////////////// knowledge Module
 ////////////////////  Module knowledge // editorpicks form knowledge module
 	   'Module knowledge editorpicks_get' =>array(
					'module' => 'knowledge',
					'function' => 'editorpicks_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>' รับค่าแบบ Get ตัวแปรที่ต้องการ เช่น ?menu_id=10&limit=4&offset=0',
					'url' => $api_url.'/center/editorpicks?menu_id=10&limit=4&offset=0',),	
					
 ////////////////////  Module blog
 	   'Module Blog index' =>array(
					'module' => 'blog',
					'function' => 'index',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล blog/index_get ',
					'url' => $api_url.'/blog',),
					
 ////////////////////  Module Blog User
 	   'Module Blog blogcontentbyuser' =>array(
					'module' => 'blog',
					'function' => 'blogcontentbyuser_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล blog content by user รับค่า  user_id มา แล้วแสดงข้อมูล user',
					'url' => $api_url.'/center/blogcontentbyuser?user_id=ค่าuserid',),	
					
 ////////////////////  Module Blog content  by user
 	   'Module Blog contentbyuser' =>array(
					'module' => 'blog',
					'function' => 'contentlistbyuser_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล blog content  by user  Login เข้าระบบ รับค่า  ?user_id=ค่าuserid&type=type&q=keyword  หรือ ?user_id=ค่าuserid ',
					'url' => $api_url.'/center/contentlistbyuser?user_id=ค่าuserid&type=&q=',), //	 510325
 	   'Module Blog content  bycontentlistbyuserenable' =>array(
					'module' => 'blog',
					'function' => 'contentlistbyuserenable_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล  ไม่ ได้ Login blog content  by user  รับค่า  ?user_id=ค่าuserid&type=type&q=keyword  หรือ ?user_id=ค่าuserid ',
					'url' => $api_url.'/center/contentlistbyuserenable?user_id=ค่าuserid&type=&q=',), //	 510325
					
 	   'Module Blog content  contentlistbyuserecount' =>array(
					'module' => 'blog',
					'function' => 'contentlistbyuserecount_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล  ไม่ ได้ Login blog content  by user  รับค่า  ?user_id=ค่าuserid',
					'url' => $api_url.'/center/contentlistbyuserecount?user_id=ค่าuserid',), //	 510325

	   'Module cmsblog   cmsbloglist' =>array(
					'module' => 'cmsblog',
					'function' => 'cmsbloglist_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล   cmsbloglist รับค่า  แบบ get ?limit=&offset=&order=last&keyword=&type=&user_id_login=xxx หรือ ?limit=&offset=&order=last&keyword=&type=&user_id=xxx หรือ?limit=&offset=&order=last&keyword=&type=&user_id=
  เช่น  '.$api_url.'api/center/cmsbloglist?limit=20&offset=&order=last&keyword=&type=&user_id=',
					'url' => $api_url.'/center/cmsbloglist?limit=20&offset=&order=last&keyword=&type=&user_id='),  

	*/ 
 	/// Examination_center
	
		/// Examination relateexamination_get
 	   'Module examination content  relateexamination_get' =>array(
					'module'=>'relateexamination',
					'models'=>'api/getRelate_model->getRelate_exam',
					'function'=>'relateexamination_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'',
					'url' => $api_url.'/center/relateexamination?content_id=&limit=20&offset=15&order=&subject_id=&level_id=&context_id=',),  
			
 	   'Module examination content  examinationlistall' =>array(
					'module' => 'examination',
					'models' => 'api/models/Examination_center_model',
					'function' => 'examinationlistall_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล ชุดวิชาแบบทดสอบ  หากต้องการดูรายวิชาแบบเจาะจงส่งค่า ?exam_id=xx ตามด้วย  limit=จำนวนที่จะแสดง ตามด้วย  exam_name=ชือรายวิชา รับค่า แบบ get เช่น api/center/examinationlistall?exam_id=499',
					'url' => $api_url.'/center/examinationlistall?exam_id=&limit=100&exam_name=',),    
	/// Examination_examinationscore
   /*
	   'Module examination content  examinationscore' =>array(
					'module' => 'examination',
					'models' => 'api/models/Examination_center_model',
					'function' => 'examinationscore_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล การทำแบบทดสอบ และ  log ที่เคยทำแบบทดสอบ  รับค่า แบบ get เช่น api/center/examinationscore?exam_id=499&member_id=MEM0002874',
					'url' => $api_url.'/center/examinationscore?exam_id=idชุดวิชา&member_id=รหัสสมาชิก',),  
	*/				
/// Examination_examinationscoremax_get
 	   'Module examination content  examinationscoremax' =>array(
					'module' => 'examination',
					'models' => 'api/models/Examination_center_model',
					'function' => 'examinationscoremax_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล คแนนสุงสุดในการทำแบบทดสอบ ของวิชาที่ต้องการ  รับค่า แบบ get เช่น  api/center/enter/examinationscore?exam_id=499',
					'url' => $api_url.'/center/examinationscore?exam_id=idชุดวิชา',),  
 	   
	   'Module examination content  examinationmemberlog' =>array(
					'module' => 'examination',
					'models' => 'api/models/Examination_center_model->get_examination_user_log',
					'function' => 'examinationmemberlog_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'description' =>'ใช้ แสดงข้อมูล  Log ตาม user member id ตัวแปร user_id=รหัสidผู้ใช้&per_page=จำหนวนแสดงต่อหน้า&offset=เลขหน้า&order=asc=เรียงจากน้อยไปมาก/desc=เรียงจากมากไปน้อย  แบบทดสอบ ของวิชาที่ต้องการ  score_value=คะแนนที่ทำได้  countquestion=คะแนนเต็ม /จำนวนข้อทั้งหมด รับค่า แบบ get เช่น  api/center/examinationmemberlog?user_id=2874&per_page=10&offset=1&order=asc',
					'url' => $api_url.'/center/examinationmemberlog?user_id=2874&per_page=10&offset=1&order=asc',),  

	   'Module examination content  examinationgraphbycourse' =>array(
					'module' => 'examination',
					'models' => 'api/models/Examination_center_model->get_examinationgraphbycourse($exam_id,$start,$end)',
					'function' => 'examinationgraphbycourse_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0  date 2017-01-23',
					'Parameter รับมา' => '?exam_id=500&startdate=1420045200&enddate=1485104400 || $exam_id=รหัสวิชา,$start=วันที่เริ่มต้น  รับค่าแบบ strtotime ,$end=วันที่สินสุด   รับค่าแบบ strtotime',
					'Return  ค่าที่ส่งกลับมา' => 'listlog=ข้อมูลlog้อสอบ,exam=ข้อมูลข้อสอบ,count=จำนวนข้อมูล,score=ข้อมูลคำตอบและจำนวนคนตอบ,startdate=วันที่เริ่มต้น,enddate=วันที่สินสุด,exam_id=รหัสวิชา	
	',
					'Etc ' => '$exam_id=รหัสวิชา,$start=วันที่เริ่มต้น  รับค่าแบบ strtotime ,$end=วันที่สินสุด   รับค่าแบบ strtotime',
					'description' =>'ใช้ แสดงข้อมูล ไปแสดง ใน กราฟ สถิติคะแนนของผู้ที่ทำข้อสอบ        $exam_id=รหัสวิชา,$start=วันที่เริ่มต้น  รับค่าแบบ strtotime ,$end=วันที่สินสุด   รับค่าแบบ strtotime เช่น ?exam_id=500&startdate=1420045200&enddate=1485104400',
					'url' => $api_url.'/center/center/examinationgraphbycourse?exam_id=500&startdate=1420045200&enddate=1485104400',),  

	   ////////////// API For Mobile START ##################
	   
	   'Module Mobile Admission function admissiontabmenu_get' =>array(
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_tabmenu',
					'function' => 'admissiontabmenu_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'ใช้ แสดงข้อมูล ',
					'url' => $api_url.'/center/admissiontabmenu/xxx',),  
	   
	    
	   'Module Mobile Admission function admissiontabfeed_get' =>array(
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_tabfeed',
					'function' => 'admissiontabfeed_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'ใช้ แสดงข้อมูล ',
					'url' => $api_url.'/center/admissiontabfeed/xxx',),  
					
	   'Module Mobile Admission function admissiongang_get' =>array(
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_gang',
					'function' => 'admissiongang_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'ใช้ แสดงข้อมูล ',
					'url' => $api_url.'/center/admissiongang/xxx',),  
					
	   'Module Mobile Admission function admissionschooltour_get' =>array( 
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_schooltour',
					'function' => 'admissionschooltour_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'ใช้ แสดงข้อมูล ',
					'url' => $api_url.'/center/admissionschooltour/xxx',),  
					
	   'Module Mobile Admission function admissiontimeline_get' =>array( 
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_timeline',
					'function' => 'admissiontimeline_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'ใช้ แสดงข้อมูล ',
					'url' => $api_url.'/center/admissiontimeline/xxx',),  
					
	   'Module Mobile Admission function admissionweboard_get' =>array( 
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_weboard',
					'function' => 'admissionweboard_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'ใช้ แสดงข้อมูล ',
					'url' => $api_url.'/center/admissionweboard/xxx',),  
					
	   'Module Mobile Admission function admissionweboardadd_post' =>array( 
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_weboardadd',
					'function' => 'admissionweboardadd_post',
					'type' =>'POST',
					'type info' =>'HTTP POST',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'ใช้ เพิ่มกระทู้ฮอต ',
					'url' => $api_url.'/center/admissionweboardadd/xxx',),  

	   'Module Mobile Admission function admissioncampnews_get' =>array(  
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_campnews',
					'function' => 'admissioncampnews_get',
					'type' =>'POST',
					'type info' =>'HTTP POST',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'ใช้ หน้า ข่าวค่าย ของเดิมย้ายมาอยู่ tab ข่าวค่าย ',
					'url' => $api_url.'/center/admissioncampnews/xxx',),  

	   'Module Mobile Admission function admissionquicksearch_post' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_quicksearch',
					'function' => 'admissionquicksearch_post',
					'type' =>'POST',
					'type info' =>'HTTP POST',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'ใช้ หน้า ข่าวค่าย Quick Search',
					'url' => $api_url.'/center/admissionquicksearch/xxx',),  
					
	   'Module Mobile Admission function admissiongetstraightnews_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_get_straight_news',
					'function' => 'admissiongetstraightnews_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>' tab  ข่าวรับตรง get_straight_news  ',
					'url' => $api_url.'/center/admissiongetstraightnews/xxx',),  
						
	   'Module Mobile Admission function admissiongetstraightnewsquickqearch_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_get_straight_news_quick_search',
					'function' => 'admissiongetstraightnewsquickqearch_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',  
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>' tab  ข่าวรับตรง get_straight_news  Quick Search ',
					'url' => $api_url.'/center/admissiongetstraightnewsquickqearch/xxx',),  
					
	   'Module Mobile Admission function admissionforumsall_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_forumsall',
					'function' => 'admissionforumsall_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'ใช้ แสดง  เว็บบอร์ด admission ทั้งหมด',
					'url' => $api_url.'/center/admissionforumsall/xxx',),  
			 
				 
	   'Module Mobile Admission function admissionhottopic_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_hottopic',
					'function' => 'admissionhottopic_get',
					'type' =>'GET',
					'type info' =>'HTTP GET', 
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'ใช้ แสดง  เว็บบอร์ด กระทู้ฮอต',
					'url' => $api_url.'/center/admissionhottopic/xxx',),  

	   'Module Mobile Admission function admissionrecentposts_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_recentposts',
					'function' => 'admissionrecentposts_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'แสดงข้อมูล กระทู้ล้าสุด ',
					'url' => $api_url.'/center/admissionrecentposts/xxx',),  

	   'Module Mobile Admission function admissiontutoringandtest_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_tutoringandtest',
					'function' => 'admissiontutoringandtest_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'แสดงข้อมูล   ติวและข้อสอบ ',
					'url' => $api_url.'/center/admissiontutoringandtest/xxx',),  
					
	   'Module Mobile Admission function admissionforumdetails_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_forum_details',
					'function' => 'admissionforumdetails_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'แสดงข้อมูล  หน้า รายละเอียดเว็บบอร์ด ',
					'url' => $api_url.'/center/admissionforumdetails/xxx',),  
					
	   'Module Mobile Admission function admissionshare_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_share',
					'function' => 'admissionshare_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'แสดงข้อมูล   admission share ',
					'url' => $api_url.'/center/dmissionshare/xxx',),  

	   'Module Mobile Admission function admissioncommentandsticker_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_commentandsticker',
					'function' => 'admissioncommentandsticker_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'แสดงข้อมูล admission comment and sticker ',
					'url' => $api_url.'/center/admissioncommentandsticker/xxx',),  

	   'Module Mobile Admission function admissionreport_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_report',
					'function' => 'admissionreport_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'แสดงข้อมูล  ',
					'url' => $api_url.'/center/admissionreport/xxx',),  
				
	   'Module Mobile Admission function admissionlikeandunlike_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_like_and_unlike',
					'function' => 'admissionlikeandunlike_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'แสดงข้อมูล  ',
					'url' => $api_url.'/center/admissionlikeandunlike/xxx',),  					
/*
	   'Module Mobile Admission function admission_get' =>array(   
					'module' => 'Admissionmobile',
					'models' => 'api/models/Examination_center_model->get_admission_',
					'function' => 'admission_get',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'Parameter รับมา' => '--',
					'Return  ค่าที่ส่งกลับมา' => '--',
					'Etc ' => '--',
					'Statusapi ' => 'OnHold', // Process //Done
					'description' =>'แสดงข้อมูล  ',
					'url' => $api_url.'/center/admission_/xxx',),  
					
*/					
	   ////////////// API For Mobile END ##################	
	   );
	   
		$this->load->model('api/Crypt_model');
		$key=$this->Crypt_model->key2(); // key ที่ต้องใช้
		#echo 'key'.$key; Die(); // keyCaLl@!truEpLOOkPaYa
		$base64_encrypt=$this->input->get('apikeys');
		$base64_decrypt = $this->Crypt_model->base64_decrypt($base64_encrypt,$key);
		$memkey=$this->Crypt_model->get_profile_member_id($base64_decrypt);
		//echo '<pre> $memkey==>'; print_r($memkey); echo '</pre>'; 
		//echo '<pre> $base64_decrypt==>'; print_r($base64_decrypt); echo '</pre>'; Die();
		//if($string==$base64_decrypt){
		if($memkey==1){
				$json['header']=$headerapi;
				$json['countapi']=count($dataapi);
				$json['data']=$dataapi;
		}else{ 
			
			  redirect('api/center/login', 'location'); Die();
		
			   $header404=$this->response(array('header'=>array(
												'title'=>'List API',
												'message' => 'REST Error 404',
												'remarks' => 'HTTP GET',
												'message'=>'Forbiden',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);$data404=Null;
				$json['header']=$header404;
				$json['data']=$data404;
			}
	    
		
		
	    
	   
		 //$json= str_replace('&amp;','&', $json);
		 //$json= preg_replace('&amp;','&', $json);
       //echo '<pre> $json=>'; print_r($json); echo '</pre>'; Die();
       $this->response($json);     	
	}
    private function _set_response() {
        return array('response' => array(
                'status' => TRUE,
                "massage" => 'success',
                'code' => 200
        ));
    }
    private function _set_not_found() {
        return array('response' => array(
                'status' => FALSE,
                "massage" => 'Data not found',
                'code' => 404
            ),
            'data' => []);
    }
    private function _set_response_error($msg = "unkown error", $code = 400) {
        return array('response' => array(
                'status' => FALSE,
                "massage" => $msg,
                'code' => $code
            ),
            'data' => []);
    }
	public function sessioncheck_get() {  
			$this->load->library('session');
			$this->load->library('TPPY_Oauth');
			$data=$this->session->userdata('user_session');
			//$data['doSendResult']=$this->session->userdata('doSendResult');
			$user_password=$data->user_password;
			$count=count($data);
			 if($count<=0 ||$count==Null){
				$this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> Null),404);
										Die();
			 }
			 
			 if($data){
			    //$data['user_password']=Null;	 
			    //$data['user_password']=Null;	 
				$this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true,
										'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
			
			
	}
	/////////////////////
	// new KNOWLEDGE = LEARNING : start
	public function learning_get() {

        $req = [];

        $req['subject_id'] = $_GET['sid'];
        $req['level_id'] = $_GET['lid'];
        $req['knowledge_id'] = $_GET['ssid'];
		$req['context_id'] = $_GET['cid'];
        $req['q'] = $_GET['q'];

        $req['start'] = $_GET['limit'];
        $req['end'] = $_GET['offset'];
		$req['order'] = $_GET['order'];
        $req['type'] = "all"; //$_GET['type'];
		$req['labeltagList'] = $_GET['lt'];
		
		$json['header']['title'] = 'center Version 2.0 Learning';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		
		$this->load->model('getRelates_model');
        $qResult = $this->getRelates_model->get_Learning_list($req);
        $json['data'] = $qResult;
		
        $this->response($json);
	}
	public function learningdetail_get($mul_content_id = 0){
		
		$json['header']['title'] = 'center Version 2.0 Learning';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		
		$this->load->model('getRelates_model');
        $qResult = $this->getRelates_model->get_Learning_detail($mul_content_id);
        $json['data'] = $qResult;
		
        $this->response($json);
	}
	// new KNOWLEDGE = LEARNING : end
    public function getdetailcontent_get($content_id = null) {
        $json = null;
        $this->load->model('getRelates_model');
        $qResult = $this->getRelates_model->getDetail_content($content_id, 'mul_content');
        //var_dump($qResult);

        $json['header']['title'] = 'get Content Detail';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = $v['child_view_count']; //$this->trueplook->getViewNumber($v['content_child_id'], 7); 
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr['d']['sub_category_id'] = $v['sub_category_id'];
                $arr['d']['sub_category_name'] = $v['sub_category_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }	
    public function knowledgesource_get($content_id = null) {
        $json = null;
        $this->load->model('getRelates_model');

        $limit = null;
        $offset = null;
        $orderby = null;
        $qResult = $this->getRelates_model->center_source($content_id, $limit, $offset, $orderby);
        //var_dump($qResult);

        $json['header']['title'] = 'center Version 2.0 Content';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                if ($v['content_child_id'] == '') {
                    $arr['d']['viewcount'] = $v['content_view_count']; //$this->trueplook->getViewNumber($v['content_id'], 0); //$v['viewcount'];
                } else {
                    $arr['d']['viewcount'] = $v['child_view_count']; //$this->trueplook->getViewNumber($v['content_child_id'], 7); //$v['viewcount'];
                }
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr['d']['sub_category_id'] = $v['sub_category_id'];
                $arr['d']['sub_category_name'] = $v['sub_category_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;

        $this->response($json);
    }
    public function knowledgecontent_get($content_id = null, $limit = 20, $order = "random") {
        $json = null;
        $subject_id = null;
        $level_id = null;
        $context_id = null;

        if (isset($_GET['sid']) && !empty($_GET['sid'])) {
            $subject_id = trim($_GET['sid']);
        }
        if (isset($_GET['cid']) && !empty($_GET['cid'])) {
            $context_id = trim($_GET['cid']);
        }
        if (isset($_GET['lid']) && !empty($_GET['lid'])) {
            $level_id = trim($_GET['lid']);
        }

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }

        $this->load->model('getRelates_model');

        //$limit = null;
        //$offset = null;
        //$orderby = null;

        $req["isActivity"] = FALSE;

        if ($subject_id >= 1000 && $subject_id < 9000) {
            $qResult = $this->getRelates_model->center_content($content_id, $limit, $offset, $order, $subject_id, $level_id, $context_id);
        } else {
            
            $req['start'] = $_GET['start'] == '' ? 0 : $_GET['start'];
            $req['end'] = $_GET['end']=='' ? 10 : $_GET['end'];
            
            
            if (isset($_GET['mul_content_id']) && !empty($_GET['mul_content_id'])) {
                $req['mul_content_id'] = trim($_GET['mul_content_id']);
            }
            if (isset($_GET['mul_source_id']) && !empty($_GET['mul_source_id'])) {
                $req['mul_source_id'] = trim($_GET['mul_source_id']);
            }

            $req['category_id'] = $subject_id;
            $req["isUniverseContent"] = TRUE;
            $req["isActivity"] = TRUE;
            
            
            $sql = $this->getRelates_model->_getContent_query_lite('all', $req);
            //_pr($sql);
            $qResult = $this->db->query($sql)->result_array();
        }



        //var_dump($qResult);

        $json['header']['title'] = 'center Version 2.0 Content';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];

                if ($req["isActivity"]) {
                    $arr['d']['content_child_id'] = $v['content_id_child'];
                    $arr['d']['topic'] = $v['content_subject'];
                    $arr['d']['url'] = $v['content_url'];
                    $arr['d']['thumbnail'] = $v['thumbnail'];
                } else {
                    $arr['d']['topic'] = $v['title'];
                    $arr['d']['url'] = $v['url'];
                    $arr['d']['thumbnail'] = $v['thumbnail'];
                }

                if ($v['content_child_id'] == '') {
                    $arr['d']['viewcount'] = $v['content_view_count']; //$this->trueplook->getViewNumber($v['content_id'], 0); //$v['viewcount'];
                } else {
                    $arr['d']['viewcount'] = $v['child_view_count']; //$this->trueplook->getViewNumber($v['content_child_id'], 7); //$v['viewcount'];
                }
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                if ($v['context_id'] != null) {
                    $arr['d']['context_id'] = $v['context_id'];
                    $arr['d']['context_name'] = $v['context_name'];
                } else {
                    $arr['d']['context_id'] = "";
                    $arr['d']['context_name'] = '';
                }
                $arr['d']['sub_category_id'] = $v['sub_category_id'];
                $arr['d']['sub_category_name'] = $v['sub_category_name'];
                $arr_result[] = $arr['d'];
            }
        }
        $json['data'] = $arr_result;
        $this->response($json);
    }
    public function admissionnews_get($content_id = null, $limit = 50, $order = 'random') {
        $json = null;

        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
        }
        if (isset($_GET['f']) && !empty($_GET['f'])) {
            $f = trim($_GET['f']);
        }
        
        if (isset($_GET['limit']) && !empty($_GET['limit'])) {
            $limit = trim($_GET['limit']);
        }
        
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = trim($_GET['offset']);
        }
        
        if (isset($_GET['order']) && !empty($_GET['order'])) {
            $order = trim($_GET['order']);
        }
        
        $this->load->model('getRelates_model');

        $order = trim(strtolower($order));

        //$limit = null;
        //$offset = null;
        //$orderby = null;
        $qResult = $this->getRelates_model->center_admissionNews($content_id, $limit, $offset, $order, $f, $q, $arrFilter);
        //var_dump($qResult);

        $json['header']['title'] = 'center Version 2.0 AdmissionNews';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['short_detail'] =$v['short_detail'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = intval($v['viewcount']); //$this->trueplook->getViewNumber($v['content_id'], 21); 
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }
    public function exam_get($content_id = null, $limit = 20, $order = "random") {
        $json = null;
        $subject_id = null;
        $level_id = null;
        $context_id = null;

        if (isset($_GET['sid']) && !empty($_GET['sid'])) {
            $subject_id = trim($_GET['sid']);
        }
        if (isset($_GET['cid']) && !empty($_GET['cid'])) {
            $context_id = trim($_GET['cid']);
        }
        if (isset($_GET['lid']) && !empty($_GET['lid'])) {
            $level_id = trim($_GET['lid']);
        }

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }

        $this->load->model('getRelates_model');

        //$limit = null;
        //$offset = null;
        //$orderby = null;	// FORCE not order by ; for faster query
        $qResult = $this->getRelates_model->center_exam($content_id, $limit, $offset, $order, $subject_id, $level_id, $context_id);
        //var_dump($qResult);

        $json['header']['title'] = 'center Version 2.0 Examination';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }
		////tv program
    public function tvprogram_get($content_id = null, $limit = 50, $order = "random") {
        $json = null;
        $l1 = null;
        $l2 = null;

        if (isset($_GET['l1']) && !empty($_GET['l1'])) {
            $l1 = trim($_GET['l1']);
        }
        if (isset($_GET['l2']) && !empty($_GET['l2'])) {
            $l2 = trim($_GET['l2']);
        }
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
        }

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }

        $this->load->model('getRelates_model');

        //$limit = null;
        //$offset = null;
        //$orderby = null;
        $qResult = $this->getRelates_model->center_tvprogram($content_id, $limit, $offset, $order, $l1, $l2,$q);
        //var_dump($qResult);

        $json['header']['title'] = 'center Version 2.0 TV Program';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                //$arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['viewcount'] = $this->trueplook->getViewCenter($v['content_id'], 'tv_program', 'media');
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }
    public function tvprogramepisode_get($content_id = null, $limit = 50, $order = "random") {
        $json = null;
        $l1 = null;
        $l2 = null;

        if (isset($_GET['l1']) && !empty($_GET['l1'])) {
            $l1 = trim($_GET['l1']);
        }
        if (isset($_GET['l2']) && !empty($_GET['l2'])) {
            $l2 = trim($_GET['l2']);
        }

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }

        $this->load->model('getRelates_model');

        //$limit = null;
        //$offset = null;
        //$orderby = null;
        $qResult = $this->getRelates_model->center_tvprogram_episode($content_id, $limit, $offset, $order, $l1, $l2);
        //var_dump($qResult);

        $json['header']['title'] = 'center Version 2.0 TV Program';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                //$arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['viewcount'] = $this->trueplook->getViewCenter($v['content_child_id'], 'tv_program_episode', 'media');
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }
    public function knowledgeupsale_get($content_id = null, $limit = 50, $order = "random") {
        $json = null;
        $result1 = array();
        $result2 = array();

        $this->load->model('getRelates_model');


        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }
        $filter = array();
        $filter['contentType'] = "sourceOnly";

        $contentResult = $this->getRelates_model->getDetail_content($content_id, "mul_content");
        //var_dump($contentResult);exit();
        if (is_array($contentResult)) {
            foreach ($contentResult as $key => $v) {
                $subject_id = $v["mul_category_id"];
                $level_id = $v["mul_level_id"];
                $context_id = $v['context_id'];


                $result1 = $this->getRelates_model->center_content(null, 5, null, $order, $subject_id, $level_id, $context_id, $filter);
                if (empty($result1)) {
                    $result1 = $this->getRelates_model->center_content(null, 5, null, $order, null, $level_id, null, $filter);
                }
                if (empty($result1)) {
                    $result1 = $this->getRelates_model->center_content(null, 5, null, $order, $subject_id, null, null, $filter);
                }

                $result2 = $this->getRelates_model->center_tvprogram(null, 5, null, $order, $l1 = 1, $l2);
            }
        }
        if (empty($result1)) {
            $result1 = $this->getRelates_model->center_content(null, 5, null, 'last', null, null, null);
        }
        $qResult = array_merge($result1, $result2);

        $json['header']['title'] = 'center Version 2.0 Content';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }
    public function knowledgecrosssale_get($content_id = null, $limit = 50, $order = "random") {
        $json = null;
        $result1 = array();
        $result2 = array();

        $this->load->model('getRelates_model');

        $contentResult = $this->getRelates_model->getDetail_content($content_id, "mul_content");

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }
        $filter = array();
        $filter['contentType'] = "sourceOnly";

        //var_dump($contentResult);exit();
        if (is_array($contentResult)) {
            foreach ($contentResult as $key => $v) {
                $subject_id = $v["mul_category_id"];
                $level_id = $v["mul_level_id"];
                $context_id = $v['context_id'];


                $result1 = $this->getRelates_model->center_content(null, 3, null, $order, $subject_id = null, $level_id, $context_id = null, $filter);
                $result2 = $this->getRelates_model->center_content(null, 2, null, $order, $subject_id, $level_id = null, $context_id = null, $filter);
                $result3 = $this->getRelates_model->center_tvprogram(null, 1, null, $order, $l1 = 1, $l2);
                $result4 = $this->getRelates_model->center_tvprogram(null, 2, null, $order, $l1 = 2, $l2);
                $result5 = $this->getRelates_model->center_tvprogram(null, 1, null, $order, $l1 = 3, $l2);
            }
        }
        $qResult = array_merge($result1, $result2, $result3, $result4, $result5);

        $json['header']['title'] = 'center Version 2.0 Content';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }
        $json['data'] = $arr_result;
        $this->response($json);
    }
    public function newscamp_get($content_id = null, $limit = 50, $order = 'random') {
        $json = null;

        $arrFilter = array();

        $this->load->model('getRelates_model');

        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }

        //$limit = null;
        //$offset = null;
        //$orderby = null;
        $qResult = $this->getRelates_model->center_ams_newscamp($content_id, $limit, $offset, $order, $arrFilter);
        //var_dump($qResult);

        $json['header']['title'] = 'center Version 2.0 NewsCamp';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = $v['viewcount'];
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['short_detail'] = $v['short_detail'];
                $arr['d']['camp_date_start'] = $v['camp_date_start'];
                $arr['d']['camp_date_end'] = $v['camp_date_end'];
                $arr['d']['register_date_start'] = $v['register_date_start'];
                $arr['d']['register_date_end'] = $v['register_date_end'];
                $arr['d']['announce_date'] = $v['announce_date'];
                $arr_result[] = $arr['d'];
            }
        }


        $json['data'] = $arr_result;
        $this->response($json);
    }
	public function lessonplan_get($content_id = null) {
        $json = null;
        $subject_id = null;
        $level_id = null;
        $context_id = null;

        if (isset($_GET['sid']) && !empty($_GET['sid'])) {
            $subject_id = trim($_GET['sid']);
        }
        if (isset($_GET['cid']) && !empty($_GET['cid'])) {
            $context_id = trim($_GET['cid']);
        }
        if (isset($_GET['lid']) && !empty($_GET['lid'])) {
            $level_id = trim($_GET['lid']);
        }

        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
        }
        
        if (isset($_GET['limit']) && !empty($_GET['limit'])) {
            $limit = trim($_GET['limit']);
        }else{
			$limit = 20;
		}
        
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = trim($_GET['offset']);
        }
        
        if (isset($_GET['order']) && !empty($_GET['order'])) {
            $order = trim($_GET['order']);
        }
        
        $this->load->model('getRelates_model');

        $order = trim(strtolower($order));

        $arrFilter = array();
		$arrFilter['textSearch'] = $q;
		$arrFilter['mul_category_id'] = $subject_id;
		$arrFilter['mul_level_id'] = $level_id;
		$arrFilter['context_id'] = $context_id;
        $qResult = $this->getRelates_model->center_lessonplan($content_id, $limit, $offset, $order, $arrFilter);
        //var_dump($qResult);		

        $json['header']['title'] = 'center Version 2.0 LessonPlan';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $json['data'] = $qResult;
        $this->response($json);
    }
	public function lessonplandetail_get($content_id = null) {
		
		$this->load->model('getRelates_model');
		$qResult = $this->getRelates_model->center_lessonplan_detail($content_id);
		
		$json['header']['title'] = 'center Version 2.0 LessonPlan Detail';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $json['data'] = $qResult;
        //$this->tppymemcached->set($key, $json, 259200);
        $this->response($json);
	}
	public function cmsblog_get($content_id = null) {
        $json = null;
		
		if (isset($_GET['id']) && !empty($_GET['id'])) {
            $this_content_id = trim($_GET['id']);
        }
        if (isset($_GET['menu']) && !empty($_GET['menu'])) {
            $cat = trim($_GET['menu']);
        }
        if (isset($_GET['lt']) && !empty($_GET['lt'])) {
            $labeltag = trim($_GET['lt']);
        }

        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
        }
        
		//-- context start
		if (isset($_GET['sid']) && !empty($_GET['sid'])) {
            $sid = trim($_GET['sid']);
        }
		if (isset($_GET['lid']) && !empty($_GET['lid'])) {
            $lid = trim($_GET['lid']);
        }
		if (isset($_GET['ssid']) && !empty($_GET['ssid'])) {
            $ssid = trim($_GET['ssid']);
        }
		if (isset($_GET['cid']) && !empty($_GET['cid'])) {
            $cid = trim($_GET['cid']);
        }
		if (isset($_GET['mapcontext']) && !empty($_GET['mapcontext'])) {
            $mapcontext = trim($_GET['mapcontext']);
        }
		//-- context end
		
        if (isset($_GET['limit']) && !empty($_GET['limit'])) {
            $limit = trim($_GET['limit']);
        }else{
			$limit = 20;
		}
        
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = trim($_GET['offset']);
        }
        
        if (isset($_GET['order']) && !empty($_GET['order'])) {
            $order = trim($_GET['order']);
			$order = trim(strtolower($order));
        }
        
        $this->load->model('getRelates_model');


        $arrFilter = array();
		$arrFilter['textSearch'] = $q;
		$arrFilter['categoryList'] = $cat;
		$arrFilter['labeltagList']=$labeltag;
		$arrFilter['this_content_id'] = $this_content_id;
		if($mapcontext==1){
			$arrFilter['isMapContext'] = true;
			$arrFilter['knowledge_id'] = $ssid;
			$arrFilter['subject_id'] = $sid;
			$arrFilter['context_id'] = $cid;
			$arrFilter['level_id'] = $lid;
		}
        $qResult = $this->getRelates_model->get_cmsblogList($limit, $offset, $order, $arrFilter);
        //var_dump($qResult);		

        $json['header']['title'] = 'center Version 2.0 CMS Blog';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $json['data'] = $qResult;
        $this->response($json);
    }
	public function cmsblogdetail_get() {
		$content_id=@$this->input->get('content_id');
		if($content_id==Null){ 
            $json['header']['title'] = 'center Version 2.0 CMS Blog Detail';
        	$json['header']['status'] = 404;
        	$json['header']['message'] = 'Error 404 ไม่พบข้อมูลกรูณาระบุ content_id ';
        	$json['header']['description'] = 'Error 404';
        	$json['header']['remarks'] = 'HTTP GET';
       		$json['data']=false;
			$this->response($json);   
			Die();
		}
		$this->load->model('getRelates_model');
		$qResult = $this->getRelates_model->getDetail_cmsblog($content_id);
		$json['header']['title'] = 'center Version 2.0 CMS Blog Detail';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';

        $json['data'] = $qResult;
        $this->response($json);
	}
	public function cmsblogdetailrelate_get() {
		$content_id=@$this->input->get('content_id');
		if($content_id==Null){ //$content_id = null
            $json['header']['title'] = 'center Version 2.0 CMS Blog Detail';
        	$json['header']['status'] = 404;
        	$json['header']['message'] = 'ok';
        	$json['header']['description'] = 'Error 404 ไม่พบข้อมูลกรูณาระบุ content_id ';
        	$json['header']['remarks'] = 'HTTP GET';
       		$json['data']=false;
			$this->response($json);   
			Die();
		}
		$json = null;
		$json['header']['title'] = 'center Version 2.0 CMS Blog';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelates_model');
		$qResult = $this->getRelates_model->center_cmsblog_forDetail($content_id);
        $json['data'] = $qResult;
        $this->response($json);
	}
	public function cmsblogcategory_get() {
        $json = null;
		
		$cat = "";
        if (isset($_GET['menu']) && !empty($_GET['menu'])) {
            $cat = trim($_GET['menu']);
        }
		
		$json['header']['title'] = 'center Version 2.0 CMS Blog';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelates_model');
		$qResult = $this->getRelates_model->get_cmsblogCategoryInfo($cat);
		$json['count'] = count($qResult);
        $json['data'] = $qResult;
        $this->response($json);
	}
	public function cmsblogcategorycode_get() {
	
		$content_id=@$this->input->get('content_id');
		$category_id=@$this->input->get('category_id');
		//?cmsblog_id=0&category_id=50192
		//$cmsblog_id=null , $category_id = null
		/*
		if($content_id==Null){ //$cmsblog_id=null , $category_id = null
            $json['header']['title'] = 'center Version 2.0 CMS Blog Detail';
        	$json['header']['status'] = 404;
        	$json['header']['message'] = 'Error 404';
        	$json['header']['description'] = 'Error 404 ไม่พบข้อมูลกรูณาระบุ content_id และ category_id';
        	$json['header']['remarks'] = 'HTTP GET';
       		$json['data']=false;
			$this->response($json);   
			Die();
		}
		*/
		$json = null;
		$json['header']['title'] = 'center Version 2.0 CMS Blog';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelates_model');
		if($category_id==Null){
			$qResult = $this->getRelates_model->get_cmsblogCategoryCode($cmsblog_id);
		}else{
			$qResult = $this->getRelates_model->get_cmsblogCategoryCode($cmsblog_id,$category_id);
		}
        $json['data'] = $qResult;
        $this->response($json);
	}
	public function cmsblogcountcontent_get($category_id = null,$zone_id=1) {
        $json = null;
		$json['header']['title'] = 'center Version 2.0 CMS Blog Count';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelates_model');
		
		$filter['menu'] = $category_id;
		$filter['zone_id'] = $zone_id;
		$qResult = $this->getRelates_model->get_cmsblogCountContent($filter);
        $json['data'] = $qResult;
        $this->response($json);
	}	
	public function cmsbloghistory_get() {
		// วันนี้เมื่อวันวาน History & Future
		$dateNo=@$this->input->get('dateNo');
		$monthNo=@$this->input->get('monthNo');
		$yearNo=@$this->input->get('yearNo'); 
		//$dateNo=null,$monthNo=null,$yearNo=null
		$json = null; 
		$arrFilter = array();
		
		if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
			$arrFilter['q'] = $q;
        }
        
        if (isset($_GET['limit']) && !empty($_GET['limit'])) {
            $limit = trim($_GET['limit']);
			
        }else{
			$limit = 20;
		}
		$arrFilter['limit'] = $limit;
		
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = trim($_GET['offset']);
			$arrFilter['offset'] = $offset;
        }
		
		$json['header']['title'] = 'center Version 2.0 CMS Blog History';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelates_model');
		$qResult = $this->getRelates_model->get_cmsblog_history($dateNo, $monthNo, $yearNo, $arrFilter);
        //var_dump($qResult);
        $json['data'] = $qResult;
        $this->response($json);
	}
	public function cmsblogfuture_get($dateNo=null,$monthNo=null,$yearNo=null) {
		$json = null; 
		$arrFilter = array();
		
		if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($_GET['q']);
			$arrFilter['q'] = $q;
        }
        
        if (isset($_GET['limit']) && !empty($_GET['limit'])) {
            $limit = trim($_GET['limit']);
			
        }else{
			$limit = 20;
		}
		$arrFilter['limit'] = $limit;
		
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = trim($_GET['offset']);
			$arrFilter['offset'] = $offset;
        }		
		
		$json['header']['title'] = 'center Version 2.0 CMS Blog Future';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelates_model');
		$qResult = $this->getRelates_model->get_cmsblog_future($dateNo, $monthNo, $yearNo, $arrFilter);
        //var_dump($qResult);
        $json['data'] = $qResult;
        $this->response($json);
	}
	public function bloggersummary_get($sumType = 'daily' ,$zone_id = 1, $category_id = null) {
        // Blog
        $json = null;
		$json['header']['title'] = 'center Version 2.0 CMS Blog Count';
        $json['header']['status'] = '200';
        $json['header']['description'] = 'ok';
		$this->load->model('getRelates_model');
		
		$filter['sumType'] = $sumType;
		//$filter['lastXdays'] = 50;
		$filter['zone_id'] = $zone_id;
		$filter['menu'] = $category_id;
		$qResult = $this->getRelates_model->get_blogger_summary($filter);
        $json['data'] = $qResult;
        $this->response($json);
	}
	public function blogcontentbyuser_get() {
		// Blog User
		$user_id=@$this->get('user_id');
			if($user_id==''){$user_id=Null;}
			//$this->load->model('blog/blogmodel');
			//$data=$this->blogmodel->get_blogger_status_by_blogger($user_id);
			
			$this->load->model('Center_model');
			$data=$this->Center_model->get_blogger_status_by_blogger($user_id);
			
			if($data){
			  $data['user_password']=Null;
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '2.0',
									'description' => 'List data by user',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}else{
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '1.0 and 2.0',
									'description' => 'List data by user',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}
	  } 
	public function blogcontentbyuserlogin_get() { //&type=type&q=keyword&s=sort&zone_id=1
		 $type=@$this->input->get('type');
		 $user_id=@$this->input->get('user_id');
		 $keyword=@$this->input->get('q');
		 if($keyword==Null){$keyword=@$this->input->get('keyword');}
		 $sort=@$this->input->get('s') == 'view' ? 'cd.view_count DESC' : 'cd.idx DESC';
		 if($sort==Null){$sort=@$this->input->get('sort');}
		 $zone_id=@$this->input->get('zone_id');
			//$this->load->model('blog/blogmodelapi');
			//$data=$this->blogmodelapi->get_blogger_status_by_blogger_login($type, $user_id, $keyword, $sort, ($user_id == $this->user_id ? 11 : 12),0,$zone_id=0);
			$this->load->model('Center_model');
			$data=$this->Center_model->get_blogger_status_by_blogger_login($type, $user_id, $keyword, $sort, ($user_id == $this->user_id ? 11 : 12),0,$zone_id=0);
			
			if($data){
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '1.0 and 2.0',
									'description' => 'List data by user',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}else{
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '2.0',
									'description' => 'ไม่พบข้อมูล',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}
	  } 
	public function contentlistbyuser_get() { //&type=type&q=keyword&s=sort&zone_id=1
         $type=@$this->input->get('type');
		 $user_id=@$this->input->get('user_id');
		 $keyword=@$this->input->get('q');
		 if($keyword==Null){$keyword=@$this->input->get('keyword');}
		 $sort=@$this->input->get('s') == 'view' ? 'cd.view_count DESC' : 'cd.idx DESC';
		 if($sort==Null){$sort=@$this->input->get('sort');}
		 $zone_id=@$this->input->get('zone_id');
			//$this->load->model('blog/blogmodel');
			//$data=$this->blogmodel->get_content_list_by_blogger($type, $user_id, $keyword, $sort, ($user_id == $this->user_id ? 11 : 12),0,$zone_id=0);
			
			$this->load->model('Center_model');
			$data=$this->Center_model->get_content_list_by_blogger($type, $user_id, $keyword, $sort, ($user_id == $this->user_id ? 11 : 12),0,$zone_id=0);
			
			
			if($data){
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '1.0 and 2.0',
									'description' => 'List data by user',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}else{
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '2.0',
									'description' => 'ไม่พบข้อมูล',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}
	  }
	public function contentlistbyuserenable_get() { //&type=type&q=keyword&s=sort&zone_id=1
		 $type=@$this->input->get('type');
		 $user_id=@$this->input->get('user_id');
		 $keyword=@$this->input->get('q');
		 if($keyword==Null){$keyword=@$this->input->get('keyword');}
		 $sort=@$this->input->get('s') == 'view' ? 'cd.view_count DESC' : 'cd.idx DESC';
		 if($sort==Null){$sort=@$this->input->get('sort');}
		 $zone_id=@$this->input->get('zone_id');
		 
			//$this->load->model('blog/blogmodelapi');
			//$data=$this->blogmodelapi->get_content_list_by_blogger_enable($type, $user_id, $keyword, $sort, ($user_id == $this->user_id ? 11 : 12),0,$zone_id=0);
			
			$this->load->model('Center_model');
			$data=$this->Center_model->get_content_list_by_blogger_enable($type, $user_id, $keyword, $sort, ($user_id == $this->user_id ? 11 : 12),0,$zone_id=0);
			
			if($data){
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '1.0 and 2.0',
									'description' => 'List data by user',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}else{
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '2.0',
									'description' => 'ไม่พบข้อมูล',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}
	  }
	public function contentlistbyuserenable2_get() { //&type=type&q=keyword&s=sort&zone_id=1
		 $type=@$this->input->get('type');
		 $user_id=@$this->input->get('user_id');
		 $keyword=@$this->input->get('q');
		 if($keyword==Null){$keyword=@$this->input->get('keyword');}
		 $sort=@$this->input->get('s') == 'view' ? 'cd.view_count DESC' : 'cd.idx DESC';
		 if($sort==Null){$sort=@$this->input->get('sort');}
		 $zone_id=@$this->input->get('zone_id');
			$this->load->model('blog/blogmodel');
			$data=$this->blogmodel->get_content_list_by_blogger2($type, $user_id, $keyword, $sort, ($user_id == $this->user_id ? 11 : 12),0,$zone_id=0);
			if($data){
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '1.0 and 2.0',
									'description' => 'List data by user',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}else{
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '2.0',
									'description' => 'ไม่พบข้อมูล',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}
	  }
	public function contentlistbyuserecount_get() { 
		 $type='';
		 $user_id=@$this->input->get('user_id');
		 $keyword='';
		 $sort='cd.idx DESC';
		 if($sort==Null){$sort=@$this->input->get('sort');}
		 $zone_id=@$this->input->get('zone_id');
			//$this->load->model('blog/blogmodelapi');
			//$data=$this->blogmodelapi->get_content_list_by_blogger_count($type,$user_id,$keyword,$sort,$user_id,$zone_id=0);
			
			$this->load->model('Center_model');
			$data=$this->Center_model->get_content_list_by_blogger_count($type,$user_id,$keyword,$sort,$user_id,$zone_id=0);
			
			
			
			if($data){
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '1.0 and 2.0',
									'description' => 'List data by user',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}else{
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '2.0',
									'description' => 'ไม่พบข้อมูล',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}
	  }
	public function editorpicks_get() {
		    // editorpicks form knowledge module  
			$menu_id=@$this->get('menu_id', FALSE, 1);
			$limit=@$this->get('limit', FALSE, 4); 
			$offset=@$this->get('offset', FALSE, 0);
			$this->load->model('knowledge/cmsblogmodel');
			$data['editorpicks']=$this->cmsblogmodel->cmsblog_get_editorpicks_list($menu_id,$limit,$offset);
			if($data){
			$this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '2.0',
									'description' => 'List data by user',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}else{
			  $this->response(array('response'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '2.0',
									'description' => 'List data by user',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>404), 
									'data'=> 'Null'),404);
			}
	  }
	public function cms_get($content_id=null,$limit=50,$order='desc',$catid=Null) {
		//$limit = null;
        //$offset = null;
        //$orderby = null;
		
		if ($content_id==Null){$content_id=Null;}
		if ($limit==Null){$content_id=Null;}
		if ($order==Null){$order = 'random';}
		if ($catid==Null){$catid = null;}
        if (isset($_GET['catid']) && !empty($_GET['catid'])) {
            $catid = trim($_GET['catid']);
        }
        
		
        $order = trim(strtolower($order));
        if ($order != "last") {
            $order = "rand";
        }
		//$this->load->model('getRelates_model');
        //$qResult = $this->getRelates_model->getRelate_cms($content_id, $limit, $offset, $order, $catid);
		
		$this->load->model('Getrelatev2_model');
		$qResult = $this->Getrelatev2_model->getRelate_cms($content_id, $limit, $offset, $order, $catid);
        //var_dump($qResult);

        $arr_result = array();
        if (is_array($qResult)) {
            foreach ($qResult as $key => $v) {
                $arr = array();
                //$arr['d']['id'] = $v['mul_source_id'];
                $arr['d']['content_type'] = $v['type'];
                $arr['d']['content_id'] = $v['content_id'];
                $arr['d']['content_child_id'] = $v['content_child_id'];
                $arr['d']['topic'] = $v['title'];
                $arr['d']['url'] = $v['url'];
                $arr['d']['thumbnail'] = $v['thumbnail'];
                $arr['d']['viewcount'] = intval($v['viewcount']); //$this->trueplook->getViewNumber($v['content_id'], 21); 
                $arr['d']['addDateTime'] = $v['addDateTime'];
                $arr['d']['addBy'] = $v['addBy'];
                $arr['d']['mul_level_id'] = $v['mul_level_id'];
                $arr['d']['mul_level_name'] = $v['mul_level_name'];
                $arr['d']['mul_category_id'] = $v['mul_category_id'];
                $arr['d']['mul_category_name'] = $v['mul_category_name'];
                $arr['d']['context_id'] = $v['context_id'];
                $arr['d']['context_name'] = $v['context_name'];
                $arr_result[] = $arr['d'];
            }
        }


			$data= $arr_result;
			$count=count($data);
			if($data){
			$this->response(array('header'=>array(
									'title'=>'getRelate CMS',
									'count'=>$count,
									'message'=>'success',
									'status'=>true, 
									'version ' => '2.0',
									'description' => 'List data',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}else{
			  $this->response(array('header'=>array(
									'status'=>true, 
									'message'=>'success',
									'version ' => '2.0',
									'description' => 'List data',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>404), 
									'data'=> 'Null'),404);
			}
	  }
	public function cmsbloglist_get() {
	    $limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$order = $this->input->get('order');
		$textSearch = $this->input->get('keyword');
		$type = $this->input->get('type');
		if ($type==Null ||$type!=='series'){$type = Null;}else if($type=='series'){$type='and cms.parent_idx > 0';}
		if ($limit==Null){$limit = 50;}
		if ($offset==Null){$offset = null;}
		if ($order==Null){$order=null;}
		if ($textSearch==Null){$textSearch=Null;}
		$user_id_login=@$this->input->get('user_id_login');
		 if ($user_id_login==Null){
				 $user_id=@$this->input->get('user_id');  
				 $arrFilter=array('record_status' => '0,1','user_id' => $user_id,'textSearch'=>$textSearch,'moreCriteria'=>$type);
			 }else if ($user_id_login!==Null){
				 $user_id=$user_id_login;
				 $arrFilter=array('record_status' => '-1,0,1','user_id' => $user_id,'textSearch'=>$textSearch,'moreCriteria'=>$type);
			 }else{
			  $arrFilter=array('record_status' => '1','textSearch'=>$textSearch,'moreCriteria'=>$type);
			 }
		 
		 
		// ?limit=&offset=&order=last&keyword=&type=&user_id_login=219779
		// ?limit=&offset=&order=last&keyword=&type=&user_id=219779
		// ?limit=&offset=&order=last&keyword=&type=&user_id=
		// api/center/cmsbloglist?limit=2&offset=&order=view_asc&keyword=ปรากฏการณ์   
	    // limit=จำนวนทั้งหมดที่ต้องการแสดง  offset=ช่วงที่ต้องการ  order=ลำดับการดึงค่า  keyword=คำค้นหา  user_id=idผู้ใช้ ไม่ได้ login user_id_login=idผู้ใช้ ไม่ได้ login
		// cmsbloglist?limit=10&offset=&order=view_asc&keyword=ปรากฏการณ์
		# 	api/center/cmsbloglist?limit=200&offset=&order=last&keyword=&type=series&user_id_login=219779
		#	api/center/cmsbloglist?limit=200&offset=&order=last&keyword=&type=series&user_id_login=510325
		#  echo '<pre> $arrFilter=>'; print_r($arrFilter); echo '</pre>';  die();
		
		 $this->load->model('getRelates_model');
         $qResult = $this->getRelates_model->get_cmsblogList($limit,$offset,$order,$arrFilter);
		 
		 //$this->load->model('Getrelatev2_model');
         //$qResult = $this->Getrelatev2_model->get_cmsblogList($limit,$offset,$order);
		 //var_dump($qResult);
			$data= $qResult;
			$count=count($data);
			if($data){
			$this->response(array('header'=>array(
									'title'=>'cmsblogList',
									'model'=>'api/model/getRelates_model',
									'function'=>'get_cmsblogList',
									'version ' => '2.0',
									'description' => 'List data form cmsblog',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'count'=>$count,
									'message'=>'Success',
									'status'=>true, 
									'code'=>200), 
									'data'=> $data),200);
			}else{
			  $this->response(array('header'=>array(
									'status'=>true, 
									'message'=>'Success',
									'version ' => '2.0',
									'description' => 'List data',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>404), 
									'data'=> 'Null'),404);
			}
	  }
	public function cmsbloglistv2_get() {
	    $limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$order = $this->input->get('order');
		$textSearch = $this->input->get('keyword');
		$type = $this->input->get('type');
		if ($type==Null ||$type!=='series'){$type = Null;}else if($type=='series'){$type='and cms.parent_idx > 0';}
		if ($limit==Null){$limit = 50;}
		if ($offset==Null){$offset = null;}
		if ($order==Null){$order=null;}
		if ($textSearch==Null){$textSearch=Null;}
		$user_id_login=@$this->input->get('user_id_login');
		 if ($user_id_login==Null){
				 $user_id=@$this->input->get('user_id');  
				 $arrFilter=array('record_status' => '0,1','user_id' => $user_id,'textSearch'=>$textSearch,'moreCriteria'=>$type);
			 }else if ($user_id_login!==Null){
				 $user_id=$user_id_login;
				 $arrFilter=array('record_status' => '-1,0,1','user_id' => $user_id,'textSearch'=>$textSearch,'moreCriteria'=>$type);
			 }else{
			  $arrFilter=array('record_status' => '1','textSearch'=>$textSearch,'moreCriteria'=>$type);
			 }
		 
		 
		// ?limit=&offset=&order=last&keyword=&type=&user_id_login=219779
		// ?limit=&offset=&order=last&keyword=&type=&user_id=219779
		// ?limit=&offset=&order=last&keyword=&type=&user_id=
		// api/center/cmsbloglist?limit=2&offset=&order=view_asc&keyword=ปรากฏการณ์   
	    // limit=จำนวนทั้งหมดที่ต้องการแสดง  offset=ช่วงที่ต้องการ  order=ลำดับการดึงค่า  keyword=คำค้นหา  user_id=idผู้ใช้ ไม่ได้ login user_id_login=idผู้ใช้ ไม่ได้ login
		// cmsbloglist?limit=10&offset=&order=view_asc&keyword=ปรากฏการณ์
		# 	api/center/cmsbloglist?limit=200&offset=&order=last&keyword=&type=series&user_id_login=219779
		#	api/center/cmsbloglist?limit=200&offset=&order=last&keyword=&type=series&user_id_login=510325
		#  echo '<pre> $arrFilter=>'; print_r($arrFilter); echo '</pre>';  die();
		
		 $this->load->model('getRelates_model');
         $qResult = $this->getRelates_model->get_cmsblogListv2($limit,$offset,$order,$arrFilter);
		 //echo '<pre> qResult=>'; print_r($qResult); echo '</pre>';  die();
		 //$this->load->model('Getrelatev2_model');
         //$qResult = $this->Getrelatev2_model->get_cmsblogList($limit,$offset,$order);
		 //var_dump($qResult);
			$data= $qResult;
			$count=count($data);
			if($data){
			$this->response(array('header'=>array(
									'title'=>'cmsblogList',
									'model'=>'api/model/getRelates_model',
									'function'=>'get_cmsblogList',
									'version ' => '2.0',
									'description' => 'List data form cmsblog',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'count'=>$count,
									'message'=>'Success',
									'status'=>true, 
									'code'=>200), 
									'data'=> $data),200);
			}else{
			  $this->response(array('header'=>array(
									'status'=>true, 
									'message'=>'Error',
									'version ' => '2.0',
									'description' => 'List data',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>404), 
									'data'=> 'Null'),404);
			}
	  }
  	public function cmsblogview_get() {
			$idx = @$this->input->get('idx');
			 $this->load->model('Center_model');
			 $qResult = $this->Center_model->get_content_id_count($idx);
			 //echo '<pre> $qResult=>'; print_r($qResult); echo '</pre>';  die();
			  $view_count_old=(int)$qResult['view_count_old'];
			  $view_count=(int)$qResult['view_count'];
				$data=array('view_count_old'=>$view_count_old,'view_count'=>$view_count);
				$count=count($data);
				if($data){
				$this->response(array('header'=>array(
										'title'=>'cmsblogview',
										'model'=>'api/model/Center_model/get_content_id_count',
										'function'=>'cmsblogview_get',
										'version ' => '2.0',
										'description' => 'List data count view',
										'message' => 'REST DONE',
										'remarks' => 'HTTP GET',
										'count'=>$count,
										'message'=>'Success',
										'status'=>true, 
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'cmsblogview',
										'model'=>'api/model/Center_model/get_content_id_count',
										'function'=>'cmsblogview_get',
										'status'=>true, 
										'message'=>'Success',
										'version ' => '2.0',
										'description' => 'List data count view',
										'message' => 'REST DONE',
										'remarks' => 'HTTP GET',
										'code'=>404), 
										'data'=> 'Null'),404);
				}
		  }
    public function examinationgraphbycourse_get() {  
	////////////////////////////////
	$this->response(array('header'=>array(
										'title'=>'examination graph by course',
										'model'=>'api/Examination_center_model->examinationgraph_get',
										'function'=>'examinationgraphbycourse_get',
										'version ' => '2.0.1',
										'description' => 'Null',
										'message' => 'REST Test',
										'remarks' => 'HTTP GET',
										'message'=>'Success',
										'status'=>true, 
										'code'=>503), 
										'data'=> $data),503);
	Die();
	//////////////////////////////////
  
  
  
  
			// api/center/examinationgraphbycourse?exam_id=370&start=15/01/22&end=2017-01-23
			$exam_id=@$this->input->get('exam_id');
			$startdate=@$this->input->get('startdate');
			$enddate=@$this->input->get('enddate');
			
			//// set default timezone

			if($exam_id==Null){$exam_id=0;}
			/*
			if($startdate==Null){$startdate=date('Y-m-d', strtotime("-1 days"));}
			if($enddate==Null){$enddate=date('Y-m-d');}
			//define date and time
			//$startdate=strtotime($startdate);
			//$enddate=strtotime($enddate);
			// $startdate=>1262278800  $enddate=>1485104400
			*/
			//examinationgraphbycourse?exam_id=500&startdate=1420045200&enddate=1485104400
			if($startdate==Null){$startdate1=date('Y-m-d', strtotime("-1 days"));
				$startdate=strtotime($startdate1);
			}if($enddate==Null){$enddate1=date('Y-m-d');
				$enddate=strtotime($enddate1);
			}
			//
			 //$startdate=strtotime('2015-01-01'); $enddate=strtotime('2017-01-23');
			 //echo '$startdate=>'.$startdate.' $enddate=>'.$enddate; die();
			$this->load->model('Examination_center_model');
			$data['listlog']=$this->Examination_center_model->get_examinationgraphbycourse($exam_id,$startdate,$enddate);
			 //var_dump($data);Die();
			$data['exam']=$this->Examination_center_model->get_examinationgraphbycourse_row($exam_id);
			// var_dump($datarow);Die();
			$data['count']=$this->Examination_center_model->get_examinationgraphbycourse_count($exam_id);
			$data['score']=$this->Examination_center_model->get_examinationgraphbycourse_scoreallanswer($exam_id,$startdate,$enddate);
			$data['startdate']=$start;
			$data['enddate']=$end;
			$data['exam_id']=$exam_id;
			 //  var_dump($datarow);Die();
			 // echo '<pre>data=>';print_r($data); echo '</pre>'; Die();	

			 $count=count($data['listlog']);
			 if($data){
				$this->response(array('header'=>array(
										'title'=>'examination graph by course',
										'model'=>'api/Examination_center_model->examinationgraph_get',
										'function'=>'examinationgraphbycourse_get',
										'version ' => '2.0',
										'description' => 'List data',
										'message' => 'REST DONE',
										'remarks' => 'HTTP GET',
										'message'=>'Success',
										'status'=>true,
										'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'examination graph by course',
										'model'=>'api/Examination_center_model->examinationgraph_get',
										'function'=>'examinationgraphbycourse_get',
										'version ' => '2.0',
										'description' => 'Null',
										'message' => 'REST Error 404',
										'remarks' => 'HTTP GET',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
		} 		//////////
    /////////*************Examination_center END*************///////////
    public function profilemember_get() {
			$user_id=@$this->input->get('user_id');
			$this->load->model('member/member_model');
			$data1 = $this->member_model->get_profile_by_userid($user_id, TRUE);
			$data=$data1['user_username']='Null';
			$data=$data1['user_password']='Null';
			$data=$data1;
			 //var_dump($data);Die();
			 $count=count($data);
			 if($data){
				$this->response(array('header'=>array(
										'title'=>'profilemember',
										'message'=>'Success',
										'status'=>true,
										'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'profilemember',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
		} 
    /////////////	Webboard
	public function webboardsubjectpost_post() {  
		$this->load->library('session');
		$this->load->library('TPPY_Oauth');
	     //$user_session=$this->session->userdata('user_session');
		 //echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>';  Die();
		 // Oauth zone
        $token=@$this->input->post('token');
		$title=@$this->input->post('title');
        $description_long=@$this->input->post('description_long');
        $wb_category_id=@$this->input->post('wb_category_id');

        if($wb_category_id==Null){
		 $wb_category_id='59';	
		}

		/*
		echo '<pre> $token=>'; print_r($token); echo '</pre>'; 
		echo '<pre> $title=>'; print_r($title); echo '</pre>'; 
		echo '<pre> $description_long=>'; print_r($description_long); echo '</pre>'; Die();
		 
		
		*/
		
		if($token==Null){
				$this->response(array('header'=>array(
											'status'=>false,
											'message'=>'token required',
											'code'=>400,
											),
											'data'=> Null),400);
											Die();
			}
        if ($token!==NULL) {
            $my=$this->me = $this->tppy_oauth->parse_token($token);
			 //echo '<pre>  $my=>'; print_r($my); echo '</pre>'; Die();
		    $user_session=$this->session->set_userdata($my);
			$user_id=$my->user_id;
		    $member_id=$my->member_id;	
			$this->load->model('api/centermobile_model', 'centermobile_model');
			$datauser=$this->centermobile_model->get_memberdata($user_id);
			if($member_id==Null){
			$member_id=$datauser['member_id'];
			}
			/*
			 echo '<pre>  $datauser=>'; print_r($datauser); echo '</pre>';
			 echo '<pre>  $user_session=>'; print_r($user_session); echo '</pre>';
			 echo '<pre>  $user_id=>'; print_r($user_id); echo '</pre>';
			 echo '<pre>  $member_id=>'; print_r($member_id); echo '</pre>';
		     echo '<pre>  $my=>'; print_r($my); echo '</pre>'; Die();
			*/
		
        }elseif ($this->session->userdata('user_session') != NULL && $this->session->userdata('user_session') != '') {
            $my=$this->me = $this->session->userdata('user_session');
			$user_session=$this->session->userdata('user_session');
			$user_id=$user_session->user_id;
		    $member_id=$user_session->member_id;
        }

		if($user_id==Null || $member_id==Null){
				$this->response(array('header'=>array(
											'status'=>false,
											'message'=>'Error user_id or member_id is null',
											'code'=>200,
											'description' => 'Error user_id or member_id is nul',
											'remarks' => 'HTTP POST',
											),
											'data'=> Null),200);
											Die();
			}
			
			
		    //echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>';  
		    //echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>'; Die(); 
			//echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>'; Die();
				
				
		        $insert['wb_subject'] = $this->input->post('title');
                $insert['wb_detail'] = $this->input->post('description_long');
                $insert['flag_recv_email'] = 1;//$this->input->post('create_flag_recv_email') == TRUE ? 1 : 0;
                $insert['wb_status'] = 1;//$this->input->post('record_status') == TRUE ? 1 : 0;
				$insert['member_id'] = $member_id;
                $insert['user_id'] = $user_id;
                $insert['add_date'] = date("Y-m-d H:i:s");
                $insert['last_update_date'] = date("Y-m-d H:i:s");
                $insert['wb_category_id'] = $wb_category_id;
				//echo '<pre> $insert=>'; print_r($insert); echo '</pre>'; Die();
		
		
		// Webboard 
        $this->load->model('admissions/webboard_models', 'am_webboardapi_models');
        $this->load->model('api/webboardapi_model', 'api_webboardapi_models');
		$data['id']=$this->am_webboardapi_models->create_Webboard($insert);
		$id=$data['id'];
		//http://www.trueplookpanya.com/api/center/topicdetail?topicid=18094
		$data['urlredirec']=base_url('api/center/topicdetail?topicid='.$id);
		$data['urlredirec2']=base_url('mobile2/api/admission/webboardpostdetail?post_id='.$id);
		if($data){

					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'title'=>'Module admission mobile webboard  webboardsubjectpost',
											'model'=>'admissions/webboardapi_models',
											'function'=>'webboardsubjectpost_post',
											'version ' => '2.0',
											'description' => 'webboardsubjectpost_post',
											'remarks' => 'HTTP GET post_id',
											'count'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'title'=>'Module admission mobile webboard  webboardsubjectpost',
											'model'=>'admissions/webboardapi_models',
											'function'=>'webboardsubjectpost_post',
											'version ' => '2.0',
											'description' => 'webboardsubjectpost_post',
											'remarks' => 'HTTP GET post_id',
											'count'=>0,),
											'data'=> Null),200);
					}
	 }
	//////////POST reply webboard
	public function webboardreplypost_post() {   
		 //$user_session=$this->session->userdata('user_session'); echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>';  Die();
		 // Oauth zone
        $this->load->library('session');
		$this->load->library('TPPY_Oauth');
	     //$user_session=$this->session->userdata('user_session');
		 //echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>';  Die();
		 // Oauth zone
		//$reply_sticker=@$this->input->post('reply_sticker'); // =reply_sticker
		// URL API http://www.trueplookpanya.com/api/center/webboardreplypost
        $token=@$this->input->post('token');
		$comment=@$this->input->post('comment');
        $post_id=@$this->input->post('post_id');
        $reply_type=@$this->input->post('reply_type'); // ( 0 = text , 1 = sticker)
        $sticker=@$this->input->post('sticker');
        $sticker_id=@$this->input->post('sticker_id'); // if(sticker==1){ รับค่า  sticker_id }  
        $sticker_id = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $sticker_id);
        //$sticker_id=json_encode($sticker_id);
        
        // $sticker_id= {"sticker_list_id":"11","sticker_icon_id":"7"}
      
		/*
		 ขาดการรับตัวแปร reply_sticker (เก็บเป็น full path url)
		// http:/static.trueplookpanya.com/trueplookpanya/sticker/ชือสติกเกอร์.png
		 ขาดการรับตัวแปร reply_type ( 0 = text , 1 = sticker)
		
		echo '<pre> $token=>'; print_r($token); echo '</pre>'; 
		echo '<pre> $title=>'; print_r($title); echo '</pre>'; 
		echo '<pre> $description_long=>'; print_r($description_long); echo '</pre>'; Die();
		*/
		
		if($token==Null){
				$this->response(array('header'=>array(
											'status'=>false,
											'message'=>'token required',
											'code'=>400,
											),'data'=> Null),400);
											Die();
			}
        if ($token!==NULL) {
            $my=$this->me = $this->tppy_oauth->parse_token($token);
			 //echo '<pre>  $my=>'; print_r($my); echo '</pre>'; Die();
		    $user_session=$this->session->set_userdata($my);
			$user_id=$my->user_id;
		    $member_id=$my->member_id;	
			//$this->load->model('api/public_model', 'public_model');
			$datauser=$this->public_model->get_memberdata($user_id);
			if($member_id==Null){
			$member_id=$datauser['member_id'];
			}
			/*
			 echo '<pre>  $datauser=>'; print_r($datauser); echo '</pre>';
			 echo '<pre>  $user_session=>'; print_r($user_session); echo '</pre>';
			 echo '<pre>  $user_id=>'; print_r($user_id); echo '</pre>';
			 echo '<pre>  $member_id=>'; print_r($member_id); echo '</pre>';
		     echo '<pre>  $my=>'; print_r($my); echo '</pre>'; Die();
			*/
		
        }elseif ($this->session->userdata('user_session') != NULL && $this->session->userdata('user_session') != '') {
            $my=$this->me = $this->session->userdata('user_session');
			$user_session=$this->session->userdata('user_session');
			$user_id=$user_session->user_id;
		    $member_id=$user_session->member_id;
        }

		if($user_id==Null || $member_id==Null){
				$this->response(array('header'=>array(
											'status'=>false,
											'message'=>'Error user_id or member_id is null',
											'code'=>200,
											),
											'data'=> Null),200);
											Die();
			}
		    //echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>';  
		    //echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>'; Die(); 
			//echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>'; Die();
				
		            $comment = $this->input->post('comment');
					$comment = $this->trueplook->convert_plainText2HTML($comment);
					
					if($reply_type==1){
						#$stickerarray='{"type":"sticker","sticker_id":"'.$sticker_id.'"}';
						$array = $sticker_id;
						
						/*
						array("type" => "sticker",
									    "sticker_id" => 1); 
						*/
						
						$stickerarray=json_encode($array, JSON_PRETTY_PRINT);
						$insert['reply_detail'] = $sticker;
					}else{
						$insert['reply_detail'] = $comment;
					}
                    $insert['wb_post_id'] = $this->input->post('post_id');
                    $insert['member_id'] = $member_id;
                    $insert['user_id'] = $user_id;
                    $insert['reply_status'] = 1;
                    $insert['reply_datetime'] = date("Y-m-d H:i:s");
                    //echo '<pre> $insert=>'; print_r($insert); echo '</pre>'; Die();
                    
                    #########################
                    # $insert['reply_sticker'] = $reply_sticker;
        			# $insert['reply_type'] = $reply_type;
                    ######################### 
                    
		// Webboard  
        $this->load->model('admissions/webboard_models', 'am_webboardapi_models');
        //$this->load->model('api/webboardapi_model', 'api_webboardapi_models');
        #echo '<pre>  $sticker=>'; print_r($sticker); echo '</pre>';
        #echo '<pre>  $insert=>'; print_r($insert); echo '</pre>'; Die();
        //$data = $this->public_model->add_Comment($insert);
	    $data = $this->am_webboardapi_models->add_Comment($insert);
		//$data=$this->centermobile_model->add_Comment_insert($insert);
		
		if($data){
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>$count,),
											'data'=> $data),200);
										
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>0,),
											'data'=> Null),200);
					}
	 
	} 
	//////////PIN
	public function topicdetail_get() {

	$token=@$this->input->get('token');
	// $user_session=$this->session->userdata('user_session');
	// echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>';  Die();
	$this->load->library('session');
 	if ($token==NULL) {
 			$islikepublic='false';
 		}else{
 			$islikepublic=Null;
 			$this->load->library('TPPY_Oauth');
            $my=$this->me = $this->tppy_oauth->parse_token($token);
			//echo '<pre>  $my=>'; print_r($my); echo '</pre>'; Die();
		    $user_session=$this->session->set_userdata($my);
			$user_id=$my->user_id;
		    $member_id=$my->member_id;	
			#$this->load->model('api/centermobile_model', 'centermobile_model');
			#$datauser=$this->centermobile_model->get_memberdata($user_id);
			$datauser=$this->public_model->get_memberdata($user_id);
		}if($member_id==Null){
			$member_id=$datauser['member_id'];
			}
       
		$key=@$this->input->get('key');  
		if($key==Null){$key='16';}
	    $categoryid=@$this->input->get('category_id');  
			if($categoryid==Null){$categoryid=Null;}
		$room=@$this->input->get('room');
			if($room==Null){$room=Null;}
		$topicSearch=@$this->input->get('topicSearch');
			if($topicSearch==Null){$topicSearch=Null;}
		$order=@$this->input->get('order');
		if($order==Null){$order_reply=@$this->input->get('order_reply');
			if($order_reply==Null){$order_reply=Null;}
		}else{$order_reply=$order;}
		$limit=@$this->input->get('limit');
		if($limit==Null){$limit_reply=@$this->input->get('limit_reply');
			if($limit_reply==Null){$limit_reply=40;}
		}else{$limit_reply=$limit;}
		$offset=@$this->input->get('offset');
			if($offset==Null){$offset=0;}
		$topicid=@$this->input->get('topicid');
			if($topicid==Null){$topicid=Null;}
		$type=@$this->input->get('type'); // type=mobile // apps //type=web	
			
		$json = null;
		$this->load->model('webboardapi_model');

		$order=$order_reply;

		$pageSize = $limit_reply;
		$pageSize = ($pageSize > 100) ? 100 : $pageSize;
		if(isset($_GET['pageNo']) && !empty($_GET['pageNo'])){
			$pageNo = trim($_GET['pageNo']);
		}
		else {
			$pageNo = 1;
		}
		$rowStart = (($pageNo-1) * $pageSize);
		$rowEnd = ($pageNo * $pageSize);

		if($order=='lastreply') { $v_order='reply_datetime desc'; }
		else { $v_order=''; }
		

        $device=@$this->input->get('type');  
        if($device==Null){ $device='desktop'; }



         $cache_key = "AdmissionWebboard_Detail_".$topicid; //$this->input->post('post_id');
		 $this->tppymemcached->delete($cache_key);
		// get Topic
		
		$arrFilter=array('forceDeleteKey' => true,);
		
		#echo '<pre> $arrFilter=>'; print_r($arrFilter); echo '</pre>';  Die();
		$qResult = $this->webboardapi_model->getTopic($room, null, null , null, 1, null, $topicid,$arrFilter);
		$json['header']['title'] = 'Webboard get webboard detail By topicid';
		$json['header']['code'] = 200;
		$json['header']['status'] = true;
		$json['header']['message'] = 'Success';
		$json['header']['type'] = 'HTTP GET';
		$json['header']['device'] = $device;
		$user_session=$this->session->set_userdata($my);
		$user_id=$my->user_id;
		$json['header']['user_id'] = $user_id;
		#echo '<pre> $qResult=>'; print_r($qResult); echo '</pre>'; Die();
		$arr_result = array();
		if(isset($qResult) and $qResult) {
			$compData1='';
			$compData2='';
			$comData3='';
			$i=-1;
			$j=-1;
			$k=-1;
			foreach ($qResult as $v_result) {
				$arr = array();
				if($compData1!=$v_result['wb_room_id']){
					$i++;
					$arr['room']['roomId'] = $v_result['wb_room_id'];
					$arr['room']['roomName'] = $v_result['wb_room_name'];
					$arr_result['room'][$i] = $arr['room'];
				}
				if($compData2!=$v_result['wb_category_id']){
					$j++;
					$arr['room']['category']['categoryId'] = $v_result['wb_category_id'];
					$arr['room']['category']['categoryName'] = htmlspecialchars($v_result['wb_category_name']);
					$arr['room']['category']['categoryDescription'] = htmlspecialchars($v_result['wb_category_desc']);
					$arr_result['room'][$i]['category'][$j] = $arr['room']['category'];
				}
				if($compData3!=$v_result['wb_post_id']){
					$k++;
					$arr['room']['category']['topic']['topicId'] = $v_result['wb_post_id'];
					if($v_result['thumb']==Null){
					$arr['room']['category']['topic']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';	
					}else{
					$arr['room']['category']['topic']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/'.$v_result['thumb'];
					}
					$arr['room']['category']['topic']['createByImage'] = $v_result['psn_display_image'];
					
					$arr['room']['category']['topic']['topicTitle'] = htmlspecialchars($v_result['wb_subject']);
					$arr['room']['category']['topic']['topicDetail'] = htmlspecialchars($v_result['wb_detail']);
					$arr['room']['category']['topic']['createBy'] = htmlspecialchars($v_result['psn_display_name']);
					
					
					$arr['room']['category']['topic']['topic_viewcount'] = $this->webboardapi_model->getViewNumber($v_result['wb_post_id'], '15');//$v_result['viewcount'];
					$arr['room']['category']['topic']['topic_createdate'] = $v_result['add_date'];
					$arr['room']['category']['topic']['topic_lastupdate'] = $v_result['last_update_date'];
					//$arr['room']['category']['topic']['web_url_go'] = 'http://www.trueplookpanya.com/true/webboard_detail.php?postid='.$v_result['wb_post_id'];
					//$arr['room']['category']['topic']['flagPin'] = $v_result['flag_pin'];
					$totalRows = $this->webboardapi_model->getCountReply($v_result['wb_post_id']);
					$pageTotal = ceil($totalRows / $pageSize);
					$arr['room']['category']['topic']['rowCount_reply'] = $totalRows;
					$arr['room']['category']['topic']['rowRange_reply'] = trim(($rowStart+1).'-'.$rowEnd);
					$arr['room']['category']['topic']['pageTotal_reply'] = $pageTotal;
					$arr_result['room'][$i]['category'][$j]['topic'][] = $arr['room']['category']['topic'];
				}
				
				// Like
			 
				$like_count=$this->webboardapi_model->like_count($topicid);
				$reply_count=$this->webboardapi_model->reply_count($topicid);
				$isFavorite2=$this->webboardapi_model->isFavorite($topicid);
				$isFavorite=$this->favorite_model->get_user_favorite($user_id,16,$topicid);
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['like_count']=$like_count;
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply_count']=$reply_count;
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['isFavorite']=$isFavorite;
				 
				$islike=$this->webboardapi_model->islike($topicid,$user_id='');
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['islike ']=$islike;
				//og // node Social
				
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:app_id'] = "704799662982418";
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:title'] = htmlspecialchars($v_result['wb_subject']);
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:type'] = 'article';
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:locale'] = 'th_TH';
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:url'] = 'http://www.trueplookpanya.com/admissions/webboard/detail/'.$v_result['wb_post_id'];
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:image'] = $v_result['psn_display_image'];
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:site_name'] = 'trueplookpanya.com';
				$wb_detail= htmlspecialchars($v_result['wb_detail']);
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:description'] = $this->trueplook->limitText(strip_tags(str_replace('\'', ' ', str_replace('\"', ' ', $wb_detail))), 200);
				
				// get LastReply
				//$qResultRs = $this->webboardapi_model->getTopicDetail($topicid,$order_reply,$limit_reply=1,$offset_reply=0);
				$this->load->model('api/centermobile_model', 'centermobile_model');
				$qResultRs = $this->centermobile_model->get_webboard_postreply_for_mobile_offset($topicid,$limit=1,$offset=0);
				 //$arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']=$qResultRs;
				 if($qResultRs==Null){
				 	$arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']= []; //Null;
				 }else{
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyId']=$qResultRs['0']['wb_reply_id'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyDetail']=$qResultRs['0']['reply_detail'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyBy']=$qResultRs['0']['username'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyByImage']=$qResultRs['0']['display_image'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyDate']=$qResultRs['0']['add_date'];
				 //reply_sticker 
				 $InputArray=$qResultRs['0']['reply_detail'];
				 $IsArray=$this->IsArraySomeKeyIntAndSomeKeyString($InputArray);
				 //echo '<pre> $IsArray=>'; print_r($IsArray); echo '</pre> <hr>'; Die();
				 
				 
				 if(is_array($InputArray)){
				 	
				 	$reply_type=0;
				 	 $reply_sticker=Null; 
					 $sticker_id=Null;
					 $sticker_url=Null;
				 	/*
					$reply_type=1;
				 	$InputArraydecode=(json_decode($InputArray, true));
				 	  
					  $sticker_id=$InputArraydecode['sticker_list_id'];
    				  $icon_id=$InputArraydecode['sticker_icon_id'];
    				  $sticker_url=$InputArraydecode['sticker_url']; 
    				  $reply_sticker==$InputArraydecode['sticker_url']; 
    				 #echo '<pre> $InputArraydecode=>'; print_r($InputArraydecode); echo '</pre> <hr>'; 
    				 #echo '<pre> $sticker_id=>'; print_r($sticker_id); echo '</pre>';
    				 #echo '<pre> $reply_sticker=>'; print_r($reply_sticker); echo '</pre>'; Die();
    				 */
					
				 }else{
				 	/*
				 	 $reply_type=0;
				 	 $reply_sticker=Null; 
					 $sticker_id=Null;
					 $sticker_url=Null;
					 */
					 
					 
				 	$InputArraydecode=(json_decode($InputArray, true));
				 	  
					  $sticker_id=$InputArraydecode['sticker_list_id'];
    				  $icon_id=$InputArraydecode['sticker_icon_id'];
    				  $sticker_url=$InputArraydecode['sticker_url']; 
    				  $reply_sticker==$InputArraydecode['sticker_url']; 
					 if($sticker_url==Null){
						$reply_type=0;
						}else{
						$reply_type=1;
						}
				 }
				 
				  
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['reply_type']=(int)$reply_type;
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['sticker_id']=$sticker_id;
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['sticker_icon_id']=$icon_id;
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['sticker_url']=$sticker_url;
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['reply_sticker']=$sticker_url;
				 
				 $islike=$this->webboardapi_model->islike($topicid,$user_id='');
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['islike ']=$islike; 
			    }
				 
				// get Reply
				$qResultR = $this->webboardapi_model->getTopicDetail($topicid, $v_order, $pageSize, $rowStart);
				if(isset($qResultR) and $qResultR) {
					foreach ($qResultR as $v_resultR) {
						if($v_resultR['wb_reply_id']!=null || !empty($v_resultR['wb_reply_id'])){
							$arr['room']['category']['topic']['reply']['replyId'] = $v_resultR['wb_reply_id'];
							$arr['room']['category']['topic']['reply']['replyDetail'] = htmlspecialchars($v_resultR['reply_detail']);
							$arr['room']['category']['topic']['reply']['replyBy'] = htmlspecialchars($v_resultR['replyBy']);
							$arr['room']['category']['topic']['reply']['replyByImage'] = $v_resultR['replyByImage'];
							$arr['room']['category']['topic']['reply']['replyDate'] = $v_resultR['reply_datetime'];
				//reply_sticker 	
 				 $InputArray=$v_resultR['reply_detail'];
 				 $InputArraydecode=(json_decode($InputArray, true));
				 $IsArray=$this->IsArraySomeKeyIntAndSomeKeyString($InputArray);
				 
				 /*
				 $value = $InputArray;
					if(is_array($value)){
					  echo '<pre> $InputArraydecode=>'; print_r('is array'); echo '</pre>';
					} else {
					 
					 echo '<pre> $InputArraydecode=>'; print_r('not array'); echo '</pre>';
				  }
				 echo '<pre> $InputArraydecode=>'; print_r($InputArraydecode); echo '</pre>';
				 echo '<pre> $reply_sticker=>'; print_r($reply_sticker); echo '</pre>'; //Die();
				 */
				 
				if(is_array($InputArray)){
					$reply_type=0;
				 	 $reply_sticker=Null; 
					 $sticker_id=Null;
					 $sticker_url=Null;
					 /*
					$reply_type=1;
				 	$InputArraydecode=(json_decode($InputArray, true));
					$sticker_id=$InputArraydecode['sticker_list_id'];
    				  $icon_id=$InputArraydecode['sticker_icon_id'];
    				  $sticker_url=$InputArraydecode['sticker_url']; 
    				  $reply_sticker==$InputArraydecode['sticker_url']; 
    				 #echo '<pre> $InputArraydecode=>'; print_r($InputArraydecode); echo '</pre> <hr>'; 
    				 #echo '<pre> $sticker_id=>'; print_r($sticker_id); echo '</pre>';
    				 #echo '<pre> $reply_sticker=>'; print_r($reply_sticker); echo '</pre>'; Die();
					*/
				 }else{
				 	/*
				 	$reply_type=0;
				 	 $reply_sticker=Null; 
					 $sticker_id=Null;
					 $sticker_url=Null;
					 */
					 $reply_type=1;
				 	$InputArraydecode=(json_decode($InputArray, true));
					$sticker_id=$InputArraydecode['sticker_list_id'];
					
					
					
    				  $icon_id=$InputArraydecode['sticker_icon_id'];
    				  $sticker_url=$InputArraydecode['sticker_url']; 
    				  $reply_sticker==$InputArraydecode['sticker_url']; 
    				  
    				  if($sticker_url==Null){
						$reply_type=0;
						}else{
						$reply_type=1;
						}
    				  
    				 #echo '<pre> $InputArraydecode=>'; print_r($InputArraydecode); echo '</pre> <hr>'; 
    				 #echo '<pre> $sticker_id=>'; print_r($sticker_id); echo '</pre>';
    				 #echo '<pre> $reply_sticker=>'; print_r($reply_sticker); echo '</pre>'; Die();
					 
				 }

				 $arr['room']['category']['topic']['reply']['reply_type']=(int)$reply_type;
				 $arr['room']['category']['topic']['reply']['sticker_id']=$sticker_id;
				 $arr['room']['category']['topic']['reply']['sticker_icon_id']=$icon_id;
				 $arr['room']['category']['topic']['reply']['sticker_url']=$sticker_url;
				 $arr['room']['category']['topic']['reply']['reply_sticker']=$sticker_url;
							
							
						    $islike=$this->webboardapi_model->islike($topicid,$user_id='');
				 			$arr['room']['category']['topic']['reply']['islike ']=$islike;
							
							$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = $arr['room']['category']['topic']['reply'];
						}else{
							$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'] = []; //null;
						}
					}
				}else{
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply']= []; //null;
				}
				

				$compData1 = $v_result['wb_room_id'];
				$compData2 = $v_result['wb_category_id'];
				$compData3 = $v_result['wb_post_id'];
				$compData4 = $v_result['wb_post_id'];
				if($type=='apps' || $type=='mobile'){}else{
				 $limit=@$this->input->get('limit'); 
				 if($limit==Null){$limit=10;}
				$qResultRelated=$this->webboardapi_model->getTopic($room=null, null, null , null,$limit, null, $topicid=null);
				foreach ($qResultRelated as $v_result) {
						$arr = array();
						if($qResultRelated!=Null){
							$k++;
							$arr['room']['category']['related']['topicId'] = $v_result['wb_post_id'];
							if($v_result['thumb']==Null){
							$arr['room']['category']['related']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';	
							}else{
							$arr['room']['category']['related']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/'.$v_result['thumb'];
							}
							$arr['room']['category']['related']['createByImage'] = $v_result['psn_display_image'];
							
							$arr['room']['category']['related']['topicTitle'] = htmlspecialchars($v_result['wb_subject']);
							$arr['room']['category']['related']['topicDetail'] = htmlspecialchars($v_result['wb_detail']);
							$arr['room']['category']['related']['createBy'] = htmlspecialchars($v_result['psn_display_name']);
							
							
							$arr['room']['category']['related']['topic_viewcount'] = $this->webboardapi_model->getViewNumber($v_result['wb_post_id'], '15');//$v_result['viewcount'];
							$arr['room']['category']['related']['topic_createdate'] = $v_result['add_date'];
							$arr['room']['category']['related']['topic_lastupdate'] = $v_result['last_update_date'];
							//$arr['room']['category']['related']['web_url_go'] = 'http://www.trueplookpanya.com/true/webboard_detail.php?postid='.$v_result['wb_post_id'];
							//$arr['room']['category']['related']['flagPin'] = $v_result['flag_pin'];
							$totalRows = $this->webboardapi_model->getCountReply($v_result['wb_post_id']);
							$pageTotal = ceil($totalRows / $pageSize);
							$arr['room']['category']['related']['rowCount_reply'] = $totalRows;
							$arr['room']['category']['related']['rowRange_reply'] = trim(($rowStart+1).'-'.$rowEnd);
							$arr['room']['category']['related']['pageTotal_reply'] = $pageTotal;
							$arr_result['room'][$i]['category'][$j]['related'][] = $arr['room']['category']['related'];
						}
				}
				//////////
			} 
				 
				
				
				
			}
		}
 
        # echo '<pre> $arr_result=>'; print_r($arr_result); echo '</pre>'; 
		//Die();
		$json['data'] = $arr_result;
		$json['header']['orderby'] = $order;
		$json['header']['pageno'] = $pageNo;
		$json['header']['pagesize'] = $pageSize;
        $this->tppymemcached->set($key, $json, 259200);
		//echo json_encode($json, JSON_UNESCAPED_UNICODE);
		$this->response($json);
	}
	public function webboarddetail_get() {
	    $categoryid=@$this->input->get('category_id');  
			if($categoryid==Null){$categoryid=Null;}
		$room=@$this->input->get('room');
			if($room==Null){$room=Null;}
		$topicSearch=@$this->input->get('topicSearch');
			if($topicSearch==Null){$topicSearch=Null;}
		$order=@$this->input->get('order');
		if($order==Null){$order_reply=@$this->input->get('order_reply');
			if($order_reply==Null){$order_reply=Null;}
		}else{$order_reply=$order;}
		$limit=@$this->input->get('limit');
		if($limit==Null){$limit_reply=@$this->input->get('limit_reply');
			if($limit_reply==Null){$limit_reply=40;}
		}else{$limit_reply=$limit;}
		$offset=@$this->input->get('offset');
			if($offset==Null){$offset=0;}
		$topicid=@$this->input->get('topicid');
			if($topicid==Null){$topicid=Null;}
			
			
		$json = null;
		$this->load->model('webboardapi_model');

		$order=$order_reply;

		$pageSize = $limit_reply;
		$pageSize = ($pageSize > 100) ? 100 : $pageSize;
		if(isset($_GET['pageNo']) && !empty($_GET['pageNo'])){
			$pageNo = trim($_GET['pageNo']);
		}
		else {
			$pageNo = 1;
		}
		$rowStart = (($pageNo-1) * $pageSize);
		$rowEnd = ($pageNo * $pageSize);

		if($order=='lastreply') { $v_order='reply_datetime desc'; }
		else { $v_order=''; }
		

		// get Topic
		$qResult = $this->webboardapi_model->getTopic($room, null, null , null, 1, null, $topicid);
		$json['header']['title'] = 'Webboard get webboard detail By topicid';
		$json['header']['code'] = 200;
		$json['header']['status'] = true;
		$json['header']['message'] = 'Success';
		$json['header']['type'] = 'HTTP GET';
		$arr_result = array();
		if(isset($qResult) and $qResult) {
			$compData1='';
			$compData2='';
			$comData3='';
			$i=-1;
			$j=-1;
			$k=-1;
			foreach ($qResult as $v_result) {
				$arr = array();
				
				
				
				if($compData1!=$v_result['wb_room_id']){
					$i++;
					$arr['room']['roomId'] = $v_result['wb_room_id'];
					$arr['room']['roomName'] = $v_result['wb_room_name'];
					$arr_result['room'][$i] = $arr['room'];
				}
				if($compData2!=$v_result['wb_category_id']){
					$j++;
					$arr['room']['category']['categoryId'] = $v_result['wb_category_id'];
					$arr['room']['category']['categoryName'] = htmlspecialchars($v_result['wb_category_name']);
					$arr['room']['category']['categoryDescription'] = htmlspecialchars($v_result['wb_category_desc']);
					$arr_result['room'][$i]['category'][$j] = $arr['room']['category'];
				}
				if($compData3!=$v_result['wb_post_id']){
					$k++;
					$arr['room']['category']['topic']['topicId'] = $v_result['wb_post_id'];
					if($v_result['thumb']==Null){
					$arr['room']['category']['topic']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';	
					}else{
					$arr['room']['category']['topic']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/'.$v_result['thumb'];
					}
					$arr['room']['category']['topic']['createByImage'] = $v_result['psn_display_image'];
					
					$arr['room']['category']['topic']['topicTitle'] = htmlspecialchars($v_result['wb_subject']);
					$arr['room']['category']['topic']['topicDetail'] = htmlspecialchars($v_result['wb_detail']);
					$arr['room']['category']['topic']['createBy'] = htmlspecialchars($v_result['psn_display_name']);
					
					
					$arr['room']['category']['topic']['topic_viewcount'] = $this->webboardapi_model->getViewNumber($v_result['wb_post_id'], '15');//$v_result['viewcount'];
					$arr['room']['category']['topic']['topic_createdate'] = $v_result['add_date'];
					$arr['room']['category']['topic']['topic_lastupdate'] = $v_result['last_update_date'];
					//$arr['room']['category']['topic']['web_url_go'] = 'http://www.trueplookpanya.com/true/webboard_detail.php?postid='.$v_result['wb_post_id'];
					//$arr['room']['category']['topic']['flagPin'] = $v_result['flag_pin'];
					$totalRows = $this->webboardapi_model->getCountReply($v_result['wb_post_id']);
					$pageTotal = ceil($totalRows / $pageSize);
					$arr['room']['category']['topic']['rowCount_reply'] = $totalRows;
					$arr['room']['category']['topic']['rowRange_reply'] = trim(($rowStart+1).'-'.$rowEnd);
					$arr['room']['category']['topic']['pageTotal_reply'] = $pageTotal;
					$arr_result['room'][$i]['category'][$j]['topic'][] = $arr['room']['category']['topic'];
				}
				
				
				//og // node Social
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:app_id'] = "704799662982418";
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:title'] = htmlspecialchars($v_result['wb_subject']);
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:type'] = 'article';
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:locale'] = 'th_TH';
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:url'] = 'http://www.trueplookpanya.com/admissions/webboard/detail/'.$v_result['wb_post_id'];
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:image'] = $v_result['psn_display_image'];
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:site_name'] = 'trueplookpanya.com';
					$wb_detail= htmlspecialchars($v_result['wb_detail']);
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:description'] = $this->trueplook->limitText(strip_tags(str_replace('\'', ' ', str_replace('\"', ' ', $wb_detail))), 200);
				
				// get LastReply
				//$qResultRs = $this->webboardapi_model->getTopicDetail($topicid,$order_reply,$limit_reply=1,$offset_reply=0);
				$this->load->model('api/centermobile_model', 'centermobile_model');
				$qResultRs = $this->centermobile_model->get_webboard_postreply_for_mobile_offset($topicid,$limit=1,$offset=0);
				 //$arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']=$qResultRs;
				 if($qResultRs==Null){$arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']=Null;}else{
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyId']=$qResultRs['0']['wb_reply_id'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyDetail']=$qResultRs['0']['reply_detail'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyBy']=$qResultRs['0']['username'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyByImage']=$qResultRs['0']['display_image'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyDate']=$qResultRs['0']['add_date'];
			    }
				 
				// get Reply
				$qResultR = $this->webboardapi_model->getTopicDetail($topicid, $v_order, $pageSize, $rowStart);
				if(isset($qResultR) and $qResultR) {
					foreach ($qResultR as $v_resultR) {
						if($v_resultR['wb_reply_id']!=null || !empty($v_resultR['wb_reply_id'])){
							$arr['room']['category']['topic']['reply']['replyId'] = $v_resultR['wb_reply_id'];
							$arr['room']['category']['topic']['reply']['replyDetail'] = htmlspecialchars($v_resultR['reply_detail']);
							$arr['room']['category']['topic']['reply']['replyBy'] = htmlspecialchars($v_resultR['replyBy']);
							$arr['room']['category']['topic']['reply']['replyByImage'] = $v_resultR['replyByImage'];
							$arr['room']['category']['topic']['reply']['replyDate'] = $v_resultR['reply_datetime'];
							$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = $arr['room']['category']['topic']['reply'];
						}else{
							$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = null;
						}
					}
				}else{
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = null;
				}
				

				$compData1 = $v_result['wb_room_id'];
				$compData2 = $v_result['wb_category_id'];
				$compData3 = $v_result['wb_post_id'];
				$compData4 = $v_result['wb_post_id'];
			}
		}
 
		
		$json['data'] = $arr_result;
		$json['header']['orderby'] = $order;
		$json['header']['pageno'] = $pageNo;
		$json['header']['pagesize'] = $pageSize;
        $this->tppymemcached->set($key, $json, 259200);
		//echo json_encode($json, JSON_UNESCAPED_UNICODE);
		$this->response($json);
	}
	//////////LAST
	public function webboardlistall_get() {
	$room=@$this->input->get('room');
			if($room==Null){$room=null;}
	    $categoryid=@$this->input->get('category_id');  
			if($categoryid==Null){$categoryid=Null;}
		$order=@$this->input->get('order');
			if($order==Null){$order=Null;}
		$limit=@$this->input->get('limit');
			if($limit==Null){$limit=80;}
			if($limit>=120){$limit=120;}
	    $topicSearch=@$this->input->get('topicSearch');
			if($topicSearch==Null){$topicSearch=Null;}
			
		 $offset=@$this->input->get('offset');
			if($offset==Null){$offset=Null;}	
		$topicid=@$this->input->get('topicid');
			if($topicid==Null){$topicid=Null;}
			
		$arrFilter=@$this->input->get('arrFilter');
			if($arrFilter==Null){$arrFilter = array();}
		// Plook Admissions
		// api/webboard/last?category_id=59&room=6&order=desc&limit=10
		
		// Webboard 
            $this->load->model('api/webboardapi_model', 'webboardapi_model_api');
			$qResultlast=$this->webboardapi_model_api->getTopic($room, $categoryid,$topicSearch,$order,$limit,$offset,$topicid, $arrFilter);
			$arr_resultlast = array();
				if (is_array($qResultlast)) {
				foreach ($qResultlast as $key => $va) {
					$arr = array();
					$arr['last']['topicId'] = $va['wb_post_id'];
					$arr['last']['categoryId'] = $va['wb_category_id'];
					$arr['last']['roomId'] = $va['wb_room_id'];
					$arr['last']['roomName'] = $va['wb_room_name'];
					$arr['last']['categoryName'] = $va['wb_category_name'];
					$arr['last']['topicTitle'] = $va['wb_subject'];
					$arr['last']['topicDetail'] = $va['wb_detail'];
					if($va['thumb']==NUll){
					  $thumb='http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';
					  }else{
						$thumb='http://static.trueplookpanya.com/trueplookpanya/'.$va['thumb'];
					}
					$arr['last']['thumbnail'] = $thumb;
					$arr['last']['topic_createdate'] = $va['add_date'];
					$arr['last']['topic_lastupdate'] = $va['last_update_date'];
					$arr['last']['topic_viewcount'] = $va['view_count'];   
					$arr['last']['reply_viewcount'] = $this->webboardapi_model->getCountReply($va['wb_post_id']);
					$arr['last']['createBy'] = $va['psn_display_name'];
					$arr['last']['createByImage'] = $va['psn_display_image'];
					$arr_resultlast[] = $arr['last'];
				}
			}
		
		$data=$arr_resultlast;
	 
		// Plook Admissions
		// api/webboard/last?category_id=59&room=6&order=desc&limit=10
		
		// Webboard 
        //$this->load->model('admissions/webboardapi_models', 'am_webboardapi_models');
        //$this->load->model('api/webboardapi_model', 'api_webboardapi_models');
		//$this->load->model('api/centermobile_model', 'centermobile_model');
		//$data=$this->centermobile_model->get_webboard_last_for_mobile($room,$categoryid,$limit,$order_by);
		
		 #echo '<pre> $data=>'; print_r($data); echo '</pre>'; Die();
		$count=count($data);
				 if($data){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'title'=>'Webboard List All',
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>404,
											'count'=>$count,),
											'data'=> Null,),404);
					}
	 }
	public function last_get() {
		$room=@$this->input->get('room');
			if($room==Null){$room=6;}
	    $categoryid=@$this->input->get('category_id');  
			if($categoryid==Null){$categoryid=59;}
		$order=@$this->input->get('order');
			if($order==Null){$order=Null;}
		$limit=@$this->input->get('limit');
			if($limit==Null){$limit=16;}
			if($limit>=80){$limit=80;}
	    $topicSearch=@$this->input->get('topicSearch');
			if($topicSearch==Null){$topicSearch=Null;}
			
		 $offset=@$this->input->get('offset');
			if($offset==Null){$offset=Null;}	
		$topicid=@$this->input->get('topicid');
			if($topicid==Null){$topicid=Null;}
			
		$arrFilter=@$this->input->get('arrFilter');
			if($arrFilter==Null){$arrFilter = array();}
		// Plook Admissions
		// api/webboard/last?category_id=59&room=6&order=desc&limit=10
		
		// Webboard 

			$this->load->model('api/webboard_model', 'webboard_model_api');
			$qResultlast=$this->webboard_model_api->getTopic($room, $categoryid,$topicSearch,$order,$limit,$offset,$topicid,$arrFilter);
			#echo '<pre> $qResultlast=>'; print_r($qResultlast); echo '</pre>'; Die();
			$arr_result = array();
			if (is_array($qResultlast)) {
				foreach ($qResultlast as $key => $value) {
					$arr = array();
					$arr['last']['room_id'] = $value['wb_room_id'];
					$arr['last']['topicId'] = $value['wb_post_id'];
					$arr['last']['category_id'] = $value['wb_category_id'];
					$arr['last']['room_name'] = $value['wb_room_name'];
					$arr['last']['categoryname'] = $value['wb_category_name'];
					$arr['last']['topicTitle'] = $value['wb_subject'];
					$arr['last']['topicDetail'] = $value['wb_detail'];
					if($value['thumb']==NUll){
					  	$thumb='http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';
					}else{
						$thumb='http://static.trueplookpanya.com/trueplookpanya/'.$value['thumb'];
					}
					$arr['last']['thumbnail'] = $thumb;
					$arr['last']['topic_createdate'] = $value['add_date'];
					$arr['last']['topic_lastupdate'] = $value['last_update_date'];
					$arr['last']['topic_viewcount'] = $value['view_count'];  
					$arr['last']['createBy'] = $value['psn_display_name'];
					$arr['last']['createByimage'] = $value['psn_display_image'];
					//$arr['last']['postby'] = $value['post_by_name'];
					$arr['last']['member_id'] = $value['member_id'];
					$arr['last']['user_id'] = $value['user_id'];
					##############################################
					/*
					$arr['last']['flag_recv_email'] = $value['flag_recv_email'];
					$arr['last']['flag_pin'] = $value['flag_pin'];
					$arr['last']['flag_delete'] = $value['flag_delete'];
					$arr['last']['wb_status'] = $value['wb_status'];
					$arr['last']['wb_category_desc'] = $value['wb_category_desc'];
					$arr['last']['blog_id'] = $value['blog_id'];
					*/
					##############################################
					$arr_result[] = $arr['last'];
				}
			}
			$data=$arr_result;
	 
		// Plook Admissions
		// api/webboard/last?category_id=59&room=6&order=desc&limit=10
		
		// Webboard 
        //$this->load->model('admissions/webboardapi_models', 'am_webboardapi_models');
        //$this->load->model('api/webboardapi_model', 'api_webboardapi_models');
		//$this->load->model('api/centermobile_model', 'centermobile_model');
		//$data=$this->centermobile_model->get_webboard_last_for_mobile($room,$categoryid,$limit,$order_by);
		
		 #echo '<pre> $data=>'; print_r($data); echo '</pre>'; Die();
		$count=count($data);
				 if($data){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'title'=>'Webboard last topic',
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>404,
											'count'=>$count,),
											'data'=> Null,),404);
					}
	 }
	//////////HOT
	public function hot_get() {
		$room=@$this->input->get('room');
			if($room==Null){$room=Null;}
	    $categoryid=@$this->input->get('category_id');  
			if($categoryid==Null){$categoryid=Null;}
		$order=@$this->input->get('order');
			if($order==Null){$order=Null;}
		$limit=@$this->input->get('limit');
			if($limit==Null){$limit=16;}
			if($limit>=80){$limit=80;}
	    $topicSearch=@$this->input->get('topicSearch');
			if($topicSearch==Null){$topicSearch=Null;}
			
		 $offset=@$this->input->get('offset');
			if($offset==Null){$offset=Null;}	
		$topicid=@$this->input->get('topicid');
			if($topicid==Null){$topicid=Null;}
			
		$arrFilter=@$this->input->get('arrFilter');
			if($arrFilter==Null){$arrFilter = array();}
		// Plook Admissions
		// http://www.trueplookpanya.com/api/center/hot?limit=4
		// Webboard 

			$this->load->model('api/webboard_model', 'webboard_model_api');
			$qResulthot=$this->webboard_model_api->getTopic2($room =Null, $categoryid =Null, $topicSearch = null , $order = null, $limit, $offset = null , $topicid = null, $arrFilter = array('isPin'=>1));
			$arr_resulthot = array();
			if (is_array($qResulthot)) {
				foreach ($qResulthot as $key => $value) {
					$arr = array();
					$arr['hot']['room_id'] = $value['wb_room_id'];
					$arr['hot']['topicId'] = $value['wb_post_id'];
					$arr['hot']['category_id'] = $value['wb_category_id'];
					$arr['hot']['room_name'] = $value['wb_room_name'];
					$arr['hot']['categoryname'] = $value['wb_category_name'];
					$arr['hot']['topicTitle'] = $value['wb_subject'];
					$arr['hot']['topicDetail'] = $value['wb_detail'];
					if($value['thumb']==NUll){
					  	$thumb='http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';
					}else{
						$thumb='http://static.trueplookpanya.com/trueplookpanya/'.$value['thumb'];
					}
					$arr['hot']['thumbnail'] = $thumb;
					$arr['hot']['topic_createdate'] = $value['add_date'];
					$arr['hot']['topic_lastupdate'] = $value['last_update_date'];
					$arr['hot']['topic_viewcount'] = $value['view_count'];  
					$arr['hot']['createBy'] = $value['psn_display_name'];
					$arr['hot']['createByimage'] = $value['psn_display_image'];
					//$arr['hot']['postby'] = $value['post_by_name'];
					$arr['hot']['member_id'] = $value['member_id'];
					$arr['hot']['user_id'] = $value['user_id'];
					##############################################
					/*
					$arr['hot']['flag_recv_email'] = $value['flag_recv_email'];
					$arr['hot']['flag_pin'] = $value['flag_pin'];
					$arr['hot']['flag_delete'] = $value['flag_delete'];
					$arr['hot']['wb_status'] = $value['wb_status'];
					$arr['hot']['wb_category_desc'] = $value['wb_category_desc'];
					$arr['hot']['blog_id'] = $value['blog_id'];
					*/
					##############################################
					$arr_resulthot[] = $arr['hot'];
				}
			} 
			 //echo '<pre> $qResulthot=>'; print_r($qResulthot); echo '</pre>';
			 //echo '<pre> $arr_resulthot=>'; print_r($arr_resulthot); echo '</pre>'; Die();
			$data["data"]["webboardhot"] = $arr_resulthot;
			$data=$arr_resulthot;
	 
		// Plook Admissions
		// api/webboard/hot?category_id=59&room=6&order=desc&limit=10
		 #echo '<pre> $data=>'; print_r($data); echo '</pre>'; Die();
		$count=count($data);
				 if($data){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'title'=>'Webboard hot topic',
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'count'=>$count,),
											'data'=> Null),404);
					}
	 }
   //////////ontour
	public function ontourlistall_get() {
	
	    $campaign_id=@$this->input->get('campaign_id');
			if($campaign_id==Null){$campaign_id=Null;}
		$limit=@$this->input->get('limit');
			if($limit==Null){$limit=20;}
		$offset=@$this->input->get('offset');
			if($offset==Null){$offset=0;}
		$type=@$this->input->get('type');
			if($type==Null){$type='cover';}
		 
		// http://www.trueplookpanya.local/api/center/ontourlistall?campaign_id=1&limit=50&offset=0
		 
			
		$this->load->library('memcache');
		$this->load->library('trueplook');
        //-------------------General & Images Setting ----------------//
        $campaign = 'on_tour';
        $image_path = base_url() .'new/assets/images/campaign/' . $campaign . '/';
		//echo '<pre> $image_path=>'; print_r($image_path); echo '</pre>'; Die();
        $data['nav'] = '<a href="' . base_url() . '">หน้าหลัก</a> &gt; <a href="' . base_url() . 'on_tour/">กิจกรรมทรูปลูกปัญญา On Tour</a>';
        $data['back_url'] = base_url() . 'new/on_tour/';
        $data['campaign_id'] = $campaign_id;
        $data['title'] = 'กิจกรรม ontour';
        $data['today_date'] = $this->trueplook->data_format('small', 'th', date("Y-m-d"));
        $data['today_day'] = $this->trueplook->get_day_name(date(time()));
        $data['today_time'] = date("H : i", time()) . ' น.';
        $data['link_social'] =  'http://www.trueplookpanya.com/new/on_tour/';//current_url();
        $data['media_tag'] = 'กิจกรรม, โครงการ, ontour, ทรูปลูกปัญญา, event, การเรียน, การเรียนรู้';
        $data['title_image'] = $image_path . 'tab-title.png';
        $data['banner_710'] = $image_path . 'banner710.png';
        //-------------------End General &  Images Setting ----------------//
        //------------------- META Zone --------------------------//
        $data['page_keyword'] = 'กิจกรรม, โครงการ, ontour, ทรูปลูกปัญญา, event, การเรียน, การเรียนรู้';
        $data['page_description'] = 'กิจกรรมทรูปลูกปัญญา On Tour กิจกรรม ที่มอบ สื่อการเรียนรู้ แบบระยะประชิด มอบความสนุก ความรู้ พร้อมเรียนรู้ประสบการณ์ใหม่ๆ รับของที่ระลึกไม่ซ้ำใครไปเลย แล้วพบกันค่ะ';
        $data['page_title'] = 'กิจกรรมทรูปลูกปัญญา On Tour : ทรูปลูกปัญญาดอทคอม';
        //---------------------- END -----------------------------//
        //-------------------- Facebook Zone ------------------//
        $data['facebook_status'] = 'show';
        $data['facebook_adminid'] = '704799662982418';
        $data['facebook_subject'] = 'กิจกรรมทรูปลูกปัญญา On Tour : ทรูปลูกปัญญาดอทคอม';
        $data['facebook_type'] = 'movie';
        $data['facebook_link'] = 'http://www.trueplookpanya.com/on_tour/';
        $data['facebook_filename'] = 'http://www.trueplookpanya.com/data/product/media/2012/hash_banner/10/10/BANNER_ADS10vedj47hWFu.jpg';
        $data['facebook_sitename'] = 'Trueplookpanya.com';
        $data['facebook_des'] = 'กิจกรรม ที่มอบ สื่อการเรียนรู้ แบบระยะประชิด มอบความสนุก ความรู้ พร้อมเรียนรู้ประสบการณ์ใหม่ๆ รับของที่ระลึกไม่ซ้ำใครไปเลย แล้วพบกันค่ะ';
        //------------------------ END ---------------------------//
        //============= Relate Banner Campaign ================//
        $data['banner_relate'] = $this->trueplook->show_banner(9, 10);
//================= END =========================//
		// Model 
		$this->load->model('api/centermobile_model', 'centermobile_model');
		$data['media_path']='http://www.trueplookpanya.com/data/product/media/';
		$data['campaign_list'] =$this->centermobile_model->get_campaign_work($campaign_id,$limit,$offset,$type);
        

		
 
		 //echo '<pre> $data=>'; print_r($data); echo '</pre>'; Die();
		$count=count($data['campaign_list']);
		if($count==0){$data['campaign_list'] =Null;}
				 if($data){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'count'=>$count,),
											'data'=> Null),404);
					}
	 }
	public function ontour_get() {
	
	    $campaign_id=@$this->input->get('campaign_id');
			if($campaign_id==Null){$campaign_id=Null;}
		$limit=@$this->input->get('limit');
			if($limit==Null){$limit=10;}
			//if($limit!==Null){$limit=20;}
		$offset=@$this->input->get('offset');
			if($offset==Null){$offset=0;}
		$type=@$this->input->get('type');
			if($type==Null){$type='cover';}
		############
		$hight=@$this->input->get('hight');
			if($hight==Null){$hight=800;}
		$width=@$this->input->get('width');
			if($width==Null){$width=1600;}
		####
		 
		 // &hight=400&width=800
		// http://www.trueplookpanya.com/api/center/ontour?campaign_id=1&limit=10&offset=0&type=image&hight=400&width=800
		// http://www.trueplookpanya.com/api/center/ontour?campaign_id=1&limit=10&offset=0&type=cover&hight=400&width=800
		 
			
		$this->load->library('memcache');
		$this->load->library('trueplook');
        //-------------------General & Images Setting ----------------//
        $campaign = 'on_tour';
        $image_path = base_url() .'new/assets/images/campaign/' . $campaign . '/';
		//echo '<pre> $image_path=>'; print_r($image_path); echo '</pre>'; Die();
        $data['web']['nav'] = '<a href="' . base_url() . '">หน้าหลัก</a> &gt; <a href="' . base_url() . 'on_tour/">กิจกรรมทรูปลูกปัญญา On Tour</a>';
        $data['web']['back_url'] = base_url() . 'new/on_tour/';
        $data['web']['campaign_id'] = $campaign_id;
        $data['web']['title'] = 'กิจกรรม ontour';
        $data['web']['today_date'] = $this->trueplook->data_format('small', 'th', date("Y-m-d"));
        $data['web']['today_day'] = $this->trueplook->get_day_name(date(time()));
        $data['web']['today_time'] = date("H : i", time()) . ' น.';
        $data['web']['link_social'] =  'http://www.trueplookpanya.com/new/on_tour/';//current_url();
        $data['web']['media_tag'] = 'กิจกรรม, โครงการ, ontour, ทรูปลูกปัญญา, event, การเรียน, การเรียนรู้';
        $data['web']['title_image'] = $image_path . 'tab-title.png';
        $data['web']['banner_710'] = $image_path . 'banner710.png';
        //-------------------End General &  Images Setting ----------------//
        //------------------- META Zone --------------------------//
        $data['web']['page_keyword'] = 'กิจกรรม, โครงการ, ontour, ทรูปลูกปัญญา, event, การเรียน, การเรียนรู้';
        $data['web']['page_description'] = 'กิจกรรมทรูปลูกปัญญา On Tour กิจกรรม ที่มอบ สื่อการเรียนรู้ แบบระยะประชิด มอบความสนุก ความรู้ พร้อมเรียนรู้ประสบการณ์ใหม่ๆ รับของที่ระลึกไม่ซ้ำใครไปเลย แล้วพบกันค่ะ';
        $data['web']['page_title'] = 'กิจกรรมทรูปลูกปัญญา On Tour : ทรูปลูกปัญญาดอทคอม';
        //---------------------- END -----------------------------//
        //-------------------- Facebook Zone ------------------//
        $data['web']['facebook_status'] = 'show';
        $data['web']['facebook_adminid'] = '704799662982418';
        $data['web']['facebook_subject'] = 'กิจกรรมทรูปลูกปัญญา On Tour : ทรูปลูกปัญญาดอทคอม';
        $data['web']['facebook_type'] = 'movie';
        $data['web']['facebook_link'] = 'http://www.trueplookpanya.com/on_tour/';
        $data['web']['facebook_filename'] = 'http://www.trueplookpanya.com/data/product/media/2012/hash_banner/10/10/BANNER_ADS10vedj47hWFu.jpg';
        $data['web']['facebook_sitename'] = 'Trueplookpanya.com';
        $data['web']['facebook_des'] = 'กิจกรรม ที่มอบ สื่อการเรียนรู้ แบบระยะประชิด มอบความสนุก ความรู้ พร้อมเรียนรู้ประสบการณ์ใหม่ๆ รับของที่ระลึกไม่ซ้ำใครไปเลย แล้วพบกันค่ะ';
        //------------------------ END ---------------------------//
        //============= Relate Banner Campaign ================//
        $data['web']['banner_relate'] = $this->trueplook->show_banner(9, 10);
//================= END =========================//
		// Model 
		$this->load->model('api/centermobile_model', 'centermobile_model');
		#$data['media_path']='http://www.trueplookpanya.com/data/product/media/'; 
		//$data['campaign_list'] =$this->centermobile_model->get_campaign_work($campaign_id,$limit,$offset,$type);

		$qResults=$this->centermobile_model->get_campaign_work($campaign_id,$limit,$offset,$type);
		$arr_results = array();
				if (is_array($qResults)) {
				foreach ($qResults as $key => $va) {
					$arr = array();
					$arr['campaign_list']['Ontour_id'] = $va['ontour_id'];
					//$arr['campaign_list']['Campaign_id'] = $va['campaign_id'];
					$arr['campaign_list']['Campaign_name'] = $va['campaign_name'];
					
					$media_path=$va['media_path'];
					$media_filename=$va['file'];
					$media_path=$this->trueplook->image_resize($hight,$width, $media_path, $media_filename);
					$arr['campaign_list']['Thumbnail'] = $media_path; 
					//$arr['campaign_list']['Media_name'] = $va['media_name'];
					//$arr['campaign_list']['Media_detail'] = $va['media_detail'];
					//$arr['campaign_list']['Media_link'] = $va['media_link'];
					
					$arr['campaign_list']['Title'] = $va['title'];
					$arr['campaign_list']['Detail'] = $va['detail'];
					//$arr['campaign_list']['Link'] = $va['work_link'];
					$arr['campaign_list']['Tag'] = $va['tag'];
					$arr['campaign_list']['View'] = $va['content_view'];
					$arr['campaign_list']['Add_date'] = $va['add_date'];
					$arr['campaign_list']['Last_update'] = $va['last_update'];
					//$arr['campaign_list']['Activity_date'] = $va['activity_date'];
					//$arr['campaign_list']['Media_type'] = $va['media_type'];
					$arr_results[] = $arr['campaign_list'];
				}
			}
		$data['campaign'] =$arr_results;

		
 
		 //echo '<pre> $data=>'; print_r($data); echo '</pre>'; Die();
		$count=count($qResults);
		if($count==0){$count=Null;}
				 if($data){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'countrow'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'count'=>$count,),
											'data'=> Null),404);
					}
	 }
	public function ontourdetail_get() {
	
		$ontour_id=@$this->input->get('ontour_id');
			if($ontour_id==Null){$ontour_id=Null;}
		$media_id=@$this->input->get('media_id');
			if($media_id==Null){$media_id=Null;}
		############
		$hight=@$this->input->get('hight');
			if($hight==Null){$hight=800;}
		$width=@$this->input->get('width');
			if($width==Null){$width=1024;}
		####
		// http://www.trueplookpanya.com/api/center/ontourdetail?ontour_id=456
		// http://www.trueplookpanya.com/api/center/ontourdetail?ontour_id=456&media_id=867
		$this->load->library('memcache');
		$this->load->library('trueplook');
		// Model 
		$this->load->model('api/centermobile_model', 'centermobile_model');
		$datas=$this->centermobile_model->get_campaign_work_detail($ontour_id,$media_id);
		$qResults=$datas['0'];
		$va=$qResults;
		#echo '<pre> $qResults=>'; print_r($qResults); echo '</pre>'; Die();
		$data['ontourdetail'] ['Ontour_id'] = $va['ontour_id'];
		$data['ontourdetail'] ['Title'] = $va['title'];
		$data['ontourdetail'] ['Detail'] = $va['detail'];
		$data['ontourdetail'] ['Tag'] = $va['tag'];
		//$data['ontourdetail'] ['Views'] = $va['content_view'];
		$data['ontourdetail'] ['Add_date'] = $va['add_date'];
		$data['ontourdetail'] ['Last_update'] = $va['last_update'];
		$data['ontourdetail'] ['Activity_date'] = $va['activity_date'];
		$content_view_old=$datas['content_view'];
		$datacampaign_list_media=$this->centermobile_model->get_campaign_work_media_setcover($ontour_id,$media_id);
		$campaign_list_media1=$datacampaign_list_media['0'];
		$campaign_list_media2=$this->centermobile_model->get_campaign_work_media_setcover2($ontour_id,$media_id);
		$campaign_list_media2=$campaign_list_media2['0'];
		#echo '<pre> $campaign_list_media1=>'; print_r($campaign_list_media1); echo '</pre>'; 
		#echo '<pre> $campaign_list_media2=>'; print_r($campaign_list_media2); echo '</pre>'; Die();
		
		if($campaign_list_media1==Null){
		$media_type=$campaign_list_media2['media_type'];
		$media_path=$campaign_list_media2['media_path'];
		$data['ontourdetail']['Thumbnail_detail']=$campaign_list_media2['media_detail'];
		$media_filename=$campaign_list_media2['media_filename'];
		$datacampaign_list_media=$campaign_list_media2;
		//$datas['media_type']=$media_type;
		//$datas['cover']=$media_path_url.$media_path.'/'.$media_filename;
		}elseif($campaign_list_media1!=Null){
		$media_type=$campaign_list_media1['media_type'];
		$media_path=$campaign_list_media1['media_path'];
		$data['ontourdetail']['Thumbnail_detail']=$campaign_list_media1['media_detail'];
		$media_filename=$campaign_list_media1['media_filename'];
		$datacampaign_list_media=$campaign_list_media1;
		//$data['ontourdetail']['media_type']=$media_type;
		//$data['ontourdetail']['cover']=$media_path_url.$media_path.'/'.$media_filename;
		}else{$data['ontourdetail']['Thumbnail_detail']=Null;}
		$media_path=$media_path;
		$media_filename=$media_filename;
		$media_path=$this->trueplook->image_resize($hight,$width, $media_path, $media_filename);
		$media_path_image=$this->trueplook->image_resize($hight,$width, $media_path, $media_filename);
		$data['ontourdetail']['Thumbnail'] = $media_path; 
		$data['ontourdetail']['Image'] = $media_path; ;
		$time = time()+(300);
		$timeMemo = (string)$time;
		$ip_address=@$this->input->get('ip_address');
		if($ip_address==Null){ $ip_address=$this->get_ip_address(); }else{$ip_address=@$this->input->get('ip_address');}
		//sets cookie with expiration time defined above
		$ip_address_cookieset=$ip_address.'_'.$ontour_id.'_'.$media_id;
		setcookie("ip_address_cookie", "" . $ip_address_cookieset . "", $time);
		$ip_address_cookie=@$_COOKIE['ip_address_cookie'];
		//campaign_work_media
		if(isset($ip_address_cookie)){
		
		    $cookieip=$ip_address_cookie;
			if($media_id==Null){
			$content_view_id=$ontour_id;
			$dataview=$this->centermobile_model->get_campaign_listss_view($content_view_id,$table='campaign_work');	
			}else{
			$content_view_id=$media_id;
			$dataview=$this->centermobile_model->get_campaign_listss_view($content_view_id,$table='campaign_work_media');	
			}
			
		}else{
		
			$cookieip=Null;
			if($media_id==Null){
			$content_view_id=$ontour_id;
			$dataview=$this->centermobile_model->get_campaign_update_view($content_view_id,$table='campaign_work');	
			}else{
			$content_view_id=$media_id;
			$dataview=$this->centermobile_model->get_campaign_update_view($content_view_id,$table='campaign_work_media');	
			}
			
			
		}
		
		//$data['ontourdetail']['Ontour_view_update']=$dataview;
		$content_view_id=$ontour_id;
		$Ontour_view=$this->centermobile_model->get_campaign_listss_view($content_view_id,$table='campaign_work');
		$viewcount=$Ontour_view;
			$data['ontourdetail']['Ontour_view']=$Ontour_view;
		
		if($media_id!==Null){
			$Media_view=$this->centermobile_model->get_campaign_listss_view($media_id,$table='campaign_work_media');
		    $viewcount=$Media_view;
			$data['ontourdetail']['Media_view']=$Media_view;
		}else{$data['ontourdetail']['Media_view']=Null;}
		//$data['ontourdetail']['ip_address']=$ip_address;
		//$data['ontourdetail']['cookie']=$ip_address_cookieset;
		
		$data['ontourdetail']['View']=$viewcount;
		
		
		
		if($data['ontourdetail']==Null){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,),
											'data'=> Null),200);
											Die();
					}
		
        $qResultsgallery=$this->centermobile_model->get_campaign_work_media($ontour_id);
		#echo '<pre> $qResultsgallery=>'; print_r($qResultsgallery); echo '</pre>'; Die();
		$arr_results1 = array();
				if (is_array($qResultsgallery)) {
					foreach ($qResultsgallery as $key => $va) {
					$arr = array();
					$arr['gallery_list']['Media_id'] = $va['media_id'];
					#$arr['gallery_list']['Campaign_id'] = $va['campaign_id'];
					$media_path=$va['media_path'];
					$media_filename=$va['media_filename'];
					$media_path=$this->trueplook->image_resize($hight,$width, $media_path, $media_filename);
					$arr['gallery_list']['Image']=$media_path; 
					$arr['gallery_list']['Thumbnail'] = $media_path; 
					#$arr['gallery_list']['Media_name'] = $va['media_name'];
					$arr['gallery_list']['Media_detail'] = $va['media_detail'];
					$arr['gallery_list']['Last_update'] = $va['last_update'];
					$arr_results[] = $arr['gallery_list'];
				}
			}
		$data['gallery'] =$arr_results;
		#echo '<pre> $data=>'; print_r($data); echo '</pre>'; Die();
		$count=count($data['gallery']);
		if($count==0){$data['gallery'] =Null;}
				 if($data){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count_media'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'count_media'=>$count,),
											'data'=> Null),404);
					}
	 } 
	public function timeline_get() {
	    $limit=@$this->input->get('limit');
			if($limit==Null){$limit=100;} 
				// http://www.trueplookpanya.local/api/center/timeline?limit=10
        $this->load->library('TPPY_Utils');
        $this->load->model('admissions/admissions_models', 'am');
        $this->load->model('mobile/admission_model', 'amm');
        $this->load->helper('tppy_helper');
		#$data['timeline'] = $this->amm->timeline('', 'limit '.$limit.'');
        #$data['timeline_pass'] = $this->amm->timeline('', 'limit '.$limit.'', TRUE);
		###########################
	    $qResulttimeline=$this->amm->timeline('', 'limit '.$limit.'');
		$data['timeline'] = $qResulttimeline;
		###########################
		$qResulttimeline_pass=$this->amm->timeline('', 'limit '.$limit.'', TRUE);
		$data['timeline_pass']=$qResulttimeline_pass;
		###########################
		//echo '<pre> $qResulttimeline_pass=>'; print_r($qResulttimeline_pass); echo '</pre>'; Die();	
		/*   
			$arr_result = array();
				if(is_array($qResulttimeline_pass)) {
				foreach ($qResulttimeline_pass as $key => $va) {
					$arr = array();
					$arr['timelinepass']['tablename'] = $va['tablename'];
					$arr['timelinepass']['typename'] = $va['typename'];
					$arr['timelinepass']['typename_display'] = $va['typename_display'];
					$arr['timelinepass']['tablecode'] = $va['tablecode'];
					$arr['timelinepass']['typecode'] = $va['typecode'];
					$arr['timelinepass']['id'] = $va['id'];
					$arr['timelinepass']['title'] = $va['title'];
					$arr['timelinepass']['date'] = $va['date'];
					$arr['timelinepass']['url'] = $va['url'];
					$arr_result[] = $arr['timelinepass'];
				}
			}
	    $data['timeline_pass']=$arr_result;
       */
	 
		$count=count($data['timeline_pass']);
		if($count==0){$data['timeline_pass'] =Null;}
				 if($data){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count_media'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'count_media'=>$count,),
											'data'=> Null),404);
					}
	}
	public function admissionsgang_get() {
		$id=@$this->input->get('id');
			if($id==Null){$id=186;} 
		$episode_id=@$this->input->get('episode_id');
			if($episode_id==Null){$episode_id=Null;} 
	    $limit=@$this->input->get('limit');
			if($limit==Null){$limit=2;} 
			
		// http://www.trueplookpanya.com/api/center/admissionsgang?id=186&episode_id=&limit=2
		
        $this->load->library('TPPY_Utils');
        $this->load->model('admissions/admissions_models', 'am');
        $this->load->model('mobile/admission_model', 'amm');
		$this->load->model('api/centermobile_model', 'centermobile');
        $this->load->helper('tppy_helper');
	   $data['admissionsgang'] = $this->centermobile->tvEpisode($id,$episode_id,$limit);
	   //tvEpisode($id = 186, $episode_id = NULL, $limit = 2)
		$count=count($data['admissionsgang']);
		if($count==0){$data['admissionsgang'] =Null;}
				 if($data){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'count'=>$count,),
											'data'=> Null),404);
					}
	}
	public function campaignviewlist_get() { 
	    $content_view_id=@$this->input->get('content_view_id');
	     if($content_view_id==Null){$content_view_id=Null;} 
		$limit=@$this->input->get('limit');
	     if($limit==Null){$limit=100;} 
        $this->load->library('TPPY_Utils');
        $this->load->helper('tppy_helper');
		$this->load->model('api/centermobile_model', 'centermobile_model');
	    $data=$this->centermobile_model->get_campaign_list_view($content_view_id,$table='campaign_work',$limit);
		if($content_view_id!==Null){
			$data=$data[0];
		}
		$count=count($data);
		if($count==0){$data =Null;}
				 if($data){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count_media'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'count_media'=>$count,),
											'data'=> Null),404);
					}
	}
	public function campaignviewlistupdate_get() { 
	    $content_view_id=@$this->input->get('content_view_id');
	     if($content_view_id==Null){$content_view_id=18;} 
        $this->load->library('TPPY_Utils');
        $this->load->helper('tppy_helper');
		$this->load->model('api/centermobile_model', 'centermobile_model');
		//expiration time (a*b*c*d) <- change D corresponding to number of days for cookie expiration
		//$time = time()+(60*60*24*365);
		$time = time()+(300);
		$timeMemo = (string)$time;
		$ip_address=@$this->input->get('ip_address');
		if($ip_address==Null){ $ip_address=$this->get_ip_address(); }else{$ip_address=@$this->input->get('ip_address');}
		//sets cookie with expiration time defined above
		setcookie("ip_address_cookie", "" . $ip_address . "", $time);
		$ip_address_cookie=@$_COOKIE['ip_address_cookie'];
		if(isset($ip_address_cookie)){
		    $cookieip=$ip_address_cookie;
			$dataview=$this->centermobile_model->get_campaign_listss_view($content_view_id,$table='campaign_work');
		}else{
			$cookieip=Null;
			$dataview=$this->centermobile_model->get_campaign_update_view($content_view_id,$table='campaign_work');
		}
		$data['view']=$dataview;
		$data['ipaddress']= $ip_address;
		$count=count($data['view']);
		if($count==0){$data =Null;}
				 if($data){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count_media'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'count_media'=>$count,),
											'data'=> Null),404);
					}
	}
  ///////////////////////////
  	public function testusersession_get() { 
	
	$url=base_url('/api/center/webboardsubjectpost');
	$url2=base_url('/api/center/webboardreplypost');
	
	    
	?>
			<form action="<?php echo $url;?>" method="post" enctype="ret" name="form1" id="form1">
			<table width="428" border="0">
			<tr>
				<td colspan="2"><div align="center">post </div></td>
			</tr>
			<tr>
			  <td>token</td>
			  <td><input name="token" type="text" id="token" /></td>
			</tr>
			<tr>
			  <td width="92">title </td>
			  <td width="326"><input name="title" type="text" id="title" /></td>
			</tr>
			<tr>
			  <td>description_long</td>
			  <td><input name="description_long" type="text" id="description_long" /></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td><input type="submit" name="Submit" value="LOGIN" /></td>
			</tr>
			
			 
		  </table>
		  </form>
		  
		  
		  <hr>
		  
	 
		  
			<form action="<?php echo $url2;?>" method="post" enctype="ret" name="form1" id="form1">
			<table width="428" border="0">
			<tr>
				<td colspan="2"><div align="center">post </div></td>
			</tr>
			<tr>
			  <td>token</td>
			  <td><input name="token" type="text" id="token" /></td>
			</tr>
			<tr>
			  <td width="92">post_id </td>
			  <td width="326"><input name="post_id" type="text" id="post_id" /></td>
			</tr>
			<tr>
			  <td>comment</td>
			  <td><input name="comment" type="text" id="comment" /></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td><input type="submit" name="Submit" value="LOGIN" /></td>
			</tr>
			
			 
		  </table>
		  </form>
	<?php	
	}
	////////////////  บทความ admissions
	public function articles_get() {
	///////
	 if ($this->session->userdata('user_session') != NULL && $this->session->userdata('user_session') != '') {
            $this->me = $this->session->userdata('user_session');
            if (intval($this->me->user_permission) == 6 || intval($this->me->user_permission) == 1) {
                $this->isAdmin_AMS = true;
            }
        }
	////////
	
	// http://www.trueplookpanya.com/api/center/articles?q=&f=&order=desc
	
			$q = @$this->input->get('q');
			$f = @$this->input->get('f');
			$f = (int)$f;
			
			
			
			if ($f > 0) {
				$faculty = $f;
			}//else{
			// $faculty = 39;
			// }

			$order = @$this->input->get('order') != '' ? $this->input->get('order') : 'sort';
			// $data['num_all'] = $this->db->query("select * from cms WHERE cms_category_id=135")->num_rows();
			// $offset = 0;
			// $limit = 33;
			//
			 // $data['num_rows'] = $this->db->query("select * from cms WHERE cms_category_id=135 AND CONCAT_WS(' ',cms_subject, seo_keywords, hashtag) LIKE %q% ORDER BY $sort", array('%q%' => "%$q%"))->num_rows();
			// $data['cms'] = $this->db->query("select * from cms WHERE cms_category_id=135 AND CONCAT_WS(' ',cms_subject, seo_keywords, hashtag) LIKE %q% ORDER BY $sort LIMIT $offset, $limit ", array('%q%' => "%$q%"))->result_array();
			//
			 // _vd($data);
			$data['top_baner'] = $this->banner_model->bannerV3(13, 5);
			//$count = $this->amm->news_balloon('1900');
			//$data['resut_all'] = $count; //บทความทั้งหมวด
			
/*
			echo '<pre> q=>'; print_r($q); echo '</pre>';   
			echo '<pre> f=>'; print_r($f); echo '</pre>';  
			echo '<pre> order=>'; print_r($order); echo '</pre>'; 
			echo '<pre> $data=>'; print_r($data); echo '</pre>'; Die();
*/

			$data['q'] = $q;
			$data['fac_id'] = $faculty;
			$data['isLogin'] = $this->me->user_id != NULL ? $this->me->user_id : NULL;

			$data['resut_all'] = count($this->getRelate_model->getRelate_admissionNews(null, 0, null, null, null, $textSearch = null, array()));
			$data['resut_count'] = count($this->getRelate_model->getRelate_admissionNews(null, 0, null, null, $facultyid = $faculty, $textSearch = $q, $filter)); //ผลการค้นหา
			$data['cmsnew'] = $this->getRelate_model->getRelate_admissionNews($content_id=null, $limit=1, $offset=null, $order='desc', $facultyid=null, $textSearch=null, $filter=null);
			$data['cms'] = $this->getRelate_model->getRelate_admissionNews($content_id = null, $limit = 33, $offset = null, $order, $facultyid = $faculty, $textSearch = $q, $filter);
			$data['isFavorite'] = $this->favorite_model->get_user_favorite_list_idScalar($this->me->user_id, $key = 4);
			//_vd($data['isFavorite']);
			$data['cms_mobile'] = $data['cms'];
			$count=count($data['cms_mobile'] );
			if($count==0){$data =Null;}
				 if($data){
					 //$this->response(array('response'=>$data['response'], 'data'=>$data["data"])); Die();
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'count'=>$count,),
											'data'=> Null),404);
					}
		}	
		///News Adminition Start
	public function newsdetail_get(){
			$CI = & get_instance();
            $this->db = $this->load->database('select', TRUE);
            $this->db->set_dbprefix('');
			$data = array();
			$data = $this->_set_response();
			
			if(isset($_GET['id']) && !empty($_GET['id'])){
				$cms_id = trim($_GET['id']);
			}else{
				$data = $this->_set_response_error("[e01] invalid parameter sending", 401);
				$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
				exit();
			}
			if(isset($_GET['favkey']) && !empty($_GET['favkey'])){			
				$favkey = trim($_GET['favkey']);
			}
			$catid=null;
			
			$this->load->model('api/getRelate_model');
			$this->load->model('mobile/tv_model');
			
			// node1 : main detail
			if($cms_id>1000000){
				// -- start TV Program Episode
				$qResult =$this->tv_model->getEpisodeDetail($tve_id = $cms_id-1000000);
				$arr_result = array();
				if($qResult){
					foreach($qResult as $key => $v){
						$arr = array();
						
						$htmlFILE = "";
						if($v['vdo_type'] != "Youtube"){
							$path_vdo_full = $this->trueplook->set_media_path_full('vdo', 'no');
							$static_folder      = $this->trueplook->get_media_path('vdo');
							$file_vdo_name      = $this->trueplook->get_vdo_qty($path_vdo_full . $v['tv_vdo_path'] . '/' . $v['tv_vdo_filename'], $static_folder . $v['tv_vdo_path'] . '/' . $v['tv_vdo_filename']);
							$file_vdo_name_hd   = $this->trueplook->check_hd($path_vdo_full . $v['tv_vdo_path'] . '/' . $v['tv_vdo_filename'], $static_folder . $v['tv_vdo_path'] . '/' . $v['tv_vdo_filename']);
							if(is_null($file_vdo_name_hd)){
								$vdo = $file_vdo_name;
							}else{
								$vdo = $file_vdo_name_hd;
							}
							$arr['d']['vdo_url'] = $vdo;
							
							$htmlFILE .=' 
							<!-- START #player -->
							<link href="http://www.trueplookpanya.com/assets/video-js/video-js.css" rel="stylesheet" type="text/css" />  
							<script type="text/javascript" src="http://www.trueplookpanya.com/assets/video-js/video.js"></script>
											<strong>'. $v["title"] .'</strong>
											<div id="player_<?= $k ?>">
												<center>
													<video id="example_video_<?= $k ?>" class="video-js vjs-default-skin vjs-big-play-centered"
														   controls width="" height="350"
														   poster="'. $v['thumbnail'] .'"
														   data-setup=\'{ "controls": true, "autoplay": false, "preload": "auto" }\'>
														<source src="'. $this->trueplook->get_vdo_url($v['file_path'], $v['file_name']) .'" type="video/mp4" />                            
														<p class="vjs-no-js">To view this<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
													</video>
												</center>
											</div>
											<br>
											<!-- END #player -->
							';
							
						}else{
							$vdo_face = $v['vdo_url'] . '?version=3&amp;autohide=1';
							$arr['d']['vdo_url'] =  $v['vdo_url'];
							
							$htmlFILE .= '<div class="embeddedContent oembed-provider- oembed-provider-youtube" data-align="center" data-maxheight="" data-maxwidth="" data-oembed="https://www.youtube.com/watch?v='.$v['youtube_code'].'" data-oembed_provider="youtube" data-resizetype="responsive" style="text-align: center;">
							<iframe allowfullscreen="true" allowscriptaccess="always" frameborder="0" height="349" scrolling="no" src="//www.youtube.com/embed/'.$v['youtube_code'].'?wmode=transparent&amp;jqoemcache=WL4Qr" width="425">
							</iframe></div>';
						}
						
						if($key==count($qResult)-1){
							$arr = array();		// for get only 1 record for PARENT
							//$arr['d']['id'] = $v['mul_source_id'];
							$arr['d']['content_type'] = $v['type'];
							$arr['d']['content_id'] = $cms_id;
							$arr['d']['content_child_id'] = 'null';
							$arr['d']['topic'] = $v['title'];
							$arr['d']['web_url'] = $v['web_url'];
							$arr['d']['thumbnail'] = $v['thumbnail'];
							$arr['d']['viewcount'] =  intval(str_replace(",","",$this->trueplook->getViewCenter($v['tv_episode_id'],'tv_program_episode','media')));
							$arr['d']['addDateTime'] = $v['addDateTime'];
							$arr['d']['addBy'] = $v['addBy'];
							$arr['d']['category_id'] =  'null';
							$arr['d']['category_name'] =  'null';
							$arr['d']['short_detail'] = 'รายการดีๆจากช่องทรูปลูกปัญญา';
							$arr['d']['full_detail'] = $htmlFILE."<link rel=\"stylesheet\" href=\"http://www.trueplookpanya.com/assets/tppy_v1/styles/is_mobile.css\"> ".$v['description'];
							
							// favorite
							if($favkey==""){
								$key=3;
							}else{
								$key=$favkey;
							}
							$isFavorite=$this->favorite_model->get_user_favorite($this->me->user_id, $key, $content_id=$cms_id);
							$arr['d']['isFavorite'] = $isFavorite;
							
								
							// node Social
							$arr['d']['og']['og:title'] = $v['title'];
							$arr['d']['og']['og:type'] = 'website';
							$arr['d']['og']['og:url'] = $v['url'];
							$arr['d']['og']['og:image'] = $v['thumbnail'];
							$arr['d']['og']['og:site_name'] = 'trueplookpanya.com';
							$arr['d']['og']['og:description'] = $this->trueplook->limitText(strip_tags(str_replace('\'', ' ', str_replace('\"', ' ', $v['short_detail']))), 200);
							
							
							$arr_result[] = $arr['d'];
						}
					}
					$data["data"]["NewsDetail"] = $arr_result;
				}else{//no data
					$data = $this->_set_response_error("[e01] data not found", 404);
				}
				// -- end TV Program Episode
			}elseif($cms_id>49999){
				// -- start CMSBLOG
				$qResult =$this->getRelate_model->getDetail_cmsblog($cmsblogID=$cms_id, $arrFilter = array());
				$arr_result = array();
				$htmlFILE="";
				if(is_array($qResult)){
					//var_dump($qResult);exit();
					foreach($qResult as $key => $v){
						//var_dump($v);
						foreach($v['files'] as $a => $f){
							//var_dump($f);
							$htmlFILE .= $f['HTML_tag'];
						}
						
						// cms content as PARENT 
						if($key==count($qResult)-1){
							$arr = array();		// for get only 1 record for PARENT
							//$arr['d']['id'] = $v['mul_source_id'];
							$arr['d']['content_type'] = $v['content_type'];
							$arr['d']['content_id'] = $v['content_id'];
							$arr['d']['content_child_id'] = 'null';
							$arr['d']['topic'] = $v['topic'];
							$arr['d']['web_url'] = $v['web_url'];
							$arr['d']['thumbnail'] = $v['thumbnail'];
							$arr['d']['viewcount'] =  $v['viewcount'];
							$arr['d']['addDateTime'] = $v['addDateTime'];
							$arr['d']['addBy'] = $v['addBy'];
							$arr['d']['category_id'] =  'null';
							$arr['d']['category_name'] =  'null';
							$arr['d']['short_detail'] = $v['short_detail'];
							$arr['d']['full_detail'] = $htmlFILE."<link rel=\"stylesheet\" href=\"http://www.trueplookpanya.com/assets/tppy_v1/styles/is_mobile.css\"> ".$v['long_detail']; // << ยังเหลือ credit + tag
							
							$catid=$v['mul_category_id'];
							// favorite
							if($favkey==""){
								$key=14;
							}else{
								$key=$favkey;
							}
							$isFavorite=$this->favorite_model->get_user_favorite($this->me->user_id, $key, $content_id=$v['content_id']);
							$arr['d']['isFavorite'] = $isFavorite;
							
								
							// node Social
							$arr['d']['og']['og:title'] = $v['title'];
							$arr['d']['og']['og:type'] = 'website';
							$arr['d']['og']['og:url'] = $v['url'];
							$arr['d']['og']['og:image'] = $v['thumbnail'];
							$arr['d']['og']['og:site_name'] = 'trueplookpanya.com';
							$arr['d']['og']['og:description'] = $this->trueplook->limitText(strip_tags(str_replace('\'', ' ', str_replace('\"', ' ', $v['short_detail']))), 200);
							
							
							$arr_result[] = $arr['d'];
						}
						
						$arrRelate = $v['relate_content']['relate_by_menu'];
					}
					$data["data"]["NewsDetail"] = $arr_result;
					
					// // node2 : relate content
					// $data["data"]["RelateContent"] = $arrRelate;
					
				}else{//no data
					$data = $this->_set_response_error("[e01] data not found", 404);
				}
				// -- end CMSBLOG
			}else{
				// -- start CMS
				$qResult =$this->tv_model->getCMSDetail_And_File($cms_id);
				$arr_result = array();
				$htmlFILE="";
				if(is_array($qResult)){
					foreach($qResult as $key => $v){
						
						// File as Child
						if ($v['file_type'] == 'v') {
							$htmlFILE .=' 
							<!-- START #player -->
							<link href="http://www.trueplookpanya.com/assets/video-js/video-js.css" rel="stylesheet" type="text/css" />  
							<script type="text/javascript" src="http://www.trueplookpanya.com/assets/video-js/video.js"></script>
											<strong>'. $v["file_title"] .'</strong>
											<div id="player_<?= $k ?>">
												<center>
													<video id="example_video_<?= $k ?>" class="video-js vjs-default-skin vjs-big-play-centered"
														   controls width="" height="350"
														   poster="'. $v['thumbnail'] .'"
														   data-setup=\'{ "controls": true, "autoplay": false, "preload": "auto" }\'>
														<source src="'. $this->trueplook->get_vdo_url($v['file_path'], $v['file_name']) .'" type="video/mp4" />                            
														<p class="vjs-no-js">To view this<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
													</video>
												</center>
											</div>
											<br>
											<!-- END #player -->
							';
						} else if ($v['file_type'] == 'a') {
							$htmlFILE .='
							<style type="text/css" title="audiojs">.audiojs audio { position: absolute; left: -1px; }         .audiojs { width: 460px; height: 36px; background: #404040; overflow: hidden; font-family: monospace; font-size: 12px;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #444), color-stop(0.5, #555), color-stop(0.51, #444), color-stop(1, #444));           background-image: -moz-linear-gradient(center top, #444 0%, #555 50%, #444 51%, #444 100%);           -webkit-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); -moz-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3);           -o-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); }         .audiojs .play-pause { width: 25px; height: 40px; padding: 4px 6px; margin: 0px; float: left; overflow: hidden; border-right: 1px solid #000; }         .audiojs p { display: none; width: 25px; height: 40px; margin: 0px; cursor: pointer; }         .audiojs .play { display: block; }         .audiojs .scrubber { position: relative; float: left; width: 280px; background: #5a5a5a; height: 14px; margin: 10px; border-top: 1px solid #3f3f3f; border-left: 0px; border-bottom: 0px; overflow: hidden; }         .audiojs .progress { position: absolute; top: 0px; left: 0px; height: 14px; width: 0px; background: #ccc; z-index: 1;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ccc), color-stop(0.5, #ddd), color-stop(0.51, #ccc), color-stop(1, #ccc));           background-image: -moz-linear-gradient(center top, #ccc 0%, #ddd 50%, #ccc 51%, #ccc 100%); }         .audiojs .loaded { position: absolute; top: 0px; left: 0px; height: 14px; width: 0px; background: #000;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #222), color-stop(0.5, #333), color-stop(0.51, #222), color-stop(1, #222));           background-image: -moz-linear-gradient(center top, #222 0%, #333 50%, #222 51%, #222 100%); }         .audiojs .time { float: left; height: 36px; line-height: 36px; margin: 0px 0px 0px 6px; padding: 0px 6px 0px 12px; border-left: 1px solid #000; color: #ddd; text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.5); }         .audiojs .time em { padding: 0px 2px 0px 0px; color: #f9f9f9; font-style: normal; }         .audiojs .time strong { padding: 0px 0px 0px 2px; font-weight: normal; }         .audiojs .error-message { float: left; display: none; margin: 0px 10px; height: 36px; width: 400px; overflow: hidden; line-height: 36px; white-space: nowrap; color: #fff;           text-overflow: ellipsis; -o-text-overflow: ellipsis; -icab-text-overflow: ellipsis; -khtml-text-overflow: ellipsis; -moz-text-overflow: ellipsis; -webkit-text-overflow: ellipsis; }         .audiojs .error-message a { color: #eee; text-decoration: none; padding-bottom: 1px; border-bottom: 1px solid #999; white-space: wrap; }                 .audiojs .play { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -1px no-repeat; }         .audiojs .loading { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -31px no-repeat; }         .audiojs .error { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -61px no-repeat; }         .audiojs .pause { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -91px no-repeat; }                 .playing .play, .playing .loading, .playing .error { display: none; }         .playing .pause { display: block; }                 .loading .play, .loading .pause, .loading .error { display: none; }         .loading .loading { display: block; }                 .error .time, .error .play, .error .pause, .error .scrubber, .error .loading { display: none; }         .error .error { display: block; }         .error .play-pause p { cursor: auto; }         .error .error-message { display: block; }</style>
											<script src="http://www.trueplookpanya.com/new/assets/javascript/audiojs/audio.min.js"></script>   
											<script>
															audiojs.events.ready(function () {
																var as = audiojs.createAll();
															});
											</script>
											<div class="col-lg-12 col-xs-12 mt-10 pl-0 pr-0">
												<center>
													<div style="width:auto; margin:30px auto" id="audio_area"><audio src="http://www.trueplookpanya.com/data/product/media/' . $v['file_path'] . '/' . $v['file_name'] .'" preload="auto" /></div>
												</center>
											</div>
							';
						} else if ($v['file_type'] == 'd') {
							$htmlFILE .='
							<div class="col-lg-12 col-xs-12 mt-10 pl-0 pr-0">
												<div style="display: table;width:auto; border:1px solid #CCC; margin:20px auto; padding:10px">
													<div>
														<div style="float:left"><a href="http://www.trueplookpanya.com/data/product/media/' . $v['file_path'] . '/' . $v['file_name'] .'" target="_blank" title="โหลดเอกสารเรื่อง '. $v['title'] .'" ><img src="http://www.trueplookpanya.com/new/assets/images/icon/icon_download.png" width="48" height="48" border="" alt=""  /></a></div>
														<div style="float:left; margin-left:10px">
															<div style="padding-left:10px"><a href="http://www.trueplookpanya.com/data/product/media/' . $v['file_path'] . '/' . $v['file_name'] .'" target="_blank" title="โหลดเอกสารเรื่อง '. $v['title'] .'" >เอกสารแนบ</a></div>
															<div><div style="float:left ; margin:5px 5px 0px 0px; "><img src="http://www.trueplookpanya.com/new/assets/images/icon/'. substr($v['file_name'], -3) .'.gif" border="0" alt="" height="30"  /></div><div style="float:left; margin-top:5px">ขนาด : '. $this->trueplook->get_mb($v['file_size']) .' MB</div></div>    
														</div>
													</div>
												</div>
											</div>
							';
						}
						
						// cms content as PARENT 
						if($key==count($qResult)-1){
							$arr = array();		// for get only 1 record for PARENT
							//$arr['d']['id'] = $v['mul_source_id'];
							$arr['d']['content_type'] = $v['type'];
							$arr['d']['content_id'] = $v['content_id'];
							$arr['d']['content_child_id'] = $v['content_child_id'];
							$arr['d']['topic'] = $v['title'];
							$arr['d']['web_url'] = $v['url'];
							$arr['d']['thumbnail'] = $v['thumbnail'];
							$arr['d']['viewcount'] =  intval(str_replace(",","",$this->trueplook->getViewNumber($v['content_id'],21)));	//$v['viewcount'];
							$arr['d']['addDateTime'] = $v['addDateTime'];
							$arr['d']['addBy'] = $v['addBy'];
							$arr['d']['category_id'] = $v['mul_category_id'];
							$arr['d']['category_name'] = $v['mul_category_name'];
							$arr['d']['short_detail'] = $v['short_detail'];
							$arr['d']['full_detail'] = $htmlFILE."<link rel=\"stylesheet\" href=\"http://www.trueplookpanya.com/assets/tppy_v1/styles/is_mobile.css\"> ".$v['full_detail'].$v['credit_by'];
							
							$catid=$v['mul_category_id'];
							// favorite
							if($favkey==""){
								$key=14;
							}else{
								$key=$favkey;
							}
							$isFavorite=$this->favorite_model->get_user_favorite($this->me->user_id, $key, $content_id=$v['content_id']);
							$arr['d']['isFavorite'] = $isFavorite;
							
								
							// node Social
							$arr['d']['og']['og:title'] = $v['title'];
							$arr['d']['og']['og:type'] = 'website';
							$arr['d']['og']['og:url'] = $v['url'];
							$arr['d']['og']['og:image'] = $v['thumbnail'];
							$arr['d']['og']['og:site_name'] = 'trueplookpanya.com';
							$arr['d']['og']['og:description'] = $this->trueplook->limitText(strip_tags(str_replace('\'', ' ', str_replace('\"', ' ', $v['short_detail']))), 200);
							
							
							$arr_result[] = $arr['d'];
						}
					}
					$data["data"]["NewsDetail"] = $arr_result;
				}else{//no data
					$data = $this->_set_response_error("[e01] data not found", 404);
				}
				
				// // node2 : relate content
				// $this->load->model('api/getRelate_model');
				// $qResult=array(); $content_id=null;  $order="rand"; 
				// $qResult = $this->getRelate_model->getRelate_cms($content_id, $limit =2, $offset = 0, $order, $catid);
				// $arr_result = array();
				// if($qResult){
					// foreach($qResult as $key => $v){
						// $arr = array();
						// $arr['d']['content_type'] = $v['type'];
						// $arr['d']['content_id'] = $v['content_id'];
						// $arr['d']['content_child_id'] = $v['content_child_id'];
						// $arr['d']['topic'] = $v['title'];
						// $arr['d']['web_url'] = $v['url'];
						// $arr['d']['thumbnail'] = $v['thumbnail'];
						// $arr['d']['viewcount'] =  intval(str_replace(",","",$this->trueplook->getViewNumber($v['content_id'],21)));	
						// $arr['d']['addDateTime'] = $v['addDateTime'];
						// $arr['d']['addBy'] = $v['addBy'];
						// $arr['d']['mul_category_id'] = $v['mul_category_id'];
						// $arr['d']['mul_category_name'] = $v['mul_category_name'];
						// $arr['d']['short_detail'] = $v['short_detail'];
						// $arr_result[] = $arr['d'];
					// }
				// }
				// $data["data"]["RelateContent"] = $arr_result;							
				// -- end CMS
			}
			
			// // node2 : relate content
			//$filter['moreCriteria'] = "";
			$qResult = $this->getRelate_model->getRelate_admissionNews($cms_id, 3, null, $order="random", $catid=null , null, $filter=array());
			$arr_result = array();
			if(is_array($qResult)){
				foreach($qResult as $key => $v){
					$arr = array();
					$arr['d']['content_type'] = $v['type'];
					$arr['d']['content_id'] = $v['content_id'];
					$arr['d']['content_child_id'] = $v['content_child_id'];
					$arr['d']['topic'] = $v['title'];
					$arr['d']['web_url'] = $v['url'];
					$arr['d']['thumbnail'] = $v['thumbnail'];
					$arr['d']['viewcount'] =  intval(str_replace(",","",$this->trueplook->getViewNumber($v['content_id'],21)));
					$arr['d']['addDateTime'] = $v['addDateTime'];
					$arr['d']['addBy'] = $v['addBy'];
					$arr['d']['mul_category_id'] = $v['mul_category_id'];
					$arr['d']['mul_category_name'] = $v['mul_category_name'];
					$arr['d']['short_detail'] = $v['short_detail'];
					
					// favorite
					if($this->myfav==1){
						$isFavorite =true;
					}else{
						$isFavorite=$this->favorite_model->get_user_favorite($this->me->user_id, $favkey, $content_id=$v['content_id']);
					}
					$arr['d']['isFavorite'] = $isFavorite;
					
					$arr_result[] = $arr['d'];
				}
			}
			$data["data"]["RelateContent"] = $arr_result;

			//$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
			
			
			$count=count($data["data"] );
			$data=$data["data"];
			if($count==0){$data =Null;}
				 if($data){
					 $this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'count'=>$count,),
											'data'=> Null),404);
					}
			
			
			
	}
	public function newscenter_get(){
		$CI = & get_instance();
		$this->db = $this->load->database('select', TRUE);
		$this->db->set_dbprefix('');
		$filter = array();
		
		if(isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = trim($_GET['limit']);
		}else{
			$limit = 10;
		}
		if(isset($_GET['offset']) && !empty($_GET['offset'])){
			$offset = trim($_GET['offset']);
		}
		if(isset($_GET['q']) && !empty($_GET['q'])){
			$q = trim($_GET['q']);
		}
		
		if(isset($_GET['catid']) && !empty($_GET['catid'])){
			$catid = trim($_GET['catid']);
			$filter['catid'] = $catid;
		}
		if(isset($_GET['favkey']) && !empty($_GET['favkey'])){
			$favkey = trim($_GET['favkey']);
			$filter['favkey'] = $favkey;
		}

		$groupFavID = "";
		if($this->myfav==1){
			$userid=$this->me->user_id;
			$i=0;
			foreach(explode(',',$favkey) as $fkey) {
			  if(isset($this->favorite_model->define_data[$fkey])) {
				if($i>0) $groupFavID .= ",";
				$groupFavID .= $this->favorite_model->get_user_favorite_list_idScalar($userid, $fkey);
				$i++;
			  }
			}
			if($groupFavID=="") $groupFavID="0,0";
			$filter['groupIDFilter'] = $groupFavID;
		}
		//var_dump($filter['groupFavID'] );exit();
		
		$data = array();
		$data = $this->_set_response();
		
		$this->load->model('api/getRelate_model');
		$qResult=array(); $content_id=null;  $order="last"; $textSearch = $q;
		$qResult = $this->getRelate_model->getRelate_cms($content_id, $limit, $offset, $order, $catid,$textSearch,$filter);
		$arr_result = array();
		if(is_array($qResult)){
			foreach($qResult as $key => $v){
				$arr = array();
				$arr['d']['content_type'] = $v['type'];
				$arr['d']['content_id'] = $v['content_id'];
				$arr['d']['content_child_id'] = $v['content_child_id'];
				$arr['d']['topic'] = $v['title'];
				$arr['d']['web_url'] = $v['url'];
				$arr['d']['thumbnail'] = $v['thumbnail'];
				$arr['d']['viewcount'] = intval(str_replace(",","",$this->trueplook->getViewNumber($v['content_id'],21)));	
				$arr['d']['addDateTime'] = $v['addDateTime'];
				$arr['d']['addBy'] = $v['addBy'];
				$arr['d']['mul_category_id'] = $v['mul_category_id'];
				$arr['d']['mul_category_name'] = $v['mul_category_name'];
				$arr['d']['short_detail'] = $v['short_detail'];
				
				// favorite
				if($this->myfav==1){
					$isFavorite =true;
				}else{
					$isFavorite=$this->favorite_model->get_user_favorite($this->me->user_id, $favkey, $content_id=$v['content_id']);
				}
				$arr['d']['isFavorite'] = $isFavorite;
				
				$arr_result[] = $arr['d'];
			}
		}
		$data["data"] = $arr_result;
		
		$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
	}
	public function newstv_get(){
		$CI = & get_instance();
		$this->db = $this->load->database('select', TRUE);
		$this->db->set_dbprefix('');
		$filter = array();
		
		if(isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = trim($_GET['limit']);
		}else{
			$limit = 10;
		}
		if(isset($_GET['offset']) && !empty($_GET['offset'])){
			$offset = trim($_GET['offset']);
		}
		if(isset($_GET['q']) && !empty($_GET['q'])){
			$q = trim($_GET['q']);
		}
		
		$favkey=1;
		$filter['favkey'] = $favkey;
		$groupFavID = "";
		if($this->myfav==1){
			$userid=$this->me->user_id;
			$i=0;
			foreach(explode(',',$favkey) as $fkey) {
			  if(isset($this->favorite_model->define_data[$fkey])) {
				if($i>0) $groupFavID .= ",";
				$groupFavID .= $this->favorite_model->get_user_favorite_list_idScalar($userid, $fkey);
				$i++;
			  }
			}
			$filter['groupFavID'] = $groupFavID;
		}
		//var_dump($filter['groupFavID'] );exit();
		
		$data = array();
		$data = $this->_set_response();
		
		$this->load->model('api/getRelate_model');
		$qResult=array(); $content_id=null;  $order="last"; $catid=140; $textSearch = $q;
		$qResult = $this->getRelate_model->getRelate_cms($content_id, $limit, $offset, $order, $catid,$textSearch,$filter);
		$arr_result = array();
		if(is_array($qResult)){
			foreach($qResult as $key => $v){
				$arr = array();
				$arr['d']['content_type'] = $v['type'];
				$arr['d']['content_id'] = $v['content_id'];
				$arr['d']['content_child_id'] = $v['content_child_id'];
				$arr['d']['topic'] = $v['title'];
				$arr['d']['web_url'] = $v['url'];
				$arr['d']['thumbnail'] = $v['thumbnail'];
				$arr['d']['viewcount'] =  intval(str_replace(",","",$this->trueplook->getViewNumber($v['content_id'],21)));	
				$arr['d']['addDateTime'] = $v['addDateTime'];
				$arr['d']['addBy'] = $v['addBy'];
				$arr['d']['mul_category_id'] = $v['mul_category_id'];
				$arr['d']['mul_category_name'] = $v['mul_category_name'];
				$arr['d']['short_detail'] = $v['short_detail'];
				
				// favorite
				if($this->myfav==1){
					$isFavorite =true;
				}else{
					$isFavorite=$this->favorite_model->get_user_favorite($this->me->user_id, $favkey, $content_id=$v['content_id']);
				}
				$arr['d']['isFavorite'] = $isFavorite;
				
				$arr_result[] = $arr['d'];
			}
		}
		$data["data"] = $arr_result;
		
		$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
	}
	public function newsadmissions_get(){
		$CI = & get_instance();
		$this->db = $this->load->database('select', TRUE);
		$this->db->set_dbprefix('');
		$filter = array();
		
		if(isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = trim($_GET['limit']);
		}else{
			$limit = 10;
		}
		if(isset($_GET['offset']) && !empty($_GET['offset'])){
			$offset = trim($_GET['offset']);
		}
		if(isset($_GET['q']) && !empty($_GET['q'])){
			$q = trim($_GET['q']);
		}
		
		$favkey=4;
		$filter['favkey'] = $favkey;
		$groupFavID = "";
		if($this->myfav==1){
			$userid=$this->me->user_id;
			$i=0;
			foreach(explode(',',$favkey) as $fkey) {
			  if(isset($this->favorite_model->define_data[$fkey])) {
				if($i>0) $groupFavID .= ",";
				$groupFavID .= $this->favorite_model->get_user_favorite_list_idScalar($userid, $fkey);
				$i++;
			  }
			}
			$filter['groupFavID'] = $groupFavID;
		}
		//var_dump($filter['groupFavID'] );exit();
		
		$data = array();
		$data = $this->_set_response();
		
		// node1 : TopBanner
		$arr = array();
		$arr['d']['title'] = "ข่าวแอดมิสชั่น";
		$arr['d']['thumbnail'] = "http://static.trueplookpanya.com/tppy/app/images/banner_directapplynews.png";// "http://static.trueplookpanya.com/tppy/banner/banner/57da345548a30152693641.png";
		$arr['d']['url'] ="";
		$data["data"]["TopBanner"][] = $arr["d"];
		
		$this->load->model('api/getRelate_model');
		//$filter['moreCriteria'] = "";
		$filter['list4App']=true;
		$qResult = $this->getRelate_model->getRelate_admissionNews($content_id = null, $limit, $offset, $order="sort", $catid=null , $textSearch=$q, $filter);
		$arr_result = array();
		if(is_array($qResult)){
			foreach($qResult as $key => $v){
				$arr = array();
				$arr['d']['content_type'] = $v['type'];
				$arr['d']['content_id'] = $v['content_id'];
				$arr['d']['content_child_id'] = $v['content_child_id'];
				$arr['d']['topic'] = $v['title'];
				$arr['d']['web_url'] = $v['url'];
				$arr['d']['thumbnail'] = $v['thumbnail'];
				$arr['d']['viewcount'] =  $v['viewcount'];// intval(str_replace(",","",$this->trueplook->getViewNumber($v['content_id'],21)));
				$arr['d']['addDateTime'] = $v['addDateTime'];
				$arr['d']['addBy'] = $v['addBy'];
				$arr['d']['mul_category_id'] = $v['mul_category_id'];
				$arr['d']['mul_category_name'] = $v['mul_category_name'];
				$arr['d']['short_detail'] = $v['short_detail'];
				
				// favorite
				if($this->myfav==1){
					$isFavorite =true;
				}else{
					$isFavorite=$this->favorite_model->get_user_favorite($this->me->user_id, $favkey, $content_id=$v['content_id']);
				}
				$arr['d']['isFavorite'] = $isFavorite;
				
				$arr_result[] = $arr['d'];
			}
		}
		$data["data"]['news_admissions'] = $arr_result;
		
		$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
		}
	public function feedhome_get(){
			$CI = & get_instance();
			$this->db = $this->load->database('select', TRUE);
			$data = array();
			$data = $this->_set_response();
			//- start code here
			
			$this->load->model('api/getRelate_model');
			$this->load->model('home/Home_models');
			
			
			// node : TopBanner
			// $url="http://www.trueplookpanya.com/api/banner/bannerV3/1";
			// $ch= curl_init();
			// curl_setopt($ch, CURLOPT_URL,$url);
			// curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
			// $cdata = json_decode(curl_exec($ch));
			$cdata = $this->Home_models->getBannerHeader();
			//var_dump($cdata);exit();
			$arr = array(); $i=0;
			foreach($cdata as $k=>$v){
				$i++;
				$arr['d'][$k]['title'] = $v['title'];
				$arr['d'][$k]['thumbnail'] = $v['thumbnail'];
				$arr['d'][$k]['url'] = $v['link'];
				if($i==3){break;}
			}
			$data["data"]["TopBanner"] = $arr['d'];
			
			// node : Highlight
			$cdata = $this->Home_models->getHighlightKnowledge();
			//var_dump($cdata);exit();
			$arr = array(); $i=0;
			foreach($cdata as $k=>$v){
				$i++;
				$arr['d'][$k]['title'] = $v->title;
				$arr['d'][$k]['thumbnail'] = $v->image;
				$arr['d'][$k]['url'] = $v->link;
				$arr['d'][$k]['description'] = $v->description;
				if($i==3){break;}
			}
			$data["data"]["Highlight"] = $arr['d'];

			
			// node : TVNews
			// $qResult=array(); $content_id=null; $limit=3; $offset=null; $order="last"; $catid=140;
			// $qResult = $this->getRelate_model->getRelate_cms($content_id, $limit, $offset, $order, $catid);
			// $arr_result = array();
			// if(is_array($qResult)){
				// foreach($qResult as $key => $v){
					// $arr = array();
					// $arr['d']['content_type'] = $v['type'];
					// $arr['d']['content_id'] = $v['content_id'];
					// $arr['d']['content_child_id'] = $v['content_child_id'];
					// $arr['d']['topic'] = $v['title'];
					// $arr['d']['url'] = $v['url'];
					// $arr['d']['thumbnail'] = $v['thumbnail'];
					// $arr['d']['viewcount'] = intval(str_replace(",","",$this->trueplook->getViewNumber($v['content_id'],21)));
					// $arr['d']['addDateTime'] = $v['addDateTime'];
					// $arr['d']['addBy'] = $v['addBy'];
					// $arr['d']['mul_level_id'] = $v['mul_level_id'];
					// $arr['d']['mul_level_name'] = $v['mul_level_name'];
					// $arr['d']['mul_category_id'] = $v['mul_category_id'];
					// $arr['d']['mul_category_name'] = $v['mul_category_name'];
					// $arr['d']['context_id'] = $v['context_id'];
					// $arr['d']['context_name'] = $v['context_name'];
					// $arr_result[] = $arr['d'];
				// }
			// }
			// $data["data"]["TVNews"] = $arr_result;
			
			// node : TVStreamingURL
			$key = "@doo-plookpanya@";
			$timespan=300;

			$ch = $this->input->get('ch') ? $this->input->get('ch') : 139;
			$uuid = $this->input->get('uuid') ? $this->input->get('uuid') : uniqid();//'be02b035-7c18-4fe9-89bf-60d1961b72dc';
			$agent =$this->input->get('agent') ? $this->input->get('agent') : 'IOS';
			$ts = $this->input->get('ts') ? $this->input->get('ts') : time();
			$hash = $this->input->get('hash') ? $this->input->get('hash') : MD5("$ch|$uuid|$agent|$ts|$key");
			$url="http://www.trueplookpanya.com/mobile/api/stream/getStreamUrl?ch=$ch&uuid=$uuid&agent=$agent&ts=$ts&hash=$hash";
			$ch= curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
			$stream_data = json_decode(curl_exec($ch));
			if($stream_data->response->status){
				$TPPYstreamingURL=$stream_data->data->url;
			}else{
			  $TPPYstreamingURL=$url;
			}
			$data["data"]["TVStreamingURL"]["url"] = $TPPYstreamingURL;
			$data["data"]["TVStreamingURL"]["title"] = "TruePlookpanya Channel";
			$data["data"]["TVStreamingURL"]["thumbnail"] = "http://www.trueplookpanya.com/new/cutresize/re/500/370/images/trueplook.png/TV/";
			
			// node : TVContent
			$qResult=array(); $content_id=null; $limit=6; $offset=null; $order=null; $l1=null;$l2=null;
			$qResult = $this->getRelate_model->getRelate_tvprogram($content_id, $limit, $offset, $order,$l1,$l2);
			$arr_result = array();
			if(is_array($qResult)){
				foreach($qResult as $key => $v){
					$arr = array();
					$arr['d']['content_type'] = $v['type'];
					$arr['d']['content_id'] = $v['content_id'];
					$arr['d']['content_child_id'] = $v['content_child_id'];
					$arr['d']['topic'] = $v['title'];
					$arr['d']['url'] = $v['url'];
					$arr['d']['thumbnail'] = $v['thumbnail'];
					//$arr['d']['viewcount'] = $v['viewcount'];
					$arr['d']['viewcount'] =  intval(str_replace(",","",$this->trueplook->getViewCenter($v['content_id'],'tv_program','media')));
					$arr['d']['addDateTime'] = $v['addDateTime'];
					$arr['d']['addBy'] = $v['addBy'];
					$arr['d']['mul_level_id'] = $v['mul_level_id'];
					$arr['d']['mul_level_name'] = $v['mul_level_name'];
					$arr['d']['mul_category_id'] = $v['mul_category_id'];
					$arr['d']['mul_category_name'] = $v['mul_category_name'];
					$arr['d']['context_id'] = $v['context_id'];
					$arr['d']['context_name'] = $v['context_name'];
					$arr['d']['tv_cat1_id'] = $v['tv_menu_level1_id'];
					$arr['d']['tv_cat1_name'] = $v['tv_menu_level1_name'];
					$arr['d']['tv_cat2_id'] = $v['tv_menu_level2_id'];
					$arr['d']['tv_cat2_name'] = $v['tv_menu_level2_name'];
					$arr_result[] = $arr['d'];
				}
			}
			$data["data"]["TVContent"] = $arr_result;
			
			// node : Magazine
			$url="http://www.trueplookpanya.com/mobile/api/magazine/getmagazinelist?limit=1&order=last";
			$ch= curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
			$cdata = json_decode(curl_exec($ch));
			if($cdata->response->status){
				$strData=$cdata->data->magazine;
			}else{
			  $strData=$url;
			}
			$data["data"]["magazine"] = $strData;
			// $this->load->model('mobile/magazine_model');
			// $mons = array('01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม');
			// $arrX = array();
			// $rs = $this->magazine_model->getMagazine_List($offset=0, $limit=1, $type_data = "desc");
			// foreach ($rs as $k => $v) {
				// $arr = array();
				// $arr['magazine_id'] = $v['magazine_id'];
				// $arr['thumb'] = "http://www.trueplookpanya.com/data/product/media/MAGAZINE/cover/" . $v['magazine_id'] . ".jpg";
				// $arr['Topic'] = split('/', $v['magazine_name'])[0];
				// $arr['caption'] = trim(split(':', $v['magazine_caption'])[1]);
				// $arr['short_desc'] = $v['short_desc'];

				// $date = split('-', $v['magazine_year']);

				// $arr['month'] = $mons[$date[0]];
				// $arr['year'] = $date[1];

				// $check_path_pdf = split(' ', $arr['Topic'])[1];
				// $arrX['magazine'][] = $arr;
			// }
			// $data['data']['Magazine'] = $arrX['magazine'];
			
			// node : Admission news
			$qResult=array();
			$arrFilter=array();
			$arrFilter['list4App']=true;
			$qResult = $this->getRelate_model->getRelate_admissionNews($content_id = null, $limit=3, $offset=null, $order="sort", $catid=null , $textSearch=null, $arrFilter);
			$arr_result = array();
			if(is_array($qResult)){
				foreach($qResult as $key => $v){
					$arr = array();
					$arr['d']['content_type'] = $v['type'];
					$arr['d']['content_id'] = $v['content_id'];
					$arr['d']['content_child_id'] = $v['content_child_id'];
					$arr['d']['topic'] = $v['title'];
					$arr['d']['url'] = $v['url'];
					$arr['d']['thumbnail'] = $v['thumbnail'];
					$arr['d']['viewcount'] = intval(str_replace(",","",$this->trueplook->getViewNumber($v['content_id'],21)));
					$arr['d']['addDateTime'] = $v['addDateTime'];
					$arr['d']['addBy'] = $v['addBy'];
					$arr_result[] = $arr['d'];
				}
			}
			$data["data"]["AdmissionsNews_Balloon"] = 9;
			$data["data"]["AdmissionsNews"] = $arr_result;
			
			// node : camp news
			$qResult=array();
			$qResult = $this->getRelate_model->getRelate_ams_newscamp($content_id=null, $limit =2, $offset = 0, $order="last", $arrFilter=array());
			$arr_result = array();
			if($qResult){
				foreach($qResult as $key => $v){
					$arr = array();
					//$arr['d']['id'] = $v['mul_source_id'];
					$arr['d']['content_type'] = $v['type'];
					$arr['d']['content_id'] = $v['content_id'];
					$arr['d']['content_child_id'] = $v['content_child_id'];
					$arr['d']['topic'] = $v['title'];
					$arr['d']['url'] = $v['url'];
					$arr['d']['thumbnail'] = $v['thumbnail'];
					$arr['d']['viewcount'] = $v['viewcount'];
					$arr['d']['addDateTime'] = $v['addDateTime'];
					$arr['d']['addBy'] = $v['addBy'];
					$arr['d']['short_detail'] = $v['short_detail'];
					$arr['d']['camp_date_start'] = $v['camp_date_start'];
					$arr['d']['camp_date_end'] = $v['camp_date_end'];
					$arr['d']['register_date_start'] = $v['register_date_start'];
					$arr['d']['register_date_end'] = $v['register_date_end'];
					$arr['d']['announce_date'] = $v['announce_date'];
					$arr_result[] = $arr['d'];
				}
			}
			$data["data"]["CampNews_Balloon"] = 'N';
			$data["data"]["CampNews"] = $arr_result;
			
			// node : directapply news
			$url="http://www.trueplookpanya.com/mobile/api/admission/directapplynews?limit=6";
			$ch= curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
			$cdata = json_decode(curl_exec($ch));
			if($cdata->response->status){
				$s1=$cdata->data->directapplynews;
				$s2= $cdata->data->SearchBanner;
				$s3= $cdata->data->count_directapplynews_all;
				$s4= $cdata->data->count_directapplynews_open;
			}else{
			  $strData="";
			}
			$data["data"]["SearchBanner"] = $s2;
			$data["data"]["count_directapplynews_all"] = $s3;
			$data["data"]["count_directapplynews_open"] = $s4;
			$data["data"]["directapplynews"] = $s1;
			
			// node : EPG (for now showing)
			$this->load->model('mobile/epg_model');
			$qResult=$this->epg_model->_onlineEpg();
			$data['data']["EPG"]=$qResult['data'];
			
			$this->response(array('response'=>$data['response'], 'data'=>$data["data"]));
		}
	// News Adminsion End
	/// Mudules examination	
	/////////*************Examination_center Start*************///////////
	public function  examinationlistall_get() {
					 // api/center/examinationlistall?exam_id=499
					 $exam_id = @$this->input->get('exam_id');
					 if($exam_id==Null){$exam_id=Null;}
					 $limit = @$this->input->get('limit');
					 if($limit==Null){$limit=100;}
					 $exam_name = @$this->input->get('exam_name');
					 if($exam_name==Null){$exam_name=Null;}
					 $this->load->model('Examination_center_model');
					 $data = $this->Examination_center_model->getListall($exam_id,$limit,$exam_name);
					 $countalls=$this->Examination_center_model->getcountall($exam_name);
					 $countall=$countalls['countrow'];
					 $rows=count($data);
					 if($data){
						$this->response(array('header'=>array(
												'title'=>'examinationlistall',
												'model'=>'api/model/Examination_center_model',
												'function'=>'getListall',
												'version ' => '2.0',
												'description' => 'List data',
												'message' => 'REST DONE',
												'remarks' => 'HTTP GET',
												'rows'=>$rows,
												'countall'=>$countall,
												'message'=>'Success',
												'status'=>true, 
												'code'=>200), 
												'data'=> $data),200);
						}else{
						  $this->response(array('header'=>array(
												'title'=>'examinationlistall',
												'model'=>'api/model/Examination_center_model',
												'function'=>'getListall',
												'version ' => '2.0',
												'description' => 'Null',
												'message' => 'REST Error 404',
												'remarks' => 'HTTP GET',
												'rows'=>$rows,
												'message'=>'Success',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
						}
				  }
	public function  examinationscore_get() {
					// api/center/examinationscore?exam_id=499&member_id=MEM0002874
					 $exam_id = @$this->input->get('exam_id');
					 $member_id = @$this->input->get('member_id');
					 $this->load->model('Examination_center_model');
					 $data = $this->Examination_center_model->getcourse_examscore($exam_id,$member_id);
					 //echo '<pre> $data=>'; print_r($data); echo '</pre>';  die();
					 $log= $this->Examination_center_model->getcourse_examscore_log($exam_id,$member_id);
					 if($data){
						$this->response(array('header'=>array(
												'title'=>'examinationscore',
												'model'=>'api/model/Examination_center_model',
												'function'=>'getcourse_examscore',
												'version ' => '2.0',
												'description' => 'List data',
												'message' => 'REST DONE',
												'remarks' => 'HTTP GET',
												'message'=>'Success',
												'status'=>true, 
												'code'=>200), 
												'data'=> $data,
												'scorelog'=> $log),200);
						}else{
						  $this->response(array('header'=>array(
												'title'=>'examinationscore',
												'model'=>'api/model/Examination_center_model',
												'function'=>'getcourse_examscore',
												'version ' => '2.0',
												'description' => 'Null',
												'message' => 'REST Error 404',
												'remarks' => 'HTTP GET',
												'message'=>'Success',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
						}
				  }
	public function  examinationscoremax_get() {
					// api/center/examinationscore?exam_id=499
					 $exam_id = @$this->input->get('exam_id');
					 $this->load->model('Examination_center_model');
					 $data = $this->Examination_center_model->getcourse_examscoremax($exam_id);
					 $count=count($data);
					 if($data){
						$this->response(array('header'=>array(
												'title'=>'examinationscoremax',
												'model'=>'api/model/Examination_center_model',
												'function'=>'getcourse_examscore',
												'version ' => '2.0',
												'description' => 'List data',
												'message' => 'REST DONE',
												'remarks' => 'HTTP GET',
												'message'=>'Success',
												'status'=>true,
												'count'=>$count,
												'code'=>200), 
												'data'=> $data),200);
						}else{
						  $this->response(array('header'=>array(
												'title'=>'examinationscoremax',
												'model'=>'api/model/Examination_center_model',
												'function'=>'getcourse_examscore',
												'version ' => '2.0',
												'description' => 'Null',
												'message' => 'REST Error 404',
												'remarks' => 'HTTP GET',
												'message'=>'Success',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
						}
				  }	  
	public function examinationindex_get() {
						$keyword=@$this->input->get('keyword');
						$sort=@$this->input->get('sort');
						$order=@$this->input->get('order');
						if($order==Null){$order='desc';}
						$status=@$this->input->get('status');
						$perpage=@$this->input->get('per_page');
						if($perpage==Null){$perpage=100;}
						$offset=@$this->input->get('offset');
						if($offset==Null){$offset=1;}
					 $this->load->model('Examination_center_model');
					 $data = $this->Examination_center_model->get_examination_index();
					 $count=count($data);
					 if($data){
						$this->response(array('header'=>array(
												'title'=>'examinationindex',
												'model'=>'api/model/Examination_center_model->get_examination_index',
												'function'=>'examinationindex_get',
												'version ' => '2.0',
												'description' => 'List data',
												'message' => 'REST DONE',
												'remarks' => 'HTTP GET',
												'message'=>'Success',
												'status'=>true,
												'count'=>$count,
												'code'=>200), 
												'data'=> $data),200);
						}else{
						  $this->response(array('header'=>array(
												'title'=>'examinationindex',
												'model'=>'api/model/Examination_center_model->get_examination_index',
												'function'=>'examinationindex_get',
												'version ' => '2.0',
												'description' => 'Null',
												'message' => 'REST Error 404',
												'remarks' => 'HTTP GET',
												'message'=>'Success',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
						}
				}  
	public function relateexamination_get() {
					 $content_id=@$this->input->get('content_id'); 
					 if($content_id==Null){$content_id=Null;}
					 $limit=@$this->input->get('limit');
					 if($limit==Null){$limit=100;}
					 $offset=@$this->input->get('offset');
					 if($offset==Null){$offset=1;}
					 $order=@$this->input->get('order'); 
					 if($order==Null){$order='desc';}
					 $subject_id=@$this->input->get('subject_id'); 
					 if($subject_id==Null){$subject_id=Null;}
					 $level_id=@$this->input->get('level_id'); 
					 if($level_id==Null){$level_id=Null;}
					 $context_id=@$this->input->get('context_id');
					 if($context_id==Null){$context_id=Null;}
					 $this->load->model('api/getRelate_model');
					 $data = $this->getRelate_model->getRelate_exam($content_id, $limit, $offset, $order, $subject_id, $level_id, $context_id);
					//var_dump($data);

					 $count=count($data);
					 if($data){
						$this->response(array('header'=>array(
												'title'=>'relateexamination',
												'model'=>'api/getRelate_model->getRelate_exam',
												'function'=>'relateexamination_get',
												'version ' => '2.0',
												'description' => 'List data',
												'message' => 'REST DONE',
												'remarks' => 'HTTP GET',
												'message'=>'Success',
												'status'=>true,
												'count'=>$count,
												'code'=>200), 
												'data'=> $data),200);
						}else{
						  $this->response(array('header'=>array(
												'title'=>'relateexamination',
												'model'=>'api/getRelate_model->getRelate_exam',
												'function'=>'relateexamination_get',
												'version ' => '2.0',
												'description' => 'Null',
												'message' => 'REST Error 404',
												'remarks' => 'HTTP GET',
												'message'=>'Success',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
						}
				}  	
	public function examinationmemberlog_get() {
			
					$exam_id=@$this->input->get('exam_id');
					$member_id=@$this->input->get('member_id');
					$user_id=@$this->input->get('user_id');
					$order_by=@$this->input->get('order');
					$perpage=@$this->input->get('per_page');
					
					if($perpage>100){$perpage=100;}
					$offset=@$this->input->get('offset');
					  //api/center/examinationmemberlog?member_id=MEM0002848&per_page=10&offset=1&order=asc
					  //api/center/examinationmemberlog?user_id=2874&per_page=10&offset=1&order=asc

					 $this->load->model('member/member_model');
					 $data1 = $this->member_model->get_profile_by_userid($user_id, TRUE);
					 $data=$data1['user_username']='Forbiden';
					 $data=$data1['user_password']='Forbiden';
					 $data=$data1['user_password_tmp']='Forbiden';
					 $data=$data1['psn_id_number']='Forbiden';
					 $data=$data1['psn_tel']='Forbiden';
					 $data=$data1['psn_birthdate']='Forbiden';
					 $profile=$data1;
					 $this->load->model('Examination_center_model');
					 $data= $this->Examination_center_model->get_examination_user_log();
					 $userid=@$data['user_id'];
					 if($userid<>''){$member_id=@$this->input->get('member_id');}else{$user_id=@$this->input->get('user_id');}
					 $countallrows= $this->Examination_center_model->get_course_exam_scorelistalllog();
					 #var_dump($data);Die();
					 $count=count($data);
					 if($data){
						$this->response(array('header'=>array(
												'title'=>'examinationmemberlog',
												'model'=>'api/Examination_center_model->get_examination_user_log',
												'function'=>'examinationmemberlog_get',
												'version ' => '2.0',
												'description' => 'List data',
												'message' => 'REST DONE',
												'remarks' => 'HTTP GET',
												'message'=>'Success',
												'status'=>true,
												'count'=>$count,
												'code'=>200), 
												'count_examination_rows'=> $countallrows,
												'profile'=> $profile,
												'data'=> $data,),200);
						}else{
						  $this->response(array('header'=>array(
												'title'=>'examinationmemberlog',
												'model'=>'api/Examination_center_model->get_examination_user_log',
												'function'=>'examinationmemberlog_get',
												'version ' => '2.0',
												'description' => 'Null',
												'message' => 'REST Error 404',
												'remarks' => 'HTTP GET',
												'message'=>'Success',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
						}
		} 
	//////////examination
	//////TEST WEBBOARD
	public function webbordtest_get() {
			
			//2017-01-24////////////
			$this->load->model('api/centermobile_model', 'centermobile_model_api');
			#$data["data"]["Webboardlast"] = $this->centermobile_model_api->get_webboard_last_for_mobile($room=6,$categoryid=59,$limit=4,$order_by='desc');
			
			$this->load->model('api/webboardapi_model', 'webboardapi_model_api');
			$qResultlast=$this->webboardapi_model_api->getTopic($room = 6, $categoryid = 59 , $topicSearch = null , $order = null, $limit = 4, $offset = null , $topicid = null, $arrFilter = array());
			////
			$arr_result = array();
			if (is_array($qResultlast)) {
				foreach ($qResultlast as $key => $value) {
					$arr = array();
					$arr['webboard']['wb_post_id'] = $value['wb_post_id'];
					$arr['webboard']['wb_subject'] = $value['wb_subject'];
					$arr['webboard']['wb_detail'] = $value['wb_detail'];
					if($value['thumb']==NUll){
					  $thumb='http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';
					  }else{
						$thumb='http://static.trueplookpanya.com/trueplookpanya/'.$value['thumb'];
					}
					$arr['webboard']['wb_thumb'] = $thumb;
					$arr['webboard']['wb_add_date'] = $value['add_date'];
					$arr['webboard']['wb_last_update'] = $value['last_update_date'];
					$arr['webboard']['wb_view_count'] = $value['view_count'];  
					$arr['webboard']['psn_display_name'] = $value['psn_display_name'];
					$arr['webboard']['psn_display_image'] = $value['psn_display_image'];
					$arr_result[] = $arr['webboard'];
				}
			}
			$data["data"]["webboardlast"] =$arr_result;
			$data["data"]["lastapireadmore"] = base_url('api/center/last?category_id=59&room=6&order=desc&limit=40');
			
			$this->load->model('api/webboardapi_model', 'webboardapi_model_api');
			$qResulthot=$this->webboardapi_model_api->getTopic2($room =Null, $categoryid =Null, $topicSearch = null , $order = null, $limit = 4, $offset = null , $topicid = null, $arrFilter = array('isPin'=>1));
			$arr_resulthot = array();
				if (is_array($qResulthot)) {
				foreach ($qResulthot as $key => $va) {
					$arr = array();
					$arr['hot']['wb_post_id'] = $va['wb_post_id'];
					$arr['hot']['wb_subject'] = $va['wb_subject'];
					$arr['hot']['wb_detail'] = $va['wb_detail'];
					if($va['thumb']==NUll){
					  $thumb='http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';
					  }else{
						$thumb='http://static.trueplookpanya.com/trueplookpanya/'.$va['thumb'];
					}
					$arr['hot']['wb_thumb'] = $thumb;
					$arr['hot']['wb_add_date'] = $va['add_date'];
					$arr['hot']['wb_last_update'] = $va['last_update_date'];
					$arr['hot']['wb_view_count'] = $va['view_count'];  
					$arr['hot']['psn_display_name'] = $va['psn_display_name'];
					$arr['hot']['psn_display_image'] = $va['psn_display_image'];
					$arr_resulthot[] = $arr['hot'];
				}
			}
			#echo '<pre> $qResulthot=>'; print_r($qResulthot); echo '</pre>';
			#echo '<pre> $arr_resulthot=>'; print_r($arr_resulthot); echo '</pre>'; Die();
			$data["data"]["webboardhot"] = $arr_resulthot;
			//$this->centermobile_model_api->get_webboard_hot_for_mobile($room=6,$categoryid=59,$limit=4,$order_by='desc');
			$data["data"]["hotapireadmore"] = base_url('api/center/hot?category_id=59&room=6&order=desc&limit=40');
			$count1=count($qResultlast);
			$count2=count($qResulthot);
					 if($data){
						$this->response(array('header'=>array(
												'title'=>'webbordtest',
												'message'=>'Success',
												'status'=>true,
												'countwebboardlast'=>$count1,
												'countwebboardhot'=>$count1,
												'code'=>200), 
												'data'=> $data,),200);
						}else{
						  $this->response(array('header'=>array(
												'title'=>'examinationmemberlog',
												'message'=>'Success',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
						}
		} 	
		///////////////////
	public function tvprogramreadmore_get() {
			
			#http://www.trueplookpanya.local/api/center/tvprogramreadmore?tv_id=13&tv_episode_id=&limit=20
			
			  $id=@$this->input->get('tv_id');
			  if($id==Null){
			  $id=13;
			  //$id=186;
				  }
			 $episode_id=@$this->input->get('tv_episode_id');
			  if($episode_id==Null){$episode_id=Null;}
			 $limit=@$this->input->get('limit');
			 if($limit==Null){$limit=20;}
			 $this->load->model('api/getrelatetv_model');
			$admissionsgangs=$this->getrelatetv_model->tvEpisode($id,$episode_id,$limit);
			$qResult=$admissionsgangs;
			$arr_result = array();
							if(is_array($qResult)){
								foreach($qResult as $key => $v){
									$arr = array();
										$arr['d']['tv_id'] = $v['tv_id'];
										$arr['d']['tv_episode_id'] = $v['tv_episode_id'];
										$arr['d']['thumbnail'] = $v['thumbnail'];
										$arr['d']['title'] = $v['title'];
										$arr['d']['desc'] = $v['desc'];
										$arr['d']['tv_episode_tag'] = $v['tv_episode_tag'];
										//$arr['d']['view'] = $v['view'];
										//$arr['d']['tv_episode_description'] = $v['tv_episode_description'];
										$arr['d']['add_date'] = $v['add_date'];
										//$arr['d']['last_update'] = $v['last_update'];
										//$arr['d']['update_by'] = $v['update_by'];
										
										//$arr['d']['cms_level_id'] = $v['cms_level_id'];
										//$arr['d']['cms_category_id'] = $v['cms_category_id'];
										//$arr['d']['tv_episode_onair'] = $v['tv_episode_onair'];
										//$arr['d']['content_stage'] = $v['content_stage'];
										//$arr['d']['view_count'] = $v['view_count'];
										//$arr['d']['psn_display_name'] = $v['psn_display_name'];
										//$arr['d']['tv_name'] = $v['tv_name'];
										$arr['d']['detail'] =base_url().'mobile/api/tvprogram/episode?eid='.$v['tv_episode_id'];
									$arr_result[]= $arr['d'];
								}
							}
					$data=$arr_result;
					$count=count($data);
					 if($data){
						$this->response(array('header'=>array(
												'title'=>'tvprogramdetail',
												'message'=>'Success',
												'status'=>true,
												'count'=>$count,
												'code'=>200), 
												'data'=> $data,),200);
						}else{
						  $this->response(array('header'=>array(
												'title'=>'tvprogramdetail',
												'message'=>'Success',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
						}
		}
		////////////////
	public function tvprogramdetail_get() {
			 $id=@$this->input->get('tv_id');
			  if($id==Null){
			  $id=13;
			  //$id=186;
				  }
			 $episode_id=@$this->input->get('tv_episode_id');
			  if($episode_id==Null){$episode_id=Null;}
			 $limit=@$this->input->get('limit');
			 if($limit==Null){$limit=20;}
			 $this->load->model('api/getrelatetv_model');
			$admissionsgangs=$this->getrelatetv_model->tvEpisodeDetail($id,$episode_id,$limit);
			$qResult=$admissionsgangs;
			$arr_result = array();
							if(is_array($qResult)){
								foreach($qResult as $key => $v){
									$arr = array();
										$arr['d']['tv_id'] = $v['tv_id'];
										$arr['d']['tv_episode_id'] = $v['tv_episode_id'];
										$arr['d']['thumbnail'] = $v['thumbnail'];
										$arr['d']['title'] = $v['title'];
										$arr['d']['desc'] = $v['desc'];
										$arr['d']['tv_episode_tag'] = $v['tv_episode_tag'];
										$arr['d']['view'] = $v['view'];
										$arr['d']['tv_episode_description'] = $v['tv_episode_description'];
										$arr['d']['add_date'] = $v['add_date'];
										$arr['d']['last_update'] = $v['last_update'];
										$arr['d']['update_by'] = $v['update_by'];
										$arr['d']['cms_level_id'] = $v['cms_level_id'];
										$arr['d']['cms_category_id'] = $v['cms_category_id'];
										$arr['d']['tv_episode_onair'] = $v['tv_episode_onair'];
										$arr['d']['content_stage'] = $v['content_stage'];
										$arr['d']['view_count'] = $v['view_count'];
										$arr['d']['psn_display_name'] = $v['psn_display_name'];
										$arr['d']['tv_name'] = $v['tv_name'];
										$arr['d']['linkapi'] = $v['linkapi'];
									$arr_result[]= $arr['d'];
								}
							}
					$data=$arr_result;
					$count=count($data);
					 if($data){
						$this->response(array('header'=>array(
												'title'=>'tvprogramdetail',
												'message'=>'Success',
												'status'=>true,
												'count'=>$count,
												'code'=>200), 
												'data'=> $data,),200);
						}else{
						  $this->response(array('header'=>array(
												'title'=>'tvprogramdetail',
												'message'=>'Success',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
						}
		}
	public function topicdetailtest_get() {
	$token=@$this->input->get('token');
	// $user_session=$this->session->userdata('user_session');
	// echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>';  Die();
	$this->load->library('session');
 	if ($token==NULL) {
 			$islikepublic='false';
 		}else{
 			$islikepublic=Null;
 			$this->load->library('TPPY_Oauth');
            $my=$this->me = $this->tppy_oauth->parse_token($token);
			//echo '<pre>  $my=>'; print_r($my); echo '</pre>'; Die();
		    $user_session=$this->session->set_userdata($my);
			$user_id=$my->user_id;
		    $member_id=$my->member_id;	
			$this->load->model('api/centermobile_model', 'centermobile_model');
			$datauser=$this->centermobile_model->get_memberdata($user_id);
		}if($member_id==Null){
			$member_id=$datauser['member_id'];
			}
       
		
	    $categoryid=@$this->input->get('category_id');  
			if($categoryid==Null){$categoryid=Null;}
		$room=@$this->input->get('room');
			if($room==Null){$room=Null;}
		$topicSearch=@$this->input->get('topicSearch');
			if($topicSearch==Null){$topicSearch=Null;}
		$order=@$this->input->get('order');
		if($order==Null){$order_reply=@$this->input->get('order_reply');
			if($order_reply==Null){$order_reply=Null;}
		}else{$order_reply=$order;}
		$limit=@$this->input->get('limit');
		if($limit==Null){$limit_reply=@$this->input->get('limit_reply');
			if($limit_reply==Null){$limit_reply=40;}
		}else{$limit_reply=$limit;}
		$offset=@$this->input->get('offset');
			if($offset==Null){$offset=0;}
		$topicid=@$this->input->get('topicid');
			if($topicid==Null){$topicid=Null;}
		$type=@$this->input->get('type'); // type=mobile // apps //type=web	
			
		$json = null;
		$this->load->model('webboardapi_model');

		$order=$order_reply;

		$pageSize = $limit_reply;
		$pageSize = ($pageSize > 100) ? 100 : $pageSize;
		if(isset($_GET['pageNo']) && !empty($_GET['pageNo'])){
			$pageNo = trim($_GET['pageNo']);
		}
		else {
			$pageNo = 1;
		}
		$rowStart = (($pageNo-1) * $pageSize);
		$rowEnd = ($pageNo * $pageSize);

		if($order=='lastreply') { $v_order='reply_datetime desc'; }
		else { $v_order=''; }
		
 
		// get Topic
		$qResult = $this->webboardapi_model->getTopic($room, null, null , null, 1, null, $topicid);
		$json['header']['title'] = 'Webboard get webboard topicdetailtest By topicid';
		$json['header']['code'] = 200;
		$json['header']['status'] = true;
		$json['header']['message'] = 'Success';
		$json['header']['type'] = 'HTTP GET';
		$user_session=$this->session->set_userdata($my);
		$user_id=$my->user_id;
		$json['header']['user_id'] = $user_id;
		$arr_result = array();
		if(isset($qResult) and $qResult) {
			$compData1='';
			$compData2='';
			$comData3='';
			$i=-1;
			$j=-1;
			$k=-1;
			foreach ($qResult as $v_result) {
				$arr = array();
				if($compData1!=$v_result['wb_room_id']){
					$i++;
					$arr['room']['roomId'] = $v_result['wb_room_id'];
					$arr['room']['roomName'] = $v_result['wb_room_name'];
					$arr_result['room'][$i] = $arr['room'];
				}
				if($compData2!=$v_result['wb_category_id']){
					$j++;
					$arr['room']['category']['categoryId'] = $v_result['wb_category_id'];
					$arr['room']['category']['categoryName'] = htmlspecialchars($v_result['wb_category_name']);
					$arr['room']['category']['categoryDescription'] = htmlspecialchars($v_result['wb_category_desc']);
					$arr_result['room'][$i]['category'][$j] = $arr['room']['category'];
				}
				if($compData3!=$v_result['wb_post_id']){
					$k++;
					$arr['room']['category']['topic']['topicId'] = $v_result['wb_post_id'];
					if($v_result['thumb']==Null){
					$arr['room']['category']['topic']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';	
					}else{
					$arr['room']['category']['topic']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/'.$v_result['thumb'];
					}
					$arr['room']['category']['topic']['createByImage'] = $v_result['psn_display_image'];
					
					$arr['room']['category']['topic']['topicTitle'] = htmlspecialchars($v_result['wb_subject']);
					$arr['room']['category']['topic']['topicDetail'] = htmlspecialchars($v_result['wb_detail']);
					$arr['room']['category']['topic']['createBy'] = htmlspecialchars($v_result['psn_display_name']);
					
					
					$arr['room']['category']['topic']['topic_viewcount'] = $this->webboardapi_model->getViewNumber($v_result['wb_post_id'], '15');//$v_result['viewcount'];
					$arr['room']['category']['topic']['topic_createdate'] = $v_result['add_date'];
					$arr['room']['category']['topic']['topic_lastupdate'] = $v_result['last_update_date'];
					//$arr['room']['category']['topic']['web_url_go'] = 'http://www.trueplookpanya.com/true/webboard_detail.php?postid='.$v_result['wb_post_id'];
					//$arr['room']['category']['topic']['flagPin'] = $v_result['flag_pin'];
					$totalRows = $this->webboardapi_model->getCountReply($v_result['wb_post_id']);
					$pageTotal = ceil($totalRows / $pageSize);
					$arr['room']['category']['topic']['rowCount_reply'] = $totalRows;
					$arr['room']['category']['topic']['rowRange_reply'] = trim(($rowStart+1).'-'.$rowEnd);
					$arr['room']['category']['topic']['pageTotal_reply'] = $pageTotal;
					$arr_result['room'][$i]['category'][$j]['topic'][] = $arr['room']['category']['topic'];
				}
				
				// Like
			 
				$like_count=$this->webboardapi_model->like_count($topicid);
				$reply_count=$this->webboardapi_model->reply_count($topicid);
				$isFavorite=$this->webboardapi_model->isFavorite($topicid);
				
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['like_count']=$like_count;
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply_count']=$reply_count;
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['isFavorite']=$isFavorite;
				$islike='false'; // true //false
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['islike ']=$islike;
				//og // node Social
				
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:app_id'] = "704799662982418";
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:title'] = htmlspecialchars($v_result['wb_subject']);
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:type'] = 'article';
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:locale'] = 'th_TH';
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:url'] = 'http://www.trueplookpanya.com/admissions/webboard/detail/'.$v_result['wb_post_id'];
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:image'] = $v_result['psn_display_image'];
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:site_name'] = 'trueplookpanya.com';
				$wb_detail= htmlspecialchars($v_result['wb_detail']);
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:description'] = $this->trueplook->limitText(strip_tags(str_replace('\'', ' ', str_replace('\"', ' ', $wb_detail))), 200);
				
				// get LastReply
				//$qResultRs = $this->webboardapi_model->getTopicDetail($topicid,$order_reply,$limit_reply=1,$offset_reply=0);
				$this->load->model('api/centermobile_model', 'centermobile_model');
				$qResultRs = $this->centermobile_model->get_webboard_postreply_for_mobile_offset($topicid,$limit=1,$offset=0);
				 //$arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']=$qResultRs;
				 if($qResultRs==Null){$arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']=Null;}else{
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyId']=$qResultRs['0']['wb_reply_id'];

				$string=$qResultRs['0']['reply_detail'];
				if(isJSON($string)){
				    $reply_details=$string;
				    $jsondata=file_get_contents($reply_details);
					$reply_detail =json_decode($jsondata);
    				$reply_detail = str_replace("&quot;", ", ", $reply_detail);
 					// echo '<pre> $reply_detail=>'; print_r($reply_detail); echo '</pre>'; // Die();
				 
				}else{
					$reply_detail=$qResultRs['0']['reply_detail'];
				}
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyDetail']=
				 
				 
				 
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyBy']=$qResultRs['0']['username'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyByImage']=$qResultRs['0']['display_image'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyDate']=$qResultRs['0']['add_date'];
				 //reply_sticker 
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['reply_sticker ']=Null;
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['reply_type ']=1;
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['sticker_id ']=Null;
				 $islike='false'; // true //false
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['islike ']=$islike;
				   
			    }
				 
				// get Reply
				$qResultR = $this->webboardapi_model->getTopicDetail($topicid, $v_order, $pageSize, $rowStart);
				if(isset($qResultR) and $qResultR) {
					foreach ($qResultR as $v_resultR) {
						if($v_resultR['wb_reply_id']!=null || !empty($v_resultR['wb_reply_id'])){
							$arr['room']['category']['topic']['reply']['replyId'] = $v_resultR['wb_reply_id'];
							$arr['room']['category']['topic']['reply']['replyDetail'] = htmlspecialchars($v_resultR['reply_detail']);
							$arr['room']['category']['topic']['reply']['replyBy'] = htmlspecialchars($v_resultR['replyBy']);
							$arr['room']['category']['topic']['reply']['replyByImage'] = $v_resultR['replyByImage'];
							$arr['room']['category']['topic']['reply']['replyDate'] = $v_resultR['reply_datetime'];
							//reply_sticker 
							$arr['room']['category']['topic']['reply']['reply_sticker ']=Null;
							$arr['room']['category']['topic']['reply']['sticker_id ']=Null;
							$arr['room']['category']['topic']['reply']['reply_type ']=1;
						    $islike='false'; // true //false
				 			$arr['room']['category']['topic']['reply']['islike ']=$islike;
							
							$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = $arr['room']['category']['topic']['reply'];
						}else{
							$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = null;
						}
					}
				}else{
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = null;
				}
				

				$compData1 = $v_result['wb_room_id'];
				$compData2 = $v_result['wb_category_id'];
				$compData3 = $v_result['wb_post_id'];
				$compData4 = $v_result['wb_post_id'];
				if($type=='apps' || $type=='mobile'){}else{
				 $limit=@$this->input->get('limit'); 
				 if($limit==Null){$limit=10;}
				$qResultRelated=$this->webboardapi_model->getTopic($room=null, null, null , null,$limit, null, $topicid=null);
				foreach ($qResultRelated as $v_result) {
						$arr = array();
						if($qResultRelated!=Null){
							$k++;
							$arr['room']['category']['related']['topicId'] = $v_result['wb_post_id'];
							if($v_result['thumb']==Null){
							$arr['room']['category']['related']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';	
							}else{
							$arr['room']['category']['related']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/'.$v_result['thumb'];
							}
							$arr['room']['category']['related']['createByImage'] = $v_result['psn_display_image'];
							
							$arr['room']['category']['related']['topicTitle'] = htmlspecialchars($v_result['wb_subject']);
							$arr['room']['category']['related']['topicDetail'] = htmlspecialchars($v_result['wb_detail']);
							$arr['room']['category']['related']['createBy'] = htmlspecialchars($v_result['psn_display_name']);
							
							
							$arr['room']['category']['related']['topic_viewcount'] = $this->webboardapi_model->getViewNumber($v_result['wb_post_id'], '15');//$v_result['viewcount'];
							$arr['room']['category']['related']['topic_createdate'] = $v_result['add_date'];
							$arr['room']['category']['related']['topic_lastupdate'] = $v_result['last_update_date'];
							//$arr['room']['category']['related']['web_url_go'] = 'http://www.trueplookpanya.com/true/webboard_detail.php?postid='.$v_result['wb_post_id'];
							//$arr['room']['category']['related']['flagPin'] = $v_result['flag_pin'];
							$totalRows = $this->webboardapi_model->getCountReply($v_result['wb_post_id']);
							$pageTotal = ceil($totalRows / $pageSize);
							$arr['room']['category']['related']['rowCount_reply'] = $totalRows;
							$arr['room']['category']['related']['rowRange_reply'] = trim(($rowStart+1).'-'.$rowEnd);
							$arr['room']['category']['related']['pageTotal_reply'] = $pageTotal;
							$arr_result['room'][$i]['category'][$j]['related'][] = $arr['room']['category']['related'];
						}
				}
				//////////
			} 
				 
				
				
				
			}
		}
 
		echo '<pre> $arr_result=>'; print_r($arr_result); echo '</pre>'; Die();
		
		
		$json['data'] = $arr_result;
		$json['header']['orderby'] = $order;
		$json['header']['pageno'] = $pageNo;
		$json['header']['pagesize'] = $pageSize;
        $this->tppymemcached->set($key, $json, 259200);
		echo json_encode($json, JSON_UNESCAPED_UNICODE);
		$this->response($json);
	}
	///////////File	
	public function tvprogramreadmorecachefile_get() { 
	// Write file Read File
			// json_cached_api_results( $cache_file = NULL, $expires = NULL)
			#   http://www.trueplookpanya.local/api/center/tvprogramreadmorecachefile?tv_id=13&tv_episode_id=&limit=20
			#	http://www.trueplookpanya.com/api/center/tvprogramreadmorecachefile?tv_id=13&tv_episode_id=&limit=20
			#####################################
			$id=@$this->input->get('tv_id');
			if($id==Null){ $id=186; }
			$episode_id=@$this->input->get('tv_episode_id');
			if($episode_id==Null){$episode_id=Null;}
			$limit=@$this->input->get('limit');
			if($limit==Null){$limit=20;}
			#####################################
			$this->load->model('api/getrelatetv_model');
			$admissionsgangs=$this->getrelatetv_model->tvEpisode($id,$episode_id,$limit);
			$qResult=$admissionsgangs;
			$arr_result = array();
							if(is_array($qResult)){  
							
								foreach($qResult as $key => $v){
									$arr = array();
										$arr['d']['tv_id'] = $v['tv_id'];
										$arr['d']['tv_episode_id'] = $v['tv_episode_id'];
										$arr['d']['thumbnail'] = $v['thumbnail'];
										$arr['d']['title'] = $v['title'];
										$arr['d']['desc'] = $v['desc'];
										$arr['d']['tv_episode_tag'] = $v['tv_episode_tag'];
										//$arr['d']['view'] = $v['view'];
										//$arr['d']['tv_episode_description'] = $v['tv_episode_description'];
										$arr['d']['add_date'] = $v['add_date'];
										//$arr['d']['last_update'] = $v['last_update'];
										//$arr['d']['update_by'] = $v['update_by'];
										
										//$arr['d']['cms_level_id'] = $v['cms_level_id'];
										//$arr['d']['cms_category_id'] = $v['cms_category_id'];
										//$arr['d']['tv_episode_onair'] = $v['tv_episode_onair'];
										//$arr['d']['content_stage'] = $v['content_stage'];
										//$arr['d']['view_count'] = $v['view_count'];
										//$arr['d']['psn_display_name'] = $v['psn_display_name'];
										//$arr['d']['tv_name'] = $v['tv_name'];
										$arr['d']['detail'] =base_url().'mobile/api/tvprogram/episode?eid='.$v['tv_episode_id'];
									$arr_result[]= $arr['d'];
								}
							}
					$data=$arr_result;
				 #echo '<pre> data=>'; print_r($data); echo '</pre>'; Die();
				 ///////////////////////////
				  $time=3600;
			      $pathfile='tvprogramreadmore_'.$id.'_'.$limit.'.json';
				  // $dirs='http://static.trueplookpanya.local/cachefile/';
				    #$dirs='../../static.trueplookpanya.com/public_html/cachefile/';
					$dirs='http://static.trueplookpanya.com/cachefile';
				    $directory_name=$dirs.'/tvprogramreadmore/'.$id.'/';
				    
					//$urlstatic=base_url().'/cachefile/tvprogramreadmore/'.$id.'/';
					//$url='http://static.trueplookpanya.local/cachefile/tvprogramreadmore/'.$id.'/';
					$url='http://static.trueplookpanya.com/cachefile/tvprogramreadmore/'.$id.'/';
					$filename=$directory_name.$pathfile;
					$cache_file=$filename;
					#####################################
					if (!is_dir($directory_name)) {
						 mkdir($directory_name, 0777, TRUE);
					 } 
					if (!file_exists($filename)){ 
					$objFopen=fopen($directory_name.$pathfile, 'w');
					$data_json = json_encode($data);
					fwrite($objFopen, $data_json);
					fclose($objFopen);	
					}
					if (file_exists($cache_file) && (filemtime($cache_file) > (time() - $time))) {
						$objFopen=fopen($directory_name.$pathfile, 'w');
						$data_json = json_encode($data);
						fwrite($objFopen, $data_json);
						fclose($objFopen);	
						
						}
					if (file_exists($filename)){ 
						$data=json_decode($data_json);
						$file='File '.$filename;
						  //echo 'มี'; Die();
					}else{
						    $data=$arr_result;
							$file='DB Mysql';
					}
					#####################################
				 /////////
				 // $arr_result=Null;
				 if($arr_result==Null){					 
					 if (file_exists($filename)){ 
						$data=json_decode($data_json); 
					}
				 }else{
				  $data=$arr_result;
				  $file='DB';
				 
				 }
				////////////////////////////////
					$count=count($data);
					 if($data){
						$this->response(array('header'=>array(
												'title'=>'tvprogramdetail',
												'message'=>'Success',
												'status'=>true,
												'count'=>$count,
												'form'=>$file,
												'code'=>200), 
												'data'=> $data,),200);
						}else{
						  $this->response(array('header'=>array(
												'title'=>'tvprogramdetail',
												'message'=>'Success',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
						}
		}
	//// report	
	public function report_post() {   
        $this->load->library('session');
		$this->load->library('TPPY_Oauth');
	     // $user_session=$this->session->userdata('user_session');
		 // echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>';  Die();
		 // Oauth zone
        $token=@$this->input->post('token');
		$report_key=@$this->input->post('report_key');
		$ref_id=@$this->input->post('ref_id');
		$ref_value=@$this->input->post('ref_value');
	 
		if($token==Null){
				$this->response(array('header'=>array(
											'status'=>false,
											'message'=>'token required',
											'code'=>400,
											),'data'=> Null),400);
											Die();
			}
        if ($token!==NULL) {
            $my=$this->me = $this->tppy_oauth->parse_token($token);
			 //echo '<pre>  $my=>'; print_r($my); echo '</pre>'; Die();
		    $user_session=$this->session->set_userdata($my);
			$user_id=$my->user_id;
		    $member_id=$my->member_id;	
			$this->load->model('api/centermobile_model', 'centermobile_model');
			$datauser=$this->centermobile_model->get_memberdata($user_id);
			if($member_id==Null){
			$member_id=$datauser['member_id'];
			}
			/*
			 echo '<pre>  $datauser=>'; print_r($datauser); echo '</pre>';
			 echo '<pre>  $user_session=>'; print_r($user_session); echo '</pre>';
			 echo '<pre>  $user_id=>'; print_r($user_id); echo '</pre>';
			 echo '<pre>  $member_id=>'; print_r($member_id); echo '</pre>';
		     echo '<pre>  $my=>'; print_r($my); echo '</pre>'; Die();
			*/
		
        }elseif ($this->session->userdata('user_session') != NULL && $this->session->userdata('user_session') != '') {
            $my=$this->me = $this->session->userdata('user_session');
			$user_session=$this->session->userdata('user_session');
			$user_id=$user_session->user_id;
		    $member_id=$user_session->member_id;
        }
		if($user_id==Null || $member_id==Null){
				$this->response(array('header'=>array(
											'status'=>false,
											'message'=>'Error user_id or member_id is null',
											'code'=>200,
											),
											'data'=> Null),200);
											Die();
			}
			
			
		    //echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>';  
		    //echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>'; Die(); 
			//echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>'; Die();

		            $insert['report_key'] = $this->input->post('report_key');
                    $insert['ref_id'] = $this->input->post('ref_id');
                    $insert['ref_value']= $this->input->post('ref_value');
                    $insert['user_id'] = $user_id;
                    $insert['ipddress'] = Null;
                    $insert['datetime'] = date("Y-m-d H:i:s");
                    //echo '<pre> $insert=>'; print_r($insert); echo '</pre>'; Die();
                    
		// report 
        //$this->load->model('api/report_model', 'report_model');
	    //$data = $this->report_model->add($insert);
		 
		$count=1;
		$data='Thank you very much';
		if($data){

					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>0,),
											'data'=> Null),200);
					}
	 
	}
	//// Like
	public function like_get() {   
        $this->load->library('session');
		$this->load->library('TPPY_Oauth');
	     // $user_session=$this->session->userdata('user_session');
		 // echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>';  Die();
		 // Oauth zone
		 //?like_key=1&ref_id=18&token=
        $token=@$this->input->get('token');
		$like_key=@$this->input->get('like_key');
		$ref_id=@$this->input->get('ref_id');
		$ref_value=@$this->input->get('ref_value');
	 
		if($token==Null){
				$this->response(array('header'=>array(
											'status'=>false,
											'message'=>'token required',
											'code'=>400,
											),'data'=>array(
											'like_key'=>$like_key,
											'ref_id'=>$ref_id,
											'ref_value'=>0,
											'user_id'=>Null,
											)),400);
											Die();
			}
        if ($token!==NULL) {
            $my=$this->me = $this->tppy_oauth->parse_token($token);
			 //echo '<pre>  $my=>'; print_r($my); echo '</pre>'; Die();
		    $user_session=$this->session->set_userdata($my);
			$user_id=$my->user_id;
		    $member_id=$my->member_id;	
			$this->load->model('api/centermobile_model', 'centermobile_model');
			$datauser=$this->centermobile_model->get_memberdata($user_id);
			if($member_id==Null){
			$member_id=$datauser['member_id'];
			}
			/*
			 echo '<pre>  $datauser=>'; print_r($datauser); echo '</pre>';
			 echo '<pre>  $user_session=>'; print_r($user_session); echo '</pre>';
			 echo '<pre>  $user_id=>'; print_r($user_id); echo '</pre>';
			 echo '<pre>  $member_id=>'; print_r($member_id); echo '</pre>';
		     echo '<pre>  $my=>'; print_r($my); echo '</pre>'; Die();
			*/
		
        }elseif ($this->session->userdata('user_session') != NULL && $this->session->userdata('user_session') != '') {
            $my=$this->me = $this->session->userdata('user_session');
			$user_session=$this->session->userdata('user_session');
			$user_id=$user_session->user_id;
		    $member_id=$user_session->member_id;
        }
		if($user_id==Null || $member_id==Null){
				$this->response(array('header'=>array(
											'status'=>false,
											'message'=>'Error user_id or member_id is null',
											'code'=>200,
											),
											'data'=> Null),200);
											Die();
			}
			
			
		    //echo '<pre> $user_id=>'; print_r($user_id); echo '</pre>';  
		    //echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>'; Die(); 
			//echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>'; Die();

		            $insert['like_key'] = $this->input->get('like_key');
                    $insert['ref_id'] = $this->input->get('ref_id');
                    $insert['ref_value']= $this->input->get('ref_value');
                    $insert['user_id'] = $user_id;
                    $insert['ipddress'] = Null;
                    $insert['datetime'] = date("Y-m-d H:i:s");
                    //echo '<pre> $insert=>'; print_r($insert); echo '</pre>'; Die();
                    
		// like  select
        //$this->load->model('api/like_model', 'like_model');
	    //$dataselect = $this->like_model->select($id);
		$id=$dataselect['id'];
		$ref_value=$dataselect['ref_value'];
		// like insert
        //$this->load->model('api/like_model', 'like_model');
	    //$data = $this->like_model->add($insert);
		
		// like  update 
        //$this->load->model('api/like_model', 'like_model');
	    //$data = $this->like_model->update($update,$id);

		$count=1;
		$data='Thank you very much';
		if($data){

					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>$count,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,
											'count'=>0,),
											'data'=> Null),200);
					}
	 
	}
    public function stickerapps_get(){
   	//stickerapps?sticker_id=10&icon_id=5
   	$stickerid=@$this->input->get('sticker_id');
   	$icon_id=@$this->input->get('icon_id');
    #$stickerid='10'; $icon_id='5';
	$sticker_list_api='http://trueplookpanya.appmanager.biz/api/v1/sticker';
	$json = file_get_contents($sticker_list_api);
	$sticker = json_decode($json);
	if($sticker==Null){  
					$this->response(array('header'=>array(
												'title'=>'Sticker url',
												'message'=>'Error',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
												Die();
	}
	
	
	$data=$sticker->data;
	$sticker_list=$data->sticker_list;
	#echo '<pre> $sticker_list==>'; print_r($sticker_list); echo '</pre>';  #Die();
		
	if($sticker_list==Null){  
					$this->response(array('header'=>array(
												'title'=>'Sticker url',
												'message'=>'Error',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
												Die();
	}
		
		
		$i=1;
		foreach ($sticker_list as $key => $value) {
			   $sticker_list_id=$value->sticker_list_id;
			   $sticker_name=$value->sticker_name;
			   $sticker_description=$value->sticker_description;
			   $ref1=$value->ref1;
			   $sticker_preview_image=$value->sticker_preview_image;
			   $sticker_image=$value->sticker_image;
			   $sticker_icon_list=$value->sticker_icon_list;
			   if($stickerid==$sticker_list_id){
				   #echo 'sticker_id = '.$sticker_list_id; echo '<br>';
				   $j=1;
					foreach ($sticker_icon_list as $key => $icon) {
						$sticker_icon_id=$icon->sticker_icon_id;
						$sticker_list_id=$icon->sticker_list_id;
						$sticker_icon_name=$icon->sticker_icon_name;
						$sticker_icon_image=$icon->sticker_icon_image;
						if($icon_id==$sticker_icon_id){
					  	#echo 'icon_id=>'.$sticker_icon_id; echo ' url sticker=>'; echo $sticker_icon_image; echo '<br>';
					  		
					      $this->response(array('header'=>array(
												'title'=>'Sticker url',
												'message'=>'Success',
												'status'=>true,
												'code'=>200), 
												'sticker_url'=> $sticker_icon_image,),200);
						  
					  		
					  		 
						}
					 $j++;
					}
				$i++;
			}
	}
	
	
}
    public function stickerappslist_get(){
    $sticker_list_api='http://trueplookpanya.appmanager.biz/api/v1/sticker';
	$json = file_get_contents($sticker_list_api);
	$sticker = json_decode($json);
	if($sticker==Null){  
					$this->response(array('header'=>array(
												'title'=>'Sticker url',
												'message'=>'Error',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
												Die();
	}
	
	
	$data=$sticker->data;
	$sticker_list=$data->sticker_list;
	#echo '<pre> $sticker_list==>'; print_r($sticker_list); echo '</pre>';  #Die();
		
	if($sticker_list==Null){  
					$this->response(array('header'=>array(
												'title'=>'Sticker url',
												'message'=>'Error',
												'status'=>true, 
												'code'=>404), 
												'data'=> $data),404);
												Die();
	}
		
		
		$i=1;
		foreach ($sticker_list as $key => $value) {
			   $sticker_list_id=$value->sticker_list_id;
			   $sticker_name=$value->sticker_name;
			   $sticker_description=$value->sticker_description;
			   $ref1=$value->ref1;
			   $sticker_preview_image=$value->sticker_preview_image;
			   $sticker_image=$value->sticker_image;
			   $sticker_icon_list=$value->sticker_icon_list;
			   if($stickerid==$sticker_list_id){
				   #echo 'sticker_id = '.$sticker_list_id; echo '<br>';
				   $j=1;
					foreach ($sticker_icon_list as $key => $icon) {
						$sticker_icon_id=$icon->sticker_icon_id;
						$sticker_list_id=$icon->sticker_list_id;
						$sticker_icon_name=$icon->sticker_icon_name;
						$sticker_icon_image=$icon->sticker_icon_image;
						if($icon_id==$sticker_icon_id){
					  	#echo 'icon_id=>'.$sticker_icon_id; echo ' url sticker=>'; echo $sticker_icon_image; echo '<br>';
			 
						}
					 $j++;
					}
				$i++;
			}
	}
	
	 $sticker_data='';
	 $this->response(array('header'=>array(
												'title'=>'Sticker url',
												'message'=>'Success',
												'status'=>true,
												'code'=>200), 
												'sticker_data'=> $sticker_data,),200);
	
	
}
    public function IsArraySomeKeyIntAndSomeKeyString($InputArray){
	    if(!is_array($InputArray)){
	        return false;
	    }
	    if(count($InputArray) <= 0){
	        return true;
	    }
    //return count(array_unique(array_map("is_string", array_keys($InputArray)))) >= 2;
   }
	public function webboardpostall_get(){ 
   	    $wb_post_id=@$this->input->get('topicid');
        $data = $this->center_model->get_webboardpost($wb_post_id);
		if($data){

					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,),
											'data'=> $data),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Unsuccess',
											'code'=>404,),
											'data'=> Null),404);
					}
		}
	public function webboardpoststatus_get(){ 
	// http://www.trueplookpanya.com/api/center/webboardpoststatus?topicid=21049
   	  $wb_post_id=@$this->input->get('topicid');
   	  $status_post=@$this->input->get('status');
   	  $status_data=$this->center_model->get_webboardpost_status_data($wb_post_id); 
   	  $wbstatus=$status_data['wb_status'];
   	  if($wbstatus==1){
   	  	               $status=0;
					   $data=$this->center_model->get_webboardpost_status($wb_post_id,$status);
					   $data_reply=$this->center_model->get_webboard_reply_status($wb_post_id,$status);
					   
					   $statusretrun='Disable';
					   $statusretrun=array('status'=>$status,
										    'message'=>$statusretrun,
											);
					}else{
						$status=1;
					    $data=$this->center_model->get_webboardpost_status($wb_post_id,$status);
					    $data_reply=$this->center_model->get_webboard_reply_status($wb_post_id,$status);
					    $statusretrun='Enable';
					    $statusretrun=array('status'=>$status,
										    'message'=>$statusretrun,
											);
					} 
    
   	   if($data){
                     
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,),
											'data'=> $statusretrun),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Unsuccess',
											'code'=>404,),
											'data'=> Null),404);
					}
		 
	}
 	public function webboardreplytstatus_get(){ 
 // http://www.trueplookpanya.com/api/center/webboardreplytstatus?replyid=11257
   	  $wb_reply_id=@$this->input->get('replyid');
   	  $status_post=@$this->input->get('status');
   	  $status_data=$this->center_model->get_webboardreply_status_data($wb_reply_id); 
   	  $wbstatus=$status_data['reply_status'];
   	  if($wbstatus==1){
   	  	               $status=0;
					   $data=$this->center_model->get_webboard_reply_status_id($wb_reply_id,$status);
					   $statusretrun='Disable';
					   $statusretrun=array('status'=>$status,
										    'message'=>$statusretrun,
											);
					}else{
						$status=1;
					    $data=$this->center_model->get_webboard_reply_status_id($wb_reply_id,$status);
					    $statusretrun='Enable';
					    $statusretrun=array('status'=>$status,
										    'message'=>$statusretrun,
											);
					} 
    
   	   if($data){
                     
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Success',
											'code'=>200,),
											'data'=> $statusretrun),200);
					}else{
					$this->response(array('header'=>array(
											'status'=>true,
											'message'=>'Unsuccess',
											'code'=>404,),
											'data'=> Null),404);
					}
		 
	}
	public function topicdetailv2_get() {

	$token=@$this->input->get('token');
	// $user_session=$this->session->userdata('user_session');
	// echo '<pre> $user_session=>'; print_r($user_session); echo '</pre>';  Die();
	$this->load->library('session');
 	if ($token==NULL) {
 			$islikepublic='false';
 		}else{
 			$islikepublic=Null;
 			$this->load->library('TPPY_Oauth');
            $my=$this->me = $this->tppy_oauth->parse_token($token);
			//echo '<pre>  $my=>'; print_r($my); echo '</pre>'; Die();
		    $user_session=$this->session->set_userdata($my);
			$user_id=$my->user_id;
		    $member_id=$my->member_id;	
			$this->load->model('api/centermobile_model', 'centermobile_model');
			$datauser=$this->centermobile_model->get_memberdata($user_id);
		}if($member_id==Null){
			$member_id=$datauser['member_id'];
			}
       
		$key=@$this->input->get('key');  
		if($key==Null){$key='16';}
	    $categoryid=@$this->input->get('category_id');  
			if($categoryid==Null){$categoryid=Null;}
		$room=@$this->input->get('room');
			if($room==Null){$room=Null;}
		$topicSearch=@$this->input->get('topicSearch');
			if($topicSearch==Null){$topicSearch=Null;}
		$order=@$this->input->get('order');
		if($order==Null){$order_reply=@$this->input->get('order_reply');
			if($order_reply==Null){$order_reply=Null;}
		}else{$order_reply=$order;}
		$limit=@$this->input->get('limit');
		if($limit==Null){$limit_reply=@$this->input->get('limit_reply');
			if($limit_reply==Null){$limit_reply=40;}
		}else{$limit_reply=$limit;}
		$offset=@$this->input->get('offset');
			if($offset==Null){$offset=0;}
		$topicid=@$this->input->get('topicid');
			if($topicid==Null){$topicid=Null;}
		$type=@$this->input->get('type'); // type=mobile // apps //type=web	
			
		$json = null;
		$this->load->model('webboardapi_model');

		$order=$order_reply;

		$pageSize = $limit_reply;
		$pageSize = ($pageSize > 100) ? 100 : $pageSize;
		if(isset($_GET['pageNo']) && !empty($_GET['pageNo'])){
			$pageNo = trim($_GET['pageNo']);
		}
		else {
			$pageNo = 1;
		}
		$rowStart = (($pageNo-1) * $pageSize);
		$rowEnd = ($pageNo * $pageSize);

		if($order=='lastreply') { $v_order='reply_datetime desc'; }
		else { $v_order=''; }
		

        $device=@$this->input->get('type');  
        if($device==Null){ $device='desktop'; }

		// get Topic
		$qResult = $this->webboardapi_model->getTopic($room, null, null , null, 1, null, $topicid);
		$json['header']['title'] = 'Webboard get webboard detail By topicid';
		$json['header']['code'] = 200;
		$json['header']['status'] = true;
		$json['header']['message'] = 'Success';
		$json['header']['type'] = 'HTTP GET';
		$json['header']['device'] = $device;
		$user_session=$this->session->set_userdata($my);
		$user_id=$my->user_id;
		$json['header']['user_id'] = $user_id;
		#echo '<pre> $qResult=>'; print_r($qResult); echo '</pre>'; Die();
		$arr_result = array();
		if(isset($qResult) and $qResult) {
			$compData1='';
			$compData2='';
			$comData3='';
			$i=-1;
			$j=-1;
			$k=-1;
			foreach ($qResult as $v_result) {
				$arr = array();
				if($compData1!=$v_result['wb_room_id']){
					$i++;
					$arr['room']['roomId'] = $v_result['wb_room_id'];
					$arr['room']['roomName'] = $v_result['wb_room_name'];
					$arr_result['room'][$i] = $arr['room'];
				}
				if($compData2!=$v_result['wb_category_id']){
					$j++;
					$arr['room']['category']['categoryId'] = $v_result['wb_category_id'];
					$arr['room']['category']['categoryName'] = htmlspecialchars($v_result['wb_category_name']);
					$arr['room']['category']['categoryDescription'] = htmlspecialchars($v_result['wb_category_desc']);
					$arr_result['room'][$i]['category'][$j] = $arr['room']['category'];
				}
				if($compData3!=$v_result['wb_post_id']){
					$k++;
					$arr['room']['category']['topic']['topicId'] = $v_result['wb_post_id'];
					if($v_result['thumb']==Null){
					$arr['room']['category']['topic']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';	
					}else{
					$arr['room']['category']['topic']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/'.$v_result['thumb'];
					}
					$arr['room']['category']['topic']['createByImage'] = $v_result['psn_display_image'];
					
					$arr['room']['category']['topic']['topicTitle'] = htmlspecialchars($v_result['wb_subject']);
					$arr['room']['category']['topic']['topicDetail'] = htmlspecialchars($v_result['wb_detail']);
					$arr['room']['category']['topic']['createBy'] = htmlspecialchars($v_result['psn_display_name']);
					
					
					$arr['room']['category']['topic']['topic_viewcount'] = $this->webboardapi_model->getViewNumber($v_result['wb_post_id'], '15');//$v_result['viewcount'];
					$arr['room']['category']['topic']['topic_createdate'] = $v_result['add_date'];
					$arr['room']['category']['topic']['topic_lastupdate'] = $v_result['last_update_date'];
					//$arr['room']['category']['topic']['web_url_go'] = 'http://www.trueplookpanya.com/true/webboard_detail.php?postid='.$v_result['wb_post_id'];
					//$arr['room']['category']['topic']['flagPin'] = $v_result['flag_pin'];
					$totalRows = $this->webboardapi_model->getCountReply($v_result['wb_post_id']);
					$pageTotal = ceil($totalRows / $pageSize);
					$arr['room']['category']['topic']['rowCount_reply'] = $totalRows;
					$arr['room']['category']['topic']['rowRange_reply'] = trim(($rowStart+1).'-'.$rowEnd);
					$arr['room']['category']['topic']['pageTotal_reply'] = $pageTotal;
					$arr_result['room'][$i]['category'][$j]['topic'][] = $arr['room']['category']['topic'];
				}
				
				// Like
			 
				$like_count=$this->webboardapi_model->like_count($topicid);
				$reply_count=$this->webboardapi_model->reply_count($topicid);
				$isFavorite2=$this->webboardapi_model->isFavorite($topicid);
				$isFavorite=$this->favorite_model->get_user_favorite($user_id,16,$topicid);
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['like_count']=$like_count;
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply_count']=$reply_count;
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['isFavorite']=$isFavorite;
				 
				$islike=$this->webboardapi_model->islike($topicid,$user_id='');
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['islike ']=$islike;
				//og // node Social
				
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:app_id'] = "704799662982418";
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:title'] = htmlspecialchars($v_result['wb_subject']);
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:type'] = 'article';
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:locale'] = 'th_TH';
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:url'] = 'http://www.trueplookpanya.com/admissions/webboard/detail/'.$v_result['wb_post_id'];
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:image'] = $v_result['psn_display_image'];
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:site_name'] = 'trueplookpanya.com';
				$wb_detail= htmlspecialchars($v_result['wb_detail']);
				$arr_result['room'][$i]['category'][$j]['topic'][$k]['og']['og:description'] = $this->trueplook->limitText(strip_tags(str_replace('\'', ' ', str_replace('\"', ' ', $wb_detail))), 200);
				
				// get LastReply
				//$qResultRs = $this->webboardapi_model->getTopicDetail($topicid,$order_reply,$limit_reply=1,$offset_reply=0);
				$this->load->model('api/centermobile_model', 'centermobile_model');
				$qResultRs = $this->centermobile_model->get_webboard_postreply_for_mobile_offset($topicid,$limit=1,$offset=0);
				 //$arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']=$qResultRs;
				 if($qResultRs==Null){
				 	$arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']= []; //Null;
				 }else{
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyId']=$qResultRs['0']['wb_reply_id'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyDetail']=$qResultRs['0']['reply_detail'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyBy']=$qResultRs['0']['username'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyByImage']=$qResultRs['0']['display_image'];
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['replyDate']=$qResultRs['0']['add_date'];
				 //reply_sticker 
				 $InputArray=$qResultRs['0']['reply_detail'];
				 $IsArray=$this->IsArraySomeKeyIntAndSomeKeyString($InputArray);
				 //echo '<pre> $IsArray=>'; print_r($IsArray); echo '</pre> <hr>'; Die();
				 
				 
				 if(is_array($InputArray)){
				 	
				 	$reply_type=0;
				 	 $reply_sticker=Null; 
					 $sticker_id=Null;
					 $sticker_url=Null;
				 	/*
					$reply_type=1;
				 	$InputArraydecode=(json_decode($InputArray, true));
				 	  
					  $sticker_id=$InputArraydecode['sticker_list_id'];
    				  $icon_id=$InputArraydecode['sticker_icon_id'];
    				  $sticker_url=$InputArraydecode['sticker_url']; 
    				  $reply_sticker==$InputArraydecode['sticker_url']; 
    				 #echo '<pre> $InputArraydecode=>'; print_r($InputArraydecode); echo '</pre> <hr>'; 
    				 #echo '<pre> $sticker_id=>'; print_r($sticker_id); echo '</pre>';
    				 #echo '<pre> $reply_sticker=>'; print_r($reply_sticker); echo '</pre>'; Die();
    				 */
					
				 }else{
				 	/*
				 	 $reply_type=0;
				 	 $reply_sticker=Null; 
					 $sticker_id=Null;
					 $sticker_url=Null;
					 */
					 
					 
				 	$InputArraydecode=(json_decode($InputArray, true));
				 	  
					  $sticker_id=$InputArraydecode['sticker_list_id'];
    				  $icon_id=$InputArraydecode['sticker_icon_id'];
    				  $sticker_url=$InputArraydecode['sticker_url']; 
    				  $reply_sticker==$InputArraydecode['sticker_url']; 
					 if($sticker_url==Null){
						$reply_type=0;
						}else{
						$reply_type=1;
						}
				 }
				 
				  
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['reply_type']=(int)$reply_type;
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['sticker_id']=$sticker_id;
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['sticker_icon_id']=$icon_id;
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['sticker_url']=$sticker_url;
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['reply_sticker']=$sticker_url;
				 
				 $islike=$this->webboardapi_model->islike($topicid,$user_id='');
				 $arr_result['room'][$i]['category'][$j]['topic'][$k]['lastreply']['islike ']=$islike; 
			    }
				 
				// get Reply
				$qResultR = $this->webboardapi_model->getTopicDetail($topicid, $v_order, $pageSize, $rowStart);
				if(isset($qResultR) and $qResultR) {
					foreach ($qResultR as $v_resultR) {
						if($v_resultR['wb_reply_id']!=null || !empty($v_resultR['wb_reply_id'])){
							$arr['room']['category']['topic']['reply']['replyId'] = $v_resultR['wb_reply_id'];
							$arr['room']['category']['topic']['reply']['replyDetail'] = htmlspecialchars($v_resultR['reply_detail']);
							$arr['room']['category']['topic']['reply']['replyBy'] = htmlspecialchars($v_resultR['replyBy']);
							$arr['room']['category']['topic']['reply']['replyByImage'] = $v_resultR['replyByImage'];
							$arr['room']['category']['topic']['reply']['replyDate'] = $v_resultR['reply_datetime'];
				//reply_sticker 	
 				 $InputArray=$v_resultR['reply_detail'];
 				 $InputArraydecode=(json_decode($InputArray, true));
				 $IsArray=$this->IsArraySomeKeyIntAndSomeKeyString($InputArray);
				 
				 /*
				 $value = $InputArray;
					if(is_array($value)){
					  echo '<pre> $InputArraydecode=>'; print_r('is array'); echo '</pre>';
					} else {
					 
					 echo '<pre> $InputArraydecode=>'; print_r('not array'); echo '</pre>';
				  }
				 echo '<pre> $InputArraydecode=>'; print_r($InputArraydecode); echo '</pre>';
				 echo '<pre> $reply_sticker=>'; print_r($reply_sticker); echo '</pre>'; //Die();
				 */
				 
				if(is_array($InputArray)){
					$reply_type=0;
				 	 $reply_sticker=Null; 
					 $sticker_id=Null;
					 $sticker_url=Null;
					 /*
					$reply_type=1;
				 	$InputArraydecode=(json_decode($InputArray, true));
					$sticker_id=$InputArraydecode['sticker_list_id'];
    				  $icon_id=$InputArraydecode['sticker_icon_id'];
    				  $sticker_url=$InputArraydecode['sticker_url']; 
    				  $reply_sticker==$InputArraydecode['sticker_url']; 
    				 #echo '<pre> $InputArraydecode=>'; print_r($InputArraydecode); echo '</pre> <hr>'; 
    				 #echo '<pre> $sticker_id=>'; print_r($sticker_id); echo '</pre>';
    				 #echo '<pre> $reply_sticker=>'; print_r($reply_sticker); echo '</pre>'; Die();
					*/
				 }else{
				 	/*
				 	$reply_type=0;
				 	 $reply_sticker=Null; 
					 $sticker_id=Null;
					 $sticker_url=Null;
					 */
					 //$reply_type=1;
				 	$InputArraydecode=(json_decode($InputArray, true));
					$sticker_id=$InputArraydecode['sticker_list_id'];
					
					
					
    				  $icon_id=$InputArraydecode['sticker_icon_id'];
    				  $sticker_url=$InputArraydecode['sticker_url']; 
    				  $reply_sticker==$InputArraydecode['sticker_url']; 
    				  
    				  if($sticker_url==Null){
						$reply_type=0;
						}else{
						$reply_type=1;
						}
    				  
    				 #echo '<pre> $InputArraydecode=>'; print_r($InputArraydecode); echo '</pre> <hr>'; 
    				 #echo '<pre> $sticker_id=>'; print_r($sticker_id); echo '</pre>';
    				 #echo '<pre> $reply_sticker=>'; print_r($reply_sticker); echo '</pre>'; Die();
					 
				 }

				 $arr['room']['category']['topic']['reply']['reply_type']=(int)$reply_type;
				 $arr['room']['category']['topic']['reply']['sticker_id']=$sticker_id;
				 $arr['room']['category']['topic']['reply']['sticker_icon_id']=$icon_id;
				 $arr['room']['category']['topic']['reply']['sticker_url']=$sticker_url;
				 $arr['room']['category']['topic']['reply']['reply_sticker']=$sticker_url;
							
							
						    $islike=$this->webboardapi_model->islike($topicid,$user_id='');
				 			$arr['room']['category']['topic']['reply']['islike ']=$islike;
							
							$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'][] = $arr['room']['category']['topic']['reply'];
						}else{
							$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply'] = []; //null;
						}
					}
				}else{
					$arr_result['room'][$i]['category'][$j]['topic'][$k]['reply']= []; //null;
				}
				

				$compData1 = $v_result['wb_room_id'];
				$compData2 = $v_result['wb_category_id'];
				$compData3 = $v_result['wb_post_id'];
				$compData4 = $v_result['wb_post_id'];
				if($type=='apps' || $type=='mobile'){}else{
				 $limit=@$this->input->get('limit'); 
				 if($limit==Null){$limit=10;}
				$qResultRelated=$this->webboardapi_model->getTopic($room=null, null, null , null,$limit, null, $topicid=null);
				foreach ($qResultRelated as $v_result) {
						$arr = array();
						if($qResultRelated!=Null){
							$k++;
							$arr['room']['category']['related']['topicId'] = $v_result['wb_post_id'];
							if($v_result['thumb']==Null){
							$arr['room']['category']['related']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/webboard/file/thumpdefault.png';	
							}else{
							$arr['room']['category']['related']['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/'.$v_result['thumb'];
							}
							$arr['room']['category']['related']['createByImage'] = $v_result['psn_display_image'];
							
							$arr['room']['category']['related']['topicTitle'] = htmlspecialchars($v_result['wb_subject']);
							$arr['room']['category']['related']['topicDetail'] = htmlspecialchars($v_result['wb_detail']);
							$arr['room']['category']['related']['createBy'] = htmlspecialchars($v_result['psn_display_name']);
							
							
							$arr['room']['category']['related']['topic_viewcount'] = $this->webboardapi_model->getViewNumber($v_result['wb_post_id'], '15');//$v_result['viewcount'];
							$arr['room']['category']['related']['topic_createdate'] = $v_result['add_date'];
							$arr['room']['category']['related']['topic_lastupdate'] = $v_result['last_update_date'];
							//$arr['room']['category']['related']['web_url_go'] = 'http://www.trueplookpanya.com/true/webboard_detail.php?postid='.$v_result['wb_post_id'];
							//$arr['room']['category']['related']['flagPin'] = $v_result['flag_pin'];
							$totalRows = $this->webboardapi_model->getCountReply($v_result['wb_post_id']);
							$pageTotal = ceil($totalRows / $pageSize);
							$arr['room']['category']['related']['rowCount_reply'] = $totalRows;
							$arr['room']['category']['related']['rowRange_reply'] = trim(($rowStart+1).'-'.$rowEnd);
							$arr['room']['category']['related']['pageTotal_reply'] = $pageTotal;
							$arr_result['room'][$i]['category'][$j]['related'][] = $arr['room']['category']['related'];
						}
				}
				//////////
			} 
				 
				
				
				
			}
		}
 
        # echo '<pre> $arr_result=>'; print_r($arr_result); echo '</pre>'; 
		//Die();
		$json['data'] = $arr_result;
		$json['header']['orderby'] = $order;
		$json['header']['pageno'] = $pageNo;
		$json['header']['pagesize'] = $pageSize;
        $this->tppymemcached->set($key, $json, 259200);
		//echo json_encode($json, JSON_UNESCAPED_UNICODE);
		$this->response($json);
	}
##### END ######	
}

  