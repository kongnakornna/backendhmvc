<?php

class CSA_model extends CI_Model {

    private $DBSelect, $DBEdit;

    public function __construct() {
        parent::__construct();
    }

	public function getSchoolMaster($orderby = null, $limit = null, $offset = null , $id = null, $name = null, $tvsmember = null)
	{
		$DBSelect = $this->load->database('select', TRUE);

		$sql = "select
					*
					from `school_master`
					where 1
					 and `id`=COALESCE(nullif(@id,'0'), `id`)
					 and `tvs_member`=COALESCE(nullif(@tvsmember,'0'), `tvs_member`)
					 and (`name` like concat('%',COALESCE(nullif(@name,'0'), `name`),'%') or `name`= COALESCE(nullif(@name,'0'), `name`))
					";

		$arrWhere = array(
					 '@id'=>$id
					 ,'@name'=>$name
					 ,'@tvsmember'=>$tvsmember
		);

		if($orderby=='name'){
			$orderby = " order by name";
		}elseif($orderby=="first"){
			$orderby = " order by id asc";
		}else{
			$orderby = " order by id desc";
		}
		$sql .= $orderby;

		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;
			}
		}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;
		}

		$query = $DBSelect->query($sql, $arrWhere);
		//print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		if($query)
			return $query->result();
		else
			return false;
	}

	public function getSyncData($last_unixtimestamp = null,$isIncludeData = false)
	{
			$moreQry = " limit 100";
			
			if((is_null($last_unixtimestamp)) || (empty($last_unixtimestamp)) || ($last_unixtimestamp=='') || (!isset($last_unixtimestamp)) ){
				$last_unixtimestamp=time();
			}

			$DBSelect = $this->load->database('select', TRUE);
			$arr = array();
			
			$arr["ask_timestamp"] = date('Y-m-d H:i:s', $last_unixtimestamp);
			
			// Zone : last modify
			$sql = "select max(lastModify) as lastmodify from(
						select max(add_timestamp) lastModify from school_master
						union all
						select max(update_timestamp) lastModify from school_master
						union all
						select max(delete_timestamp) lastModify from school_master_delete
					) AA;";
			//echo $sql; exit();
			$query = $DBSelect->query($sql, $arrWhere);
			if($query)
				$arr["last_modify"] = $query->row()->lastmodify;
			else
				$arr["last_modify"] = null;
			
			if($isIncludeData){
				// Zone : Insert
				$sql = "SELECT * FROM school_master where add_timestamp>'".date('Y-m-d H:i:s', $last_unixtimestamp)."'".$moreQry;
				//echo $sql; exit();
				$query = $DBSelect->query($sql, $arrWhere);
				if($query)
					$arr["insert"] = $query->result();
				else
					$arr["insert"] = null;

				// Zone : Update
				$sql = "SELECT * FROM school_master where update_timestamp>'".date('Y-m-d H:i:s', $last_unixtimestamp)."' and add_timestamp<'".date('Y-m-d H:i:s', $last_unixtimestamp)."'".$moreQry;
				//echo $sql; exit();
				$query = $DBSelect->query($sql, $arrWhere);
				if($query)
					$arr["update"] = $query->result();
				else
					$arr["update"] = null;

				// Zone : Delete
				$sql = "SELECT id,delete_timestamp FROM school_master_delete where delete_timestamp>'".date('Y-m-d H:i:s', $last_unixtimestamp)."'".$moreQry;
				//$sql = "select id, now() as delete_timestamp from school_master where id in (123,1000,2029,3030)";
				//echo $sql; exit();
				$query = $DBSelect->query($sql, $arrWhere);
				if($query)
					$arr["delete"] = $query->result();
				else
					$arr["delete"] = null;

			}
			return array('status'=>true,'data'=>$arr);

	}

	public function getONETdata($id=null , $type="master")
	{
			if((is_null($id)) || (empty($id)) || ($id=='') || (!isset($id)) ){
				return false;
			}

			$criteria = "";
			if($type=="register"){
				$criteria .= " and school_register_id=".$id;
			}else{
				$criteria .= " and school_master_id=".$id;
			}

			$DBSelect = $this->load->database('select', TRUE);

			$sql = "SELECT apply_year,onet_data_json FROM school_master_onet where 1=1 ".$criteria." order by apply_year desc";
			//echo $sql;
			$query = $DBSelect->query($sql);
			if($query){
				$resp=&$query->result();
				foreach ($resp as $key => $value) {
				   $resp[$key]->onet_data_json=(array)json_decode($value->onet_data_json);
				}
				return $resp;
			 }else{
					return false;
			 }
	}
	
	public function _isReadyDump($last_unixtimestamp = null)
	{
		if((is_null($last_unixtimestamp)) || (empty($last_unixtimestamp)) || ($last_unixtimestamp=='') || (!isset($last_unixtimestamp)) ){
				$last_unixtimestamp=time();
			}

			$DBSelect = $this->load->database('select', TRUE);
			$arr = array();
			
			// Zone : last modify
			$sql = "select sum(cnt) as sumRec from(
						select count(*) cnt from school_master where add_timestamp>'".date('Y-m-d H:i:s', $last_unixtimestamp)."' 
						union all
						select count(*) cnt from school_master where update_timestamp>'".date('Y-m-d H:i:s', $last_unixtimestamp)."' 
						union all
						select count(*) cnt from school_master_delete where delete_timestamp>'".date('Y-m-d H:i:s', $last_unixtimestamp)."' 
					) AA;";
			//echo $sql; exit();
			$query = $DBSelect->query($sql);
			
			if($query){
				$sumRec = $query->row()->sumRec;
				if($sumRec>100){
					$arr["ready"] = true;
				}else{
					$arr["ready"] = false;
				}
			}else{
				$arr["ready"] = false;
			}
			
			return array($arr);
	}
}
