<?php
/*  Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand  @copyright kongnakorn  jantakun 2015 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Activity_logsna extends CI_Controller {
public function __construct()    {
	parent::__construct();
        // load helper
		$this->load->helper('url');
		$this->load->library('session');
        $this->load->model('Adminna_log_activity_model');
        $this->load->library("pagination");
		//$this->load->library(array('pagination','session'));
		$breadcrumb = array();
		//chack login
		if(!$this->session->userdata('is_logged_in')){redirect(base_url());}
	}
public function replacetext($find,$replace,$string) {
		$strreplace1=str_replace($find,$replace,$string,$count);
		$strreplace=str_replace($search,$replace,$string);
		// $search คือ คำที่ต้องการลบ
		// $replace คือ คำที่ต้องการใส่แทน
		// $string คือ ค่าตัวแปรที่ส่งมา 
	
		// รูปแบบ
		// ฟังก์ชัน str_replace()
		// แทนคำในสตริงด้วยคำที่ต้องการ ด้วยฟังก์ชัน str_replace()
		// ฟังก์ชัน str_ireplace() เป็น case-sensitive คือ สนใจตัวอักษรใหญ่และเล็ก
		// str_replace(find , replace , string , count)
		// find คือ คำที่ต้องการลบ
		// replace คือ คำที่ต้องการใส่แทน
		// string คือ สตริง 
		// count คือ ตัวแปรที่รับข้อมูลจำนวนคำที่แทนลงไป (นับเป็นคำ)
			return $strreplace;
		}
public function base64encrypttime($data,$time='',$key='') {
		if($key==null){
			$key=$this->config->item('jwt_key');
		}
		if($time==null){
			$time_setting=20; 
		}else{
			$time_setting=$time;
		}
		$issued_at=time();
		$issued_at_date=(date("Y-m-d H:i:s",$issued_at));
		$expiration_time=$issued_at+$time_setting;  
		$expiration_time_date=(date("Y-m-d H:i:s",$expiration_time));
		#echo '<hr><pre>data=>'; print_r($data); echo '</pre><pre>expiration_time_date=>'; print_r($expiration_time_date); echo '</pre>';die();
		$data=array('data'=>$data,
					'time_issued_at'=>(date("Y-m-d H:i:s",$issued_at)),
					'time_expiration'=>(date("Y-m-d H:i:s",$expiration_time)),
					'key'=>$key,
					'time_setting'=>$time_setting
				);
		$dataalls=base64_encode(serialize($data)); 
		#echo '<hr><pre>base64_encode=>'; print_r($dataalls); die();
		return $dataalls;
		#Die();
		}
public function base64decrypttime($data,$key='') {
		if($key==null){
			$key=$this->config->item('jwt_key');
		}
		$dataalls=unserialize(base64_decode($data));
		$data=$dataalls['data'];
		$keyrs=$dataalls['key'];
		$time_issued_at=$dataalls['time_issued_at'];
		$time_expiration=$dataalls['time_expiration'];
		$time_setting=(int)$dataalls['time_setting'];
		if($keyrs!==$key){
				$dataalls=array('message'=>' Error key ไม่ถูกต้อง','data'=>null,'status'=>0);
				 return $dataalls;
				 Die();
			}
		$issued_at=strtotime($time_issued_at);
		$issued_expiration=strtotime($time_expiration);
		$now=time();
		$now=(int)$now;
		$issued_at=(int)$issued_at;
		$timecul=($now-$issued_at);
		$issued_at=$issued_at+$time_setting;   
		/*
		echo '<hr><pre>timenow=>'; print_r($now);
		echo '<pre>issued_at=>'; print_r($issued_at);
		echo '<pre>expiration=>'; print_r($issued_expiration);
		echo '<hr><pre>timecul=>'; print_r($timecul); 
		echo '<hr><pre>time_setting=>'; print_r($time_setting); 
		echo '<hr><pre>dataalls=>'; print_r($dataalls);die();
		*/
			if($timecul>$time_setting){
				$msg_time='Expired หมดเวลา Seesion มีอายุ '.$time_setting.' วินาที';
				$dataalls=array('message'=>$msg_time,'data'=>null,'status'=>0);
				return $dataalls;
				Die();
			}else{
				$msg_time='On time not Expired yet';
				$dataalls=array('message'=>$msg_time,'data'=>$data,'status'=>1);
				return $dataalls;
				Die();
			}
		}   
public function base64_encrypt($string, $key) {
			$result='';
			for ($i=0; $i < strlen($string); $i++) {
				$char=substr($string, $i, 1);
				$keychar=substr($key, ($i % strlen($key)) - 1, 1);
				$char=chr(ord($char) + ord($keychar));
				$result.=$char;
			}
			return base64_encode($result);
		}
public function base64_decrypt($string, $key) {
			$result='';
			$string=base64_decode($string);
			for ($i=0; $i < strlen($string); $i++) {
				$char=substr($string, $i, 1);
				$keychar=substr($key, ($i % strlen($key)) - 1, 1);
				$char=chr(ord($char) - ord($keychar));
				$result.=$char;
			}
			return $result;
		}
public function serialize($dataset) {
			$result=serialize($dataset);
			return $result;
		}
public function unserialize_get($dataset) {
			$result=unserialize($dataset);
			return $result;
		}
public function implode($array) {
			$result=implode(",", $array);
			return $result;
		}
public function explode($array) {
			$result=explode(",", $array);
			return $result;
		}	
public function index(){
			$this->listview();
    }
public function listview1($pageIndex=1) {
	$startdate = $this->input->get_post('startdate',TRUE);
	$enddate = $this->input->get_post('enddate',TRUE);
	$admin_type=(int)$this->session->userdata('admin_type');
	if($admin_type<>1){
		redirect(base_url());
	}
	   // $pagination = $this->session->userdata($segment);
		$language = $this->lang->language;
		//$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
		if($admin_type==1){
		$breadcrumb[] = '<a href="'.base_url('activity_logsna/cleardataall').'">'.$language['reset'].'</a>';
		}
		$breadcrumb[] = $language['activity_logs'];
		$webtitle = $language['activity_logs'].' - '.$language['titleweb'];
		//$searchterm = $this->input->get_post('searchterm',TRUE);
		//Debug($ListSelect);
		//die();	
		
		//$segment=$this->uri->segment(3);
		//$segment2=$this->uri->segment(4);
		//$segment=$this->session->userdata($segment);
		// Pagination  
		
		$total_rows= $this->Adminna_log_activity_model->totallogactivity($startdate,$enddate);
		if($startdate=='' && $enddate=='' ){$limit = 200;}else{$limit = $total_rows;}
		$segment = 3;
		$pageSize=$limit;
		$start=1;
		$this->load->helper("pagination"); //Pagination helper  
		if($startdate!==''){
		$search_key='/'.$startdate.'/'.$enddate.'/';
		$pageConfig = doPagination($this->Adminna_log_activity_model->totallogactivity($startdate,$enddate), $limit, $segment,$startdate,$enddate, site_url("/activity_logsna/listview"));
		//$pageConfig=$pageConfig;//.$search_key;
		}else{
		$yesterday=strtotime("yesterday");
	     $yesterday =date("Y-m-d", $yesterday); 
	     $timena=date(' H:i:s');
	     $startdate=$yesterday.$timena;
	     $enddate=date('Y-m-d H:i:s');
		$pageConfig = doPagination($this->Adminna_log_activity_model->totallogactivity($startdate,$enddate), $limit, $segment,$startdate,$enddate, site_url("/activity_logsna/listview"));
		}
		//Debug($pageConfig);
		//die();
		//his->load->library("pagination");
		$this->pagination->initialize($pageConfig, $pageIndex);
	
		// Get data from my_model  
		if($startdate!==''){
		$logs_list = $this->Adminna_log_activity_model->view_log($pageIndex, $limit,$startdate,$enddate);
		}else{
		$logs_list = $this->Adminna_log_activity_model->view_log($pageIndex, $limit);
		}
		
		//$logs_list = $this->Adminna_log_activity_model->view_log($limit,$start * $limit);
		 #Debug($logs_list); die();
		 $links=$this->pagination->create_links();
		 //$links=$this->pagination->create_links($limit, $start);
		 //Debug($links);
		 //die();
		$data = array(
					"startdate" => $startdate,
					"enddate" => $enddate,
					//"ListSelect" => $ListSelect,
					"logs_list" => $logs_list,
					"webtitle" => $webtitle,
					"pagination" => $links,
					"content_view" => 'tmon/activity_logsna',
					"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);
		 //Debug($data);
		 
		 
	}
public function cleardataall(){
			$admin_type=(int)$this->session->userdata('admin_type');
			if($admin_type<>1){
				redirect(base_url());
			}
  
			$cleardataalld = $this->Adminna_log_activity_model->cleardataall();		
   #echo '<pre>$cleardataalld=>'; print_r($cleardataalld); echo '</pre>';  Die();
			$language = $this->lang->language;	
          //**************Log activity
          	$language = $this->lang->language;
			$edit = $language['edit'];
			$savedata = $language['savedata'];
               $session_id_admin=$this->session->userdata('admin_id');
               $ref_id=$this->session->userdata('admin_type');
               ########IP#################	
              # $ipaddress=$_SERVER['REMOTE_ADDR'];	
               $ipaddress = '127.0.0.1';
               ########IP#################
               $ref_type=1;
               if($cur_status==1){$cur_statusname='ON';}else{$cur_statusname='OFF';}
               $ref_title='RESET LOG';
               $action=2;
               $create_date=date('Y-m-d H:i:s');
               $status=1;
               $langlog = $language['lang'];
          	 $log_activity = array(
          					"admin_id" => $session_id_admin,
          					"ref_id" => $ref_id,
          					"from_ip" => $ipaddress,
          					"ref_type" => $ref_type,
          					"ref_title" => $ref_title,
          					"action" => $action,
		                         "create_date" => $create_date,
		                         "status" => $status,
		                         "lang"=>$langlog,
          			);		
          	$this->Admin_log_activity_model->store($log_activity);
               //Debug($log_activity); Die();
          //**************Log activity
          redirect('activity_logsna');

	}
public function listview($pageIndex=1) {
		$startdate = $this->input->get_post('startdate',TRUE);
		$enddate = $this->input->get_post('enddate',TRUE);
		$admin_type=(int)$this->session->userdata('admin_type');
		#Debug($startdate); Debug($enddate); die();	
		if($admin_type<>1){
				redirect(base_url());
			}
		   // $pagination = $this->session->userdata($segment);
			$language = $this->lang->language;
			//$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			if($admin_type==1){
				$breadcrumb[] = '<a href="'.base_url('activity_logsna/cleardataall').'">'.$language['reset'].'</a>';
				}
			$breadcrumb[] = $language['activity_logs'];
			$webtitle = $language['activity_logs'].' - '.$language['titleweb'];
			//$searchterm = $this->input->get_post('searchterm',TRUE);
			//Debug($ListSelect); die();	
			//$segment=$this->uri->segment(3);
			//$segment2=$this->uri->segment(4);
			//$segment=$this->session->userdata($segment);
			// Pagination  
			$total_rows= $this->Adminna_log_activity_model->totallogactivity($startdate,$enddate);
			if($startdate=='' && $enddate=='' ){$limit = 200;}else{$limit = $total_rows;}
			$segment = 3;
			$pageSize=$limit;
			$start=1;
			$this->load->helper("pagination"); //Pagination helper  
			if($startdate!==''){
			$search_key='/'.$startdate.'/'.$enddate.'/';
			$pageConfig = doPagination($this->Adminna_log_activity_model->totallogactivity($startdate,$enddate), $limit, $segment,$startdate,$enddate, site_url("/activity_logsna/listview"));
			//$pageConfig=$pageConfig;//.$search_key;
			}else{
			$yesterday=strtotime("yesterday");
			 $yesterday =date("Y-m-d", $yesterday); 
			 $timena=date(' H:i:s');
			 $startdate=$yesterday.$timena;
			 $enddate=date('Y-m-d H:i:s');
			$pageConfig = doPagination($this->Adminna_log_activity_model->totallogactivity($startdate,$enddate), $limit, $segment,$startdate,$enddate, site_url("/activity_logsna/listview"));
			}
			//Debug($pageConfig);
			//die();
			//his->load->library("pagination");
			$this->pagination->initialize($pageConfig, $pageIndex);
			// Get data from my_model  
			if($startdate!==''){
			#########################################
				$lang=$this->lang->line('lang'); 
				$langs=$this->lang->line('langs'); 
				    $date_parts_date_start=explode(' ',$startdate);
                    $day_start=@$date_parts_date_start[0];  
                    $timestart=@$date_parts_date_start[1];
                    $time_start=str_replace(':','-',$timestart);
					$startdate1=$day_start.'-'.$time_start;
				#########################################
					$date_parts_date_start2=explode(' ',$enddate);
                    $day_start2=@$date_parts_date_start2[0];  
                    $timestart2=@$date_parts_date_start2[1];
                    $time_start2=str_replace(':','-',$timestart2);
					$enddate1=$day_start2.'-'.$time_start2;
				#########################################
				$logs_list=$this->Adminna_log_activity_model->view_log($pageIndex, $limit,$startdate,$enddate);
			}else{
				$logs_list = $this->Adminna_log_activity_model->view_log($pageIndex, $limit);
			}
			//$logs_list = $this->Adminna_log_activity_model->view_log($limit,$start * $limit);
			 #Debug($logs_list); die();
			 $links=$this->pagination->create_links();
			 //$links=$this->pagination->create_links($limit, $start);
			 //Debug($links);
			 //die();
			$data = array(
						"startdate" => $startdate,
						"enddate" => $enddate,
						//"ListSelect" => $ListSelect,
						"logs_list" => $logs_list,
						"webtitle" => $webtitle,
						"pagination" => $links,
						"content_view" => 'tmon/activity_logsna',
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
			 //Debug($data); 
	}
}

/*

	######## Cach Toools Start ################################################
		$lang=$this->lang->line('lang'); 
		//	$langs=$this->lang->line('langs'); 
		$cachekey='key-district2-'.'page-'.$pageIndex.'-limit-'.$limit;
		$cachetime=60*60*1;
		##Cach Toools Start######
		//cachefile 
		$input=@$this->input->post(); 
		if($input==null){ $input=@$this->input->get();}
		$deletekey=@$input['deletekey'];
		if($deletekey==''){$deletekey=null;}else{$deletekey=1;}
		$this->load->model('Cachtool_model');
		$sql=null;
		$cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype=2,$deletekey);
		$cachechklist=$cachechk['list'];
		// echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechk);echo'</pre>'; Die();
		if($cachechklist==null){ // NOT IN CACHE
		// เอา FUNCTION ที่ทำงาน SQL มาใส่ตรงนี้ //
		$this->load->model('test_model');
				$rs=$this->test_model->view_log($pageIndex, $limit);
				//  ############################# //
				$sql=null;
				$datalist=$this->Cachtool_model->cachedbsetkey($sql,$rs,$cachekey,$cachetime,$cachetype,$deletekey);
				// เอา FUNCTION ที่ทำงาน SQL มาใส่ตรงนี้ //
				$datalist = $this->test_model->view_log($pageIndex, $limit);
			}else{   // IN CACHE
				$datalist=$cachechklist;
				}
	######## Cach Toools END ################################################

*/

/*
        //###################Log activity
						 $session_id_admin=$this->session->userdata('admin_id');
						 $ref_id=$this->session->userdata('admin_type');
						 $ipaddress=$_SERVER['REMOTE_ADDR'];
						 $ref_type=1;
						 $ref_title="LOGOUT..  ".'[SYSTEM]'."";
						 $action=2;
						 $create_date=date('Y-m-d H:i:s');
						 $status=1;
						$log_activity = array(
										"admin_id" => $session_id_admin,
										"ref_id" => $ref_id,
										"from_ip" => '127.0.0.1',//$ipaddress,
										"ref_type" => $ref_type,
										"ref_title" => $ref_title,
										"action" => $action,
									   "create_date" => $create_date,
									   "status" => $status,
									   "lang" => $this->lang->line('lang')
								);			
						$this->Admin_log_activity_model->store($log_activity);
		//###################Log activity

*/