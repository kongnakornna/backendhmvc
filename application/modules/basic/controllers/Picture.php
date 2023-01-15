<?php

class Picture extends MY_Controller {

    public function __construct()    {
        parent::__construct();        
        $this->load->model('Picture_model');
		$breadcrumb = array();
        
        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }
    }

	public function index(){
		
		$ListSelect = $this->Api_model->user_menu($this->session->userdata('admin_type'));		
		$language = $this->lang->language;

		//$breadcrumb[] = '<a href="'.base_url('tags').'">'.$language['tags'].'</a>';
		$breadcrumb[] = $language['picture'];
		//"admin_menu" => $this->menufactory->getMenu(),

		$data = array(				
					//"picture_list" => $this->Picture_model->get_content_all(),
					//"content_view" => 'picture/picture',
					"ListSelect" => $ListSelect,
					"breadcrumb" => $breadcrumb,
		);
		$this->load->view('template/template',$data);
	}
	
	public function remove_img($picture_id = 0){
			
			$src = $this->input->post('img');
			//echo "picture_id = $picture_id";
			//echo "<br>src = $src";
			//echo $picture_id .' '. $src;

			unlink($src);
			if($this->Picture_model->delete_picture_admin($picture_id))
				echo 'Yes';
			else
				echo 'No';

			/*$obj_data['status'] = 0;
			if($this->Picture_model->store($picture_id, $obj_data))
				echo 'Yes';
			else
				echo 'No';*/

	}

	public function watermark_img(){
			
			$this->load->helper('img');

			$src = $this->input->get('img');
			$folder = $this->input->get('folder');
			//echo "picture_id = $picture_id";

			echo "<br>src = $src";
			echo "<br>folder = $folder";
			watermark($src , $folder, 1);

	}
	
	/*public function crop_img(){
			ob_start();
			$display_cor = array();
			$layout = $this->input->get('t');
			$img = $this->input->get('img');
			$path = $this->input->get('folder');
			
			$display_cor['width'] = $w = $this->input->get('w');
			$display_cor['height'] = $h = $this->input->get('h');
			$display_cor['left'] = $x = $this->input->get('x1');
			$display_cor['top'] = $y = $this->input->get('y1');

			$upload_path = './uploads/thumb/';
			if(!is_dir($upload_path)) mkdir($upload_path, 0777);

			$upload_path = './uploads/thumb/'.$path;
			if(!is_dir($upload_path)) mkdir($upload_path, 0777);

			$upload_path2 = './uploads/thumb2/';
			if(!is_dir($upload_path2)) mkdir($upload_path2, 0777);

			$upload_path2 = './uploads/thumb2/'.$path;
			if(!is_dir($upload_path2)) mkdir($upload_path2, 0777);
			
			if(!is_dir($upload_path)){
				$upload_path = './uploads/'.$path;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
			}
				
			$new_name = $img;
			
			//thumb 1
			$t_width = 300;	// Maximum thumbnail width
			$t_height = 169;	// Maximum thumbnail height
			
			$ratio = ($t_width/$w);
			$nw = ceil($w * $ratio);
			$nh = ceil($h * $ratio);

			$file_source = "./uploads/news/".$path."/".$img;
			if(!file_exists($file_source)){
				$file_source = "./uploads/tmp/".$path."/".$img;
				if(!file_exists($file_source)){
						$file_source = "./uploads/".$path."/".$img;
				}
			}

			$nimg = imagecreatetruecolor($nw,$nh);
			$im_src = imagecreatefromjpeg($file_source);
			imagecopyresampled($nimg,$im_src,0,0,$x,$y,$nw,$nh,$w,$h);
			imagejpeg($nimg, $upload_path."/".$new_name,90);

			$out = ob_get_clean();
			$out = strtolower($out);
	}*/

	public function make_highlight($t_width = 940, $t_height = 530){

			ob_start();
			$display_cor = array();
			$layout = $this->input->get('t');
			$img = $this->input->get('img');
			$path = $this->input->get('folder');
			
			//$type = $this->input->get('type');
			$type = 'highlight';
			
			$display_cor['width'] = $w = $this->input->get('w');
			$display_cor['height'] = $h = $this->input->get('h');
			$display_cor['left'] = $x = $this->input->get('x1');
			$display_cor['top'] = $y = $this->input->get('y1');

			$upload_path = './uploads/tmp2/';
			if(!is_dir($upload_path)) mkdir($upload_path, 0777);

			$upload_path = './uploads/tmp2/'.$path;
			if(!is_dir($upload_path)) mkdir($upload_path, 0777);
			
			$upload_path = './uploads/'.$type;
			if(!is_dir($upload_path)){ mkdir($upload_path, 0777); }

			$upload_path = './uploads/'.$type.'/'.$path;
			if(!is_dir($upload_path)){ mkdir($upload_path, 0777); }
	
			if(isset($img)){

					extract($_GET);
					//$file_source = "./uploads/".$type."/".$path."/".$img;
					$file_source = "./uploads/tmp2/".$path."/".$img;
					if(!file_exists($file_source)){
						$file_source = "./uploads/tmp/".$path."/".$img;
						if(!file_exists($file_source)){
								$file_source = "./uploads/".$path."/".$img;
						}
					}		
					//echo "<br>$file_source";
					$new_name = $img;

					$ratio = ($t_width/$w);

					$nw = ceil($w * $ratio);
					$nh = ceil($h * $ratio);

					$nimg = imagecreatetruecolor($nw,$nh);
					$im_src = imagecreatefromjpeg($file_source);

					imagecopyresampled($nimg,$im_src,0,0,$x,$y,$nw,$nh,$w,$h);
					imagejpeg($nimg, $upload_path."/".$new_name,90);

					//echo "imagecopyresampled($nimg,$im_src,0,0, x=$x1, y=$y1, w=$nw, h=$nh, w=$w, h=$h);<br>";

					//Debug($display_cor);
					//echo "<br>";
					//$imagesize = getimagesize(base_url($upload_path)."/".$new_name."?".time());
					//Debug($imagesize);
					echo Debug("<img src='" . base_url($upload_path)."/".$new_name."?".time() . "' /><br>".$t_width."x".$t_height);
			}
			exit;
	}

	public function make_img($t_width = 620, $t_height = 349){

			ob_start();
			$display_cor = array();
			$layout = $this->input->get('t');
			$img = $this->input->get('img');
			$path = $this->input->get('folder');
			$type = $this->input->get('type');

			/*if(isset($this->input->get('mod')))
				$mod = $this->input->get('mod');
			else
				$mod = '';*/
			
			$display_cor['width'] = $w = $this->input->get('w');
			$display_cor['height'] = $h = $this->input->get('h');
			$display_cor['left'] = $x = $this->input->get('x1');
			$display_cor['top'] = $y = $this->input->get('y1');
			//$display_txt = '';

			if((($type == 'news') || ($type == 'column') || ($type == 'gallery') || ($type == 'vdo') || ($type == 'dara')) && ($w <= 620)){
				//$display_txt = $type."<br>";
				if($this->input->get('w') > 0) $t_width = $this->input->get('w');
				if($this->input->get('h') > 0) $t_height = $this->input->get('h');
			}
			$display_txt = "<br><b>".$type."</b> ".$t_width."x".$t_height."<br>";
			//die();

			$upload_path = './uploads/tmp2/';
			if(!is_dir($upload_path)) mkdir($upload_path, 0777);

			$upload_path = './uploads/tmp2/'.$path;
			if(!is_dir($upload_path)) mkdir($upload_path, 0777);
			
			$upload_path = './uploads/'.$type;
			if(!is_dir($upload_path)){ mkdir($upload_path, 0777); }

			$upload_path = './uploads/'.$type.'/'.$path;
			if(!is_dir($upload_path)){ mkdir($upload_path, 0777); }
	
			if(isset($img)){

					extract($_GET);
					//$file_source = "./uploads/".$type."/".$path."/".$img;
					$file_source = "./uploads/tmp2/".$path."/".$img;
					if(!file_exists($file_source)){
						$file_source = "./uploads/tmp/".$path."/".$img;
						if(!file_exists($file_source)){
								$file_source = "./uploads/".$path."/".$img;
						}
					}		
					//echo "<br>$file_source";
					$new_name = $img;

					$ratio = ($t_width/$w);

					$nw = ceil($w * $ratio);
					$nh = ceil($h * $ratio);

					$nimg = imagecreatetruecolor($nw,$nh);
					$im_src = imagecreatefromjpeg($file_source);

					imagecopyresampled($nimg,$im_src,0,0,$x,$y,$nw,$nh,$w,$h);
					imagejpeg($nimg, $upload_path."/".$new_name,90);

					//echo "imagecopyresampled($nimg,$im_src,0,0, x=$x1, y=$y1, w=$nw, h=$nh, w=$w, h=$h);<br>";

					//Debug($display_cor);
					//echo "<br>";
					//$imagesize = getimagesize(base_url($upload_path)."/".$new_name."?".time());
					//Debug($imagesize);
					echo Debug("<img src='" . base_url($upload_path)."/".$new_name."?".time() . "' />$display_txt");
			}
			exit;
	}

	public function make_thumb($t_width = 300, $t_height = 169){

			//ob_start();
			$display_cor = array();

			$get_input = $this->input->get();

			$layout = $get_input['t'];
			$img = $get_input['img'];
			$path = $get_input['folder'];

			//Debug($get_input);
			//die();		
			$display_cor['width'] = $w = $this->input->get('w');
			$display_cor['height'] = $h = $this->input->get('h');
			$display_cor['left'] = $x = $this->input->get('x1');
			$display_cor['top'] = $y = $this->input->get('y1');

			if(isset($get_input['t_width'])) $t_width = $get_input['t_width'];
			if(isset($get_input['t_height'])) $t_height = $get_input['t_height'];

			$mod = $this->input->get('mod');

			$folder_img = explode("/", $path);
			$tmp_folder = '';
			if(isset($folder_img)){
				foreach($folder_img as $val){
					$tmp_folder .= $val."/";
					$this->Picture_model->chkfolder_exists($tmp_folder);
				}
			}

			if($mod == "thumb2"){
					
					$t_width = 310;
					$t_height = 175;


					$upload_path2 = './uploads/thumb2';
					if(!is_dir($upload_path2)) mkdir($upload_path2, 0777);

					$upload_path2 = './uploads/thumb2/'.$path;
					if(!is_dir($upload_path2)) mkdir($upload_path2, 0777);

					$upload_path = $upload_path2;

			}else if($mod == "thumb4"){
					
					$t_width = 89;
					$t_height = 50;

					$upload_path2 = './uploads/thumb4';
					if(!is_dir($upload_path2)) mkdir($upload_path2, 0777);

					$upload_path2 = './uploads/thumb4/'.$path;
					if(!is_dir($upload_path2)) mkdir($upload_path2, 0777);

					$upload_path = $upload_path2;

			}else if($mod == "menu"){
					
					$t_width = 212;
					$t_height = 120;

					$upload_path2 = './uploads/menu/';
					if(!is_dir($upload_path2)) mkdir($upload_path2, 0777);

					$upload_path2 = './uploads/menu/'.$path;
					if(!is_dir($upload_path2)) mkdir($upload_path2, 0777);

					$upload_path = $upload_path2;

			}else{

					$upload_path = './uploads/thumb/';
					if(!is_dir($upload_path)) mkdir($upload_path, 0777);

					$upload_path = './uploads/thumb/'.$path;
					if(!is_dir($upload_path)) mkdir($upload_path, 0777);

					if(isset($get_input['t_width']) && isset($get_input['t_height'])){

							if($t_width == $t_height){ //thumb3 = 80x80

									$upload_path3 = './uploads/thumb3/';
									if(!is_dir($upload_path3)) mkdir($upload_path3, 0777);

									$upload_path3 = './uploads/thumb3/'.$path;
									if(!is_dir($upload_path3)) mkdir($upload_path3, 0777);
									
									$upload_path = $upload_path3;
							}
					}
			}

			//echo "upload_path=$upload_path<br>";
			//echo "upload_path3=$upload_path3<br>";

			if(isset($img)){

					extract($_GET);
					//$file_source = "./uploads/news/".$path."/".$img;
					//if(!file_exists($file_source)){
						$file_source = "./uploads/tmp/".$path."/".$img;
						if(!file_exists($file_source)){
								$file_source = "./uploads/".$path."/".$img;
						}
					//}		
					//echo "<br>$file_source";
					$new_name = $img;

					$ratio = ($t_width/$w);
					$nw = ceil($w * $ratio);
					$nh = ceil($h * $ratio);

					$nimg = imagecreatetruecolor($nw,$nh);
					$im_src = imagecreatefromjpeg($file_source);

					imagecopyresampled($nimg,$im_src,0,0,$x,$y,$nw,$nh,$w,$h);
					imagejpeg($nimg, $upload_path."/".$new_name,90);

					//echo "imagecopyresampled($nimg,$im_src,0,0, x=$x1, y=$y1, w=$nw, h=$nh, w=$w, h=$h);<br>";
					//Debug($display_cor);
					//echo "<br>";
					//$imagesize = getimagesize(base_url($upload_path)."/".$new_name."?".time());
					//Debug($imagesize);
					$display_txt = "<br><b>".$mod."</b> ".$t_width."x".$t_height."<br>";

					echo Debug("<img src='" . base_url($upload_path)."/".$new_name."?".time() . "' />$display_txt");
			}
			exit;
	}

	public function make_thumb2($t_width = 310, $t_height = 175){

			ob_start();
			$display_cor = array();
			
			$layout = $this->input->get('t');
			$img = $this->input->get('img');
			$path = $this->input->get('folder');
			
			$display_cor['width'] = $w = $this->input->get('w');
			$display_cor['height'] = $h = $this->input->get('h');
			$display_cor['left'] = $x = $this->input->get('x1');
			$display_cor['top'] = $y = $this->input->get('y1');
			
			$upload_path2 = './uploads/thumb2/';
			if(!is_dir($upload_path2)) mkdir($upload_path2, 0777);

			$upload_path2 = './uploads/thumb2/'.$path;
			if(!is_dir($upload_path2)) mkdir($upload_path2, 0777);

			//thumb 2
			//$t_width = 310;	// Maximum thumbnail width
			//$t_height = 175;	// Maximum thumbnail height

			if(isset($img)){

					//extract($_GET);
					//$file_source = "./uploads/news/".$path."/".$img;
					//if(!file_exists($file_source)){
						$file_source = "./uploads/tmp/".$path."/".$img;
						if(!file_exists($file_source)){
								$file_source = "./uploads/".$path."/".$img;
						}
					//}		
					$new_name = $img;

					$ratio = ($t_width/$w);

					$nw = ceil($w * $ratio);
					$nh = ceil($h * $ratio);

					$nimg = imagecreatetruecolor($nw,$nh);
					$im_src = imagecreatefromjpeg($file_source);

					imagecopyresampled($nimg,$im_src,0,0,$x,$y,$nw,$nh,$w,$h);
					imagejpeg($nimg, $upload_path2."/".$new_name,90);

					$display_txt = "<br><b>".$mod."</b> ".$t_width."x".$t_height."<br>";

					echo Debug("<img src='" . base_url($upload_path2)."/".$new_name."?".time() . "' />".$display_txt);
			}
			exit;
	}

}