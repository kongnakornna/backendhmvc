<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Gallery extends CI_Controller {

    public function __construct()    {
        parent::__construct();
		 $this->load->model('Gallery_model');
		 $breadcrumb = array();

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }

	public function index(){
			
			$this->load->model('gallery_type_model');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$notification_birthday = $this->Api_model->notification_birthday();

			$gallery_type_id = 0;

			$breadcrumb[] = $language['gallery'];

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					//Debug($search_form);
					//die();

					if(isset($search_form['selectid'])){
							$selectid = $search_form['selectid'];
							$order = $search_form['order'];
							$maxsel = count($selectid);
							$tmp = 0;

							for($i=0;$i<$maxsel;$i++){

									$this->Gallery_model->update_order($selectid[$i], $order[$i]);
									if($tmp > $order[$i]){
											//Update ID ด้านหน้า
											//$this->Gallery_model->update_orderid_to_down($order[$i], $tmp);
									}
									if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
							}
					}

					if(isset($search_form['keyword'])){
						//$keyword = trim(str_replace(" ", "-", $search_form['keyword']));
						$keyword = trim($search_form['keyword']);
						$gallery_list = $this->Gallery_model->get_data(null, $keyword);
					}else{
						$keyword = "Tmon";
						$gallery_list = $this->Gallery_model->get_data();
					}

					if(isset($search_form['gallery_type_id'])){
							$gallery_type_id = $search_form['gallery_type_id'];
							$gallery_list = $this->Gallery_model->get_data(null, null, $gallery_type_id);
					}

			}else{

					$keyword = "Tmon";
					$gallery_list = $this->Gallery_model->get_data();
			}

			$gallery_type_list = $this->gallery_type_model->getSelectCat($gallery_type_id, $language['all']);
			$gallery_count = $this->Gallery_model->countgallery($gallery_type_id);

			$data = array(
					"ListSelect" => $ListSelect,
					"gallery_list" => $gallery_list,
					"gallery_count" => $gallery_count,
					"type_id" => $gallery_type_id,
					"gallery_type_list" => $gallery_type_list,
					"notification_birthday" => $notification_birthday,
					"content_view" => 'gallery/gallery',
					"breadcrumb" => $breadcrumb
			);
			$this->load->view('template/template',$data);
	}
	
	public function add(){
			
			 $this->load->model('gallery_type_model');
			 $this->load->model('Dara_model');
			 $this->load->model('Tags_model');
			 $this->load->model('Credit_model');
			 $this->load->helper('ckeditor');

			$Path_CKfinder = base_url('plugins/ckeditor-integrated/ckfinder');
			$Path_CKeditor = base_url('plugins/ckeditor-integrated/ckeditor');

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('gallery').'">'.$language['gallery'].'</a>';
			$breadcrumb[] = $language['add'];

			$gallery_type_list = $this->gallery_type_model->getSelectCat();
			$dara_list = $this->Dara_model->get_dara_profile();
			$tags_list = $this->Tags_model->getSelect();
			$credit_list = $this->Credit_model->get_data();
			
			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"dara_list" => $dara_list,
					"gallery_type_list" => $gallery_type_list,
					"tags_list" => $tags_list,
					"credit_list" => $credit_list,
					"content_view" => 'gallery/gallery_add',
					"breadcrumb" => $breadcrumb,
			);

			//Ckeditor's configuration
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
						
			 $this->load->model('gallery_type_model');
			 $this->load->model('Dara_model');
			 $this->load->model('Tags_model');
			 $this->load->model('Picture_model');
			 $this->load->model('Credit_model');
			$this->load->helper('ckeditor');

			$Path_CKfinder = base_url('plugins/ckeditor-integrated/ckfinder');
			$Path_CKeditor = base_url('plugins/ckeditor-integrated/ckeditor');

			$tag_id = array();
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('gallery').'">'.$language['gallery'].'</a>';
			$breadcrumb[] = $language['edit'];
			
			$gallery_list =  $this->Gallery_model->get_data($id);
			//debug($gallery_list);
			$gallery_type_list = $this->gallery_type_model->getSelectCat($gallery_list[0]['gallery_type_id']);
						
			$sel_tags = $this->Tags_model->get_tag_pair($id, 3);
			//Debug($sel_tags);
			if($sel_tags)
				for($i=0;$i<count($sel_tags);$i++){
					@$tag_id[$i]->value = $sel_tags[$i]->tag_id;
				}
			
			//Debug($tag_id);
			$tags_list = $this->Tags_model->getSelect($tag_id);

			$dara_list = $this->Dara_model->get_dara_profile();
			$picture_list = $this->Picture_model->get_picture_by_ref_id($id, 3);
			$credit_list = $this->Credit_model->get_data();

			$get_relate = $this->Gallery_model->get_relate($id);

			$datalog = array(
					"ref_type" => 3,
					"ref_id" => $id
			);
			$view_log = $this->Admin_log_activity_model->view_log(0, $datalog);

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"gallery_list" => $gallery_list,
					"gallery_type_list" => $gallery_type_list,					
					"dara_list" => $dara_list,
					"tags_list" => $tags_list,
					"picture_list" => $picture_list,
					"credit_list" => $credit_list,
					"get_relate" => $get_relate,
					"view_log" => $view_log,
					"content_view" => 'gallery/gallery_edit',
					"breadcrumb" => $breadcrumb,
			);

			//Ckeditor's configuration
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
	
	public function picture($id = 0){
	
		//$this->load->model('Category_model');
		//$this->load->model('Subcategory_model');
		//$this->load->model('Dara_model');

		$this->load->model('Api_model');
		$this->load->model('Picture_model');
	
		$tag_id = array();
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
		$language = $this->lang->language;
	
		$breadcrumb[] = '<a href="'.base_url('gallery').'">'.$language['gallery'].'</a>';
		$breadcrumb[] = $language['picture'];
	
		$picture_list = $this->Api_model->get_picture($id, 0, 3);
			
		//$picture_list = json_decode("http://elvis.siamsport.co.th/services/search?q=autosalon%20AND%20extension:jpg&start=0&num=10&sort=name&metadataToReturn=all&format=json&facet.assetDomain.selection=image");
		//$picture_list = LoadJSON("search?q=autosalon%20AND%20extension:jpg&start=0&num=10&sort=name&metadataToReturn=all&format=json&facet.assetDomain.selection=image", "http://elvis.siamsport.co.th/services");
		//Debug($picture_list);
		
		$data = array(
				"admin_menu" => $this->menufactory->getMenu(),
				"ListSelect" => $ListSelect,
	
				//"news_list" => $this->News_model->get_news($id),
				//"category_list" => $category_list,
				//"subcategory_list" => $subcategory_list,
	
				"picture_list" => $picture_list,
				"content_view" => 'gallery/gallery_pic',
				"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);
	
	}
	
	public function picture_order($id = 0){
	
		$this->load->model('Api_model');
		$this->load->model('Picture_model');
	
		$tag_id = array();
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
		$language = $this->lang->language;
	
		$breadcrumb[] = '<a href="'.base_url('gallery').'">'.$language['gallery'].'</a>';
		$breadcrumb[] ='<a href="'.base_url('gallery/picture/'.$this->uri->segment(3)).'">'.$language['picture'].'</a>';
		$breadcrumb[] = $language['order'];

		$picture_list = $this->Api_model->get_picture($id, 0, 3);
					
		$data = array(
				"admin_menu" => $this->menufactory->getMenu(),
				"ListSelect" => $ListSelect,
	
				//"news_list" => $this->News_model->get_news($id),
				//"category_list" => $category_list,
				//"subcategory_list" => $subcategory_list,
	
				"picture_list" => $picture_list,
				"content_view" => 'gallery/gallery_pic_order',
				"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);
	
	}
	
	public function picture_edit($id = 0, $idtype = 0){

		$this->load->model('Api_model');
		$this->load->model('Picture_model');
		
		$tag_id = array();
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
		$language = $this->lang->language;
		$orientation = 1;
	
		$ref_id = $this->input->get();
		if($ref_id)
			foreach($ref_id as $key => $val){
					//echo "<br>".$news_id." : ".$val;
					if($key == "gallery_id") $gallery_id = $val;
					if($key == "Orientation") $orientation = $val;
			}	

		$breadcrumb[] = '<a href="'.base_url('gallery').'">'.$language['gallery'].'</a>';
		$breadcrumb[] ='<a href="'.base_url('gallery/picture/'.$gallery_id).'">'.$language['picture'].'</a>';
		$breadcrumb[] = $language['edit'];

		$picture_list = $this->Api_model->get_picture($gallery_id, $id, 3);
		$gallery_list =  $this->Gallery_model->get_data($gallery_id);

		$data = array(
				"admin_menu" => $this->menufactory->getMenu(),
				"ListSelect" => $ListSelect,
				"gallery_id" => $gallery_id,
				"orientation" => $orientation,
				"picture_list" => $picture_list,
				"gallery_list" => $gallery_list[0],
				//"content_view" => 'gallery/gallery_pic_edit',
				"content_view" => 'gallery/gallery_pic_edit_thumb',
				"breadcrumb" => $breadcrumb
		);
		$this->load->view('template/template',$data);
	
	}

	public function rotate(){

			$this->load->model('Picture_model');

			 $gallery_id = $this->input->get('gallery_id');
			 $rotate = $this->input->get('rotate');
			 $folder = $this->input->get('folder');
			 $file = $this->input->get('file');

			//Debug($this->input->get());

			$rotate = ($rotate == "l") ? -90 : 90;
			$sourcefile = './uploads/gallery/'.$folder.'/'.$file;

			$sourcefile_tmp = './uploads/tmp/'.$folder.'/'.$file;

			//Debug($sourcefile);
			//Debug($rotate);

			$this->Picture_model->rotate_img($sourcefile, $sourcefile, $rotate, 1);
			$this->Picture_model->rotate_img($sourcefile_tmp, $sourcefile_tmp, $rotate, 0);
	}

	public function picture_watermark($id = 0){

			$this->load->model('Api_model');
			$this->load->model('Picture_model');
			$this->load->helper('img');

			$ref_id = $this->input->post();
			//Debug($ref_id);
			//die();
			if($ref_id)
				foreach($ref_id as $key => $val){
						if($key == "picture_id") $picture_id = $val;
						if($key == "gallery_id") $gallery_id = $val;
						if($key == "folder") $folder = $val;
						if($key == "file") $file = $val;
						//if($key == "type") $type = $val;
						if($key == "watermark") $watermark = $val;
						if($key == "caption") $caption = StripTxt($val);
				}
			//Debug($ref_id);
			//if(!isset($type)) $type='gallery';
			$type='gallery';
			switch($watermark){
						case "center" : $picture_list = $this->Picture_model->watermark($file, $folder, $type); break;
						case "horizontal" : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 1); break;
						case "vertical" : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 2); break;
						default : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 3); break; //Logo ขนาดใหญ่
			}

			$data = array(
				"caption" => $caption
			);
			$this->Picture_model->store($picture_id, $data);

			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $gallery_id,
					"ref_type" => 3,
					"ref_title" => "Set picture gallery id: ".$id." ".$caption,
					"action" => 2
			);			
			$this->Admin_log_activity_model->store($log_activity);

			//$img1 = base_url('uploads/gallery').'/'.$folder.'/'.$file;
			//$img2 = base_url('uploads/thumb').'/'.$folder.'/'.$file;
			//$img3 = base_url('uploads/tmp').'/'.$folder.'/'.$file;

			//echo'<img src="'.$img1.'" border="0" alt="">';
			//echo'<img src="'.$img2.'" border="0" alt="">';
			//echo'<img src="'.$img3.'" border="0" alt="">';

			//Debug($picture_list);
			redirect('gallery/picture_edit/'.$picture_id.'?gallery_id='.$gallery_id);
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
			$type = 3; //Gallery

			$ref_id = $this->input->get();
			if($ref_id)
				foreach($ref_id as $gallery_id => $val){
						//echo $gallery_id;
				}

			if($ref_id > 0 && $gallery_id > 0){
					$picture_list = $this->Api_model->get_picture($gallery_id, $pic_id, $type);

					//$tmp = 'uploads/tmp/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					//if(file_exists($tmp)) unlink($tmp);

					$tmp = 'uploads/gallery/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					if(file_exists($tmp)) unlink($tmp);

					$tmp = 'uploads/thumb/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'];
					if(file_exists($tmp)) unlink($tmp);

					$this->Picture_model->delete_picture_admin($pic_id, 0, $type);
	
			}
			redirect('gallery/picture/'.$gallery_id);

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
			$folder = date('Ymd');

			$data_input = $this->input->post();

			//$config['path_thumb'] = './uploads/gallery/thumb/';
			//if(!is_dir($config['path_thumb'])) mkdir($config['path_thumb'], 0777);

			$cpt = count($_FILES['picture_gallery']['name']);
			for($i=0; $i<$cpt; $i++){

				$_FILES['userfile']['name']= $files['picture_gallery']['name'][$i];
				$_FILES['userfile']['type']= $files['picture_gallery']['type'][$i];
				$_FILES['userfile']['tmp_name']= $files['picture_gallery']['tmp_name'][$i];
				$_FILES['userfile']['error']= $files['picture_gallery']['error'][$i];
				$_FILES['userfile']['size']= $files['picture_gallery']['size'][$i];    
				//Debug($_FILES['userfile']);

				if($_FILES['userfile']['error'] == 0){

						//Upload File
						$this->upload->initialize($this->set_upload_options());
						$this->upload->do_upload();

						//Resize Image
						$this->image_lib->clear();
						$this->image_lib->initialize($this->set_resize_options($this->upload->client_name, 165, 100));
						$this->image_lib->resize();

						//Watermark
						//$this->image_lib->initialize($this->set_watermark_options($this->upload->client_name));
						//$this->image_lib->watermark();

						//$picture_obj['ref_id'] = $this->uri->segment(3);
						$picture_obj['ref_id'] = $data_input['gallery_id'];
						$picture_obj['ref_type'] = 3;
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

			redirect(base_url('gallery/picture/'.$data_input['gallery_id']));


	}

	private function set_watermark_options($client_name, $text = "Siamdara"){   

		$config = array();
		$folder = date('Ymd');
		
		$config['source_image'] = './uploads/gallery/'.$folder.'/'.$client_name;
		$config['new_image'] =  './uploads/gallery/'.$folder.'/sd_'.$client_name;
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

	private function set_resize_options($client_name, $width = 250, $height = 170){   

		$config = array();
		$folder = date('Ymd');
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = './uploads/tmp/'.$folder.'/'.$client_name;
		$config['new_image'] = './uploads/gallery/'.$folder.'/'.$client_name;

		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;


		//$config['create_thumb'] = TRUE;
		//$config['width']     = $width;
		//$config['height']   = $height;

		//****Copy Original File to TMP
		$upload_path = './uploads/tmp/'.$folder;
		$tmp = $upload_path.'/'.$client_name;

		$src = fopen($config['new_image'], 'r');
		$dest = fopen($tmp, 'w');

		stream_copy_to_stream($src, $dest);

		return $config;
	}

	private function set_upload_options(){   

		$config = array();
		$folder = date('Ymd');
		
		$config['upload_path'] = './uploads/gallery/';
		if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

		$config['upload_path'] = './uploads/gallery/'.$folder;
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
	
	public function remove_img($dara_profile_id = 0){
			
			$src = $this->input->post('name');

			unlink('uploads/dara/'.$src);
			$obj_data['picture_gallery'] = '';
			if($this->Gallery_model->store($dara_profile_id, $obj_data))
				echo 'Yes';
			else
				echo 'No';

	}

	public function status($id = 0){
				
			if($id == 0){
				$data = array(
						"error" => 'id error'
				);
				return false;
			}else{

					$obj_status = $this->Gallery_model->get_status($id);
					$cur_status = $obj_status[0]['status'];
					$title = $obj_status[0]['title'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;

					$obj_data['status'] = $cur_status;
					if($this->Gallery_model->store2($id, $obj_data)) echo $cur_status;
						//echo "update succedd.";
			}
			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 3,
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

					$obj_status = $this->Gallery_model->get_status($id);
					$cur_status = $obj_status[0]['approve'];
					$title = $obj_status[0]['title'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;

					$obj_data['approve'] = $cur_status;
					if($this->Gallery_model->store2($id, $obj_data)) echo $cur_status;
						//echo "update succedd.";

					//$this->gen_json($id);
			}
			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 3,
								"ref_title" => "[Approve]".$title,
								"action" => 2
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity
	}

	public function save($dara_profile_id = 0){

			$this->load->model('Tags_model');
			//$this->load->model('Picture_model');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			
			$tags_all = $this->Tags_model->getall_tag_pair(5); //tags
			//Debug($this->input->post());
			
			//$breadcrumb[] = $language['dara'];

			$gallery_data = $tag_id = $tag_pair = $picture_obj = array();
			$data_input = $this->input->post();
			$upload_status = "";
			$gallery_id = 0;

			/*$this->load->library('form_validation');
			// field name, error message, validation rules
			$this->form_validation->set_rules('title_en', $language['title'], 'trim|required');
			$this->form_validation->set_rules('title_th	', $language['title'], 'trim|required');
			$this->form_validation->set_rules('detail_en', $language['detail'], 'trim|required');
			$this->form_validation->set_rules('detail_th	', $language['detail'], 'trim|required');

			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">ร—</a><strong>', '</strong></div>');

			if($this->form_validation->run() == FALSE){
					echo "<hr>FALSE";
					Debug($this->form_validation->set_error_delimiters());
			}else{
					echo "<hr>Save";
					//Debug($this->input->post());
					//$this->Gallery_model->store($dara_profile_id, $this->input->post());
			}*/

			$now_date = date('Y-m-d H:i:s');
			$folder = date('Ymd');

			/*$this->load->library('upload', $this->set_upload_options());

			// Alternately you can set preferences by calling the initialize function. Useful if you auto-load the class:
			$this->upload->initialize($this->set_upload_options());

			if ( ! $this->upload->do_upload('picture')){
					$error = array('error' => $this->upload->display_errors());
					$upload_status = $error;
			}else{
					$data = array(
							'admin_menu' => $this->menufactory->getMenu(),
							'upload_data' => $this->upload->data(),
							'upload_status' => 'Success',
							'error' => array()
					);
					$upload_status = "Success";
			}*/

			//Debug($data_input);
			//die();
			//******************* List data input **********************/
			if($data_input)
					foreach($data_input as $key => $val){
							if($key == "gallery_id_en"){
									$new_data['gallery_id'] = $data_input['gallery_id_en'];

							}else if($key == "gallery_id_th"){
									$new_data_th['gallery_id'] = $data_input['gallery_id_th'];

							}else if($key == "gallery_id"){

									if($data_input['gallery_id'] == 0) $new_data['gallery_id'] = $data_input['gallery_id'];

									$new_data['gallery_id2'] = $data_input['gallery_id'];
									$new_data_th['gallery_id2'] = $data_input['gallery_id'];

							}else if($key != "tag_id" && $key != "highlight" && $key != "megamenu"){
									
									if($key == "title_en" || $key == "description_en" || $key == "detail_en"){

											if($key == "title_en"){  
												$new_data['title'] = StripTxt($data_input['title_en']); 
												//$new_data['lang'] = "en"; 
											}
											if($key == "description_en")  $new_data['description'] = $data_input['description_en'];
											if($key == "detail_en")  $new_data['detail'] = $data_input['detail_en'];

									}else if($key == "title_th" || $key == "description_th" || $key == "detail_th"){

											if($key == "title_th"){
												$new_data_th['title'] = StripTxt($data_input['title_th']);
												//$new_data_th['lang'] = "th"; 
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
			
			if($this->upload->client_name){
					//$new_data['picture_gallery'] = $this->upload->client_name;
			}

			/*Debug($new_data);
			Debug($new_data_th);
			Debug($tag_id);
			echo "<hr>";*/
			//die();

			/**************************************** Relate**************************/
			$number_of_relate = 4;
			$tag_relate = $this->Gallery_model->get_relate($new_data['gallery_id2']); //Get relate มาดู ว่ามีกี่อัน
			//Debug($tag_relate);
			//echo "(".$new_data['gallery_id2']." > 0) && (".$new_data['dara_id']." > 0)";

			if($new_data['gallery_id2'] > 0){
					//if(count($tag_relate)<$number_of_relate){
					if(count($tag_relate) == 0){

							$t=0;
							$where_id = array();
							//echo "<br>$number_of_relate<br>";

							if($new_data['dara_id'] > 0)
								$tag_relate = $this->Gallery_model->gen_relate($new_data['dara_id'], $tag_id, $new_data['gallery_id2'], $number_of_relate);
							else
								$tag_relate = $this->Gallery_model->gen_relate(null, $tag_id, $new_data['gallery_id2'], $number_of_relate);

							//if(count($tag_relate) == 0) $tag_relate = $this->Gallery_model->gen_relate(null, $tag_id, $new_data['gallery_id2'], $number_of_relate);

							//Debug("<hr>");
							//Debug($tag_relate);

							//$tag_relate = $this->Column_model->gen_relate(null, $tag_id, $new_data['gallery_id2']);
							//die();

							$max5 = (count($tag_relate) > $number_of_relate) ? $number_of_relate : count($tag_relate);
							//$max5 = ($tag_relate[0]->NumberOfTag > $number_of_relate) ? $number_of_relate : $tag_relate[0]->NumberOfTag;
							//$tag_relate = $this->Gallery_model->gen_relate($new_data['dara_id'], $tag_id, $new_data['gallery_id2'], $number_of_relate);
							//Debug($tag_relate);

							for($t=0;$t<$max5;$t++){

										$number = $t+1;
										//$tag_relate[$t]->gallery_id;
										//echo "<hr>".$number." ".$tag_relate[$t]->gallery_id."<hr>";

										if($tag_relate[$t] != $new_data['gallery_id']){
											$where_id[$t]['gallery_id'] = intval($tag_relate[$t]);
											$where_id[$t]['ref_id'] = intval($new_data['gallery_id']);
											//$where_id[$t]['ref_type'] = 3; //Gallery
											$where_id[$t]['order'] = $number;
										}
							}
							
							//Debug("<hr>gallery_id2=".$new_data['gallery_id2']);
							//Debug($where_id);

							if(count($where_id) > 0) $this->Gallery_model->save_relate($new_data['gallery_id2'], $where_id);
					}
			}
			//die();

			//**************************************** ใส่ Tags ใน Content editor ***************************************
			if($new_data['gallery_id'] == 0){
					//Debug($tags_all);
					if($tags_all){

							$number_tag = count($tags_all);
							for($i=0;$i<$number_tag;$i++){
									
									//$textnormal = $tags_all[$i]->tag_text;
									//$text_replace = "@[".$tags_all[$i]->tag_text."]";

									$textnormal = $tags_all[$i]->tag_text;
									$dara_type_id = $tags_all[$i]->dara_type_id;
									$dara_profile_id = $tags_all[$i]->dara_profile_id;
									$dara_type_name = $tags_all[$i]->dara_type_name;

									$text_replace = '<a href="'.$this->config->config['www'].'/dara/'.$dara_type_id.'/'.$dara_profile_id.'/'.RewriteTitle($dara_type_name).'/'.RewriteTitle($textnormal).'" target=_blank>'.$textnormal.'</a>';

									$new_data['detail'] = str_replace($textnormal, $text_replace, $new_data['detail']);
									//$new_data['description'] = str_replace($textnormal, $text_replace, $new_data['description']);

									$new_data_th['detail'] = str_replace($textnormal, $text_replace, $new_data_th['detail']);
									//$new_data_th['description'] = str_replace($textnormal, $text_replace, $new_data_th['description']);

							}
					}
			}
			//die();			
			//***********************INSERT & UPDATE**************
			if($new_data['gallery_id'] > 0){

						$action = 2;
						$gallery_id = $new_data['gallery_id2'];

						$new_data['lastupdate_by'] = $this->session->userdata('admin_id');
						$new_data_th['lastupdate_by'] = $this->session->userdata('admin_id');
						
						$gallery_id_en = $new_data['gallery_id'];
						$gallery_id_th = $new_data_th['gallery_id'];

						unset($new_data['gallery_id']);
						unset($new_data['gallery_id2']);
						unset($new_data_th['gallery_id']);
						unset($new_data_th['gallery_id2']);

						/*Debug($new_data);
						Debug($new_data_th);

						echo "<br>gallery_id = $gallery_id";*/

						$this->Gallery_model->store($gallery_id_en, $new_data);
						$this->Gallery_model->store($gallery_id_th, $new_data_th);

						//die();
						/*$data = array(
								"admin_menu" => $this->menufactory->getMenu(),
								"ListSelect" => $ListSelect,
								"gallery_list" => $this->Gallery_model->get_gallery(),
								"content_view" => 'gallery/gallery',
								"breadcrumb" => $breadcrumb,
								"error" => 'Cat not add new.'
						);*/

			}else{ //Add New item

						$action = 1;
						$new_data['create_date'] = $now_date;
						$new_data['create_by'] = $this->session->userdata('admin_id');
						$new_data['lang'] = "en"; 

						$new_data_th['create_date'] = $now_date;
						$new_data_th['create_by'] = $this->session->userdata('admin_id');
						$new_data_th['lang'] = "th"; 
						
						$max_id = $this->Gallery_model->get_max_id();
						$gallery_id = $max_id[0]['max_id'] + 1;
						//Debug($max_id);

						$new_data['gallery_id'] = $gallery_id;
						$new_data['gallery_id2'] = $gallery_id;
						$new_data_th['gallery_id2'] = $gallery_id;

						$new_data['order_by'] = 1;
						$new_data_th['order_by'] = 1;

						/*Debug($new_data);
						Debug($new_data_th);
						die();*/

						//Add 1 all record
						$this->Gallery_model->update_orderadd();

						$this->Gallery_model->store(0, $new_data);
						$this->Gallery_model->store(0, $new_data_th);

						/*$data = array(
								"admin_menu" => $this->menufactory->getMenu(),
								"ListSelect" => $ListSelect,
								"gallery_list" => $this->Gallery_model->get_gallery(null, null, $language['lang']),
								"content_view" => 'gallery/gallery',
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
										$tag_pair[$i]["ref_id"] = $gallery_id;
										$tag_pair[$i]["ref_type"] = 3;
										$tag_pair[$i]["create_date"] = $now_date;
										$next = $i;
							}
					}
					//echo "next=$next";
					//Debug($tag_pair);
					//Debug($tag_id);

					if($tag_id){
							if(!isset($next)) $next = 0;
							$begin = $next + 1;
							$endtag = count($tag_id) + $begin;
							$t = 0;
							for($i=$begin;$i<$endtag;$i++){
										
										$tag_pair[$i]["tag_id"] = $tag_id[$t];
										$tag_pair[$i]["ref_id"] = $gallery_id;
										$tag_pair[$i]["ref_type"] = 3;
										$tag_pair[$i]["create_date"] = $now_date;
										$t++;
							}
							//Debug($tag_pair);					
							$this->Tags_model->store_tag_pair($tag_pair, $clear);
					}
			}
			//die();

			//Debug($new_data);
			//Debug($data_input);

			/***************************Picture upload*****************************/
			/*if($upload_status == "Success"){
						$picture_obj['ref_id'] = $gallery_id;
						$picture_obj['ref_type'] = 3;
						$picture_obj['file_name'] = $this->upload->client_name;
						//$picture_obj['title'] = StripTxt($data_input['title']);

						if($language['lang'] == 'en')
							$picture_obj['caption'] = StripTxt($data_input['title_en']);
						else
							$picture_obj['caption'] = StripTxt($data_input['title_th']);

						$picture_obj['folder'] = $folder;
						$picture_obj['create_date'] = $now_date;
						$picture_obj['create_by'] = $this->session->userdata('admin_id');
						$picture_obj['status'] = 1;
						$this->Picture_model->store(0, $picture_obj);
			}*/

			//**************Set Highlight
			if(isset($data_input['highlight'])){

				//Debug($data_input['highlight']);
				$data_highlight = array(
						"news_id" => $gallery_id,
						"ref_type" => 3,
						"order" => 0
				);							
				$this->Gallery_model->set_highlight($gallery_id, $data_highlight);
				$this->Gallery_model->update_order_highlight();
			}else{
				$this->Gallery_model->remove_highlight($gallery_id);
				//echo "new_id = $new_id";
			}
			//die();

			//**************Set Megamenu
			if(isset($data_input['megamenu'])){

				//Debug($data_input['highlight']);
				$data_megamenu = array(
						"id" => $gallery_id,
						"category_id" => 0,
						"ref_type" => 3,
						"order" => 0
				);							
				$this->Gallery_model->set_megamenu($gallery_id, $data_megamenu);
				//echo "new_id = $new_id";
				//die();
			}else{
				$this->Gallery_model->remove_megamenu($gallery_id);
				//echo "new_id = $new_id";
			}

			/**************************************** Relate**************************/
			//$number_of_relate = 4;
			//$tag_relate = $this->Gallery_model->get_relate($gallery_id);


			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $gallery_id,
								"ref_type" => 3,
								"ref_title" => StripTxt($data_input['title_th']),
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			//die();
			//$this->load->view('template/template',$data);
			//redirect(base_url('gallery'));
			redirect(base_url('gallery/edit/'.$gallery_id));
	}

	public function set_order_picture(){
					
					$lang = $this->lang->language;
					
					$galleryid = $this->input->post('galleryid');
					$json = $this->input->post('json');

					$obj = json_decode($json);
					$update_data = array();

					//echo "galleryid = $galleryid<br>";
					//Debug($obj);

					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj);$i++){
								//echo $num.'.) '.$obj[$i]->id.' : '.$galleryid.' ==> '.$num.'<br>';
								unset($update_data);
								$update_data['order'] = $num;
								$this->Gallery_model->set_order_picture($galleryid, $obj[$i]->id, $update_data);
								$num++;
						}
					echo $lang['update'].' '.$lang['success'].'.';
	}

	public function set_default($id = 0){
			
			$this->load->model('Picture_model');

			$typegallery = 3;

			$data_input = $this->input->get();
			//Debug($data_input);

			$id = $data_input['id'];
			$ref_id = $data_input['ref_id'];

			//echo "Set default... $id";
			//echo "<br>ref_id... $ref_id";

			$gallery_list =  $this->Gallery_model->get_data($ref_id);

			//Debug($gallery_list);
			//Die();

			$gallery_id = $gallery_list[0]['gallery_id2'];
			$title = "Set default picture ".$gallery_list[0]['title'];
			//$order_by = $gallery_list[0]['order_by'];

			$this->Picture_model->set_default($id, $ref_id, $typegallery);

			//**************Log activity
			$action = 2;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $ref_id,
								"ref_type" => 3,
								"ref_title" => $title,
								"action" => $action
			);
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('gallery/picture/'.$ref_id);
			die();
	}

	public function delete($id){
			
			$cat = 0;
			$cat = $this->input->get('cat');

			if($cat > 0 && $id > 0){
					echo "Deleting... id = $id...cat = $cat ";
					
					$item = $this->Gallery_model->get_data($id);
					$title = $item[0]['title'];
					$order_by = $item[0]['order_by'];

					//Debug($item);

					$this->Gallery_model->delete_data($id);

					//**************Order New
					$this->Gallery_model->update_orderdel($cat, $order_by);

					//**************Log activity
					$action = 3;
					$log_activity = array(
										"admin_id" => $this->session->userdata('admin_id'),
										"ref_id" => $id,
										"ref_type" => 3,
										"ref_title" => $title,
										"action" => $action
					);			
					//$this->Admin_log_activity_model->store($log_activity);
					//**************Log activity
					redirect('gallery');
					die();
			}else
					die('Can not delete.');
	}	
	
	/***********************************Relate*********************************/
	public function set_order_relate(){
					$lang = $this->lang->language;
					$update_data = array();
					
					$galleryid = $this->input->post('galleryid');
					$json = $this->input->post('json');
					$obj = json_decode($json);

					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj);$i++){
								//echo $num.'.) '.$obj[$i]->id.' : '.$newsid.' ==> '.$num.'<br>';
								unset($update_data);
								$update_data['order'] = $num;
								$this->Gallery_model->set_order_relate($galleryid, $obj[$i]->id, $update_data);
								$num++;
						}
						
					$gallery = $this->Gallery_model->get_status($galleryid);
					$action = 2;
					$log_activity = array(
							"admin_id" => $this->session->userdata('admin_id'),
							"ref_id" => $galleryid,
							"ref_type" => 3,
							"ref_title" => "Set Order Gallery relate: ".$gallery[0]['title'],
							"action" => $action
					);			
					$this->Admin_log_activity_model->store($log_activity);

					echo $lang['update'].' '.$lang['success'].'.';
	}	

	public function delete_relate($gallery_id = 0){

					$lang = $this->lang->language;
					$update_data = array();
					
					$ref_id = $this->input->get('ref_id');
					$picture_id = $this->input->get('picture_id');

					/*echo "gallery_id = $gallery_id<br>";
					Debug($this->input->get());
					die();*/

					if($picture_id > 0) $this->Gallery_model->delete_relate($picture_id);
					if($ref_id > 0) $this->Gallery_model->delete_relate(0, $ref_id);
						
					$gallery = $this->Gallery_model->get_status($gallery_id);
					$action = 3;
					$log_activity = array(
							"admin_id" => $this->session->userdata('admin_id'),
							"ref_id" => $gallery_id,
							"ref_type" => 3,
							"ref_title" => "Delete relate: ".$gallery[0]['title'],
							"action" => $action
					);			
					$this->Admin_log_activity_model->store($log_activity);

					redirect('gallery/edit/'.$gallery_id.'?success='.$lang['update'].' '.$lang['success']);
	}	


	public function search_relate(){
			
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			if($this->input->server('REQUEST_METHOD') === 'GET'){

					$search_form = $this->input->get();
					if(isset($search_form['kw'])){
							
						$galleryid = $search_form['galleryid'];
						$gallery_list = $this->Gallery_model->get_data(null, $search_form['kw']);
						if($gallery_list){
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

								$maxlist = count($gallery_list);
								for($i=0;$i<$maxlist;$i++){
										//$url = "http://www.Tmon.com/news/".urlencode($news_list[$i]['title']).".html";
										//$url = "http://www.Tmon.com/news/".$news_list[$i]['news_id2'].".html";

										$img = base_url('uploads/thumb').'/'.$gallery_list[$i]['folder'].'/'.$gallery_list[$i]['file_name'];
										//$img = 'uploads/thumb/'.$news_list[$i]['folder'].'/'.$news_list[$i]['file_name'];

										if($gallery_list[$i]['file_name'] != "")
											$tags_img = (file_exists('uploads/thumb/'.$gallery_list[$i]['folder'].'/'.$gallery_list[$i]['file_name'])) ? "<img src=".$img." height='50'>" : "";
										else
											$tags_img = "";

										$status = ($gallery_list[$i]['status'] == 1) ? 'checked' : '';
										$iconstatus = '<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-3" type="checkbox" '.$status.' disabled>
														<span class="lbl"></span>
													</label>
												</div>';
										//$addurl = 'javascript:void(0);';
										$addurl = base_url('gallery/add_relate').'?gallery_id='.$gallery_list[$i]['gallery_id2'].'&ref_id='.$galleryid;
										$edit_data = base_url('gallery/edit/'.$gallery_list[$i]['gallery_id2']);

										$iconadd = '<a href="'.$addurl.'" data-value="'.$gallery_list[$i]['gallery_id2'].'" class="add_relate"><i class="ace-icon glyphicon glyphicon-plus">Add relate</i></a>';

										echo "<tr>
										<td>".$gallery_list[$i]['gallery_id2']."</td>
										<td>".$tags_img."</td>
										<td><a href='".$edit_data."' target=_blank>".$gallery_list[$i]['title']."</a></td>
										<td>".$gallery_list[$i]['create_date']."</td>
										<td>".$iconstatus."</td>
										<td>".$gallery_list[$i]['countview']."</td>
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
			$gallery_id = $data_input['gallery_id'];
			//Debug($data_input);
			//Debug("add_relate($ref_id, $gallery_id)");
			//die();
			$this->Gallery_model->add_relate($ref_id, $gallery_id);
			echo '<script type="text/javascript">
			<!--
				window.location="'.base_url("gallery/edit").'/'.$ref_id.'";
			//-->
			</script>';
	}

	function auto_order($cat = 0){

				$this->load->model('gallery_type_model');
				$language = $this->lang->language;
				$cat_view = array();

				//$gallery_type_list = $this->gallery_type_model->getSelectCat($gallery_type_id, $language['all']);
				//if($cat != 0) $cat_view['category_id'] = $cat;

				$gallery_list = $this->Gallery_model->get_data(null, null, $cat);

				if(isset($gallery_list)){
						for($i=0;$i<count($gallery_list);$i++){
									$number = $i + 1;
									$gallery_id = $gallery_list[$i]['gallery_id2'];
									$title = $gallery_list[$i]['title'];
									$order_by = $gallery_list[$i]['order_by'];
									$data = array(
											"order_by" => $number
									);
									echo "$gallery_id $title  [$order_by ==> $number]<br>";
									$this->Gallery_model->store2($gallery_id, $data);
						}
						echo "<hr>UPDATE Order cat = $cat  total = ".count($gallery_list)." record.";
				}
	}

}
