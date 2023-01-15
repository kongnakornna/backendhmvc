<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class TPPY_Utils {
public function __construct(){
$CI =& get_instance();
$CI->load->helper('url');
$CI->load->library('session');
$CI->load->library('Memcached_library');
$CI->load->database();
}
public function ViewNumberGet($content_id,$table,$cachetime,$deletekey){
if($content_id==''){$content_id=0;}
if($table==''){$table='cvs_course_examination';}
if($cachetime==''){$cachetime=300;}
if($deletekey==''){$deletekey='';}
###############################################  
if($table=='mul_content'){ 
$field_name='mul_content_id'; $view_count_field_name='view_count';
}elseif($table=='mul_source'){ 
 $field_name='mul_source'; $view_count_field_name='view_count';
}elseif($table=='cms'){ 
$field_name='cms_id'; $view_count_field_name='view_count';
}elseif($table=='cmsblog_detail'){ 
$field_name='idx'; $view_count_field_name='view_count';
}elseif($table=='tv_program'){ 
$field_name='content_id';$view_count_field_name='view_count';
}elseif($table=='tv_program_episode'){ 
$field_name='content_child_id'; $view_count_field_name='view_count';
}elseif($table=='ams_news_directapply'){ 
$field_name='news_id';  $view_count_field_name='news_view';
}elseif($table=='webboard_post'){ 
$field_name='wb_post_id';  $view_count_field_name='view_count';  $last_update_date ='last_update_date';
}elseif($table=='ams_news_camp'){ 
$field_name='camp_id';  $view_count_field_name='viewcount'; 
}elseif($table=='lesson_plan'){ 
$field_name='lesson_plan_id';  $view_count_field_name='view_count';  
}elseif($table=='cvs_game_quiz_main'){ 
$field_name='id';  $view_count_field_name='view'; 
}elseif($table=='cvs_course_examination'){ 
$field_name='id';  $view_count_field_name='view_count'; 
}
##############################################
$value=$content_id;
$cachekey="vc_".$table."+".$field_name."+".$content_id;
$key=$cachekey;
$increment_by=1;
###################################
if($deletekey==1){
     $this->memcached_library->delete($cachekey);
}
###################################
$cacheget=$this->memcached_library->get($cachekey);
$sql="select * from $table  where  $field_name=$content_id limit 1";
if(!$cacheget){
###########DB SQL query Start###########
$querys=$this->CI->db->query($sql);
$resultsdatas=$querys->result();
$resultsdatas=$resultsdatas['0'];
$viewold=$resultsdatas->view_count;
 $this->memcached_library->add($cachekey,$viewold,$cachetime);
//$this->memcached_library->set($cachekey,$viewold,$cachetime);
###########DB SQL query Start###########
 $fromstaus='form data base mysql';
 $view_cache='';
}else{
 $viewold=$cacheget;
 $fromstaus='form memcache';
 $view_cache=$viewold;
}
################################
if($viewold!==false){  
$use_session=true;
if($use_session){ 
$CI->load->library('session');
$sessionkey=$CI->session->userdata($cachekey);
if($sessionkey)
     $CI->session->set_userdata($cachekey,'1');      
if($sessionkey!= null){
     $content_view_count=$this->memcached_library->increment($cachekey,$increment_by);
}else{
     $content_view_count=$this->memcached_library->increment($key,$increment_by);
}
if($deletekey==1){$CI->session->unset_userdata($cachekey);}
if($content_view_count==''){ $content_view_count=0;}
$use_session=true;
if(($content_view_count<10 || $content_view_count %29==0 || $use_session)){
################################
###########DB SQL query Start###########
/*
$sql="select * from $table where $field_name=$content_id and view_count < $content_view_count";
$query=$this->CI->db->query($sql);
$resultsdata=$query->result();
$resultsdatas=$resultsdata['0'];
$viewdb=$resultsdatas->view_count;
#$contentviewcount=$viewdb+1;
*/
// ถ้าใน DB มากกว่า memcache มันจะไม่ + เพิ่ม
// echo '<pre>  resultsdatas=>'; print_r($resultsdatas); echo '</pre>'; 
$contentviewcount=$content_view_count;
###########DB SQL query Start###########
// ถ้าใน DB มากกว่า memcache มันจะไม่ + เพิ่ม
$sql2="select * from $table  where  $field_name=$content_id and view_count < $content_view_count";
#echo 'sql=>'.$sql; die();
$querys=$this->CI->db->query($sql2);
$resultsdatas=$querys->result();
$resultsdatas=$resultsdatas['0'];
$viewold=$resultsdatas->view_count;
if($resultsdatas!=''){
$data=array('view_count'=>$contentviewcount);
$this->CI->db->where($field_name,$value);
$this->CI->db->update($table,$data);
}
################################
}
$view_count=$contentviewcount;
}
##############################################
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
$data=array('view_count'=>$view_count,
               'view_cache'=>$view_cache,
               'view_key'=>$cachekey,
               'cacheget'=>$cacheget,
               'from'=>$fromstaus,
               'table'=>$table,
               'field_name'=>$view_count_field_name,
               'field_valule'=>$content_id,
               'cachetime'=>$cachetime,
               'sessionkey'=>$sessionkey,
               'sql'=>$sql,
               'sql2'=>$sql2,
               'content_view_count'=>$content_view_count,
               'info_cache'=>$cacheinfo);
return $data;
}

}
}