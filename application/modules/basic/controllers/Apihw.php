<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Apihw extends MY_Controller {	
	public function __construct(){
		parent::__construct();
		$language = $this->lang->language;
	}
	public function index(){
				if(!$this->session->userdata('is_logged_in')){
				redirect(base_url());
				 }
			$language = $this->lang->language;
			$breadcrumb[] = $language['api'];

			$language = $this->lang->language;
			$admin_id = $this->session->userdata('admin_id');
			$admin_type = $this->session->userdata('admin_type');
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
			$loadfile = "admintype".$admin_type.".json";
			$admin_menu = LoadJSON($loadfile);
			$data = array(
					"ListSelect" => $ListSelect,
					"admin_menu" => $admin_menu,
					"content_view" => 'api/api',
					"breadcrumb" => $breadcrumb,
			);	
	$this->load->view('template/template',$data);
	}
	
	public function admintype($id){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_admin_type());
		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/admin_type.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $json;		
	}
	
	public function alertconfig(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_alertconfig());

		$json=$jsonencode;
		// Make File Json
		    $url=base_url();
			$file=$url.'api/json/alert_config.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	
	public function condition($status='y'){
		$conditionid = $this->uri->segment(3);# ส่งค่าไป

		//echo '$conditionid='.$conditionid;
		//exit();
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_condition($conditionid));

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/condition.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	
	public function conditiongroup(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_condition_group());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/conditiongroup.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	
	public function conditiontype(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_condition_type());
		
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/condition_type.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	
	public function email_alert_log(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_email_alert_log());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/email_alert_log.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	
	public function email_config(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_email_config());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/email_config.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	
	public function email_lists(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_email_lists());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/email_lists.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	public function general_setting(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_general_setting());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/general_setting.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	public function hardware(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_hardware());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/hardware.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	public function hardware_access(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_hardware_access());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/hardware_access.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}	
	public function hardware_access_log(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_hardware_access_log());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/hardware_access_log.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	public function hardware_alert_log(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_hardware_alert_log());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/hardware_alert_log.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}	
	public function hardware_control(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_hardware_control());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/hardware_control.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	public function hardware_port(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_hardware_port());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/hardware_port.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	public function hardware_type(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_hardware_type());
		
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/hardware_type.json';
			$fd=fopen($file, 'w+');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	public function sensor_alert_log($startdate=null,$enddate=null,$limitstart=null,$limitend=null,$orderby=null){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$startdate = $this->uri->segment(3);# ส่งค่าไป
		$enddate = $this->uri->segment(4);# ส่งค่าไป
		$limitstart = $this->uri->segment(5);# ส่งค่าไป
		$limitend = $this->uri->segment(6);# ส่งค่าไป
		$orderby = $this->uri->segment(7);# ส่งค่าไป
		$jsonencode=json_encode($this->apijson_model->get_sensor_alert_log($startdate,$enddate,$limitstart,$limitend,$orderby));
		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/sensor_alert_log.json';
			$fd=fopen($file, 'w+');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}	
		public function sensor_config(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_sensor_config());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/sensor_config.json';
			$fd=fopen($file, 'w+');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $json;		
	}
	public function sensor_log($startdate=null,$enddate=null,$limitstart=null,$limitend=null,$orderby=null){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$startdate = $this->uri->segment(3);# ส่งค่าไป
		$enddate = $this->uri->segment(4);# ส่งค่าไป
		$limitstart = $this->uri->segment(5);# ส่งค่าไป
		$limitend = $this->uri->segment(6);# ส่งค่าไป
		$orderby = $this->uri->segment(7);# ส่งค่าไป
		$jsonencode=json_encode($this->apijson_model->get_sensor_log($startdate,$enddate,$limitstart,$limitend,$orderby));

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/sensor_log.json';
			$fd=fopen($file, 'w+');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $json;		
	}		
	public function sensor_log_all($hwname=null,$sensor_name=null,$startdate=null,$enddate=null,$limitstart=null,$limitend=null,$orderby=null){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$hwname = $this->uri->segment(3);# ส่งค่าไป
		$sensor_name = $this->uri->segment(4);# ส่งค่าไป
		$startdate = $this->uri->segment(5);# ส่งค่าไป
		$enddate = $this->uri->segment(6);# ส่งค่าไป
		$limitstart = $this->uri->segment(7);# ส่งค่าไป
		$limitend = $this->uri->segment(8);# ส่งค่าไป
		$orderby = $this->uri->segment(9);# ส่งค่าไป
		$jsonencode=json_encode($this->apijson_model->get_sensor_log_all($hwname,$sensor_name,$startdate,$enddate,$limitstart,$limitend,$orderby));

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/sensor_log_all.json';
			$fd=fopen($file, 'w+');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $json;		
	}	
	public function sensor_type(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_sensor_type());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/sensor_type.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	public function sms_lists(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_sms_lists());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/sms_lists.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	public function waterleak_log_all(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_waterleak_log_all());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/waterleak_log_all.json';
			$fd=fopen($file, 'w');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
	public function waterleak_log($startdate=null,$enddate=null,$limitstart=null,$limitend=null,$orderby=null){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$startdate = $this->uri->segment(3);# ส่งค่าไป
		$enddate = $this->uri->segment(4);# ส่งค่าไป
		$limitstart = $this->uri->segment(5);# ส่งค่าไป
		$limitend = $this->uri->segment(6);# ส่งค่าไป
		$orderby = $this->uri->segment(7);# ส่งค่าไป
		$jsonencode=json_encode($this->apijson_model->get_waterleak_log($startdate,$enddate,$limitstart,$limitend,$orderby));

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/waterleak_log.json';
			$fd=fopen($file, 'w+');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $json;		
	}	
######################## 
	public function hw($ipaddress=null){
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$url = $this->uri->segment(3);# ส่งค่าไป
		$ch = curl_init();  	
		if($url=='192.168.10.223'){
	    echo 'Error';
	    exit();
		}
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		//curl_setopt($ch,CURLOPT_HEADER, false); 
		$output=curl_exec($ch);
		curl_close($ch);
		$json=$output;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/hw_'.$ipaddress.'.json';
			$fd=fopen($file, 'w+');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			//$filejson=base_url($file);
			$filejson=$json;
			echo $filejson;		
	}
	public function hw2($ipaddress=null){
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: text/html  charset=utf-8');
		$url = $this->uri->segment(3);# ส่งค่าไป
		$ch = curl_init();  
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		//curl_setopt($ch,CURLOPT_HEADER, false); 
		$output=curl_exec($ch);
		curl_close($ch);
		$json=$output;
		// Make File Hext
			$file='api/json/hw_'.$ipaddress.'.txt';
			$fd=fopen($file, 'w+');
			fwrite($fd,$json);
			fclose($fd);
		echo $json;		
	}
########################
	public function settings_company(){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$jsonencode=json_encode($this->apijson_model->get_settings_company());

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/settings_company.json';
			$fd=fopen($file, 'w+');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}

	public function sensor_chart($hwname=null,$sensorname=null,$group=null,$startdate=null,$enddate=null,$limit=null){
		$this->load->model('apijson_model');
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json  charset=utf-8');
		$hwname = $this->uri->segment(3);# ส่งค่าไป
		$sensorname = $this->uri->segment(4);# ส่งค่าไป
		$group = $this->uri->segment(5);# ส่งค่าไป
		$startdate = $this->uri->segment(6);# ส่งค่าไป
		$enddate = $this->uri->segment(7);# ส่งค่าไป
		$limit = $this->uri->segment(8);# ส่งค่าไป
		$timenow=date(' H:i:s');
		$startdate=strtotime($startdate.$timenow);
		$enddate=strtotime($enddate.$timenow);
		$jsonencode=json_encode($this->apijson_model->get_sensor_chart($hwname,$sensorname,$group,$startdate,$enddate,$limit));

		$json=$jsonencode;
		// Make File Json
			$url=base_url();
			$file=$url.'api/json/'.$hwname.'_'.$sensorname.'_'.$group.'_chart.json';
			$fd=fopen($file, 'w+');
			fwrite($fd,$json);
			fclose($fd);
			// Show Json
			echo $jsonencode;		
	}
		public function dcodesensor_charthw1(){
		    $url=base_url();
			$json_string=$url.'/api/json/HW1_sensor1_hour_chart.json';
			$jsondata=file_get_contents($json_string);
			$data_ret=json_decode($jsondata,true);
			$count=count($data_ret);
			$arr =$data_ret;

			if($count > 0){
			echo '[';
				for($i=1; $i<$count; $i++){
					$sensor_log_id=$arr[$i]['sensor_log_id'];
					$sensor_hwname=$arr[$i]['sensor_hwname'];
					$sensor_name=$arr[$i]['sensor_name'];
					$sensor_type=$arr[$i]['sensor_type'];
					$sensor_value=$arr[$i]['sensor_value'];
					$datetime_log=$arr[$i]['datetime_log'];
					$count2=$count-1;
					//echo '$count='.$count2;
					echo'['.$i.',';
					echo $sensor_value.']';
					if($i<>$count2){echo ',';}
				}
				echo ']';
			}else{echo 'Error 200';}	
		}
		public function dcodesensor_chart(){
		    $url=base_url();
			$json_string=$url.'/api/json/HW2_sensor1_hour_chart.json';
			$jsondata=file_get_contents($json_string);
			$data_ret=json_decode($jsondata,true);
			$count=count($data_ret);
			$arr =$data_ret;

			if($count > 0){
			echo '[';
				for($i=1; $i<$count; $i++){
					$sensor_log_id=$arr[$i]['sensor_log_id'];
					$sensor_hwname=$arr[$i]['sensor_hwname'];
					$sensor_name=$arr[$i]['sensor_name'];
					$sensor_type=$arr[$i]['sensor_type'];
					$sensor_value=$arr[$i]['sensor_value'];
					$datetime_log=$arr[$i]['datetime_log'];
					$count2=$count-1;
					//echo '$count='.$count2;
					echo'['.$i.',';
					echo $sensor_value.']';
					if($i<>$count2){echo ',';}
				}
				echo ']';
			}else{echo 'Error 200';}	
		}
}
?>