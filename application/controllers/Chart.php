<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Chart extends MY_Controller {

    public function __construct()    {
        parent::__construct();

        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }else if($this->session->userdata['admin_type'] > 3){
            redirect(base_url());
        }
    }

	public function index(){
		$this->load->library('session');
        $this->load->driver('session');
			$this->load->model('Report_model');
			$this->load->model('Chart_model');

			$admin_id = $this->session->userdata('admin_id');
			$admin_type = $this->session->userdata('admin_type');
			$ListSelect = $this->Api_model->user_menu($admin_type);
			$language = $this->lang->language;
			$type = 'all';

			if($this->uri->segment(2)){
					$mod = $this->uri->segment(2);
			}else
					$mod = '';

			switch($type){
					case 'news':$news_list = $this->Report_model->news($mod);break;
					case 'column':$column_list = $this->Report_model->column($mod);break;
					case 'gallery':$gallery_list = $this->Report_model->gallery($mod);break;
					case 'vdo':$vdo_list = $this->Report_model->vdo($mod);break;
					default :
							$news_list = $this->Report_model->news($mod);
							$column_list = $this->Report_model->column($mod);
							$gallery_list = $this->Report_model->gallery($mod);
							$vdo_list = $this->Report_model->vdo($mod);
					break;
			}

			$report = new stdClass();
			$next = $total = $count_fb = $count_tw = 0;

			$webtitle = 'Chart - '.$language['titleweb'];

			switch($type){
					case 'news':$news_report = json_decode($news_list);break;
					case 'column':$column_report = json_decode($column_list);break;
					case 'gallery':$gallery_report = json_decode($gallery_list);break;
					case 'vdo':$vdo_report = json_decode($vdo_list);break;
					default :
							$news_report = json_decode($news_list);
							$column_report = json_decode($column_list);
							$gallery_report = json_decode($gallery_list);
							//$vdo_report = json_decode($vdo_list);
					break;
			}

			//Debug($vdo_report);
			$subcategory_name = '';
			if(isset($news_report))
			if($news_report->header->resultcode == 200)
				for($i = 0;$i < count($news_report->body);$i++){					
						foreach($news_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}

						if(trim($category_name) == '')
							$report->name[$next] = $subcategory_name;
						else if(trim($subcategory_name) == '')
							$report->name[$next] = $category_name;
						else
							$report->name[$next] = $category_name.'=>'.$subcategory_name;

						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'news';
						$total += $sum_view;
						//echo "total = $total<br>";
						$next++;
				}
			
			$subcategory_name = '';
			if(isset($vdo_report))
			if($vdo_report->header->resultcode == 200)
				for($i = 0;$i < count($vdo_report->body);$i++){					
						foreach($vdo_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						if(trim($category_name) == '')
							$report->name[$next] = $subcategory_name;
						else
							$report->name[$next] = $category_name.'=>'.$subcategory_name;

						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'vdo';
						$total += $sum_view;
						//echo "total = $total<br>";
						$next++;
				}
			
			if(isset($gallery_report))
			if($gallery_report->header->resultcode == 200)
				for($i = 0;$i < count($gallery_report->body);$i++){
						foreach($gallery_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "gallery_type_name") $gallery_type_name = $val;
								//if($key == "gallery_type_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						$report->name[$next] = $language['gallery']."=>".$gallery_type_name;
						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'gallery';
						$total += $sum_view;
						//echo "total = $total<br>";
						$next++;
				}

			$subcategory_name = '';
			if(isset($column_report))
			if($column_report->header->resultcode == 200)
				for($i = 0;$i < count($column_report->body);$i++){					
						foreach($column_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						if(trim($category_name) == '')
							$report->name[$next] = $subcategory_name;
						else if(trim($subcategory_name) == '')
							$report->name[$next] = $category_name;
						else
							$report->name[$next] = $category_name.'=>'.$subcategory_name;

						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'column';
						$total += $sum_view;
						//echo "total = $total<br>";
						$next++;
				}


			for($i = 0;$i < count($report->name);$i++){
					$report->percent[$i] = round(($report->sum_view[$i]/$total)*100, 2);
					if($i > 4) $report->color[$i] = "#".rand(111111,999999);
			}

			$report->color[0] = "#68BC31";
			$report->color[1] = "#2091CF";
			$report->color[2] = "#AF4E96";
			$report->color[3] = "#DA5430";
			$report->color[4] = "#FEE074";

			//echo "total = $total";
			//Debug($news_report);
			//Debug($column_report);
			//Debug($report);
			//die();

			/**************fill data pie****************/
			$display_pie = '';
			for($i = 0;$i < count($report->name);$i++){
				$display_pie .= '{ label: "'.$report->name[$i].'",  data: '.$report->percent[$i].', color: "'.$report->color[$i].'"},';
			}

			/**************morris****************/
			$mod = '';

			$line1 = new stdClass();
			$lineall = new stdClass();
			$report_morris = new stdClass();
			$next = $total = $count_fb = $count_tw = 0;

			$news_report = json_decode($this->Report_model->news());
			$column_report = json_decode($this->Report_model->column());
			$gallery_report = json_decode($this->Report_model->gallery());
			$vdo_report = json_decode($this->Report_model->vdo());

			$daily_report = json_decode($this->Report_model->daily());
			//Debug($daily_report);

			$subcategory_name = '';
			if(isset($news_report))
			if($news_report->header->resultcode == 200)
				for($i = 0;$i < count($news_report->body);$i++){					
						foreach($news_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						/*if(trim($category_name) == '')
							$report_morris->name[$next] = $subcategory_name;
						else if(trim($subcategory_name) == '')
							$report_morris->name[$next] = $category_name;
						else
							$report_morris->name[$next] = $category_name.'=>'.$subcategory_name;

						$report_morris->sum_view[$next] = $sum_view;
						$report_morris->icon[$next] = 'news';*/
						$total += $sum_view;
						//echo "total = $total<br>";
				}
				
			$report_morris->name[$next] = 'ข่าว';
			$report_morris->sum_view[$next] = $total;
			$report_morris->icon[$next] = 'news';
			$next++;

			//Debug($news_report);
			//Debug($report_morris);
			
			$total = 0;
			$subcategory_name = '';
			if(isset($vdo_report))
			if($vdo_report->header->resultcode == 200)
				for($i = 0;$i < count($vdo_report->body);$i++){					
						foreach($vdo_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						/*if(trim($category_name) == '')
							$report_morris->name[$next] = $subcategory_name;
						else
							$report_morris->name[$next] = $category_name.'=>'.$subcategory_name;

						$report_morris->sum_view[$next] = $sum_view;
						$report_morris->icon[$next] = 'vdo';*/
						$total += $sum_view;
						//echo "total = $total<br>";
						//$next++;
				}

			$report_morris->name[$next] = 'คลิปวิดีโอ';
			$report_morris->sum_view[$next] = $total;
			$report_morris->icon[$next] = 'vdo';
			$next++;

			//Debug($vdo_report);
			//Debug($report_morris);
			
			$total = 0;
			if(isset($gallery_report))
			if($gallery_report->header->resultcode == 200)
				for($i = 0;$i < count($gallery_report->body);$i++){
						foreach($gallery_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "gallery_type_name") $gallery_type_name = $val;
								//if($key == "gallery_type_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						/*$report_morris->name[$next] = $language['gallery']."=>".$gallery_type_name;
						$report_morris->sum_view[$next] = $sum_view;
						$report_morris->icon[$next] = 'gallery';*/
						$total += $sum_view;
						//echo "total = $total<br>";
						//$next++;
				}
				
			$report_morris->name[$next] = 'แกเลอรี่';
			$report_morris->sum_view[$next] = $total;
			$report_morris->icon[$next] = 'gallery';
			$next++;

			//Debug($gallery_report);
			//Debug($report_morris);

			$total = 0;
			$subcategory_name = '';
			if(isset($column_report))
			if($column_report->header->resultcode == 200)
				for($i = 0;$i < count($column_report->body);$i++){					
						foreach($column_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						/*if(trim($category_name) == '')
							$report_morris->name[$next] = $subcategory_name;
						else if(trim($subcategory_name) == '')
							$report_morris->name[$next] = $category_name;
						else
							$report_morris->name[$next] = $category_name.'=>'.$subcategory_name;

						$report_morris->sum_view[$next] = $sum_view;
						$report_morris->icon[$next] = 'column';*/
						$total += $sum_view;
						//echo "total = $total<br>";
						//$next++;
				}
			$report_morris->name[$next] = 'บทความ';
			$report_morris->sum_view[$next] = $total;
			$report_morris->icon[$next] = 'column';
			$next++;

			//Debug($gallery_report);
			//Debug($report_morris);

			/**************fill data pie****************/
			//Debug($daily_report);

			$n = 0;
			$next = 0;
			$tmp = '';
			if(isset($daily_report))
			if($daily_report->header->resultcode == 200)
				for($i = 0;$i < count($daily_report->body);$i++){
						foreach($daily_report->body[$i] as $key => $val){
								if($key == "view_date") $view_date = $val;
								if($key == "date") $date = $val;
								if($key == "device") $device = $val;
						}

						$line1->view_date[$n] = $view_date;
						$line1->date[$n] = $date;
						$line1->device[$n] = $device;
						$n++;

						//ALL total
						if($date != $tmp){
							$lineall->view_date[$next] = $view_date;
							$lineall->date[$next] = $date;
							$lineall->device[$next] = 'all device';
							$tmp = $date;
							$next++;
						}else{
							$lineall->view_date[$next - 1] += $view_date;
							//$line1->view_date[$next - 1] = $view_date;
							//$line1->device[$next] = $device;
						}

						//$total += $sum_view;
						//echo "total = $total<br>";
				}

			/*$line1->name[$next] = 'บทความ';
			$line1->sum_view[$next] = $total;
			$line1->icon[$next] = 'column';*/

			if(!$lineall->view_date) $lineall->view_date = null;

			for($i = 0;$i < count($lineall->view_date);$i++){
					//Debug($lineall->date[$i]);
					$lineall->all[$i] = $lineall->view_date[$i];
					//Debug($lineall->view_date[$i]);
					for($j=0;$j<count($line1->view_date);$j++){
							if($lineall->date[$i] == $line1->date[$j]){
									//Debug($line1->date[$j]);
									//Debug($line1->view_date[$j]);
									//Debug($line1->device[$j]);
									if($line1->device[$j] == 'desktop'){
											$lineall->desktop[$i] = $line1->view_date[$j];
									}else if($line1->device[$j] == 'mobile'){
											$lineall->mobile[$i] = $line1->view_date[$j];
									}else if($line1->device[$j] == 'tablet'){
											$lineall->tablet[$i] = $line1->view_date[$j];
									}else{
											$lineall->other[$i] = $line1->view_date[$j];									
									}
									
							}
					}
					//echo "<hr>";
					//Debug($lineall->view_date[$i]);
			}
			//Debug($daily_report);
			//Debug($line1);
			//Debug($lineall);
			//die();

			$webtitle = 'Chart - '.$language['titleweb'];

			$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();
			$notification_birthday = $this->Api_model->notification_birthday();
			$notification_news = $this->Api_model->notification_msg('news');
			if($notification_news[0]->count_approve == 1) $notification_news_list = $this->Api_model->notification_msg('news', 0);
			$notification_column = $this->Api_model->notification_msg('column');
			if($notification_column[0]->count_approve == 1) $notification_column_list = $this->Api_model->notification_msg('column', 0);
			$notification_gallery = $this->Api_model->notification_msg('gallery');
			if($notification_gallery[0]->count_approve == 1) $notification_gallery_list = $this->Api_model->notification_msg('gallery', 0);
			$notification_vdo = $this->Api_model->notification_msg('vdo');
			if($notification_vdo[0]->count_approve == 1) $notification_vdo_list = $this->Api_model->notification_msg('vdo', 0);
			$notification_dara = $this->Api_model->notification_msg('dara');
			if($notification_dara[0]->count_approve == 1) $notification_dara_list = $this->Api_model->notification_msg('dara', 0);

			$data = array(
					//"news_report" => json_decode($news_report),
					//"column_report" => json_decode($column_report),
					"admin_menu" => $this->menufactory->getMenu(),

					"report" => $report,
					"count_fb" => $count_fb,
					"count_tw" => $count_tw,
					"display_pie" => $display_pie,

					"report_morris" => $report_morris,
					"lineall" => $lineall,

					"notification_birthday" => $notification_birthday,
					"notification_news" => $notification_news[0]->count_approve,
					"notification_column" => $notification_column[0]->count_approve,
					"notification_gallery" => $notification_gallery[0]->count_approve,
					"notification_vdo" => $notification_vdo[0]->count_approve,
					"notification_dara" => $notification_dara[0]->count_approve,
					"notification_news_list" => $notification_news_list,
					"notification_column_list" => $notification_column_list,
					"notification_gallery_list" => $notification_gallery_list,
					"notification_vdo_list" => $notification_vdo_list,
					"notification_dara_list" => $notification_dara_list,
					"content_view" => 'chart/index',
					"webtitle" => $webtitle,
					"ListSelect" => $ListSelect
			);

			$this->load->view('template',$data);
	}

	function morris(){

			$this->load->model('Report_model');
			$this->load->model('Chart_model');

			$admin_id = $this->session->userdata('admin_id');
			$admin_type = $this->session->userdata('admin_type');
			$ListSelect = $this->Api_model->user_menu($admin_type);
			$language = $this->lang->language;

			$type = 'all';
			$mod = '';

			$line1 = new stdClass();
			$lineall = new stdClass();
			$report = new stdClass();
			$next = $total = $count_fb = $count_tw = 0;

			$news_report = json_decode($this->Report_model->news());
			$column_report = json_decode($this->Report_model->column());
			$gallery_report = json_decode($this->Report_model->gallery());
			$vdo_report = json_decode($this->Report_model->vdo());

			$daily_report = json_decode($this->Report_model->daily());
			//Debug($daily_report);

			$subcategory_name = '';
			if(isset($news_report))
			if($news_report->header->resultcode == 200)
				for($i = 0;$i < count($news_report->body);$i++){					
						foreach($news_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						/*if(trim($category_name) == '')
							$report->name[$next] = $subcategory_name;
						else if(trim($subcategory_name) == '')
							$report->name[$next] = $category_name;
						else
							$report->name[$next] = $category_name.'=>'.$subcategory_name;

						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'news';*/
						$total += $sum_view;
						//echo "total = $total<br>";
				}
				
			$report->name[$next] = 'ข่าว';
			$report->sum_view[$next] = $total;
			$report->icon[$next] = 'news';
			$next++;

			//Debug($news_report);
			//Debug($report);
			
			$total = 0;
			$subcategory_name = '';
			if(isset($vdo_report))
			if($vdo_report->header->resultcode == 200)
				for($i = 0;$i < count($vdo_report->body);$i++){					
						foreach($vdo_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						/*if(trim($category_name) == '')
							$report->name[$next] = $subcategory_name;
						else
							$report->name[$next] = $category_name.'=>'.$subcategory_name;

						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'vdo';*/
						$total += $sum_view;
						//echo "total = $total<br>";
						//$next++;
				}

			$report->name[$next] = 'คลิปวิดีโอ';
			$report->sum_view[$next] = $total;
			$report->icon[$next] = 'vdo';
			$next++;

			//Debug($vdo_report);
			//Debug($report);
			
			$total = 0;
			if(isset($gallery_report))
			if($gallery_report->header->resultcode == 200)
				for($i = 0;$i < count($gallery_report->body);$i++){
						foreach($gallery_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "gallery_type_name") $gallery_type_name = $val;
								//if($key == "gallery_type_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						/*$report->name[$next] = $language['gallery']."=>".$gallery_type_name;
						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'gallery';*/
						$total += $sum_view;
						//echo "total = $total<br>";
						//$next++;
				}
				
			$report->name[$next] = 'แกเลอรี่';
			$report->sum_view[$next] = $total;
			$report->icon[$next] = 'gallery';
			$next++;

			//Debug($gallery_report);
			//Debug($report);

			$total = 0;
			$subcategory_name = '';
			if(isset($column_report))
			if($column_report->header->resultcode == 200)
				for($i = 0;$i < count($column_report->body);$i++){					
						foreach($column_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						/*if(trim($category_name) == '')
							$report->name[$next] = $subcategory_name;
						else if(trim($subcategory_name) == '')
							$report->name[$next] = $category_name;
						else
							$report->name[$next] = $category_name.'=>'.$subcategory_name;

						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'column';*/
						$total += $sum_view;
						//echo "total = $total<br>";
						//$next++;
				}
			$report->name[$next] = 'บทความ';
			$report->sum_view[$next] = $total;
			$report->icon[$next] = 'column';
			$next++;

			//Debug($gallery_report);
			//Debug($report);

			/**************fill data pie****************/
			//Debug($daily_report);

			$n = 0;
			$next = 0;
			$tmp = '';
			if(isset($daily_report))
			if($daily_report->header->resultcode == 200)
				for($i = 0;$i < count($daily_report->body);$i++){
						foreach($daily_report->body[$i] as $key => $val){
								if($key == "view_date") $view_date = $val;
								if($key == "date") $date = $val;
								if($key == "device") $device = $val;
						}

						$line1->view_date[$n] = $view_date;
						$line1->date[$n] = $date;
						$line1->device[$n] = $device;
						$n++;

						//ALL total
						if($date != $tmp){
							$lineall->view_date[$next] = $view_date;
							$lineall->date[$next] = $date;
							$lineall->device[$next] = 'all device';
							$tmp = $date;
							$next++;
						}else{
							$lineall->view_date[$next - 1] += $view_date;
							//$line1->view_date[$next - 1] = $view_date;
							//$line1->device[$next] = $device;
						}

						//$total += $sum_view;
						//echo "total = $total<br>";
				}

			/*$line1->name[$next] = 'บทความ';
			$line1->sum_view[$next] = $total;
			$line1->icon[$next] = 'column';*/

			for($i = 0;$i < count($lineall->view_date);$i++){
					//Debug($lineall->date[$i]);
					$lineall->all[$i] = $lineall->view_date[$i];
					//Debug($lineall->view_date[$i]);
					for($j=0;$j<count($line1->view_date);$j++){
							if($lineall->date[$i] == $line1->date[$j]){
									//Debug($line1->date[$j]);
									//Debug($line1->view_date[$j]);
									//Debug($line1->device[$j]);
									if($line1->device[$j] == 'desktop'){
											$lineall->desktop[$i] = $line1->view_date[$j];
									}else if($line1->device[$j] == 'mobile'){
											$lineall->mobile[$i] = $line1->view_date[$j];
									}else if($line1->device[$j] == 'tablet'){
											$lineall->tablet[$i] = $line1->view_date[$j];
									}else{
											$lineall->other[$i] = $line1->view_date[$j];									
									}
									
							}
					}
					//echo "<hr>";
					//Debug($lineall->view_date[$i]);
			}
			//Debug($daily_report);
			//Debug($line1);
			//Debug($lineall);
			//die();

			$webtitle = 'Chart - '.$language['titleweb'];

			$data = array(
				"admin_menu" => $this->menufactory->getMenu(),
				"report" => $report,
				"lineall" => $lineall,
				"content_view" => 'chart/morris',
				"webtitle" => $webtitle,
				"ListSelect" => $ListSelect
			);

			$this->load->view('template',$data);
	}

	public function cat(){
			
			$this->load->model('Report_model');
			$this->load->model('Chart_model');

			$admin_id = $this->session->userdata('admin_id');
			$admin_type = $this->session->userdata('admin_type');
			$ListSelect = $this->Api_model->user_menu($admin_type);
			$language = $this->lang->language;
			$type = 'all';

			if($this->uri->segment(2)){
				$mod = $this->uri->segment(2);
			}else
				$mod = '';

			switch($type){
					case 'news':$news_list = $this->Report_model->news($mod);break;
					case 'column':$column_list = $this->Report_model->column($mod);break;
					case 'gallery':$gallery_list = $this->Report_model->gallery($mod);break;
					case 'vdo':$vdo_list = $this->Report_model->vdo($mod);break;
					default :
							$news_list = $this->Report_model->news($mod);
							$column_list = $this->Report_model->column($mod);
							$gallery_list = $this->Report_model->gallery($mod);
							$vdo_list = $this->Report_model->vdo($mod);
					break;
			}

			$report = new stdClass();
			$next = $total = $count_fb = $count_tw = 0;
			$webtitle = 'Chart - '.$language['titleweb'];

			switch($type){
					case 'news':$news_report = json_decode($news_list);break;
					case 'column':$column_report = json_decode($column_list);break;
					case 'gallery':$gallery_report = json_decode($gallery_list);break;
					case 'vdo':$vdo_report = json_decode($vdo_list);break;
					default :
							$news_report = json_decode($news_list);
							$column_report = json_decode($column_list);
							$gallery_report = json_decode($gallery_list);
							//$vdo_report = json_decode($vdo_list);
					break;
			}

			//Debug($vdo_report);
			$subcategory_name = '';
			if(isset($news_report))
			if($news_report->header->resultcode == 200)
				for($i = 0;$i < count($news_report->body);$i++){					
						foreach($news_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}

						if(trim($category_name) == '')
							$report->name[$next] = $subcategory_name;
						else if(trim($subcategory_name) == '')
							$report->name[$next] = $category_name;
						else
							$report->name[$next] = $category_name.'=>'.$subcategory_name;

						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'news';
						$total += $sum_view;
						//echo "total = $total<br>";
						$next++;
				}
			
			$subcategory_name = '';
			if(isset($vdo_report))
			if($vdo_report->header->resultcode == 200)
				for($i = 0;$i < count($vdo_report->body);$i++){					
						foreach($vdo_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						if(trim($category_name) == '')
							$report->name[$next] = $subcategory_name;
						else
							$report->name[$next] = $category_name.'=>'.$subcategory_name;

						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'vdo';
						$total += $sum_view;
						//echo "total = $total<br>";
						$next++;
				}
			
			if(isset($gallery_report))
			if($gallery_report->header->resultcode == 200)
				for($i = 0;$i < count($gallery_report->body);$i++){
						foreach($gallery_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "gallery_type_name") $gallery_type_name = $val;
								//if($key == "gallery_type_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						$report->name[$next] = $language['gallery']."=>".$gallery_type_name;
						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'gallery';
						$total += $sum_view;
						//echo "total = $total<br>";
						$next++;
				}

			$subcategory_name = '';
			if(isset($column_report))
			if($column_report->header->resultcode == 200)
				for($i = 0;$i < count($column_report->body);$i++){					
						foreach($column_report->body[$i] as $key => $val){
								//echo "$key => $val<br>";
								if($key == "category_name") $category_name = $val;
								if($key == "subcategory_name") $subcategory_name = $val;
								if($key == "category_id") $category_id = $val;
								if($key == "subcategory_id") $subcategory_id = $val;
								if($key == "sum_view") $sum_view = $val;
						}
						if(trim($category_name) == '')
							$report->name[$next] = $subcategory_name;
						else if(trim($subcategory_name) == '')
							$report->name[$next] = $category_name;
						else
							$report->name[$next] = $category_name.'=>'.$subcategory_name;

						$report->sum_view[$next] = $sum_view;
						$report->icon[$next] = 'column';
						$total += $sum_view;
						//echo "total = $total<br>";
						$next++;
				}


			for($i = 0;$i < count($report->name);$i++){
					$report->percent[$i] = round(($report->sum_view[$i]/$total)*100, 2);
					if($i > 4) $report->color[$i] = "#".rand(111111,999999);
			}

			$report->color[0] = "#68BC31";
			$report->color[1] = "#2091CF";
			$report->color[2] = "#AF4E96";
			$report->color[3] = "#DA5430";
			$report->color[4] = "#FEE074";

			//echo "total = $total";
			//Debug($news_report);
			//Debug($column_report);
			//Debug($report);
			//die();

			/**************fill data pie****************/
			$display_pie = '';
			for($i = 0;$i < count($report->name);$i++){
				$display_pie .= '{ label: "'.$report->name[$i].'",  data: '.$report->percent[$i].', color: "'.$report->color[$i].'"},';
			}

			$notification_news_list = $notification_column_list = $notification_gallery_list = $notification_vdo_list = $notification_dara_list = array();

			$notification_birthday = $this->Api_model->notification_birthday();

			$notification_news = $this->Api_model->notification_msg('news');
			if($notification_news[0]->count_approve == 1) $notification_news_list = $this->Api_model->notification_msg('news', 0);
			$notification_column = $this->Api_model->notification_msg('column');
			if($notification_column[0]->count_approve == 1) $notification_column_list = $this->Api_model->notification_msg('column', 0);
			$notification_gallery = $this->Api_model->notification_msg('gallery');
			if($notification_gallery[0]->count_approve == 1) $notification_gallery_list = $this->Api_model->notification_msg('gallery', 0);
			$notification_vdo = $this->Api_model->notification_msg('vdo');
			if($notification_vdo[0]->count_approve == 1) $notification_vdo_list = $this->Api_model->notification_msg('vdo', 0);
			$notification_dara = $this->Api_model->notification_msg('dara');
			if($notification_dara[0]->count_approve == 1) $notification_dara_list = $this->Api_model->notification_msg('dara', 0);

			$data = array(
					//"news_report" => json_decode($news_report),
					//"column_report" => json_decode($column_report),
					"admin_menu" => $this->menufactory->getMenu(),
					"report" => $report,
					"count_fb" => $count_fb,
					"count_tw" => $count_tw,
					"display_pie" => $display_pie,
					"notification_birthday" => $notification_birthday,
					"notification_news" => $notification_news[0]->count_approve,
					"notification_column" => $notification_column[0]->count_approve,
					"notification_gallery" => $notification_gallery[0]->count_approve,
					"notification_vdo" => $notification_vdo[0]->count_approve,
					"notification_dara" => $notification_dara[0]->count_approve,
					"notification_news_list" => $notification_news_list,
					"notification_column_list" => $notification_column_list,
					"notification_gallery_list" => $notification_gallery_list,
					"notification_vdo_list" => $notification_vdo_list,
					"notification_dara_list" => $notification_dara_list,
					"content_view" => 'chart/chart',
					"webtitle" => $webtitle,
					"ListSelect" => $ListSelect
			);
			$this->load->view('template',$data);
	}
	
}