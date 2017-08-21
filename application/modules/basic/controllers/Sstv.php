<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sstv extends CI_Controller {

    public function __construct()    {
		parent::__construct();
		$this->load->model('Sstv_model');
		$breadcrumb = array();

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }

	public function index($id = null){

	}
	
	
	public function news($id = null){
		
		//$this->load->model('News_model');

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
				//"item_list" => $this->News_model->get_news_by_id($id),
				//"elvis_list" => $this->Elvis_model->search($keyword, 0, 50),
				"content_view" => 'elvis/list',
				"breadcrumb" => $breadcrumb,
				"error" => array()
		);
	
		$this->load->view('template/template',$data);
	}

	public function download($id = null){
		
		ini_set('allow_url_fopen', true);
		
		$this->load->model('Picture_model');
		$this->load->model('Vdo_model');

		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
		$language = $this->lang->language;
		
		$now_date = date('Y-m-d H:i:s');
		$picture_size = 0;

		//$sstv_url = urldecode($this->input->get('url'));
		$sstv_url = base64_decode($this->input->get('url'));
		$getsstv_url = explode("/", $sstv_url);
		$ref_type = $this->input->get('ref_type');
	
		$folder = $this->input->get('folder');
		//Debug($folder);

		if(($folder == "") && ($getsstv_url[4] != "") && ($getsstv_url[5] != "")){
			//$folder = date('Ymd');	
			$folder = $getsstv_url[4]."/".$getsstv_url[5];
		}

		$chkfolder = explode("/", $folder);
		//Debug($chkfolder);
		if(count($chkfolder) > 1){
				$addfolder = "";
				for($i=0;$i<count($chkfolder);$i++){

						if($i == 0)
							$addfolder = $chkfolder[$i];
						else
							$addfolder .= "/".$chkfolder[$i];

						$upload_path = './uploads/tmp/'.$addfolder;
						if(!is_dir($upload_path)) mkdir($upload_path, 0777);

						$upload_path = './uploads/vdo';
						if(!is_dir($upload_path)) mkdir($upload_path, 0777);

						$upload_path = './uploads/vdo/'.$addfolder;
						if(!is_dir($upload_path)) mkdir($upload_path, 0777);

						//Debug($upload_path);
						
				}
				$folder = $addfolder;
		}else{
				$upload_path = './uploads/tmp/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);

				$upload_path = './uploads/vdo';
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);

				$upload_path = './uploads/vdo/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
		}

		if($ref_type == 0) $ref_type = 4;

		$img_size = getimagesize($sstv_url);

		//Debug($this->input->get());
		/*echo "<br>sstv_url=$sstv_url<br>";
		Debug($img_size);
		echo "<br>this->Sstv_model->download($sstv_url, $folder, $ref_type)<br>";
		die();*/

		$picture_size = ($img_size[0] > 500) ? $img_size[0] : 500;
		//Debug($picture_size);
		//Debug("($sstv_url, $folder, $ref_type, $picture_size)");

		$display_pic = $this->Sstv_model->download($sstv_url, $folder, $ref_type, $picture_size);
		/******* Insert to DB ********/
		//die();

		$picture_obj['ref_id'] = $id;
		$picture_obj['ref_type'] = $ref_type;//Type of News Column Gallery
		$picture_obj['file_name'] = $display_pic;
		//$picture_obj['title'] = StripTxt($data_input['title']);		
		//$picture_obj['caption'] = StripTxt($data_input['title_en']);
		
		$picture_obj['folder'] = $folder;
		$picture_obj['create_date'] = $now_date;
		$picture_obj['create_by'] = $this->session->userdata('admin_id');
		$picture_obj['status'] = 1;
		$picture_obj['default'] = 1;
		$this->Picture_model->store(0, $picture_obj);

		/******************************/
		echo "Download complete. ";

		redirect('vdo/picture/'.$id);
	
		/*$data = array(
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
		$this->load->view('template/template',$data);*/
	}
	
	public function make_picture($id){
		
	}
		
}
