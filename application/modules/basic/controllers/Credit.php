<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Credit extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Credit_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		$breadcrumb[] = $language['credit'];

		$data = array(				
				"credit_list" => $this->Credit_model->get_data(),
				"content_view" => 'credit/credit',
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb,
		);

		$this->load->view('template/template',$data);
	}
	
	public function view_cat($id){
	
		$lang = $this->lang->language['lang'];
		
		$credit = $this->Credit_model->get_data($id);
		//debug($credit);
		
		if($credit)
			for($i=0;$i<count($credit);$i++){
	
					if(($lang == 'th') && ($credit[$i]['lang'] == 'th'))
							$cat_name =  $credit[$i]['credit_name'];
					else if(($lang == 'en') && ($credit[$i]['lang'] == 'en'))
							$cat_name =  $credit[$i]['credit_name'];
							
			}
		return $cat_name;	
	}
	
	public function add(){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('credit').'">'.$language['credit'].'</a>';
			$breadcrumb[] = $language['add'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(						
						"content_view" => 'credit/credit_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('credit').'">'.$language['credit'].'</a>';
			$breadcrumb[] = $language['edit'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			if($id > 0){
				$data = array(						
					"credit_arr" => $this->Credit_model->get_data($id),
					"content_view" => 'credit/credit_edit',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
				);
			}else{
				$data = array(				
					"credit_list" => $this->Credit_model->get_data(),
					"content_view" => 'credit/credit',
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
			
			$obj_status = $this->Credit_model->get_status($id);
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;

			$this->Credit_model->status_credit($id, $cur_status);
			
			//$data = array(
					//"cat_arr" => $this->Credit_model->status_credit($id, $cur_status),
					//"content_view" => 'credit/credit_edit',
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

		$breadcrumb[] = $language['credit'];

		//echo "<br>REQUEST_METHOD == ".$this->input->server('REQUEST_METHOD');
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				

				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('credit_name', 'credit_name', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$modified_date = $create_date = date('Y-m-d h:i:s');
				
				//$lang_en = array( "lang" => 'en');
				//$lang_th = array( "lang" => 'th');
				//echo "credit_id = ".$this->input->post('credit_id')."<br>";

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						if($this->input->post('credit_id')){ //UPDATE SQL

								$credit_id = $this->input->post('credit_id');
								//$credit_id_en = $this->input->post('credit_id_en');
								//$credit_id_th = $this->input->post('credit_id_th');

								$data_to_store = array(
									'credit_name' => $this->input->post('credit_name'),
									'status' => $this->input->post('status')
								);

								/*$data_to_store_th = array(
									'credit_name' => $this->input->post('credit_name'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
									'lastupdate_by' => $admin_id
								);*/

								//Debug($data_to_store);
								//die();

								//if the insert has returned true then we show the flash message
								if($this->Credit_model->store($credit_id, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}

								/*if($this->Credit_model->store_update($credit_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}*/
						
						}else{ //INSERT New

								//$order_by = $this->Credit_model->get_max_order();
								$get_max_id = $this->Credit_model->get_max_id();

								//$order = $order_by[0]['max_order'] +1;
								$max_id = $get_max_id[0]['max_id'] +1;

								$data_to_store = array(
									'credit_id' => $max_id,
									'credit_name' => $this->input->post('credit_name'),
									'status' => $this->input->post('status'),
									//'create_date' => $create_date
								);

								//if the insert has returned true then we show the flash message
								if($this->Credit_model->store(0, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						}

				}else{

						//$data['error'] = '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>\', \'</strong></div>';
						//$data['main_content'] = 'credit/credit/add';
						
						//"admin_menu" => $this->menufactory->getMenu(),
						/*$data = array(									
									"content_view" => 'credit/credit_add',
									"ListSelect" => $ListSelect,
									"error" =>  'Please, enter field name'
						);*/

						$data['error'] =  'Please, enter field name';

						//$this->load->view('template/template',$data);
						//exit;
				}
        }

		//Debug($data);

        //load the view
        //$data['main_content'] = 'credit/credit';
        //$this->load->view('template/template',$data);

		//if ($this->form_validation->run()) redirect('credit');

		$data = array(				
					"credit_list" => $this->Credit_model->get_data(),
					"content_view" => 'credit/credit',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);

    }       

}