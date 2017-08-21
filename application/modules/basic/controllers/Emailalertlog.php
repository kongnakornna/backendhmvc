<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Emailalertlog extends CI_Controller {

    public function __construct()    {
        parent::__construct();
        // load helper
		$this->load->helper('url');
        $this->load->model('Emailalertlog_model');
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
		$breadcrumb[] = $language['emailalertlog'];
		$webtitle = $language['emailalertlog'].' - '.$language['titleweb'];
		//$searchterm = $this->input->get_post('searchterm',TRUE);
		//Debug($ListSelect);
		//die();	
		
		//$segment=$this->uri->segment(3);
		//$segment2=$this->uri->segment(4);
		//$segment=$this->session->userdata($segment);
		// Pagination  
		$total_rows= $this->Emailalertlog_model->totalemailalertlog($startdate,$enddate);
		if($startdate=='' && $enddate=='' ){$limit = 100;}else{
			$limit = $total_rows;
			if($limit>5000){$limit=5000;}
			}
		$segment = 3;
		$pageSize=$limit;
		$start=1;
	
	
		$this->load->helper("pagination"); //Pagination helper  
		
		if($startdate!==''){
		$search_key='/'.$startdate.'/'.$enddate.'/';
		//$pageConfig = doPagination($this->Emailalertlog_model->totalemailalertlog(), $limit, $segment,$startdate,$enddate, site_url("/emailalertlog/listview").$search_key);
		$pageConfig = doPagination($this->Emailalertlog_model->totalemailalertlog(), $limit, $segment,$startdate,$enddate, site_url("/emailalertlog/listview"));
		//$pageConfig=$pageConfig;//.$search_key;
		}else{
		$pageConfig = doPagination($this->Emailalertlog_model->totalemailalertlog(), $limit, $segment, site_url("/emailalertlog/listview/"));
		}
		//Debug($pageConfig);
		//die();
		//his->load->library("pagination");
		$this->pagination->initialize($pageConfig, $pageIndex);
	
		// Get data from my_model  
		if($startdate!==''){
		$web_menu = $this->Emailalertlog_model->getemailalertlog($pageIndex, $limit,$startdate,$enddate);
		}else{
		$web_menu = $this->Emailalertlog_model->getemailalertlog($pageIndex, $limit);
		}
		
		//$web_menu = $this->Emailalertlog_model->getemailalertlog($limit,$start * $limit);
		  //Debug($web_menu);
		  //die();
		 $links=$this->pagination->create_links();
		 //$links=$this->pagination->create_links($limit, $start);
		 //Debug($links);
		 //die();
 
		 
		 
		$data = array(
					"startdate" => $startdate,
					"enddate" => $enddate,
					"ListSelect" => $ListSelect,
					"web_menu" => $web_menu,
					"webtitle" => $webtitle,
					"pagination" => $links,
					"content_view" => 'tmon/emailsalertlog',
					"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);
		 //Debug($data);
	}

}