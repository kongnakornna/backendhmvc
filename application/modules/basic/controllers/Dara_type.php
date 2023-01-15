<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Dara_type extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Dara_type_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		//$breadcrumb[] = '<a href="'.base_url('category').'">'.$language['category'].'</a>';
		$breadcrumb[] = $language['dara_type'];
				
		$data = array(				
				"dara_type" => $this->Dara_type_model->get_dara_type(),
				"content_view" => 'basic/dara_type',
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb,
		);

			$this->load->view('template/template',$data);
	}
	
	public function add($id = 0){	
		
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			$language = $this->lang->language;
			
			$breadcrumb[] = '<a href="'.base_url('dara_type').'">'.$language['dara_type'].'</a>';
						
			//"admin_menu" => $this->menufactory->getMenu(),
			if($id == 0){
				$breadcrumb[] = $language['add'];
				$data = array(						
						"content_view" => 'basic/dara_type_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
				);
			}else{
				$breadcrumb[] = $language['edit'];
				$data = array(						
						"cat_arr" => $this->Dara_type_model->get_dara_type($id),
						"content_view" => 'basic/dara_type_edit',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
				);
			}

			$this->load->view('template/template',$data);

	}
	
	public function status($id = 0){

		if($id == 0){
			$data = array(
					"error" => 'id error'
			);
			return false;
		}else{
			
			//$dara_type_list = $this->Dara_type_model->get_dara_type($id);			
			$obj_status = $this->Dara_type_model->get_status($id, 0);
			$cur_status = $obj_status[0]['status'];
			$dara_type_name = $obj_status[0]['dara_type_name'];
			//Debug($cur_status);
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;
			
			$data = array(
					"cat_arr" => $this->Dara_type_model->status_dara_type($id, $cur_status),
					//"content_view" => 'basic/category_edit',
					//"ListSelect" => $ListSelect,
					//"error" => array()
			);

			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 0,
								"ref_title" => "Dara Type : ".$dara_type_name." [Status]",
								"action" => 2
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			return $cur_status;
		}	
		//$this->load->view('template/template',$data);	
	}
	
	public function save(){

		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				
				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('dara_type_en', 'dara_type_en', 'required');
				$this->form_validation->set_rules('dara_type_th', 'dara_type_th', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$modified_date = $create_date = date('Y-m-d h:i:s');
				
				$lang_en = array( "lang" => 'en');
				$lang_th = array( "lang" => 'th');

				//$order_by = $this->Dara_type_model->get_max_order();
				$get_max_id = $this->Dara_type_model->get_max_id();

				//$order = $order_by[0]['max_order'] +1;
				$max_id = $get_max_id[0]['max_id'] +1;

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						if($this->input->post('dara_type_id') > 0){ //UPDATE SQL

								$dara_type_id_map = $this->input->post('dara_type_id');
								$dara_type_id_en = $this->input->post('dara_type_id_en');
								$dara_type_id_th = $this->input->post('dara_type_id_th');
								$action = 2;
								$data_to_store = array(
									'dara_type_name' => $this->input->post('dara_type_en'),
									'status' => $this->input->post('status'),
									'lastupdate_by' => $admin_id
								);
								$data_to_store_th = array(
									'dara_type_name' => $this->input->post('dara_type_th'),
									'status' => $this->input->post('status'),									
									'lastupdate_by' => $admin_id
								);

								/*echo "<pre>";
								print_r($data_to_store);
								echo "</pre>";

								echo "<pre>";
								print_r($data_to_store_th);
								echo "</pre>";
								die();*/

								//if the insert has returned true then we show the flash message
								if($this->Dara_type_model->store_update($dara_type_id_en, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
								if($this->Dara_type_model->store_update($dara_type_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						
						}else{ //INSERT SQL
								
								$action = 1;
								$data_to_store = array(
									'dara_type_id_map' => $max_id,
									'dara_type_name' => $this->input->post('dara_type_en'),
									'status' => $this->input->post('status'),
									'lang' => 'en',
									'create_date' => $create_date,
									'create_by' => $admin_id
								);
								$data_to_store_th = array(
									'dara_type_id_map' => $max_id,
									'dara_type_name' => $this->input->post('dara_type_th'),
									'status' => $this->input->post('status'),
									'lang' => 'th',
									'create_date' => $create_date,
									'create_by' => $admin_id
								);

								//if the insert has returned true then we show the flash message
								if($this->Dara_type_model->store($data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
								if($this->Dara_type_model->store($data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						}
						$dara_type_name = $this->input->post('dara_type_th');

				}else{

						//$data['error'] = '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>\', \'</strong></div>';
						//$data['main_content'] = 'basic/category/add';
						
						//"admin_menu" => $this->menufactory->getMenu(),
						$data = array(									
									"content_view" => 'basic/dara_type_add',
									"ListSelect" => $ListSelect,
									"error" =>  'Please, enter field name'
						);

						$dara_type_name = $this->input->post('dara_type_th')." [Fail]";

						$this->load->view('template/template',$data);
						//exit;
				}
        }

		//**************Log activity		
		$log_activity = array(
						"admin_id" => $this->session->userdata('admin_id'),
						"ref_id" => $dara_type_id_en,
						"ref_type" => 0,
						"ref_title" => "Dara Type : ".$dara_type_name,
						"action" => $action
		);			
		$this->Admin_log_activity_model->store($log_activity);
		//**************Log activity

        //load the view
        //$data['main_content'] = 'basic/category';
        //$this->load->view('template/template',$data);

		if ($this->form_validation->run()) redirect('dara_type');
    }       

	public function delete($id){
			echo "Deleting... $id";
			
			$OBJnews = $this->Dara_type_model->get_status($id, 0);
			$title = $OBJnews[0]['title'];
			//$order_by = $OBJnews[0]['order_by'];

			$this->Dara_type_model->delete_user($id);

			//**************Order New
			//$this->Dara_type_model->update_orderdel($order_by);

			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 0,
								"ref_title" => $title,
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('dara_type');
			die();
	}

}