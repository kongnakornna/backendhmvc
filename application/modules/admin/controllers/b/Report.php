<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Report extends CI_Controller {

    public function __construct()    {
        parent::__construct();
        // load helper
		$this->load->helper('url');
        $this->load->model('Report_Model');
        $this->load->library("pagination");
		//$this->load->library(array('pagination','session'));
		//$this->load->library('session');
		$breadcrumb = array();
		//chack login
		if(!$this->session->userdata('is_logged_in')){
			redirect(base_url());
			}
    }
	public function index(){
			$this->listview();
    }
	
	public function listview($pageIndex=1) {
	$startdate = $this->input->get_post('startdate',TRUE);
	$enddate = $this->input->get_post('enddate',TRUE);
	
	
	
	   // $pagination = $this->session->userdata($segment);
		$language = $this->lang->language;
		$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
		$breadcrumb[] = $language['alarmlogreport'];
		$webtitle = $language['alarmlogreport'].' - '.$language['titleweb'];
		//$searchterm = $this->input->get_post('searchterm',TRUE);
		//Debug($ListSelect);
		//die();	
		
		//$segment=$this->uri->segment(3);
		//$segment2=$this->uri->segment(4);
		//$segment=$this->session->userdata($segment);
		// Pagination  
		$total_rows= $this->Report_Model->totalsensorlog($startdate,$enddate);
		//if($startdate=='' && $enddate=='' ){$limit = 100;}else{$limit = $total_rows;}
		if($startdate=='' && $enddate=='' ){
			$limit = 200;
			$total_rows=(int)$total_rows;
		}else{
			$limit = $total_rows;
			if($limit>2000){$limit=2000; $total_rows=2000;}
			}
		$segment = 3;
		$pageSize=$limit;
		$start=1;
	
		$this->load->helper("pagination"); //Pagination helper  
		
		if($startdate!==''){
		$search_key='/'.$startdate.'/'.$enddate.'/';
		//$pageConfig = doPagination($this->Report_Model->totalsensorlog($startdate,$enddate), $limit, $segment,$startdate,$enddate, site_url("/alarmlogreport/listview").$search_key);
		$pageConfig = doPagination($this->Report_Model->totalsensorlog($startdate,$enddate), $limit, $segment,$startdate,$enddate, site_url("/alarmlogreport/listview"));
		//$pageConfig=$pageConfig;//.$search_key;
		}else{
		$pageConfig = doPagination($this->Report_Model->totalsensorlog($startdate,$enddate), $limit, $segment, site_url("/alarmlogreport/listview/"));
		}
		//Debug($pageConfig);
		//die();
		//his->load->library("pagination");
		$this->pagination->initialize($pageConfig, $pageIndex);
	
		// Get data from my_model  
		if($startdate!==''){
		$web_menu = $this->Report_Model->getalarmlogreport($pageIndex, $limit,$startdate,$enddate);
		}else{
		$web_menu = $this->Report_Model->getalarmlogreport($pageIndex, $limit);
		}
		
		//$web_menu = $this->Report_Model->getalarmlogreport($limit,$start * $limit);
		  //Debug($web_menu);
		  //die();
		 $links=$this->pagination->create_links();
		 //$links=$this->pagination->create_links($limit, $start);
		 //Debug($links);
		 //die();
 
		 
		
		$data = array(
					"total_rows" => $total_rows,
					"startdate" => $startdate,
					"enddate" => $enddate,
					"ListSelect" => $ListSelect,
					"web_menu" => $web_menu,
					"webtitle" => $webtitle,
					"pagination" => $links,
					"content_view" => 'tmon/alarmlogreport',
					"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);
		 //Debug($data);
		 
//**************Log activity
$action='1';
########IP#################		
$ipaddress1=$this->ip_address = array_key_exists('HTTP_CLIENT_IP',$_SERVER) ? $_SERVER['HTTP_CLIENT_IP'] : '127.0.0.1';
$ipaddress2=$this->ip_address = array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '0.0.0.0';
$ipaddress3=$this->ip_address = array_key_exists('HTTP_X_FORWARDED',$_SERVER) ? $_SERVER['HTTP_X_FORWARDED'] : '0.0.0.0';
$ipaddress4=$this->ip_address = array_key_exists('HTTP_FORWARDED_FOR',$_SERVER) ? $_SERVER['HTTP_FORWARDED_FOR'] : '0.0.0.0';
$ipaddress5=$this->ip_address = array_key_exists('HTTP_FORWARDED',$_SERVER) ? $_SERVER['HTTP_FORWARDED'] : '0.0.0.0';
$ipaddress6=$this->ip_address = array_key_exists('REMOTE_ADDR',$_SERVER) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
if($ipaddress1!==''){$ipaddress=$ipaddress1;}
elseif($ipaddress2!==''){$ipaddress=$ipaddress2;}
elseif($ipaddress3!==''){$ipaddress=$ipaddress3;}
elseif($ipaddress4!==''){$ipaddress=$ipaddress4;}
elseif($ipaddress5!==''){$ipaddress=$ipaddress5;}
elseif($ipaddress6!==''){$ipaddress=$ipaddress6;}
elseif($ipaddress = '127.0.0.1'||$ipaddress = '::1'){$ipaddress = '127.0.0.1';}
else{$ipaddress='UNKNOWN';}
//"from_ip" => $ipaddress,
		$admin_id=$this->session->userdata('admin_id');
		$log_activity = array(
								"admin_id" => $admin_id,
								"ref_id" => 1,
								"from_ip" => $_SERVER['REMOTE_ADDR'],
								"ref_type" => 1,
								"from_ip" => $ipaddress,
								"ref_title" => "[Seneor Alarmlog Report ".''." ]",
								"action" => $action,
								"create_date" => date('Y-m-d H:i:s'),
								"status" => '1',
								"lang" => $this->lang->line('lang')
		);
		$this->Admin_log_activity_model->store($log_activity);
		//Debug($log_activity);
		// Die();
//**************Log activity
	}

}