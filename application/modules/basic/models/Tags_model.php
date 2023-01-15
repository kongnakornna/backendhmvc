<?php
class Tags_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        //$this->load->database();
    }
    
    public function getSelect($default = 0,$name = "tag_id"){//Dropdown List
    		//$language = $this->lang->language;    		
			//$first = "--- ".$language['please_select']." ---";
	    	$rows = $this->get_content_all(0, 3000, 1);
			//Debug($rows);
	    	$opt = array();
	    	//$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption($row['tag_id'], $row['tag_text']);
	    	}
	    	//return selectList( $opt, $name, 'class="chosen-select"', 'value', 'text',$default);
	    	return MultiSelectList( $opt, $name, 'class="chosen-select"', 'value', 'text', $default);
    }

    public function get_status($id){
    	//$language = $this->lang->language['lang'];
    	$this->db->select('status');
    	$this->db->from('_tag');
    	$this->db->where('tag_id', $id);
    	//$this->db->where('lang', $language);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    }
        
    public function get_max_order(){
		//$language = $this->lang->language['lang'];
		$this->db->select('max(order_by) as max_order');
		$this->db->from('_tag');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_max_id(){
		//$language = $this->lang->language['lang'];
		$this->db->select('max(tag_id) as max_id');
		$this->db->from('_tag');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 
    }
    
    public function validate_tags($keyword, $curid = 0){
    
		$this->db->select('*');
		$this->db->from('_tag');

		if($curid != 0) $this->db->where('tag_id !=', $curid);

		$this->db->where('tag_text', trim($keyword));
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object(); 
    }
     
   public function get_content_id($tag_id){

		//$language = $this->lang->language['lang'];
		$this->db->select('t.*, tp.ref_id, tp.ref_type');
		$this->db->from('_tag t');
		$this->db->join('_tag_pair tp', 't.tag_id=tp.tag_id', 'left');
		$this->db->where('t.tag_id', $tag_id);

		//$this->db->where('lang', $language);
		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_array(); 
	}

	public function get_content_all($limit_start = 0, $listpage = 100, $status = 0, $keyword = '', $order_by = 'tag_text', $order_type = 'asc'){

		$this->db->select('*');
		$this->db->from('_tag');
		$this->db->where('status !=', 2);

		if($status == 1) $this->db->where('status', $status);
		if($keyword != '') $this->db->like('tag_text', trim($keyword));

		$this->db->order_by($order_by, $order_type);
		if($listpage != 0) $this->db->limit($listpage, $limit_start);

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_array(); 

    }

    public function get_content($tag_status=null, $order_by='tag_id', $order_type='Asc', $limit_start = 0, $listpage = 20){
		
		//$language = $this->lang->language['lang'];
		$this->db->select('_tag.tag_id');
		$this->db->select('_tag.tag_text');
		$this->db->select('_tag.create_date');
		$this->db->select('_tag.status');
		$this->db->from('_tag');

		/*if($tag_id != null && $tag_id > 0){
			$this->db->where('tag_id', $tag_id);
		}*/

		/*if($search_string){
			$this->db->like('tag_text', $search_string);
		}*/
		//if($tag_id){
			//$this->db->where('status', $cat_status);
		//}

		//$this->db->where('lang', $language);
		$this->db->order_by($order_by, $order_type);
		$this->db->limit($listpage, $limit_start);
		//$this->db->query('SELECT * FROM `sd_tag`');

		$query = $this->db->get();
		//$this->db->last_query();
		//Debug($this->db->get());
		return $query->result_array();
    }

    function count_tags($tag_id=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_tag');
		if($tag_id != null && $tag_id != 0){
			$this->db->where('tag_id', $tag_id);
		}
		if($search_string){
			$this->db->like('tag_text', $search_string);
		}

		if($order){
			$this->db->order_by($order, 'Asc');
		}

		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($data){
    	//echo "Query INSERT TAG<br>";
		$insert = $this->db->insert('_tag', $data);
		//Debug($this->db->last_query());
	    return $insert;
	}

    function store_update($tag_id, $data){

		$this->db->where('tag_id', $tag_id);
		$this->db->update('_tag', $data);
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

	function status_tags($tag_id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('tag_id', $tag_id);
		$this->db->update('_tag', $data);
				
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}	

	public function getall_tag_pair($ref_type = 1){
	
		$language = $this->lang->language['lang'];

		if($ref_type == 1)
			$this->db->select('n.news_id2 as listid, n.title');

		$this->db->select('tp.*, t.tag_text, d.dara_profile_id, d.iddara, d.dara_type_id, d.first_name, d.last_name, d.nick_name, d.pen_name, d.avatar as picture, dt.dara_type_name');
		$this->db->from('_tag_pair tp');
		$this->db->join('_tag t', 'tp.tag_id=t.tag_id and t.status = 1');

		if($ref_type == 1)
			$this->db->join('_news n', 'n.news_id2 = tp.ref_id and n.lang = "'.$language.'"');

		//JOIN `sd_dara_profile` d ON d.`dara_profile_id`=tp.`ref_id`
		$this->db->join('_dara_profile d', 'd.dara_profile_id = tp.ref_id');
		$this->db->join('_dara_type dt', 'd.dara_type_id = dt.dara_type_id_map and dt.lang="th"');

		//$this->db->where('tp.ref_id', $ref_id);
		$this->db->where('tp.ref_type', $ref_type);
		
		if($ref_type == 5) $this->db->group_by('d.dara_profile_id');

		//$this->db->limit(100);
	
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	
	}
	
   public function get_tag_pair($ref_id, $ref_type = 1){

		//$language = $this->lang->language['lang'];
		$this->db->select('tp.*, t.tag_text, t.tag_id as value');
		$this->db->from('_tag_pair tp');
		$this->db->join('_tag t', 'tp.tag_id=t.tag_id and t.status = 1');
		$this->db->where('tp.ref_id', intval($ref_id));
		$this->db->where('tp.ref_type', $ref_type);

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_object(); 

    }
	
   public function chktag_pair($tag_id, $ref_type = 1){

		//$language = $this->lang->language['lang'];
		$this->db->select('tp.*, t.tag_text');
		$this->db->from('_tag_pair tp');
		$this->db->join('_tag t', 'tp.tag_id=t.tag_id and t.status = 1');
		$this->db->where('tp.tag_id', intval($tag_id));
		$this->db->where('tp.ref_type', $ref_type);
		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_object(); 

    }	

    function store1_tagpair($data, $clear = 0, $tag_id = 0){

		if($tag_id > 0){
			$this->db->where('tag_id', $tag_id);
			$this->db->update('_tag_pair', $data);
			//Debug($this->db->last_query());			
		}else{
			if($clear == 1){
				$this->db->where('ref_id', $data['ref_id']);
				$this->db->where('ref_type', $data['ref_type']);
				$this->db->delete('_tag_pair');
				//Debug($this->db->last_query());
			}
			$insert = $this->db->insert('_tag_pair', $data);
			//Debug($this->db->last_query());
			//pair_id, tag_id, ref_id, ref_type, create_date
		}
	    return $insert;
	}

    function store_tag_pair($data, $clear = 1, $tag_id = 0, $refid = 0){
		if($tag_id > 0){
			$this->db->where('tag_id', $tag_id);
			$this->db->update('_tag_pair', $data);
			//Debug($this->db->last_query());			
		}else{

			if($clear == 1){
				$curdata = current($data);
				$ref_id = intval($curdata['ref_id']);
				$ref_type = intval($curdata['ref_type']);
				//echo "ref_id = $ref_id<br>ref_type = $ref_type";
				//$ref_id = (isset($data[0]['ref_id'])) ? intval($data[0]['ref_id']) : intval($data[1]['ref_id']);
				//$ref_type = (isset($data[0]['ref_type'])) ? intval($data[0]['ref_type']) : intval($data[1]['ref_type']);

				$this->db->where('ref_id', $ref_id);
				$this->db->where('ref_type', $ref_type);

				$this->db->delete('_tag_pair');
				//Debug($this->db->last_query());
			}
			$insert = $this->db->insert_batch('_tag_pair', $data);
			//Debug($this->db->last_query());
		}
	    return $insert;
	}

	function status_tag($tag_id, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('tag_id', $tag_id);
		$this->db->update('_tag', $data);
		//echo $this->db->last_query();
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}
		
	function delete_tag_pair($ref_id, $ref_type = 0){
		$this->db->where('ref_id', $ref_id);
		$this->db->where('ref_type', $ref_type);
		$this->db->delete('_tag_pair'); 
		//Debug($this->db->last_query());
	}
	
    function delete_tag($tag_id){

		$data = array(
				'status' => 2
		);
		$this->db->where('tag_id', $tag_id);
		$this->db->update('_tag', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_tag_by_admin($tag_id){
		$this->db->where('tag_id', $tag_id);
		$this->db->delete('_tag'); 
	}
	/**********************************************/
   public function check_tag_error($ref_type = 1, $tag_id = 0, $tag_text = ''){

		switch($ref_type){
				case 1 : $this->db->select('n.news_id2 as contentid, n.title'); break;
				case 2 : $this->db->select('c.column_id2 as contentid, c.title'); break;
				case 3 : $this->db->select('g.gallery_id2 as contentid, g.title'); break;
				case 4 : $this->db->select('v.video_id2 as contentid, v.title'); break;
				case 5 : $this->db->select('dp.dara_profile_id as contentid, dp.first_name as title'); break;
		}
		$this->db->select('t.*, tp.pair_id,tp.ref_id, tp.ref_type');
		$this->db->from('_tag_pair tp');
		$this->db->join('_tag t', 'tp.tag_id=t.tag_id', 'left');
		switch($ref_type){
				case 1 : $this->db->join('_news n', 'tp.ref_id = n.news_id2 AND n.lang = "th"', 'left'); break;
				case 2 : $this->db->join('_column c', 'tp.ref_id = c.column_id2 AND c.subcategory_id = 39 AND c.lang = "th"', 'left'); break;
				case 3 : $this->db->join('_gallery g', 'tp.ref_id = g.gallery_id2 AND n.lang = "th"', 'left'); break;
				case 4 : $this->db->join('_video v', 'tp.ref_id = v.video_id2 AND n.lang = "th"', 'left'); break;
				case 5 : $this->db->join('_dara_profile dp', 'tp.ref_id = dp.dara_profile_id ', 'left'); break;
		}

		if($tag_id != 0) $this->db->where('t.tag_id', intval($tag_id));
		if($tag_text != '') $this->db->like('t.tag_text', $tag_text);

		$this->db->where('tp.ref_type', $ref_type);

		$query = $this->db->get();		
		Debug($this->db->last_query());
		return $query->result_object(); 

    }

	function delete_tag_pair_error($pair_id){
		$this->db->where('pair_id', $pair_id);
		$this->db->delete('_tag_pair'); 
		Debug($this->db->last_query());
	}
}
?>	
