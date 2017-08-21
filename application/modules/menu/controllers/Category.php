<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Category extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Category_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		//$breadcrumb[] = '<a href="'.base_url('category').'">'.$language['category'].'</a>';
		$breadcrumb[] = $language['category'];

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

									$this->Category_model->update_order($selectid[$i], $order[$i]);
									//Debug($this->db->last_query());
									if($tmp > $order[$i]){
											//Update ID ด้านหน้า
											//$this->Category_model->update_orderid_to_down($order[$i]);
									}

									if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
									//echo "<hr>$tmp > ".$order[$i]."<hr>";

							}
							$data['success'] = "Save success.";
					}
					//die();
			}

			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(				
					"category" => $this->Category_model->get_category(),
					"content_view" => 'basic/category',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb
			);

			$this->load->view('template/template',$data);
	}
	
	public function view_cat($id){
	
		$lang = $this->lang->language['lang'];
		
		$category = $this->Category_model->get_category_by_id($id);
		//debug($category);
		
		if($category)
			for($i=0;$i<count($category);$i++){
	
					if(($lang == 'th') && ($category[$i]['lang'] == 'th'))
							$cat_name =  $category[$i]['category_name'];
					else if(($lang == 'en') && ($category[$i]['lang'] == 'en'))
							$cat_name =  $category[$i]['category_name'];
							
			}
		return $cat_name;	
	}
	
	public function add($id = 0){	
		
			$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));		
			$language = $this->lang->language;
			
			$breadcrumb[] = '<a href="'.base_url('category').'">'.$language['category'].'</a>';
			
						
			//"admin_menu" => $this->menufactory->getMenu(),
			if($id == 0){
				$breadcrumb[] = $language['add'];
				$data = array(						
						"content_view" => 'basic/category_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb
				);
			}else{
				$breadcrumb[] = $language['edit'];
				$data = array(						
						"cat_arr" => $this->Category_model->get_category_by_id($id),
						"content_view" => 'basic/category_edit',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb
				);				
			}

			$this->load->view('template/template',$data);

	}
	
	public function status(){
		//$admin_id = $this->session->userdata('admin_id');
		//$admin_type = $this->session->userdata('admin_type');
		//$ListSelect = $this->Api_model_na->user_menu($admin_type);
		$id = 0;
		
		$id = $this->input->post("id");

			//echo $id;die();
			
			$obj_status = $this->Category_model->get_status($id);
			$cur_status = $obj_status[0]['status'];
			$category_name = $obj_status[0]['category_name'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;

			$this->Category_model->status_category($id, $cur_status);
			
			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"from_ip" => $_SERVER['REMOTE_ADDR'],
								"ref_type" => 0,
								"ref_title" => "Category : ".$category_name." [Status]",
								"action" => 2,
								"create_date" => date('Y-m-d H:i:s'),
								"status" => '1',
								"lang" => $this->lang->line('lang')
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

		echo $cur_status;	
	}
	
	public function save(){
		$admin_id = $this->session->userdata('admin_id');
		$admin_type = $this->session->userdata('admin_type');
		$ListSelect = $this->Api_model_na->user_menu($admin_type);
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				
				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('category_en', 'category_en', 'required');
				$this->form_validation->set_rules('category_th', 'category_th', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$modified_date = $create_date = date('Y-m-d h:i:s');
				
				$lang_en = array( "lang" => 'en');
				$lang_th = array( "lang" => 'th');

				$order_by = $this->Category_model->get_max_order();
				$get_max_id = $this->Category_model->get_max_id();

				$order = $order_by[0]['max_order'] +1;
				$max_id = $get_max_id[0]['max_id'] +1;

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						if($this->input->post('category_id') > 0){ //UPDATE SQL

								$category_id = $this->input->post('category_id');
								$category_id_en = $this->input->post('category_id_en');
								$category_id_th = $this->input->post('category_id_th');

								$data_to_store = array(
									'category_name' => $this->input->post('category_en'),
									'status' => $this->input->post('status'),
									'lastupdate_date' => $modified_date,
									'lastupdate_by' => $admin_id
								);
								$data_to_store_th = array(
									'category_name' => $this->input->post('category_th'),
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
								if($this->Category_model->update_category($category_id_en, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
								if($this->Category_model->update_category($category_id_th, $data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}

								$action = 2;
						
						}else{ //INSERT SQL
$category_id_en=$max_id;
								$data_to_store = array(
									'category_id_map' => $max_id,
									'category_name' => $this->input->post('category_en'),
									'status' => $this->input->post('status'),
									'lang' => 'en',
									'order_by' => $order,
									'create_date' => $create_date,
									'create_by' => $admin_id
								);
								$data_to_store_th = array(
									'category_id_map' => $max_id,
									'category_name' => $this->input->post('category_th'),
									'status' => $this->input->post('status'),
									'lang' => 'th',
									'order_by' => $order,
									'create_date' => $create_date,
									'create_by' => $admin_id
								);
								$action = 1;

								//if the insert has returned true then we show the flash message
								if($this->Category_model->store_product($data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
								if($this->Category_model->store_product($data_to_store_th)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						}

						$category_name = $data_to_store['category_name'];

				}else{

						$category_name = "Update [Fail]";

						//$data['error'] = '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>\', \'</strong></div>';
						//$data['main_content'] = 'basic/category/add';
						
						//"admin_menu" => $this->menufactory->getMenu(),
						$data = array(									
									"content_view" => 'basic/category_add',
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
								"ref_id" => $category_id_en,
								"from_ip" => $_SERVER['REMOTE_ADDR'],
								"ref_type" => 0,
								"ref_title" => "Category : ".$category_name."",
								"action" => $action,
								"create_date" => date('Y-m-d H:i:s'),
								"status" => '1',
								"lang" => $this->lang->line('lang')
			);
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

        //load the view
        //$data['main_content'] = 'basic/category';
        //$this->load->view('template/template',$data);

		if ($this->form_validation->run()) redirect('category');
    }       

	public function delete($id){
			echo "Deleting... $id";
			
			$OBJnews = $this->Category_model->get_status($id);

			//Debug($OBJnews);
			//die();
			$category_name = $OBJnews[0]['category_name'];
			$order_by = $OBJnews[0]['order_by'];

			$this->Category_model->delete_category($id);

			//**************Order New
			$this->Category_model->update_orderdel($order_by);

			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"from_ip" => $_SERVER['REMOTE_ADDR'],
								"ref_type" => 0,
								"ref_title" => "Category : ".$category_name,
								"action" => $action,
								"create_date" => date('Y-m-d H:i:s'),
								"status" => '1',
								"lang" => $this->lang->line('lang')
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('category');
			die();
	}

}