<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Channel extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Channel_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		//$breadcrumb[] = '<a href="'.base_url('channel').'">'.$language['channel'].'</a>';
		$breadcrumb[] = $language['channel'];

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

									$this->Channel_model->update_order($selectid[$i], $order[$i]);
									//Debug($this->db->last_query());
									if($tmp > $order[$i]){
											//Update ID ด้านหน้า
											//$this->Channel_model->update_orderid_to_down($order[$i]);
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
				"channel_list" => $this->Channel_model->get_content_all(),
				"content_view" => 'channel/channel',
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb
		);

		$this->load->view('template/template',$data);
	}
	
	public function add(){	
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('channel').'">'.$language['channel'].'</a>';
			$breadcrumb[] = $language['add'];
						
			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(						
						"content_view" => 'channel/channel_add',
						"ListSelect" => $ListSelect,
						"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){	

			$this->load->model('Tags_model');
		
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('channel').'">'.$language['channel'].'</a>';
			$breadcrumb[] = $language['edit'];						
			//"admin_menu" => $this->menufactory->getMenu(),

			if($id > 0){
				$tags_channel = $this->Tags_model->get_tag_pair($id, 7);
				$data = array(						
					"channel_arr" => $this->Channel_model->get_content_id($id),
					"tags_channel" => $tags_channel,
					"content_view" => 'channel/channel_edit',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
				);
			}else{
				$data = array(				
					"channel_list" => $this->Channel_model->get_content_all(),
					"content_view" => 'channel/channel',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					"error" => 'กรุณาเลือก ช่องรายการก่อน'
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
			
			$obj_status = $this->Channel_model->get_status($id);
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;

			$this->Channel_model->status_channel($id, $cur_status);
			
			//$data = array(
					//"cat_arr" => $this->Channel_model->status_channel($id, $cur_status),
					//"content_view" => 'channel/channel_edit',
					//"ListSelect" => $ListSelect,
					//"error" => array()
			//);
			echo $cur_status;
			//return $cur_status;
		}
	
		//$this->load->view('template/template',$data);
	
	}

	public function remove_img($id = 0){
			
			$src = $this->input->post('img');

			unlink('uploads/channel/'.$src);
			//$obj_data['channel_icon'] = '';
			if($this->Channel_model->remove_img($id))
				echo 'Yes';
			else
				echo 'No';

	}

	private function set_upload_options(){   

		$config = array();
		//$folder = date('Ymd');
		
		$config['upload_path'] = './uploads/channel/';
		if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size']      = '0';
		$config['overwrite']     = FALSE;

		return $config;
	}
	
	public function save(){

		$this->load->model('Tags_model');
		
		$language = $this->lang->language;
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

		$breadcrumb[] = $language['channel'];
		
        if ($this->input->server('REQUEST_METHOD') === 'POST'){

				//$data_store_en = $data_store_th = array();
				
				//$this->input->user_agent
				//form validation
				$this->form_validation->set_rules('channel_name', 'channel_name', 'required');
				$this->form_validation->set_rules('channel_name2', 'channel_name2', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');

				$now_date = date('Y-m-d H:i:s');
				$folder = date('Ymd');
				
				$this->load->library('upload', $this->set_upload_options());

				// Alternately you can set preferences by calling the initialize function. Useful if you auto-load the class:
				$this->upload->initialize($this->set_upload_options());
				//Debug($config);
				if ( ! $this->upload->do_upload('channel_icon')){
					$error = array('error' => $this->upload->display_errors());
					$upload_status = $error;
				}else{
					$upload_status = "Success";
				}

				//Debug($this->upload);
				//if($this->upload->file_size > 0) echo "file_size = ".$this->upload->file_size;
				//die();
				//Debug($this->input->post());

				//if the form has passed through the validation
				if ($this->form_validation->run()){

						$data_input = $this->input->post();
						//Debug($data_input);
						if($data_input['tags'] != "") $get_tags = explode(",", trim($data_input['tags']));

						//Debug($get_tags);
						//die();

						if(isset($data_input['channel_status']) == 1) $channel_status = 1; else $channel_status = 0;

						if($this->input->post('channel_id') > 0){ //UPDATE SQL

								//$channel_id = $this->input->post('channel_id');
								$channel_id_en = $this->input->post('channel_id');
								$channel_id_th = $this->input->post('channel_id2');

								$data_store_en = array(
											'channel_id2' => $channel_id_en,
											'channel_name' => $data_input['channel_name2'],
											'lang' => 'en',
											'status' => $channel_status,
											'lastupdate_date' => $now_date,
											'lastupdate_by' => $admin_id
										);

								$data_store_th = array(
											'channel_id2' => $channel_id_en,
											'channel_name' => $data_input['channel_name'],
											'lang' => 'th',
											'status' => $channel_status,
											'lastupdate_date' => $now_date,
											'lastupdate_by' => $admin_id
										);

								if($this->upload->client_name){
										$data_store_en['channel_icon'] = $this->upload->client_name;
										$data_store_th['channel_icon'] = $this->upload->client_name;
								}

								$this->Channel_model->store($channel_id_en, $data_store_en);
								$this->Channel_model->store($channel_id_th, $data_store_th);
								$action = 2;

						}else{ //INSERT SQL								

								//$order_by = $this->Channel_model->get_max_order();
								$get_max_id = $this->Channel_model->get_max_id();

								//$order = $order_by[0]['max_order'] +1;
								$max_id = $get_max_id[0]['max_id'] +1;
								$channel_id_en = $max_id;

								$data_store_en = array(
											'channel_id2' => $max_id,
											'channel_name' => $data_input['channel_name2'],
											'lang' => 'en',
											'status' => $channel_status,
											'create_date' => $now_date,
											'create_by' => $admin_id
										);
								$data_store_th = array(
											'channel_id2' => $max_id,
											'channel_name' => $data_input['channel_name'],
											'lang' => 'th',
											'status' => $channel_status,
											'create_date' => $now_date,
											'create_by' => $admin_id
										);

								if($this->upload->client_name){
										$data_store_en['channel_icon'] = $this->upload->client_name;
										$data_store_th['channel_icon'] = $this->upload->client_name;
								}

								$this->Channel_model->store(0, $data_store_en);
								$this->Channel_model->store(0, $data_store_th);
								$action = 1;
						}

				}else{
						redirect('channel/channel_add?error=Please, enter field '.$language['channel_name']);
						exit;
				}
        }

        //load the view
        //$data['main_content'] = 'channel/channel';
        //$this->load->view('template/template',$data);

		//if ($this->form_validation->run()) redirect('channel');

		if ($this->form_validation->run()){

				//**************Add tags******************
				if(isset($get_tags)){
						foreach($get_tags as $key){
									//echo "key = $key<br>";
									$curtag = $this->Tags_model->validate_tags($key);
									if(count($curtag) == 0){

											$get_max_id = $this->Tags_model->get_max_id();											
											$maxid_tag = $get_max_id[0]['max_id'];
											$maxid_tag++;
										
											$obj['tag_id'] = $maxid_tag;
											$obj['tag_text'] = trim($key);
											$obj['create_date'] = $now_date;
											$this->Tags_model->store($obj);

											$obj2['tag_id'] = $maxid_tag;
											$obj2['ref_id'] = $channel_id_en;
											$obj2['ref_type'] = 7;
											$obj2['create_date'] = $now_date;
											$this->Tags_model->store1_tagpair($obj2);

									}else{

											$chktag_pair = $this->Tags_model->chktag_pair($curtag[0]->tag_id, 7);
											if(count($chktag_pair) == 0){

													$obj2['tag_id'] = $curtag[0]->tag_id;
													$obj2['ref_id'] = $channel_id_en;
													$obj2['ref_type'] = 7;
													$obj2['create_date'] = $now_date;

													$this->Tags_model->store1_tagpair($obj2);
											}
									}
						}
				}

				//**************Log activity
				$log_activity = array(
						"admin_id" => $this->session->userdata('admin_id'),
						"ref_id" => $channel_id_en,
						"ref_type" => 7,
						"ref_title" => $data_input['channel_name'],
						"action" => $action
				);			
				$this->Admin_log_activity_model->store($log_activity);
				//**************Log activity

				$data = array(				
							"channel_list" => $this->Channel_model->get_content_all(),
							"content_view" => 'channel/channel',
							"ListSelect" => $ListSelect,
							"breadcrumb" => $breadcrumb,
							"success" =>  'Save Channel complete.'
				);
				$this->load->view('template/template',$data);
		}

    }       

}