<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Ads extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Ads_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$this->load->model('Category_model');
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		//$breadcrumb[] = '<a href="'.base_url('ads').'">'.$language['ads'].'</a>';
		$breadcrumb[] = $language['ads'];

		//$access_token = $this->config->config['access_token_www'];

		/********API***********/
		//http://daraapi.Tmon.com/api/web-api.php?method=ListADS&key=mMs3dAkM&gen_file=1
		$gen_api = $this->config->config['api'].'/api/web-api.php';
		$api_key = $this->config->config['api_key'];
		$method = 'ListADS';

		$GenADS = $gen_api.'?key='.$api_key.'&method='.$method.'&gen_file=1';

		$access_token = $this->config->config['access_token_www'];

		//Desktop
		$device = ($this->input->get('device')) ? $this->input->get('device') : 'desktop';
		$name = ($this->input->get('name')) ? $this->input->get('name') : 'home';
		$url = ($this->input->get('url')) ? $this->input->get('url') : $this->config->config['www'];
		$GenHome = $this->config->config['www'].'/catching/main/?access_token='.$access_token.'&name='.$name.'&url='.$url.'&device='.$device;

		$webtitle = $language['ads'].' - '.$language['titleweb'];
		//"admin_menu" => $this->menufactory->getMenu(),

		$notification_article_list = '';
		$count_approve = 0;
		$notification_article = $this->Api_model->notification_msg('article');
		if(isset($notification_article[0]->count_approve)) $count_approve = $notification_article[0]->count_approve;
		if($notification_article[0]->count_approve == 1) $notification_article_list = $this->Api_model->notification_msg('article', 0);

		$data = array(				
				"ads_list" => $this->Ads_model->get_content_all(),
				"category_list" => $this->Category_model->getSelectCat(),
				"GenADS" => $GenADS,
				"GenHome" => $GenHome,
				"notification_article" => $count_approve,
				"notification_article_list" => $notification_article_list,
				"content_view" => 'ads/ads',
				"ListSelect" => $ListSelect,
				"webtitle" => $webtitle,
				"breadcrumb" => $breadcrumb,
		);

		$this->load->view('template/template',$data);
	}
	
	public function add(){	
		
			$this->load->model('Category_model');
			//$this->load->model('Subcategory_model');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			
			$breadcrumb[] = '<a href="'.base_url('ads').'">'.$language['ads'].'</a>';
			$breadcrumb[] = $language['add'];

			$webtitle = $language['add'].$language['ads'].' - '.$language['titleweb'];
			//"admin_menu" => $this->menufactory->getMenu(),
			$data = array(						
						"content_view" => 'ads/ads_add',
						"category_list" => $this->Category_model->getSelectCat(),
						"ListSelect" => $ListSelect,
						"webtitle" => $webtitle,
						"breadcrumb" => $breadcrumb
			);
			$this->load->view('template/template',$data);
	}

	public function edit($id = 0){	
		
			$this->load->model('Category_model');
			$this->load->model('Subcategory_model');
			$this->load->model('Box_model');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			

			$ads_list = $this->Ads_model->get_content_id($id);

			if($ads_list[0]['category_id'] == 0 && $ads_list[0]['subcategory_id'] == 0){
					$ads_title = "Home";
			}else if($ads_list[0]['category_id'] == 99 && $ads_list[0]['subcategory_id'] == 0){
					$ads_title = "Default";
			}else if($ads_list[0]['category_id'] == 98 && $ads_list[0]['subcategory_id'] == 0){
					$ads_title = "Default 18+";
			}else
					$ads_title = $ads_list[0]['category_name']." ".$ads_list[0]['subcategory_name'];

			$breadcrumb[] = '<a href="'.base_url('ads').'">'.$language['ads'].'</a>';
			$breadcrumb[] = $language['edit'].' '.$ads_title;

			$webtitle = $language['edit'].$language['ads'].' - '.$language['titleweb'];

			if($id > 0){

				/*$datalog = array(
						"ref_type" => 0
				);
				$view_log = $this->Admin_log_activity_model->view_log(0, $datalog);
				$displaylogs = $this->Admin_log_activity_model->DisplayLogs($id);*/

				$data = array(						
					"ads_arr" => $ads_list[0],
					"category_list" => $this->Category_model->getSelectCat($ads_list[0]['category_id']),
					"subcategory_list" => $this->Subcategory_model->getSelectSubcat($ads_list[0]['category_id'], $ads_list[0]['subcategory_id']),
					"content_view" => 'ads/ads_edit',
					"ListSelect" => $ListSelect,
					//"view_log" => $view_log,
					//"displaylogs" => $displaylogs,
					"webtitle" => $webtitle,
					"breadcrumb" => $breadcrumb,
				);
			}else{
				$data = array(				
					"ads_arr" => $ads_list[0],
					"category_list" => $this->Category_model->getSelectCat(),
					"content_view" => 'ads/ads',
					"ListSelect" => $ListSelect,
					"webtitle" => $webtitle,
					"breadcrumb" => $breadcrumb,
					"error" => 'กรุณาเลือก ads ก่อนแก้ไข'
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
			
			$obj_status = $this->Ads_model->get_status($id);
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;

			$this->Ads_model->status_ads($id, $cur_status);
			
			//$data = array(
					//"cat_arr" => $this->Ads_model->status_ads($id, $cur_status),
					//"content_view" => 'ads/ads_edit',
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

		$breadcrumb[] = $language['ads'];
		
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
				
				//$this->input->user_agent
				//form validation
				//$this->form_validation->set_rules('category_id', 'category_id', 'required');
				//$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

				$admin_id = $this->session->userdata('admin_id');
				$now_date = date('Y-m-d H:i:s');
				
				//$data_input = $this->input->post();
				//$lang_en = array( "lang" => 'en');
				//$lang_th = array( "lang" => 'th');
				//echo "ads_id = ".$this->input->post('ads_id')."<br>";

				//Debug($this->input->post());
				//die();

				//if the form has passed through the validation
				//if ($this->form_validation->run()){

						if($this->input->post('ads_id') > 0){ //UPDATE SQL

								$action = 2;

								$ads_id = $this->input->post('ads_id');

								$data_to_store = array(
									'header' => $this->input->post('header'),
									'cover' => $this->input->post('cover'),
									'leader_board_big' => $this->input->post('leader_board_big'),
									'leader_board_mediem' => $this->input->post('leader_board_mediem'),
									'leader_board_small' => $this->input->post('leader_board_small'),
									'skin1' => $this->input->post('skin1'),
									'skin2' => $this->input->post('skin2'),
									'skin3' => $this->input->post('skin3'),
									'ads_1' => $this->input->post('ads_1'),
									'ads_2' => $this->input->post('ads_2'),
									'ads_3' => $this->input->post('ads_3'),
									'ads_4' => $this->input->post('ads_4'),
									'ads_5' => $this->input->post('ads_5'),
									'footer' => $this->input->post('footer'),

									'm_cover' => $this->input->post('m_cover'),

									'pre_roll' => $this->input->post('pre_roll'),
									'overlay' => $this->input->post('overlay'),

									'status' => $this->input->post('status'),
									'lastupdate_by' => $admin_id,
									'lastupdate_date' => $now_date
								);

								if($this->input->post('category_id') != 0 && $this->input->post('category_id') != 99 && $this->input->post('category_id') != 98)
									$data_to_store['category_id'] = intval($this->input->post('category_id'));

								//if($this->input->post('subcategory_id'))
								$data_to_store['subcategory_id'] = intval($this->input->post('subcategory_id'));

								//Debug($data_to_store);
								//die();

								//if the insert has returned true then we show the flash message
								if($this->Ads_model->store($ads_id, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						
						}else{ //INSERT SQL
								
								$action = 1;
								$get_max_id = $this->Ads_model->get_max_id();
								$max_id = $get_max_id[0]['max_id'] +1;
								$ads_id = $max_id;

								$data_to_store = array(
									'ads_id' => $max_id,
									'category_id' => $this->input->post('category_id'),
									'subcategory_id' => $this->input->post('subcategory_id'),
									'header' => $this->input->post('header'),
									'cover' => $this->input->post('cover'),
									'leader_board_big' => $this->input->post('leader_board_big'),
									'leader_board_mediem' => $this->input->post('leader_board_mediem'),
									'leader_board_small' => $this->input->post('leader_board_small'),

									'skin1' => $this->input->post('skin1'),
									'skin2' => $this->input->post('skin2'),
									'skin3' => $this->input->post('skin3'),
									'ads_1' => $this->input->post('ads_1'),
									'ads_2' => $this->input->post('ads_2'),
									'ads_3' => $this->input->post('ads_3'),
									'ads_4' => $this->input->post('ads_4'),
									'ads_5' => $this->input->post('ads_5'),
									'footer' => $this->input->post('footer'),

									'm_cover' => $this->input->post('m_cover'),

									'status' => $this->input->post('status'),
									'create_by' => $admin_id,
									'create_date' => $now_date
								);

								//if the insert has returned true then we show the flash message
								if($this->Ads_model->store(0, $data_to_store)){
									$data['flash_message'] = TRUE; 
								}else{
									$data['flash_message'] = FALSE; 
								}
						}

				//}
        }

		//Debug($data_to_store);
		//Debug($this->db->last_query());
		//die();
        //load the view
        //$data['main_content'] = 'ads/ads';
        //$this->load->view('template/template',$data);

		//if ($this->form_validation->run()) redirect('ads');

		//**************Log activity
		$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $ads_id,
								"from_ip" => $_SERVER['REMOTE_ADDR'],
								"ref_type" => 0,
								"ref_title" => "Update Ads",
								"action" => $action,
								"create_date" => date('Y-m-d H:i:s'),
								"status" => '1',
								"lang" => $this->lang->line('lang')
		);			
		$this->Admin_log_activity_model->store($log_activity);
		//**************Log activity

		/*$data = array(				
					"ads_list" => $this->Ads_model->get_content_all(),
					"content_view" => 'ads/ads',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
					"success" =>  'Save complete.'
		);
		$this->load->view('template/template',$data);*/
		redirect('ads?success='.urlencode('Save complete'));

    }       

	public function delete($id = 0){
			
		if($id > 0){

			$title = $this->input->get('title');
			$this->Ads_model->delete_ads($id);

		}else{
			
		}

		//**************Log activity
		$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"from_ip" => $_SERVER['REMOTE_ADDR'],
								"ref_type" => 0,
								"ref_title" => "Delete Ads ".$title,
								"action" => 3,
								"create_date" => date('Y-m-d H:i:s'),
								"status" => '1',
								"lang" => $this->lang->line('lang')
		);			
		$this->Admin_log_activity_model->store($log_activity);
		//**************Log activity

		redirect('ads');
	
		//$this->load->view('template/template',$data);
	
	}
}