<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/** 
 * @copyright kongnakorn  jantakun 2015
*/
class Accessmenu extends CI_Controller {
public function __construct()    {
        parent::__construct();

        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }  
        ob_clean();
    }
public function index(){
			$this->load->library("AdminFactory");
			$admin_id = $this->session->userdata('admin_id');
			$admin_type = $this->session->userdata('admin_type');
			//echo 'admin_type='.$admin_type; Die();
			$language = $this->lang->language;
			$ListSelect = $this->Api_model_na->user_menu($admin_type);
                $getAdmin_menu=$this->menufactory->getMenu(0, $admin_id, $admin_type);
               //Debug($getAdmin_menu);
			//die();
               $getAdminType=$this->adminfactory->getAdminType($id=NULL,$admin_id);
               //Debug($getAdminType);
			//die();
			$breadcrumb[] = $language['access_menu'];
			//Debug($this->session);
			//echo "admin_id = $admin_id"; die();
			$data = array(
						"admin_menu" => $getAdmin_menu,
						"admintype" => $getAdminType,
						"ListSelect" => $ListSelect,
						"headtxt" => 'Admin Level',
						"breadcrumb" => $breadcrumb,
						"content_view" => 'admin/list_group',
			);
			$this->load->view('template/template',$data);

	}
public function edit($id){
		ob_clean();
			$admin_id = $this->session->userdata('admin_id');
			$admin_type = $this->session->userdata('admin_type');
			$this->load->library("AdminFactory");
			$language = $this->lang->language;
			#$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			//Debug($ListSelect);die();
			$parentna=(int)'0';
			$idna=(int)'0';
			$admin_typena=(int)$admin_type;
			//echo ' $parentna='.$parentna.' $idna='.$idna.' $admin_typena='.$admin_typena; Die();
			$input=@$this->input->get();  
			if($input==null){$input=@$this->input->get(); } 
			$parent=$parentna;
			$admin_type=$admin_typena;
			$cachetype=2;
			$deletekey=$input['deletekey'];
			if($deletekey==1){$deletekey='1';}else{$deletekey='0';}
			$dev=$input['dev'];
			if($dev==1){$dev=1;}else{$dev=0;}
			#echo' admin_menu->'; 
			$admin_menu =  $this->menufactory->getMenu($parent,$idna,$admin_type,$cachetype,$deletekey,$dev);
			# Debug($admin_menu);die();
			#$admin_menu =  $this->menufactory->getMenu($parentna,$idna, $admin_typena);
						//$admin_menu =  $this->menufactory->getMenu();
			//Debug($admin_menu);die();
						$get_admin = $this->adminfactory->getAdminType($id);
			#Debug($admin_menu);die();
						$get_accessmenu = $this->menufactory->getAccessMenu($id);
			#Debug($get_accessmenu);die();
			$access_menu = '';
			$view_obj = $view_subobj = array();
			$breadcrumb[] = '<a href="'.base_url('accessmenu').'">'.$language['access_menu'].'</a>';
			$breadcrumb[] = $language['edit'];
			if($admin_menu){
					$allmenu = count($admin_menu);
					for($m=0;$m<$allmenu;$m++){
						
							$row = $admin_menu[$m];
							if(CompareID($row->_admin_menu_id2, $get_accessmenu, 'admin_menu_id')) $sel = 'selected'; else $sel = '';
							
							$access_menu .= '<option value="'.$row->_admin_menu_id2.'" '.$sel.'>'.$row->_title.'</option>';
							$view_obj[$row->_admin_menu_id2]['title'] = $row->_title;
							$view_obj[$row->_admin_menu_id2]['order'] = $m + 1;

							//Debug($row);
							$submenu = $this->menufactory->getMenu($row->_admin_menu_id2);
							//Debug($this->db->last_query());

							//Debug($submenu);							
							if($submenu){
									$allsubmenu = count($submenu);
									for($n=0;$n<$allsubmenu;$n++){
											$subrow = $submenu[$n];
											if(CompareID($subrow->_admin_menu_id2, $get_accessmenu, 'admin_menu_id')) $sel = 'selected';
											else $sel = '';
											$menu_id2=$subrow->_admin_menu_id2;
											$title=$subrow->_title;	
											$s=' ็';
											$rp=''; 
											$title=str_replace($s,$rp,$title);
											$access_menu .= '<option value="'.$menu_id2.'" '.$sel.'>-'.$title.'</option>';
											$view_subobj[$row->_admin_menu_id2][$menu_id2]['title'] = $title;
											$view_subobj[$row->_admin_menu_id2][$menu_id2]['order'] = $n + 1;
									}
							}
					}
			}
			
			/*echo '<select multiple="multiple" id="access_menu" name="access_menu[]" class="form-control">';
			echo $access_menu;
			echo '</select>';
			die();*/
			 #echo '<pre> AccessMenu=>'; print_r($access_menu); echo '</pre>';  Die();
			$admintype=$this->adminfactory->getAdminType($id);

			#echo '<pre>id=>'; print_r($id); echo '</pre>';  echo '<pre>admintype=>'; print_r($admintype); echo '</pre>';  Die();

			$data = array(
					"error" => 0,
					"success" => null,
					"admin_menu" => $this->menufactory->getMenu(),
					"admintype" => $this->adminfactory->getAdminType($id),
					//"ListSelect" => $ListSelect,
					"AccessMenu" => $access_menu,
					"view_obj" => $view_obj,
					"view_subobj" => $view_subobj,
					"headtxt" => 'Admin Level',
					"breadcrumb" => $breadcrumb,
					"content_view" => 'admin/list_access',
			);
  # echo '<pre>$data=>'; print_r($data); echo '</pre>';  Die();

   
			$this->load->view('template/template',$data);
	}

public function save(){
		$language = $this->lang->language;
		$this->load->library("AdminFactory");
		$i = $maxaccess = 0;
		$arr = $access_menu = array();
		$save_json = null;
		$access_menu = $this->input->post('access_menu');
		$admin_type_id = $this->input->post('admin_type_id',TRUE);
		$admintype = 'admintype'.$admin_type_id;
		# Debug($access_menu);	die();
	if($access_menu==null){
		$error=$language['error'];
		$notdata=$language['notdata'];
				//	Alert($msg, base_url('accessmenu/edit/'.$admin_type_id)); die();
				$urldirec=base_url('accessmenu/edit/'.$admin_type_id).'?deletekey=1'; 
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
												title: " '.$error.'!",
												text: "'.$notdata.'",
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


			if($access_menu){
				$maxaccess = count($access_menu);
				for($i=0;$i<$maxaccess;$i++){
					
					$arr[$i]['admin_type_id'] = $admin_type_id;
					$arr[$i]['admin_menu_id'] = $access_menu[$i];
					//echo "$i .)".$access_menu[$i]."<br>";				
					$menu_parem = $this->menufactory->getMenuParem($access_menu[$i]);
					
					//Debug($menu_parem);
					//echo "<hr>";
					
					@$save_json[$i]->admin_type_id = $admin_type_id;
					$save_json[$i]->admin_menu_id = $access_menu[$i];
					$save_json[$i]->title_en = $menu_parem[0]->title;
					$save_json[$i]->title_th = $menu_parem[1]->title;
					$save_json[$i]->url = $menu_parem[0]->url;
					$save_json[$i]->sub = $menu_parem[0]->option;
					$save_json[$i]->parent = $menu_parem[0]->parent;
					$save_json[$i]->icon = $menu_parem[0]->icon;
					//Debug($menu_parem);
				}			
			}
			//Debug($save_json);

			SaveJSON($save_json, $admintype, true);
			//Insert to DB
			$this->menufactory->accessMenu($arr);
			$savedata=$language['savedata'];
			$msg = $savedata;	
		//**************Log activity
		$action='1';
	########IP#################		
	$ipaddress = '127.0.0.1';
	//"from_ip" => $ipaddress,
			$date=date('T-m-d H:i:s');
			$status='1';
			$admin_id=$this->session->userdata('admin_id');
			$log_activity = array(
						"admin_id" => $admin_id,
						"ref_id" => 1,
						"from_ip" => $_SERVER['REMOTE_ADDR'],
						"ref_type" => 1,
						"from_ip" => $ipaddress,
						"ref_title" => "[Update Accessmenu Type:".$admintype." ]",
						"action" => $action,
						"create_date" => date('Y-m-d H:i:s'),
						"status" => $status,
						"lang" => $this->lang->language['lang'],
			);
		$this->Admin_log_activity_model->store($log_activity);
		//Debug($log_activity);
		// Die();
		//**************Log activity
				
			//	Alert($msg, base_url('accessmenu/edit/'.$admin_type_id)); die();
			$urldirec=base_url('accessmenu/edit/'.$admin_type_id).'?deletekey=1'; 
		?>
					<!-- This is sweetalert2 -->
					<script type="text/javascript" src="<?php echo base_url('assets/sweetalert2/dist/js/jquery-latest.js');?>"></script>
					<script src="<?php echo base_url('assets/sweetalert2/dist/sweetalert-dev.js');?>"></script>
					<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert2/dist/sweetalert.css');?>">
		<?php
		$savedata=$language['savedata'];
				echo'<script>
								$( document ).ready(function() {
									//////////////////
									swal({
									title: " '.$savedata.'!",
									text: "  '.$msg.'.",
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

					/*$obj_status = $this->News_model->get_status($id);
					$cur_status = $obj_status[0]['status'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;

					$obj_data['status'] = $cur_status;
					if($this->News_model->store2($id, $obj_data)) echo $cur_status;*/
					//echo "update succedd.";
					
			}

	}
}