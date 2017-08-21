<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Article extends CI_Controller {

    public function __construct()    {
        parent::__construct();
		$this->load->library("AdminFactory");
		$this->load->model('Admin_team_model');        
		$this->load->model('Article_model');
		$breadcrumb = array();
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
			$list_page = 100;
			$listspage = '';
			$filter = array();

			//debug($this->session->userdata);

			// Check Level access category 
			if($this->session->userdata('admin_type') > 3){
				$admin_id = $this->session->userdata('admin_id');
				$team_list = $this->Admin_team_model->get_admin_team();
				$admin_info = $this->adminfactory->getAdmin($admin_id);				
				$admin_team_id = $admin_info->_admin_team_id;

				for($i=0;$i<count($team_list);$i++){
						if($team_list[$i]['admin_team_id'] == $admin_team_id)
								$access_cat = json_decode($team_list[$i]['access']);
				}
				//$access_cat = json_decode($team_list[$admin_team_id]['access']);
				//Debug($team_list);

				//filter category from admin level
				//Debug($access_cat);
				if($access_cat)
						foreach($access_cat as $val){
								//echo "category = $val<br>";
								$search_form['category_id'][] = $val;				
						}
			}else
				$access_cat = null;

			//Debug($search_form['category_id']);

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

			if($p > 1){
				$page_start = (($p - 1) * $list_page);
			}else
				$page_start = 0;

			$breadcrumb[] = $language['article'];
			$opt[]	= makeOption(10, 10);
			$opt[]	= makeOption(20, 20);
			$opt[]	= makeOption(50, 50);
			$opt[]	= makeOption(100, 100);

			//USE Filter category
			if(count($access_cat) > 0){
				foreach($access_cat as $val){
						$filter['category_id'][] = intval($val);
				}
			}else
				$filter = null;

			if($this->input->server('REQUEST_METHOD') === 'POST'){
					$search_form = $this->input->post();
					if(isset($search_form['category_id'])){ 
						$category_id = $search_form['category_id'];
						if(isset($search_form['subcategory_id'])){ 
							$subcategory_id = $search_form['subcategory_id']; 
						}else
							$subcategory_id = 0;
					}else{
						unset($search_form['subcategory_id']);
						$category_id = 0;
					}

					if(isset($search_form['list_page'])) $list_page = ($search_form['list_page'] > 0) ? $search_form['list_page'] : $list_page;
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
											$this->Article_model->update_orderid_to_down($category, $order[$i], $tmp);
											//Debug($this->db->last_query());
									}
									//echo "<hr>";

									if($order[$i]%10 == 1){
											//echo "(".$order[$i]."%10 == 1)";
									}else if((($tmp + 1) <> $order[$i]) && ($toup == 0)){
											
											//echo "((($tmp + 1) <> ".$order[$i].") && ($toup == 0))<br>";
											//Update ID ด้านหลัง - 1
											$toup = $tmp + 1;
											$this->Article_model->update_orderid_to_up($category, $order[$i], $toup);
											//Debug($this->db->last_query());
									}

									//Update Cur ID
									$this->Article_model->update_order($category, $selectid[$i], $order[$i]);
									//Debug($this->db->last_query());
									if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
									//echo "<hr>";
							}
							$category_id = $category;
							$subcategory_id = 0;
							//die();
					}

					//Debug($search_form);

					//$keyword = trim($search_form['keyword']);
					if(isset($search_form['keyword'])){

						if($this->session->userdata('admin_type') > 3){
							$search_form = array_merge($search_form, $filter);
							$search_article = $search_form;
						}else{
							$search_article = explode(" " ,$search_form['keyword']);
						}

						//$search_article['keyword'] = $search_form['keyword'];
						//$search_article = explode(" " ,$search_form['keyword']);

						//Debug($search_article);
						$article_list = $this->Article_model->get_article(null, $search_article);
						$category_list = $this->Category_model->getSelectCat(0, 'category_id', $access_cat);

					}else if(isset($search_form['selectid'])){

						$keyword = "Tmon";
						$article_list = $this->Article_model->get_article(null, null, $language['lang']);

						if(isset($search_form['category_id'])){
								
							$cat_view['category_id'] = $search_form['category_id'];
							//Debug($cat_view);
							$article_list = $this->Article_model->get_article(null, null,  $cat_view);
							//Debug($this->db->last_query());
							$category_list = $this->Category_model->getSelectCat($cat_view['category_id'], 'category_id', $access_cat);
							//Debug($this->db->last_query());

						}else
							$category_list = $this->Category_model->getSelectCat(0, 'category_id', $access_cat);

					}else{	
						//echo "no keyword";
						//$keyword = "Tmon";
						//Debug($search_form);
						
						$search_status = array();
						$sp = 0;
						if(intval($search_form['sp']) == 1){ //highlight only
							$sp = 1;
						}else if(intval($search_form['sp']) == 2){ //megamenu only
							$sp = 2;
						}else if(intval($search_form['sp']) == 3){ //megamenu only
							$sp = 3;
						}
						
						if(intval($search_form['status']) == 3){
							$search_status['status'] = 0; 
						}else if(intval($search_form['status']) == 1){
							$search_status['status'] = 1;
						}

						unset($search_form['list_page']);
						unset($search_form['status']);
						//Debug($search_form);

						/*if($this->session->userdata('admin_type') > 3){
								$search_form['category_id'] = $filter;
						}*/

						//$article_list = $this->Article_model->get_article(null, null,  $search_form, 'order_by', 'Asc', $start_page, $list_page);
						$article_list = $this->Article_model->get_article(null, $search_status,  $search_form, '_article.order_by', 'ASC', $page_start, $list_page, $sp);
						$category_list = $this->Category_model->getSelectCat($search_form['category_id'], 'category_id', $access_cat);
						//Debug($search_form);
						//die();
					}
					//die();

			}else{ //if($this->input->server('REQUEST_METHOD') !== 'POST')

					$keyword = "";
					$get_data = $this->input->get();

					//category_id
					if(isset($get_data['category_id'])){

							$subcategory_id = 0;
							$category_id = $get_data['category_id'];
							$search_form['category_id'] = intval($category_id);

							if(isset($get_data['subcategory_id'])){
								$subcategory_id = $get_data['subcategory_id'];
								$search_form['subcategory_id'] = intval($subcategory_id);
							}

							$article_list = $this->Article_model->get_article(null, null,  $search_form, '_article.create_date', 'DESC', $page_start, $list_page,0 ,0);
							//die();
							$category_list = $this->Category_model->getSelectCat($category_id, 'category_id', $access_cat);
							$listspage = selectList( $opt, 'list_page', 'class="form-control"', 'value', 'text', $list_page);

					}else if(isset($get_data['approve'])){
							
							if($get_data['approve'] == 4){
										$search_status = array(
											"approve" => 0,
											"status" => 1,
										);
							}

							$article_list = $this->Article_model->get_article(null, $search_status, null, '_article.create_date', 'DESC', $page_start, $list_page);
							$category_list = $this->Category_model->getSelectCat(0, 'category_id', $access_cat);

					}else{ //Beginer

							//echo "get_article( 'order_by', 'Asc', $start_page, $list_page)";
							//$article_list = $this->Article_model->get_article(null, null,  null, 'order_by', 'Asc', $start_page, $list_page);
							$article_list = $this->Article_model->get_article(null, $filter,  null, '_article.create_date', 'DESC', $page_start, $list_page);
							//Debug($article_list);
							//die();

							$listspage = selectList( $opt, 'list_page', 'class="form-control"', 'value', 'text', $list_page);
							$category_list = $this->Category_model->getSelectCat(0, 'category_id', $access_cat);

					}
			}

			//$selcat = $this->input->post();
			//Debug($selcat);
			//if(isset($selcat['category_id'])) $category_id = 0;
			//if(isset($selcat['subcategory_id'])) $subcategory_id = 0;

			$totalarticle = $this->Article_model->countarticle($category_id, $subcategory_id, $access_cat);

			if($subcategory_id > 0)
				$curpage = base_url('article?view=list&category_id='.$category_id.'&subcategory_id='.$subcategory_id);
			else if($category_id > 0)
				$curpage = base_url('article?view=list&category_id='.$category_id);
			else
				$curpage = base_url('article?view=list');

			//$slip_page = ceil($maxarticle/$list_page);
			//Debug($article_list);
			//Debug($curpage);

			$notification_article_list = '';
			$count_approve = 0;
			$notification_article = $this->Api_model->notification_msg('article');
			if(isset($notification_article[0]->count_approve)) $count_approve = $notification_article[0]->count_approve;
			if($notification_article[0]->count_approve == 1) $notification_article_list = $this->Api_model->notification_msg('article', 0);

			$webtitle = $language['article'];

			$data = array(
					"ListSelect" => $ListSelect,
					"article_list" => $article_list,
					"GenPage" => GenPage($curpage, $p, $list_page, $totalarticle),
					"category_list" => $category_list,
					"category_id" => $category_id,
					"subcategory_id" => $subcategory_id,
					"listspage" => $listspage,
					"totalarticle" => $totalarticle,
					"access_cat" => $access_cat,

					"notification_article" => $count_approve,
					"notification_article_list" => $notification_article_list,

					"breadcrumb" => $breadcrumb,
					"webtitle" => $webtitle,
					"content_view" => 'article/list_article'
			);

			$msg = $this->input->get();
			if(isset($msg['success'])) $data['success'] = urldecode($msg['success']);

			$this->load->view('template/template',$data);
	}

	public function search(){
			
			//$this->load->model('Category_model');
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			//$breadcrumb[] = $language['article'];
			if($this->input->server('REQUEST_METHOD') === 'GET'){

					$search_form = $this->input->get();
					if(isset($search_form['kw'])){
						//echo "is keyword";
						$keyw = explode(" ", $search_form['kw']);
						//Debug($keyw);
						//$article_list = $this->Article_model->get_article(null, $keyw,  null, 'lastupdate_date', 'DESC', 0, 20);
						$article_list = $this->Article_model->get_approve(null, 0, 0, $keyw, 'lastupdate_date', 'DESC', 0, 20);
						//Debug($article_list);
						if($article_list){

								echo '<table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th width="5%">ID</th>
													<th width="5%">Pic</th>
													<th width="35%">Title</th>
													<th width="8%">'.$language['lastupdate'].'</th>
													<!-- <th width="5%">'.$language['status'].'</th> -->
													<th width="5%">Count view</th>
													<th width="37%">URL</th>
												</tr>
											</thead>
											<tbody>';

								$maxlist = count($article_list);
								for($i=0;$i<$maxlist;$i++){
										//$url = "http://www.Tmon.com/article/".urlencode($article_list[$i]['title']).".html";

										$article_id = $article_list[$i]['article_id2'];
										$category_id = $article_list[$i]['category_id'];
										$subcategory_id = $article_list[$i]['subcategory_id'];
										$category_name = RewriteTitle($article_list[$i]['category_name']);
										$subcategory_name = RewriteTitle($article_list[$i]['subcategory_name']);
										$title = RewriteTitle($article_list[$i]['title']);
										//RewriteTitle($article_list[$i]['title']

										$url = $this->config->config['www']."/article/".$category_id."/".$subcategory_id."/".$article_id."/".$category_name."/".$subcategory_name."/".$title;

										$img = base_url('uploads/thumb').'/'.$article_list[$i]['folder'].'/'.$article_list[$i]['file_name'];
										//$img = 'uploads/thumb/'.$article_list[$i]['folder'].'/'.$article_list[$i]['file_name'];

										if($article_list[$i]['file_name'] != "" && isset($article_list[$i]['file_name']))
											$tags_img = (file_exists('uploads/thumb/'.$article_list[$i]['folder'].'/'.$article_list[$i]['file_name'])) ? "<img src=".$img." height='50'>" : "";
										else
											$tags_img = "";

										$edit_data = base_url('article/edit/'.$article_list[$i]['article_id2']);

										//$status = ($article_list[$i]['status'] == 1) ? 'checked' : '';
										/*$iconstatus = '<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-3" type="checkbox" '.$status.' disabled>
														<span class="lbl"></span>
													</label>
												</div>';*/
										//<td>".$iconstatus."</td>

										echo "<tr>
										<td>".$article_id."</td>
										<td>".$tags_img."</td>
										<td><a href='".$edit_data."' target=_blank>".$article_list[$i]['title']."</a></td>
										<td>".$article_list[$i]['lastupdate_date']."</td>
										<td>".$article_list[$i]['countview']."</td>
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
			
			//$this->load->model('Category_model');
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			if($this->input->server('REQUEST_METHOD') === 'GET'){

					$search_form = $this->input->get();
					if(isset($search_form['kw'])){

						$articleid = $search_form['articleid'];
						$keyw = explode(" ", $search_form['kw']);
						//Debug($keyw);

						//$article_list = $this->Article_model->get_article(null, $keyw,  null, 'lastupdate_date', 'DESC', 0, 20);
						$article_list = $this->Article_model->get_approve(null, 0, 0, $keyw, 'lastupdate_date', 'DESC', 0, 20);

						if($article_list){
								echo '<table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th>ID</th>
													<th>Pic</th>
													<th>Title</th>
													<th>'.$language['lastupdate'].'</th>
													<th>Count view</th>													
													<th width = "10%">Action</th>
												</tr>
											</thead>
											<tbody>';
								//<!-- <th>'.$language['status'].'</th> -->

								$maxlist = count($article_list);
								for($i=0;$i<$maxlist;$i++){
										//$url = "http://www.Tmon.com/article/".urlencode($article_list[$i]['title']).".html";
										//$url = "http://www.Tmon.com/article/".$article_list[$i]['article_id2'].".html";

										$img = base_url('uploads/thumb').'/'.$article_list[$i]['folder'].'/'.$article_list[$i]['file_name'];
										//$img = 'uploads/thumb/'.$article_list[$i]['folder'].'/'.$article_list[$i]['file_name'];

										if($article_list[$i]['file_name'] != "" && isset($article_list[$i]['file_name']))
											$tags_img = (file_exists('uploads/thumb/'.$article_list[$i]['folder'].'/'.$article_list[$i]['file_name'])) ? "<img src=".$img." height='50'>" : "";
										else
											$tags_img = "";

										/*$status = ($article_list[$i]['status'] == 1) ? 'checked' : '';
										$iconstatus = '<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-3" type="checkbox" '.$status.' disabled>
														<span class="lbl"></span>
													</label>
												</div>';*/
										//$addurl = 'javascript:void(0);';

										$addurl = base_url('article/add_relate_article').'?article_id='.$article_list[$i]['article_id2'].'&ref_id='.$articleid;
										$edit_data = base_url('article/edit/'.$article_list[$i]['article_id2']);
										$iconadd = '<a href="'.$addurl.'" data-value="'.$article_list[$i]['article_id2'].'" class="add_relate"><i class="ace-icon glyphicon glyphicon-plus">Add relate</i></a>';

										//<td>".$iconstatus."</td>
										echo "<tr>
										<td>".$article_list[$i]['article_id2']."</td>
										<td>".$tags_img."</td>
										<td><a href='".$edit_data."' target=_blank>".$article_list[$i]['title']."</a></td>
										<td>".$article_list[$i]['lastupdate_date']."</td>
										<td>".$article_list[$i]['countview']."</td>
										<td>".$iconadd."</td></tr>";
								}						
								echo "</tbody></table>";
						}else
							echo "ไม่มีข้อมูล";
					}
					//die();
			}

	}

	public function add_relate_article(){
			
			$data_input = $this->input->get();
			$ref_id = $data_input['ref_id'];
			$article_id = $data_input['article_id'];
			//Debug($data_input);
			$this->Article_model->add_relate_article($ref_id, $article_id);
			echo '<script type="text/javascript">
			<!--
				window.location="'.base_url("article/edit").'/'.$ref_id.'";
			//-->
			</script>';
	}

	public function add(){
			
			$this->load->model('Category_model');
			//$this->load->model('Dara_model');
			$this->load->model('Tags_model');
			$this->load->model('Credit_model');
		    $this->load->model('Columnist_model');
			$this->load->helper('ckeditor');

			$Path_CKfinder = base_url('plugins/ckeditor-integrated/ckfinder');
			$Path_CKeditor = 'plugins/ckeditor-integrated/ckeditor';

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('article').'">'.$language['article'].'</a>';
			$breadcrumb[] = $language['add'];

			if($this->session->userdata('admin_type') > 2){			
				$admin_id = $this->session->userdata('admin_id');
				$team_list = $this->Admin_team_model->get_admin_team();
				$admin_info = $this->adminfactory->getAdmin($admin_id);				
				$admin_team_id = $admin_info->_admin_team_id;

				for($i=0;$i<count($team_list);$i++){
						if($team_list[$i]['admin_team_id'] == $admin_team_id)
								$access_cat = json_decode($team_list[$i]['access']);
				}
			}else
				$access_cat = null;

			//$category = $this->Category_model->get_category();
			//$dara_list = $this->Dara_model->get_dara_profile();
			$category_list = $this->Category_model->getSelectCat(0, 'category_id', $access_cat);
			$tags_list = $this->Tags_model->getSelect();

			//$credit_list = $this->Credit_model->get_data();
			$credit_list = $this->Credit_model->getSelect();
			$columnist_list = $this->Columnist_model->get_data();

			$webtitle = $language['add'].$language['article'];

			/*$notification_article_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
			$notification_birthday = $this->Api_model->notification_birthday();
			$notification_article = $this->Api_model->notification_msg('article');
			if($notification_article[0]->count_approve == 1) $notification_article_list = $this->Api_model->notification_msg('article', 0);
			$notification_column = $this->Api_model->notification_msg('column');
			if($notification_column[0]->count_approve == 1) $notification_column_list = $this->Api_model->notification_msg('column', 0);
			$notification_gallery = $this->Api_model->notification_msg('gallery');
			if($notification_gallery[0]->count_approve == 1) $notification_gallery_list = $this->Api_model->notification_msg('gallery', 0);
			$notification_vdo = $this->Api_model->notification_msg('vdo');
			if($notification_vdo[0]->count_approve == 1) $notification_vdo_list = $this->Api_model->notification_msg('vdo', 0);
			$notification_dara = $this->Api_model->notification_msg('dara');
			if($notification_dara[0]->count_approve == 1) $notification_dara_list = $this->Api_model->notification_msg('dara', 0);*/

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					//"category" => $category,
					"columnist_list" => $columnist_list,
					"category_list" => $category_list,
					"tags_list" => $tags_list,
					"credit_list" => $credit_list,
					/*"notification_birthday" => $notification_birthday,
					"notification_article" => $notification_article[0]->count_approve,
					"notification_column" => $notification_column[0]->count_approve,
					"notification_gallery" => $notification_gallery[0]->count_approve,
					"notification_vdo" => $notification_vdo[0]->count_approve,
					"notification_dara" => $notification_dara[0]->count_approve,
					"notification_article_list" => $notification_article_list,
					"notification_column_list" => $notification_column_list,
					"notification_gallery_list" => $notification_gallery_list,
					"notification_vdo_list" => $notification_vdo_list,
					"notification_dara_list" => $notification_dara_list,*/

					"content_view" => 'article/add_article',
					"webtitle" => $webtitle,
					"breadcrumb" => $breadcrumb
			);

			//Ckeditor's configuration
			$data['ckeditor'] = array(
				'id'     =>     'description_en',
				'path'    =>    $Path_CKeditor,
				'config' => array(
					'customConfig'     =>     'config.js',
					'toolbar'     =>     'Basic',
					'removeButtons'     =>     'Save,Flash,Font,Smiley',
					//'width'     =>     "800px",
					//'height'     =>     '200px',
					'filebrowserBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html',
					'filebrowserImageBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Images',
					//'filebrowserFlashBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Flash',
					'filebrowserUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Files',
					'filebrowserImageUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Images',
					//'filebrowserFlashUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Flash',
				),
			);

			$data['detail_th'] = $data['detail_en'] = $data['description_th'] = $data['ckeditor'];
			$data['description_th']['id'] = 'description_th';
			$data['detail_en']['id'] = 'detail_en';
			$data['detail_th']['id'] = 'detail_th';

			$this->load->view('template/template',$data);

	}

	public function edit($id = 0){

			if($id == 0){
				redirect('article');
				die();
			}
			
			 $this->load->library('user_agent');
			 $this->load->model('Category_model');			 
			 $this->load->model('Subcategory_model');			 
			 $this->load->model('Tags_model');
			 $this->load->model('Credit_model');
			 $this->load->model('Columnist_model');
			 //$this->load->model('Galleryset_model');
			 $this->load->model('Box_model');
			 //$this->load->model('up18_model');
			 $this->load->helper('ckeditor');

			$Path_CKfinder = base_url('plugins/ckeditor-integrated/ckfinder');
			$Path_CKeditor = base_url('plugins/ckeditor-integrated/ckeditor');

			$credit_id = $tag_id = array();
			//$credit_id = new stdClass;

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('article').'">'.$language['article'].'</a>';
			$breadcrumb[] = $language['edit'];

			if($this->session->userdata('admin_type') > 2){			
				$admin_id = $this->session->userdata('admin_id');
				$team_list = $this->Admin_team_model->get_admin_team();
				$admin_info = $this->adminfactory->getAdmin($admin_id);				
				$admin_team_id = $admin_info->_admin_team_id;

				for($i=0;$i<count($team_list);$i++){
						if($team_list[$i]['admin_team_id'] == $admin_team_id)
								$access_cat = json_decode($team_list[$i]['access']);
				}
			}else
				$access_cat = null;
			
			//$category = $this->Category_model->get_category();
			$article_list =  $this->Article_model->get_article($id);

			if(!$article_list){
				redirect(base_url('article'));
				die();
			}
			
			$category_list = $this->Category_model->getSelectCat($article_list[0]['category_id'], 'category_id', $access_cat);
			
			if($article_list[0]['subcategory_id'] > 0){
				$subcategory_list = $this->Subcategory_model->getSelectSubcat($article_list[0]['category_id'], $article_list[0]['subcategory_id']);
			}else
				$subcategory_list = '<select class="form-control" id="subcategory_id" name="subcategory_id"></select>';
			
			$sel_tags = $this->Tags_model->get_tag_pair($id);
			if($sel_tags)
				for($i=0;$i<count($sel_tags);$i++){
					@$tag_id[$i]->value = $sel_tags[$i]->tag_id;
				}

			$credit_list = json_decode($article_list[0]['credit_id'], true);
			if($credit_list)
				for($i=0;$i<count($credit_list);$i++){
					@$credit_id[$i]->value = $credit_list[$i];
				}
			//Debug($credit_id);
			//die();

			//$dara_list = $this->Dara_model->get_dara_profile();
			//$picture_list = $this->Galleryset_model->get_picture_by_ref_id($id);
			$relate_list = $this->Article_model->get_relate($id);

			//$credit_list = $this->Credit_model->get_data();
			$credit_list = $this->Credit_model->getSelect($credit_id);
			$columnist_list = $this->Columnist_model->get_data();

			$tags_list = $this->Tags_model->getSelect($tag_id);
			
			$datalog = array(
					"ref_type" => 1,
					"ref_id" => $id
			);
			$view_log = $this->Admin_log_activity_model->view_log(0, $datalog);

			$displaylogs = $this->Admin_log_activity_model->DisplayLogs($id);

			//$get_up18 = $this->up18_model->get_up18($id, 1);
			//Debug($view_log);
			//Debug($article_list);
			//die();
			//$article_list = $this->Article_model->get_article($id);


			if(isset($article_list[1]['title'])) $title = $article_list[1]['title'];
			else $title = $article_list[0]['title'];

			$webtitle = $language['edit'].$language['article'].' '.$title;
			//Debug($get_up18);
			//die();
			
			//http://dara.Tmon.com/catching/main/?access_token=lUW4Ju2ei6&name=article_1_1_55740&url=http://dara.Tmon.com/article/1/1/55740&device=desktop&opt=del

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"article_list" => $article_list,
					"category_list" => $category_list,
					"highlight" => $this->Article_model->get_highlight($id),
					//"megamenu" => $this->Article_model->get_megamenu($id),
					"subcategory_list" => $subcategory_list,
					//"get_up18" => $get_up18,
					"tags_list" => $tags_list,
					"credit_list" => $credit_list,
					"columnist_list" => $columnist_list,
					//"picture_list" => $picture_list,
					"relate_list" => $relate_list,
					"view_log" => $view_log,
					"displaylogs" => $displaylogs,

					"content_view" => 'article/edit_article',
					"webtitle" => $webtitle,
					"breadcrumb" => $breadcrumb
			);

			//Ckeditor's configuration
			$data['ckeditor'] = array(				
				'id'     =>     'editorFull',
				'path'    =>    $Path_CKeditor,
				'config' => array(
					'customConfig'     =>     'config.js',
					'toolbar'     =>     'Basic',
					'removeButtons'     =>     'Save,Flash,Font,Smiley',
					//'width'     =>     "800px",
					//'height'     =>     '200px',
					//'uiColor' => '#ff6468',
					'filebrowserBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html',
					'filebrowserImageBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Images',
					//'filebrowserFlashBrowseUrl'     =>     $Path_CKfinder .'/ckfinder.html?type=Flash',
					'filebrowserUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Files',
					'filebrowserImageUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Images',
					//'filebrowserFlashUploadUrl'     =>     $Path_CKfinder .'/core/connector/php/connector.php?command=QuickUpload&type=Flash',
				),
			);

			$data['ckeditor2'] = $data['ckeditor3'] = $data['ckeditor4'] = $data['ckeditor'];
			$data['ckeditor2']['id'] = 'description_th';
			$data['ckeditor3']['id'] = 'detail_en';
			$data['ckeditor4']['id'] = 'detail_th';

			if ($this->agent->is_mobile()) $data['mobile'] = $this->agent->mobile();

			$this->load->view('template/template',$data);
	}

	public function gallery($id = 0){
			
			if($id == 0){
				redirect('article');
				die();
			}
			 $this->load->model('Api_model');
			 $this->load->model('Galleryset_model');
			 //$this->load->helper('youtube_helper');

			$tag_id = $load_bk = array();
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('article').'">'.$language['article'].'</a>';
			$breadcrumb[] = $language['gallery'];

			//$picture_list = $this->Api_model->get_picture($id);
			$item_list = $this->Api_model->get_item('all', $id);
			$article_list =  $this->Article_model->get_article_by_id($id);

			$webtitle = $language['gallery'].$language['article'].' '.$article_list[1]['title'];

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"article_list" => $article_list,
					"item_list" => $item_list,
					"load_bk" => $load_bk,
					"content_view" => 'article/item_article',
					"webtitle" => $webtitle,
					"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}

	public function add_item($id = 0){
			
			if($id == 0){
				redirect('article');
				die();
			}

			 $this->load->model('Api_model');
			 $this->load->model('Galleryset_model');
			 //$this->load->helper('youtube_helper');

			$tag_id = $load_bk = array();
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('article').'">'.$language['article'].'</a>';
			$breadcrumb[] = '<a href="'.base_url('article/gallery').'/'.$id.'">'.$language['gallery'].'</a>';
			$breadcrumb[] = $language['add'];
			
			$article_list =  $this->Article_model->get_article_by_id($id);
			
			$datainput = $this->input->get();

			if(isset($datainput['item_id'])){
				$itemedit_list = $this->Api_model->get_item('all', $id, $datainput['item_id']);
			}else
				$itemedit_list = null;

			//Debug($datainput);
			
			if(isset($datainput['item'])){
				switch($datainput['item']){
					case 'picture' :
						$item_list = $this->Api_model->get_item(1, $id);
						$content_view = 'article/add_picture';
					break;
					case 'vdo' :
						$item_list = $this->Api_model->get_item(2, $id);
						$content_view = 'article/add_vdo';
					break;
					default :
					break;
				}
			}
			
			//die();
			$webtitle = $language['gallery'].$language['article'].' '.$article_list[1]['title'];
			
			$data = array(
				"admin_menu" => $this->menufactory->getMenu(),
				"ListSelect" => $ListSelect,
				"article_list" => $article_list,
				"item_list" => $item_list,
				"itemedit_list" => $itemedit_list,
				"load_bk" => $load_bk,
				"content_view" => $content_view,
				"webtitle" => $webtitle,
				"breadcrumb" => $breadcrumb,
			);
			$this->load->view('template/template',$data);
	}
	
	public function order($id = 0){
			
			$this->load->library('user_agent');
						
			 $this->load->model('Api_model');
			 $this->load->model('Galleryset_model');
			 //$this->load->helper('youtube_helper');

			$tag_id = array();
			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			$breadcrumb[] = '<a href="'.base_url('article').'">'.$language['article'].'</a>';
			$breadcrumb[] ='<a href="'.base_url('article/gallery/'.$this->uri->segment(3)).'">'.$language['gallery'].'</a>';
			$breadcrumb[] = $language['order'];

			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;


			//$picture_list = $this->Api_model->get_picture($id);
			$item_list = $this->Api_model->get_item('all', $id);
			$article_list =  $this->Article_model->get_article_by_id($id);

			if($this->input->server('REQUEST_METHOD') === 'POST'){
					$search_form = $this->input->post();
					//Debug($search_form);
					$this->set_order_picture2($this->uri->segment(3), $search_form);
			}

			//$picture_list = $this->Api_model->get_picture($id);
			//$article_list = $this->Article_model->get_article_by_id($id);

			$webtitle = $language['order'].$language['gallery'].$language['article'].' '.$article_list[1]['title'];

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"article_list" => $article_list,
					//"category_list" => $category_list,
					//"subcategory_list" => $subcategory_list,
					"item_list" => $item_list,
					"content_view" => 'article/item_order',
					"webtitle" => $webtitle,
					"breadcrumb" => $breadcrumb
			);
			$this->load->view('template/template',$data);
	}

	public function picture_edit($id = 0, $idtype = 0){
			$this->picture_edit_thumb($id, $idtype);
	}

	public function picture_edit_thumb($id = 0, $idtype = 0){
						
			 $this->load->model('Api_model');
			 $this->load->model('Galleryset_model');
			 $this->load->model('Credit_model');

			 $orientation = 1;
			 $ref_id = $this->input->get();
			if($ref_id)
				foreach($ref_id as $key => $val){
						//echo "<br>".$article_id." : ".$val;
						if($key == "article_id") $article_id = $val;
						if($key == "Orientation") $orientation = $val;
				}
			$tag_id = array();
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			$language = $this->lang->language;

			$breadcrumb[] = '<a href="'.base_url('article').'">'.$language['article'].'</a>';
			$breadcrumb[] = '<a href="'.base_url('article/gallery').'/'.$ref_id['article_id'].'">'.$language['picture'].'</a>';
			$breadcrumb[] = $language['edit'];

			$item_list = $this->Api_model->get_item(0, $article_id, $id);
			$article_item = $this->Article_model->get_article($article_id);			
			$credit_list = $this->Credit_model->get_data();

			//Debug($picture_list[0]['credit_id']);

			/*$credit_id = json_decode($picture_list[0]['credit_id']);
			if($credit_id)
				for($i=0;$i<count($credit_id);$i++){
					@$credit_id[$i]->value = $credit_id[$i];
				}
			$credit_list = $this->Credit_model->getSelect($credit_id, 'credit_id', 'credit_id', 'single');*/

			if(count($item_list) == 0){
				$load_bk = $this->Api_model->load_pic_bk($id);
			}

			if(isset($article_item[1]['title'])) $title = $article_item[1]['title'];
			else $title = $article_item[0]['title'];

			$webtitle = $language['edit'].$language['picture'].$language['article'].' '.$title;
			//$webtitle = $language['edit'].$language['picture'].$language['article'].' '.$article_item[1]['title'];

			$data = array(
					"admin_menu" => $this->menufactory->getMenu(),
					"ListSelect" => $ListSelect,
					"article_id" => $article_id,
					"article_item" => $article_item[0],
					"credit_list" => $credit_list,
					"orientation" => $orientation,
					"item_list" => $item_list,
					"content_view" => 'article/thumb_article',
					"webtitle" => $webtitle,
					"breadcrumb" => $breadcrumb
			);
			$this->load->view('template/template',$data);
	}

	public function rotate(){

			$this->load->model('Galleryset_model');
			//$gallery_id = $this->input->get('gallery_id');
			$rotate = $this->input->get('rotate');
			$folder = $this->input->get('folder');
			$file = $this->input->get('file');

			$rotate = ($rotate == "l") ? -90 : 90;  //หมุนทวนเข็มนาฬิกา และ ตามเข็มนาฬิกา
			$sourcefile = './uploads/article/'.$folder.'/'.$file;
			$sourcefile_tmp = './uploads/tmp/'.$folder.'/'.$file;
			$sourcefile_tmp2 = './uploads/tmp2/'.$folder.'/'.$file;

			//Debug($sourcefile);
			//Debug($rotate);
			$this->Galleryset_model->rotate_img($sourcefile, $sourcefile, $rotate, 1);
			$this->Galleryset_model->rotate_img($sourcefile_tmp, $sourcefile_tmp, $rotate, 1);
			$this->Galleryset_model->rotate_img($sourcefile_tmp2, $sourcefile_tmp2, $rotate, 1);
	}

	public function picture_watermark($id = 0){

			$this->load->model('Api_model');
			$this->load->model('Galleryset_model');
			$this->load->helper('img');
			$type='article';

			$ref_id = $this->input->post();
			if($ref_id)
				foreach($ref_id as $key => $val){
						if($key == "id") $id = $val;
						if($key == "article_id") $article_id = $val;
						if($key == "folder") $folder = $val;
						if($key == "file") $file = $val;
						if($key == "watermark") $watermark = $val;
						if($key == "caption") $caption = StripTxt($val);
						if($key == "credit_id") $credit_id = $val;
						if($key == "status") $pic_status = $val;
				}

			switch($watermark){
						case "no" : $picture_list = $this->Galleryset_model->watermark($file, $folder, $type); break;	//ไม่มี Logo
						case "center" : $picture_list = $this->Galleryset_model->watermark($file, $folder, $type, 4); break;	//Logo ตรงกลาง
						case "horizontal" : $picture_list = $this->Galleryset_model->watermark($file, $folder, $type, 1); break;
						case "vertical" : $picture_list = $this->Galleryset_model->watermark($file, $folder, $type, 2); break;
						case "logo" : $picture_list = $this->Galleryset_model->watermark($file, $folder, $type, 3); break; //Logo ขนาดใหญ่
						case "topleft" : $picture_list = $this->Galleryset_model->watermark($file, $folder, $type, 5); break;
						case "buttomright" : $picture_list = $this->Galleryset_model->watermark($file, $folder, $type, 6); break;

						default : $picture_list = $this->Galleryset_model->watermark($file, $folder, $type); break; //ไม่มี Logo
			}

			$data = array(
				"caption" => $caption,
				"status" => $pic_status,
				"credit_id" => $credit_id
			);
			$this->Galleryset_model->store($id, $data);

			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $article_id,
					"ref_type" => 1,
					"ref_title" => "Set picture article picture id: ".$id." ".$caption,
					"action" => 2
			);			
			$this->Admin_log_activity_model->store($log_activity);

			 //Debug($picture_list);
			 //die();

			//redirect('article/gallery/'.$article_id);
			redirect('article/picture_edit/'.$id.'?article_id='.$article_id);			
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

			$this->load->model('Galleryset_model');

			$ref_id = $this->input->get('ref_id');

			if($ref_id > 0 && $pic_id > 0){
					$picture_list = $this->Api_model->get_picture($ref_id, $pic_id);
					$this->Galleryset_model->delete_gallery_set_admin($pic_id);
			}

			//debug($picture_list);
			//die();

			$article_list =  $this->Article_model->get_article_by_id($ref_id);
			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $ref_id,
								"ref_type" => 1,
								"ref_title" => "[picturn $pic_id]".$article_list[0]['title'],
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('article/gallery/'.$ref_id);

	}

	function upload_pic(){

			$this->load->model('Galleryset_model');
			$this->load->library('upload');
			$this->load->library('image_lib');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			$picture_obj = array();
			$files = $_FILES;
			$now_date = date('Y-m-d H:i:s');
			//$folder = date('Ymd');

			$data_input = $this->input->post();
			//Debug($data_input);

			if($data_input['create_date'] != ""){

				//$folder = $data_input['create_date'];
				$create_date = ConvertDate8toFormat($data_input['create_date'], '/');
				//Debug($create_date);
				//die();
				$folder = $create_date;

				$folder_img = explode("/", ConvertDate8toFormat($folder));
				$tmp_folder = '';
				$p=0;
				if(isset($folder_img)){
						foreach($folder_img as $val){
								$p++;
								if($p < 3)
									$tmp_folder .= $val.'/';
								else
									$tmp_folder .= $val;

								$this->Galleryset_model->chkfolder_exists($tmp_folder);
								$folder = $tmp_folder;
								//echo "<br>tmp_folder = $tmp_folder<br>";
								echo "<br>folder = $folder<br>";
						}
				}
				//Debug($folder);
				//die();
				//$this->Galleryset_model->chkfolder_exists($folder);
			}else{
				$folder = date('Y/m/d');
			}
			//Debug($folder);
			//die();

			//$picture_list = $this->Api_model->get_picture($data_input['article_id']);
			$picture_list = $this->Api_model->get_item($data_input['article_id']);

			//$config['path_thumb'] = './uploads/article/thumb/';
			//if(!is_dir($config['path_thumb'])) mkdir($config['path_thumb'], 0777);
			$cpt = count($_FILES['picture_article']['name']);
			for($i=0; $i<$cpt; $i++){

				$_FILES['userfile']['name']= $files['picture_article']['name'][$i];
				$_FILES['userfile']['type']= $files['picture_article']['type'][$i];
				$_FILES['userfile']['tmp_name']= $files['picture_article']['tmp_name'][$i];
				$_FILES['userfile']['error']= $files['picture_article']['error'][$i];
				$_FILES['userfile']['size']= $files['picture_article']['size'][$i];    
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
						$picture_obj['ref_id'] = $data_input['article_id'];
						$picture_obj['ref_type'] = 1;
						$picture_obj['file_name'] = $this->upload->client_name;
						//$picture_obj['title'] = StripTxt($data_input['title']);						
						$picture_obj['caption'] = StripTxt($data_input['caption']);
						$picture_obj['folder'] = $folder;
						$picture_obj['create_date'] = $now_date;
						$picture_obj['create_by'] = $this->session->userdata('admin_id');
						$picture_obj['status'] = 1;

						if(isset($data_input['set_default']) && $data_input['set_default'] == 1 && $i == 0) $picture_obj['default'] = 1;
						
						$this->Galleryset_model->store(0, $picture_obj);
				}
				//Debug($this->upload);
			}

			$article_list =  $this->Article_model->get_article_by_id($data_input['article_id']);
			//**************Log activity
			$action = 2;
			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $data_input['article_id'],
					"ref_type" => 1,
					"ref_title" => "[Upload Picturn $cpt]".$article_list[0]['title'],
					"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect(base_url('article/gallery/'.$data_input['article_id']));
	}

	function save_clip(){

			$this->load->model('Galleryset_model');
			$this->load->library('upload');
			$this->load->library('image_lib');

			$language = $this->lang->language;
			$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));

			$picture_obj = array();
			$files = $_FILES;
			$now_date = date('Y-m-d H:i:s');
			//$folder = date('Ymd');

			if($this->input->server('REQUEST_METHOD') === 'POST'){

						$data_input = $this->input->post();
						//Debug($data_input);

						$clip_status = $data_input['status'];

						$picture_obj['ref_id'] = $data_input['article_id'];
						$picture_obj['ref_type'] = 2;
						//$picture_obj['title'] = StripTxt($data_input['title']);						
						$picture_obj['caption'] = StripTxt($data_input['caption']);
						//$picture_obj['folder'] = $folder;
						$picture_obj['url'] = $data_input['url'];
						$picture_obj['create_date'] = $now_date;
						$picture_obj['create_by'] = $this->session->userdata('admin_id');
						$picture_obj['status'] = $clip_status;

						//if(isset($data_input['set_default']) && $data_input['set_default'] == 1 && $i == 0) $picture_obj['default'] = 1;
						//Debug($picture_obj);
						//die();

						$action = 1;

						if(isset($data_input['galleryset_id'])){
							$action = 2;
							$this->Galleryset_model->store($data_input['galleryset_id'], $picture_obj);
						}else		
							$this->Galleryset_model->store(0, $picture_obj);

					$article_list =  $this->Article_model->get_article_by_id($data_input['article_id']);
					//**************Log activity
					$log_activity = array(
							"admin_id" => $this->session->userdata('admin_id'),
							"ref_id" => $data_input['article_id'],
							"ref_type" => 1,
							"ref_title" => "[Upload Picturn $cpt]".$article_list[0]['title'],
							"action" => $action
					);			
					$this->Admin_log_activity_model->store($log_activity);
					//**************Log activity

					redirect(base_url('article/gallery/'.$data_input['article_id']));
			}
	}

	private function set_watermark_options($client_name, $text = "Girldaily"){   

		$config = array();
		$folder = date('Ymd');
		
		$config['source_image'] = './uploads/article/'.$folder.'/'.$client_name;
		$config['new_image'] =  './uploads/article/'.$folder.'/sd_'.$client_name;
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
		$config['new_image'] = './uploads/article/'.$folder.'/'.$client_name;

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
		$addfolder = "";
		$chkfolder = explode("/", $folder);
		//if($folder == '') $folder = date('Ymd');
		if(count($chkfolder) > 1){
				$addfolder = "";
				for($i=0;$i<count($chkfolder);$i++){

						if($i == 0)
							$addfolder = $chkfolder[$i];
						else
							$addfolder .= "/".$chkfolder[$i];

						$config['upload_path'] = './uploads/article/';
						if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

						$config['upload_path'] = './uploads/article/'.$addfolder;
						if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

						$upload_path = './uploads/tmp/';
						if(!is_dir($upload_path)) mkdir($upload_path, 0777);

						$upload_path = './uploads/tmp/'.$addfolder;
						if(!is_dir($upload_path)) mkdir($upload_path, 0777);
						//Debug($upload_path);
				}
				$folder = $addfolder;
		}else{

				$config['upload_path'] = './uploads/article/';
				if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

				$config['upload_path'] = './uploads/article/'.$folder;
				if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777);

				$upload_path = './uploads/tmp/';
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);

				$upload_path = './uploads/tmp/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);

		}
		
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size']      = '0';
		$config['overwrite']     = FALSE;

		return $config;
	}
	
	public function remove_img($id = 0){
			
			$src = $this->input->post('name');

			if(file_exists('uploads/dara/'.$src)) unlink('uploads/dara/'.$src);
			$obj_data['picture_article'] = '';
			if($this->Article_model->store($dara_profile_id, $obj_data))
				echo 'Yes';
			else
				echo 'No';

			/*$src = $this->input->post('name');
			 if ($this->input->server('REQUEST_METHOD') === 'POST'){
					if(file_exists('uploads/article/'.$src)) unlink('uploads/article/'.$src);
					if(file_exists('uploads/thumb/'.$src)) unlink('uploads/thumb/'.$src);
					$obj_data['picture_article'] = '';
					if($this->Article_model->store($id, $obj_data))
						echo 'Yes';
					else
						echo 'No';
			 }*/
	}

	public function orderset($id1, $id2){
			/*if($id == 0){
				$data = array(
						"error" => 'id error'
				);
				return false;
			}else{

					$obj_status = $this->Article_model->get_status($id);
					$cur_status = $obj_status[0]['status'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;
					$obj_data['status'] = $cur_status;
					if($this->Article_model->store2($id, $obj_data)) echo $cur_status;
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

					$obj_status = $this->Article_model->get_status($id);
					$cur_status = $obj_status[0]['status'];
					$title = $obj_status[0]['title'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;

					$obj_data['status'] = $cur_status;
					$obj_data['lastupdate_date'] = date('Y-m-d H:i:s');
					$obj_data['lastupdate_by'] = $this->session->userdata('admin_id');

					if($this->Article_model->store2($id, $obj_data)) echo $cur_status;
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
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity
	}


	public function approve($id = 0){
			if($id == 0){
					$data = array(
							"error" => 'id error'
					);
					return false;
			}else{

					$obj_status = $this->Article_model->get_status($id);
					$cur_status = $obj_status[0]['approve'];
					$title = $obj_status[0]['title'];

					if($cur_status == 0) $cur_status = 1;
					else $cur_status = 0;

					$obj_data['approve'] = $cur_status;
					$obj_data['approve_date'] = date('Y-m-d H:i:s');
					$obj_data['approve_by'] = $this->session->userdata('admin_id');

					if($this->Article_model->store2($id, $obj_data)) echo $cur_status;
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
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity
	}

	public function save(){

		$this->load->model('Tags_model');
		$this->load->model('Galleryset_model');
		$this->load->model('Category_model');

		//$this->load->model('up18_model');

		$language = $this->lang->language;
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));
			
		//$breadcrumb[] = $language['dara'];
		if($this->input->server('REQUEST_METHOD') === 'POST'){

			$new_data = $tag_id = $tag_pair = $tag_compair = $picture_obj = $tag_dara = $log_activity = $tags_arr = $tags_arr2 = array();
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
					$this->Article_model->store($dara_profile_id, $this->input->post());
			}*/

			/*$this->load->library('upload', $this->set_upload_options());
			$this->upload->initialize($this->set_upload_options());

			if ( ! $this->upload->do_upload('picture_article')){
					$error = array('error' => $this->upload->display_errors());
					$upload_status = $error;
			}else{
					$upload_status = "Success";
			}*/

				//Debug($data_input['relate_id']);
				//die();
			
				if(isset($data_input['relate_id'])){
						if(count($data_input['relate_id']) < 5){

								$relate_id = $data_input['relate_id'];
								$orderid = $data_input['orderid'];
								$i = 1; 
								foreach($relate_id as $arr => $val){
										$this->Article_model->update_order_relate($val, $data_input['article_id'], $orderid[$arr]);
										$i++;
								}
								/**************Log activity
								$log_activity = array(
													"admin_id" => $this->session->userdata('admin_id'),
													"ref_id" => $data_input['article_id'],
													"ref_type" => 1,
													"ref_title" => $data_input['title_th']." [Order By]",
													"action" => 2
								);			
								$this->Admin_log_activity_model->store($log_activity);*/
								//**************Log activity
								//$success =  'Save Order complete.';
								//redirect(base_url('article/edit').'/'.$data_input['article_id']);
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
							if($key == "article_id_en"){
								$new_data['article_id'] = $data_input['article_id_en'];
							}else if($key == "article_id_th"){
								$new_data_th['article_id'] = $data_input['article_id_th'];
							}else if($key == "article_id"){

								$new_data['article_id2'] = $data_input['article_id'];
								$new_data_th['article_id2'] = $data_input['article_id'];

							}else if($key != "tag_id" && $key != "" && $key != "highlight" && $key != "megamenu" && $key != "orderid" && $key != "'relate_id" && $key != "'editor_choice" && $key != "'editor_picks"){
									
								if($key == "title_en" || $key == "description_en" || $key == "detail_en"){

									if($key == "title_en"){  
										$new_data['title'] = trim(StripTxt($data_input['title_en']));
										$new_data['lang'] = "en"; 
									}
									if($key == "description_en")  $new_data['description'] = $data_input['description_en'];
									if($key == "detail_en")  $new_data['detail'] = $data_input['detail_en'];

								}else if($key == "title_th" || $key == "description_th" || $key == "detail_th"){

									if($key == "title_th"){
										$new_data_th['title'] = trim(StripTxt($data_input['title_th']));
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
							/*if($key == "editor_picks"){
									if($data_input['editor_picks'] == "on"){
											//$new_data['editor_choice'] = 1; 
											//$new_data_th['editor_choice'] = 1; 
									}else{
											//$new_data['editor_choice'] = 0; 
											//$new_data_th['editor_choice'] = 0;
									}
							}*/
							if($key == "recommend"){
									if($data_input['recommend'] == "on"){
											$new_data['recommend'] = 1; 
											$new_data_th['recommend'] = 1; 
									}else{
											$new_data['recommend'] = 0; 
											$new_data_th['recommend'] = 0;
									}
							}
			}

			//Debug($data_input);
			//Debug($new_data_th);
			//Debug($new_data);
			//die();

			//$get_dara = $this->Dara_model->get_dara_profile_by_id($data_input['']);
			//if(isset($get_dara[0]->gender)) $new_data['gender'] = $get_dara[0]->gender;

			//Debug($new_data);
			//die();

			/*$data_highlight = array(
					"article_id" => $data_input['article_id'],
					"ref_id" => 1,
					"order" => 0
			);*/

			//if(isset($data_input['highlight'])) Debug($data_input['highlight']);
			//$this->Article_model->set_highlight($data_input['article_id'], $data_highlight);
			//die();

			/*if(!isset($new_data[''])){
				$new_data[''] = 0;				
			}*/

			//if($this->upload->client_name){
					//$new_data['picture_article'] = $this->upload->client_name;
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

			//$newcount = count($data_input);

			if(isset($data_input['article_id_en'])){
				$newcount = 2;
			}else
				$newcount = 1;
			//echo "newcount = $newcount<br>";
			//Debug($data_input);
			if(isset($data_input['icon_vdo'])){
					$new_data['icon_vdo'] = 1;
					$new_data_th['icon_vdo'] = 1;
			}else{
					$new_data['icon_vdo'] = 0;
					$new_data_th['icon_vdo'] = 0;
			}
			//Debug($new_data);
			//die();

			if(!isset($new_data['article_id'])){
					$new_data['article_id'] = $new_data['article_id2'];
			}

			//**************************************** Relate article ***************************************
			$chk_relate = 0;
			$number_of_relate = 4;			
			//Debug($data_input['relate_list']);
			//echo "($chk_relate == 1 || relate_list <= ".$data_input['relate_list']." )<br>";

			if($chk_relate == 1 || $data_input['relate_list'] <= 0){
				if($new_data['article_id2'] > 0){
					
					$tag_relate = $this->Article_model->get_relate($data_input['article_id']); // Check ว่ามี relate รึป่าว
					//Debug($tag_relate);
					
					//if(count($tag_relate)<$number_of_relate){
					if(count($tag_relate) == 0){

							/*if($new_data[''] > 0) 
								$tag_relate = $this->Article_model->gen_relate($new_data[''], $data_input['tag_id'], $new_data['article_id']);
							else */
								$tag_relate = $this->Article_model->gen_relate(null, $data_input['tag_id'], $new_data['article_id']);

							//Debug($tag_relate);
							//die();
							$where_articleid = array();
							if($tag_relate){
								$max5 = (count($tag_relate) > $number_of_relate) ? $number_of_relate : count($tag_relate);
								for($t=0;$t<$max5;$t++){

										$number = $t+1;
										$tag_relate[$t]->article_id;
										//echo "<hr>".$number." ".$tag_relate[$t]->article_id."<hr>";
										if($tag_relate[$t]->article_id != $new_data['article_id']){
											$where_articleid[$t]['article_id'] = $tag_relate[$t]->article_id;
											$where_articleid[$t]['ref_id'] = $new_data['article_id'];
											$where_articleid[$t]['order'] = $number;
										}

								}
							}
							if(count($where_articleid) > 0) $this->Article_model->save_relate($new_data['article_id'], $where_articleid);
					}
					unset($new_data['relate_id']);
					unset($new_data_th['relate_id']);
					unset($new_data['orderid']);
					unset($new_data_th['orderid']);
				}
			}
			//Debug($where_articleid);
			//echo "NO";

			//Debug($data_input);
			//Debug($new_data);
			//Debug($new_data_th);

			//**************************************** ใส่ Tags ใน Content editor ***************************************
			/*$autotags == "off";
			$autotags = (isset($data_input['auto_tags'])) ? $data_input['auto_tags'] : '';
			//echo "<br>autotags = $autotags";

			if(($new_data['article_id2'] == 0) || ($autotags == "on")){

				$tags_all = $this->Tags_model->getall_tag_pair(5); //tags ของดารา

				if($tags_all){
					$number_tag = count($tags_all);
					$counttag = 0;
					for($i=0;$i<$number_tag;$i++){

							$textnormal = $tags_all[$i]->tag_text;
							$dara_type_id = $tags_all[$i]->dara_type_id;
							$dara_profile_id = $tags_all[$i]->dara_profile_id;
							$dara_type_name = $tags_all[$i]->dara_type_name;
									
							$url = $this->config->config['www'].'/dara/'.$dara_type_id.'/'.$dara_profile_id.'/'.RewriteTitle($dara_type_name).'/'.RewriteTitle($textnormal);

							$text_replace = '<a href="'.$url.'" target=_blank>'.$textnormal.'</a>';

							//echo "<hr>$textnormal<br>";
							//echo "preg_match($textnormal, new_data_th['detail']) == ".preg_match("/$textnormal/i", $new_data_th['detail'])."<br>";
							//Eng
							if(isset($new_data['detail']))
								if(preg_match("/$textnormal/i", $new_data['detail'])){
										$new_data['detail'] = str_replace($textnormal, $text_replace, $new_data['detail']);
										$counttag++;
								}

							if(isset($new_data['description']))
								if(preg_match("/$textnormal/i", $new_data['description'])){
										$new_data['description'] = str_replace($textnormal, $text_replace, $new_data['description']);
										$counttag++;
								}

							//Thai
							if(preg_match("/$textnormal/i", $new_data_th['detail'])){
									$new_data_th['detail'] = str_replace($textnormal, $text_replace, $new_data_th['detail']);
									$counttag++;
							}
							if(preg_match("/$textnormal/i", $new_data_th['description'])){
									$new_data_th['description'] = str_replace($textnormal, $text_replace, $new_data_th['description']);
									$counttag++;
							}
					}
				}
			} //Add tags*/

			//echo "<hr>counttag = $counttag";
			//Debug($tags_arr);
			//Debug($tags_arr2);
			//die();
			//*********************************************************

			unset($new_data['tag_id']);
			unset($new_data['auto_tags']);
			unset($new_data['auto_keyword']);
			unset($new_data['relate_list']);
			unset($new_data['editor_picks']);

			unset($new_data_th['tag_id']);
			unset($new_data_th['auto_tags']);
			unset($new_data_th['auto_keyword']);
			unset($new_data_th['relate_list']);
			unset($new_data_th['editor_picks']);

			if(isset($new_data['credit_id']))
				$new_data['credit_id'] = $new_data_th['credit_id'] = json_encode($new_data['credit_id']);
			else
				$new_data['credit_id'] = $new_data_th['credit_id'] = null;

			//************** checkbox* ***************
			if(!isset($new_data['status'])){ 
					$new_data['status'] = 0; $new_data_th['status'] = 0; 
			}else{
					$new_data['status'] = 1; $new_data_th['status'] = 1; 
			}

			if(!isset($new_data['icon_vdo'])){ $new_data['icon_vdo'] = 0; $new_data_th['icon_vdo'] = 0; }
			//if(!isset($new_data['editor_choice'])){ $new_data['editor_choice'] = 0; $new_data_th['editor_choice'] = 0; }
			if(!isset($new_data['recommend'])){ $new_data['recommend'] = 0; $new_data_th['recommend'] = 0; }
			if(!isset($new_data['up18'])){ $new_data['up18'] = 0; $new_data_th['up18'] = 0; }

			//Debug($new_data);
			//die();

			if((isset($new_data['article_id'])) && ($new_data['article_id'] > 0)){
						
				$chk_tags_pair = 0;
				$action = 2;
						
				$new_data['lastupdate_by'] = $this->session->userdata('admin_id');
				$new_data_th['lastupdate_by'] = $this->session->userdata('admin_id');

				$new_data['lastupdate_date'] = $now_date;
				$new_data_th['lastupdate_date'] = $now_date;
						
				$new_id = $new_data['article_id2'];

				if($newcount > 1) $this->Article_model->store($new_data['article_id'], $new_data);
				$this->Article_model->store($new_data_th['article_id'], $new_data_th);

			}else{ //Insert

				$chk_tags_pair = 1;
				$action = 1;

				$new_data['create_date'] = $now_date;
				$new_data['create_by'] = $this->session->userdata('admin_id');
				$new_data['order_by'] = 1;

				$new_data_th['create_date'] = $now_date;
				$new_data_th['create_by'] = $this->session->userdata('admin_id');
				$new_data_th['order_by'] = 1;

				$max_id = $this->Article_model->get_max_id();
				$new_id = $max_id[0]['max_id'] + 1;
						
				$new_data['article_id'] = $new_id;
				$new_data['article_id2'] = $new_id;
				$new_data_th['article_id2'] = $new_id;

				//Add 1 all record
				$this->Article_model->update_orderadd($new_data['category_id']);
				$this->Article_model->store(0, $new_data);
				$this->Article_model->store(0, $new_data_th);
			}
/***************************tags*************************************/
			//$article_id = $new_data[''];
			//$chk_tags_pair = $auto_keyword  = 1;

			if(isset($data_input['auto_keyword'])) $auto_keyword = 1; else $auto_keyword = 0;

			//Debug($data_input);
			//Debug($tag_id);
			//echo "(($chk_tags_pair == 1 || $auto_keyword == 1))";

			if($chk_tags_pair == 1 || $auto_keyword == 1){

				//$curtag = $this->Tags_model->validate_tags($tag_name);
						//$get_tag_dara = $this->Tags_model->get_tag_pair($new_data[''], 5); //หา tag_id ของดารา

						//Debug($get_tag_dara);
						$clear = 1;
						//if($new_id <= 0) $article_id = $data_input['article_id'];
						/*if($get_tag_dara){
							for($i=0;$i<count($get_tag_dara);$i++){
								$tag_pair[$i]["tag_id"] = intval($get_tag_dara[$i]->tag_id);
								$tag_pair[$i]["ref_id"] = intval($new_id);
								$tag_pair[$i]["ref_type"] = 1;
								$tag_pair[$i]["create_date"] = $now_date;
								$next = $i;
							}
						}*/
						//Insert tags
						//Debug($tag_id);
						if($tag_id){
								/*if(!isset($next)) $next = 0;
								$begin = $next + 1;
								$endtag = count($tag_id) + $begin;
								$t = 0;*/
								for($i=0;$i<count($tag_id);$i++){
									$tag_pair[$i]["tag_id"] = intval($tag_id[$i]);
									$tag_pair[$i]["ref_id"] = intval($new_id);
									$tag_pair[$i]["ref_type"] = 1;
									$tag_pair[$i]["create_date"] = $now_date;
									//$t++;
								}
							
							/*echo "<hr>tag_pair";
							Debug($tag_pair);
							echo "<hr>tag_pair2";
							Debug($tag_pair2);*/
								//$tag_pair = array_merge($tag_pair, $tag_pair2);
								/*$m=0;
								//echo "<hr>array_merge";
								for($i=0;$i<count($tag_pair);$i++){
										//echo "<hr>tag_id => ".$tag_pair2[$i]["tag_id"];
										$have = 0;
										for($j=0;$j<count($tag_pair2);$j++){
												if($tag_pair[$i]["tag_id"] == $tag_pair2[$j]["tag_id"]) $have = 1;
												//echo "<br>COMPARE ".$tag_pair[$i]["tag_id"].' == '.$tag_pair2[$j]["tag_id"];
										}
										if($have == 0 && isset($tag_pair2[$j-1]["tag_id"])){

												//echo "<br> is add array ".$tag_pair2[$j-1]["tag_id"];
												$tag_compair[$m]["tag_id"] = $tag_pair[$i]["tag_id"];
												$tag_compair[$m]["ref_id"] = $tag_pair[$i]["ref_id"];
												$tag_compair[$m]["ref_type"] = 1;
												$tag_compair[$m]["create_date"] = $now_date;
												$m++;
										}
								}*/
							//$tag_pair = array_merge($tag_pair2, $tag_compair);
							//echo "<hr>tag_pair";
							//Debug($tag_pair);
							//die();
						}
						if($tag_pair) $this->Tags_model->store_tag_pair($tag_pair, $clear);
			}
			//die();

			//**************Set Highlight
			if(isset($data_input['highlight'])){
				$data_highlight = array(
					"article_id" => $new_id,
					"ref_type" => 1,
					"order" => 0
				);							
				$this->Article_model->set_highlight($new_id, $data_highlight);
				$this->Article_model->update_order_highlight();
				//echo "new_id = $new_id";
				//die();
			}else{
				$this->Article_model->remove_highlight($new_id);
			}

			//**************Set Megamenu
			if(isset($data_input['megamenu'])){

				//Debug($data_input['highlight']);
				$data_megamenu = array(
					"id" => $new_id,
					"category_id" => $new_data['category_id'],
					"ref_type" => 1,
					"order" => 0
				);							
				$this->Article_model->set_megamenu($new_id, $data_megamenu);
				//echo "new_id = $new_id";
				//die();
			}else{
				$this->Article_model->remove_megamenu($new_id);
				//echo "new_id = $new_id";
			}

			//**************Set editor pick's
			if(isset($data_input['editor_picks'])){

				//Debug($data_input['highlight']);
				$data_editor_picks = array(
					"article_id" => $new_id,
					"ref_type" => 1,
					"order" => 0
				);							
				$this->Article_model->set_editor_picks($new_id, $data_editor_picks);
				//echo "new_id = $new_id";
				//die();
			}else{
				$this->Article_model->remove_editor_picks($new_id);
				//echo "new_id = $new_id";
			}

			//**************18+
			/*if(isset($data_input['up18'])){
				$this->up18_model->set_up18($new_id, 1);
			}else{
				$this->up18_model->remove_up18($new_id, 1);
			}*/

			//**************Log activity
			$log_activity = array(
				"admin_id" => $this->session->userdata('admin_id'),
				"ref_id" => $new_id,
				"ref_type" => 1,
				"ref_title" => $new_data_th['title'],
				"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
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
				$this->Galleryset_model->store(0, $picture_obj);

			}

			//$data['category_list'] = $this->Category_model->getSelectCat();
			//$totalarticle = $this->Article_model->countarticle(0, 0);
			//$curpage = base_url('article?view=list');

			$data = array(
				"admin_menu" => $this->menufactory->getMenu(),
				"ListSelect" => $ListSelect,
				//"GenPage" => GenPage($curpage, 1, $list_page, $totalarticle),
				//"article_list" => $this->Article_model->get_article(null, null, $language['lang']),
				//"category_list" => $this->Category_model->getSelectCat(),
				"content_view" => 'article/list_article',
				"breadcrumb" => $breadcrumb,
				"success" =>  'Save article complete.'
			);

			$success =  'Save complete.';
			//die();
			//$this->load->view('template/template',$data);
			unset($data_input);
			unset($new_data);
			unset($new_data_th);

			//redirect(base_url('article?category_id='.$new_data['category_id'].'&success='.urlencode($success)));
			redirect(base_url('article/edit/'.$new_id.'?success='.urlencode($success)));
			
		}else{//if($this->input->server('REQUEST_METHOD') !== 'POST')

			redirect(base_url('article'));
		
		}
	}

	public function set_order_relate(){
					
		$lang = $this->lang->language;
					
		$articleid = $this->input->post('articleid');
		$json = $this->input->post('json');

		$obj = json_decode($json);
		$update_data = array();

		//echo "articleid = $articleid<br>";
		//Debug($obj);
		$num = 1;
		if($obj)
			for($i = 0; $i< count($obj);$i++){
				//echo $num.'.) '.$obj[$i]->id.' : '.$articleid.' ==> '.$num.'<br>';
				unset($update_data);
				$update_data['order'] = $num;
				$this->Article_model->set_order_relate($articleid, $obj[$i]->id, $update_data);
				$num++;
			}
		echo $lang['update'].' '.$lang['success'].'.';
	}

	public function set_order_picture(){
					
			$lang = $this->lang->language;

			$this->load->model('Api_model');
			$this->load->model('Galleryset_model');
			$this->load->helper('youtube_helper');

			//$item_list = $this->Api_model->get_item('all', $id);
			//$article_list =  $this->Article_model->get_article_by_id($id);
					
					$articleid = $this->input->post('articleid');
					$json = $this->input->post('json');

					//$articleid = 1;
					//$json = '[{"id":11},{"id":5},{"id":6},{"id":3},{"id":1}]';

					$obj = json_decode($json);
					$update_data = array();

					//Debug($this->input->post());
					//echo "articleid = $articleid<br>";
					//Debug($obj);
					//die();

					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj);$i++){
								//echo $num.'.) '.$obj[$i]->id.' : '.$articleid.' ==> '.$num.'<br>';
								unset($update_data);
								$update_data['order'] = $num;
								$this->Article_model->set_order_picture($articleid, $obj[$i]->id, $update_data);
								$num++;
						}
					echo $lang['update'].' '.$lang['success'].'.';
	}

	public function set_order_picture2($articleid, $obj){
					
					//$lang = $this->lang->language;
					$update_data = array();
					//Debug($this->input->post());
					//echo "articleid = $articleid<br>";
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
											$this->Article_model->set_order_picture_to_up($articleid, $picture_id, $orderid, $tmp+1); //--
											$updateorder = $orderid;
											$this->Article_model->set_order_picture($articleid, $picture_id, $update_data);
											$do++;

										}else{
											$this->Article_model->set_order_picture($articleid, $picture_id, $update_data);
											//$tmp = $orderid;
										}
										//echo "<br>case 0 $picture_id == $orderid<br>";
								}else if(($tmp != $orderid) && (($tmp+1) == $orderid)){
										if($updateorder != $orderid) $this->Article_model->set_order_picture($articleid, $picture_id, $update_data);
										//$tmp = $orderid;
										//echo "<br>case 1 $picture_id == $orderid<br>";
								}else{

										//echo "<br>case 2 tmp =$tmp > $orderid<br>";
										//echo "($tmp < $orderid)<br>";

										if($tmp > $orderid){
												$between = $orderid - $tmp;
												//echo "(between : $between)<br>";
												/*echo "(articleid =$articleid, orderid =$orderid)<br>";
												echo "(articleid =$articleid, picture_id = $picture_id, update_data = ".print_r($update_data).")<br>";*/
												if($do == 0){
													$this->Article_model->set_order_picture_to_down($articleid, $picture_id, $orderid, $tmp);
													$updateorder = $orderid;
													$this->Article_model->set_order_picture($articleid, $picture_id, $update_data);
													$do++;
												}else{
													if($updateorder != $orderid) $this->Article_model->set_order_picture($articleid, $picture_id, $update_data);
												}
										}else{
												//echo "($articleid, $orderid, $tmp)<br>";
												if($do == 0){
													$this->Article_model->set_order_picture_to_up($articleid, $picture_id, $orderid, $tmp+1);
													$updateorder = $orderid;
													$this->Article_model->set_order_picture($articleid, $picture_id, $update_data);
													$do++;
												}else{
													if($updateorder != $orderid) $this->Article_model->set_order_picture($articleid, $picture_id, $update_data);
												}
										}
								}
								$tmp++;
								$num++;
						}

				$article = $this->Article_model->get_article_by_id($articleid);

				//**************Log activity
				$action = 2;
				$log_activity = array(
									"admin_id" => $this->session->userdata('admin_id'),
									"ref_id" => $articleid,
									"ref_type" => 1,
									"ref_title" => "จัดเรียง ".$article[0]['title'],
									"action" => $action
				);
				$this->Admin_log_activity_model->store($log_activity);
				//**************Log activity
	}

	public function gen_json($id){

				//http://daraapi.siamsport.co.th/api/rest.php?method=DetailNews&key=5AckEziE&lang=th&news_id=39&gen_file=1

				$articleid_th = $this->Api_model->article('th', $id);
				$articleid_en = $this->Api_model->article('en', $id);

				//$articleid_th = JsonContent($articleid_th);
				//$articleid_en = JsonContent($articleid_en);

				//Debug($articleid_th);
				//Debug($articleid_en);

				if(!is_dir('json/www/article')) mkdir('json/www/article', 0777);

				SaveJSON($articleid_th, $id.'_th', 0, 'www/article/');
				SaveJSON($articleid_en, $id.'_en', 0, 'www/article/');
				//echo "Finish";
				//redirect('json/index');
	}

	public function set_default($id = 0){

			$this->load->model('Galleryset_model');

			echo "Set default... $id";
			$type = 1;

			//$data_input = $this->input->post();
			$data_input = $this->input->get();

			//Debug($data_input);

			$id = $data_input['id'];
			$ref_id = $data_input['ref_id'];
			//die();
			
			$OBJarticle = $this->Article_model->get_article($ref_id);
			//Debug($OBJarticle);

			$article_id = $OBJarticle[0]['article_id2'];
			$title = "Set default picture ".$OBJarticle[0]['title'];
			$order_by = $OBJarticle[0]['order_by'];

			//$this->Article_model->set_default($id, $ref_id);
			$this->Galleryset_model->set_default($id, $ref_id, $type);

			//**************Log activity
			$action = 2;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $ref_id,
								"ref_type" => $type,
								"ref_title" => 'set default picture'.$title,
								"action" => $action
			);
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			//redirect('article/picture/'.$this->uri->segment(3));
			redirect('article/gallery/'.$ref_id);
			die();
	}	

	public function delete($id, $cat){
		
			echo "Deleting... $id";
			
			$OBJarticle = $this->Article_model->get_article($id);
			$title = $OBJarticle[0]['title'];
			$order_by = $OBJarticle[0]['order_by'];

			$this->Article_model->delete_article($id);

			//**************Order New
			$this->Article_model->update_orderdel($cat, $order_by);

			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 1,
								"ref_title" => $title,
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity

			redirect('article');
			die();
	}

	public function delrelate($id){
			
			//$this->load->model('Tags_model');
			echo "Deleting... $id";
			
			$OBJarticle = $this->Article_model->get_article($id);
			$title = $OBJarticle[0]['title'];
			$this->Article_model->delete_relate_article($id);
			//$this->Tags_model->delete_tag_pair($id, 1);

			//**************Log activity
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 1,
								"ref_title" => "Delete relate article ".$title,
								"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);
			//**************Log activity
			$stop = $this->input->get('stop');
			if($stop == 1) die();
			redirect('article/edit/'.$id);
			die();
	}

	public function DelRelateID(){

			$data_input = $this->input->post();
			$newid = $data_input['newid'];
			$id = $data_input['id'];
			$title = $data_input['name'];
			//echo "Deleting... $id";
			$this->Article_model->delete_relate_article(0, $id);

			$action = 3;
			$log_activity = array(
					"admin_id" => $this->session->userdata('admin_id'),
					"ref_id" => $newid,
					"ref_type" => 1,
					"ref_title" => "Delete relate article id: ".$id." ".$title,
					"action" => $action
			);			
			$this->Admin_log_activity_model->store($log_activity);

			die();
	}

	public function saveorder(){
			
			$language = $this->lang->language['lang'];

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$search_form = $this->input->post();
					//Debug($search_form);
					//die();
					$data['order'] = $search_form['orderid'];
					$articleid = $search_form['article_id'];

					$getobj = $this->Article_model->get_status($articleid, $language);
					//Debug($getobj);

					if(isset($search_form['relate_id'])){
							//if(count($search_form['relate_id']) < 5){

									$relate_id = $search_form['relate_id'];
									$orderid = $search_form['orderid'];
									$i = 1; 

									//Debug($relate_id);

									foreach($relate_id as $arr => $val){
											$this->Article_model->update_order_relate($search_form['relate_id'][$arr], $articleid, $search_form['orderid'][$arr]);
											$i++;
									}

									/**************Log activity *********/
									$log_activity = array(
														"admin_id" => $this->session->userdata('admin_id'),
														"ref_id" => $articleid,
														"ref_type" => 1,
														"ref_title" => $getobj[0]['title']." [Order By]",
														"action" => 2
									);			
									$this->Admin_log_activity_model->store($log_activity);
									/**************Log activity**********/

									//$success =  'Save Order complete.';
									redirect(base_url('article/edit').'/'.$articleid);
									die();
							//}
					}
			}

	}	
	
	function hotarticle_save(){

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$data_form = $this->input->post();
					Debug($data_form);
					die();

			}
	}
	
	function sethighlight($id){

			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$title = urldecode($this->input->post('title'));
					$op = $this->input->post('op');
					if($op == 'remove'){

							//**************Remove Highlight
							$this->Article_model->remove_highlight($id);
							echo "0";
							/**************Log activity *********/
							/*$log_activity = array(
									"admin_id" => $this->session->userdata('admin_id'),
									"ref_id" => $id,
									"ref_type" => 1,
									"ref_title" => $title." [Remove highlight]",
									"action" => 2
							);			
							$this->Admin_log_activity_model->store($log_activity);*/

					}else{

							//**************Set Highlight
							$data_highlight = array(
									"article_id" => $id,
									"ref_type" => 1,
									"order" => 0
							);
							//Debug($data_highlight);
							$this->Article_model->set_highlight($id, $data_highlight);
							$this->Article_model->update_order_highlight();
							echo "1";
							/**************Log activity *********/
							/*$log_activity = array(
									"admin_id" => $this->session->userdata('admin_id'),
									"ref_id" => $id,
									"ref_type" => 1,
									"ref_title" => $title." [Set highlight]",
									"action" => 2
							);			
							$this->Admin_log_activity_model->store($log_activity);*/
					}
			}
	}

	function setmegamenu($id){

			if($this->input->server('REQUEST_METHOD') === 'POST'){
					$title = urldecode($this->input->post('title'));
					$catid = $this->input->post('catid');
					$catname = $this->input->post('cat');
					$op = $this->input->post('op');
					/*Debug($id);
					Debug($title);
					Debug($catid);
					die();*/

					if($op == 'remove'){

						//**************Remove Highlight
						$this->Article_model->remove_megamenu($id);
						echo "0";
						/**************Log activity *********/
						$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 1,
								"ref_title" => $title." [Remove megamenu]",
								"action" => 2
						);			
						$this->Admin_log_activity_model->store($log_activity);
						/**************Log activity**********/

					}else{

						//**************Set Megamenu
						$data_megamenu = array(
							"id" => $id,
							"category_id" => $catid,
							"ref_type" => 1,
							"order" => 0
						);							
						$this->Article_model->set_megamenu($id, $data_megamenu);
						echo "1";
						/**************Log activity *********/
						$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"ref_type" => 1,
								"ref_title" => $title." [Set megamenu]",
								"action" => 2
						);			
						$this->Admin_log_activity_model->store($log_activity);
						/**************Log activity**********/
					}

			}
	}

	function seteditor_picks($id){
			$this->load->model('editor_picks_model');
			if($this->input->server('REQUEST_METHOD') === 'POST'){
					$title = urldecode($this->input->post('title'));
					$op = $this->input->post('op');
					//echo "title = $title<br>";
					//echo "op = $op<br>";
					//die();
					if($op == 'remove'){
							//**************Remove editor_picks
							$this->Article_model->remove_editor_picks($id);
							echo "0";
					}else{
							//**************Set Highlight
							$data = array(
									"article_id" => $id,
									"ref_type" => 1,
									"order" => 0
							);
							$this->Article_model->set_editor_picks($id, $data);
							$this->Article_model->update_order_editor_picks();
							echo "1";
					}
			}
	}

	function auto_order($cat = 0){
				
				$language = $this->lang->language;
				$cat_view = array();

				if($cat != 0) $cat_view['category_id'] = $cat;
				$article_list = $this->Article_model->get_article(null, null,  $cat_view);
				//Debug($article_list);

				if(isset($article_list)){
						for($i=0;$i<count($article_list);$i++){
									$number = $i + 1;
									$idarticle = $article_list[$i]['article_id2'];
									$title = $article_list[$i]['title'];
									$order_by = $article_list[$i]['order_by'];
									$data = array(
											"order_by" => $number
									);
									echo "$idarticle $title [$order_by ==> $number]<br>";
									$this->Article_model->store2($idarticle, $data);
						}
						echo "<hr>UPDATE Order cat = $cat  total = ".count($article_list)." record.";
						unset($data);
						$data = array();
						$this->Article_model->store2(0, $data);
				}
	}

}
