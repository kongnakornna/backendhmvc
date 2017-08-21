<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class cmsblogmodel extends CI_Model {
    function __construct() {
      // parent::__construct();
      $this->load->library('tppymemcached');
      $this->load->library('tppy_utils');
      
      $this->db = $this->load->database("select", TRUE);
      
    }
    private $db;
    function content_detail($cms_id = 0) {
    
      $key="x_cms_$cms_id";
      $content=$this->tppymemcached->delete($key);

      if(!$content=$this->tppymemcached->get($key)){
        $content = $this->db->query("SELECT * FROM cmsblog_detail c LEFT JOIN users_account u ON c.create_user_id=u.user_id WHERE idx=$cms_id")->row_array();
        if($content){
          $content['file_lists'] =$this->db->query("SELECT * FROM cmsblog_file WHERE cmsblog_idx=$cms_id")->result_array();
        }else{
          return null;
        }
        $this->tppymemcached->set($key, $content, 60*30);
      }
      $content['view_count']=$this->tppy_utils->ViewNumberSet($cms_id, 'cmsblog_detail');
      return $content;
    }
    
    
    function cmsblog_message_get($cmsblog_id=0, $message_id=0, $like_count='like_count', $limit=15, $offset=0){
      $sql = "SELECT *, ( SELECT
      COUNT(*) FROM cmsblog_message AA 
      WHERE 1=1
      AND A.cmsblog_id=AA.cmsblog_id
      AND AA.record_status=A.record_status 
      AND A.message_id =AA.parent_message_id
      ) reply_count FROM cmsblog_message  A
      INNER JOIN users_account u ON A.user_id=u.user_id
      WHERE 1=1
      AND cmsblog_id=%cmsblog_id%
      AND message_id=COALESCE(NULLIF(%message_id%, 0),message_id)
      AND record_status=1 
      AND parent_message_id IS NULL
    ORDER BY $like_count DESC
    LIMIT $offset, $limit";
    
    $content = $this->db->query($sql, array(
    '%cmsblog_id%'=>$cmsblog_id,
    '%message_id%'=>$message_id
    ))->row_array();
    }
    function content_message_add($cmsblog_id, $message_detail, $user_id, $create_date, $parent_message_id){
    $insert_array= array(
    'cmsblog_id'=>$cmsblog_id, 
    'message_detail'=>$message_detail, 
    'user_id'=>$user_id, 
    'create_date'=>$create_date, 
    'parent_message_id'=>$parent_message_id
    );
    
    $this->db->insert("cmsblog_message", $insert_array);
    return $this->db->last_id();
    
    }
    function cmsblog_get_list($category_id=0, $limit=19, $offset=0, $order_by='create_date DESC'){
    
    $sql = "SELECT * FROM cmsblog_category_mapping mm
    INNER JOIN cmsblog_detail cd ON cd.idx=mm.content_id AND mm.content_type=2
    INNER JOIN cmsblog_category mc ON mc.category_id=mm.category_id
    WHERE mm.category_id IN ($category_id)
    ORDER BY $order_by LIMIT $offset, $limit";
    $query=$this->db->query($sql);
    return $query->result_array();
    }  
    function cmsblog_get_editorpicks_list($category_id=0, $limit=19, $offset=0, $order_by='create_date DESC'){
    $sql = "SELECT * FROM cmsblog_category_mapping mm
    INNER JOIN cmsblog_detail cd ON cd.idx=mm.content_id AND mm.content_type=2
    INNER JOIN cmsblog_category mc ON mc.category_id=mm.category_id
    WHERE mm.category_id IN ($category_id". ($child ? ','.$child : '').")
    AND editor_picks=1
    ORDER BY $order_by LIMIT $offset, $limit";
    $query=$this->db->query($sql);
    
    return $query->result_array();
    }
    
    function find_children_recursive($category_id=0, $zone_id=1) {
    $sql ="SELECT GROUP_CONCAT(lv SEPARATOR ',') children FROM (
    SELECT @pv:=(SELECT GROUP_CONCAT(category_id SEPARATOR ',') FROM cmsblog_category WHERE zone_id=%zone_id% AND category_parent_id IN (@pv) AND status=1) AS lv FROM cmsblog_category
    JOIN (SELECT @pv:=%category_id%) tmp WHERE category_parent_id IN (@pv)) a";
    
    $query=$this->db->query($sql, array('%category_id%'=>$category_id, '%zone_id%'=>$zone_id));
    $result=$query->row()->children;
    return $result;
    }
    function find_children($category_id=0, $zone_id=1){
    $sql="SELECT * FROM cmsblog_category WHERE category_parent_id=%category_id% AND status=1 AND zone_id=%zone_id%";
    $query=$this->db->query($sql, array('%category_id%'=>$category_id, '%zone_id%'=>$zone_id));
    $result=$query->result_array();
    return $result;
    } 
    function category_detail($category_id=0, $zone_id=1){
      $sql="SELECT *
      FROM cmsblog_category mc
      INNER JOIN (
      SELECT %category_id% category_id, COUNT(DISTINCT(idx)) count_content, COUNT(DISTINCT(create_user_id)) count_blogger FROM
      cmsblog_category_mapping mm INNER JOIN cmsblog_detail cd  ON mm.content_id=cd.idx
      WHERE cd.record_status=1 
      AND mm.category_id IN ($category_id) 
      ) AA ON AA.category_id=mc.category_id
      WHERE mc.category_id=COALESCE(NULLIF(%category_id%, 0), mc.category_id) AND mc.status=1 AND zone_id=%zone_id%";
      $query=$this->db->query($sql, array('%category_id%'=>$category_id, '%zone_id%'=>$zone_id));
      
      foreach($result = $query->result_array() as $k=>$v){
        $result[$k]['top_blogger']=$this->cmsblog_get_top_blogger_of_week($v['child_category_id_list']);
      }
      
      if(count($result) == 1){
        $result = reset($result);
      }
      return $result;
    }
    
    function menu_detail_by_category_name_code($category_name_code=0, $zone_id=1){
      $query=$this->db->query("SELECT * FROM cmsblog_category WHERE category_name_code=%category_name_code% AND status=1 AND zone_id=%zone_id%", array('%category_name_code%'=>urldecode($category_name_code), '%zone_id%'=>$zone_id));
      
      // exit($this->db->last_query());
      
      return $query->row();
    }
    
    function cmsblog_get_top_blogger_of_week($category_id=0) {
    $timestamp = (time() - 60*60*24*7);
    $time_sql = $timestamp > 0 ? "AND create_date > $timestamp" : "";
    
    $sql="SELECT cd.create_user_id, COUNT(DISTINCT(cd.idx)) count_content, u.psn_display_name,u.psn_display_image
    FROM cmsblog_category_mapping mm
    INNER JOIN cmsblog_detail cd ON cd.idx=mm.content_id
    INNER JOIN users_account u ON u.user_id=cd.create_user_id
    WHERE content_type=2 
    AND mm.category_id IN ($category_id)
    $time_sql
    GROUP BY cd.create_user_id
    ORDER BY count_content DESC
    LIMIT 1";
    $query=$this->db->query($sql);
    if($category_id>0){
    $result=$query->row_array();
    }
    return $result ? $result : null ;
    
    }
  }
    
?>    