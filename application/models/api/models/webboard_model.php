<?php
class webboard_model extends CI_Model {

    private $DBSelect, $DBEdit;
    public function __construct() {
        parent::__construct();
    }
	function setViewNumber($content_id, $table){
		$DBEdit = $this->load->database('edit', TRUE);
		//$sql = "update mul_sum_view set mul_view_value=mul_view_value+1 where mul_content_id='".$content_id."' and mul_view_table='".$table."'";
		//$result = $DBEdit->query($sql);
		$w = array ('mul_content_id' => $content_id, 'mul_view_table' => $table);
		$DBEdit->where($w) ;
		$DBEdit->set('mul_view_value', '`mul_view_value`+1', FALSE);
		$DBEdit->update('mul_sum_view');
		
		if($DBEdit->affected_rows()<1)
		{
			//do insert
			$data = array (
				'mul_content_id' => $content_id,
				'mul_view_table' => $table,
				'mul_view_value' => 1,
			);
			$DBEdit->insert('mul_sum_view', $data); 
		}
		
		//$CI = & get_instance();
		//$CI->load->library('tppymemcached');
		
		return true;
	}
	function getViewNumber($content_id, $table){
		$DBSelect = $this->load->database('select', TRUE);
		
		$sql = "SELECT IFNULL(mul_view_value,0) as num_view FROM `mul_sum_view` WHERE mul_content_id = '".$content_id."' AND mul_view_table = '15'";
		if(!$row = $this->tppymemcached->get("getViewNumber_content_".$content_id."_table_".$table))
		{
			$query = $DBSelect->query($sql);
			$row = $query->result_array();
			$this->tppymemcached->set("getViewNumber_content_".$content_id."_table_".$table,$row);
		}
		return $DBSelect->query($sql)->row()->num_view;
	}
	public function getCountTopic($categoryid){
		$DBSelect = $this->load->database('select', TRUE);
		
		$sql = "SELECT IFNULL(count(wb_post_id),0) as num_topic FROM webboard_post WHERE wb_category_id = '".$categoryid."' AND wb_status = '1' and member_id != '' ";
		return $DBSelect->query($sql)->row()->num_topic;
	}
	public function getCountReply($postid){
		$DBSelect = $this->load->database('select', TRUE);
		
		$sql = "SELECT IFNULL(count(wb_reply_id),0) as num_reply FROM webboard_reply WHERE wb_post_id = '".$postid."' AND reply_status = '1' ";
		return $DBSelect->query($sql)->row()->num_reply;
	}
	public function getLastestReply($postid){
		$DBSelect = $this->load->database('select', TRUE);
		
		$sql = "SELECT wr.reply_by_name , wr.reply_datetime, u.psn_display_name,u.psn_display_image,u.user_id as blog_id 
					FROM webboard_reply wr 
					LEFT JOIN users_account u on wr.member_id=u.member_id
					WHERE wr.wb_post_id = '".$postid."' and wr.reply_status ='1' 
					ORDER BY wr.wb_reply_id DESC LIMIT 0, 1 ";
		$query = $DBSelect->query($sql);
     //echo $DBSelect->last_query();
		$row_reply = $query->result_array();
		/*if(!$row_reply = $memcache->get(md5("1webboard_list_main_getLastestReply_".$postid)))
		{
			$qry = mysql_query($sql, $DBSelect);
			$row_reply = mysql_fetch_array($qry);
			$memcache->set(md5("1webboard_list_main_getLastestReply_".$postid),$row_reply,60);
		}*/
		//if(mysql_num_rows($qry) > 0){
		foreach ($row_reply as $v)
		{
			$return['id'] = $v['blog_id'];
			$return['name'] = htmlspecialchars($v['psn_display_name']);
			$return['display_image'] = $v['psn_display_image'];
			$return['date'] = $v['reply_datetime'];
			$return['temp_name'] = $v["reply_by_name"];
			return $return;
		}
		return false;
	}
	public function getMaxReplyPageNo($postid , $pageSize){
		$DBSelect = $this->load->database('select', TRUE);
		
		$selTotalReply = "select count(*) as num_reply from webboard_reply where wb_post_id = '".$postid."' and reply_status = '1' ";
		$q_total_reply = mysql_query($selTotalReply, $DBSelect);
		$row_total_reply = mysql_fetch_assoc( $q_total_reply );
		/*if(!$row_total_reply = $memcache->get(md5("1webboard_list_main_getMaxReplyPageNo_".$postid)))
		{
			$q_total_reply = mysql_query($selTotalReply, $DBSelect);
			$row_total_reply = mysql_fetch_assoc( $q_total_reply );
			$memcache->set(md5("1webboard_list_main_getMaxReplyPageNo_".$postid),$row_total_reply,60);
			mysql_free_result( $q_total_reply );
		}*/

		$total_page = ceil($row_total_reply['num_reply'] / $pageSize);

		if( !$total_page )
			$total_page = 1;

		return $total_page;

	}
    //------------------
	public function getCategoryName($categoryid = 0, $arrFilter=array()){
		if(empty($categoryid) || $categoryid==null || (int)$categoryid==0){
			return "";
		}
		
		// if(is_array($categoryid)){
			// $categoryid = implode(',',$categoryid);
		// }
		
		$DBSelect = $this->load->database('select', TRUE);
		if($categoryid>1000000){
			$sql = "select 
						mc.category_id as id
						,mc.category_name_th as name
						,mc.category_parent_id as parent_id
						,(select category_name_th from cmsblog_category where category_id=mc.category_parent_id) as parent_name
						,1000000 as room_id
						,tc.meaning as room_name 
						from cmsblog_category mc 
						left join tb_code tc on mc.zone_id=tc.fieldValue and tableName='CMSBLOG_CATEGORY' and fieldName='ZONE_ID'
						where mc.category_id=$categoryid";
		}else{
			$sql = "select 
						wc.wb_category_id as id
						,wc.wb_category_name as name
						,wc.wb_category_parent_id as parent_id
						,(select wb_category_name from webboard_category where wb_category_id=wc.wb_category_parent_id) as parent_name
						,wr.wb_room_id as room_id
						,wr.wb_room_name as room_name 
						from webboard_category wc 
						left join webboard_room wr on wc.wb_room_id=wr.wb_room_id
						where wc.wb_category_id=$categoryid";
		}
		//echo $sql;
		$query = $DBSelect->query($sql);
		$r="";
		if($query){
			$result = $query->row();
			if($arrFilter['getFullParent']==true){
				if($result->room_name!=null || $result->room_name!="")  $r .= $result->room_name . ' > ' ;
				if($result->parent_name!=null || $result->parent_name!="")  $r .= $result->parent_name . ' > ' ;
			}
			$r .= $result->name;
		}
		//var_dump($r);
		return $r;
	}
	public function getCategory($room = null, $categoryid = null , $order = '', $limit = 10, $offset = 0, $arrFilter = array() ) {
        // if(empty($room))
            // return false;
		
		if(!is_array($room) && strpos($room,',')>0){
			$room = explode(',',$room);
		}
		if(!is_array($categoryid) && strpos($categoryid,',')>0){
			$categoryid = explode(',',$categoryid);
		}
		if($arrFilter['parentcat']!=null){
			$parentcategoryid = $arrFilter['parentcat'];
			if(!is_array($parentcategoryid) && strpos($parentcategoryid,',')>0){
				$parentcategoryid = explode(',',$parentcategoryid);
			}elseif($parentcategoryid=='root'){
				$parentcategoryid = '0';
			}
		}
		
		$DBSelect = $this->load->database('select', TRUE);
        
        $DBSelect->select('wc.*,wr.*');
        $DBSelect->from('webboard_category as wc');
		$DBSelect->join('webboard_room as wr','wc.wb_room_id = wr.wb_room_id');
		
		// $sql = "select wc.*,wr.* 
					// from webboard_category wc 
					// left join webboard_room wr on wc.wb_room_id = wr.wb_room_id
					// where 1=1 ";
        
		if(isset($room) && $room!=null){
			//$sql .= ' and wc.wb_room_id in ('.$room.')';
			$DBSelect->where_in('wc.wb_room_id', $room);  // fix for CSA
		}
		
		if(isset($categoryid) && $categoryid!=null){
			//$sql .= ' and wc.wb_category_id in ('.$categoryid.')';
			$DBSelect->where_in('wc.wb_category_id',$categoryid);
		}
			
		if(isset($parentcategoryid) && $parentcategoryid!=null){
			//$sql .= ' and wc.wb_category_parent_id in ('.$parentcategoryid.')';
			$DBSelect->where_in('wc.wb_category_parent_id',$parentcategoryid);
		}
			
        if(isset($order) and $order){
			//$sql .= ' order by '.$order;
			$DBSelect->order_by($order); 
		}else{
			//$sql .= ' order by wc.wb_category_id asc';
			$DBSelect->order_by('wc.wb_category_id asc'); 
		}
            
        if(isset($offset) and $offset){
			//$sql .= ' limit '.$limit.','.$offset;
			$DBSelect->limit($limit, $offset);
		} else{
			//$sql .= ' limit '.$limit;
			$DBSelect->limit($limit);
		}
         
		//echo $sql;
		//$query = $DBSelect->query($sql);
        $query = $DBSelect->get();
		//echo ($DBSelect->last_query()); //die;
		
		
        if($query) {
            return $query->result_array();
        } else {
            return false;
        }
	}
	public function getTopic($room = null, $categoryid = null , $topicSearch = null , $order = null, $limit = 1, $offset = null , $topicid = null, $arrFilter = array()) {
        // if(empty($room))
            // return false;
		if(is_array($room)){
			$room = implode(',',$room);
		}
		if(is_array($categoryid)){
			$categoryid = implode(',',$categoryid);
		}
		
		// $criteria = "";
		// $criteria .= " and wp.wb_status=1";
		
		// if(isset($room)){
			// $criteria .= " and wc.wb_room_id in ($room)";
		// }
		// if(isset($categoryid)){
			// $criteria .= " and wp.wb_category_id in ($categoryid)";
		// }	
		// if(isset($topicid)){
			// $criteria .= " and wp.wb_post_id in ($topicid)";
		// }
		// if(isset($topicSearch)){
			// $criteria .= " and wp.wb_subject like '%$topicSearch%'";
		// }
		// if($arrFilter['isPin'] && $arrFilter['isPin'] != null) {
			// $criteria .= " and wp.flag_pin=1";
		// }
		// $sqlOrderLimit = "";
		// if(isset($order) and $order){
			// $sqlOrderLimit .= " order by $order";
		// } else{
			// $sqlOrderLimit .= " order by wp.wb_category_id asc, wp.flag_pin desc, wp.wb_post_id desc";
		// }
        // if(isset($offset) and $offset){
			// $sqlOrderLimit .= " limit $limit, $offset";
		// }else{
			// $sqlOrderLimit .= " limit $limit";
		// }
		
		$criteria = "";
		$criteria .= " and wb_status=1";
		
		if(isset($room)){
			$criteria .= " and wb_room_id in ($room)";
		}
		if(isset($categoryid)){
			$criteria .= " and wb_category_id in ($categoryid)";
		}	
		if(isset($topicid)){
			if(is_array($topicid)){
				$topicid = implode(',',$topicid);
			}
			$criteria .= " and wb_post_id in ($topicid)";
		}
		if(isset($topicSearch)){
			$criteria .= " and (wb_subject like '%$topicSearch%' or wb_subject='$topicSearch') ";
		}
		if($arrFilter['isPin'] && $arrFilter['isPin'] != null) {
			$criteria .= " and flag_pin=1";
		}
		
		$sqlOrderLimit = "";
		if(isset($order) and $order){
			$order = str_replace('wp.','',$order);
			$sqlOrderLimit .= " order by $order";
		} else{
			$sqlOrderLimit .= " order by wb_category_id asc, flag_pin desc, wb_post_id desc";
		}
        if(isset($offset) and $offset){
			$sqlOrderLimit .= " limit $limit, $offset";
		}else{
			$sqlOrderLimit .= " limit $limit";
		}
		
		$DBSelect = $this->load->database('select', TRUE);
		
		$sql = "select A.*
					,ifnull(if(A.user_id is not null,(select psn_display_name from users_account where user_id=A.user_id),(select psn_display_name from users_account where member_id=A.member_id)),'Guest') psn_display_name
					,ifnull(if(A.user_id is not null,(select psn_display_image from users_account where user_id=A.user_id),(select psn_display_image from users_account where member_id=A.member_id)),'http://static.trueplookpanya.com/tppy/member_default.jpg') psn_display_image
					from (
						select 
						wp.*
						, case
						 when wp.wb_category_id>1000000 then 1000000
						 else wc.wb_room_id
						 end as wb_room_id
						, case
						 when wp.wb_category_id>1000000 then 'cmsblog'
						 else wr.wb_room_name
						 end as wb_room_name
						, case
						 when wp.wb_category_id>1000000 then cc.category_name_th
						 else wc.wb_category_name
						 end as wb_category_name
						, case
						 when wp.wb_category_id>1000000 then cc.direct_link
						 else wc.wb_category_desc
						 end as wb_category_desc
						,if(wp.user_id is not null,wp.user_id,wp.member_id) as blog_id
						from webboard_post as wp
						left join webboard_category wc on wp.wb_category_id = wc.wb_category_id and wp.wb_category_id<1000000
						left join cmsblog_category cc on wp.wb_category_id = cc.category_id and wp.wb_category_id>1000000
						left join webboard_room wr on wc.wb_room_id = wr.wb_room_id and wp.wb_category_id>1000000
					) A where 1=1 ".$criteria.$sqlOrderLimit;
		
		// $sql = "select A.*
					// ,ifnull(if(A.user_id is not null,(select psn_display_name from users_account where user_id=A.user_id),(select psn_display_name from users_account where member_id=A.member_id)),'Guest') psn_display_name
					// ,ifnull(if(A.user_id is not null,(select psn_display_image from users_account where user_id=A.user_id),(select psn_display_image from users_account where member_id=A.member_id)),'http://static.trueplookpanya.com/tppy/member_default.jpg') psn_display_image
					// from (
						// select 
						// wp.*
						// , wc.wb_room_id 
						// , wr.wb_room_name
						// , wc.wb_category_name
						// , wc.wb_category_desc
						// ,if(wp.user_id is not null,wp.user_id,wp.member_id) as blog_id
						// from webboard_post as wp
						// left join webboard_category wc on wp.wb_category_id = wc.wb_category_id
						// left join webboard_room wr on wc.wb_room_id = wr.wb_room_id
						// where 1=1 ".$criteria.$sqlOrderLimit."
					// ) A";
		//echo $sql;
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$query = $this->db->query($sql);
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult, 60);
		}
		
		return $arrResult;
	}
	public function getTopic2($room = null, $categoryid = null , $topicSearch = null , $order = null, $limit = 1, $offset = null , $topicid = null, $arrFilter = array()) {
        // if(empty($room))
            // return false;
		if(is_array($room)){
			$room = implode(',',$room);
		}
		if(is_array($categoryid)){
			$categoryid = implode(',',$categoryid);
		}
		
		// $criteria = "";
		// $criteria .= " and wp.wb_status=1";
		
		// if(isset($room)){
			// $criteria .= " and wc.wb_room_id in ($room)";
		// }
		// if(isset($categoryid)){
			// $criteria .= " and wp.wb_category_id in ($categoryid)";
		// }	
		// if(isset($topicid)){
			// $criteria .= " and wp.wb_post_id in ($topicid)";
		// }
		// if(isset($topicSearch)){
			// $criteria .= " and wp.wb_subject like '%$topicSearch%'";
		// }
		// if($arrFilter['isPin'] && $arrFilter['isPin'] != null) {
			// $criteria .= " and wp.flag_pin=1";
		// }
		// $sqlOrderLimit = "";
		// if(isset($order) and $order){
			// $sqlOrderLimit .= " order by $order";
		// } else{
			// $sqlOrderLimit .= " order by wp.wb_category_id asc, wp.flag_pin desc, wp.wb_post_id desc";
		// }
        // if(isset($offset) and $offset){
			// $sqlOrderLimit .= " limit $limit, $offset";
		// }else{
			// $sqlOrderLimit .= " limit $limit";
		// }
		
		$criteria = "";
		$criteria .= " and wb_status=1";
		
		if(isset($room)){
			$criteria .= " and wb_room_id in ($room)";
		}
		if(isset($categoryid)){
			$criteria .= " and wb_category_id in ($categoryid)";
		}	
		if(isset($topicid)){
			if(is_array($topicid)){
				$topicid = implode(',',$topicid);
			}
			$criteria .= " and wb_post_id in ($topicid)";
		}
		if(isset($topicSearch)){
			$criteria .= " and (wb_subject like '%$topicSearch%' or wb_subject='$topicSearch') ";
		}
		if($arrFilter['isPin'] && $arrFilter['isPin'] != null) {
			$criteria .= " and flag_pin=1";
		}
		
		$sqlOrderLimit = "";
		if(isset($order) and $order){
			$order = str_replace('wp.','',$order);
			$sqlOrderLimit .= " order by $order";
		} else{
			$sqlOrderLimit .= " order by wb_category_id asc, flag_pin desc, wb_post_id desc";
		}
        if(isset($offset) and $offset){
			$sqlOrderLimit .= " limit $limit, $offset";
		}else{
			$sqlOrderLimit .= " limit $limit";
		}
		
		$DBSelect = $this->load->database('select', TRUE);
		
		$sql = "select A.*
					,ifnull(if(A.user_id is not null,(select psn_display_name from users_account where user_id=A.user_id),(select psn_display_name from users_account where member_id=A.member_id)),'Guest') psn_display_name
					,ifnull(if(A.user_id is not null,(select psn_display_image from users_account where user_id=A.user_id),(select psn_display_image from users_account where member_id=A.member_id)),'http://static.trueplookpanya.com/tppy/member_default.jpg') psn_display_image
					from (
						select 
						wp.*
						, case
						 when wp.wb_category_id>1000000 then 1000000
						 else wc.wb_room_id
						 end as wb_room_id
						, case
						 when wp.wb_category_id>1000000 then 'cmsblog'
						 else wr.wb_room_name
						 end as wb_room_name
						, case
						 when wp.wb_category_id>1000000 then cc.category_name_th
						 else wc.wb_category_name
						 end as wb_category_name
						, case
						 when wp.wb_category_id>1000000 then cc.direct_link
						 else wc.wb_category_desc
						 end as wb_category_desc
						,if(wp.user_id is not null,wp.user_id,wp.member_id) as blog_id
						from webboard_post as wp
						left join webboard_category wc on wp.wb_category_id = wc.wb_category_id and wp.wb_category_id<1000000
						left join cmsblog_category cc on wp.wb_category_id = cc.category_id and wp.wb_category_id>1000000
						left join webboard_room wr on wc.wb_room_id = wr.wb_room_id and wp.wb_category_id>1000000
					) A where 1=1 ".$criteria.$sqlOrderLimit;
		
		// $sql = "select A.*
					// ,ifnull(if(A.user_id is not null,(select psn_display_name from users_account where user_id=A.user_id),(select psn_display_name from users_account where member_id=A.member_id)),'Guest') psn_display_name
					// ,ifnull(if(A.user_id is not null,(select psn_display_image from users_account where user_id=A.user_id),(select psn_display_image from users_account where member_id=A.member_id)),'http://static.trueplookpanya.com/tppy/member_default.jpg') psn_display_image
					// from (
						// select 
						// wp.*
						// , wc.wb_room_id 
						// , wr.wb_room_name
						// , wc.wb_category_name
						// , wc.wb_category_desc
						// ,if(wp.user_id is not null,wp.user_id,wp.member_id) as blog_id
						// from webboard_post as wp
						// left join webboard_category wc on wp.wb_category_id = wc.wb_category_id
						// left join webboard_room wr on wc.wb_room_id = wr.wb_room_id
						// where 1=1 ".$criteria.$sqlOrderLimit."
					// ) A";
		//echo $sql;
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$query = $this->db->query($sql);
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult, 60);
		}
		
		return $arrResult;
	}
	public function getTopicDetail($topicid , $order_reply = null, $limit_reply = 10, $offset_reply = null) {
        if(empty($topicid))
            return false;
		
		$order = $order_reply;
		$limit = $limit_reply;
		$offset = $offset_reply;
		
		$criteria = "";
		$criteria .= " and wp.wb_status=1";
		
		if(isset($topicid)){
			$criteria .= " and wp.wb_post_id in ($topicid)";
		}
		
		if(isset($order) and $order){
			$sql .= " order by $order";
		} else{
			$sql .= " order by wp.wb_category_id asc, wp.flag_pin desc, wp.wb_post_id desc";
		}
        
        $sqlOrderLimit = "";
        if(isset($offset) and $offset){
			$sqlOrderLimit .= " limit $limit, $offset";
		}else{
			$sqlOrderLimit .= " limit $limit";
		}	
		
		$DBSelect = $this->load->database('select', TRUE);
		
		$sql = "select A.*
					, wc.wb_room_id
					, wrm.wb_room_name
					, wc.wb_category_name
					,ifnull(if(A.reply_user_id is not null,(select psn_display_name from users_account where user_id=A.reply_user_id),(select psn_display_name from users_account where member_id=A.reply_member_id)),'Guest') replyBy
					,ifnull(if(A.reply_user_id is not null,(select psn_display_image from users_account where user_id=A.reply_user_id),(select psn_display_image from users_account where member_id=A.reply_member_id)),'http://static.trueplookpanya.com/tppy/member_default.jpg') replyByImage
					,ifnull(if(A.post_user_id is not null,(select psn_display_name from users_account where user_id=A.post_user_id),(select psn_display_name from users_account where member_id=A.post_member_id)),'Guest') postBy
					,ifnull(if(A.post_user_id is not null,(select psn_display_image from users_account where user_id=A.post_user_id),(select psn_display_image from users_account where member_id=A.post_member_id)),'http://static.trueplookpanya.com/tppy/member_default.jpg') postByImage
					from (
						select 
						wr.wb_reply_id,wr.reply_detail,wr.flag_delete as reply_flag_delete,wr.reply_by_name,wr.reply_by_email,wr.reply_status,wr.reply_datetime
						,wr.user_id as reply_user_id , wr.member_id as reply_member_id
						, wp.*
						,wp.user_id as post_user_id , wp.member_id as post_member_id
						,concat('') as folder_path, concat('') as psn_picture
						,if(wp.user_id is not null,wp.user_id,wp.member_id) as blog_id
						from webboard_post as wp
						left join webboard_reply wr on wp.wb_post_id=wr.wb_post_id and wr.reply_status=1
						where 1=1 ".$criteria.$sqlOrderLimit."
					) A
					left join webboard_category wc on A.wb_category_id = wc.wb_category_id
					left join webboard_room wrm on wc.wb_room_id = wrm.wb_room_id";	
		
	
				
				

        // $DBSelect->select("wr.*,urply.psn_display_name as replyBy, urply.psn_display_image as replyByImage, wp.*, u.user_username as member_usrname, u.psn_display_name as postBy, u.psn_display_image as postByImage ,concat('') as folder_path, concat('') as psn_picture, u.user_id as blog_id, wc.wb_room_id , wrm.wb_room_name, wc.wb_category_name");
		// $DBSelect->select("(SELECT IFNULL(mul_view_value,0) as num_view FROM `mul_sum_view` WHERE mul_content_id =wp.wb_post_id AND mul_view_table = 15) as viewcount", FALSE);
        // $DBSelect->from('webboard_post as wp');
		// $DBSelect->join('webboard_reply as wr','wp.wb_post_id = wr.wb_post_id and wr.reply_status=1','left');
		// $DBSelect->join('webboard_category as wc','wp.wb_category_id = wc.wb_category_id','left');
		// $DBSelect->join('webboard_room as wrm','wc.wb_room_id = wrm.wb_room_id');
		// $DBSelect->join('users_account as u','wp.member_id = u.member_id','left');
		// $DBSelect->join('users_account as urply','wr.member_id = urply.member_id','left');
		// // $DBSelect->join('blog as b','wp.member_id = b.member_id','left');
		// // $DBSelect->join('personnel as psn','wp.member_id = psn.member_id','left');
		// // $DBSelect->join('personnel as psnr','wr.member_id = psnr.member_id','left');
		// // $DBSelect->join('member as mem','wr.member_id = mem.member_id','left');
        
		// $arr_where = "wp.wb_status =1";
		// $DBSelect->where($arr_where);
		
		// if(isset($topicid))
			// $DBSelect->where('wp.wb_post_id',$topicid);
		
        // if(isset($order) and $order)
            // $DBSelect->order_by($order); 
        // else
            // $DBSelect->order_by('wr.reply_datetime ASC'); 
        
        // if(isset($offset) and $offset)
            // $DBSelect->limit($limit, $offset);
        // else
            // $DBSelect->limit($limit);
        
        // $query = $DBSelect->get();
		// //var_export($DBSelect->last_query()); die;
		// //_pr($DBSelect->queries);
		
		
		//echo $sql;
		
        /* IGNORE CACHE : ไม่งั้นตอน comment มันจะไม่ขึ้นทันที 
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$query = $this->db->query($sql);
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult,60);
		}
		*/
		$query = $this->db->query($sql);
		if($query){
			$arrResult = $query->result_array();
		}else{
			$arrResult = false;
		}
		
		return $arrResult;
	}
	public function getTopicDetails($topicid , $order_reply = null, $limit_reply = 1, $offset_reply = null) {
        if(empty($topicid))
            return false;
		
		$order = $order_reply;
		$limit = $limit_reply;
		$offset = $offset_reply;
		
		$criteria = "";
		$criteria .= " and wp.wb_status=1";
		
		if(isset($topicid)){
			$criteria .= " and wp.wb_post_id in ($topicid)";
		}
		
		if(isset($order) and $order){
			$sql .= " order by $order";
		} else{
			//$sql .= " order by wp.wb_category_id asc, wp.flag_pin desc, wp.wb_post_id desc";
			$sql .= " order by wp.wb_post_id desc";
		}
        
        $sqlOrderLimit = "";
        if(isset($offset) and $offset){
			$sqlOrderLimit .= " limit $limit, $offset";
		}else{
			$sqlOrderLimit .= " limit $limit";
		}	
		
		$DBSelect = $this->load->database('select', TRUE);
		
		$sql = "select A.*
					, wc.wb_room_id
					, wrm.wb_room_name
					, wc.wb_category_name
					,ifnull(if(A.reply_user_id is not null,(select psn_display_name from users_account where user_id=A.reply_user_id),(select psn_display_name from users_account where member_id=A.reply_member_id)),'Guest') replyBy
					,ifnull(if(A.reply_user_id is not null,(select psn_display_image from users_account where user_id=A.reply_user_id),(select psn_display_image from users_account where member_id=A.reply_member_id)),'http://static.trueplookpanya.com/tppy/member_default.jpg') replyByImage
					,ifnull(if(A.post_user_id is not null,(select psn_display_name from users_account where user_id=A.post_user_id),(select psn_display_name from users_account where member_id=A.post_member_id)),'Guest') postBy
					,ifnull(if(A.post_user_id is not null,(select psn_display_image from users_account where user_id=A.post_user_id),(select psn_display_image from users_account where member_id=A.post_member_id)),'http://static.trueplookpanya.com/tppy/member_default.jpg') postByImage
					from (
						select 
						wr.wb_reply_id,wr.reply_detail,wr.flag_delete as reply_flag_delete,wr.reply_by_name,wr.reply_by_email,wr.reply_status,wr.reply_datetime
						,wr.user_id as reply_user_id , wr.member_id as reply_member_id
						, wp.*
						,wp.user_id as post_user_id , wp.member_id as post_member_id
						,concat('') as folder_path, concat('') as psn_picture
						,if(wp.user_id is not null,wp.user_id,wp.member_id) as blog_id
						from webboard_post as wp
						left join webboard_reply wr on wp.wb_post_id=wr.wb_post_id and wr.reply_status=1
						where 1=1 ".$criteria.$sqlOrderLimit."
					) A
					left join webboard_category wc on A.wb_category_id = wc.wb_category_id
					left join webboard_room wrm on wc.wb_room_id = wrm.wb_room_id";	
		
	
				
				

        // $DBSelect->select("wr.*,urply.psn_display_name as replyBy, urply.psn_display_image as replyByImage, wp.*, u.user_username as member_usrname, u.psn_display_name as postBy, u.psn_display_image as postByImage ,concat('') as folder_path, concat('') as psn_picture, u.user_id as blog_id, wc.wb_room_id , wrm.wb_room_name, wc.wb_category_name");
		// $DBSelect->select("(SELECT IFNULL(mul_view_value,0) as num_view FROM `mul_sum_view` WHERE mul_content_id =wp.wb_post_id AND mul_view_table = 15) as viewcount", FALSE);
        // $DBSelect->from('webboard_post as wp');
		// $DBSelect->join('webboard_reply as wr','wp.wb_post_id = wr.wb_post_id and wr.reply_status=1','left');
		// $DBSelect->join('webboard_category as wc','wp.wb_category_id = wc.wb_category_id','left');
		// $DBSelect->join('webboard_room as wrm','wc.wb_room_id = wrm.wb_room_id');
		// $DBSelect->join('users_account as u','wp.member_id = u.member_id','left');
		// $DBSelect->join('users_account as urply','wr.member_id = urply.member_id','left');
		// // $DBSelect->join('blog as b','wp.member_id = b.member_id','left');
		// // $DBSelect->join('personnel as psn','wp.member_id = psn.member_id','left');
		// // $DBSelect->join('personnel as psnr','wr.member_id = psnr.member_id','left');
		// // $DBSelect->join('member as mem','wr.member_id = mem.member_id','left');
        
		// $arr_where = "wp.wb_status =1";
		// $DBSelect->where($arr_where);
		
		// if(isset($topicid))
			// $DBSelect->where('wp.wb_post_id',$topicid);
		
        // if(isset($order) and $order)
            // $DBSelect->order_by($order); 
        // else
            // $DBSelect->order_by('wr.reply_datetime ASC'); 
        
        // if(isset($offset) and $offset)
            // $DBSelect->limit($limit, $offset);
        // else
            // $DBSelect->limit($limit);
        
        // $query = $DBSelect->get();
		// //var_export($DBSelect->last_query()); die;
		// //_pr($DBSelect->queries);
		
		
		//echo $sql;
		
        /* IGNORE CACHE : ไม่งั้นตอน comment มันจะไม่ขึ้นทันที 
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$query = $this->db->query($sql);
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult,60);
		}
		*/
		$query = $this->db->query($sql);
		if($query){
			$arrResult = $query->result_array();
		}else{
			$arrResult = false;
		}
		
		return $arrResult;
	}
}	