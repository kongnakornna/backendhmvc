<?php
class Vdo_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function countvdo($subcategory_id = 0){

		$language = $this->lang->language['lang'];

		$this->db->select('count(video_id) as countall');
		$this->db->from('_video');

		if($subcategory_id != 0) $this->db->where('subcategory_id', $subcategory_id);

		$this->db->where('lang', $language);
		$this->db->where('status !=', 2);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		$result = $query->result_object();
		return $result[0]->countall;

    }

    public function get_status($id, $lang = ''){
    
    	//$language = $this->lang->language['lang'];
    	$this->db->select('title_name, status, approve');
    	$this->db->from('_video');
    	$this->db->where('video_id2', $id);
    	if($lang != '') $this->db->where('lang', $lang);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    
    }

    public function get_order($id, $lang = ''){
    
    	//$language = $this->lang->language['lang'];
    	$this->db->select('title_name, order_by');
    	$this->db->from('_video');
    	$this->db->where('video_id2', $id);
    	if($lang != '') $this->db->where('lang', $lang);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
		return $query->result_object();
    }

    public function get_sstvid($sstv_id, $lang = ''){
    
    	//$language = $this->lang->language['lang'];
    	$this->db->select('video_id, video_id2, sstv_id, title_name, status, approve');
    	$this->db->from('_video');
    	$this->db->where('sstv_id', $sstv_id);
    	if($lang != '') $this->db->where('lang', $lang);
    	$query = $this->db->get();
    	//Debug($this->db->last_query());
    	return $query->result_object();
    
    }

    public function get_max_id(){

		//$language = $this->lang->language['lang'];
		$this->db->select('max(video_id) as max_id');
		$this->db->from('_video');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_object(); 

    }

    public function get_data($video_id=null, $search_string=null, $type = null, $status = 0, $sp = 0, $order = 'order_by', $ordertype='asc', $limit_start = 0, $listpage = 100){
		
		$language = $this->lang->language['lang'];
		$prefix = "sd";
		$ref_type = 4;

		$this->db->select('v.*, _picture.file_name, _picture.folder, _news_highlight.news_highlight_id');
		//$this->db->select('_news_highlight.news_highlight_id');
		$this->db->select('m.id as megamenu_id, clip_id');
		
		$this->db->select('v.category_id, _category.category_name, _category.type');
		$this->db->select('v.subcategory_id, sub.subcategory_name');

		//if(isset($video_id)){
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id=v.create_by) AS create_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id=v.lastupdate_by) AS lastupdate_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id=v.approve_by) AS approve_by_name');
		//}

		$this->db->from('_video v');

		if($sp == 1){
			$this->db->join('_news_highlight', 'v.video_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type);
			$this->db->join('_megamenu m', 'v.video_id2 = m.id and m.ref_type = '.$ref_type, 'left');
		}else if($sp == 2){
			$this->db->join('_news_highlight', 'v.video_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu m', 'v.video_id2 = m.id and m.ref_type = '.$ref_type);
		}else{
			$this->db->join('_news_highlight', 'v.video_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu m', 'v.video_id2 = m.id and m.ref_type = '.$ref_type, 'left');
		}

		$this->db->join('_category', 'v.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory sub', 'v.subcategory_id = sub.subcategory_id_map and sub.lang = "'.$language.'"', 'left');

		$this->db->join('_picture', 'v.video_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');

		$this->db->join('_clipded', 'v.video_id2 = _clipded.clip_id ', 'left');

		if($video_id != null && $video_id > 0){
			$this->db->where('v.video_id2', intval($video_id));
		}else
			$this->db->where('v.lang', $language);

		if(is_array($search_string)){
			foreach($search_string as $key => $val){
					if($key == 'status'){
							$array_kw['v.status'] = $val;
							$this->db->where($array_kw);
					}else if($key == 'approve'){
							$array_kw['v.approve'] = $val;
							$this->db->where($array_kw);
					}else{
							$array_kw['v.title_name'] = $val;
							//$this->db->where($array_kw);
							$this->db->like('v.title_name', $val);
					}
			}
		}else if($search_string){		
				$this->db->like('v.title_name', $search_string);
		}

		if($type > 0){
			$this->db->where('v.category_id', 5);		
			$this->db->where('v.subcategory_id', $type);		
		}

		if($status > 0){
			if($status == 3)
				$this->db->where('v.status', 0);
			else
				$this->db->where('v.status', $status);
		}
		$this->db->where('v.status !=', 2);

		if($order){
			$this->db->order_by($order, $ordertype);
		}else{
		    $this->db->order_by('v.create_date', $ordertype);
		}

		$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_object();
    }

    public function get_approve($video_id=null, $search_string=null, $type = null, $order = 'order_by', $ordertype='asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];
		$prefix = "sd";
		$ref_type = 4;

		$this->db->select('v.*, _picture.file_name, _picture.folder, _news_highlight.news_highlight_id');
		//$this->db->select('_news_highlight.news_highlight_id');
		$this->db->select('m.id as megamenu_id');
		
		$this->db->select('v.category_id, _category.category_name, _category.type');
		$this->db->select('v.subcategory_id, sub.subcategory_name');

		if(isset($video_id)){
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id=v.create_by) AS create_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id=v.lastupdate_by) AS lastupdate_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id=v.approve_by) AS approve_by_name');
		}
		$this->db->from('_video v');

		$this->db->join('_news_highlight', 'v.video_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type, 'left');
		$this->db->join('_megamenu m', 'v.video_id2 = m.id and m.ref_type = '.$ref_type, 'left');
		
		$this->db->join('_category', 'v.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory sub', 'v.subcategory_id = sub.subcategory_id_map and sub.lang = "'.$language.'"', 'left');

		$this->db->join('_picture', 'v.video_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');

		if($video_id != null && $video_id > 0){
			$this->db->where('v.video_id2', intval($video_id));
		}else
			$this->db->where('v.lang', $language);

		if($search_string){
			$this->db->like('v.title_name', $search_string);
		}

		if($type > 0){
			//$this->db->where('v.category_id', 5);		
			//$this->db->where('v.subcategory_id', $type);		
		}
		$this->db->where('v.status', 1);
		$this->db->where('v.approve', 1);

		if($order){
			$this->db->order_by($order, $ordertype);
		}else{
		    $this->db->order_by('v.order_by', $ordertype);
		}

		$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_object();
    }

    function store($id = 0, $data){

			if(isset($data['tag_id'])){
				unset($data['tag_id']);
			}
			//die();

			if($id > 0){
					$this->db->where('video_id', $id);
					$this->db->update('_video', $data);
					$report = array();
					//$report['error'] = $this->db->_error_number();
					//$report['message'] = $this->db->_error_message();
					if($report !== 0){
						//echo $this->db->last_query();
						return true;
					}else{
						return false;
					}					
			}else{
					$insert = $this->db->insert('_video', $data);
					//echo $this->db->last_query();
					return $insert;
			}
	}

    function store2($id = 0, $data){

			if($id > 0){
					$this->db->where('video_id2', $id);
					$this->db->update('_video', $data);
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

    function get_highlight($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';
			$ref_type = 4;
			$this->db->select('_news_highlight.*, _video.title_name as title, _category.category_name, _video.create_date as folder_news ,_video.create_date, _video.lastupdate_date, _video.countclick as countview, _picture.file_name, _picture.folder');
			//$this->db->select('_news_highlight.*, _news.title, _news.create_date as folder_news');
			$this->db->from('_news_highlight');
			//$this->db->join('_news', '_news_highlight.news_id = _news.news_id2 and '.$prefix.'_news_highlight.ref_type = 1 ');
			$this->db->join('_video', '_news_highlight.news_id = _video.video_id2 and '.$prefix.'_news_highlight.ref_type = '.$ref_type);
			$this->db->join('_category', '_video.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_picture', '_news_highlight.news_id = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_video.video_id', $id);

			$this->db->where('_news_highlight.ref_type',1);
			$this->db->where('_video.lang', $language);
			$this->db->order_by('_news_highlight.order', 'Asc');
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    function setorder_highlight($id = 0, $data){

			if($id > 0){
					$this->db->where('news_id', $id);
					$this->db->where('ref_type', 4);
					$this->db->update('_news_highlight', $data);
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

	function status_vdo($id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('video_id2', $id);
		$this->db->update('_video', $data);
		
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
					$this->db->where('ref_type', 4);
					$this->db->delete('_news_highlight'); 
			}
	}

    function get_megamenu($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';

			$ref_type = 4;

			$this->db->select('_megamenu.*, _video.title, _category.category_name, _video.create_date as folder_news , _video.create_date, _video.lastupdate_date, _video.countview, _picture.file_name, _picture.folder');

			$this->db->from('_megamenu');
			$this->db->join('_video', '_news_highlight.news_id = _video.video_id2 and '.$prefix.'_news_highlight.ref_type = '.$ref_type);
			$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_picture', '_news_highlight.news_id = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_video.video_id', $id);

			$this->db->where('_news_highlight.ref_type',$ref_type);
			$this->db->where('_video.lang', $language);

			$this->db->order_by('_news_highlight.order', 'Asc');
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    function set_megamenu($id = 0, $data){

			$this->db->select('*');
			$this->db->from('_megamenu');
			$this->db->where('id', $id);
			$this->db->where('ref_type', 4);
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
					$this->db->where('ref_type', 4);
					$this->db->delete('_megamenu'); 
			}
	}

	function update_orderplus($order = 0, $max = 0){

		$this->db->set('order_by', 'order_by + 1', FALSE); 
		if($order != 0) $this->db->where('order_by >=', $order); 
		if($max != 0) $this->db->where('order_by <', $max); 
		$this->db->update('_video');

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function update_orderdiv($order, $max){

		$this->db->set('order_by', 'order_by - 1', FALSE); 
		if($order != 0) $this->db->where('order_by >', $order); 
		if($max != 0) $this->db->where('order_by <=', $max); 
		$this->db->update('_video');

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
		$this->db->update('_video');

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
		$this->db->update('_video');
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

    public function getdelete($id = null){

		$language = $this->lang->language['lang'];
		$prefix = 'sd';

		$this->db->select('d.*, _category.category_name, _subcategory.subcategory_name, _picture.folder, _picture.file_name ');
		$this->db->from('_video d');
		$this->db->join('_category', 'd.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', 'd.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');
		$this->db->join('_picture', 'd.video_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 4 AND '.$prefix.'_picture.`default`=1 ', 'left');
		$this->db->where('d.status =', 2);
		$this->db->where('d.lang', $language);

		if($id != null && $id > 0){
			$this->db->where('d.video_id2', $id);
		}

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

    function delete_data($video_id){
		$data = array(
				'status' => 2
		);
		$this->db->where('video_id2', $video_id);
		$this->db->update('_video', $data);
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

	function delete_by_admin($video_id){
		$this->db->where('video_id2', $video_id);
		$this->db->delete('_video'); 
	}

	/*************Relate***************/
    public function get_relate($id, $showdabug = 0){
		
			$language = $this->lang->language;
			$lang = $language['lang'];

			$prefix = 'sd';
			$ref_type = 4;

			$this->db->select('_vdo_relate.ref_id, _vdo_relate.order, _video.video_id, _video.video_id2, _video.title_name as title, _video.lastupdate_date, _video.create_date, _picture.file_name, _picture.folder');
			$this->db->from('_video');
			$this->db->join('_vdo_relate', '_vdo_relate.vdo_id = _video.video_id2');
			$this->db->join('_picture', '_video.video_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
			$this->db->where('_vdo_relate.ref_id', $id);
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
			$ref_type = 4;

			$this->db->select('g.video_id, g.video_id2, g.subcategory_id, g.dara_id, g.title_name as title, tp.pair_id, tp.tag_id, tp.ref_id, tp.ref_type, t.tag_text');

			$this->db->from('_video g');
			$this->db->join('_tag_pair tp', 'tp.ref_id = g.video_id2 and tp.ref_type = '.$ref_type);
			$this->db->join('_tag t', 't.tag_id = tp.tag_id and t.status = 1');

			//$this->db->join('_picture', '_gallery.video_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
			$this->db->where('tp.tag_id', $tagid);
			$this->db->where('g.video_id2 !=', $curid);

			$this->db->where('lang', $lang);
			$this->db->order_by("tp.order", "ASC"); 
			$query = $this->db->get();
			if($showdabug == 1) Debug($this->db->last_query());
			$result = $query->result_object();
			return $result;

	}

    public function gen_relate($daraid = 0, &$tag_id = array(), $curid, $number_of_relate = 0){
		
		$this->load->model('tags_model');
		
		$language = $this->lang->language;
		$lang = $language['lang'];

		//Debug($language);
		$listpage = 20;
		$limit_start = 0;
		$prefix = 'sd';
		$ref_type = 4;
		$result_relate = $getrelate_other = $where_tag = $where_tag2 = $where_id = $where_id2 = $tag_id_dara = array();

		/********************************/
		if($daraid > 0){
				$this->db->select('v.video_id2 as video_id, v.title_name as title, v.lang, v.create_date');
				$this->db->from('_video v');
				$this->db->where('v.status', 1);
				$this->db->where('v.approve', 1);
				$this->db->where('lang', $lang);
				if($daraid) $this->db->where('dara_id', $daraid);
				$this->db->where('v.video_id2 !=', $curid);
				$this->db->order_by('v.create_date', 'DESC');
				$this->db->limit($listpage, $limit_start);
		}else{
			if(count($tag_id > 0)){
				
				$where = array();
				$this->db->select('tag_id, ref_id as video_id, order, create_date');
				$this->db->from('_tag_pair');
				if($tag_id)
				foreach($tag_id as $val){
						$where[] = " tag_id = ".$val;
				}
				if(count($where) > 0) $this->db->where('('.implode(' OR', $where).')', NULL, FALSE);
				$this->db->where('ref_type', $ref_type); 
				$this->db->order_by('create_date', 'DESC');
			}
		}

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

		if($result)
				for($i=0;$i<count($result);$i++){
						foreach($result[$i] as $key => $val){
								if($key == "video_id") $video_id = $val;
						}
						if($curid != $video_id) $where_id[] = $video_id;
				}
			
				$this->db->select('_tag_pair.ref_id as video_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
				$this->db->from('_tag');
				$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = '.$ref_type);
				$this->db->where('_tag.status', 1);
				$this->db->where('ref_id !=', $curid);

				if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
				if($where_id) $this->db->where_in('_tag_pair.ref_id', $where_id);

				$this->db->group_by("video_id"); 
				$this->db->order_by("NumberOfTag", "DESC"); 
				$this->db->limit(5);

				//$this->db->where('lang', $language);
				$query2 = $this->db->get();
				//Debug($this->db->last_query());

				$getrelate = $query2->result_object();
				//echo "Q2";
				//Debug($getrelate);

				//if ไม่ข่าวไม่ถึง 5 จากดารา หาเพิ่ม
				if(count($getrelate) < 5){

							//$get_add = 5 - count($getrelate);

							$this->db->select('_tag_pair.ref_id as video_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
							$this->db->from('_tag');
							$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = '.$ref_type);
							$this->db->join('_video v', '_tag_pair.ref_id = v.video_id2 AND v.status = 1');
							$this->db->where('_tag.status', 1);

							if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
							if($where_id) $this->db->where_not_in('_tag_pair.ref_id', $where_id);

							$this->db->where('_tag_pair.ref_id !=', intval($curid));

							if(count($getrelate) > 0){
									foreach($getrelate as $arrw => $conw){
											$this->db->where('_tag_pair.ref_id !=', intval($getrelate[$arrw]->video_id));
									}
							}

							$this->db->group_by("video_id2"); 
							$this->db->order_by("NumberOfTag", "DESC"); 
							$this->db->limit(5);

							$other_query = $this->db->get();
							//Debug($this->db->last_query());
							$getrelate_other = $other_query->result_object();
							//echo "Other";
							//Debug($getrelate_other);

							$getrelate = array_merge($getrelate, $getrelate_other);
				}

				$getrelate = $this->make_unique($getrelate);

				return $getrelate;
	} 

	function make_unique($array, $ignore = ''){
		while($values = each($array)){
			if(!@in_array($values[1], $ignore)){
				$dupes = array_keys($array, $values[1]);
				unset($dupes[0]);
				foreach($dupes as $rmv){
					unset($array[$rmv]);
				}           
			}
		}
		return $array;
	}

     public function save_relate($video_id, &$data = array()){

					$this->db->delete('_vdo_relate', array('ref_id' => $video_id));
					$insert = $this->db->insert_batch('_vdo_relate', $data);
					Debug($this->db->last_query());
					//return $insert;
    }

     public function delete_relate($id = 0, $ref_id = 0){

			if($id > 0)
				$this->db->delete('_vdo_relate', array('vdo_id' => $id));

			if($ref_id > 0)
				$this->db->delete('_vdo_relate', array('ref_id' => $ref_id));

    }

    public function set_order_relate($ref_id, $video_id, &$data = array(), $table = '_vdo_relate'){

			if($ref_id > 0){
					$this->db->where('ref_id', $ref_id);
					$this->db->where('video_id', $video_id);
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

	function add_relate($ref_id = 0, $vdo_id = 0, $table = '_vdo_relate'){

		$data = array(
			"ref_id" => $ref_id,
			"vdo_id" => $vdo_id,
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

	function update_order_relate($vdo_id, $ref_id, $order = 1, $table = '_vdo_relate'){

		$data['order'] = $order;
		$this->db->where('vdo_id', $vdo_id);
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


    public function set_order($order = 0, $neworder, $category_id = '', $video_id = ''){

			$this->db->set('order_by', $neworder); 
			$this->db->where('status', 1); 
			$this->db->where('approve', 1); 

			if($video_id != '') 
				$this->db->where('video_id2', $video_id); 
			else 
				$this->db->where('order_by', $order); 

			$this->db->where('category_id', $category_id);
			$this->db->update('_video');

			if($this->session->userdata('admin_id') == 1){
				Debug($this->db->last_query());
				echo "\n\n";
			}

			$report = array();
			//$report['error'] = $this->db->_error_number();
			//$report['message'] = $this->db->_error_message();

			if($report !== 0){
					return true;
			}else{
					return false;
			}
    }

    public function set_addorder($minorder, $maxorder, $category_id){
		$this->db->set('order_by', 'order_by + 1', FALSE); 
		$this->db->where('category_id', $category_id);
		$this->db->where('order_by >=', $minorder);
		$this->db->where('order_by <', $maxorder);
		$this->db->update('_video');
		if($this->session->userdata('admin_id') == 1){
			Debug($this->db->last_query());
			echo "\n\n";
		}
	}

    public function set_delorder($minorder, $maxorder, $category_id){
		$this->db->set('order_by', 'order_by - 1', FALSE); 
		$this->db->where('category_id', $category_id);
		$this->db->where('order_by >', $maxorder);
		$this->db->where('order_by <=', $minorder);
		$this->db->update('_video');
		if($this->session->userdata('admin_id') == 1){
			Debug($this->db->last_query());
			echo "\n\n";
		}
	}


	/******************SSTV***********************/
    public function load_sstv($page = 1, $page_size = 10, $lang ='th'){

				$get_url = "http://sstv.siamsport.co.th/genXML/api/getclipdara.php?page=".$page."&page_size=".$page_size;
				//echo "<h4>$get_url</h4>";
				$jsonData = file_get_contents($get_url);
				$jsonobj = json_decode($jsonData, true);

				return $jsonobj;
	}

	public function import_sstv_to_db(&$data = array()){
			$insert = $this->db->insert_batch('_video', $data);
			return $insert;
	}

	public function update_sstv_to_db($sstv_id, $youtube, $countclick){
			$this->db->set('youtube', $youtube); 
			$this->db->set('countclick', $countclick); 
			$this->db->where('sstv_id', $sstv_id); 
			$this->db->update('_video');
			//Debug($this->db->last_query());
	}

	function allorderplus($order = 1, $maxorder = 99){

		$this->db->set('order_by', 'order_by + '.$order, FALSE); 
		$this->db->where('order_by <', $maxorder); 
		$this->db->update('_video');
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}
	
	function player(){
			
			//$now = date('Ymdhis');
/*
			<link rel="stylesheet" href="'.base_url("theme/player/style.css").'" />
			<script type="text/javascript" src="//s0.2mdn.net/instream/html5/ima3.js?'.$now.'>"></script>
			<script type="text/javascript" src="'.base_url("theme/player/application.js").'"></script>
			<script type="text/javascript" src="'.base_url("theme/player/ads.js").'"></script>
			<script type="text/javascript" src="'.base_url("theme/player/video_player.js").'"></script>

				<!-- GPT Companion Code -->
				<!-- Initialize the tagging library -->
				 <script type="text/javascript">
				   var googletag = googletag || {};
				   googletag.cmd = googletag.cmd || [];
				   (function() {
					 var gads = document.createElement("script");
					 gads.async = true;
					 gads.type = "text/javascript";
					 gads.src = "//www.googletagservices.com/tag/js/gpt.js";
					 var node = document.getElementsByTagName("script")[0];
					 node.parentNode.insertBefore(gads, node);
				   })();
				 </script>
*/

			$player = '
				<div id="videoplayer">
				  <video id="content" poster="{clipsource}">
				  
					<source src="{imgsource}"></source>
				  </video>
				  <div id="adcontainer"></div>
				  <div id="related"></div>
				  <button id="playstart">&#9654;</button>
				  <button id="skipad" title="Skip">Skip Ad >></button>
				</div>
				
				<div id="videocontrol">
				  <button id="playpause" title="Play/Pause">&#9654;</button>
				  <div id="texttimecurrent">00:00</div>
				  <div id="slidebar">
					  <div id="barbackground"></div>
					  <div id="barbuffer"></div>
					  <div id="barplaying"></div>
					  <div id="barseek"></div>
				  </div>
				  <div id="texttimeduration">00:00</div>
				  <button id="fullscreen" title="Full screen">[&nbsp;&nbsp;&nbsp;]</button>
				  
				</div>
			  <script type="text/javascript">

			  var application = null;

			  window.onload = function() {
				//application = new Application();
				//application.relatedAPI("");
			  };
			  
			  window.onresize = function() {
				  application.resize();
			  };
			  </script>';
			
			return $player;

	}

}
?>	
