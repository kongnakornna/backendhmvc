<?php
class Magazine_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function countmagazine($brand_id = 0){

		$language = $this->lang->language['lang'];

		$this->db->select('count(magazine_id) as countall');
		$this->db->from('_magazine');

		if($brand_id != 0) $this->db->where('brand_id', $brand_id);

		$this->db->where('lang', $language);
		$this->db->where('status !=', 2);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		$result = $query->result_object();
		return $result[0]->countall;

    }

    public function get_status($id){
        
    	$this->db->select('title, status, approve');
    	$this->db->from('_magazine');
    	$this->db->where('magazine_id2', $id);
    	$query = $this->db->get();
    	return $query->result_array();
    
    }

    public function get_order($id){
        
    	$this->db->select('title, order_by');
    	$this->db->from('_magazine');
    	$this->db->where('magazine_id2', $id);		
		$this->db->where('lang', 'th');

    	$this->db->limit(1);
    	$query = $this->db->get();
    	return $query->result_object();
    
    }

    public function get_max_id(){

		$this->db->select('max(magazine_id) as max_id');
		$this->db->from('_magazine');
		$query = $this->db->get();
		echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function getdata_byid($magazine_id){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_magazine');
		$this->db->where('magazine_id2', $magazine_id);
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function get_data($magazine_id=null, $search_string=null, $brand_id = '', $status = 0, $sp = 0, $order = '_magazine.order_by', $ordertype='asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];
		$prefix = "sd";
		$ref_type = 3;

		$this->db->select('_magazine.*, _brand.brand_name, _picture.file_name, _picture.folder, _picture.default');

		//if(isset($magazine_id)){
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_magazine.create_by) AS create_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_magazine.lastupdate_by) AS lastupdate_by_name');
				$this->db->select('(SELECT '.$prefix.'_admin.admin_username FROM '.$prefix.'_admin WHERE '.$prefix.'_admin.admin_id='.$prefix.'_magazine.approve_by) AS approve_by_name');
		//}
		//$this->db->select('_up18.u18_id AS is_18');

		$this->db->from('_magazine');
		$this->db->join('_brand', '(_magazine.brand_id=_brand.brand_id AND '.$prefix.'_brand.`status` = 1', 'left');

		/*if($sp == 1){
			$this->db->join('_highlight', '_magazine.magazine_id2 = _highlight.news_id and '.$prefix.'_highlight.ref_type = '.$ref_type);
			$this->db->join('_megamenu', '_magazine.magazine_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		}else if($sp == 2){
			$this->db->join('_highlight', '_magazine.magazine_id2 = _highlight.news_id and '.$prefix.'_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_magazine.magazine_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type);
		}else{
			$this->db->join('_highlight', '_magazine.magazine_id2 = _highlight.news_id and '.$prefix.'_highlight.ref_type = '.$ref_type, 'left');
			$this->db->join('_megamenu', '_magazine.magazine_id2 = _megamenu.id and '.$prefix.'_megamenu.ref_type = '.$ref_type, 'left');
		}*/

		$this->db->join('_picture', '_magazine.magazine_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 3 AND '.$prefix.'_picture.`default`=1 ', 'left');
		//$this->db->join('_up18', '_magazine.magazine_id2 = _up18.ref_id and '.$prefix.'_up18.ref_type = '.$ref_type , 'left');

		if($magazine_id != null && $magazine_id > 0){
			$this->db->where('magazine_id2', $magazine_id);
		}else
			$this->db->where('_magazine.lang', $language);

		//debug($search_string);

		if(is_array($search_string)){
			foreach($search_string as $key => $val){
					if($key == 'status'){
							$array_kw['_magazine.status'] = $val;
							$this->db->where($array_kw);
					}else if($key == 'approve'){
							$array_kw['_magazine.approve'] = $val;
							$this->db->where($array_kw);

					}else if($key == 'create_date'){

							if($val != ''){
								$arr = explode("/", $val);
								$val = $arr[2].'-'.$arr[0].'-'.$arr[1];
								$this->db->like('_magazine.create_date' , $val, 'after');
							}
							//$array_kw['_magazine.approve'] = $val;
							//$this->db->where($array_kw);

					}else{
							$array_kw['_magazine.title'] = $val;
							//$this->db->where($array_kw);
							$this->db->like('_magazine.title', $val);
					}
			}
		}else if($search_string){		
			$this->db->like('_magazine.title', $search_string);
		}

		if($brand_id != '' && $brand_id != 0){
			$this->db->where('_magazine.brand_id', intval($brand_id));
		}

		if($status > 0){
			if($status == 3)
				$this->db->where('_magazine.status', 0);
			else
				$this->db->where('_magazine.status', $status);
		}
		$this->db->where('_magazine.status !=', 2);
		$this->db->where('_magazine.status !=', 9);

		if($order){
			$this->db->order_by($order, $ordertype);
		}else{
		    $this->db->order_by('_magazine.create_date', $ordertype);
		}
		$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_array();
    }

    public function get_approve($magazine_id=null, $search_string=null, $brand_id = '', $order = '_magazine.order_by', $ordertype='asc', $limit_start = 0, $listpage = 20){
		
		$language = $this->lang->language['lang'];
		$prefix = "sd";

		$this->db->select('_magazine.*, _brand.brand_name, _picture.file_name, _picture.folder, _picture.default');
		$this->db->from('_magazine');

		$this->db->join('_brand', '(_magazine.brand_id=_brand.brand_id AND '.$prefix.'_brand.`status` = 1', 'left');
		$this->db->join('_picture', '_magazine.magazine_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 3 AND '.$prefix.'_picture.`default`=1 ', 'left');

		if($magazine_id != null && $magazine_id > 0){
			$this->db->where('magazine_id2', $magazine_id);
		}else
			$this->db->where('_magazine.lang', $language);

		if($search_string){
			$this->db->like('_magazine.title', $search_string);
		}

		if($brand_id != '' && $brand_id != 0){
			$this->db->like('_magazine.brand_id', $brand_id);
		}

		$this->db->where('_magazine.status', 1);
		$this->db->where('_magazine.approve', 1);

		if($order){
			$this->db->order_by($order, $ordertype);
		}else{
		    $this->db->order_by('_magazine.create_date', $ordertype);
		}
		$this->db->limit($listpage, $limit_start);

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_array();
    }

	function status_magazine($id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('magazine_id2', $id);
		$this->db->update('_magazine', $data);
		
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

    function count_all($magazine_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_magazine');
		if($magazine_id != null && $magazine_id != 0){
			$this->db->where('magazine_id', $magazine_id);
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

    function store($magazine_id = 0, $data){
			
			//echo "magazine_id = $magazine_id<br>";
			//Debug($data);
			//echo "<hr>";

			if($magazine_id > 0){
					$this->db->where('magazine_id', $magazine_id);
					$this->db->update('_magazine', $data);
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
					$this->db->insert('_magazine', $data);
					$insert = $this->db->insert_id();
					//Debug($this->db->last_query());
					return $insert;
			}
	}

    function store2($magazine_id = 0, $data){
			
					$this->db->where('magazine_id2', $magazine_id);
					$this->db->update('_magazine', $data);
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

	function update_order($magazine_id, $order = 1){

		$data['order_by'] = $order;
		$this->db->where('magazine_id2', intval($magazine_id));
		$this->db->update('_magazine', $data);
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
		$this->db->where('magazine_id2 !=', intval($gallery_type_id));
		$this->db->update('_magazine');
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
		$this->db->where('magazine_id2 !=', intval($gallery_type_id));
		$this->db->update('_magazine');
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
		$this->db->update('_magazine');

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
		$this->db->update('_magazine');
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
		$prefix = "sd";

		$this->db->select('_magazine.*, _brand.brand_name, _picture.file_name, _picture.folder, _picture.default');
		$this->db->from('_magazine');

		$this->db->join('_brand', '(_magazine.brand_id=_brand.brand_id AND sd_brand.lang="'.$language.'" AND sd_brand.`status` = 1', 'left');

		$this->db->join('_picture', '_magazine.magazine_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = 3 AND '.$prefix.'_picture.`default`=1 ', 'left');

		$this->db->where('_magazine.lang', $language);
		$this->db->where('_magazine.status', 9);
		$this->db->order_by('_magazine.create_date', 'DESC');

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_array();

    }

    function delete_data($magazine_id){
		$data = array(
				'status' => 9
		);
		$this->db->where('magazine_id2', $magazine_id);
		$this->db->update('_magazine', $data);
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

	function delete_by_admin($magazine_id){
		$this->db->where('magazine_id2', $magazine_id);
		$this->db->delete('_magazine'); 
	}

	/*************Relate***************/
    public function get_relate($id, $showdabug = 0){
		
			$language = $this->lang->language;
			$lang = $language['lang'];

			$prefix = 'sd';
			$ref_type = 3;

			$this->db->select('_magazine_relate.ref_id, _magazine_relate.order, _magazine.magazine_id, _magazine.magazine_id2, _magazine.title, _magazine.lastupdate_date, _magazine.create_date, _picture.file_name, _picture.folder');
			$this->db->from('_magazine');
			$this->db->join('_magazine_relate', '_magazine_relate.magazine_id = _magazine.magazine_id2');
			$this->db->join('_picture', '_magazine.magazine_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
			$this->db->where('_magazine_relate.ref_id', $id);
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

			$this->db->select('g.magazine_id, g.magazine_id2, g.gallery_type_id, g.dara_id, g.title, tp.pair_id, tp.tag_id, tp.ref_id, tp.ref_type, t.tag_text');

			$this->db->from('_magazine g');
			$this->db->join('_tag_pair tp', 'tp.ref_id = g.magazine_id2 and tp.ref_type = '.$ref_type);
			$this->db->join('_tag t', 't.tag_id = tp.tag_id and t.status = 1');

			//$this->db->join('_picture', '_magazine.magazine_id2 = _picture.ref_id and '.$prefix.'_picture.ref_type = '.$ref_type.' AND '.$prefix.'_picture.`default`=1 ', 'left');
			$this->db->where('tp.tag_id', intval($tagid));
			$this->db->where('g.magazine_id2 !=', intval($curid));

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
		$ref_type = 3;
		$result_relate = $getrelate_other = $where_tag = $where_tag2 = $where_id = $where_id2 = $tag_id_dara = array();

		/********************************/
		if($daraid > 0){
				$this->db->select('_magazine.magazine_id2 as magazine_id, _magazine.title, _magazine.lang, _magazine.create_date');
				$this->db->from('_magazine');
				$this->db->where('_magazine.status', 1);
				$this->db->where('_magazine.approve', 1);
				$this->db->where('lang', $lang);
				if($daraid) $this->db->where('dara_id', $daraid);
				$this->db->where('_magazine.magazine_id2 !=', $curid);
				$this->db->order_by('create_date', 'DESC');
				$this->db->limit($listpage, $limit_start);
		}else{
			if(count($tag_id > 0)){
				
				$where = array();
				$this->db->select('tag_id, ref_id as magazine_id, order, create_date');
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
								if($key == "magazine_id") $magazine_id = $val;
						}
						if($curid != $magazine_id) $where_id[] = $magazine_id;
				}
			
				$this->db->select('_tag_pair.ref_id as magazine_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
				$this->db->from('_tag');
				$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = '.$ref_type);
				$this->db->where('_tag.status', 1);
				$this->db->where('ref_id !=', $curid);

				if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
				if($where_id) $this->db->where_in('_tag_pair.ref_id', $where_id);

				$this->db->group_by("magazine_id"); 
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

							$this->db->select('_tag_pair.ref_id as magazine_id, COUNT(`sd_tag`.`tag_id`) AS NumberOfTag ');
							$this->db->from('_tag');
							$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag_pair`.ref_type = '.$ref_type);
							$this->db->join('_magazine g', '_tag_pair.ref_id = g.magazine_id2 AND g.status = 1');
							//JOIN `sd_magazine` g ON `sd_tag_pair`.`ref_id`= g.`magazine_id2` AND g.`status` = 1 AND g.`approve`=1

							$this->db->where('_tag.status', 1);

							if($where_tag) $this->db->where_in('_tag.tag_id', $where_tag);
							if($where_id) $this->db->where_not_in('_tag_pair.ref_id', $where_id);

							$this->db->where('_tag_pair.ref_id !=', intval($curid));
							if(count($getrelate) > 0){
									foreach($getrelate as $arrw => $conw){
											$this->db->where('_tag_pair.ref_id !=', intval($getrelate[$arrw]->magazine_id));
									}
							}

							$this->db->group_by("magazine_id"); 
							$this->db->order_by("NumberOfTag", "DESC"); 
							$this->db->limit(5);

							$other_query = $this->db->get();
							//Debug($this->db->last_query());
							$getrelate_other = $other_query->result_object();
							//echo "Other";
							//Debug($getrelate_other);

							$getrelate = array_merge($getrelate, $getrelate_other);
				}
				//echo "<hr>final";
				//array_unique($getrelate);
				//Debug($getrelate);
				//die();

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

     public function save_relate($magazine_id, &$data = array(), $clear = 1){

					if($clear == 1) $this->db->delete('_magazine_relate', array('ref_id' => $magazine_id));
					$insert = $this->db->insert_batch('_magazine_relate', $data);
					//Debug($this->db->last_query());
					//return $insert;
    }

     public function delete_relate($id = 0, $ref_id = 0){

			if($id > 0)
				$this->db->delete('_magazine_relate', array('magazine_id' => $id));

			if($ref_id > 0)
				$this->db->delete('_magazine_relate', array('ref_id' => $ref_id));

    }

    public function set_order_relate($ref_id, $magazine_id, &$data = array(), $table = '_magazine_relate'){

			if($ref_id > 0){
					$this->db->where('ref_id', $ref_id);
					$this->db->where('magazine_id', $magazine_id);
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

	function add_relate($ref_id = 0, $magazine_id = 0, $table = '_magazine_relate'){

		$data = array(
			"ref_id" => $ref_id,
			"magazine_id" => $magazine_id,
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

	function update_order_relate($magazine_id, $ref_id, $order = 1, $table = '_magazine_relate'){

		$data['order'] = $order;
		$this->db->where('magazine_id', $magazine_id);
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
