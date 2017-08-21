<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Belong_to extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Belong_to_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		$breadcrumb[] = $language['belong_to'];

		$data = array(				
				"belong_to_list" => $this->Belong_to_model->get_data(),
				"content_view" => 'belong_to/belong_to',
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb,
		);

		$this->load->view('template/template',$data);
	}
	
	public function view_cat($id){
	
		$lang = $this->lang->language['lang'];
		
		$belong_to = $this->Belong_to_model->get_data($id);
		//debug($belong_to);
		
		if($belong_to)
			for($i=0;$i<count($belong_to);$i++){
	
					if(($lang == 'th') && ($belong_to[$i]['lang'] == 'th'))
							$cat_name =  $belong_to[$i]['belong_to_name'];
					else if(($lang == 'en') && ($belong_to[$i]['lang'] == 'en'))
							$cat_name =  $belong_to[$i]['belong_to_name'];
							
			}
		return $cat_name;	
	}
	
	public function add(){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('belong_to').'">'.$language['belong_to'].'</a>';
			$breadcrumb[] = $language['add'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(						
						"content_view" => 'belong_to/belong_to_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('belong_to').'">'.$language['belong_to'].'</a>';
			$breadcrumb[] = $language['edit'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			if($id > 0){
				$data = array(						
					"belong_to_arr" => $this->Belong_to_model->get_data($id),
					"content_view" => 'belong_to/belong_to_edit',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
				);
			}else{
				$data = array(				
					"belong_to_list" => $this->Belong_to_model->get_data(),
					"content_view" => 'belong_to/belong_to',
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
			
			$obj_status = $this->Belong_to_model->get_status($id);
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;

			$this->Belong_to_model->status_belong_to($id, $cur_status);
			
			//$data = array(
					//"cat_arr" => $this->Belong_to_model->status_belong_to($id, $cur_status),
					//"content_view" => 'belong_to/belong_to_edit',
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

		$breadcrumb[] = $language['belong_to'];

		//echo "<br>REQUEST_METHOD == ".$this->input->server('REQUEST_METHOD');
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				

				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('belong_to', 'belong_to', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$now_date = date('Y-m-d h:i:s');
				
				//$lang_en = array( "lang" => 'en');
				//$lang_th = array( "lang" => 'th');
				//echo "belong_to_id = ".$this->input->post('belong_to_id')."<br>";

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						//Debug($this->input->post());
						//die();

						if($this->input->post('belong_to_id')){ //UPDATE SQL

								$belong_to_id = $this->input->post('belong_to_id');
								//$belong_to_id_en = $this->input->post('belong_to_id_en');
								//$belong_to_id_th = $this->input->post('belong_to_id_th');

								$data_to_store = array(
									'belong_to' => $this->input->post('belong_to'),
									'status' => $this->input->post('status'),
									'lastupdate_by' => $this->session->userdata('admin_id')
								);

								/*$data_to_store_th = array(
									'belong_to_name' => $this->input->post('belong_to_name'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
									'lastupdate_by' => $admin_id
								);*/

								//Debug($data_to_store);
								//die();

								//if the insert has returned true then we show the flash message
								if($this->Belong_to_model->store($belong_to_id, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}

								/*if($this->Belong_to_model->store_update($belong_to_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}*/
						
						}else{ //INSERT New

								//$order_by = $this->Belong_to_model->get_max_order();
								$get_max_id = $this->Belong_to_model->get_max_id();

								//$order = $order_by[0]['max_order'] +1;
								$max_id = $get_max_id[0]['max_id'] +1;

								$data_to_store = array(
									'belong_to_id' => $max_id,
									'belong_to' => $this->input->post('belong_to'),
									'status' => $this->input->post('status'),
									'create_date' => $now_date,
									'create_by' => $this->session->userdata('admin_id')
								);

								//if the insert has returned true then we show the flash message
								if($this->Belong_to_model->store(0, $data_to_store)){
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
        //$data['main_content'] = 'belong_to/belong_to';
        //$this->load->view('template/template',$data);

		//if ($this->form_validation->run()) redirect('belong_to');

		if ($this->form_validation->run()){
			$data = array(				
					"belong_to_list" => $this->Belong_to_model->get_data(),
					"content_view" => 'belong_to/belong_to',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
		}else{
			$data = array(				
					"content_view" => 'belong_to/add',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					"error" => 'Please, enter field name'
			);
			$this->load->view('template/template',$data);		
		}

    }       

}