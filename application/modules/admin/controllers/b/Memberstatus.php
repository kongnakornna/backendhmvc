<?php

class Memberstatus extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Memberstatus_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;
		$breadcrumb[] = $language['member'];
		$data = array(				
				"memberstatus_list" => $this->Memberstatus_model->get_data(),
				"content_view" => 'member/status/memberstatus',
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb,
		);

		$this->load->view('template',$data);
	}
	
	public function view_cat($id){
	
		$lang = $this->lang->language['lang'];
		
		$memberstatus = $this->Memberstatus_model->get_data($id);
		//debug($memberstatus);
		
		if($memberstatus)
			for($i=0;$i<count($memberstatus);$i++){
	
					if(($lang == 'th') && ($memberstatus[$i]['lang'] == 'th'))
							$cat_name =  $memberstatus[$i]['memberstatus_name'];
					else if(($lang == 'en') && ($memberstatus[$i]['lang'] == 'en'))
							$cat_name =  $memberstatus[$i]['memberstatus_name'];
							
			}
		return $cat_name;	
	}
	
	public function add(){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('memberstatus').'">'.$language['status'].$language['member'].'</a>';
			$breadcrumb[] = $language['add'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(						
						"content_view" => 'member/status/memberstatus_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template',$data);
	}

	public function edit($id = 0){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('memberstatus').'">'.$language['status'].$language['member'].'</a>';
			$breadcrumb[] = $language['edit'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			if($id > 0){
				$data = array(						
					"memberstatus_arr" => $this->Memberstatus_model->get_data($id),
					"content_view" => 'member/status/memberstatus_edit',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
				);
				
			}else{
				$data = array(				
					"memberstatus_list" => $this->Memberstatus_model->get_data(),
					"content_view" => 'member/status/memberstatus',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					"error" => 'กรุณาเลือก Tags ก่อนแก้ไข'
				);			
			}
             
			$this->load->view('template',$data);
	}
	
	public function status($id = 0){

			
		if($id == 0){
			$data = array(
					"error" => 'id error'
			);
			return false;
		}else{
			
			$obj_status = $this->Memberstatus_model->get_status($id);
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;

			$this->Memberstatus_model->status_memberstatus($id, $cur_status);
			

			echo $cur_status;

			return $cur_status;
		}
	
		//$this->load->view('template',$data);
	
	}
	
	public function save(){

		$language = $this->lang->language;
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

		$breadcrumb[] = $language['status'].$language['member'];

		//echo "<br>REQUEST_METHOD == ".$this->input->server('REQUEST_METHOD');
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				

				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('memberstatus_name', 'memberstatus_name', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$now_date = date('Y-m-d h:i:s');
				//if the form has passed through the validation
				if ($this->form_validation->run()){

						//Debug($this->input->post());
						//die();

						if($this->input->post('memberstatus_id')){ //UPDATE SQL

								$memberstatus_id = $this->input->post('memberstatus_id');
								//$memberstatus_id_en = $this->input->post('memberstatus_id_en');
								//$memberstatus_id_th = $this->input->post('memberstatus_id_th');

								$data_to_store = array(
									'memberstatus_name' => $this->input->post('memberstatus_name'),
									'status' => $this->input->post('status'),
									'lastupdate_by' => $this->session->userdata('admin_id')
								);

								/*$data_to_store_th = array(
									'memberstatus_name' => $this->input->post('memberstatus_name'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
									'lastupdate_by' => $admin_id
								);*/

								//Debug($data_to_store);
								//die();

								//if the insert has returned true then we show the flash message
								if($this->Memberstatus_model->store($memberstatus_id, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}

								/*if($this->Memberstatus_model->store_update($memberstatus_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}*/
						
						}else{ //INSERT New

								//$order_by = $this->Memberstatus_model->get_max_order();
								$get_max_id = $this->Memberstatus_model->get_max_id();

								//$order = $order_by[0]['max_order'] +1;
								$max_id = $get_max_id[0]['max_id'] +1;

								$data_to_store = array(
									'memberstatus_id' => $max_id,
									'memberstatus_name' => $this->input->post('memberstatus_name'),
									'status' => $this->input->post('status'),
									'create_date' => $now_date,
									'create_by' => $this->session->userdata('admin_id')
								);

								//if the insert has returned true then we show the flash message
								if($this->Memberstatus_model->store(0, $data_to_store)){
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
        //$data['main_content'] = 'memberstatus/memberstatus';
        //$this->load->view('template',$data);

		//if ($this->form_validation->run()) redirect('memberstatus');

		if ($this->form_validation->run()){
			$data = array(				
					"memberstatus_list" => $this->Memberstatus_model->get_data(),
					"content_view" => 'member/status/memberstatus',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template',$data);
		}else{
			$data = array(				
					"content_view" => 'member/status/memberstatus_add',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					"error" => 'Please, enter field name'
			);
			$this->load->view('template',$data);		
		}

    }       

}