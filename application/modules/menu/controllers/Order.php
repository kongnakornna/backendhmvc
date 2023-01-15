<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller {

    public function __construct()    {
        parent::__construct();
		$this->load->library("AdminFactory");
		$this->load->model('Admin_team_model');        
		$this->load->model('Article_model');

		$this->load->library('session');

        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
			$this->hotnews();
	}

	public function highlight(){
					
					$language = $this->lang->language;
					//$this->load->model('news_model');

					$admin_id = $this->session->userdata('admin_id');
					$admin_type = $this->session->userdata('admin_type');
					$ListSelect = $this->Api_model->user_menu($admin_type);

					$allhighlight_list = $this->Api_model->get_highlight();
					//Debug($allhighlight_list);

					//$highlight_list1 = $this->Api_model->get_highlight(1);
					//$highlight_list2 = $this->Api_model->get_highlight(2);
					//$highlight_list3 = $this->Api_model->get_highlight(3);
					//$highlight_list4 = $this->Api_model->get_highlight(4);
					//$highlight_list = array_merge($highlight_list1, $highlight_list2, $highlight_list3, $highlight_list4);

					$new_highlight = array();
					$number = 0;
					$new_highlight = $allhighlight_list;

					/*if(isset($allhighlight_list)){
							$maxorder = count($allhighlight_list);
							for($i=0;$i<$maxorder;$i++){

									$number++;
									$get_highlight = $this->Api_model->get_highlight($allhighlight_list[$i]->ref_type, $allhighlight_list[$i]->article_id);

									if(count($get_highlight) == 1){
										$new_highlight[$i] = $get_highlight[0];
									}
							}
					}*/
					//Debug($allhighlight_list);
					//die();

					/*echo "<hr>";
					Debug($new_highlight);
					die();*/

					//sort($new_highlight);
					//echo "<hr>";
					//Debug($new_highlight);
					//$new_highlight = arsort($highlight_list);
					//aasort($highlight_list,"order");
					//echo "<hr>";

				/*$get_url = urldecode($this->input->get('url'));
				$json_obj = file_get_contents($get_url);
				$view_content = $json_obj;*/

				/*$loadfile = "highlight_th.json";	
				if(file_exists('./json/www/highlight/'.$loadfile)) 
					$json_highlight_th = LoadJSON($loadfile, 'www/highlight/');
				else
					$json_highlight_th = '';*/



				$js[] = 'jquery.nestable.js';

				$access_token = $this->config->config['access_token_www'];
				$api_key = $this->config->config['api_key'];
				//Desktop
				$device = ($this->input->get('device')) ? $this->input->get('device') : 'desktop';
				$name = ($this->input->get('name')) ? $this->input->get('name') : 'home';
				$url = ($this->input->get('url')) ? $this->input->get('url') : $this->config->config['www'];
				$gen_home = $this->config->config['www'].'/catching/main/?access_token='.$access_token.'&name='.$name.'&device='.$device.'&url='.$url;

				//http://wpapi.siamsport.co.th/live/api/rest.php?method=hilight&key=ZxDE@96jK&gen=1&num=10
				$gen_highlight = $this->config->config['api'].'/live/api/rest.php?method=hilight&gen=1&key='.$api_key;

				$this_hilight = $this->config->config['api'].'/live/api/rest.php?method=hilight&key='.$api_key;
				//$this_hilight = $this->config->config['api'].'/live/api/rest.php?method=hilight';
				$jsonData = $this->Api_model->normal_request($this_hilight);
				$json_highlight = json_decode($jsonData, true);

				$webtitle = $language['order'].$language['highlight'];

				$data = array(
						"admin_menu" => $this->menufactory->getMenu(),
						"ListSelect" => $ListSelect,
						"highlight_list" => $new_highlight,
						"gen_home" => $gen_home,
						"gen_highlight" => $gen_highlight,
						//"js" => $js,
						"json_highlight" => $json_highlight,
						"webtitle" => $webtitle
				);

				$data['content_view'] = 'sort/highlight';
				$this->load->view('template/template',$data);
	}

	public function update_hl(){
					
					//$this->load->model('news_model');
					$lang = $this->lang->language;
					
					$json = $this->input->post('json');
					//$json = '[{"id":"1-3"},{"id":"1-1"}]';
					//Debug($json);
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
								$this->Api_model->setorder_highlight($hlid, $type, $update_data);
								$num++;
						}

					//**************Log activity
					$action = 4;
					$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => 0,
								"from_ip" => $_SERVER['REMOTE_ADDR'],
								"ref_type" => 0,
								"ref_title" => $lang['order'].' '.$lang['highlight'],
								"action" => $action,
								"create_date" => date('Y-m-d H:i:s'),
								"status" => '1',
								"lang" => $this->lang->line('lang')
					);
					$this->admin_log_activity_model->store($log_activity);
					//**************Log activity
					if(count($obj) > 1)
						echo $lang['update'].' '.$lang['success'].'.';
					else
						echo $lang['update'].' '.$lang['notsuccess'].'.';

	}

	public function delete_highlight($id = 0){

			$this->load->model('highlight_model');
			$title = urldecode($this->uri->segment(4));
			$this->highlight_model->del_highlight($id);
			/**************Log activity******/
			$action = 3;
			$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => $id,
								"from_ip" => $_SERVER['REMOTE_ADDR'],
								"ref_type" => 0,
								"ref_title" => $lang['delete'].' '.$lang['highlight'].' '.$title,
								"action" => $action,
								"create_date" => date('Y-m-d H:i:s'),
								"status" => '1',
								"lang" => $this->lang->line('lang')
			);			
			$this->admin_log_activity_model->store($log_activity);
			/**************Log activity*****/
			redirect('order/highlight?auto=1');
	}

	public function editor_picks(){
					
				$language = $this->lang->language;
				$admin_id = $this->session->userdata('admin_id');
				$admin_type = $this->session->userdata('admin_type');
				$ListSelect = $this->Api_model->user_menu($admin_type);
				$new_editor_choice = array();
				$number = 0;

				$new_editor_picks = $this->Api_model->get_editorpick();

				$loadfile = "editor_picks.json";	
				if(file_exists('./json/www/'.$loadfile)) 
					$json_highlight_th = LoadJSON($loadfile, 'www/');
				else
					$json_highlight_th = '';

				$js[] = 'jquery.nestable.js';

				$access_token = $this->config->config['access_token_www'];
				//Desktop
				/*$device = ($this->input->get('device')) ? $this->input->get('device') : 'desktop';
				$name = ($this->input->get('name')) ? $this->input->get('name') : 'home';
				$url = ($this->input->get('url')) ? $this->input->get('url') : $this->config->config['www'];
				$gen_home = $this->config->config['www'].'/catching/main/?access_token='.$access_token.'&name='.$name.'&device='.$device.'&url='.$url;*/

				$webtitle = $language['order'].$language['highlight'];

				$data = array(
						"admin_menu" => $this->menufactory->getMenu(),
						"ListSelect" => $ListSelect,
						"editor_picks_list" => $new_editor_picks,
						//"gen_home" => $gen_home,
						"json_highlight" => $json_highlight_th,
						"webtitle" => $webtitle
				);

				$data['content_view'] = 'sort/editor_picks';
				$this->load->view('template/template',$data);
	}

	public function update_ed(){
					
					//$this->load->model('news_model');
					$lang = $this->lang->language;
					
					$json = $this->input->post('json');
					//$json = '[{"id":"1-3"},{"id":"1-1"}]';
					//Debug($json);
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
								$this->Api_model->setorder_editor_picks($hlid, $type, $update_data);
								$num++;
						}

					//**************Log activity
					$action = 4;
					$log_activity = array(
								"admin_id" => $this->session->userdata('admin_id'),
								"ref_id" => 0,
								"from_ip" => $_SERVER['REMOTE_ADDR'],
								"ref_type" => 0,
								"ref_title" => $lang['order'].' '.$lang['editor_choice'],
								"action" => $action,
								"create_date" => date('Y-m-d H:i:s'),
								"status" => '1',
								"lang" => $this->lang->line('lang')
					);
					$this->admin_log_activity_model->store($log_activity);
					//**************Log activity
					if(count($obj) > 1)
						echo $lang['update'].' '.$lang['success'].'.';
					else
						echo $lang['update'].' '.$lang['notsuccess'].'.';
	}
}

