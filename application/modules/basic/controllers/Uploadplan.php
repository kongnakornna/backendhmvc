<?php

class Uploadplan extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Sensorconfig_model');
		$breadcrumb = array();

        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){

			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = $language['sensorsettings'];

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					//Debug($search_form);
					//die();

					if(isset($search_form['selectid'])){

							$selectid = $search_form['selectid'];
							$order = $search_form['order'];
							if(isset($search_form['parent'])) $parent = $search_form['parent']; else $parent = 0;
							$maxsel = count($selectid);
							$toup = $tmp = 0;

							/*for($i=0;$i<$maxsel;$i++){
									$this->Sensorconfig_model->update_order($selectid[$i], $order[$i]);
									if($tmp > $order[$i]){
									}
									if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
							}*/
							for($i=0;$i<$maxsel;$i++){

									if($tmp > $order[$i]){
											//Update ID ด้านหน้า + 1
											$this->Sensorconfig_model->update_orderid_to_down($order[$i], $tmp, $parent);
									}

									$chkadd = $tmp + 1;

									if((($chkadd) <> $order[$i]) && ($toup == 0)){
											//Update ID ด้านหลัง - 1
											$toup = $tmp + 1;
											//echo "($toup, $order[$i])";
											$this->Sensorconfig_model->update_orderid_to_up($toup, $order[$i], $parent);
											$toup = 0;
									}

									//Update Current ID
									$this->Sensorconfig_model->update_order($selectid[$i], $order[$i], $parent);
									if($tmp == 0 || $tmp != $order[$i]) $tmp++;
							}
					}
					//die();
			}

			$web_menu = $this->Sensorconfig_model->getSensorreport();
			$webtitle = $language['sensorsettings'];
			//Debug($web_menu);
			//die();

			$data = array(
					"ListSelect" => $ListSelect,
					"web_menu" => $web_menu,
					"webtitle" => $webtitle,
					"content_view" => 'tmon/sensorconfig',
					"breadcrumb" => $breadcrumb,
			);

			//$data['content_view'] = 'admin/web_menu';
			$this->load->view('template/template',$data);

	}



	public function save(){

		$admin_id = $this->session->userdata('admin_id');
		$admin_type = $this->session->userdata('admin_type');
		$ListSelect = $this->Api_model_na->user_menu($admin_type);
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				
				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('hardware_id', 'hardware_id', 'required');
				$this->form_validation->set_rules('sensor_group', 'sensor_group', 'required');
				$this->form_validation->set_rules('sensor_name', 'sensor_name', 'required');
				$this->form_validation->set_rules('sensor_type_id', 'sensor_type_id', 'required');
				$this->form_validation->set_rules('sensor_type_id', 'sensor_type_id', 'required');
				$this->form_validation->set_rules('sensor_high', 'sensor_high', 'required');
				$this->form_validation->set_rules('sensor_warning', 'sensor_warning', 'required');
				$this->form_validation->set_rules('sn', 'sn', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$now_date = date('Y-m-d h:i:s');
				
				//$order_by = $this->admin_Sensorconfig_model->get_max_order();
				//$get_max_id = $this->admin_Sensorconfig_model->get_max_id();
				//$order = $order_by[0]['max_order'] +1;
				//$max_id = $get_max_id[0]['max_id'] +1;

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						if($this->input->post('sensor_config_id') > 0){ //UPDATE SQL

								$sensor_config_id = $this->input->post('sensor_config_id');
								$data_to_store = array(
								'sensor_config_id' => $this->input->post('sensor_config_id'),
								'hardware_id' => $this->input->post('hardware_id'),
								'sensor_group' => $this->input->post('sensor_group'),
								'sensor_name' => $this->input->post('sensor_name'),
								'sensor_type_id' => $this->input->post('sensor_type_id'),
								'sensor_high' => $this->input->post('sensor_high'),
								'sensor_warning' => $this->input->post('sensor_warning'),
								'sn' => $this->input->post('sn'),
								'model' => $this->input->post('model'),
								//'date' => $this->input->post('date'),
								'date' => $now_date,
								'vendor' => $this->input->post('vendor'),
								'sensor_status' => $this->input->post('sensor_status'),
								//////////////////////
								//'lastupdate_date' => $now_date,
								//'lastupdate_by' => $admin_id
								);

								
$sensor_config_id=$data_to_store['sensor_config_id'];
$sensor_group=$data_to_store['sensor_group'];			
$sensor_name=$data_to_store['sensor_name'];
$sensor_high=$data_to_store['sensor_high'];
$sensor_warning=$data_to_store['sensor_warning'];			
$vendor=$data_to_store['vendor'];
$hardware_id=$data_to_store['hardware_id'];
$sn=$data_to_store['sn'];
$sensor_status=$data_to_store['sensor_status'];
$action = 2;
$sensorconfigjoin=$this->Sensorconfig_model->getSensorreportwhere($sensor_config_id);
$sensor_type_name=$sensorconfigjoin[0]['sensor_type_name'];

//echo "<pre>";print_r($sensorconfigjoin);echo "</pre>";
//echo "<pre>";print_r($data_to_store);echo "</pre>";
//echo 'Sensor type='.$sensor_type_name;
//die();
 
								//if the insert has returned true then we show the flash message
								if($this->Sensorconfig_model->store($sensor_config_id, $data_to_store)){
								echo 'Save data';
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
								



						
						}else{ //INSERT SQL

								$data_to_store = array(
									'sensor_config_id' => $max_id,
									'sensor_name' => $this->input->post('sensor_name'),
									'status' => $this->input->post('status'),
									'create_date' => $now_date,
									'create_by' => $admin_id
								);
								$action = 1;
								$sensor_config_id = $max_id;

								//if the insert has returned true then we show the flash message
								if($this->admin_Sensorconfig_model->store(0, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						}
						$admin_sensorconfig_name = $data_to_store['sensor_name'];

				}else{

						$admin_sensorconfig_name = "Update [Fail]";
						$data = array(									
									"content_view" => 'sensorconfig/add',
									"ListSelect" => $ListSelect,
									"error" =>  'Please, enter field name'
						);

						$this->load->view('template/template',$data);
						//exit;
				}
        }


		//**************Log activity
		$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $sensor_config_id,
					"ref_type" => 0,
					"ref_title" => "Update Sensor Config  ".'['.$sensor_group.' :'.$sensor_name.' '.$sensor_type_name.']'.' Alert high '.$sensor_high.' Warning :'.$sensor_warning.' Vendor '.$vendor.'Serail no.'.$sn.']'."",
					"action" => $action
		);
		$this->Admin_log_activity_model->store($log_activity);
		//**************Log activity
		if ($this->form_validation->run()) redirect('sensorconfig');
    }


	public function edit($id = 0){

			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));		
			$language = $this->lang->language;	

			$breadcrumb[] = '<a href="'.base_url('sensorconfig').'">'.$language['sensorsettings'].'</a>';
			$breadcrumb[] = $language['edit'];

			$sensorconfig_list = $this->Sensorconfig_model->get_sensorconfig($id);

			$data = array(						
				"sensorconfig_list" => $sensorconfig_list,
				"content_view" => 'tmon/sensorconfig_edit1',
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb
			);
			$this->load->view('template/template',$data);
	}	



}