<?php
class Tags_content_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
        //$this->load->database();
    }

	public function get_content($limit_start = 0, $listpage = 100, $status = 0, $keyword = '', $order_by = 'tag_text', $order_type = 'asc'){

		//$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_tag');
		//$this->db->where('tag_id', $tag_id);
		//$this->db->where('lang', $language);
		$this->db->where('status !=', 2);

		if($status == 1) $this->db->where('status', $status);
		if($keyword != '') $this->db->like('tag_text', trim($keyword));

		$this->db->order_by($order_by, $order_type);
		$this->db->limit($listpage, $limit_start);
		$query = $this->db->get();		
		//echo $this->db->last_query();
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

	public function search_incontent($ref_type = 1, $keyword = ''){
	
		$language = $this->lang->language['lang'];

		if($ref_type == 1){

				$this->db->select('news_id2 as listid, title, category_id, subcategory_id , status, approve');
				$this->db->from('_news n');

		}else if($ref_type == 2){

				$this->db->select('column_id2 as listid, title, category_id, subcategory_id , status, approve');
				$this->db->from('_column c');

		}else if($ref_type == 3){

				$this->db->select('gallery_id2 as listid, title, gallery_type_id, status, approve');
				$this->db->from('_gallery g');

		}else if($ref_type == 4){

				$this->db->select('video_id2 as listid, title_name as title, category_id, subcategory_id , status, approve');
				$this->db->from('_video v');
		}

		if($keyword != ''){
			if($ref_type == 3){
				$search_kw = "(detail like '%".$keyword."%')";
				$this->db->where($search_kw);
			}else{
				$search_kw = "(description like '%".$keyword."%' or detail like '%".$keyword."%')";
				$this->db->where($search_kw);
			}
		}else 
			$search_kw = '';

		//$this->db->select('*');
		//$this->db->from('_tag_pair tp');

		$this->db->where('lang', $language);
		$this->db->order_by('create_date', 'DESC');
		$this->db->limit(20);
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
	
	}

	public function getall_tag_pair($ref_type = 1, $keyword = ''){
	
		$language = $this->lang->language['lang'];

		if($ref_type == 1)
			$this->db->select('n.news_id2 as listid, n.title');

		$this->db->select('tp.*, t.tag_text, d.dara_profile_id, d.iddara, d.dara_type_id, d.first_name, d.last_name, d.nick_name, d.pen_name, d.avatar as picture, dt.dara_type_name');

		$this->db->from('_tag_pair tp');
		$this->db->join('_tag t', 'tp.tag_id=t.tag_id and t.status = 1');

		if($ref_type == 1){
				if($keyword != '') 
					$search_kw = " and (n.description like '%".$keyword."%' or n.detail like '%".$keyword."%')";
				else 
					$search_kw = '';
				$this->db->join('_news n', 'n.news_id2 = tp.ref_id and n.lang = "'.$language.'" '.$search_kw);
		}


		//JOIN `sd_dara_profile` d ON d.`dara_profile_id`=tp.`ref_id`
		$this->db->join('_dara_profile d', 'd.dara_profile_id = tp.ref_id', 'left');
		$this->db->join('_dara_type dt', 'd.dara_type_id = dt.dara_type_id_map and dt.lang="th"', 'left');

		//$this->db->where('tp.ref_id', $ref_id);
		$this->db->where('tp.ref_type', $ref_type);
		
		if($ref_type == 5) $this->db->group_by('d.dara_profile_id');

		//$this->db->limit(100);
		$query = $this->db->get();
		Debug($this->db->last_query());
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
		$this->db->where('tp.tag_id', $tag_id);
		$this->db->where('tp.ref_type', $ref_type);
		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_object(); 

    }	
 
}
?>	
