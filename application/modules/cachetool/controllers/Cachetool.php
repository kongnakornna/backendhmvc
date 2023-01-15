<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cachetool extends MX_Controller {
 	public function __construct(){
 		parent::__construct();
 		 $this->load->library('session');
 		 $this->config->item('Template');
  		$this->load->model('Cachetool_model');
  		$this->load->model('admin/Admin_team_model','Admin_team_model');
  		$this->load->library("AdminFactory");
  		$this->load->library("MenuFactory");
  		$this->load->library('session');
  		if(!$this->session->userdata('is_logged_in')){
              redirect(base_url());
    }
 	}
  public function testkey(){
   $this->load->library('encrypt');
  
    $data1='คงนคร จันทะคุณ '; 
    $encrypt=$this->encrypt->encode($data1); 
    $decode=$this->encrypt->decode($encrypt); 
    echo '<pre> data1=>'; print_r($data1); echo '</pre>'; 
    echo '<pre> encry=>'; print_r($encrypt); echo '</pre>'; 
    echo '<pre> decode=>'; print_r($decode); echo '</pre>'; 
    
    $data='คงนคร จันทะคุณ Windows NT DESKTOP-C8EP93S '; 
    $access_key=$this->config->item('access_key');
    $api_key=$this->config->item('api_key');
    $access_token_www=$this->config->item('access_token_www');
    $service_key=$this->config->item('service_key');
    $data_key=$this->config->item('data_key');
    $cachedata_key=$this->config->item('cachedata_key');
    $key=$access_token_www;
    $encodedatakey=$this->Encrypt_model->encodekey($data,$key);
    $decodedatakey=$this->Encrypt_model->decodekey($encodedatakey,$key);
   
    echo '<pre> data=>'; print_r($data); echo '</pre>'; 
    echo '<pre>  encodedatakey=>'; print_r($encodedatakey); echo '</pre>'; 
    echo '<pre>  decodedatakey=>'; print_r($decodedatakey); echo '</pre>';die();
   
  } 
  public function index(){
  $language = $this->lang->language;
 	//$breadcrumb[] = $language['dashboard'];
  $breadcrumb[] = '<a href="'.base_url('cachetool/sessionpath').'">'.'Sessionpath'.'</a>';
  $breadcrumb[] = '<a href="'.base_url('cachetool/database').'">'.$language['cache_db'].'</a>';
	 $breadcrumb[] = '<a href="'.base_url('cachetool/file').'">'.$language['cache_file'].'</a>';
 
	$admin_id = $this->session->userdata('admin_id');
	$admin_type = $this->session->userdata('admin_type');
	$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
	//$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
	$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
	$loadfile = "admintype".$admin_type.".json";
	$admin_menu = LoadJSON($loadfile);
    $key='testpage'; 
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs');
    // cache ไว้ 5 นาที
    $time_cach_set_min=$this->config->item('time_cach_set_min');
    $time_cach_set=$this->config->item('time_cach_set');
    $time_cach_level0=$this->config->item('time_cach_level0');
    #echo '<pre>$time_cach_set=>'; print_r($time_cach_set); echo '</pre>'; Die();
	   $timecache=$time_cach_set;
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs'); 
    $segment1=$this->uri->segment(1);
    $segment2=$this->uri->segment(2);
    $segment3=$this->uri->segment(3);
    $segment4=$this->uri->segment(4);
    $segment5=$this->uri->segment(5);
    $segment6=$this->uri->segment(6);
    $segment7=$this->uri->segment(7);
    $segment8=$this->uri->segment(8);
    $segment9=$this->uri->segment(9);
    $segment10=$this->uri->segment(10);
	
    $cachdata = array("key" => 'cachetool_index',
                      "breadcrumb" => $breadcrumb,
                      "lang" => $lang,
                      "langs" => $langs,
                      "title" => 'cachtool',
					                 "timecache" => $timecache,
                      "Data"=> 'cachtool',
               					  "ListSelect" => $ListSelect,
               					  "admin_menu" => $admin_menu,
               					  "content_view" => 'cachetool_index',
                      	); 
     $time_cach_set_min=$this->config->item('time_cach_set_min'); // Time min
     #$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
     #$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
     #$this->output->set_header("Pragma: no-cache"); 
     //$this->output->cache($time_cach_set_min); 
	 //echo '<pre>cachdata=>'; print_r($cachdata); echo '</pre>'; die();
	 $this->load->view('template/template',$cachdata);
	
  } 
  public function file(){
  $language = $this->lang->language;
 	//$breadcrumb[] = $language['dashboard'];
  $breadcrumb[] = '<a href="'.base_url('cachetool/sessionpath').'">'.'Sessionpath'.'</a>';
  $breadcrumb[] = '<a href="'.base_url('cachetool/database').'">'.$language['cache_db'].'</a>';
	 $breadcrumb[] = $language['cache_file'];
 
	$admin_id = $this->session->userdata('admin_id');
	$admin_type = $this->session->userdata('admin_type');
	$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
	//$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
	$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
	$loadfile = "admintype".$admin_type.".json";
	$admin_menu = LoadJSON($loadfile);
    $key='testpage'; 
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs');
    // cache ไว้ 5 นาที
    $time_cach_set_min=$this->config->item('time_cach_set_min');
    $time_cach_set=$this->config->item('time_cach_set');
    $time_cach_level0=$this->config->item('time_cach_level0');
    #echo '<pre>$time_cach_set=>'; print_r($time_cach_set); echo '</pre>'; Die();
	   $timecache=$time_cach_set;
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs'); 
    $segment1=$this->uri->segment(1);
    $segment2=$this->uri->segment(2);
    $segment3=$this->uri->segment(3);
    $segment4=$this->uri->segment(4);
    $segment5=$this->uri->segment(5);
    $segment6=$this->uri->segment(6);
    $segment7=$this->uri->segment(7);
    $segment8=$this->uri->segment(8);
    $segment9=$this->uri->segment(9);
    $segment10=$this->uri->segment(10);
	
    $cachdata = array("key" => 'cachetool_index',
                      "breadcrumb" => $breadcrumb,
                      "lang" => $lang,
                      "langs" => $langs,
                      "title" => 'cachtool',
					                 "timecache" => $timecache,
                      "Data"=> 'cachtool',
               					  "ListSelect" => $ListSelect,
               					  "admin_menu" => $admin_menu,
               					  "content_view" => 'cachetool_file',
                      	); 
     $time_cach_set_min=$this->config->item('time_cach_set_min'); // Time min
     #$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
     #$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
     #$this->output->set_header("Pragma: no-cache"); 
     //$this->output->cache($time_cach_set_min); 
	 //echo '<pre>cachdata=>'; print_r($cachdata); echo '</pre>'; die();
	 $this->load->view('template/template',$cachdata);
	
  } 
  public function sessionpath(){
  $language = $this->lang->language;
 	//$breadcrumb[] = $language['dashboard'];
  $breadcrumb[] = 'sessionpath';
  $breadcrumb[] = '<a href="'.base_url('cachetool/database').'">'.$language['cache_db'].'</a>';
	 $breadcrumb[] = '<a href="'.base_url('cachetool/file').'">'.$language['cache_file'].'</a>';
 
	$admin_id = $this->session->userdata('admin_id');
	$admin_type = $this->session->userdata('admin_type');
	$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
	//$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
	$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
	$loadfile = "admintype".$admin_type.".json";
	$admin_menu = LoadJSON($loadfile);
    $key='testpage'; 
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs');
    // cache ไว้ 5 นาที
    $time_cach_set_min=$this->config->item('time_cach_set_min');
    $time_cach_set=$this->config->item('time_cach_set');
    $time_cach_level0=$this->config->item('time_cach_level0');
    #echo '<pre>$time_cach_set=>'; print_r($time_cach_set); echo '</pre>'; Die();
	   $timecache=$time_cach_set;
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs'); 
    $segment1=$this->uri->segment(1);
    $segment2=$this->uri->segment(2);
    $segment3=$this->uri->segment(3);
    $segment4=$this->uri->segment(4);
    $segment5=$this->uri->segment(5);
    $segment6=$this->uri->segment(6);
    $segment7=$this->uri->segment(7);
    $segment8=$this->uri->segment(8);
    $segment9=$this->uri->segment(9);
    $segment10=$this->uri->segment(10);
	
    $cachdata = array("key" => 'cachetool_index',
                      "breadcrumb" => $breadcrumb,
                      "lang" => $lang,
                      "langs" => $langs,
                      "title" => 'cachtool',
					                 "timecache" => $timecache,
                      "Data"=> 'cachtool',
               					  "ListSelect" => $ListSelect,
               					  "admin_menu" => $admin_menu,
               					  "content_view" => 'cachetool_session',
                      	); 
     $time_cach_set_min=$this->config->item('time_cach_set_min'); // Time min
     #$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
     #$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
     #$this->output->set_header("Pragma: no-cache"); 
     //$this->output->cache($time_cach_set_min); 
	 //echo '<pre>cachdata=>'; print_r($cachdata); echo '</pre>'; die();
	 $this->load->view('template/template',$cachdata);
	
  } 
  public function database(){
  $language = $this->lang->language;
 	//$breadcrumb[] = $language['dashboard'];
  //$breadcrumb[] = '<a href="'.base_url('cachetool').'">'.$language['cache_manage'].'</a>';
  $breadcrumb[] = '<a href="'.base_url('cachetool/sessionpath').'">'.'Sessionpath'.'</a>';
  $breadcrumb[] = $language['cache_db'];
	 $breadcrumb[] = '<a href="'.base_url('cachetool/file').'">'.$language['cache_file'].'</a>';
 
	$admin_id = $this->session->userdata('admin_id');
	$admin_type = $this->session->userdata('admin_type');
	$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
	//$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
	$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
	$loadfile = "admintype".$admin_type.".json";
	$admin_menu = LoadJSON($loadfile);
    $key='testpage'; 
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs');
    // cache ไว้ 5 นาที
    $time_cach_set_min=$this->config->item('time_cach_set_min');
    $time_cach_set=$this->config->item('time_cach_set');
    $time_cach_level0=$this->config->item('time_cach_level0');
    #echo '<pre>$time_cach_set=>'; print_r($time_cach_set); echo '</pre>'; Die();
	   $timecache=$time_cach_set;
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs'); 
    $segment1=$this->uri->segment(1);
    $segment2=$this->uri->segment(2);
    $segment3=$this->uri->segment(3);
    $segment4=$this->uri->segment(4);
    $segment5=$this->uri->segment(5);
    $segment6=$this->uri->segment(6);
    $segment7=$this->uri->segment(7);
    $segment8=$this->uri->segment(8);
    $segment9=$this->uri->segment(9);
    $segment10=$this->uri->segment(10);
	
    $cachdata = array("key" => 'cachetool_index',
                      "breadcrumb" => $breadcrumb,
                      "lang" => $lang,
                      "langs" => $langs,
                      "title" => 'cachtool',
					                 "timecache" => $timecache,
                      "Data"=> 'cachtool',
               					  "ListSelect" => $ListSelect,
               					  "admin_menu" => $admin_menu,
               					  "content_view" => 'cachetool_database',
                      	); 
     $time_cach_set_min=$this->config->item('time_cach_set_min'); // Time min
     #$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
     #$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
     #$this->output->set_header("Pragma: no-cache"); 
     //$this->output->cache($time_cach_set_min); 
	 //echo '<pre>cachdata=>'; print_r($cachdata); echo '</pre>'; die();
	 $this->load->view('template/template',$cachdata);
	
  } 
  public function database2(){
  $language = $this->lang->language;
 	//$breadcrumb[] = $language['dashboard'];
  //$breadcrumb[] = '<a href="'.base_url('cachetool').'">'.$language['cache_manage'].'</a>';
  $breadcrumb[] = '<a href="'.base_url('cachetool/sessionpath').'">'.'Sessionpath'.'</a>';
  $breadcrumb[] = $language['cache_db'];
	 $breadcrumb[] = '<a href="'.base_url('cachetool/file').'">'.$language['cache_file'].'</a>';
 
	$admin_id = $this->session->userdata('admin_id');
	$admin_type = $this->session->userdata('admin_type');
	$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
	//$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
	$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
	$loadfile = "admintype".$admin_type.".json";
	$admin_menu = LoadJSON($loadfile);
    $key='testpage'; 
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs');
    // cache ไว้ 5 นาที
    $time_cach_set_min=$this->config->item('time_cach_set_min');
    $time_cach_set=$this->config->item('time_cach_set');
    $time_cach_level0=$this->config->item('time_cach_level0');
    #echo '<pre>$time_cach_set=>'; print_r($time_cach_set); echo '</pre>'; Die();
	   $timecache=$time_cach_set;
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs'); 
    $segment1=$this->uri->segment(1);
    $segment2=$this->uri->segment(2);
    $segment3=$this->uri->segment(3);
    $segment4=$this->uri->segment(4);
    $segment5=$this->uri->segment(5);
    $segment6=$this->uri->segment(6);
    $segment7=$this->uri->segment(7);
    $segment8=$this->uri->segment(8);
    $segment9=$this->uri->segment(9);
    $segment10=$this->uri->segment(10);
	
    $cachdata = array("key" => 'cachetool_index',
                      "breadcrumb" => $breadcrumb,
                      "lang" => $lang,
                      "langs" => $langs,
                      "title" => 'cachtool',
					                 "timecache" => $timecache,
                      "Data"=> 'cachtool',
               					  "ListSelect" => $ListSelect,
               					  "admin_menu" => $admin_menu,
               					  "content_view" => 'cachetool_database2',
                      	); 
     $time_cach_set_min=$this->config->item('time_cach_set_min'); // Time min
     #$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
     #$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
     #$this->output->set_header("Pragma: no-cache"); 
     //$this->output->cache($time_cach_set_min); 
	 //echo '<pre>cachdata=>'; print_r($cachdata); echo '</pre>'; die();
	 $this->load->view('template/template',$cachdata);
	
  } 
  public function testdriver(){
    $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    // cache ไว้ 5 นาที
    $time_cach_set_min=$this->config->item('time_cach_set_min');
    $time_cach_set=$this->config->item('time_cach_set');
    $time_cach_level0=$this->config->item('time_cach_level0');
    #echo '<pre>$time_cach_set=>'; print_r($time_cach_set); echo '</pre>'; Die();
		  $timecache=$time_cach_set;
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs'); 
    $segment1=$this->uri->segment(1);
    $segment2=$this->uri->segment(2);
    $segment3=$this->uri->segment(3);
    $segment4=$this->uri->segment(4);
    $segment5=$this->uri->segment(5);
    $segment6=$this->uri->segment(6);
    $segment7=$this->uri->segment(7);
    $segment8=$this->uri->segment(8);
    $segment9=$this->uri->segment(9);
    $segment10=$this->uri->segment(10);
     #$post=@$this->input->post();
     $get=@$this->input->get();
     //echo '<pre>$segment3>'; print_r($segment3); echo '</pre>'; Die();
     $id=@$segment3;
     if($id==Null){
        $id=@$get['id'];
     }
     if($id==Null){
       $id='1';
     }
     
    $this->load->model('Demo/Test_model','Test_model');
    $datars = $this->Test_model->get_member_by_id($id);
    $SQLKEY='get_member_by_id'.$id;
    $key='Cachtool_testdriver_'.$lang.'_'.$SQLKEY;
    if (!$cachdata= $this->cache->get($key)){
        	$cachdata = array("key" => $key,
                           "Title"=> 'na',
                           "page"=> 'index',
                           "page2"=> '9999',
                           "page3"=> '55',
                           "lang"=> $lang,
                           "langs"=> $langs,
                           "rs"=>$datars,
                           "timecache"=> $timecache,
                           "content_view" => 'cachetool',
                      	   ); 
         // Save into the cache for 5 minutes
         $this->cache->file->save($key,$cachdata,$timecache);
    }
    //echo '<pre>$cachdata=>'; print_r($cachdata); echo '</pre>'; Die();
    $this->load->view('template/template',$cachdata);
   
  }           
  public function testpage(){
	$language = $this->lang->language;
	$breadcrumb[] = $language['dashboard'];
	$admin_id = $this->session->userdata('admin_id');
	$admin_type = $this->session->userdata('admin_type');
	$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
	//$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
	$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
	$loadfile = "admintype".$admin_type.".json";
	$admin_menu = LoadJSON($loadfile);
    $key='testpage'; 
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs');
    // cache ไว้ 5 นาที
    $time_cach_set_min=$this->config->item('time_cach_set_min');
    $time_cach_set=$this->config->item('time_cach_set');
    $time_cach_level0=$this->config->item('time_cach_level0');
    #echo '<pre>$time_cach_set=>'; print_r($time_cach_set); echo '</pre>'; Die();
	$timecache=$time_cach_set;
    $lang=$this->lang->line('lang'); 
    $langs=$this->lang->line('langs'); 
    $segment1=$this->uri->segment(1);
    $segment2=$this->uri->segment(2);
    $segment3=$this->uri->segment(3);
    $segment4=$this->uri->segment(4);
    $segment5=$this->uri->segment(5);
    $segment6=$this->uri->segment(6);
    $segment7=$this->uri->segment(7);
    $segment8=$this->uri->segment(8);
    $segment9=$this->uri->segment(9);
    $segment10=$this->uri->segment(10);
	
    $cachdata = array("key" => 'test',
                      "lang" => $lang,
                      "langs" => $langs,
                      "title" => 'cachtool',
					  "timecache" => $timecache,
                      "Data"=> 'cachtool',
					  "ListSelect" => $ListSelect,
					  "admin_menu" => $admin_menu,
					  "content_view" => 'cachetool',
                      	); 
     $time_cach_set_min=$this->config->item('time_cach_set_min'); // Time min
     #$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
     #$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
     #$this->output->set_header("Pragma: no-cache"); 
     $this->output->cache($time_cach_set_min); 
	 //echo '<pre>cachdata=>'; print_r($cachdata); echo '</pre>'; die();
	 $this->load->view('template/template',$cachdata);
	
  }
  public function delete_cache_file(){
      //http://localhost/cihmvcdev3/cachetool/delete_cache_file?uri=cachetool/testdriver&file=Cachtool_testdriver_en_get_member_by_id2
      $post=@$this->input->post();
      $get=@$this->input->get();
      #$uri_string=@$post['uri'];
      #$filecachename=@$post['file'];
      $uri_string=@$get['uri']; 
      $filecachename=@$get['file'];
      $deletefilecache=$this->Cachetool_model->delete_cache_filename($uri_string,$filecachename);
      #echo '<pre>$filecachename=>'; print_r($filecachename); echo '</pre>'; 
      #echo '<pre>$uri_string=>'; print_r($uri_string); echo '</pre>'; 
      #echo '<pre>$deletefilecache=>'; print_r($deletefilecache); echo '</pre>'; 
      $status=$deletefilecache['status'];
       if($status==0){
        $title='Can not delete cache!';
        $msgst='Can not delete cache '.$filecachename;
       }else{
        $title='Delete cache!';
        $msgst='Delete cache '.$filecachename;
       }
      $urldirec=base_url('cachetool');
?>
				<!-- This is sweetalert2 -->
				<script type="text/javascript" src="<?php echo base_url('assets/sweetalert2/dist/js/jquery-latest.js');?>"></script>
				<script src="<?php echo base_url('assets/sweetalert2/dist/sweetalert-dev.js');?>"></script>
				<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert2/dist/sweetalert.css');?>">
<?php
			echo'<script>
							$( document ).ready(function() {
								//////////////////
								swal({
								  title: " '.$title.'",
								  text: "'.$msgst.'",
								  timer: 1000,
								  showConfirmButton: false
								}, function(){
											setTimeout(function() {
												  // Javascript URL redirection
												   window.location.replace("'.$urldirec.'");
											}, 200);
  });
								//////////////////
							});
			 </script>';
	Die(); 
  }
  public function delete_cache_sessionpath(){
      //http://localhost/cihmvcdev3/cachetool/delete_cache_file?uri=cachetool/testdriver&file=Cachtool_testdriver_en_get_member_by_id2
      $post=@$this->input->post();
      $get=@$this->input->get();
      #$uri_string=@$post['uri'];
      #$filecachename=@$post['file'];
      $uri_string=@$get['uri']; 
      $filecachename=@$get['file'];
      $deletefilecache=$this->Cachetool_model->delete_cache_filesession($uri_string,$filecachename);
      #echo '<pre>$filecachename=>'; print_r($filecachename); echo '</pre>'; 
      #echo '<pre>$uri_string=>'; print_r($uri_string); echo '</pre>'; 
      #echo '<pre>$deletefilecache=>'; print_r($deletefilecache); echo '</pre>'; 
      $status=$deletefilecache['status'];
       if($status==0){
        $title='Can not delete session!';
        $msgst='Can not delete session '.$filecachename;
       }else{
        $title='Delete session!';
        $msgst='Delete session '.$filecachename;
       }
      $urldirec=base_url('cachetool/sessionpath');
?>
				<!-- This is sweetalert2 -->
				<script type="text/javascript" src="<?php echo base_url('assets/sweetalert2/dist/js/jquery-latest.js');?>"></script>
				<script src="<?php echo base_url('assets/sweetalert2/dist/sweetalert-dev.js');?>"></script>
				<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert2/dist/sweetalert.css');?>">
<?php
			echo'<script>
							$( document ).ready(function() {
								//////////////////
								swal({
								  title: " '.$title.'",
								  text: "'.$msgst.'",
								  timer: 1000,
								  showConfirmButton: false
								}, function(){
											setTimeout(function() {
												  // Javascript URL redirection
												   window.location.replace("'.$urldirec.'");
											}, 200);
  });
								//////////////////
							});
			 </script>';
	Die(); 
  }
  public function delete_cache_file_db(){
      $post=@$this->input->post();
      $get=@$this->input->get();
      #$uri_string=@$post['uri'];
      #$filecachename=@$post['file'];
      $uri_string=@$get['uri']; 
      $filecachename=@$get['file'];
	  $dirfile=str_replace(' ','+',$filecachename);
	  $dlete_cache_db_dir=$this->Cachetool_model->dlete_cache_db_dir($dirfile);
      //echo '<pre>$dlete_cache_db_dir=>'; print_r($dlete_cache_db_dir); echo '</pre>'; Die();
	  $status=$dlete_cache_db_dir['status'];
	  $masange=$dlete_cache_db_dir['masange'];
	  $dir=$dlete_cache_db_dir['dir'];
	  $urldirec=base_url('cachetool/database');
       if($status==0){
        $title='Can not delete cache!';
        $msgst='Can not delete cache '.$dirfile;
       }else{
        $title='Delete cache!';
        $msgst='Delete cache '.$dirfile;
       }
?>
				<!-- This is sweetalert2 -->
				<script type="text/javascript" src="<?php echo base_url('assets/sweetalert2/dist/js/jquery-latest.js');?>"></script>
				<script src="<?php echo base_url('assets/sweetalert2/dist/sweetalert-dev.js');?>"></script>
				<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert2/dist/sweetalert.css');?>">
<?php
			echo'<script>
							$( document ).ready(function() {
								//////////////////
								swal({
								  title: " '.$title.'",
								  text: "'.$msgst.'",
								  timer: 1000,
								  showConfirmButton: false
								}, function(){
											setTimeout(function() {
												  // Javascript URL redirection
												   window.location.replace("'.$urldirec.'");
											}, 200);
  });
								//////////////////
							});
			 </script>';
	Die(); 
  }
  public function delete_cache_file_db2(){
      $post=@$this->input->post();
      $get=@$this->input->get();
      #$uri_string=@$post['uri'];
      #$filecachename=@$post['file'];
      $uri_string=@$get['uri']; 
      $filecachename=@$get['file'];
	  $dirfile=str_replace(' ','+',$filecachename);
	  $dir1=CACHE_PATH_DB.$dirfile.'/';
	  $this->load->helper('path');

	  $non_existent_directory ='./file/dbcache/'.$dirfile.'/';
	  $dir=$non_existent_directory;
	  #echo set_realpath($non_existent_directory, TRUE);       // Shows an error, as the path cannot be resolved
	  #echo set_realpath($non_existent_directory, FALSE);      // Prints '/path/to/nowhere'
	  //echo '<pre>$dir=>'; print_r($dir); echo '</pre>'; 
	  if(!is_dir($dir)){ 
		  echo '<pre>ไม่มี Directory=>'; print_r($dirfile); echo '</pre>';
		}else{
			 echo '<pre>มี Directory=>'; print_r($dirfile); echo '</pre>';  
			$path=$dir;
			$this->load->helper("file"); // load the helper
			delete_files($path, true); // delete all files/folders
			 if(rmdir($path)){
				 echo '<pre> Deleted=>'; print_r($path); echo '</pre>';  
			 }else{ 
				 echo '<pre> Not Deleted=>'; print_r($path); echo '</pre>'; 
			} 
		}
	  
      //$this->db->cache_delete($dirfile);
       echo '<pre>$filecachename=>'; print_r($filecachename); echo '</pre>'; 
       echo '<pre>$uri_string=>'; print_r($uri_string); echo '</pre>'; 
       echo '<pre>$dirfile=>'; print_r($dir); echo '</pre>'; 
       echo '<pre>all=>'; print_r($uri_string,$filecachename); echo '</pre>'; Die();
      if($dirfile==Null){
       $deletefilecache=$this->Cachetool_model->delete_cache_filename($uri_string,$filecachename);
      }else{
       $deletefilecache=$this->Cachetool_model->delete_cache_filename_db($uri_string,$dirfile,$filecachename);
      
      }
       
       $status=$deletefilecache['status'];
       if($status==0){
        $title='Can not delete cache!';
        $msgst='Can not delete cache '.$filecachename;
       }else{
        $title='Delete cache!';
        $msgst='Delete cache '.$filecachename;
       }
       
       #echo '<pre>$status=>'; print_r($status); echo '</pre>'; 
       #echo '<pre>$deletefilecache=>'; print_r($deletefilecache); echo '</pre>'; Die();
      $urldirec=base_url('cachetool/database?dirfile='.$dirfile);
?>
				<!-- This is sweetalert2 -->
				<script type="text/javascript" src="<?php echo base_url('assets/sweetalert2/dist/js/jquery-latest.js');?>"></script>
				<script src="<?php echo base_url('assets/sweetalert2/dist/sweetalert-dev.js');?>"></script>
				<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert2/dist/sweetalert.css');?>">
<?php
			echo'<script>
							$( document ).ready(function() {
								//////////////////
								swal({
								  title: " '.$title.'",
								  text: "'.$msgst.'",
								  timer: 1000,
								  showConfirmButton: false
								}, function(){
											setTimeout(function() {
												  // Javascript URL redirection
												   window.location.replace("'.$urldirec.'");
											}, 200);
  });
								//////////////////
							});
			 </script>';
	Die(); 
  }
  public function delete_cache_uri(){
      //http://localhost/cihmvcdev3/cachtool/delete_cache_uri?uri=cachtool/test
      $post=@$this->input->post();
      $get=@$this->input->get();
      $uri=@$post['uri'];
      $uri=@$get['uri'];
      $uri_string=$uri;
      $clear_all_cache=$this->Cachetool_model->delete_cache_uri($uri);
       echo '<pre>clear_all_cache=>'; print_r($clear_all_cache); echo '</pre>'; 
  }
  public function clear_all_cache(){
	$id=@$this->uri->segment(3);
	$get=@$this -> input->get(); 
    $clear_all_cache=$this->Cachetool_model->clear_all_cache();
    #echo '<pre>clear_all_cache=>'; print_r($clear_all_cache); echo '</pre>'; 
    $urldirec=base_url('cachetool');
?>
				<!-- This is sweetalert2 -->
				<script type="text/javascript" src="<?php echo base_url('assets/sweetalert2/dist/js/jquery-latest.js');?>"></script>
				<script src="<?php echo base_url('assets/sweetalert2/dist/sweetalert-dev.js');?>"></script>
				<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert2/dist/sweetalert.css');?>">
<?php
			echo'<script>
							$( document ).ready(function() {
								//////////////////
								swal({
								  title: " Clear cache all !",
								  text: " ลบข้อมูล cache เรียบร้อยแล้ว.",
								  timer: 1000,
								  showConfirmButton: false
								}, function(){
											setTimeout(function() {
												  // Javascript URL redirection
												   window.location.replace("'.$urldirec.'");
											}, 200);
  });
								//////////////////
							});
			 </script>';
	Die();
  }
  public function clear_all_cache_db(){
	$id=@$this->uri->segment(3);
	$get=@$this -> input->get(); 
    $clear_all_cache=$this->Cachetool_model->clear_all_cache_db();
    #echo '<pre>clear_all_cache=>'; print_r($clear_all_cache); echo '</pre>'; 
    $urldirec=base_url('cachetool/database');
?>
				<!-- This is sweetalert2 -->
				<script type="text/javascript" src="<?php echo base_url('assets/sweetalert2/dist/js/jquery-latest.js');?>"></script>
				<script src="<?php echo base_url('assets/sweetalert2/dist/sweetalert-dev.js');?>"></script>
				<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert2/dist/sweetalert.css');?>">
<?php
			echo'<script>
							$( document ).ready(function() {
								//////////////////
								swal({
								  title: " Clear cache all !",
								  text: " ลบข้อมูล cache เรียบร้อยแล้ว.",
								  timer: 1000,
								  showConfirmButton: false
								}, function(){
											setTimeout(function() {
												  // Javascript URL redirection
												   window.location.replace("'.$urldirec.'");
											}, 200);
  });
								//////////////////
							});
			 </script>';
	Die();
  }
  public function cache_expire_check() {
	$baseurl=base_url();
	$uri_string=$baseurl.'admin/memberlist/';
	$cache_path_md5=md5($uri_string);
	echo '<pre>uri_string=>'; print_r($uri_string); echo '</pre>';
	echo '<pre>cache_path_md5=>'; print_r($cache_path_md5); echo '</pre>';
	echo '<pre>CACHE_DIR=>'; print_r(CACHE_DIR); echo '</pre>';
	echo '<pre>CACHE_DIR=>'; print_r(CACHE_DIR); echo '</pre>';
	echo '<pre>CACHE_DIR_APPPATH=>'; print_r(CACHE_DIR_APPPATH); echo '</pre>';
	echo '<pre>CACHE_PATH_STATIC=>'; print_r(CACHE_PATH_STATIC); echo '</pre>'; 
	echo '<pre>CACHE_PATH_DB=>'; print_r(CACHE_PATH_DB); echo '</pre>'; 
	$lifespan='3';
	$cache_name='dashboard_en';
	$cache_expire=$this->Cachetool_model->is_cache_valid($cache_name,$lifespan);
	echo '<pre>cache_expire=>'; print_r($cache_expire); echo '</pre>'; Die();
   //$cache_expire=$this->load->helper('cache_expire');   
  }
  public function delete_cache($uri_string){
			$CI =& get_instance();
			$path = $CI->config->item('cache_path');
			$cache_path = ($path == '') ? APPPATH.'cache/' : $path;
			$uri =  $CI->config->item('base_url').
				$CI->config->item('index_page').
				$uri_string;
			$cache_path .= md5($uri);
			echo '<pre>cache_path=>'; print_r($cache_path); echo '</pre>'; Die();
			if (file_exists($cache_path)){
				return unlink($cache_path);
			}else{
				return TRUE;
			}
		}
 }
 
 
/**
* 
* 
function my_function()
{
     if ( ! $cache_data = $this->cache->get('cache_key'))
     {
          //here goes your codes...
          $some_object = (object) NULL;
          $some_object->test_property = "test date";
           
          // Save into the cache for 2 minutes
          $this->cache->save('cache_key', $some_object, 120);
          $cache_data = $some_object;
     }
     * 
     * 
    $this->db->cache_on();
 
$result1 = $this->db->query("SELECT * FROM some_table");
 
$this->db->select("id");
$this->db->from("some_table");
$result2 = $this->db->get();
* 
* 
* function my_controller_function()
{
       //your controller codes goes her.....
 
       $this->output->cache(n);
       //n = number of minutes to cache output
 
       //your controller codes goes here....
}
*/