<?php
class Dara_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function get_status($id){
    
    	$language = $this->lang->language['lang'];
    
    	$this->db->select('nick_name, first_name, status, approve');
    	$this->db->from('_dara_profile');

    	$this->db->where('dara_profile_id', $id);
    	//$this->db->where('dara_profile_id', $id);
    	//$this->db->where('lang', $language);

    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    }

    public function get_max_id(){

		//$language = $this->lang->language['lang'];
		$this->db->select('max(dara_profile_id) as max_id');
		$this->db->from('_dara_profile');
		//$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_count(){

		//$language = $this->lang->language['lang'];
		$this->db->select('count(dara_profile_id) as countid');
		$this->db->from('_dara_profile');
		//$this->db->where('lang', $language);
		$this->db->where('status', 1);
		$query = $this->db->get();
		return $query->result_object(); 
    }

    public function get_dara_profile_by_id($daraid){

		$this->db->select('*');
		$this->db->from('_dara_profile');
		$this->db->where('dara_profile_id', $daraid);
		$query = $this->db->get();	
		//Debug($this->db->last_query());
		return $query->result_object(); 

    }

    public function get_dara_profile($dara_profile_id=null, $search_string=null, $dara_type = null, $gender = '', $order = 'lastupdate_date', $order_type='DESC', $dara_status = null, $limit_start = 0, $listpage = 3000, $approve = 0){
		
		$language = $this->lang->language['lang'];
		$all = $this->lang->language['all'];
		$prefix = "sd";

		$this->db->select('dp.*, dt.dara_type_name, bt.belong_to');

		if(isset($dara_profile_id)){
				$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`dp`.`create_by`) AS create_by_name');
				$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`dp`.`lastupdate_by`) AS lastupdate_by_name');
				$this->db->select('(SELECT `'.$prefix.'_admin`.`admin_username` FROM `'.$prefix.'_admin` WHERE `'.$prefix.'_admin`.`admin_id`=`dp`.`approve_by`) AS approve_by_name');
		}

		$this->db->from('_dara_profile as dp');
		$this->db->join('_dara_type as dt', '(dp.dara_type_id=dt.dara_type_id_map AND dt.lang="'.$language.'"');
		$this->db->join('_belong_to as bt', '(dp.belong_to_id=bt.belong_to_id', 'left');

		//$this->db->select('*');
		//$this->db->from('_dara_profile');

		if($dara_profile_id != null && $dara_profile_id > 0){
			//$this->db->where('dara_profile_id', $dara_profile_id);
			$this->db->where('dara_profile_id', $dara_profile_id);
		}

		//if($dara_profile_id <= 0) $this->db->where('lang', $language);
		if($dara_type) $this->db->where('dp.dara_type_id', $dara_type);

		if($dara_status !== null) $this->db->where('dp.status', $dara_status);

		if($gender != '' && $gender != $all) $this->db->where('dp.gender', $gender);

		//$this->db->where('dp.avatar !=', '');
		//$this->db->where('dp.avatar !=', '-');

		if(is_array($search_string)){

			$searchsql = '';
			$array_kw = array();
			foreach($search_string as $key => $val){

					if(trim($val) != ''){
							if($key === 'status'){
								$array_kw['dp.status'] = $val;
								$this->db->where($array_kw);
							}else if($key === 'approve'){
								$array_kw['dp.approve'] = $val;
								$this->db->where($array_kw);
							}else{
								$searchsql = $val;
							}
					}
			}

			if(!isset($array_kw['dp.status']) && !isset($array_kw['dp.approve']))
				$this->db->where('(dp.first_name LIKE "%'.$searchsql.'%" OR dp.last_name LIKE "%'.$searchsql.'%" OR dp.nick_name LIKE "%'.$searchsql.'%" OR dp.pen_name LIKE "%'.$searchsql.'%" )');

		}else{
			if($search_string != ''){
				/*$this->db->like('dp.first_name', $search_string);
				$this->db->or_like('dp.last_name', $search_string);
				$this->db->or_like('dp.nick_name', $search_string);
				$this->db->or_like('dp.pen_name', $search_string);*/

				$this->db->where('(dp.first_name LIKE "%'.$search_string.'%" OR dp.last_name LIKE "%'.$search_string.'%" OR dp.nick_name LIKE "%'.$search_string.'%" OR dp.pen_name LIKE "%'.$search_string.'%" )');
			}
		}

		if($approve == 1){
			$this->db->where('dp.status', 1);
			$this->db->where('dp.approve', 1);
		}else
			$this->db->where('dp.status !=', 2);

		if($order){
			if($order == "lastupdate_date") $order_type = "DESC";
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('lastupdate_date', "DESC");
		}

		if($listpage != 0) $this->db->limit($listpage, $limit_start);

		$query = $this->db->get();		
		//Debug($this->db->last_query());
		return $query->result_array();
    }


    public function get_daraall(){
		
		$language = $this->lang->language['lang'];
		$all = $this->lang->language['all'];

		$this->db->select('dp.*');
		$this->db->from('_dara_profile as dp');
		//$this->db->join('_dara_type as dt', '(dp.dara_type_id=dt.dara_type_id_map AND dt.lang="'.$language.'"');
		//$this->db->join('_belong_to as bt', '(dp.belong_to_id=bt.belong_to_id', 'left');

		$this->db->where('dp.status !=', 2);

		//if($dara_type) $this->db->where('dp.dara_type_id', $dara_type);
		//if($dara_status !== null) $this->db->where('dp.status', $dara_status);
		//if($gender != '' && $gender != $all) $this->db->where('dp.gender', $gender);
		//$this->db->where('dp.avatar !=', '');
		//$this->db->where('dp.avatar !=', '-');

		$this->db->order_by('iddara', "ASC");
		$this->db->limit(1000, 0);
		$query = $this->db->get();		
		
		return $query->result_array();
    }

    public function search_dara_profile($field = 'all', $search_string=null, $listpage = 20){
		
		$language = $this->lang->language['lang'];
		//$all = $this->lang->language['all'];

		$this->db->select('dp.nick_name, dp.pen_name, dp.first_name, dp.last_name');
		//$this->db->select('dp.nick_name, dp.first_name, dp.last_name, dt.dara_type_name, bt.belong_to');
		$this->db->from('_dara_profile as dp');
		//$this->db->join('_dara_type as dt', '(dp.dara_type_id=dt.dara_type_id_map AND dt.lang="'.$language.'"');
		//$this->db->join('_belong_to as bt', '(dp.belong_to_id=bt.belong_to_id', 'left');

		if($field == 'all'){
			/*$this->db->like('first_name', $search_string);
			$this->db->or_like('last_name', $search_string);
			$this->db->or_like('nick_name', $search_string);
			$this->db->or_like('pen_name', $search_string);*/

			$this->db->where('(dp.first_name LIKE "%'.$search_string.'%" OR dp.last_name LIKE "%'.$search_string.'%" OR dp.nick_name LIKE "%'.$search_string.'%" OR dp.pen_name LIKE "%'.$search_string.'%" )');

		}else{
			$this->db->like($field, $search_string);
		}

		//$this->db->where('dp.status !=', 2);
		$this->db->where('dp.status', 1);
		$this->db->where('dp.approve', 1);

		$this->db->order_by('first_name', "ASC");

		if($listpage == 0)  $listpage = 20;
		if($listpage != 0) $this->db->limit($listpage, 0);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
    }

    public function check_daraprofile($first_name, $last_name, $nick_name, $pen_name ,$showdebug = 0){
		
		$language = $this->lang->language['lang'];
		//$all = $this->lang->language['all'];

		$this->db->select('dp.nick_name, dp.pen_name, dp.first_name, dp.last_name');
		$this->db->from('_dara_profile as dp');

		$this->db->where('(dp.first_name LIKE "%'.$first_name.'%" and dp.last_name LIKE "%'.$last_name.'%" and dp.nick_name LIKE "%'.$nick_name.'%") 
		or (dp.pen_name LIKE "%'.$pen_name.'%")');

		//$this->db->where('dp.status !=', 2);
		$this->db->where('dp.status', 1);
		$this->db->where('dp.approve', 1);

		$this->db->order_by('first_name', "ASC");

		$query = $this->db->get();
		if($showdebug == 1) Debug($this->db->last_query());
		return $query->result_object();
    }

    function count_dara_profile($id_map=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_dara_profile');
		if($id_map != null && $id_map != 0){
			$this->db->where('dara_profile_id', $id_map);
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

    function store($dara_profile_id = 0, $data, $showdebug = 0){
			//echo "(dara_profile_id = $dara_profile_id)";

			if($dara_profile_id > 0){
					$this->db->where('dara_profile_id', $dara_profile_id);
					$this->db->update('_dara_profile', $data);

					if($showdebug > 0) Debug($this->db->last_query());

					$report = array();
					//$report['error'] = $this->db->_error_number();
					//$report['message'] = $this->db->_error_message();
					if($report !== 0){
						//if($showdebug > 0) Debug($this->db->last_query());
						//die();
						return true;
					}else{
						return false;
					}					
			}else{
					$this->db->insert('_dara_profile', $data);
					if($showdebug > 0) Debug($this->db->last_query());
					$insert = $this->db->insert_id();
					return $insert;
			}
	}

    /*function store($data){
		$insert = $this->db->insert('_dara_profile', $data);
	    return $insert;
	}

    function store_update($dara_profile_id, $data){

		$this->db->where('dara_profile_id', $dara_profile_id);
		$this->db->update('_dara_profile', $data);
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}*/

	function status_dara_profile($id_map, $enable = 1){

		$data['status'] = $enable;
		$this->db->where('dara_profile_id', $id_map);
		$this->db->update('_dara_profile', $data);
		
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

    public function getdelete($id = null){

		$language = $this->lang->language['lang'];
		$prefix = 'sd';

		$this->db->select('dp.*, p.folder,  p.file_name');
		$this->db->from('_dara_profile dp');
		$this->db->join('_picture p', 'dp.dara_profile_id = p.ref_id and p.ref_type = 5 AND p.`default`=1 ', 'left');
		$this->db->where('dp.status', 2);

		if($id != null && $id > 0){
			$this->db->where('dara_profile_id', $id);
		}
		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_array();
    }

	
    function delete_dara_profile($id_map){

		$data = array(
				'status' => 2
		);
		//$this->db->where('dara_profile_id', $id_map);
		$this->db->where('dara_profile_id', $id_map);
		$this->db->update('_dara_profile', $data);
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

	function delete_dara_admin($id_map){
		$this->db->where('dara_profile_id', $id_map);
		$this->db->delete('_dara_profile'); 
	}

	function delete_tag($dara, $ref_type = 5){
			
			$this->db->select('tp.*, t.tag_text');
			$this->db->from('_tag_pair tp');
			$this->db->join('_tag t', 'tp.tag_id=t.tag_id and t.status = 1');
			$this->db->where('t.tag_text', $dara);
			$this->db->where('tp.ref_type', $ref_type);

			$query = $this->db->get();				
			$result =  $query->result_object();
			
			if($result){

					$pair_id = $result[0]->pair_id;
					//Debug($pair_id);

					$this->db->where('pair_id', $pair_id);
					$this->db->delete('_tag_pair');
					//Debug($this->db->last_query());
			}
	} 

    public function load_sstv($page = 1, $page_size = 10, $lang ='th'){

				$get_url = "http://www.siamdara.com/apisiamdara/select_profile_dara.php?page_size=".$page_size;
				$jsonData = file_get_contents($get_url);
				$jsonobj = json_decode($jsonData, true);

				return $jsonobj;
	}

	public function import_sstv_to_db(&$data = array()){

					$insert = $this->db->insert_batch('_dara_profile', $data);
					return $insert;
	}

	public function auto_tags(){

			$this->load->model('tags_model');

			//SELECT dp.`dara_profile_id`, dp.iddara,  dp.`first_name`, dp.`last_name` , dp.`nick_name`, tp.`pair_id` FROM `sd_dara_profile` dp LEFT JOIN `sd_tag_pair` tp ON dp.`dara_profile_id`= tp.`ref_id` AND tp.`ref_type` = '5'  ORDER BY dp.iddara

			$this->db->select('dp.`dara_profile_id`, dp.iddara,  dp.`first_name`, dp.`last_name` , dp.`nick_name`, tp.`pair_id`');
			$this->db->from('_dara_profile dp');
			$this->db->join('_tag_pair tp', 'dp.dara_profile_id= tp.`ref_id` AND tp.`ref_type` = 5', 'left');
			//$this->db->where('t.tag_text', $dara);
			//$this->db->where('tp.ref_type', $ref_type);
			$this->db->order_by('dp.iddara', "ASC");

			$query = $this->db->get();				
			$result =  $query->result_object();
			//Debug($result);
			//die();
			$obj = array();
			$now_date = date('Y-m-d H:i:s');

			if($result){
					$alldara = count($result);

					for($i=0;$i<$alldara;$i++){
							
							if($result[$i]->pair_id > 0 || $result[$i]->dara_profile_id == 631){
									//echo "<br>pair_id = ".$result[$i]->pair_id;
							}else{
									//Debug($result);
									//echo "<br>no pair_id";

									$ref_id = $result[$i]->dara_profile_id;

									//**************Add tags first_name last_name******************
									$tag_name = trim($result[$i]->first_name." ".$result[$i]->last_name);
									$curtag = $this->tags_model->validate_tags($tag_name);
									if(isset($curtag[0]->tag_id)) $tag_id = $curtag[0]->tag_id;

									if(!$curtag){
											$get_max_id = $this->tags_model->get_max_id();
											$tag_id = $get_max_id[0]['max_id'];
											$tag_id++;
											unset($obj);
											$obj['tag_id'] = $tag_id;
											$obj['tag_text'] = $tag_name;
											$obj['create_date'] = $now_date;
											//Debug($obj);
											$this->tags_model->store($obj);
									}
									unset($obj);

									$obj[0]['tag_id'] = intval($tag_id);
									$obj[0]['ref_id'] = $ref_id;
									$obj[0]['ref_type'] = 5;
									$obj[0]['order'] = 1;
									$obj[0]['create_date'] = $now_date;

									$this->tags_model->store_tag_pair($obj, 0);

									//**************Add tags nickname******************
									$tag_name = trim($result[$i]->nick_name);
									$curtag = $this->tags_model->validate_tags($tag_name);
									if(isset($curtag[0]->tag_id)) $tag_id = $curtag[0]->tag_id;

									if(!$curtag){
											$get_max_id = $this->tags_model->get_max_id();
											$tag_id = $get_max_id[0]['max_id'];
											$tag_id++;

											unset($obj);
											$obj['tag_id'] = $tag_id;
											$obj['tag_text'] = $tag_name;
											$obj['create_date'] = $now_date;
											//Debug($obj);
											$this->tags_model->store($obj);
									}
									unset($obj);

									$obj[0]['tag_id'] = $tag_id;
									$obj[0]['ref_id'] = $ref_id;
									$obj[0]['ref_type'] = 5;
									$obj[0]['order'] = 1;
									$obj[0]['create_date'] = $now_date;

									$this->tags_model->store_tag_pair($obj, 0);
							}

					
					}
					echo "add tags $alldara complete.";

					//$pair_id = $result[0]->pair_id;
					//Debug($result);
					//Debug($this->db->last_query());
			}	
	}
}
?>	
