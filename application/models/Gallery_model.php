<?php
class Gallery_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function countgallery($gallery_type_id = 0){

		$language = $this->lang->language['lang'];

		$this->db->select('count(gallery_id) as countall');
		$this->db->from('_gallery');

		if($gallery_type_id != 0) $this->db->where('gallery_type_id', $gallery_type_id);

		$this->db->where('lang', $language);
		$this->db->where('status !=', 2);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		$result = $query->result_object();
		return $result[0]->countall;

    }

    public function get_status($id){
        
    	$this->db->select('title, status, approve');
    	$this->db->from('_gallery');
    	$this->db->where('gallery_id2', $id);
    	$query = $this->db->get();
    	return $query->result_array();
    
    }

    public function get_order($id){
        
    	$this->db->select('title, order_by');
    	$this->db->from('_gallery');
    	$this->db->where('gallery_id2', $id);		
		$this->db->where('lang', 'th');

    	$this->db->limit(1);
    	$query = $this->db->get();
    	return $query->result_object();
    
    }

    public function get_max_id(){

		$this->db->select('max(gallery_id) as max_id');
		$this->db->from('_gallery');
		$query = $this->db->get();
		echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function get_data_by_id($gallery_id){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_gallery');
		$this->db->where('gallery_id2', $gallery_id);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function get_data($gallery_id=null, $search_string=null, $cat = '', $order = '_gallery.order_by', $ordertype='asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];
		$prefix = "sd";
		$ref_type = 3;

		$this->db->select('_gallery.*, _gallery_type.gallery_type_name, _news_highlight.news_highlight_id, _megamenu.id as megamenu_id, _picture.file_name, _picture.folder, _picture.default');

		if(isset($gallery_id)){
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_gallery.create_by) AS create_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_gallery.lastupdate_by) AS lastupdate_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_gallery.approve_by) AS approve_by_name');
		}

		$this->db->from('_gallery');

		if($gallery_id != null && $gallery_id > 0)
			$this->db->join('_gallery_type', '(_gallery.gallery_type_id=_gallery_type.gallery_type_id2 AND sd_gallery_type.`lang`= sd_gallery.`lang` AND sd_gallery_type.`status` = 1', 'left');
		else
			$this->db->join('_gallery_type', '(_gallery.gallery_type_id=_gallery_type.gallery_type_id2 AND sd_gallery_type.lang="'.$language.'" AND sd_gallery_type.`status` = 1', 'left');

		$this->db->join('_news_highlight', '_gallery.gallery_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type, 'left');
		$this->db->join('_megamenu', '_gallery.gallery_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');

		$this->db->join('_picture', '_gallery.gallery_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 3 AND '.$prefix.'_picture.`default`=1 ', 'left');

		if($gallery_id != null && $gallery_id > 0){
			$this->db->where('gallery_id2', $gallery_id);
		}else
			$this->db->where('_gallery.lang', $language);

		if($search_string){
			$this->db->like('_gallery.title', $search_string);
		}

		if($cat != '' && $cat != 0){
			$this->db->where('_gallery.gallery_type_id', intval($cat));
		}

		$this->db->where('_gallery.status !=', 2);

		if($order){
			$this->db->order_by($order, $ordertype);
		}else{
		    $this->db->order_by('_gallery.create_date', $ordertype);
		}
		$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_array();
    }

    public function get_approve($gallery_id=null, $search_string=null, $cat = '', $order = '_gallery.order_by', $ordertype='asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];
		$prefix = "sd";

		$this->db->select('_gallery.*, _gallery_type.gallery_type_name, _picture.file_name, _picture.folder, _picture.default');
		$this->db->from('_gallery');

		if($gallery_id != null && $gallery_id > 0)
			$this->db->join('_gallery_type', '(_gallery.gallery_type_id=_gallery_type.gallery_type_id2 AND sd_gallery_type.`status` = 1', 'left');
		else
			$this->db->join('_gallery_type', '(_gallery.gallery_type_id=_gallery_type.gallery_type_id2 AND sd_gallery_type.lang="'.$language.'" AND sd_gallery_type.`status` = 1', 'left');

		$this->db->join('_picture', '_gallery.gallery_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 3 AND '.$prefix.'_picture.`default`=1 ', 'left');

		if($gallery_id != null && $gallery_id > 0){
			$this->db->where('gallery_id2', $gallery_id);
		}else
			$this->db->where('_gallery.lang', $language);

		if($search_string){
			$this->db->like('_gallery.title', $search_string);
		}

		if($cat != '' && $cat != 0){
			$this->db->like('_gallery.gallery_type_id', $cat);
		}

		$this->db->where('_gallery.status', 1);
		$this->db->where('_gallery.approve', 1);

		if($order){
			$this->db->order_by($order, $ordertype);
		}else{
		    $this->db->order_by('_gallery.create_date', $ordertype);
		}
		$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_array();
    }

    function count_all($gallery_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_gallery');
		if($gallery_id != null && $gallery_id != 0){
			$this->db->where('gallery_id', $gallery_id);
		}

		if($search_string){
			$this->db->like('gallery_name', $search_string);
		}

		/*if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('create_date', 'Asc');
		}*/
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($gallery_id = 0, $data){
			
			//echo "gallery_id = $gallery_id<br>";
			//Debug($data);
			//echo "<hr>";

			if($gallery_id > 0){
					$this->db->where('gallery_id', $gallery_id);
					$this->db->update('_gallery', $data);
					//Debug($this->db->last_query());
					$report = array();
					//$report['error'] = $this->db->_error_number();
					//$report['message'] = $this->db->_error_message();
					if($report !== 0){
						return true;
					}else{
						return false;
					}					
			}else{
					$this->db->insert('_gallery', $data);
					$insert = $this->db->insert_id();
					//Debug($this->db->last_query());
					return $insert;
			}
	}

    function store2($gallery_id = 0, $data){
			
					$this->db->where('gallery_id2', $gallery_id);
					$this->db->update('_gallery', $data);
					$report = array();
					//$report['error'] = $this->db->_error_number();
					//$report['message'] = $this->db->_error_message();
					if($report !== 0){
						//echo $this->db->last_query();
						return true;
					}else{
						return false;
					}
	}

    function get_highlight($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';

			$ref_type = 3;

			$this->db->select('_news_highlight.*, _gallery.title, _gallery_type.gallery_type_name as category_name, 
			_gallery.create_date as folder_news , _gallery.create_date, _gallery.lastupdate_date, _gallery.countview, _picture.file_name, _picture.folder');

			$this->db->from('_news_highlight');
			$this->db->join('_gallery', '_news_highlight.news_id = _gallery.gallery_id2 and '.$prefix.'_news_highlight.ref_type = '.$ref_type);
			
			//$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_gallery_type', '_gallery.gallery_type_id = _gallery_type.gallery_type_id2 and '.$prefix.'_gallery_type.lang = "'.$language.'"', 'left');

			$this->db->join('_picture', '_news_highlight.news_id = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_gallery.gallery_id', $id);

			$this->db->where('_news_highlight.ref_type',$ref_type);
			$this->db->where('_gallery.lang', $language);

			$this->db->order_by('_news_highlight.order', 'Asc');
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

	function status_gallery($id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('gallery_id2', $id);
		$this->db->update('_gallery', $data);
		
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

    function set_highlight($id = 0, $data){

			$this->db->select('*');
			$this->db->from('_news_highlight');
			$this->db->where('news_id', $id);
			$this->db->where('ref_type', 3);
			$query = $this->db->get();
			if($query->num_rows == 0){
					$insert = $this->db->insert('_news_highlight', $data);
					return $insert;
			}
	}

    function update_order_highlight(){
			$this->db->set('order', '`order`  + 1', FALSE); 
			$this->db->update('_news_highlight');
	}

    function remove_highlight($id = 0){
			if($id > 0){
					$this->db->where('news_id', $id);
					$this->db->where('ref_type', 3);
					$this->db->delete('_news_highlight'); 
			}
	}

    function get_megamenu($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';

			$ref_type = 3;

			$this->db->select('_megamenu.*, _gallery.title, _category.category_name, _gallery.create_date as folder_news , _gallery.create_date, _gallery.lastupdate_date, _gallery.countview, _picture.file_name, _picture.folder');

			$this->db->from('_megamenu');
			$this->db->join('_gallery', '_news_highlight.news_id = _gallery.gallery_id2 and '.$prefix.'_news_highlight.ref_type = '.$ref_type);
			$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_picture', '_news_highlight.news_id = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_gallery.gallery_id', $id);

			$this->db->where('_news_highlight.ref_type',$ref_type);
			$this->db->where('_gallery.lang', $language);

			$this->db->order_by('_news_highlight.order', 'Asc');
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    function set_megamenu($id = 0, $data){

			$this->db->select('*');
			$this->db->from('_megamenu');
			$this->db->where('id', $id);
			$this->db->where('ref_type', 3);
			$query = $this->db->get();
			//Debug($this->db->last_query());
			//die();
			if($query->num_rows == 0){
					$insert = $this->db->insert('_megamenu', $data);
					//Debug($this->db->last_query());
					return $insert;
			}
	}

    function remove_megamenu($id = 0){
			if($id > 0){
					$this->db->where('id', $id);
					$this->db->where('ref_type', 3);
					$this->db->delete('_megamenu'); 
			}
	}

    public function set_order_picture($ref_id, $picture_id, &$data = array()){

			if($ref_id > 0){
					$this->db->where('ref_id', $ref_id);
					$this->db->where('picture_id', $picture_id);
					$this->db->update('_picture', $data);
					$report = array();
					//$report['error'] = $this->db->_error_number();
					//$report['message'] = $this->db->_error_message();
					if($report !== 0){
						//Debug($this->db->last_query());
						return true;
					}else{
						return false;
					}
			}
    }

	function update_order($gallery_id, $order = 1){

		$data['order_by'] = $order;
		$this->db->where('gallery_id2', intval($gallery_id));
		$this->db->update('_gallery', $data);
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

	function update_orderid_to_down($gallery_type_id, $order, $max){

		$this->db->set('order_by', 'order_by + 1', FALSE); 
		$this->db->where('order_by >=', intval($order)); 
		$this->db->where('order_by <', intval($max)); 
		//$this->db->where('gallery_type_id !=', intval($gallery_type_id));
		$this->db->where('gallery_id2 !=', intval($gallery_type_id));
		$this->db->update('_gallery');
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

	function update_orderid_to_up($gallery_type_id, $order, $min){

		$this->db->set('order_by', 'order_by - 1', FALSE); 
		$this->db->where('order_by >', intval($min)); 
		$this->db->where('order_by <=', intval($order)); 
		//$this->db->where('gallery_type_id !=', intval($gallery_type_id));
		$this->db->where('gallery_id2 !=', intval($gallery_type_id));
		$this->db->update('_gallery');
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
	
	function update_orderadd($category_id = 0){

		$this->db->set('order_by', 'order_by + 1', FALSE); 
		//$this->db->where('category_id', $category_id);
		$this->db->update('_gallery');

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function update_orderdel($category_id = 0, $order){

		$this->db->set('order_by', 'order_by - 1', FALSE); 
		$this->db->where('order_by >', $order); 
		//$this->db->where('category_id', $category_id);
		$this->db->update('_gallery');
		Debug($this->db->last_query());

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    public function getdelete($id = null){

		$language = $this->lang->language['lang'];
		$prefix = "sd";

		$this->db->select('_gallery.*, _gallery_type.gallery_type_name, _picture.file_name, _picture.folder, _picture.default');
		$this->db->from('_gallery');

		$this->db->join('_gallery_type', '(_gallery.gallery_type_id=_gallery_type.gallery_type_id2 AND sd_gallery_type.lang="'.$language.'" AND sd_gallery_type.`status` = 1', 'left');

		$this->db->join('_picture', '_gallery.gallery_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 3 AND '.$prefix.'_picture.`default`=1 ', 'left');

		$this->db->where('_gallery.lang', $language);
		$this->db->where('_gallery.status', 2);
		$this->db->order_by('_gallery.create_date', 'DESC');

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_array();

    }

    function delete_data($gallery_id){
		$data = array(
				'status' => 2
		);
		$this->db->where('gallery_id2', $gallery_id);
		$this->db->update('_gallery', $data);
		Debug($this->db->last_query());
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_by_admin($gallery_id){
		$this->db->where('gallery_id2', $gallery_id);
		$this->db->delete('_gallery'); 
	}

	/*************Relate***************/
    public function get_relate($id, $showdabug = 0){
		
			$language = $this->lang->language;
			$lang = $language['lang'];

			$prefix = 'sd';
			$ref_type = 3;

			$this->db->select('_gallery_relate.ref_id, _gallery_relate.order, _gallery.gallery_id, _gallery.gallery_id2, _gallery.title, _gallery.lastupdate_date, _gallery.create_date, _picture.file_name, _picture.folder');
			$this->db->from('_gallery');
			$this->db->join('_gallery_relate', '_gallery_relate.gallery_id = _gallery.gallery_id2');
			$this->db->join('_picture', '_gallery.gallery_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
			$this->db->where('_gallery_relate.ref_id', $id);
			$this->db->where('lang', $lang);
			$this->db->order_by("order", "ASC"); 
			$query = $this->db->get();
			if($showdabug == 1) Debug($this->db->last_query());
			$result = $query->result_object();
			return $result;

	}

    public function get_tag($tagid, $curid = 0, $showdabug = 0){
		
			$language = $this->lang->language;
			$lang = $language['lang'];

			$prefix = 'sd';
			$ref_type = 3;

			$this->db->select('g.gallery_id, g.gallery_id2, g.gallery_type_id, g.dara_id, g.title, tp.pair_id, tp.tag_id, tp.ref_id, tp.ref_type, t.tag_text');

			$this->db->from('_gallery g');
			$this->db->join('_tag_pair tp', 'tp.ref_id = g.gallery_id2 and tp.ref_type = '.$ref_type);
			$this->db->join('_tag t', 't.tag_id = tp.tag_id and t.status = 1');

			//$this->db->join('_picture', '_gallery.gallery_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
			$this->db->where('tp.tag_id', $tagid);
			$this->db->where('g.gallery_id2 !=', $curid);

			$this->db->where('lang', $lang);
			$this->db->order_by("tp.order", "ASC"); 
			$query = $this->db->get();
			if($showdabug == 1) Debug($this->db->last_query());
			$result = $query->result_object();
			return $result;

	}

    public function gen_relate($daraid = 0, &$tag_id = array(), $curid, $number_of_relate){
		
		$this->load->model('tags_model');
		
		$language = $this->lang->language;
		$lang = $language['lang'];

		//Debug($language);
		$listpage = 10;
		$limit_start = 0;
		$prefix = 'sd';
		$ref_type = 3;
		$result_relate = $getrelate_other = $where_tag = $where_tag2 = $where_id = $where_id2 = $tag_id_dara = array();

		/********************************/
		$this->db->select('_gallery.gallery_id2 as gallery_id, _gallery.title, _gallery.lang, _gallery.create_date');
		$this->db->from('_gallery');
		$this->db->where('_gallery.status', 1);
		$this->db->where('_gallery.approve', 1);
		$this->db->where('lang', $lang);
		if($daraid) $this->db->where('dara_id', $daraid);
		$this->db->where('_gallery.gallery_id2 !=', $curid);
		$this->db->order_by('create_date', 'DESC');
		$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		$result = $query->result_object();
		/********************************/
		if($tag_id){
			foreach($tag_id as $data){
				$where_tag[] = $data;
			}
		}
		$number = count($result); //ได้ข่าวจากการ tag dara มา ตรวจสอบ
		//echo "<b>result</b>";
		//Debug($result);
		//echo "<br>number=$number<br>";
		
		//echo "<hr><b>tag_id</b>";
		//Debug($tag_id);
		//echo "<hr>";

		/*$get_tag_dara = $this->tags_model->get_tag_pair($daraid, 5);
		echo "<b>get_tag_dara</b>";
		Debug($get_tag_dara);
		if($get_tag_dara){
				for($i=0;$i<count($get_tag_dara);$i++){
						$tag_id_dara[] = $get_tag_dara[$i]->tag_id;
				}
		}*/

		//echo "<b>tag_id_dara</b>";
		//Debug($tag_id_dara);

		if($result){
				//Debug($result);
				for($i=0;$i<count($result);$i++){
						foreach($result[$i] as $key => $val){
								if($key == "gallery_id") $gallery_id = $val;
						}
						if($curid != $gallery_id) $where_id[] = $gallery_id; //จัดเก็บ ID ลง array
				}
				//Debug($where_id);
		}

		if($tag_id){
				//Debug($result);
				for($i=0;$i<count($tag_id);$i++){
						if($curid != $tag_id[$i]){
								$where_tag2[] = $this->get_tag($tag_id[$i], $curid);
						}
				}
				
				if(count($where_tag2) == 2){
						$where_id2[] = array_merge($where_tag2[0], $where_tag2[1]);
				}else if(count($where_tag2) == 3){
						$where_id2[] = array_merge($where_tag2[0], $where_tag2[1], $where_tag2[2]);
				}else  if(count($where_tag2) == 4){
						$where_id2[] = array_merge($where_tag2[0], $where_tag2[1], $where_tag2[2], $where_tag2[3]);
				}else  if(count($where_tag2) == 5){
						$where_id2[] = array_merge($where_tag2[0], $where_tag2[1], $where_tag2[2], $where_tag2[3], $where_tag2[4]);
				}else{
						$where_id2 = $where_tag2;
				}
		}
		
		//echo "<b>where_id2</b>";
		//Debug($where_id2);

		//$result_relate = new stdclass();
		//$result_relate = $result;
		
		//Debug($result_relate);
		if($daraid > 0){		
				for($i = 0;$i<$number;$i++){
						$result_relate[$i] = $result[$i]->gallery_id;
				}
				//Debug($result_relate);
				//echo "($number_of_relate > $number)";
				if($number_of_relate > $number){
						$l=0;
						for($i = $number;$i<$number_of_relate;$i++){
								/*$result_relate[$i]>gallery_id = $where_id2[0][$l]->gallery_id2;
								$result_relate[$i]->title = $where_id2[0][$l]->title;;*/
								$result_relate[$i] =$where_id2[0][$l]->gallery_id2;
								$l++;
						}
				}
		}else{
				for($i = 0;$i<$number_of_relate;$i++){
						$result_relate[$i] =$where_id2[0][$i]->gallery_id2;
				}
		}
		//Debug($result_relate);
		return $result_relate;
	} 

     public function save_relate($gallery_id, &$data = array()){

					$this->db->delete('_gallery_relate', array('ref_id' => $gallery_id));
					$insert = $this->db->insert_batch('_gallery_relate', $data);
					Debug($this->db->last_query());
					//return $insert;
    }

     public function delete_relate($id = 0, $ref_id = 0){

			if($id > 0)
				$this->db->delete('_gallery_relate', array('gallery_id' => $id));

			if($ref_id > 0)
				$this->db->delete('_gallery_relate', array('ref_id' => $ref_id));

    }

    public function set_order_relate($ref_id, $gallery_id, &$data = array(), $table = '_gallery_relate'){

			if($ref_id > 0){
					$this->db->where('ref_id', $ref_id);
					$this->db->where('gallery_id', $gallery_id);
					$this->db->update($table, $data);
					//Debug($this->db->last_query());

					$report = array();
					//$report['error'] = $this->db->_error_number();
					//$report['message'] = $this->db->_error_message();
					if($report !== 0){
						//Debug($this->db->last_query());
						return true;
					}else{
						return false;
					}
			}
    }

	function add_relate($ref_id = 0, $gallery_id = 0, $table = '_gallery_relate'){

		$data = array(
			"ref_id" => $ref_id,
			"gallery_id" => $gallery_id,
			"order" => 0,
		);

		$insert = $this->db->insert($table, $data);
		echo "Add success.";
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			Debug($report);
			return false;
		}

	}

	function update_order_relate($gallery_id, $ref_id, $order = 1, $table = '_gallery_relate'){

		$data['order'] = $order;
		$this->db->where('gallery_id', $gallery_id);
		$this->db->where('ref_id', $ref_id);
		$this->db->update($table, $data);
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
?>	
