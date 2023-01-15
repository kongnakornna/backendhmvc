<?php

class guidance_model extends CI_Model {

    private $DBSelect, $DBEdit;

    public function __construct() {
        parent::__construct();
		$CI = & get_instance();
    }
	
	public function get_categoryListById($cid=null,$isDetail=false, $thisID=null){
		$arrResult = array();
		$cache_key = "Guidance_category_$cid+$type";
		$sql_detail = "";
		
		$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			
			if($cid==0 && !is_null($cid)){
				if($isDetail){
					$sql_detail = ",cd.cms_category_detail as description";
				}
				$sql = "select c.cms_category_id as id , c.cms_category_name as name ".$sql_detail." from cms_category c left join cms_category_detail cd on c.cms_category_id=cd.cms_category_id where c.cms_category_id in (102,104) order by c.cms_category_id ASC";
				$arr = $this->db->query($sql);
			}else{
				if(is_null($cid)){
					$cid=$thisID;
				}
				$sql_fieldWhere = "c.cms_parent_category_id";
				if($isDetail){
					$sql_detail = ",cd.cms_category_detail as description";
				}
				if(!is_null($thisID)){
					$sql_fieldWhere = "c.cms_category_id";
				}
				$sql = "select c.cms_category_id as id , c.cms_category_name as name ".$sql_detail." from cms_category c left join cms_category_detail cd on c.cms_category_id=cd.cms_category_id where ".$sql_fieldWhere." in (%cid%) order by c.cms_category_id ASC";
				$arr = $this->db->query($sql, array(
				  '%cid%'=> $cid,
				));
			}
			
			if($arr){
				$arrResult = $arr->result_array();
			}
			
			
			$this->tppymemcached->set($cache_key, $arrResult,60);
		}
		return $arrResult;
	}
	
	public function get_cmsListById($cmsid=null,$isDetail=false,$thisID=null){
		$arrResult = array();
		$cache_key = "Guidance_cms+$cmsid+$type";
		$sql_detail = "";
		
		$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			
			if(is_null($cmsid)){
				$cmsid=$thisID;
			}
			
			$sql_fieldWhere = "cg.cms_faculty_id";
			if($cmsid==122 || $cmsid==124){ $cmsid=$cmsid.',126';}
			if($isDetail){
				$sql_detail = ",concat(cms.cms_detail_long,cg.desc1,desc2,desc3,desc4) as description";
			}
			if(!is_null($thisID)){
				$sql_fieldWhere = "cms.cms_id";
			}
			$sql = "select cms.cms_id as id,cms.cms_subject as name ".$sql_detail." from cms LEFT JOIN cms_guide_faculty cg ON cms.cms_id=cg.cms_id where cms.record_status=1 and ".$sql_fieldWhere." in ($cmsid) order by cms.cms_id ASC";
			$arr = $this->db->query($sql, array(
			  '%cmsid%'=> $cmsid,
			));
			
			
			if($arr){
				$arrResult = $arr->result_array();
			}
			
			
			$this->tppymemcached->set($cache_key, $arrResult,60);
		}
		return $arrResult;
	}
}	