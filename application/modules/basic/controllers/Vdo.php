<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vdo extends CI_Controller {

    public function __construct()    {
        parent::__construct();
		 $this->load->model('Vdo_model');
		 $breadcrumb = array();

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }

	public function index(){

			$this->load->model('Category_model');
			$this->load->model('Subcategory_model');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$notification_birthday = $this->Api_model->notification_birthday();
			$breadcrumb[] = $language['vdo'];

			$category_id = 5;
			$subcategory_id = 0;

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					//Debug($search_form);

					if(isset($search_form['subcategory_id']))  $subcategory_id = $search_form['subcategory_id'];

					if($subcategory_id > 0)
							$subcategory_list = $this->Subcategory_model->getSelectSubcat($category_id, $subcategory_id, 'subcategory_id');
					else
							$subcategory_list = $this->Subcategory_model->getSelectSubcat($category_id, 0, 'subcategory_id');

					if(isset($search_form['keyword'])){
						//$keyword = trim(str_replace(" ", "-", $search_form['keyword']));
						$keyword = trim($search_form['keyword']);

						$vdo_list = $this->Vdo_model->get_data(null, $keyword, $subcategory_id);
					}else{
						$keyword = "Tmon";
						$vdo_list = $this->Vdo_model->get_data(null, null, $subcategory_id);
					}
					//if(isset($search_form['category_id']))  $category_id = $search_form['category_id'];
					//$category_list = $this->Category_model->getSelectCat($cat_view['category_id'], 'category_id', 'Clip');
			}else{
					$keyword = "Tmon";
					$vdo_list = $this->Vdo_model->get_data();
					//$category_list = $this->Category_model->getSelectCat(0, 'subcategory_id', 'Clip');
					$subcategory_list = $this->Subcategory_model->getSelectSubcat($category_id, 0, 'subcategory_id');
			}

			//Debug($vdo_list);
			//die();\
			//$vdo_type_list = $this->vdo_type_model->getSelectCat($subcategory_id, $language['all']);
			$vdo_count = $this->Vdo_model->countvdo($subcategory_id);

			$data = array(
					"ListSelect" => $ListSelect,
					"vdo_list" => $vdo_list,
					"vdo_count" => $vdo_count,
					"category_id" => $category_id,
					"subcategory_id" => $subcategory_id,
					"subcategory_list" => $subcategory_list,
					"notification_birthday" => $notification_birthday,
					"content_view" => 'vdo/list',
					"breadcrumb" => $breadcrumb
			);
			$this->load->view('template/template',$data);
	}

	public function add(){

			 $this->load->model('Dara_model');
			 $this->load->model('Tags_model');
			 $this->load->model('Credit_model');
			 $this->load->model('Category_model');
			 $this->load->model('Subcategory_model');
			 $this->load->helper('ckeditor');

			$Path_CKfinder = base_url('plugins/ckeditor-integrated/ckfinder');
			$Path_CKeditor = base_url('plugins/ckeditor-integrated/ckeditor');

			$tags_list = array();
			$category_id = 5;

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$notification_birthday = $this->Api_model->notification_birthday();

			$breadcrumb[] = '<a href="'.base_url('vdo').'">'.$language['vdo'].'</a>';
			$breadcrumb[] = $language['add'];

			$dara_list = $this->Dara_model->get_dara_profile();
			$credit_list = $this->Credit_model->get_data();
			$tags_list = $this->Tags_model->getSelect();
			$subcategory_list = $this->Subcategory_model->getSelectSubcat($category_id, 0, 'subcategory_id');

			//$vdo_list = $this->Vdo_model->get_data($id);
			//Debug($vdo_list);
			$data = array(
					"ListSelect" => $ListSelect,
					"dara_list" => $dara_list,
					"credit_list" => $credit_list,
					"tags_list" => $tags_list,
					"category_id" => $category_id,
					"subcategory_list" => $subcategory_list,
					"notification_birthday" => $notification_birthday,
					"content_view" => 'vdo/add',
					"breadcrumb" => $breadcrumb
			);

			$data['detail_en'] = array(
				'id'     =>     'detail_en',
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

			$data['detail_th'] = $data['detail_en'];
			$data['detail_th']['id'] = 'detail_th';

			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){

			 $this->load->model('Dara_model');
			 //$this->load->model('Picture_model');
			 $this->load->model('Credit_model');
			 $this->load->model('Tags_model');
			 $this->load->helper('ckeditor');
			$this->load->model('Category_model');
			$this->load->model('Subcategory_model');

			 $tags_list = array();
			 $category_id = 5;

			$Path_CKfinder = base_url('plugins/ckeditor-integrated/ckfinder');
			$Path_CKeditor = base_url('plugins/ckeditor-integrated/ckeditor');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$notification_birthday = $this->Api_model->notification_birthday();

			$breadcrumb[] = '<a href="'.base_url('vdo').'">'.$language['vdo'].'</a>';
			$breadcrumb[] = $language['edit'];

			$dara_list = $this->Dara_model->get_dara_profile();
			//$picture_list = $this->Picture_model->get_picture_by_ref_id($id, 4);
			$credit_list = $this->Credit_model->get_data();

			$sel_tags = $this->Tags_model->get_tag_pair($id, 4);
			//Debug($sel_tags);

			if(count($sel_tags) > 0){
				/*for($i=0;$i<count($sel_tags);$i++){
					@$tag_id[$i]->value = $sel_tags[$i]->tag_id;
				}*/
				$tag_id = $sel_tags;
				//Debug($tag_id);
				$tags_list = $this->Tags_model->getSelect($tag_id);
				//echo "1";
			}else{
				$tags_list = $this->Tags_model->getSelect();
				//echo "2";
			}
			//die();

			$datalog = array(
					"ref_type" => 4,
					"ref_id" => $id
			);
			$view_log = $this->Admin_log_activity_model->view_log(0, $datalog);
			$vdo_list = $this->Vdo_model->get_data($id);
			$subcategory_list = $this->Subcategory_model->getSelectSubcat($category_id, $vdo_list[0]->subcategory_id);

			$get_relate = $this->Vdo_model->get_relate($id);

			$data = array(
					"ListSelect" => $ListSelect,
					"vdo_list" => $vdo_list,
					"dara_list" => $dara_list,
					"credit_list" => $credit_list,
					"tags_list" => $tags_list,
					"get_relate" => $get_relate,
					"category_id" => $category_id,
					"subcategory_list" => $subcategory_list,
					"view_log" => $view_log,
					"notification_birthday" => $notification_birthday,
					"content_view" => 'vdo/edit',
					"breadcrumb" => $breadcrumb
			);

			$data['detail_en'] = array(
				'id'     =>     'detail_en',
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

			$data['detail_th'] = $data['detail_en'];
			$data['detail_th']['id'] = 'detail_th';

			$this->load->view('template/template',$data);
	}

	public function save(){

			 $this->load->model('Tags_model');

			$tag_list = $post_data = $new_data = $tag_id = $tag_pair = $new_data_th = $tag_dara = $log_activity = array();
			$vdo_id = 0;
			$now_date = date('Y-m-d H:i:s');
			$folder = date('Ymd');
			$tag_list = array();

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$data_input = $this->input->post();
					//Debug($data_input);
					//die();
					//******************* List data input **********************/
					if($data_input)
							foreach($data_input as $key => $val){
									if($key == "vdo_id_en"){
											$new_data['video_id'] = $data_input['vdo_id_en'];
									}else if($key == "vdo_id_th"){
											$new_data_th['video_id'] = $data_input['vdo_id_th'];
									}else if($key == "vdo_id"){
											$new_data['video_id2'] = $data_input['vdo_id'];
											$new_data_th['video_id2'] = $data_input['vdo_id']; 

									}else if( $key != "tag_id" && $key != "orderid" && $key != "'relate_id" && $key != "'highlight" && $key != "'megamenu"){
											
											if($key == "title_en" || $key == "description_th" || $key == "detail_en"){

													if($key == "title_en"){  
														$title = $new_data['title_name'] = StripTxt($data_input['title_en']); 
														$new_data['lang'] = "en"; 
													}
													if($key == "description_en")  $new_data['description'] = $data_input['description_en'];
													if($key == "detail_en")  $new_data['detail'] = $data_input['detail_en'];

											}else if($key == "title_th" || $key == "description_th" || $key == "detail_th"){

													if($key == "title_th"){
														$new_data_th['title_name'] = StripTxt($data_input['title_th']);
														$new_data_th['lang'] = "th"; 
													}
													if($key == "description_th")  $new_data_th['description'] = $data_input['description_th'];
													if($key == "detail_th")  $new_data_th['detail'] = $data_input['detail_th'];

											}else{
													$new_data[$key] = $val;
													$new_data_th[$key] = $val;
											}
									}

									if($key == "tag_id"){
											foreach($val as $key2 => $val2){			
													$tag_list[] = $val2;
											}
									}

							}
					//Debug($tag_list);
					//die();

					if(isset($new_data['ref_url'])){

						$new_data['ref_url'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $new_data['ref_url']);
						$new_data_th['ref_url'] = $new_data['ref_url'];

					}else{
							echo "Check Relate.";

							/**************************************** Relate**************************/
							$number_of_relate = 4;
							$tag_relate = $this->Vdo_model->get_relate($new_data['video_id2']); //Get relate มาดู ว่ามีกี่อัน
							//Debug($tag_relate);
							//echo "(".$new_data['video_id2']." > 0) && (".$new_data['dara_id']." > 0)";

							if($new_data['video_id2'] > 0){
									//if(count($tag_relate)<$number_of_relate){
									if(count($tag_relate) == 0){

											$t=0;
											$where_id = array();
											//echo "<br>$number_of_relate<br>";

											if($new_data['dara_id'] > 0)
												$tag_relate = $this->Vdo_model->gen_relate($new_data['dara_id'], $tag_list, $new_data['video_id2'], $number_of_relate);
											else
												$tag_relate = $this->Vdo_model->gen_relate(null, $tag_list, $new_data['video_id2'], $number_of_relate);

											$max5 = (count($tag_relate) > $number_of_relate) ? $number_of_relate : count($tag_relate);
											
											//Debug($tag_relate);
											//echo "max5 = $max5";

											for($t=0;$t<$max5;$t++){
														$number = $t+1;
														if(intval($tag_relate[$t]) != $new_data['video_id']){
															$where_id[$t]['vdo_id'] = intval($tag_relate[$t]);
															$where_id[$t]['ref_id'] = intval($new_data['video_id']);
															//$where_id[$t]['ref_type'] = 4; //VDO Clip
															$where_id[$t]['order'] = $number;
														}
											}

											//Debug("<hr>video_id2=".$new_data['video_id2']);
											//Debug($where_id);
											//die();
											if(count($where_id) > 0) $this->Vdo_model->save_relate($new_data['video_id2'], $where_id);
									}
							}
							//die();
					}

					unset($new_data['record']);
					unset($new_data_th['record']);

					unset($new_data['highlight']);
					unset($new_data_th['highlight']);

					if($data_input['record'] == 1){
						unset($new_data_th);
					}else unset($new_data_th['megamenu']);

					unset($new_data['megamenu']);
					
					if($new_data['video_id'] == 0){


							/*if(($new_data['subcategory_id'] == 11) || ($new_data['subcategory_id'] == 15))
									$post_data['idtvp'] = 98;  //98 สยามดารา คลิปร้อน!!
							else
									$post_data['idtvp'] = 64; //ตัวแปรช่องทีวี 64 สยามดารา คลิปเซ็กซี่!! 

							$post_data['title'] = $new_data['title_name']; */
							//Debug($post_data);
							//unset($post_data['idtvp']);

							//$url = 'http://clip.Tmon.com/genXML/api/insert_clip.php';
					
							$action = 1;
							$new_data['create_by'] = $this->session->userdata('admin_id');
							$new_data_th['create_by'] = $this->session->userdata('admin_id');

							$new_data['create_date'] = $now_date;
							$new_data_th['create_date'] = $now_date;
							$new_data['order_by'] = 1;
							$new_data_th['order_by'] = 1;
							
							$max_id = $this->Vdo_model->get_max_id();
							$video_id = $max_id[0]->max_id + 1;
							$new_data['video_id2'] = $video_id;
							$new_data_th['video_id2'] = $video_id;

							$this->Vdo_model->update_orderplus();
							$this->Vdo_model->store($new_data['video_id'], $new_data);
							$this->Vdo_model->store($new_data_th['video_id'], $new_data_th);

			}else{
					
							$action = 2;
							$new_data['lastupdate_by'] = $this->session->userdata('admin_id');
							$new_data['lastupdate_date'] = $now_date;

							if($data_input['record'] == 2){
									$new_data_th['lastupdate_by'] = $this->session->userdata('admin_id');
									$new_data_th['lastupdate_date'] = $now_date;							
							}
							$video_id = $new_data['video_id2'];
							//Debug($new_data);
							//Debug($new_data_th);

							$this->Vdo_model->store($new_data['video_id'], $new_data);
							if($data_input['record'] == 2) $this->Vdo_model->store($new_data_th['video_id'], $new_data_th);
			}

			/***************************tags*************************************/
			//Debug($data_input);
			//echo "count = ".count($tag_id);
			if(count($tag_list) < 3){
					$get_tag_dara = $this->Tags_model->get_tag_pair($new_data['dara_id'], 5);
					//Debug($get_tag_dara);
					$clear = 1;
					if($get_tag_dara){
							for($i=0;$i<count($get_tag_dara);$i++){
										$tag_pair[$i]["tag_id"] = $get_tag_dara[$i]->tag_id;
										$tag_pair[$i]["ref_id"] = $video_id;
										$tag_pair[$i]["ref_type"] = 4;
										$tag_pair[$i]["create_date"] = $now_date;
										$next = $i;
							}
					}			
					//Insert tags
					//Debug($tag_list);
					if($tag_list){
							if(!isset($next)) $next = 0;
							$begin = $next + 1;
							$endtag = count($tag_list) + $begin;
							$t = 0;
							for($i=$begin;$i<$endtag;$i++){
										
										$tag_pair[$i]["tag_id"] = $tag_list[$t];
										$tag_pair[$i]["ref_id"] = $video_id;
										$tag_pair[$i]["ref_type"] = 4;
										$tag_pair[$i]["create_date"] = $now_date;
										$t++;
							}
							//Debug($tag_pair);					
							$this->Tags_model->store_tag_pair($tag_pair, $clear);
					}else{
						echo "no tag";
					}
			}
			//die();

					//**************Set Highlight
					if(isset($data_input['highlight'])){

						//Debug($data_input['highlight']);
						$data_highlight = array(
								"news_id" => $video_id,
								"ref_type" => 4,
								"order" => 0
						);							
						$this->Vdo_model->set_highlight($video_id, $data_highlight);
						$this->Vdo_model->update_order_highlight();
					}else{
						$this->Vdo_model->remove_highlight($video_id);
					}

					//**************Set Megamenu
					if(isset($data_input['megamenu'])){
						$data_megamenu = array(
								"id" => $video_id,
								"category_id" => 0,
								"ref_type" => 4,
								"order" => 0
						);							
						$this->Vdo_model->set_megamenu($video_id, $data_megamenu);
					}else{
						$this->Vdo_model->remove_megamenu($video_id);
					}

					//**************Log activity
					$log_activity = array(
							"admin_id" => $this->session->userdata('admin_id'),
							"ref_id" => $video_id,
							"ref_type" => 4,
							"ref_title" => $title,
							"action" => $action
					);
					$this->Admin_log_activity_model->store($log_activity);
					//**************Log activity
			}
			$success =  'Save complete.';
			redirect(base_url('vdo/edit/'.$video_id.'?success='.urlencode($success)));

	}

	public function status($id = 0){
				
			if($id == 0){
				$data = array(
						"error" => 'id error'
				);
				return false;
			}else{

					$obj_status = $this->Vdo_model->get_status($id);
					$cur_status = $obj_status[0]['status'];
					$title = $obj_status[0]['title_name'];
					$cur_status = ($cur_status == 0) ? 1 : 0;
					$obj_data['status'] = $cur_status;
					if($this->Vdo_model->store2($id, $obj_data)) echo $cur_status;
					//echo "update succedd.";
			}
			//**************Log activity
			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $id,
					"ref_type" => 4,
					"ref_title" => "[Status]".$title,
					"action" => 2
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity
	}

	public function approve($id = 0){

			if($id == 0){
				$data = array(
						"error" => 'id error'
				);
				return false;
			}else{

					$obj_status = $this->Vdo_model->get_status($id);
					$cur_status = $obj_status[0]['approve'];
					$title = $obj_status[0]['title_name'];
					$cur_status = ($cur_status == 0) ? 1 : 0;
					$obj_data['approve'] = $cur_status;
					if($this->Vdo_model->store2($id, $obj_data)) echo $cur_status;
						//echo "update succedd.";

					//$this->gen_json($id);
			}
			//**************Log activity
			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $id,
					"ref_type" => 4,
					"ref_title" => "[Approve]".$title,
					"action" => 2
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity
	}

	public function picture($id = 0){
			
			if($id == 0){
				redirect('vdo');
				die();
			}

			 $this->load->model('Api_model');
			 $this->load->model('Picture_model');

			$tag_id = array();
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('vdo').'">'.$language['vdo'].'</a>';
			$breadcrumb[] = $language['picture'];

			$picture_list = $this->Api_model->get_picture($id, 0, 4);

			$vdo_list = $this->Vdo_model->get_data($id);
			
			//$picture_list = json_decode("http://elvis.siamsport.co.th/services/search?q=autosalon%20AND%20extension:jpg&start=0&num=10&sort=name&metadataToReturn=all&format=json&facet.assetDomain.selection=image");

			//$picture_list = LoadJSON("search?q=autosalon%20AND%20extension:jpg&start=0&num=10&sort=name&metadataToReturn=all&format=json&facet.assetDomain.selection=image", "http://elvis.siamsport.co.th/services");

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"vdo_list" => $vdo_list,
					"picture_list" => $picture_list,
					"content_view" => 'vdo/picture',
					"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function picture_edit($id = 0){
						
			 $this->load->model('Api_model');
			 $this->load->model('Picture_model');

			 $typeOfPic = 4 ;
			 $orientation = 1;

			 $getdata = $this->input->get();
			 //Debug($getdata);
			if($getdata){
				foreach($getdata as $key => $val){
						if($key == "picture_id") $picture_id = $val;
						//if($key == "Orientation") $orientation = $val;
				}
				$vdoid = $id;
			}else{
				 $vdoid = $this->uri->segment(3);
				 $picture_id = $this->uri->segment(4);
			}

			 //Debug($picid);
			/*if($getdata)
				foreach($getdata as $key => $val){
						//echo "<br>".$news_id." : ".$val;
						if($key == "news_id") $news_id = $val;
						if($key == "Orientation") $orientation = $val;
				}*/

			$tag_id = array();
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('vdo').'">'.$language['vdo'].'</a>';
			$breadcrumb[] = '<a href="'.base_url('vdo/edit/'.$vdoid).'">'.$language['edit'].'</a>';
			$breadcrumb[] = $language['picture'];

			$picture_list = $this->Api_model->get_picture($vdoid, $picture_id, $typeOfPic);
			$vdo_item = $this->Vdo_model->get_data($vdoid);

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"vdoid" => $vdoid,
					"vdo_item" => $vdo_item[0],
					"orientation" => $orientation,
					"picture_list" => $picture_list,
					"content_view" => 'vdo/picture_edit',
					"breadcrumb" => $breadcrumb
			);
			$this->load->view('template/template',$data);
	}


	public function picture_del($pic_id = 0){

			$this->load->model('Picture_model');

			$type = 4; //VDO

			$ref_id = $this->input->get();
			if($ref_id)
				foreach($ref_id as $vdo_id => $val){
						//echo $vdo_id;
				}

			//Debug($ref_id);
			//echo "pic_id= $pic_id<br>";
			//echo "vdo_id= $vdo_id<br>";

			if($pic_id > 0 && $vdo_id > 0){
					$picture_list = $this->Api_model->get_picture($vdo_id, $pic_id, $type);
					//Debug($picture_list);
					//die();

					//$tmp = 'uploads/tmp/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					//if(file_exists($tmp)) unlink($tmp);

					$tmp = 'uploads/vdo/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					if(file_exists($tmp)) unlink($tmp);

					$tmp = 'uploads/thumb/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					if(file_exists($tmp)) unlink($tmp);

					$tmp = 'uploads/thumb2/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					if(file_exists($tmp)) unlink($tmp);

					$tmp = 'uploads/thumb3/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					if(file_exists($tmp)) unlink($tmp);

					$tmp = 'uploads/headnews/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					if(file_exists($tmp)) unlink($tmp);

					$tmp = 'uploads/highlight/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					if(file_exists($tmp)) unlink($tmp);

					$tmp = 'uploads/menu/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					if(file_exists($tmp)) unlink($tmp);

					$this->Picture_model->delete_picture_admin($pic_id, 0, $type);
			}
			redirect('vdo/picture/'.$vdo_id);
	}

	public function load_json_SSTV(){
			
			$data_en = $data_th = array();
			$insert = true;
			$display = false;

			if($this->input->get('number'))
				$number = $this->input->get('number');
			else
				$number = 20;
			//Debug($this->input->get());

			$video_id = 0;
			$category_id = 5;
			$subcategory_id = 11;

			$vdo_list = $this->Vdo_model->load_sstv(1, $number);

			if($display == true) Debug($vdo_list);

			if(isset($vdo_list)){
				if($vdo_list['header']['code'] == 200){
					$allvdo = count($vdo_list['body']);
					//echo "ALL = $allvdo <br>";
					
					for($i=0;$i<$allvdo;$i++){

								$sstv_id = $vdo_list['body'][$i]['id'];
								$title_name = StripTxt($vdo_list['body'][$i]['topic']);
								$create_date = $vdo_list['body'][$i]['datecreate'];
								$countclick = $vdo_list['body'][$i]['countclick'];
								$countpost = $vdo_list['body'][$i]['countpost'];
								$originalpic = $vdo_list['body'][$i]['originalpic'];
								$thumpic = $vdo_list['body'][$i]['thumpic'];
								$ref_url = $vdo_list['body'][$i]['url'];
								$embed = $vdo_list['body'][$i]['urlclip'];

								$video_id++;
								$data_en[$i]['video_id'] = $video_id;
								$data_en[$i]['video_id2'] = $video_id;
								$data_en[$i]['category_id'] = $category_id;
								$data_en[$i]['subcategory_id'] = $subcategory_id;

								$data_en[$i]['sstv_id'] = $sstv_id;
								$data_en[$i]['title_name'] = $title_name;
								$data_en[$i]['create_date'] = $create_date;
								$data_en[$i]['countclick'] = $countclick;

								$data_en[$i]['originalpic'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $originalpic);
								$data_en[$i]['thumpic'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $thumpic);
								$data_en[$i]['ref_url'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $ref_url);
								$data_en[$i]['embed'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $embed);
								$data_en[$i]['lang'] = 'en';
								$data_en[$i]['order_by'] = $i+1;

								$video_id++;
								$data_th[$i]['video_id'] = $video_id;
								$data_th[$i]['video_id2'] = $data_en[$i]['video_id'];
								$data_th[$i]['category_id'] = $category_id;
								$data_th[$i]['subcategory_id'] = $subcategory_id;

								$data_th[$i]['sstv_id'] = $sstv_id;
								$data_th[$i]['title_name'] = $title_name;
								$data_th[$i]['create_date'] = $create_date;
								$data_th[$i]['countclick'] = $countclick;

								$data_th[$i]['originalpic'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $originalpic);
								$data_th[$i]['thumpic'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $thumpic);
								$data_th[$i]['ref_url'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $ref_url);
								$data_th[$i]['embed'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $embed);
								$data_th[$i]['lang'] = 'th';
								$data_th[$i]['order_by'] = $i+1;
					}

					if($insert == true){
							if($this->Vdo_model->import_sstv_to_db($data_en)){
									echo "<br>Import vdo en $allvdo Success.";
							}
							if($this->Vdo_model->import_sstv_to_db($data_th)){
									echo "<br>Import vdo th $allvdo Success.";
							}
					}
				}
			}
	}
	
	public function chk_sstv(){

			$data_en = $data_th = array();

			//$insert = true;
			//$display = true;
			$insert = true;
			$display = true;

			if($this->input->get('number'))
				$number = $this->input->get('number');
			else
				$number = 20;
			//Debug($this->input->get());
			$vdo_list = $this->Vdo_model->load_sstv(1, $number);

			//if($display == true) Debug($vdo_list);
			//die();
			$video_id = $number = 0;
			$category_id = 5;
			$subcategory_id = 11;

			if(count($vdo_list) > 0){
				if($vdo_list['header']['code'] == 200){
					$allvdo = count($vdo_list['body']);
					for($i=0;$i<$allvdo;$i++){

								$sstv_id = $vdo_list['body'][$i]['id'];
								$title_name = StripTxt($vdo_list['body'][$i]['topic']);
								$create_date = $vdo_list['body'][$i]['datecreate'];
								$countclick = $vdo_list['body'][$i]['countclick'];
								$countpost = $vdo_list['body'][$i]['countpost'];
								$originalpic = $vdo_list['body'][$i]['originalpic'];
								$thumpic = $vdo_list['body'][$i]['thumpic'];
								$ref_url = $vdo_list['body'][$i]['url'];
								$embed = $vdo_list['body'][$i]['urlclip'];
								
								//echo "check sstv_id = $sstv_id <br>";
								$obj_status = $this->Vdo_model->get_sstvid($sstv_id);
								if(isset($obj_status) && @$obj_status[0]['title_name'] != ""){
										//Debug($obj_status);
								}else{

										$max_id = $this->Vdo_model->get_max_id();
										if($insert == true){
											$this->Vdo_model->allorderplus();
										}
										//$number++;
										$number = 1;
										unset($data_en);
										unset($data_th);

										$video_id2 = $max_id[0]->max_id;
										$video_id2++;
										$video_id = $video_id2;
										//Debug($vdo_list['body'][$i]);
										//INSERT NEW FROM SSTV

										$data_en[0]['video_id'] = $video_id;
										$data_en[0]['video_id2'] = $video_id2;
										$data_en[0]['category_id'] = $category_id;
										$data_en[0]['subcategory_id'] = $subcategory_id;

										$data_en[0]['sstv_id'] = $sstv_id;
										$data_en[0]['title_name'] = $title_name;
										$data_en[0]['create_date'] = $create_date;
										$data_en[0]['countclick'] = $countclick;

										$data_en[0]['originalpic'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $originalpic);
										$data_en[0]['thumpic'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $thumpic);
										$data_en[0]['ref_url'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $ref_url);
										$data_en[0]['embed'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $embed);
										$data_en[0]['lang'] = 'en';
										$data_en[0]['order_by'] = $number;
										if($insert == true){
											$this->Vdo_model->import_sstv_to_db($data_en);
										}
										Debug($data_en[0]);

										$video_id++;
										$data_th[0]['video_id'] = $video_id;
										$data_th[0]['video_id2'] = $video_id2;
										$data_th[0]['category_id'] = $category_id;
										$data_th[0]['subcategory_id'] = $subcategory_id;

										$data_th[0]['sstv_id'] = $sstv_id;
										$data_th[0]['title_name'] = $title_name;
										$data_th[0]['create_date'] = $create_date;
										$data_th[0]['countclick'] = $countclick;

										$data_th[0]['originalpic'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $originalpic);
										$data_th[0]['thumpic'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $thumpic);
										$data_th[0]['ref_url'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $ref_url);
										$data_th[0]['embed'] = str_replace("http://sstv.siamsport.co.th", "http://clip.Tmon.com", $embed);
										$data_th[0]['lang'] = 'th';
										$data_th[0]['order_by'] = $number;
										if($insert == true){
											$this->Vdo_model->import_sstv_to_db($data_th);
										}
										Debug($data_th[0]);

								}

					}

					if($number == 1) echo "<br>Success."; else echo "<br>This last updated."; 
					/*if($insert == true){
							if($this->Vdo_model->import_sstv_to_db($data_en)){
									echo "<br>Import vdo en $allvdo Success.";
							}
							if($this->Vdo_model->import_sstv_to_db($data_th)){
									echo "<br>Import vdo th $allvdo Success.";
							}
					}*/
				}
			}
	}

	/***********************************Relate*********************************/
	public function set_order_relate(){
					$lang = $this->lang->language;
					$update_data = array();
					
					$videoid = $this->input->post('videoid');
					$json = $this->input->post('json');
					$obj = json_decode($json);

					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj);$i++){
								//echo $num.'.) '.$obj[$i]->id.' : '.$newsid.' ==> '.$num.'<br>';
								unset($update_data);
								$update_data['order'] = $num;
								$this->Vdo_model->set_order_relate($videoid, $obj[$i]->id, $update_data);
								$num++;
						}
						
					$vdo = $this->Vdo_model->get_status($videoid);
					$action = 2;
					$log_activity = array(
							"admin_id" => $this->session->userdata('admin_id'),
							"ref_id" => $videoid,
							"ref_type" => 3,
							"ref_title" => "Set Order Gallery relate: ".$vdo[0]['title_name'],
							"action" => $action
					);			
					$this->Admin_log_activity_model->store($log_activity);

					echo $lang['update'].' '.$lang['success'].'.';
	}	

	public function delete_relate($video_id = 0){

					$lang = $this->lang->language;
					$update_data = array();
					
					$ref_id = $this->input->get('ref_id');
					$picture_id = $this->input->get('picture_id');

					/*echo "video_id = $video_id<br>";
					Debug($this->input->get());
					die();*/

					if($picture_id > 0) $this->Vdo_model->delete_relate($picture_id);
					if($ref_id > 0) $this->Vdo_model->delete_relate(0, $ref_id);
						
					$vdo = $this->Vdo_model->get_status($video_id);
					$action = 3;
					$log_activity = array(
							"admin_id" => $this->session->userdata('admin_id'),
							"ref_id" => $video_id,
							"ref_type" => 3,
							"ref_title" => "Delete relate: ".$vdo[0]['title_name'],
							"action" => $action
					);			
					$this->Admin_log_activity_model->store($log_activity);

					redirect('vdo/edit/'.$video_id.'?success='.$lang['update'].' '.$lang['success']);
	}	


	public function search_relate(){
			
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			if($this->input->server('REQUEST_METHOD') === 'GET'){

					$search_form = $this->input->get();
					if(isset($search_form['kw'])){
							
						$vdoid = $search_form['vdoid'];
						$data_list = $this->Vdo_model->get_data(null, $search_form['kw']);
						//Debug($data_list);
						//die();
						if($data_list){
								echo '<table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th>ID</th>
													<th>Pic</th>
													<th>Title</th>
													<th>'.$language['create_date'].'</th>
													<th>'.$language['status'].'</th>
													<th>Count view</th>													
													<th width = "10%">Action</th>
												</tr>
											</thead>
											<tbody>';

								$maxlist = count($data_list);
								for($i=0;$i<$maxlist;$i++){

										//$url = "http://www.Tmon.com/news/".urlencode($news_list[$i]['title']).".html";
										//$url = "http://www.Tmon.com/news/".$news_list[$i]['news_id2'].".html";

										$img = base_url('uploads/thumb').'/'.$data_list[$i]->folder.'/'.$data_list[$i]->file_name;

										if($data_list[$i]->file_name != "")
											$tags_img = (file_exists('uploads/thumb/'.$data_list[$i]->folder.'/'.$data_list[$i]->file_name)) ? "<img src=".$img." height='50'>" : "";
										else
											$tags_img = "";

										//$img = $data_list[$i]->thumpic;
										//$tags_img = "<img src=".$img." height='50'>";

										$status = ($data_list[$i]->status == 1) ? 'checked' : '';
										$iconstatus = '<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-3" type="checkbox" '.$status.' disabled>
														<span class="lbl"></span>
													</label>
												</div>';
										//$addurl = 'javascript:void(0);';
										$addurl = base_url('vdo/add_relate').'?video_id='.$data_list[$i]->video_id2.'&ref_id='.$vdoid;
										$edit_data = base_url('vdo/edit/'.$data_list[$i]->video_id2);

										$iconadd = '<a href="'.$addurl.'" data-value="'.$data_list[$i]->video_id2.'" class="add_relate"><i class="ace-icon glyphicon glyphicon-plus">Add relate</i></a>';

										echo "<tr>
										<td>".$data_list[$i]->video_id2."</td>
										<td>".$tags_img."</td>
										<td><a href='".$edit_data."' target=_blank>".$data_list[$i]->title_name."</a></td>
										<td>".$data_list[$i]->create_date."</td>
										<td>".$iconstatus."</td>
										<td>".$data_list[$i]->countclick."</td>
										<td>".$iconadd."</td></tr>";
								}						
								echo "</tbody></table>";
						}else
								echo "ไม่มีข้อมูล";
					}
					//die();
			}

	}

	public function add_relate(){
			
			$data_input = $this->input->get();
			$ref_id = $data_input['ref_id'];
			$video_id = $data_input['video_id'];
			//Debug($data_input);
			//Debug("add_relate($ref_id, $video_id)");
			//die();
			$this->Vdo_model->add_relate($ref_id, $video_id);
			echo '<script type="text/javascript">
			<!--
				window.location="'.base_url("vdo/edit").'/'.$ref_id.'";
			//-->
			</script>';
	}

}