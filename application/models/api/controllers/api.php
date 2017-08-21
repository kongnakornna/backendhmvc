<?php
//(defined('BASEPATH')) OR exit('No direct script access allowed');
class Api extends REST_Controller {
    public function __construct() {
        parent::__construct();
        //if (ob_get_length() > 0) {       
        ob_clean();
        //}
        //ini_set('display_errors', '1');
        //error_reporting(E_ALL);
    }
	public function index_get(){
		// api?format=xml
		redirect('api/center/', 'location'); Die();
		echo 'API';Die();
       ob_clean();
       $this->load->helper('url');
       $api_url=base_url('api');
	   $json['header']=array('title' => 'Trueplookpanya web service restful api Dev use PHP/MySQL',
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
       $json['data']=array(
 //////////////////// getrelate Module
	   'Module getrelate learning' =>array('module' => 'getrelate','function' => 'learning',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล learning',
					'url' => $api_url.'/getrelate/learning',),
	   'Module getrelate exam' =>array('module' => 'getrelate','function' => 'exam',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล exam',
					'url' => $api_url.'/getrelate/exam',),
	   'Module getrelate mul_content' =>array('Module' => 'getrelate','function' => 'learningdetail',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล mul_conten',
					'url' => $api_url.'/getrelate/learningdetail?mul_content_id=500',),
	   'Module getrelate knowledgecontent' =>array('module' => 'getrelate','function' => 'knowledgecontent',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล knowledgecontent',
					'url' => $api_url.'/getrelate/knowledgecontent',),
	   'Module getrelate cms' =>array('module' => 'getrelate','function' => 'cms',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล cms แบบไม่ระบุหมวดหมู่',
					'url' => $api_url.'/getrelate/cms',),	
       'Module getrelate cms->catid' =>array('module' => 'getrelate','function' => 'cms',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล cms  ระบุหมวดหมู่ ส่งค่า catid ',
					'url' => $api_url.'/getrelate/cms?catid=136',),	
	   'Module getrelate cms->content_id&catid' =>array('module' => 'getrelate','function' => 'cms',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล cms ระบุหมวดหมู่และบทความ ส่งค่า content_id และ catid',
					'url' => $api_url.'/getrelate/cms?content_id=50009&catid=21',),	
	   'Module getrelate admissionnews' =>array('module' => 'getrelate','function' => 'admissionnews',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล admissionnews all',
					'url' => $api_url.'/getrelate/admissionnews',),	
	   'Module getrelate tvprogram' =>array('module' => 'getrelate','function' => 'tvprogram',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล getRelate TV Program all',
					'url' => $api_url.'/getrelate/tvprogram',),	
       'Module getrelate cmsblog' =>array('module' => 'getrelate','function' => 'cmsblog',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล cmsblog',
					'url' => $api_url.'/getrelate/cmsblog?format=jsonp',),
	   'Module getrelate cmsblogdetail->content_id' =>array('module' => 'getrelate','function' => 'cmsblogdetail(content_id)',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'description' =>'ใช้ แสดงข้อมูล getRelate cmsblogdetail content_id',
					'url' => $api_url.'/getrelatev2/cmsblogdetail?content_id=50013',),		 
	   'Module getrelate cmsblogdetailrelate->content_id' =>array(
					'module' => 'getrelate',
					'function' => 'cmsblogdetailrelate(content_id)',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'description' =>'ใช้ แสดงข้อมูล getRelate cmsblogdetailrelate content_id',
					'url' => $api_url.'/getrelatev2/cmsblogdetailrelate?content_id=50001',),		
	   'Module getrelate cmsblogcategory' =>array(
					'module' => 'getrelate',
					'function' => 'cmsblogcategory',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'description' =>'ใช้ แสดงข้อมูล getRelate cmsblogdetailrelate content_id',
					'url' => $api_url.'/getrelatev2/cmsblogcategory',),	
  
	   'Module getrelate cmsblogcategorycode' =>array(
					'module' => 'getrelate',
					'function' => 'cmsblogcategorycode',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'description' =>'ใช้ แสดงข้อมูล getRelate cmsblogdetailrelate cmsblog_id & category_id ',
					'url' => $api_url.'/getrelatev2/cmsblogcategorycode?cmsblog_id=1&category_id=0',),	
	   'Module getrelate cmsblogcountcontent' =>array(
					'module' => 'getrelate',
					'function' => 'cmsblogcountcontent',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล getRelate cmsblogcountcontent ',
					'url' => $api_url.'/getrelate/cmsblogcountcontent',),	
	   'Module getrelate cmsbloghistory' =>array(
					'module' => 'getrelate',
					'function' => 'cmsbloghistory',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'description' =>'ใช้ แสดงข้อมูล getRelate cmsbloghistory ',
					'url' => $api_url.'/getrelatev2/cmsbloghistory',),	
	   'Module getrelate cmsblogfuture' =>array(
					'module' => 'getrelate',
					'function' => 'cmsblogfuture',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล getRelate cmsblogfuture ',
					'url' => $api_url.'/getrelate/cmsblogfuture',),	
	   'Module getrelate bloggersummary' =>array(
					'module' => 'getrelate',
					'function' => 'bloggersummary',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล getRelate bloggersummary  sumType =daily,zone_id=1',
					'url' => $api_url.'/getrelate/bloggersummarybloggersummary',),	
 //////////////////// knowledgebase Module
 	   'Module knowledgebase' =>array(
					'module' => 'knowledgebase',
					'function' => 'geteduimg',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '1.0',
					'description' =>'ใช้ แสดงข้อมูล getRelate knowledgebase/eteduimg ',
					'url' => $api_url.'/knowledgebase/geteduimg',),	
 ////////////////////  Module blog
 	   'Module Blog' =>array(
					'module' => 'blog',
					'function' => 'index',
					'type' =>'GET',
					'type info' =>'HTTP GET',
					'Version ' => '2.0',
					'description' =>'ใช้ แสดงข้อมูล blog/index_get ',
					'url' => $api_url.'/blog',),	
	   );

       #echo '<pre> $json=>'; print_r($json); echo '</pre>'; Die();
       $this->response($json);     	
	} 
    private function _set_response(){
        return array('response' => array(
                'status' => TRUE,
                "massage" => 'success',
                'code' => 200
        ));
    }
    private function _set_not_found(){
        return array('response' => array(
                'status' => FALSE,
                "massage" => 'Data not found',
                'code' => 404
	            ),'data' => []);
    }

}
