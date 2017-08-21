<?php

class Tags_content extends MY_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('Tags_model');
        $this->load->model('Tags_content_model');
        $breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){

		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		$breadcrumb[] = '<a href="'.base_url('tags').'">'.$language['tags'].'</a>';
		$breadcrumb[] = $language['news'];

		$number_mod = 1;
		$keyword = '';
		
        if($this->input->server('REQUEST_METHOD') === 'POST'){
				//Debug($this->input->post());
				if($this->input->post('keyword')) $keyword = $this->input->post('keyword');
				if($this->input->post('mod')){
					$mod = $this->input->post('mod');
					/*switch($mod){
							case 'news' : $number_mod = 1; break;
							case 'column' : $number_mod = 2; break;
							case 'gallery' : $number_mod = 3; break;
							case 'vdo' : $number_mod = 4; break;
							case 'dara' : $number_mod = 5; break;
					}*/
				}
		}
		//Debug($mod);
		//Debug($keyword);

		if($keyword){
			$news_list = $this->Tags_content_model->search_incontent(1, $keyword); //tags ของ news
			$column_list = $this->Tags_content_model->search_incontent(2, $keyword); //tags ของ news
			$gallery_list = $this->Tags_content_model->search_incontent(3, $keyword); //tags ของ news
			$vdo_list = $this->Tags_content_model->search_incontent(4, $keyword); //tags ของ news
		}else
			$news_list = $column_list = $gallery_list = $vdo_list = array();

		//Debug($tags_list);
		//die();

		$data = array(						
				"content_view" => 'tags/tags_in_detail',
				"news_list" => $news_list,
				"column_list" => $column_list,
				"gallery_list" => $gallery_list,
				"vdo_list" => $vdo_list,
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);
	}
	
	public function dara(){	

		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		$breadcrumb[] = '<a href="'.base_url('tags').'">'.$language['tags'].'</a>';
		//$breadcrumb[] = $language['tags'];
		$breadcrumb[] = $language['dara'];
		
		$tags_list = $this->Tags_model->getall_tag_pair(5); //tags ของดารา

		$data = array(						
				"content_view" => 'tags/tags_pair_dara',
				"tags_list" => $tags_list,
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);
	}

	public function news(){	

		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		$breadcrumb[] = '<a href="'.base_url('tags').'">'.$language['tags'].'</a>';
		$breadcrumb[] = $language['news'];

		$number_mod = 1;
		$keyword = '';
		
        if($this->input->server('REQUEST_METHOD') === 'POST'){
				//Debug($this->input->post());
				if($this->input->post('keyword')) $keyword = $this->input->post('keyword');
				if($this->input->post('mod')){
					$mod = $this->input->post('mod');
					switch($mod){
							case 'news' : $number_mod = 1; break;
							case 'column' : $number_mod = 2; break;
							case 'gallery' : $number_mod = 3; break;
							case 'vdo' : $number_mod = 4; break;
							case 'dara' : $number_mod = 5; break;
					}
				}
		}
		//Debug($mod);
		//Debug($keyword);

		$tags_list = $this->Tags_content_model->getall_tag_pair($number_mod, $keyword); //tags ของ news
		//Debug($tags_list);
		//die();

		$data = array(						
				"content_view" => 'tags/tags_pair_list',
				"tags_list" => $tags_list,
				"ListSelect" => $ListSelect,
				"breadcrumb" => $breadcrumb,
		);
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
			
			$obj_status = $this->Tags_model->get_status($id);
			$cur_status = $obj_status[0]['status'];
			//Debug($cur_status);
			
			if($cur_status == 0) $cur_status = 1;
			else $cur_status = 0;

			$this->Tags_model->status_tags($id, $cur_status);
			
			//$data = array(
					//"cat_arr" => $this->Tags_model->status_tags($id, $cur_status),
					//"content_view" => 'tags/tags_edit',
					//"ListSelect" => $ListSelect,
					//"error" => array()
			//);
			echo $cur_status;

			return $cur_status;
		}
		//$this->load->view('template/template',$data);
	}

	/*public function delete($tag_id){	
			$this->Tags_model->delete_tag($tag_id);
			//if(isset($this->input->get('tag_text'))) $tag_text = $this->input->get('tag_text');
			$tag_text = $this->input->get('tag_text');
			//**************Log activity
			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $tag_id,
					"ref_type" => 1,
					"ref_title" => "tags : ".trim($tag_text),
					"action" => 3
			);
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity
			redirect('tags');
	}*/

}