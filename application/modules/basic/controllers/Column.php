<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Column extends CI_Controller {

    public function __construct()    {
        parent::__construct();
		 $this->load->model('Column_model');
		 $breadcrumb = array();

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }

	public function index(){
			
			$this->load->model('Category_model');
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$notification_birthday = $this->Api_model->notification_birthday();

			$datainput = $this->input->get();

			$start_page = 0;
			$p = 1;
			$list_page = 10;
			$listspage = '';

			//Debug($datainput);
			if(isset($datainput['p'])){
				$p = ($datainput['p'] > 0) ? $datainput['p'] : 1;
				if($p > 1){
						$start_page = ($p-1)*$list_page;
				}
			}else{
				$p = 1;
			}

			$opt[]	= makeOption(10, 10);
			$opt[]	= makeOption(20, 20);
			$opt[]	= makeOption(50, 50);
			$opt[]	= makeOption(100, 100);

			$breadcrumb[] = $language['column'];
			$category_id = 0;
			$subcategory_id = 0;

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					//Debug($search_form);
					//die();
					if(isset($search_form['category_id'])){ 
						$category_id = $search_form['category_id'];

						if(isset($search_form['subcategory_id'])){ 
							$subcategory_id = $search_form['subcategory_id']; 
						}else $subcategory_id = 0;

					}else{
						unset($search_form['subcategory_id']);
						$category_id = 0;
					}

					if(isset($search_form['list_page'])) $list_page = ($search_form['list_page'] > 0) ? $search_form['list_page'] : 10;

					$listspage = selectList( $opt, 'list_page', 'class="form-control"', 'value', 'text', $list_page);

					if(isset($search_form['selectid'])){

							$selectid = $search_form['selectid'];
							$order = $search_form['order'];
							$search_form['category_id'] = $category = $search_form['category'];
							$maxsel = count($selectid);
							$toup = $tmp = 0;

							for($i=0;$i<$maxsel;$i++){

									if($tmp > $order[$i]){
											//Update ID ด้านหน้า + 1
											$this->Column_model->update_orderid_to_down($order[$i], $tmp);
									}

									if((($tmp + 1) <> $order[$i]) && ($toup == 0)){
											//Update ID ด้านหลัง - 1
											$toup = $tmp + 1;
											$this->Column_model->update_orderid_to_up($order[$i], $toup);
											
									}

									//Update Cur ID
									$this->Column_model->update_order($selectid[$i], $order[$i]);
									//Debug($this->db->last_query());

									if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
							}
							$category_id = $category;
							$subcategory_id = 0;
					}

					if(isset($search_form['keyword'])){
						$keyword = trim($search_form['keyword']);
						$search_news = explode(" " ,$search_form['keyword']);

						$column_list = $this->Column_model->get_column(null, $search_news, $language['lang'], '_column.order_by', 'asc', 0, 100);
						//Debug($column_list);
						//die();
						//echo "1<br>";
					}else{
						$keyword = "Tmon";
						if(isset($search_form['category_id'])) $search_cat['category_id'] = $search_form['category_id'];
						if(isset($search_form['subcategory_id']) && $search_form['subcategory_id'] > 0) $search_cat['subcategory_id'] = $search_form['subcategory_id'];
						$column_list = $this->Column_model->get_column(null, $search_cat, $language['lang'], '_column.order_by', 'asc', 0, 100);
						//echo "2<br>";
					}
			}else{
					$keyword = "Tmon";
					$listspage = selectList( $opt, 'list_page', 'class="form-control"', 'value', 'text', $list_page);
					$column_list = $this->Column_model->get_column(null, null, $language['lang'], '_column.order_by', 'asc', 0, 100);
					//echo "3<br>";
			}
			//Debug($this->db->last_query());

			if($category_id > 0)
				$category_list = $this->Category_model->getSelectCat($category_id , 'category_id', 'Column');
			else
				$category_list = $this->Category_model->getSelectCat(0, 'category_id', 'Column');
			if(isset($subcategory_id)) $subcategory_id = 0;


			$totalnews = $this->Column_model->countcolumn($category_id, $subcategory_id);
			//$curpage = base_url('column?view=list');

			$data = array(
					"ListSelect" => $ListSelect,
					"column_list" => $column_list,
					"category_list" => $category_list,
					"category_id" => $category_id,
					"subcategory_id" => $subcategory_id,
					"breadcrumb" => $breadcrumb,
					//"GenPage" => GenPage($curpage, $p, $list_page, $totalnews),
					"notification_birthday" => $notification_birthday,
					"totalnews" => $totalnews,
					"listspage" => $listspage,
					"content_view" => 'column/column'
			);
			$this->load->view('template/template',$data);
	}
	
	public function add(){
			
			 $this->load->model('Category_model');
			 $this->load->model('Dara_model');
			 $this->load->model('Tags_model');
			 $this->load->model('Credit_model');
			 $this->load->model('Columnist_model');
			 $this->load->helper('ckeditor');

			$Path_CKfinder = base_url('plugins/ckeditor-integrated/ckfinder');
			$Path_CKeditor = base_url('plugins/ckeditor-integrated/ckeditor');

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('column').'">'.$language['column'].'</a>';
			$breadcrumb[] = $language['add'];

			$category_list = $this->Category_model->getSelectCat(0, 'category_id', 'Column');
			$dara_list = $this->Dara_model->get_dara_profile();
			$tags_list = $this->Tags_model->getSelect();
			$credit_list = $this->Credit_model->get_data();
			$columnist_list = $this->Columnist_model->get_data();
			
			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"dara_list" => $dara_list,
					"category_list" => $category_list,
					"tags_list" => $tags_list,
					"credit_list" => $credit_list,
					"columnist_list" => $columnist_list,
					"content_view" => 'column/column_add',
					"breadcrumb" => $breadcrumb,
			);

			//Ckeditor's configuration
			$data['description_en'] = array(
				'id'     =>     'description_en',
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

			$data['detail_th'] = $data['detail_en'] = $data['description_th'] = $data['description_en'];

			$data['description_th']['id'] = 'description_th';
			$data['detail_en']['id'] = 'detail_en';
			$data['detail_th']['id'] = 'detail_th';

			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){
						
			$this->load->model('Category_model');			 
			$this->load->model('Subcategory_model');
			$this->load->model('Dara_model');
			$this->load->model('Tags_model');
			$this->load->model('Picture_model');
			$this->load->model('Credit_model');
			$this->load->model('Columnist_model');
			$this->load->helper('ckeditor');

			$Path_CKfinder = base_url('plugins/ckeditor-integrated/ckfinder');
			$Path_CKeditor = base_url('plugins/ckeditor-integrated/ckeditor');

			$tag_id = array();
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('column').'">'.$language['column'].'</a>';
			$breadcrumb[] = $language['edit'];
			
			$column_list =  $this->Column_model->get_column($id);
		
			$category_list = $this->Category_model->getSelectCat($column_list[0]['category_id'], 'category_id', 'Column');
			//debug($column_list);
			//debug($category_list);
			//die();
			
			if($column_list[0]['subcategory_id'] > 0){
				$subcategory_list = $this->Subcategory_model->getSelectSubcat($column_list[0]['category_id'], $column_list[0]['subcategory_id']);
			}else
				$subcategory_list = $this->Subcategory_model->getSelectSubcat($column_list[0]['category_id'], 0);
				//$subcategory_list = '<select class="form-control" id="subcategory_id" name="subcategory_id"></select>';
			
			$sel_tags = $this->Tags_model->get_tag_pair($id, 2);
			//Debug($sel_tags);
			if($sel_tags)
				for($i=0;$i<count($sel_tags);$i++){
					@$tag_id[$i]->value = $sel_tags[$i]->tag_id;
				}

			$tags_list = $this->Tags_model->getSelect($tag_id);
			$dara_list = $this->Dara_model->get_dara_profile();
			$picture_list = $this->Picture_model->get_picture_by_ref_id($id, 2);
			$credit_list = $this->Credit_model->get_data();
			$columnist_list = $this->Columnist_model->get_data();

			$relate_list = $this->Column_model->get_relate($id);
			$relate_columnist_list = $this->Column_model->get_relate_columnist($id);

			//Debug($column_list);
			//Debug($relate_list);
			//Debug($relate_columnist_list);
			//die();

			$datalog = array(
					"ref_type" => 2,
					"ref_id" => $id
			);
			$view_log = $this->Admin_log_activity_model->view_log(0, $datalog);

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"column_list" => $column_list,
					"category_list" => $category_list,
					"subcategory_list" => $subcategory_list,
					"dara_list" => $dara_list,
					"tags_list" => $tags_list,
					"picture_list" => $picture_list,
					"credit_list" => $credit_list,
					"columnist_list" => $columnist_list,
					"relate_list" => $relate_list,
					"relate_columnist_list" => $relate_columnist_list,
					"view_log" => $view_log,
					"content_view" => 'column/column_edit',
					"breadcrumb" => $breadcrumb,
			);

			//Ckeditor's configuration
			$data['description_en'] = array(
				'id'     =>     'description_en',
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

			$data['detail_th'] = $data['detail_en'] = $data['description_th'] = $data['description_en'];

			$data['description_th']['id'] = 'description_th';
			$data['detail_en']['id'] = 'detail_en';
			$data['detail_th']['id'] = 'detail_th';

			$this->load->view('template/template',$data);
	}

	public function picture($id = 0){
			
			if($id == 0){
				redirect('column');
				die();
			}

			 $this->load->model('Api_model');
			 $this->load->model('Picture_model');

			$tag_id = array();
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('column').'">'.$language['column'].'</a>';
			$breadcrumb[] = $language['picture'];

			//$news_list =  $this->News_model->get_news($id);
			$column_list =  $this->Column_model->get_column($id);
			$picture_list = $this->Api_model->get_picture($id, 0, 2);
			
			//$picture_list = json_decode("http://elvis.siamsport.co.th/services/search?q=autosalon%20AND%20extension:jpg&start=0&num=10&sort=name&metadataToReturn=all&format=json&facet.assetDomain.selection=image");

			//$picture_list = LoadJSON("search?q=autosalon%20AND%20extension:jpg&start=0&num=10&sort=name&metadataToReturn=all&format=json&facet.assetDomain.selection=image", "http://elvis.siamsport.co.th/services");

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"column_list" => $column_list,
					"picture_list" => $picture_list,
					"content_view" => 'column/picture',
					"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	function upload_pic(){

			$this->load->model('Picture_model');
			$this->load->library('upload');
			$this->load->library('image_lib');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			$picture_obj = array();

			$files = $_FILES;
			$now_date = date('Y-m-d H:i:s');
			//$folder = date('Ymd');

			$data_input = $this->input->post();

			if($data_input['create_date'] != "") $folder = $data_input['create_date'];

			$picture_list = $this->Api_model->get_picture($data_input['column_id']);
			//Debug($picture_list);

			$cpt = count($_FILES['picture_column']['name']);
			for($i=0; $i<$cpt; $i++){

				$_FILES['userfile']['name']= $files['picture_column']['name'][$i];
				$_FILES['userfile']['type']= $files['picture_column']['type'][$i];
				$_FILES['userfile']['tmp_name']= $files['picture_column']['tmp_name'][$i];
				$_FILES['userfile']['error']= $files['picture_column']['error'][$i];
				$_FILES['userfile']['size']= $files['picture_column']['size'][$i];    
				//Debug($_FILES['userfile']);

				if($_FILES['userfile']['error'] == 0){

						//Upload File
						$this->upload->initialize($this->set_upload_options($folder));
						$this->upload->do_upload();

						//Resize Image
						$this->image_lib->clear();
						$this->image_lib->initialize($this->set_resize_options($this->upload->client_name, $folder));
						$this->image_lib->resize();

						//Watermark
						//$this->image_lib->initialize($this->set_watermark_options($this->upload->client_name));
						//$this->image_lib->watermark();

						//$picture_obj['ref_id'] = $this->uri->segment(3);
						$picture_obj['ref_id'] = $data_input['column_id'];
						$picture_obj['ref_type'] = 2;
						$picture_obj['file_name'] = $this->upload->client_name;
						//$picture_obj['title'] = StripTxt($data_input['title']);						
						$picture_obj['caption'] = StripTxt($data_input['caption']);
						$picture_obj['folder'] = $folder;
						$picture_obj['create_date'] = $now_date;
						$picture_obj['create_by'] = $this->session->userdata('admin_id');
						$picture_obj['status'] = 1;

						if((count($picture_list) == 0) && ($i == 0)) $picture_obj['default'] = 1;

						$this->Picture_model->store(0, $picture_obj);
				}
				//Debug($this->upload);
			}

			$column_list =  $this->Column_model->get_column_by_id($data_input['column_id']);
			//**************Log activity
			$action = 2;
			$log_activity = array(
									"admin_id" => $this->session->userdata('admin_id'),
									"ref_id" => $data_input['column_id'],
									"ref_type" => 2,
									"ref_title" => "[Upload Picturn $cpt]".$column_list[0]['title'],
									"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity
			redirect(base_url('column/picture/'.$data_input['column_id']));
	}

	public function picture_edit($id = 0){
						
			 $this->load->model('Api_model');
			 $this->load->model('Picture_model');

			 $typeOfPic = 2 ;
			 $orientation = 1;

			 $getdata = $this->input->get();
			if($getdata){
				foreach($getdata as $key => $val){
						if($key == "picture_id") $picture_id = $val;
						//if($key == "Orientation") $orientation = $val;
				}
				$columnid = $id;
			}else{
				 $columnid = $this->uri->segment(3);
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

			$breadcrumb[] = '<a href="'.base_url('column').'">'.$language['column'].'</a>';
			$breadcrumb[] = '<a href="'.base_url('column/edit/'.$columnid).'">'.$language['edit'].'</a>';
			$breadcrumb[] = $language['picture'];

			$picture_list = $this->Api_model->get_picture($columnid, $picture_id, $typeOfPic);
			$column_item = $this->Column_model->get_column($columnid);

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"columnid" => $columnid,
					"column_item" => $column_item[0],
					"orientation" => $orientation,
					"picture_list" => $picture_list,
					"content_view" => 'column/picture_edit',
					"breadcrumb" => $breadcrumb
			);
			$this->load->view('template/template',$data);
	}

	public function picture_watermark(){

			 $this->load->model('Api_model');
			 $this->load->model('Picture_model');
			 $this->load->helper('img');

			$getdata = $this->input->post();
			if($getdata)
				foreach($getdata as $key => $val){
						//if($key == "id") $id = $val;
						if($key == "picture_id") $picture_id = $val;
						if($key == "columnid") $column_id = $val;
						if($key == "folder") $folder = $val;
						if($key == "file") $file = $val;
						if($key == "watermark") $watermark = $val;
						if($key == "caption") $caption = StripTxt($val);
						if($key == "type") $type = $val;
				}
			//Debug($getdata);
			//echo "columnid = $column_id";
			//die();

			switch($watermark){
						case "center" : $picture_list = $this->Picture_model->watermark($file, $folder, $type ); break;
						case "horizontal" : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 1); break;
						case "vertical" : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 2); break;
						default : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 3); break; //Logo ขนาดใหญ่
			}

			$data = array(
				"caption" => $caption
			);
			$this->Picture_model->store($id, $data);

			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $column_id,
					"ref_type" => 2,
					"ref_title" => "Set picture column picture id: ".$id." ".$caption,
					"action" => 2
			);			
			$this->Admin_log_activity_model->store($log_activity);

			$url = 'column/picture_edit/'.$column_id.'/'.$picture_id;
			redirect($url );
			die();	
	}

	public function remove_pic(){
			//Debug($this->input->post());
			$src = base64_decode($this->input->post('src'));
			if(file_exists($src)){
				unlink($src);
				echo "Yes";
				//echo "Remove Picture success.";
			}else echo "No";
	}

	public function picture_del($pic_id = 0){

			$this->load->model('Picture_model');
			$type = 2; //Column

			$ref_id = $this->input->get();
			if($ref_id)
				foreach($ref_id as $column_id => $val){
						//echo $column_id;
				}

			if($ref_id > 0 && $column_id > 0){
					$picture_list = $this->Api_model->get_picture($column_id, $pic_id, $type);

					//$tmp = 'uploads/tmp/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					//if(file_exists($tmp)) unlink($tmp);

					$tmp = 'uploads/column/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					if(file_exists($tmp)) unlink($tmp);

					$tmp = 'uploads/thumb/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					if(file_exists($tmp)) unlink($tmp);

					$this->Picture_model->delete_picture_admin($pic_id, 0, $type);
	
			}
			redirect('column/picture/'.$column_id);

	}

	/*function upload_pic(){

			$this->load->model('Picture_model');
			$this->load->library('upload');
			$this->load->library('image_lib');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			$picture_obj = array();

			$files = $_FILES;
			$now_date = date('Y-m-d H:i:s');
			$folder = date('Ymd');

			$data_input = $this->input->post();

			if($data_input['create_date'] != "") $folder = $data_input['create_date'];

			//$config['path_thumb'] = './uploads/column/thumb/';
			//if(!is_dir($config['path_thumb'])) mkdir($config['path_thumb'], 0777);

			$cpt = count($_FILES['picture_column']['name']);
			for($i=0; $i<$cpt; $i++){

				$_FILES['userfile']['name']= $files['picture_column']['name'][$i];
				$_FILES['userfile']['type']= $files['picture_column']['type'][$i];
				$_FILES['userfile']['tmp_name']= $files['picture_column']['tmp_name'][$i];
				$_FILES['userfile']['error']= $files['picture_column']['error'][$i];
				$_FILES['userfile']['size']= $files['picture_column']['size'][$i];    
				//Debug($_FILES['userfile']);

				if($_FILES['userfile']['error'] == 0){

						//Upload File
						$this->upload->initialize($this->set_upload_options());
						$this->upload->do_upload();

						//Resize Image
						//Resize Image
						$this->image_lib->clear();
						$this->image_lib->initialize($this->set_resize_options($this->upload->client_name, $folder));
						$this->image_lib->resize();

						//Watermark
						//$this->image_lib->initialize($this->set_watermark_options($this->upload->client_name));
						//$this->image_lib->watermark();

						//$picture_obj['ref_id'] = $this->uri->segment(3);
						$picture_obj['ref_id'] = $data_input['column_id'];
						$picture_obj['ref_type'] = 1;
						$picture_obj['file_name'] = $this->upload->client_name;
						//$picture_obj['title'] = StripTxt($data_input['title']);
						
						$picture_obj['caption'] = StripTxt($data_input['caption']);

						$picture_obj['folder'] = $folder;
						$picture_obj['create_date'] = $now_date;
						$picture_obj['create_by'] = $this->session->userdata('admin_id');
						$picture_obj['status'] = 1;
						$this->Picture_model->store(0, $picture_obj);
				}
			}

			redirect(base_url('column/picture/'.$data_input['column_id']));

	}*/

	private function set_watermark_options($client_name, $text = "Siamdara"){   

		$config = array();
		$folder = date('Ymd');
		
		$config['source_image'] = './uploads/column/'.$folder.'/'.$client_name;
		$config['new_image'] =  './uploads/column/'.$folder.'/sd_'.$client_name;
		$config['wm_text'] = $text ;
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = './system/fonts/texb.ttf';
		$config['wm_font_size'] = '16';
		$config['wm_font_color'] = 'ffffff';
		$config['wm_vrt_alignment'] = 'middle';
		$config['wm_hor_alignment'] = 'center';
		$config['wm_padding'] = '20';
		$config['wm_opacity'] = '50';
		$config['wm_shadow_distance'] = '3';

		return $config;
	}


	private function set_resize_options($client_name, $folder = '', $width = 300, $height = 169){   

		$config = array();
		if($folder == '') $folder = date('Ymd');
		$foldermain = 'column';
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = './uploads/tmp/'.$folder.'/'.$client_name;
		$config['new_image'] = './uploads/'.$foldermain.'/'.$folder.'/'.$client_name;

		$config['create_thumb'] = FALSE;	//สร้าง Thumb โดย CI
		$config['maintain_ratio'] = TRUE;
		//$config['width']     = $width;
		//$config['height']   = $height;

		//****Copy Original File to TMP
		$upload_path = './uploads/tmp/'.$folder;
		$tmp = $upload_path.'/'.$client_name;

		//echo "$upload_path  ==> $tmp ";

		$src = fopen($config['new_image'], 'r');
		$dest = fopen($tmp, 'w');

		stream_copy_to_stream($src, $dest);

		return $config;
	}

	private function set_upload_options($folder = ''){   

		$config = array();
		if($folder == '') $folder = date('Ymd');
		
		$config['upload_path'] = './uploads/column/';
		if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

		$config['upload_path'] = './uploads/column/'.$folder;
		if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

		$upload_path = './uploads/tmp/';
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		$upload_path = './uploads/tmp/'.$folder;
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size']      = '0';
		$config['overwrite']     = FALSE;

		return $config;
	}
	
	public function remove_img($id = 0){
			
			//$src = $this->input->post('name');
			//echo $id .' '. $src;

			/*unlink('uploads/dara/'.$src);
			$obj_data['picture_column'] = '';
			if($this->Column_model->store($id, $obj_data))
				echo 'Yes';
			else
				echo 'No';*/

	}

	public function status($id = 0){
				
			if($id == 0){
				$data = array(
						"error" => 'id error'
				);
				return false;
			}else{

					$obj_status = $this->Column_model->get_status($id);
					$cur_status = $obj_status[0]['status'];
					$title = $obj_status[0]['title'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;

					$obj_data['status'] = $cur_status;
					if($this->Column_model->store2($id, $obj_data)) echo $cur_status;
						//echo "update succedd.";
			}
			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 2,
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

					$obj_status = $this->Column_model->get_status($id);
					$cur_status = $obj_status[0]['approve'];
					$title = $obj_status[0]['title'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;

					$obj_data['approve'] = $cur_status;
					if($this->Column_model->store2($id, $obj_data)) echo $cur_status;
						//echo "update succedd.";
			}
			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 2,
								"ref_title" => "[Approve]".$title,
								"action" => 2
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity
	}

	public function save(){

			$this->load->library('upload');
			$this->load->library('image_lib');
			$this->load->model('Picture_model');
			$this->load->model('Tags_model');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			
			$tags_all = $this->Tags_model->getall_tag_pair(5); //tags ของดารา
			
			//$breadcrumb[] = $language['dara'];
			$column_data = $tag_id = $tag_pair = $picture_obj = array();
			$upload_status = "";
			$column_id = 0;

			$data_input = $this->input->post();

			/*$this->load->library('form_validation');
			// field name, error message, validation rules
			$this->form_validation->set_rules('title_en', $language['title'], 'trim|required');
			$this->form_validation->set_rules('title_th	', $language['title'], 'trim|required');
			$this->form_validation->set_rules('detail_en', $language['detail'], 'trim|required');
			$this->form_validation->set_rules('detail_th	', $language['detail'], 'trim|required');
			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

			if($this->form_validation->run() == FALSE){
					echo "<hr>FALSE";
					Debug($this->form_validation->set_error_delimiters());
			}else{
					echo "<hr>Save";
					//Debug($this->input->post());
					//$this->Column_model->store($dara_profile_id, $this->input->post());
			}*/

				$now_date = date('Y-m-d H:i:s');
				if(isset($data_input['create_date'])){
					$folder = ($data_input['create_date'] != "") ? $data_input['create_date'] : date('Ymd');
				}else
					$folder = date('Ymd');

				$config_upload = $this->set_upload_options($folder);
				$this->load->library('upload', $config_upload);
				$this->upload->initialize($config_upload);
				//$this->upload->do_upload();

				if (!$this->upload->do_upload('picture')){
					$error = array('error' => $this->upload->display_errors());
					$upload_status = $error;
				}else{

					$this->image_lib->clear();
					$this->image_lib->initialize($this->set_resize_options($this->upload->client_name, $folder));
					$this->image_lib->resize();

					$upload_status = "Success";
				}

			//Debug($data_input);
			//echo "upload_status = $upload_status";
			//echo "<br>folder = $folder";
			//echo "<br>client_name = ".$this->upload->client_name;
			//die();
			//******************* List data input **********************/
			if($data_input)
					foreach($data_input as $key => $val){
							if($key == "column_id_en"){
									$new_data['column_id'] = $data_input['column_id_en'];

							}else if($key == "column_id_th"){
									$new_data_th['column_id'] = $data_input['column_id_th'];

							}else if($key == "column_id"){
									$new_data['column_id2'] = $data_input['column_id'];
									$new_data_th['column_id2'] = $data_input['column_id'];

							}else if($key != "tag_id" && $key != "start_date" && $key != "folder" && $key != "create_date" && $key != "highlight" && $key != "megamenu"){
									
									if($key == "title_en" || $key == "description_en" || $key == "detail_en"){

											if($key == "title_en"){  
												$new_data['title'] = StripTxt($data_input['title_en']); 
												$new_data['lang'] = "en"; 
											}
											if($key == "description_en")  $new_data['description'] = $data_input['description_en'];
											if($key == "detail_en")  $new_data['detail'] = $data_input['detail_en'];

									}else if($key == "title_th" || $key == "description_th" || $key == "detail_th"){

											if($key == "title_th"){
												$new_data_th['title'] = StripTxt($data_input['title_th']);
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
											$tag_id[] = $val2;
									}
							}

					}

			if(!isset($new_data['dara_id'])){
				$new_data['dara_id'] = 0;				
			}
			
			//if($this->upload->client_name){
					//$new_data['picture_column'] = $this->upload->client_name;
			//}

			//Set Date Display
			if(isset($data_input['start_date'])){

				//list($date, $time, $time2) = explode(" ", $data_input['start_date']);
				//if($time2 == "PM") $time = $time +12;

				$new_data['start_date'] = DateTimeDB($data_input['start_date']);
				$new_data_th['start_date'] = $new_data['start_date'];
			}

			if(isset($data_input['expire_date'])){

				//list($date, $time, $time2) = explode(" ", $data_input['expire_date']);
				//if($time2 == "PM") $time = $time +12;

				$new_data['expire_date'] = DateTimeDB($data_input['expire_date']);
				$new_data_th['expire_date'] = $new_data['expire_date'];
			}

			//Debug($new_data);
			//die();
			
			/**************************************** Relate**************************/
			$number_of_relate = 4;

			$tag_relate = $this->Column_model->get_relate($new_data['column_id2']);
			//Debug($tag_relate);
			//die();
			if($data_input['column_id'] > 0){
				//if(count($tag_relate)<$number_of_relate){
				if(count($tag_relate) == 0){

						$t=0;
						$where_id = array();

						$tag_relate = $this->Column_model->gen_relate($new_data['dara_id'], $tag_id, $new_data['column_id2']);
						if(count($tag_relate) == 0) $tag_relate = $this->Column_model->gen_relate(null, $tag_id, $new_data['column_id2']);

						//$tag_relate = $this->Column_model->gen_relate(null, $tag_id, $new_data['column_id2']);
						//Debug($tag_relate);
						//if($tag_relate){
							$max5 = (count($tag_relate) > $number_of_relate) ? $number_of_relate : count($tag_relate);
							for($t=0;$t<$max5;$t++){

									$number = $t+1;
									$tag_relate[$t]->column_id;
									//echo "<hr>".$number." ".$tag_relate[$t]->column_id."<hr>";

									if($tag_relate[$t]->column_id != $new_data['column_id']){
										$where_id[$t]['column_id'] = intval($tag_relate[$t]->column_id);
										$where_id[$t]['ref_id'] = intval($new_data['column_id']);
										//$where_id[$t]['ref_type'] = 2; //Column
										$where_id[$t]['order'] = $number;
									}

							}
						//Debug($where_id);

						if(count($where_id) > 0) $this->Column_model->save_relate($new_data['column_id2'], $where_id);
				}
			}

			//Debug($new_data);
			//die();

			/**************************************** Relate Columnist **************************/
			if($data_input['column_id'] > 0){
				//$number_of_relate = 5;
				unset($tag_relate);

				$tag_relate = $this->Column_model->get_relate_columnist($new_data['column_id2']);
				//Debug($tag_relate);
				//die();

				//if(count($tag_relate)<$number_of_relate){
				if(count($tag_relate) == 0){
						$t=0;
						unset($where_id);
						//$tag_relate = $this->Column_model->gen_relate($new_data['dara_id'], $tag_id, $new_data['column_id2']);
						//if(count($tag_relate) == 0) $tag_relate = $this->Column_model->gen_relate(null, $tag_id, $new_data['column_id2']);

						$tag_relate = $this->Column_model->gen_relate_columnist($new_data['columnist_id'], $tag_id, $new_data['column_id2']);
						//Debug($tag_relate);
						//if($tag_relate){
							$max5 = (count($tag_relate) > $number_of_relate) ? $number_of_relate : count($tag_relate);
							for($t=0;$t<$max5;$t++){
									$number = $t+1;
									$tag_relate[$t]->column_id;
									//echo "<hr>".$number." ".$tag_relate[$t]->column_id."<hr>";
									if($tag_relate[$t]->column_id != $new_data['column_id2']){
											$where_id[$t]['column_id'] = $tag_relate[$t]->column_id;
											$where_id[$t]['ref_id'] = $new_data['column_id2'];
											//$where_id[$t]['ref_type'] = 2; //Column
											$where_id[$t]['order'] = $number;
									}
							}
						//}
						if(count($where_id) > 0) $this->Column_model->save_relate_columnist($new_data['column_id2'], $where_id);
				}
			}

			$autotags = false;
			//**************************************** ใส่ Tags ใน Content editor ***************************************
			if(($new_data['column_id2'] == 0) || ($autotags == true)){
				if($tags_all){
						$number_tag = count($tags_all);
						$counttag = 0;
						for($i=0;$i<$number_tag;$i++){

							$textnormal = $tags_all[$i]->tag_text;
							$dara_type_id = $tags_all[$i]->dara_type_id;
							$dara_profile_id = $tags_all[$i]->dara_profile_id;
							$dara_type_name = $tags_all[$i]->dara_type_name;
									
							$url = $this->config->config['www'].'/dara/'.$dara_type_id.'/'.$dara_profile_id.'/'.urlencode(RewriteTitle($dara_type_name)).'/'.urlencode(RewriteTitle($textnormal));

							$text_replace = '<a href="'.$url.'" target=_blank>'.$textnormal.'</a>';

							if(preg_match("/$textnormal/i", $new_data['detail'])){
											$new_data['detail'] = str_replace($textnormal, $text_replace, $new_data['detail']);
											$counttag++;
							}
							if(preg_match("/$textnormal/i", $new_data['description'])){
											$new_data['description'] = str_replace($textnormal, $text_replace, $new_data['description']);
											$counttag++;
							}
							if(preg_match("/$textnormal/i", $new_data_th['detail'])){
											$new_data_th['detail'] = str_replace($textnormal, $text_replace, $new_data_th['detail']);
											$counttag++;
							}
							if(preg_match("/$textnormal/i", $new_data_th['description'])){
											$new_data_th['description'] = str_replace($textnormal, $text_replace, $new_data_th['description']);
											$counttag++;
							}

						}
				}
			} //Add tags
			//*********************************************************

			unset($new_data['orderid']);
			unset($new_data_th['orderid']);

			if(isset($data_input['column_id_en'])){
				$newcount = 2;
			}else
				$newcount = 1;
			
			//echo "newcount = $newcount<hr>";
			/*echo "new_data";
			Debug($new_data);
			echo "new_data_th";
			Debug($new_data_th);
			die();*/

			if(isset($data_input['icon_vdo'])){
					$new_data['icon_vdo'] = 1;
					$new_data_th['icon_vdo'] = 1;
			}else{
					$new_data['icon_vdo'] = 0;
					$new_data_th['icon_vdo'] = 0;
			}

			if($new_data['column_id2'] > 0){

						$action = 2;
						if($newcount == 2){
								$new_data['lastupdate_by'] = $this->session->userdata('admin_id');
								$new_data_th['lastupdate_by'] = $this->session->userdata('admin_id');

								$column_id_en = $new_data['column_id'];
								$column_id_th = $new_data_th['column_id'];
								$new_id = $column_id = $new_data['column_id'];

								unset($new_data['column_id']);
								unset($new_data_th['column_id']);
								unset($new_data['column_id2']);
								unset($new_data_th['column_id2']);

								$this->Column_model->store($column_id_en, $new_data);
								$this->Column_model->store($column_id_th, $new_data_th);
						}else{

								$new_data_th['lastupdate_by'] = $this->session->userdata('admin_id');
								$new_id = $column_id = $new_data_th['column_id'];
								unset($new_data_th['column_id']);
								unset($new_data_th['column_id2']);
								$this->Column_model->store($column_id, $new_data_th);
						
						}

			}else{

						$action = 1;
						$new_data['create_date'] = $now_date;
						$new_data['create_by'] = $this->session->userdata('admin_id');

						$new_data_th['create_date'] = $now_date;
						$new_data_th['create_by'] = $this->session->userdata('admin_id');

						$max_id = $this->Column_model->get_max_id();
						$column_id = $max_id[0]['max_id'] + 1;
						
						$new_data['column_id'] = $column_id;
						$new_data['column_id2'] = $column_id;
						$new_data_th['column_id2'] = $column_id;


						$new_data['order_by'] = 1;
						$new_data_th['order_by'] = 1;
						$this->Column_model->update_orderadd($new_data['category_id']);

						$this->Column_model->store(0, $new_data);
						$this->Column_model->store(0, $new_data_th);
						/*$data = array(
								"admin_menu" => $this->menufactory->getMenu(),
								"ListSelect" => $ListSelect,
								"column_list" => $this->Column_model->get_column(null, null, $language['lang'], '_column.order_by', 'asc', 0, 100),
								"content_view" => 'column/column',
								//"breadcrumb" => $breadcrumb,
								"error" => 'Success.'
						);*/
						
			}

			/***************************tags*************************************/
			if(count($tag_id) < 3){
					$get_tag_dara = $this->Tags_model->get_tag_pair($new_data['dara_id'], 5);
					//Debug($get_tag_dara);
					$clear = 1;

					if($get_tag_dara){
							for($i=0;$i<count($get_tag_dara);$i++){
										$tag_pair[$i]["tag_id"] = $get_tag_dara[$i]->tag_id;
										$tag_pair[$i]["ref_id"] = $column_id;
										$tag_pair[$i]["ref_type"] = 2;
										$tag_pair[$i]["create_date"] = $now_date;
										$next = $i;
							}
					}			
					//Insert tags
					//Debug($tag_id);
					if($tag_id){
							if(!isset($next)) $next = 0;
							$begin = $next + 1;
							$endtag = count($tag_id) + $begin;
							$t = 0;
							for($i=$begin;$i<$endtag;$i++){
										
										$tag_pair[$i]["tag_id"] = $tag_id[$t];
										$tag_pair[$i]["ref_id"] = $column_id;
										$tag_pair[$i]["ref_type"] = 2;
										$tag_pair[$i]["create_date"] = $now_date;
										$t++;
							}
							//Debug($tag_pair);					
							$this->Tags_model->store_tag_pair($tag_pair, $clear);
					}
			}

			//Debug($new_data);
			//Debug($data_input);
			if($upload_status == "Success"){
						$picture_obj['ref_id'] = $column_id;
						$picture_obj['ref_type'] = 2;
						$picture_obj['file_name'] = $this->upload->client_name;
						if($language['lang'] == 'en')
							$picture_obj['caption'] = StripTxt($data_input['title_en']);
						else
							$picture_obj['caption'] = StripTxt($data_input['title_th']);

						$picture_obj['folder'] = $folder;
						$picture_obj['create_date'] = $now_date;
						$picture_obj['create_by'] = $this->session->userdata('admin_id');
						$picture_obj['default'] = 1;
						$picture_obj['status'] = 1;
						$this->Picture_model->store(0, $picture_obj);
			}


			//**************Set Highlight
			if(isset($data_input['highlight'])){

				//Debug($data_input['highlight']);
				$data_highlight = array(
						"news_id" => $column_id,
						"ref_type" => 2,
						"order" => 0
				);							
				//Debug($data_highlight);
				$this->Column_model->set_highlight($column_id, $data_highlight);
				$this->Column_model->update_order_highlight();
				//echo "new_id = $new_id";
				//die();
			}else{
				$this->Column_model->remove_highlight($column_id);
				//echo "new_id = $new_id";
			}
			//die();

			//**************Set Megamenu
			if(isset($data_input['megamenu'])){

				//Debug($data_input['highlight']);
				$data_megamenu = array(
						"id" => $column_id,
						"category_id" => $new_data['category_id'],
						"ref_type" => 2,
						"order" => 0
				);							
				$this->Column_model->set_megamenu($column_id, $data_megamenu);
				//echo "new_id = $new_id";
				//die();
			}else{
				$this->Column_model->remove_megamenu($column_id);
				//echo "new_id = $new_id";
			}

			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $column_id,
								"ref_type" => 2,
								"ref_title" => StripTxt($data_input['title_th']),
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//Debug($log_activity );
			//**************Log activity

			$success =  'Save complete.';

			//$this->load->view('template/template',$data);
			//redirect(base_url('column/edit/'.$column_id));
			redirect(base_url('column/edit/'.$column_id.'?success='.urlencode($success)));
	}

	public function search_relate(){
			
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			if($this->input->server('REQUEST_METHOD') === 'GET'){

					$search_form = $this->input->get();
					if(isset($search_form['kw'])){
							
						$columnid = $search_form['columnid'];
						$column_list = $this->Column_model->get_column(null, $search_form['kw'], $language['lang']);
						if($column_list){
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

								$maxlist = count($column_list);
								for($i=0;$i<$maxlist;$i++){
										//$url = "http://www.Tmon.com/news/".urlencode($news_list[$i]['title']).".html";
										//$url = "http://www.Tmon.com/news/".$news_list[$i]['news_id2'].".html";

										$img = base_url('uploads/thumb').'/'.$column_list[$i]['folder'].'/'.$column_list[$i]['file_name'];
										//$img = 'uploads/thumb/'.$news_list[$i]['folder'].'/'.$news_list[$i]['file_name'];

										if($column_list[$i]['file_name'] != "")
											$tags_img = (file_exists('uploads/thumb/'.$column_list[$i]['folder'].'/'.$column_list[$i]['file_name'])) ? "<img src=".$img." height='50'>" : "";
										else
											$tags_img = "";

										$status = ($column_list[$i]['status'] == 1) ? 'checked' : '';
										$iconstatus = '<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-3" type="checkbox" '.$status.' disabled>
														<span class="lbl"></span>
													</label>
												</div>';
										//$addurl = 'javascript:void(0);';
										$addurl = base_url('column/add_relate_column').'?column_id='.$column_list[$i]['column_id2'].'&ref_id='.$columnid;
										$edit_data = base_url('column/edit/'.$column_list[$i]['column_id2']);

										$iconadd = '<a href="'.$addurl.'" data-value="'.$column_list[$i]['column_id2'].'" class="add_relate"><i class="ace-icon glyphicon glyphicon-plus">Add relate</i></a>';

										echo "<tr>
										<td>".$column_list[$i]['column_id2']."</td>
										<td>".$tags_img."</td>
										<td><a href='".$edit_data."' target=_blank>".$column_list[$i]['title']."</a></td>
										<td>".$column_list[$i]['create_date']."</td>
										<td>".$iconstatus."</td>
										<td>".$column_list[$i]['countview']."</td>
										<td>".$iconadd."</td></tr>";
								}						
								echo "</tbody></table>";
						}else
								echo "ไม่มีข้อมูล";
					}
					//die();
			}

	}

	public function add_relate_column(){
			
			$data_input = $this->input->get();
			$ref_id = $data_input['ref_id'];
			$column_id = $data_input['column_id'];
			//Debug($data_input);
			$this->Column_model->add_relate_column($ref_id, $column_id);
			echo '<script type="text/javascript">
			<!--
				window.location="'.base_url("column/edit").'/'.$ref_id.'";
			//-->
			</script>';
	}

	public function add_relate_column2(){
			
			$data_input = $this->input->get();
			$ref_id = $data_input['ref_id'];
			$column_id = $data_input['column_id'];
			//Debug($data_input);
			$this->Column_model->add_relate_column($ref_id, $column_id, '_column_relate_columnist');
			echo '<script type="text/javascript">
			<!--
				window.location="'.base_url("column/edit").'/'.$ref_id.'";
			//-->
			</script>';
	}

	public function DelRelateID(){

			$data_input = $this->input->post();
			//$column_id = $data_input['column_id'];
			$id = $data_input['id'];
			$refid = $data_input['refid'];
			$title = $data_input['name'];
			//echo "Deleting... $id";
			
			//Debug($data_input);
			//die();

			$this->Column_model->delete_relate_column($refid, $id);

			//$this->Column_model->get_status($refid);
			$action = 3;
			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $refid,
					"ref_type" => 2,
					"ref_title" => "Delete relate column id: ".$id." ".$title,
					"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);

			die();
	}

	public function delete($id){

			
			$OBJnews = $this->Column_model->get_column($id);
			$title = $OBJnews[0]['title'];
			$order_by = $OBJnews[0]['order_by'];

			$cat = $this->input->get('cat');

			//echo "Deleting... $id $title ";
			//Debug($OBJnews);

			$this->Column_model->delete_column($id);

			//**************Order New
			$this->Column_model->update_orderdel($cat, $order_by);

			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 2,
								"ref_title" => $title,
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('column');
			die();
	}

	public function DelRelateColID(){

			$data_input = $this->input->post();
			//$column_id = $data_input['column_id'];
			$id = $data_input['id'];
			$refid = $data_input['refid'];
			$title = $data_input['name'];
			//echo "Deleting... $id";
			$this->Column_model->delete_relate_column($refid, $id, '_column_relate_columnist');

			//$this->Column_model->get_status($refid);
			$action = 3;
			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $refid,
					"ref_type" => 2,
					"ref_title" => "Delete relate column columnist id: ".$id." ".$title,
					"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);

			die();
	}

	public function set_order_relate(){
					
					$lang = $this->lang->language;
					
					$columnid = $this->input->post('columnid');
					$json = $this->input->post('json');

					$obj = json_decode($json);
					$update_data = array();

					//echo "columnid = $columnid<br>";
					//Debug($obj);
					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj);$i++){
								//echo $num.'.) '.$obj[$i]->id.' : '.$newsid.' ==> '.$num.'<br>';
								unset($update_data);
								$update_data['order'] = $num;
								$this->Column_model->set_order_relate($columnid, $obj[$i]->id, $update_data);
								$num++;
						}
						
					$column = $this->Column_model->get_status($columnid);

					$action = 2;
					$log_activity = array(
							"admin_id" => $this->session->userdata('admin_id'),
							"ref_id" => $columnid,
							"ref_type" => 2,
							"ref_title" => "Set Order column relate: ".$column[0]['title'],
							"action" => $action
					);			
					$this->Admin_log_activity_model->store($log_activity);

					echo $lang['update'].' '.$lang['success'].'.';
	}	

	public function set_order_relate2(){
					
					$lang = $this->lang->language;
					
					$columnid = $this->input->post('columnid');
					$json = $this->input->post('json');

					$obj = json_decode($json);
					$update_data = array();

					//echo "columnid = $columnid<br>";
					//Debug($obj);
					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj);$i++){
								//echo $num.'.) '.$obj[$i]->id.' : '.$newsid.' ==> '.$num.'<br>';
								unset($update_data);
								$update_data['order'] = $num;
								$this->Column_model->set_order_relate($columnid, $obj[$i]->id, $update_data, '_column_relate_columnist');
								$num++;
						}
						
					$column = $this->Column_model->get_status($columnid);

					$action = 2;
					$log_activity = array(
							"admin_id" => $this->session->userdata('admin_id'),
							"ref_id" => $columnid,
							"ref_type" => 2,
							"ref_title" => "Set Order column columnist relate: ".$column[0]['title'],
							"action" => $action
					);			
					$this->Admin_log_activity_model->store($log_activity);

					echo $lang['update'].' '.$lang['success'].'.';
	}	

	public function saveorder(){

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					Debug($search_form);
					//die();
					$data['order'] = $search_form['orderid'];
					$column_id = $search_form['column_id'];

					$columnobj = $this->Column_model->get_status($column_id);

					if(isset($search_form['relate_id'])){
							if(count($search_form['relate_id']) < 5){

									$relate_id = $search_form['relate_id'];
									$orderid = $search_form['orderid'];
									$i = 1; 

									foreach($relate_id as $arr => $val){
											$this->Column_model->update_order_relate($search_form['relate_id'][$arr], $column_id, $search_form['orderid'][$arr]);
											$i++;
									}

									/**************Log activity *********/
									$log_activity = array(
														"admin_id" => $this->session->userdata('admin_id'),
														"ref_id" => $column_id,
														"ref_type" => 2,
														"ref_title" => $columnobj[0]['title']." relate [Order By]",
														"action" => 2
									);			
									$this->Admin_log_activity_model->store($log_activity);
									//**************Log activity

									//$success =  'Save Order complete.';
									redirect(base_url('column/edit').'/'.$column_id);
									die();
							}
					}
			}

	}	

	public function saveorder2(){

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					Debug($search_form);
					//die();
					$data['order'] = $search_form['orderid'];
					$column_id = $search_form['column_id'];

					$columnobj = $this->Column_model->get_status($column_id);

					if(isset($search_form['relate_id'])){
							if(count($search_form['relate_id']) < 5){

									$relate_id = $search_form['relate_id'];
									$orderid = $search_form['orderid'];
									$i = 1; 

									foreach($relate_id as $arr => $val){
											$this->Column_model->update_order_relate($search_form['relate_id'][$arr], $column_id, $search_form['orderid'][$arr], '_column_relate_columnist');
											$i++;
									}

									/**************Log activity *********/
									$log_activity = array(
														"admin_id" => $this->session->userdata('admin_id'),
														"ref_id" => $column_id,
														"ref_type" => 2,
														"ref_title" => $columnobj[0]['title']." relate columnist [Order By]",
														"action" => 2
									);			
									$this->Admin_log_activity_model->store($log_activity);
									//**************Log activity

									//$success =  'Save Order complete.';
									redirect(base_url('column/edit').'/'.$column_id);
									die();
							}
					}
			}

	}	

	public function set_default($id = 0){
			echo "Set default... $id";

			//$data_input = $this->input->post();
			$data_input = $this->input->get();

			//Debug($data_input);
			$id = $data_input['id'];
			$ref_id = $data_input['ref_id'];
			
			$OBJnews = $this->Column_model->get_column($ref_id);

			//Debug($OBJnews);

			$news_id = $OBJnews[0]['column_id2'];
			$title = "Set default picture ".$OBJnews[0]['title'];
			$order_by = $OBJnews[0]['order_by'];

			$this->Column_model->set_default($id, $ref_id);

			//**************Log activity
			$action = 2;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $ref_id,
								"ref_type" => 2,
								"ref_title" => $title,
								"action" => $action
			);
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			//redirect('column/picture/'.$this->uri->segment(3));
			redirect('column/picture/'.$ref_id);
			die();
	}

	function auto_order($cat = 0){
				
				$language = $this->lang->language;
				$cat_view = array();

				//if($cat != 0) $cat_view['category_id'] = $cat;
				$object_list = $this->Column_model->get_catcolumn($cat);
				//Debug($object_list);
				if(isset($object_list)){
						for($i=0;$i<count($object_list);$i++){
									$number = $i + 1;
									$id = $object_list[$i]['column_id2'];
									$title = $object_list[$i]['title'];
									$order_by = $object_list[$i]['order_by'];
									$data = array(
										"order_by" => $number
									);
									echo "$id $title  [$order_by ==> $number]<br>";
									$this->Column_model->store2($id, $data);
						}
						echo "<hr>UPDATE Order cat = $cat  total = ".count($object_list)." record.";
				}
	}

}
