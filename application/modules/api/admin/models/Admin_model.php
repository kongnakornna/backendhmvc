<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); header('Content-type: text/html; charset=utf-8');

class Admin_model extends CI_Model {
  
	public function __construct() {
    	parent::__construct();    
    	//$CI =& get_instance();
    	// $this->load->library('tppymemcached');
  	}

  	public function filter_users($filter)
  	{
  		$sql = "";

  		$q = isset($filter['q']) ? $filter['q'] : "";
  		if(!empty($q)){
  			$sql .= "AND CONCAT_WS(' ', s.school_name, s.province, u.name, u.surname) LIKE '%$q%'";
  		}
  		$remark_pmo = isset($filter['remark_pmo']) ? $filter['remark_pmo'] : "";
  		if(!empty($remark_pmo)){
  			$sql .= "AND u.remark_PMO = '$remark_pmo'";
  		}

  		return $sql;
  	}

  	public function getReportUsers($filter = "")
	{
		$sql = ("SELECT 
			u.user_idx,u.username, concat(salutation,' ',u.name,' ',u.surname) user_realname
			,(SELECT COUNT(*) FROM tbl_pms_trans WHERE user_idx=u.user_idx) total_trans
			,(SELECT COUNT(*) FROM tbl_pms_trans WHERE user_idx=u.user_idx AND date_action IS NOT NULL AND pms_topic_idx IS NOT NULL) action_trans
			,(SELECT COUNT(*) FROM tbl_pms_trans WHERE user_idx=u.user_idx AND date_save IS NOT NULL) save_trans
			,u.remark_PMO, s.school_name, s.province
			FROM tbl_user u
			LEFT JOIN tbl_school_master s ON u.user_id = s.ict_id
			WHERE u.username LIKE 'ICT%'
		");
		if(!empty($filter)){
			$sql .= $this->filter_users($filter);
		}
		$sql .= "ORDER BY total_trans DESC";
		
		$query = $this->db->query($sql);

		if($query && !empty($query)){
  			return $query->result();
	  	}else{
	  		return false;
	  	}
	}

	public function getTransOfDate($user_id, $date_action)
	{
		$query = $this->db->query("
			SELECT topic.pms_name, trans.action_name, trans.action_target, trans.action_member_count, trans.action_detail, trans.action_duration, trans.action_media
			FROM tbl_pms_trans trans, tbl_pms_topic topic
			WHERE (user_idx = $user_id AND date_action = '$date_action') AND (topic.idx = trans.pms_topic_idx)
		");

		if($query && !empty($query)){
  			return $query->result();
	  	}else{
	  		return false;
	  	}
	}

	public function getUsers($user_id)
	{
		$this->db->select('*');
		$this->db->select('CONCAT(name," ",surname) AS full_name');
		$this->db->from('tbl_user');
		$this->db->where('user_idx', $user_id);
		$query = $this->db->get();

		if($query && !empty($query)){
  			return $query->result();
	  	}else{
	  		return false;
	  	}
	}

	public function searchTransByMonthYear($user_id, $month, $year)
	{
		
		// $query = $this->db->query("
		// 	SELECT t.user_idx, CONCAT(u.name,' ',u.surname) AS full_name, COUNT(t.date_action) AS count_action, date_action, date_save
		// 	FROM tbl_pms_trans t, tbl_user u
		// 	WHERE t.user_idx = '$user_id' AND (t.date_action_m = '$month' AND t.date_action_y = '$year') AND t.user_idx = u.user_idx
		// 	GROUP BY date_action
		// ");

		$this->db->select('t.user_idx, CONCAT(u.name," ",u.surname) AS full_name, date_action, date_save, (CASE WHEN t.pms_topic_idx IS NULL THEN 0 ELSE COUNT(date_action) END) AS count_action');
		$this->db->from('tbl_pms_trans t, tbl_user u');
		$this->db->where('t.user_idx', $user_id);
		$this->db->where('t.date_action_m', $month);
		$this->db->where('t.date_action_y', $year);
		$this->db->where('t.user_idx = u.user_idx');
		$this->db->group_by('date_action');
		$query = $this->db->get();
		// echo $this->db->last_query(); die;
		
		if($query && !empty($query)){
  			return $query->result();
	  	}else{
	  		return false;
	  	}
	}

	public function unsubmitTrans($user_id, $date_save)
	{
		$status = false;
		if($user_id && $date_save){
			$data['date_save'] = NULL;
			$data['date_save_d'] = NULL;
			$data['date_save_m'] = NULL;
			$data['date_save_y'] = NULL;
			
			$this->db->where('user_idx', $user_id);
			$this->db->where('date_save', $date_save);
			$this->db->update('tbl_pms_trans', $data);

			// $this->db->query("
			// 	UPDATE tbl_pms_trans
			// 	SET date_save = null, date_save_d = null, date_save_m = null, date_save_y = null
			// 	WHERE (user_idx = $user_id AND date_save = '$date_save')
			// ");
			$query = $this->db->affected_rows();

			if($query > 0){
			   $status =  true;
			}
		}

		return $status;
	}

	public function getRemarkPmaByGroupInUser()
	{
		$this->db->select('remark_PMO');
		$this->db->from('tbl_user');
		$this->db->where('remark_PMO IS NOT NULL');
		$this->db->group_by('remark_PMO');
		$query = $this->db->get();

		if($query && !empty($query)){
  			return $query->result();
	  	}else{
	  		return false;
	  	}
	}

	public function searchRemarkPmo($remark_pmo)
	{
		$query = $this->db->query("
			SELECT 
			u.user_idx,u.username, concat(salutation,' ',u.name,' ',u.surname) user_realname
			,(SELECT COUNT(*) FROM tbl_pms_trans WHERE user_idx=u.user_idx) total_trans
			,(SELECT COUNT(*) FROM tbl_pms_trans WHERE user_idx=u.user_idx AND date_action IS NOT NULL AND pms_topic_idx IS NOT NULL) action_trans
			,(SELECT COUNT(*) FROM tbl_pms_trans WHERE user_idx=u.user_idx AND date_save IS NOT NULL) save_trans
			,u.remark_PMO, s.school_name, s.province
			FROM tbl_user u
			LEFT JOIN tbl_school_master s ON u.user_id = s.ict_id
			WHERE u.username LIKE 'ICT%' AND u.remark_PMO = '$remark_pmo'
			ORDER BY total_trans DESC
		");

		if($query && !empty($query)){
  			return $query->result();
	  	}else{
	  		return false;
	  	}
	}

	public function seachKey($filter)
	{
		$query = $this->db->query("
			SELECT 
			u.user_idx,u.username, concat(salutation,' ',u.name,' ',u.surname) user_realname
			,(SELECT COUNT(*) FROM tbl_pms_trans WHERE user_idx=u.user_idx) total_trans
			,(SELECT COUNT(*) FROM tbl_pms_trans WHERE user_idx=u.user_idx AND date_action IS NOT NULL AND pms_topic_idx IS NOT NULL) action_trans
			,(SELECT COUNT(*) FROM tbl_pms_trans WHERE user_idx=u.user_idx AND date_save IS NOT NULL) save_trans
			,u.remark_PMO, s.school_name, s.province
			FROM tbl_user u
			LEFT JOIN tbl_school_master s ON u.user_id = s.ict_id
			WHERE u.username LIKE 'ICT%' AND CONCAT_WS(' ', s.school_name, s.province, u.name, u.surname) LIKE '%$filter%'
			ORDER BY total_trans DESC
		");
		// echo($query); die;

		if($query && !empty($query)){
  			return $query->result();
	  	}else{
	  		return false;
	  	}
	}

	public function insertUserLog($table_query, $data)
	{
		$status = false;
		$this->db->insert($table_query, $data);
		$query = $this->db->affected_rows();

		if($query > 0){
		   $status = true;
		}
		
		return $status;
	}

	public function getUnsubmit($user_id, $date_save)
	{
		$this->db->where('user_idx', $user_id);
		$this->db->where('date_save', $date_save);
		$query = $this->db->get('tbl_pms_trans');

		if($query && !empty($query)){
  			return $query->result();
	  	}else{
	  		return false;
	  	}
	}

	public function getSummaryWorkByMonthYear($user_id, $month, $year)
	{
		$this->db->select("COUNT(DISTINCT date_action) AS trans_all");
		$this->db->select("(SELECT COUNT(DISTINCT date_action) FROM tbl_pms_trans WHERE user_idx = '$user_id' AND date_action_m = '$month' AND date_action_y = '$year' AND date_save IS NOT NULL AND pms_topic_idx IS NOT NULL) AS trans_confirm_topic");
		$this->db->select("(SELECT COUNT(DISTINCT date_action) FROM tbl_pms_trans WHERE user_idx = '$user_id' AND date_action_m = '$month' AND date_action_y = '$year' AND date_save IS NOT NULL AND pms_topic_idx IS NULL) AS trans_confirm_notopic");
		$this->db->select("(SELECT COUNT(DISTINCT date_action) FROM tbl_pms_trans WHERE user_idx = '$user_id' AND date_action_m = '$month' AND date_action_y = '$year' AND date_save IS NULL) AS trans_notconfirm");
		$this->db->where('user_idx', $user_id);
		$this->db->where('date_action_m', $month);
		$this->db->where('date_action_y', $year);
		$query = $this->db->get('tbl_pms_trans');
		// echo $this->db->last_query(); die;
		if($query && !empty($query)){
  			return $query->result();
	  	}else{
	  		return false;
	  	}
	}

	public function getTopicAndSummary($user_id, $month, $year)
	{
		// $this->db->select("COUNT(tbl_pms_trans.idx) AS topic_summary");
		// $this->db->join("tbl_pms_topic", "tbl_pms_topic.idx = tbl_pms_trans.pms_topic_idx");
		// $this->db->where($data);
		// $query = $this->db->get('tbl_pms_trans');

		$this->db->select("idx, pms_name");
		$this->db->select("(SELECT COUNT(DISTINCT date_action)  FROM tbl_pms_trans WHERE user_idx = '$user_id' AND date_action_m = '$month' AND date_action_y = '$year' AND pms_topic_idx = tbl_pms_topic.idx ORDER BY date_action) AS topic_summary");
		$query = $this->db->get('tbl_pms_topic');
		// echo $this->db->last_query(); die;
		if($query && !empty($query)){
  			return $query->result();
	  	}else{
	  		return false;
	  	}
	}

	public function getActionOfTopic($data)
	{
		$this->db->select("*");
		$this->db->where($data);
		$this->db->group_by("date_action");
		$this->db->order_by("date_action", "ASC");
		$query = $this->db->get('tbl_pms_trans');

		if($query && !empty($query)){
  			return $query->result();
	  	}else{
	  		return false;
	  	}
	}

}