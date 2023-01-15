<?php
class Galleryset_model extends CI_Model { 
    public function __construct(){
		parent::__construct();
    }

    public function get_status($id){
    
		$this->db->select('status');
    	$this->db->from('_gallery_set');
    	$this->db->where('galleryset_id', $id);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    }
        
    public function get_max_id(){

		$this->db->select('max(galleryset_id) as max_id');
		$this->db->from('_gallery_set');
		$query = $this->db->get();
		return $query->result_array(); 

    }

    public function get_gallery_set_by_id($galleryset_id = 0, $ref_id = 0, $ref_type = 1, $pic_status = 1){

		$this->db->select('*');
		$this->db->from('_gallery_set');

		if(($galleryset_id == 0) && ($ref_id == 0))
				$this->db->where('galleryset_id', 0);
		else{
			if($galleryset_id > 0) $this->db->where('galleryset_id', $galleryset_id);
			if($ref_id > 0) $this->db->where('ref_id', $ref_id);
		}

		$this->db->where('ref_type', $ref_type);
		$this->db->where('status', $pic_status);
		
		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_array();

    }

    public function get_gallery_set_by_ref_id($ref_id, $ref_type = 1, $pic_status = 1){

		$this->db->select('*');
		$this->db->from('_gallery_set');
		$this->db->where('ref_id', $ref_id);
		$this->db->where('ref_type', $ref_type);
		$this->db->where('status', $pic_status);

		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function get_gallery_set($ref_id=null, $ref_type = 1, $search_string=null, $order = 'create_date', $order_type='Asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];

		//$sql ="SELECT dp.*, dt.dara_type_name FROM sd_dara_profile as dp INNER JOIN sd_dara_type as dt ON (dp.dara_type_id=dt.dara_type_id_map AND dt.lang='".$language."')";
		//$this->db->query($sql);

		$this->db->select('*');
		$this->db->from('_gallery_set');
		//$this->db->where('lang', $language);
		$this->db->where('status !=', 2);

		if($ref_id != null && $ref_id > 0){
			$this->db->where('ref_type', $ref_type);
			$this->db->where('ref_id', $ref_id);
		}

		//if($id <= 0) $this->db->where('lang', $language);

		if($search_string){
			$this->db->like('title', $search_string);
			$this->db->like('caption', $search_string);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('create_date', $order_type);
		}

		$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();		
		//echo $this->db->last_query();
		//die();		
		return $query->result_array();
    }

    function count_gallery_set($id_map=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_gallery_set');
		if($id_map != null && $id_map != 0){
			$this->db->where('galleryset_id', $id_map);
		}
		if($search_string){
			$this->db->like('title', $search_string);
			$this->db->like('caption', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('create_date', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($id = 0, $data, $debug = 0){
			//echo "(dara_profile_id = $dara_profile_id)";
			if($id > 0){
					$this->db->where('galleryset_id', $id);
					$this->db->update('_gallery_set', $data);
					if($debug == 1) Debug($this->db->last_query());
					$report = array();
					//$report['error'] = $this->db->_error_number();
					//$report['message'] = $this->db->_error_message();
					if($report !== 0){
						
						//die();
						return true;
					}else{
						return false;
					}					
			}else{
					$insert = $this->db->insert('_gallery_set', $data);
					if($debug == 1) Debug($this->db->last_query());
					return $insert;
			}
	}

    public function add_batch(&$data = array(), $debug = 0){

					if($debug == 1) Debug($data);
					$insert = $this->db->insert_batch('_gallery_set', $data);
					if($debug == 1) Debug($this->db->last_query());

					return $insert;
    }

    /*function store($data){
		$insert = $this->db->insert('_dara_profile', $data);
	    return $insert;
	}
    function store_update($dara_profile_id, $data){
		$this->db->where('dara_profile_id', $dara_profile_id);
		$this->db->update('_dara_profile', $data);
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}*/

	function status_gallery_set($id, $enable = 1){

			$data['status'] = $enable;
			$this->db->where('galleryset_id', $id);
			$this->db->update('_gallery_set', $data);
			
			return $this->db->last_query();
			
			$report = array();
			//$report['error'] = $this->db->_error_number();
			//$report['message'] = $this->db->_error_message();
			if($report !== 0){
				return true;
			}else{
				return false;
			}					
	}	
	
    function delete_gallery_set($id){

			if($this->session->userdata('admin_type') > 2){

					$data = array(
							'status' => 2
					);
					$this->db->where('galleryset_id', $id);
					$this->db->update('_gallery_set', $data);
					
					$report = array();
					//$report['error'] = $this->db->_error_number();
					//$report['message'] = $this->db->_error_message();
					if($report !== 0){
						return true;
					}else{
						return false;
					}
			}else{
					$this->delete_gallery_set_admin($id);			
			}
	}

	function delete_gallery_set_admin($galleryset_id = 0, $ref_id = 0, $ref_type = 1, $del_tmp = 1){
		
		//Delete picture
		$picture_list = array();
		if($galleryset_id > 0 || $ref_id > 0) $picture_list = $this->get_gallery_set_by_id($galleryset_id, $ref_id, $ref_type);

		if($picture_list)
			for($i=0;$i<=count($picture_list);$i++){
					
					$folder = @$picture_list[$i]['folder'];
					$file_name = @$picture_list[$i]['file_name'];
					if($folder != '' && $file_name != ''){

							if(file_exists('uploads/article/'.$folder.'/'.$file_name)) @unlink('uploads/article/'.$folder.'/'.$file_name);
							if(file_exists('uploads/thumb120/'.$folder.'/'.$file_name)) @unlink('uploads/thumb120/'.$folder.'/'.$file_name);
							if(file_exists('uploads/thumb300/'.$folder.'/'.$file_name)) @unlink('uploads/thumb300/'.$folder.'/'.$file_name);
							if(file_exists('uploads/thumb450/'.$folder.'/'.$file_name)) @unlink('uploads/thumb450/'.$folder.'/'.$file_name);
							if(file_exists('uploads/thumb340/'.$folder.'/'.$file_name)) @unlink('uploads/thumb340/'.$folder.'/'.$file_name);
							if(file_exists('uploads/thumb620/'.$folder.'/'.$file_name)) @unlink('uploads/thumb620/'.$folder.'/'.$file_name);
							if(file_exists('uploads/thumb768/'.$folder.'/'.$file_name)) @unlink('uploads/thumb768/'.$folder.'/'.$file_name);
							if(file_exists('uploads/tmp2/'.$folder.'/'.$file_name)) @unlink('uploads/tmp2/'.$folder.'/'.$file_name);

							if($del_tmp == 1){
								if(file_exists('uploads/tmp/'.$folder.'/'.$file_name)) @unlink('uploads/tmp/'.$folder.'/'.$file_name);
							}

							/*switch ($ref_type){
									case 1 :
										if(file_exists('uploads/news/'.$folder.'/'.$file_name)) @unlink('uploads/news/'.$folder.'/'.$file_name);
									break;
									case 2 :
										if(file_exists('uploads/column/'.$folder.'/'.$file_name)) @unlink('uploads/column/'.$folder.'/'.$file_name);
									break;
									case 3 :
										if(file_exists('uploads/gallery/'.$folder.'/'.$file_name)) @unlink('uploads/gallery/'.$folder.'/'.$file_name);
									break;
									case 4 :
										if(file_exists('uploads/vdo/'.$folder.'/'.$file_name)) @unlink('uploads/vdo/'.$folder.'/'.$file_name);
									break;
									//case 5 :
									//	if(file_exists('uploads/dara/'.$file_name)) @unlink('uploads/dara/'.$file_name);
									//break;
							}*/
							//**************Log activity
							$action = 3;
							$log_activity = array(
									"admin_id" => $this->session->userdata('admin_id'),
									"ref_id" => $ref_id,
									"ref_type" => $ref_type,
									"ref_title" => "[delete]".$folder.'/'.$file_name,
									"action" => $action
							);			
							$this->admin_log_activity_model->store($log_activity);
							//**************Log activity
					}
			}

			if($ref_id > 0) $this->db->where('ref_id', $ref_id);
			//$this->db->where('ref_type', $ref_type);
			if($galleryset_id > 0) $this->db->where('galleryset_id', $galleryset_id);
			$this->db->delete('_gallery_set');
			//Debug($this->db->last_query());
	}

	function watermark($imgfile_name, $folder, $type = 'article', $orientation = 0, $transparency = 10){

			$upload_path = './uploads/tmp/';
			if(!is_dir($upload_path)) mkdir($upload_path, 0777);
			$upload_path = './uploads/tmp/'.$folder;
			if(!is_dir($upload_path)) mkdir($upload_path, 0777);

			$upload_path = './uploads/tmp2/';
			if(!is_dir($upload_path)) mkdir($upload_path, 0777);
			$upload_path = './uploads/tmp2/'.$folder;
			if(!is_dir($upload_path)) mkdir($upload_path, 0777);

			$logofile = '';
			if(preg_match("/.jpg|.jpeg/i", strtolower($imgfile_name))) {
					/*if($murl) $text=$murl; else $text="www.siamdara.com";*/

					$text="www.siamdara.com";
					switch($type){

							case 'admin' :
							case 'member' :

									$tmpfile = 'uploads/tmp/'.$folder.'/'.$imgfile_name;
									$tmpfile2 = 'uploads/tmp2/'.$folder.'/'.$imgfile_name;

									$sourcefile = 'uploads/'.$folder.'/'.$imgfile_name;

									/*if(file_exists($sourcefile)){
										Debug('<img src="'.$sourcefile.'" border="0" alt="">');
									}*/

									if(!file_exists($tmpfile2)){
										if(file_exists($tmpfile)){
											copy($tmpfile, $tmpfile2);
										}else{
											copy($sourcefile, $tmpfile);
											copy($tmpfile, $tmpfile2);
										}
									}

									//die();
									$imagesize = getimagesize($tmpfile);

							break;

							case 'article' :
							default :
									$tmpfile = './uploads/tmp/'.$folder.'/'.$imgfile_name;
									$tmpfile2 = './uploads/tmp2/'.$folder.'/'.$imgfile_name;
									if(!file_exists($tmpfile2)){ copy($tmpfile, $tmpfile2); }
									$sourcefile = './uploads/'.$type.'/'.$folder.'/'.$imgfile_name;
									$imagesize = getimagesize($tmpfile);
							break;
					}

					//Webtxt($sourcefile, $logofile, $text, $imagesize[0]);

					echo "<br>($tmpfile, $sourcefile)<br>";

					waterMark($tmpfile, $sourcefile, $orientation , $text, $imagesize[0], $transparency);
					if(isset($tmpfile2)) waterMark($tmpfile, $tmpfile2, $orientation , $text, $imagesize[0], $transparency);

					//Debug('<img src="'.base_url($sourcefile).'" border="0" alt="">');

					//echo "waterMark($tmpfile, $sourcefile, $orientation , $text, $imagesize[0], 20);";
					//die();

					/*
					$orientation = 0 = center
					$orientation = 1 = horizontal
					$orientation = 2 = vertical*/

					//Debug($imagesize);
					//die();
			}
	}

	public function rotate_img($source, $detination ,$angle = 90, $display = 1){			
			
			$this->load->helper('img');

			//$angle = preg_replace("/[^0-9]/","", $angle]) + 0;
			
			if(file_exists($source)) rotateImage($source, $detination, $angle);

			if($display == 1) echo '<img src="'.site_url($source).'?='.date("U").' " >';

	}

	public function set_default($galleryset_id = 0, $ref_id = 0, $ref_type = 1){

			$data = array(
					'default' => 0
			);
			$this->db->where('ref_id', $ref_id);
			$this->db->where('ref_type', $ref_type);
			$this->db->update('_gallery_set', $data);
			//Debug($this->db->last_query()); 

			$data = array(
					'default' => 1
			);
			$this->db->where('galleryset_id', $galleryset_id);
			$this->db->where('ref_type', $ref_type);
			$this->db->update('_gallery_set', $data);
			//Debug($this->db->last_query()); 
			
			$report = array();
			//$report['error'] = $this->db->_error_number();
			//$report['message'] = $this->db->_error_message();
			if($report !== 0){
				return true;
			}else{
				return false;
			}

	}

	public function chkfolder_exists($folder, $type='article'){

				$upload_path = './uploads/'.$type.'/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);

				$upload_path = './uploads/tmp/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				$upload_path = './uploads/tmp2/'.$folder;

				if(!is_dir($upload_path)) mkdir($upload_path, 0777);	
				$upload_path = './uploads/thumb/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				$upload_path = './uploads/thumb2/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				$upload_path = './uploads/thumb3/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				$upload_path = './uploads/thumb4/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);

				$upload_path = './uploads/size120/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				$upload_path = './uploads/size305/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);

				//$upload_path = './uploads/size400/'.$folder;
				//if(!is_dir($upload_path)) mkdir($upload_path, 0777);

				$upload_path = './uploads/size540/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);

				$upload_path = './uploads/headnews/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				$upload_path = './uploads/menu/'.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
	}
	
}
?>	
