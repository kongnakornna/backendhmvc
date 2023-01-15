<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {

    public function __construct()    {
        parent::__construct();
			$this->load->model('news_model');
			$breadcrumb = array();
			//$Path_CKeditor = 'theme/assets/ckeditor-integrated/ckeditor';
			//$Path_CKfinder = 'theme/assets/ckeditor-integrated/ckfinder';

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }

	public function index(){
			
			$this->load->model('category_model');
			$language = $this->lang->language;
			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));

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
			$breadcrumb[] = $language['news'];

			$opt[]	= makeOption(10, 10);
			$opt[]	= makeOption(20, 20);
			$opt[]	= makeOption(50, 50);
			$opt[]	= makeOption(100, 100);

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					//Debug($search_form);
					//die();
					if(isset($search_form['category_id'])){ 
						$category_id = $search_form['category_id'];

						if(isset($search_form['subcategory_id'])){ 
							$subcategory_id = $search_form['subcategory_id']; 
						}else $subcategory_id = 0;

					}else{
						unset($search_form['subcategory_id']);
						$category_id = 0;
					}

					if(isset($search_form['list_page'])) $list_page = ($search_form['list_page'] > 0) ? $search_form['list_page'] : 10;

					$listspage = selectList( $opt, 'list_page', 'class="form-control"', 'value', 'text', $list_page);

					if(isset($search_form['selectid'])){
							//Debug($search_form);
							$selectid = $search_form['selectid'];
							$order = $search_form['order'];
							$search_form['category_id'] = $category = $search_form['category'];
							$maxsel = count($selectid);
							$toup = $tmp = 0;

							for($i=0;$i<$maxsel;$i++){


									if($tmp > $order[$i]){

											//echo "(".$tmp." > ".$order[$i].")<br>";
											//Update ID ด้านหน้า + 1
											$this->news_model->update_orderid_to_down($category, $order[$i], $tmp);
											//Debug($this->db->last_query());
									}

									//echo "<hr>";

									if($order[$i]%10 == 1){
											
											//echo "(".$order[$i]."%10 == 1)";

									}else if((($tmp + 1) <> $order[$i]) && ($toup == 0)){
											
											//echo "((($tmp + 1) <> ".$order[$i].") && ($toup == 0))<br>";

											//Update ID ด้านหลัง - 1
											$toup = $tmp + 1;
											$this->news_model->update_orderid_to_up($category, $order[$i], $toup);
											//Debug($this->db->last_query());
									}

									//Update Cur ID
									$this->news_model->update_order($category, $selectid[$i], $order[$i]);
									//Debug($this->db->last_query());
									if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
									//echo "<hr>";
							}
							$category_id = $category;
							$subcategory_id = 0;
							//die();
					}

					//$keyword = trim($search_form['keyword']);
					if(isset($search_form['keyword'])){
						//$search_news = $search_form['keyword'];
						//$search_news['keyword'] = $search_form['keyword'];
						$search_news = explode(" " ,$search_form['keyword']);

						$news_list = $this->news_model->get_news(null, $search_news, $language['lang']);
						$category_list = $this->category_model->getSelectCat(0, 'category_id', 'News');

					}else if(isset($search_form['selectid'])){

						$keyword = "siamdara";
						$news_list = $this->news_model->get_news(null, null, $language['lang']);

						if(isset($search_form['category_id'])){
								
							$cat_view['category_id'] = $search_form['category_id'];
							//Debug($cat_view);

							$news_list = $this->news_model->get_news(null, null, $language['lang'], $cat_view);
							//Debug($this->db->last_query());
							$category_list = $this->category_model->getSelectCat($cat_view['category_id'], 'category_id', 'News');
							//Debug($this->db->last_query());

						}else
							$category_list = $this->category_model->getSelectCat(0, 'category_id', 'News');

					}else{	
						//echo "no keyword";
						//$keyword = "siamdara";
						//Debug($search_form);
						
						unset($search_form['list_page']);
						//Debug($search_form);

						//$news_list = $this->news_model->get_news(null, null, $language['lang'], $search_form, 'order_by', 'Asc', $start_page, $list_page);
						$news_list = $this->news_model->get_news(null, null, $language['lang'], $search_form);
						$category_list = $this->category_model->getSelectCat($search_form['category_id'], 'category_id', 'News');
						//Debug($search_form);
						//die();
					}
					//die();
			}else{

					$keyword = "siamdara";

					$get_data = $this->input->get();

					//category_id
					if(isset($get_data['category_id'])){
							$category_id = $get_data['category_id'];
							$search_form['category_id'] = $category_id;
							$news_list = $this->news_model->get_news(null, null, $language['lang'], $search_form);
							$category_list = $this->category_model->getSelectCat($category_id, 'category_id', 'News');
							$listspage = selectList( $opt, 'list_page', 'class="form-control"', 'value', 'text', $list_page);
							$subcategory_id = 0;
					}else{

							//echo "get_news( 'order_by', 'Asc', $start_page, $list_page)";
							//$news_list = $this->news_model->get_news(null, null, $language['lang'], null, 'order_by', 'Asc', $start_page, $list_page);
							$news_list = $this->news_model->get_news(null, null, $language['lang'], null);

							$listspage = selectList( $opt, 'list_page', 'class="form-control"', 'value', 'text', $list_page);
							$category_list = $this->category_model->getSelectCat(0, 'category_id', 'News');
							$subcategory_id = 0;
							$category_id = 0;
					}
			}

			if(!isset($subcategory_id)) $subcategory_id = 0;

			$totalnews = $this->news_model->countnews($category_list, $subcategory_id);
			$curpage = base_url('news?view=list');

			//$slip_page = ceil($maxnews/$list_page);
			//Debug($news_list);
			//die();

			$notification_birthday = $this->api_model->notification_birthday();

			$data = array(
					"ListSelect" => $ListSelect,
					"news_list" => $news_list,
					//"GenPage" => GenPage($curpage, $p, $list_page, $totalnews),
					"category_list" => $category_list,
					"category_id" => $category_id,
					"subcategory_id" => $subcategory_id,
					"listspage" => $listspage,
					"notification_birthday" => $notification_birthday,
					"breadcrumb" => $breadcrumb,
					"content_view" => 'news/news'
			);
			$msg = $this->input->get();
			if(isset($msg['success'])) $data['success'] = urldecode($msg['success']);

			$this->load->view('template',$data);
	}

	public function search(){
			
			//$this->load->model('category_model');
			$language = $this->lang->language;
			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));

			//$breadcrumb[] = $language['news'];
			if($this->input->server('REQUEST_METHOD') === 'GET'){
					$search_form = $this->input->get();
					if(isset($search_form['kw'])){
						//echo "is keyword";
						$keyw = explode(" ", $search_form['kw']);
						//Debug($keyw);

						$news_list = $this->news_model->get_news(null, $keyw, $language['lang'], null, 'lastupdate_date', 'DESC', 0, 20);
						//Debug($news_list);
						//die();

						if($news_list){
								echo '<table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th>ID</th>
													<th>Pic</th>
													<th>Title</th>
													<th>'.$language['lastupdate'].'</th>
													<th>'.$language['status'].'</th>
													<th>Count view</th>
													<th width="40%">URL</th>
												</tr>
											</thead>
											<tbody>';

								$maxlist = count($news_list);
								for($i=0;$i<$maxlist;$i++){
										//$url = "http://www.siamdara.com/news/".urlencode($news_list[$i]['title']).".html";
										$url = "http://www.siamdara.com/news/".$news_list[$i]['news_id2'].".html";

										$img = base_url('uploads/thumb').'/'.$news_list[$i]['folder'].'/'.$news_list[$i]['file_name'];
										//$img = 'uploads/thumb/'.$news_list[$i]['folder'].'/'.$news_list[$i]['file_name'];

										$tags_img = (file_exists('uploads/thumb/'.$news_list[$i]['folder'].'/'.$news_list[$i]['file_name'])) ? "<img src=".$img." height='50'>" : "";

										$status = ($news_list[$i]['status'] == 1) ? 'checked' : '';
										$iconstatus = '<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-3" type="checkbox" '.$status.' disabled>
														<span class="lbl"></span>
													</label>
												</div>';
										echo "<tr>
										<td>".$news_list[$i]['news_id2']."</td>
										<td>".$tags_img."</td>
										<td>".$news_list[$i]['title']."</td>
										<td>".$news_list[$i]['lastupdate_date']."</td>
										<td>".$iconstatus."</td>
										<td>".$news_list[$i]['countview']."</td>
										<td>".$url."</td></tr>";

								}						
								echo "</tbody></table>";
						}else
								echo "ไม่มีข้อมูล";
					}
					//die();
			}

	}

	public function search_relate(){
			
			//$this->load->model('category_model');
			$language = $this->lang->language;
			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));

			if($this->input->server('REQUEST_METHOD') === 'GET'){

					$search_form = $this->input->get();
					if(isset($search_form['kw'])){

						$newsid = $search_form['newsid'];
						$keyw = explode(" ", $search_form['kw']);
						//Debug($keyw);

						$news_list = $this->news_model->get_news(null, $keyw, $language['lang'], null, 'lastupdate_date', 'DESC', 0, 20);
						//$news_list = $this->news_model->get_news(null, $search_form['kw'], $language['lang']);

						if($news_list){
								echo '<table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th>ID</th>
													<th>Pic</th>
													<th>Title</th>
													<th>'.$language['lastupdate'].'</th>
													<th>'.$language['status'].'</th>
													<th>Count view</th>													
													<th width = "10%">Action</th>
												</tr>
											</thead>
											<tbody>';

								$maxlist = count($news_list);
								for($i=0;$i<$maxlist;$i++){
										//$url = "http://www.siamdara.com/news/".urlencode($news_list[$i]['title']).".html";
										//$url = "http://www.siamdara.com/news/".$news_list[$i]['news_id2'].".html";

										$img = base_url('uploads/thumb').'/'.$news_list[$i]['folder'].'/'.$news_list[$i]['file_name'];
										//$img = 'uploads/thumb/'.$news_list[$i]['folder'].'/'.$news_list[$i]['file_name'];

										$tags_img = (file_exists('uploads/thumb/'.$news_list[$i]['folder'].'/'.$news_list[$i]['file_name'])) ? "<img src=".$img." height='50'>" : "";
										$status = ($news_list[$i]['status'] == 1) ? 'checked' : '';
										$iconstatus = '<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-3" type="checkbox" '.$status.' disabled>
														<span class="lbl"></span>
													</label>
												</div>';
										//$addurl = 'javascript:void(0);';
										$addurl = base_url('news/add_relate_news').'?news_id='.$news_list[$i]['news_id2'].'&ref_id='.$newsid;
										$iconadd = '<a href="'.$addurl.'" data-value="'.$news_list[$i]['news_id2'].'" class="add_relate"><i class="ace-icon glyphicon glyphicon-plus">Add relate</i></a>';
										echo "<tr>
										<td>".$news_list[$i]['news_id2']."</td>
										<td>".$tags_img."</td>
										<td>".$news_list[$i]['title']."</td>
										<td>".$news_list[$i]['lastupdate_date']."</td>
										<td>".$iconstatus."</td>
										<td>".$news_list[$i]['countview']."</td>
										<td>".$iconadd."</td></tr>";
								}						
								echo "</tbody></table>";
						}else
								echo "ไม่มีข้อมูล";
					}
					//die();
			}

	}

	public function add_relate_news(){
			
			$data_input = $this->input->get();
			$ref_id = $data_input['ref_id'];
			$news_id = $data_input['news_id'];
			//Debug($data_input);
			$this->news_model->add_relate_news($ref_id, $news_id);
			echo '<script type="text/javascript">
			<!--
				window.location="'.base_url("news/edit").'/'.$ref_id.'";
			//-->
			</script>';
	}

	public function add(){
			
			$this->load->model('category_model');
			$this->load->model('dara_model');
			$this->load->model('tags_model');
			$this->load->model('credit_model');
			$this->load->helper('ckeditor');

			$Path_CKeditor = 'plugins/ckeditor-integrated/ckeditor';
			$Path_CKfinder = 'plugins/ckeditor-integrated/ckfinder';

			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('news').'">'.$language['news'].'</a>';
			$breadcrumb[] = $language['add'];

			//$category = $this->category_model->get_category();
			$dara_list = $this->dara_model->get_dara_profile();
			$category_list = $this->category_model->getSelectCat(0, 'category_id', 'News');
			$tags_list = $this->tags_model->getSelect();
			$credit_list = $this->credit_model->get_data();
			
			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					//"category" => $category,
					"dara_list" => $dara_list,
					"category_list" => $category_list,
					"tags_list" => $tags_list,
					"credit_list" => $credit_list,
					"content_view" => 'news/news_add',
					"breadcrumb" => $breadcrumb
			);

			//Ckeditor's configuration
			$data['ckeditor'] = array(
				'id'     =>     'editorFull',
				'path'    =>    $Path_CKeditor,
				'config' => array(
					'toolbar'     =>     "Full",
					//'width'     =>     "800px",
					//'height'     =>     '200px',
					'filebrowserBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html',
					'filebrowserImageBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Images',
					'filebrowserFlashBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Flash',
					'filebrowserUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Files',
					'filebrowserImageUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Images',
					'filebrowserFlashUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Flash',
				),
			);
			$data['description_th'] = array(
				'id'     =>     'description_th',
				'path'    =>    $Path_CKeditor,
			);
			$data['detail_en'] = array(
				'id'     =>     'detail_en',
				'path'    =>    $Path_CKeditor,

			);
			$data['detail_th'] = array(
				'id'     =>     'detail_th',
				'path'    =>    $Path_CKeditor,
			);

			$this->load->view('template',$data);

	}

	public function edit($id = 0){
			 
			 $this->load->library('user_agent');

			 $this->load->model('category_model');			 
			 $this->load->model('subcategory_model');
			 $this->load->model('dara_model');
			 $this->load->model('tags_model');
			  $this->load->model('credit_model');
			 //$this->load->model('picture_model');
			 $this->load->helper('ckeditor');

			$tag_id = array();
			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('news').'">'.$language['news'].'</a>';
			$breadcrumb[] = $language['edit'];
			
			//$category = $this->category_model->get_category();
			$news_list =  $this->news_model->get_news($id);
			//debug($news_list);
			//die();
			
			$category_list = $this->category_model->getSelectCat($news_list[0]['category_id'], 'category_id', 'News');
			
			if($news_list[0]['subcategory_id'] > 0){
				$subcategory_list = $this->subcategory_model->getSelectSubcat($news_list[0]['category_id'], $news_list[0]['subcategory_id']);
			}else
				$subcategory_list = '<select class="form-control" id="subcategory_id" name="subcategory_id"></select>';
			
			$sel_tags = $this->tags_model->get_tag_pair($id);
			//Debug($sel_tags);
			if($sel_tags)
				for($i=0;$i<count($sel_tags);$i++){
					@$tag_id[$i]->value = $sel_tags[$i]->tag_id;
				}

			//Debug($tag_id);
			//die();

			$tags_list = $this->tags_model->getSelect($tag_id);
			$dara_list = $this->dara_model->get_dara_profile();
			//$picture_list = $this->picture_model->get_picture_by_ref_id($id);
			$relate_list = $this->news_model->get_relate($id);
			$credit_list = $this->credit_model->get_data();
			
			$datalog = array(
					"ref_type" => 1,
					"ref_id" => $id
			);

			$view_log = $this->admin_log_activity_model->view_log(0, $datalog);

			$displaylogs = $this->admin_log_activity_model->DisplayLogs($id);
			//Debug($view_log);
			//die();

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"news_list" => $this->news_model->get_news($id),
					"category_list" => $category_list,
					"highlight" => $this->news_model->get_highlight($id),
					"subcategory_list" => $subcategory_list,
					"dara_list" => $dara_list,
					"tags_list" => $tags_list,
					"credit_list" => $credit_list,
					//"picture_list" => $picture_list,
					"relate_list" => $relate_list,
					"view_log" => $view_log,
					"displaylogs" => $displaylogs,
					"content_view" => 'news/news_edit',
					"breadcrumb" => $breadcrumb
			);

			//Ckeditor's configuration
			$data['ckeditor'] = array(
				'id'     =>     'editorFull',
				'path'    =>    'theme/assets/ckeditor-upload',
				'config' => array(
					'toolbar'     =>     "Full",
					'width'     =>     "800px",
					'height'     =>     '200px',
				),
			);

			$data['ckeditor2'] = array(
				'id'     =>     'description_th',
				'path'    =>    'theme/assets/ckeditor-upload',
				'config' => array(
					'toolbar'     =>     "Full",
					'width'     =>     "800px",
					'height'     =>     '200px',
				),
			);

			$data['ckeditor3'] = array(
				'id'     =>     'detail_en',
				'path'    =>    'theme/assets/ckeditor-upload',
				'config' => array(
					'toolbar'     =>     "Full",
					'width'     =>     "800px",
					'height'     =>     '200px',
				),
			);

			$data['ckeditor4'] = array(
				'id'     =>     'detail_th',
				'path'    =>    'theme/assets/ckeditor-upload',
				'config' => array(
					'toolbar'     =>     "Full",
					'width'     =>     "800px",
					'height'     =>     '200px',
				),
			);

			if ($this->agent->is_mobile())
					$data['mobile'] = $this->agent->mobile();

			$this->load->view('template',$data);
	
	}

	public function picture($id = 0){
			
			if($id == 0){
				redirect('news');
				die();
			}

			 $this->load->model('api_model');
			 $this->load->model('picture_model');

			$tag_id = array();
			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('news').'">'.$language['news'].'</a>';
			$breadcrumb[] = $language['picture'];

			$news_list =  $this->news_model->get_news($id);
			$picture_list = $this->api_model->get_picture($id);
			
			//$picture_list = json_decode("http://elvis.siamsport.co.th/services/search?q=autosalon%20AND%20extension:jpg&start=0&num=10&sort=name&metadataToReturn=all&format=json&facet.assetDomain.selection=image");

			//$picture_list = LoadJSON("search?q=autosalon%20AND%20extension:jpg&start=0&num=10&sort=name&metadataToReturn=all&format=json&facet.assetDomain.selection=image", "http://elvis.siamsport.co.th/services");

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"news_list" => $news_list,
					"picture_list" => $picture_list,
					"content_view" => 'news/news_pic',
					"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template',$data);
	}

	public function picture_order($id = 0){
			
			$this->load->library('user_agent');
						
			 $this->load->model('api_model');
			 $this->load->model('picture_model');

			$tag_id = array();
			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('news').'">'.$language['news'].'</a>';
			$breadcrumb[] ='<a href="'.base_url('news/picture/'.$this->uri->segment(3)).'">'.$language['picture'].'</a>';
			$breadcrumb[] = $language['order'];

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					//Debug($search_form);
					$this->set_order_picture2($this->uri->segment(3), $search_form);

			}

			$picture_list = $this->api_model->get_picture($id);

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"news_list" => $this->news_model->get_news_by_id($id),
					//"category_list" => $category_list,
					//"subcategory_list" => $subcategory_list,

					"picture_list" => $picture_list,
					"content_view" => 'news/news_pic_order',
					"breadcrumb" => $breadcrumb
			);
			$this->load->view('template',$data);
	}

	public function picture_edit($id = 0, $idtype = 0){
			
			$this->picture_edit_thumb($id, $idtype);

			 /*$this->load->model('api_model');
			 $this->load->model('picture_model');

			 $orientation = 1;
			 $ref_id = $this->input->get();
			if($ref_id)
				foreach($ref_id as $key => $val){
						//echo "<br>".$news_id." : ".$val;
						if($key == "news_id") $news_id = $val;
						if($key == "Orientation") $orientation = $val;
				}
			$tag_id = array();
			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('news').'">'.$language['news'].'</a>';
			$breadcrumb[] = '<a href="'.base_url('news/picture').'/'.$ref_id['news_id'].'">'.$language['picture'].'</a>';
			$breadcrumb[] = $language['edit'];

			$picture_list = $this->api_model->get_picture($news_id, $id);
			$news_item = $this->news_model->get_news($news_id);

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"news_id" => $news_id,
					"news_item" => $news_item[0],
					"orientation" => $orientation,
					"picture_list" => $picture_list,
					"content_view" => 'news/news_pic_edit',
					"breadcrumb" => $breadcrumb,
					"error" => array()
			);
			$this->load->view('template',$data);*/
	}

	public function picture_edit_thumb($id = 0, $idtype = 0){
						
			 $this->load->model('api_model');
			 $this->load->model('picture_model');

			 $orientation = 1;
			 $ref_id = $this->input->get();
			if($ref_id)
				foreach($ref_id as $key => $val){
						//echo "<br>".$news_id." : ".$val;
						if($key == "news_id") $news_id = $val;
						if($key == "Orientation") $orientation = $val;
				}
			$tag_id = array();
			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('news').'">'.$language['news'].'</a>';
			$breadcrumb[] = '<a href="'.base_url('news/picture').'/'.$ref_id['news_id'].'">'.$language['picture'].'</a>';
			$breadcrumb[] = $language['edit'];

			$picture_list = $this->api_model->get_picture($news_id, $id);
			$news_item = $this->news_model->get_news($news_id);

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"news_id" => $news_id,
					"news_item" => $news_item[0],
					"orientation" => $orientation,
					"picture_list" => $picture_list,
					"content_view" => 'news/news_pic_edit_thumb',
					"breadcrumb" => $breadcrumb
			);
			$this->load->view('template',$data);
	}

	public function rotate(){

			$this->load->model('picture_model');
			//$gallery_id = $this->input->get('gallery_id');
			$rotate = $this->input->get('rotate');
			$folder = $this->input->get('folder');
			$file = $this->input->get('file');

			$rotate = ($rotate == "l") ? -90 : 90;  //หมุนทวนเข็มนาฬิกา และ ตามเข็มนาฬิกา
			$sourcefile = './uploads/news/'.$folder.'/'.$file;
			$sourcefile_tmp = './uploads/tmp/'.$folder.'/'.$file;

			//Debug($sourcefile);
			//Debug($rotate);
			$this->picture_model->rotate_img($sourcefile, $sourcefile, $rotate, 1);
			$this->picture_model->rotate_img($sourcefile_tmp, $sourcefile_tmp, $rotate, 0);
	}

	public function picture_watermark($id = 0){

			$this->load->model('api_model');
			$this->load->model('picture_model');
			$this->load->helper('img');

			$ref_id = $this->input->post();
			if($ref_id)
				foreach($ref_id as $key => $val){
						if($key == "id") $id = $val;
						if($key == "news_id") $news_id = $val;
						if($key == "folder") $folder = $val;
						if($key == "file") $file = $val;
						if($key == "watermark") $watermark = $val;
						if($key == "caption") $caption = StripTxt($val);
				}
			//Debug($ref_id);
			//die();
			switch($watermark){
						case "center" : $picture_list = $this->picture_model->watermark($file, $folder); break;
						case "horizontal" : $picture_list = $this->picture_model->watermark($file, $folder, 'news', 1); break;
						case "vertical" : $picture_list = $this->picture_model->watermark($file, $folder, 'news', 2); break;
						default : $picture_list = $this->picture_model->watermark($file, $folder, 'news', 3); break; //Logo ขนาดใหญ่
			}

			$data = array(
				"caption" => $caption
			);
			$this->picture_model->store($id, $data);

			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $news_id,
					"ref_type" => 1,
					"ref_title" => "Set picture news picture id: ".$id." ".$caption,
					"action" => 2
			);			
			$this->admin_log_activity_model->store($log_activity);

			 //Debug($picture_list);
			 //die();
			redirect('news/picture_edit_thumb/'.$id.'?news_id='.$news_id);
			//http://localhost/siamdara_admin/news/picture_edit_thumb/121?news_id=43
			die();	
	}


	public function remove_pic(){
			//Debug($this->input->post());
			$src = base64_decode($this->input->post('src'));
			if(file_exists($src)){
				unlink($src);
				echo "Yes";
				//echo "Remove Picture success.";
			}else echo "No";
	}

	public function picture_del($pic_id = 0){

			$this->load->model('picture_model');

			$ref_id = $this->input->get('ref_id');

			if($ref_id > 0 && $pic_id > 0){

					$picture_list = $this->api_model->get_picture($ref_id, $pic_id);
					$this->picture_model->delete_picture_admin($pic_id);
	
			}

			$news_list =  $this->news_model->get_news_by_id($ref_id);
			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $ref_id,
								"ref_type" => 1,
								"ref_title" => "[picturn $pic_id]".$news_list[0]['title'],
								"action" => $action
			);			
			$this->admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('news/picture/'.$ref_id);

	}

	function upload_pic(){

			$this->load->model('picture_model');
			$this->load->library('upload');
			$this->load->library('image_lib');

			$language = $this->lang->language;
			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));

			$picture_obj = array();

			$files = $_FILES;
			$now_date = date('Y-m-d H:i:s');
			//$folder = date('Ymd');

			$data_input = $this->input->post();

			if($data_input['create_date'] != "") $folder = $data_input['create_date'];

			$picture_list = $this->api_model->get_picture($data_input['news_id']);

			//Debug($data_input);
			//echo "<br>".count($picture_list);
			//die();

			//$config['path_thumb'] = './uploads/news/thumb/';
			//if(!is_dir($config['path_thumb'])) mkdir($config['path_thumb'], 0777);

			$cpt = count($_FILES['picture_news']['name']);
			for($i=0; $i<$cpt; $i++){

				$_FILES['userfile']['name']= $files['picture_news']['name'][$i];
				$_FILES['userfile']['type']= $files['picture_news']['type'][$i];
				$_FILES['userfile']['tmp_name']= $files['picture_news']['tmp_name'][$i];
				$_FILES['userfile']['error']= $files['picture_news']['error'][$i];
				$_FILES['userfile']['size']= $files['picture_news']['size'][$i];    
				//Debug($_FILES['userfile']);

				if($_FILES['userfile']['error'] == 0){

						//Upload File
						$this->upload->initialize($this->set_upload_options($folder));
						$this->upload->do_upload();

						//Resize Image
						$this->image_lib->clear();
						$this->image_lib->initialize($this->set_resize_options($this->upload->client_name, $folder));
						$this->image_lib->resize();

						//Watermark
						//$this->image_lib->initialize($this->set_watermark_options($this->upload->client_name));
						//$this->image_lib->watermark();

						//$picture_obj['ref_id'] = $this->uri->segment(3);
						$picture_obj['ref_id'] = $data_input['news_id'];
						$picture_obj['ref_type'] = 1;
						$picture_obj['file_name'] = $this->upload->client_name;
						//$picture_obj['title'] = StripTxt($data_input['title']);						
						$picture_obj['caption'] = StripTxt($data_input['caption']);
						$picture_obj['folder'] = $folder;
						$picture_obj['create_date'] = $now_date;
						$picture_obj['create_by'] = $this->session->userdata('admin_id');
						$picture_obj['status'] = 1;

						if((count($picture_list) == 0) && ($i == 0)) $picture_obj['default'] = 1;

						$this->picture_model->store(0, $picture_obj);
				}
				//Debug($this->upload);

			}

			$news_list =  $this->news_model->get_news_by_id($data_input['news_id']);
			//**************Log activity
			$action = 2;
			$log_activity = array(
									"admin_id" => $this->session->userdata('admin_id'),
									"ref_id" => $data_input['news_id'],
									"ref_type" => 1,
									"ref_title" => "[Upload Picturn $cpt]".$news_list[0]['title'],
									"action" => $action
			);			
			$this->admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect(base_url('news/picture/'.$data_input['news_id']));
	}

	private function set_watermark_options($client_name, $text = "Siamdara"){   

		$config = array();
		$folder = date('Ymd');
		
		$config['source_image'] = './uploads/news/'.$folder.'/'.$client_name;
		$config['new_image'] =  './uploads/news/'.$folder.'/sd_'.$client_name;
		$config['wm_text'] = $text ;
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = './system/fonts/texb.ttf';
		$config['wm_font_size'] = '16';
		$config['wm_font_color'] = 'ffffff';
		$config['wm_vrt_alignment'] = 'middle';
		$config['wm_hor_alignment'] = 'center';
		$config['wm_padding'] = '20';
		$config['wm_opacity'] = '50';
		$config['wm_shadow_distance'] = '3';

		return $config;
	}

	private function set_resize_options($client_name, $folder = '', $width = 300, $height = 169){   

		$config = array();
		if($folder == '') $folder = date('Ymd');
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = './uploads/tmp/'.$folder.'/'.$client_name;
		$config['new_image'] = './uploads/news/'.$folder.'/'.$client_name;

		$config['create_thumb'] = FALSE;	//สร้าง Thumb โดย CI
		$config['maintain_ratio'] = TRUE;
		//$config['width']     = $width;
		//$config['height']   = $height;

		//****Copy Original File to TMP
		$upload_path = './uploads/tmp/'.$folder;
		$tmp = $upload_path.'/'.$client_name;

		$src = fopen($config['new_image'], 'r');
		$dest = fopen($tmp, 'w');

		stream_copy_to_stream($src, $dest);

		return $config;
	}

	private function set_upload_options($folder = ''){  

		$config = array();
		if($folder == '') $folder = date('Ymd');
		
		$config['upload_path'] = './uploads/news/';
		if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

		$config['upload_path'] = './uploads/news/'.$folder;
		if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

		$upload_path = './uploads/tmp/';
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		$upload_path = './uploads/tmp/'.$folder;
		if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size']      = '0';
		$config['overwrite']     = FALSE;

		return $config;
	}
	
	public function remove_img($id = 0){
			
			$src = $this->input->post('name');

			if(file_exists('uploads/dara/'.$src)) unlink('uploads/dara/'.$src);
			$obj_data['picture_news'] = '';
			if($this->news_model->store($dara_profile_id, $obj_data))
				echo 'Yes';
			else
				echo 'No';

			/*$src = $this->input->post('name');
			 if ($this->input->server('REQUEST_METHOD') === 'POST'){
					if(file_exists('uploads/news/'.$src)) unlink('uploads/news/'.$src);
					if(file_exists('uploads/thumb/'.$src)) unlink('uploads/thumb/'.$src);
					$obj_data['picture_news'] = '';
					if($this->news_model->store($id, $obj_data))
						echo 'Yes';
					else
						echo 'No';
			 }*/
	}

	public function order($id1, $id2){
			/*if($id == 0){
				$data = array(
						"error" => 'id error'
				);
				return false;
			}else{

					$obj_status = $this->news_model->get_status($id);
					$cur_status = $obj_status[0]['status'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;
					$obj_data['status'] = $cur_status;
					if($this->news_model->store2($id, $obj_data)) echo $cur_status;
						//echo "update succedd.";
					$this->gen_json($id);
			}*/
	}

	public function status($id = 0){
			if($id == 0){
					$data = array(
							"error" => 'id error'
					);
					return false;
			}else{

					$obj_status = $this->news_model->get_status($id);
					$cur_status = $obj_status[0]['status'];
					$title = $obj_status[0]['title'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;

					$obj_data['status'] = $cur_status;
					if($this->news_model->store2($id, $obj_data)) echo $cur_status;
					//$this->gen_json($id);
			}

			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 1,
								"ref_title" => "[Status]".$title,
								"action" => 2
			);			
			$this->admin_log_activity_model->store($log_activity);
			//**************Log activity
	}


	public function approve($id = 0){
			if($id == 0){
					$data = array(
							"error" => 'id error'
					);
					return false;
			}else{

					$obj_status = $this->news_model->get_status($id);
					$cur_status = $obj_status[0]['approve'];
					$title = $obj_status[0]['title'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;

					$obj_data['approve'] = $cur_status;
					$obj_data['approve_date'] = date('Y-m-d H:i:s');
					$obj_data['approve_by'] = $this->session->userdata('admin_id');

					if($this->news_model->store2($id, $obj_data)) echo $cur_status;
					//echo "update succedd.";
					
					//$this->gen_json($id);

			}
			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 1,
								"ref_title" => "[Approve]".$title,
								"action" => 2
			);			
			$this->admin_log_activity_model->store($log_activity);
			//**************Log activity
	}

	public function save(){

			$this->load->model('tags_model');
			$this->load->model('picture_model');
			$this->load->model('category_model');
			
			$language = $this->lang->language;
			$ListSelect = $this->api_model->user_menu($this->session->userdata('admin_type'));
			
			$tags_all = $this->tags_model->getall_tag_pair(5); //tags ของดารา
			$breadcrumb[] = $language['dara'];

			$new_data = $tag_id = $tag_pair = $picture_obj = $tag_dara = $log_activity = array();
			$data_input = $this->input->post();
			$upload_status = "";
			$new_id = 0;

			$now_date = date('Y-m-d H:i:s');
			$folder = date('Ymd');

			/*$this->load->library('form_validation');
			// field name, error message, validation rules
			$this->form_validation->set_rules('title', $language['title'], 'trim|required');
			$this->form_validation->set_rules('category_id	', $language['category_id'], 'trim|required');

			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

			if($this->form_validation->run() == FALSE){
					Debug($this->form_validation->set_error_delimiters());
			}else{
					echo "<hr>Save";
					//Debug($this->input->post());
					$this->news_model->store($dara_profile_id, $this->input->post());
			}*/

				$this->load->library('upload', $this->set_upload_options());
				$this->upload->initialize($this->set_upload_options());
				//Debug($config);
				//die();

				if ( ! $this->upload->do_upload('picture_news')){
					$error = array('error' => $this->upload->display_errors());
					$upload_status = $error;
				}else{
					/*$data = array(
							'admin_menu' => $this->menufactory->getMenu(),
							'upload_data' => $this->upload->data(),
							'upload_status' => 'Success',
							'error' => array()
					);*/
					$upload_status = "Success";
				}

				//Debug($data_input);
				//die();
			
				if(isset($data_input['relate_id'])){
						if(count($data_input['relate_id']) < 5){

								$relate_id = $data_input['relate_id'];
								$orderid = $data_input['orderid'];
								$i = 1; 

								foreach($relate_id as $arr => $val){
										$this->news_model->update_order_relate($val, $data_input['news_id'], $orderid[$arr]);
										$i++;
								}

								/**************Log activity
								$log_activity = array(
													"admin_id" => $this->session->userdata('admin_id'),
													"ref_id" => $data_input['news_id'],
													"ref_type" => 1,
													"ref_title" => $data_input['title_th']." [Order By]",
													"action" => 2
								);			
								$this->admin_log_activity_model->store($log_activity);*/
								//**************Log activity

								//$success =  'Save Order complete.';
								//redirect(base_url('news/edit').'/'.$data_input['news_id']);
								//die();
						}
				}

			//Debug($relate_id);
			//Debug($orderid);

			//Debug($data_input);
			//die();

			//******************* List data input **********************/
			if($data_input)
					foreach($data_input as $key => $val){
							if($key == "news_id_en"){
									
									$new_data['news_id'] = $data_input['news_id_en'];

							}else if($key == "news_id_th"){
									
									$new_data_th['news_id'] = $data_input['news_id_th'];

							}else if($key == "news_id"){
									
									$new_data['news_id2'] = $data_input['news_id'];
									$new_data_th['news_id2'] = $data_input['news_id'];

							}else if($key != "editorCurrent" && $key != "tag_id" && $key != "editorCurrent2" && $key != "editorh3" && $key != "editorh4" && $key != "highlight" && $key != "orderid" && $key != "'relate_id"){
									
									if($key == "title_en" || $key == "description_en" || $key == "detail_en"){

											if($key == "title_en"){  
												$new_data['title'] = StripTxt($data_input['title_en']); 
												$new_data['lang'] = "en"; 
											}
											if($key == "description_en")  $new_data['description'] = $data_input['description_en'];
											if($key == "detail_en")  $new_data['detail'] = $data_input['detail_en'];

									}else if($key == "title_th" || $key == "description_th" || $key == "detail_th"){

											if($key == "title_th"){
												$new_data_th['title'] = StripTxt($data_input['title_th']);
												$new_data_th['lang'] = "th"; 
											}
											if($key == "description_th")  $new_data_th['description'] = $data_input['description_th'];
											if($key == "detail_th")  $new_data_th['detail'] = $data_input['detail_th'];

									}else{
											$new_data[$key] = $val;
											$new_data_th[$key] = $val;
									}
							}

							if($key == "tag_id"){
									foreach($val as $key2 => $val2){			
											$tag_id[] = $val2;
									}
							}

					}
			//Debug($data_input);
			//Debug($new_data);
			//die();
			/*$data_highlight = array(
					"news_id" => $data_input['news_id'],
					"ref_id" => 1,
					"order" => 0
			);*/

			//if(isset($data_input['highlight'])) Debug($data_input['highlight']);
			//$this->news_model->set_highlight($data_input['news_id'], $data_highlight);
			//die();

			if(!isset($new_data['dara_id'])){
				$new_data['dara_id'] = 0;				
			}
			
			//if($this->upload->client_name){
					//$new_data['picture_news'] = $this->upload->client_name;
			//}
			
			//Set Date Display
			if(isset($data_input['start_date'])){
				//list($date, $time, $time2) = explode(" ", $data_input['start_date']);
				//if($time2 == "PM") $time = $time +12;
				$new_data['start_date'] = DateTimeDB($data_input['start_date']);
				$new_data_th['start_date'] = $new_data['start_date'];
			}

			if(isset($data_input['expire_date'])){
				//list($date, $time, $time2) = explode(" ", $data_input['expire_date']);
				//if($time2 == "PM") $time = $time +12;
				$new_data['expire_date'] = DateTimeDB($data_input['expire_date']);
				$new_data_th['expire_date'] = $new_data['expire_date'];
			}

			if(isset($data_input['pin_start_date'])){
				//list($date, $time, $time2) = explode(" ", $data_input['pin_start_date']);
				//if($time2 == "PM") $time = $time +12;
				$new_data['pin_start_date'] = DateTimeDB($data_input['pin_start_date']);
				$new_data_th['pin_start_date'] = $new_data['pin_start_date'];
			}

			if(isset($data_input['pin_expire_date'])){
				//list($date, $time, $time2) = explode(" ", $data_input['pin_expire_date']);
				//if($time2 == "PM") $time = $time +12;
				$new_data['pin_expire_date'] = DateTimeDB($data_input['pin_expire_date']);
				$new_data_th['pin_expire_date'] = $new_data['pin_expire_date'];
			}

			//Debug($data_input);
			//Debug($new_data);
			//die();
			//**************************************** Relate News ***************************************
			if($new_data['news_id2'] > 0){

					$number_of_relate = 5;
					$tag_relate = $this->news_model->get_relate($data_input['news_id']);
					//Debug($tag_relate);

					if(count($tag_relate)<$number_of_relate){

							if($new_data['dara_id'] > 0) 
								$tag_relate = $this->news_model->gen_relate($new_data['dara_id'], $new_data['tag_id'], $new_data['news_id']);
							else 
								$tag_relate = $this->news_model->gen_relate(null, $new_data['tag_id'], $new_data['news_id']);

							//Debug($tag_relate);
							$where_newsid = array();
							if($tag_relate){

								$max5 = (count($tag_relate) > $number_of_relate) ? $number_of_relate : count($tag_relate);
								for($t=0;$t<$max5;$t++){

										$number = $t+1;
										$tag_relate[$t]->news_id;
										//echo "<hr>".$number." ".$tag_relate[$t]->news_id."<hr>";
										if($tag_relate[$t]->news_id != $new_data['news_id']){
											$where_newsid[$t]['news_id'] = $tag_relate[$t]->news_id;
											$where_newsid[$t]['ref_id'] = $new_data['news_id'];
											$where_newsid[$t]['order'] = $number;
										}

								}
							}
							if(count($where_newsid) > 0) $this->news_model->save_relate($new_data['news_id'], $where_newsid);
					}
					unset($new_data['relate_id']);
					unset($new_data_th['relate_id']);

					unset($new_data['orderid']);
					unset($new_data_th['orderid']);
			}
			//Debug($where_newsid);
			//die();

			//**************************************** ใส่ Tags ใน Content editor ***************************************
			if($new_data['news_id2'] == 0){
					//Debug($tags_all);
					if($tags_all){
							$number_tag = count($tags_all);
							for($i=0;$i<$number_tag;$i++){
									$textnormal = $tags_all[$i]->tag_text;
									$text_replace = "@[".$tags_all[$i]->tag_text."]";

									$new_data['detail'] = str_replace($textnormal, $text_replace, $new_data['detail']);
									$new_data['description'] = str_replace($textnormal, $text_replace, $new_data['description']);
									$new_data_th['detail'] = str_replace($textnormal, $text_replace, $new_data_th['detail']);
									$new_data_th['description'] = str_replace($textnormal, $text_replace, $new_data_th['description']);
							}
					}
			}
			//die();			
			//*********************************************************

			//Debug($new_data);
			unset($new_data['tag_id']);
			unset($new_data_th['tag_id']);

			if(isset($new_data['news_id'])){
			//if($new_data['news_id'] > 0){
						
						//Debug($new_data);

						$new_data['lastupdate_by'] = $this->session->userdata('admin_id');
						$new_data_th['lastupdate_by'] = $this->session->userdata('admin_id');

						$new_data['lastupdate_date'] = $now_date;
						$new_data_th['lastupdate_date'] = $now_date;
						
						$new_id = $new_data['news_id'];
						$action = 2;

						//Debug($new_data);
						$this->news_model->store($new_data['news_id'], $new_data);
						$this->news_model->store($new_data_th['news_id'], $new_data_th);

						/*$data = array(
								"admin_menu" => $this->menufactory->getMenu(),
								"ListSelect" => $ListSelect,
								"news_list" => $this->news_model->get_news(null, null, $language['lang']),
								"content_view" => 'news/news',
								"breadcrumb" => $breadcrumb,
								"success" =>  'Save News complete.'
						);*/

			}else{
				
						$new_data['create_date'] = $now_date;
						$new_data['create_by'] = $this->session->userdata('admin_id');
						$new_data['order_by'] = 1;

						$new_data_th['create_date'] = $now_date;
						$new_data_th['create_by'] = $this->session->userdata('admin_id');
						$new_data_th['order_by'] = 1;
						$action = 1;

						//Debug($data_input['tag_id']);
						//Debug($new_data);
						//die();

						$max_id = $this->news_model->get_max_id();
						//Debug($max_id);
						$new_id = $max_id[0]['max_id'] + 1;

						//echo "new_id = $new_id";
						//die();
						
						$new_data['news_id'] = $new_id;
						$new_data['news_id2'] = $new_id;
						$new_data_th['news_id2'] = $new_id;

						//Debug($new_data);
						//Debug($new_data_th);
						//die();

						//Add 1 all record
						$this->news_model->update_orderadd($new_data['category_id']);

						$this->news_model->store(0, $new_data);
						$this->news_model->store(0, $new_data_th);

						/*$data = array(
								"admin_menu" => $this->menufactory->getMenu(),
								"ListSelect" => $ListSelect,
								"news_list" => $this->news_model->get_news(null, null, $language['lang']),
								"content_view" => 'news/news',
								"breadcrumb" => $breadcrumb,
								"success" =>  'Save News complete.'
						);*/
			}
			
			//Insert tags
			//Debug($tag_id);
			if($tag_id){
					for($i=0;$i<count($tag_id);$i++){
								$tag_pair[$i]["tag_id"] = $tag_id[$i];
								$tag_pair[$i]["ref_id"] = $new_id;
								$tag_pair[$i]["ref_type"] = 1;
								$tag_pair[$i]["create_date"] = $now_date;
					}
					//Debug($tag_pair);
					$this->tags_model->store_tag_pair($tag_pair);
			}

			//**************Set Highlight
			if(isset($data_input['highlight'])){

				//Debug($data_input['highlight']);
				$data_highlight = array(
						"news_id" => $new_id,
						"ref_id" => 1,
						"order" => 0
				);							
				$this->news_model->set_highlight($new_id, $data_highlight);
				//echo "new_id = $new_id";
				//die();
			}else{
				$this->news_model->remove_highlight($new_id);
				//echo "new_id = $new_id";
			}

			//**************Log activity
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $new_id,
								"ref_type" => 1,
								"ref_title" => $new_data['title'],
								"action" => $action
			);			
			$this->admin_log_activity_model->store($log_activity);
			//**************Log activity

			if($upload_status == "Success"){

						$picture_obj['ref_id'] = $new_id;
						$picture_obj['ref_type'] = 1;
						$picture_obj['file_name'] = $this->upload->client_name;
						//$picture_obj['title'] = StripTxt($data_input['title']);

						if($language['lang'] == 'en')
							$picture_obj['caption'] = StripTxt($data_input['title_en']);
						else
							$picture_obj['caption'] = StripTxt($data_input['title_th']);

						$picture_obj['folder'] = $folder;
						$picture_obj['create_date'] = $now_date;
						$picture_obj['create_by'] = $this->session->userdata('admin_id');
						$picture_obj['status'] = 1;
						$this->picture_model->store(0, $picture_obj);

			}

			//$data['category_list'] = $this->category_model->getSelectCat();
			//$totalnews = $this->news_model->countnews(0, 0);
			//$curpage = base_url('news?view=list');

			$data = array(
								"admin_menu" => $this->menufactory->getMenu(),
								"ListSelect" => $ListSelect,
								//"GenPage" => GenPage($curpage, 1, $list_page, $totalnews),
								//"news_list" => $this->news_model->get_news(null, null, $language['lang']),
								//"category_list" => $this->category_model->getSelectCat(),
								"content_view" => 'news/news',
								"breadcrumb" => $breadcrumb,
								"success" =>  'Save News complete.'
			);

			$success =  'Save complete.';
			//die();
			//$this->load->view('template',$data);

			//redirect(base_url('news?category_id='.$new_data['category_id'].'&success='.urlencode($success)));
			redirect(base_url('news/edit/'.$new_id.'?success='.urlencode($success)));
	}

	public function set_order_relate(){
					
					$lang = $this->lang->language;
					
					$newsid = $this->input->post('newsid');
					$json = $this->input->post('json');

					$obj = json_decode($json);
					$update_data = array();

					//echo "newsid = $newsid<br>";
					//Debug($obj);
					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj);$i++){
								//echo $num.'.) '.$obj[$i]->id.' : '.$newsid.' ==> '.$num.'<br>';
								unset($update_data);
								$update_data['order'] = $num;
								$this->news_model->set_order_relate($newsid, $obj[$i]->id, $update_data);
								$num++;
						}
					echo $lang['update'].' '.$lang['success'].'.';
	}

	public function set_order_picture(){
					
					$lang = $this->lang->language;
					
					$newsid = $this->input->post('newsid');
					$json = $this->input->post('json');

					$obj = json_decode($json);
					$update_data = array();

					//Debug($this->input->post());
					//echo "newsid = $newsid<br>";
					//Debug($obj);
					//die();

					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj);$i++){
								//echo $num.'.) '.$obj[$i]->id.' : '.$newsid.' ==> '.$num.'<br>';
								unset($update_data);
								$update_data['order'] = $num;
								$this->news_model->set_order_picture($newsid, $obj[$i]->id, $update_data);
								$num++;
						}
					echo $lang['update'].' '.$lang['success'].'.';
	}

	public function set_order_picture2($newsid, $obj){
					
					//$lang = $this->lang->language;
					$update_data = array();
					//Debug($this->input->post());
					//echo "newsid = $newsid<br>";
					//echo "count = ".count($obj['picture_id'])."<br>";
					//Debug($obj);
					$updateorder = $tmp = $do = 0;
					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj['picture_id']);$i++){

								unset($update_data);
								$orderid = $obj['orderid'][$i];
								$picture_id = $obj['picture_id'][$i];
								$update_data['order'] = $orderid;

								if($tmp == 0){
										if(($do == 0) && (($tmp+1) != $orderid)){
											$this->news_model->set_order_picture_to_up($newsid, $picture_id, $orderid, $tmp+1); //--
											$updateorder = $orderid;
											$this->news_model->set_order_picture($newsid, $picture_id, $update_data);
											$do++;

										}else{
											$this->news_model->set_order_picture($newsid, $picture_id, $update_data);
											//$tmp = $orderid;
										}
										//echo "<br>case 0 $picture_id == $orderid<br>";
								}else if(($tmp != $orderid) && (($tmp+1) == $orderid)){
										if($updateorder != $orderid) $this->news_model->set_order_picture($newsid, $picture_id, $update_data);
										//$tmp = $orderid;
										//echo "<br>case 1 $picture_id == $orderid<br>";
								}else{

										//echo "<br>case 2 tmp =$tmp > $orderid<br>";
										//echo "($tmp < $orderid)<br>";

										if($tmp > $orderid){
												$between = $orderid - $tmp;
												//echo "(between : $between)<br>";
												/*echo "(newsid =$newsid, orderid =$orderid)<br>";
												echo "(newsid =$newsid, picture_id = $picture_id, update_data = ".print_r($update_data).")<br>";*/
												if($do == 0){
													$this->news_model->set_order_picture_to_down($newsid, $picture_id, $orderid, $tmp);
													$updateorder = $orderid;
													$this->news_model->set_order_picture($newsid, $picture_id, $update_data);
													$do++;
												}else{
													if($updateorder != $orderid) $this->news_model->set_order_picture($newsid, $picture_id, $update_data);
												}
										}else{
												//echo "($newsid, $orderid, $tmp)<br>";
												if($do == 0){
													$this->news_model->set_order_picture_to_up($newsid, $picture_id, $orderid, $tmp+1);
													$updateorder = $orderid;
													$this->news_model->set_order_picture($newsid, $picture_id, $update_data);
													$do++;
												}else{
													if($updateorder != $orderid) $this->news_model->set_order_picture($newsid, $picture_id, $update_data);
												}
										}
								}
								$tmp++;
								$num++;
						}

				$news = $this->news_model->get_news_by_id($newsid);

				//**************Log activity
				$action = 2;
				$log_activity = array(
									"admin_id" => $this->session->userdata('admin_id'),
									"ref_id" => $newsid,
									"ref_type" => 1,
									"ref_title" => "จัดเรียง ".$news[0]['title'],
									"action" => $action
				);
				$this->admin_log_activity_model->store($log_activity);
				//**************Log activity
	}

	public function gen_json($id){

				//http://daraapi.siamsport.co.th/api/rest.php?method=DetailNews&key=5AckEziE&lang=th&news_id=39&gen_file=1

				$newsid_th = $this->api_model->news('th', $id);
				$newsid_en = $this->api_model->news('en', $id);

				//$newsid_th = JsonContent($newsid_th);
				//$newsid_en = JsonContent($newsid_en);

				//Debug($newsid_th);
				//Debug($newsid_en);

				if(!is_dir('json/www/news')) mkdir('json/www/news', 0777);

				SaveJSON($newsid_th, $id.'_th', 0, 'www/news/');
				SaveJSON($newsid_en, $id.'_en', 0, 'www/news/');
				//echo "Finish";
				//redirect('json/index');
	}

	public function set_default($id = 0){
			echo "Set default... $id";

			//$data_input = $this->input->post();
			$data_input = $this->input->get();

			//Debug($data_input);

			$id = $data_input['id'];
			$ref_id = $data_input['ref_id'];
			//die();
			
			$OBJnews = $this->news_model->get_news($ref_id);
			//Debug($OBJnews);

			$news_id = $OBJnews[0]['news_id2'];
			$title = "Set default picture ".$OBJnews[0]['title'];
			$order_by = $OBJnews[0]['order_by'];

			$this->news_model->set_default($id, $ref_id);

			//**************Log activity
			$action = 2;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $ref_id,
								"ref_type" => 1,
								"ref_title" => $title,
								"action" => $action
			);
			$this->admin_log_activity_model->store($log_activity);
			//**************Log activity

			//redirect('news/picture/'.$this->uri->segment(3));
			redirect('news/picture/'.$ref_id);
			die();
	}	

	public function delete($id, $cat){
			echo "Deleting... $id";
			
			$OBJnews = $this->news_model->get_news($id);
			$title = $OBJnews[0]['title'];
			$order_by = $OBJnews[0]['order_by'];

			$this->news_model->delete_news($id);

			//**************Order New
			$this->news_model->update_orderdel($cat, $order_by);

			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 1,
								"ref_title" => $title,
								"action" => $action
			);			
			$this->admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('news');
			die();
	}

	public function delrelate($id){
			
			//$this->load->model('tags_model');
			echo "Deleting... $id";
			
			$OBJnews = $this->news_model->get_news($id);
			$title = $OBJnews[0]['title'];
			$this->news_model->delete_relate_news($id);
			//$this->tags_model->delete_tag_pair($id, 1);

			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 1,
								"ref_title" => "Delete relate news ".$title,
								"action" => $action
			);			
			$this->admin_log_activity_model->store($log_activity);
			//**************Log activity
			$stop = $this->input->get('stop');
			if($stop == 1) die();
			redirect('news/edit/'.$id);
			die();
	}

	public function DelRelateID(){

			$data_input = $this->input->post();
			$newid = $data_input['newid'];
			$id = $data_input['id'];
			$title = $data_input['name'];
			//echo "Deleting... $id";
			$this->news_model->delete_relate_news(0, $id);

			$action = 3;
			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $newid,
					"ref_type" => 1,
					"ref_title" => "Delete relate news id: ".$id." ".$title,
					"action" => $action
			);			
			$this->admin_log_activity_model->store($log_activity);

			die();
	}

	public function saveorder(){
			
			$language = $this->lang->language['lang'];

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					//Debug($search_form);
					//die();
					$data['order'] = $search_form['orderid'];
					$newsid = $search_form['news_id'];

					$getobj = $this->news_model->get_status($newsid, $language);
					//Debug($getobj);

					if(isset($search_form['relate_id'])){
							//if(count($search_form['relate_id']) < 5){

									$relate_id = $search_form['relate_id'];
									$orderid = $search_form['orderid'];
									$i = 1; 

									//Debug($relate_id);

									foreach($relate_id as $arr => $val){
											$this->news_model->update_order_relate($search_form['relate_id'][$arr], $newsid, $search_form['orderid'][$arr]);
											$i++;
									}

									/**************Log activity *********/
									$log_activity = array(
														"admin_id" => $this->session->userdata('admin_id'),
														"ref_id" => $newsid,
														"ref_type" => 1,
														"ref_title" => $getobj[0]['title']." [Order By]",
														"action" => 2
									);			
									$this->admin_log_activity_model->store($log_activity);
									/**************Log activity**********/

									//$success =  'Save Order complete.';
									redirect(base_url('news/edit').'/'.$newsid);
									die();
							//}
					}
			}

	}	
	
	function hotnews_save(){

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$data_form = $this->input->post();
					Debug($data_form);
					die();

			}
	}
	
	function auto_order($cat = 0){
				
				$language = $this->lang->language;
				$cat_view = array();

				if($cat != 0) $cat_view['category_id'] = $cat;
				$news_list = $this->news_model->get_news(null, null, $language['lang'], $cat_view);
				//Debug($news_list);
				if(isset($news_list)){
						for($i=0;$i<count($news_list);$i++){
									$number = $i + 1;
									$idnews = $news_list[$i]['news_id2'];
									$title = $news_list[$i]['title'];
									$order_by = $news_list[$i]['order_by'];
									$data = array(
											"order_by" => $number
									);
									echo "$idnews $title  [$order_by ==> $number]<br>";
									$this->news_model->store2($idnews, $data);
						}
						echo "<hr>UPDATE Order cat = $cat  total = ".count($news_list)." record.";
				}
	}

}
