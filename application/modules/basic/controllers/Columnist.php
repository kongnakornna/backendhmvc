<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Columnist extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Columnist_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		//$breadcrumb[] = '<a href="'.base_url('columnist').'">'.$language['columnist'].'</a>';
		$breadcrumb[] = $language['columnist'];

		$data = array(				
				"columnist_list" => $this->Columnist_model->get_data(),
				"content_view" => 'columnist/columnist',
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb,
		);

		$this->load->view('template/template',$data);
	}
	
	public function add(){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('columnist').'">'.$language['columnist'].'</a>';
			$breadcrumb[] = $language['add'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(						
						"content_view" => 'columnist/columnist_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('columnist').'">'.$language['columnist'].'</a>';
			$breadcrumb[] = $language['edit'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			if($id > 0){
				$data = array(						
					"columnist_arr" => $this->Columnist_model->get_data($id),
					"content_view" => 'columnist/columnist_edit',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
				);
			}else{
				$data = array(				
					"columnist_list" => $this->Columnist_model->get_data(),
					"content_view" => 'columnist/columnist',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					"error" => 'กรุณาเลือก columnist ก่อนแก้ไข'
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
			
			$obj_status = $this->Columnist_model->get_data($id);
			$columnist_name = $obj_status[0]['columnist_name'];
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;

			$this->Columnist_model->status_columnist($id, $cur_status);
			
			//**************Log activity
			$action = 2;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 0,
								"ref_title" => "Columnist: ".$columnist_name." [status]",
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			return $cur_status;
		}
	
		//$this->load->view('template/template',$data);
	
	}
	
	public function save(){

		$language = $this->lang->language;
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

		$breadcrumb[] = $language['columnist'];
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				
				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('columnist_name', 'columnist_name', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$now_date = date('Y-m-d h:i:s');
				
				//$lang_en = array( "lang" => 'en');
				//$lang_th = array( "lang" => 'th');
				//echo "columnist_id = ".$this->input->post('tag_id')."<br>";
				
				$data_to_store = $this->input->post();
				//Debug($data_to_store);

				//die();

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						if(isset($data_to_store['columnist_id'])){ //UPDATE SQL

								/*$data_to_store = array(
									'lastupdate_by' => $admin_id,
									'lastupdate_date' => $create_date
								);*/
								$action = 2;
								$data_to_store['lastupdate_by'] = $admin_id;
								$data_to_store['lastupdate_date'] = $now_date;

								//Debug($data_to_store);
								//die();

								if($this->Columnist_model->store($data_to_store['columnist_id'], $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}

								/*if($this->Columnist_model->store_update($columnist_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}*/

								$columnist_id = $data_to_store['columnist_id'];
						
						}else{ //INSERT SQL
								
								$action = 1;

								$get_max_id = $this->Columnist_model->get_max_id();
								$max_id = $get_max_id[0]['max_id'] +1;

								$data_to_store['create_by'] = $admin_id;
								$data_to_store['create_date'] = $now_date;

								$columnist_id = $max_id;
								/*$data_to_store = array(
									'columnist_id' => $max_id,
									'create_by' => $admin_id,
									'create_date' => $create_date
								);*/
								//Debug($data_to_store);

								//if the insert has returned true then we show the flash message
								if($this->Columnist_model->store(0, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
								/*if($this->Columnist_model->store($data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}*/
						}

				}else{

						//$data['error'] = '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>\', \'</strong></div>';
						//$data['main_content'] = 'columnist/columnist/add';
						//"admin_menu" => $this->menufactory->getMenu(),
						/*$data = array(									
									"content_view" => 'columnist/columnist_add',
									"ListSelect" => $ListSelect,
									"error" =>  'Please, enter field name'
						);*/
						$data['error'] =  'Please, enter field name';
						//$this->load->view('template/template',$data);
						//exit;
				}
        }

        //load the view

			//**************Log activity
			
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $columnist_id,
								"ref_type" => 0,
								"ref_title" => "Columnist: ".$data_to_store['columnist_name'],
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity


		//if ($this->form_validation->run()) redirect('columnist');

		$data = array(
					"columnist_list" => $this->Columnist_model->get_data(),
					"content_view" => 'columnist/columnist',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
		);

		$this->load->view('template/template',$data);

    }       

	public function delete($id){
			echo "Deleting... $id";
			
			$OBJnews = $this->Columnist_model->get_data($id);

			$columnist_name = $OBJnews[0]['columnist_name'];
			//$order_by = $OBJnews[0]['order_by'];

			$this->Columnist_model->delete_data($id);

			//**************Order New
			//$this->Columnist_model->update_orderdel($order_by);

			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 0,
								"ref_title" => "Columnist: ".$columnist_name,
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('columnist');
			die();
	}

}