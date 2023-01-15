<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller {

    public function __construct()    {
        parent::__construct();

		$this->load->library('session');

        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
			$this->hotnews();
	}

	public function update_news(){ //ajax
					
					$this->load->model('news_model');
					$lang = $this->lang->language;
					
					$json = $this->input->post('json');
					$obj = json_decode($json);
					$update_data = array();
					//Debug($obj);
					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj);$i++){
								//echo $num.'.) '.$obj[$i]->id.'<br>';

								unset($update_data);
								$update_data['order_by'] = $num;
								$this->news_model->store2($obj[$i]->id, $update_data);

								$num++;
						}
					//**************Log activity
					$action = 2;
					$log_activity = array(
										"admin_id" => $this->session->userdata('admin_id'),
										"ref_id" => 0,
										"ref_type" => 0,
										"ref_title" => $lang['order'].' '.$lang['news'],
										"action" => $action
					);			
					$this->admin_log_activity_model->store($log_activity);
					//**************Log activity
					echo $lang['update'].' '.$lang['success'].'.';
	}

	public function highlight(){
					
					$language = $this->lang->language;
					$this->load->model('news_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);

					$highlight_list1 = $this->api_model->get_highlight(1);
					$highlight_list2 = $this->api_model->get_highlight(2);
					$highlight_list3 = $this->api_model->get_highlight(3);
					$highlight_list4 = $this->api_model->get_highlight(4);

					$highlight_list = array_merge($highlight_list1, $highlight_list2, $highlight_list3, $highlight_list4);
					asort($highlight_list);
					//Debug($highlight_list);
					//die();

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"highlight_list" => $highlight_list
					);

					$data['content_view'] = 'sort/highlight';
					$this->load->view('template',$data);
	}

	public function block(){
					
					$this->load->model('block_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);
					$language = $this->lang->language;

					$breadcrumb[] = 'Block';
					//$breadcrumb[] = $language['edit'];

					$block_list = $this->block_model->get_block();
					//Debug($highlight_list);

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"block_list" => $block_list,
							"breadcrumb" => $breadcrumb
					);

					$data['content_view'] = 'sort/block';
					$this->load->view('template',$data);
	}

	public function update_block(){
					
					$this->load->model('block_model');
					$lang = $this->lang->language;
					
					$json = $this->input->post('json');
					$obj = json_decode($json);
					$update_data = array();
					//Debug($obj);
					//die();
					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj);$i++){
								
								$id = $obj[$i]->id;
								//echo $num.'.) '.$id.'<br>';
								unset($update_data);
								$update_data['order'] = $num;
								
								$this->block_model->setorder($id, $update_data);
								//Debug($this->db->last_query());
								$num++;
						}

					/**************Log activity
					$action = 2;
					$log_activity = array(
										"admin_id" => $this->session->userdata('admin_id'),
										"ref_id" => 0,
										"ref_type" => 0,
										"ref_title" => $lang['order'].' '.$lang['block'],
										"action" => $action
					);			
					$this->admin_log_activity_model->store($log_activity);
					**************Log activity****/

					echo $lang['update'].' '.$lang['success'].'.';
	}

	public function update_hl(){
					
					$this->load->model('news_model');
					$lang = $this->lang->language;
					
					$json = $this->input->post('json');
					$obj = json_decode($json);
					$update_data = array();
					//Debug($obj);
					//exit;
					//die();
					$num = 1;
					if($obj)
						for($i = 0; $i< count($obj);$i++){
								//echo $num.'.) '.$obj[$i]->id.'<br>';

								unset($update_data);
								$update_data['order'] = $num;

								list($type, $hlid) = explode("-", $obj[$i]->id);
								$this->api_model->setorder_highlight($hlid, $type, $update_data);
								$num++;
						}

					//**************Log activity
					$action = 2;
					$log_activity = array(
										"admin_id" => $this->session->userdata('admin_id'),
										"ref_id" => 0,
										"ref_type" => 0,
										"ref_title" => $lang['order'].' '.$lang['highlight'],
										"action" => $action
					);			
					$this->admin_log_activity_model->store($log_activity);
					//**************Log activity

					echo $lang['update'].' '.$lang['success'].'.';
	}

	public function hotnews_update(){

					if($this->input->server('REQUEST_METHOD') === 'POST'){
							
							$language = $this->lang->language;
							$this->load->model('news_model');

							$setupdate = true;
							$counter = 0;
							$set_arr = array();
							
							$search_form = $this->input->post();
							
							if(isset($search_form['catid'])) $catid = $search_form['catid']; else $catid = 1;
							$newsid = $search_form['newsid'];
							$val = $search_form['val'];
							if(isset($search_form['pin_list'])) $pin_list = $search_form['pin_list'];
							//Debug($pin_list);

							$news_list = $this->news_model->get_order($newsid, $language['lang']);
							/*echo "\n newsid = $newsid\n";
							echo "<b>now</b> ".$news_list[0]->order_by." ==> $val\n\n";
							echo "Begin " .$news_list[0]->order_by;*/

							if($news_list[0]->order_by > $val){ //delete1
									
									$max = ($news_list[0]->order_by - $val) + $val;									
									for($i=$val;$i<$max;$i++){
											$setupdate = true;
											//echo "\nfor ($i=$val; $i<=(".$news_list[0]->order_by." - $val))\n";
											for($c=0;$c<count($pin_list);$c++){
													if($i == $pin_list[$c]['pin']) $setupdate = false;
											}
									
											if($setupdate == true){
												//echo "UPDATE $i \n";
												$set_arr[$counter] = $i;
												$counter++;
											}
									}
									//Debug($set_arr);
									rsort($set_arr);
									//Debug($set_arr);
									$setnew = 0;

									for($i=0;$i<count($set_arr);$i++){
											
											if($setnew == 0) $setnew = $news_list[0]->order_by;
											else $setnew = $set_arr[$i-1];
																						
											//echo "SET ".$set_arr[$i]." \n";

											$news_list = $this->news_model->set_order($set_arr[$i], intval($setnew), $catid);

											//$sql = 'SELECT * FROM `sd_news` WHERE category_id=1 AND order_by=4 AND STATUS=1';
									}
									$news_list = $this->news_model->set_order(0, intval($val), $catid,$newsid);
							
							}else{ //add1
									
									$thisorder = $news_list[0]->order_by;

									/*echo "\n newsid = $newsid\n";
									echo "<b>now</b> ".$thisorder." ==> $val\n\n";
									echo "Begin " .$thisorder;*/

									$begin = ($val - $thisorder) + $thisorder;
									$i=$begin;
									
									//echo "($i=$begin;$i>$thisorder;$i--)\n";

									for($i=$begin;$i>$thisorder;$i--){
											$setupdate = true;
											for($c=0;$c<count($pin_list);$c++){
													if($i == $pin_list[$c]['pin']) $setupdate = false;
											}
									
											if($setupdate == true){
												//echo "UPDATE $i \n";
												$set_arr[$counter] = $i;
												$counter++;
											}
									}
									//Debug($set_arr);
									//asort($set_arr);				
									//Debug($set_arr);
									$setnew = 0;
									$max = count($set_arr)-1;

									//$i=$max;
									//echo "\n ($i=$max;$i>=0;$i--);\n";

									for($i=$max;$i>=0;$i--){
																						
											if($setnew == 0) 
												$setnew = $thisorder;
											else
												$setnew = $set_arr[$i+1];

											//echo "\n set_order($set_arr[$i], $setnew, 1);\n";
											$news_list = $this->news_model->set_order($set_arr[$i], intval($setnew), $catid);
									}
									//echo "\n set_order(0, $val, 1, $newsid);\n";
									$news_list = $this->news_model->set_order(0, intval($val), $catid,$newsid);
							}
					}
	}	

	public function hotnews(){
					
					$language = $this->lang->language;
					$this->load->model('news_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);

					$categoryid = array("category_id" => 1);

					/*if($this->input->server('REQUEST_METHOD') === 'POST'){							
							$search_form = $this->input->post();
							Debug($search_form);
							die();
					}*/

					$news_list = $this->news_model->get_approve($categoryid, $language['lang'], null, null, 'order_by', 'Asc', 0, 20);
					$pin_list = $this->news_model->get_pin($categoryid, $language['lang']);

					//Debug($pin_list);
					//Debug($this->db->last_query());
					//die();

					if($pin_list){
						for($i=0;$i<count($pin_list);$i++){
							$this->news_model->set_order(0, intval($pin_list[$i]->pin), 1,$pin_list[$i]->news_id2);
						}
					}

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"news_list" => $news_list,
							"pin_list" => $pin_list
					);

					$data['content_view'] = 'sort/hotnews';
					$this->load->view('template',$data);
	}

	public function gossip(){
					
					$language = $this->lang->language;
					$this->load->model('news_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);

					$catid = 3;

					$categoryid = array("category_id" => $catid);

					/*if($this->input->server('REQUEST_METHOD') === 'POST'){							
							$search_form = $this->input->post();
							Debug($search_form);
							die();
					}*/

					$news_list = $this->news_model->get_approve($categoryid, $language['lang'], null, null, 'order_by', 'Asc', 0, 20);
					$pin_list = $this->news_model->get_pin($categoryid, $language['lang']);

					//Debug($pin_list);
					//Debug($this->db->last_query());
					//die();

					if($pin_list){
						for($i=0;$i<count($pin_list);$i++){
							$this->news_model->set_order(0, intval($pin_list[$i]->pin), $catid,$pin_list[$i]->news_id2);
						}
					}

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"news_list" => $news_list,
							"pin_list" => $pin_list,
							"catid" => $catid
					);

					$data['content_view'] = 'sort/news';
					$this->load->view('template',$data);
	}

	public function clip(){
					
					$language = $this->lang->language;
					$this->load->model('column_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);

					$catid = 5;

					$categoryid = array("category_id" => $catid);

					$news_list = $this->column_model->get_approve($categoryid, null, $language['lang'], 'order_by', 'Asc', 0, 20);
					//$pin_list = $this->news_model->get_pin($categoryid, $language['lang']);

					if(isset($pin_list)){
						for($i=0;$i<count($pin_list);$i++){
							$this->column_model->set_order(0, intval($pin_list[$i]->pin), $catid,$pin_list[$i]->news_id2);
						}
					}

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"news_list" => $news_list,
							//"pin_list" => $pin_list,
							"catid" => $catid
					);

					$data['content_view'] = 'sort/column';
					$this->load->view('template',$data);
	}

	public function social(){
					
					$language = $this->lang->language;
					$this->load->model('column_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);

					$catid = 7;

					$categoryid = array("category_id" => $catid);

					$news_list = $this->column_model->get_approve($categoryid, null, $language['lang'], 'order_by', 'Asc', 0, 20);
					//$pin_list = $this->news_model->get_pin($categoryid, $language['lang']);

					if(isset($pin_list)){
						for($i=0;$i<count($pin_list);$i++){
							$this->column_model->set_order(0, intval($pin_list[$i]->pin), $catid,$pin_list[$i]->news_id2);
						}
					}

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"news_list" => $news_list,
							//"pin_list" => $pin_list,
							"catid" => $catid
					);

					$data['content_view'] = 'sort/column';
					$this->load->view('template',$data);
	}

	public function profiledara(){
					
					$language = $this->lang->language;
					$this->load->model('column_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);

					$catid = 9;

					$categoryid = array("category_id" => $catid);

					$news_list = $this->column_model->get_approve($categoryid, null, $language['lang'], 'order_by', 'Asc', 0, 20);
					//$pin_list = $this->column_model->get_pin($categoryid, $language['lang']);

					/*if(isset($pin_list)){
						for($i=0;$i<count($pin_list);$i++){
							$this->column_model->set_order(0, intval($pin_list[$i]->pin), $catid,$pin_list[$i]->news_id2);
						}
					}*/

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"news_list" => $news_list,
							//"pin_list" => $pin_list,
							"catid" => $catid
					);

					$data['content_view'] = 'sort/column';
					$this->load->view('template',$data);
	}

	public function mv(){
					
					$language = $this->lang->language;
					$this->load->model('column_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);

					$catid = 11;

					$categoryid = array("category_id" => $catid);

					$news_list = $this->column_model->get_approve($categoryid, null, $language['lang'], 'order_by', 'Asc', 0, 20);
					//$pin_list = $this->news_model->get_pin($categoryid, $language['lang']);

					if(isset($pin_list)){
						for($i=0;$i<count($pin_list);$i++){
							$this->column_model->set_order(0, intval($pin_list[$i]->pin), $catid,$pin_list[$i]->news_id2);
						}
					}

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"news_list" => $news_list,
							//"pin_list" => $pin_list,
							"catid" => $catid
					);

					$data['content_view'] = 'sort/column';
					$this->load->view('template',$data);
	}

	public function tv(){
					
					$language = $this->lang->language;
					$this->load->model('column_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);

					$catid = 13;

					$categoryid = array("category_id" => $catid);

					$news_list = $this->column_model->get_approve($categoryid, null, $language['lang'], 'order_by', 'Asc', 0, 20);
					//$pin_list = $this->news_model->get_pin($categoryid, $language['lang']);

					if(isset($pin_list)){
						for($i=0;$i<count($pin_list);$i++){
							$this->column_model->set_order(0, intval($pin_list[$i]->pin), $catid,$pin_list[$i]->news_id2);
						}
					}

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"news_list" => $news_list,
							//"pin_list" => $pin_list,
							"catid" => $catid
					);

					$data['content_view'] = 'sort/column';
					$this->load->view('template',$data);
	}

	public function lovestory(){
					
					$language = $this->lang->language;
					$this->load->model('column_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);

					$catid = 15;

					$categoryid = array("category_id" => $catid);

					$news_list = $this->column_model->get_approve($categoryid, null, $language['lang'], 'order_by', 'Asc', 0, 20);
					//$pin_list = $this->news_model->get_pin($categoryid, $language['lang']);

					if(isset($pin_list)){
						for($i=0;$i<count($pin_list);$i++){
							$this->column_model->set_order(0, intval($pin_list[$i]->pin), $catid,$pin_list[$i]->news_id2);
						}
					}

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"news_list" => $news_list,
							//"pin_list" => $pin_list,
							"catid" => $catid
					);

					$data['content_view'] = 'sort/column';
					$this->load->view('template',$data);
	}

	public function varity(){
					
					$language = $this->lang->language;
					$this->load->model('column_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);

					$catid = 17;

					$categoryid = array("category_id" => $catid);

					$news_list = $this->column_model->get_approve($categoryid, null, $language['lang'], 'order_by', 'Asc', 0, 20);
					//$pin_list = $this->news_model->get_pin($categoryid, $language['lang']);

					if(isset($pin_list)){
						for($i=0;$i<count($pin_list);$i++){
							$this->column_model->set_order(0, intval($pin_list[$i]->pin), $catid,$pin_list[$i]->news_id2);
						}
					}

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"news_list" => $news_list,
							//"pin_list" => $pin_list,
							"catid" => $catid
					);

					$data['content_view'] = 'sort/column';
					$this->load->view('template',$data);
	}

	public function gallery(){
					
					//echo "55";
					//die();

					$language = $this->lang->language;
					$this->load->model('gallery_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->api_model->user_menu($admin_type);

					if($this->input->server('REQUEST_METHOD') === 'POST'){
							
							/*$setupdate = true;
							$counter = 0;
							$set_arr = array();
							
							$search_form = $this->input->post();
							Debug($search_form);
							die();*/
					}


					//$catid = 17;
					//$categoryid = array("category_id" => $catid);

					$gallery_list = $this->gallery_model->get_approve();
					//$pin_list = $this->news_model->get_pin($categoryid, $language['lang']);
					//Debug($gallery_list);
					//die();

					/*if($pin_list){
						for($i=0;$i<count($pin_list);$i++){
							$this->gallery_model->set_order(0, intval($pin_list[$i]->pin), $catid,$pin_list[$i]->news_id2);
						}
					}*/

					$data = array(
							"admin_menu" => $this->menufactory->getMenu(),
							"ListSelect" => $ListSelect,
							"gallery_list" => $gallery_list,
							//"catid" => $catid
					);

					$data['content_view'] = 'sort/gallery';
					$this->load->view('template',$data);
	}

	public function gallery_update(){
					
			if($this->input->server('REQUEST_METHOD') === 'POST'){

					$this->load->model('gallery_model');
					$lang = $this->lang->language;
					$update_data = array();
					
					//$json = $this->input->post('json');
					//$obj = json_decode($json);

					$galleryid = $this->input->post('galleryid');
					$val = $this->input->post('val');
					//Debug($galleryid);
					//Debug($val);

					$gallery_list = $this->gallery_model->get_order($galleryid);
					//Debug($gallery_list);

					//echo "\n ".$gallery_list[0]->order_by." ==> $val\n";

					if($gallery_list[0]->order_by > $val){
							//echo "<br>delete - 1\n";
							$this->gallery_model->update_orderid_to_down($galleryid, $val, $gallery_list[0]->order_by);
							//echo "\n";
							$this->gallery_model->update_order($galleryid, $val);
							//echo "\n";

					}else{
							//echo "<br>add + 1\n";
							$this->gallery_model->update_orderid_to_up($galleryid, $val, $gallery_list[0]->order_by);
							//echo "\n";
							$this->gallery_model->update_order($galleryid, $val);
							//echo "\n";
					}

					//die();

					/**************Log activity******/
					$action = 2;
					$log_activity = array(
										"admin_id" => $this->session->userdata('admin_id'),
										"ref_id" => 0,
										"ref_type" => 0,
										"ref_title" => $lang['order'].' '.$lang['gallery'],
										"action" => $action
					);			
					$this->admin_log_activity_model->store($log_activity);
					/**************Log activity*****/
					//echo '<br>'.$lang['update'].' '.$lang['success'].'.';
			}

	}

}

