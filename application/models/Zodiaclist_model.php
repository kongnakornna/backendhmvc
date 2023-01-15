<?php
class Zodiaclist_model extends CI_Model {
 
    public function __construct(){
			parent::__construct();
    }

    public function get_status($id){    
			//$language = $this->lang->language['lang'];
			$this->db->select('title, status, approve');
			$this->db->from('_zodiac_list');
			$this->db->where('zid2', $id);
			//$this->db->where('lang', $language);
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_array();
    }

    public function get_order($id, $lang = ''){
			//$language = $this->lang->language['lang'];
			$this->db->select('title, order_by, status, approve');
			$this->db->from('_zodiac_list');
			$this->db->where('zid2', $id);
			if($lang != '') $this->db->where('lang', $lang);
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_object();
    }

    public function get_max_id(){
			//$language = $this->lang->language['lang'];
			$this->db->select('max(zid) as max_id');
			$this->db->from('_zodiac_list');
			//$this->db->where('lang', $language);
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_array(); 
    }

    public function get_max_order($category_id){
			$this->db->select('max(order_by) as max_order');
			$this->db->from('_zodiac_list');
			$this->db->where('category_id', $category_id);
			$query = $this->db->get();
			return $query->result_object(); 
    }

    public function countcolumn($category_id = 0, $subcategory_id = 0){

			$language = $this->lang->language['lang'];

			$this->db->select('count(zid) as countcolumn');
			$this->db->from('_zodiac_list');

			if($category_id != 0) $this->db->where('category_id', $category_id);
			if($subcategory_id != 0) $this->db->where('subcategory_id', $subcategory_id);

			$this->db->where('lang', $language);
			$this->db->where('status !=', 2);
			$this->db->where('status !=', 9);
			$query = $this->db->get();
			//Debug($this->db->last_query());
			$result = $query->result_object();
			//Debug($result[0]->countnews);
			return $result[0]->countcolumn;

    }

    public function get_zodiac_list_by_id($id_map){

			//$language = $this->lang->language['lang'];
			$this->db->select('*');
			$this->db->from('_zodiac_list');
			$this->db->where('zid2', $id_map);
			//$this->db->where('lang', $language);
			$query = $this->db->get();
			
			//echo $this->db->last_query();
			return $query->result_array(); 

    }
    public function get_catcolumn($catid = null, $order = '_zodiac_list.order_by', $order_type='Asc', $limit_start = 0, $listpage = 99){
		
		$language = $this->lang->language;
		$prefix = 'sd';
		$ref_type = 2;

		$this->db->select('_zodiac_list.*, _category.category_name, _picture.file_name, _picture.folder');
		$this->db->from('_zodiac_list');
		$this->db->join('_category', '_zodiac_list.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language['lang'].'"', 'left');
		//$this->db->join('_subcategory', '_zodiac_list.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');
		//$this->db->join('_highlight', '_zodiac_list.zid2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type, 'left');
		//$this->db->join('_megamenu', '_zodiac_list.zid2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		$this->db->join('_picture', '_zodiac_list.zid2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
		
		$this->db->where('_zodiac_list.lang', $language['lang']);
		$this->db->where('_zodiac_list.status !=', 2);
		$this->db->where('_zodiac_list.status !=', 9);
		$this->db->where('_zodiac_list.category_id', $catid);
		$this->db->order_by('_zodiac_list.create_date', 'DESC');
		$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

    public function get_zodiac_list($id = null, $search_string = null, $status = 0, $sp = 0, $order = '_zodiac_list.create_date', $order_type='DESC', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];
		$prefix = 'sd';
		$ref_type = 2;

		//$sql ="SELECT dp.*, dt.dara_type_name FROM sd_dara_profile as dp INNER JOIN sd_dara_type as dt ON (dp.dara_type_id=dt.dara_type_id_map AND dt.lang='".$language."')";
		//$this->db->query($sql);

		$this->db->select('_zodiac_list.*, _category.category_name, _subcategory.subcategory_name, _highlight.highlight_id, _megamenu.id as megamenu_id, _picture.file_name, _picture.folder');

		//if(isset($id)){
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_zodiac_list.create_by) AS create_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_zodiac_list.lastupdate_by) AS lastupdate_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_zodiac_list.approve_by) AS approve_by_name');
		//}

		$this->db->from('_zodiac_list');

		$this->db->join('_category', '_zodiac_list.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', '_zodiac_list.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');

		if($sp == 1){
			$this->db->join('_highlight', '_zodiac_list.zid2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type);
			$this->db->join('_megamenu', '_zodiac_list.zid2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		}else if($sp == 2){
			$this->db->join('_highlight', '_zodiac_list.zid2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_zodiac_list.zid2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type);
		}else{
			$this->db->join('_highlight', '_zodiac_list.zid2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_zodiac_list.zid2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		}

		$this->db->join('_picture', '_zodiac_list.zid2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
		
		if($id == null) $this->db->where('_zodiac_list.lang', $language);

		if($status > 0){
			if($status == 3)
				$this->db->where('_zodiac_list.status', 0);
			else
				$this->db->where('_zodiac_list.status', $status);
		}

		$this->db->where('_zodiac_list.status !=', 2);
		$this->db->where('_zodiac_list.status !=', 9);

		if($id != null && $id > 0){
			$this->db->where('zid2', $id);
		}

		/*if($search_string){
			$this->db->like('_zodiac_list.title', $search_string);
		}*/
		//Debug($search_string);

		if(is_array($search_string)){
			foreach($search_string as $key => $val){
					//echo "$key => $val <br>";
					
					if(($key === "category_id") || ($key === "subcategory_id")){
							$array_kw['_zodiac_list.'.$key] = $val;
							//$this->db->where('_zodiac_list.'.$key, $val);
							$this->db->where($array_kw);

					}else if($key === 'status'){
							$array_kw['_zodiac_list.status'] = $val;
							$this->db->where($array_kw);
					}else if($key === 'approve'){
							$array_kw['_zodiac_list.approve'] = $val;
							$this->db->where($array_kw);
							$this->db->where('_zodiac_list.create_date >=', '2015-01-01');
					}else if($key === 'zodiac_date'){
							$array_kw['_zodiac_list.zodiac_date'] = $val;
							$this->db->where($array_kw);
					}else{
							//$array_kw['_zodiac_list.'.$key] = $val;
							$array_kw['_zodiac_list.title'] = $val;
							//$this->db->where($array_kw);
							$this->db->like('_zodiac_list.title', $val);
					}
			}
			//$this->db->like($array_kw);
		}else{
			$this->db->like('_zodiac_list.title', $search_string);		
		}

		//if($search_string == null && $id == 0) $this->db->where('_zodiac_list.subcategory_id !=', 39);
		
		if($id > 0){
				$this->db->order_by('_zodiac_list.zid', 'ASC');
		}else{
				if($order){
					$this->db->order_by($order, $order_type);
				}else{
					$this->db->order_by('_zodiac_list.create_date', $order_type);
				}		
		}

		$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

    public function get_approve($filter_key = array(),$id = null, $lang = '', $order = '_zodiac_list.order_by', $order_type='Asc', $limit_start = 0, $listpage = 20, $nosubcat = 0){
		
		$language = $this->lang->language['lang'];
		$prefix = 'sd';
		$ref_type = 2;

		$this->db->select('_zodiac_list.*, _category.category_name, _subcategory.subcategory_name, _highlight.highlight_id, _megamenu.id as megamenu_id, _picture.file_name, _picture.folder');

		$this->db->from('_zodiac_list');
		$this->db->join('_category', '_zodiac_list.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', '_zodiac_list.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');

		$this->db->join('_highlight', '_zodiac_list.zid2 = _highlight.article_id and '.$prefix.'_highlight.ref_type = '.$ref_type, 'left');
		$this->db->join('_megamenu', '_zodiac_list.zid2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		$this->db->join('_picture', '_zodiac_list.zid2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
		
		if($lang != '') $this->db->where('_zodiac_list.lang', $language);

		$this->db->where('_zodiac_list.status =', 1);
		$this->db->where('_zodiac_list.approve =', 1);

		if($nosubcat  != 0) $this->db->where('_zodiac_list.subcategory_id !=', $nosubcat);

		if($id != null && $id > 0){
			$this->db->where('zid2', $id);
			//$this->db->where('article_id', $id);
		}
		//if($id <= 0) $this->db->where('lang', $language);

		/*if($search_string){
			$this->db->like('_zodiac_list.title', $search_string);
		}*/

		if(isset($filter_key)){
			foreach($filter_key as $key => $val){
					$array_kw['_zodiac_list.'.$key] = $val;
					$this->db->where($array_kw);
			}
			//$this->db->like($array_kw);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('_zodiac_list.create_date', $order_type);
		}

		$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();
		//Debug($this->db->last_query());

		return $query->result_array();
    }

    function count_zodiac_list($id_map=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_zodiac_list');
		if($id_map != null && $id_map != 0){
			$this->db->where('columnist_id', $id_map);
		}
		if($search_string){
			$this->db->like('columnist_name', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('create_date', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($id = 0, $data){
			if($id > 0){
					$this->db->where('zid', $id);
					$this->db->update('_zodiac_list', $data);
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
					$insert = $this->db->insert('_zodiac_list', $data);
					//Debug($this->db->last_query());
					return $insert;
			}
	}

    function store2($id = 0, $data){
			if($id > 0){
					$this->db->where('zid2', $id);
					$this->db->update('_zodiac_list', $data);
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
	}

    function get_highlight($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';

			$ref_type = 2;

			$this->db->select('_highlight.*, _zodiac_list.title, _category.category_name, _zodiac_list.create_date as folder_news ,_zodiac_list.create_date, _zodiac_list.lastupdate_date, _zodiac_list.countview, _picture.file_name, _picture.folder');
			//$this->db->select('_highlight.*, _news.title, _news.create_date as folder_news');
			$this->db->from('_highlight');
			$this->db->join('_zodiac_list', '_highlight.article_id = _zodiac_list.zid2 and '.$prefix.'_highlight.ref_type = '.$ref_type);
			$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_picture', '_highlight.article_id = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_news.news_id', $id);

			$this->db->where('_highlight.ref_type',$ref_type);
			$this->db->where('_zodiac_list.lang', $language);

			$this->db->order_by('_highlight.order', 'Asc');
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    function set_highlight($id = 0, $data){

			$this->db->select('*');
			$this->db->from('_highlight');
			$this->db->where('article_id', $id);
			$this->db->where('ref_type', 2);
			$query = $this->db->get();
			if($query->num_rows == 0){
					$insert = $this->db->insert('_highlight', $data);
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
					$this->db->where('ref_type', 2);
					$this->db->delete('_highlight'); 
			}
	}

    function get_megamenu($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';

			$ref_type = 2;

			$this->db->select('_megamenu.*, _zodiac_list.title, _category.category_name, _zodiac_list.create_date as folder_news ,_zodiac_list.create_date, _zodiac_list.lastupdate_date, _zodiac_list.countview, _picture.file_name, _picture.folder');
			//$this->db->select('_highlight.*, _news.title, _news.create_date as folder_news');
			$this->db->from('_megamenu');
			$this->db->join('_zodiac_list', '_highlight.article_id = _zodiac_list.zid2 and '.$prefix.'_highlight.ref_type = '.$ref_type);
			$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_picture', '_highlight.article_id = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_zodiac_list.zid', $id);

			$this->db->where('_highlight.ref_type',$ref_type);
			$this->db->where('_zodiac_list.lang', $language);

			$this->db->order_by('_highlight.order', 'Asc');
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    function set_megamenu($id = 0, $data){

			$this->db->select('*');
			$this->db->from('_megamenu');
			$this->db->where('id', $id);
			$this->db->where('ref_type', 2);
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
					$this->db->where('ref_type', 2);
					$this->db->delete('_megamenu'); 
			}
	}

	function status_zodiac_list($id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('zid2', $id);
		$this->db->update('_zodiac_list', $data);
		
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
		$this->db->where('ref_type', 2);
		$this->db->update('_picture', $data);
		//Debug($this->db->last_query()); 

		$data = array(
				'default' => 1
		);
		$this->db->where('picture_id', $id);
		$this->db->where('ref_type', 2);
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

	function update_order($zid, $order = 1){

		$data['order_by'] = $order;
		$this->db->where('zid2', $zid);
		$this->db->update('_zodiac_list', $data);
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

	function update_orderid_to_down($order, $max){

		$this->db->set('order_by', 'order_by + 1', FALSE); 
		$this->db->where('order_by >=', $order); 
		$this->db->where('order_by <=', $max); 
		$this->db->update('_zodiac_list');
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

	function update_orderid_to_up($order, $min){

		$this->db->set('order_by', 'order_by - 1', FALSE); 
		$this->db->where('order_by >', $min); 
		$this->db->where('order_by <=', $order); 
		$this->db->update('_zodiac_list');
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
	
    function delete_zodiac_list($id){

		$data = array(
				'status' => 9
		);
		$this->db->where('zid2', $id);
		$this->db->update('_zodiac_list', $data);
		
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
		$this->db->from('_zodiac_list d');
		$this->db->join('_category', 'd.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', 'd.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');
		$this->db->join('_picture', 'd.zid2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 2 AND '.$prefix.'_picture.`default`=1 ', 'left');
		$this->db->where('d.status =', 2);
		$this->db->where('d.lang', $language);

		if($id != null && $id > 0){
			$this->db->where('d.zid2', $id);
		}

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

	function delete_zodiac_list_admin($id){
		$this->db->where('zid2', $id);
		$this->db->delete('_zodiac_list'); 
		//Debug($this->db->last_query());
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

    public function set_order($order = 0, $neworder, $category_id = '', $columnid = ''){

			$this->db->set('order_by', $neworder); 
			$this->db->where('status', 1); 
			$this->db->where('approve', 1); 

			if($columnid != '') 
				$this->db->where('zid2', $columnid); 
			else 
				$this->db->where('order_by', $order); 

			$this->db->where('category_id', $category_id);
			$this->db->update('_zodiac_list');

			if($this->session->userdata('admin_id') == 1) Debug($this->db->last_query());

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
		$this->db->update('_zodiac_list');
		if($this->session->userdata('admin_id') == 1) Debug($this->db->last_query());
	}

    public function set_delorder($minorder, $maxorder, $category_id){
		$this->db->set('order_by', 'order_by - 1', FALSE); 
		$this->db->where('category_id', $category_id);
		$this->db->where('order_by >', $maxorder);
		$this->db->where('order_by <=', $minorder);
		$this->db->update('_zodiac_list');
		if($this->session->userdata('admin_id') == 1) Debug($this->db->last_query());
	}

	function add_relate_zodiac_list($ref_id = 0, $zid = 0, $table = '_zodiac_list_relate'){

		$data = array(
			"ref_id" => $ref_id,
			"zid" => $zid,
			"order" => 0,
		);

		$insert = $this->db->insert($table, $data);
		echo "Add success.";
		//Debug($this->db->last_query());

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

	function update_order_relate($zid, $ref_id, $order = 1, $table = '_zodiac_list_relate'){

		$data['order'] = $order;
		$this->db->where('zid', $zid);
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

	function update_orderadd($category_id){

		$this->db->set('order_by', 'order_by + 1', FALSE); 
		$this->db->where('category_id', $category_id);
		$this->db->update('_zodiac_list');

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
		$this->db->where('category_id', $category_id);
		$this->db->update('_zodiac_list');

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
