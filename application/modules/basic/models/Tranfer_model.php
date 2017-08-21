<?php
class Tranfer_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function load_zoonnews(){

				$get_url = "http://www.siamdara.com/apisiamdara/select_zoonnews.php";
				echo "$get_url<hr>";
				$jsonData = file_get_contents($get_url);
				$jsonobj = json_decode($jsonData, true);

				return $jsonobj;
	}

    public function load_news($idzone = "001", $page = 1, $page_size = 5, $lang ='th'){

				//$get_url = "http://siamdara.com/apisiamdara/select_newsdara_v2.php?idzone=".$idzone."&page=".$page."&page_size=".$page_size;
				//$get_url = "http://www.siamdara.com/apisiamdara/zoonnews001_1000-1.json";
				//$get_url = "http://www.siamdara.com/apisiamdara/zoonnews".$idzone ."_".$page_size."-".$page.".json";

				$get_url = "http://www.siamdara.com/apisiamdara/data/zoonnews".$idzone ."_".$page_size."-".$page.".json";

				echo "$get_url<hr>";
				$jsonData = file_get_contents($get_url);
				$jsonobj = json_decode($jsonData, true);

				return $jsonobj;
	}

	public function import_to_db(&$data = array()){

					//$insert = $this->db->insert_batch('_news', $data);
					//return $insert;
	}

    public function download($pic_url, $folder = '', $filename = '', $ref_type = 1, $img_size = 500, $showdebug = 0){
    	
    	$this->load->helper('img');
		$type_folder = '';

		switch($ref_type){
				case 1 : $type_folder = 'news';  break;
				case 2 : $type_folder = 'column';  break;
				case 3 : $type_folder = 'gallery';  break;
				case 4 : $type_folder = 'vdo';  break;
		}
    	//if($folder == '') $folder = date('Ymd');
    	//$fname =  $type_folder.date("YmdHi") . rand(000, 999);
    	
    	$upload_tmppath = './uploads/tmp/'.$folder;
    	$upload_path = './uploads/'.$type_folder.'/'.$folder;

    	$newtmp_filename = $upload_tmppath.'/'.$filename;
		$new_filename = $upload_path.'/'.$filename;

		if(!file_exists($new_filename)){

				$src = fopen($pic_url, 'r');
				$dest1 = fopen($newtmp_filename, 'w');
				stream_copy_to_stream($src, $dest1);
				//resize_img($newtmp_filename, $newtmp_filename, $img_size);

				//Copy to Tmp
				/*$src2 = fopen($newtmp_filename, 'r');
				$dest2 = fopen($new_filename, 'w');
				stream_copy_to_stream($src2, $dest2);*/
				//resize_img($new_filename, $new_filename, $img_size);

				//Copy to news				
				if($showdebug == 1) Debug("Download <b>$type_folder</b> $pic_url ==> $new_filename");

		}else
				echo "<br>file_exists($new_filename)<br>";

		return $new_filename;    
    }

	public function chkfolder_exists($folder){

				//DEFINE( 'DS', DIRECTORY_SEPARATOR );
				//DEFINE( 'DS', '/' );

				$upload_path = '.'.DS.'uploads'.DS.'news'.DS.''.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				//echo "<li>chk($upload_path)<\li>";

				$upload_path = '.'.DS.'uploads'.DS.'column'.DS.''.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				//echo "<li>chk($upload_path)<\li>";

				$upload_path = '.'.DS.'uploads'.DS.'thumb'.DS.''.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				//echo "<li>chk($upload_path)<\li>";

				$upload_path = '.'.DS.'uploads'.DS.'thumb2'.DS.''.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				//echo "<li>chk($upload_path)<\li>";

				$upload_path = '.'.DS.'uploads'.DS.'thumb3'.DS.''.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				//echo "<li>chk($upload_path)<\li>";

				$upload_path = '.'.DS.'uploads'.DS.'thumb4'.DS.''.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				//echo "<li>chk($upload_path)<\li>";

				$upload_path = '.'.DS.'uploads'.DS.'headnews'.DS.''.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				//echo "<li>chk($upload_path)<\li>";

				$upload_path = '.'.DS.'uploads'.DS.'menu'.DS.''.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				//echo "<li>chk($upload_path)<\li>";

				$upload_path = '.'.DS.'uploads'.DS.'tmp'.DS.''.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);
				//echo "<li>chk($upload_path)<\li>";

				$upload_path = '.'.DS.'uploads'.DS.'tmp2'.DS.''.$folder;
				if(!is_dir($upload_path)) mkdir($upload_path, 0777);	
				//echo "<li>chk($upload_path)<\li>";

	}

    public function add_batch(&$data = array(), $table = '_news_copy', $debug = 0){
					if($debug == 1) Debug($data);
					$insert = $this->db->insert_batch($table, $data);
					if($debug == 1) Debug($this->db->last_query());
					return $insert;
    }


    public function load_table_news($idzone = "001", $page = 1, $page_size = 5, $newid = 0, $showdebug = 0){

			$limit_start=((intval($page) - 1) * $page_size);
			$listpage = $page_size;

			$this->db->select('*');
			$this->db->from('_news_old');

			if($idzone != "000") $this->db->where('idzone =', $idzone);
			if($newid > 0) $this->db->where('news_id =', $newid);

			$this->db->order_by('news_id', 'ASC');

			$this->db->limit($listpage, $limit_start);
			$query = $this->db->get();

			if($showdebug == 1) Debug($this->db->last_query());
			//die();		
			return $query->result_object();
	}

    public function GetID_TMP($category_id = 0, $subcategory_id = 0, $showdebug = 1){
			$this->db->select('news_id');
			$this->db->from('_news_old');

			$this->db->where('category_id', $category_id);
			$this->db->where('subcategory_id', $subcategory_id);
			$this->db->order_by('news_id', 'asc');
			$this->db->limit(1, 0);
			$query = $this->db->get();

			if($showdebug == 1) Debug($this->db->last_query());
			return $query->result_object();
	}

    public function get_column($category_id = 0, $subcategory_id = 0, $id = 180, $showdebug = 1){

			$this->db->select('*');
			$this->db->from('_column');
			$this->db->where('column_id >', $id);
			$this->db->where('category_id', $category_id);
			$this->db->where('subcategory_id', $subcategory_id);
			$this->db->order_by('column_id', 'asc');
			$query = $this->db->get();

			if($showdebug == 1) Debug($this->db->last_query());
			return $query->result_object();
	}

    function update_column($id = 0, $data){

			if($id > 0){

					$this->db->where('news_id', $id);
					$this->db->update('_news_old2', $data);
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
	}

}
?>	
