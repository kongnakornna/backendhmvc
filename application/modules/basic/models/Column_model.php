<?php
class Column_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function get_status($id){
    
    	//$language = $this->lang->language['lang'];
    
    	$this->db->select('title, status, approve');
    	$this->db->from('_column');
    	$this->db->where('column_id2', $id);
    	//$this->db->where('lang', $language);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    }

    public function get_order($id, $lang = ''){
			//$language = $this->lang->language['lang'];
			$this->db->select('title, order_by, status, approve');
			$this->db->from('_column');
			$this->db->where('column_id2', $id);
			if($lang != '') $this->db->where('lang', $lang);
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_object();
    }

    public function get_max_id(){

		//$language = $this->lang->language['lang'];
		$this->db->select('max(column_id) as max_id');
		$this->db->from('_column');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 

    }

    public function get_max_order($category_id){

		$this->db->select('max(order_by) as max_order');
		$this->db->from('_column');
		$this->db->where('category_id', $category_id);
		$query = $this->db->get();
		return $query->result_object(); 

    }
    public function countcolumn($category_id = 0, $subcategory_id = 0){

		$language = $this->lang->language['lang'];

		$this->db->select('count(column_id) as countcolumn');
		$this->db->from('_column');
		if($category_id != 0) $this->db->where('category_id', $category_id);
		if($subcategory_id != 0) $this->db->where('subcategory_id', $subcategory_id);

		$this->db->where('lang', $language);
		$this->db->where('status !=', 2);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		$result = $query->result_object();
		//Debug($result[0]->countnews);
		return $result[0]->countcolumn;

    }

    public function get_column_by_id($id_map){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_column');
		$this->db->where('column_id2', $id_map);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->result_array(); 

    }
    public function get_catcolumn($catid = null, $order = '_column.order_by', $order_type='Asc', $limit_start = 0, $listpage = 99){
		
		$language = $this->lang->language;
		$prefix = 'sd';
		$ref_type = 2;

		$this->db->select('_column.*, _category.category_name, _picture.file_name, _picture.folder');
		$this->db->from('_column');
		$this->db->join('_category', '_column.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language['lang'].'"', 'left');
		//$this->db->join('_subcategory', '_column.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');
		//$this->db->join('_news_highlight', '_column.column_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type, 'left');
		//$this->db->join('_megamenu', '_column.column_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		$this->db->join('_picture', '_column.column_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
		
		$this->db->where('_column.lang', $language['lang']);
		$this->db->where('_column.status !=', 2);
		$this->db->where('_column.category_id', $catid);
		$this->db->order_by('_column.create_date', 'DESC');
		$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

    public function get_column($id = null, $search_string = null, $status = 0, $sp = 0, $order = '_column.create_date', $order_type='DESC', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];
		$prefix = 'sd';
		$ref_type = 2;

		//$sql ="SELECT dp.*, dt.dara_type_name FROM sd_dara_profile as dp INNER JOIN sd_dara_type as dt ON (dp.dara_type_id=dt.dara_type_id_map AND dt.lang='".$language."')";
		//$this->db->query($sql);

		$this->db->select('_column.*, _category.category_name, _subcategory.subcategory_name, _news_highlight.news_highlight_id, _megamenu.id as megamenu_id, _picture.file_name, _picture.folder');

		//if(isset($id)){
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_column.create_by) AS create_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_column.lastupdate_by) AS lastupdate_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_column.approve_by) AS approve_by_name');
		//}

		$this->db->from('_column');

		$this->db->join('_category', '_column.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', '_column.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');

		if($sp == 1){
			$this->db->join('_news_highlight', '_column.column_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type);
			$this->db->join('_megamenu', '_column.column_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		}else if($sp == 2){
			$this->db->join('_news_highlight', '_column.column_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_column.column_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type);
		}else{
			$this->db->join('_news_highlight', '_column.column_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_column.column_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		}

		$this->db->join('_picture', '_column.column_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
		
		if($id == null) $this->db->where('_column.lang', $language);

		if($status > 0){
			if($status == 3)
				$this->db->where('_column.status', 0);
			else
				$this->db->where('_column.status', $status);
		}

		$this->db->where('_column.status !=', 2);

		if($id != null && $id > 0){
			$this->db->where('column_id2', $id);
		}

		/*if($search_string){
			$this->db->like('_column.title', $search_string);
		}*/
		//Debug($search_string);

		if(is_array($search_string)){
			foreach($search_string as $key => $val){
					//echo "$key => $val <br>";
					
					if(($key === "category_id") || ($key === "subcategory_id")){
							$array_kw['_column.'.$key] = $val;
							//$this->db->where('_column.'.$key, $val);
							$this->db->where($array_kw);

					}else if($key === 'status'){
							$array_kw['_column.status'] = $val;
							$this->db->where($array_kw);
					}else if($key === 'approve'){
							$array_kw['_column.approve'] = $val;
							$this->db->where($array_kw);
							$this->db->where('_column.create_date >=', '2015-01-01');
					}else if($key === 'zodiac_date'){
							$array_kw['_column.zodiac_date'] = $val;
							$this->db->where($array_kw);
					}else{
							//$array_kw['_column.'.$key] = $val;
							$array_kw['_column.title'] = $val;
							//$this->db->where($array_kw);
							$this->db->like('_column.title', $val);
					}
			}
			//$this->db->like($array_kw);
		}else{
			$this->db->like('_column.title', $search_string);		
		}

		if($search_string == null && $id == 0) $this->db->where('_column.subcategory_id !=', 39);
		
		if($id > 0){
				$this->db->order_by('_column.column_id', 'ASC');
		}else{
				if($order){
					$this->db->order_by($order, $order_type);
				}else{
					$this->db->order_by('_column.create_date', $order_type);
				}		
		}

		$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

    public function get_approve($filter_key = array(),$id = null, $lang = '', $order = '_column.order_by', $order_type='Asc', $limit_start = 0, $listpage = 20, $nosubcat = 0){
		
		$language = $this->lang->language['lang'];
		$prefix = 'sd';
		$ref_type = 2;

		$this->db->select('_column.*, _category.category_name, _subcategory.subcategory_name, _news_highlight.news_highlight_id, _megamenu.id as megamenu_id, _picture.file_name, _picture.folder');

		$this->db->from('_column');
		$this->db->join('_category', '_column.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', '_column.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');

		$this->db->join('_news_highlight', '_column.column_id2 = _news_highlight.news_id and '.$prefix.'_news_highlight.ref_type = '.$ref_type, 'left');
		$this->db->join('_megamenu', '_column.column_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		$this->db->join('_picture', '_column.column_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
		
		if($lang != '') $this->db->where('_column.lang', $language);

		$this->db->where('_column.status =', 1);
		$this->db->where('_column.approve =', 1);

		if($nosubcat  != 0) $this->db->where('_column.subcategory_id !=', $nosubcat);

		if($id != null && $id > 0){
			$this->db->where('column_id2', $id);
			//$this->db->where('news_id', $id);
		}
		//if($id <= 0) $this->db->where('lang', $language);

		/*if($search_string){
			$this->db->like('_column.title', $search_string);
		}*/

		if(isset($filter_key)){
			foreach($filter_key as $key => $val){
					$array_kw['_column.'.$key] = $val;
					$this->db->where($array_kw);
			}
			//$this->db->like($array_kw);
		}


		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('_column.create_date', $order_type);
		}

		$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();
		//Debug($this->db->last_query());

		return $query->result_array();
    }

    function count_column($id_map=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_column');
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
					$this->db->where('column_id', $id);
					$this->db->update('_column', $data);
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
					$insert = $this->db->insert('_column', $data);
					//Debug($this->db->last_query());
					return $insert;
			}
	}

    function store2($id = 0, $data){
			if($id > 0){
					$this->db->where('column_id2', $id);
					$this->db->update('_column', $data);
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

			$this->db->select('_news_highlight.*, _column.title, _category.category_name, _column.create_date as folder_news ,_column.create_date, _column.lastupdate_date, _column.countview, _picture.file_name, _picture.folder');
			//$this->db->select('_news_highlight.*, _news.title, _news.create_date as folder_news');
			$this->db->from('_news_highlight');
			$this->db->join('_column', '_news_highlight.news_id = _column.column_id2 and '.$prefix.'_news_highlight.ref_type = '.$ref_type);
			$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_picture', '_news_highlight.news_id = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_news.news_id', $id);

			$this->db->where('_news_highlight.ref_type',$ref_type);
			$this->db->where('_column.lang', $language);

			$this->db->order_by('_news_highlight.order', 'Asc');
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    function set_highlight($id = 0, $data){

			$this->db->select('*');
			$this->db->from('_news_highlight');
			$this->db->where('news_id', $id);
			$this->db->where('ref_type', 2);
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
					$this->db->where('ref_type', 2);
					$this->db->delete('_news_highlight'); 
			}
	}

    function get_megamenu($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';

			$ref_type = 2;

			$this->db->select('_megamenu.*, _column.title, _category.category_name, _column.create_date as folder_news ,_column.create_date, _column.lastupdate_date, _column.countview, _picture.file_name, _picture.folder');
			//$this->db->select('_news_highlight.*, _news.title, _news.create_date as folder_news');
			$this->db->from('_megamenu');
			$this->db->join('_column', '_news_highlight.news_id = _column.column_id2 and '.$prefix.'_news_highlight.ref_type = '.$ref_type);
			$this->db->join('_category', '_news.category_id = _category.category_id_map and '.$prefix.'_category.lang = "'.$language.'"', 'left');
			$this->db->join('_picture', '_news_highlight.news_id = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');

			if($id > 0) $this->db->where('_column.column_id', $id);

			$this->db->where('_news_highlight.ref_type',$ref_type);
			$this->db->where('_column.lang', $language);

			$this->db->order_by('_news_highlight.order', 'Asc');
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

	function status_column($id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('column_id2', $id);
		$this->db->update('_column', $data);
		
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

	function update_order($column_id, $order = 1){

		$data['order_by'] = $order;
		$this->db->where('column_id2', $column_id);
		$this->db->update('_column', $data);
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
		$this->db->update('_column');
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
		$this->db->update('_column');
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
	
    function delete_column($id){

		$data = array(
				'status' => 2
		);
		$this->db->where('column_id2', $id);
		$this->db->update('_column', $data);
		
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
		$this->db->from('_column d');
		$this->db->join('_category', 'd.category_id = _category.category_id_map and '.$prefix .'_category.lang = "'.$language.'"', 'left');
		$this->db->join('_subcategory', 'd.subcategory_id = _subcategory.subcategory_id_map and '.$prefix .'_subcategory.lang = "'.$language.'"', 'left');
		$this->db->join('_picture', 'd.column_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 2 AND '.$prefix.'_picture.`default`=1 ', 'left');
		$this->db->where('d.status =', 2);
		$this->db->where('d.lang', $language);

		if($id != null && $id > 0){
			$this->db->where('d.column_id2', $id);
		}

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

	function delete_column_admin($id){
		$this->db->where('column_id2', $id);
		$this->db->delete('_column'); 
		//Debug($this->db->last_query());
	}

	function delete_relate_column($ref_id = 0, $column_id = 0, $table ='_column_relate'){

		if($ref_id > 0) $this->db->where('ref_id', $ref_id);
		if($column_id > 0) $this->db->where('column_id', $column_id);
		$this->db->delete($table); 
		//Debug($this->db->last_query());
		$obj['lastupdate_date'] = date('Y-m-d H:i:s');
		$obj['lastupdate_by'] = $this->session->userdata('admin_id');
		$this->store2($ref_id , $obj);
	}

	//cloumn relate
    public function get_relate($id){

			$prefix = 'sd';
			$language = $this->lang->language['lang'];
			$this->db->select('_column_relate.ref_id, _column_relate.order, _column.column_id, _column.column_id2, _column.title, _columnist.columnist_name, _columnist.full_name as columnist_full_name, _column.lastupdate_date,_picture.file_name, _picture.folder');
			$this->db->from('_column');
			$this->db->join('_column_relate', '_column_relate.column_id = _column.column_id2');
			$this->db->join('_columnist', '_columnist.columnist_id = _column.columnist_id', 'left');
			$this->db->join('_picture', '_column.column_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 2 AND '.$prefix.'_picture.`default`=1 ', 'left');
			$this->db->where('_column_relate.ref_id', $id);
			$this->db->where('lang', $language);
			$this->db->order_by("order", "ASC"); 
			$query = $this->db->get();
			//Debug($this->db->last_query());
			$result = $query->result_object();
			return $result;

	}

    public function gen_relate($daraid = 0, &$tag_id = array(), $curid, $number_of_relate = 0){
		
		$language = $this->lang->language['lang'];

		$listpage = 50;
		$limit_start = 0;
		$prefix = 'sd';
		$ref_type = 2;
		$getrelate_other = $where_tag = $where_id = array();

		/********************************/
		if($daraid > 0){
			
			$subcat = 39; //ดูดวง

			$this->db->select('_column.column_id2 as column_id, _column.title, _column.lang, _column.create_date');
			$this->db->from('_column');

			$this->db->where('_column.status', 1);
			$this->db->where('_column.approve', 1);
			$this->db->where('_column.lang', $language);

			$this->db->where('_column.dara_id', $daraid);
			$this->db->where('_column.column_id2 !=', $curid);
			$this->db->where('_column.subcategory_id !=', $subcat);
			$this->db->order_by('_column.create_date', 'DESC');

			$this->db->limit($listpage, $limit_start);

		}else{
			if(count($tag_id > 0)){
				
				$where = array();
				$this->db->select('tag_id, ref_id as column_id, order, create_date');
				$this->db->from('_tag_pair');
				if($tag_id)
				foreach($tag_id as $val){
						//$array = array('tag_id' =>  intval($val));
						//$this->db->or_where('tag_id', $val); 
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
								if($key == "column_id") $column_id = $val;
						}
						if($curid != $column_id) $where_id[] = $column_id;
				}
			
				$this->db->select('_tag_pair.ref_id as column_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
				$this->db->from('_tag');
				$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = '.$ref_type);
				$this->db->where('_tag.status', 1);
				$this->db->where('ref_id !=', $curid);

				if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
				if($where_id) $this->db->where_in('_tag_pair.ref_id', $where_id);

				$this->db->group_by("column_id"); 
				$this->db->order_by("NumberOfTag", "DESC"); 
				$this->db->limit(5);

				//$this->db->where('lang', $language);
				$query2 = $this->db->get();
				//Debug($this->db->last_query());

				$getrelate = $query2->result_object();
				//Debug($getrelate);

				//if ไม่ข่าวไม่ถึง 5 จากดารา หาเพิ่ม
				if(count($getrelate) < 5){

							//$get_add = 5 - count($getrelate);

							$this->db->select('_tag_pair.ref_id as column_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
							$this->db->from('_tag');
							$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = '.$ref_type);
							$this->db->join('_column c', '_tag_pair.ref_id = c.column_id2 AND c.status = 1');
							$this->db->where('_tag.status', 1);

							if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
							if($where_id) $this->db->where_not_in('_tag_pair.ref_id', $where_id);

							$this->db->where('_tag_pair.ref_id !=', $curid);
							$this->db->group_by("column_id"); 
							$this->db->order_by("NumberOfTag", "DESC"); 
							$this->db->limit(5);

							$other_query = $this->db->get();
							//Debug($this->db->last_query());
							$getrelate_other = $other_query->result_object();
							//Debug($getrelate_other);
							$getrelate = array_merge($getrelate, $getrelate_other);
				}

				/*if($getrelate && $getrelate_other)
					$getrelate = array_merge($getrelate, $getrelate_other);
				else if($getrelate_other)
					$getrelate = $getrelate_other;*/

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

     public function save_relate($column_id, &$data = array(), $clear = 0){

					if($clear == 1) $this->db->delete('_column_relate', array('ref_id' => $column_id));

					$insert = $this->db->insert_batch('_column_relate', $data);

					//Debug($this->db->last_query());
					return $insert;
    }

	//cloumn relate columnist
    public function get_relate_columnist($id){

			$prefix = 'sd';
			$language = $this->lang->language['lang'];
			$this->db->select('_column_relate_columnist.ref_id, _column_relate_columnist.order, _column.column_id, _column.column_id2, _column.title, _columnist.columnist_name, _columnist.full_name as columnist_full_name, _column.lastupdate_date,_picture.file_name, _picture.folder');
			$this->db->from('_column');
			$this->db->join('_column_relate_columnist', '_column_relate_columnist.column_id = _column.column_id2');
			$this->db->join('_columnist', '_columnist.columnist_id = _column.columnist_id');
			$this->db->join('_picture', '_column.column_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 2 AND '.$prefix.'_picture.`default`=1 ', 'left');
			$this->db->where('_column_relate_columnist.ref_id', $id);
			$this->db->where('lang', $language);
			$this->db->order_by("order", "ASC"); 
			$query = $this->db->get();
			//Debug($this->db->last_query());
			$result = $query->result_object();
			return $result;

	}

    public function gen_relate_columnist($columnist_id = 0, &$tag_id = array(), $curid){
		
		$language = $this->lang->language['lang'];

		$listpage = 50;
		$limit_start = 0;
		$prefix = 'sd';
		$getrelate_other = $where_tag = $where_id = array();

		/********************************/
		$this->db->select('_column.column_id2 as column_id, _column.title, _column.lang, _column.create_date');
		$this->db->from('_column');
		$this->db->where('_column.status', 1);
		$this->db->where('_column.approve', 1);
		$this->db->where('lang', $language);
		$this->db->where('columnist_id', $columnist_id);
		$this->db->where('column_id2 !=', $curid);
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
		//Debug($where_tag);
		//die();

		if($result)
				for($i=0;$i<count($result);$i++){
						foreach($result[$i] as $key => $val){
								if($key == "column_id") $column_id = $val;
						}
						if($curid != $column_id) $where_id[] = $column_id;
				}
			
				$this->db->select('_tag_pair.ref_id as column_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
				$this->db->from('_tag');
				$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = 2');
				$this->db->where('_tag.status', 1);
				$this->db->where('ref_id !=', $curid);

				if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
				if($where_id) $this->db->where_in('_tag_pair.ref_id', $where_id);

				$this->db->group_by("column_id"); 
				$this->db->order_by("NumberOfTag", "DESC"); 
				$this->db->limit(5);

				//$this->db->where('lang', $language);
				$query2 = $this->db->get();
				//Debug($this->db->last_query());

				$getrelate = $query2->result_object();
				$loop = count($getrelate);

				//if ไม่ข่าวไม่ถึง 5 จากดารา หาเพิ่ม
				if($loop < 5){

							//$get_add = 5 - count($getrelate);

							$this->db->select('_tag_pair.ref_id as column_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
							$this->db->from('_tag');
							$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = 2');
							$this->db->where('_tag.status', 1);

							if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
							if($where_id) $this->db->where_not_in('_tag_pair.ref_id', $where_id);

							if(isset($getrelate))
									for($r=0;$r<$loop;$r++){
											$this->db->where_not_in('_tag_pair.ref_id', $getrelate[$r]->column_id);
									}

							$this->db->where('_tag_pair.ref_id !=', $curid);
							$this->db->group_by("column_id"); 
							$this->db->order_by("NumberOfTag", "DESC"); 
							$this->db->limit(5);

							$other_query = $this->db->get();
							//Debug($this->db->last_query());
							$getrelate_other = $other_query->result_object();
							//Debug($getrelate_other);
				}

				//Debug($getrelate);
				if($getrelate && $getrelate_other)
					$getrelate = array_merge($getrelate, $getrelate_other);
				else if($getrelate_other)
					$getrelate = $getrelate_other;

				//$getrelate = @array_unique($getrelate);
				//$newArray = array_unique($getrelate);
				//Debug($getrelate);
				//die();

				return $getrelate;
	}

     public function save_relate_columnist($column_id, &$data = array(), $clear = 1){
			if($clear == 1) $this->db->delete('_column_relate_columnist', array('ref_id' => $column_id));
			$insert = $this->db->insert_batch('_column_relate_columnist', $data);
			return $insert;
    }

     public function clear_relate_columnist($column_id){
			$this->db->delete('_column_relate_columnist', array('ref_id' => $column_id));
    }

    public function set_order_relate($ref_id, $column_id, &$data = array(), $table = '_column_relate'){

			if($ref_id > 0){
					$this->db->where('ref_id', $ref_id);
					$this->db->where('column_id', $column_id);
					$this->db->update($table, $data);

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

    public function set_order($order = 0, $neworder, $category_id = '', $columnid = ''){

			$this->db->set('order_by', $neworder); 
			$this->db->where('status', 1); 
			$this->db->where('approve', 1); 

			if($columnid != '') 
				$this->db->where('column_id2', $columnid); 
			else 
				$this->db->where('order_by', $order); 

			$this->db->where('category_id', $category_id);
			$this->db->update('_column');

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
		$this->db->update('_column');
		if($this->session->userdata('admin_id') == 1) Debug($this->db->last_query());
	}

    public function set_delorder($minorder, $maxorder, $category_id){
		$this->db->set('order_by', 'order_by - 1', FALSE); 
		$this->db->where('category_id', $category_id);
		$this->db->where('order_by >', $maxorder);
		$this->db->where('order_by <=', $minorder);
		$this->db->update('_column');
		if($this->session->userdata('admin_id') == 1) Debug($this->db->last_query());
	}

	function add_relate_column($ref_id = 0, $column_id = 0, $table = '_column_relate'){

		$data = array(
			"ref_id" => $ref_id,
			"column_id" => $column_id,
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

	function update_order_relate($column_id, $ref_id, $order = 1, $table = '_column_relate'){

		$data['order'] = $order;
		$this->db->where('column_id', $column_id);
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
		$this->db->update('_column');

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
		$this->db->update('_column');

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
