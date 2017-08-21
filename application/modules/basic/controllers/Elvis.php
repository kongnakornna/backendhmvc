<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Elvis extends CI_Controller {

    public function __construct()    {
		parent::__construct();
		$this->load->model('Elvis_model');
		$breadcrumb = array();

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }

	public function index($id = null){

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
			$language = $this->lang->language;

			$keyword = "";

			$webelvis = "http://elvis.siamsport.co.th/web";

			//$breadcrumb[] = $language['elvis'];
			$breadcrumb[] = "Elvis";

			$search_form = $this->input->post();
			$keyword = $search_form['keyword'];

			if($search_form){
					$keyword = $search_form['keyword'];
			}else
					$keyword = "Tmon";

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"gotoelvis" => $webelvis,
					//"dara_list" => $this->Dara_model->get_dara_profile(),
					"elvis_list" => $this->Elvis_model->search($keyword, 0, 50),
					"content_view" => 'elvis/list',
					"breadcrumb" => $breadcrumb,
					"error" => array()
			);

			$this->load->view('template/template',$data);
	}
	
	public function login(){

			//$login = $this->Elvis_model->login();
			//debug($login);

			$data['content_view'] =  'elvis/login';
			$this->load->view('template/template',$data);	
	}
	
	public function news($id = null){
		
		$this->load->model('News_model');
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
		$language = $this->lang->language;
		$keyword = "";
		$breadcrumb[] = "Elvis";
		$search_form = $this->input->post();
		/*$test = explode(" ", $keyword);	
		if(sizeof($test) > 1){
			redirect(base_url('elvis/news/'.$id));
			die();
		}*/
		if($search_form){
			$keyword = str_replace(" ", "-", $search_form['keyword']);
		}else
			$keyword = "Tmon";
	
		$data = array(
				"admin_menu" => $this->menufactory->getMenu(),
				"ListSelect" => $ListSelect,
				"ref_type" => 1,	//1 = news, 2 = column, 3 = gallery
				"item_id" => $id,
				"item_list" => $this->News_model->get_news_by_id($id),
				"elvis_list" => $this->Elvis_model->search($keyword, 0, 50),
				"content_view" => 'elvis/list',
				"breadcrumb" => $breadcrumb,
				"error" => array()
		);
	
		$this->load->view('template/template',$data);
	}

	public function column($id = null){
		
		$this->load->model('Column_model');
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
		$language = $this->lang->language;
		$keyword = "";
		//$breadcrumb[] = $language['elvis'];
		$breadcrumb[] = "Elvis";
		$search_form = $this->input->post();
		/*$test = explode(" ", $keyword);		
		if(sizeof($test) > 1){
			redirect(base_url('elvis/news/'.$id));
			die();
		}*/
		if($search_form){
			$keyword = str_replace(" ", "-", $search_form['keyword']);
			//$keyword = $search_form['keyword'];
		}else
			$keyword = "Tmon";

		$data = array(
				"admin_menu" => $this->menufactory->getMenu(),
				"ListSelect" => $ListSelect,
				"ref_type" => 2,
				"item_id" => $id,
				"item_list" => $this->Column_model->get_column_by_id($id),
				"elvis_list" => $this->Elvis_model->search($keyword, 0, 50),
				//"content_view" => 'elvis/list_gallery',
				"content_view" => 'elvis/list',
				"breadcrumb" => $breadcrumb,
				"error" => array()
		);
	
		$this->load->view('template/template',$data);
	}

	public function gallery($id = null){
		
		$this->load->model('Gallery_model');
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
		$language = $this->lang->language;
		$keyword = "";
		//$breadcrumb[] = $language['elvis'];
		$breadcrumb[] = "Elvis";
		$search_form = $this->input->post();
		/*$test = explode(" ", $keyword);		
		if(sizeof($test) > 1){
			redirect(base_url('elvis/news/'.$id));
			die();
		}*/
		if($search_form){
			$keyword = str_replace(" ", "-", $search_form['keyword']);
			//$keyword = $search_form['keyword'];
		}else
			$keyword = "Tmon";

		$data = array(
				"admin_menu" => $this->menufactory->getMenu(),
				"ListSelect" => $ListSelect,
				"ref_type" => 3,
				"item_id" => $id,
				"item_list" => $this->Gallery_model->get_data_by_id($id),
				"elvis_list" => $this->Elvis_model->search($keyword, 0, 50),
				//"content_view" => 'elvis/list_gallery',
				"content_view" => 'elvis/list',
				"breadcrumb" => $breadcrumb,
				"error" => array()
		);
	
		$this->load->view('template/template',$data);
	}

	public function loadmore(){

		$this->load->model('Gallery_model');

		//Debug($this->uri->segments);
		//$lastid = $this->input->post('lastid');
		//$keyword = $this->input->post('keyword');

		$lastid = $this->uri->segment(3);
		$keyword = urldecode($this->uri->segment(4));
		$keyword = str_replace(" ", "-", $keyword);

		if(trim($keyword) == "") $keyword = "Tmon";
		if($lastid <= 0) $lastid = 0;

		$data = array(
			"elvis_list" => $this->Elvis_model->search($keyword, $lastid, 50),
			"lastid" => $lastid + 50
		);
		//$elvis_list = $this->Elvis_model->search($keyword, $lastid, 50);
		//echo "<code>keyword=$keyword lastid=$lastid</code>";
		//Debug($elvis_list);

		$this->load->view('elvis/loadmore', $data);
	}

	public function download($id = null){
		
		ini_set('allow_url_fopen', true);
		
		$this->load->model('Picture_model');
		$this->load->model('News_model');
		//$this->load->model('Elvis_model');
		$this->load->model('Gallery_model');

		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
		$language = $this->lang->language;
		
		$now_date = date('Y-m-d H:i:s');
		
		$folder = $this->input->get('folder');
		if($folder == "") $folder = date('Ymd');	
			
		$upload_path = './uploads/tmp/'.$folder;
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		$upload_path = './uploads/news/'.$folder;
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		$upload_path = './uploads/column/'.$folder;
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		$upload_path = './uploads/gallery/'.$folder;
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		$ref_elvis = base64_decode($this->input->get('ref_id'));
		$ref_type = $this->input->get('ref_type');

		//$breadcrumb[] = $language['elvis'];
		$breadcrumb[] = "Elvis";
		//echo "<hr>ref_type=$ref_type<br>folder=$folder<br>ref_elvis=$ref_elvis<hr>";
		//$fileExtension = strrchr($display_pic, ".");

		$ref_type = (!$ref_type) ? 1 : $ref_type;
		//Debug("$ref_elvis, $folder, $ref_type");

		$display_pic = $this->Elvis_model->download($ref_elvis, $folder, $ref_type);

		/******* Insert to DB ********/

		$picture_obj['ref_id'] = $id;
		$picture_obj['ref_type'] = $ref_type;//Type of News Column Gallery
		$picture_obj['file_name'] = $display_pic;
		//$picture_obj['title'] = StripTxt($data_input['title']);		
		//$picture_obj['caption'] = StripTxt($data_input['title_en']);
		
		$picture_obj['folder'] = $folder;
		$picture_obj['create_date'] = $now_date;
		$picture_obj['create_by'] = $this->session->userdata('admin_id');
		$picture_obj['status'] = 1;
		$this->Picture_model->store(0, $picture_obj);

		/******************************/
	
		$data = array(
				"admin_menu" => $this->menufactory->getMenu(),
				"ListSelect" => $ListSelect,
				"news_id" => $id,
				"folder" => $folder,
				"ref_elvis" => $ref_elvis,
				"elvis_view" => $display_pic,
				"content_view" => 'elvis/view_download',
				"breadcrumb" => $breadcrumb,
				"error" => array()
		);
	
		$this->load->view('template/template',$data);
	}
	
	public function make_picture($id){
		
	}
		
}
