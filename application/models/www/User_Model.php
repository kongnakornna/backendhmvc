<?php

class User_Model extends CI_Model{

public $_user_id;
	//public $_user_id2;
	public $_user_type_id;
	public $_user_team_id;
	public $_user_username;
	public $_user_password;
	public $_user_avatar;
	public $_user_name;
	public $_user_lastname;
	public $_user_email;
	public $_phone;
	public $_mobile;
	public $_create_date;
	public $_departmen;
	public $_address;
	public $_create_by;
	public $_lastupdate_date;
	public $_lastupdate_by;
	public $_lastlogin;
	public $_status;

	public function __construct(){
		parent::__construct();
	}

	/*
	* SET's & GET's
	* Set's and get's allow you to retrieve or set a private variable on an object
	*/
	public function setObj($v){

		if($v->user_id) $this->_user_id = $v->user_id;
		if($v->user_type_id) $this->_user_type_id = $v->user_type_id;
		if($v->user_team_id) $this->_user_team_id = $v->user_team_id;
		if($v->user_username) $this->_user_username = $v->user_username;
		if($v->user_password) $this->_user_password = $v->user_password;
		if($v->user_avatar) $this->_user_avatar = $v->user_avatar;
		if($v->user_name) $this->_user_name = $v->user_name;
		if($v->user_lastname) $this->_user_lastname = $v->user_lastname;
		if($v->department) $this->_department = $v->department;
		if($v->address) $this->_address = $v->address;
		if($v->user_email) $this->_user_email = $v->user_email;
		if($v->phone) $this->_phone = $v->phone;
		if($v->mobile) $this->_mobile = $v->mobile;
		if($v->lastlogin) $this->_lastlogin = $v->lastlogin;
		if($v->status) $this->_status = $v->status;
	}

	public function getObj(){
		return $this;
	}
	public function getId(){
		return $this->_user_id2;
		return $this->_user_id;
	}
	public function getUser(){
		return $this->_user_name;
	}
	public function getEmail(){
		return $this->_user_email;
	}

    public function getSelectUserType($default = 0, $getUserType = null, $name = "user_type_id"){
    		
    		$language = $this->lang->language;
    		
    		//debug($language);
    		//die();    		
    		$first = "--- ".$language['please_select'].$language['user_level']." ---";
    		
	    	//if($default == 0) $rows = $this->get_category(null, 1);
	    	//else $rows = $this->get_category($default, 1);

			$rows = $this->userfactory->getUserType();
			//Debug($rows);
           $user_type=$this->session->userdata('user_type');
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($getUserType);$i++){
	    		$row = @$getUserType[$i];
                   
               if($user_type==1){
               if($row->user_type_id != 0) $opt[]	= makeOption($row->user_type_id, $row->user_type_title);  
               }else{
               if($row->user_type_id != 1) $opt[]	= makeOption($row->user_type_id, $row->user_type_title);
               }
	    		
                   
                   
	    	}
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
    }


    public function Get_UserType(){

    }

	/**
	*	Commit method, this will comment the entire object to the database
	*/
	public function commit($obj = array()){
		//'user_username' => $this->_user_username,

		$this->setObj($obj);

		$data = array(
			'user_name' => $this->_user_name,
			'user_lastname' => $this->_user_lastname,
			'phone' => $this->_phone,
			'mobile' => $this->_mobile,
			'lastupdate_date' => time()
		);
		//'status' => $this->_status

		/*echo "user_id  = $user_id <br>";
		echo "<pre>";
		print_r($data);
		echo "</pre>";*/

		//$user_id = $obj->user_id;

		//if ($this->_user_id > 0) {
		/*if ($user_id > 0) {
			//We have an ID so we need to update this object because it is not new

			if ($this->db->update("_user", $data, array("user_id" => $user_id))) {
				return true;
			}
		} else {
			//We dont have an ID meaning it is new and not yet in the database so we need to do an insert
			if ($this->db->insert("_user", $data)) {
				//Now we can get the ID and update the newly created object
				$this->_user_id = $this->db->insert_id();
				return true;
			}
		}
		return false;*/
	}

    function store($id = 0, $data){

			if($id > 0){
					$this->db->where('user_id', $id);
					$this->db->update('_user', $data);
					return $this->db->last_query();
					$report = array();
					$report['error'] = $this->db->_error_number();
					$report['message'] = $this->db->_error_message();
					if($report !== 0){
						echo $this->db->last_query();
						//die();
						return true;
					}else{
						return false;
					}					
			}else{
					//$insert = $this->db->insert('_user', $data);
					//echo $this->db->last_query();
					//return $insert;
			}
	}
    
	 public function get_member_by_id($id){
		$this->db->select('*');
		$this->db->from('_user');
		$this->db->where('user_id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }   
	 
	 public function get_memberall($user_type_id=null, $keyword=null){
		$language = $this->lang->language;
		$lang=$language['lang'];		
		$this->db->select('user.*, user_type.user_type_id,user_type.user_type_title');
		//$this->db->select('user.*, user_type.*,user_detail.*');
		$this->db->from('_user as user');
		$this->db->join('_user_type as user_type', '(user.user_type_id=user_type.user_type_id AND user.lang="'.$lang.'"');
		//$this->db->join('_user_detail as user_detail', '(user_detail.user_id=user.user_id2 AND user.lang="'.$lang.'"');
		if($user_type_id != null){
			$this->db->where('user.user_type_id', $user_type_id);
		}if($keyword != null){
			$this->db->where('user.user_username', $keyword);
		}		
		$query = $this->db->get();
		return $query->result_array(); 
    }  

    function delete_user($id){
       //echo '$id='.$id; die();
        if($id>=6){
        $this->db->where('user_id', $id);
		$this->db->delete('_user'); 
        }else{
            $this->db->set('status', 0);
            $this->db->where('user_id', $id);
            $this->db->update('_user'); 
		    //echo $this->db->last_query();
            //echo '$id='.$id;die();
        }
		 
	}
    

}