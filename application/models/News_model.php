<?php
class News_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function get_status($id, $lang = ''){
    	//$language = $this->lang->language['lang'];
    	$this->db->select('news_id2, title, create_date, status, approve');
    	$this->db->from('_news');
    	$this->db->where('news_id2', $id);
    	if($lang != '') $this->db->where('lang', $lang);
    	$query = $this->db->get();
		//Debug($this->db->last_query());
    	return $query->result_array();
    }

    public function get_order($id = 0, $lang = '', $order = 0, $cat_id = 0, $subcat_id = 0){
			//$language = $this->lang->language['lang'];
			$this->db->select('news_id2, title, order_by, status, approve');
			$this->db->from('_news');

			if($id != 0) $this->db->where('news_id2', $id);
			if($order != 0) $this->db->where('order_by', $order);
			if($cat_id != 0) $this->db->where('category_id', $cat_id);
			//if($subcat_id != 0) $this->db->where('subcategory_id', $subcat_id);

			if($lang != '') $this->db->where('lang', $lang);
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
    }

    /*public function count_news($table = '_news'){
		$language = $this->lang->language['lang'];
		$this->db->select('count(news_id) as number');
		$this->db->from($table);
		$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_object(); 
    }*/

    public function get_max_id($table = '_news'){
		//$language = $this->lang->language['lang'];
		$this->db->select('max(news_id) as max_id');
		$this->db->from($table);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_max_order($category_id){
		//$language = $this->lang->language['lang'];
		$this->db->select('max(order_by) as max_order');
		$this->db->from('_news');
		$this->db->where('category_id', $category_id);
		$query = $this->db->get();
		return $query->result_object(); 
    }

    public function countnews($category_id = 0, $subcategory_id = 0){

		$language = $this->lang->language['lang'];

		$this->db->select('count(news_id) as countnews');
		$this->db->from('_news');
		if($category_id != 0) $this->db->where('category_id', $category_id);
		if($subcategory_id != 0) $this->db->where('subcategory_id', $subcategory_id);

		$this->db->where('lang', $language);
		$this->db->where('status !=', 2);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		$result = $query->result_object();
		//Debug($result[0]->countnews);
		return $result[0]->countnews;

    }

    public function get_news_by_id($id_map){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_news');
		$this->db->where('news_id2', $id_map);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function gen_relate($ref_id = 0, &$tag_id = array(), $curid){
		
		$language = $this->lang->language['lang'];
		//echo "($ref_id , $tag_id , $curid)";
		//Debug($tag_id);
		//die();

		$listpage = 50;
		$limit_start = 0;
		$prefix = 'sd';
		$getnews_other = array();
		/********************************/
		$this->db->select('_news.news_id2 as news_id, _news.title, _news.lang, _news.create_date');
		$this->db->from('_news');
		$this->db->where('_news.status', 1);
		$this->db->where('_news.approve', 1);
		$this->db->where('lang', $language);
		$this->db->where('dara_id', $ref_id);
		$this->db->where('news_id2 !=', $curid);
		$this->db->order_by('create_date', 'DESC');
		$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		//die();
		$result = $query->result_object();
		//Debug($result);

		$where_tag = $where_newsid = array();
		if($tag_id){
			foreach($tag_id as $data){
				$where_tag[] = $data;
			}
		}

		if($result)
				for($i=0;$i<count($result);$i++){						
						foreach($result[$i] as $key => $val){
								if($key == "news_id") $news_id = $val;
						}
						if($curid != $news_id) $where_newsid[] = $news_id;
				}
			
				//$this->db->select('_tag_pair.ref_id as news_id, _tag.* ');
				$this->db->select('_tag_pair.ref_id as news_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
				$this->db->from('_tag');
				//$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = 1 AND `'.$prefix.'_tag_pair`.tag_id = '.$news_id);
				$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = 1');

				//$this->db->where('_tag_pair.tag_id', $news_id);
				$this->db->where('_tag.status', 1);
				$this->db->where('ref_id !=', $curid);

				if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
				if($where_newsid) $this->db->where_in('_tag_pair.ref_id', $where_newsid);
				//$this->db->where_not_in('_tag_pair.ref_id', $ref_id);

				$this->db->group_by("news_id"); 
				$this->db->order_by("NumberOfTag", "DESC"); 
				$this->db->limit(5);

				//$this->db->where('lang', $language);
				$query2 = $this->db->get();
				//Debug($this->db->last_query());

				$getnews = $query2->result_object();
				//echo "getnews";
				//Debug($getnews);

				//if ไม่ข่าวไม่ถึง 5 จากดารา หาเพิ่ม
				if(count($getnews) < 5){

							//$get_add = 5 - count($getnews);

							$this->db->select('_tag_pair.ref_id as news_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
							$this->db->from('_tag');
							$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = 1');
							$this->db->where('_tag.status', 1);

							if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
							if($where_newsid) $this->db->where_not_in('_tag_pair.ref_id', $where_newsid);

							$this->db->where('_tag_pair.ref_id !=', $curid);
							$this->db->group_by("news_id"); 
							$this->db->order_by("NumberOfTag", "DESC"); 
							$this->db->limit(5);

							$other_query = $this->db->get();
							//Debug($this->db->last_query());

							$getnews_other = $other_query->result_object();
							//echo "getnews_other";
							//Debug($getnews_other);

				}

				if($getnews && $getnews_other)
					$getnews = array_merge($getnews, $getnews_other);
				else if($getnews_other)
					$getnews = $getnews_other;

				//echo "<hr>";
				//Debug($getnews);
				$getnews = $this->make_unique($getnews);
				//Debug($getnews);
				//array_unique($getnews);
				//$getnews = array_unique($getnews);
				//Debug($getnews);
				//die();
				return $getnews;
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

    public function save_relate($news_id, &$data = array()){
			$this->db->delete('_news_relate', array('ref_id' => $news_id));
			$insert = $this->db->insert_batch('_news_relate', $data);
			return $insert;
    }

    public function set_order_relate($ref_id, $news_id, &$data = array()){

			if($ref_id > 0){
					$this->db->where('ref_id', $ref_id);
					$this->db->where('news_id', $news_id);
					$this->db->update('_news_relate', $data);
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

    public function set_order_picture($ref_id, $picture_id, &$data = array()){

			if($ref_id > 0){
					$this->db->where('ref_id', $ref_id);
					$this->db->where('picture_id', $picture_id);
					$this->db->update('_picture', $data);
					//Debug($this->db->last_query());
					//echo "//".$picture_id;

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

	function set_order_picture_to_down($ref_id, $picture_id, $order, $tmp){
		
		$this->db->set('order', '`order` + 1', FALSE); 
		$this->db->where('order >=', $order); 
		$this->db->where('order <=', $tmp); 
		$this->db->where('ref_id', $ref_id);
		//$this->db->where('picture_id !=', $picture_id);

		$this->db->update('_picture');
		//Debug($this->db->last_query());
		//echo "//".$ref_id;

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}	

	function set_order_picture_to_up($ref_id, $picture_id, $order, $tmp){		

		$this->db->set('order', '`order` - 1', FALSE); 
		$this->db->where('order <=', $order); 
		$this->db->where('order >', $tmp); 
		$this->db->where('ref_id', $ref_id);
		//$this->db->where('picture_id !=', $picture_id);

		$this->db->update('_picture');
		//Debug($this->db->last_query());
		//echo "//".$ref_id;

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    public function get_relate($id){

			$prefix = 'sd';
			$language = $this->lang->language['lang'];
			$this->db->select('_news_relate.ref_id, _news_relate.order, _news.news_id, _news.news_id2, _news.title, _news.lastupdate_date,_picture.file_name, _picture.folder');
			$this->db->from('_news');
			$this->db->join('_news_relate', '_news_relate.news_id = _news.news_id2');
			$this->db->join('_picture', '_news.news_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 1 AND '.$prefix.'_picture.`default`=1 ', 'left');
			$this->db->where('_news_relate.ref_id', $id);
			$this->db->where('lang', $language);
			$this->db->order_by("order", "ASC"); 

			$query = $this->db->get();
			$result = $query->result_object();
			//Debug($this->db->last_query());
			return $result;
	}

    public function get_news($id = null, $search_string = array(), $cat = null, $order = '_news.order_by', $order_type='ASC', $limit_start = 0, $listpage = 20, $sp = 0, $showdebug = 0){
		
		$language = $this->lang->language['lang'];
		$prefix = 'sd';
		$ref_type = 1;
		$this->db->select('_news.*, _category.category_name, _subcategory.subcategory_name, _news_highlight.news_highlight_id, _megamenu.id as megamenu_id, _picture.file_name, _picture.folder');

		//if(isset($id)){
				$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_news`.`create_by`) AS create_by_name');
				$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_news`.`lastupdate_by`) AS lastupdate_by_name');
				$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_news`.`approve_by`) AS approve_by_name');
		//}

		$this->db->from('_news');
		//$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'" and '.$prefix .'_news.lang = "'.$language.'"', 'left');		
		//$this->db->join('_subcategory', '_news.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'" and '.$prefix .'_news.lang = "'.$language.'"', 'left');

		$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix .'_category.lang = '.$prefix .'_news.lang', 'left');		
		$this->db->join('_subcategory', '_news.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = '.$prefix .'_news.lang', 'left');

		if($sp == 1){
			$this->db->join('_news_highlight', '_news.news_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type);
			$this->db->join('_megamenu', '_news.news_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		}else if($sp == 2){
			$this->db->join('_news_highlight', '_news.news_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_news.news_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type);
		}else{
			$this->db->join('_news_highlight', '_news.news_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_news.news_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');		
		}

		$this->db->join('_picture', '_news.news_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
		//$this->db->join('_admin', '_news.create_by = _admin.admin_id', 'left');

		$this->db->where('_news.lang', $language);
		$this->db->where('_news.status !=', 2);

		if($id != null && $id > 0){
			$this->db->where('news_id2', $id);
			//$this->db->where('news_id', $id);
		}

		//Debug($cat);
		if($cat){
			foreach($cat as $key => $val){
					if($key !== 'sp' && $key !== ''){
						if($val > 0) $this->db->where('_news.'.$key , $val);
					}
			}
		}

		//$this->db->where("_news.category_id <=", 3);
		//$this->db->where("_category.category_id", 'news');
		$this->db->where('( '.$prefix.'_news.category_id = 1 OR '.$prefix.'_news.category_id = 3 OR '.$prefix.'_news.category_id = 21 )');

		//if($id <= 0) $this->db->where('lang', $language);
		$array_kw = array();

		//Debug($search_string );
		$i = 0;
		if(isset($search_string)){
			//Debug($search_string);
			//$this->db->where('_news.pin <=', 0);
			foreach($search_string as $key => $val){
					//echo "$key => $val<br>";
					if($key === 'status'){
						$array_kw[$prefix.'_news.status'] = $val;
						$this->db->where($array_kw);
					}else if($key === 'approve'){
						$array_kw[$prefix.'_news.approve'] = $val;
						$this->db->where($array_kw);
						$this->db->where('_news.create_date >=', '2015-01-01');
					}else{
						$array_kw[$prefix.'_news.title'] = $val;
						$this->db->like($array_kw);
					}

					//$array_kw[$prefix.'_news.news_id2'] = $val;
					//$this->db->like('_news.title', $search_string);
					//if($i == 0) $this->db->like($array_kw);
					//else $this->db->or_like($array_kw);
					//$i++;

					
			}
			//$this->db->like($array_kw);
		}

		$this->db->order_by('_news.create_date', 'DESC');

		/*$this->db->order_by('_news.order_by', 'ASC');
		$this->db->order_by('_category.category_id', 'ASC');*/

		/*if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('_news.create_date', 'DESC');
		}*/

		//echo "($listpage, $limit_start)<br>";
		//if($listpage == 0) $listpage = 100;

		if($listpage != 0) $this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		if($showdebug == 1) Debug($this->db->last_query());
		//die();		
		return $query->result_array();
    }

	public function get_approve($cat = null, $hl = 1, $id = 0, $search_string = array(),$order = 'order_by', $order_type='Asc', $limit_start = 0, $listpage = 0,$showdebug = 0){
		
		$language = $this->lang->language['lang'];
		$prefix = 'sd';

		if($hl == 1) $this->db->select('_news_highlight.news_highlight_id');
		$this->db->select('_news.news_id, _news.news_id2, _news.title, _news.order_by, _news.countview, _news.create_date, _news.lastupdate_date,_news.category_id  ,_news.subcategory_id, _category.category_name, _subcategory.subcategory_name, _picture.file_name, _picture.folder');

		$this->db->from('_news');
		$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', '_news.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');
		$this->db->join('_picture', '_news.news_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 1 AND '.$prefix.'_picture.`default`=1 ', 'left');

		if($hl == 1) $this->db->join('_news_highlight', '_news.news_id2 = _news_highlight.news_id', 'left');

		$this->db->where('_news.lang', $language);
		$this->db->where('_news.status', 1);
		$this->db->where('_news.approve', 1);

		if($id > 0){
			$this->db->where('news_id2', $id);
		}

		if($cat)
			foreach($cat as $key => $val){
					if($val > 0) $this->db->where('_news.'.$key , $val);
			}

		//if($id <= 0) $this->db->where('lang', $language);
		$array_kw = array();

		if($search_string){
			//$this->db->where('_news.pin <=', 0);
			foreach($search_string as $key => $val){
					$array_kw[$prefix.'_news.title'] = $val;
					//$this->db->like('_news.title', $search_string);
					$this->db->like($array_kw);
			}
			//$this->db->like($array_kw);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('_news.create_date', $order_type);
		}

		//echo "($listpage, $limit_start)<br>";
		if($listpage == 0) $listpage = 100;
		if($listpage != 0) $this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		if($showdebug == 1) Debug($this->db->last_query());
		//die();		
		return $query->result_array();
    }

    function count_news($id_map=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_news');
		if($id_map != null && $id_map != 0){
			$this->db->where('news_id2', $id_map);
		}
		if($search_string){
			$this->db->like('first_name', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('create_date', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($id = 0, $data, $showdebug = 0){
			//echo "(dara_profile_id = $dara_profile_id)";
			//Debug($data);
			if(isset($data['tag_id'])){
					unset($data['tag_id']);
			}

			//die();

			if($id > 0){
					$this->db->where('news_id', $id);
					$this->db->update('_news', $data);
					$report = array();
					//$report['error'] = $this->db->_error_number();
					//$report['message'] = $this->db->_error_message();
					if($report !== 0){
						if($showdebug == 1) Debug($this->db->last_query());
						return true;
					}else{
						return false;
					}					
			}else{
					$insert = $this->db->insert('_news', $data);
					if($showdebug == 1) Debug($this->db->last_query());
					return $insert;
			}
	}

    function store2($id = 0, $data){

			if($id > 0){
					$this->db->where('news_id2', $id);
					$this->db->update('_news', $data);
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
			}else{
					$update_order = array(
						"order_by" => 99
					);
					$this->db->where('order_by >=', 99);
					$this->db->update('_news', $update_order);			
			}

	}

    public function add_batch(&$data = array(), $table = '_news_copy', $debug = 0){
					if($debug == 1) Debug($data);
					$insert = $this->db->insert_batch($table, $data);
					if($debug == 1) Debug($this->db->last_query());
					return $insert;
    }

    function get_highlight($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';

			$this->db->select('_news_highlight.*, _news.title, _category.category_name, _news.create_date as folder_news ,_news.create_date, _news.lastupdate_date, _news.countview, _picture.file_name, _picture.folder');
			//$this->db->select('_news_highlight.*, _news.title, _news.create_date as folder_news');
			$this->db->from('_news_highlight');
			$this->db->join('_news', '_news_highlight.news_id = _news.news_id2 and '.$prefix.'_news_highlight.ref_type = 1 ');
			$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_picture', '_news_highlight.news_id = _picture.ref_id and '.$prefix.'_picture.ref_type = 1 AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_news.news_id', $id);

			$this->db->where('_news_highlight.ref_type',1);
			$this->db->where('_news.lang', $language);

			$this->db->order_by('_news_highlight.order', 'Asc');
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    function setorder_highlight($id = 0, $data){

			if($id > 0){
					$this->db->where('news_id', $id);
					$this->db->where('ref_type', 1);
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

    function set_highlight($id = 0, $data){

			$this->db->select('*');
			$this->db->from('_news_highlight');
			$this->db->where('news_id', $id);
			$query = $this->db->get();
			//Debug($this->db->last_query());
			//Debug($query->num_rows);
			//die();
			if($query->num_rows == 0){
					$insert = $this->db->insert('_news_highlight', $data);
					//Debug($this->db->last_query());
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
					$this->db->where('ref_type', 1);
					$this->db->delete('_news_highlight'); 
			}
	}

    function get_megamenu($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';

			$this->db->select('_megamenu.*, _news.title, _category.category_name, _news.create_date as folder_news ,_news.create_date, _news.lastupdate_date, _news.countview, _picture.file_name, _picture.folder');
			//$this->db->select('_megamenu.*, _news.title, _news.create_date as folder_news');
			$this->db->from('_megamenu');
			$this->db->join('_news', '_megamenu.id = _news.news_id2 and '.$prefix.'_megamenu.ref_type = 1');
			$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_picture', '_megamenu.id = _picture.ref_id and '.$prefix.'_picture.ref_type = 1 AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_news.news_id', $id);

			$this->db->where('_megamenu.ref_type',1);
			$this->db->where('_news.lang', $language);

			$this->db->order_by('_megamenu.order', 'Asc');
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    function set_megamenu($id = 0, $data){

			$this->db->select('*');
			$this->db->from('_megamenu');
			$this->db->where('id', $id);
			$query = $this->db->get();
			//Debug($this->db->last_query());

			//Debug($query->num_rows);
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
					$this->db->where('ref_type', 1);
					$this->db->delete('_megamenu'); 
			}
	}

	function update_order($category_id, $news_id, $order = 1){

		$data['order_by'] = $order;
		$this->db->where('news_id2', $news_id);
		$this->db->where('category_id', $category_id);
		$this->db->update('_news', $data);
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

	function update_orderid_to_down($category_id, $order, $max){

		$this->db->set('order_by', 'order_by + 1', FALSE); 
		$this->db->where('order_by >=', $order); 
		$this->db->where('order_by <=', $max); 
		$this->db->where('category_id', $category_id);
		$this->db->update('_news');
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

	function update_orderid_to_up($category_id, $order, $min){

		$this->db->set('order_by', 'order_by - 1', FALSE); 
		$this->db->where('order_by >', $min); 
		$this->db->where('order_by <=', $order); 
		$this->db->where('category_id', $category_id);
		$this->db->update('_news');
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

	function update_orderadd($category_id){

		$this->db->set('order_by', 'order_by + 1', FALSE); 
		$this->db->where('category_id', intval($category_id));
		$this->db->where('order_by <', 100);
		$this->db->update('_news');
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

	function update_orderdel($category_id, $order){

		$this->db->set('order_by', 'order_by - 1', FALSE); 
		$this->db->where('order_by >', $order); 
		$this->db->where('order_by <', 100);
		$this->db->where('category_id', $category_id);
		$this->db->update('_news');

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function status_news($id, $enable = 1){

		$data['status'] = $enable;
		$data['lastupdate_by'] = $this->session->userdata('admin_id');

		$this->db->where('news_id2', $id);
		$this->db->update('_news', $data);
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

    function set_default($id, $ref_id){

		$data = array(
				'default' => 0
		);
		$this->db->where('ref_id', $ref_id);
		$this->db->where('ref_type', 1);
		$this->db->update('_picture', $data);
		//Debug($this->db->last_query()); 

		$data = array(
				'default' => 1
		);
		$this->db->where('picture_id', $id);
		$this->db->where('ref_type', 1);
		$this->db->update('_picture', $data);
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
	
    function delete_news($id){

		$data = array(
				'status' => 2
		);
		$this->db->where('news_id2', $id);
		$this->db->update('_news', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    public function getdelete_news($id = null){

		$language = $this->lang->language['lang'];
		$prefix = 'sd';

		$this->db->select('_news.*, _category.category_name, _subcategory.subcategory_name, _picture.folder, _picture.file_name ');
		$this->db->from('_news');
		$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', '_news.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');
		$this->db->join('_picture', '_news.news_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 1 AND '.$prefix.'_picture.`default`=1 ', 'left');

		//$this->db->join('_news_highlight', '_news.news_id2 = _news_highlight.news_id', 'left');
		//if($lang != '') $this->db->where('_news.lang', $language);

		$this->db->where('_news.status =', 2);
		$this->db->where('_news.lang', $language);

		if($id != null && $id > 0){
			$this->db->where('news_id2', $id);
			//$this->db->where('news_id', $id);
		}

		/*if($cat)
			foreach($cat as $key => $val){
					if($val > 0) $this->db->where('_news.'.$key , $val);
			}*/

		//if($id <= 0) $this->db->where('lang', $language);
		/*if($search_string){
			$this->db->like('_news.title', $search_string);
		}*/

		/*if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('_news.create_date', $order_type);
		}*/

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

	function delete_news_admin($id){
		$this->db->where('news_id2', $id);
		$this->db->delete('_news'); 
	}
 
	function delete_relate_news($ref_id = 0, $news_id = 0){

		if($ref_id > 0) $this->db->where('ref_id', $ref_id);
		if($news_id > 0) $this->db->where('news_id', $news_id);
		$this->db->delete('_news_relate'); 

		$obj['lastupdate_date'] = date('Y-m-d H:i:s');
		$obj['lastupdate_by'] = $this->session->userdata('admin_id');
		$this->store2($ref_id , $obj);

	}

	function add_relate_news($ref_id = 0, $news_id = 0){

		$data = array(
			"ref_id" => $ref_id,
			"news_id" => $news_id,
			"order" => 0,
		);

		$obj['lastupdate_date'] = date('Y-m-d H:i:s');
		$obj['lastupdate_by'] = $this->session->userdata('admin_id');
		$this->store2($ref_id , $obj);

		$insert = $this->db->insert('_news_relate', $data);
		//echo "Add success.";

	}

	function update_order_relate($news_id, $ref_id, $order = 1){

		$data['order'] = $order;
		$this->db->where('news_id', $news_id);
		$this->db->where('ref_id', $ref_id);
		$this->db->update('_news_relate', $data);
		//Debug($this->db->last_query());

		$obj['lastupdate_date'] = date('Y-m-d H:i:s');
		$obj['lastupdate_by'] = $this->session->userdata('admin_id');
		$this->store2($news_id , $obj);

		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    public function get_pin($cat = null, $lang = '', $id = null, $search_string = array(), $order = '_news.pin', $order_type='Asc'){
		
		$language = $this->lang->language['lang'];
		$prefix = 'sd';

		$this->db->select('_news.news_id2, _news.title, _news.description, _news.lastupdate_date, _news.countview, 
		_news.pin, _news.pin_start_date, _news.pin_expire_date, 
		_category.category_name, _subcategory.subcategory_name');

		$this->db->from('_news');
		$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', '_news.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');

		//$this->db->join('_news_highlight', '_news.news_id2 = _news_highlight.news_id', 'left');
		//$this->db->join('_picture', '_news.news_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 1 AND '.$prefix.'_picture.`default`=1 ', 'left');

		if($lang != '') $this->db->where('_news.lang', $language);
		$this->db->where('_news.status !=', 2);

		$this->db->where('_news.pin >', 0);

		//AND NOW() BETWEEN pin_start_date AND pin_expire_date
		$this->db->where('now() BETWEEN pin_start_date AND pin_expire_date');

		if($id != null && $id > 0){
			$this->db->where('news_id2', $id);
			//$this->db->where('news_id', $id);
		}

		if($cat)
			foreach($cat as $key => $val){
					if($val > 0) $this->db->where('_news.'.$key , $val);
			}

		//if($id <= 0) $this->db->where('lang', $language);
		$array_kw = array();

		if($search_string){
			//$this->db->like('_news.title', $search_string);
			foreach($search_string as $key => $val){
					$array_kw[$prefix.'_news.title'] = $val;
					//$this->db->like('_news.title', $search_string);
					$this->db->like($array_kw);
			}
			//$this->db->like($array_kw);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('_news.create_date', $order_type);
		}

		$this->db->limit(20, 0);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		//die();		
		return $query->result_object();
    }

    public function set_order($order = 0, $neworder, $category_id = 0, $newsid = 0, $showdebug = 0){

			$this->db->set('order_by', $neworder); 
			$this->db->where('status', 1); 
			$this->db->where('approve', 1); 

			if($newsid != 0)
				$this->db->where('news_id2', intval($newsid));
			else 
				$this->db->where('order_by', $order); 

			if($category_id > 0) $this->db->where('category_id', $category_id);
			$this->db->update('_news');

			if($showdebug == 1) Debug($this->db->last_query());

			$report = array();
			//$report['error'] = $this->db->_error_number();
			//$report['message'] = $this->db->_error_message();

			if($report !== 0){
					return true;
			}else{
					return false;
			}
			
    }

    public function set_plusorder($order = 0, $curorder = 20, $category_id = 0, $showdebug = 0){

			$this->db->set('order_by', 'order_by + 1', FALSE); 
			$this->db->where('order_by >=', $order); 
			$this->db->where('order_by <=', $curorder); 
			$this->db->where('category_id', $category_id);
			$this->db->update('_news');

			if($showdebug == 1) Debug($this->db->last_query());

			$report = array();
			//$report['error'] = $this->db->_error_number();
			//$report['message'] = $this->db->_error_message();

			if($report !== 0){
					return true;
			}else{
					return false;
			}	
	}

    /*public function set_auto_order($news_id, &$data = array()){
			$insert = $this->db->update_batch('_news_relate', $data);
			return $insert;
    }*/

    public function import($page = 1, $page_size = 10){

				$get_url = "http://www.siamdara.com/apisiamdara/select_newsdara_v2.php?page_size=".$page_size;
				$jsonData = file_get_contents($get_url);
				$jsonobj = json_decode($jsonData, true);

				return $jsonobj;
	}

    public function json_zoonnews($page = 1, $page_size = 10){

				$get_url = "http://www.siamdara.com/apisiamdara/select_zoonnews.php?page_size=".$page_size;
				$jsonData = file_get_contents($get_url);
				$jsonobj = json_decode($jsonData, true);

				return $jsonobj;
	}
/*
UPDATE sd_news
SET description = REPLACE(description, 'http://dara.siamdara.com', 'http://www.siamdara.com'),
detail = REPLACE(detail, 'http://dara.siamdara.com', 'http://www.siamdara.com')    
WHERE description LIKE '%http://dara.siamdara.com%'
OR detail LIKE '%http://dara.siamdara.com%'
*/
}
?>	
