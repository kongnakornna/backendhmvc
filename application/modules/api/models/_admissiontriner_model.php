<?php
class Admissiontriner_model extends CI_Model{
private $DBSelect, $DBEdit;
public function __construct(){
parent::__construct();
		 header('Content-Type: text/html; charset=utf-8');
			$CI = & get_instance();
		  //$this->load->database('default');  
 
    //$ci->load->helper('common');
    //$this->load->helper('common');
    
    
	}
function encryptcode($q) {
    $cryptKey  = 'tyyptruemd5';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded);
}
function decryptcode($q){
    $cryptKey  = 'tyyptruemd5';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}
public function getSelectuniversity($u_id='',$name="u_parent_id",$default){	
  #Debug($u_parent_id); Debug($default); die();
   #$first = "--- please select subuniversity ---";
   $u_parent_id=(int)$default;
   $first = "--- กรุณาเลือก ---";
   if($u_parent_id!==''){
     $rows=$this->get_university_by_parent_id(0);
   }else{
     $rows=$this->get_university_by_parent_id($u_parent_id='0');
   }
		 #Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption((int)$row['u_id'], $row['u_name']);
	    	}
      /*
      echo'<hr><pre> default=>';print_r($default);echo'<pre> <hr>';  
      echo'<hr><pre> name=>';print_r($name);echo'<pre> <hr>';  
      echo'<hr><pre> opt=>';print_r($opt);echo'<pre> <hr>';  
      */
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
}  
public function get_university_by_id($u_id='',$delekey){
if($u_id==''){
$sql="SELECT * FROM ams_university WHERE u_parent_id=0 and record_status=1 order by u_id asc";
}else{
$sql="SELECT * FROM ams_university WHERE u_id=$u_id and u_parent_id=0 and record_status=1 order by u_id asc";
}
$cache_key = "get_university_by_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function getSelectuniversity_parent($u_id='',$name="u_id",$default){	
  #Debug($u_parent_id); Debug($default); die();
  //echo'<hr><pre>  u_id=>';print_r($u_id);echo'<pre> <hr>'; 
   #$first = "--- please select subuniversity ---";
   $u_parent_id=(int)$u_id;
   $first = "--- กรุณาเลือกคณะ ---";
   if($u_parent_id!==''){
     $rows=$this->get_university_by_parent_id($u_parent_id);
   }else{
     $rows=$this->get_university_by_parent_id($u_parent_id='0');
   }
		 #Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption((int)$row['u_id'], $row['u_name']);
	    	}
      /*
      echo'<hr><pre> default=>';print_r($default);echo'<pre> <hr>';  
      echo'<hr><pre> name=>';print_r($name);echo'<pre> <hr>';  
      echo'<hr><pre> opt=>';print_r($opt);echo'<pre> <hr>';  
      */
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
}  
public function get_university_by_parent_id($u_parent_id='',$delekey){
$delekey;
if($u_parent_id==Null){
$sql="SELECT * FROM ams_university WHERE u_parent_id=0 and record_status=1 order by u_id asc";
}else{
$sql="SELECT * FROM ams_university WHERE u_parent_id=$u_parent_id and record_status=1 order by u_id asc";
}
$cache_key = "get_university_by_parent_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}

return $data; 
}
public function getSelectyear($year_id='',$name="year",$default){	
  #Debug($u_parent_id); Debug($default); die();
   #$first = "--- please select subuniversity ---";
   $year_id=(int)$default;
   $first = "--- กรุณาเลือกปี ---";
   $ydate=date('Y');
   $yf=$ydate+5;
   $yb=$ydate-15;
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=$yb;$i<=$yf;$i++){
	    		$row = $i;
	    		$opt[]	= makeOption((int)$i,$i);
	    	}
      /*
      echo'<hr><pre> default=>';print_r($default);echo'<pre> <hr>';  
      echo'<hr><pre> name=>';print_r($name);echo'<pre> <hr>';  
      echo'<hr><pre> opt=>';print_r($opt);echo'<pre> <hr>';  
      */
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
}  
public function get_university_u_id_and_parent_id($u_parent_id,$delekey){
$delekey;
$sql="SELECT * FROM ams_university WHERE  u_parent_id=$u_parent_id and record_status=1 order by u_id asc";
$cache_key = "get_university_u_id_and_parent_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();
return $data; 
}
public function get_ams_entrance($id,$year,$config='',$delekey){
 if($config==''){
$sql="select ent_id,u_id,year_config,major_code,major_remark,gpax_weight,gpax_min,onet_weight_total,onet_min_total
,01_onet_weight,01_onet_mi,02_onet_weight,02_onet_min,03_onet_weight,03_onet_mint,04_onet_weight,04_onet_min
,05_onet_weight,05_onet_min,85_gat_weight,85_gat_min,85_gat_min_part2,71_pat1_weight,71_pat1_min,72_pat2_weight
,72_pat2_min,73_pat3_weight,73_pat3_min,74_pat4_weight,74_pat4_min,75_pat5_weight,75_pat5_min,76_pat6_weight
,76_pat6_min,77_pat71_weight,77_pat71_min,78_pat72_weight,78_pat72_min,79_pat73_weight,79_pat73_min
,80_pat74_weight,80_pat74_min,81_pat75_weight,81_pat75_min,82_pat76_weight,82_pat76_min
,special_remark,receive_amount,receive_amount_sharecode,lastupdate,config
,case config when '1' then 'เกณฑ์คะนนอย่างเดียว' when '2' then 'เกณฑ์การรับและเกรณ์คะนน' when '' then 'เกณฑ์การรับ' end as criterion
,(select score_min  from ams_entrance_score_history as h where h.year=ams_entrance.year_config and h.ent_id=ams_entrance .ent_id)as score_min
,(select score_max  from ams_entrance_score_history as h where h.year=ams_entrance.year_config and h.ent_id=ams_entrance .ent_id)as score_max
from ams_entrance 
where  u_id=$id and year_config=$year order by ent_id asc";
 }else{
  $sql="select ent_id,u_id,year_config,major_code,major_remark,gpax_weight,gpax_min,onet_weight_total,onet_min_total
,01_onet_weight,01_onet_mi,02_onet_weight,02_onet_min,03_onet_weight,03_onet_mint,04_onet_weight,04_onet_min
,05_onet_weight,05_onet_min,85_gat_weight,85_gat_min,85_gat_min_part2,71_pat1_weight,71_pat1_min,72_pat2_weight
,72_pat2_min,73_pat3_weight,73_pat3_min,74_pat4_weight,74_pat4_min,75_pat5_weight,75_pat5_min,76_pat6_weight
,76_pat6_min,77_pat71_weight,77_pat71_min,78_pat72_weight,78_pat72_min,79_pat73_weight,79_pat73_min
,80_pat74_weight,80_pat74_min,81_pat75_weight,81_pat75_min,82_pat76_weight,82_pat76_min
,special_remark,receive_amount,receive_amount_sharecode,lastupdate,config
,case config when '1' then 'เกณฑ์คะนนอย่างเดียว' when '2' then 'เกณฑ์การรับและเกรณ์คะนน' when '' then 'เกณฑ์การรับ' end as criterion
,(select score_min  from ams_entrance_score_history as h where h.year=ams_entrance.year_config and h.ent_id=ams_entrance .ent_id)as score_min
,(select score_max  from ams_entrance_score_history as h where h.year=ams_entrance.year_config and h.ent_id=ams_entrance .ent_id)as score_max
from ams_entrance 
where  u_id=$id and year_config=$year and config=$config order by ent_id asc";
 }

$cache_key = "get_ams_entrance_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();

return $data; 
}
public function get_ams_entrance_config($id,$year,$config='',$delekey){
 if($config==''){
$sql="select ent_id,u_id,year_config,major_code,major_remark,gpax_weight,gpax_min,onet_weight_total,onet_min_total
,01_onet_weight
,01_onet_mi
,02_onet_weight
,02_onet_min
,03_onet_weight
,03_onet_mint
,04_onet_weight
,04_onet_min
,05_onet_weight
,05_onet_min
,85_gat_weight
,85_gat_min
,85_gat_min_part2
,71_pat1_weight
,71_pat1_min
,72_pat2_weight
,72_pat2_min
,73_pat3_weight
,73_pat3_min
,74_pat4_weight
,74_pat4_min
,75_pat5_weight
,75_pat5_min
,76_pat6_weight
,76_pat6_min
,77_pat71_weight
,77_pat71_min
,78_pat72_weight
,78_pat72_min
,79_pat73_weight
,79_pat73_min
,80_pat74_weight
,80_pat74_min
,81_pat75_weight
,81_pat75_min
,82_pat76_weight
,82_pat76_min
,special_remark,receive_amount,receive_amount_sharecode,lastupdate,score,scoremin
,config
,case config when '1' then 'เกณฑ์คะนนอย่างเดียว' when '2' then 'เกณฑ์การรับและเกรณ์คะนน' when '' then 'เกณฑ์การรับ' end as criterion
 from ams_entrance where u_id=$id and year_config=$year and (config=1 or config=2)order by ent_id asc";
 }else{
  $sql="select ent_id,u_id,year_config,major_code,major_remark,gpax_weight,gpax_min,onet_weight_total,onet_min_total
,01_onet_weight
,01_onet_mi
,02_onet_weight
,02_onet_min
,03_onet_weight
,03_onet_mint
,04_onet_weight
,04_onet_min
,05_onet_weight
,05_onet_min
,85_gat_weight
,85_gat_min
,85_gat_min_part2
,71_pat1_weight
,71_pat1_min
,72_pat2_weight
,72_pat2_min
,73_pat3_weight
,73_pat3_min
,74_pat4_weight
,74_pat4_min
,75_pat5_weight
,75_pat5_min
,76_pat6_weight
,76_pat6_min
,77_pat71_weight
,77_pat71_min
,78_pat72_weight
,78_pat72_min
,79_pat73_weight
,79_pat73_min
,80_pat74_weight
,80_pat74_min
,81_pat75_weight
,81_pat75_min
,82_pat76_weight
,82_pat76_min
,special_remark,receive_amount,receive_amount_sharecode,lastupdate,score,scoremin
,config 
,case config when '1' then 'เกณฑ์คะนนอย่างเดียว' when '2' then 'เกณฑ์การรับและเกรณ์คะนน' when '' then 'เกณฑ์การรับ' end as criterion
 from ams_entrance where u_id=$id and year_config=$year and config=$config and (config=1 or config=2) order by ent_id asc";
 }

$cache_key = "get_ams_entrance_config_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################


$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();

return $data; 
}
public function datawherearrays($table,$datawhere,$delekey){       
$sql = "select * from $table where $datawhere ";
#echo '<pre> $sql=>';echo $sql;echo '</pre>';        
$cache_key = "datawherearrays_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################


$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
   $query=$this->db->query($sql);
   $data = $query->result_array();
   $num_rows=$query->num_rows();
   $this->tppymemcached->set($cache_key_encrypt,$rs,3600);
}

            $data=array();
            foreach ($rs as $key1 => $v1) {
             $u_id=$v1['u_id'];
             $table='ams_university';
             $datawhere='u_parent_id='.$u_id;
             $sql="select u_id from $table where $datawhere ";
		           $query=$this->db->query($sql);
		           $rs2=$query->result_array();
		           $num_rows2=$query->num_rows();
	            $data[$key1]['u_id']=$v1['u_id'];
	            $data[$key1]['u_parent_id']=$v1['u_parent_id'];
	            $data[$key1]['u_name']=$v1['u_name'];
	            $data[$key1]['thumbnail']=$v1['thumbnail'];
	            $data[$key1]['short_description']=$v1['short_description'];
	            $data[$key1]['detail']=$v1['detail']; 
	            $data[$key1]['u_group_id']=$v1['u_group_id'];
	            $data[$key1]['record_status']=$v1['record_status'];
	            $data[$key1]['add_timestamp']=$v1['add_timestamp'];
	            $data[$key1]['update_timestamp']=$v1['update_timestamp'];
	            $data[$key1]['list']=$rs2;
	            $data[$key1]['num_rows']=$num_rows2;
			  }
            $data1=array('sql' => $sql,'num_rows' => $num_rows,'data' => $data,);
            
            
            if($data1){  
            	return $data1;
            }else{
            	$data=null;
            	return $data;
            }
        }
public function get_faculty_name_all_university($faculty_name,$delekey){
$sql="select u.*
,(select u_name  from  ams_university  where u_name!='' and u_id=u.u_parent_id and  record_status=1)as faculty_name
from ams_university  u where record_status=1 and u.u_name='$faculty_name' ";
$cache_key="get_faculty_name_all_university_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_branch_name_all_faculty_name($faculty_name,$delekey){
$sql="select (select u_name  from  ams_university  where u_name!='' and u_id=u.u_parent_id and  record_status=1)as faculty_name
,u.u_name as branch_name,u.u_id  as id,u.u_parent_id,u.thumbnail
from ams_university  u where record_status=1 and u.u_name='$faculty_name'";
$cache_key="get_get_branch_name_all_faculty_name_'.$sql.";

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_universitybyid($u_id,$delekey){
$sql="select (select u_name  from  ams_university  where u_name!='' and u_id=u.u_parent_id and  record_status=1)as faculty_name
,u.u_name as branch_name,u.u_id  as id,u.u_parent_id
,concat('http://static.trueplookpanya.com/trueplookpanya/',u.thumbnail) AS thumbnail
from ams_university  u where record_status=1 and u.u_id=$u_id";
$cache_key="get_universitybyid_'.$sql.";


 
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_group_m2m($u_group_id_source,$delekey){
$sql="select m2m.u_group_id_source as group_id,m2m.u_group_id_destination as fac_id
,(select u_name  from  ams_university  where u_id=fac_id and  record_status=1)as faculty_name
,(select u_id  from  ams_university  where u_id=fac_id and  record_status=1)as u_id
,(select u_parent_id  from  ams_university  where u_id=fac_id and  record_status=1)as u_parent_id
from ams_university_group_m2m as m2m where m2m.u_group_id_source=$u_group_id_source";
$cache_key="get_ams_university_group_m2m_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_u_parent_id($u_parent_id,$delekey){
$sql="select 
(select u_id from  ams_university  where u_id=p.u_parent_id)as university_id
,(select u_name from  ams_university  where u_id=p.u_parent_id)as university_name
,p.* from  ams_university as p where p.u_id=$u_parent_id";
$cache_key="get_ams_university_u_parent_id_'.$sql.";


 
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_u_id($u_id,$delekey){
$sql="select *  from  ams_university  where u_id=$u_id ";
$cache_key="get_ams_university_u_id_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_entrance_score_history_ent_id($ent_id,$delekey){
$sql="select  * from  ams_entrance_score_history  where ent_id=$ent_id order by year asc ";
$cache_key="get_ams_entrance_score_history_ent_id_'.$sql.";

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
// list box 
public function get_ams_university_group3($delekey){
$sql="select * from ams_university_group where u_group_type=3";
$cache_key="get_ams_university_group3_'.$sql.";

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
// list box 2 Var1
public function get_ams_university_group_m2m_typy3($u_group_id,$delekey){
$sql="select * from ams_university_group_m2m where u_group_id_source=$u_group_id";
$cache_key="get_ams_university_group_m2m_typy3_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
$data=array('data'=>$data,
										  'sql'=>$sql,);
return $data; 
}
// list box 2 Var2
public function get_ams_university_group_map_list_group_in($group_in,$delekey){
$sql="select *
,(select u_name from  ams_university where u_id=map.u_id)as fac_name
,(select u_parent_id from  ams_university where u_id=map.u_id)as u_parent_id
from ams_university_group_map as map where map.u_group_id in ($group_in) order by idx asc";
$cache_key="get_ams_university_group_map_list_group_in_".$sql;



##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
return $data; 
}
// list box 2 listdata form u_id
public function get_ams_entrance_list($u_id,$delekey){
$sql="select ams.*
,(select (select uu.u_id from  ams_university as uu where uu.u_id=u1.u_parent_id)as university_name from  ams_university as u1 where u1.u_id=u.u_parent_id)as university_id
,(select (select uu.u_name from  ams_university as uu where uu.u_id=u1.u_parent_id)as university_name from  ams_university as u1 where u1.u_id=u.u_parent_id)as university_name
,(select u_name from  ams_university where u_id=u.u_parent_id)as faculty_name
,(select u_name from  ams_university where u_id=ams.u_id)as branch_name
,u.u_parent_id,h.score_min,h.score_max
from  ams_entrance as ams 
left join  ams_university as u on u.u_id=ams.u_id
left join  ams_entrance_score_history as h on h.ent_id=ams.ent_id and   h.year=ams.year_config
where ams.u_id=$u_id";
$cache_key="get_ams_entrance_list_".$sql;

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_university_group_map_typy3($u_group_id_destination,$delekey){
$sql="select * from ams_university_group_map where u_group_id=$u_group_id_destination";
$cache_key="get_university_group_map_typy3_'.$sql.";

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
################# group3
public function get_ams_university_typy3($u_id,$delekey){
$sql="select * from ams_university where u_id=$u_id";
$cache_key="get_ams_university_typy3_'.$sql.";



##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
################# group4
public function get_ams_university_group4($delekey){
$sql="select * from ams_university_group where u_group_type=4";
$cache_key="get_ams_university_group4_'.$sql.";



##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_group_id($u_group_id,$delekey){
$sql="select g.* from ams_university_group as g where g.u_group_id=$u_group_id";
$cache_key="get_ams_university_group_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_group_type4_u_group_id_list($u_group_id,$delekey){
$sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))) AS thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																	
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  g.u_group_id=$u_group_id
order by amsu.u_id asc,m.idx asc";
$cache_key="get_group_type4_u_group_id_list_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
return $data; 
}
################
public function get_ams_faculty_list($u_parent_id,$delekey){
$sql="select un.u_id as faculty_id,un.u_name as faculty_name
from  ams_university as un 
left join  ams_university as u on u.u_id=un.u_id
where un.u_parent_id=$u_parent_id";
$cache_key="get_ams_faculty_list_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_university_faculty_branch_list($branch_id,$delekey){
$sql="select (select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
from  ams_university as un where un.u_id=$branch_id";
$cache_key="get_university_faculty_branch_list_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
  $data=$this->db->query($sql)->result_array();
  $this->tppymemcached->set($cache_key_encrypt,$data,3600);
  }
return $data; 
}
public function get_ams_entrance_score_history_where($ent_id,$year='',$delekey){
 if($year==''){
  $sql="SELECT * FROM ams_entrance_score_history WHERE  ent_id=$ent_id order by year asc,idx desc";
 }else{
  $sql="SELECT * FROM ams_entrance_score_history WHERE  ent_id=$ent_id and year=$year order by year asc,idx desc";
 }
$cache_key = "get_ams_entrance_score_history_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();
return $data; 
}
public function get_ams_entrance_group_by_branch($year='',$delekey){
 if($year==''){
  $sql="select DISTINCT  ent.ent_id,un.u_id
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,ent.major_remark
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!=''  group by branch_id order by un.u_id asc";
 }else{
  $sql="select DISTINCT ent.ent_id,un.u_id
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,ent.major_remark
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!=''  and ent.year_config=$year group by branch_id order by un.u_id asc";
 }
$cache_key = "get_ams_entrance_group_by_branch_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();
return $data; 
}
public function get_ams_entrance_group_by_university($year='',$delekey){
 if($year==''){
  $sql="select DISTINCT  ent.ent_id,un.u_id
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,ent.major_remark
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!=''  group by university_id order by un.u_id asc";
 }else{
  $sql="select DISTINCT ent.ent_id,un.u_id
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,ent.major_remark
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!=''  and ent.year_config=$year group by university_id order by un.u_id asc";
 }
$cache_key = "get_ams_entrance_group_by_university_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();
return $data; 
}
public function get_ams_university_u_parent_ido($delekey){
$sql="select  u_id,u_name from ams_university  where  u_parent_id=0 and u_name!='' order by u_id asc";
$cache_key="get_ams_university_u_parent_ido_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_group_type3_id_list($group_id,$delekey){
$sql="select * from ams_university_group where u_group_type=3 and u_group_id=$group_id";
$cache_key="get_group_type3_id_list_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
return $data; 
}
public function get_ams_university_by_u_id($u_id,$delekey){
$sql="select u_id,u_group_id,u_name,short_description
,(select count(u_id) from  ams_university where u_parent_id=u.u_id)as faculty_count
,concat('http://static.trueplookpanya.com/trueplookpanya/',if(thumbnail is not null,concat('',thumbnail),'')) AS thumbnail_url
from  ams_university as u  where u.u_id=$u_id";
$cache_key="get_ams_university_by_u_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_by_u_parent_id($u_parent_id,$delekey){
$sql="select u_id,u_group_id,u_name,short_description
,concat('http://static.trueplookpanya.com/trueplookpanya/',if(thumbnail is not null,concat('',thumbnail),'')) AS thumbnail_url
from  ams_university  where u_parent_id=$u_parent_id";
$cache_key="get_ams_university_by_u_parent_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_list_all($delekey){
$sql="select u.u_id,u.u_group_id,u.u_name
,concat(if(ifnull(g.u_group_name,'')!='',concat(g.u_group_name),'')) as group_name
,(select count(u_id) from  ams_university where u_parent_id=u.u_id)as faculty_count
,concat('http://static.trueplookpanya.com/trueplookpanya/',if(u.thumbnail is not null,concat( '',u.thumbnail),'')) AS thumbnail_url
from  ams_university as u 
left join  ams_university_group as g on g.u_group_id=u.u_group_id
where u.u_parent_id=0 order by u_id asc";
$cache_key="get_ams_university_list_all_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_dataset($dataset,$delekey){
$sql="select  u_id as branch_id,u_name as branch_name from  ams_university  where  u_parent_id in($dataset)order by u_id asc";
$cache_key="get_ams_university_dataset_2018_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
$dataall=$data;
/*
$branch_data=array();
 foreach($data as $key=> $value) {
                      $branch['branch_id']=$value['branch_id'];
                      $branch_data[] = $branch;
}
$num=0;
 foreach($branch_data as $k=>$v){
                   $id=$v['branch_id'];
                   $num++;
}
$branch_set=implode(',', array_map(function($v){ return $v['branch_id'];}, $branch_data ));
#########################
$dataall = array('sql'=$sql,'branch_set'=$branch_set,'data'=>$data);
echo '<hr><pre>  dataall=>'; print_r($dataall); echo '</pre>';die();
*/
//echo '<hr><pre>  dataall=>'; print_r($dataall); echo '</pre>';die();
return $dataall; 
}
public function get_ams_university_u_parent_id_to_u_id_dataset($dataset,$delekey){
$sql="select  uu.u_parent_id as uid
,(select u_name from  ams_university where u_id=uu.u_parent_id)as uname
from  ams_university as uu where  uu.u_id in($dataset)group by uu.u_parent_id order by uu.u_id asc";
$cache_key="get_ams_university_u_parent_id_to_u_id_dataset_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_u_id_to_u_parent_id_dataset($dataset,$delekey){
$sql="select  uu.u_id as uid
,(select u_name from  ams_university where u_id=uu.u_id)as uname
from  ams_university as uu where  uu.u_parent_id in($dataset)group by uu.u_id order by uu.u_id asc";
$cache_key="get_ams_university_u_parent_id_dataset_".$sql;
//echo '<pre>  $sql=>'; print_r($sql); echo '</pre>';die();
########################## 
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_entrance_to_u_parent_id_dataset($dataset,$delekey){
$sql="select ent.*
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select score_min from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_min
,(select score_max from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_max
from  ams_entrance as ent
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=2560 and  ent.u_id in($dataset)order by ent.ent_id asc";
$cache_key="get_ams_entrance_to_u_parent_id_datase_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_u_id_dataset($dataset,$delekey){
$sql="select  uu.u_id as uid,uu.u_name as uname
from  ams_university as uu where  uu.u_id in($dataset)group by uu.u_id order by uu.u_id asc";
$cache_key="get_ams_university_u_id_dataset_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_ams_entrance_dataset($dataset,$year,$delekey){
$sql="select 
(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_id
,(select u_parent_id from  ams_university where u_id=ent.u_id)as faculty_id
,(select u_id from  ams_university where u_id=ent.u_id)as branch_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id))as faculty_name
,(select u_name from  ams_university where u_id=ent.u_id)as branch_name
,ent.*,h.score_min,h.score_max
from  ams_entrance as ent
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.u_id in ($dataset)and ent.year_config=$year order by ent.ent_id asc";
$cache_key="get_ams_university_ams_entrance_dataset_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
$dataall=$data;
#echo '<hr><pre>  dataall=>'; print_r($dataall); echo '</pre>';die();
return $dataall; 
}
//group_map_form_ams_entrance_branch_id
public function get_university_group_map_form_ams_entrance_branch_id_dataset($dataset,$delekey){
$sql="select m2m.u_group_id_source as group_id 
,(select u_group_name from  ams_university_group where  u_group_id=m2m.u_group_id_source)as group_name
from  ams_university_group_map as map 
left join  ams_university_group_m2m as m2m on m2m.u_group_id_destination=map.u_id
where m2m.u_group_id_source!='' and map.u_id in($dataset)
group by m2m.u_group_id_source order by m2m.u_group_id_source,map.idx asc";
$cache_key="get_university_group_map_form_ams_entrance_branch_id_dataset_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
//แยก ams รายมหาวิทยาลัย รายสาขา
public function get_ams_university_ams_entrance_group_by_u_id_year($year_config,$u_id,$delekey){
$sql="select ent.*
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select score_min from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_min
,(select score_max from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_max
from  ams_entrance as ent
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year_config and  ent.u_id in (select u.u_id from  ams_university as u where u.u_parent_id in (select u.u_id from  ams_university as u where u.u_parent_id=$u_id))
group by un.u_id order by  un.u_id asc ";
$cache_key="get_ams_university_ams_entrance_group_by_u_id_year".$year_config.$u_id;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
 #echo '<hr><pre>  dataall=>'; print_r($dataall); echo '</pre>';die();
return $data; 
}
#group
public function get_ams_university_group_map_group_id($group_id,$delekey){
$sql="select(select u_group_name from ams_university_group where u_group_id=m.u_group_id)as group_name
,(select u_name from ams_university where u_id=m.u_id)as b_name
,m.u_id,m.u_id as branch_id from ams_university_group_map as m where m.u_group_id=$group_id group by u_id order by u_id asc";
$cache_key="get_ams_university_group_map_group_id_".$group_id;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
 #echo '<hr><pre>  dataall=>'; print_r($dataall); echo '</pre>';die();
return $data; 
}
# entrance group u_id  year
public function get_ams_university_ams_entrance_group_by_u_id_year_setid($year_config,$u_id,$setid,$delekey){
$sql="select ent.*
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select score_min from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_min
,(select score_max from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_max
from  ams_entrance as ent
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year_config and  ent.u_id in (select u.u_id from  ams_university as u where u.u_parent_id in 
(select u.u_id from  ams_university as u where u.u_parent_id=$u_id and ent.u_id in($setid)))
group by un.u_id order by  un.u_id asc ";
$cache_key="get_ams_university_ams_entrance_group_by_u_id_year_setid_".$year_config.$u_id.$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}

#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';
#echo '<hr><pre>dataall=>'; print_r($dataall); echo '</pre>';die();

return $data; 
}
public function get_profile_by_username_password($user_username,$user_password){
$user_password=MD5($user_password);
$sql="select u.*, 'upload' as folder_path, b.blog_id 
FROM users_account u 
LEFT OUTER JOIN blog b ON b.member_id = u.member_id 
where u.user_username='$user_username' AND u.user_password='$user_password'
";
//echo 'sql=>'.$sql; 
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data=$this->db->query($sql)->result_array();
return $data;
}
public function get_profile_by_user_id($user_id){
$user_password=MD5($user_password);
$sql="select u.*, 'upload' as folder_path, b.blog_id 
FROM users_account u 
LEFT OUTER JOIN blog b ON b.member_id = u.member_id 
where u.user_id='$user_id'
";
//echo 'sql=>'.$sql; 
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data=$this->db->query($sql)->result_array();
return $data;
}
public function get_setgroup_ams_news_directapply_university($setgroup){
$sql="select (select news_title from  ams_news_directapply where news_id=ud.news_id)as news_title
,ud.major_receivers as receivers
,ud.major_grade as gpax
,ud.major_gatpat AS gatpat
,ud.major_gnet AS req_gnet
,ud.major_portfolio AS req_portfolio
,ud.major_interview AS req_interview
,ud.major_matches AS req_matches
,ud.major_property AS req_property
,ud.major_receivers AS receiver_number
,(select u_name from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=ud.u_id)))as university
,(select u_name from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=ud.u_id))as fac_name
,(select u_name from  ams_university where  u_id=ud.u_id)as u_name
,(select u_parent_id from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=ud.u_id))as university_id
,(select u_id from  ams_university where  u_id=ud.u_id)as faculty_id
,(select u_parent_id from  ams_university where  u_id=ud.u_id)as branch_id
from ams_news_directapply_university as ud
where ud.major_receivers!='' and ud.u_id in (select u_id from ams_university_group_map 
where u_group_id in ($setgroup)) group by branch_id";

//echo 'sql=>'.$sql; 

$cache_key="get_setgroup_ams_news_directapply_university_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}


return $data;
}
#
public function get_ams_university_ams_entrance_group_by_u_id_year_branch_id($year_config,$u_id,$branch_id,$delekey){
$sql="select ent.*
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select score_min from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_min
,(select score_max from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_max
from  ams_entrance as ent
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year_config and ent.u_id=$branch_id and ent.u_id in (select u.u_id from  ams_university as u where u.u_parent_id in (select u.u_id from  ams_university as u where u.u_parent_id=$u_id))
group by un.u_id order by  un.u_id asc ";
$cache_key="get_ams_university_ams_entrance_group_by_u_id_year_branch_id".$year_config.$u_id.$branch_id.$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
 //echo '$sql=>'.$sql.'<br>';echo '<pre>data=>';print_r($data); echo '</pre>';die();
return $data; 
}


}

/*
//$input="SmackFactory";
//$encrypted=$this->encryptcode($input);
//$decrypted=$this->decryptcode($encrypted);
//echo $encrypted.'<br/>'.$decrypted;
*/