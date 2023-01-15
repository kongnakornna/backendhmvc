<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/

class Other extends CI_Controller {

    public function __construct()    {
        parent::__construct();
			$this->load->model('Smsalertlog_model');
			$breadcrumb = array();
			//$Path_CKeditor = 'theme/assets/ckeditor-integrated/ckeditor';
			//$Path_CKfinder = 'theme/assets/ckeditor-integrated/ckfinder';

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }

	public function index(){
			
			$this->load->model('Category_model');
			$this->load->model('Subcategory_model');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			
			$category_id = $subcategory_id = 0;
			$start_page = 0;
			$p = 1;
			$list_page = 10;
			$listspage = '';

			$datainput = $this->input->get();
			//Debug($datainput);
			if(isset($datainput['p'])){
				$p = ($datainput['p'] > 0) ? $datainput['p'] : 1;
				if($p > 1){
						$start_page = ($p-1)*$list_page;
				}
			}else{
				$p = 1;
			}
			$breadcrumb[] = $language['alarmlogreport'];

			$opt[]	= makeOption(10, 10);
			$opt[]	= makeOption(20, 20);
			$opt[]	= makeOption(50, 50);
			$opt[]	= makeOption(100, 100);
 			$opt[]	= makeOption(500, 500);
					$keyword = "sensor";

					$get_data = $this->input->get();

					//category_id
					if(isset($get_data['category_id'])){
							$category_id = $get_data['category_id'];
							$search_form['category_id'] = $category_id;
							$news_list = $this->Smsalertlog_model->get_news(null, null, $language['lang'], $search_form);
							$category_list = $this->Category_model->getSelectCat($category_id, 'category_id', 'News');
							$listspage = selectList( $opt, 'list_page', 'class="form-control"', 'value', 'text', $list_page);
							$subcategory_id = 0;
					}else{

							//echo "get_news( 'order_by', 'Asc', $start_page, $list_page)";
							//$news_list = $this->Smsalertlog_model->get_news(null, null, $language['lang'], null, 'order_by', 'Asc', $start_page, $list_page);
							$news_list = $this->Smsalertlog_model->get_news(null, null, $language['lang'], null);

							$listspage = selectList( $opt, 'list_page', 'class="form-control"', 'value', 'text', $list_page);
							$category_list = $this->Category_model->getSelectCat(0, 'category_id', 'News');

					}
			

			//$selcat = $this->input->post();
			//Debug($selcat);
			//if(isset($selcat['category_id'])) $category_id = 0;
			//if(isset($selcat['subcategory_id'])) $subcategory_id = 0;

			$totalnews = $this->Smsalertlog_model->countnews($category_id, $subcategory_id);
			$curpage = base_url('alarmlogreport?view=list');

			//$slip_page = ceil($maxnews/$list_page);
			//Debug($news_list);
			//die();

			$notification_birthday = $this->Api_model->notification_birthday();

			$data = array(
					"ListSelect" => $ListSelect,
					"news_list" => $news_list,
					//"GenPage" => GenPage($curpage, $p, $list_page, $totalnews),
					"category_list" => $category_list,
					"category_id" => $category_id,
					"subcategory_id" => $subcategory_id,
					"listspage" => $listspage,
					"totalnews" => $totalnews,
					"notification_birthday" => $notification_birthday,
					"breadcrumb" => $breadcrumb,
					"content_view" => 'tmon/alarmlogreport'
			);
			$msg = $this->input->get();
			if(isset($msg['success'])) $data['success'] = urldecode($msg['success']);

			$this->load->view('template/template',$data);
	}
}
