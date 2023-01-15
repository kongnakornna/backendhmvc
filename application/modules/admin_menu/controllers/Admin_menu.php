<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
//draggable
class Admin_menu extends CI_Controller {
public function __construct()    {
        parent::__construct();
		 $this->load->model('Admin_menu_model');
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }  
        ob_clean();
    }
public function index(){

			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;
			$breadcrumb[] = $language['admin_menu']; 
		//	$breadcrumb[] = '<a href="'.base_url('admin_menu/draggable').'">'.$language['draggable'].'</a>';
   
			if($this->input->server('REQUEST_METHOD') === 'POST'){
					$search_form = $this->input->post();
					//Debug($search_form);
					//die();
					if(isset($search_form['selectid'])){

							$selectid = $search_form['selectid'];
							$order = $search_form['order'];
							if(isset($search_form['parent'])) $parent = $search_form['parent']; else $parent = 0;
							$maxsel = count($selectid);
							$toup = $tmp = 0;

							/*for($i=0;$i<$maxsel;$i++){
									$this->Admin_menu_model->update_order($selectid[$i], $order[$i]);
									if($tmp > $order[$i]){
									}
									if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
							}*/
							for($i=0;$i<$maxsel;$i++){

									if($tmp > $order[$i]){
											//Update ID ด้านหน้า + 1
											$this->Admin_menu_model->update_orderid_to_down($order[$i], $tmp, $parent);
									}

									$chkadd = $tmp + 1;

									if((($chkadd) <> $order[$i]) && ($toup == 0)){
											//Update ID ด้านหลัง - 1
											$toup = $tmp + 1;
											//echo "($toup, $order[$i])";
											$this->Admin_menu_model->update_orderid_to_up($toup, $order[$i], $parent);
											$toup = 0;
									}

									//Update Current ID
									$this->Admin_menu_model->update_order($selectid[$i], $order[$i], $parent);
									if($tmp == 0 || $tmp != $order[$i]) $tmp++;
							}
					}
					//die();
			}

			$web_menu = $this->Admin_menu_model->getMenu();
			$webtitle = $language['admin_menu'].' - '.$language['titleweb'];
			//Debug($web_menu);
			//die();

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"web_menu" => $web_menu,
					"webtitle" => $webtitle,
					"content_view" => 'admin/admin_menu',
					"breadcrumb" => $breadcrumb,
			);

			//$data['content_view'] = 'admin/web_menu';
			$this->load->view('template/template',$data);

	}
public function draggable(){
		//	$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;
			$breadcrumb[] = '<a href="'.base_url('admin_menu').'">'.$language['admin_menu'].'</a>';
			$breadcrumb[] = $language['draggable']; 
  
			if($this->input->server('REQUEST_METHOD') === 'POST'){
					$search_form = $this->input->post();
					//Debug($search_form);
					//die();
					if(isset($search_form['selectid'])){

							$selectid = $search_form['selectid'];
							$order = $search_form['order'];
							if(isset($search_form['parent'])) $parent = $search_form['parent']; else $parent = 0;
							$maxsel = count($selectid);
							$toup = $tmp = 0;

							/*for($i=0;$i<$maxsel;$i++){
									$this->Admin_menu_model->update_order($selectid[$i], $order[$i]);
									if($tmp > $order[$i]){
									}
									if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
							}*/
							for($i=0;$i<$maxsel;$i++){

									if($tmp > $order[$i]){
											//Update ID ด้านหน้า + 1
											$this->Admin_menu_model->update_orderid_to_down($order[$i], $tmp, $parent);
									}

									$chkadd = $tmp + 1;

									if((($chkadd) <> $order[$i]) && ($toup == 0)){
											//Update ID ด้านหลัง - 1
											$toup = $tmp + 1;
											//echo "($toup, $order[$i])";
											$this->Admin_menu_model->update_orderid_to_up($toup, $order[$i], $parent);
											$toup = 0;
									}

									//Update Current ID
									$this->Admin_menu_model->update_order($selectid[$i], $order[$i], $parent);
									if($tmp == 0 || $tmp != $order[$i]) $tmp++;
							}
					}
					//die();
			}

			$web_menu = $this->Admin_menu_model->getMenu();
			$webtitle = $language['admin_menu'].' - '.$language['titleweb'];
			//Debug($web_menu);
			//die();

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
				//	"ListSelect" => $ListSelect,
					"web_menu" => $web_menu,
					"webtitle" => $webtitle,
					"content_view" => 'admin/admin_menu_draggable',
					"breadcrumb" => $breadcrumb,
			);

		#	Debug($data);

			$this->load->view('template/template',$data);

	}
public function add(){

			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('admin_menu').'">'.$language['admin_menu'].'</a>';
			$breadcrumb[] = $language['add'];

			$parent = $this->Admin_menu_model->getMenu(0, 0);
			$webtitle = $language['add'].$language['admin_menu'].' - '.$language['titleweb'];

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"webtitle" => $webtitle,
					"parent" => $parent,
					"content_view" => 'admin/admin_menu_add',
					"breadcrumb" => $breadcrumb,
			);

			//$data['content_view'] = 'admin/web_menu';
			$this->load->view('template/template',$data);

	}
public function edit($id){

			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('admin_menu').'">'.$language['admin_menu'].'</a>';
			$breadcrumb[] = $language['edit'];

			$web_menu = $this->Admin_menu_model->viewMenu($id);
			$webtitle = $language['edit'].$language['admin_menu'].' - '.$language['titleweb'];

			$parent = $this->Admin_menu_model->getMenu(0, 0);
			//Debug($web_menu);
			//die();

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"parent" => $parent,
					"web_menu" => $web_menu,
					"webtitle" => $webtitle,
					"content_view" => 'admin/admin_menu_edit',
					"breadcrumb" => $breadcrumb,
			);

			//$data['content_view'] = 'admin/web_menu';
			$this->load->view('template/template',$data);

	}
public function save(){
			$this->db->cache_delete_all();
			$now_date = date('Y-m-d H:i:s');
			$data_input = $this->input->post();
			$obj_th = $obj_en = array();
			//bj_th['admin_menu_id'] = $data_input['admin_menu_id_th'];
			//obj_en['admin_menu_id'] = $data_input['admin_menu_id_en'];
			$obj_en['title'] = $data_input['title_en'];
			$obj_th['title'] = $data_input['title_th'];
			$obj_en['url'] = $data_input['url'];
			$obj_th['url'] = $data_input['url'];
			$obj_en['status'] = $data_input['status'];
			$obj_th['status'] = $data_input['status'];
			$obj_en['parent'] = $data_input['parentid'];
			$obj_th['parent'] = $data_input['parentid'];
			$obj_en['icon'] = $data_input['icon'];
			$obj_th['icon'] = $data_input['icon'];
			$obj_en['create_date'] = $now_date;
			$obj_th['create_date'] = $now_date;
			$obj_en['create_by'] = $this->session->userdata('admin_id');
			$obj_th['create_by'] = $this->session->userdata('admin_id');

			$maxid = $this->Admin_menu_model->get_max_id();
			$max_id = $maxid[0]['max_id'] + 1;

			//$maxorder = $this->Admin_menu_model->get_max_order($data_input['parentid']);

			$parent = $data_input['parentid'];
			$maxorder = $this->Admin_menu_model->get_max_order($parent);
			$max_order_by = $maxorder[0]['max_order_by'] + 1;

			$obj_en['admin_menu_id2'] = $max_id;
			$obj_th['admin_menu_id2'] = $max_id;
			$obj_en['order_by'] = $max_order_by;
			$obj_th['order_by'] = $max_order_by;
			$obj_en['lang'] = 'en';
			$obj_th['lang'] = 'th';





			//Debug($data_input);
			//Debug($obj_th);
			//Debug($obj_en);
			//die();

			//$insertid = $this->Admin_menu_model->store(0, $obj_th);
			//$obj_en['admin_menu_id2'] = $insertid;
			//$this->Admin_menu_model->store($data_input['admin_menu_id_en'], $obj_en);

			$this->Admin_menu_model->store(0, $obj_en);
			$this->Admin_menu_model->store(0, $obj_th);
		//**************Log activity
		$action='1';		
		$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => 1,
					"ref_type" => 0,
					"from_ip" =>'127.0.0.1',
					"ref_title" => "ADD..".'[MENU :'.$obj_en['title'].':'.$obj_th['title']."]",
					"action" => $action
		);
		$this->Admin_log_activity_model->store($log_activity);
		//**************Log activity
			// redirect alert Before
			//echo "<script>alert('Save Data');window.location.href='../admin_menu';</script>";
			// redirect alert After
			//echo "<script> window.location.href='admin_menu'; alert('Save Data'); </script>";
			// non alert 
			//redirect('admin_menu');
		?>
		<!-- This is sweetalert2 -->
		<script type="text/javascript" src="<?php echo base_url('assets/sweetalert2/dist/js/jquery-latest.js');?>"></script>
		<script src="<?php echo base_url('assets/sweetalert2/dist/sweetalert-dev.js');?>"></script>
		<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert2/dist/sweetalert.css');?>">
		<?php
		$language=$this->lang->language;
		$title=$language['savedata'];
		$msgst=$language['savecomplate'];
		$urldirec=base_url('admin_menu');
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
public function update(){
			$this->db->cache_delete_all();
			$now_date = date('Y-m-d H:i:s');
			$data_input = $this->input->post();
			$obj_th = $obj_en = array();
			//Debug($data_input);
			$obj_en['admin_menu_id'] = $data_input['admin_menu_id_en'];
			$obj_th['admin_menu_id'] = $data_input['admin_menu_id_th'];
			$obj_en['title'] = $data_input['title_en'];
			$obj_th['title'] = $data_input['title_th'];
			$obj_en['url'] = $data_input['url'];
			$obj_th['url'] = $data_input['url'];
			$obj_en['status'] = $data_input['status'];
			$obj_th['status'] = $data_input['status'];
			$obj_en['parent'] = $data_input['parentid'];
			$obj_th['parent'] = $data_input['parentid'];
			$obj_en['lastupdate_date'] = $now_date;
			$obj_th['lastupdate_date'] = $now_date;
			$obj_en['lastupdate_by'] = $this->session->userdata('admin_id');
			$obj_th['lastupdate_by'] = $this->session->userdata('admin_id');
			$obj_en['icon'] = $data_input['icon'];
			$obj_th['icon'] = $data_input['icon'];
			//Debug($obj_th);
			//Debug($obj_en);
			//die();
			$this->Admin_menu_model->store($data_input['admin_menu_id_en'], $obj_en);
			$this->Admin_menu_model->store($data_input['admin_menu_id_th'], $obj_th);
		//**************Log activity
		$action='1';
		########IP#################		
		$ipaddress='127.0.0.1';
		$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => 1,
					"ref_type" => 0,
					"from_ip" => $ipaddress,
					"ref_title" => "UPDATE..".'[MENU :'.$obj_en['title'].':'.$obj_th['title']."]",
					"action" => $action
		);
		$this->Admin_log_activity_model->store($log_activity);
		//**************Log activity
			// redirect alert Before
			//echo "<script>alert('Save Data');window.location.href='../admin_menu';</script>";
			// redirect alert After
			//echo "<script> window.location.href='admin_menu'; alert('Save Data'); </script>";
			// non alert 
			//redirect('admin_menu');
			?>
			<!-- This is sweetalert2 -->
			<script type="text/javascript" src="<?php echo base_url('assets/sweetalert2/dist/js/jquery-latest.js');?>"></script>
			<script src="<?php echo base_url('assets/sweetalert2/dist/sweetalert-dev.js');?>"></script>
			<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert2/dist/sweetalert.css');?>">
			<?php
			$language=$this->lang->language;
			$title=$language['savedata'];
			$msgst=$language['savecomplate'];
			$urldirec=base_url('admin_menu');
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
public function status($id = 0){
		if($id == 0){
			$data = array(
					"error" => 'id error'
			);
			return false;
		}else{
			
			$obj_status = $this->Admin_menu_model->get_status($id);
			$cur_status = $obj_status[0]['status'];
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;
			
			$this->Admin_menu_model->status_new($id, $cur_status);
			echo $cur_status;
			return $cur_status;
		}
	}
public function delete($id = 0){
		
		if($id == 0){
			$data = array(
					"error" => 'id error'
			);
			return false;
		}else{
			$this->Admin_menu_model->delete_menu($id);
		}
		redirect('admin_menu');
	}
public function view($id){
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;
			$breadcrumb[] = '<a href="'.base_url('admin_menu').'">'.$language['admin_menu'].'</a>';
			$breadcrumb[] = $language['view'];
			$web_menu = $this->Admin_menu_model->getMenu(0, $id, null, $language['lang']);
			//Debug($web_menu);
			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"main_menu" => $this->Admin_menu_model->viewMenu( $id),
					"web_menu" => $web_menu,
					"content_view" => 'admin/admin_menu_view',
					"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}
public function list_menu(){	
	}
public function updateblock(){
					$post=$this->input->post();
					$list=$post['list'];
					$i=1; 
					foreach ($list as $item){ 
					$id=(int)$item['id'];
					$order=$i;
					 //echo '$id=>'.$id; 
					 //echo '$order=>'.$order; 
					$data=array('weight' => $order);	
					//echo '<pre> $data=> '; print_r($data); echo '</pre>'; 	
    				$resultCount = $this->Admin_menu_model->update_draggable($data,$id);
    				 //echo '$resultCount=>'.$resultCount; 
					$i++;
					}
					echo 'Success';
 }
}