<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); 
header('Content-type: text/html; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);


class Blog extends REST_Controller {
  
  function __construct() {

    parent::__construct();

    if(!is_internal()){
      show_404();
    }
    // $this->load->library('pagination');
    // $this->load->library('form_validation');
    // $this->load->library('TPPY_Utils');
    // $this->load->library('tppymemcached');
    $this->load->model('blog/blogmodel');
	$this->load->model('getRelate_model');
    // $this->template->set_theme('trueplookpanya', 'default', 'themes');
    if($this->session->userdata('user_session')){
      $this->user_id=$this->session->userdata('user_session')->user_id;
    }
  }
	public function index_get(){
       ob_clean();
       $this->load->helper('url');
       $api_url=base_url('api');
	   $json['header']=array('title' => 'Blog Modules',
					'status' => 200,
					'Version ' => '2.0',
					'description' => 'List data ',
					'message' => 'REST DONE',
	        		'remarks' => 'HTTP GET',
                    );
 	   $json['data']=array(
					'Function setfollow_get' =>array(
						'type' =>'GET',
						'type info' =>'HTTP GET',
						'Version ' => '2.0',
						'description' =>'ใช้ แสดงข้อมูล setfollow ',
						'url' => $api_url.'/blog/setfollow',),
					'Function blogcontentbyuser_get' =>array(
						'type' =>'GET',
						'type info' =>'HTTP GET',
						'Version ' => '2.0',
						'description' =>'ใช้ แสดงข้อมูล blogcontentbyuser ',
						'url' => $api_url.'/blog/blogcontentbyuser?user_id=510325',),
					
				 
					
		);			
		#echo '<pre> $json=>'; print_r($json); echo '</pre>'; Die();
        $this->response($json);    
	}
	
	public function setfollow_get() {
		if($token=$this->get('token')){
		  $user_id=$this->user->paseToken($token);
		}else{
		  $user_id=$this->secure->get_user_session()->user_id;
		}
		$blogger_id = $this->get('blogger_id');
		if(!$blogger_id){
		  $this->response(array('response'=>array('status'=>false, 'message'=>'Paramter Missing [blogger_id]','code'=>200), 'data'=> $data), 200);
		}else if($user_id){
		  $isFollowresult=$this->blogmodel->setFollow($blogger_id, $user_id);
		  $data['isFollowed']=$isFollowresult ===1 ? true : false;
		  $data['followers']=$this->blogmodel->countFollow($blogger_id);
		  $this->response(array('response'=>array('status'=>false, 'message'=>'success','code'=>200), 'data'=> $data), 200);
		}else{
		  $this->response(array('response'=>array('status'=>false, 'message'=>'plase login first','code'=>200), 'data'=> $data), 200);
		}
  }
  
	public function getContent_get() {
		$id=$this->get('id');
		$q=$this->get('q');
		$sort=$this->get('sort');
		$offset=$this->get('offset');   
		$this->load->model('knowledge/cmsblogmodel');
		$data=$this->cmsblogmodel->getContentDetail($id);
		if($data){
		  $this->response(array('response'=>array('status'=>true, 'message'=>'success','code'=>200), 'data'=> $data), 200);
		}else{
		  $this->response(array('response'=>array('status'=>false, 'message'=>'success','code'=>200), 'data'=> $data), 200);
		}
  }
	public function blogcontentbyuser_get() {
			$user_id=@$this->get('user_id');
			if($user_id==''){$user_id=Null;}
			$this->load->model('blog/blogmodel');
			$data=$this->blogmodel->get_blogger_status_by_blogger($user_id);
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
									'version ' => '1.0 and 2.0',
									'description' => 'List data by user',
									'message' => 'REST DONE',
									'remarks' => 'HTTP GET',
									'code'=>200), 
									'data'=> $data),200);
			}
	  }
  

  
}