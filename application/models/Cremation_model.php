<?php
class Cremation_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function get_status($id){
    	$language = $this->lang->language['lang'];
    	$this->db->select('*');
    	$this->db->from('_na_member');
    	//$this->db->where('member_id_map', $id);
    	$this->db->where('status', $id);
    	$this->db->where('lang', $language);
    	$query = $this->db->get();
    	//echo $this->db->last_query();
    	return $query->result_array();
    }

    public function get_max_id(){
        $language = $this->lang->language['lang'];
		$this->db->select('max(member_id_map) as max_id');
		$this->db->from('_na_member');
		$this->db->where('lang', $language);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_count(){
		$language = $this->lang->language['lang'];
		$this->db->select('count(member_id_map) as countid');
		$this->db->from('_na_member');
		$this->db->where('lang', $language);
		//$this->db->where('status', 1);
		$query = $this->db->get();
		return $query->result_object(); 
    }

    public function get_member_by_id($id_map){
		$language = $this->lang->language['lang'];
		$this->db->select('*');
		$this->db->from('_na_member');
		$this->db->where('member_id_map', $id_map);
		$this->db->where('lang', $language);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array(); 

    }

    public function get_member_profile($member_id_map=null, $search_string=null, $member_type = null, $gender = '', $order = 'member_id', $order_type='DESC', $member_status = null, $limit_start =null, $listpage = null){
        	//$from = $this->input->post('fromdate');
            //$to = $this->input->post('todate');
            $from = '2008-01-10';
            $to = '2015-01-10';
            $idn='3650799085644';
            $name='ประ';
            $provincename='เพรช';
        	$language = $this->lang->language['lang'];
        	$this->db->distinct();
            $this->db->select('a.*,b.member_type_name,c.status_type_name,d.geo_name,e.province_name,f.amphur_name,g.district_name,h.village_name,i.countries_name,j.zipcode');
            $this->db->from('_na_member a'); 
            $this->db->join('_member_type b', 'b.member_type_id_map=a.member_type_id', 'left');
            $this->db->join('_status_type c', 'c.status_type_id_map=a.status', 'left');
            $this->db->join('_na_geography d', 'd.geo_id_map=a.geography_id', 'left');
			$this->db->join('_na_province e', 'e.province_id_map=a.province_id_map', 'left');
			$this->db->join('_na_amphur f', 'f.amphur_id_map=a.amphur_id_map', 'left');
			$this->db->join('_na_district g', 'g.district_id_map=a.district_id_map', 'left');
			$this->db->join('_na_village h', 'h.village_id_map=a.village_id_map', 'left');
			$this->db->join('_na_countries i', 'i.countries_id=a.countries_id', 'left');
			$this->db->join('_na_zipcode j', 'j.district_id=a.district_id_map', 'left');
            $this->db->where('a.lang', $language);
            $this->db->where('b.lang', $language);
            $this->db->where('d.lang', $language);
            $this->db->where('e.lang', $language);
            $this->db->where('f.lang', $language);
            $this->db->where('g.lang', $language);
            $this->db->where('h.lang', $language);
            $this->db->where('a.member_type_id',1);
            //$this->db->where('a.status_type_id_map',2);
            //$this->db->where('a.startdate >=', $from);
			//$this->db->where('a.startdate <=', $to);
            //$this->db->where('a.idcard', $idn);
            //$this->db->or_like('a.idcard', $idn);
            //$this->db->or_like('a.name', $name);
            //$this->db->or_like('e.province_name', $provincename);
            $this->db->order_by('a.member_id','asc');         
            $this->db->limit($listpage, $limit_start);
            $query = $this->db->get(); 
 			//echo $this->db->last_query();
            if($query->num_rows() != 0){
                return $query->result_array();
            }else{
                return false;
            }
        }
    
 	public function member_join($member_id_map=null, $search_string=null, $member_type = null, $gender = '', $order = 'member_id', $order_type='DESC', $member_status = null, $limit_start =null, $listpage = null){
        	//$from = $this->input->post('fromdate');
            //$to = $this->input->post('todate');
            $from = '2008-01-10';
            $to = '2015-01-10';
            $idn='3650799085644';
            $name='ประ';
            $provincename='เพรช';
        	$language = $this->lang->language['lang'];
        	$this->db->distinct();
            $this->db->select('a.*,b.member_type_name,c.status_type_name,d.geo_name,e.province_name,f.amphur_name,g.district_name,h.village_name,i.countries_name,j.zipcode');
            $this->db->from('_na_member a'); 
            $this->db->join('_member_type b', 'b.member_type_id_map=a.member_type_id', 'left');
            $this->db->join('_status_type c', 'c.status_type_id_map=a.status', 'left');
            $this->db->join('_na_geography d', 'd.geo_id_map=a.geography_id', 'left');
			$this->db->join('_na_province e', 'e.province_id_map=a.province_id_map', 'left');
			$this->db->join('_na_amphur f', 'f.amphur_id_map=a.amphur_id_map', 'left');
			$this->db->join('_na_district g', 'g.district_id_map=a.district_id_map', 'left');
			$this->db->join('_na_village h', 'h.village_id_map=a.village_id_map', 'left');
			$this->db->join('_na_countries i', 'i.countries_id=a.countries_id', 'left');
			$this->db->join('_na_zipcode j', 'j.district_id=a.district_id_map', 'left');
            $this->db->where('a.lang', $language);
            $this->db->where('b.lang', $language);
            $this->db->where('d.lang', $language);
            $this->db->where('e.lang', $language);
            $this->db->where('f.lang', $language);
            $this->db->where('g.lang', $language);
            $this->db->where('h.lang', $language);
            $this->db->where('a.member_type_id',1);
            //$this->db->where('a.status_type_id_map',2);
            //$this->db->where('a.startdate >=', $from);
			//$this->db->where('a.startdate <=', $to);
            //$this->db->where('a.idcard', $idn);
            //$this->db->or_like('a.idcard', $idn);
            //$this->db->or_like('a.name', $name);
            //$this->db->or_like('e.province_name', $provincename);
            $this->db->order_by('a.member_id','asc');         
            #$this->db->order_by('a.member_id','desc');         
          // $this->db->limit($listpage, $limit_start);
			$this->db->limit(50, 20);
            $query = $this->db->get(); 
//echo $this->db->last_query();
            if($query->num_rows() != 0){
                return $query->result_array();
            }else{
                return false;
            }
        }

    public function get_memberall(){
		
		$language = $this->lang->language['lang'];
		$all = $this->lang->language['all'];

		$this->db->select('dp.*');
		$this->db->from('_memberna as dp');
		//$this->db->join('_member_type as dt', '(dp.member_type_id=dt.member_type_id_map AND dt.lang="'.$language.'"');
		//$this->db->join('_belong_to as bt', '(dp.belong_to_id=bt.belong_to_id', 'left');

		$this->db->where('dp.status !=', 2);

		//if($member_type) $this->db->where('dp.member_type_id', $member_type);
		//if($member_status !== null) $this->db->where('dp.status', $member_status);
		//if($gender != '' && $gender != $all) $this->db->where('dp.gender', $gender);
		//$this->db->where('dp.avatar !=', '');
		//$this->db->where('dp.avatar !=', '-');

		$this->db->order_by('idcard', "ASC");
		$this->db->limit(1000, 0);
		$query = $this->db->get();		
		
		return $query->result_array();
    }

    public function search_member($field = 'all', $search_string=null, $listpage = 20){
		
		$language = $this->lang->language['lang'];
		//$all = $this->lang->language['all'];

		$this->db->select('dp.startdate, dp.username, dp.name, dp.lastname');
		//$this->db->select('dp.startdate, dp.name, dp.lastname, dt.member_type_name, bt.belong_to');
		$this->db->from('_member_na as dp');
		//$this->db->join('_member_type as dt', '(dp.member_type_id=dt.member_type_id_map AND dt.lang="'.$language.'"');
		//$this->db->join('_belong_to as bt', '(dp.belong_to_id=bt.belong_to_id', 'left');

		if($field == 'all'){
			$this->db->like('name', $search_string);
			$this->db->or_like('lastname', $search_string);
			$this->db->or_like('startdate', $search_string);
			$this->db->or_like('username', $search_string);
		}else{
			$this->db->like($field, $search_string);
		}

		$this->db->where('dp.status !=', 2);
		$this->db->order_by('name', "ASC");

		if($listpage == 0)  $listpage = 20;
		if($listpage != 0) $this->db->limit($listpage, 0);

		$query = $this->db->get();
		//Debug($this->db->last_query());
		return $query->result_object();
    }

    function count_member($id_map=null, $search_string=null, $order=null){

		$this->db->select('*');
		$this->db->from('_na_member');
		if($id_map != null && $id_map != 0){
			$this->db->where('member_id_map', $id_map);
		}
		if($search_string){
			$this->db->like('name', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('member_id_map', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    function store($member_id = 0, $data, $showdebug = 0){
			//echo "(member_profile_id = member_id_map)";

			if($member_id > 0){
					$this->db->where('member_id', $member_id);
					$this->db->update('_na_member', $data);

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
					$this->db->insert('_na_member', $data);
					if($showdebug > 0) Debug($this->db->last_query());
					$insert = $this->db->insert_id();
					return $insert;
			}
	}
/*
    function store($data){
		$insert = $this->db->insert('_na_member', $data);
	    return $insert;
	}
*/
    function store_update($member_id, $data){

		$this->db->where('member_id_map', $member_id);
		$this->db->update('_na_member', $data);
		$report = array();
		//$report['error'] = $this->db->_error_number();
		//$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	} 

	function status_member($id_map, $enable = 1){

		//$data['status'] = $enable;
		$this->db->where('member_id_map', $id_map);
		$this->db->update('_na_member', $data);
		
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
	
    function delete_member_profile($id_map){

		$data = array(
				'status' => 2
		);
		$this->db->where('member_id_map', $id_map);
		$this->db->update('_na_member', $data);
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

	function delete_member_admin($id_map){
		$this->db->where('member_id_map', $id_map);
		$this->db->delete('_na_member'); 
	}
/*
	function delete_tag($member, $ref_type = 5){
			
			$this->db->select('tp.*, t.tag_text');
			$this->db->from('_tag_pair tp');
			$this->db->join('_tag t', 'tp.tag_id=t.tag_id and t.status = 1');
			$this->db->where('t.tag_text', $member);
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
*/
/*
    public function load_sstv($page = 1, $page_size = 10, $lang ='th'){

				$get_url = "http://www.siammember.com/apisiammember/select_profile_member.php?page_size=".$page_size;
				$jsonData = file_get_contents($get_url);
				$jsonobj = json_decode($jsonData, true);

				return $jsonobj;
	}
*/
/*
	public function import_sstv_to_db(&$data = array()){

					$insert = $this->db->insert_batch('_na_member', $data);
					return $insert;
	}
*/
/* 
	public function auto_tags(){

			$this->load->model('tags_model');

			//SELECT dp.`member_profile_id`, dp.idcard,  dp.`name`, dp.`lastname` , dp.`startdate`, tp.`pair_id` FROM `sd_member_profile` dp LEFT JOIN `sd_tag_pair` tp ON dp.`member_profile_id`= tp.`ref_id` AND tp.`ref_type` = '5'  ORDER BY dp.idcard

			$this->db->select('dp.`member_profile_id`, dp.idcard,  dp.`name`, dp.`lastname` , dp.`startdate`, tp.`pair_id`');
			$this->db->from('_na_member dp');
			$this->db->join('_tag_pair tp', 'dp.member_profile_id= tp.`ref_id` AND tp.`ref_type` = 5', 'left');
			//$this->db->where('t.tag_text', $member);
			//$this->db->where('tp.ref_type', $ref_type);
			$this->db->order_by('dp.idcard', "ASC");

			$query = $this->db->get();				
			$result =  $query->result_object();
			//Debug($result);
			//die();
			$obj = array();
			$now_date = date('Y-m-d H:i:s');

			if($result){
					$allmember = count($result);

					for($i=0;$i<$allmember;$i++){
							
							if($result[$i]->pair_id > 0 || $result[$i]->member_profile_id == 631){
									//echo "<br>pair_id = ".$result[$i]->pair_id;
							}else{
									//Debug($result);
									//echo "<br>no pair_id";

									$ref_id = $result[$i]->member_profile_id;

									//**************Add tags name lastname******************
									$tag_name = trim($result[$i]->name." ".$result[$i]->lastname);
									$curtag = $this->tags_model->validate_tags($tag_name);
									if(isset($curtag[0]->tag_id)) $tag_id = $curtag[0]->tag_id;

									if(!$curtag){
											$get_max_id = $this->tags_model->get_max_id();
											$tag_id = $get_max_id[0]['max_id'];
											$tag_id++;
											unset($obj);
											$obj['tag_id'] = $tag_id;
											$obj['tag_text'] = $tag_name;
											$obj['member_id_map'] = $now_date;
											//Debug($obj);
											$this->tags_model->store($obj);
									}
									unset($obj);

									$obj[0]['tag_id'] = intval($tag_id);
									$obj[0]['ref_id'] = $ref_id;
									$obj[0]['ref_type'] = 5;
									$obj[0]['order'] = 1;
									$obj[0]['member_id_map'] = $now_date;

									$this->tags_model->store_tag_pair($obj, 0);

									//**************Add tags nickname******************
									$tag_name = trim($result[$i]->startdate);
									$curtag = $this->tags_model->validate_tags($tag_name);
									if(isset($curtag[0]->tag_id)) $tag_id = $curtag[0]->tag_id;

									if(!$curtag){
											$get_max_id = $this->tags_model->get_max_id();
											$tag_id = $get_max_id[0]['max_id'];
											$tag_id++;

											unset($obj);
											$obj['tag_id'] = $tag_id;
											$obj['tag_text'] = $tag_name;
											$obj['member_id_map'] = $now_date;
											//Debug($obj);
											$this->tags_model->store($obj);
									}
									unset($obj);

									$obj[0]['tag_id'] = $tag_id;
									$obj[0]['ref_id'] = $ref_id;
									$obj[0]['ref_type'] = 5;
									$obj[0]['order'] = 1;
									$obj[0]['member_id_map'] = $now_date;

									$this->tags_model->store_tag_pair($obj, 0);
							}

					
					}
					echo "add tags $allmember complete.";

					//$pair_id = $result[0]->pair_id;
					//Debug($result);
					//Debug($this->db->last_query());
			}	
	}
	*/
}
?>	
