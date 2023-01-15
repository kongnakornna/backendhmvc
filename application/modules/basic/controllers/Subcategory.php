<?php

class Subcategory extends MY_Controller {

    public function __construct()    {
			parent::__construct();
			$this->load->model('Subcategory_model');
			$breadcrumb = array();			
			
			if(!$this->session->userdata('is_logged_in')){
				redirect(base_url());
			}
    }

	public function index(){
		
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('category').'">'.$language['category'].'</a>';
			$breadcrumb[] = $language['subcategory'];
			
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
					"subcategory" => $this->Subcategory_model->get_subcategory(),
					"content_view" => 'basic/subcategory',
					"ListSelect" => $ListSelect,
			);
			$this->load->view('template/template',$data);
	}

	public function cat($id = 0){

			if($id < 1){
				redirect(base_url('category'));
			}
			$category_id	= 0;
			$this->load->model('Category_model');
			$language = $this->lang->language;			
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			//$get_category = $this->Category_model->get_category_by_id($id);
			//Debug($get_category);

			$breadcrumb[] = '<a href="'.base_url('category').'">'.$language['category'].'</a>';
			$breadcrumb[] = $language['subcategory'];

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					//Debug($search_form);
					//die();

					$category_id	= $search_form['category_id'];

					if(isset($search_form['selectid'])){
							$selectid = $search_form['selectid'];
							$order = $search_form['order'];
							$maxsel = count($selectid);
							$tmp = 0;

							for($i=0;$i<$maxsel;$i++){

									$this->Subcategory_model->update_order($selectid[$i], $order[$i]);
									//Debug($this->db->last_query());

									if($tmp > $order[$i]){
											//Update ID ด้านหน้า
											//$this->Subcategory_model->update_orderid_to_down($order[$i]);
									}

									if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
									//echo "<hr>$tmp > ".$order[$i]."<hr>";
							}
					}
					//die();
			}

			if($category_id == 0) $category_id	 = $id; else $category_id	 = $category_id ;

			$data = array(
					"category_name" => $this->Category_model->get_category_by_id($category_id),
					"subcategory" => $this->Subcategory_model->get_subcategory($category_id),
					"content_view" => 'basic/subcategory',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
			);

			$this->load->view('template/template',$data);
	}

	public function add($catid = 0){
			
			$this->load->model('Category_model');
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));			
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('category').'">'.$language['category'].'</a>';
			$breadcrumb[] = '<a href="'.base_url('subcategory/cat/'.$catid).'">'.$language['subcategory'].'</a>';
			$breadcrumb[] = $language['add'];

			$data = array(
						"category_name" => $this->Category_model->get_category_by_id($catid),
						"content_view" => 'basic/subcategory_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){
			
			$catid = 0;
			$this->load->model('Category_model');
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('category').'">'.$language['category'].'</a>';
			$breadcrumb[] = '<a href="'.base_url('subcategory/cat/'.$catid).'">'.$language['subcategory'].'</a>';
			$breadcrumb[] = $language['edit'];			
			//Debug($this->input->get('category_id'));
			$catid = $this->input->get('category_id');

			$category_name = $this->Category_model->get_category_by_id($catid);
			//Debug($this->db->lastquery());
			$cat_arr = $this->Subcategory_model->get_subcategory_by_id($id);
			//Debug($this->db->lastquery());
			$data = array(
						"category_name" => $category_name,
						"cat_arr" => $cat_arr,
						"content_view" => 'basic/subcategory_edit',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);

	}

	public function list_dd($id = 0){
			
			$this->load->model('Category_model');
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;
			
			$subcatid = $this->uri->segment(4);
			
			$first = "--- ".$language['please_select']." ---";
			$subcat = $this->Subcategory_model->get_dd_list($id);
			//Debug($subcat);
			$allsubcat = count($subcat);

			echo '<option value="0">'.$first.'</option>';
			if($subcat)
				for($i=0;$i<$allsubcat;$i++){
						//echo "<li>".$subcat[$i]->subcategory_id_map." ".$subcat[$i]->subcategory_name."</li>";

						$sel = ($subcatid == $subcat[$i]->subcategory_id_map) ? 'selected' : '';
						echo '<option value="'.$subcat[$i]->subcategory_id_map.'" '.$sel.'>'.$subcat[$i]->subcategory_name.'</option>';
						//echo '<option value="'.$subcat[$i]["category_id_map"].'">'.$subcat[$i]["category_name"].'</option>';
				}
			//Debug($subcat);
			//echo "subcatid = $subcatid";
			exit;

	}	
	
	public function status($id = 0){

		if($id == 0){
			$data = array(
					"error" => 'id error'
			);
			return false;
		}else{
			
			$obj_status = $this->Subcategory_model->get_status($id);

			$subcategory_name = $obj_status[0]['subcategory_name'];
			$order_by = $obj_status[0]['order_by'];
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;
			
			$data = array(
					"cat_arr" => $this->Subcategory_model->status_subcategory($id, $cur_status),
			);

			//**************Log activity
			$action = 2;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 0,
								"ref_title" => "Sub Category : ".$subcategory_name." [Status]",
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			return $cur_status;
		}
	}
	
	public function save(){

		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
		$category_id = 0;
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				
				$category_id = $this->input->post('category_id_map');
				
				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('subcategory_en', 'subcategory_en', 'required');
				$this->form_validation->set_rules('subcategory_th', 'subcategory_th', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$modified_date = $create_date = date('Y-m-d h:i:s');
				
				$lang_en = array( "lang" => 'en');
				$lang_th = array( "lang" => 'th');

				//Debug($this->input->post());
				$order_by = $this->Subcategory_model->get_max_order($category_id);
				$get_max_id = $this->Subcategory_model->get_max_id();
				//Debug($order_by);
				//die();

				$order = $order_by[0]['max_order'] +1;
				$max_id = $get_max_id[0]['max_id'] +1;

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						if($this->input->post('subcategory_id') > 0){ //UPDATE SQL
								$action = 2;
								$subcategory_id = $this->input->post('subcategory_id');
								$subcategory_id_en = $this->input->post('subcategory_id_en');
								$subcategory_id_th = $this->input->post('subcategory_id_th');

								$data_to_store = array(
									'subcategory_name' => $this->input->post('subcategory_en'),
									'title' => $this->input->post('title'),
									'description' => $this->input->post('description'),
									'keyword' => $this->input->post('keyword'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
									'lastupdate_by' => $admin_id
								);
								$data_to_store_th = array(
									'subcategory_name' => $this->input->post('subcategory_th'),
									'title' => $this->input->post('title'),
									'description' => $this->input->post('description'),
									'keyword' => $this->input->post('keyword'),
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
								if($this->Subcategory_model->update_subcategory($subcategory_id_en, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
								if($this->Subcategory_model->update_subcategory($subcategory_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						
						}else{ //INSERT SQL
								$action = 1;

								$subcategory_id_en = $max_id;
								$data_to_store = array(
									'subcategory_id_map' => $max_id,
									'subcategory_name' => $this->input->post('subcategory_en'),
									'category_id' => $this->input->post('category_id_map'),
									'title' => $this->input->post('title'),
									'description' => $this->input->post('description'),
									'keyword' => $this->input->post('keyword'),
									'status' => $this->input->post('status'),
									'lang' => 'en',
									'order_by' => $order,
									'create_date' => $create_date,
									'create_by' => $admin_id
								);

								$data_to_store_th = array(
									'subcategory_id_map' => $max_id,
									'subcategory_name' => $this->input->post('subcategory_th'),
									'category_id' => $this->input->post('category_id_map'),
									'title' => $this->input->post('title'),
									'description' => $this->input->post('description'),
									'keyword' => $this->input->post('keyword'),
									'status' => $this->input->post('status'),
									'lang' => 'th',
									'order_by' => $order,
									'create_date' => $create_date,
									'create_by' => $admin_id
								);

								//if the insert has returned true then we show the flash message
								if($this->Subcategory_model->store_product($data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}

								if($this->Subcategory_model->store_product($data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						}

						$subcategory_name = $data_to_store['subcategory_name'];

				}else{
						
						$subcategory_name = "Update [Fail]";

						//$data['error'] = '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>\', \'</strong></div>';
						//$data['main_content'] = 'basic/subcategory/add';						
						//"admin_menu" => $this->menufactory->getMenu(),
						$data = array(
							"content_view" => 'basic/subcategory_add',
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
								"ref_id" => $subcategory_id_en,
								"ref_type" => 0,
								"ref_title" => "Sub Category : ".$subcategory_name,
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			if($this->form_validation->run()) redirect('subcategory/cat/'.$category_id);

    }       

	public function delete($id){
			echo "Deleting... $id";
			
			$OBJnews = $this->Subcategory_model->get_status($id);


			$subcategory_name = $OBJnews[0]['subcategory_name'];
			$order_by = $OBJnews[0]['order_by'];

			$this->Subcategory_model->delete_subcategory($id);

			//**************Order New
			$this->Subcategory_model->update_orderdel($order_by);

			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 0,
								"ref_title" => "Sub Category  : ".$subcategory_name,
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('category');
			die();
	}

}