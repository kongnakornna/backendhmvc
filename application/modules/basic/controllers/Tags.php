<?php

class Tags extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Tags_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		//$breadcrumb[] = '<a href="'.base_url('tags').'">'.$language['tags'].'</a>';
		$breadcrumb[] = $language['tags'];
		//"admin_menu" => $this->menufactory->getMenu(),
		$data = array(				
				"tags_list" => $this->Tags_model->get_content_all(),
				"content_view" => 'tags/tags',
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb,
		);

		$this->load->view('template/template',$data);
	}
	
	public function view_cat($id){
	
		$lang = $this->lang->language['lang'];
		
		$tags = $this->Tags_model->get_content_id($id);
		//debug($tags);
		
		if($tags)
			for($i=0;$i<count($tags);$i++){
	
					if(($lang == 'th') && ($tags[$i]['lang'] == 'th'))
							$cat_name =  $tags[$i]['tags_name'];
					else if(($lang == 'en') && ($tags[$i]['lang'] == 'en'))
							$cat_name =  $tags[$i]['tags_name'];
							
			}
		return $cat_name;	
	}
	
	public function add(){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('tags').'">'.$language['tags'].'</a>';
			$breadcrumb[] = $language['add'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(						
						"content_view" => 'tags/tags_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('tags').'">'.$language['tags'].'</a>';
			$breadcrumb[] = $language['edit'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			if($id > 0){
				$data = array(						
					"tags_arr" => $this->Tags_model->get_content_id($id),
					"content_view" => 'tags/tags_edit',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
				);
			}else{
				$data = array(				
					"tags_list" => $this->Tags_model->get_content_all(),
					"content_view" => 'tags/tags',
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
			
			$obj_status = $this->Tags_model->get_status($id);
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;

			$this->Tags_model->status_tags($id, $cur_status);
			
			//$data = array(
					//"cat_arr" => $this->Tags_model->status_tags($id, $cur_status),
					//"content_view" => 'tags/tags_edit',
					//"ListSelect" => $ListSelect,
					//"error" => array()
			//);
			echo $cur_status;

			return $cur_status;
		}
	
		//$this->load->view('template/template',$data);
	
	}
	
	public function save(){

		$language = $this->lang->language;
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

		$breadcrumb[] = $language['tags'];
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				
				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('tag_text', 'tag_text', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$modified_date = $create_date = date('Y-m-d h:i:s');
				
				//$lang_en = array( "lang" => 'en');
				//$lang_th = array( "lang" => 'th');
				//echo "tags_id = ".$this->input->post('tag_id')."<br>";

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						if($this->input->post('tag_id') > 0){ //UPDATE SQL

								$tag_id = $this->input->post('tag_id');
								//$tags_id_en = $this->input->post('tags_id_en');
								//$tags_id_th = $this->input->post('tags_id_th');

								$data_to_store = array(
									'tag_text' => $this->input->post('tag_text'),
									'status' => $this->input->post('status')
								);

								/*$data_to_store_th = array(
									'tag_text' => $this->input->post('tag_text'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
									'lastupdate_by' => $admin_id
								);*/

								//Debug($data_to_store);
								//die();

								//if the insert has returned true then we show the flash message
								if($this->Tags_model->store_update($tag_id, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}

								/*if($this->Tags_model->store_update($tags_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}*/
						
						}else{ //INSERT SQL

								//$order_by = $this->Tags_model->get_max_order();
								$get_max_id = $this->Tags_model->get_max_id();

								//$order = $order_by[0]['max_order'] +1;
								$max_id = $get_max_id[0]['max_id'] +1;

								$data_to_store = array(
									'tag_id' => $max_id,
									'tag_text' => $this->input->post('tag_text'),
									'status' => $this->input->post('status'),
									'create_date' => $create_date
								);
								/*$data_to_store_th = array(
									'tag_id' => $max_id,
									'tag_text' => $this->input->post('tag_text'),
									'status' => $this->input->post('status'),
									'create_date' => $create_date
								);*/

								//if the insert has returned true then we show the flash message
								if($this->Tags_model->store($data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
								/*if($this->Tags_model->store($data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}*/
						}

				}else{

						//$data['error'] = '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>\', \'</strong></div>';
						//$data['main_content'] = 'tags/tags/add';
						
						//"admin_menu" => $this->menufactory->getMenu(),
						/*$data = array(									
									"content_view" => 'tags/tags_add',
									"ListSelect" => $ListSelect,
									"error" =>  'Please, enter field name'
						);*/

						$data['error'] =  'Please, enter field name';

						//$this->load->view('template/template',$data);
						//exit;
				}
        }

        //load the view
        //$data['main_content'] = 'tags/tags';
        //$this->load->view('template/template',$data);

		//if ($this->form_validation->run()) redirect('tags');

		$data = array(				
					"tags_list" => $this->Tags_model->get_content_all(),
					"content_view" => 'tags/tags',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);

    }       

	public function dara(){	
		
			$tags_all = $this->Tags_model->getall_tag_pair(5); //tags ของดารา

			Debug($tags_all);
						
			//"admin_menu" => $this->menufactory->getMenu(),
			/*$data = array(						
						"content_view" => 'tags/tags_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);*/
	}

	public function delete(){	
			
			redirect('tags');
	}

}