<?php
class Article_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function get_status($id, $lang = ''){
    	//$language = $this->lang->language['lang'];
    	$this->db->select('article_id2, title, create_date, status, approve');
    	$this->db->from('_article');
    	$this->db->where('article_id2', $id);
    	if($lang != '') $this->db->where('lang', $lang);
    	$query = $this->db->get();
		//Debug($this->db->last_query());
    	return $query->result_array();
    }

    public function get_order($id = 0, $lang = '', $order = 0, $cat_id = 0, $subcat_id = 0){
			//$language = $this->lang->language['lang'];
			$this->db->select('article_id2, title, order_by, status, approve');
			$this->db->from('_article');

			if($id != 0) $this->db->where('article_id2', $id);
			if($order != 0) $this->db->where('order_by', $order);
			if($cat_id != 0) $this->db->where('category_id', $cat_id);
			//if($subcat_id != 0) $this->db->where('subcategory_id', $subcat_id);

			if($lang != '') $this->db->where('lang', $lang);
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
    }

    /*public function count_article($table = '_article'){
		$language = $this->lang->language['lang'];
		$this->db->select('count(article_id) as number');
		$this->db->from($table);
		$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_object(); 
    }*/

    public function get_max_id($table = '_article'){
		//$language = $this->lang->language['lang'];
		$this->db->select('max(article_id) as max_id');
		$this->db->from($table);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_max_order($category_id){
		//$language = $this->lang->language['lang'];
		$this->db->select('max(order_by) as max_order');
		$this->db->from('_article');
		$this->db->where('category_id', $category_id);
		$query = $this->db->get();
		return $query->result_object(); 
    }

    public function countarticle($category_id = 0, $subcategory_id = 0, $access_cat = null){

		$language = $this->lang->language['lang'];

		$this->db->select('count(article_id) as countarticle');
		$this->db->from('_article');
		if($category_id != 0) $this->db->where('category_id', $category_id);
		if($subcategory_id != 0) $this->db->where('subcategory_id', $subcategory_id);

		$this->db->where('lang', $language);
		$this->db->where('status !=', 2);

		if($this->session->userdata('admin_type') > 3){
				if($access_cat && count($access_cat) == 1){
						foreach($access_cat as $arr => $val){
								$this->db->where('category_id', intval($val));
						}
				}				
		}

		$query = $this->db->get();
		//Debug($this->db->last_query());
		$result = $query->result_object();
		//Debug($result[0]->countarticle);
		return $result[0]->countarticle;

    }

    public function get_article_by_id($id_map){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_article');
		$this->db->where('article_id2', $id_map);
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
		$getarticle_other = array();
		/********************************/
		$this->db->select('_article.article_id2 as article_id, _article.title, _article.lang, _article.create_date');
		$this->db->from('_article');
		$this->db->where('_article.status', 1);
		$this->db->where('_article.approve', 1);
		$this->db->where('lang', $language);
		//$this->db->where('dara_id', $ref_id);
		$this->db->where('article_id2 !=', $curid);
		$this->db->order_by('create_date', 'DESC');
		$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		//die();
		$result = $query->result_object();
		//Debug($result);

		$where_tag = $where_articleid = array();
		if($tag_id){
			foreach($tag_id as $data){
				$where_tag[] = $data;
			}
		}

		if($result)
				for($i=0;$i<count($result);$i++){						
						foreach($result[$i] as $key => $val){
								if($key == "article_id") $article_id = $val;
						}
						if($curid != $article_id) $where_articleid[] = $article_id;
				}
			
				//$this->db->select('_tag_pair.ref_id as article_id, _tag.* ');
				$this->db->select('_tag_pair.ref_id as article_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
				$this->db->from('_tag');
				//$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = 1 AND `'.$prefix.'_tag_pair`.tag_id = '.$article_id);
				$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = 1');

				//$this->db->where('_tag_pair.tag_id', $article_id);
				$this->db->where('_tag.status', 1);
				$this->db->where('ref_id !=', $curid);

				if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
				if($where_articleid) $this->db->where_in('_tag_pair.ref_id', $where_articleid);
				//$this->db->where_not_in('_tag_pair.ref_id', $ref_id);

				$this->db->group_by("article_id"); 
				$this->db->order_by("NumberOfTag", "DESC"); 
				$this->db->limit(5);

				//$this->db->where('lang', $language);
				$query2 = $this->db->get();
				//Debug($this->db->last_query());

				$getarticle = $query2->result_object();
				//echo "getarticle";
				//Debug($getarticle);

				//if ไม่ข่าวไม่ถึง 5 จากดารา หาเพิ่ม
				if(count($getarticle) < 5){

							//$get_add = 5 - count($getarticle);

							$this->db->select('_tag_pair.ref_id as article_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
							$this->db->from('_tag');
							$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = 1');
							$this->db->where('_tag.status', 1);

							if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
							if($where_articleid) $this->db->where_not_in('_tag_pair.ref_id', $where_articleid);

							$this->db->where('_tag_pair.ref_id !=', $curid);
							$this->db->group_by("article_id"); 
							$this->db->order_by("NumberOfTag", "DESC"); 
							$this->db->limit(5);

							$other_query = $this->db->get();
							//Debug($this->db->last_query());

							$getarticle_other = $other_query->result_object();
							//echo "getarticle_other";
							//Debug($getarticle_other);

				}

				if($getarticle && $getarticle_other)
					$getarticle = array_merge($getarticle, $getarticle_other);
				else if($getarticle_other)
					$getarticle = $getarticle_other;

				//echo "<hr>";
				//Debug($getarticle);
				$getarticle = $this->make_unique($getarticle);
				//Debug($getarticle);
				//array_unique($getarticle);
				//$getarticle = array_unique($getarticle);
				//Debug($getarticle);
				//die();
				return $getarticle;
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

    public function save_relate($article_id, &$data = array()){
			$this->db->delete('_article_relate', array('ref_id' => $article_id));
			$insert = $this->db->insert_batch('_article_relate', $data);
			return $insert;
    }

    public function set_order_relate($ref_id, $article_id, &$data = array()){

			if($ref_id > 0){
					$this->db->where('ref_id', $ref_id);
					$this->db->where('article_id', $article_id);
					$this->db->update('_article_relate', $data);
					$report = array();
					$report['error'] = $this->db->_error_number();
					$report['message'] = $this->db->_error_message();
					if($report !== 0){
						//Debug($this->db->last_query());
						return true;
					}else{
						return false;
					}
			}
    }

    public function set_order_picture($ref_id, $galleryset_id, &$data = array()){

			if($ref_id > 0){
					$this->db->where('ref_id', $ref_id);
					$this->db->where('galleryset_id', $galleryset_id);
					$this->db->update('_gallery_set', $data);
					//Debug($this->db->last_query());
					//echo "//".$picture_id;

					$report = array();
					$report['error'] = $this->db->_error_number();
					$report['message'] = $this->db->_error_message();
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
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
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
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    public function get_relate($id){

			$prefix = 'sd';
			$language = $this->lang->language['lang'];
			$this->db->select('_article_relate.ref_id, _article_relate.order, _article.article_id, _article.article_id2, _article.title, _article.lastupdate_date,_gallery_set.file_name, _gallery_set.folder');
			$this->db->from('_article');
			$this->db->join('_article_relate', '_article_relate.article_id = _article.article_id2');
			$this->db->join('_gallery_set', '_article.article_id2 = _gallery_set.ref_id and '.$prefix.'_gallery_set.ref_type = 1 AND '.$prefix.'_gallery_set.`default`=1 ', 'left');
			$this->db->where('_article_relate.ref_id', $id);
			$this->db->where('lang', $language);
			$this->db->order_by("order", "ASC"); 

			$query = $this->db->get();
			$result = $query->result_object();
			//Debug($this->db->last_query());
			return $result;
	}

    public function get_article($id = null, $search_string = array(), $cat = null, $order = '_article.order_by', $order_type='ASC', $limit_start = 0, $listpage = 20, $sp = 0, $showdebug = 0){
		
		$language = $this->lang->language['lang'];
		$prefix = 'sd';
		$ref_type = 1;
		//Debug($this->config);
		//die();

		$this->db->select('_article.*, _category.category_name, _subcategory.subcategory_name, _gallery_set.file_name, _gallery_set.folder');
		$this->db->select('_highlight.highlight_id, _megamenu.id as megamenu_id, _editor_picks.article_id as editor_picks_id');
		$this->db->select('_columnist.columnist_name');

		//if(isset($id)){
				$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_article`.`create_by`) AS create_by_name');
				$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_article`.`lastupdate_by`) AS lastupdate_by_name');
				$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_article`.`approve_by`) AS approve_by_name');
		//}
		//$this->db->select('_up18.u18_id AS is_18');

		$this->db->from('_article');

		$this->db->join('_category', '_article.category_id = _category.category_id_map and '.$prefix .'_category.lang = '.$prefix .'_article.lang', 'left');		
		$this->db->join('_subcategory', '_article.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = '.$prefix .'_article.lang', 'left');

		if($sp == 1){ // highlight only
			$this->db->join('_highlight', '_article.article_id2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type);
			$this->db->join('_megamenu', '_article.article_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
			$this->db->join('_editor_picks', '_article.article_id2 = _editor_picks.article_id and '.$prefix.'_editor_picks.ref_type = '.$ref_type, 'left');
		}else if($sp == 2){ // megamenu only
			$this->db->join('_highlight', '_article.article_id2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_article.article_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type);
			$this->db->join('_editor_picks', '_article.article_id2 = _editor_picks.article_id and '.$prefix.'_editor_picks.ref_type = '.$ref_type, 'left');		
		}else if($sp == 3){ // editor picks only
			$this->db->join('_highlight', '_article.article_id2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_article.article_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
			$this->db->join('_editor_picks', '_article.article_id2 = _editor_picks.article_id and '.$prefix.'_editor_picks.ref_type = '.$ref_type);		
		}else{
			$this->db->join('_highlight', '_article.article_id2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_article.article_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');		
			$this->db->join('_editor_picks', '_article.article_id2 = _editor_picks.article_id and '.$prefix.'_editor_picks.ref_type = '.$ref_type, 'left');
		}

		$this->db->join('_columnist', '_article.columnist_id = _columnist.columnist_id and '.$prefix.'_columnist.status =1 ', 'left');
		//$this->db->join('_picture', '_article.article_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
		$this->db->join('_gallery_set', '_article.article_id2 = _gallery_set.ref_id and '.$prefix.'_gallery_set.ref_type = '.$ref_type.' AND '.$prefix.'_gallery_set.`default`=1 ', 'left');
		//$this->db->join('_admin', '_article.create_by = _admin.admin_id', 'left');
		//$this->db->join('_up18', '_article.article_id2 = _up18.ref_id and '.$prefix.'_up18.ref_type = '.$ref_type , 'left');

		if($id == null) $this->db->where('_article.lang', $language);
		$this->db->where('_article.status !=', 2);
		$this->db->where('_article.status !=', 9);

		if($id != null && $id > 0){
			$this->db->where('article_id2', $id);
			//$this->db->where('article_id', $id);
		}

		//Debug($cat);
		if($cat){
			foreach($cat as $key => $val){
				if($key !== 'sp' && $key !== ''){
					if($val > 0) $this->db->where('_article.'.$key , $val);
				}
			}
		}

		//$this->db->where("_article.category_id <=", 3);
		//$this->db->where("_category.category_id", 'article');
		//$this->db->where('( '.$prefix.'_article.category_id = 1 OR '.$prefix.'_article.category_id = 3 OR '.$prefix.'_article.category_id = 21 )');

		//if($id <= 0) $this->db->where('lang', $language);
		$array_kw = array();

		//Debug($search_string );
		$i = 0;

		if(isset($search_string)){
			//Debug($search_string);
			//$this->db->where('_article.pin <=', 0);
			foreach($search_string as $key => $val){
					//echo "$key => $val<br>";
					if($key === 'status'){
						$array_kw[$prefix.'_article.status'] = $val;
						$this->db->where($array_kw);
					}else if($key === 'approve'){
						$array_kw[$prefix.'_article.approve'] = $val;
						$this->db->where($array_kw);
						//$this->db->where('_article.create_date >=', '2015-01-01');
					}else{
						$where_arr = '';

						if(is_array($val)){
							foreach($val as $val2){
									//echo "val2 => $val2<br>";
									if($where_arr === '')
										$where_arr .= '('.$prefix.'_article.category_id = '.$val2;
									else
										$where_arr .= ' OR '.$prefix.'_article.category_id = '.$val2;

									//$array_kw[$prefix.'_article.category_id'][] = $val2;
							}
							$where_arr .= ')';
							$this->db->where($where_arr);

						}else{

							if($key === 'category_id'){
								$array_kw[$prefix.'_article.category_id'] = $val;
								$this->db->where($array_kw);
							}else{
								$array_kw[$prefix.'_article.title'] = $val;
								//$array_kw[$prefix.'_article.'.$key] = $val;
								$this->db->like($array_kw);
							}
						}
					}
					//$array_kw[$prefix.'_article.article_id2'] = $val;
					//$this->db->like('_article.title', $search_string);
					//if($i == 0) $this->db->like($array_kw);
					//else $this->db->or_like($array_kw);
					//$i++;
			}
			//$this->db->like($array_kw);
		}

		$this->db->order_by('_article.create_date', 'DESC');
		/*$this->db->order_by('_article.order_by', 'ASC');
		$this->db->order_by('_category.category_id', 'ASC');*/
		/*if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('_article.create_date', 'DESC');
		}*/
		//echo "($listpage, $limit_start)<br>";
		//if($listpage == 0) $listpage = 100;

		if($listpage != 0) $this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		if($showdebug == 1) Debug($this->db->last_query());
		//die();		
		return $query->result_array();
    }

	public function get_approve($cat = null, $hl = 1, $id = 0, $search_string = array(),$order = 'order_by', $order_type='Asc', $limit_start = 0, $listpage = 0, $sp = 0, $showdebug = 0){
		
		$language = $this->lang->language['lang'];
		$prefix = 'sd';
		$ref_type = 1;
		//Debug($this->config);
		//die();

		$this->db->select('_article.*, _category.category_name, _subcategory.subcategory_name, _gallery_set.file_name, _gallery_set.folder');
		$this->db->select('_highlight.highlight_id, _megamenu.id as megamenu_id');
		$this->db->select('_columnist.columnist_name');
		$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_article`.`create_by`) AS create_by_name');
		$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_article`.`lastupdate_by`) AS lastupdate_by_name');
		$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`'.$prefix.'_article`.`approve_by`) AS approve_by_name');
		$this->db->select('_up18.u18_id AS is_18');
		$this->db->from('_article');
		$this->db->join('_category', '_article.category_id = _category.category_id_map and '.$prefix .'_category.lang = '.$prefix .'_article.lang', 'left');		
		$this->db->join('_subcategory', '_article.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = '.$prefix .'_article.lang', 'left');

		if($sp == 1){
			$this->db->join('_highlight', '_article.article_id2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type);
			$this->db->join('_megamenu', '_article.article_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		}else if($sp == 2){
			$this->db->join('_highlight', '_article.article_id2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_article.article_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type);
		}else{
			$this->db->join('_highlight', '_article.article_id2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_article.article_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');		
		}

		$this->db->join('_columnist', '_article.columnist_id = _columnist.columnist_id and '.$prefix.'_columnist.status =1 ', 'left');
		//$this->db->join('_picture', '_article.article_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
		$this->db->join('_gallery_set', '_article.article_id2 = _gallery_set.ref_id and '.$prefix.'_gallery_set.ref_type = '.$ref_type.' AND '.$prefix.'_gallery_set.`default`=1 ', 'left');
		$this->db->join('_up18', '_article.article_id2 = _up18.ref_id and '.$prefix.'_up18.ref_type = '.$ref_type , 'left');

		if($id == null) $this->db->where('_article.lang', $language);

		$this->db->where('_article.status !=', 9);

		if($id != null && $id > 0){
			$this->db->where('article_id2', $id);
			//$this->db->where('article_id', $id);
		}

		//Debug($cat);
		if($cat){
			foreach($cat as $key => $val){
				if($key !== 'sp' && $key !== ''){
					if($val > 0) $this->db->where('_article.'.$key , $val);
				}
			}
		}

		//$this->db->where("_article.category_id <=", 3);
		//$this->db->where("_category.category_id", 'article');
		//$this->db->where('( '.$prefix.'_article.category_id = 1 OR '.$prefix.'_article.category_id = 3 OR '.$prefix.'_article.category_id = 21 )');

		//if($id <= 0) $this->db->where('lang', $language);
		$array_kw = array();

		//Debug($search_string );
		$i = 0;
		if(isset($search_string)){
			//Debug($search_string);
			//$this->db->where('_article.pin <=', 0);
			foreach($search_string as $key => $val){
					//echo "$key => $val<br>";
					if($key === 'status'){
						$array_kw[$prefix.'_article.status'] = $val;
						$this->db->where($array_kw);
					}else if($key === 'approve'){
						$array_kw[$prefix.'_article.approve'] = $val;
						$this->db->where($array_kw);
						//$this->db->where('_article.create_date >=', '2015-01-01');
					}else if($key === 0){
							$array_kw[$prefix.'_article.title'] = $val;
							$this->db->like($array_kw);
					}else{
						if($key != 'category_id'){
							$array_kw[$prefix.'_article.title'] = $val;
							$this->db->like($array_kw);
						}else{
							$array_kw[$prefix.'_article.'.$key] = $val;
							$this->db->where($array_kw);						
						}
					}

					//$array_kw[$prefix.'_article.article_id2'] = $val;
					//$this->db->like('_article.title', $search_string);
					//if($i == 0) $this->db->like($array_kw);
					//else $this->db->or_like($array_kw);
					//$i++;

			}
			//$this->db->like($array_kw);
		}

		//$this->db->where('_article.lang', $language);
		$this->db->where('_article.status', 1);
		$this->db->where('_article.approve', 1);

		$this->db->order_by('_article.create_date', 'DESC');

		/*$this->db->order_by('_article.order_by', 'ASC');
		$this->db->order_by('_category.category_id', 'ASC');*/

		/*if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('_article.create_date', 'DESC');
		}*/

		//echo "($listpage, $limit_start)<br>";
		//if($listpage == 0) $listpage = 100;

		if($listpage != 0) $this->db->limit($listpage, $limit_start);

		$query = $this->db->get();
		if($showdebug == 1) Debug($this->db->last_query());
		//die();		
		return $query->result_array();
    }

    function count_article($id_map=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_article');
		if($id_map != null && $id_map != 0){
			$this->db->where('article_id2', $id_map);
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
					$this->db->where('article_id', $id);
					$this->db->update('_article', $data);
					$report = array();
					$report['error'] = $this->db->_error_number();
					$report['message'] = $this->db->_error_message();
					if($report !== 0){
						if($showdebug == 1) Debug($this->db->last_query());
						return true;
					}else{
						return false;
					}					
			}else{
					$insert = $this->db->insert('_article', $data);
					if($showdebug == 1) Debug($this->db->last_query());
					return $insert;
			}
	}

    function store2($id = 0, $data){

			if($id > 0){
					$this->db->where('article_id2', $id);
					$this->db->update('_article', $data);
					//Debug($this->db->last_query());
					$report = array();
					$report['error'] = $this->db->_error_number();
					$report['message'] = $this->db->_error_message();
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
					$this->db->update('_article', $update_order);			
			}

	}

    public function add_batch(&$data = array(), $table = '_article_copy', $debug = 0){
					if($debug == 1) Debug($data);
					$insert = $this->db->insert_batch($table, $data);
					if($debug == 1) Debug($this->db->last_query());
					return $insert;
    }

    function get_highlight($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';

			$this->db->select('_highlight.*, _article.title, _category.category_name, _article.create_date as folder_article ,_article.create_date, _article.lastupdate_date, _article.countview, _picture.file_name, _picture.folder');
			//$this->db->select('_highlight.*, _article.title, _article.create_date as folder_article');
			$this->db->from('_highlight');
			$this->db->join('_article', '_highlight.article_id = _article.article_id2 and '.$prefix.'_highlight.ref_type = 1 ');
			$this->db->join('_category', '_article.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_picture', '_highlight.article_id = _picture.ref_id and '.$prefix.'_picture.ref_type = 1 AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_article.article_id', $id);

			$this->db->where('_highlight.ref_type',1);
			$this->db->where('_article.lang', $language);

			$this->db->order_by('_highlight.order', 'Asc');
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    function setorder_highlight($id = 0, $data){

			if($id > 0){
					$this->db->where('article_id', $id);
					$this->db->where('ref_type', 1);
					$this->db->update('_highlight', $data);
					$report = array();
					$report['error'] = $this->db->_error_number();
					$report['message'] = $this->db->_error_message();
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
			$this->db->from('_highlight');
			$this->db->where('article_id', $id);
			$query = $this->db->get();
			//Debug($this->db->last_query());
			//Debug($query->num_rows);
			//die();
			if($query->num_rows == 0){
					$insert = $this->db->insert('_highlight', $data);
					//Debug($this->db->last_query());
					return $insert;
			}
	}

    function update_order_highlight(){
		$this->db->set('order', '`order`  + 1', FALSE); 
		$this->db->update('_highlight');
	}

    function remove_highlight($id = 0){
			if($id > 0){
					$this->db->where('article_id', $id);
					$this->db->where('ref_type', 1);
					$this->db->delete('_highlight'); 
			}
	}

    function get_megamenu($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';

			$this->db->select('_megamenu.*, _article.title, _category.category_name, _article.create_date as folder_article ,_article.create_date, _article.lastupdate_date, _article.countview, _picture.file_name, _picture.folder');
			//$this->db->select('_megamenu.*, _article.title, _article.create_date as folder_article');
			$this->db->from('_megamenu');
			$this->db->join('_article', '_megamenu.id = _article.article_id2 and '.$prefix.'_megamenu.ref_type = 1');
			$this->db->join('_category', '_article.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_picture', '_megamenu.id = _picture.ref_id and '.$prefix.'_picture.ref_type = 1 AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_article.article_id', $id);

			$this->db->where('_megamenu.ref_type',1);
			$this->db->where('_article.lang', $language);

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
/*****************************************************/
    function set_editor_picks($id = 0, $data){

			$this->db->select('*');
			$this->db->from('_editor_picks');
			$this->db->where('article_id', $id);
			$query = $this->db->get();

			if($query->num_rows == 0){
					$insert = $this->db->insert('_editor_picks', $data);
					return $insert;
			}
	}

    function update_order_editor_picks(){
		$this->db->set('order', '`order`  + 1', FALSE); 
		$this->db->update('_editor_picks');
	}

    function remove_editor_picks($id = 0){
			if($id > 0){
					$this->db->where('article_id', $id);
					$this->db->where('ref_type', 1);
					$this->db->delete('_editor_picks');
					//Debug($this->db->last_query());
			}
	}
/*****************************************************/

	function update_order($category_id, $article_id, $order = 1){

		$data['order_by'] = $order;
		$this->db->where('article_id2', $article_id);
		$this->db->where('category_id', $category_id);
		$this->db->update('_article', $data);
		//Debug($this->db->last_query());

		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
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
		$this->db->update('_article');
		//Debug($this->db->last_query());

		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
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
		$this->db->update('_article');
		//Debug($this->db->last_query());

		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
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
		$this->db->update('_article');
		//Debug($this->db->last_query());

		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
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
		$this->db->update('_article');

		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function status_article($id, $enable = 1){

		$data['status'] = $enable;
		$data['lastupdate_by'] = $this->session->userdata('admin_id');

		$this->db->where('article_id2', $id);
		$this->db->update('_article', $data);
		return $this->db->last_query();
		
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
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
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}
	
    function delete_article($id){

		$data = array(
				'status' => 9
		);
		$this->db->where('article_id2', $id);
		$this->db->update('_article', $data);
		
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    public function getdelete_article($id = null){

		$language = $this->lang->language['lang'];
		$prefix = 'sd';

		$this->db->select('_article.*, _category.category_name, _subcategory.subcategory_name, _picture.folder, _picture.file_name ');
		$this->db->from('_article');
		$this->db->join('_category', '_article.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', '_article.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');
		$this->db->join('_picture', '_article.article_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 1 AND '.$prefix.'_picture.`default`=1 ', 'left');

		//$this->db->join('_highlight', '_article.article_id2 = _highlight.article_id', 'left');
		//if($lang != '') $this->db->where('_article.lang', $language);

		//$this->db->where('_article.status =', 2);
		$this->db->where('_article.status =', 9);
		$this->db->where('_article.lang', $language);

		if($id != null && $id > 0){
			$this->db->where('article_id2', $id);
			//$this->db->where('article_id', $id);
		}

		/*if($cat)
			foreach($cat as $key => $val){
					if($val > 0) $this->db->where('_article.'.$key , $val);
			}*/

		//if($id <= 0) $this->db->where('lang', $language);
		/*if($search_string){
			$this->db->like('_article.title', $search_string);
		}*/

		/*if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('_article.create_date', $order_type);
		}*/

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

	function delete_article_admin($id){
		$this->db->where('article_id2', $id);
		$this->db->delete('_article'); 
	}
 
	function delete_relate_article($ref_id = 0, $article_id = 0){

		if($ref_id > 0) $this->db->where('ref_id', $ref_id);
		if($article_id > 0) $this->db->where('article_id', $article_id);
		$this->db->delete('_article_relate'); 

		$obj['lastupdate_date'] = date('Y-m-d H:i:s');
		$obj['lastupdate_by'] = $this->session->userdata('admin_id');
		$this->store2($ref_id , $obj);

	}

	function add_relate_article($ref_id = 0, $article_id = 0){

		$data = array(
			"ref_id" => $ref_id,
			"article_id" => $article_id,
			"order" => 0,
		);

		$obj['lastupdate_date'] = date('Y-m-d H:i:s');
		$obj['lastupdate_by'] = $this->session->userdata('admin_id');
		$this->store2($ref_id , $obj);

		$insert = $this->db->insert('_article_relate', $data);
		//echo "Add success.";

	}

	function update_order_relate($article_id, $ref_id, $order = 1){

		$data['order'] = $order;
		$this->db->where('article_id', $article_id);
		$this->db->where('ref_id', $ref_id);
		$this->db->update('_article_relate', $data);
		//Debug($this->db->last_query());

		$obj['lastupdate_date'] = date('Y-m-d H:i:s');
		$obj['lastupdate_by'] = $this->session->userdata('admin_id');
		$this->store2($article_id , $obj);

		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    public function get_pin($cat = null, $lang = '', $id = null, $search_string = array(), $order = '_article.pin', $order_type='Asc'){
		
		$language = $this->lang->language['lang'];
		$prefix = 'sd';

		$this->db->select('_article.article_id2, _article.title, _article.description, _article.lastupdate_date, _article.countview, 
		_article.pin, _article.pin_start_date, _article.pin_expire_date, 
		_category.category_name, _subcategory.subcategory_name');

		$this->db->from('_article');
		$this->db->join('_category', '_article.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', '_article.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');

		//$this->db->join('_highlight', '_article.article_id2 = _highlight.article_id', 'left');
		//$this->db->join('_picture', '_article.article_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 1 AND '.$prefix.'_picture.`default`=1 ', 'left');

		if($lang != '') $this->db->where('_article.lang', $language);
		$this->db->where('_article.status !=', 2);

		$this->db->where('_article.pin >', 0);

		//AND NOW() BETWEEN pin_start_date AND pin_expire_date
		$this->db->where('now() BETWEEN pin_start_date AND pin_expire_date');

		if($id != null && $id > 0){
			$this->db->where('article_id2', $id);
			//$this->db->where('article_id', $id);
		}

		if($cat)
			foreach($cat as $key => $val){
					if($val > 0) $this->db->where('_article.'.$key , $val);
			}

		//if($id <= 0) $this->db->where('lang', $language);
		$array_kw = array();

		if($search_string){
			//$this->db->like('_article.title', $search_string);
			foreach($search_string as $key => $val){
					$array_kw[$prefix.'_article.title'] = $val;
					//$this->db->like('_article.title', $search_string);
					$this->db->like($array_kw);
			}
			//$this->db->like($array_kw);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('_article.create_date', $order_type);
		}

		$this->db->limit(20, 0);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		//die();		
		return $query->result_object();
    }

    public function set_order($order = 0, $neworder, $category_id = 0, $articleid = 0, $showdebug = 0){

			$this->db->set('order_by', $neworder); 
			$this->db->where('status', 1); 
			$this->db->where('approve', 1); 

			if($articleid != 0)
				$this->db->where('article_id2', intval($articleid));
			else 
				$this->db->where('order_by', $order); 

			if($category_id > 0) $this->db->where('category_id', $category_id);
			$this->db->update('_article');

			if($showdebug == 1) Debug($this->db->last_query());

			$report = array();
			$report['error'] = $this->db->_error_number();
			$report['message'] = $this->db->_error_message();

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
			$this->db->update('_article');

			if($showdebug == 1) Debug($this->db->last_query());

			$report = array();
			$report['error'] = $this->db->_error_number();
			$report['message'] = $this->db->_error_message();

			if($report !== 0){
					return true;
			}else{
					return false;
			}	
	}

    /*public function set_auto_order($article_id, &$data = array()){
			$insert = $this->db->update_batch('_article_relate', $data);
			return $insert;
    }*/


/*
UPDATE sd_article
SET description = REPLACE(description, 'http://dara.siamdara.com', 'http://www.siamdara.com'),
detail = REPLACE(detail, 'http://dara.siamdara.com', 'http://www.siamdara.com')    
WHERE description LIKE '%http://dara.siamdara.com%'
OR detail LIKE '%http://dara.siamdara.com%'
*/
}
?>	
