<?php

class Brand extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Brand_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		$breadcrumb[] = $language['brand'];

		$data = array(				
				"brand_list" => $this->Brand_model->get_data(),
				"content_view" => 'brand/brand',
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb,
		);

		$this->load->view('template/template',$data);
	}
	
	public function view_cat($id){
	
		$lang = $this->lang->language['lang'];
		
		$brand = $this->Brand_model->get_data($id);
		//debug($brand);
		
		if($brand)
			for($i=0;$i<count($brand);$i++){
	
					if(($lang == 'th') && ($brand[$i]['lang'] == 'th'))
							$cat_name =  $brand[$i]['brand_name'];
					else if(($lang == 'en') && ($brand[$i]['lang'] == 'en'))
							$cat_name =  $brand[$i]['brand_name'];
							
			}
		return $cat_name;	
	}
	
	public function add(){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('brand').'">'.$language['brand'].'</a>';
			$breadcrumb[] = $language['add'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(						
						"content_view" => 'brand/brand_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('brand').'">'.$language['brand'].'</a>';
			$breadcrumb[] = $language['edit'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			if($id > 0){
				$data = array(						
					"brand_arr" => $this->Brand_model->get_data($id),
					"content_view" => 'brand/brand_edit',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
				);
			}else{
				$data = array(				
					"brand_list" => $this->Brand_model->get_data(),
					"content_view" => 'brand/brand',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					"error" => 'กรุณาเลือก Tags ก่อนแก้ไข'
				);			
			}

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
			
			$obj_status = $this->Brand_model->get_status($id);
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;

			$this->Brand_model->status_brand($id, $cur_status);

			//$data = array(
					//"cat_arr" => $this->Brand_model->status_brand($id, $cur_status),
					//"content_view" => 'brand/brand_edit',
					//"ListSelect" => $ListSelect,
					//"error" => array()
			//);
			echo $cur_status;

			return $cur_status;
		}
	
		//$this->load->view('template/template',$data);
	
	}

	public function remove_img($id = 0){
			$src = $this->input->post('img');

			if(file_exists('uploads/magazine/'.$src)) unlink('uploads/magazine/'.$src);
			if(file_exists('uploads/magazine/thumb/'.$src)) unlink('uploads/magazine/thumb/'.$src);
			//$obj_data['logo'] = '';

			if($this->Brand_model->remove_img($id))
				echo 'Yes';
			else
				echo 'No';
	}

	private function set_upload_options(){   

		$config = array();
		//$folder = date('Ymd');
		
		$config['upload_path'] = './uploads/magazine';
		if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size']      = '0';
		$config['overwrite']     = FALSE;

		return $config;
	}

	private function set_resize_options($client_name, $folder = 'thumb', $width = 200, $height = 256){   

		$config = array();
		if($folder == '') $folder = date('Ymd');
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = './uploads/magazine/'.$client_name;
		$config['new_image'] = './uploads/magazine/'.$folder.'/'.$client_name;

		$config['upload_path'] = './uploads/magazine/'.$folder.'/';
		if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

		$config['create_thumb'] = FALSE;	//สร้าง Thumb โดย CI
		$config['maintain_ratio'] = TRUE;
		$config['width']     = $width;
		$config['height']   = $height;

		/****Copy Original File to TMP
		$upload_path = './uploads/tmp/'.$folder;
		$tmp = $upload_path.'/'.$client_name;

		$src = fopen($config['new_image'], 'r');
		$dest = fopen($tmp, 'w');
		stream_copy_to_stream($src, $dest);*/

		return $config;
	}
	
	public function save(){

		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->library('image_lib');

		$language = $this->lang->language;
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

		$breadcrumb[] = $language['brand'];

		//echo "<br>REQUEST_METHOD == ".$this->input->server('REQUEST_METHOD');
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				
				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('brand_name', 'brand_name', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
				$admin_id = $this->session->userdata('admin_id');
				$now_date = date('Y-m-d H:i:s');
				$logo_file = '';
				
				/*************UPLOAD FILE***************/
				if($_FILES['logo']['error'] == 0){

						$this->upload->initialize($this->set_upload_options());
						if ( ! $this->upload->do_upload('logo')){
							$error = array('error' => $this->upload->display_errors());
							$upload_status = $error;
						}else{
							$upload_status = "Success";
						}
						//Debug($upload_status);

						//Resize Image
						//if($upload_status == "Success" && ($this->input->post('resize'))){
								$config_resize = $this->set_resize_options($this->upload->client_name);
								//Debug($config_resize);
								$this->image_lib->clear();
								$this->image_lib->initialize($config_resize);
								$this->image_lib->resize();
						//}
						if($this->upload->client_name){
								$logo_file = $this->upload->client_name;
						}
				}
				/******************************************/

				//$lang_en = array( "lang" => 'en');
				//$lang_th = array( "lang" => 'th');
				//echo "brand_id = ".$this->input->post('brand_id')."<br>";

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						//Debug($this->input->post());
						//die();
						if($this->input->post('brand_id')){ //UPDATE SQL

								$brand_id = $this->input->post('brand_id');
								//$brand_id_en = $this->input->post('brand_id_en');
								//$brand_id_th = $this->input->post('brand_id_th');

								if($logo_file == ''){
									$data_to_store = array(
										'brand_name' => $this->input->post('brand_name'),
										'status' => $this->input->post('status'),
										'lastupdate_date' => $now_date,
										'lastupdate_by' => $this->session->userdata('admin_id')
									);
								}else{
									$data_to_store = array(
										'brand_name' => $this->input->post('brand_name'),
										'logo' => $logo_file,
										'status' => $this->input->post('status'),
										'lastupdate_date' => $now_date,
										'lastupdate_by' => $this->session->userdata('admin_id')
									);
								}

								/*$data_to_store_th = array(
									'brand_name' => $this->input->post('brand_name'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
									'lastupdate_by' => $admin_id
								);*/

								//Debug($data_to_store);
								//die();

								//if the insert has returned true then we show the flash message
								if($this->Brand_model->store($brand_id, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}

								/*if($this->Brand_model->store_update($brand_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}*/
						
						}else{ //INSERT New

								//$order_by = $this->Brand_model->get_max_order();
								$get_max_id = $this->Brand_model->get_max_id();

								//$order = $order_by[0]['max_order'] +1;
								$max_id = $get_max_id[0]['max_id'] +1;

								if($logo_file == ''){
									$data_to_store = array(
										'brand_id' => $max_id,
										'brand_name' => $this->input->post('brand_name'),
										'status' => $this->input->post('status'),
										'create_date' => $now_date,
										'create_by' => $this->session->userdata('admin_id')
									);
								}else{
									$data_to_store = array(
										'brand_id' => $max_id,
										'brand_name' => $this->input->post('brand_name'),
										'logo' => $logo_file,
										'status' => $this->input->post('status'),
										'create_date' => $now_date,
										'create_by' => $this->session->userdata('admin_id')
									);								
								}

								//if the insert has returned true then we show the flash message
								if($this->Brand_model->store(0, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						}

				}else{
						//$data['error'] =  'Please, enter field name';
				}
        }

		//Debug($data);

        //load the view
        //$data['main_content'] = 'brand/brand';
        //$this->load->view('template/template',$data);

		//if ($this->form_validation->run()) redirect('brand');

		if ($this->form_validation->run()){
			$data = array(				
					"brand_list" => $this->Brand_model->get_data(),
					"breadcrumb" => $breadcrumb,
					"success" => 'Save complete.',
					"ListSelect" => $ListSelect,
					"content_view" => 'brand/brand'
			);
			$this->load->view('template/template',$data);
		}else{
			$data = array(				
					"content_view" => 'brand/brand_add',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					"error" => 'Please, enter field name'
			);
			$this->load->view('template/template',$data);		
		}

    }       

}