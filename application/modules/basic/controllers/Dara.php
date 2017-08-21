<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Dara extends CI_Controller {

    public function __construct()    {
			parent::__construct();
			
			$this->load->model('Dara_model');
			$this->load->model('Dara_type_model');
			$this->load->helpers('img');

			$breadcrumb = array();

			if(!$this->session->userdata('is_logged_in')){
				redirect('admin/login');
			}
    }

	public function index(){
			$this->listview();
    }

	public function gridview(){

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			$language = $this->lang->language;
			$breadcrumb[] = $language['dara'];
			$search_form = $this->input->post();

			$notification_birthday = $this->Api_model->notification_birthday();

			if(isset($search_form)){

				//Debug($search_form);
				//if(isset($search_form["p"])){ $p = $search_form["p"]; }else $p = 0;

				/*if(isset($search_form['list_page'])) $list_page = trim($search_form['list_page']); else $list_page = '20'; //แสดงผลหน้าละกี่หัวข้อ  ค่าเริ่มต้น
				if(isset($search_form['p'])) $p = trim($search_form['p']); else $p = 0;

				if($p == 0){
						$p=1;
						$startpage = 0;
				}else{
						$startpage=(($p-1)*$list_page);
				}*/

				if(isset($search_form['keyword'])) $keyword['keyword'] = trim($search_form['keyword']); else $keyword = null;

				$sortby = (isset($search_form['sortby'])) ? trim($search_form['sortby']) : null;
				$dara_type = (isset($search_form['dara_type'])) ? trim($search_form['dara_type']) : null;
				$dara_status = (isset($search_form['dara-status'])) ? trim($search_form['dara-status']) : null;
				$gender = (isset($search_form['gender'])) ? trim($search_form['gender']) : null;

				if($dara_status == 0) $dara_status = null;
				elseif($dara_status == 3) $dara_status = 0;
				//Debug("(null, $keyword, $dara_type, $gender, $sortby, 'Asc', $dara_status)");

				//echo "keyword=$keyword";

				//$dara_list = $this->Dara_model->get_dara_profile(null, $keyword, $dara_type, $gender, $sortby, 'Asc', $dara_status, $startpage, $list_page);
				$dara_list = $this->Dara_model->get_dara_profile(null, $keyword, $dara_type, $gender, $sortby, 'Asc', $dara_status, 0, 100);
			}else{
				$keyword = "Tmon";
				$dara_list = $this->Dara_model->get_dara_profile(null, null, null, '', 'lastupdate_date', 'asc', null, 0, 100);
			}
			//Debug($this->db->last_query());
			$dara_all = $this->Dara_model->get_count();

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"dara_type" => $this->Dara_type_model->get_dara_type(),
					"dara_list" => $dara_list,
					"dara_all" => $dara_all[0]->countid,
					"search_form" => $search_form,
					"notification_birthday" => $notification_birthday,
					"content_view" => 'basic/dara',
					"breadcrumb" => $breadcrumb
			);

			$this->load->view('template/template',$data);
	}

	public function listview(){

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			$language = $this->lang->language;
			$notification_birthday = $this->Api_model->notification_birthday();
			$breadcrumb[] = $language['dara'];

			$search_form = $this->input->post();

			if(isset($search_form)){

				//Debug($search_form);
				//if(isset($search_form["p"])){ $p = $search_form["p"]; }else $p = 0;

				/*if(isset($search_form['list_page'])) $list_page = trim($search_form['list_page']); else $list_page = '20'; //แสดงผลหน้าละกี่หัวข้อ  ค่าเริ่มต้น
				if(isset($search_form['p'])) $p = trim($search_form['p']); else $p = 0;
				if($p == 0){
						$p=1;
						$startpage = 0;
				}else{
						$startpage=(($p-1)*$list_page);
				}*/

				if(isset($search_form['keyword'])) $keyword = trim($search_form['keyword']); else $keyword = null;
				$sortby = (isset($search_form['sortby'])) ? trim($search_form['sortby']) : null;
				$dara_type = (isset($search_form['dara_type'])) ? trim($search_form['dara_type']) : null;
				$dara_status = (isset($search_form['dara-status'])) ? trim($search_form['dara-status']) : null;
				$gender = (isset($search_form['gender'])) ? trim($search_form['gender']) : null;

				if($dara_status == 0) $dara_status = null;
				elseif($dara_status == 3) $dara_status = 0;

				//$dara_list = $this->Dara_model->get_dara_profile(null, $keyword, $dara_type, $gender, $sortby, 'Asc', $dara_status, $startpage, $list_page);
				$dara_list = $this->Dara_model->get_dara_profile(null, $keyword, $dara_type, $gender, $sortby, 'Asc', $dara_status, 0, 100);
			}else{
				$keyword = "Tmon";
				$dara_list = $this->Dara_model->get_dara_profile(null, null, null, '', 'lastupdate_date', 'asc', null, 0, 100);
			}
			$dara_all = $this->Dara_model->get_count();

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"dara_type" => $this->Dara_type_model->get_dara_type(),
					"dara_list" => $dara_list,
					"dara_all" => $dara_all[0]->countid,
					"search_form" => $search_form,
					"notification_birthday" => $notification_birthday,
					"content_view" => 'basic/dara_listview',
					"breadcrumb" => $breadcrumb
			);

			$this->load->view('template/template',$data);
	}	

	public function search(){
			
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			if($this->input->server('REQUEST_METHOD') === 'GET'){

					$search_form = $this->input->get();
					if(isset($search_form['kw'])){
							
						$dara_type_id = $dara_profile_id = $dara_type_name = $first_name = $nick_name = "";

						$keyw = explode(" ", $search_form['kw']);
						$dara_list = $this->Dara_model->get_dara_profile(null, $keyw, null, null, 'lastupdate_date', 'Asc', null, 0, 20);
						//$dara_list = $this->Dara_model->get_dara_profile(null, $keyw);
						//Debug($dara_list);
						if($dara_list){
								echo '<table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th>ID</th>
													<th>Pic</th>
													<th>Title</th>
													<th>'.$language['lastupdate'].'</th>
													<th>'.$language['status'].'</th>
													<th width="50%">URL</th>
												</tr>
											</thead>
											<tbody>';

								$maxlist = count($dara_list);
								for($i=0;$i<$maxlist;$i++){
										
										$dara_type_id = $dara_list[$i]['dara_type_id'];
										$dara_profile_id = $dara_list[$i]['dara_profile_id'];
										$dara_type_name = $dara_list[$i]['dara_type_name'];
										$first_name = $dara_list[$i]['first_name'];
										$nick_name = $dara_list[$i]['nick_name'];

										$url = $this->config->config['www'].'/dara/'.$dara_type_id.'/'.$dara_profile_id.'/'.($dara_type_name).'/'.($nick_name.'-'.$first_name).'';
										//$url = "http://www.Tmon.com/dara/".$dara_list[$i]['dara_profile_id'].".html";

										$img = base_url('uploads/thumb/dara').'/'.$dara_list[$i]['avatar'];
										//$img = 'uploads/thumb/'.$news_list[$i]['folder'].'/'.$news_list[$i]['file_name'];

										if($dara_list[$i]['avatar'] != "")
											$tags_img = (file_exists('uploads/thumb/dara/'.$dara_list[$i]['avatar'])) ? "<img src=".$img." height='50'>" : "";
										else
											$tags_img = "";

										$status = ($dara_list[$i]['status'] == 1) ? 'checked' : '';
										$iconstatus = '<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-3" type="checkbox" '.$status.' disabled>
														<span class="lbl"></span>
													</label>
												</div>';
										$dara_name = $dara_list[$i]['nick_name']." ".$dara_list[$i]['first_name']." ".$dara_list[$i]['last_name'];
										$edit_data = base_url('dara/edit/'.$dara_profile_id);
										//dara/edit/1357

										//echo "<li>".$news_list[$i]['news_id2'].". ".$news_list[$i]['title']." ".$url."</li>";										
										//Debug($news_list[$i]['title']);

										echo "<tr>
											<td>".$dara_list[$i]['dara_profile_id']."</td>
											<td>".$tags_img."</td>
											<td><a href='".$edit_data."' target=_blank>".$dara_name."</a></td>
											<td>".$dara_list[$i]['lastupdate_date']."</td>
											<td>".$iconstatus."</td>
											<td>".$url."</td>
										</tr>";										
								}						
								echo "</tbody></table>";
						}else
								echo "ไม่มีข้อมูล";
					}
					//die();
			}

	}

	public function chkname(){
			
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();

					if(isset($search_form['first_name'])){
							//echo "is keyword";
							//Debug($search_form);
							$dara_list = $this->Dara_model->search_dara_profile('first_name', $search_form['first_name']);
							//Debug($dara_list);
							$name = '';
							
							$maxlist = count($dara_list);
							for($i=0;$i<$maxlist;$i++){
									if($i == 0){
										echo "<ul>";
										echo "<li><b>รายชื่อที่มีอยู่แล้ว</b></li>";
									}
									$name = '['.$dara_list[$i]->nick_name.'] '.$dara_list[$i]->first_name.' '.$dara_list[$i]->last_name;
									echo "<li>$name</li>";
							}
							if($name == '')
								echo "<li><b>สามารถเพิ่มรายชื่อนี้ได้ ยังไม่มีข้อมูล</b></li>";
							else 
								echo "</ul>";
					}

					if(isset($search_form['last_name'])){
							//echo "is keyword";
							//Debug($search_form);
							$dara_list = $this->Dara_model->search_dara_profile('last_name', $search_form['last_name']);
							//Debug($dara_list);
							$name = '';
							
							$maxlist = count($dara_list);
							for($i=0;$i<$maxlist;$i++){
									if($i == 0){
										echo "<ul>";
										echo "<li><b>รายชื่อที่มีอยู่แล้ว</b></li>";
									}
									$name = '['.$dara_list[$i]->nick_name.'] '.$dara_list[$i]->first_name.' '.$dara_list[$i]->last_name;
									echo "<li>$name</li>";
							}
							if($name == '')
								echo "<li><b>สามารถเพิ่มรายชื่อนี้ได้ ยังไม่มีข้อมูล</b></li>";
							else 
								echo "</ul>";
					}

					if(isset($search_form['nick'])){
							//echo "is keyword";
							$dara_list = $this->Dara_model->search_dara_profile('nick_name', $search_form['nick']);
							//Debug($dara_list);
							$name = '';
							$maxlist = count($dara_list);
							for($i=0;$i<$maxlist;$i++){
									if($i == 0){
										echo "<ul>";
										echo "<li><b>รายชื่อที่มีอยู่แล้ว</b></li>";
									}
									$name = '['.$dara_list[$i]->nick_name.'] '.$dara_list[$i]->first_name.' '.$dara_list[$i]->last_name;
									echo "<li>$name</li>";
							}
							if($name == '') 
								echo "<li><b>สามารถเพิ่มรายชื่อนี้ได้ ยังไม่มีข้อมูล</b></li>";
							else 
								echo "</ul>";
					}

					if(isset($search_form['pen_name'])){
							//echo "is keyword";
							$dara_list = $this->Dara_model->search_dara_profile('pen_name', $search_form['pen_name']);
							//Debug($dara_list);
							$name = '';
							$maxlist = count($dara_list);
							for($i=0;$i<$maxlist;$i++){
									if($i == 0){
										echo "<ul>";
										echo "<li><b>รายชื่อที่มีอยู่แล้ว</b></li>";
									}
									$name = ''.$dara_list[$i]->pen_name;
									echo "<li>$name</li>";
							}
							if($name == '') 
								echo "<li><b>สามารถเพิ่มรายชื่อนี้ได้ ยังไม่มีข้อมูล</b></li>";
							else 
								echo "</ul>";
					}
					//die();
			}

	}


	public function add(){

			$this->load->model('Belong_to_model');
			$this->load->helper('ckeditor');

			$Path_CKeditor = './plugins/ckeditor-integrated/ckeditor';
			$Path_CKfinder = './plugins/ckeditor-integrated/ckfinder';

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;
			$notification_birthday = $this->Api_model->notification_birthday();

			$breadcrumb[] = '<a href="'.base_url('dara').'">'.$language['dara'].'</a>';
			$breadcrumb[] = $language['add'];

			$dara_type = $this->Dara_type_model->get_dara_type();
			//$belong_to = $this->Belong_to_model->get_data();
			$belong_to = $this->Belong_to_model->getSelect();
			
			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"dara_type" => $dara_type,
					"belong_to" => $belong_to,
					"notification_birthday" => $notification_birthday,
					"content_view" => 'basic/dara_add',
					"breadcrumb" => $breadcrumb
			);

			//Ckeditor's configuration
			$data['ckeditor'] = array(
				'id'     =>     'hobby',
				'path'    =>    $Path_CKeditor,
				'config' => array(
					'toolbar'     =>     "Full",
					//'width'     =>     "800px",
					//'height'     =>     '200px',
					'filebrowserBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html',
					'filebrowserImageBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Images',
					'filebrowserFlashBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Flash',
					'filebrowserUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Files',
					'filebrowserImageUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Images',
					'filebrowserFlashUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Flash',
				),
			);
			$data['ckeditor2'] = array(
				'id'     =>     'profile_background',
				'path'    =>    $Path_CKeditor,
				'config' => array(
					'toolbar'     =>     "Full",
					//'width'     =>     "800px",
					//'height'     =>     '200px',
					'filebrowserBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html',
					'filebrowserImageBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Images',
					'filebrowserFlashBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Flash',
					'filebrowserUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Files',
					'filebrowserImageUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Images',
					'filebrowserFlashUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Flash',
				),
			);
			$data['ckeditor3'] = array(
				'id'     =>     'portfolio',
				'path'    =>    $Path_CKeditor,
				'config' => array(
					'toolbar'     =>     "Full",
					//'width'     =>     "800px",
					//'height'     =>     '200px',
					'filebrowserBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html',
					'filebrowserImageBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Images',
					'filebrowserFlashBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Flash',
					'filebrowserUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Files',
					'filebrowserImageUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Images',
					'filebrowserFlashUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Flash',
				),
			);
			
			$this->load->view('template/template',$data);
	
	}

	public function edit($id = 0){
			
			$this->load->model('Tags_model');
			$this->load->model('Belong_to_model');
			//$this->load->model('default_model');
			$this->load->helper('ckeditor');

			$Path_CKeditor = './plugins/ckeditor-integrated/ckeditor';
			$Path_CKfinder = './plugins/ckeditor-integrated/ckfinder';

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;
			$notification_birthday = $this->Api_model->notification_birthday();

			$breadcrumb[] = '<a href="'.base_url('dara').'">'.$language['dara'].'</a>';
			$breadcrumb[] = $language['edit'];

			//$dara_type = $this->Dara_type_model->get_dara_type();
			$dara_profile = $this->Dara_model->get_dara_profile($id);
			
			$sel_tags = $this->Tags_model->get_tag_pair($id, 5);
			//Debug($dara_profile);
			$belong_to = explode(',', $dara_profile[0]['belong_to_id']);
			//Debug($belong_to);
			//$set_default = array();
			//$set_default = new default_model();
			$set_default = NULL;

			if($belong_to)
				foreach($belong_to as $arr => $val){
							//echo "$arr => $val<br>";
							//if (!isset($set_default[$arr]->value)) 
							@$set_default[$arr]->value = $val;
				}
			//Debug($set_default);

			$sel_belong_to = $this->Belong_to_model->getSelect($set_default);
			//$sel_belong_to = $this->Belong_to_model->get_data();
			
			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"dara_type" => $this->Dara_type_model->get_dara_type(),
					"belong_to_list" => $sel_belong_to,
					"dara_list" => $dara_profile[0],
					"sel_tags" => $sel_tags,
					"notification_birthday" => $notification_birthday,
					"content_view" => 'basic/dara_edit',
					"breadcrumb" => $breadcrumb
			);
			//Ckeditor's configuration
			$data['ckeditor'] = array(
				'id'     =>     'hobby',
				'path'    =>    $Path_CKeditor,
				'config' => array(
					'toolbar'     =>     "Full",
					//'width'     =>     "800px",
					//'height'     =>     '200px',
					'filebrowserBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html',
					'filebrowserImageBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Images',
					'filebrowserFlashBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Flash',
					'filebrowserUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Files',
					'filebrowserImageUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Images',
					'filebrowserFlashUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Flash',
				),
			);
			$data['ckeditor2'] = array(
				'id'     =>     'profile_background',
				'path'    =>    $Path_CKeditor,
				'config' => array(
					'toolbar'     =>     "Full",
					//'width'     =>     "800px",
					//'height'     =>     '200px',
					'filebrowserBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html',
					'filebrowserImageBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Images',
					'filebrowserFlashBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Flash',
					'filebrowserUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Files',
					'filebrowserImageUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Images',
					'filebrowserFlashUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Flash',
				),
			);
			$data['ckeditor3'] = array(
				'id'     =>     'portfolio',
				'path'    =>    $Path_CKeditor,
				'config' => array(
					'toolbar'     =>     "Full",
					//'width'     =>     "800px",
					//'height'     =>     '200px',
					'filebrowserBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html',
					'filebrowserImageBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Images',
					'filebrowserFlashBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Flash',
					'filebrowserUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Files',
					'filebrowserImageUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Images',
					'filebrowserFlashUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Flash',
				),
			);
					
			$this->load->view('template/template',$data);
	
	}

	public function remove_img($dara_profile_id = 0){
			
			//echo $dara_profile_id ;
			$src = $this->input->post('name');
			//echo $dara_profile_id .' '. $src ;
			if(file_exists('uploads/dara/'.$src)) unlink('uploads/dara/'.$src);
			$obj_data['avatar'] = '';
			if($this->Dara_model->store($dara_profile_id, $obj_data))
				echo 'Yes';
			else
				echo 'No';

	}

	public function picture($id = 0){
						
			$this->load->model('Api_model');
			$this->load->model('Picture_model');

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			/*$ref_id = $this->input->get();
			if($ref_id)
				foreach($ref_id as $key => $val){
						//echo "<br>".$news_id." : ".$val;
						if($key == "news_id") $news_id = $val;
						if($key == "Orientation") $orientation = $val;
				}*/

			$breadcrumb[] = '<a href="'.base_url('dara').'">'.$language['dara'].'</a>';
			$breadcrumb[] = '<a href="'.base_url('dara/edit').'/'.$this->uri->segment(3).'">'.$language['edit'].'</a>';
			$breadcrumb[] = $language['picture'];
			//$picture_list = $this->Api_model->get_picture($news_id, $id);

			$dara_profile = $this->Dara_model->get_dara_profile($id);

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"dara_id" => $id,
					"dara_list" => $dara_profile[0],
					"content_view" => 'basic/dara_edit_picture',
					"breadcrumb" => $breadcrumb
			);
			$this->load->view('template/template',$data);

	}

	private function set_uploadto_tmp($client_name, $width = 250, $height = 170){   

		$config = array();
		$folder = date('Ymd');
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = './uploads/dara/'.$client_name;
		//$config['new_image'] = './uploads/tmp/'.$client_name;

		$config['create_thumb'] = FALSE;	//สร้าง Thumb โดย CI
		$config['maintain_ratio'] = TRUE;
		//$config['width']     = $width;
		//$config['height']   = $height;

		//****Copy Original File to TMP
		$upload_path = './uploads/tmp/dara';
		$tmp = $upload_path.'/'.$client_name;

		$src = fopen($config['source_image'], 'r');
		$dest = fopen($tmp, 'w');

		echo "stream_copy_to_stream(".$config['source_image'].")";
		echo " ==> ".$tmp;

		stream_copy_to_stream($src, $dest);

		return $config;
	}

	private function set_upload_options(){   

		$config = array();
		$folder = date('Ymd');
			
		$config['upload_path'] = './uploads/dara/';
		if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

		$upload_path = './uploads/tmp/';
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		$upload_path = './uploads/tmp/dara/';
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		//$config['max_size']      = '0';
		$config['overwrite']     = FALSE;
		//$config['max_size'] = '300';
		//$config['max_width'] = '1024';
		//$config['max_height'] = '768';
		return $config;
	}

	public function rotate(){

			$this->load->model('Picture_model');
			//$gallery_id = $this->input->get('gallery_id');
			$rotate = $this->input->get('rotate');
			//$folder = $this->input->get('folder');
			$file = $this->input->get('file');

			$rotate = ($rotate == "l") ? -90 : 90;  //หมุนทวนเข็มนาฬิกา และ ตามเข็มนาฬิกา
			//$sourcefile = './uploads/dara/'.$folder.'/'.$file;
			//$sourcefile_tmp = './uploads/tmp/dara/'.$folder.'/'.$file;
			$sourcefile = './uploads/dara/'.$file;
			$sourcefile_tmp = './uploads/tmp/dara/'.$file;

			//Debug($sourcefile);
			//Debug($rotate);
			$this->Picture_model->rotate_img($sourcefile, $sourcefile, $rotate, 1);
			$this->Picture_model->rotate_img($sourcefile_tmp, $sourcefile_tmp, $rotate, 0);
	}

	public function picture_watermark($id = 0){

			$this->load->model('Api_model');
			$this->load->model('Picture_model');
			$this->load->helper('img');

			$inputdata = $this->input->post();
			if($inputdata)
				foreach($inputdata as $key => $val){
						if($key == "id") $id = $val;
						if($key == "news_id") $news_id = $val;
						if($key == "folder") $folderdb = $val;
						if($key == "file") $file = $val;
						if($key == "watermark") $watermark = $val;
				}
			//Debug($inputdata);
			//die();
			$folder = 'dara';
			$type = 'dara';

			switch($watermark){
						case "center" : $picture_list = $this->Picture_model->watermark($file, $folder, $type); break; //Logo ขนาดเล็ก
						case "horizontal" : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 1); break; //แนวนอน
						case "vertical" : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 2); break; //แนวตั้ง
						case "logo" :
						default : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 3); break; //Logo ขนาดใหญ่
			}

			//Debug($inputdata);
			//die();
			redirect('dara/edit/'.$id);
			//http://localhost/Tmon_admin/news/picture_edit/121?news_id=43
			die();	
	}

	public function save(){//บันทึกลงฐานข้อมูล
		
			$this->load->model('Tags_model');
			$this->load->library('image_lib');

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			$language = $this->lang->language;

			$breadcrumb[] = $language['dara'];

			$new_data = $obj = array();
			$insert_newid = $tag_name = '';
			$data_input = $this->input->post();

			if($data_input)
					foreach($data_input as $key => $val){			
							if($key != "editorCurrent" && $key != "tags" && $key != "tags_remove") $new_data[$key] = $val;
							if($key == "tags") $get_tags = explode(",", trim($val));
							//if($key == "tags_remove") $tags_remove = explode(",", $val);
							//if($key == "belong_to_id") $belong_to = explode(",", $val);
					}

			//if(!$new_data['dara_profile_id_map']) $new_data['dara_profile_id_map'] = $new_data['dara_profile_id'];
			if(!isset($new_data['dara_profile_id'])){
					$new_data['dara_profile_id'] = 0;
					//$new_data['dara_profile_id_map'] = 0;
			}

			//Debug($new_data);			
			//echo "get_tags";
			//Debug($get_tags);
			//die();

			//$this->load->library('form_validation');
			// field name, error message, validation rules
			//$this->form_validation->set_rules('first_name', $language['first_name'], 'trim|required');
			//$this->form_validation->set_rules('last_name', $language['last_name'], 'trim|required');
			//$this->form_validation->set_rules('nick_name	', $language['nickname'], 'trim|required');

			//$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

			//if($this->form_validation->run() == FALSE){
			if(1 == 2){
					//$error_delimit = $this->form_validation->set_error_delimiters();
					//Debug($error_delimit);

					/*$this->load->model('Belong_to_model');
					//$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
					//$language = $this->lang->language;
					//$breadcrumb[] = '<a href="'.base_url('dara').'">'.$language['dara'].'</a>';
					$breadcrumb[] = $language['add'];
					$dara_type = $this->Dara_type_model->get_dara_type();
					$belong_to = $this->Belong_to_model->getSelect();
					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"dara_type" => $dara_type,
							"belong_to" => $belong_to,
							"content_view" => 'basic/dara_add',
							"error" => 'Please, Enter nickname firstname lastname',		
							"breadcrumb" => $breadcrumb
					);			
					$this->load->view('template/template',$data);*/

					echo '<script type="text/javascript">
					alert("Please, Enter nickname firstname and lastname");window.location="'.base_url("dara").'";
					</script>';
					//exit;
					die();
			}else{
					

					$now_date = date('Y-m-d H:i:s');

					//if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);
					$this->load->library('upload', $this->set_upload_options());

					$this->upload->initialize($this->set_upload_options());
					if ( ! $this->upload->do_upload('avatar')){
							$error = array('error' => $this->upload->display_errors());
							$data['upload_status'] = $error;
					}else{
							$data = array(
									'admin_menu' => $this->menufactory->getMenu(),
									'upload_data' => $this->upload->data(),
									'upload_status' => 'Success',
							);

							$this->image_lib->clear();
							$this->image_lib->initialize($this->set_uploadto_tmp($this->upload->client_name));
							$this->image_lib->resize();

							//$data['upload_status'] = $data;
					}

					if($this->upload->client_name){
							$new_data['avatar'] = $this->upload->client_name;
					}

					//die();
					
					if($data_input['birth_date'] != '') $new_data['birth_date'] = DateDB($data_input['birth_date']);

					$belong_to_id = '';
					unset($new_data['belong_to_id']);

					//Debug($data_input['belong_to_id']);
					if(isset($data_input['belong_to_id'])){
							foreach($data_input['belong_to_id'] as $field => $val){
									if($belong_to_id == '') $belong_to_id = $val;
									else $belong_to_id .= ','.$val;
							}
							$new_data['belong_to_id'] = $belong_to_id;
					}

					//Debug($new_data);
					//die();

					if($new_data['dara_profile_id'] > 0){

								$action = 2;
								$new_data['lastupdate_date'] = $now_date;
								$new_data['lastupdate_by'] = $this->session->userdata('admin_id');
								$this->Dara_model->store($new_data['dara_profile_id'], $new_data);
								$data = array(
										"admin_menu" => $this->menufactory->getMenu(),
										"ListSelect" => $ListSelect,
										"dara_type" => $this->Dara_type_model->get_dara_type(),
										"dara_list" => $this->Dara_model->get_dara_profile(),
										"search_form" => null,
										"content_view" => 'basic/dara',
										"breadcrumb" => $breadcrumb,
										"success" => 'Update Dara Complete.'
								);
								$insert_newid = $new_data['dara_profile_id'];						
								
					}else{ //Insert DB

								$action = 1;

								unset($new_data['dara_profile_id_map']);

								$new_data['create_date'] = $now_date;
								$new_data['create_by'] = $this->session->userdata('admin_id');
								$insert_newid = $this->Dara_model->store(0, $new_data);
								//Debug($insert_newid);
								$data = array(
										"admin_menu" => $this->menufactory->getMenu(),
										"ListSelect" => $ListSelect,
										"dara_type" => $this->Dara_type_model->get_dara_type(),
										"dara_list" => $this->Dara_model->get_dara_profile(),
										"search_form" => null,
										"content_view" => 'basic/dara',
										"breadcrumb" => $breadcrumb,
										"success" => 'Add Dara Complete.'
								);				
					}

					//Debug($data_input);
					//echo count($get_tags);
					//echo "action = $action";
					//die();

					//echo "action = $action ".count($get_tags);

					if($action == 1 ||  count($get_tags) <= 1){
					//เพิ่มดาราใหม่ให้ Add Default Tags เป็น [first_name last_name], [nick_name]
							//**************Add tags******************
							$tag_name = trim($new_data['first_name']." ".$new_data['last_name']);
							$curtag = $this->Tags_model->validate_tags($tag_name);
							$maxid_tag = $curtag[0]->tag_id;

							if(!$curtag){
									$get_max_id = $this->Tags_model->get_max_id();
									//Debug($get_max_id);
									
									$maxid_tag = $get_max_id[0]['max_id'];
									$maxid_tag++;
								
									$obj['tag_id'] = $maxid_tag;
									$obj['tag_text'] = $tag_name;
									$obj['create_date'] = $now_date;
									
									//Debug($obj);
									$this->Tags_model->store($obj);
							}
							unset($obj);
							//$get_max_id = $new_data['dara_profile_id'];
							$obj[0]['tag_id'] = $maxid_tag;
							$obj[0]['ref_id'] = $insert_newid;
							$obj[0]['ref_type'] = 5;
							$obj[0]['order'] = 1;
							$obj[0]['create_date'] = $now_date;
							$this->Tags_model->store_tag_pair($obj, 0);		
									
							//Add tag nick name
							unset($curtag);
							$tag_name = trim($new_data['nick_name']);
							$curtag = $this->Tags_model->validate_tags($tag_name);
							$maxid_tag = $curtag[0]->tag_id;

							if(!$curtag){
									$get_max_id = $this->Tags_model->get_max_id();
									$maxid_tag = $get_max_id[0]['max_id'];
									$maxid_tag++;					
									//echo "maxid_tag = $maxid_tag";		
									unset($obj);
													
									$obj['tag_id'] = $maxid_tag;
									$obj['tag_text'] = $tag_name;
									$obj['create_date'] = $now_date;						
									$this->Tags_model->store($obj);
									//Debug($new_data);
							}
										
							unset($obj);
							$obj[0]['tag_id'] = $maxid_tag;
							$obj[0]['ref_id'] = $insert_newid;
							$obj[0]['ref_type'] = 5;
							$obj[0]['order'] = 2;
							$obj[0]['create_date'] = $now_date;
							$this->Tags_model->store_tag_pair($obj, 0);

					}else{
							//Compare Tags
							//Debug($get_tags);
							$addtags = array();
							//echo "<hr>";
							//$chktags = count($get_tags);
							if($get_tags)
									foreach($get_tags as $key => $data){
											//echo "<p>$key => $data</p>";
											$chktag = $this->Tags_model->validate_tags(trim($data));

											if($chktag){
												//Debug($chktag);
												$addtags[] = $chktag[0]->tag_id;
											}else{ //Add Tags ใหม่ลง DB

														echo "NO TAGS IN DB<hr>";

														unset($obj);
														$get_max_id = $this->Tags_model->get_max_id();
														$maxid_tag = $get_max_id[0]['max_id'];
														$maxid_tag++;

														$obj['tag_id'] = $maxid_tag;
														$obj['tag_text'] = trim($data);
														$obj['create_date'] = $now_date;
														$addtags[] = $this->Tags_model->store($obj);

											}
									}
					}

					//ตรวจสอบ TAGS ว่ามี Tags ของ ดาราแล้วยัง
					//echo "<hr>";
					//Debug('Addtags');
					//Debug($addtags);
					if(isset($addtags))
							foreach($addtags as $key => $data){
									//echo "<p>[<b>chktag_pair</b>] $key => $data</p>";
									$result = $this->Tags_model->chktag_pair($data, 5); //Type = 5 = ดารา
									//Debug($result);
									if($result){
											
											if($result[0]->ref_type != 5){//Update Type of tag_pair
													//echo "<p>UPDATE TAGS PAIR $data</p>";
													unset($obj);
													//$obj[0]['tag_id'] = $result[0]->tag_id;
													$obj[0]['ref_type'] = 5;

													//echo "ref_type != 5<br>";
													//Debug('store_tag_pair');
													$this->Tags_model->store_tag_pair($obj, 0, $data);		
											}

									}else{ //Add Tags pair ใหม่ลง DB
											
											//Debug('Add Tags pair ใหม่ลง DB');
											
											//echo "<p>Add Tags pair ใหม่ลง DB $data</p>";
											unset($obj);
											$obj[0]['tag_id'] = $data;
											$obj[0]['ref_id'] = $insert_newid;
											$obj[0]['ref_type'] = 5;
											$obj[0]['create_date'] = $now_date;

											//echo "result == 0<br>";
											//Debug($obj);
											$this->Tags_model->store_tag_pair($obj, 0);
									}
							}
					//die();

					//**************Log activity
					$log_activity = array(
										"admin_id" => $this->session->userdata('admin_id'),
										"ref_id" => $insert_newid,
										"ref_type" => 5,
										"ref_title" => trim($new_data['nick_name']).' '.trim($new_data['first_name']." ".$new_data['last_name']),
										"action" => $action
					);			
					$this->Admin_log_activity_model->store($log_activity);
					//**************Log activity

					//$this->load->view('template/template',$data);
					redirect('dara');
			}//check validate
	}

	public function status($id = 0){

			if($id == 0){
					$data = array(
							"error" => 'id error'
					);
					return false;
			}else{
					$obj_status = $this->Dara_model->get_status($id);

					//Debug($obj_status);
					$cur_status = $obj_status[0]['status'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;

					$obj_data['status'] = $cur_status;
					if($this->Dara_model->store($id, $obj_data)) echo $cur_status;
					//$this->gen_json($id);
			}
	}
	
	public function delete($id){

			echo "Deleting... $id";
			
			$OBJnews = $this->Dara_model->get_dara_profile($id);
			$title = $OBJnews[0]['nick_name'].' '.$OBJnews[0]['first_name'].' '.$OBJnews[0]['last_name'];
			//$order_by = $OBJnews[0]['order_by'];

			$this->Dara_model->delete_dara_profile($id);
			//die();

			//$this->News_model->update_orderdel($order_by);

			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 5,
								"ref_title" => $title,
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('dara');
			die();
	}

	public function delete_tags($txt){

			$txt = urldecode($txt);
			//echo $txt;
			$result = $this->Dara_model->delete_tag($txt, 5);

			/*if($result)
				echo "Delete Success.";
			else
				echo "Can not delete.";*/

	}

	public function clear_picture(){
	
    		$upload_tmppath = '/var/www/darabackend/uploads/dara/';
			//echo __FILE__;

			/*if (!is_dir($upload_tmppath)) {
				mkdir($upload_tmppath);
			}
			rmdir($upload_tmppath);*/

			echo "<br>";
			$dir =  $upload_tmppath;

			// Open a directory, and read its contents
			if (is_dir($dir)){
			  if ($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false){
				  echo "filename:" . $file . "<br>";
				}
				closedir($dh);
			  }
			}

			/*if (is_readable($upload_tmppath)) {
			  
				$dir = $upload_tmppath;
			   // open specified directory and remove all files within
			   $dirHandle = opendir($dir);
			   $total_deleted_images = 0;
			   while ($file = readdir($dirHandle)) {
			 
				  //if(!is_dir($file)) {
						//unlink($dir.$file);
						/////remove  >>> // below if needed
						//echo 'Deleted file <b>'.$file.'</b><br />';
						// $total_deleted_images++;
				  //}
				  echo 'Deleted file <b>'.$file.'</b><br />';

			   }
			   closedir($dirHandle);
				if($total_deleted_images=='0'){
					echo '<!-- no if you want to see on page - now hidden -->';
				}
			   
				//remove dir at the end
				//rmdir($dir);
			} else {
			echo "";
			}*/

			//exec("cd ".$upload_tmppath );
			//exec("rm -fr *");

	}

	public function import_picture(){

			$this->load->helper('img');

    		$upload_tmppath = './uploads/dara';

			$dara_list = $this->Dara_model->get_daraall();
			//Debug($dara_list);
			//die();
			if(isset($dara_list)){
					$start = 0;
					//$all = count($dara_list);
					$all = 50;

					for($i=$start;$i<$all;$i++){
								
								$avatar_dara = $dara_list[$i]['avatar'];
								$url_picture = 'http://www.Tmon.com/Picture_Dara/'.$avatar_dara;
								$new_filename = $upload_tmppath.'/'.$avatar_dara;

								$src = fopen($url_picture, 'r');
								$dest1 = fopen($new_filename, 'w');
								stream_copy_to_stream($src, $dest1);
								$number = $i+1;
								//echo $number." success.<br>";
					}
					echo "download picture ".count($dara_list)." success.<br>";
			}

	}

	public function import(){
			
			$data = array();
			$insert = false;
			$display = false;

			if($this->input->get('number'))
				$number = $this->input->get('number');
			else
				$number = 20;

			if($this->input->get('insert')) $insert = $this->input->get('insert');
			if($this->input->get('display')) $display = $this->input->get('display');

			//Debug($this->input->get());

			$vdo_list = $this->Dara_model->load_sstv(1, $number);

			if($display == true) Debug($vdo_list);
			//die();

			if(isset($vdo_list)){
				if($vdo_list['header']['code'] == 200){
					$allvdo = count($vdo_list['body']);
					//echo "ALL = $allvdo <br>";
					
					for($i=0;$i<$allvdo;$i++){

								$IDDara = $vdo_list['body'][$i]['IDDara'];
								$FkIDDaraType = StripTxt($vdo_list['body'][$i]['FkIDDaraType']);
								$Fullname = $vdo_list['body'][$i]['NameD'];
								$nickname = $vdo_list['body'][$i]['NickN'];
								$Sex = $vdo_list['body'][$i]['Sex'];
								$BirthDay = $vdo_list['body'][$i]['BirthDay'];
								$Province = $vdo_list['body'][$i]['Province'];
								$Weight = $vdo_list['body'][$i]['Weight'];
								$Tall = $vdo_list['body'][$i]['Tall'];
								$Education = $vdo_list['body'][$i]['Education'];
								$Hobby = $vdo_list['body'][$i]['Hobby'];
								$Profile = $vdo_list['body'][$i]['Profile'];
								$Performance = $vdo_list['body'][$i]['Performance'];
								$LastPerformance = $vdo_list['body'][$i]['LastPerformance'];
								$Pic0 = $vdo_list['body'][$i]['Pic0'];
								$Pic1 = $vdo_list['body'][$i]['Pic1'];
								$Pic2 = $vdo_list['body'][$i]['Pic2'];
								$Pic3 = $vdo_list['body'][$i]['Pic3'];
								$CountView = $vdo_list['body'][$i]['CountView'];
								$DateCreate = $vdo_list['body'][$i]['DateCreate'];

								$picture = 'http://www.Tmon.com/Picture_Dara/'.$Pic0;
								$picture1 = 'http://www.Tmon.com/Picture_Dara/'.$Pic1;
								$picture2 = 'http://www.Tmon.com/Picture_Dara/'.$Pic2;
								$picture3 = 'http://www.Tmon.com/Picture_Dara/'.$Pic3;

								switch($FkIDDaraType){	
										case 1 : $FkIDDaraType = 1; break;
										case 2 : $FkIDDaraType = 3; break;
										case 3 : $FkIDDaraType = 5; break;
										case 4 : $FkIDDaraType = 7; break;
										default : $FkIDDaraType = 9; break;
								}
								switch($Sex){	
										case 1 : $Sex = 'm'; break;
										default : $Sex = 'f'; break;
								}

								list($first_name, $last_name) = explode(" ", $Fullname);

								$data[$i]['iddara'] = $IDDara;
								$data[$i]['dara_type_id'] = $FkIDDaraType;
								$data[$i]['first_name'] = $first_name;
								$data[$i]['last_name'] = $last_name;
								$data[$i]['nick_name'] = $nickname;
								$data[$i]['gender'] = $Sex;
								//$data[$i]['birth_date'] = $BirthDay;
								$data[$i]['birth_place'] = $Province;
								$data[$i]['weight'] = $Weight;
								$data[$i]['height'] = $Tall;
								//$data[$i]['education'] = $Education;
								$data[$i]['hobby'] = $Hobby;
								$data[$i]['profile_background'] = $Profile;
								$data[$i]['portfolio'] = $Performance;
								$data[$i]['last_portfolio'] = $LastPerformance;

								$data[$i]['avatar'] = $Pic0;

								$data[$i]['lang'] = 'en';
								//$data[$i]['order_by'] = $i+1;

								//Debug($data[$i]);

								//echo '<img src="'.$picture.'" border="0" alt=""><br>';
								//echo '<img src="'.$picture1.'" border="0" alt=""><br>';
								//echo '<img src="'.$picture2.'" border="0" alt=""><br>';
								//echo '<img src="'.$picture3.'" border="0" alt=""><br>';
								//echo "<hr>";
					}
					//Debug($data);

					if($insert == true)
						if($this->Dara_model->import_sstv_to_db($data)){
								echo "$i record Import Success.";
						}
				}
			}

	}

	public function sentmail(){
			
			$idsent = $this->input->post('id');
			echo 'Sentmail. '.$idsent ;
	}

	public function auto_tags(){
			
			$this->Dara_model->auto_tags();
	}
	
}
