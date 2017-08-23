<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Membertype extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Membertype_model');
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
				"membertype_list" => $this->Membertype_model->get_data(),
				"content_view" => 'member/type/membertype',
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb,
		);

		$this->load->view('template/template',$data);
	}
	
	public function view_cat($id){
	
		$lang = $this->lang->language['lang'];
		
		$membertype = $this->Membertype_model->get_data($id);
		//debug($membertype);
		
		if($membertype)
			for($i=0;$i<count($membertype);$i++){
	
					if(($lang == 'th') && ($membertype[$i]['lang'] == 'th'))
							$cat_name =  $membertype[$i]['membertype_name'];
					else if(($lang == 'en') && ($membertype[$i]['lang'] == 'en'))
							$cat_name =  $membertype[$i]['membertype_name'];
							
			}
		return $cat_name;	
	}
	
	public function add(){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('membertype').'">'.$language['type'].$language['member'].'</a>';
			$breadcrumb[] = $language['add'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(						
						"content_view" => 'member/type/membertype_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('membertype').'">'.$language['type'].$language['member'].'</a>';
			$breadcrumb[] = $language['edit'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			if($id > 0){
				$data = array(						
					"membertype_arr" => $this->Membertype_model->get_data($id),
					"content_view" => 'member/type/membertype_edit',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
				);
				
			}else{
				$data = array(				
					"membertype_list" => $this->Membertype_model->get_data(),
					"content_view" => 'member/type/membertype',
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
			
			$obj_status = $this->Membertype_model->get_status($id);
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;

			$this->Membertype_model->status_membertype($id, $cur_status);
			
			//$data = array(
					//"cat_arr" => $this->Membertype_model->status_membertype($id, $cur_status),
					//"content_view" => 'membertype/membertype_edit',
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

		$breadcrumb[] = $language['type'].$language['member'];

		//echo "<br>REQUEST_METHOD == ".$this->input->server('REQUEST_METHOD');
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				

				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('membertype_name', 'membertype_name', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$now_date = date('Y-m-d h:i:s');
				
				//$lang_en = array( "lang" => 'en');
				//$lang_th = array( "lang" => 'th');
				//echo "membertype_id = ".$this->input->post('membertype_id')."<br>";

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						//Debug($this->input->post());
						//die();

						if($this->input->post('membertype_id')){ //UPDATE SQL

								$membertype_id = $this->input->post('membertype_id');
								//$membertype_id_en = $this->input->post('membertype_id_en');
								//$membertype_id_th = $this->input->post('membertype_id_th');

								$data_to_store = array(
									'membertype_name' => $this->input->post('membertype_name'),
									'status' => $this->input->post('status'),
									'lastupdate_by' => $this->session->userdata('admin_id')
								);

								/*$data_to_store_th = array(
									'membertype_name' => $this->input->post('membertype_name'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
									'lastupdate_by' => $admin_id
								);*/

								//Debug($data_to_store);
								//die();

								//if the insert has returned true then we show the flash message
								if($this->Membertype_model->store($membertype_id, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}

								/*if($this->Membertype_model->store_update($membertype_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}*/
						
						}else{ //INSERT New

								//$order_by = $this->Membertype_model->get_max_order();
								$get_max_id = $this->Membertype_model->get_max_id();

								//$order = $order_by[0]['max_order'] +1;
								$max_id = $get_max_id[0]['max_id'] +1;

								$data_to_store = array(
									'membertype_id' => $max_id,
									'membertype_name' => $this->input->post('membertype_name'),
									'status' => $this->input->post('status'),
									'create_date' => $now_date,
									'create_by' => $this->session->userdata('admin_id')
								);

								//if the insert has returned true then we show the flash message
								if($this->Membertype_model->store(0, $data_to_store)){
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
        //$data['main_content'] = 'membertype/membertype';
        //$this->load->view('template/template',$data);

		//if ($this->form_validation->run()) redirect('membertype');

		if ($this->form_validation->run()){
			$data = array(				
					"membertype_list" => $this->Membertype_model->get_data(),
					"content_view" => 'member/type/membertype',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
		}else{
			$data = array(				
					"content_view" => 'member/type/membertype_add',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					"error" => 'Please, enter field name'
			);
			$this->load->view('template/template',$data);		
		}

    }       

}