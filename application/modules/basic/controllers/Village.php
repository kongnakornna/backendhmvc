<?php

class Village extends MY_Controller {

    public function __construct()    {
			parent::__construct();
			$this->load->model('Village_model');
			$breadcrumb = array();			
			
			if(!$this->session->userdata('is_logged_in')){
				redirect(base_url());
			}
    }

	public function index(){
		
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));		
			$language = $this->lang->language;
			$breadcrumb[] = '<a href="'.base_url('countries').'">'.$language['countries'].'</a>';
            $breadcrumb[] = '<a href="'.base_url('geography').'">'.$language['geography'].'</a>';
            $breadcrumb[] = '<a href="'.base_url('province').'">'.$language['province'].'</a>';
            $breadcrumb[] = '<a href="'.base_url('amphur').'">'.$language['amphur'].'</a>';
            $breadcrumb[] = '<a href="'.base_url('village').'">'.$language['village'].'</a>';
			
			
			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					//Debug($search_form);
					//die();

					if(isset($search_form['selectid'])){
							$selectid = $search_form['selectid'];
							$order = $search_form['order'];
							$maxsel = count($selectid);
							$tmp = 0;

							for($i=0;$i<$maxsel;$i++){

									//$this->Admin_menu_model->update_order($selectid[$i], $order[$i]);

									if($tmp > $order[$i]){
											//Update ID ด้านหน้า
											//$this->Admin_menu_model->update_orderid_to_down($order[$i]);
									}

									if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
									//echo "<hr>$tmp > ".$order[$i]."<hr>";

							}
					}
					//die();
			}

			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(		
			        "village" => $this->Village_model->village(),		
					#"village" => $this->Village_model->get_amphur(),
					"content_view" => 'address/village',
					"ListSelect" => $ListSelect,
			);
			$this->load->view('template/template',$data);
	}

	public function pid($id = 0){  
            
			if($id < 1){
				redirect(base_url('geography'));
			}
			$districtid = $this->uri->segment(3); 
			$amphurid = $this->uri->segment(4);  
			$provinceid = $this->uri->segment(5);  
		    $geoid = $this->uri->segment(6);  
		    $countries_id= $this->uri->segment(7);
			$amphur_id	= $amphurid; 
			$this->load->model('Countries_model');
			$this->load->model('Geography_model');
			$this->load->model('Province_model');
			$this->load->model('Amphur_model');
			$this->load->model('District_model');
			
			$language = $this->lang->language;			
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
            $language = $this->lang->language;
            
            $get_countries = $this->Countries_model->get_countries_by_id($countries_id);
			//Debug($get_countries);
			$object = json_decode(json_encode($get_countries), TRUE);
            $countriesname=$object[0]['countries_name'];
            
            
            $get_geography = $this->Geography_model->get_geography_by_id($geoid);
			//Debug($get_geography);
			$object = json_decode(json_encode($get_geography), TRUE);
            $geoname=$object[0]['geo_name'];
            
            $get_province = $this->Province_model->get_province_by_id($provinceid,$language);
			//Debug($get_province);
			$object = json_decode(json_encode($get_province), TRUE);
            $provincename=$object[0]['province_name'];
            
            $get_amphur = $this->Amphur_model->get_amphur_by_id($amphurid,$language);
			//Debug($get_amphur);
			$object = json_decode(json_encode($get_amphur), TRUE);
            $amphurname=$object[0]['amphur_name']; 
            
			$get_district = $this->District_model->get_district_by_id($districtid,$language);
			//Debug($get_district);
			$object = json_decode(json_encode($get_district), TRUE);
            $districtname=$object[0]['district_name']; 
            
            $this->load->model('Village_model');
			$breadcrumb[] = '<a href="'.base_url('countries').'">'.$language['countries'].$countriesname.'</a>';			                  $breadcrumb[] = '<a href="'.base_url('geography').'">'.$language['geography'].$geoname.'</a>';
            $breadcrumb[] = '<a href="'.base_url('province').'">'.$language['province'].$provincename.'</a>'; 
            $breadcrumb[] = '<a href="'.base_url('amphur'.'/pid/'.$provinceid.'/'.$geoid.'/'.$countries_id.'').'">'.$language['amphur'].$amphurname.'</a>'; 
            $breadcrumb[] = '<a href="'.base_url('district'.'/pid/'.$amphurid.'/'.$provinceid.'/'.$geoid.'/'.$countries_id.'').'">'.$language['district'].'</a>'; 
            $breadcrumb[] = $districtname;			
            
            
			

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					//Debug($search_form);
					//die();

					 

					if(isset($search_form['selectid'])){
							$selectid = $search_form['selectid'];
							$order = $search_form['order'];
							$maxsel = count($selectid);
							$tmp = 0;

							for($i=0;$i<$maxsel;$i++){

									$this->Village_model->update_order($selectid[$i], $order[$i]);
									//Debug($this->db->last_query());

									if($tmp > $order[$i]){
											//Update ID ด้านหน้า
											//$this->Village_model->update_orderid_to_down($order[$i]);
									}

									if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
									//echo "<hr>$tmp > ".$order[$i]."<hr>";
							}
					}
					//die();
			}

			if($amphur_id == 0) $amphur_id= $id; else $amphur_id =$amphur_id ;
           
			$data = array(
					"amphur" => $this->Amphur_model->get_amphur_by_id($amphur_id,$language),
					"village" => $this->Village_model->get_village($districtid),
					"content_view" => 'address/village',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
			);

			$this->load->view('template/template',$data);
	}

	public function add($pid = 0){
			
			
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));			
			$language = $this->lang->language;
 
            $districtid = $this->uri->segment(3); 
			$amphurid = $this->uri->segment(4);  
			$provinceid = $this->uri->segment(5);  
		    $geoid = $this->uri->segment(6);  
		    $countries_id= $this->uri->segment(7);
			$amphur_id	= $amphurid; 
			$this->load->model('Countries_model');
			$this->load->model('Geography_model');
			$this->load->model('Province_model');
			$this->load->model('Amphur_model');
			$this->load->model('District_model');
			$this->load->model('Village_model');
			$language = $this->lang->language;			
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
            $language = $this->lang->language;
            
            $get_countries = $this->Countries_model->get_countries_by_id($countries_id);
			//Debug($get_countries);
			$object = json_decode(json_encode($get_countries), TRUE);
            $countriesname=$object[0]['countries_name'];
            
            
            $get_geography = $this->Geography_model->get_geography_by_id($geoid);
			//Debug($get_geography);
			$object = json_decode(json_encode($get_geography), TRUE);
            $geoname=$object[0]['geo_name'];
            
            $get_province = $this->Province_model->get_province_by_id($provinceid,$language);
			//Debug($get_province);
			$object = json_decode(json_encode($get_province), TRUE);
            $provincename=$object[0]['province_name'];
            
            $get_amphur = $this->Amphur_model->get_amphur_by_id($amphurid,$language);
			//Debug($get_amphur);
			$object = json_decode(json_encode($get_amphur), TRUE);
            $amphurname=$object[0]['amphur_name']; 
            
			$get_district = $this->District_model->get_district_by_id($districtid,$language);
			//Debug($get_district);
			$object = json_decode(json_encode($get_district), TRUE);
            $districtname=$object[0]['district_name']; 
            
        
			$breadcrumb[] = '<a href="'.base_url('countries').'">'.$language['countries'].$countriesname.'</a>';			                  $breadcrumb[] = '<a href="'.base_url('geography').'">'.$language['geography'].$geoname.'</a>';
            $breadcrumb[] = '<a href="'.base_url('province').'">'.$language['province'].$provincename.'</a>'; 
            $breadcrumb[] = '<a href="'.base_url('amphur'.'/pid/'.$provinceid.'/'.$geoid.'/'.$countries_id.'').'">'.$language['amphur'].$amphurname.'</a>'; 
             	
            $breadcrumb[] = '<a href="'.base_url('district'.'/pid/'.$amphurid.'/'.$provinceid.'/'.$geoid.'/'.$countries_id.'').'">'.$language['district'].$districtname.'</a>'; 		
            
         

			$data = array(
						"village_name" => $this->Village_model->get_village_by_id($pid),
						"content_view" => 'address/village_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){
			
			$pid = 0;
			$this->load->model('Village_model');
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;
			$village_idmap = $this->uri->segment(3);
            $districtid = $this->uri->segment(4); 
			$amphurid = $this->uri->segment(5);  
			$provinceid = $this->uri->segment(6);  
		    $geoid = $this->uri->segment(7);  
		    $countries_id= $this->uri->segment(8);
			$amphur_id	= $amphurid; 
			$this->load->model('Countries_model');
			$this->load->model('Geography_model');
			$this->load->model('Province_model');
			$this->load->model('Amphur_model');
			$this->load->model('District_model');
			$this->load->model('Village_model');
			$language = $this->lang->language;			
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
            $language = $this->lang->language;
            
            $get_countries = $this->Countries_model->get_countries_by_id($countries_id);
			//Debug($get_countries);
			$object = json_decode(json_encode($get_countries), TRUE);
            $countriesname=$object[0]['countries_name'];
            
            
            $get_geography = $this->Geography_model->get_geography_by_id($geoid);
			//Debug($get_geography);
			$object = json_decode(json_encode($get_geography), TRUE);
            $geoname=$object[0]['geo_name'];
            
            $get_province = $this->Province_model->get_province_by_id($provinceid,$language);
			//Debug($get_province);
			$object = json_decode(json_encode($get_province), TRUE);
            $provincename=$object[0]['province_name'];
            
            $get_amphur = $this->Amphur_model->get_amphur_by_id($amphurid,$language);
			//Debug($get_amphur);
			$object = json_decode(json_encode($get_amphur), TRUE);
            $amphurname=$object[0]['amphur_name']; 
            
			$get_district = $this->District_model->get_district_by_id($districtid,$language);
			//Debug($get_district);
			$object = json_decode(json_encode($get_district), TRUE);
            $districtname=$object[0]['district_name']; 
            
        
			$breadcrumb[] = '<a href="'.base_url('countries').'">'.$language['countries'].$countriesname.'</a>';			                  $breadcrumb[] = '<a href="'.base_url('geography').'">'.$language['geography'].$geoname.'</a>';
            $breadcrumb[] = '<a href="'.base_url('province').'">'.$language['province'].$provincename.'</a>'; 
            $breadcrumb[] = '<a href="'.base_url('amphur'.'/pid/'.$provinceid.'/'.$geoid.'/'.$countries_id.'').'">'.$language['amphur'].$amphurname.'</a>'; 
             	
            $breadcrumb[] = '<a href="'.base_url('district'.'/pid/'.$amphurid.'/'.$provinceid.'/'.$geoid.'/'.$countries_id.'').'">'.$language['district'].$districtname.'</a>'; 		
            
         
 
			$breadcrumb[] = $language['edit'];			
			//Debug($this->input->get('village_id'));
			//$pid = $this->input->get('village_id');
            $pid=$village_idmap;
           // echo '$pid='.$pid;
			$village_name = $this->Village_model->get_village_by_id($pid);
			// Debug($village_name);
			//Debug($this->db->lastquery());
			$pid_arr = $this->Village_model->get_village_by_id($pid);
			//Debug($village_name);
			//Debug($this->db->lastquery());
			$data = array(
						"village_name" => $village_name,
						"pid_arr" => $pid_arr,
						"content_view" => 'address/village_edit',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);

	}

	public function list_dd($id = 0, $subpidid = 0){
			
			$this->load->model('Village_model');
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;
			
			$first = "--- ".$language['please_select']." ---";
			$subpid = $this->Village_model->get_dd_list($id);
			//Debug($subpid);
			$allsubpid = count($subpid);

			echo '<option value="0">'.$first.'</option>';
			if($subpid)
				for($i=0;$i<$allsubpid;$i++){
						//echo "<li>".$subpid[$i]->village_id_map." ".$subpid[$i]->village_name."</li>";

						$sel = ($subpidid == $subpid[$i]->village_id_map) ? 'selected' : '';
						echo '<option value="'.$subpid[$i]->village_id_map.'" '.$sel.'>'.$subpid[$i]->village_name.'</option>';
						//echo '<option value="'.$subpid[$i]["village_id_map"].'">'.$subpid[$i]["village_name"].'</option>';
				}
			//Debug($subpid);
			//echo "subpidid = $subpidid";
			exit;

	}	
	
	public function status($id = 0){

		if($id == 0){
			$data = array(
					"error" => 'id error'
			);
			return false;
		}else{
			
			$obj_status = $this->Village_model->get_status($id);

			$village_name = $obj_status[0]['village_name'];
			$order_by = $obj_status[0]['order_by'];
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;
			
			$data = array(
					"pid_arr" => $this->Village_model->status_village($id, $cur_status),
			);

			//**************Log activity
			$action = 2;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 0,
								"ref_title" => "Sub village : ".$village_name." [Status]",
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			return $cur_status;
		}
	}
	
	public function save(){
		$language = $this->lang->language['lang'];
        $this->load->model('Village_model');
		$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
		$get_village_id_map= $this->Village_model->get_village_id_map($language);
		#Debug($get_village_id_map);
		$object = json_decode(json_encode($get_village_id_map), TRUE);
        $village_id_map=$object[0]['village_id']; 
        if($village_id_map==''){
		$village_id = 0;	
		}else{
		$village_id = $village_id_map;
		}
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				
				$village_id = $this->input->post('village_id_map');
				
				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('village_en', 'village_en', 'required');
				$this->form_validation->set_rules('village_th', 'village_th', 'required');
				$this->form_validation->set_rules('village_moo', 'village_moo', 'required');
				$this->form_validation->set_rules('district_id', 'district_id', 'required');
				$this->form_validation->set_rules('amphur_id', 'amphur_id', 'required');
				$this->form_validation->set_rules('province_id', 'province_id', 'required');
				$this->form_validation->set_rules('geo_id', 'geo_id', 'required');
				$this->form_validation->set_rules('countries_id', 'countries_id', 'required');
                $this->form_validation->set_rules('status', 'status', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$modified_date = $create_date = date('Y-m-d h:i:s');
				
				$lang_en = array( "lang" => 'en');
				$lang_th = array( "lang" => 'th');

				 //Debug($this->input->post());


				$order_by = $this->Village_model->get_max_order($village_id);
				//Debug($order_by);
				$get_max_id = $this->Village_model->get_max_id();
 				#Debug($get_max_id);
				#die();

				 $order = $order_by[0]['max_order'] +1;
				#$max_id = $get_max_id[0]['max_id'] +1;
				 #$order = $village_id+1;
				 $max_id = $village_id+1;
				# echo '$village_id='.$village_id;
				# echo '<br>';
				# echo '$order='.$order;
				# echo '<br>';
                # echo '$max_id='.$max_id;
                 
				//if the form has passed through the validation
				if ($this->form_validation->run()){

						if($this->input->post('village_id') > 0){ //UPDATE SQL
								$action = 2;
								$village_id = $this->input->post('village_id');
								$village_id_en = $this->input->post('village_id_en');
								$village_id_th = $this->input->post('village_id_th');
								$village_moo = $this->input->post('village_moo');
								$distrct_id = $this->input->post('distrct_id');
								$amphur_id = $this->input->post('amphur_id');
								$province_id = $this->input->post('province_id');
								$geo_id = $this->input->post('geo_id');
								$countries_id = $this->input->post('countries_id');
								$status = $this->input->post('status');
								$data_to_store = array(
									'village_name' => $this->input->post('village_en'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
									'lastupdate_by' => $admin_id
								);
								$data_to_store_th = array(
									'village_name' => $this->input->post('village_th'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
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
								if($this->Village_model->update_village($village_id_en, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
								if($this->Village_model->update_village($village_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						
						}else{ //INSERT SQL
								$action = 1;
 
								$village_id_en = $max_id;
								$data_to_store = array(
								   
									'village_id_map' => $max_id,
									'village_moo' => $this->input->post('village_moo'),
									'distrct_id' => $this->input->post('district_id'),
									'amphur_id' => $this->input->post('amphur_id'),
									'province_id' => $this->input->post('province_id'),
									'geo_id' => $this->input->post('geo_id'),
									'countries_id' => $this->input->post('countries_id'),
									'village_name' => $this->input->post('village_en'),
									'status' => $this->input->post('status'),
									'lang' => 'en',
									'create_by' => $admin_id
								);

								$data_to_store_th = array(
								   
									'village_id_map' => $max_id,
									'village_moo' => $this->input->post('village_moo'),
									'distrct_id' => $this->input->post('district_id'),
									'amphur_id' => $this->input->post('amphur_id'),
									'province_id' => $this->input->post('province_id'),
									'geo_id' => $this->input->post('geo_id'),
									'countries_id' => $this->input->post('countries_id'),
									'village_name' => $this->input->post('village_th'),
									'status' => $this->input->post('status'),
									'lang' => 'th',
									'create_by' => $admin_id
								);

								//if the insert has returned true then we show the flash message
								if($this->Village_model->store_product($data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}

								if($this->Village_model->store_product($data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						}

						$village_name = $data_to_store['village_name'];
						$distrct_id = $data_to_store['distrct_id'];
						$amphur_id = $data_to_store['amphur_id'];
						$province_id = $data_to_store['province_id'];
						$geo_id = $data_to_store['geo_id'];
						$countries_id = $data_to_store['countries_id'];
 

				}else{
						
						$village_name = "Update [Fail]";

						 $data = array(
							"content_view" => 'address/village_add/',
							"ListSelect" => $ListSelect,
							"error" =>  'Please, enter field name'
						);
						$this->load->view('template/template',$data);
						//exit;
				}
        }
        //load the view

			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => 1,
								"ref_type" => 0,
								"ref_title" => "Village : ".$village_name.' Distrctid:'.$distrct_id.' Amphur_id:'.$amphur_id.' Province_id:'.$province_id.' Geo_id :'.$geo_id.' Countries_id :'.$countries_id,
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
		 //**************Log activity
 
         

        if($this->form_validation->run()) 
        redirect('village/pid/'.$distrct_id.'/'.$amphur_id.'/'.$province_id.'/'.$geo_id.'/'.$countries_id);
         //End**************Log activity
    }       
///////////////////////////////

	public function save2(){
		$language = $this->lang->language['lang'];
        $this->load->model('Village_model');
		$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
		$get_village_id_map= $this->Village_model->get_village_id_map($language);
		#Debug($get_village_id_map);
		$object = json_decode(json_encode($get_village_id_map), TRUE);
        $village_id_map=$object[0]['village_id']; 
        if($village_id_map==''){
		$village_id = 0;	
		}else{
		$village_id = $village_id_map;
		}
		

        
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				
				$village_id = $this->input->post('village_id_map');
				
				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('village_en', 'village_en', 'required');
				$this->form_validation->set_rules('village_th', 'village_th', 'required');
				$this->form_validation->set_rules('village_moo', 'village_moo', 'required');
				$this->form_validation->set_rules('district_id', 'district_id', 'required');
				$this->form_validation->set_rules('amphur_id', 'amphur_id', 'required');
				$this->form_validation->set_rules('province_id', 'province_id', 'required');
				$this->form_validation->set_rules('geo_id', 'geo_id', 'required');
				$this->form_validation->set_rules('countries_id', 'countries_id', 'required');
                $this->form_validation->set_rules('status', 'status', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$modified_date = $create_date = date('Y-m-d h:i:s');
				
				$lang_en = array( "lang" => 'en');
				$lang_th = array( "lang" => 'th');

				 //Debug($this->input->post());


				$order_by = $this->Village_model->get_max_order($village_id);
				//Debug($order_by);
				$get_max_id = $this->Village_model->get_max_id();
 				#Debug($get_max_id);
				#die();

				 $order = $order_by[0]['max_order'] +1;
				#$max_id = $get_max_id[0]['max_id'] +1;
				 #$order = $village_id+1;
				 $max_id = $village_id+1;
				# echo '$village_id='.$village_id;
				# echo '<br>';
				# echo '$order='.$order;
				# echo '<br>';
                # echo '$max_id='.$max_id;
                 
				//if the form has passed through the validation
				if ($this->form_validation->run()){
								$action = 2;
								$village_id = $this->input->post('village_id');
								$village_id_en = $this->input->post('village_id_en');
								$village_id_th = $this->input->post('village_id_th');
								$village_moo = $this->input->post('village_moo');
								$distrct_id = $this->input->post('distrct_id');
								$amphur_id = $this->input->post('amphur_id');
								$province_id = $this->input->post('province_id');
								$geo_id = $this->input->post('geo_id');
								$countries_id = $this->input->post('countries_id');
								$status = $this->input->post('status');
								$data_to_store = array(
									'village_name' => $this->input->post('village_en'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
									'lastupdate_by' => $admin_id
								);
								$data_to_store_th = array(
									'village_name' => $this->input->post('village_th'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
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
								if($this->Village_model->update_village($village_id_en, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
								if($this->Village_model->update_village($village_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
	
				}
        }
        //load the view

                        $village_name = $data_to_store['village_name'];
						$distrct_id = $data_to_store['distrct_id'];
						$amphur_id = $data_to_store['amphur_id'];
						$province_id = $data_to_store['province_id'];
						$geo_id = $data_to_store['geo_id'];
						$countries_id = $data_to_store['countries_id'];	
 
		$action='1';
		//**************Log activity
########IP#################		
$ipaddress1=$this->ip_address = array_key_exists('HTTP_CLIENT_IP',$_SERVER) ? $_SERVER['HTTP_CLIENT_IP'] : '127.0.0.1';
$ipaddress2=$this->ip_address = array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '0.0.0.0';
$ipaddress3=$this->ip_address = array_key_exists('HTTP_X_FORWARDED',$_SERVER) ? $_SERVER['HTTP_X_FORWARDED'] : '0.0.0.0';
$ipaddress4=$this->ip_address = array_key_exists('HTTP_FORWARDED_FOR',$_SERVER) ? $_SERVER['HTTP_FORWARDED_FOR'] : '0.0.0.0';
$ipaddress5=$this->ip_address = array_key_exists('HTTP_FORWARDED',$_SERVER) ? $_SERVER['HTTP_FORWARDED'] : '0.0.0.0';
$ipaddress6=$this->ip_address = array_key_exists('REMOTE_ADDR',$_SERVER) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
if($ipaddress1!==''){$ipaddress=$ipaddress1;}
elseif($ipaddress2!==''){$ipaddress=$ipaddress2;}
elseif($ipaddress3!==''){$ipaddress=$ipaddress3;}
elseif($ipaddress4!==''){$ipaddress=$ipaddress4;}
elseif($ipaddress5!==''){$ipaddress=$ipaddress5;}
elseif($ipaddress6!==''){$ipaddress=$ipaddress6;}
elseif($ipaddress = '127.0.0.1'||$ipaddress = '::1'){$ipaddress = '127.0.0.1';}
else{$ipaddress='UNKNOWN';}
//"from_ip" => $ipaddress,
		$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => 1,
					"ref_type" => 0,
                    "from_ip" => $ipaddress,
					"ref_title" => "Update Village..  ",
					//"ref_title" => "Village : ".$village_name.' Distrctid:'.$distrct_id.' Amphur_id:'.$amphur_id.' Province_id:'.$province_id.' Geo_id :'.$geo_id.' Countries_id :'.$countries_id,
					"action" => $action
		);
		$this->Admin_log_activity_model->store($log_activity);
		//**************Log activity

        //if($this->form_validation->run()) 
        redirect('village/pid/'.$distrct_id.'/'.$amphur_id.'/'.$province_id.'/'.$geo_id.'/'.$countries_id);
         //End**************Log activity
    }       

//////////////////////////////////
	public function delete($id){
			echo "Deleting... $id";
########IP#################		
$ipaddress1=$this->ip_address = array_key_exists('HTTP_CLIENT_IP',$_SERVER) ? $_SERVER['HTTP_CLIENT_IP'] : '127.0.0.1';
$ipaddress2=$this->ip_address = array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '0.0.0.0';
$ipaddress3=$this->ip_address = array_key_exists('HTTP_X_FORWARDED',$_SERVER) ? $_SERVER['HTTP_X_FORWARDED'] : '0.0.0.0';
$ipaddress4=$this->ip_address = array_key_exists('HTTP_FORWARDED_FOR',$_SERVER) ? $_SERVER['HTTP_FORWARDED_FOR'] : '0.0.0.0';
$ipaddress5=$this->ip_address = array_key_exists('HTTP_FORWARDED',$_SERVER) ? $_SERVER['HTTP_FORWARDED'] : '0.0.0.0';
$ipaddress6=$this->ip_address = array_key_exists('REMOTE_ADDR',$_SERVER) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
if($ipaddress1!==''){$ipaddress=$ipaddress1;}
elseif($ipaddress2!==''){$ipaddress=$ipaddress2;}
elseif($ipaddress3!==''){$ipaddress=$ipaddress3;}
elseif($ipaddress4!==''){$ipaddress=$ipaddress4;}
elseif($ipaddress5!==''){$ipaddress=$ipaddress5;}
elseif($ipaddress6!==''){$ipaddress=$ipaddress6;}
elseif($ipaddress = '127.0.0.1'||$ipaddress = '::1'){$ipaddress = '127.0.0.1';}
else{$ipaddress='UNKNOWN';}
//"from_ip" => $ipaddress,
			$OBJnews = $this->Village_model->get_status($id);
			$village_name = $OBJnews[0]['village_name'];
			$order_by = $OBJnews[0]['order_by'];

			$this->Village_model->delete_village($id);

			//**************Order New
			$this->Village_model->update_orderdel($order_by);

			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 0,
                                "from_ip" => $ipaddress,
								"ref_title" => "Deleting. village  : ".$village_name,
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('village');
			//die();
	}

}