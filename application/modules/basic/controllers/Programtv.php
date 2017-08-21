<?php
header('Content-Type: text/html; charset=utf-8');

class Programtv extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Programtv_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$this->load->model('Channel_model');
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		//$breadcrumb[] = '<a href="'.base_url('programtv').'">'.$language['programtv'].'</a>';
		$breadcrumb[] = $language['programtv'];
		//"admin_menu" => $this->menufactory->getMenu(),

		$channel_id = 0;
		$filter_date = '';
		$keyword = '';

		if($this->input->server('REQUEST_METHOD') === 'POST'){
				$input_data = $this->input->post();
				if(isset($input_data['channel_id'])){
					$channel_id = $input_data['channel_id'];
					$filter_date = DateDB($input_data['date']);
				}else{
					$keyword = $input_data['keyword'];
				}
		}
		//echo "get_content_all($channel_id, $filter_date, $keyword);";
		$programtv_list = $this->Programtv_model->get_content_all($channel_id, $filter_date, $keyword);
		$data = array(				
				"programtv_list" => $programtv_list,
				"channel_list" => $this->Channel_model->getSelect($channel_id, 'channel_id', '-- Select --'),
				"channel_id" => $channel_id,
				"content_view" => 'programtv/programtv',
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb
		);

		$this->load->view('template/template',$data);
	}

	public function add(){	
		
			$this->load->model('Channel_model');
			$this->load->model('Tags_model');
			$this->load->helper('ckeditor');

			$Path_CKfinder = base_url('plugins/ckeditor-integrated/ckfinder');
			$Path_CKeditor = base_url('plugins/ckeditor-integrated/ckeditor');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			$tags_list = $this->Tags_model->getSelect();

			$breadcrumb[] = '<a href="'.base_url('programtv').'">'.$language['programtv'].'</a>';
			$breadcrumb[] = $language['add'];

			$now_date = date('Y-m-d');

			if($this->input->server('REQUEST_METHOD') === 'POST'){
					$channel_id = $this->input->post('channel_id');
					$programtv_other = $this->Programtv_model->get_content_in_date($channel_id, $now_date);
			}else{
					$channel_id = null;
					$programtv_other = null;
			}

			$now_date = date('d-m-Y');

			//Debug($channel_id);
						
			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(
						"channel_list" => $this->Channel_model->getSelect($channel_id),
						"programtv_other" => $programtv_other,
						"content_view" => 'programtv/programtv_add',
						"now_date" => $now_date,
						"tags_list" => $tags_list,
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);

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

			$data['description_th'] = $data['description_en'];
			$data['description_th']['id'] = 'description_th';

			$this->load->view('template/template',$data);
	}

	public function import_csv(){	
		
			 $this->load->model('Channel_model');
			 $this->load->model('Tags_model');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			$breadcrumb[] = '<a href="'.base_url('programtv').'">'.$language['programtv'].'</a>';
			$breadcrumb[] = $language['import_csv'];

			//$now_date = date('Y-m-d');
			$now_date = date('d-m-Y');

			$channel_id = null;
			$programtv_other = null;
			//Debug($channel_id);

			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(
						"channel_list" => $this->Channel_model->getSelect($channel_id),
						//"programtv_other" => $programtv_other,
						"content_view" => 'programtv/import_csv',
						"now_date" => $now_date,
						//"tags_list" => $tags_list,
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function import_files(){

			$this->load->model('Tags_model');
			$this->load->library('image_lib');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			//$breadcrumb[] = $language['programtv'];
			$data = $data_th = array();
			
			//if save button was clicked, get the data sent via post
			if ($this->input->server('REQUEST_METHOD') === 'POST'){
					//$this->input->user_agent
					//form validation
					$this->form_validation->set_rules('title_th', 'title_th', 'required');
					$this->form_validation->set_rules('title_en', 'title_en', 'required');
					$this->form_validation->set_rules('date', 'date', 'required');
					$this->form_validation->set_rules('time', 'time', 'required');
					$this->form_validation->set_rules('time2', 'time2', 'required');

					$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

					$admin_id = $this->session->userdata('admin_id');

					$now_date = date('Y-m-d H:i:s');
					$folder = date('Ymd');
					$admin_id = $this->session->userdata('admin_id');

					$data_input = $this->input->post();

					//Debug($this->input->post());
					//Debug($_FILES);

					$channel_id = $this->input->post('channel_id');
					$date = DateDB($this->input->post('date'));
					$status = $this->input->post('status');

					$loadfilename = $_FILES['csv_file']['name'];
					$tmp_name = $_FILES['csv_file']['tmp_name'];
					$filetype = $_FILES['csv_file']['type'];
					$size = $_FILES['csv_file']['size'];
					
					/*echo "loadfilename=$loadfilename<br>";
					echo "tmp_name=$tmp_name<br>";
					echo "filetype=$filetype<br>";
					echo "size=$size<br>";*/

					$query_add="";
					$i=0;

					$get_max_id = $this->Programtv_model->get_max_id();
					$max_id = $get_max_id[0]['max_id'];

					if($_FILES['csv_file']['error'] == 0) {

							//echo "Read file $loadfilename  waiting...........<br>";
							//copy($tmp_name, "user/".$usrfile_name);
							$fd = fopen ($_FILES['csv_file']['tmp_name'], "r");
							while (!feof ($fd)) {

										$buffer = fgets($fd, 4096);
										$arr=explode(",", $buffer);
										//echo "$buffer <br />";
										$time = trim($arr[0]);
										if($time != ''){
												
												$max_id++;
												$arr_time = explode("-", $time);

												if(count($arr_time) > 1){												
													$data[$i]['time'] = trim($arr_time[0]);
													$data[$i]['time2'] = trim($arr_time[1]);
													$data_th[$i]['time'] = trim($arr_time[0]);
													$data_th[$i]['time2'] = trim($arr_time[1]);
												}else{
													$data[$i]['time'] = trim($time);
													$data[$i]['time2'] = '00:00:00';
													$data_th[$i]['time'] = trim($time);
													$data_th[$i]['time2'] = '00:00:00';
												}
												
												$data[$i]['program_id2'] = $max_id;
												$data[$i]['channel_id'] = $channel_id;
												$data[$i]['date'] = $date;												
												$data[$i]['lang'] = 'en';												
												$data[$i]['title'] = Qtitle(iconv("windows-874","UTF-8",$arr[1]));
												$data[$i]['status'] = $status;
												$data[$i]['create_date'] = $now_date;
												$data[$i]['create_by'] = $admin_id;

												$data_th[$i]['program_id2'] = $max_id;
												$data_th[$i]['channel_id'] = $channel_id;
												$data_th[$i]['date'] = $date;												
												$data_th[$i]['lang'] = 'th';
												$data_th[$i]['title'] = Qtitle(iconv("windows-874","UTF-8",$arr[1]));
												$data_th[$i]['status'] = $status;
												$data_th[$i]['create_date'] = $now_date;
												$data_th[$i]['create_by'] = $admin_id;
												$i++;
												$max_id++;

										}
							}
				}

				//Debug($data);
				//Debug($data_th);
				//die();
				$this->Programtv_model->add_batch($data);
				$this->Programtv_model->add_batch($data_th);
				/**************Log activity**********/
				$log_activity = array(
										"admin_id" => $this->session->userdata('admin_id'),
										"ref_id" => 0,
										"ref_type" => 6,
										"ref_title" => 'import csv file ของ channel id '.$channel_id,
										"action" => 1
				);			
				$this->Admin_log_activity_model->store($log_activity);
				/**************Log activity***********/
				redirect('programtv');
				die();
			}
	}
		
	public function edit($id = 0){	
		
			$this->load->model('Channel_model');
			$this->load->model('Tags_model');
			$this->load->helper('ckeditor');

			$Path_CKfinder = base_url('plugins/ckeditor-integrated/ckfinder');
			$Path_CKeditor = base_url('plugins/ckeditor-integrated/ckeditor');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('programtv').'">'.$language['programtv'].'</a>';
			$breadcrumb[] = $language['edit'];

			$programtv_arr = $this->Programtv_model->get_content_id($id);
			$channel_list = $this->Channel_model->getSelect($programtv_arr[0]['channel_id']);

			$programtv_other = $this->Programtv_model->get_content_in_date($programtv_arr[0]['channel_id'], $programtv_arr[0]['date']);
			
			$tag_id = array();
			$sel_tags = $this->Tags_model->get_tag_pair($id, 6);
			//Debug($sel_tags);
			if($sel_tags)
				for($i=0;$i<count($sel_tags);$i++){
					@$tag_id[$i]->value = $sel_tags[$i]->tag_id;
				}
			$tags_list = $this->Tags_model->getSelect($tag_id);
			//Debug($programtv_arr);
			//die();
			//"admin_menu" => $this->menufactory->getMenu(),
			if($id > 0){
					$data = array(
						"programtv_arr" => $programtv_arr,
						"programtv_other" => $programtv_other,
						"content_view" => 'programtv/programtv_edit',
						"channel_list" => $channel_list,
						"tags_list" => $tags_list,
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
					);

			}else{

					$data = array(
						"programtv_list" => $this->Programtv_model->get_content_all(),
						"content_view" => 'programtv/programtv',
						"tags_list" => $tags_list,
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
						"error" => 'กรุณาเลือก Tags ก่อนแก้ไข'
					);			
			}
			//Debug($data );
			//die();
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

			$data['description_th'] = $data['description_en'];
			$data['description_th']['id'] = 'description_th';

			$this->load->view('template/template',$data);
	}
	
	public function status($id = 0){

			//$admin_id = $this->session->userdata('admin_id');
			//$admin_type = $this->session->userdata('admin_type');
			//$ListSelect = $this->Api_model->user_menu($admin_type);
				
			if($id == 0){
				$data = array(
						"error" => 'id error'
				);
				return false;
			}else{
				
				$obj_status = $this->Programtv_model->get_status($id);
				$cur_status = $obj_status[0]['status'];
				//Debug($cur_status);
				
				if($cur_status == 0) $cur_status = 1;
				else $cur_status = 0;

				$this->Programtv_model->status_programtv($id, $cur_status);
				//Debug($this->db->last_query());
				
				//$data = array(
						//"cat_arr" => $this->Programtv_model->status_programtv($id, $cur_status),
						//"content_view" => 'programtv/programtv_edit',
						//"ListSelect" => $ListSelect,
						//"error" => array()
				//);
				echo $cur_status;

				//return $cur_status;
			}

			//**************Log activity
			$log_activity = array(
									"admin_id" => $this->session->userdata('admin_id'),
									"ref_id" => $id,
									"ref_type" => 6,
									"ref_title" => $obj_status[0]['title'].' [Status]',
									"action" => 2
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity
			
		//$this->load->view('template/template',$data);
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

			$breadcrumb[] = '<a href="'.base_url('programtv').'">'.$language['programtv'].'</a>';
			$breadcrumb[] = '<a href="'.base_url('programtv/edit').'/'.$this->uri->segment(3).'">'.$language['edit'].'</a>';
			$breadcrumb[] = $language['picture'];

			//$picture_list = $this->Api_model->get_picture($id, 0, 6);
			$programtv = $this->Programtv_model->get_content_id($id);

			//Debug($picture_list);
			//Debug($programtv);
			//die();

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"dara_id" => $id,
					"programtv_list" => $programtv[0],
					"content_view" => 'programtv/programtv_picture',
					"breadcrumb" => $breadcrumb
			);
			$this->load->view('template/template',$data);
	}

	public function remove_img(){
			
			$src = $this->input->post('name');
			$id = $this->input->post('v');

			$programtv_arr = $this->Programtv_model->get_content_id($id);
			
			if(file_exists('./uploads/program/'.$src)) unlink('uploads/program/'.$src);
			$obj_data['picture'] = '';
			if($this->Programtv_model->store($id, $obj_data))
				echo 'Yes';
			else
				echo 'No';

			//**************Log activity
			$log_activity = array(
									"admin_id" => $this->session->userdata('admin_id'),
									"ref_id" => $id,
									"ref_type" => 6,
									"ref_title" => $src.' from '.$programtv_arr[0]['title'],
									"action" => 3
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

	}

	private function set_upload_options($type = ''){   

		$config = array();
		$folder = date('Ymd');
			
		$config['upload_path'] = './uploads/program/';
		if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

		$upload_path = 'uploads/tmp/';
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		$upload_path = 'uploads/tmp/program/';
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		if($type == '')
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
		else
			$config['allowed_types'] = $type;

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
			$sourcefile = './uploads/program/'.$file;
			$sourcefile_tmp = './uploads/tmp/program/'.$file;

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
						//if($key == "program_id") $program_id = $val;
						//if($key == "folder") $folderdb = $val;
						if($key == "file") $file = $val;
						if($key == "watermark") $watermark = $val;
				}
			//Debug($inputdata);
			//die();
			$type = 'programtv';
			$folder = 'program';

			switch($watermark){
						case "center" : $picture_list = $this->Picture_model->watermark($file, $folder, $type); break; //Logo ขนาดเล็ก
						case "horizontal" : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 1); break; //แนวนอน
						case "vertical" : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 2); break; //แนวตั้ง
						case "logo" :
						default : $picture_list = $this->Picture_model->watermark($file, $folder, $type, 3); break; //Logo ขนาดใหญ่
			}

			//Debug($inputdata);
			//die();
			redirect('programtv/picture/'.$id);
			//http://localhost/Tmon_admin/news/picture_edit/121?news_id=43
			die();	
	}
	
	public function save(){

		$this->load->model('Tags_model');
		$this->load->library('image_lib');

		$language = $this->lang->language;
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

		$breadcrumb[] = $language['programtv'];
		$tag_id = $data_store_en = $data_store_th = array();
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('title_th', 'title_th', 'required');
				$this->form_validation->set_rules('title_en', 'title_en', 'required');
				$this->form_validation->set_rules('date', 'date', 'required');
				$this->form_validation->set_rules('time', 'time', 'required');
				$this->form_validation->set_rules('time2', 'time2', 'required');

				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');

				$now_date = date('Y-m-d H:i:s');
				$folder = date('Ymd');

				$data_input = $this->input->post();

			/******************UPLOAD Files*************/
			$this->load->library('upload', $this->set_upload_options());

			$this->upload->initialize($this->set_upload_options());
			if ( ! $this->upload->do_upload('picture')){
					$error = array('error' => $this->upload->display_errors());
					$data['upload_status'] = $error;
			}else{
					$data = array(
							'admin_menu' => $this->menufactory->getMenu(),
							'upload_data' => $this->upload->data(),
							'upload_status' => 'Success',
					);

					$this->image_lib->clear();
					//$this->image_lib->initialize($this->set_uploadto_tmp($this->upload->client_name));
					//$this->image_lib->resize();
					//$data['upload_status'] = $data;
			}

			if($this->upload->client_name){
					$data_input['picture'] = $this->upload->client_name;
			}
			/************************************************/

			//******************* List data input **********************/
			if($data_input)
					foreach($data_input as $key => $val){

							if($key != "editorCurrent" && $key != "tag_id" && $key != "editorCurrent2" && $key != "editorh3" && $key != "editorh4" && $key != "program_id" && $key != "program_id2"){
									
									if($key == "title_en" || $key == "description_en"){

											if($key == "title_en"){  
												$data_store_en['title'] = StripTxt($data_input['title_en']); 
												$data_store_en['lang'] = "en"; 
											}
											if($key == "description_en")  $data_store_en['description'] = $data_input['description_en'];

									}else if($key == "title_th" || $key == "description_th"){

											if($key == "title_th"){
												$data_store_th['title'] = StripTxt($data_input['title_th']);
												$data_store_th['lang'] = "th"; 
											}
											if($key == "description_th")  $data_store_th['description'] = $data_input['description_th'];

									}else{
											$data_store_en[$key] = $val;
											$data_store_th[$key] = $val;
									}
							}

							if($key == "tag_id"){
									foreach($val as $key2 => $val2){			
											$tag_id[] = $val2;
									}
							}

					}
			//******************* List data input **********************/
			$dateDB = DateDB($data_store_en['date']);
			$data_store_en['date'] = $dateDB;
			$data_store_th['date'] = $dateDB;

			/*Debug($data_store_en);
			Debug($data_store_th);
			die();*/
				
				//$tag_id = $data_input['tag_id'];

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						if($this->input->post('program_id') > 0){ //UPDATE SQL

								//$program_id = $this->input->post('program_id');
								$programtv_id_th = $this->input->post('program_id');
								$programtv_id_en = $this->input->post('program_id2');

								$data_store_en['lastupdate_date'] = $now_date;
								$data_store_th['lastupdate_date'] = $now_date;
								$data_store_en['lastupdate_by'] = $admin_id;
								$data_store_th['lastupdate_by'] = $admin_id;

								/*$data_store_en = array(
											'channel_id' => $data_input['channel_id'],
											'date' => $dateDB,
											'time' => $data_input['time'],
											'title' => $data_input['title_en'],
											'description' => $data_input['description_en'],
											'url' => $data_input['url'],
											'lang' => 'en',
											'status' => $data_input['status'],
											'lastupdate_date' => $now_date,
											'lastupdate_by' => $admin_id
										);
								$data_store_th = array(
											'channel_id' => $data_input['channel_id'],
											'date' =>$dateDB,
											'time' => $data_input['time'],
											'title' => $data_input['title_th'],
											'description' => $data_input['description_th'],
											'url' =>$data_input['url'],
											'lang' => 'th',
											'status' => $data_input['status'],
											'lastupdate_date' => $now_date,
											'lastupdate_by' => $admin_id
										);*/

								$this->Programtv_model->store($programtv_id_en, $data_store_en);
								$this->Programtv_model->store($programtv_id_th, $data_store_th);
								$action = 2;
								
						}else{ //INSERT SQL

								//$order_by = $this->Channel_model->get_max_order();
								//$order = $order_by[0]['max_order'] +1;

								$get_max_id = $this->Programtv_model->get_max_id();
								$max_id = $get_max_id[0]['max_id'] +1;
								$programtv_id_en = $max_id;

								//Debug($data_input);
								//die();
								$data_store_en['program_id2'] = $max_id;
								$data_store_th['program_id2'] = $max_id;

								$data_store_en['create_date'] = $now_date;
								$data_store_th['create_date'] = $now_date;
								$data_store_en['create_by'] = $admin_id;
								$data_store_th['create_by'] = $admin_id;

								/*$data_store_en = array(
											'program_id2' => $max_id,
											'channel_id' => $data_input['channel_id'],
											'date' => $dateDB,
											'time' => $data_input['time'],
											'time2' => $data_input['time2'],
											'title' => $data_input['title_en'],
											'title' => $data_input['title_en'],
											'lang' => 'en',
											'status' =>  $data_input['status'],
											'create_date' => $now_date,
											'create_by' => $admin_id
										);

								$data_store_th = array(
											'program_id2' => $max_id,
											'channel_id' => $data_input['channel_id'],
											'date' => $dateDB,
											'time' => $data_input['time'],
											'time2' => $data_input['time2'],
											'title' => $data_input['title_th'],
											'lang' => 'th',
											'status' => $data_input['status'],
											'create_date' => $now_date,
											'create_by' => $admin_id
										);*/

								$this->Programtv_model->store(0, $data_store_en);
								$this->Programtv_model->store(0, $data_store_th);
								$action = 1;
						}

				}else{

						//$data['error'] = '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>\', \'</strong></div>';
						//$data['main_content'] = 'programtv/programtv/add';
						
						//"admin_menu" => $this->menufactory->getMenu(),
						$data = array(									
									"content_view" => 'programtv/programtv',
									"ListSelect" => $ListSelect,
									"error" =>  'Please, enter all field'
						);
						$this->load->view('template/template',$data);
				}
        }

		//die();
		//**************Log activity
		$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $programtv_id_en,
								"ref_type" => 6,
								"ref_title" => trim($data_store_th['title']),
								"action" => $action
		);			
		$this->Admin_log_activity_model->store($log_activity);
		//**************Log activity

		if ($this->form_validation->run()){
				//Add tags to DB
				if($tag_id){
							for($i=0;$i<count($tag_id);$i++){
										$tag_pair[$i]["tag_id"] = $tag_id[$i];
										$tag_pair[$i]["ref_id"] = $programtv_id_en;
										$tag_pair[$i]["ref_type"] = 6;
										$tag_pair[$i]["create_date"] = $now_date;
							}
							$this->Tags_model->store_tag_pair($tag_pair);
				}
				
				$data = array(				
							"programtv_list" => $this->Programtv_model->get_content_all(),
							"content_view" => 'programtv/programtv',
							"ListSelect" => $ListSelect,
							"breadcrumb" => $breadcrumb,
							"success" =>  'Save program complete.'
				);
				$this->load->view('template/template',$data);
		}

    }

	public function delete($id){
			echo "Deleting... $id";

			$OBJdata = $this->Programtv_model->get_content_id($id);
			$title = $OBJdata[0]['title'];
			$this->Programtv_model->delete_program($id);

			//**************Log activity************
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 6,
								"ref_title" => $title,
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity***********
			redirect('programtv');
			die();
	}
}