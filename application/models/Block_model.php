<?php
class Block_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    function get_block($id = 0){

			$language = $this->lang->language['lang'];
			$prefix = 'sd';

			$this->db->select('*');
			$this->db->from('_block');
			$this->db->order_by('order', 'Asc');
			$query = $this->db->get();
			//Debug($this->db->last_query());
			return $query->result_object();
	}

    function setorder($block_id = 0, $data){

			if($block_id > 0){
					$this->db->where('block_id', $block_id);
					$this->db->update('_block', $data);
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

    public function get_max_id(){

		//$language = $this->lang->language['lang'];
		$this->db->select('max(news_id) as max_id');
		$this->db->from('_news');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 

    }

    public function countblock($category_id = 0, $subcategory_id = 0){

		$language = $this->lang->language['lang'];

		$this->db->select('count(news_id) as countnews');
		$this->db->from('_news');
		if($category_id != 0) $this->db->where('category_id', $category_id);
		if($subcategory_id != 0) $this->db->where('subcategory_id', $subcategory_id);

		$this->db->where('lang', $language);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_object();
		//Debug($result[0]->countnews);
		return $result[0]->countnews;

    }

    function store($id = 0, $data){
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
						//echo $this->db->last_query();
						return true;
					}else{
						return false;
					}					
			}else{
					$insert = $this->db->insert('_news', $data);
					//echo $this->db->last_query();
					return $insert;
			}
	}

    function delete_news($id){

		$data = array(
				'status' => 2
		);
		$this->db->where('news_id2', $id);
		$this->db->update('_block', $data);
		
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_news_admin($id){
		$this->db->where('id', $id);
		$this->db->delete('_block'); 
	}
 

}
?>	
