<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends MY_Controller {
public function __construct()    {
        parent::__construct();
        $this->load->library('session');
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }
public function index(){
$admin_id = $this->session->userdata('admin_id');
$admin_type = $this->session->userdata('admin_type');
$language = $this->lang->language;                     
$input=@$this->input->post(); 
if($input==null){ $input=@$this->input->get();} 
$deletekey=@$input['deletekey']; //deletekey=1
$cachetype=@$input['cachetype'];
$dev=@$input['dev'];
 #echo '<pre> $getMenu1-> '; print_r($getMenu); echo '</pre>'; die(); 
      if($dev==1){$getMenu=$getMenu['data'];}
				$breadcrumb[] = $language['settings'];
				$setting =  array();
				$setting = GetConfig1(); // form json_helper
			//echo '<pre>  setting-> '; print_r($setting); echo '</pre>'; die(); 
      if($dev==1){$getMenu=$getMenu['data'];}
				$data = array(
						"setting" => $setting,
						"breadcrumb" => $breadcrumb
				);
				$data['content_view'] = 'setting/setting';
				$this->load->view('template/template',$data);
	}
	public function save(){
		if($this->input->server('REQUEST_METHOD') === 'POST'){
				$input_data = $this->input->post();
				//Debug($input_data);
				//die();
				//$configfile = "./json/www/configuration.json";
				//@chmod ($configfile, 0766);
				//$permission = is_writable($configfile);
				//Debug($permission);
				SaveJSON($input_data, 'configuration', true, 'setting/');
				//redirect('setting');
    	//	Alert($msg, base_url('accessmenu/edit/'.$admin_type_id)); die();
$language=$this->lang->language;
$title=$language['savedata'];
$msgst=$language['savecomplate'];
$urldirec=base_url('setting'); 
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
								  title: "'.$title.'",
								  text: "  '.$msgst.'.",
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
				/*if(isset($input_data['channel_id'])){
					$channel_id = $input_data['channel_id'];
					$filter_date = DateDB($input_data['date']);
				}else{
					$keyword = $input_data['keyword'];
				}*/	
		}

	}
}

